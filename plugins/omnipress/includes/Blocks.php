<?php

/**
 * Main class for handling blocks registration.
 *
 * @package Omnipress
 */

namespace Omnipress;

use Omnipress\Abstracts\BlocksBase;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for handling blocks registration.
 *
 * @since 1.1.0
 */
class Blocks extends BlocksBase {
	/**
	 * {@inheritDoc}
	 */
	public static function get_dirpath() {
		return OMNIPRESS_PATH . 'assets/blocks';
	}

	/**
	 * {@inheritDoc}
	 */
	public static function get_blocks() {
		return array(
			'button'       => array(),
			'column'       => array(),
			'container'    => array(),
			'countdown'    => array(),
			'dualButton'   => array(),
			'custom-css'   => array(),
			'heading'      => array(),
			'icons'        => array(),
			'megaMenu'     => array(),
			'menuItem'     => array(),
			'slider'       => array(),
			'slide'       => array(),
			'team'         => array(),
			'tab-labels'         => array(),
			'tabs'         => array(),
			'tabs-content'         => array(),
			'woo-carousel' => array(),
			'wooCategory'  => array(),
			'woocommerce'  => array(),
			'wooGrid'      => array(),
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public static function register_category( $block_categories ) {
		return array_merge(
			array(
				array(
					'slug'  => 'omnipress',
					'title' => __( 'Omnipress', 'omnipress' ),
				),
                array(
					'slug'  => 'omnipress-woo',
					'title' => __( 'Omnipress-Woo', 'omnipress' ),
				),
			),
			$block_categories,
		);
	}

}
