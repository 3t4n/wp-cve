<?php
/**
 * @package WP Product Review Feed Manager/Traits
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait WPPRFM_XML_Element_Functions {

	/**
	 * Handles the simple elements.
	 *
	 * @param string $element_name  The name of the element.
	 * @param array  $review_data   The review data array.
	 *
	 * @return  string  String with the correct feed element to be placed in the feed.
	 */
	protected function wpprfm_handle_simple_element( $element_name, $review_data ) {
		if ( 'content' === $element_name && array_key_exists( $element_name, $review_data ) ) {
			$review_data[ $element_name ] = htmlspecialchars( $review_data[ $element_name ] );
		}

		if ( array_key_exists( $element_name, $review_data ) && '' !== $review_data[ $element_name ] ) {
			if ( 'review_timestamp' === $element_name ) {
				$date = new DateTime( $review_data[ $element_name ] );

				$time_stamp = $date->format( 'Y-m-d\TH:i:s\Z' );

				return sprintf( '<%s>%s</%s>', $element_name, $time_stamp, $element_name );
			} else {
				return sprintf( '<%s>%s</%s>', $element_name, $review_data[ $element_name ], $element_name );
			}
		} else {
			return '';
		}
	}

	/**
	 * Handles the reviewer element.
	 *
	 * @param array $review_data    That contains the reviewer data.
	 *
	 * @return string   String with the xml elements for the reviewer.
	 */
	protected function wpprfm_handle_reviewer( $review_data ) {
		if ( array_key_exists( 'reviewer_name', $review_data ) && '' !== $review_data['reviewer_name'] ) {
			if ( 'Anonymous' === $review_data['reviewer_name'] || 'anonymous' === $review_data['reviewer_name'] || '' === $review_data['reviewer_name'] ) {
				$name_element = '<name is_anonymous="true">Anonymous</name>';
			} else {

				if ( filter_var( $review_data['reviewer_name'], FILTER_VALIDATE_EMAIL ) ) {
					// If the given reviewer name is an email, set the name element to anonymous.
					$name_element = '<name is_anonymous="true">Anonymous</name>';
				} else {
					$name_element = sprintf( '<name>%s</name>', $review_data['reviewer_name'] );
				}
			}
		} else {
			$name_element = '';
		}

		if ( array_key_exists( 'reviewer_id', $review_data ) && '' !== $review_data['reviewer_id'] ) {
			$id_element = sprintf( '<reviewer_id>%s</reviewer_id>', $review_data['reviewer_id'] );
		} else {
			$id_element = '';
		}

		if ( $name_element || $id_element ) {
			return sprintf( '<reviewer>%s%s</reviewer>', $name_element, $id_element );
		} else {
			return '';
		}
	}

	protected function wpprfm_handle_pros( $review_data ) {
		$key = 'pro';

		if ( array_key_exists( $key, $review_data ) && is_array( $review_data[ $key ] ) && ! empty( $review_data[ $key ] ) ) {
			return $this->handle_array_data( $key, $key, $review_data );
		} else {
			return '';
		}
	}

	protected function wpprfm_handle_cons( $review_data ) {
		$key = 'con';

		if ( array_key_exists( $key, $review_data ) && is_array( $review_data[ $key ] ) && ! empty( $review_data[ $key ] ) ) {
			return $this->handle_array_data( $key, $key, $review_data );
		} else {
			return '';
		}
	}

	protected function wpprfm_handle_review_url( $review_data ) {
		if ( array_key_exists( 'review_url', $review_data ) && '' !== $review_data['review_url'] ) {
			return sprintf( '<review_url type="%s">%s</review_url>', $review_data['review_url_type'], $review_data['review_url'] );
		} else {
			return '';
		}
	}

	protected function wpprfm_handle_reviewer_images( $review_data ) {
		$xml_string = '';

		if ( array_key_exists( 'reviewer_image_url', $review_data ) && is_array( $review_data['reviewer_image_url'] ) && ! empty( $review_data['reviewer_image_url'] ) ) {
			$xml_string .= '<reviewer_images>';

			foreach ( $review_data['reviewer_image_url'] as $reviewer_image ) {
				$xml_string .= sprintf( '<reviewer_image><url>%s</url></reviewer_image>', $reviewer_image );
			}

			$xml_string .= '</reviewer_images>';
		}

		return $xml_string;
	}

	protected function wpprfm_handle_ratings( $review_data ) {
		if ( array_key_exists( 'ratings_overall', $review_data ) && '' !== $review_data['ratings_overall'] ) {
			$min_val = array_key_exists( 'ratings_overall_min', $review_data ) && ! '' !== $review_data['ratings_overall_min'] ? sprintf( ' min="%s"', $review_data['ratings_overall_min'] ) : '';
			$max_val = array_key_exists( 'ratings_overall_max', $review_data ) && ! empty( $review_data['ratings_overall_max'] ) ? sprintf( ' max="%s"', $review_data['ratings_overall_max'] ) : '';

			$result = sprintf( '<ratings><overall%s%s>%s</overall></ratings>', $min_val, $max_val, $review_data['ratings_overall'] );

			return $result;
		} else {
			return '';
		}
	}

	protected function wpprfm_handle_products( $review_data ) {
		$xml_string = '';

		if ( array_key_exists( 'product_url', $review_data ) && ! empty( $review_data['product_url'] ) ) {
			$xml_string .= '<products><product>';

			$xml_string .= $this->handle_product_ids( $review_data );

			$xml_string .= array_key_exists( 'product_name', $review_data ) && ! empty( $review_data['product_name'] ) ? sprintf( '<product_name>%s</product_name>', $review_data['product_name'] ) : '';
			$xml_string .= sprintf( '<product_url>%s</product_url>', $review_data['product_url'] );

			$xml_string .= '</product></products>';
		}

		return $xml_string;
	}

	private function handle_product_ids( $review_data ) {
		$ids_string = '';
		$gtin       = $this->get_id_from_product( 'product_ids_gtin', $review_data );
		$mpn        = $this->get_id_from_product( 'product_ids_mpn', $review_data );
		$sku        = $this->get_id_from_product( 'product_ids_sku', $review_data );
		$brand      = $this->get_id_from_product( 'product_ids_brand', $review_data );

		if ( $gtin || $mpn || $sku || $brand ) {

			$review_data['product_ids_gtin']  = $gtin;
			$review_data['product_ids_mpn']   = $mpn;
			$review_data['product_ids_sku']   = $sku;
			$review_data['product_ids_brand'] = $brand;

			$ids_string .= '<product_ids>';

			$ids_string .= $this->handle_array_data( 'gtin', 'product_ids_gtin', $review_data );
			$ids_string .= $this->handle_array_data( 'mpn', 'product_ids_mpn', $review_data );
			$ids_string .= $this->handle_array_data( 'sku', 'product_ids_sku', $review_data );
			$ids_string .= $this->handle_array_data( 'brand', 'product_ids_brand', $review_data );

			$ids_string .= '</product_ids>';
		}

		return $ids_string;
	}

	private function get_id_from_product( $id_key, $review_data ) {
		if ( array_key_exists( $id_key, $review_data ) ) {

			if ( is_array( $review_data[ $id_key ] ) && ! empty( $review_data[ $id_key ] ) ) {
				return $review_data[ $id_key ];
			} elseif ( $review_data[ $id_key ] ) {
				return array( $review_data[ $id_key ] );
			}
		}

		return '';
	}

	private function handle_array_data( $key, $data_title, $review_data ) {
		$xml_string = '';

		if ( empty( $review_data[ $data_title ] ) ) {
			return $xml_string;
		}

		$xml_string .= sprintf( '<%s>', $key . 's' );

		foreach ( $review_data[ $data_title ] as $value ) {
			$xml_string .= sprintf( '<%s>%s</%s>', $key, $value, $key );
		}

		$xml_string .= sprintf( '</%s>', $key . 's' );

		return $xml_string;
	}
}

