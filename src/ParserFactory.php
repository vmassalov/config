<?php

declare(strict_types=1);

namespace VMassalov\Config;

use VMassalov\Config\Exceptions\InvalidResourceException;
use VMassalov\Config\Parsers\Json;
use VMassalov\Config\Parsers\ParserInterface;
use VMassalov\Config\Parsers\Xml;
use VMassalov\Config\Parsers\Yaml;
use VMassalov\Config\ValueObjects\ConfigType;
use VMassalov\Config\ValueObjects\Scheme;

class ParserFactory
{
    public function __construct(
        private readonly Scheme $scheme,
    ) {
    }

    public function build(string $configName): ParserInterface
    {
        return match ($this->scheme) {
            Scheme::Filesystem => $this->buildForFilename($configName),
        };
    }

    private function buildForFilename(string $fileName): ParserInterface
    {
        $extension = pathinfo($fileName, flags: PATHINFO_EXTENSION);

        $configType = match (strtolower($extension)) {
            'xml' => ConfigType::Xml,
            'json' => ConfigType::Json,
            'yaml',
            'yml' => ConfigType::Yaml,
            default => throw new InvalidResourceException('Unsupported file extension'),
        };

        return $this->buildForConfigType($configType);
    }

    private function buildForConfigType(ConfigType $configType): ParserInterface
    {
        return match ($configType) {
            ConfigType::Json => new Json(),
            ConfigType::Xml => new Xml(),
            ConfigType::Yaml => new Yaml(),
        };
    }
}
