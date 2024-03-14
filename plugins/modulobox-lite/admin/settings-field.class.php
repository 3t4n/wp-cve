<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ModuloBox Settings class
 *
 * @class ModuloBox_Settings_field
 * @version	1.0.0
 * @since 1.0.0
 */
class ModuloBox_Settings_field {

	/**
	 * Holds all fields name and type registered
	 *
	 * @since 1.0.0
	 * @var private
	 */
	private $fields = array();

	/**
	 * Holds default settings field values
	 *
	 * @since 1.0.0
	 * @var public
	 */
	public $default = array();

	/**
	 * Holds settings field values
	 *
	 * @since 1.0.0
	 * @var private
	 */
	private $settings = array();

	/**
	 * Cloning disabled
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __clone() {
	}

	/**
	 * De-serialization disabled
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __wakeup() {
	}

	/**
	 * Initialization
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Register settings
		add_action( 'admin_init', array( $this, 'init_settings' ) );
		// Enqueue field scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

	}

	/**
	 * Init settings API
	 *
	 * @since 1.0.0
	 * @access private
	 */
	public function init_settings() {

		// If settings page
		if ( strtolower( get_admin_page_title() ) === MOBX_NAME || isset( $_POST[ MOBX_NAME ] ) ) {

			// Get settings
			$this->get_settings();
			// Register settings
			$this->register_fields();
			$this->register_setting();

		}

	}

	/**
	 * Get class name of setting field
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $type Field type
	 * @return mixed Class name of setting field
	 */
	private function get_class_name( $type ) {

		$class = MOBX_NAME . '_' . $type . '_field';
		// return class name only if exists
		return class_exists( $class ) ? $class : false;

	}

	/**
	 * Get settings
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function get_settings() {

		$this->settings = get_option( MOBX_NAME );

	}

	/**
	 * Include field files
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function register_fields() {

		$fields = array(
			'text',
			'code',
			'number',
			'radio',
			'checkbox',
			'select',
			'color',
			'slider',
			'sorter',
			'sizes',
			'typography',
		);

		// Require from static list instead of glob for security reason
		// glob() is also often deactivated on shared hosting server
		foreach ( $fields as $field ) {
			require_once( MOBX_ADMIN_PATH . 'fields/' . $field . '.php' );
		}

	}

	/**
	 * Register settings
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_setting() {

		register_setting(
			MOBX_NAME,
			MOBX_NAME,
			array( $this, 'sanitize_settings' )
		);

		do_action( MOBX_NAME . '_register_settings_field', $this );

	}

	/**
	 * Add sections/settings field
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $section Contains the section ID
	 * @param array $fields Contains all settings fields
	 */
	public function add_settings_field( $section, $fields ) {

		add_settings_section( $section, null, null, $section );

		foreach ( $fields as $field ) {

			// Normalize default field parameters
			$field = $this->normalize_attributes( $field );
			// Get class name of field type
			$class = $this->get_class_name( $field['type'] );

			if ( $class ) {

				// Prepare field parameters
				$field = $this->get_field_attributes( $field );
				// Normalize specific field parameters
				$field = call_user_func( array( $class, 'normalize' ), $field );

				add_settings_field(
					$field['ID'],
					$field['title'],
					array( $class, 'render' ),
					$section,
					$section,
					$field
				);

				// Store field attributes to sanitize later
				$this->fields[ $field['ID'] ] = $field;

			}
		}

	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $inputs Contains all settings fields as array keys
	 * @return array Array of sanitized values
	 */
	public function sanitize_settings( $inputs ) {

		// Reset settings
		if ( isset( $_POST['reset'] ) ) {
			return null;
		}

		// Import settings
		if ( isset( $_POST['import'] ) ) {
			$inputs = $this->import_settings();
		}

		// Initialize the new array that will hold the sanitize values
		$new_inputs = array();
		// Get all registered field names set to empty values
		$default = array_fill_keys( array_keys( $this->fields ), '' );
		// Merge current fields to sanitize with all registered fields
		$inputs  = wp_parse_args( $inputs, $default );

		// Loop through the input and sanitize each of the values
		foreach ( $inputs as $key => $val ) {

			// If current option key was registered (mainly to prevent importing option(s) not valid)
			if ( isset( $default[ $key ] ) ) {

				// Get field type
				$type  = $this->fields[ $key ]['type'];
				// Get class name of field type
				$class = $this->get_class_name( $type );
				// Set value
				$value = $this->fields[ $key ]['premium'] ? $this->fields[ $key ]['default'] : $val;
				// Sanitize field
				$new_inputs[ $key ] = $class ? call_user_func( array( $class, 'sanitize' ), $value, $this->fields[ $key ] ) : '';

			}
		}

		// Export settings
		if ( isset( $_POST['export'] ) ) {
			$this->export_settings( $new_inputs );
		}

		// Return sanitized values
		return $new_inputs;

	}

	/**
	 * Export settings in .json
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $settings Contains all settings field
	 */
	private function export_settings( $settings ) {

		// Ignore user aborts
		ignore_user_abort( true );

		// Sets the headers to prevent caching for the different browsers
		nocache_headers();

		// Prepare header
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . MOBX_NAME . '-settings-' . date( 'm-d-Y' ) . '.json' );
		header( 'Expires: 0' );

		// Encode and output settings
		echo wp_json_encode( $settings );

		exit;

	}

