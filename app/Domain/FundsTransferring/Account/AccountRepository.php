<?php
declare(strict_types=1);

namespace App\Domain\FundsTransferring\Account;

interface AccountRepository
{
    public function findById(int $id): ?Account;

    public function exists(int $id): bool;

    public function store(Account $account);
}
