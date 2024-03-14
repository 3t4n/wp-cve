<?php
if (!defined('ABSPATH')) {
	exit;
}
use Illuminate\Config\Repository as Shop_Ready_Repository;

/*
 ** All Config file access
 ** Use this file anywhere of this plugin
 */
function shop_ready_app_config()
{
	// memoization cache
	static $mangocube_gl_config = null;
	if (is_null($mangocube_gl_config)) {
		$mangocube_gl_config = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/system/config/app.php');
	}

	return $mangocube_gl_config;
}


/**
 ** shop_ready_sysytem_module_options
 ** Use this file anywhere of this plugin
 *  @version 1.0
 */
function shop_ready_sysytem_module_options_is_active($key = null)
{

	$option = get_option('shop_ready_modules') ? get_option('shop_ready_modules') : [];

	if (isset($option[$key])) {
		return true;
	}

	return false;
}

function shop_ready_sysytem_api_options_is_active($key = null)
{

	$option = get_option('shop_ready_data_api') ? get_option('shop_ready_data_api') : [];

	if (isset($option[$key]) && $option[$key] != '') {
		return $option[$key];
	}

	return false;
}

/*
 ** All WooComerce Templates Config file access
 ** Use this file anywhere of this plugin
 */

function shop_ready_templates_config()
{
	// memoization cache
	static $shop_ready_templates_config = null;
	if (is_null($shop_ready_templates_config)) {

		$shop_templates = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/system/config/dashboard/templates.php');
		$db_opt = get_option('shop_ready_templates') ? get_option('shop_ready_templates') : [];
		$shop_templates_with_presets = [];

		if (empty($db_opt)) {

			$shop_templates_with_presets = array_merge($shop_templates->get('templates'), $db_opt);
		}

		foreach ($shop_templates->get('templates') as $key => $value) {

			if (isset($db_opt[$key])) {
				$shop_templates_with_presets[$key] = array_merge($value, $db_opt[$key]);
			}

		}

		$shop_ready_templates_config = new Shop_Ready_Repository($shop_templates_with_presets);

	}

	return apply_filters('shop_ready_sr_templates_config', $shop_ready_templates_config);
}

if (!function_exists('shop_ready_template_is_active_gl')) {

	function shop_ready_template_is_active_gl($key = null)
	{

		$status = false;
		$sr_templates_config = shop_ready_templates_config();
		$single_template = $sr_templates_config->get($key);

		if (is_array($single_template) && isset($single_template['active']) && isset($single_template['id'])) {

			if ($single_template['active'] && $single_template['id'] > 0) {
				$status = true;
			}
		}

		return $status;
	}

}

/*
 ** All Base css js Config file access
 ** Use this file anywhere of this plugin
 */
function shop_ready_assets_config()
{
	// memoization cache
	static $mangocube_asset_config = null;
	if (is_null($mangocube_asset_config)) {
		$mangocube_asset_config = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/system/config/assets.php');
	}

	return $mangocube_asset_config;
}

/*
 ** All Base Dashboard  Settings
 ** Use this file anywhere of this plugin
 */
function shop_ready_dashboard_config()
{
	// memoization cache
	static $shop_ready_dashboard_config = null;
	if (is_null($shop_ready_dashboard_config)) {
		$shop_ready_dashboard_config = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/system/config/dashboard/tabs.php');
	}

	return apply_filters('shop_ready_dashboard_config', $shop_ready_dashboard_config);
}

/*
 ** All Base widgets Settings
 ** Use this file anywhere of this plugin
 */
function shop_ready_widgets_config()
{

	// memoization cache
	static $shop_ready_widgets_config = null;

	if (is_null($shop_ready_widgets_config)) {

		$shop_ready_widgets_config = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/system/config/dashboard/widgets.php');

	}

	return apply_filters('shop_ready_system_widgets_config', $shop_ready_widgets_config);
}

/*
 ** All Base moudles Settings
 ** Use this file anywhere of this plugin
 */
function shop_ready_modules_config()
{
	// memoization cache
	static $shop_ready_modules_config = null;
	if (is_null($shop_ready_modules_config)) {

		$shop_ready_modules_config = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/system/config/dashboard/modules.php');

	}

	return apply_filters('shop_ready_system_modules_config', $shop_ready_modules_config);
}


/*
 ** All Base api Settings
 ** Use this file anywhere of this plugin
 */

function shop_ready_api_config()
{
	// memoization cache
	static $shop_ready_api_config = null;
	if (is_null($shop_ready_api_config)) {

		$shop_ready_api = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/system/config/dashboard/api.php');
		$db_data = get_option('shop_ready_data_api') ? get_option('shop_ready_data_api') : [];
		$shop_api_old = array_merge($shop_ready_api->all(), $db_data);
		$shop_ready_api_config = new Shop_Ready_Repository($shop_api_old);

	}

	return apply_filters('shop_ready_api_config', $shop_ready_api_config);
}

if (!function_exists('shop_ready_system_db_option_config')) {

	function shop_ready_system_db_option_config()
	{
		// widget drectoryname+filename
		// memoization cache
		static $shop_ready_system_db_option_config = null;
		if (is_null($shop_ready_system_db_option_config)) {

			$shop_ready_ele = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/system/config/dashboard/modules.php');
			$db_opt = get_option('shop_ready_modules') ? get_option('shop_ready_modules') : [];
			$shop_wd_old = $shop_ready_ele->all();

			if (is_array($db_opt)) {

				foreach ($db_opt as $key => $opt) {

					if (isset($shop_wd_old[$key])) {
						$shop_wd_old[$key]['is_pro'] = false;
					}
				}

			}

			$shop_ready_system_db_option_config = new Shop_Ready_Repository($shop_wd_old);
		}

		return $shop_ready_system_db_option_config;
	}

}