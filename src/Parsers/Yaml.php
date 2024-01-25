<?php declare(strict_types=1);

namespace VMassalov\Config\Parsers;

use VMassalov\Config\Exceptions\InvalidResourceException;
use VMassalov\Config\Structure\ConfigItem;
use VMassalov\Config\Structure\ConfigMap;
use VMassalov\Config\Structure\ItemResult;

class Yaml implements ParserInterface
{
    private const CONDITION_KEY = 'conditions';
    private const RESULT_KEY = 'result';

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

            $itemModel = new ConfigItem(
                new ItemResult($configItem[self::RESULT_KEY]),
            );
            $result->addItem($itemModel);
        }

        return $result;
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
