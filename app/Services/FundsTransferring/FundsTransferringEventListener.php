<?php
declare(strict_types=1);

namespace App\Services\FundsTransferring;

/**
 * Contract for events published by funds transferring package
 */
interface FundsTransferringEventListener
{
    public function transferCompleted(FundsTransferSnapshot $snapshot);
}
