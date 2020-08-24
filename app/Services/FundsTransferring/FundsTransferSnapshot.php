<?php

namespace App\Services\FundsTransferring;

use App\Domain\Common\Funds;
use App\Events\FundsTransferCompleted;
use App\Listeners\UpdateReportingModelWhenFundsTransferCompleted;
use Ramsey\Uuid\UuidInterface;

/**
 * Contract for funds transferring events data
 *
 * Removes dependency on Laravel events while still allowing using Laravel easily
 *
 * @see UpdateReportingModelWhenFundsTransferCompleted
 * @see FundsTransferCompleted
 */
interface FundsTransferSnapshot
{
    public function getTransferId(): UuidInterface;

    public function getOriginAccountId(): int;

    public function getDestinationAccountId(): int;

    public function getFunds(): Funds;

    public function getSnapshotDate(): \DateTimeImmutable;
}
