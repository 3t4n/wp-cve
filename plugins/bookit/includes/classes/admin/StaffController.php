<?php

namespace Bookit\Classes\Admin;

use Bookit\Classes\Base\Plugin;
use Bookit\Classes\Base\User;
use Bookit\Classes\Database\Appointments;
use Bookit\Classes\Database\Customers;
use Bookit\Classes\Database\Services;
use Bookit\Classes\Database\Staff;
use Bookit\Classes\Database\Staff_Services;
use Bookit\Classes\Database\Staff_Working_Hours;
use Bookit\Classes\Template;
use Bookit\Helpers\CleanHelper;

class StaffController extends DashboardController {

	private static function getCleanRules() {
		return array(
			'id'        => array( 'type' => 'intval' ),
			'limit'     => array( 'type' => 'intval' ),
			'offset'    => array( 'type' => 'intval' ),
			'email'     => array( 'type' => 'strval' ),
			'full_name' => array( 'type' => 'strval' ),
			'phone'     => array(
				'function' => array(
					'custom' => true,
					'name'   => 'custom_sanitize_phone',
				),
			),
		);
	}
	/**
	 * Display Rendered Template
	 * @return bool|string
	 */
	public static function render() {

		$bookitUser = self::bookitUser();

		/** show just self if this staff */
		if ( true == $bookitUser['is_staff'] ) {
			$staff = $bookitUser['staff'];
		} else {
			$staff = Staff::get_all();
		}

		$services = Services::get_all_short();
		$wp_users = get_users(
			array(
				'fields'       => array( 'ID', 'display_name', 'user_email' ),
				'role__not_in' => array( 'administrator' ),
			)
		);

		$addons = array();
		$answer = array();
		/** if google calendar addon is installed */
		if ( Plugin::isAddonInstalledAndEnabled( self::$googleCalendarAddon ) && has_filter( 'bookit_filter_connect_staff_google_calendar' ) ) {
			$addons[]       = Plugin::getAddonInfo( self::$googleCalendarAddon );
			$gcFilterResult = apply_filters( 'bookit_filter_connect_staff_google_calendar', $staff );
			$staff          = $gcFilterResult['staff'];
			if ( array_key_exists( 'answer', $gcFilterResult ) ) {
				$answer = $gcFilterResult['answer'];
			}
		}
		/** if google calendar addon is installed | end */

		self::enqueue_styles_scripts();

		return Template::load_template(
			'dashboard/bookit-staff',
			array(
				'staff'    => self::parseStaff( $staff ),
				'services' => $services,
				'addons'   => $addons,
				'answer'   => $answer,
				'wp_users' => $wp_users,
				'page'     => __( 'Staff', 'bookit' ),
			),
			true
		);
	}


	public static function parseStaff( $staff ) {
		$result = array();
		foreach ( $staff as $key => $employee ) {
			$item                   = (array) $employee;
			$item['staff_services'] = json_decode( $employee['staff_services'], true ) ?? array();
			$item['working_hours']  = json_decode( $employee['working_hours'], true ) ?? array();
			array_push( $result, $item );
		}
		unset( $staff );
		return $result;
	}

	/**
	 * Get Staff with Pagination
	 */
	public static function get_staff() {

		$data = CleanHelper::cleanData( $_GET, self::getCleanRules() );

		if ( ! empty( $data['limit'] ) ) {
			$response['staff'] = Staff::get_paged( $data['limit'], $data['offset'] );
			$response['total'] = Staff::get_count();

			wp_send_json_success( $response );
		}

		wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
	}

	/**
	 * Validate staff fields
	 */
	public static function validate( $data ) {
		$errors = array();

		if ( $data['phone'] || false === $data['phone'] ) {
			if ( ! preg_match( '/^((\+)?[0-9]{9,14})$/', $data['phone'] ) ) {
				$errors['phone'] = esc_html__( 'Please enter a valid phone number', 'bookit' );
			}
		}

		if ( ! $data['email'] ) {
			$errors['email'] = esc_html__( 'Email is required', 'bookit' );
		}

		if ( $data['email'] && ! is_email( $data['email'] ) ) {
			$errors['email'] = esc_html__( 'Not valid Email', 'bookit' );
		}

		if ( $data['full_name'] ) {
			if ( strlen( $data['full_name'] ) < 3 || strlen( $data['full_name'] ) > 50 ) {
				$errors['full_name'] = esc_html__( 'Full Name must be between 3 and 50 characters long', 'bookit' );
			}
		} elseif ( 'wp_user' != $data['object'] ) {
			$errors['full_name'] = esc_html__( 'Full Name is required.', 'bookit' );
		}

		if ( count( $errors ) > 0 ) {
			wp_send_json_error(
				array(
					'errors'  => $errors,
					'message' => esc_html__( 'Error occurred!', 'bookit' ),
				)
			);
		}
	}

