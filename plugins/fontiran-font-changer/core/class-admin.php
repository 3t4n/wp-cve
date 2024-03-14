<?php

/**
 * Class Fontiran_Admin
 *
 * Manage the admin core functionality
 */
class Fontiran_Admin {

	public $pages = array();

	public function __construct() {
		
		$this->includes();

		// set admin menu
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		
		// set link in plugins page
		add_filter( 'network_admin_plugin_action_links_fontiran/fontiran.php', array( $this, 'add_plugin_action_links' ) );
		add_filter( 'plugin_action_links_fontiran/fontiran.php', array( $this, 'add_plugin_action_links' ) );
		
		// load main css
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}
	
	public function enqueue_scripts() {
		wp_enqueue_style( 'firan-fonts-main', FIRAN_URL . 'assets/css/fi-main.css', array(), FIRAN_VERSION );
	}
	
	public function add_plugin_action_links( $actions ) {
				
		if ( current_user_can( 'manage_options' ) ) {

				$url = 'admin.php?page=wpfi-options';
			$actions['dashboard'] = '<a href="' . $url . '" aria-label="رفتن به برگه پیکربندی">پیکربندی</a>';
		}

		return $actions;
	}



	private function includes() {
		include_once( 'abstract-class-admin-page.php' );
		include_once( 'class-dashboard-page.php' );
		include_once( 'class-upload-page.php' );
		include_once( 'class-fonts-page.php' );
		include_once( 'class-manager-page.php' );
		include_once( 'class-options-page.php' );
	}


	/**
	 * Add all the menu pages in admin for the plugin
	 */
	public function add_menu_pages() {
		
		if ( ! is_multisite() ) {
			$this->pages['wpfi'] = new Fontiran_Dashboard_Page( 'wpfi', 'فونت ایران', 'فونت ایران', false, false );
			$this->pages['wpfi-dashboard'] = new Fontiran_Dashboard_Page( 'wpfi', 'پیشخوان', 'پیشخوان', 'wpfi' );
			$this->pages['wpfi-upload'] = new Fontiran_Upload_Page( 'wpfi-finuf', 'نصب فونت', 'نصب فونت', 'wpfi' );
			$this->pages['wpfi-fonts'] = new Fontiran_Fonts_Page( 'wpfi-fiafi', 'فونت ها', 'فونت ها', 'wpfi' );
			$this->pages['wpfi-manager'] = new Fontiran_Manager_Page( 'wpfi-fimf', 'مدیریت تایپوگرافی', 'مدیریت تایپوگرافی', 'wpfi' );
			$this->pages['wpfi-options'] = new Fontiran_Options_Page( 'wpfi-options', 'پیکربندی', 'پیکربندی', 'wpfi' );
		}
				
	}

}