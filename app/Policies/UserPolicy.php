<?php

namespace App\Policies;

use App\FundsTransfer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function spend(User $authenticatedUser, User $fundsOwner)
    {
        return $authenticatedUser->is($fundsOwner);
    }

    public function read(User $authenticatedUser, User $targetUser)
    {
        return $authenticatedUser->is($targetUser);
    }
}
