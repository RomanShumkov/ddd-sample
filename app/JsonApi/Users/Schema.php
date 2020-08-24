<?php

namespace App\JsonApi\Users;

use App\User;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @OA\Schema(
 *     schema="User",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/JsonApiResourceObjectWithServerGeneratedId"),
 *         @OA\Schema(@OA\Property(property="type", enum={JsonApiUserResourceType})),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="attributes",
 *                 @OA\Property(property="email", ref="#/components/schemas/User.attributes.email"),
 *                 @OA\Property(property="password", ref="#/components/schemas/User.attributes.password"),
 *             ),
 *         ),
 *     },
 * )
 */
class Schema extends SchemaProvider
{
    public const RESOURCE_TYPE = 'users';

    protected $resourceType = self::RESOURCE_TYPE;

    public function getId($resource)
    {
        /** @var User $resource */
        return (string) $resource->getRouteKey();
    }

    public function getAttributes($resource)
    {
        return [
            'email' => $resource->email,
        ];
    }
}
