<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class I18n
{
    const
        textdomain = 'encyclopedia-lexicon-glossary-wiki-dictionary';

    private static
        $loaded = false;

    public static function loadTextDomain()
    {
        $locale = apply_Filters('plugin_locale', get_Locale(), static::textdomain);
        $language_folder = Core::$plugin_folder . '/languages';

        load_Plugin_TextDomain(static::textdomain);
        load_TextDomain(static::textdomain, "{$language_folder}/{$locale}.mo");

        # Fallback for german
        if (in_Array($locale, ['de_AT_formal', 'de_CH']))
            load_TextDomain(static::textdomain, "{$language_folder}/de_DE_formal.mo");
        elseif (in_Array($locale, ['de_AT', 'de_CH_informal']))
            load_TextDomain(static::textdomain, "{$language_folder}/de_DE.mo");

        static::$loaded = true;
    }

    public static function translate(string $text, string $context = ''): string
    {
        if (apply_filters('encyclopedia_translate', true, $text, $context)) {
            # Load text domain
            if (!static::$loaded) static::loadTextDomain();

            # Translate the string $text with context $context
            if (empty($context))
                return translate($text, static::textdomain);
            else
                return translate_With_GetText_Context($text, $context, static::textdomain);
        } else {
            return $text;
        }
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
