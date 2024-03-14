<?php

defined( 'ABSPATH' ) or die();

class wlcm_frontend_scripts
{
    public static function frontend_enqueue_assets() {
        wp_enqueue_script( 'subscribe_front_ajax', WL_COMPANION_PLUGIN_URL . 'public/js/travelogged-custom.js', array( 'jquery' ), true, true );
		wp_localize_script( 'subscribe_front_ajax', 'ajax_subscribe', array(
			'ajax_url'        => admin_url( 'admin-ajax.php' ),
			'subscribe_nonce' => wp_create_nonce( 'subscribe_ajax_nonce' ),
		) );
    }
}
