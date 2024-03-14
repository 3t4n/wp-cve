<?php

namespace Bookit\Classes\Admin;

use Bookit\Classes\Base\User;
use Bookit\Classes\Database\Appointments;
use Bookit\Classes\Database\Customers;
use Bookit\Classes\Template;
use Bookit\Helpers\CleanHelper;

class CustomersController extends DashboardController {

	private static $sortFields = array( 'id', 'full_name', 'email', 'phone' );

	private static function getCleanRules() {
		return array(
			'id'        => array( 'type' => 'intval' ),
			'limit'     => array( 'type' => 'intval' ),
			'offset'    => array( 'type' => 'intval' ),
			'sort'      => array( 'type' => 'strval' ),
			'order'     => array( 'type' => 'strval' ),
			'search'    => array( 'type' => 'strval' ),
			'full_name' => array( 'type' => 'strval' ),
			'phone'     => array(
				'function' => array(
					'custom' => true,
					'name'   => 'custom_sanitize_phone',
				),
			),
			'email'     => array(
				'type'     => 'strval',
				'function' => array(
					'custom' => false,
					'name'   => 'sanitize_email',
				),
			),
		);
	}

	/**
	 * Display Rendered Template
	 * @return bool|string
	 */
	public static function render() {
		self::enqueue_styles_scripts();

		$user_args = array(
			'fields' => array( 'ID', 'display_name' ),
		);
		$wp_users  = get_users( $user_args );

		return Template::load_template(
			'dashboard/bookit-customers',
			array(
				'wp_users' => $wp_users,
				'page' => __( 'Customers', 'bookit' ),
			),
			true
		);
	}

	/** Check is email in wp users and password is correct */
	public static function validate_wp_user_if_exist() {
		check_ajax_referer( 'bookit_validate_wp_user_if_exist', 'nonce' );

		if ( ! isset( $_POST['email'] ) || ( isset( $_POST['email'] ) && empty( $_POST['email'] ) )
			|| ! isset( $_POST['password'] ) || ( isset( $_POST['password'] ) && empty( $_POST['password'] ) ) ) {
			wp_send_json_success(
				array(
					'exist' => false,
					'valid' => false,
				)
			);
		}

		$exist_customer = get_user_by( 'email', $_POST['email'] );
		if ( ! ( $exist_customer instanceof \WP_User ) ) {
			wp_send_json_success(
				array(
					'exist' => false,
					'valid' => false,
				)
			);
		}

		if ( wp_check_password( $_POST['password'], $exist_customer->data->user_pass, $exist_customer->ID ) ) {
			wp_send_json_success(
				array(
					'exist' => true,
					'valid' => true,
				)
			);
		}
		wp_send_json_success(
			array(
				'exist' => true,
				'valid' => false,
			)
		);
	}
	/**
	 * @param $data
	 * get wp user by email if exist
	 */
	public static function get_wp_user_by_email() {
		check_ajax_referer( 'bookit_get_wp_user_by_email', 'nonce' );

		if ( ! isset( $_POST['email'] ) || ( isset( $_POST['email'] ) && empty( $_POST['email'] ) ) ) {
			wp_send_json_success( array( 'exist' => false ) );
		}

		$exist_customer = get_user_by( 'email', $_POST['email'] );
		if ( $exist_customer instanceof \WP_User ) {
			wp_send_json_success( array( 'exist' => true ) );
		} else {
			wp_send_json_success( array( 'exist' => false ) );
		}
	}

	/**
	 * Get Customers with Pagination
	 */
	public static function get_customers() {
		check_ajax_referer( 'bookit_get_customers', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_GET, self::getCleanRules() );

		if ( ! empty( $data['limit'] ) ) {
			$response['customers'] = Customers::get_paged(
				$data['limit'],
				$data['offset'],
				( ! empty( $data['search'] ) ) ? "WHERE full_name LIKE '%{$data['search']}%' OR email LIKE '%{$data['search']}%' OR phone LIKE '%{$data['search']}%'" : '',
				( isset( $data['sort'] ) && in_array( $data['sort'], self::$sortFields ) ) ? $data['sort'] : '',
				( isset( $data['order'] ) && in_array( $data['order'], array( 'asc', 'desc' ) ) ) ? $data['order'] : ''
			);
			$response['total'] = ( ! empty( $data['search'] ) ) ? count( $response['customers'] ) : Customers::get_count();

			wp_send_json_success( $response );
		}

		wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
	}

