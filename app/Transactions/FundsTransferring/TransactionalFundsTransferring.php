<?php
declare(strict_types=1);

namespace App\Transactions\FundsTransferring;

use App\Domain\Common\Funds;
use App\Domain\FundsTransferring\Account\Account;
use App\Services\FundsTransferring\FundsTransferringService;
use App\Transactions\TransactionalDecorator;
use Ramsey\Uuid\UuidInterface;

class TransactionalFundsTransferring extends TransactionalDecorator implements FundsTransferringService
{
    public function transfer(int $originAccountId, int $destinationAccountId, Funds $funds, UuidInterface $transferId)
    {
        $locks = function () use ($originAccountId, $destinationAccountId) {
            $this->lockForUpdate(Account::class, $originAccountId);
            $this->lockForUpdate(Account::class, $destinationAccountId);
        };

        $this->transactional(__FUNCTION__, func_get_args(), $locks);
    }
}
