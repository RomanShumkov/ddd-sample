<?php

namespace App\JsonApi\Users;

use App\User;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new User(), $paging);
    }

    protected function filter($query, Collection $filters)
    {
        $this->filterWithScopes($query, $filters);
    }
}
