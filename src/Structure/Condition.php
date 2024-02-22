<?php

declare(strict_types=1);

namespace VMassalov\Config\Structure;

use VMassalov\Config\ValueObjects\ConditionName;

class Condition
{
    public function __construct(
        public readonly ConditionName $name,
        private readonly CriteriaCases $cases,
        private readonly bool $isInverse = false,
    ) {
    }

    public function isValueMatch(mixed $value): bool
    {
        $result = false;
        foreach ($this->cases as $case) {
            if ($case->isValueMatch($value)) {
                $result = true;
                break;
            }
        }

        if ($this->isInverse) {
            $result = !$result;
        }

        return $result;
    }
}
