<?php

declare(strict_types=1);

namespace VMassalov\Config\Tests\Unit\Structure;

use PHPUnit\Framework\TestCase;
use VMassalov\Config\Structure\ItemResult;

class ItemResultTest extends TestCase
{
    public function testBuildFromString(): void
    {
        $itemResult = new ItemResult('string');
        $this->assertEquals(['string'], $itemResult->data);
    }

    public function testBuildFromArray(): void
    {
        $array = ['result'];
        $itemResult = new ItemResult($array);
        $this->assertSame($array, $itemResult->data);
    }
}
