<?php
if (!defined('ABSPATH')) {
	exit;
}
use Illuminate\Config\Repository as Shop_Ready_Repository;

if (!function_exists('shop_ready_elementor_meta_config')) {
	/** 
	 ** All Config file access
	 ** Use this function only elewidgets extension
	 ** return array object
	 */
	function shop_ready_elementor_meta_config()
	{

		// memoization cache
		static $mangocube_shortcode_config = null;
		if (is_null($mangocube_shortcode_config)) {
			$mangocube_shortcode_config = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/extension/elewidgets/config/meta.php');
		}

		return $mangocube_shortcode_config;
	}

}


if (!function_exists('shop_ready_elementor_component_config')) {
	/**
	 ** All Component Config file access
	 ** Use this function only elewidgets extension
	 * @return array object
	 * @since 1.0
	 */
	function shop_ready_elementor_component_config()
	{
		// widget drectoryname+filename
		// memoization cache
		static $shop_ready_ele_component = null;
		if (is_null($shop_ready_ele_component)) {

			$shop_ready_ele = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/extension/elewidgets/config/widgets.php');
			$db_opt = get_option('shop_ready_components') ? get_option('shop_ready_components') : [];
			$shop_wd_old = $shop_ready_ele->all();

			if (is_array($db_opt)) {

				foreach ($db_opt as $key => $opt) {

					if (isset($shop_wd_old[$key])) {
						$shop_wd_old[$key]['show_in_panel'] = true;
					}
				}

			}

			$shop_ready_ele_component = new Shop_Ready_Repository($shop_wd_old);

		}

		return $shop_ready_ele_component;
	}

}

if (!function_exists('shop_ready_elementor_blacklist_component_config')) {
	/**
	 ** All Component Config file access
	 ** Use this function only elewidgets extension
	 * @return array object
	 * @since 1.0
	 */
	function shop_ready_elementor_blacklist_component_config()
	{
		// widget drectoryname+filename
		// memoization cache
		static $shop_ready_ele_component = null;
		if (is_null($shop_ready_ele_component)) {

			$shop_ready_ele_component = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/extension/elewidgets/config/blacklist-widgets.php');

		}

		return $shop_ready_ele_component;
	}

}



if (!function_exists('shop_ready_product_meta_config')) {
	/** 
	 ** All Component Config file access
	 ** Use this function only elewidgets extension
	 **@return array object
	 */
	function shop_ready_product_meta_config()
	{

		static $shop_ready_product_component = null;
		if (is_null($shop_ready_product_component)) {
			$shop_ready_product_component = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/extension/elewidgets/config/product.php');
		}

		return $shop_ready_product_component;
	}

}

/*
 ** All Elementor Base css js Config file access
 ** Use this file Only in This extension
 */
function shop_ready_elewidget_assets_config()
{
	// memoization cache
	static $woo_g_assets_config = null;
	if (is_null($woo_g_assets_config)) {
		$woo_g_assets_config = new Shop_Ready_Repository(require SHOP_READY_DIR_PATH . 'src/extension/elewidgets/config/assets.php');
	}


	// echo "<pre>";
	// var_dump($woo_g_assets_config);
	// echo "<pre>";
	// exit;
	return $woo_g_assets_config;


}