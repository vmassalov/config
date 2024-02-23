<?php

declare(strict_types=1);

namespace VMassalov\Config\Structure;

use VMassalov\Config\Exceptions\InvalidResourceException;
use VMassalov\Config\ValueObjects\ConditionName;

class Condition
{
    /**
     * @throws InvalidResourceException
     */
    public function __construct(
        public readonly ConditionName $name,
        private readonly CriteriaCases $cases,
        private readonly bool $isInverse = false,
    ) {
        if ($this->cases->count() <= 0) {
            throw new InvalidResourceException('Empty case for condition');
        }
    }

    public function isValueMatch(int|string|float|bool $value): bool
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
