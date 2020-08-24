<?php

namespace App\Listeners;

use App\Domain\Common\Funds;
use App\Events\FundsTransferCompleted;
use App\FinancialOperation;
use App\Services\FundsTransferring\FundsTransferringEventListener;
use App\Services\FundsTransferring\FundsTransferSnapshot;
use App\Services\FundsTransferring\FundsTransferSnapshotFactory;
use App\User;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\UuidInterface;

class UpdateReportingModelWhenFundsTransferCompleted implements FundsTransferringEventListener, FundsTransferSnapshotFactory
{
    public function transferCompleted(FundsTransferSnapshot $snapshot)
    {
        /**
         * TODO: tl;dr avoid data integrity issues (very rare but still)
         *
         * This method is executed in inside funds transferring doctrine transaction which has different db connection.
         * As a result there is very minor chance, that this method succeeds and immediately after that server crashes
         * and doctrine transaction is rolled back. Here are steps to visualize the process:
         *  - funds transferring transaction started (connection A)
         *  - this methid is called and reporting database transaction is eventually started (connection B)
         *  - reporting database update finishes successfully and connection B transaction is committed
         *  - now funds transferring transaction has one final step to commit but the server goes down
         *  - funds transferring transaction (connection A) is rolled backed by mysql because of connection timeout
         *  - as a result we have dispatched funds transferring event, but actual funds transferring was rolled back
         *  - funds transferring will be attempted again, but because of race conditions it may never succeed because of low balance
         *
         * Solution is to persist "funds transferring completed" event using connection A and then dispatch it to
         * reporting model with client-generated unique id (we already have transfer id for that)
         * Problem is there is no quick and simple way to persist event in laravel using doctrine. Not a rocket science,
         * but takes time.
         *
         * @see TransactionalFundsTransferring::transfer()
         */
        event($snapshot);
    }

    public function createFundsTransferSnapshot(
        UuidInterface $transferId,
        int $originAccountId,
        int $destinationAccountId,
        Funds $funds
    ): FundsTransferSnapshot {
        return new FundsTransferCompleted(
            $transferId,
            $originAccountId,
            $destinationAccountId,
            $funds,
            new \DateTimeImmutable()
        );
    }

    public function handle(FundsTransferCompleted $event)
    {
        DB::transaction(function () use ($event) {
            $this->doHandle($event);
        });
    }

    private function doHandle(FundsTransferCompleted $event)
    {
        $financialOperation = new FinancialOperation();
        $financialOperation->user()->associate(User::find($event->getOriginAccountId()));
        $financialOperation->counterparty_user()->associate(User::find($event->getDestinationAccountId()));
        $financialOperation->amount = $event->getFunds()->getAmount();
        $financialOperation->transfer_id = $event->getTransferId()->toString();
        $financialOperation->save();

        $financialOperation = new FinancialOperation();
        $financialOperation->user()->associate(User::find($event->getDestinationAccountId()));
        $financialOperation->counterparty_user()->associate(User::find($event->getOriginAccountId()));
        $financialOperation->amount = $event->getFunds()->negate()->getAmount();
        $financialOperation->transfer_id = $event->getTransferId()->toString();
        $financialOperation->save();
    }


}
