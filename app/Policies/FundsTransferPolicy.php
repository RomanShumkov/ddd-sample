<?php

namespace App\Policies;

use App\FundsTransfer;
use App\Http\Controllers\Api\FundsTransfersController;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FundsTransferPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        /** @see UserPolicy::spend() */
        /** @see FundsTransfersController::creating() */
        return true;// silence default authorizer
    }

    public function read(User $user, FundsTransfer $transfer): bool
    {
        return $user->is($transfer->origin);
    }
}
