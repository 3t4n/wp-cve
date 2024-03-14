<?php

namespace EasyBooking;
use EasyBooking\Settings;

/**
*
* Admin: appearance settings.
* @version 3.0.6
*
**/

defined( 'ABSPATH' ) || exit;

class Settings_Appearance {

	private $settings;
	private $translations;

	public function __construct() {

		$this->settings = $this->get_settings();

		// Init strings for translations
		$this->translations = array(
            esc_html__( 'Calendar theme', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Background color', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Main color', 'woocommerce-easy-booking-system' ),
            esc_html__( 'Text color', 'woocommerce-easy-booking-system' )
        );

		add_action( 'admin_init', array( $this, 'settings' ) );
		add_action( 'easy_booking_settings_appearance_tab', array( $this, 'appearance_settings_tab' ), 10 );
		add_action( 'easy_booking_save_settings', array( $this, 'generate_css_files' ) );

	}

	/**
	*
	* Get array of appearance settings.
	* @return array - $settings
	*
	**/
	private function get_settings() {

		// Backward compatibility
		$wceb_settings = get_option( 'easy_booking_settings' );

		$settings = array(
			'calendar_theme'   => isset( $wceb_settings['easy_booking_calendar_theme'] ) ? $wceb_settings['easy_booking_calendar_theme'] : 'default',
			'background_color' => isset( $wceb_settings['easy_booking_background_color'] ) ? $wceb_settings['easy_booking_background_color'] : '#FFFFFF',
			'main_color'       => isset( $wceb_settings['easy_booking_main_color'] ) ? $wceb_settings['easy_booking_main_color'] : '#0089EC',
			'text_color'       => isset( $wceb_settings['easy_booking_text_color'] ) ? $wceb_settings['easy_booking_text_color'] : '#000000'
		);

		return $settings;

	}

	/**
	*
	* Init appearance settings.
	*
	**/
	public function settings() {

		$this->add_settings_sections();
		$this->register_settings();
		$this->add_settings_fields();

		// Init appearance settings the first time
		$this->init_settings();

	}
	
	/**
	*
	* Add appearance settings section.
	*
	**/
	private function add_settings_sections() {

		add_settings_section(
			'easy_booking_main_settings',
			'',
			array( $this, 'appearance_settings_section' ),
			'easy_booking_appearance_settings'
		);

	}

	/**
	*
	* Register appearance settings.
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
				'easy_booking_appearance_settings',
				'wceb_' . $setting,
				$args
			);

		}

	}

	/**
	*
	* Add appearance settings fields.
	*
	**/
	private function add_settings_fields() {

		$field_names = array_combine( array_keys( $this->settings ), array_values( $this->translations ) );

		foreach ( $field_names as $setting => $name ) {

			 add_settings_field(
				'wceb_' . $setting,
				$name,
				array( $this, $setting ),
				'easy_booking_appearance_settings',
				'easy_booking_main_settings'
			);

		}

	}

	/**
	*
	* Maybe init appearance settings.
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
	* Display appearance settings fields in "Appearance" tab.
	*
	**/
	public function appearance_settings_tab() {
		
		do_settings_sections( 'easy_booking_appearance_settings' );
		settings_fields( 'easy_booking_appearance_settings' );

	}

	/**
	*
	* Appearance settings section description.
	*
	**/
	public function appearance_settings_section() {
		echo '<p>' . esc_html__( 'Customize the calendar to make it look great with your theme.', 'woocommerce-easy-booking-system' ) . '</br>' . esc_html__( 'For better rendering, prefer a light background and a dark text color.', 'woocommerce-easy-booking-system' ) . '</p>';
	}

	/**
	*
	* "Calendar theme" option.
	*
	**/
	public function calendar_theme() {

		Settings::select( array(
			'id'          => 'calendar_theme',
			'name'        => 'wceb_calendar_theme',
			'value'       => get_option( 'wceb_calendar_theme' ) ? get_option( 'wceb_calendar_theme' ) : 'default',
			'options'     => array(
				'default' => esc_html__( 'Default', 'woocommerce-easy-booking-system' ),
				'classic' => esc_html__( 'Classic', 'woocommerce-easy-booking-system' )
			)
		));

	}

	/**
	*
	* "Background color" option.
	*
	**/
	public function background_color() {

		Settings::input( array(
			'type'  => 'text',
			'id'    => 'background_color',
			'name'  => 'wceb_background_color',
			'value' =>  get_option( 'wceb_background_color' ) ? get_option( 'wceb_background_color' ) : '#FFFFFF',
			'class' => 'color-field'
		));

	}

	/**
	*
	* "Main color" option.
	*
	**/
	public function main_color() {

		Settings::input( array(
			'type'  => 'text',
			'id'    => 'main_color',
			'name'  => 'wceb_main_color',
			'value' =>  get_option( 'wceb_main_color' ) ? get_option( 'wceb_main_color' ) : '#0089EC',
			'class' => 'color-field'
		));

	}

	/**
	*
	* "Text color" option.
	*
	**/
	public function text_color() {

		Settings::input( array(
			'type'  => 'text',
			'id'    => 'text_color',
			'name'  => 'wceb_text_color',
			'value' =>  get_option( 'wceb_text_color' ) ? get_option( 'wceb_text_color' ) : '#000000',
			'class' => 'color-field'
		));

	}

	/**
	*
	* Sanitize "Background color" option.
	*
	**/
	public function sanitize_background_color( $value ) {
		return sanitize_hex_color( $value );
	}

	/**
	*
	* Sanitize "Text color" option.
	*
	**/
	public function sanitize_text_color( $value ) {
		return sanitize_hex_color( $value );
	}

	/**
	*
	* Sanitize "Main color" option.
	*
	**/
	public function sanitize_main_color( $value ) {
		return sanitize_hex_color( $value );
	}

	/**
	*
	* Generate new CSS files for the calendar after saving appearance settings.
	*
	**/
	public function generate_css_files() {

		$plugin_dir = plugin_dir_path( WCEB_PLUGIN_FILE );

        $php_files = array(
			'default' => realpath( $plugin_dir . 'assets/css/dev/default.css.php' ),
			'classic' => realpath( $plugin_dir . 'assets/css/dev/classic.css.php' )
        );

        $blog_id = '';

        if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$blog_id = '.' . get_current_blog_id();
        }

		$css_files = array(
			'default' => realpath( $plugin_dir . 'assets/css/default' . $blog_id . '.min.css' ),
			'classic' => realpath( $plugin_dir . 'assets/css/classic' . $blog_id . '.min.css' )
        );

        if ( $php_files ) foreach ( $php_files as $theme => $php_file ) {
        	ob_start(); // Capture all output (output buffering)

	        require( $php_file ); // Generate CSS
	        
			$css          = ob_get_clean(); // Get generated CSS (output buffering)
			$minified_css = wceb_minify_css( $css ); // Minify CSS

	        if ( file_exists( $css_files[$theme] ) ) {

	        	if ( is_writable( $css_files[$theme] ) ) {
	        		file_put_contents( $css_files[$theme], $minified_css ); // Save it
	        	}

	        } else {

	        	$file = fopen( $plugin_dir . 'assets/css/' . $theme . $blog_id . '.min.css', 'a+' );
		        fwrite( $file, $minified_css );
		        fclose( $file );

	        }

        }

	}
	
}

new Settings_Appearance();