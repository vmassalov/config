<?php declare(strict_types=1);

namespace VMassalov\Config\Structure;

class ItemConditions
{
    private array $data;

    public function __construct(Condition... $conditions) {
        foreach ($conditions as $condition) {
            $this->add($condition);
        }
    }

    public function add(Condition $condition): void
    {
        $this->data[$condition->name->value] = $condition;
    }
}
