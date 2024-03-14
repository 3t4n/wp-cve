<?php
defined( 'ABSPATH' ) || exit;

/**
 * Admin class for Performers related features
 */

class EventM_Performers_Admin {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		// add banner
		add_action( 'load-edit.php', function(){
			$screen = get_current_screen();
			if( 'edit-em_performer' === $screen->id ) {
				add_action( 'admin_footer', function(){
					do_action( 'ep_add_custom_banner' );
				});
			}
		});
	}

	/**
	 * Includes performer related admin files
	 */
	public function includes() {
		// Meta Boxes
		include_once __DIR__ . '/meta-boxes/class-ep-performer-admin-meta-boxes.php';
	}
}

new EventM_Performers_Admin();