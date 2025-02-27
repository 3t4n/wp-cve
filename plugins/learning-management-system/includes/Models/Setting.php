<?php

/**
 * Abstract Setting API Class
 *
 * Admin Settings API used by Integrations, Shipping Methods, and Payment Gateways.
 *
 * @since 1.0.0
 *
 * @package  Masteriyo\Models
 */

namespace Masteriyo\Models;

defined( 'ABSPATH' ) || exit;

use Masteriyo\Database\Model;
use Masteriyo\Repository\SettingRepository;

/**
 * Setting class.r
 */
class Setting extends Model {


	/**
	 * This is the name of this object type.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $object_type = 'setting';

	/**
	 * Callbacks for sanitize.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $sanitize_callbacks = array(
		'general'        => array(
			'pages'         => array(
				'account_page_id'                 => 'absint',
				'courses_page_id'                 => 'absint',
				'checkout_page_id'                => 'absint',
				'learn_page_id'                   => 'absint',
				'instructor_registration_page_id' => 'absint',
				'course_thankyou_page'            => array(
					'display_type' => 'string',
					'page_id'      => 'absint',
					'custom_url'   => 'string',
				),
				'instructors_list_page_id'        => 'absint',
			),
			'course_access' => array(
				'enable_course_content_access_without_enrollment' => 'masteriyo_string_to_bool',
				'restrict_instructors' => 'masteriyo_string_to_bool',
			),
			'registration'  => array(
				'enable_student_registration'    => 'masteriyo_string_to_bool',
				'enable_instructor_registration' => 'masteriyo_string_to_bool',
				'enable_guest_checkout'          => 'masteriyo_string_to_bool',
			),
			'editor'        => array(
				'default_editor' => 'string',
			),
		),
		'learn_page'     => array(
			'general' => array(
				'logo'                   => 'absint',
				'auto_load_next_content' => 'masteriyo_string_to_bool',

			),
			'display' => array(
				'enable_questions_answers' => 'masteriyo_string_to_bool',
				'show_sidebar_initially'   => 'masteriyo_string_to_bool',
			),
		),
		'payments'       => array(
			'currency' => array(
				'number_of_decimals' => 'absint',
			),
		),
		'course_archive' => array(
			'display'               => array(
				'view_mode'     => 'sanitize_title',
				'enable_search' => 'masteriyo_string_to_bool',
				'per_page'      => 'absint',
				'per_row'       => 'absint',
			),
			'components_visibility' => array(
				'thumbnail'          => 'masteriyo_string_to_bool',
				'difficulty_badge'   => 'masteriyo_string_to_bool',
				'featured_ribbon'    => 'masteriyo_string_to_bool',
				'categories'         => 'masteriyo_string_to_bool',
				'course_title'       => 'masteriyo_string_to_bool',
				'author'             => 'masteriyo_string_to_bool',
				'author_avatar'      => 'masteriyo_string_to_bool',
				'author_name'        => 'masteriyo_string_to_bool',
				'rating'             => 'masteriyo_string_to_bool',
				'course_description' => 'masteriyo_string_to_bool',
				'metadata'           => 'masteriyo_string_to_bool',
				'course_duration'    => 'masteriyo_string_to_bool',
				'students_count'     => 'masteriyo_string_to_bool',
				'lessons_count'      => 'masteriyo_string_to_bool',
				'card_footer'        => 'masteriyo_string_to_bool',
				'price'              => 'masteriyo_string_to_bool',
				'enroll_button'      => 'masteriyo_string_to_bool',
			),
			'custom_template'       => array(
				'enable'          => 'masteriyo_string_to_bool',
				'template_source' => 'sanitize_title',
				'template_id'     => 'absint',
			),
		),
		'single_course'  => array(
			'display'         => array(
				'enable_review'                     => 'masteriyo_string_to_bool',
				'enable_review_enrolled_users_only' => 'masteriyo_string_to_bool',
				'course_visibility'                 => 'masteriyo_string_to_bool',
			),
			'related_courses' => array(
				'enable' => 'masteriyo_string_to_bool',
			),
			'custom_template' => array(
				'enable'          => 'masteriyo_string_to_bool',
				'template_source' => 'sanitize_title',
				'template_id'     => 'absint',
			),
		),
		'advance'        => array(
			'permalinks' => array(
				'category_base'           => 'sanitize_title',
				'tag_base'                => 'sanitize_title',
				'difficulty_base'         => 'sanitize_title',
				'single_course_permalink' => 'sanitize_text',
			),

			'checkout'   => array(
				'pay'                        => 'sanitize_title',
				'order_received'             => 'sanitize_title',
				'add_payment_method'         => 'sanitize_title',
				'delete_payment_method'      => 'sanitize_title',
				'set_default_payment_method' => 'sanitize_title',
			),
		),
		'quiz'           => array(
			'display' => array(
				'quiz_completion_button' => 'masteriyo_string_to_bool',
				'quiz_review_visibility' => 'masteriyo_string_to_bool',
			),
			'styling' => array(
				'questions_display_per_page' => 'absint',
			),
		),
		'payments'       => array(
			'offline'         => array(
				'enable' => 'masteriyo_string_to_bool',
			),
			'paypal'          => array(
				'enable'                  => 'masteriyo_string_to_bool',
				'ipn_email_notifications' => 'masteriyo_string_to_bool',
				'sandbox'                 => 'masteriyo_string_to_bool',
				'debug'                   => 'masteriyo_string_to_bool',
			),
			'checkout_fields' => array(
				'address_1'     => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'address_2'     => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'company'       => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'country'       => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'customer_note' => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'phone'         => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'postcode'      => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'state'         => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'city'          => array(
					'enable' => 'masteriyo_string_to_bool',
				),
			),
		),
		'emails'         => array(
			'general'    => array(
				'enable' => 'masteriyo_string_to_bool',
			),
			'admin'      => array(
				'new_order'        => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'instructor_apply' => array(
					'enable' => 'masteriyo_string_to_bool',
				),
			),
			'instructor' => array(
				'instructor_registration'   => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'instructor_apply_approved' => array(
					'enable' => 'masteriyo_string_to_bool',
				),
			),
			'student'    => array(
				'student_registration'      => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'completed_order'           => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'onhold_order'              => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'cancelled_order'           => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'completed_course'          => array(
					'enable' => 'masteriyo_string_to_bool',
				),
				'instructor_apply_rejected' => array(
					'enable' => 'masteriyo_string_to_bool',
				),
			),
		),
		'advance'        => array(
			'uninstall'          => array(
				'remove_data' => 'masteriyo_string_to_bool',
			),
			'tracking'           => array(
				'allow_usage' => 'masteriyo_string_to_bool',
			),
			'gdpr'               => array(
				'enable'  => 'masteriyo_string_to_bool',
				'message' => 'sanitize_text_field',
			),
			'openai'             => array(
				'api_key' => 'sanitize_text_field',
			),
			'email_verification' => array(
				'enable' => 'masteriyo_string_to_bool',
			),
		),
		'accounts_page'  => array(
			'display' => array(
				'enable_history_page' => 'masteriyo_string_to_bool',
			),
		),
		'notification'   => array(
			'student' => array(
				'course_enroll'   => array(
					'enable'  => 'masteriyo_string_to_bool',
					'content' => 'wp_kses_post',
				),
				'course_complete' => array(
					'enable'  => 'masteriyo_string_to_bool',
					'content' => 'wp_kses_post',
				),
				'created_order'   => array(
					'enable'  => 'masteriyo_string_to_bool',
					'content' => 'wp_kses_post',
				),
				'completed_order' => array(
					'enable'  => 'masteriyo_string_to_bool',
					'content' => 'wp_kses_post',
				),
				'onhold_order'    => array(
					'enable'  => 'masteriyo_string_to_bool',
					'content' => 'wp_kses_post',
				),
				'cancelled_order' => array(
					'enable'  => 'masteriyo_string_to_bool',
					'content' => 'wp_kses_post',
				),
			),
		),
	);

	/**
	 * The posted settings data. When empty, $_POST data will be used.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $data = array(
		'general'        => array(
			'styling'       => array(
				'primary_color' => '#4584FF',
				'theme'         => 'minimum',
			),
			'widgets_css'   => '',
			'pages'         => array(
				'courses_page_id'                 => '',
				'account_page_id'                 => '',
				'checkout_page_id'                => '',
				'learn_page_id'                   => '',
				'instructor_registration_page_id' => '',
				'course_thankyou_page'            => array(
					'display_type' => '',
					'page_id'      => 0,
					'custom_url'   => '',
				),
				'instructors_list_page_id'        => '',
			),
			'course_access' => array(
				'enable_course_content_access_without_enrollment' => false,
				'restrict_instructors' => true,
			),
			'registration'  => array(
				'enable_student_registration'    => true,
				'enable_instructor_registration' => true,
				'enable_guest_checkout'          => true,
			),
			'editor'        => array(
				'default_editor' => 'classic_editor',
			),
		),
		'course_archive' => array(
			'display'               => array(
				'view_mode'      => 'grid-view',
				'enable_search'  => true,
				'per_page'       => 12,
				'per_row'        => 3,
				'thumbnail_size' => 'masteriyo_thumbnail',
			),
			'components_visibility' => array(
				'thumbnail'          => true,
				'difficulty_badge'   => true,
				'featured_ribbon'    => true,
				'categories'         => true,
				'course_title'       => true,
				'author'             => true,
				'author_avatar'      => true,
				'author_name'        => true,
				'rating'             => true,
				'course_description' => true,
				'metadata'           => true,
				'course_duration'    => true,
				'students_count'     => true,
				'lessons_count'      => true,
				'card_footer'        => true,
				'price'              => true,
				'enroll_button'      => true,
			),
			'custom_template'       => array(
				'enable'          => false,
				'template_source' => 'elementor',
				'template_id'     => 0,
			),
		),
		'single_course'  => array(
			'display'         => array(
				'enable_review'                     => true,
				'enable_review_enrolled_users_only' => false,
				'course_visibility'                 => false,
			),
			'related_courses' => array(
				'enable' => true,
			),
			'custom_template' => array(
				'enable'          => false,
				'template_source' => 'elementor',
				'template_id'     => 0,
			),
		),
		'learn_page'     => array(
			'general' => array(
				'logo_id'                => '',
				'auto_load_next_content' => false,

			),
			'display' => array(
				'enable_questions_answers' => true,
				'show_sidebar_initially'   => false,
			),
		),
		'payments'       => array(
			'store'           => array(
				'country'       => '',
				'city'          => '',
				'state'         => '',
				'address_line1' => '',
				'address_line2' => '',
			),
			'currency'        => array(
				'currency'           => 'USD',
				'currency_position'  => 'left',
				'thousand_separator' => ',',
				'decimal_separator'  => '.',
				'number_of_decimals' => 2,
			),
			'offline'         => array(
				// Offline payment
				'enable'       => false,
				'title'        => 'Offline payment',
				'description'  => 'Pay with offline payment.',
				'instructions' => 'Pay with offline payment',
			),
			'paypal'          => array(
				// Standard Paypal
				'enable'                  => false,
				'title'                   => 'Paypal',
				'description'             => 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.',
				'ipn_email_notifications' => true,
				'sandbox'                 => false,
				'email'                   => '',
				'receiver_email'          => '',
				'identity_token'          => '',
				'invoice_prefix'          => 'masteriyo-',
				'payment_action'          => 'sale',
				'image_url'               => '',
				'debug'                   => false,
				'sandbox_api_username'    => '',
				'sandbox_api_password'    => '',
				'live_api_username'       => '',
				'live_api_password'       => '',
				'live_api_signature'      => '',
				'sandbox_api_signature'   => '',

			),
			'checkout_fields' => array(
				// Checkout Fields
				'address_1'     => false,
				'address_2'     => false,
				'company'       => false,
				'country'       => false,
				'customer_note' => false,
				'phone'         => false,
				'postcode'      => false,
				'state'         => false,
				'city'          => false,
			),
		),
		'quiz'           => array(
			'display' => array(
				'quiz_completion_button' => false,
				'quiz_review_visibility' => false,
			),
			'styling' => array(
				'questions_display_per_page' => 5,
			),
			'general' => array(
				'quiz_access' => 'default',
			),
		),
		'emails'         => array(
			'general'    => array(
				'enable'     => true,
				'from_name'  => '',
				'from_email' => '',
			),
			'admin'      => array(
				'new_order'            => array(
					'enable'     => true,
					'recipients' => array(),
					'subject'    => 'You made a sale!',
					'content'    => '',
				),
				'new_withdraw_request' => array(
					'enable'     => true,
					'recipients' => array(),
					'subject'    => 'New withdraw request!',
					'content'    => '',
				),
				'instructor_apply'     => array(
					'enable'     => true,
					'recipients' => array(),
					'subject'    => 'A student has applied for instructor status.',
					'content'    => '',
				),
			),
			'instructor' => array(
				'instructor_registration'   => array(
					'enable'     => true,
					'recipients' => array(),
					'subject'    => 'Registration Complete!',
					'content'    => '',
				),
				'withdraw_request_pending'  => array(
					'enable'     => true,
					'recipients' => array(),
					'subject'    => 'Withdraw request pending!',
					'content'    => '',
				),
				'withdraw_request_approved' => array(
					'enable'     => true,
					'recipients' => array(),
					'subject'    => 'Withdraw request approved!',
					'content'    => '',
				),
				'withdraw_request_rejected' => array(
					'enable'     => true,
					'recipients' => array(),
					'subject'    => 'Withdraw request rejected!',
					'content'    => '',
				),
				'instructor_apply_approved' => array(
					'enable'     => true,
					'recipients' => array(),
					'subject'    => 'Exciting News! Your Application for Instructor Status Has Been Approved!',
					'content'    => '',
				),
			),
			'student'    => array(
				'student_registration'      => array(
					'enable'  => true,
					'subject' => 'Registration Complete!',
					'content' => '',
				),
				'instructor_apply_rejected' => array(
					'enable'  => true,
					'subject' => 'Update Regarding Your Application for Instructor Status',
					'content' => '',
				),
				'completed_order'           => array(
					'enable'  => true,
					'subject' => 'Thanks for your purchase!',
					'content' => '',
				),
				'onhold_order'              => array(
					'enable'  => true,
					'subject' => 'Order On-Hold!',
					'content' => '',
				),
				'cancelled_order'           => array(
					'enable'     => true,
					'recipients' => array(),
					'subject'    => 'Order Cancelled!',
					'content'    => '',
				),
			),
		),
		'notification'   => array(
			'student' => array(
				'course_enroll'   => array(
					'type'    => 'course_enroll',
					'content' => 'You have successfully enrolled into this course.',
				),
				'course_complete' => array(
					'type'    => 'course_complete',
					'content' => 'You have successfully completed this course.',
				),
				'created_order'   => array(
					'type'    => 'created_order',
					'content' => 'Your order is successfully created.',
				),
				'completed_order' => array(
					'type'    => 'completed_order',
					'content' => 'Your order is completed.',
				),
				'onhold_order'    => array(
					'type'    => 'onhold_order',
					'content' => 'Your order is on-hold.',
				),
				'cancelled_order' => array(
					'type'    => 'cancelled_order',
					'content' => 'Your order is cancelled.',
				),
			),
		),
		'advance'        => array(
			'permalinks'         => array(
				'category_base'           => 'course-category',
				'tag_base'                => 'course-tag',
				'difficulty_base'         => 'course-difficulty',
				'single_course_permalink' => 'course',
			),
			// Checkout endpoints.
			'checkout'           => array(
				'pay'                        => 'order-pay',
				'order_received'             => 'order-received',
				'add_payment_method'         => 'add-payment-method',
				'delete_payment_method'      => 'delete-payment-method',
				'set_default_payment_method' => 'set-default-payment-method',
			),
			'debug'              => array(
				'template_debug' => false,
				'debug'          => false,
			),
			'uninstall'          => array(
				'remove_data' => false,
			),
			'tracking'           => array(
				'allow_usage' => false,
			),
			'gdpr'               => array(
				'enable'  => false,
				'message' => "Check the box to confirm you've read our",
			),
			'openai'             => array(
				'api_key' => '',
			),
			'email_verification' => array(
				'enable' => true,
			),
		),
		'accounts_page'  => array(
			'display' => array(
				'enable_history_page' => true,
			),
		),
	);


	/**
	 * Get the setting if ID
	 *
	 * @since 1.0.0
	 *
	 * @param SettingRepository $setting_repository Setting Repository,
	 */
	public function __construct( SettingRepository $setting_repository ) {
		$this->repository = $setting_repository;
		$this->set_default_values();
	}

