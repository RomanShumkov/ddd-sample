<?php

namespace App\JsonApi\FundsTransfers;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    protected $allowedPagingParameters = [];

    protected $allowedIncludePaths = [];

    protected $allowedSortParameters = [];

    protected $allowedFilteringParameters = [];

    protected function rules($record = null): array
    {
        return [
            'id' => 'required|uuid',
            'amount' => 'required|integer|min:1',
        ];
    }

    protected function queryRules(): array
    {
        return [];
    }

}
