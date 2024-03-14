<?php
/**
 * The plugin premium page.
 *
 * @link       https://shapedplugin.com/
 * @since      1.2.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

/**
 * Woo_Category_Slider_Premium class
 */
class Woo_Category_Slider_Premium {

	/**
	 * Add SubMenu Page
	 */
	public function premium_page() {
		$landing_page = 'https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=115';
		add_submenu_page( 'edit.php?post_type=sp_wcslider', __( 'Category Slider for WooCommerce Premium', 'woo-category-slider-grid' ), '<span class="sp-go-pro-icon" style="font-size: 17px;"></span>Go Pro', 'manage_options', $landing_page );
	}

}
