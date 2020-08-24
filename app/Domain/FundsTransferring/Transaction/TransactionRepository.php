<?php
declare(strict_types=1);

namespace App\Domain\FundsTransferring\Transaction;

use Ramsey\Uuid\UuidInterface;

interface TransactionRepository
{
    public function findById(UuidInterface $id): ?Transaction;

    public function store(Transaction $transaction);
}
