<?php

namespace WordPress\Plugin\GalleryManager;

abstract class I18n
{
    const
        textdomain = 'gallery-manager';

    private static
        $loaded = false;

    public static function loadTextDomain(): void
    {
        $locale = apply_Filters('plugin_locale', get_Locale(), static::textdomain);
        $language_folder = Core::$plugin_folder . '/languages';

        load_Plugin_TextDomain(static::textdomain);
        load_TextDomain(static::textdomain, "{$language_folder}/{$locale}.mo");

        static::$loaded = true;
    }

    public static function translate(string $text, string $context = ''): string
    {
        # Load text domain
        if (!static::$loaded) static::loadTextDomain();

        # Translate the string $text with context $context
        if (empty($context))
            return translate($text, static::textdomain);
        else
            return translate_With_GetText_Context($text, $context, static::textdomain);
    }

    public static function t(string $text, string $context = ''): string
    {
        return static::translate($text, $context);
    }

    public static function __(string $text): string
    {
        return static::translate($text);
    }

    public static function _e(string $text): void
    {
        echo static::translate($text);
    }

    public static function _x(string $text, string $context): string
    {
        return static::translate($text, $context);
    }

    public static function _ex(string $text, string $context): void
    {
        echo static::translate($text, $context);
    }
}
