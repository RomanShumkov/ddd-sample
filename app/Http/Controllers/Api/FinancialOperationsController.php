<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\FinancialOperation;
use App\User;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use CloudCreativity\LaravelJsonApi\Http\Requests\FetchResources;

class FinancialOperationsController extends JsonApiController
{
    protected function searching(FetchResources $request)
    {
        // have to obtain user from validated JSON API request filters before proceeding to authorization
        $userFromFilters = User::find($request->query('filter')['user']);
        $this->authorize('read', $userFromFilters);
    }
}
