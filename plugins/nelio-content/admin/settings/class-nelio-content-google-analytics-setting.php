<?php
/**
 * This file contains the setting for connecting Google Analytics with
 * Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class represents the setting for connecting Google Analytics with
 * Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */
class Nelio_Content_Google_Analytics_Setting extends Nelio_Content_Abstract_React_Setting {

	public function __construct() {
		parent::__construct( 'ga4_property_id', 'GoogleAnalyticsSetting' );
	}//end __construct()

	// @Overrides
	// phpcs:ignore
	protected function get_field_attributes() {
		$settings      = Nelio_Content_Settings::instance();
		$use_analytics = $settings->get( 'use_analytics' );
		$has_value     = ! empty( trim( $this->value ) );
		return array(
			'mode' => $use_analytics && $has_value ? 'ga4-property-id' : 'init',
		);
	}//end get_field_attributes()

	// @Implements
	// phpcs:ignore
	public function sanitize( $input ) {

		$value = false;
		if ( isset( $input[ $this->name ] ) ) {
			$value = $input[ $this->name ];
		}//end if

		if ( ! empty( $value ) ) {
			$value = sanitize_text_field( $value );
		} else {
			$value = '';
		}//end if

		$input[ $this->name ] = $value;
		return $input;

	}//end sanitize()

}//end class
