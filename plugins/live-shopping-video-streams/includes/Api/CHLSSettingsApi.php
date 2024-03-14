<?php

/**
 * File contain all the call back functions..
 * @package  Channelize Shopping
 */

namespace Includes\Api;

defined('ABSPATH') || exit;


use Includes\CHLSCallBacks\CHLSCallBacks;

/**
 * 
 * Al the callbacks funciton are present in callback class
 */


class CHLSSettingsApi extends CHLSCallBacks
{
	public function register()
	{
		$config = get_option('channelize_live_shopping');
		add_action('admin_menu', array($this, 'chls_add_admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'chls_admin_enqueue'));
		add_action('wp_enqueue_scripts', array($this, 'chls_load_ui_kit_js_files'));
		add_filter('theme_page_templates', array($this, 'chls_template_page_for_strems'));
		add_filter('template_include', array($this, 'chls_pt_change_page_template'), 99);
		add_action('init', array($this, 'chls_allow_custom_param_on_stream_page'));
		add_action('init', array($this, 'channelize_live_shop_user_sync_login'));
		add_action('wp_login', array($this, 'channelize_live_shop_user_login'), 10, 2);
		add_action('clear_auth_cookie', array($this, 'channelize_live_shop_user_logout'), 10);
		add_action('user_register', array($this, 'channelize_live_shop_user_register'));
		add_action('profile_update', array($this, 'channelize_live_shop_wp_profile_update'), 10, 2);
		add_action('delete_user', array($this, 'channelize_live_shop_user_delete'));
		add_action('woocommerce_thankyou', array($this, 'channelize_live_shop_sales_analytics'),10 ,1);
	}
}
