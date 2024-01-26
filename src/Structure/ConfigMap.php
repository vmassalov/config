<?php declare(strict_types=1);

namespace VMassalov\Config\Structure;

class ConfigMap
{
    private array $items;

    public function __construct(ConfigItem... $items) {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function addItem(ConfigItem $item): void
    {
        $this->items[] = $item;
    }
}
