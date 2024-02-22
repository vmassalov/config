<?php declare(strict_types=1);

namespace VMassalov\Config\Structure;

class CriteriaCases implements \Iterator
{
    private array $data;

    public function __construct(CaseElement... $elements) {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    public function add(CaseElement $caseElement): void
    {
        $this->data[] = $caseElement;
    }

    public function current(): CaseElement
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): ?int
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
