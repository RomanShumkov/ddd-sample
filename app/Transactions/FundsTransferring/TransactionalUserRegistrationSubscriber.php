<?php
declare(strict_types=1);

namespace App\Transactions\FundsTransferring;

use App\Domain\FundsTransferring\Account\Account;
use App\Services\FundsTransferring\UserRegistrationEventSubscriber;
use App\Transactions\TransactionalDecorator;

class TransactionalUserRegistrationSubscriber extends TransactionalDecorator implements UserRegistrationEventSubscriber
{
    public function handleUserRegistered(int $userId): void
    {
        $locks = function () use ($userId) {
            $this->lockForUpdate(Account::class, $userId);
        };
        $this->transactional(__FUNCTION__, func_get_args(), $locks);
    }
}
