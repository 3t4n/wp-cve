<?php
/**
 *
 * TZWB Settings Class
 *
 * Registers all plugin settings with the WordPress Settings API.
 * Handles license key activation with the ThemeZee Store API.
 *
 * @link https://codex.wordpress.org/Settings_API
 * @package ThemeZee Widget Bundle
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Use class to avoid namespace collisions.
if ( ! class_exists( 'TZWB_Settings' ) ) :
	/**
	 * Settings Class
	 */
	class TZWB_Settings {
		/** Singleton *************************************************************/

		/**
		 * @var instance The one true TZWB_Settings instance
		 */
		private static $instance;

		/**
		 * @var options Plugin options array
		 */
		private $options;

		/**
		 * Creates or returns an instance of this class.
		 *
		 * @return TZWB_Settings A single instance of this class.
		 */
		public static function instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Plugin Setup
		 *
		 * @return void
		 */
		public function __construct() {

			// Register Settings.
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// Merge Plugin Options Array from Database with Default Settings Array.
			$this->options = wp_parse_args( get_option( 'tzwb_settings' , array() ), $this->default_settings() );
		}

		/**
		 * Get the value of a specific setting
		 *
		 * @param String $key     Settings key.
		 * @param String $default Default value.
		 * @return mixed
		 */
		public function get( $key, $default = false ) {
			$value = ! empty( $this->options[ $key ] ) ? $this->options[ $key ] : $default;
			return $value;
		}

		/**
		 * Get all settings
		 *
		 * @return array
		 */
		public function get_all() {
			return $this->options;
		}

		/**
		 * Retrieve default settings
		 *
		 * @return array
		 */
		public function default_settings() {

			$default_settings = array();

			foreach ( $this->get_registered_settings() as $key => $option ) :

				if ( 'multicheck' == $option['type'] ) :

					foreach ( $option['options'] as $index => $value ) :

						$default_settings[ $key ][ $index ] = isset( $option['default'] ) ? $option['default'] : false;

					endforeach;

				else :

					$default_settings[ $key ] = isset( $option['default'] ) ? $option['default'] : false;

				endif;

			endforeach;

			return $default_settings;
		}

		/**
		 * Register all settings sections and fields
		 *
		 * @return void
		 */
		function register_settings() {

			// Make sure that options exist in database.
			if ( false == get_option( 'tzwb_settings' ) ) {
				add_option( 'tzwb_settings' );
			}

			// Add Sections.
			add_settings_section( 'tzwb_settings_widgets', esc_html__( 'Widgets', 'themezee-widget-bundle' ), array( $this, 'widget_section_intro' ), 'tzwb_settings' );
			add_settings_section( 'tzwb_settings_modules', esc_html__( 'Modules', 'themezee-widget-bundle' ), array( $this, 'module_section_intro' ), 'tzwb_settings' );

			// Add Settings.
			foreach ( $this->get_registered_settings() as $key => $option ) :

				$name = isset( $option['name'] ) ? $option['name'] : '';
				$section = isset( $option['section'] ) ? $option['section'] : 'widgets';

				add_settings_field(
					'tzwb_settings[' . $key . ']',
					$name,
					is_callable( array( $this, $option['type'] . '_callback' ) ) ? array( $this, $option['type'] . '_callback' ) : array( $this, 'missing_callback' ),
					'tzwb_settings',
					'tzwb_settings_' . $section,
					array(
					'id'      => $key,
					'name'    => isset( $option['name'] ) ? $option['name'] : null,
					'desc'    => ! empty( $option['desc'] ) ? $option['desc'] : '',
					'size'    => isset( $option['size'] ) ? $option['size'] : null,
					'max'     => isset( $option['max'] ) ? $option['max'] : null,
					'min'     => isset( $option['min'] ) ? $option['min'] : null,
					'step'    => isset( $option['step'] ) ? $option['step'] : null,
					'options' => isset( $option['options'] ) ? $option['options'] : '',
					'default'     => isset( $option['default'] ) ? $option['default'] : '',
					)
				);

			endforeach;

			// Creates our settings in the options table.
			register_setting( 'tzwb_settings', 'tzwb_settings', array( $this, 'sanitize_settings' ) );
		}

		/**
		 * Widget Section Intro
		 *
		 * @return void
		 */
		function widget_section_intro() {
			esc_html_e( 'Activate all the widgets you want to use here.', 'themezee-widget-bundle' );
		}

		/**
		 * Module Section Intro
		 *
		 * @return void
		*/
		function module_section_intro() {
			esc_html_e( 'Activate all the modules you want to use.', 'themezee-widget-bundle' );
		}

		/**
		 * Sanitize the Plugin Settings
		 *
		 * @param array $input User Input.
		 * @return array
		 */
		function sanitize_settings( $input = array() ) {

			if ( empty( $_POST['_wp_http_referer'] ) ) {
				return $input;
			}

			$saved    = get_option( 'tzwb_settings', array() );
			if ( ! is_array( $saved ) ) {
				$saved = array();
			}

			$settings = $this->get_registered_settings();
			$input = $input ? $input : array();

			// Loop through each setting being saved and pass it through a sanitization filter.
			foreach ( $input as $key => $value ) :

				// Get the setting type (checkbox, select, etc).
				$type = isset( $settings[ $key ]['type'] ) ? $settings[ $key ]['type'] : false;

				// Sanitize user input based on setting type.
				if ( 'text' == $type ) :

					$input[ $key ] = sanitize_text_field( $value );

				elseif ( 'radio' == $type or 'select' == $type ) :

					$available_options = array_keys( $settings[ $key ]['options'] );
					$input[ $key ] = in_array( $value, $available_options, true ) ? $value : $settings[ $key ]['default'];

				elseif ( 'number' == $type ) :

					$input[ $key ] = floatval( $value );

				elseif ( 'checkbox' == $type or 'multicheck' == $type ) :

					$input[ $key ] = $value; // Validate Checkboxes later.

				else :

					// Default Sanitization.
					$input[ $key ] = esc_html( $value );

				endif;

			endforeach;

			// Ensure a value is always passed for every checkbox.
			if ( ! empty( $settings ) ) :
				foreach ( $settings as $key => $setting ) :

					// Single checkbox.
					if ( isset( $settings[ $key ]['type'] ) && 'checkbox' == $settings[ $key ]['type'] ) :
						$input[ $key ] = ! empty( $input[ $key ] );
					endif;

					// Multicheck list.
					if ( isset( $settings[ $key ]['type'] ) && 'multicheck' == $settings[ $key ]['type'] ) :
						foreach ( $settings[ $key ]['options'] as $index => $value ) :
							$input[ $key ][ $index ] = ! empty( $input[ $key ][ $index ] );
						endforeach;
					endif;

				endforeach;
			endif;

			return array_merge( $saved, $input );
		}

		/**
		 * Retrieve the array of plugin settings
		 *
		 * @return array
		 */
		function get_registered_settings() {

			$settings = array(
				'recent_comments' => array(
					'name' => esc_html__( 'Recent Comments', 'themezee-widget-bundle' ),
					'desc' => esc_html__( 'Enable Recent Comments Widget', 'themezee-widget-bundle' ),
					'section' => 'widgets',
					'type' => 'checkbox',
					'default' => true,
				),
				'recent_posts' => array(
					'name' => esc_html__( 'Recent Posts', 'themezee-widget-bundle' ),
					'desc' => esc_html__( 'Enable Recent Posts Widget', 'themezee-widget-bundle' ),
					'section' => 'widgets',
					'type' => 'checkbox',
					'default' => true,
				),
				'social_icons' => array(
					'name' => esc_html__( 'Social Icons', 'themezee-widget-bundle' ),
					'desc' => esc_html__( 'Enable Social Icons Widget', 'themezee-widget-bundle' ),
					'section' => 'widgets',
					'type' => 'checkbox',
					'default' => true,
				),
				'tabbed_content' => array(
					'name' => esc_html__( 'Tabbed Content', 'themezee-widget-bundle' ),
					'desc' => esc_html__( 'Enable Tabbed Content Widget', 'themezee-widget-bundle' ),
					'section' => 'widgets',
					'type' => 'checkbox',
					'default' => true,
				),
				'widget_visibility' => array(
					'name'    => esc_html__( 'Widget Visibility', 'themezee-widget-bundle' ),
					'desc'    => esc_html__( 'Add "Visibility" tab to widget settings to set conditions where the widget should be displayed', 'themezee-widget-bundle' ),
					'section' => 'modules',
					'type'    => 'checkbox',
					'default' => false,
				),
			);

			return apply_filters( 'tzwb_settings', $settings );
		}

		/**
		 * Checkbox Callback
		 *
		 * Renders checkboxes.
		 *
		 * @param array $args Arguments passed by the setting.
		 * @global $this->options Array of all the ThemeZee Widget Bundle Options.
		 * @return void
		 */
		function checkbox_callback( $args ) {

			$checked = isset( $this->options[ $args['id'] ] ) ? checked( 1, $this->options[ $args['id'] ], false ) : '';
			$html = '<input type="checkbox" id="tzwb_settings[' . $args['id'] . ']" name="tzwb_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>';
			$html .= '<label for="tzwb_settings[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';

			echo $html;
		}

		/**
		 * Multicheck Callback
		 *
		 * Renders multiple checkboxes.
		 *
		 * @param array $args Arguments passed by the setting.
		 * @global $this->options Array of all the ThemeZee Widget Bundle Options.
		 * @return void
		 */
		function multicheck_callback( $args ) {

			if ( ! empty( $args['options'] ) ) :
				foreach ( $args['options'] as $key => $option ) {
					$checked = isset( $this->options[ $args['id'] ][ $key ] ) ? checked( 1, $this->options[ $args['id'] ][ $key ], false ) : '';
					echo '<input name="tzwb_settings[' . $args['id'] . '][' . $key . ']" id="tzwb_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="1" ' . $checked . '/>&nbsp;';
					echo '<label for="tzwb_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
				}
			endif;
			echo '<p class="description">' . $args['desc'] . '</p>';
		}

		/**
		 * Text Callback
		 *
		 * Renders text fields.
		 *
		 * @param array $args Arguments passed by the setting.
		 * @global $this->options Array of all the ThemeZee Widget Bundle Options.
		 * @return void
		 */
		function text_callback( $args ) {

			if ( isset( $this->options[ $args['id'] ] ) ) {
				$value = $this->options[ $args['id'] ];
			} else { 			$value = isset( $args['default'] ) ? $args['default'] : '';
			}

			$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
			$html = '<input type="text" class="' . $size . '-text" id="tzwb_settings[' . $args['id'] . ']" name="tzwb_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
			$html .= '<p class="description">' . $args['desc'] . '</p>';

			echo $html;
		}

		/**
		 * Radio Callback
		 *
		 * Renders radio boxes.
		 *
		 * @param array $args Arguments passed by the setting.
		 * @global $this->options Array of all the ThemeZee Widget Bundle Options.
		 * @return void
		 */
		function radio_callback( $args ) {

			if ( ! empty( $args['options'] ) ) :
				foreach ( $args['options'] as $key => $option ) :
					$checked = false;

					if ( isset( $this->options[ $args['id'] ] ) && $this->options[ $args['id'] ] == $key ) {
						$checked = true;
					} elseif ( isset( $args['default'] ) && $args['default'] == $key && ! isset( $this->options[ $args['id'] ] ) ) {
						$checked = true;
					}

					echo '<input name="tzwb_settings[' . $args['id'] . ']"" id="tzwb_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked( true, $checked, false ) . '/>&nbsp;';
					echo '<label for="tzwb_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
				endforeach;
			endif;
			echo '<p class="description">' . $args['desc'] . '</p>';
		}

		/**
		 * Number Callback
		 *
		 * Renders number fields.
		 *
		 * @param array $args Arguments passed by the setting.
		 * @global $this->options Array of all the ThemeZee Widget Bundle Options.
		 * @return void
		 */
		function number_callback( $args ) {

			if ( isset( $this->options[ $args['id'] ] ) ) {
				$value = $this->options[ $args['id'] ];
			} else { 			$value = isset( $args['default'] ) ? $args['default'] : '';
			}

			$max  = isset( $args['max'] ) ? $args['max'] : 999999;
			$min  = isset( $args['min'] ) ? $args['min'] : 0;
			$step = isset( $args['step'] ) ? $args['step'] : 1;

			$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
			$html = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="tzwb_settings[' . $args['id'] . ']" name="tzwb_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
			$html .= '<p class="description">' . $args['desc'] . '</p>';

			echo $html;
		}

		/**
		 * Select Callback
		 *
		 * Renders select fields.
		 *
		 * @param array $args Arguments passed by the setting.
		 * @global $this->options Array of all the ThemeZee Widget Bundle Options.
		 * @return void
		 */
		function select_callback( $args ) {

			if ( isset( $this->options[ $args['id'] ] ) ) {
				$value = $this->options[ $args['id'] ];
			} else { 			$value = isset( $args['default'] ) ? $args['default'] : '';
			}

			$html = '<select id="tzwb_settings[' . $args['id'] . ']" name="tzwb_settings[' . $args['id'] . ']"/>';

			foreach ( $args['options'] as $option => $name ) :
				$selected = selected( $option, $value, false );
				$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
			endforeach;

			$html .= '</select>';
			$html .= '<p class="description">' . $args['desc'] . '</p>';

			echo $html;
		}

		/**
		 * Missing Callback
		 *
		 * If a function is missing for settings callbacks alert the user.
		 *
		 * @param array $args Arguments passed by the setting.
		 * @return void
		 */
		function missing_callback( $args ) {
			printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'themezee-widget-bundle' ), $args['id'] );
		}
	}

	// Run Setting Class.
	TZWB_Settings::instance();

endif;
