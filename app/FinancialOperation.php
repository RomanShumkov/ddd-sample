<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property User $user
 * @property User $counterparty_user
 * @property string $amount
 * @property string $transfer_id
 */
class FinancialOperation extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function counterparty_user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
