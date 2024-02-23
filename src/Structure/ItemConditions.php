<?php

declare(strict_types=1);

namespace VMassalov\Config\Structure;

/**
 * @implements \Iterator<string, Condition>
 */
class ItemConditions implements \Iterator, \Countable
{
    /** @var array<string, Condition> */
    private array $data = [];

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

    public function current(): Condition|false
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): ?string
    {
        $key = key($this->data);
        if (!is_null($key) && !is_string($key)) {
            throw new \LogicException('Invalid condition key');
        }

        return $key;
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    public function rewind(): void
    {
        reset($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }
}
