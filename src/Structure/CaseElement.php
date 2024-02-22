<?php declare(strict_types=1);

namespace VMassalov\Config\Structure;

use VMassalov\Config\ValueObjects\MatchType;

class CaseElement
{
    public function __construct(
        private readonly mixed $value,
        private readonly MatchType $matchType,
    ) {
    }

    public function isValueMatch(mixed $value): bool
    {
        return match ($this->matchType) {
            MatchType::Equal => $this->value == $value,
            MatchType::StringStartsWith => str_starts_with($value, $this->value),
            MatchType::Pcre => preg_match($this->value, $value),
        };
    }
}
