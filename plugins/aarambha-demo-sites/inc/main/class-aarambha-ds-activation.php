<?php

/**
 * The methods defined here is run during this plugin activation.
 * 
 * @since       1.0.0
 * @package     Aarambha_Demo_Sites
 * @subpackage  Aarambha_Demo_Sites/Inc/Core
 */

if (!defined('WPINC')) {
    exit;    // Exit if accessed directly.
}

/**
 * Class Aarambha_DS_Activation
 * 
 * Runs during plugin activation.
 */
class Aarambha_DS_Activation
{
    /**
     * This function is caalled from the core file.
     */
    public static function activate()
    {
        Aarambha_DS_Activation::createDirectory();
    }

    /**
     * Creates the demo directory
     * 
     * @return void.
     */
    private static function createDirectory()
    {
        $uploadsDir = aarambha_ds_get_custom_uploads_dir();

        if (!file_exists($uploadsDir)) {
            wp_mkdir_p(trailingslashit($uploadsDir));

            $content = '';
            $file_name = "{$uploadsDir}/index.html";

            $file_handle = @fopen($file_name, 'w'); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.file_system_read_fopen

            if ($file_handle) {
                fwrite($file_handle, $content); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fwrite

                fclose($file_handle); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fclose
            }
        }
    }
}
