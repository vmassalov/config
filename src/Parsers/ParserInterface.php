<?php

declare(strict_types=1);

namespace VMassalov\Config\Parsers;

use VMassalov\Config\Structure\ConfigMap;

interface ParserInterface
{
    public function parse(string $configData): ConfigMap;
}
