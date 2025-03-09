<?php

namespace App\DataObjects;

final readonly class Money
{
    private int $amountInPennies;

    private function __construct(int $amountInPennies)
    {
        $this->amountInPennies = $amountInPennies;
    }

    public static function fromPounds(float $amountInPounds): self
    {
        return new self((int) round($amountInPounds * 100));
    }

    public static function fromPennies(int $amountInPennies): self
    {
        return new self($amountInPennies);
    }

    public function getAmountInPennies(): int
    {
        return $this->amountInPennies;
    }

    public function getAmountInPounds(): float
    {
        return $this->amountInPennies / 100;
    }

    public function __toString(): string
    {
        return '£' . number_format($this->getAmountInPounds(), 2);
    }
}
