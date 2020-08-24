<?php
declare(strict_types = 1);

namespace App\Domain\Common;

class Funds
{
    private int $amount;

    public function __construct(string $amount)
    {
        // TODO: support bigint
        $this->amount = (int) $amount;
    }

    public function getAmount(): string
    {
        return (string) $this->amount;
    }

    public function lessThan(Funds $funds): bool
    {
        // TODO: support bigint
        return $this->amount < (int) $funds->getAmount();
    }

    public function subtract(Funds $funds): self
    {
        // TODO: support bigint
        return static::fromInt($this->amount - (int) $funds->getAmount());
    }

    public function add(Funds $funds): self
    {
        // TODO: support bigint
        return static::fromInt($this->amount + (int) $funds->getAmount());
    }

    public function negate(): self
    {
        // TODO: support bigint
        return static::fromInt(-$this->amount);
    }

    private static function fromInt(int $amount): self
    {
        return new static((string) $amount);
    }
}
