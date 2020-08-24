<?php
declare(strict_types=1);

namespace App\Services\FundsTransferring\Implementation;

use App\Services\FundsTransferring\DuplicateAccountException;
use App\Services\FundsTransferring\DuplicateTransferException;
use App\Services\FundsTransferring\FundsTransferringEventListener;
use App\Services\FundsTransferring\FundsTransferringService;
use App\Services\FundsTransferring\FundsTransferSnapshotFactory;
use App\Services\FundsTransferring\UnknownAccountException;
use App\Domain\Common\Funds;
use App\Domain\FundsTransferring\Transaction\Transaction;
use App\Domain\FundsTransferring\Transaction\TransactionRepository;
use App\Domain\FundsTransferring\Account\Account;
use App\Domain\FundsTransferring\Account\AccountRepository;
use App\Services\FundsTransferring\UserRegistrationEventSubscriber;
use Ramsey\Uuid\UuidInterface;

/**
 * Implementation for funds transferring contracts
 *
 * @see FundsTransferringService
 * @see UserRegistrationEventSubscriber
 */
class FundsTransferrer implements FundsTransferringService, UserRegistrationEventSubscriber
{
    private AccountRepository $accounts;

    private TransactionRepository $transactions;

    private FundsTransferSnapshotFactory $snapshotFactory;

    private FundsTransferringEventListener $eventListener;

    public function __construct(
        AccountRepository $users,
        TransactionRepository $transactions,
        FundsTransferSnapshotFactory $snapshotFactory,
        FundsTransferringEventListener $eventListener
    ) {
        $this->accounts = $users;
        $this->transactions = $transactions;
        $this->snapshotFactory = $snapshotFactory;
        $this->eventListener = $eventListener;
    }

    /**
     * {@inheritDoc}
     */
    public function transfer(int $originAccountId, int $destinationAccountId, Funds $funds, UuidInterface $transferId)
    {
        $duplicateTransfer = $this->transactions->findById($transferId);
        if ($duplicateTransfer) {
            throw new DuplicateTransferException();
        }

        $originAccount = $this->mustFindAccount($originAccountId);
        $destinationAccount = $this->mustFindAccount($destinationAccountId);
        $originAccount->transfer($funds, $destinationAccount);

        $transaction = new Transaction($transferId);
        $this->transactions->store($transaction);

        $snapshot = $this->snapshotFactory->createFundsTransferSnapshot(
            $transferId,
            $originAccountId,
            $destinationAccountId,
            $funds
        );

        $this->eventListener->transferCompleted($snapshot);
    }

    public function handleUserRegistered(int $userId): void
    {
        $this->openNewAccount($userId);
    }

    /**
     * @param int $id
     * @return Account
     * @throws UnknownAccountException
     */
    private function mustFindAccount(int $id): Account
    {
        $user = $this->accounts->findById($id);
        if (!$user) {
            throw new UnknownAccountException();
        }

        return $user;
    }

    private function openNewAccount(int $userId)
    {
        // For now associate accounts with users by using same primary id. This way account model stays clean
        // without user id reference which is not required in funds transferring context.
        if ($this->accounts->exists($userId)) {
            throw new DuplicateAccountException();
        }

        // Starting with non zero balance for demo purposes
        $account = new Account($userId, new Funds('10'));
        $this->accounts->store($account);
    }
}
