<?php

namespace Bookit\Classes;

use Bookit\Classes\Admin\SettingsController;
use Bookit\Classes\Base\Plugin;
use Bookit\Classes\Base\User;
use Bookit\Classes\Database\Appointments;
use Bookit\Classes\Database\Customers;
use Bookit\Classes\Database\Staff_Services;
use Bookit\Classes\Vendor\Payments;
use Bookit\Helpers\CleanHelper;


class AppointmentController {

	protected static $googleCalendarAddon = 'google-calendar';

	private static function getCleanRules() {

		return array(
			'clear_price'     => array( 'type' => 'floatval' ),
			'user_id'         => array( 'type' => 'intval' ),
			'staff_id'        => array( 'type' => 'intval' ),
			'service_id'      => array( 'type' => 'intval' ),
			'start_timestamp' => array( 'type' => 'intval' ),
			'end_timestamp'   => array( 'type' => 'intval' ),
			'now_timestamp'   => array( 'type' => 'intval' ),
			'today_timestamp' => array( 'type' => 'intval' ),
			'email'           => array(
				'type'     => 'strval',
				'function' => array(
					'custom' => false,
					'name'   => 'sanitize_email',
				),
			),
			'payment_method'  => array( 'type' => 'strval' ),
			'full_name'       => array( 'type' => 'strval' ),
			'phone'           => array(
				'function' => array(
					'custom' => true,
					'name'   => 'custom_sanitize_phone',
				),
			),
		);
	}

	/**
	 * Validation
	 * @param $data
	 */
	public static function validate( $data ) {
		$errors   = array();
		$settings = SettingsController::get_settings();

		if ( ! $data['staff_id'] ) {
			$errors['staff_id'] = __( 'No staff', 'bookit' );
		}
		if ( ! $data['service_id'] ) {
			$errors['service_id'] = __( 'No service', 'bookit' );
		}
		if ( ! $data['start_time'] ) {
			$errors['start_time'] = __( 'No start time', 'bookit' );
		}
		if ( ! $data['end_time'] ) {
			$errors['end_time'] = __( 'No end time', 'bookit' );
		}
		if ( ! $data['date_timestamp'] ) {
			$errors['date_timestamp'] = __( 'No date', 'bookit' );
		}

		$appointment = Appointments::checkAppointment( $data );

		if ( $appointment > 0 ) {
			$errors['appointment'] = __( 'Selected Service Time is not available!', 'bookit' );
		}

		if ( $data['phone'] || false === $data['phone'] ) {
			if ( ! preg_match( '/^((\+)?[0-9]{8,14})$/', $data['phone'] ) ) {
				$errors['phone'] = __( 'Please enter a valid phone number', 'bookit' );
			}
		}

		if ( 'guest' == $settings['booking_type'] ) {
			if ( ! $data['email'] && ! $data['phone'] ) {
				$errors['phone'] = __( 'Please enter email or phone', 'bookit' );
			}
		}

		if ( ! $data['email'] ) {
			$errors['email'] = __( 'Please enter email', 'bookit' );
		}

		if ( $data['email'] && ! is_email( $data['email'] ) ) {
			$errors['email'] = __( 'Please enter your email in format youremail@example.com', 'bookit' );
		}

		if ( $data['full_name'] ) {
			if ( strlen( $data['full_name'] ) < 3 || strlen( $data['full_name'] ) > 25 ) {
				$errors['full_name'] = __( 'Full name must be between 3 and 25 characters long', 'bookit' );
			}
		} else {
			$errors['full_name'] = __( 'Please enter full name', 'bookit' );
		}

		if ( 'registered' == $settings['booking_type'] ) {

			$exist_user = get_user_by( 'email', $data['email'] );
			if ( ! $data['user_id'] && $exist_user && ! wp_check_password( $data['password'], $exist_user->data->user_pass, $exist_user->ID ) ) {
				$errors['password'] = __( 'Wrong password', 'bookit' );
			}

			if ( ! $data['user_id'] ) {
				if ( empty( $data['password'] ) ) {
					$errors['password'] = __( 'Please enter a password', 'bookit' );
				}

				if ( false !== strpos( wp_unslash( $data['password'] ), '\\' ) ) {
					$errors['password'] = __( "Passwords may not contain the character '\\'", 'bookit' );
				}

				if ( ! ( $exist_user instanceof \WP_User ) && ( ! empty( $data['password'] ) ) && $data['password'] != $data['password_confirmation'] ) {
					$errors['password_confirmation'] = __( 'Please enter the same password in both password fields', 'bookit' );
				}
			}

			if ( $data['user_id'] && ! is_user_logged_in() ) {
				$errors['appointment'] = __( 'Authorization error', 'bookit' );
			}
		}

		$price = Staff_Services::get_service_price_by_staff( $data['service_id'], $data['staff_id'] );
		if ( (float) $data['clear_price'] !== (float) $price ) {
			$errors['clear_price'] = __( 'Price is incorrect', 'bookit' );
		}

		if ( (float) $data['clear_price'] > 0 && ! array_key_exists( $data['payment_method'], $settings['payments'] ) ) {
			$errors['payment_method'] = __( 'Please choose correct payment method', 'bookit' );
		}

		if ( count( $errors ) > 0 ) {
			wp_send_json_error( array( 'errors' => $errors ) );
		}
	}

