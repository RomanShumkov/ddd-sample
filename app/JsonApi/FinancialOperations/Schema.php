<?php

namespace App\JsonApi\FinancialOperations;

use App\FinancialOperation;
use App\FundsTransfer;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @OA\Schema(
 *     schema="FinancialOperation",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/JsonApiResourceObjectWithServerGeneratedId"),
 *         @OA\Schema(@OA\Property(property="type", enum={JsonApiFinancialOperationResourceType})),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="attributes",
 *                 @OA\Property(property="amount", ref="#/components/schemas/FinancialOperation.attributes.amount"),
 *             ),
 *             @OA\Property(
 *                 property="relationships",
 *                 @OA\Property(
 *                     property="user",
 *                     @OA\Property(
 *                         property="data",
 *                         @OA\Property(property="type", type="string", enum={"users"}),
 *                         @OA\Property(property="id", type="string", example="1"),
 *                     ),
 *                 ),
 *                 @OA\Property(
 *                     property="counterparty",
 *                     @OA\Property(
 *                         property="data",
 *                         @OA\Property(property="type", type="string", enum={"users"}),
 *                         @OA\Property(property="id", type="string", example="2"),
 *                     ),
 *                 ),
 *             ),
 *         ),
 *     },
 * )
 */
class Schema extends SchemaProvider
{
    public const RESOURCE_TYPE = 'financial-operations';

    protected $resourceType = self::RESOURCE_TYPE;

    public function getId($resource)
    {
        /** @var FinancialOperation $resource */
        return (string) $resource->getKey();
    }

    public function getAttributes($resource)
    {

        return [
            /**
             * @OA\Schema(
             *     schema="FinancialOperation.attributes.amount",
             *     type="string",
             *     title="Funds amount in smallest unit of account",
             *     example="10"
             * )
             */
            'amount' => (string) $resource->amount,
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'user' => [
                self::DATA => function () use ($resource) {
                    /** @var FinancialOperation $resource */
                    return $resource->user;
                }
            ],
            'counterparty' => [
                self::DATA => function () use ($resource) {
                    /** @var FinancialOperation $resource */
                    return $resource->counterparty_user;
                }
            ],
        ];
    }

    public function getResourceLinks($resource)
    {
        return [];
    }
}
