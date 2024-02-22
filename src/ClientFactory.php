<?php

declare(strict_types=1);

namespace VMassalov\Config;

use VMassalov\Config\Exceptions\InvalidConfigurationException;
use VMassalov\Config\Providers\FileProvider;
use VMassalov\Config\ValueObjects\Scheme;

class ClientFactory
{
    /**
     * @param string $dsn Root path for file config or DB connection string
     * @return Client
     */
    public static function build(
        string $dsn,
    ): Client {
        $parsedDsn = self::parseDsn($dsn);

        $provider = match ($parsedDsn['scheme']) {
            Scheme::Filesystem => new FileProvider($parsedDsn['path']),
        };

        return new Client(
            $provider,
            new ParserFactory($parsedDsn['scheme']),
        );
    }

    /**
     * @throws InvalidConfigurationException
     */
    private static function parseDsn(string $dsn): array
    {
        list($scheme, $dsnWithoutScheme) = explode('://', $dsn);

        $scheme = Scheme::tryFrom($scheme);
        if (null === $scheme) {
            throw new InvalidConfigurationException('Invalid or unsupported DSN scheme');
        }

        if (empty($dsnWithoutScheme)) {
            throw new InvalidConfigurationException('Invalid or empty DSN path');
        }

        return [
            'scheme' => $scheme,
            'path' => $dsnWithoutScheme,
        ];
    }
}
