<?php

namespace Baqend\WordPress;

/**
 * The class containing information about the plugin.
 */
class Info {

    /**
     * The plugin slug.
     *
     * @var string
     */
    const SLUG = 'baqend';

    /**
     * The plugin version.
     *
     * @var string
     */
    const VERSION = '2.0.1';

    /**
     * The URL where your update server is located (uses wp-update-server).
     *
     * @var string
     */
    const UPDATE_URL = 'https://www.baqend.com/guide/topics/wordpress/';

    /**
     * Retrieves the plugin title from the main plugin file.
     *
     * @return string The plugin title
     */
    public static function get_plugin_title() {
        $path = self::get_plugin_directory() . '/' . self::SLUG . '.php';

        return get_plugin_data( $path )['Name'];
    }

    /**
     * @return string
     */
    public static function get_plugin_directory() {
        return realpath( plugin_dir_path( __DIR__ . '/../..' ) );
    }

    /**
     * @return string
     */
    public static function get_plugin_file() {
        return basename( dirname( dirname( __FILE__ ) ) ) . '/baqend.php';
    }
}
