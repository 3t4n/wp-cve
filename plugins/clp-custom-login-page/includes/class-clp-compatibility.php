<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
};


class CLP_Compatibility {
    /**
     * All In One WP Security customizer fix
     *
     * @since 1.4.5
     */
    public static function aio_wp_security_customizer_fix() {

        if ( ! is_customize_preview() ){
            return;
        }

        if ( ! class_exists( 'AIO_WP_Security' ) ){
            return;
        }

        global $aio_wp_security;

        if( ! is_a( $aio_wp_security, 'AIO_WP_Security' ) ) {
            return;
        }

        if ( remove_action( 'wp_loaded', array( $aio_wp_security, 'aiowps_wp_loaded_handler' ) ) ) {
            add_filter( 'option_aio_wp_security_configs', array( 'CLP_Compatibility', 'aio_wp_security_filter_options' ) );
        }
    }

    /**
     * Filter options aio_wp_security_configs.
     *
     * @since 1.4.5
     */
    public function aio_wp_security_filter_options( $option ) {
        unset( $option['aiowps_enable_rename_login_page'] );
        return $option;
    }
}