<?php

namespace App\JsonApi\FinancialOperations;

use App\JsonApi\JsonApiSettings;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    protected $allowedPagingParameters = ['number', 'size'];

    protected $allowedIncludePaths = [];

    protected $allowedSortParameters = [];

    protected $allowedFilteringParameters = ['user'];

    protected function rules($record = null): array
    {
        return [];
    }

    protected function queryRules(): array
    {
        return [
            /**
             * @OA\Schema(
             *     schema="FinancialOperation.query.filter.user",
             *     type="integer",
             *     minimum="1",
             *     example="1",
             * )
             */
            'filter.user' => 'required|integer|min:1',

            /**
             * @OA\Schema(
             *     schema="FinancialOperation.query.page.number",
             *     type="integer",
             *     minimum="1",
             *     example="1",
             * )
             */
            'page.number' => 'filled|numeric|min:1',

            /**
             * @OA\Schema(
             *     schema="FinancialOperation.query.page.size",
             *     type="integer",
             *     minimum="1",
             *     maximum=JsonApiCollectionMaxPageSize,
             *     example=JsonApiCollectionDefaultPageSize,
             * )
             */
            'page.size' => 'filled|numeric|between:1,' . JsonApiSettings::MAX_PAGE_SIZE,
        ];
    }

}
