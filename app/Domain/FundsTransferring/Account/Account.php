<?php
declare(strict_types=1);

namespace App\Domain\FundsTransferring\Account;

use App\Domain\Common\Funds;

class Account
{
    private int $id;

    private Funds $balance;

    public function __construct(int $id, Funds $initialBalance)
    {
        $this->id = $id;
        $this->balance = $initialBalance;
    }

    /**
     * @param Funds $funds
     * @throws InsufficientBalanceException
     */
    public function withdraw(Funds $funds)
    {
        if ($this->balance->lessThan($funds)) {
            throw new InsufficientBalanceException();
        }

        $this->balance = $this->balance->subtract($funds);
    }

    public function deposit(Funds $funds)
    {
        $this->balance = $this->balance->add($funds);
    }

    /**
     * @param Funds $funds
     * @param Account $destination
     * @throws InsufficientBalanceException
     */
    public function transfer(Funds $funds, Account $destination)
    {
        $this->withdraw($funds);
        $destination->deposit($funds);
    }
}
