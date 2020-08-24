<?php

namespace App\Services\FundsTransferring;

use App\Domain\Common\Funds;
use App\Listeners\UpdateReportingModelWhenFundsTransferCompleted;
use Ramsey\Uuid\UuidInterface;

/**
 * In combination with funds transferring snapshot contract decouples service layer from actual framework
 *
 * @see FundsTransferSnapshot
 * @see UpdateReportingModelWhenFundsTransferCompleted
 */
interface FundsTransferSnapshotFactory
{
    public function createFundsTransferSnapshot(
        UuidInterface $transferId,
        int $originAccountId,
        int $destinationAccountId,
        Funds $funds
    ): FundsTransferSnapshot;
}
