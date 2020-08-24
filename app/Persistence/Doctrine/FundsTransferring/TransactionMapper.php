<?php
declare(strict_types=1);

namespace App\Persistence\Doctrine\FundsTransferring;

use App\Domain\FundsTransferring\Transaction\Transaction;
use App\Domain\FundsTransferring\Transaction\TransactionRepository;
use App\Persistence\Doctrine\AbstractMapper;
use Ramsey\Uuid\UuidInterface;

class TransactionMapper extends AbstractMapper implements TransactionRepository
{
    public function findById(UuidInterface $id): ?Transaction
    {
        return $this->find($id);
    }

    public function store(Transaction $transaction)
    {
        $this->persist($transaction);
    }
}