	/**
	 * Import settings from .json
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return array Contains all settings value
	 */
	private function import_settings() {

		// Get uploaded file
		$import_file = $_FILES['import_file']['tmp_name'];

		// If no file was uploaded
		if ( empty( $import_file ) ) {

			$args = array(
				'page' => esc_attr( MOBX_NAME ),
				'settings-updated' => 'no_file',
			);

			wp_redirect( esc_url_raw( add_query_arg( $args, admin_url( 'admin.php' ) ) ) );
			exit;

		}

		// Get file content (settings)
		$settings = file_get_contents( $import_file );

		// If an error occurred while getting file content
		if ( false === $settings ) {

			$args = array(
				'page' => esc_attr( MOBX_NAME ),
				'settings-updated' => 'import_error',
			);

			wp_redirect( esc_url_raw( add_query_arg( $args, admin_url( 'admin.php' ) ) ) );
			exit;

		}

		// Remove UTF-8 BOM if present otherwise json_decode() will not work (prevent issue (in rare cases) with multibyte)
		if ( substr( $settings, 0, 3 ) === pack( 'CCC', 0xEF, 0xBB, 0xBF ) ) {
			$settings = substr( $settings, 3 );
		}

		// Decode settings
		$settings = (array) json_decode( $settings );

		// If no setting available or at least the first setting key (ID) was not found
		if ( empty( $settings ) || ! isset( $settings[ current( array_keys( (array) $this->fields ) ) ] ) ) {

			$args = array(
				'page' => esc_attr( MOBX_NAME ),
				'settings-updated' => 'invalid_file',
			);

			wp_redirect( esc_url_raw( add_query_arg( $args, admin_url( 'admin.php' ) ) ) );
			exit;

		}

		return $settings;

	}

	/**
	 * Get and normalize main field attributes
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $args Contains all fields attributes
	 * @return array Contains all fields attributes normailized
	 */
	private function normalize_attributes( $args ) {

		$default = ! isset( $args['default'] ) && isset( $this->default[ $args['ID'] ] ) ? $this->default[ $args['ID'] ] : '';

		return wp_parse_args( $args, array(
			'ID'          => null,
			'type'        => '',
			'title'       => '',
			'description' => '',
			'default'     => $default,
			'premium'     => '',
		));

	}

	/**
	 * Get and format main field attributes
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $args Contains all settings fields as array keys
	 * @return array Contains all settings value
	 */
	private function get_field_attributes( $args ) {

		$args['name']    = MOBX_NAME . '[' . $args['ID']  . ']';
		$args['value']   = isset( $this->settings[ $args['ID'] ] ) ? $this->settings[ $args['ID'] ] : $args['default'];
		$args['desc']    = $this->set_field_description( $args );
		$args['premium'] = $this->set_premium_label( $args );

		return $args;

	}

	/**
	 * Generate field tooltip description
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $args Contains all settings fields as array keys
	 * @return string
	 */
	public function set_field_description( $args ) {

		if ( isset( $args['description'] ) && ! empty( $args['description'] ) ) {

			$desc = '<span class="mobx-info-desc"></span>';
			$desc .= '<p class="mobx-field-desc">';
				$desc .= '<span>' . esc_html( $args['description'] ) . '</span>';
			$desc .= '</p>';

			return $desc;

		}

	}

	/**
	 * Generate field premium label
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $args Contains all settings fields as array keys
	 * @return string
	 */
	public function set_premium_label( $args ) {

		if ( isset( $args['premium'] ) && ! empty( $args['premium'] ) ) {

			return '<div class="mobx-premium-label mobx-lock-' . sanitize_html_class( $args['ID'] ) . '"><span>' . esc_html__( 'Premium Version', 'modulobox' ) . '</span></div>';

		}

	}

	/**
	 * Enqueue fields scripts and styles
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_enqueue_scripts() {

		// Get all unique field types
		$types = array_unique( array_map( function ( $arr ) {
			return $arr['type'];
		}, $this->fields ) );

		foreach ( $types as $type ) {

			// Get class name of field type
			$class = $this->get_class_name( $type );

			// Enqueue scripts and styles for fields
			if ( $class && method_exists( $class, 'scripts' ) ) {
				call_user_func( array( $class, 'scripts' ) );
			}
		}

	}
}