	/**
	 * Book Appointment
	 */
	public static function save() {

		$send_no_cache_headers = apply_filters( 'rest_send_nocache_headers', is_user_logged_in() );
		if ( ! $send_no_cache_headers ) {
			$nonce                      = wp_create_nonce( 'bookit_nonce' );
			$_SERVER['HTTP_X_WP_NONCE'] = $nonce;
		}

		check_ajax_referer( 'bookit_book_appointment', 'nonce' );

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		self::validate( $data );

		if ( empty( $data ) ) {
			wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
			die();
		}

		$customer = CustomerController::get_customer( $data );

		$notes = array();
		if ( ! empty( $data['comment'] ) ) {
			$notes['comment'] = $data['comment'];
		}
		if ( $customer->email != $data['email'] ) {
			$notes['email'] = $data['email'];
		}
		if ( $customer->phone != $data['phone'] && ! empty( $data['phone'] ) ) {
			$notes['phone'] = $data['phone'];
		}
		if ( $customer->full_name != $data['full_name'] && ! empty( $data['full_name'] ) ) {
			$notes['full_name'] = $data['full_name'];
		}
		$data['customer_id'] = $customer->id;
		$data['notes']       = serialize( $notes );
		$data['status']      = Appointments::$pending;

		$id = Appointments::create_appointment( $data );

		do_action( 'bookit_appointment_created', $id );

		$appointment          = (array) Appointments::get_full_appointment_by_id( $id );
		$appointment['token'] = $data['token'];

		/** if google calendar addon is installed */
		if ( Plugin::isAddonInstalledAndEnabled( self::$googleCalendarAddon )
			&& has_action( 'bookit_google_calendar_create_appointment' ) ) {
			$appointment['customer_email'] = $notes['email'] ?? $appointment['customer_email'];
			$appointment['customer_phone'] = $notes['phone'] ?? $appointment['customer_phone'];
			do_action( 'bookit_google_calendar_create_appointment', $appointment );
		}
		/** if google calendar addon is installed | end */

		$redirect_url = '';
		if ( ! is_null( $appointment['payment_method'] ) ) {
			$payments     = new Payments( $appointment );
			$redirect_url = $payments->redirect_url();
		}
		wp_send_json_success(
			array(
				'appointment'  => $appointment,
				'customer'     => $customer,
				'nonce'        => $nonce,
				'redirect_url' => $redirect_url,
				'message'      => __( 'Appointment Saved!', 'bookit' ),
			)
		);
	}

	/**
	 * Get Month Appointments
	 */
	public static function get_month_appointments() {
		check_ajax_referer( 'bookit_month_appointments', 'nonce' );

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		if ( ! empty( $data ) ) {
			$appointments = Appointments::month_appointments( $data );

			wp_send_json_success( $appointments );
		}

		wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
	}

	/**
	 * Get Day Appointments
	 */
	public static function get_day_appointments() {
		check_ajax_referer( 'bookit_day_appointments', 'nonce' );

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );

		if ( empty( $data ) ) {
			wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
			die();
		}

		$appointments = Appointments::day_appointments( $data );

		/** if google calendar addon is installed */
		if ( Plugin::isAddonInstalledAndEnabled( self::$googleCalendarAddon )
			&& has_filter( 'bookit_google_calendar_get_events_by_date' ) ) {
			$gcBusyTimeSlots = apply_filters( 'bookit_google_calendar_get_events_by_date', $data );

			/** remove double values by id key */
			foreach ( $gcBusyTimeSlots as $key => $value ) {
				if ( array_search( $value->id, array_column( $appointments, 'id' ) ) !== false
					|| array_search( (string) $value->start_time, array_column( $appointments, 'start_time' ) ) ) {
					unset( $gcBusyTimeSlots[ $key ] );
				}
			}
			$appointments = array_merge( $gcBusyTimeSlots, $appointments );
		}
		/** if google calendar addon is installed | end */
		wp_send_json_success( $appointments );
	}

	/**
	 * Check is Appointment time free
	 */
	public static function is_free_appointment() {
		check_ajax_referer( 'bookit_is_free_appointment', 'nonce' );

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		if ( empty( $data['staff_id'] ) || empty( $data['service_id'] ) || empty( $data['date_timestamp'] )
			|| empty( $data['start_time'] ) || empty( $data['end_time'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
		}

		$appointment = Appointments::checkAppointment( $data );
		wp_send_json_success( array( 'is_free' => false ) );
	}
}
