<?php declare(strict_types=1);

namespace VMassalov\Config\Structure;

class ItemResult
{
    private readonly array $data;

    public function __construct(
        string|array $data
    ) {
        if (is_string($data)) {
            $data = [$data];
        }
        $this->data = $data;
    }
}
