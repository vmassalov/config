<?php

declare(strict_types=1);

namespace VMassalov\Config\Structure;

use VMassalov\Config\Exceptions\InvalidResourceException;

class ConfigItem
{
    public function __construct(
        private readonly ItemConditions $conditions,
        public readonly ItemResult $result,
    ) {
        if ($this->conditions->count() === 0) {
            throw new InvalidResourceException('Unsupported empty conditions');
        }
    }

    /**
     * @param array<string, int|string|float|bool> $criteria
     */
    public function isMatchCriteria(array $criteria): bool
    {
        foreach ($this->conditions as $condition) {
            if (!array_key_exists($condition->name->value, $criteria)) {
                return false;
            }
            if (false === $condition->isValueMatch($criteria[$condition->name->value])) {
                return false;
            }
        }

        return true;
    }
}
