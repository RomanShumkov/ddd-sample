<?php

namespace App\JsonApi\FundsTransfers;

use App\FundsTransfer;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @OA\Schema(
 *     schema="FundsTransfer",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/JsonApiResourceObjectWithClientGeneratedId"),
 *         @OA\Schema(@OA\Property(property="type", enum={JsonApiFundsTransferResourceType})),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="attributes",
 *                 @OA\Property(property="amount", ref="#/components/schemas/FundsTransfer.attributes.amount"),
 *                 @OA\Property(property="state", ref="#/components/schemas/FundsTransfer.attributes.state"),
 *                 @OA\Property(property="isFinalState", ref="#/components/schemas/FundsTransfer.attributes.isFinalState"),
 *             ),
 *             @OA\Property(
 *                 property="relationships",
 *                 @OA\Property(
 *                     property="origin",
 *                     @OA\Property(
 *                         property="data",
 *                         @OA\Property(property="type", type="string", enum={"users"}),
 *                         @OA\Property(property="id", type="string", example="1"),
 *                     ),
 *                 ),
 *                 @OA\Property(
 *                     property="destination",
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
    public const RESOURCE_TYPE = 'funds-transfers';

    protected $resourceType = self::RESOURCE_TYPE;

    public function getId($resource)
    {
        /** @var FundsTransfer $resource */
        return (string) $resource->getKey();
    }

    public function getAttributes($resource)
    {

        return [
            /**
             * @OA\Schema(
             *     schema="FundsTransfer.attributes.amount",
             *     type="string",
             *     title="Funds amount in smallest unit of account",
             *     example="10"
             * )
             */
            'amount' => $resource->amount,

            /**
             * @OA\Schema(
             *     schema="FundsTransfer.attributes.state",
             *     type="string",
             *     enum=JsonApiFundsTransferPossibleStates,
             *     readOnly=true,
             * )
             */
            'state' => $resource->state,

            /**
             * @OA\Schema(
             *     schema="FundsTransfer.attributes.isFinalState",
             *     type="boolean",
             *     readOnly=true,
             * )
             */
            'isFinalState' => $resource->is_final_state,
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'origin' => [
                self::DATA => function () use ($resource) {
                    /** @var FundsTransfer $resource */
                    return $resource->origin;
                }
            ],
            'destination' => [
                self::DATA => function () use ($resource) {
                    /** @var FundsTransfer $resource */
                    return $resource->destination;
                }
            ],
        ];
    }


}
