<?php

declare(strict_types=1);

namespace VMassalov\Config\Structure;

/**
 * @implements \Iterator<int, CaseElement>
 */
class CriteriaCases implements \Iterator, \Countable
{
    /** @var array<int, CaseElement> */
    private array $data = [];

    public function __construct(CaseElement ...$elements)
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    public function add(CaseElement $caseElement): void
    {
        $this->data[] = $caseElement;
    }

    public function current(): CaseElement|false
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): ?int
    {
        $key = key($this->data);
        if (!is_null($key) && !is_int($key)) {
            throw new \LogicException('Invalid criteria key');
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
