<?php

/*
* mb_settings
*
* @description: conroller for mango buttons settings sub menu page
*
*/

class mb_settings{

	var $action;

	function __construct(){
		add_action('admin_menu', array($this, 'admin_menu'));
	}

	function admin_menu(){
		//note mangobuttons not mangobuttons-settings. This is the options page
		$page = add_submenu_page('mangobuttons', 'Settings', 'Settings', 'manage_options', 'mangobuttons', array($this, 'html') );
	}

	function subscribe_to_email_list($email_address){
		update_option('mb_subscribed', true);

		/*Subscribe via Mailchimp*/

		return true;
	}

	/*Save the user's mango buttons settings from the settings page*/
	static function save_settings($settings, $format = 'php'){

		if(isset($settings['email'])){
			update_option('mb_email', $settings['email']);
		}
		if(isset($settings['subscribed'])){
			update_option('mb_subscribed', $settings['subscribed']);
		}
		if(isset($settings['icon_color'])){
			update_option('mb_icon_color', $settings['icon_color']);
		}
		if(isset($settings['extended_language_support'])){
			update_option('mb_extended_language_support', $settings['extended_language_support']);
		}

		$result = true;

		if($format == 'json'){
			return json_encode($result);
		}
		else{
			return $result;
		}

	}

	static function destroy_plugin_data(){
		mb()->deactivateAndDestroyMBData();

		return true;
	}

	//echo out the settings view (html file) file when loading the bars admin page
	function html(){
		echo file_get_contents(MB_PLUGIN_PATH . 'admin/views/settings.html');

		//enqueue scripts for this view
		$this->enqueue_scripts_for_view();

	}

	function enqueue_scripts_for_view(){

		wp_enqueue_script('mb-settings', MB_PLUGIN_URL . 'admin/js/settings.js', array('jquery', 'knockout', 'underscore'), microtime(), true);
		wp_localize_script('mb-settings', 'MB_GLOBALS', array( 'MB_ADMIN_NONCE' => wp_create_nonce('mb_admin_nonce') ));

		wp_localize_script('mb-settings', 'mb_settings', array(
			'email' => wp_get_current_user()->user_email,
			'fname' => wp_get_current_user()->user_firstname,
			'subscribed' => get_option('mb_subscribed'),
			'icon_color' => get_option('mb_icon_color'),
			'extended_language_support' => get_option('mb_extended_language_support'),
			'website' => get_site_url()
		) );

	}
}

new mb_settings();

?>
