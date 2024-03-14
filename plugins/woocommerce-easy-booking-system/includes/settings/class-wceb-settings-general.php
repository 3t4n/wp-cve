<?php

namespace EasyBooking;
use EasyBooking\Settings;

/**
*
* Admin: General settings.
* @version 3.0.6
*
**/

defined( 'ABSPATH' ) || exit;

class Settings_General {

	private $settings;
	private $translations;

	public function __construct() {

		$this->settings = $this->get_settings();

		// Init strings for translations
		$this->translations = array(
            esc_html__( 'Make all products bookable?', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Number of dates to select', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Booking mode', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Booking duration', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Minimum duration', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Maximum duration', 'woocommerce-easy-booking-system' ),
            esc_html__( 'First available date', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Last available date', 'woocommerce-easy-booking-system' )
        );

		add_action( 'admin_init', array( $this, 'settings' ) );
		add_action( 'easy_booking_settings_general_tab', array( $this, 'general_settings_tab' ), 10 );
		add_action( 'easy_booking_save_settings', array( $this, 'maybe_make_all_products_bookable' ) );

	}

	/**
	*
	* Get array of general settings.
	* @return array | $settings
	*
	**/
	private function get_settings() {

		// Backward compatibility
		$wceb_settings = get_option( 'easy_booking_settings' );

		$settings = array(
			'all_bookable'            => isset( $wceb_settings['easy_booking_all_bookable'] ) ? $wceb_settings['easy_booking_all_bookable'] : 'no',
			'number_of_dates'         => isset( $wceb_settings['easy_booking_dates'] ) ? $wceb_settings['easy_booking_dates'] : 'two',
			'booking_mode'            => isset( $wceb_settings['easy_booking_calc_mode'] ) ? $wceb_settings['easy_booking_calc_mode'] : 'nights',
			'booking_duration'        => isset( $wceb_settings['easy_booking_duration'] ) ? $wceb_settings['easy_booking_duration'] : '1',
			'booking_min'             => isset( $wceb_settings['easy_booking_booking_min'] ) ? $wceb_settings['easy_booking_booking_min'] : '0',
			'booking_max'             => isset( $wceb_settings['easy_booking_booking_max'] ) ? $wceb_settings['easy_booking_booking_max'] : '0',
			'first_available_date'    => isset( $wceb_settings['easy_booking_first_available_date'] ) ? $wceb_settings['easy_booking_first_available_date'] : '0',
			'last_available_date'     => isset( $wceb_settings['easy_booking_last_available_date'] ) ? $wceb_settings['easy_booking_last_available_date'] : '1825'
		);

		return $settings;

	}

	/**
	*
	* Init general settings.
	*
	**/
	public function settings() {

		$this->add_settings_sections();
		$this->register_settings();
		$this->add_settings_fields();

		// Init general settings the first time
		$this->init_settings();

	}
	
	/**
	*
	* Add general settings section.
	*
	**/
	private function add_settings_sections() {

		add_settings_section(
			'easy_booking_main_settings',
			'',
			'',
			'easy_booking_general_settings'
		);

	}

	/**
	*
	* Register general settings.
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
				'easy_booking_general_settings',
				'wceb_' . $setting,
				$args
			);

		}

	}

	/**
	*
	* Add general settings fields.
	*
	**/
	private function add_settings_fields() {

		$field_names = array_combine( array_keys( $this->settings ), array_values( $this->translations ) );

		foreach ( $field_names as $setting => $name ) {

			 add_settings_field(
				'wceb_' . $setting,
				$name,
				array( $this, $setting ),
				'easy_booking_general_settings',
				'easy_booking_main_settings'
			);

		}

	}

	/**
	*
	* Maybe init general settings.
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
	* Display general settings fields in "General" tab.
	*
	**/
	public function general_settings_tab() {
		
		do_settings_sections( 'easy_booking_general_settings' );
		settings_fields( 'easy_booking_general_settings' );

	}

	/**
	*
	* "All bookable" option.
	*
	**/
	public function all_bookable() {

		Settings::checkbox( array(
			'id'          => 'all_bookable',
			'name'        => 'wceb_all_bookable',
			'description' => __( 'If checked, any new or modified product will be automatically bookable.', 'woocommerce-easy-booking-system' ),
			'value'       => get_option( 'wceb_all_bookable' ) ? get_option( 'wceb_all_bookable' ) : '',
			'cbvalue'     => 'yes'
		));

	}

	/**
	*
	* "Number of dates" option.
	*
	**/
	public function number_of_dates() {

		Settings::select( array(
			'id'          => 'number_of_dates',
			'name'        => 'wceb_number_of_dates',
			'description' => __( 'Customizable at product level.', 'woocommerce-easy-booking-system' ),
			'value'       => get_option( 'wceb_number_of_dates' ) ? get_option( 'wceb_number_of_dates' ) : 'two',
			'options'     => array(
				'one' => __( 'One', 'woocommerce-easy-booking-system' ),
				'two' => __( 'Two', 'woocommerce-easy-booking-system' )
			)
		));

	}

	/**
	*
	* "Booking mode" option.
	*
	**/
	public function booking_mode() {

		Settings::select( array(
			'id'          => 'booking_mode',
			'name'        => 'wceb_booking_mode',
			'value'       => get_option( 'wceb_booking_mode' ) ? get_option( 'wceb_booking_mode' ) : 'nights',
			'description' => __( 'Rent your products by day or by night (i.e. 5 days = 4 nights).' , 'woocommerce-easy-booking-system' ),
			'options'     => array(
				'days'   => __('Days', 'woocommerce-easy-booking-system'),
				'nights' => __('Nights', 'woocommerce-easy-booking-system')
			)
		));

	}

	/**
	*
	* "Booking duration" option.
	*
	**/
	public function booking_duration() {

		Settings::input( array(
			'type'              => 'number',
			'id'                => 'booking_duration',
			'name'              => 'wceb_booking_duration',
			'description'       => __( 'Number of consecutive days/nights forming a block. Only for two dates selection. Customizable at product level.', 'woocommerce-easy-booking-system' ),
			'value'             => get_option( 'wceb_booking_duration' ) ? get_option( 'wceb_booking_duration' ) : '1',
			'custom_attributes' => array(
				'step' => '1',
                'min'  => '1',
                'max'  => '366'
			)
		));

	}

	/**
	*
	* "Minimum booking duration" option.
	*
	**/
	public function booking_min() {

		Settings::input( array(
			'type'              => 'number',
			'id'                => 'booking_min',
			'name'              => 'wceb_booking_min',
			'value'             => get_option( 'wceb_booking_min' ) ? get_option( 'wceb_booking_min' ) : '0',
			'description'       => __( 'Minimum number of blocks to select. Leave 0 or empty to set no minmum. Customizable at product level.', 'woocommerce-easy-booking-system' ),
			'custom_attributes' => array(
				'step' => '1',
                'min'  => '0',
                'max'  => '3650'
			)
		));

	}

	/**
	*
	* "Maximum booking duration" option.
	*
	**/
	public function booking_max() {

		Settings::input( array(
			'type'              => 'number',
			'id'                => 'booking_max',
			'name'              => 'wceb_booking_max',
			'value'             => get_option( 'wceb_booking_max' ) ? get_option( 'wceb_booking_max' ) : '0',
			'description'       => __( 'Maximum number of blocks to select. Leave 0 or empty to set no maximum. Customizable at product level.', 'woocommerce-easy-booking-system' ),
			'custom_attributes' => array(
				'step' => '1',
                'min'  => '0',
                'max'  => '3650'
			)
		));

	}

	/**
	*
	* "First available date" option.
	*
	**/
	public function first_available_date() {

		Settings::input( array(
			'type'              => 'number',
			'id'                => 'first_available_date',
			'name'              => 'wceb_first_available_date',
			'value'             => get_option( 'wceb_first_available_date' ) ? get_option( 'wceb_first_available_date' ) : '0',
			'description'       => __( 'First available date, relative to the current day. Leave 0 or empty to keep the current day. Customizable at product level.', 'woocommerce-easy-booking-system' ),
			'custom_attributes' => array(
				'min' => '0',
				'max' => '3650'
			)
		));

	}

	/**
	*
	* "Last available date" option.
	*
	**/
	public function last_available_date() {

		Settings::input( array(
			'type'              => 'number',
			'id'                => 'last_available_date',
			'name'              => 'wceb_last_available_date',
			'value'             => get_option( 'wceb_last_available_date' ) ? get_option( 'wceb_last_available_date' ) : '1825',
			'description'       => __( 'Last available date, relative to the current day. Max: 3650 days (10 years).', 'woocommerce-easy-booking-system' ),
			'custom_attributes' => array(
				'min' => '1',
				'max' => '3650'
			)
		));
		
	}

	/**
	*
	* Sanitize "All bookable" option.
	*
	**/
	public function sanitize_all_bookable( $value ) {
		return Settings::sanitize_checkbox( $value );
	}

	/**
	*
	* Sanitize "Custom booking duration" option.
	*
	**/
	public function sanitize_booking_duration( $value ) {
		return Settings::sanitize_duration_field( $value );
	}

	/**
	*
	* Sanitize "Minimum booking duration" option.
	*
	**/
	public function sanitize_booking_min( $value ) {
		return Settings::sanitize_duration_field( $value );
	}

	/**
	*
	* Sanitize "Maximum booking duration" option.
	*
	**/
	public function sanitize_booking_max( $value ) {
		return Settings::sanitize_duration_field( $value );
	}

	/**
	*
	* Sanitize "First available date" option.
	*
	**/
	public function sanitize_first_available_date( $value ) {
		return Settings::sanitize_duration_field( $value );
	}

	/**
	*
	* Sanitize "Last available date" option.
	*
	**/
	public function sanitize_last_available_date( $value ) {
		return Settings::sanitize_duration_field( $value );
	}

	/**
	*
	* Maybe make all products and variations bookable when saving settings.
	*
	**/
	public function maybe_make_all_products_bookable() {

		$all_bookable = get_option( 'wceb_all_bookable' );

		if ( ! empty( $all_bookable ) && $all_bookable === 'yes' ) {

			$args = array(
	            'post_type'      => array( 'product', 'product_variation' ),
	            'posts_per_page' => -1,
	            'post_status'    => 'any'
	        );

	        $query = new \WP_Query( $args );

	        if ( $query ) while ( $query->have_posts() ) : $query->the_post();
	        	global $post;

	        	update_post_meta( $post->ID, '_bookable', 'yes' );

	        endwhile;

        }

	}
	
}

new Settings_General();