	/**
	 * Set default values.
	 *
	 * @since 1.3.4
	 */
	protected function set_default_values() {
		if ( empty( trim( strval( $this->get( 'email.general.from_email' ) ) ) ) ) {
			$this->set( 'emails.general.from_email', get_bloginfo( 'admin_email' ) );
		}

		if ( empty( trim( strval( $this->get( 'email.general.from_name' ) ) ) ) ) {
			$this->set( 'emails.general.from_name', get_bloginfo( 'name' ) );
		}
	}

			/**
			 * Get data.
			 *
			 * @since 1.0.0
			 *
			 * @return array
			 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * Set data.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data
	 */
	public function set_data( $data ) {
		$data_dot_arr = masteriyo_array_dot( $data );

		foreach ( $data_dot_arr as $prop => $value ) {
			$this->set( $prop, $value );
		}

		$this->set_default_values();
	}

	/**
	 * Sanitize the settings
	 *
	 * @since 1.0.0
	 *
	 * @param string $prop    Name of prop to set.
	 * @param mixed  $value   Value of the prop.
	 *
	 * @return mixed
	 */
	protected function sanitize( $prop, $value ) {
		$callback = masteriyo_array_get( $this->sanitize_callbacks, $prop );

		if ( is_callable( $callback ) ) {
			$value = call_user_func_array( $callback, array( $value ) );
		}

		return $value;
	}

