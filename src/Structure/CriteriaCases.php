<?php declare(strict_types=1);

namespace VMassalov\Config\Structure;

class CriteriaCases
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
}
