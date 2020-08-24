<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\User;
use CloudCreativity\LaravelJsonApi\Http\Controllers\JsonApiController;
use CloudCreativity\LaravelJsonApi\Http\Requests\ValidatedRequest;

class FundsTransfersController extends JsonApiController
{
    protected function creating(ValidatedRequest $request)
    {
        // have to obtain funds owner from validated JSON API request before proceeding to authorization
        $fundsOwner = User::find($request->get("data.relationships.origin.data.id"));
        $this->authorize('spend', $fundsOwner);
    }
}
