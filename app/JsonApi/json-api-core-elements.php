<?php
/**
 * @OA\Schema(
 *     schema="JsonApiResourceObjectWithServerGeneratedId",
 *     title="JSON:API Resource Object",
 *     @OA\Property(property="type", ref="#/components/schemas/JsonApiType"),
 *     @OA\Property(property="id", ref="#/components/schemas/JsonApiId"),
 *     @OA\Property(property="links", ref="#/components/schemas/JsonApiLinksObject"),
 * )
 */

/**
 * @OA\Schema(
 *     schema="JsonApiResourceObjectWithClientGeneratedId",
 *     title="JSON:API Resource Object",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/JsonApiResourceObjectWithServerGeneratedId"),
 *         @OA\Schema(@OA\Property(property="id", readOnly=false, format="uuid"))
 *     },
 * )
 */

/**
 * @OA\Schema(
 *     schema="JsonApiType",
 *     title="JSON:API type Member",
 *     type="string"
 * )
 */

/**
 * @OA\Schema(
 *     schema="JsonApiId",
 *     title="JSON:API id Member",
 *     type="string",
 *     readOnly=true
 * )
 */

/**
 * @OA\Schema(
 *     schema="JsonApiLinksObject",
 *     title="JSON:API Links Object",
 *     readOnly=true,
 *     anyOf={
 *         @OA\Schema(@OA\Property(property="self", ref="#/components/schemas/JsonApiLink")),
 *         @OA\Schema(@OA\Property(property="related", ref="#/components/schemas/JsonApiLink")),
 *         @OA\Schema(@OA\Property(property="first", ref="#/components/schemas/JsonApiLink")),
 *         @OA\Schema(@OA\Property(property="last", ref="#/components/schemas/JsonApiLink")),
 *         @OA\Schema(@OA\Property(property="prev", ref="#/components/schemas/JsonApiLink")),
 *         @OA\Schema(@OA\Property(property="next", ref="#/components/schemas/JsonApiLink"))
 *     },
 *     example={"self": "http://example/com/users/1"}
 * )
 */

/**
 * @OA\Schema(
 *     schema="JsonApiLink",
 *     title="JSON:API Link",
 *     oneOf={
 *         @OA\Schema(ref="#/components/schemas/URL"),
 *         @OA\Schema(ref="#/components/schemas/JsonApiLinkObject")
 *     }
 * )
 */

/**
 * @OA\Schema(
 *     schema="URL",
 *     format="url",
 *     type="string",
 *     example="https://example.com/"
 * )
 */

/**
 * @OA\Schema(
 *     schema="JsonApiLinkObject",
 *     title="JSON:API Link Object",
 *     @OA\Property(property="href", type="string"),
 *     @OA\Property(property="meta", type="object")
 * )
 */
