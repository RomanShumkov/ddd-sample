<?php
declare(strict_types=1);

namespace App\Events;

use App\User;

class UserCreated
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
