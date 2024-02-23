<?php

declare(strict_types=1);

namespace VMassalov\Config\Structure;

class ItemResult
{
    /** @var array<string|int|float|bool> */
    public readonly array $data;

    /**
     * @param string|array<int|string|float|bool> $data
     */
    public function __construct(
        string|array $data
    ) {
        if (is_string($data)) {
            $data = [$data];
        }
        $this->data = $data;
    }
}