	/**
	 * Validate post fields
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
			$errors['email'] = __( 'Not valid Email' );
		}

		$customer = Customers::get( 'email', $data['email'] );
		if ( null != $customer && ! isset( $data['id'] ) ) {
			$errors['email'] = __( 'Customer with such email already exist', 'bookit' );
		}

		if ( $data['full_name'] ) {
			if ( strlen( $data['full_name'] ) < 3 || strlen( $data['full_name'] ) > 25 ) {
				$errors['full_name'] = __( 'Full name must be between 3 and 25 characters long' );
			}
		} else {
			$errors['full_name'] = __( 'Full Name is required.' );
		}
		$settings_booking_type = get_option_by_path( 'bookit_settings.booking_type' );
		if ( isset( $data['from'] ) && 'calendar' == $data['from'] && 'registered' == $settings_booking_type ) {

			if ( empty( $data['password'] ) ) {
				$errors['password'] = __( 'Please enter a password' );
			}

			if ( false !== strpos( wp_unslash( $data['password'] ), '\\' ) ) {
				$errors['password'] = __( "Passwords may not contain the character '\\'" );
			}

			if ( ( ! empty( $data['password'] ) ) && $data['password'] != $data['password_confirmation'] ) {
				$errors['password_confirmation'] = __( 'Please enter the same password in both password fields' );
			}
		}

		if ( count( $errors ) > 0 ) {
			wp_send_json_error(
				array(
					'errors'  => $errors,
					'message' => __( 'Error occurred!', 'bookit' ),
				)
			);
		}
	}

	/**
	 * Create Customer from appointment
	 */
	public static function create() {
		check_ajax_referer( 'bookit_save_item', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		self::validate( $data );
		unset( $data['id'] );

		$exist = Customers::get( 'email', $data['email'] );
		if ( $exist ) {
			wp_send_json_error(
				array(
					'errors'  => array( 'email' => __( 'Customer with such email already exist.', 'bookit' ) ),
					'message' => __( 'Error occurred!', 'bookit' ),
				)
			);
		}

		/** save in WordPress users based on booking type value */
		$settings_booking_type = get_option_by_path( 'bookit_settings.booking_type' );
		if ( 'registered' == $settings_booking_type ) {
			$data['role']       = User::$customer_role;
			$data['wp_user_id'] = Customers::save_or_get_wp_user( $data );
		}

		unset( $data['role'] );
		unset( $data['password'] );
		unset( $data['password_confirmation'] );
		unset( $data['from'] );

		Customers::insert( $data );
		$customer = Customers::get( 'id', Customers::insert_id() );

		wp_send_json_success(
			array(
				'customer' => $customer,
				'message'  => __( 'Customer Saved!', 'bookit' ),
			)
		);
	}

	/**
	 * Save Customer
	 */
	public static function save() {
		check_ajax_referer( 'bookit_save_item', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );
		self::validate( $data );

		if ( ! empty( $data ) ) {
			if ( ! empty( $data['id'] ) ) {
				Customers::update( $data, array( 'id' => $data['id'] ) );
			} else {
				Customers::insert( $data );
				$data['id'] = Customers::insert_id();
			}

			do_action( 'bookit_customer_saved', $data['id'] );

			wp_send_json_success(
				array(
					'id'      => $data['id'],
					'message' => __( 'Customer Saved!', 'bookit' ),
				)
			);
		}

		wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
	}

	/** Get Service Assosiated data by id **/
	public static function get_assosiated_total_data_by_id() {
		check_ajax_referer( 'bookit_get_customer_assosiated_total_data', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_POST, self::getCleanRules() );

		if ( empty( $data['id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
		}

		$appointments = Appointments::get_total_active_assosiated_appointments( '', '', $data['id'] );

		$response = array( 'total' => array( 'appointments' => $appointments ) );
		wp_send_json_success( $response );
	}

	/**
	 * Delete the Customer
	 */
	public static function delete() {
		check_ajax_referer( 'bookit_delete_item', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$data = CleanHelper::cleanData( $_GET, self::getCleanRules() );

		if ( isset( $data['id'] ) ) {

			$id = $data['id'];
			Customers::deleteCustomer( $id );
			do_action( 'bookit_customer_deleted', $id );
			wp_send_json_success( array( 'message' => __( 'Customer Deleted!', 'bookit' ) ) );
		}

		wp_send_json_error( array( 'message' => __( 'Error occurred!', 'bookit' ) ) );
	}
}
