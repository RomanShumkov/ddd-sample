<?php

namespace App\JsonApi\AccessTokens;

use App\Http\Controllers\Api\AccessTokensController;
use CloudCreativity\LaravelJsonApi\Adapter\AbstractResourceAdapter;
use CloudCreativity\LaravelJsonApi\Document\ResourceObject;
use Illuminate\Support\Collection;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;

/**
 * JSON API package expects to have resource adapter even when it's not used in runtime
 *
 * @see AccessTokensController::create()
 */
class Adapter extends AbstractResourceAdapter
{
    protected function createRecord(ResourceObject $resource)
    {
        throw new \RuntimeException('Not implemented');
    }

    protected function fillAttributes($record, Collection $attributes)
    {
        throw new \RuntimeException('Not implemented');
    }

    protected function persist($record)
    {
        throw new \RuntimeException('Not implemented');
    }

    protected function destroy($record)
    {
        throw new \RuntimeException('Not implemented');
    }

    public function query(EncodingParametersInterface $parameters)
    {
        throw new \RuntimeException('Not implemented');
    }

    public function exists(string $resourceId): bool
    {
        throw new \RuntimeException('Not implemented');
    }

    public function find(string $resourceId)
    {
        throw new \RuntimeException('Not implemented');
    }

    public function findMany(iterable $resourceIds): iterable
    {
        throw new \RuntimeException('Not implemented');
    }
}
