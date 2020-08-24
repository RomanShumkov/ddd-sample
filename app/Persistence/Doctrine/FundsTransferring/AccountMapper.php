<?php
declare(strict_types=1);

namespace App\Persistence\Doctrine\FundsTransferring;

use App\Domain\FundsTransferring\Account\AccountRepository;
use App\Domain\FundsTransferring\Account\Account;
use App\Persistence\Doctrine\AbstractMapper;

class AccountMapper extends AbstractMapper implements AccountRepository
{
    public function findById(int $id): ?Account
    {
        return $this->find($id);
    }

    public function exists(int $id): bool
    {
        return $this->find($id) !== null;
    }

    public function store(Account $account)
    {
        $this->persist($account);
    }
}
