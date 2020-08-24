<?php
declare(strict_types=1);

namespace App\Services\FundsTransferring;

/**
 * Contract for user registration event consumed by funds transferring package
 */
interface UserRegistrationEventSubscriber
{
    /**
     * Opens account for holding funds balance for newly registered users
     *
     * Responsibility of opening new accounts could be extracted to account management context/package when it's time
     * to implement account management features.
     *
     * @param int $userId
     * @throws DuplicateAccountException when account was already opened for the user
     */
    public function handleUserRegistered(int $userId): void;
}
