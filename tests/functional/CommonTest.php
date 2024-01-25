<?php

declare(strict_types=1);

namespace VMassalov\Config\Tests\Functional;

use PHPUnit\Framework\TestCase;
use VMassalov\Config\ClientFactory;
use VMassalov\Config\ValueObjects\ConfigType;

class CommonTest extends TestCase
{
    public function testBuild(): void
    {
        $client = ClientFactory::build('filesystem://./tests/functional/stubs/');
        $result = $client->find('yaml/baseConfig.yaml', []);
        $this->assertSame([], $result);
    }
}
