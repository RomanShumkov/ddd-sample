<?php
declare(strict_types=1);

namespace App\Domain\FundsTransferring\Transaction;

use App\Domain\Common\Funds;
use App\Domain\FundsTransferring\Account\Account;
use Ramsey\Uuid\UuidInterface;

class Transaction
{
    private UuidInterface $id;

    private Account $origin;

    private Account $destination;

    private Funds $funds;

    private \DateTimeImmutable $dateTime;

    private bool $isDispatched = false;

    public function __construct(UuidInterface $id, Account $origin, Account $destination, Funds $funds)
    {
        $this->id = $id;
        $this->origin = $origin;
        $this->destination = $destination;
        $this->funds = $funds;
    }

    public function isDispatched(): bool
    {
        return $this->isDispatched;
    }

    public function markDispatched()
    {

    }
}
