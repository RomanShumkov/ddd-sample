<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Services\FundsTransferring\DuplicateAccountException;
use App\Services\FundsTransferring\UserRegistrationEventSubscriber;
use Illuminate\Log\Logger;

class NotifyFundsTransferringWhenUserCreated
{
    private UserRegistrationEventSubscriber $fundsTransferring;

    private Logger $logger;

    public function __construct(UserRegistrationEventSubscriber $fundsTransferring, Logger $logger)
    {
        $this->fundsTransferring = $fundsTransferring;
        $this->logger = $logger;
    }

    public function handle(UserCreated $event)
    {
        try {
            $this->fundsTransferring->handleUserRegistered($event->getUser()->id);
        } catch (DuplicateAccountException $e) {
            // Could be due to async event delivery combined with temporary availability issue, which is
            // an exceptional occurrence but not an error with current implementation.
            $this->logger->warning('Funds transferring account already exists for recently created user');
        }
    }
}
