<?php

declare(strict_types=1);

namespace VMassalov\Config\Structure;

use VMassalov\Config\Exceptions\InvalidResourceException;
use VMassalov\Config\ValueObjects\MatchType;

class CaseElement
{
    /**
     * @throws InvalidResourceException
     */
    public function __construct(
        private readonly int|string|float|bool $value,
        private readonly MatchType $matchType,
    ) {
        if (
            in_array($this->matchType, [MatchType::StringStartsWith, MatchType::Pcre])
            && !is_string($this->value)
        ) {
            throw new InvalidResourceException('Unsupported value for match type');
        }

        if (is_string($this->value) && '' === $this->value) {
            throw new InvalidResourceException('Unsupported empty string value');
        }
    }

    public function isValueMatch(int|string|float|bool $value): bool
    {
        return match ($this->matchType) {
            MatchType::Equal => $this->value == $value,
            MatchType::StringStartsWith => str_starts_with($value, $this->value),
            MatchType::Pcre => (bool)preg_match($this->value, $value),
        };
    }
}
