<?php
if (!defined('ABSPATH')) {
	exit;
}
use Illuminate\Config\Repository as Shop_Ready_Repository;

/*
 ** All Config file access
 ** Use this function only generalwidgets extension
 ** return array object
 */

if (!function_exists('shop_ready_genwidget_meta_config')) {

	function shop_ready_genwidget_meta_config()
	{

		// memoization cache
		static $wr_gen_shortcode_config = null;
		if (is_null($wr_gen_shortcode_config)) {
			$wr_gen_shortcode_config = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/config/meta.php');
		}

		return $wr_gen_shortcode_config;
	}

}

/*
 ** All Widget Config file access
 ** Use this function only generalwidgets extension
 ** return array object
 */

if (!function_exists('shop_ready_genwidget_config')) {

	function shop_ready_genwidget_config()
	{
		// widget drectoryname+filename
		// memoization cache
		static $woo_ready_ele_gen_widget = null;
		if (is_null($woo_ready_ele_gen_widget)) {

			$shop_ready_ele = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/config/widgets.php');
			$db_opt = get_option('shop_ready_components') ? get_option('shop_ready_components') : [];
			$shop_wd_old = $shop_ready_ele->all();

			if (is_array($db_opt)) {

				foreach ($db_opt as $key => $opt) {

					if (isset($shop_wd_old[$key])) {
						$shop_wd_old[$key]['show_in_panel'] = true;
					}
				}

			}

			$woo_ready_ele_gen_widget = new Shop_Ready_Repository($shop_wd_old);
		}

		return $woo_ready_ele_gen_widget;
	}

}

/*
 ** All Elementor Base css js Config file access
 ** Use this file Only in This extension
 */
function shop_ready_genwidget_assets_config()
{
	// memoization cache
	static $woo_gen_assets_config = null;
	if (is_null($woo_gen_assets_config)) {
		$woo_gen_assets_config = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/extension/generalwidgets/config/assets.php');
	}

	return $woo_gen_assets_config;
}