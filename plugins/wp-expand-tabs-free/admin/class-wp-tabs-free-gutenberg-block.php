<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.5
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Tabs_Free_Gutenberg_Block' ) ) {
	/**
	 * Custom Gutenberg Block.
	 */
	class WP_Tabs_Free_Gutenberg_Block {
		/**
		 * Block Initializer.
		 */
		public function __construct() {
			require_once WP_TABS_PATH . 'admin/GutenbergBlock/class-wp-tabs-free-gutenberg-block-init.php';
			new WP_Tabs_Free_Gutenberg_Block_Init();
		}
	}
}
