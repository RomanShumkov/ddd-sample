<?php

namespace App\Policies;

use App\Http\Controllers\Api\FinancialOperationsController;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinancialOperationPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        /** @see UserPolicy::read() */
        /** @see FinancialOperationsController::searching() */
        return true;// silence default authorizer
    }
}
