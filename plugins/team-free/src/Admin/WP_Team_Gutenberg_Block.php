<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.8
 *
 * @package    WP_Team
 * @subpackage WP_Team/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Team_Gutenberg_Block' ) ) {

	/**
	 * Custom Gutenberg Block.
	 */
	class WP_Team_Gutenberg_Block {

		/**
		 * Block Initializer.
		 */
		public function __construct() {
			new GutenbergBlock\WP_Team_Gutenberg_Block_Init();
		}

	}
}
