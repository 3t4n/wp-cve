<?php
/**
 * This class is responsible for all override facebook template.
 *
 * @package CTXFeed\V5\Override
 */

namespace CTXFeed\V5\Override;

/**
 * Class FacebookTemplate
 *
 * @package CTXFeed\V5\Override
 */
class FacebookTemplate {

	/**
	 * FacebookTemplate class constructor
	 */
	public function __construct() {

		add_filter( 'woo_feed_filter_product_title', array( $this, 'woo_feed_filter_product_title_callback' ), 10, 1 );

		add_filter( 'woo_feed_attribute_separator', array( $this, 'woo_feed_attribute_separator_callback' ), 10, 1 );


		add_filter(
			'woo_feed_filter_product_description_with_html',
			array(
				$this,
				'woo_feed_filter_product_description_callback',
			),
			10,
			1
		);

		add_filter(
			'woo_feed_filter_product_description',
			array(
				$this,
				'woo_feed_filter_product_description_callback',
			),
			10,
			1
		);

		add_filter(
			'woo_feed_filter_product_description',
			array(
				$this,
				'woo_feed_filter_product_variation_add_callback',
			),
			10,
			4
		);

		add_filter(
			'woo_feed_filter_product_availability_date',
			array(
				$this,
				'woo_feed_filter_product_availability_date_callback',
			),
			10,
			1
		);
	}


	/**
	 * Modify Product Title
	 *
	 * @param string $title Product title.
     * @return string
	 */
	public function woo_feed_filter_product_title_callback( $title ) {

		// Google Shopping Product Title Character limit: max 150.
		$title = mb_substr($title, 0, 150, 'UTF-8');

		return $title;
	}

	/**
	 * Set (-) as separator for this merchant.
	 *
	 * @param $separator string attribute separator.
	 *
	 * @return string
	 */
	public function woo_feed_attribute_separator_callback( $separator ) {
		return ' - ';
	}

	/**
	 * Limit Facebook Product Description to 10000 characters.
	 *
	 * @param string $description Product description.
     * @return string
	 */
	public function woo_feed_filter_product_description_callback( $description ) {
		return substr( $description, 0, 10000 );
	}


	/**
	 * Limit Facebook Product Description to 10000 characters.
	 * Add variations attributes after description to prevent Facebook error.
	 *
	 * @param string $description Product description.
	 * @param object $product Product object.
	 * @param object $config Config object.
	 * @param object $parent Parent object.
	 * @return string
	 */
	public function woo_feed_filter_product_variation_add_callback( $description, $product, $config, $parent ) { //phpcs:ignore

		/**
		 * Add variations attributes after description to prevent Facebook error
		 *
		 * @see https://www.facebook.com/business/help/120325381656392?id=725943027795860
		 * @see https://www.facebook.com/business/help/2302017289821154?id=725943027795860
		 */

		if ( isset($product) && isset($parent) && $product->is_type( 'variation' ) && $parent->is_type( 'variable' ) ) {
			$attributes = array();

			foreach ( $parent->get_attributes() as $slug => $value ) {// phpcs:ignore
				$attribute = $product->get_attribute( $slug );

				if ( empty( $attribute ) ) {
					continue;
				}

				$attributes[ $slug ] = $attribute;
			}

			// set variation attributes with separator.
			$separator            = ',';
			$variation_attributes = implode( $separator, $attributes );

			$description .= ' ' . $variation_attributes;
		}

		return $description;
	}

	/**
	 * Modify product availability date.
	 *
	 * @param string $availability_date availability date.
     * @return string
	 */
	public function woo_feed_filter_product_availability_date_callback( $availability_date ) {
		return gmdate( 'c', strtotime( $availability_date ) );
	}

}
