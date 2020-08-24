<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $amount
 * @property User $origin
 * @property User $destination
 * @property string $state
 * @property bool $is_final_state
 */
class FundsTransfer extends Model
{
    const STATE_TRANSFERRING = 'transferring';
    const STATE_INSUFFICIENT_BALANCE = 'insufficient_balance';
    const STATE_COMPLETED = 'completed';

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
    ];

    public $incrementing = false;

    public function origin(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transitionToTransferringState(): self
    {
        $this->state = self::STATE_TRANSFERRING;
        // TODO: consider using state machine
        $this->is_final_state = false;

        return $this;
    }

    public function transitionToInsufficientBalanceState(): self
    {
        $this->state = self::STATE_INSUFFICIENT_BALANCE;
        // TODO: consider using state machine
        $this->is_final_state = true;

        return $this;
    }

    public function transitionToCompletedState(): self
    {
        $this->state = self::STATE_COMPLETED;
        // TODO: consider using state machine
        $this->is_final_state = true;

        return $this;
    }

    public static function getPossibleStates(): array
    {
        return [
            self::STATE_TRANSFERRING,
            self::STATE_INSUFFICIENT_BALANCE,
            self::STATE_COMPLETED,
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function booted()
    {
        static::creating(function (FundsTransfer $model) {
            $model->transitionToTransferringState();
        });
    }
}
