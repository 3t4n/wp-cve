<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The Lion_Badges_Admin class holds the admin functionality.
 */
class Lion_Badges_Admin {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Admin init.
	 */
	public function admin_init() {
	}

	public function admin_enqueue_scripts( $hook ) {
		global $post;

		if ( get_post_type( $post ) != 'lion_badge' )
			return;

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker');

		wp_enqueue_style( 'badges-admin', LION_BADGES_URL . '/admin/assets/css/admin.css' );
		wp_enqueue_script( 'badges-admin', LION_BADGES_URL . '/admin/assets/js/admin.js', array( 'jquery' ), '1.0' );

		//if ( wp_script_is( 'lion-select2', 'enqueued' ) ) {
			wp_enqueue_script( 'lion-select2', LION_BADGES_URL . '/admin/assets/js/select2.min.js', array( 'jquery' ) );
			wp_enqueue_style( 'lion-select2', LION_BADGES_URL . '/admin/assets/css/select2.min.css' );
		//}
	}
}

new Lion_Badges_Admin();
