<?php

namespace App\JsonApi\AccessTokens;

use Laravel\Sanctum\NewAccessToken;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @OA\Schema(
 *     schema="AccessToken",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/JsonApiResourceObjectWithServerGeneratedId"),
 *         @OA\Schema(@OA\Property(property="type", enum={JsonApiAccessTokenResourceType})),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="attributes",
 *                 @OA\Property(property="email", ref="#/components/schemas/AccessToken.attributes.email"),
 *                 @OA\Property(property="password", ref="#/components/schemas/AccessToken.attributes.password"),
 *                 @OA\Property(property="plainTextToken", ref="#/components/schemas/AccessToken.attributes.plainTextToken"),
 *             ),
 *         ),
 *     },
 * )
 */
class Schema extends SchemaProvider
{
    public const RESOURCE_TYPE = 'access-tokens';

    protected $resourceType = self::RESOURCE_TYPE;

    public function getId($resource)
    {
        /** @var NewAccessToken $resource */
        return (string) $resource->accessToken->getRouteKey();
    }

    public function getAttributes($resource)
    {
        /** @var NewAccessToken $resource */
        return [
            /**
             * @OA\Schema(
             *     schema="AccessToken.attributes.plainTextToken",
             *     type="string",
             *     readOnly=true,
             * )
             */
            'plainTextToken' => $resource->plainTextToken,
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'user' => [
                self::DATA => function () use ($resource) {
                    /** @var NewAccessToken $resource */
                    return $resource->accessToken->tokenable;
                }
            ],
        ];
    }

    public function getResourceLinks($resource)
    {
        return [];
    }
}
