<?php

namespace WC_BPost_Shipping\JsonArray;

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;

class WC_BPost_Shipping_JsonArray_Validator {
	/** @var \WC_BPost_Shipping_Logger */
	private $logger;
	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $woocommerce_adapter;

	/** @var string */
	private $field;
	/** @var string[] */
	private $allowed_countries;

	private $posted_values;


	/**
	 * WC_BPost_Shipping_JsonArray_Validator constructor.
	 *
	 * @param \WC_BPost_Shipping_Logger $logger
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $woocommerce_adapter
	 * @param string $field
	 * @param $allowed_countries
	 * @param $posted_values
	 */
	public function __construct( \WC_BPost_Shipping_Logger $logger, WC_BPost_Shipping_Adapter_Woocommerce $woocommerce_adapter, $field, $allowed_countries, $posted_values ) {
		$this->logger              = $logger;
		$this->woocommerce_adapter = $woocommerce_adapter;
		$this->allowed_countries   = $allowed_countries;
		$this->field               = $field;
		$this->posted_values       = $posted_values;
	}

	public function get_json() {

		$array_to_save = array();

		if ( ! $this->has_countries() ) {
			return json_encode( $array_to_save );
		}
		foreach ( $this->get_countries() as $id => $countries ) {
			$sanitized_id = $this->woocommerce_adapter->wordpress_kses_post( trim( stripslashes( $id ) ) );
			//this row is marked as deletion skip it
			if ( $this->is_removed_row( $sanitized_id ) ) {
				continue;
			}

			if ( ! is_array( $countries ) ) {
				continue;
			}

			$sanitized_countries = array_map( 'stripslashes', $countries );

			foreach ( $sanitized_countries as $sanitized_country ) {
				if ( ! array_key_exists( $sanitized_country, $this->allowed_countries ) ) {
					$this->log_forbid_country( $sanitized_countries );
					continue;
				}

				$new_country_from_val = floatval( trim( stripslashes( $this->posted_values[ $this->field . '_from' ][ $sanitized_id ] ) ) );

				if ( array_key_exists( $sanitized_country, $array_to_save ) ) {
					$this->woocommerce_adapter->settings_add_error(
						sprintf(
							'The country (%s) is already defined into array with the value %.2f. The value %.2f will be discarded',
							$sanitized_country,
							$array_to_save[ $sanitized_country ],
							$new_country_from_val
						)
					);
					continue;
				}
				$array_to_save[ $sanitized_country ] = $new_country_from_val;
			}
		}

		return $this->to_json( $array_to_save );
	}

	/**
	 * @param $sanitized_id
	 *
	 * @return bool
	 */
	private function is_removed_row( $sanitized_id ) {
		return array_key_exists( $this->field . '_remove', $this->posted_values ) && array_key_exists( $sanitized_id, $this->posted_values[ $this->field . '_remove' ] ) && $this->posted_values[ $this->field . '_remove' ][ $sanitized_id ];
	}

	/**
	 * @param $sanitized_countries
	 */
	private function log_forbid_country( $sanitized_countries ) {
		$this->logger->error(
			'Try to add a forbid country',
			array(
				'country'           => $sanitized_countries,
				'countries_allowed' => $this->allowed_countries,
			)
		);
	}

	/**
	 * @return bool
	 */
	private function has_countries() {
		return array_key_exists( $this->field . '_country', $this->posted_values ) && is_array( $this->get_countries() );
	}

	/**
	 * @return string[]
	 */
	private function get_countries() {
		return $this->posted_values[ $this->field . '_country' ];
	}

	/**
	 * @param $array_to_save
	 *
	 * @return mixed|string|void
	 */
	private function to_json( $array_to_save ) {
		$json_saved = json_encode( $array_to_save );
		$this->logger->notice(
			'json_saved',
			array(
				'original_array' => $array_to_save,
				'json_saved'     => $json_saved,
			)
		);

		return $json_saved;
	}
}
