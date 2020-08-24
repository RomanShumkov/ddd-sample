<?php


namespace App\Events;


use App\Domain\Common\Funds;
use App\Services\FundsTransferring\FundsTransferSnapshot;
use Ramsey\Uuid\UuidInterface;

class FundsTransferCompleted implements FundsTransferSnapshot
{
    private UuidInterface $transferId;

    private int $originUserId;

    private int $destinationUserId;

    private Funds $funds;

    private \DateTimeImmutable $dateTime;

    public function __construct(
        UuidInterface $transferId,
        int $originUserId,
        int $destinationUserId,
        Funds $funds,
        \DateTimeImmutable $dateTime
    ) {
        $this->transferId = $transferId;
        $this->originUserId = $originUserId;
        $this->destinationUserId = $destinationUserId;
        $this->funds = $funds;
        $this->dateTime = $dateTime;
    }

    public function getTransferId(): UuidInterface
    {
        return $this->transferId;
    }

    public function getOriginAccountId(): int
    {
        return $this->originUserId;
    }

    public function getDestinationAccountId(): int
    {
        return $this->destinationUserId;
    }

    public function getFunds(): Funds
    {
        return $this->funds;
    }

    public function getSnapshotDate(): \DateTimeImmutable
    {
        return $this->dateTime;
    }
}
