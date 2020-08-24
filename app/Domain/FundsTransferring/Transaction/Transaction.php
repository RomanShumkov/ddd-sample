<?php
declare(strict_types=1);

namespace App\Domain\FundsTransferring\Transaction;

use Ramsey\Uuid\UuidInterface;

class Transaction
{
    private UuidInterface $id;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }
}
