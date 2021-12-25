<?php

namespace AdDirector\GPT;

trait HasGlobalSizes
{
    protected static array $sizes = [];

    /**
     * @param  array  $sizes
     * @return class-string
     */
    public static function setSizes(array $sizes): string
    {
        self::$sizes = $sizes;

        return static::class;
    }

    public static function clearSizes(): string
    {
        self::$sizes = [];

        return static::class;
    }

    public static function getSizes(): array
    {
        return self::$sizes;
    }

    /**
     * @return string|static
     */
    public static function addSize(string $name, Size $size): string
    {
        self::$sizes[$name] = $size;

        return static::class;
    }

    public static function getSize(string $name, ?Size $default = null): ?Size
    {
        return self::$sizes[$name] ?? $default;
    }
}