	/**
	 * Sets a prop for a setter method.
	 *
	 * @since 1.0.0
	 * @param string $prop    Name of prop to set.
	 * @param mixed  $value   Value of the prop.
	 */
	public function set( $prop, $value ) {
		$value = $this->sanitize( $prop, $value );
		masteriyo_array_set( $this->data, $prop, $value );
	}

	/**
	 * Gets a prop for a getter method.
	 *
	 * @since  1.0.0
	 * @param  string $prop Name of prop to get.
	 * @param  string $context What the value is for. Valid values are 'view' and 'edit'. What the value is for. Valid values are view and edit.
	 * @return mixed
	 */
	public function get( $prop, $context = 'view' ) {
		if ( empty( $prop ) ) {
			$value = $this->data;
		} else {
			$value = masteriyo_array_get( $this->data, $prop );
		}

		if ( 'view' === $context ) {
			/**
			 * Filters setting value.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed $value Setting value.
			 * @param string $prop Setting name.
			 * @param Masteriyo\Models\Setting $setting Setting object.
			 */
			$value = apply_filters( 'masteriyo_get_setting_value', $value, $prop, $this );
		}

		return $value;
	}

	/**
	 * Reset defaults.
	 *
	 * @since 1.4.2
	 */
	public function reset() {
		$setting    = masteriyo( 'setting' );
		$this->data = $setting->get_data();
	}
}
