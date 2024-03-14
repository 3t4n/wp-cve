<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://digitalapps.com
 * @since      1.0.0
 *
 * @package    AdUnblocker
 * @subpackage AdUnblocker/includes
 */

class AdUnblocker_Deactivator {

    public static function deactivate() {

        $plugin_name = 'adunblocker';
        $option_name = 'adunblocker-options';

        if( is_admin() ) {

            $settings = get_option( $option_name );
            $wp_upload_dir = wp_upload_dir();

            if ( file_exists( $wp_upload_dir['basedir'] . '/' . $settings[$plugin_name . '-file-name'] . '.css' ) ) {
                unlink( $wp_upload_dir['basedir'] . '/' . $settings[$plugin_name . '-file-name'] . '.css' );
            }

            if ( file_exists( $wp_upload_dir['basedir'] . '/' . $settings[$plugin_name . '-file-name'] . '.js' ) ) {
                unlink( $wp_upload_dir['basedir'] . '/' . $settings[$plugin_name . '-file-name'] . '.js' );
            }

            delete_option( $option_name );
            delete_option( $option_name . '-install-date' );
            delete_option( $option_name . '-review-notify' );
        }
    }

}
