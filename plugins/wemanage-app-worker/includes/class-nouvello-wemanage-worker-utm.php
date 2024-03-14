<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Nouvello WeManage Worker Utm Class
 */
class Nouvello_WeManage_Worker_Utm
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->includes();
		add_action('wp_enqueue_scripts', array($this, 'nouvello_utm_enqueue'));
		add_action('wp_ajax_nopriv_nouvello_utm_view', array($this, 'ajax_nouvello_utm_view'));
		add_action('woocommerce_loaded', 'Nouvello_WeManage_Utm_WooCommerce::register_hooks');
		add_action('init',  array($this, 'init'), 9);
	}

	public function includes()
	{
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/class-nouvello-wemanage-utm-session.php';
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/class-nouvello-wemanage-utm-service.php';
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/class-nouvello-wemanage-utm-util.php';
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/class-nouvello-wemanage-utm-wordpress-user.php';
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/class-nouvello-wemanage-utm-settings.php';
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/class-nouvello-wemanage-utm-woocommerce.php';
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/class-nouvello-wemanage-utm-conversion.php';
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/class-nouvello-wemanage-utm-html.php';

		// Contact Form 7 Support
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/forms/cf7/class-nouvello-wemanage-utm-cf7-form.php';
		require NSWMW_ROOT_PATH . '/includes/utm-tracker/forms/cf7/class-nouvello-wemanage-utm-cf7-session.php';
	}

	public static function init()
	{
		$network_settings = array();
		Nouvello_WeManage_Utm_User::init(array(
			'global_user_option' => !empty($network_settings['cookie_domain']) ? true : false
		));
		$site_settings = Nouvello_WeManage_Utm_Settings::get();
		Nouvello_WeManage_Utm_Service::init(array(
			'site_settings' => $site_settings
		));
	}

	public function nouvello_utm_enqueue()
	{
		$enqueue_settings = Nouvello_WeManage_Utm_Settings::get_enqueue_settings();

		wp_register_script('nouvello-utm-tracker', NSWMW_ROOT_DIR . '/includes/assets/js/utm-tracker.min.js', array('jquery'), 1);
		wp_localize_script(
			'nouvello-utm-tracker',
			'nouvello_utm_tracker',
			$enqueue_settings,
		);
		wp_enqueue_script('nouvello-utm-tracker');
	}


	public static function ajax_nouvello_utm_view()
	{

		$user_id = get_current_user_id();

		$user_synced_session = Nouvello_WeManage_Utm_Service::get_user_synced_session($user_id);
		if (Nouvello_WeManage_Utm_Settings::get('active_attribution')) :
			Nouvello_WeManage_Utm_User::update_active_session($user_id, $user_synced_session);
		else :
			Nouvello_WeManage_Utm_User::delete_active_session($user_id);
		endif;
		wp_send_json(array(
			'code' => 'SUCCESS',
			'message' => 'Nouvello UTM'
		), 200);
	}
}
