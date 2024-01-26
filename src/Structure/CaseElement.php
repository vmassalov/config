<?php declare(strict_types=1);

namespace VMassalov\Config\Structure;

use VMassalov\Config\ValueObjects\MatchType;

class CaseElement
{
    public function __construct(
        public readonly mixed $value,
        public readonly MatchType $matchType,
    ) {
    }
}
