<?php declare(strict_types=1);

namespace VMassalov\Config\Structure;

use VMassalov\Config\ValueObjects\ConditionName;

class Condition
{
    public function __construct(
        public readonly ConditionName $name,
        public readonly CriteriaCases $cases,
        public readonly bool $isInverse = false,
    ) {
    }
}
