<?php
namespace PHPF\WP\Page;

/**
 * Enqueue and show notices after redirect
 *
 * @author  Petr Stastny <petr@stastny.eu>
 * @license GPLv3
 */
class Notices
{
    /**
     * Notices init, called from bootstrap
     *
     * @return void
     */
    public static function init()
    {
        add_action('wp_logout', function () { self::clearNotices(); });
        add_action('wp_login', function () { self::clearNotices(); });
        add_action('admin_init', function () { self::printAll(); });
    }


    /**
     * Get notices option key
     *
     * @return string
     */
    private static function getOptionKey()
    {
        return 'phpfwp_notices_'.get_current_user_id();
    }


    /**
     * Read notices queue
     *
     * @return array
     */
    private static function readNotices()
    {
        $notices = get_option(self::getOptionKey());
        if (!is_array($notices)) {
            return [];
        }

        return $notices;
    }


    /**
     * Clear notices queue
     *
     * @return void
     */
    private static function clearNotices()
    {
        delete_option(self::getOptionKey());
    }


    /**
     * Enqueue notice
     *
     * @param string $class notice class
     * @param string $message notice message
     * @return void
     */
    public static function add($class, $message)
    {
        $notices = self::readNotices();

        $notices[] = [
            'class' => $class,
            'message' => $message,
        ];

        update_option(self::getOptionKey(), $notices, false);
    }


    /**
     * Print all queued notices and clear queue
     *
     * @return void
     */
    private static function printAll()
    {
        $notices = self::readNotices();

        if (!count($notices)) {
            return;
        }

        foreach ($notices as $notice) {
            self::printNotice($notice['class'], $notice['message']);
        }

        self::clearNotices();
    }


    /**
     * Print notice
     *
     * @param string $class notice class
     * @param string $message notice message
     * @return void
     */
    public static function printNotice($class, $message)
    {
        add_action('admin_notices', function() use ($class, $message) {
            echo '<div class="notice '.esc_attr($class).'"><p>'.esc_html($message).'</p></div>';
        });
    }
}
