<?php

class SarbacaneAbout {

	public function add_admin_menu() {
		add_menu_page(
			__( 'Configuration', 'sarbacane-desktop' ),
			__( 'Sarbacane', 'sarbacane-desktop' ),
			'administrator',
			'sarbacane',
			array(
				$this,
				'display_settings'
			), plugins_url( 'images/favicon_sarbacane.png', __FILE__ )
		);
	}

	public function display_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}
		wp_enqueue_style (
			'sarbacane_global.css',
			plugins_url ( 'css/sarbacane_global.css', __FILE__ ),
			array(),
			'1.4.9'
		);
		wp_enqueue_style (
			'sarbacane_about.css',
			plugins_url ( 'css/sarbacane_about.css', __FILE__ ),
			array(),
			'1.4.9'
		);
		require_once( 'views/sarbacane-about.php' );
	}

}
