<?php

declare(strict_types=1);

namespace VMassalov\Config\Tests\Unit\Structure;

use PHPUnit\Framework\TestCase;
use VMassalov\Config\Exceptions\InvalidResourceException;
use VMassalov\Config\Structure\CaseElement;
use VMassalov\Config\ValueObjects\MatchType;

class CaseElementTest extends TestCase
{
    /**
     * @dataProvider matchDataProvider
     */
    public function testMatch(
        CaseElement $element,
        mixed $matchTo,
        bool $expectedResult
    ): void {
        $result = $element->isValueMatch($matchTo);
        $this->assertEquals($expectedResult, $result);
    }

    public static function matchDataProvider(): \Traversable
    {
        yield 'Equal matches string' => [
            new CaseElement('string', MatchType::Equal),
            'string',
            true,
        ];

        yield 'Equal matches int' => [
            new CaseElement(10, MatchType::Equal),
            10,
            true,
        ];

        yield 'Equal matches float' => [
            new CaseElement(1.1, MatchType::Equal),
            1.1,
            true,
        ];

        yield 'Equal matches bool' => [
            new CaseElement(true, MatchType::Equal),
            true,
            true,
        ];

        yield 'Equal type cast' => [
            new CaseElement('10', MatchType::Equal),
            10,
            true,
        ];

        yield 'Equal not matches string' => [
            new CaseElement('string', MatchType::Equal),
            'string1',
            false,
        ];

        yield 'Equal not matches int' => [
            new CaseElement(11, MatchType::Equal),
            12,
            false,
        ];

        yield 'Equal not matches float' => [
            new CaseElement('1.1', MatchType::Equal),
            1.2,
            false,
        ];

        yield 'Equal not matches bool' => [
            new CaseElement(true, MatchType::Equal),
            false,
            false,
        ];

        yield 'String starts matches' => [
            new CaseElement('string', MatchType::StringStartsWith),
            'stringStartsWith',
            true,
        ];

        yield 'String starts not matches' => [
            new CaseElement('string', MatchType::StringStartsWith),
            'startsWithString',
            false,
        ];

        yield 'Pcre matches' => [
            new CaseElement('/\w+/', MatchType::Pcre),
            'word',
            true,
        ];

        yield 'Pcre not matches' => [
            new CaseElement('/\w+/', MatchType::Pcre),
            '.',
            false,
        ];
    }

    /**
     * @dataProvider exceptionDataProvider
     */
    public function testExceptions(
        mixed $value,
        MatchType $matchType,
    ): void {
        $this->expectException(InvalidResourceException::class);
        $test = new CaseElement($value, $matchType);
    }

    public static function exceptionDataProvider(): \Traversable
    {
        yield 'Empty string' => [
            '',
            MatchType::Equal,
        ];

        yield 'Not string on StringStartsWith' => [
            1,
            MatchType::StringStartsWith,
        ];

        yield 'Not string on PCRE' => [
            1,
            MatchType::Pcre,
        ];
    }
}
