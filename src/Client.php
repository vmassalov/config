<?php

declare(strict_types=1);

namespace VMassalov\Config;

use VMassalov\Config\Providers\ProviderInterface;

class Client
{
    public function __construct(
        private readonly ProviderInterface $provider,
        private readonly ParserFactory $parserFactory,
    ) {
    }

    public function find(string $configName, array $searchConditions): array
    {
        $configData = $this->provider->read($configName);
        $configMap = $this->parserFactory
            ->build($configName)
            ->parse($configData);
        $result = $configMap->findMatch($searchConditions);
        if (null === $result) {
            return [];
        }

        return $result->result->data;
    }
}
