<?php
/**
 * This class is responsible for all override IdealoTemplate template.
 *
 * @package    CTXFeed
 */

namespace CTXFeed\V5\Override;

/**
 * Class IdealoTemplate
 *
 * @package    CTXFeed\V5\Override
 */
class IdealoTemplate {

	public function __construct() {
		add_filter( 'ctx_feed_number_format', array( $this, 'ctx_feed_idealo_number_format' ) );

		add_filter( 'woo_feed_filter_product_images', array( $this, 'woo_feed_filter_product_images_callback' ) );
	}

	/**
	 * Modify number format as per idealo feed.
	 *
	 *
	 * @return array
	 */
	public function ctx_feed_idealo_number_format() {
		return array(
			'decimals'           => '2',
			'decimal_separator'  => '.',
			'thousand_separator' => '',
		);
	}

	/**
	 * Modify product images as per idealo feed.
	 *
	 * @param string $images product images.
	 * @return string
	 */
	public function woo_feed_filter_product_images_callback( $images ) {
		return str_replace( ',', ';', $images );
	}

}
