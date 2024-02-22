<?php

declare(strict_types=1);

namespace VMassalov\Config\Providers;

use VMassalov\Config\Exceptions\InvalidConfigurationException;
use VMassalov\Config\Exceptions\InvalidResourceException;

class FileProvider implements ProviderInterface
{
    private readonly string $rootPath;

    /**
     * @throws InvalidConfigurationException
     */
    public function __construct(
        string $rootPath,
    ) {
        $rootPath = realpath($rootPath);
        if (false === $rootPath) {
            throw new InvalidConfigurationException('Invalid root path for filesystem provider');
        }
        $this->rootPath = $rootPath;
    }

    /**
     * @throws InvalidResourceException
     */
    public function read(string $configName): string
    {
        if ('' === $configName) {
            throw new InvalidResourceException('Empty file name for reading config');
        }

        $configPath = $this->rootPath . DIRECTORY_SEPARATOR . $configName;
        $configPath = realpath($configPath);
        if (false === $configPath) {
            throw new InvalidResourceException('Missing config file');
        }
        if (!str_starts_with($configPath, $this->rootPath)) {
            throw new InvalidResourceException('Config outside a root path');
        }
        if (!is_readable($configPath)) {
            throw new InvalidResourceException('Access denied to config file');
        }

        $configContent = file_get_contents($configPath);
        if (empty($configContent)) {
            throw new InvalidResourceException('Empty config file');
        }

        return $configContent;
    }
}
