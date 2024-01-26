<?php declare(strict_types=1);

namespace VMassalov\Config\ValueObjects;

enum MatchType: string
{
    case Equal = 'equal';
    case StringStartsWith = 'stringStartsWith';
    case Pcre = 'pcre';
}
