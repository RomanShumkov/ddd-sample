<?php

namespace App\Jobs;

use App\Domain\Common\Funds;
use App\Domain\FundsTransferring\Account\InsufficientBalanceException;
use App\FundsTransfer;
use App\Services\FundsTransferring\DuplicateTransferException;
use App\Services\FundsTransferring\FundsTransferringService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class InitiateFundsTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //TODO: specify max job attempts and timeout values
    // https://laravel.com/docs/7.x/queues#max-job-attempts-and-timeout

    private FundsTransfer $fundsTransfer;

    public function __construct(FundsTransfer $fundsTransfer)
    {
        $this->fundsTransfer = $fundsTransfer;
    }

    public function handle(FundsTransferringService $fundsTransferrer, LoggerInterface $logger)
    {
        try {
            $fundsTransferrer->transfer(
                (int) $this->fundsTransfer->origin->id,
                (int) $this->fundsTransfer->destination->id,
                new Funds($this->fundsTransfer->amount),
                Uuid::fromString($this->fundsTransfer->getKey())
            );
            $this->fundsTransfer->transitionToCompletedState()->save();
        } catch (InsufficientBalanceException $e) {
            $this->fundsTransfer->transitionToInsufficientBalanceState()->save();
        } catch (DuplicateTransferException $e) {
            // Could be due to async event delivery combined with temporary availability issue, which is
            // an exceptional occurrence but not an error with current implementation.
            $logger->warning('Funds transfer already exists in domain model');
            $this->fundsTransfer->transitionToCompletedState()->save();
        }
    }
}
