<?php

declare(strict_types=1);

namespace VMassalov\Config\Tests\Unit\Structure;

use PHPUnit\Framework\TestCase;
use VMassalov\Config\Exceptions\InvalidResourceException;
use VMassalov\Config\Structure\CaseElement;
use VMassalov\Config\Structure\Condition;
use VMassalov\Config\Structure\ConfigItem;
use VMassalov\Config\Structure\CriteriaCases;
use VMassalov\Config\Structure\ItemConditions;
use VMassalov\Config\Structure\ItemResult;
use VMassalov\Config\ValueObjects\ConditionName;

class ConfigItemTest extends TestCase
{
    public function testMatch(): void
    {
        $testValue = 'value';

        $condition = $this->getMockBuilder(Condition::class)
            ->setConstructorArgs([
                ConditionName::from('conditionName'),
                new CriteriaCases($this->createMock(CaseElement::class)),
            ])
            ->getMock();
        $condition->expects($this->once())
            ->method('isValueMatch')
            ->with($testValue)
            ->willReturn(true);

        $configItem = new ConfigItem(
            new ItemConditions(
                $condition,
            ),
            new ItemResult('result'),
        );
        $result = $configItem->isMatchCriteria([
            'conditionName' => $testValue,
        ]);
        $this->assertTrue($result);
    }

    public function testNotMatch(): void
    {
        $testValue = 'value';

        $condition = $this->getMockBuilder(Condition::class)
            ->setConstructorArgs([
                ConditionName::from('conditionName'),
                new CriteriaCases($this->createMock(CaseElement::class)),
            ])
            ->getMock();
        $condition->expects($this->once())
            ->method('isValueMatch')
            ->with($testValue)
            ->willReturn(false);

        $configItem = new ConfigItem(
            new ItemConditions(
                $condition,
            ),
            new ItemResult('result'),
        );
        $result = $configItem->isMatchCriteria([
            'conditionName' => $testValue,
        ]);
        $this->assertFalse($result);
    }

    public function testMissingCriteria(): void
    {
        $testValue = 'value';

        $condition = $this->getMockBuilder(Condition::class)
            ->setConstructorArgs([
                ConditionName::from('conditionName'),
                new CriteriaCases($this->createMock(CaseElement::class)),
            ])
            ->getMock();
        $condition->expects($this->never())
            ->method('isValueMatch');

        $configItem = new ConfigItem(
            new ItemConditions(
                $condition,
            ),
            new ItemResult('result'),
        );
        $result = $configItem->isMatchCriteria([
            'otherConditionName' => $testValue,
        ]);
        $this->assertFalse($result);
    }

    public function testEmptyConditions(): void
    {
        $this->expectException(InvalidResourceException::class);
        $configItem = new ConfigItem(
            new ItemConditions(),
            new ItemResult('result'),
        );
    }
}
