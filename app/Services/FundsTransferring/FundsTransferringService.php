<?php
declare(strict_types=1);

namespace App\Services\FundsTransferring;

use App\Domain\Common\Funds;
use App\Domain\FundsTransferring\Account\InsufficientBalanceException;
use Ramsey\Uuid\UuidInterface;

/**
 * Service layer contract for transferring funds
 *
 * @see https://martinfowler.com/eaaCatalog/serviceLayer.html
 */
interface FundsTransferringService
{
    /**
     * Records the intention to transfer funds between two accounts
     *
     * @param int $originAccountId
     * @param int $destinationAccountId
     * @param Funds $funds
     * @param UuidInterface $transferId client-generated ID for avoiding duplicate transfers due to
     * retried calls after temporary communication failures
     * @return void
     * @throws DuplicateTransferException
     * @throws InsufficientBalanceException
     * @throws UnknownAccountException
     */
    public function transfer(int $originAccountId, int $destinationAccountId, Funds $funds, UuidInterface $transferId);
}
