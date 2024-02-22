<?php

declare(strict_types=1);

namespace VMassalov\Config\ValueObjects;

class ConditionName
{
    private function __construct(
        public readonly string $value,
    ) {
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
