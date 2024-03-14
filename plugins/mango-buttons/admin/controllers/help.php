<?php

/*
* mbp_help
*
* @description: conroller for mango buttons settings sub menu page
*
*/

class mbp_help{

	var $action;

	function __construct(){
		add_action('admin_menu', array($this, 'admin_menu'));
	}

	function admin_menu(){
		$page = add_submenu_page('mangobuttons', 'Help', 'Help', 'manage_options', 'mangobuttons-help', array($this, 'html') );
	}

	//echo out the settings view (html file) file when loading the bars admin page
	function html(){
		echo file_get_contents(MB_PLUGIN_PATH . 'admin/views/help.html');

		//enqueue scripts for this view
		$this->enqueue_scripts_for_view();

	}

	function enqueue_scripts_for_view(){

		wp_enqueue_script('mb-settings', MB_PLUGIN_URL . 'admin/js/help.js', array('jquery', 'knockout', 'underscore'), microtime(), true);
		wp_localize_script('mb-settings', 'MB_GLOBALS', array( 'MB_ADMIN_NONCE' => wp_create_nonce('mb_admin_nonce') ));

		wp_localize_script('mb-settings', 'mb_settings', array(
			'email' => wp_get_current_user()->user_email,
			'fname' => wp_get_current_user()->user_firstname,
			'subscribed' => get_option('mb_subscribed'),
			'website' => get_site_url()
		) );

	}
}

new mbp_help();

?>
