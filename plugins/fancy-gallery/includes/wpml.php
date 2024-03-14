<?php

namespace WordPress\Plugin\GalleryManager;

abstract class WPML
{
    public static function init(): void
    {
        add_Filter('gettext_with_context_' . I18n::textdomain, [static::class, 'filterGettextWithContext'], 1, 3);
    }

    public static function isWPMLActive(): bool
    {
        return defined('ICL_SITEPRESS_VERSION');
    }

    public static function filterGettextWithContext(string $translation, string $text, string $context): string
    {
        # If you are using WPML the post type slug MUST NOT be translated! You can translate your slug in WPML directly
        if (static::isWPMLActive() && $context == 'URL slug')
            return $text;
        else
            return $translation;
    }
}

WPML::init();
