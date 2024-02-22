<?php

declare(strict_types=1);

namespace VMassalov\Config\Structure;

class ItemConditions implements \Iterator
{
    private array $data;

    public function __construct(Condition ...$conditions)
    {
        foreach ($conditions as $condition) {
            $this->add($condition);
        }
    }

    public function add(Condition $condition): void
    {
        $this->data[$condition->name->value] = $condition;
    }

    public function current(): Condition
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): ?string
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    public function rewind(): void
    {
        reset($this->data);
    }
}
