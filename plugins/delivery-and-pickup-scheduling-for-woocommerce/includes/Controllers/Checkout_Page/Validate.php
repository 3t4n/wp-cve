<?php
/**
 * Validation methods.
 *
 * Author:          Uriahs Victor
 * Created on:      17/11/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Controllers
 */

namespace Lpac_DPS\Controllers\Checkout_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use DateTime;
use DateTimeZone;
use Lpac_DPS\Models\Plugin_Settings\Scheduling as Scheduling_Settings;
use Lpac_DPS\Models\Plugin_Settings\GeneralSettings;

/**
 * Class Validate.
 */
class Validate extends BaseCheckoutPageController {

	/**
	 * Ensure the date being set by the user is in the future.
	 *
	 * @param array  $fields WooCommerce checkout fields.
	 * @param object $errors Errors object.
	 * @return void
	 */
	public function validate_future_date( array $fields, object $errors ): void {
		$order_type = sanitize_text_field( wp_unslash( $_POST['lpac_dps_order_type'] ?? '' ) );

		if ( empty( $order_type ) ) {
			return;
		}

		$date_set = sanitize_text_field( wp_unslash( $_POST[ "lpac_dps_{$order_type}_date" ] ?? '' ) );
		if ( empty( $date_set ) ) {
			return;
		}

		$time_set = sanitize_text_field( wp_unslash( $_POST[ "lpac_dps_{$order_type}_time" ] ?? '' ) );
		if ( empty( $time_set ) ) {
			return;
		}

		$parts = explode( '-', $time_set );
		if ( count( $parts ) > 1 ) { // If the time slot is a range example 1:00 PM - 3:00 PM.
			$time_set = trim( $parts[1] ); // Use the end time to check logic because any time before that is still technically allowed.
		}

		$general_settings = new GeneralSettings();
		$timezone         = new DateTimeZone( $general_settings::get_site_timezone() );
		$now              = new DateTime( 'now', $timezone );

		// We need to ensure we're creating the correct format depending on the time format set.
		$format            = ( '12hr' === $general_settings::get_preferred_time_format() ) ? 'Y-m-d h:i A' : 'Y-m-d H:i';
		$selected_date     = $date_set . ' ' . $time_set;
		$selected_date_obj = ( new DateTime() )::createFromFormat( $format, $selected_date, $timezone );

		if ( $selected_date_obj > $now ) {
			return;
		}

		$error_msg = '<strong>' . __( 'Please select a date and time in the future.', 'delivery-and-pickup-scheduling-for-woocommerce' ) . '</strong>';

		$errors->add( 'validation', $error_msg );
	}

	/**
	 * Check if the customer has selected a delivery date.
	 *
	 * @param array  $fields WooCommerce checkout fields.
	 * @param object $errors Errors object.
	 * @return void
	 */
	public function validate_date_field( array $fields, object $errors ): void {

		$order_type = sanitize_text_field( wp_unslash( $_POST['lpac_dps_order_type'] ?? '' ) );

		if ( empty( $order_type ) ) {
			return;
		}

		$settings = new Scheduling_Settings( $order_type );

		if ( ! $settings->orderDateFieldEnabled() ) {
			return;
		}

		if ( ! $settings->is_date_required() ) {
			return;
		}

		$date_set = sanitize_text_field( wp_unslash( $_POST[ "lpac_dps_{$order_type}_date" ] ?? '' ) );

		if ( ! empty( $date_set ) ) {
			return;
		}

		$error_msg = $settings->get_date_required_notice_text();

		$error_msg = '<strong>' . $error_msg . '</strong>';

		$errors->add( 'validation', $error_msg );
	}

	/**
	 * Check if the customer has selected a delivery date.
	 *
	 * @param array  $fields WooCommerce checkout fields.
	 * @param object $errors Errors object.
	 * @return void
	 */
	public function validate_time_field( array $fields, object $errors ): void {

		$order_type = sanitize_text_field( wp_unslash( $_POST['lpac_dps_order_type'] ?? '' ) );

		if ( empty( $order_type ) ) {
			return;
		}

		$settings = new Scheduling_Settings( $order_type );

		if ( ! $settings->orderTimeFieldEnabled() ) {
			return;
		}

		if ( ! $settings->is_time_required() ) {
			return;
		}

		$time_set = sanitize_text_field( wp_unslash( $_POST[ "lpac_dps_{$order_type}_time" ] ?? '' ) );

		if ( ! empty( $time_set ) ) {
			return;
		}

		$error_msg = $settings->get_time_required_notice_text();

		$error_msg = '<strong>' . $error_msg . '</strong>';

		$errors->add( 'validation', $error_msg );
	}
}
