<?php
/**
 * This class is responsible for all override google template.
 *
 * @package    CTXFeed
 */

namespace CTXFeed\V5\Override;

/**
 * Class GoogleTemplate
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Override
 */
class GoogleTemplate {

	/**
	 * GoogleTemplate class constructor
	 */
	public function __construct() {
		add_filter(
			'woo_feed_get_google_color_attribute',
			array(
				$this,
				'woo_feed_get_google_color_size_attribute_callback',
			),
			10,
			1
		);

		add_filter(
			'woo_feed_get_google_size_attribute',
			array(
				$this,
				'woo_feed_get_google_color_size_attribute_callback',
			),
			10,
			1
		);

		// NOTE: This filter is unnecessary, because we can get expected output ( space separated dimension unit ) without applying this filter.
//		add_filter( 'woo_feed_get_google_attribute', array( $this, 'woo_feed_get_google_attribute_callback' ), 10, 5 );

		add_filter( 'woo_feed_filter_product_title', array( $this, 'woo_feed_filter_product_title_callback' ), 10, 1 );

		add_filter( 'woo_feed_attribute_separator', array( $this, 'woo_feed_attribute_separator_callback' ), 10, 1 );

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
            'woo_feed_filter_product_availability_date',
            array(
				$this,
				'woo_feed_filter_product_availability_date_callback',
		),
            10,
            1
            );

		add_filter(
			'woo_feed_filter_product_availability',
			array(
				$this,
				'woo_feed_filter_product_availability_callback',
			),
			10,
			1
		);
	}

	/**
	 * Google template attribute value override.
	 *
	 * @param string $output attribute value.
     * @return mixed|string
	 */
	public function woo_feed_get_google_color_size_attribute_callback( $output ) {
		return str_replace( array( ' ', ',' ), array( '', '/' ), $output );
	}

	/**
	 * Google template attribute
	 *
	 * @param string                                                                            $output attribute.
	 * @param \WC_Product_Simple|\WC_Product_Variable|\WC_Product_Grouped|\WC_Product_Variation $product woocommerce product.
	 * @param \CTXFeed\V5\Utility\Config                                                        $config feed config.
	 * @param string                                                                            $product_attribute attribute name.
	 * @param string                                                                            $merchant_attribute merchant name.
     * @return mixed|string
	 */
	public function woo_feed_get_google_attribute_callback( //phpcs:ignore
		$output,
		$product, //phpcs:ignore
		$config,
		$product_attribute,
		$merchant_attribute
	) {
		$weight_attributes    = array( 'product_weight', 'shipping_weight' );
		$dimension_attributes = array(
			'product_length',
			'product_width',
			'product_height',
			'shipping_length',
			'shipping_width',
			'shipping_height',
		);


		$wc_unit  = '';
		$override = false;

		if ( in_array( $merchant_attribute, $weight_attributes, true ) ) {
			$override = true;
			$wc_unit  = ' ' . get_option( 'woocommerce_weight_unit' );
		}

		if ( in_array( $merchant_attribute, $dimension_attributes, true ) ) {
			$override = true;
			$wc_unit  = ' ' . get_option( 'woocommerce_dimension_unit' );
		}

		if ( ! $override ) {
			return $output;
		}

		$attributes = false;

		if ( $config->attributes ) {
			$attributes = $config->attributes;
		}

		if ( ! $attributes ) {
			return $output;
		}

		$key = array_search( $product_attribute, $attributes, true );

		// ! empty( $key ) this condition is removed because it is not working for 0 index.
		if ( isset( $config->suffix ) && array_key_exists( $key, $config->suffix ) ) {
			$unit = $config->suffix[ $key ];

			if ( ! empty( $unit ) && ! empty( $output ) ) {
				$output .= ' ' . $unit;
			} elseif ( ! empty( $wc_unit ) && ! empty( $output ) ) {
				$output .= $wc_unit;
			}
		}

		return $output;
	}

	/**
	 * Google Shopping Product Description Character limit: max 5000.
	 *
	 * @param string $description product description.
     * @return string
	 * @link https://webappick.atlassian.net/browse/CBT-150
	 */
	public function woo_feed_filter_product_description_callback( $description ) {
		return substr( $description, 0, 5000 );
	}

	/**
	 * Modify product title for google merchant.
	 *
	 * @param string $title product title.
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
	 * Set (_) as separator for google merchant.
	 *
	 * @param string $status product start.
     * @return string
	 */
	public function woo_feed_filter_product_availability_callback( $status ) {
		$status = explode( ' ', $status );
		$status = implode( '_', $status );

		return $status;
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
