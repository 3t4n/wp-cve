<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      2.5.4
 * @package    woo-product-slider-free.
 * @subpackage woo-product-slider-free/Admin.
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WooProductSlider\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Custom Gutenberg Block.
 */
class Gutenberg_Block {

	/**
	 * Block Initializer.
	 */
	public function __construct() {
		new GutenbergBlock\Gutenberg_Init();
	}
}
