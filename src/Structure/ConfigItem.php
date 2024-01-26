<?php declare(strict_types=1);

namespace VMassalov\Config\Structure;

class ConfigItem
{
    public function __construct(
        public readonly ItemConditions $conditions,
        public readonly ItemResult $result,
    ) {
    }
}
