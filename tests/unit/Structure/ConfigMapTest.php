<?php

declare(strict_types=1);

namespace VMassalov\Config\Tests\Unit\Structure;

use PHPUnit\Framework\TestCase;
use VMassalov\Config\Structure\ConfigItem;
use VMassalov\Config\Structure\ConfigMap;

class ConfigMapTest extends TestCase
{
    public function testFirstMatch(): void
    {
        $criteria = ['criteria' => 'value'];

        $configItem1 = $this->createMock(ConfigItem::class);
        $configItem1->expects($this->once())
            ->method('isMatchCriteria')
            ->with($criteria)
            ->willReturn(true);

        $configItem2 = $this->createMock(ConfigItem::class);
        $configItem2->expects($this->never())
            ->method('isMatchCriteria');

        $configMap = new ConfigMap($configItem1, $configItem2);

        $result = $configMap->findMatch($criteria);
        $this->assertSame($configItem1, $result);
    }

    public function testSecondMatch(): void
    {
        $criteria = ['criteria' => 'value'];
        $configMap = new ConfigMap();

        $configItem1 = $this->createMock(ConfigItem::class);
        $configItem1->expects($this->once())
            ->method('isMatchCriteria')
            ->with($criteria)
            ->willReturn(false);
        $configMap->addItem($configItem1);

        $configItem2 = $this->createMock(ConfigItem::class);
        $configItem2->expects($this->once())
            ->method('isMatchCriteria')
            ->with($criteria)
            ->willReturn(true);
        $configMap->addItem($configItem2);

        $result = $configMap->findMatch($criteria);
        $this->assertSame($configItem2, $result);
    }

    public function testNoMatch(): void
    {
        $criteria = ['criteria' => 'value'];
        $configMap = new ConfigMap();

        $configItem1 = $this->createMock(ConfigItem::class);
        $configItem1->expects($this->once())
            ->method('isMatchCriteria')
            ->with($criteria)
            ->willReturn(false);
        $configMap->addItem($configItem1);

        $configItem2 = $this->createMock(ConfigItem::class);
        $configItem2->expects($this->once())
            ->method('isMatchCriteria')
            ->with($criteria)
            ->willReturn(false);
        $configMap->addItem($configItem2);

        $result = $configMap->findMatch($criteria);
        $this->assertNull($result);
    }
}
