<?php declare(strict_types=1);

namespace VMassalov\Config\Parsers;

use VMassalov\Config\Exceptions\InvalidResourceException;
use VMassalov\Config\Structure\CaseElement;
use VMassalov\Config\Structure\Condition;
use VMassalov\Config\Structure\ConfigItem;
use VMassalov\Config\Structure\ConfigMap;
use VMassalov\Config\Structure\CriteriaCases;
use VMassalov\Config\Structure\ItemConditions;
use VMassalov\Config\Structure\ItemResult;
use VMassalov\Config\ValueObjects\ConditionName;
use VMassalov\Config\ValueObjects\MatchType;

class Yaml implements ParserInterface
{
    private const CONDITION_KEY = 'conditions';
    private const RESULT_KEY = 'result';

    private const INVERSE_KEYWORD = 'NOT';

    /**
     * @throws InvalidResourceException
     */
    public function parse(string $configData): ConfigMap
    {
        $config = yaml_parse($configData);
        if (false === $config) {
            throw new InvalidResourceException('Unable to parse yaml config');
        }
        if (!is_array($config)) {
            throw new InvalidResourceException('Invalid config format');
        }

        $result = new ConfigMap();
        foreach ($config as $configItem) {
            $this->validateItem($configItem);

            $itemConditions = new ItemConditions();
            foreach ($configItem[self::CONDITION_KEY] as $conditionName => $conditionCriteria) {
                $isInverse = false;
                $criteriaCases = new CriteriaCases();

                if (is_array($conditionCriteria)) {
                    $keys = array_flip(array_keys($conditionCriteria));
                    if (array_key_exists(self::INVERSE_KEYWORD, $keys)) {
                        if (count($conditionCriteria) !== 1) {
                            throw new InvalidResourceException('Disambiguated criteria');
                        }
                        $isInverse = true;
                        $conditionCriteria = $conditionCriteria[self::INVERSE_KEYWORD];
                    }
                }

                $criteriaCases = $this->fillCases($criteriaCases, $conditionCriteria);

                $condition = new Condition(
                    ConditionName::from($conditionName),
                    $criteriaCases,
                    $isInverse,
                );

                $itemConditions->add($condition);
            }

            $itemModel = new ConfigItem(
                $itemConditions,
                new ItemResult($configItem[self::RESULT_KEY]),
            );
            $result->addItem($itemModel);
        }

        return $result;
    }

    private function fillCases(CriteriaCases $cases, mixed $value, MatchType $matchType = null): CriteriaCases
    {
        if (is_array($value)) {
            foreach ($value as $key => $element) {
                if (is_string($key)) {
                    $matchType = MatchType::tryFrom($key);
                }
                $cases = $this->fillCases($cases, $element, $matchType ?? MatchType::Equal);
            }
        } else {
            $cases->add(new CaseElement($value, $matchType ?? MatchType::Equal));
        }

        return $cases;
    }

    /**
     * @throws InvalidResourceException
     */
    private function validateItem(mixed $configItem): void
    {
        if (!is_array($configItem)) {
            throw new InvalidResourceException('Invalid config item structure');
        }
        if (!array_key_exists(self::CONDITION_KEY, $configItem)) {
            throw new InvalidResourceException('Missing item conditions');
        }
        if (!array_key_exists(self::RESULT_KEY, $configItem)) {
            throw new InvalidResourceException('Missing item result');
        }
    }
}
