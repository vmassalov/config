<?php

declare(strict_types=1);

namespace VMassalov\Config\Tests\Unit\Parsers;

use PHPUnit\Framework\TestCase;
use VMassalov\Config\Parsers\Yaml;
use VMassalov\Config\Structure\CaseElement;
use VMassalov\Config\Structure\Condition;
use VMassalov\Config\Structure\ConfigItem;
use VMassalov\Config\Structure\ConfigMap;
use VMassalov\Config\Structure\CriteriaCases;
use VMassalov\Config\Structure\ItemConditions;
use VMassalov\Config\Structure\ItemResult;
use VMassalov\Config\ValueObjects\ConditionName;
use VMassalov\Config\ValueObjects\MatchType;

class YamlTest extends TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testParse(
        string $raw,
        ConfigMap $expectedResult,
    ): void {
        $parser = new Yaml();
        $result = $parser->parse($raw);
        $this->assertEquals($expectedResult, $result);
    }

    public static function parseDataProvider(): \Traversable
    {
        yield 'Simple condition' => [
            'raw' => <<<END
- conditions:
    name: 'value'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value', MatchType::Equal),
                            ]),
                            isInverse: false,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Multiple condition' => [
            'raw' => <<<END
- conditions:
    name:
      - 'value1'
      - 'value2'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value1', MatchType::Equal),
                                new CaseElement('value2', MatchType::Equal),
                            ]),
                            isInverse: false,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Simple inverse condition' => [
            'raw' => <<<END
- conditions:
    name:
      NOT: 'value'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value', MatchType::Equal),
                            ]),
                            isInverse: true,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Multiple inverse condition' => [
            'raw' => <<<END
- conditions:
    name:
      NOT:
       - 'value1'
       - 'value2'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value1', MatchType::Equal),
                                new CaseElement('value2', MatchType::Equal),
                            ]),
                            isInverse: true,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Simple match condition' => [
            'raw' => <<<END
- conditions:
    name:
      stringStartsWith: 'value'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value', MatchType::StringStartsWith),
                            ]),
                            isInverse: false,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Multiple match condition' => [
            'raw' => <<<END
- conditions:
    name:
      stringStartsWith:
        - 'value1'
        - 'value2'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value1', MatchType::StringStartsWith),
                                new CaseElement('value2', MatchType::StringStartsWith),
                            ]),
                            isInverse: false,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Mixed match condition' => [
            'raw' => <<<END
- conditions:
    name:
      - stringStartsWith:
        - 'value1'
        - 'value2'
      - 'value3'
      - 'value4'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value1', MatchType::StringStartsWith),
                                new CaseElement('value2', MatchType::StringStartsWith),
                                new CaseElement('value3', MatchType::Equal),
                                new CaseElement('value4', MatchType::Equal),
                            ]),
                            isInverse: false,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Mixed labeled match condition' => [
            'raw' => <<<END
- conditions:
    name:
      - stringStartsWith:
        - 'value1'
        - 'value2'
      - pcre:
        - 'value3'
        - 'value4'
      - 'value5'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value1', MatchType::StringStartsWith),
                                new CaseElement('value2', MatchType::StringStartsWith),
                                new CaseElement('value3', MatchType::Pcre),
                                new CaseElement('value4', MatchType::Pcre),
                                new CaseElement('value5', MatchType::Equal),
                            ]),
                            isInverse: false,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Mixed inverse match condition' => [
            'raw' => <<<END
- conditions:
    name:
      NOT:
        - stringStartsWith: 'value1'
        - 'value1'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value1', MatchType::StringStartsWith),
                                new CaseElement('value1', MatchType::Equal),
                            ]),
                            isInverse: true,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Many conditions' => [
            'raw' => <<<END
- conditions:
    nameA: 'valueA'
    nameB: 'valueB'
  result: 'res'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('nameA'),
                            new CriteriaCases(...[
                                new CaseElement('valueA', MatchType::Equal),
                            ]),
                            isInverse: false,
                        ),
                        new Condition(
                            ConditionName::from('nameB'),
                            new CriteriaCases(...[
                                new CaseElement('valueB', MatchType::Equal),
                            ]),
                            isInverse: false,
                        )
                    ]),
                    new ItemResult('res'),
                )
            ]),
        ];

        yield 'Multiple result' => [
            'raw' => <<<END
- conditions:
    name: 'value'
  result:
    - 'resultA'
    - 'resultB'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value', MatchType::Equal),
                            ]),
                            isInverse: false,
                        )
                    ]),
                    new ItemResult(['resultA', 'resultB']),
                )
            ]),
        ];

        yield 'Named result' => [
            'raw' => <<<END
- conditions:
    name: 'value'
  result:
    resultA: 'valueA'
    resultB: 'valueB'
END,
            'expectedResult' => new ConfigMap(...[
                new ConfigItem(
                    new ItemConditions(...[
                        new Condition(
                            ConditionName::from('name'),
                            new CriteriaCases(...[
                                new CaseElement('value', MatchType::Equal),
                            ]),
                            isInverse: false,
                        )
                    ]),
                    new ItemResult([
                        'resultA' => 'valueA',
                        'resultB' => 'valueB',
                    ]),
                )
            ]),
        ];
    }
}
