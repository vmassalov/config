<?php

declare(strict_types=1);

namespace VMassalov\Config\Structure;

class ConfigMap
{
    /** @var array<ConfigItem> */
    private array $items;

    public function __construct(ConfigItem ...$items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function addItem(ConfigItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @param array<string, int|string|float|bool> $criteria
     */
    public function findMatch(array $criteria): ?ConfigItem
    {
        foreach ($this->items as $configItem) {
            if ($configItem->isMatchCriteria($criteria)) {
                return $configItem;
            }
        }

        return null;
    }
}
