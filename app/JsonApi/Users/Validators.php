<?php

namespace App\JsonApi\Users;

use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    protected $allowedPagingParameters = [];

    protected $allowedIncludePaths = [];

    protected $allowedSortParameters = [];

    protected $allowedFilteringParameters = [];

    public static function getPasswordValidators(): string
    {
        return 'required|string|between:5,255';// weak passwords for demo purposes
    }

    public static function getEmailValidators(): string
    {
        return 'required|email:rfc';// weak passwords for demo purposes
    }

    protected function rules($record = null): array
    {
        return [
            /**
             * @OA\Schema(
             *     schema="User.attributes.email",
             *     type="string",
             *     format="email",
             *     writeOnly=true,
             * )
             */
            'email' => self::getEmailValidators() . '|' . 'unique:App\User,email',

            /**
             * @OA\Schema(
             *     schema="User.attributes.password",
             *     type="string",
             *     writeOnly=true,
             *     example="secret",
             * )
             */
            'password' => self::getPasswordValidators(),
        ];
    }

    protected function queryRules(): array
    {
        return [
            //
        ];
    }

}
