<?php

/**
 * Class GooglereviewStructure
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Structure
 */

namespace CTXFeed\V5\Structure;

use CTXFeed\V5\Filter\ValidateProduct;
use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Helper\ProductHelper;

/**
 * Class representing the structure for GoogleReview.
 * Implements the GoogleReview-related operations.
 */

class GooglereviewStructure {
	/**
	 * Configuration settings.
	 *
	 * @var \Config $config
	 */
	private $config;


	/**
	 * Product ids.
	 *
	 * @var array $ids
	 */
	private $ids;

	/**
	 * Constructor for GooglereviewStructure.
	 *
	 * @param mixed $config Configuration settings.
	 * @param array $ids Product ids.
	 */

	public function __construct( $config, $ids ) {
		$this->config = $config;
		$this->ids = $ids;
		$this->config->itemWrapper  = 'review';
		$this->config->itemsWrapper = 'reviews';
	}

	/**
	 * Retrieves the XML structure.
	 *
	 * @return array The constructed XML data structure.
	 */
	public function get_xml_structure() {
		$attributes  = $this->config->attributes;
		$mattributes = $this->config->mattributes;
		$wrapper     = \str_replace( " ", "_", $this->config->itemWrapper );;
		$data = [];
		$ids = $this->ids;
		foreach ( $ids as $id ) {
			$review = array();
			$product = \wc_get_product( $id );
			if( !ValidateProduct::is_valid( $product, $this->config, $id ) ){
				continue;
			}

			$reviews = \get_comments(
				array(
					'post_id'     => $id,
					'status'      => 'approve',
					'post_status' => 'publish',
					'post_type'   => 'product',
					'parent'      => 0
				)
			);
			$i      = 0;
			if ( $reviews && \is_array( $reviews ) ) {
				foreach ( $reviews as $single_review ) {

					$review_content = $single_review->comment_content;
					if (empty( $review_content ) ) {
						continue;
					}

					$rating = \get_comment_meta( $single_review->comment_ID, 'rating', true );
					if (empty($rating)) {
						continue;
					}

					$review_time = !empty($single_review->comment_date_gmt) ? \gmdate('c', \strtotime($single_review->comment_date_gmt)) : "";
					//Review Content
					//strip tags and spacial characters
					$strip_review_content = CommonHelper::strip_all_tags( wp_specialchars_decode($review_content ) );
					$review_content = !empty( \strlen($strip_review_content ) ) && 0 < \strlen( $strip_review_content ) ? $strip_review_content : $review_content;

					$review_product_url = !empty( $product->get_permalink() ) ? $product->get_permalink() : "";

					$review_id = !empty($single_review->comment_ID) ? $single_review->comment_ID : "";
					$review_author = !empty($single_review->comment_author) ? $single_review->comment_author : "";
					$review_user_id = !empty($single_review->user_id) ? $single_review->user_id : "";

					$review[ $wrapper ]['review_id'] = $review_id;
					$review[$wrapper]['reviewer']['name'] = $review_author;
					$review[$wrapper]['reviewer']['reviewer_id'] = $review_user_id;
					$review[$wrapper]['content'] = $review_content;
					$review[$wrapper]['review_timestamp'] = $review_time;
					$review[$wrapper]['review_url'] = $review_product_url;
					$review[$wrapper]['ratings']["overall"] = $rating;
					$review[$wrapper]['products'] = array();
					$review[$wrapper]['products']['product'] = array();

					$review[$wrapper]['products']['product']['product_name'] = !empty( $product->get_name() ) ? $product->get_name() : "";
					$review[$wrapper]['products']['product']['product_url'] = $review_product_url;

					foreach ( $attributes as $attr_key => $attribute  ) {
						$merchant_attribute = isset($mattributes[ $attr_key ] ) ? $mattributes[ $attr_key ] : '';

						if ( "review_temp_gtin" === $merchant_attribute) {
							$review[$wrapper]['products']['product']['product_ids']['gtins'] = $this->get_product_ids( $product, $this->config, $attr_key, $attribute, $merchant_attribute, 'gtin' );
						} elseif ( "review_temp_mpn" === $merchant_attribute) {
							$review[$wrapper]['products']['product']['product_ids']['mpns'] = $this->get_product_ids( $product, $this->config, $attr_key, $attribute, $merchant_attribute, 'mpn' );;
						} elseif ( "review_temp_sku" === $merchant_attribute ) {
							$review[$wrapper]['products']['product']['product_ids']['skus']= $this->get_product_ids( $product, $this->config, $attr_key, $attribute, $merchant_attribute, 'sku' );;
						} elseif ( "review_temp_brand" === $merchant_attribute) {
							$review[$wrapper]['products']['product']['product_ids']['brands'] =$this->get_product_ids( $product, $this->config, $attr_key, $attribute, $merchant_attribute, 'brand' );;
						}
					}

					$data[] = $review;
				}
			}
		}

		return $data;
	}