	/**
	 * Create WordPress User from staff form
	 */
	public static function create_wp_user() {
		check_ajax_referer( 'bookit_save_item', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		self::validate( $data );

		if ( get_user_by( 'email', $data['email'] ) ) {
			wp_send_json_error(
				array(
					'errors'  => array( 'wp_email' => __( 'Wordpress User with such email already exist.', 'bookit' ) ),
					'message' => __( 'Error occurred!', 'bookit' ),
				)
			);
		}
		$data['role'] = User::$staff_role;

		$id     = Customers::save_or_get_wp_user( $data );
		$wpUser = get_user_by( 'ID', $id )->data;

		wp_send_json_success(
			array(
				'wp_user' => array(
					'ID'           => $wpUser->ID,
					'display_name' => $wpUser->display_name,
					'user_email'   => $wpUser->user_email,
				),
				'message' => __( 'Customer Saved!', 'bookit' ),
			)
		);
	}

	/**
	 * Save Staff
	 */
	public static function save() {
		check_ajax_referer( 'bookit_save_item', 'nonce' );

		if ( ! current_user_can( 'manage_bookit_staff' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		self::validate( $data );

		$id = ( ! empty( $data['id'] ) ) ? $data['id'] : null;

		/** if this is staff can edit just self data */
		$bookitUser = self::bookitUser();
		if ( true == $bookitUser['is_staff'] && ( null == $id || ( (int) $bookitUser['staff'][0]['id'] != (int) $id ) ) ) {
			return false;
		}

		if ( empty( $data ) ) {
			wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
			return false;
		}

		$staff_services = json_decode( stripslashes( $data['staff_services'] ) );
		$working_hours  = json_decode( stripslashes( $data['working_hours'] ) );

		unset( $data['staff_services'] );
		unset( $data['working_hours'] );
		unset( $data['gc_token'] );

		if ( $id ) {
			Staff::update( $data, array( 'id' => $id ) );

			Staff_Services::delete_where( 'staff_id', $id );

			foreach ( $working_hours as $working_hour ) {
				$update = array(
					'id'         => $working_hour->id,
					'staff_id'   => $id,
					'weekday'    => $working_hour->weekday,
					'start_time' => $working_hour->start_time,
					'end_time'   => $working_hour->end_time,
					'break_from' => $working_hour->break_from,
					'break_to'   => $working_hour->break_to,
				);
				Staff_Working_Hours::update( $update, array( 'id' => $update['id'] ) );
			}
		} else {
			Staff::insert( $data );

			$id = Staff::insert_id();

			foreach ( $working_hours as $working_hour ) {
				$insert = array(
					'staff_id'   => $id,
					'weekday'    => $working_hour->weekday,
					'start_time' => $working_hour->start_time,
					'end_time'   => $working_hour->end_time,
					'break_from' => $working_hour->break_from,
					'break_to'   => $working_hour->break_to,
				);
				Staff_Working_Hours::insert( $insert );
			}
		}

		foreach ( $staff_services as $staff_service ) {
			$insert = array(
				'staff_id'   => $id,
				'service_id' => $staff_service->id,
				'price'      => number_format( (float) $staff_service->price, 2, '.', '' ),
			);
			Staff_Services::insert( $insert );
		}

		/** set bookit staff role if WordPress user connected */
		if ( $data['wp_user_id'] ) {
			$wpUser = get_user_by( 'ID', $data['wp_user_id'] );
			$wpUser->set_role( User::$staff_role );
		}

		/** if google calendar addon is installed */
		if ( Plugin::isAddonInstalledAndEnabled( self::$googleCalendarAddon ) && has_filter( 'bookit_filter_connect_employee_google_calendar' ) ) {
			$staff = (array) Staff::get( 'id', $id );
			$staff = apply_filters( 'bookit_filter_connect_employee_google_calendar', $staff );
		}
		/** if google calendar addon is installed | end */

		do_action( 'bookit_staff_saved', $id );

		wp_send_json_success(
			array(
				'id'      => $id,
				'staff'   => $staff,
				'message' => __( 'Staff Saved!', 'bookit' ),
			)
		);
	}

	/**
	 * Disconnect google calendar data from staff ( clean gc_token)
	 */
	public static function clean_gc_token() {
		check_ajax_referer( 'bookit_save_item', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );

		if ( ! isset( $data['id'] ) || empty( $data['id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
		}

		Staff::update( array( 'gc_token' => null ), array( 'id' => $data['id'] ) );
		$staff = (array) Staff::get( 'id', $data['id'] );

		/** if google calendar addon is installed */
		if ( Plugin::isAddonInstalledAndEnabled( self::$googleCalendarAddon ) && has_action( 'bookit_filter_connect_employee_google_calendar' ) ) {
			$staff = apply_filters( 'bookit_filter_connect_employee_google_calendar', $staff );
		}
		/** if google calendar addon is installed | end */

		wp_send_json_success(
			array(
				'id'      => $staff['id'],
				'staff'   => $staff,
				'message' => __( 'Staff disconnected!', 'bookit' ),
			)
		);
	}

	/** Get Staff Assosiated data by id **/
	public static function get_assosiated_total_data_by_id() {
		check_ajax_referer( 'bookit_get_staff_assosiated_total_data', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		if ( empty( $data['id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
		}

		$services     = Staff::get_staff_total_service( $data['id'] );
		$appointments = Appointments::get_total_active_assosiated_appointments( '', $data['id'] );

		$response = array(
			'total' => array(
				'services'     => $services,
				'appointments' => $appointments,
			),
		);
		wp_send_json_success( $response );
	}

	/**
	 * Delete the Staff
	 */
	public static function delete() {
		check_ajax_referer( 'bookit_delete_item', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_GET, self::getCleanRules() );

		if ( isset( $data['id'] ) ) {
			Staff::deleteStaff( $data['id'] );
			do_action( 'bookit_staff_deleted', $data['id'] );
			wp_send_json_success( array( 'message' => __( 'Staff Deleted!', 'bookit' ) ) );
		}
		wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
	}
}
