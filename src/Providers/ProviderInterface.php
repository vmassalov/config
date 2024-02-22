<?php

declare(strict_types=1);

namespace VMassalov\Config\Providers;

interface ProviderInterface
{
    public function read(string $configName): string;
}