	/**
	 * Get Product Ids associated with a review (Ex: variations)
	 *
	 * @param $product
	 * @param $config
	 * @param $attr_key
	 * @param $attribute
	 * @param $merchant_attribute
	 * @param $id_type
	 *
	 * @return array
	 */
	public function get_product_ids( $product, $config, $attr_key, $attribute, $merchant_attribute, $id_type ) {
		$prefix = $this->config->prefix[ $attr_key ];
		$suffix = $this->config->suffix[ $attr_key ];
		if ( $product->is_type( 'variable' ) ) {
			$variations = $product->get_children();
			if ( ! empty( $variations ) ) {
				$variation_ids = [];
				foreach ( $variations as $key => $variation ) {
					$variation = \wc_get_product( $variation );
					if ( 'pattern' === $config->type[ $attr_key ] ) {
						$attribute_value = $prefix." ".$config->default[ $attr_key ]." ".$suffix;
					} else {
						$attribute_value = $prefix." ".ProductHelper::get_attribute_value_by_type( $attribute, $variation,  $this->config )." ".$suffix;
					}
					$variation_ids[ $key ][ $id_type ] = \trim( $attribute_value  );
				}

				return $variation_ids;
			}
		}

		// For non variation products
		$attribute_value = "";
		if ( 'pattern' === $config->type[ $attr_key ] ) {
			$attribute_value = $config->default[ $attr_key ];
		} else {
			$attribute_value = ProductHelper::get_attribute_value_by_type( $attribute, $product, $this->config );
		}
		// Add Prefix and Suffix into Output
		$attribute_value = \trim( $prefix )." ".\trim( $attribute_value )." ".\trim( $suffix );
		$attribute_value = ! empty( $attribute_value ) ? $attribute_value : "";

		return [ $id_type => trim( $attribute_value ) ];
	}

	/**
	 * Retrieves the CSV structure.
	 * Currently, this method serves as a wrapper for the get_xml_structure method.
	 *
	 * @return mixed The XML structure converted to CSV format.
	 */
	public function get_csv_structure() {
		return $this->get_xml_structure();
	}

	/**
	 * Retrieves the TSV structure.
	 * Currently, this method serves as a wrapper for the get_xml_structure method.
	 *
	 * @return mixed The XML structure converted to TSV format.
	 */
	public function get_tsv_structure() {
		return $this->get_xml_structure();
	}

	/**
	 * Retrieves the TXT structure.
	 * Currently, this method serves as a wrapper for the get_xml_structure method.
	 *
	 * @return mixed The XML structure converted to TXT format.
	 */
	public function get_txt_structure() {
		return $this->get_xml_structure();
	}

	/**
	 * Retrieves the XML structure.
	 * Currently, this method serves as a wrapper for the get_xml_structure method.
	 *
	 * @return mixed The XML structure converted to XLS format.
	 */
	public function get_xls_structure() {
		return $this->get_xml_structure();
	}

	/**
	 * Retrieves the JSON structure.
	 * Currently, this method serves as a wrapper for the get_xml_structure method.
	 *
	 * @return mixed The XML structure converted to JSON format.
	 */
	public function get_json_structure() {
		return $this->get_xml_structure();
	}

	/**
	 * Process Reviews Product Header
	 * @return mixed
	 */
	public static function make_google_review_header() {
		$version           = '2.3';
		$aggregator_name   = 'review';
		$publisher_name    = 'CTX Feed – WooCommerce Product Feed Generator by Webappick';
		$provider_onfo     = "<version>$version</version>
							<aggregator>
								<name>$aggregator_name</name>
							</aggregator>
							<publisher>
								<name>$publisher_name</name>
							</publisher>";

		$xml_header_link = '<feed xmlns:vc="http://www.w3.org/2007/XMLSchema-versioning" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation= "http://www.google.com/shopping/reviews/schema/product/2.3/product_reviews.xsd">';
		return '<?xml version="1.0" encoding="UTF-8" ?>' . PHP_EOL . $xml_header_link. PHP_EOL . $provider_onfo. "<" . \wp_unslash( 'reviews' ) . ">";
	}

}
