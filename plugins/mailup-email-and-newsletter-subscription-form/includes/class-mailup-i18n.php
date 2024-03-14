<?php

declare(strict_types=1);

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.2.6
 *
 * @author     Your Name <email@example.com>
 */
class Mailup_i18n
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.2.6
     */
    public function load_plugin_textdomain(): void
    {
        $path = WPMUP_PLUGIN_NAME.'/languages';
        $result = load_plugin_textdomain(
            'mailup',
            false,
            $path
        );

        if (!$result) {
            $locale = apply_filters('plugin_locale', get_user_locale(), $path);
        }
    }

    public static function getLanguage()
    {
        $locale = is_admin() ? get_user_locale() : get_locale();

        return 'en' !== explode('_', $locale)[0] ? explode('_', $locale)[0] : null;
    }
}
