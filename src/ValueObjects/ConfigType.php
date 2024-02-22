<?php

declare(strict_types=1);

namespace VMassalov\Config\ValueObjects;

enum ConfigType
{
    case Json;
    case Xml;
    case Yaml;
}
