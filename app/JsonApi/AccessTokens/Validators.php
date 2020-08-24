<?php

namespace App\JsonApi\AccessTokens;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;
use App\JsonApi\Users\Validators as UserValidators;

class Validators extends AbstractValidators
{
    protected $allowedPagingParameters = [];

    protected $allowedIncludePaths = [];

    protected $allowedSortParameters = [];

    protected $allowedFilteringParameters = [];

    protected function rules($record = null): array
    {
        return array(
            /**
             * @OA\Schema(
             *     schema="AccessToken.attributes.email",
             *     ref="#/components/schemas/User.attributes.email",
             * )
             */
            'email' => UserValidators::getEmailValidators(),

            /**
             * @OA\Schema(
             *     schema="AccessToken.attributes.password",
             *     ref="#/components/schemas/User.attributes.password",
             * )
             */
            'password' => UserValidators::getPasswordValidators(),
        );
    }

    protected function queryRules(): array
    {
        return [
            //
        ];
    }

}
