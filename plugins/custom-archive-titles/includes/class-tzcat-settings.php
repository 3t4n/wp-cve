<?php
/**
 * TZCAT Settings Class
 *
 * Registers all plugin settings with the WordPress Settings API.
 *
 * @link https://codex.wordpress.org/Settings_API
 * @package ThemeZee Custom Archive Titles
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * TZCAT Settings Class
 */
class TZCAT_Settings {
	/** Singleton *************************************************************/

	/**
	 * @var instance The one true TZCAT_Settings instance
	 */
	private static $instance;

	/**
	 * @var options Plugin options array
	 */
	private $options;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return TZCAT_Settings A single instance of this class.
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

		// Register settings.
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Merge Plugin Options Array from Database with Default Settings Array.
		$this->options = wp_parse_args( get_option( 'tzcat_settings' , array() ), $this->default_settings() );
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

		$default_settings = array(
			'category_title' => __( 'Category: %s' ),
			'tag_title'      => __( 'Tag: %s' ),
			'author_title'   => __( 'Author: %s' ),
			'year_title'     => __( 'Year: %s' ),
			'month_title'    => __( 'Month: %s' ),
			'day_title'      => __( 'Day: %s' ),
		);

		return $default_settings;
	}

	/**
	 * Register all settings sections and fields
	 *
	 * @return void
	 */
	function register_settings() {

		// Make sure that options exist in database.
		if ( false === get_option( 'tzcat_settings' ) ) {
			add_option( 'tzcat_settings' );
		}

		// Add Sections.
		add_settings_section( 'tzcat_settings_general', esc_html__( 'Archive Titles', 'custom-archive-titles' ), '__return_false', 'tzcat_settings' );

		// Add Settings.
		foreach ( $this->get_registered_settings() as $key => $option ) :

			$name = isset( $option['name'] ) ? $option['name'] : '';
			$section = isset( $option['section'] ) ? $option['section'] : 'widgets';

			add_settings_field(
				'tzcat_settings[' . $key . ']',
				$name,
				is_callable( array( $this, $option['type'] . '_callback' ) ) ? array( $this, $option['type'] . '_callback' ) : array( $this, 'missing_callback' ),
				'tzcat_settings',
				'tzcat_settings_' . $section,
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
		register_setting( 'tzcat_settings', 'tzcat_settings', array( $this, 'sanitize_settings' ) );
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

		$saved = get_option( 'tzcat_settings', array() );
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
			if ( 'text' === $type ) :

				$input[ $key ] = sanitize_text_field( $value );

			elseif ( 'radio' === $type or 'select' === $type ) :

				$available_options = array_keys( $settings[ $key ]['options'] );
				$input[ $key ] = in_array( $value, $available_options, true ) ? $value : $settings[ $key ]['default'];

			elseif ( 'checkbox' === $type ) :

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

			endforeach;
		endif;

		// Reset to default settings.
		if ( isset( $_POST['tzcat_reset_defaults'] ) ) {
			$input = $this->default_settings();
		}

		return array_merge( $saved, $input );
	}

	/**
	 * Retrieve the array of plugin settings
	 *
	 * @return array
	 */
	function get_registered_settings() {

		// Get default settings.
		$default_settings = $this->default_settings();

		// Create Settings array.
		$settings = array(
			'category_title' => array(
				'name' => esc_html__( 'Category Archives', 'custom-archive-titles' ),
				'desc' => esc_html__( 'Enter the title which is displayed on category archives. %s will be replaced with the category name.', 'custom-archive-titles' ),
				'section' => 'general',
				'type' => 'text',
				'size' => 'regular',
				'default' => $default_settings['category_title'],
			),
			'tag_title' => array(
				'name' => esc_html__( 'Tag Archives', 'custom-archive-titles' ),
				'desc' => esc_html__( 'Enter the title which is displayed on tag archives. %s will be replaced with the tag name.', 'custom-archive-titles' ),
				'section' => 'general',
				'type' => 'text',
				'size' => 'regular',
				'default' => $default_settings['tag_title'],
			),
			'author_title' => array(
				'name' => esc_html__( 'Author Archives', 'custom-archive-titles' ),
				'desc' => esc_html__( 'Enter the title which is displayed on author archives. %s will be replaced with the author name.', 'custom-archive-titles' ),
				'section' => 'general',
				'type' => 'text',
				'size' => 'regular',
				'default' => $default_settings['author_title'],
			),
			'year_title' => array(
				'name' => esc_html__( 'Yearly Archives', 'custom-archive-titles' ),
				'desc' => esc_html__( 'Enter the title which is displayed on yearly archives. %s will be replaced with the year.', 'custom-archive-titles' ),
				'section' => 'general',
				'type' => 'text',
				'size' => 'regular',
				'default' => $default_settings['year_title'],
			),
			'month_title' => array(
				'name' => esc_html__( 'Monthly Archives', 'custom-archive-titles' ),
				'desc' => esc_html__( 'Enter the title which is displayed on monthly archives. %s will be replaced with the name of the month.', 'custom-archive-titles' ),
				'section' => 'general',
				'type' => 'text',
				'size' => 'regular',
				'default' => $default_settings['month_title'],
			),
			'day_title' => array(
				'name' => esc_html__( 'Daily Archives', 'custom-archive-titles' ),
				'desc' => esc_html__( 'Enter the title which is displayed on daily archives. %s will be replaced with the date.', 'custom-archive-titles' ),
				'section' => 'general',
				'type' => 'text',
				'size' => 'regular',
				'default' => $default_settings['day_title'],
			),
			'reset' => array(
				'name' => esc_html__( 'Reset to default values', 'custom-archive-titles' ),
				'section' => 'general',
				'type' => 'reset',
				'default' => '',
			),
		);

		return apply_filters( 'tzcat_settings', $settings );
	}

	/**
	 * Checkbox Callback
	 *
	 * Renders checkboxes.
	 *
	 * @param array $args Arguments passed by the setting.
	 * @global $this->options Array of all the ThemeZee Custom Archive Titles Options
	 * @return void
	 */
	function checkbox_callback( $args ) {

		$checked = isset( $this->options[ $args['id'] ] ) ? checked( 1, $this->options[ $args['id'] ], false ) : '';
		$html = '<input type="checkbox" id="tzcat_settings[' . $args['id'] . ']" name="tzcat_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>';
		$html .= '<label for="tzcat_settings[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';

		echo $html;
	}

	/**
	 * Text Callback
	 *
	 * Renders text fields.
	 *
	 * @param array $args Arguments passed by the setting.
	 * @global $this->options Array of all the ThemeZee Custom Archive Titles Options
	 * @return void
	 */
	function text_callback( $args ) {

		if ( isset( $this->options[ $args['id'] ] ) ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['default'] ) ? $args['default'] : '';
		}

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input type="text" class="' . $size . '-text" id="tzcat_settings[' . $args['id'] . ']" name="tzcat_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<p class="description">' . $args['desc'] . '</p>';

		echo $html;
	}

	/**
	 * Radio Callback
	 *
	 * Renders radio boxes.
	 *
	 * @param array $args Arguments passed by the setting.
	 * @global $this->options Array of all the ThemeZee Custom Archive Titles Options
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

				echo '<input name="tzcat_settings[' . $args['id'] . ']"" id="tzcat_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked( true, $checked, false ) . '/>&nbsp;';
				echo '<label for="tzcat_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';

			endforeach;
		endif;
		echo '<p class="description">' . $args['desc'] . '</p>';
	}

	/**
	 * Select Callback
	 *
	 * Renders select fields.
	 *
	 * @param array $args Arguments passed by the setting.
	 * @global $this->options Array of all the ThemeZee Custom Archive Titles Options
	 * @return void
	 */
	function select_callback( $args ) {

		if ( isset( $this->options[ $args['id'] ] ) ) {
			$value = $this->options[ $args['id'] ];
		} else {
			$value = isset( $args['default'] ) ? $args['default'] : '';
		}

		$html = '<select id="tzcat_settings[' . $args['id'] . ']" name="tzcat_settings[' . $args['id'] . ']"/>';

		foreach ( $args['options'] as $option => $name ) :
			$selected = selected( $option, $value, false );
			$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
		endforeach;

		$html .= '</select>';
		$html .= '<p class="description">' . $args['desc'] . '</p>';

		echo $html;
	}

	/**
	 * Reset Callback
	 *
	 * Renders reset to defaults callback.
	 *
	 * @param array $args Arguments passed by the setting.
	 * @global $this->options Array of all the ThemeZee Breadcrumbs Options
	 * @return void
	 */
	function reset_callback( $args ) {

		$html = '<input type="submit" class="button" name="tzcat_reset_defaults" value="' . esc_attr__( 'Reset', 'custom-archive-titles' ) . '"/>';
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
		printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'custom-archive-titles' ), $args['id'] );
	}
}

// Run Setting Class.
TZCAT_Settings::instance();
