<?php

declare(strict_types=1);

namespace VMassalov\Config\Tests\Unit\Structure;

use PHPUnit\Framework\TestCase;
use VMassalov\Config\Exceptions\InvalidResourceException;
use VMassalov\Config\Structure\CaseElement;
use VMassalov\Config\Structure\Condition;
use VMassalov\Config\Structure\CriteriaCases;
use VMassalov\Config\ValueObjects\ConditionName;

class ConditionTest extends TestCase
{
    public function testMatchOnFirst(): void
    {
        $testValue = 'value';

        $case1 = $this->createMock(CaseElement::class);
        $case1->expects($this->once())
            ->method('isValueMatch')
            ->with($testValue)
            ->willReturn(true);

        $case2 = $this->createMock(CaseElement::class);
        $case2->expects($this->never())
            ->method('isValueMatch');

        $condition = new Condition(
            ConditionName::from('conditionName'),
            new CriteriaCases(
                $case1,
                $case2
            ),
            isInverse: false,
        );

        $result = $condition->isValueMatch($testValue);
        $this->assertEquals(true, $result);
    }

    public function testMatchOnLast(): void
    {
        $testValue = 'value';

        $case1 = $this->createMock(CaseElement::class);
        $case1->expects($this->once())
            ->method('isValueMatch')
            ->with($testValue)
            ->willReturn(false);

        $case2 = $this->createMock(CaseElement::class);
        $case2->expects($this->once())
            ->method('isValueMatch')
            ->with($testValue)
            ->willReturn(true);

        $condition = new Condition(
            ConditionName::from('conditionName'),
            new CriteriaCases(
                $case1,
                $case2
            ),
            isInverse: false,
        );

        $result = $condition->isValueMatch($testValue);
        $this->assertEquals(true, $result);
    }

    public function testNotMatch(): void
    {
        $testValue = 'value';

        $case1 = $this->createMock(CaseElement::class);
        $case1->expects($this->once())
            ->method('isValueMatch')
            ->with($testValue)
            ->willReturn(false);

        $case2 = $this->createMock(CaseElement::class);
        $case2->expects($this->once())
            ->method('isValueMatch')
            ->with($testValue)
            ->willReturn(false);

        $condition = new Condition(
            ConditionName::from('conditionName'),
            new CriteriaCases(
                $case1,
                $case2
            ),
            isInverse: false,
        );

        $result = $condition->isValueMatch($testValue);
        $this->assertEquals(false, $result);
    }

    public function testMatchInversed(): void
    {
        $testValue = 'value';

        $case = $this->createMock(CaseElement::class);
        $case->expects($this->once())
            ->method('isValueMatch')
            ->with($testValue)
            ->willReturn(true);

        $condition = new Condition(
            ConditionName::from('conditionName'),
            new CriteriaCases(
                $case,
            ),
            isInverse: true,
        );

        $result = $condition->isValueMatch($testValue);
        $this->assertEquals(false, $result);
    }

    public function testNotMatchInversed(): void
    {
        $testValue = 'value';

        $case = $this->createMock(CaseElement::class);
        $case->expects($this->once())
            ->method('isValueMatch')
            ->with($testValue)
            ->willReturn(false);

        $condition = new Condition(
            ConditionName::from('conditionName'),
            new CriteriaCases(
                $case,
            ),
            isInverse: true,
        );

        $result = $condition->isValueMatch($testValue);
        $this->assertEquals(true, $result);
    }

    public function testEmptyCases(): void
    {
        $this->expectException(InvalidResourceException::class);
        $condition = new Condition(
            ConditionName::from('conditionName'),
            new CriteriaCases(),
        );
    }
}
