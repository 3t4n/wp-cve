<?php

declare (strict_types=1);
namespace WpifyWooDeps\h4kuna\Memoize;

final class Helpers
{
    private static bool $checked = \false;
    private static bool $checkedStatic = \false;
    /**
     * @param string|int|float|array<string|int|float> $key - keyType
     */
    public static function resolveKey($key) : string
    {
        if (\is_array($key)) {
            return \implode("\x00", $key);
        }
        return (string) $key;
    }
    /**
     * This is only for tests
     */
    public static function bypassMemoize() : void
    {
        self::bypassMemoizeObject();
        self::bypassMemoizeStatic();
    }
    public static function bypassMemoizeObject() : void
    {
        if (self::$checked === \true) {
            return;
        } elseif (\trait_exists(MemoryStorage::class, \false)) {
            throw new \RuntimeException(MemoryStorage::class . ' already loaded, you must call bypass before first use.');
        }
        self::$checked = \true;
        self::bypass(\false);
    }
    public static function bypassMemoizeStatic() : void
    {
        if (self::$checkedStatic === \true) {
            return;
        } elseif (\trait_exists(MemoryStorageStatic::class, \false)) {
            throw new \RuntimeException(MemoryStorageStatic::class . ' already loaded, you must call bypass before first use.');
        }
        self::$checkedStatic = \true;
        self::bypass(\true);
    }
    private static function bypass(bool $static) : void
    {
        if ($static) {
            $className = 'MemoryStorageStatic';
            $staticWord = ' static';
        } else {
            $className = 'MemoryStorage';
            $staticWord = '';
        }
        eval(<<<TRAIT
namespace h4kuna\\Memoize;

trait {$className}
{

\tfinal protected{$staticWord} function memoize(\$key, callable \$callback)
\t{
\t\treturn \$callback();
\t}

}
TRAIT
);
    }
}
