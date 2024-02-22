<?php declare(strict_types=1);

namespace VMassalov\Config\Tests\Functional;

use PHPUnit\Framework\TestCase;
use VMassalov\Config\ClientFactory;

class CommonTest extends TestCase
{
    public function testBuild(): void
    {
        $client = ClientFactory::build('filesystem://./tests/functional/stubs/');
        $result = $client->find(
            'yaml/baseConfig.yaml',
            [
                'conditionA' => 'A',
                'conditionB' => 'B2',
                'conditionC' => 'notC',
                'conditionD' => 'D1starts',
                'conditionE' => 'notE2',
                'conditionF' => 'Fstarts',
                'conditionG' => 'G1starts',
            ]
        );
        $this->assertSame(
            [
                'resultX' => 'X',
                'resultY' => 'Y',
            ],
            $result
        );
    }
}
