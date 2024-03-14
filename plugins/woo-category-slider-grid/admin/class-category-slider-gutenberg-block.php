<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      1.4.4
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Woo_Category_Slider_Gutenberg_Block' ) ) {

	/**
	 * Custom Gutenberg Block.
	 */
	class Woo_Category_Slider_Gutenberg_Block {

		/**
		 * Block Initializer.
		 */
		public function __construct() {
			require_once SP_WCS_PATH . '/admin/GutenbergBlock/class-woo-category-slider-shortcode-init.php';
			new Woo_Category_Slider_Gutenberg_Block_Init();
		}

	}
}
