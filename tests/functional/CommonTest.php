<?php declare(strict_types=1);

namespace VMassalov\Config\Tests\Functional;

use PHPUnit\Framework\TestCase;
use VMassalov\Config\ClientFactory;

class CommonTest extends TestCase
{
    public function testRun(): void
    {
        $client = ClientFactory::build('filesystem://./tests/functional/stubs/');
        $result = $client->find(
            'yaml/baseConfig.yaml',
            [
                'conditionA' => 'A',
                'conditionB' => 'B2',
                'conditionC' => 'notC',
                'conditionD' => 'D1Starts',
                'conditionE' => 'notE2',
                'conditionF' => 'FStarts',
                'conditionG' => 'G1Starts',
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
