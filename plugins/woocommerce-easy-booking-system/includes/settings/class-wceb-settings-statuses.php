<?php

namespace EasyBooking;
use EasyBooking\Settings;

/**
*
* Admin: Booking statuses settings.
* @version 3.1.9
*
**/

defined( 'ABSPATH' ) || exit;

class Settings_Statuses {

	private $settings;
	private $translations;

	public function __construct() {

		$this->settings = $this->get_settings();

		// Init strings for translations
		$this->translations = array(
            esc_html__( 'Set "Start" booking status', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Keep "Start" status for', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Set "Processing" booking status', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Set "End" booking status', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Keep "End" status for', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Set "Completed" booking status', 'woocommerce-easy-booking-system' )
        );

		add_action( 'admin_init', array( $this, 'settings' ) );
		add_action( 'easy_booking_settings_statuses_tab', array( $this, 'booking_statuses_settings_tab' ), 10 );

	}

	/**
	*
	* Get array of booking statuses settings.
	* @return array - $settings
	*
	**/
	private function get_settings() {

		// Backward compatibility
		$wceb_settings = get_option( 'easy_booking_settings' );

		$settings = array(
			'set_start_booking_status'      => isset( $wceb_settings['easy_booking_start_status'] ) ? $wceb_settings['easy_booking_start_status'] : 'automatic',
			'keep_start_status_for'         => isset( $wceb_settings['easy_booking_start_status_change'] ) ? $wceb_settings['easy_booking_start_status_change'] : '0',
			'set_processing_booking_status' => isset( $wceb_settings['easy_booking_processing_status'] ) ? $wceb_settings['easy_booking_processing_status'] : 'automatic',
			'set_end_booking_status'        => 'automatic',
			'keep_end_status_for'           => isset( $wceb_settings['easy_booking_completed_status_change'] ) ? $wceb_settings['easy_booking_completed_status_change'] : '0',
			'set_completed_booking_status'  => isset( $wceb_settings['easy_booking_completed_status'] ) ? $wceb_settings['easy_booking_completed_status'] : 'automatic'
		);

		return $settings;

	}

	/**
	*
	* Init booking statuses settings.
	*
	**/
	public function settings() {

		$this->add_settings_sections();
		$this->register_settings();
		$this->add_settings_fields();

		// Init booking_statuses settings the first time
		$this->init_settings();

	}
	
	/**
	*
	* Add booking statuses settings section.
	*
	**/
	private function add_settings_sections() {

		add_settings_section(
			'easy_booking_main_settings',
			'',
			array( $this, 'booking_statuses_settings_section' ),
			'easy_booking_statuses_settings'
		);

	}

	/**
	*
	* Register booking statuses settings.
	*
	**/
	private function register_settings() {

		foreach ( $this->settings as $setting => $value ) {

			$function_name = 'sanitize_' . $setting;
			$args = array(
				'type'              => 'string',
				'description'       => '',
				'sanitize_callback' => method_exists( $this, $function_name ) ? array( $this, 'sanitize_' . $setting ) : 'sanitize_text_field',
				'show_in_rest'      => false
			);

			register_setting(
				'easy_booking_statuses_settings',
				'wceb_' . $setting,
				$args
			);

		}

	}

	/**
	*
	* Add booking statuses settings fields.
	*
	**/
	private function add_settings_fields() {

		$field_names = array_combine( array_keys( $this->settings ), array_values( $this->translations ) );

		foreach ( $field_names as $setting => $name ) {

			 add_settings_field(
				'wceb_' . $setting,
				$name,
				array( $this, $setting ),
				'easy_booking_statuses_settings',
				'easy_booking_main_settings'
			);

		}

	}

	/**
	*
	* Maybe init boking statuses settings.
	*
	**/
	private function init_settings() {

		foreach ( $this->settings as $setting => $value ) {

			if ( false === get_option( 'wceb_' . $setting ) ) {
				update_option( 'wceb_' . $setting, $value );
			}

		}

	}

	/**
	*
	* Display booking statuses settings fields in "Booking statuses" tab.
	*
	**/
	public function booking_statuses_settings_tab() {

		do_settings_sections( 'easy_booking_statuses_settings' );
		settings_fields( 'easy_booking_statuses_settings' );

	}

	/**
	*
	* Booking statuses settings section description.
	*
	**/
	public function booking_statuses_settings_section() {

		?>

		<p><?php printf( 
			esc_html__( 'Booking statuses will allow you to easily track your bookings in the "Reports" page. If you are not sure how to configure them, %scheck the documentation%s.', 'woocommerce-easy-booking-system' ),
			'<a href="https://easy-booking.pro/documentation/booking-statuses/">',
			'</a>'
			); ?>
		</p>
		
		<?php

	}

	/**
	*
	* "Set start booking status" option.
	*
	**/
	public function set_start_booking_status() {

		Settings::select( array(
			'id'          => 'set_start_booking_status',
			'name'        => 'wceb_set_start_booking_status',
			'value'       => get_option( 'wceb_set_start_booking_status' ) ? get_option( 'wceb_set_start_booking_status' ) : 'automatic',
			'options'     => array(
				'automatic' => __( 'Automatically', 'woocommerce-easy-booking-system' ),
				'manual'    => __( 'Manually', 'woocommerce-easy-booking-system' )
			)
		));

	}

	/**
	*
	* "Keep start booking status for" option.
	*
	**/
	public function keep_start_status_for() {

		Settings::input( array(
			'type'        => 'number',
			'id'          => 'keep_start_status_for',
			'name'        => 'wceb_keep_start_status_for',
			'description' => __( 'Day(s) before booking start date.', 'woocommerce-easy-booking-system' ),
			'value'       =>  get_option( 'wceb_keep_start_status_for' ) ? get_option( 'wceb_keep_start_status_for' ) : '0',
			'custom_attributes' => array(
				'min' => 0,
				'max' => 30
			)
		));

	}

	/**
	*
	* "Set processing booking status" option.
	*
	**/
	public function set_processing_booking_status() {

		Settings::select( array(
			'id'          => 'set_processing_booking_status',
			'name'        => 'wceb_set_processing_booking_status',
			'value'       => get_option( 'wceb_set_processing_booking_status' ) ? get_option( 'wceb_set_processing_booking_status' ) : 'automatic',
			'options'     => array(
				'automatic' => __( 'Automatically', 'woocommerce-easy-booking-system' ),
				'manual'    => __( 'Manually', 'woocommerce-easy-booking-system' )
			)
		));

	}

	/**
	*
	* "Set end booking status" option.
	*
	**/
	public function set_end_booking_status() {

		Settings::select( array(
			'id'          => 'set_end_booking_status',
			'name'        => 'wceb_set_end_booking_status',
			'value'       => get_option( 'wceb_set_end_booking_status' ) ? get_option( 'wceb_set_end_booking_status' ) : 'automatic',
			'options'     => array(
				'automatic' => __( 'Automatically', 'woocommerce-easy-booking-system' ),
				'manual'    => __( 'Manually', 'woocommerce-easy-booking-system' )
			)
		));

	}

	/**
	*
	* "Keep end booking status for" option.
	*
	**/
	public function keep_end_status_for() {

		Settings::input( array(
			'type'        => 'number',
			'id'          => 'keep_end_status_for',
			'name'        => 'wceb_keep_end_status_for',
			'description' => __( 'Day(s) after booking end date.', 'woocommerce-easy-booking-system' ),
			'value'       =>  get_option( 'wceb_keep_end_status_for' ) ? get_option( 'wceb_keep_end_status_for' ) : '0',
			'custom_attributes' => array(
				'min' => 0,
				'max' => 30
			)
		));

	}

	/**
	*
	* "Set completed booking status" option.
	*
	**/
	public function set_completed_booking_status() {

		Settings::select( array(
			'id'          => 'set_completed_booking_status',
			'name'        => 'wceb_set_completed_booking_status',
			'value'       => get_option( 'wceb_set_completed_booking_status' ) ? get_option( 'wceb_set_completed_booking_status' ) : 'automatic',
			'options'     => array(
				'automatic' => __( 'Automatically', 'woocommerce-easy-booking-system' ),
				'manual'    => __( 'Manually', 'woocommerce-easy-booking-system' )
			)
		));

	}

	/**
	*
	* Sanitize "Keep start booking status for" option.
	*
	**/
	public function sanitize_keep_start_status_for( $value ) {
		return Settings::sanitize_duration_field( $value, 0, 30 );
	}

	/**
	*
	* Sanitize "Keep end booking status for" option.
	*
	**/
	public function sanitize_keep_end_status_for( $value ) {
		return Settings::sanitize_duration_field( $value, 0, 30 );
	}
	
}

new Settings_Statuses();