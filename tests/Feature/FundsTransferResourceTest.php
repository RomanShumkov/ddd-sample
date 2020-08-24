<?php

namespace Tests\Feature;

use App\FundsTransfer;
use App\User;
use GuzzleHttp\Psr7\Uri;
use Http\Adapter\Guzzle6\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\TestCase;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;
use WoohooLabs\Yang\JsonApi\Schema\Resource\ResourceObject;
use function GuzzleHttp\Psr7\stream_for;

class FundsTransferResourceTest extends TestCase
{
    private const HTTP_STATUS_OK = 200;
    private const HTTP_STATUS_CREATED = 201;

    private const HTTP_STATUS_BAD_REQUEST = 400;
    private const HTTP_STATUS_UNAUTHORIZED = 401;
    private const HTTP_STATUS_FORBIDDEN = 403;
    private const HTTP_STATUS_CONFLICT = 409;

    private JsonApiClient $client;

    private UriInterface $apiUri;

    private RequestInterface $apiRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new JsonApiClient(GuzzleClient::createWithConfig([]));

        $headers = [
            'Content-Type' => 'application/vnd.api+json',
            'Accept' => 'application/vnd.api+json',
        ];
        // docker compose service names are resolvable as hostnames from docker compose network
        $this->apiUri = new Uri('http://web');
        $this->apiRequest = $apiRequest = new Request("GET", $this->apiUri, $headers);
    }

    public function testFundsTransferLifecycle()
    {
        $originUser = $this->mustCreateUser(factory(User::class)->make(), $originUserPassword = 'pass1');
        $destinationUser = $this->mustCreateUser(factory(User::class)->make(), $destinationUserPassword = 'pass2');

        $transferAmount = 1;
        $transferId = Uuid::uuid4();
        $response = $this->tryCreateFundsTransfer($originUser->id(), $destinationUser->id(), $transferAmount, $transferId);
        // can't transfer as guest
        $this->assertResponseStatus(self::HTTP_STATUS_UNAUTHORIZED, $response->getStatusCode());

        $this->authenticate($originUser->attribute('email'), $originUserPassword);

        $response = $this->tryCreateFundsTransfer($destinationUser->id(), $originUser->id(), $transferAmount, $transferId);
        // can't transfer someone else's funds
        $this->assertResponseStatus(self::HTTP_STATUS_FORBIDDEN, $response->getStatusCode());

        $transfer = $this->mustCreateFundsTransfer($originUser->id(), $destinationUser->id(), $transferAmount, $transferId);
        self::assertArraySubset(
            [
                'state' => FundsTransfer::STATE_TRANSFERRING,
                'isFinalState' => false,
            ],
            $transfer->attributes()
        );

        $this->processQueuedJobs();

        $transfer = $this->mustGetFundsTransfer($transfer->id());

        self::assertArraySubset(
            [
                'state' => FundsTransfer::STATE_COMPLETED,
                'isFinalState' => true,
            ],
            $transfer->attributes()
        );

        $response = $this->tryCreateFundsTransfer($originUser->id(), $destinationUser->id(), $transferAmount, $transferId);
        // Transfer ID should be unique
        $this->assertResponseStatus(self::HTTP_STATUS_CONFLICT, $response->getStatusCode());

        $transferId = Uuid::uuid4();
        $transfer = $this->mustCreateFundsTransfer($originUser->id(), $destinationUser->id(), 10, $transferId);

        $this->processQueuedJobs();

        $transfer = $this->mustGetFundsTransfer($transfer->id());
        self::assertArraySubset(
            [
                'state' => FundsTransfer::STATE_INSUFFICIENT_BALANCE,
                'isFinalState' => true,
            ],
            $transfer->attributes()
        );

        $financialOperations = $this->mustGetFinancialOperations($originUser->id());
        $financialOperation = array_pop($financialOperations);
        $this->assertArraySubset(
            [
                'amount' => $transferAmount
            ],
            $financialOperation->attributes()
        );
        $this->assertArraySubset(
            [
                'type' => 'users',
                'id' => $originUser->id(),
            ],
            $financialOperation->relationship('user')->toArray()['data']
        );
        $this->assertArraySubset(
            [
                'type' => 'users',
                'id' => $destinationUser->id(),
            ],
            $financialOperation->relationship('counterparty')->toArray()['data']
        );

        $response = $this->tryGetFinancialOperations($destinationUser->id());
        // can't view someone else's financial operations
        $this->assertResponseStatus(self::HTTP_STATUS_FORBIDDEN, $response->getStatusCode());

        $response = $this->tryGetFinancialOperations(null);
        // can't view everyone else's financial operations
        $this->assertResponseStatus(self::HTTP_STATUS_BAD_REQUEST, $response->getStatusCode());

        $this->authenticate($destinationUser->attribute('email'), $destinationUserPassword);

        $response = $this->tryGetFundsTransfer($transfer->id());
        $this->assertResponseStatus(self::HTTP_STATUS_FORBIDDEN, $response->getStatusCode());

        $financialOperations = $this->mustGetFinancialOperations($destinationUser->id());
        $financialOperation = array_pop($financialOperations);
        $this->assertArraySubset(
            [
                'amount' => -$transferAmount
            ],
            $financialOperation->attributes()
        );
        $this->assertArraySubset(
            [
                'type' => 'users',
                'id' => $destinationUser->id(),
            ],
            $financialOperation->relationship('user')->toArray()['data']
        );
        $this->assertArraySubset(
            [
                'type' => 'users',
                'id' => $originUser->id(),
            ],
            $financialOperation->relationship('counterparty')->toArray()['data']
        );
    }

    private function processQueuedJobs()
    {
        $this->artisan('queue:work --once');
    }

    /**
     * @param string $userId
     * @return ResourceObject[]
     */
    private function mustGetFinancialOperations(string $userId): array
    {
        $response =  $this->tryGetFinancialOperations($userId);
        $this->assertResponseStatus(self::HTTP_STATUS_OK, $response->getStatusCode());

        return $response->document()->primaryResources();
    }

    private function tryGetFinancialOperations(?string $userId): JsonApiResponse
    {
        $uri = $this->apiUri->withPath('/api/v1/financial-operations');
        if ($userId) {
            $uri = $uri->withQuery('filter%5Buser%5D=' . $userId);
        }
        $request = $this->apiRequest->withUri($uri)->withMethod('GET');

        return $this->client->sendRequest($request);
    }

    private function mustGetFundsTransfer($id): ResourceObject
    {
        $response = $this->tryGetFundsTransfer($id);
        $this->assertResponseStatus(self::HTTP_STATUS_OK, $response->getStatusCode());

        return $response->document()->primaryResource();
    }

    private function tryGetFundsTransfer($id): JsonApiResponse
    {
        $request = $this->apiRequest->withUri($this->apiUri->withPath('/api/v1/funds-transfers/' . $id))
            ->withMethod('GET');

        return $this->client->sendRequest($request);
    }

    private function tryCreateFundsTransfer($originUserId, $destinationUserId, $amount, UuidInterface $transferId): JsonApiResponse
    {
        $json = <<<JSON
{
  "data": {
    "type": "funds-transfers",
    "id": "{$transferId}",
    "attributes": {
      "amount": "{$amount}"
    },
    "relationships": {
      "origin": {
        "data": {
          "type": "users",
          "id": "{$originUserId}"
        }
      },
      "destination": {
        "data": {
          "type": "users",
          "id": "{$destinationUserId}"
        }
      }
    }
  }
}
JSON;
        $request = $this->apiRequest->withUri($this->apiUri->withPath('/api/v1/funds-transfers'))
            ->withMethod('POST')
            ->withBody(stream_for($json));

        return $this->client->sendRequest($request);
    }

    private function mustCreateFundsTransfer($originUserId, $destinationUserId, $amount, UuidInterface $transferId): ResourceObject
    {
        $response = $this->tryCreateFundsTransfer($originUserId, $destinationUserId, $amount, $transferId);
        $this->assertResponseStatus(self::HTTP_STATUS_CREATED, $response->getStatusCode());

        return $response->document()->primaryResource();
    }

    private function tryCreateUser(User $user, $plainTextPassword = 'secret'): JsonApiResponse
    {
        $json = <<<JSON
{
  "data": {
    "type": "users",
    "attributes": {
      "email": "{$user->email}",
      "password": "$plainTextPassword"
    }
  }
}
JSON;
        $request = $this->apiRequest->withUri($this->apiUri->withPath('/api/v1/users'))
            ->withMethod('POST')
            ->withBody(stream_for($json));

        return $this->client->sendRequest($request);
    }

    private function mustCreateUser(User $user, $plainTextPassword = 'secret'): ResourceObject
    {
        $response = $this->tryCreateUser($user, $plainTextPassword);
        $this->assertResponseStatus(self::HTTP_STATUS_CREATED, $response->getStatusCode());

        return $response->document()->primaryResource();
    }

    private function authenticate($email, $password)
    {
        $json = <<<JSON
{
  "data": {
    "type": "access-tokens",
    "attributes": {
      "email": "{$email}",
      "password": "$password"
    }
  }
}
JSON;
        $request = $this->apiRequest->withUri($this->apiUri->withPath('/api/v1/access-tokens'))
            ->withMethod('POST')
            ->withBody(stream_for($json));
        $response = $this->client->sendRequest($request);
        $this->assertResponseStatus(self::HTTP_STATUS_CREATED, $response->getStatusCode());

        $authToken = $response->document()->primaryResource()->attribute('plainTextToken');
        $this->apiRequest = $this->apiRequest->withHeader('Authorization', 'Bearer ' . $authToken);
    }

    private function assertResponseStatus($expected, $actual)
    {
        self::assertEquals($expected, $actual, 'Unexpected response status code.');
    }
}
