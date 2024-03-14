<?php
namespace WPHR\HR_MANAGER;

/**
 * Administration Settings menu
 */
class Settings {

	/**
     * Kick-in the class
     */
	function __construct() {
		$this->settings_scripts();

        require_once dirname( __FILE__ ) . '/framework/class-settings-page.php';
        require_once dirname( __FILE__ ) . '/framework/class-settings.php';

        include dirname( __FILE__ ) . '/framework/views/settings-page.php';
	}

	/**
     * Get settings scripts
     *
     * @return void
     */
	function settings_scripts() {

        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_media();
        wp_enqueue_script( 'wphr-tiptip' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'wphr-admin-settings' );
    }
}

