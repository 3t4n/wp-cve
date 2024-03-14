<?php

/**
 * Fires during plugin deactivation
 *
 * @link       https://www.jssor.com
 * @since      1.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

class WP_Jssor_Slider_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

        #region no crons since 3.1.0

        $timestamp = wp_next_scheduled( 'wjssl_check_slider_files_hook' );

        if($timestamp !== false) {
            wp_unschedule_event( $timestamp, 'wjssl_check_slider_files_hook' );
        }

        #endregion
	}

}
