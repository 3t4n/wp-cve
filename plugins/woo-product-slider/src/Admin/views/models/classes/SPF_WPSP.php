<?php
/**
 * Framework setup.class file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Woo_Product_Slider.
 * @subpackage Woo_Product_Slider/Admin.
 */

namespace ShapedPlugin\WooProductSlider\Admin\views\models\classes;

/**
 *
 * Setup Class.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
class SPF_WPSP {

	/**
	 * Premium.
	 *
	 * @var boolean
	 */
	public static $premium = true;
	/**
	 * Version.
	 * Framework version 2.2.6.
	 *
	 * @var string
	 */
	public static $version = '2.7.0';
	/**
	 * Dir.
	 *
	 * @var string
	 */
	public static $dir = '';
	/**
	 * Url.
	 *
	 * @var string
	 */
	public static $url = '';
	/**
	 * CSS.
	 *
	 * @var string
	 */
	public static $css = '';
	/**
	 * File.
	 *
	 * @var string
	 */
	public static $file = '';
	/**
	 * Enqueue.
	 *
	 * @var boolean
	 */
	public static $enqueue = false;
	/**
	 * Webfonts.
	 *
	 * @var array
	 */
	public static $webfonts = array();
	/**
	 * Subsets.
	 *
	 * @var array
	 */
	public static $subsets = array();
	/**
	 * Inited.
	 *
	 * @var array
	 */
	public static $inited = array();
	/**
	 * Fields.
	 *
	 * @var array
	 */
	public static $fields = array();
	/**
	 * Args.
	 *
	 * @var array
	 */
	public static $args = array(
		'admin_options'   => array(),
		'metabox_options' => array(),
	);

	/**
	 * Shortcode instances.
	 *
	 * @var array
	 */
	public static $shortcode_instances = array();

	/**
	 * Instance.
	 *
	 * @var string
	 */
	private static $instance = null;

	/**
	 * Init
	 *
	 * @param mixed $file files.
	 * @return statement
	 */
	public static function init( $file = __FILE__ ) {

		// Set file constant.
		self::$file = $file;

		// Set constants.
		self::constants();

		// Include files.
		self::includes();

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Construct of the class.
	 *
	 * @return void
	 */
	public function __construct() {

		// Init action.
		do_action( 'spwps_init' );
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'init', array( $this, 'setup' ) );
		add_action( 'switch_theme', array( $this, 'setup' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_enqueue_scripts' ) );
		add_action( 'wp_head', array( $this, 'add_custom_css' ), 80 );
		add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );

	}


	/**
	 * Helper function to generate plugin installation and activation process.
	 *
	 * @param string      $plugin_slug The slug of the plugin.
	 * @param string      $button_text The text for the install/activate button.
	 * @param string|null $activate_capability Optional capability required for activation.
	 * @param string|null $class_checks Check the plugin main class existed or not.
	 * @param string|null $slug The plugin slug.
	 *
	 * @return array Plugin data array containing link, status, and activate URL.
	 */
	public static function plugin_installation_activation( $plugin_slug, $button_text, $activate_capability = null, $class_checks = null, $slug = null ) {
		$plugin_link = add_query_arg(
			array(
				'tab'       => 'plugin-information',
				'plugin'    => $slug,
				'TB_iframe' => 'true',
				'width'     => '772',
				'height'    => '540',
			),
			admin_url( 'plugin-install.php' )
		);

		$get_plugins         = get_plugins();
		$activate_plugin_url = '';
		$has_plugin          = '';

		$is_plugin_active = false;
		// Check if any of the classes exist.
		foreach ( $class_checks as $class_check ) {
			if ( class_exists( $class_check ) ) {
				$is_plugin_active = true;
				break;
			}
		}

		// Check if the plugin is active using is_plugin_active().
		if ( $is_plugin_active || is_plugin_active( $plugin_slug ) ) {
			$has_plugin = ' activated';
		} elseif ( isset( $get_plugins[ $plugin_slug ] ) ) {
			if ( 'woo-quickview/woo-quick-view.php' === $plugin_slug ) {
				$has_plugin = ' activate_plugin';
			} else {
				$has_plugin = ' activate_brand';
			}
			$button_text = 'Activate Now';
			if ( $activate_capability && current_user_can( $activate_capability ) ) {
				$activate_plugin_url = add_query_arg(
					array(
						'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $plugin_slug ),
						'action'   => 'activate',
						'plugin'   => $plugin_slug,
					),
					network_admin_url( 'plugins.php' )
				);
			}
		}

		return compact( 'plugin_link', 'has_plugin', 'button_text', 'activate_plugin_url', 'slug' );
	}


	/**
	 * Setup
	 *
	 * @return void
	 */
	public static function setup() {
		// Configs.
		self::include_plugin_file( 'configs/metabox.config.php' );
		self::include_plugin_file( 'configs/settings.config.php' );
		self::include_plugin_file( 'configs/replace-layout.config.php' );
		self::include_plugin_file( 'configs/tools.config.php' );
		// Setup admin option framework.
		$params = array();
		if ( class_exists( 'SPF_WPSP_Options' ) && ! empty( self::$args['admin_options'] ) ) {
			foreach ( self::$args['admin_options'] as $key => $value ) {
				if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

					$params['args']       = $value;
					$params['sections']   = self::$args['sections'][ $key ];
					self::$inited[ $key ] = true;

					\SPF_WPSP_Options::instance( $key, $params );
				}
			}
		}

		// Setup metabox option framework.
		$params = array();
		if ( class_exists( 'SPF_WPSP_Metabox' ) && ! empty( self::$args['metabox_options'] ) ) {
			foreach ( self::$args['metabox_options'] as $key => $value ) {
				if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

					$params['args']       = $value;
					$params['sections']   = self::$args['sections'][ $key ];
					self::$inited[ $key ] = true;

					\SPF_WPSP_Metabox::instance( $key, $params );

				}
			}
		}

		do_action( 'spwps_loaded' );

	}

	/**
	 * Create options.
	 *
	 * @param  mixed $id option id.
	 * @param  array $args args.
	 * @return void
	 */
	public static function createOptions( $id, $args = array() ) {
		self::$args['admin_options'][ $id ] = $args;
	}

	/**
	 * Create metabox options.
	 *
	 * @param  mixed $id option id.
	 * @param  array $args args.
	 * @return void
	 */
	public static function createMetabox( $id, $args = array() ) {
		self::$args['metabox_options'][ $id ] = $args;
	}

	/**
	 * Create section.
	 *
	 * @param  mixed $id option id.
	 * @param  array $sections sections.
	 * @return void
	 */
	public static function createSection( $id, $sections ) {
		self::$args['sections'][ $id ][] = $sections;
		self::set_used_fields( $sections );
	}

	/**
	 * Constants
	 *
	 * @return void
	 */
	public static function constants() {

		// We need this path-finder code for set URL of framework.
		$dirname        = str_replace( '//', '/', wp_normalize_path( dirname( dirname( self::$file ) ) ) );
		$theme_dir      = str_replace( '//', '/', wp_normalize_path( get_parent_theme_file_path() ) );
		$plugin_dir     = str_replace( '//', '/', wp_normalize_path( WP_PLUGIN_DIR ) );
		$plugin_dir     = str_replace( '/opt/bitnami', '/bitnami', $plugin_dir );
		$located_plugin = ( preg_match( '#' . self::sanitize_dirname( $plugin_dir ) . '#', self::sanitize_dirname( $dirname ) ) ) ? true : false;
		$directory      = ( $located_plugin ) ? $plugin_dir : $theme_dir;
		$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_parent_theme_file_uri();
		$foldername     = str_replace( $directory, '', $dirname );
		$protocol_uri   = ( is_ssl() ) ? 'https' : 'http';
		$directory_uri  = set_url_scheme( $directory_uri, $protocol_uri );

		self::$dir = $dirname;
		self::$url = $directory_uri . $foldername;

	}

	/**
	 * Include plugin files.
	 *
	 * @param  mixed $file file.
	 * @param  mixed $load load.
	 * @return array
	 */
	public static function include_plugin_file( $file, $load = true ) {

		$path     = '';
		$file     = ltrim( $file, '/' );
		$override = apply_filters( 'spwps_override', 'spwps-override' );

		if ( file_exists( get_parent_theme_file_path( $override . '/' . $file ) ) ) {
			$path = get_parent_theme_file_path( $override . '/' . $file );
		} elseif ( file_exists( get_theme_file_path( $override . '/' . $file ) ) ) {
			$path = get_theme_file_path( $override . '/' . $file );
		} elseif ( file_exists( self::$dir . '/' . $override . '/' . $file ) ) {
			$path = self::$dir . '/' . $override . '/' . $file;
		} elseif ( file_exists( self::$dir . '/' . $file ) ) {
			$path = self::$dir . '/' . $file;
		}
		if ( ! empty( $path ) && ! empty( $file ) && $load ) {

			global $wp_query;

			if ( is_object( $wp_query ) && function_exists( 'load_template' ) ) {

				load_template( $path, true );

			} else {

				require_once $path;

			}
		} else {

			return self::$dir . '/' . $file;

		}

	}

	/**
	 * Is active plugin
	 *
	 * @param  mixed $file file.
	 * @return statement
	 */
	public static function is_active_plugin( $file = '' ) {
		return in_array( $file, (array) get_option( 'active_plugins', array() ) );
	}

	/**
	 * Sanitize dirname.
	 *
	 * @param  mixed $dirname dirname.
	 * @return statement
	 */
	public static function sanitize_dirname( $dirname ) {
		return preg_replace( '/[^A-Za-z]/', '', $dirname );
	}

	/**
	 * Set plugin url.
	 *
	 * @param  mixed $file file.
	 * @return string
	 */
	public static function include_plugin_url( $file ) {
		return esc_url( SP_WPS_URL . 'Admin/views/models' ) . '/' . ltrim( $file, '/' );
	}

	/**
	 * General includes.
	 *
	 * @return void
	 */
	public static function includes() {

		// Helpers.
		self::include_plugin_file( 'functions/actions.php' );
		self::include_plugin_file( 'functions/helpers.php' );
		self::include_plugin_file( 'functions/sanitize.php' );
		self::include_plugin_file( 'functions/validate.php' );

		// Includes free version classes.
		self::include_plugin_file( 'classes/abstract.class.php' );
		self::include_plugin_file( 'classes/fields.class.php' );
		self::include_plugin_file( 'classes/options.class.php' );
		self::include_plugin_file( 'classes/metabox.class.php' );

		// Include all framework fields.
		$fields = apply_filters(
			'spwps_fields',
			array(
				'button_set',
				'image_select',
				'select',
				'checkbox',
				'column',
				'fieldset',
				'license',
				'spinner',
				'spacing',
				'switcher',
				'slider',
				'subheading',
				'submessage',
				'color_group',
				'color',
				'preview',
				'border',
				'notice',
				'radio',
				'text',
				'image_sizes',
				'dimensions',
				'tabbed',
				'typography',
				'code_editor',
				'custom_import',
			)
		);

		if ( ! empty( $fields ) ) {
			foreach ( $fields as $field ) {
				if ( ! class_exists( 'SPF_WPSP_Field_' . $field ) && class_exists( 'SPF_WPSP_Fields' ) ) {
					self::include_plugin_file( 'fields/' . $field . '/' . $field . '.php' );
				}
			}
		}

	}

	/**
	 * Get all of fields.
	 *
	 * @param  mixed $sections sections.
	 * @return void
	 */
	public static function set_used_fields( $sections ) {

		if ( ! empty( $sections['fields'] ) ) {

			foreach ( $sections['fields'] as $field ) {

				if ( ! empty( $field['fields'] ) ) {
					self::set_used_fields( $field );
				}

				if ( ! empty( $field['tabs'] ) ) {
					self::set_used_fields( array( 'fields' => $field['tabs'] ) );
				}

				if ( ! empty( $field['accordions'] ) ) {
					self::set_used_fields( array( 'fields' => $field['accordions'] ) );
				}

				if ( ! empty( $field['type'] ) ) {
					self::$fields[ $field['type'] ] = $field;
				}
			}
		}

	}

	/**
	 * Enqueue admin and fields styles and scripts.
	 *
	 * @return void
	 */
	public static function add_admin_enqueue_scripts() {

		// Loads scripts and styles only when needed.
		$wpscreen = get_current_screen();
		if ( 'sp_wps_shortcodes' === $wpscreen->post_type ) {
			if ( ! empty( self::$args['admin_options'] ) ) {
				foreach ( self::$args['admin_options'] as $argument ) {
					if ( substr( $wpscreen->id, -strlen( $argument['menu_slug'] ) ) === $argument['menu_slug'] ) {
						self::$enqueue = true;
					}
				}
			}

			if ( ! empty( self::$args['metabox_options'] ) ) {
				foreach ( self::$args['metabox_options'] as $argument ) {
					if ( in_array( $wpscreen->post_type, (array) $argument['post_type'] ) ) {
						self::$enqueue = true;
					}
				}
			}

			if ( ! apply_filters( 'spwps_enqueue_assets', self::$enqueue ) ) {
				return;
			}
			// Check for developer mode.
			$min = ( self::$premium && SCRIPT_DEBUG ) ? '' : '.min';

			// Admin utilities.
			wp_enqueue_media();

			// Wp color picker.
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			// Font awesome 4 and 5 loader.
			if ( apply_filters( 'spwps_fa4', true ) ) {
				wp_enqueue_style( 'spwps-fa', 'https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome' . $min . '.css', array(), '4.7.0', 'all' );
			} else {
				wp_enqueue_style( 'spwps-fa5', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all' . $min . '.css', array(), '5.15.5', 'all' );
				wp_enqueue_style( 'spwps-fa5-v4-shims', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/v4-shims' . $min . '.css', array(), '5.15.5', 'all' );
			}

			// Main style.
			wp_enqueue_style( 'spwps', self::include_plugin_url( 'assets/css/spwps.css' ), array(), self::$version, 'all' );

			// Main RTL styles.
			if ( is_rtl() ) {
				wp_enqueue_style( 'spwps-rtl', self::include_plugin_url( 'assets/css/spwps-rtl' . $min . '.css' ), array(), self::$version, 'all' );
			}
			// Main scripts.
			wp_enqueue_script( 'spwps-plugins', self::include_plugin_url( 'assets/js/spwps-plugins' . $min . '.js' ), array(), self::$version, true );
			wp_enqueue_script( 'spwps', self::include_plugin_url( 'assets/js/spwps' . $min . '.js' ), array( 'spwps-plugins' ), self::$version, true );

			// Main variables.
			wp_localize_script(
				'spwps',
				'spwps_vars',
				array(
					'previewJS'     => esc_url( SP_WPS_URL . 'Frontend/assets/js/scripts.min.js' ),
					'color_palette' => apply_filters( 'spwps_color_palette', array() ),
					'i18n'          => array(
						'confirm'         => esc_html__( 'Are you sure?', 'woo-product-slider' ),
						'typing_text'     => esc_html__( 'Please enter %s or more characters', 'woo-product-slider' ),
						'searching_text'  => esc_html__( 'Searching...', 'woo-product-slider' ),
						'no_results_text' => esc_html__( 'No results found.', 'woo-product-slider' ),
					),
				)
			);

			// Enqueue fields scripts and styles.
			$enqueued = array();

			if ( ! empty( self::$fields ) ) {
				foreach ( self::$fields as $field ) {
					if ( ! empty( $field['type'] ) ) {
						$classname = 'SPF_WPSP_Field_' . $field['type'];
						if ( class_exists( $classname ) && method_exists( $classname, 'enqueue' ) ) {
							$instance = new $classname( $field );
							if ( method_exists( $classname, 'enqueue' ) ) {
								$instance->enqueue();
							}
							unset( $instance );
						}
					}
				}
			}
		}
		do_action( 'spwps_enqueue' );
	}

	/**
	 * Admin body class
	 *
	 * @param string $classes admin body class.
	 * @return statement.
	 */
	public static function add_admin_body_class( $classes ) {

		if ( apply_filters( 'spwps_fa4', false ) ) {
			$classes .= 'spwps-fa5-shims';
		}

		return $classes;

	}

	/**
	 * Custom Css
	 *
	 * @return void
	 */
	public static function add_custom_css() {

		if ( ! empty( self::$css ) ) {
			echo '<style type="text/css">' . wp_strip_all_tags( self::$css ) . '</style>';
		}

	}

	/**
	 * Add a new framework field.
	 *
	 * @param  mixed $field Field.
	 * @param  mixed $value value.
	 * @param  mixed $unique unique id.
	 * @param  mixed $where Where.
	 * @param  mixed $parent parent.
	 * @return void
	 */
	public static function field( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {

		// Check for not allowed fields.
		if ( ! empty( $field['_notice'] ) ) {

			$field_type = $field['type'];

			$field            = array();
			$field['content'] = esc_html__( 'Oops! Not allowed.', 'woo-product-slider' ) . ' <strong>(' . $field_type . ')</strong>';
			$field['type']    = 'notice';
			$field['style']   = 'danger';

		}

		$depend     = '';
		$visible    = '';
		$unique     = ( ! empty( $unique ) ) ? $unique : '';
		$class      = ( ! empty( $field['class'] ) ) ? ' ' . esc_attr( $field['class'] ) : '';
		$is_pseudo  = ( ! empty( $field['pseudo'] ) ) ? ' spwps-pseudo-field' : '';
		$field_type = ( ! empty( $field['type'] ) ) ? esc_attr( $field['type'] ) : '';

		if ( ! empty( $field['dependency'] ) ) {

			$dependency      = $field['dependency'];
			$depend_visible  = '';
			$data_controller = '';
			$data_condition  = '';
			$data_value      = '';
			$data_global     = '';

			if ( is_array( $dependency[0] ) ) {
				$data_controller = implode( '|', array_column( $dependency, 0 ) );
				$data_condition  = implode( '|', array_column( $dependency, 1 ) );
				$data_value      = implode( '|', array_column( $dependency, 2 ) );
				$data_global     = implode( '|', array_column( $dependency, 3 ) );
				$depend_visible  = implode( '|', array_column( $dependency, 4 ) );
			} else {
				$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
				$data_condition  = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
				$data_value      = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
				$data_global     = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
				$depend_visible  = ( ! empty( $dependency[4] ) ) ? $dependency[4] : '';
			}

			$depend .= ' data-controller="' . esc_attr( $data_controller ) . '"';
			$depend .= ' data-condition="' . esc_attr( $data_condition ) . '"';
			$depend .= ' data-value="' . esc_attr( $data_value ) . '"';
			$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

			$visible = ( ! empty( $depend_visible ) ) ? ' spwps-depend-visible' : ' spwps-depend-hidden';

		}

		// These attributes has been sanitized above.
		echo '<div class="spwps-field spwps-field-' . esc_attr( $field_type . $is_pseudo . $class . $visible ) . '"' . wp_kses_post( $depend ) . '>';

		if ( ! empty( $field_type ) ) {

			if ( ! empty( $field['fancy_title'] ) ) {
				echo '<div class="spwps-fancy-title">' . wp_kses_post( $field['fancy_title'] ) . '</div>';
			}

			if ( ! empty( $field['title'] ) ) {
				$title_info = ( ! empty( $field['title_info'] ) ) ? '<span class="spwps-help title-info"><div class="spwps-help-text">' . wp_kses_post( $field['title_info'] ) . '</div><span class="tooltip-icon"><img src="' . self::include_plugin_url( 'assets/images/info.svg' ) . '"></span></span>' : '';

				echo '<div class="spwps-title">';
				echo '<h4>' . wp_kses_post( $field['title'] . $title_info ) . '</h4>';
				echo ( ! empty( $field['subtitle'] ) ) ? '<div class="spwps-subtitle-text">' . wp_kses_post( $field['subtitle'] ) . '</div>' : '';
				echo '</div>';
			}

			echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '<div class="spwps-fieldset">' : '';

			$value = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
			$value = ( isset( $field['value'] ) ) ? $field['value'] : $value;

			$classname = 'SPF_WPSP_Field_' . $field_type;

			if ( class_exists( $classname ) ) {
				$instance = new $classname( $field, $value, $unique, $where, $parent );
				$instance->render();
			} else {
				echo '<p>' . esc_html__( 'Field not found!', 'woo-product-slider' ) . '</p>';
			}
		} else {
			echo '<p>' . esc_html__( 'Field not found!', 'woo-product-slider' ) . '</p>';
		}

		echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '</div>' : '';
		echo '<div class="clear"></div>';
		echo '</div>';
	}

}

SPF_WPSP::init( __FILE__ );
