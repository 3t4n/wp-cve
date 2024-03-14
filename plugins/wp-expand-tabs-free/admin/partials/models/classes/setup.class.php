<?php
/**
 * Framework setup.class file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package wp-expand-tabs-free
 * @subpackage wp-expand-tabs-free/Framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WP_TABS' ) ) {
	/**
	 *
	 * Setup Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WP_TABS {

		/**
		 * Constants.
		 *
		 * @var string
		 */
		public static $version = '2.1.5';
		/**
		 * Premium or not.
		 *
		 * @var boolean
		 */
		public static $premium = true;
		/**
		 * Directory.
		 *
		 * @var string
		 */
		public static $dir = null;
		/**
		 * URL
		 *
		 * @var string
		 */
		public static $url = null;
		/**
		 * Initiated.
		 *
		 * @var array
		 */
		public static $inited = array();
		/**
		 * The fields array.
		 *
		 * @var array
		 */
		public static $fields = array();
		/**
		 * The arguments.
		 *
		 * @var array
		 */
		public static $args = array(
			'options'   => array(),
			'metaboxes' => array(),
		);

		/**
		 * Shortcode instances.
		 *
		 * @var array
		 */
		public static $shortcode_instances = array();

		/**
		 * Init.
		 *
		 * @return void
		 */
		public static function init() {

			// init action.
			do_action( 'wptabspro_init' );

			// Init translation in framework.
			self::textdomain();

			// set constants.
			self::constants();

			// include files.
			self::includes();

			add_action( 'after_setup_theme', array( 'SP_WP_TABS', 'setup' ) );
			add_action( 'init', array( 'SP_WP_TABS', 'setup' ) );
			add_action( 'switch_theme', array( 'SP_WP_TABS', 'setup' ) );
			add_action( 'admin_enqueue_scripts', array( 'SP_WP_TABS', 'add_admin_enqueue_scripts' ), 20 );
			add_action( 'admin_head', array( 'SP_WP_TABS', 'add_admin_head_css' ), 99 );

		}

		/**
		 * Setup textdomain.
		 *
		 * @return void
		 */
		public static function textdomain() {
			require WP_TABS_PATH . '/includes/class-wp-tabs-i18n.php';
			$plugin_i18n = new WP_Tabs_i18n();
			$plugin_i18n->load_plugin_textdomain();
		}
		/**
		 * Setup.
		 *
		 * @return void
		 */
		public static function setup() {

			// setup options.
			$params = array();
			if ( ! empty( self::$args['options'] ) ) {
				foreach ( self::$args['options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						SP_WP_TABS_Options::instance( $key, $params );

						if ( ! empty( $value['show_in_customizer'] ) ) {
							$value['output_css']                     = false;
							$value['enqueue_webfont']                = false;
							self::$args['customize_options'][ $key ] = $value;
							self::$inited[ $key ]                    = null;
						}
					}
				}
			}

			// setup metaboxes.
			$params = array();
			if ( ! empty( self::$args['metaboxes'] ) ) {
				foreach ( self::$args['metaboxes'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						SP_WP_TABS_Metabox::instance( $key, $params );

					}
				}
			}

			do_action( 'wptabspro_loaded' );

		}

		/**
		 * Create options.
		 *
		 * @param string $id The option ID.
		 * @param array  $args The arguments array.
		 * @return void
		 */
		// phpcs:ignore
		public static function createOptions( $id, $args = array() ) {
			self::$args['options'][ $id ] = $args;
		}

		/**
		 * Create metabox options.
		 *
		 * @param string $id The option ID.
		 * @param array  $args The arguments array.
		 * @return void
		 */
		// phpcs:ignore
		public static function createMetabox( $id, $args = array() ) {
			self::$args['metaboxes'][ $id ] = $args;
		}

		/**
		 * Create sections.
		 *
		 * @param string $id The option ID.
		 * @param array  $sections The sections array.
		 * @return void
		 */
		// phpcs:ignore
		public static function createSection( $id, $sections ) {
			self::$args['sections'][ $id ][] = $sections;
			self::set_used_fields( $sections );
		}

		/**
		 * Constants.
		 *
		 * @return void
		 */
		public static function constants() {

			// we need this path-finder code for set URL of framework.
			$dirname        = wp_normalize_path( dirname( dirname( __FILE__ ) ) );
			$theme_dir      = wp_normalize_path( get_parent_theme_file_path() );
			$plugin_dir     = wp_normalize_path( WP_PLUGIN_DIR );
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
		 * @param string  $file Plugin files.
		 * @param boolean $load Load files.
		 *
		 * @return mixed
		 */
		public static function include_plugin_file( $file, $load = true ) {

			$path     = '';
			$file     = ltrim( $file, '/' );
			$override = apply_filters( 'wptabspro_override', 'wptabspro-override' );

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
		 * Is plugin active.
		 *
		 * @param string $file The plugin file.
		 * @return boolean
		 */
		public static function is_active_plugin( $file = '' ) {
			return in_array( $file, (array) get_option( 'active_plugins', array() ), true );
		}

		/**
		 * Sanitize dirname.
		 *
		 * @param string $dirname The directory name.
		 * @return string
		 */
		public static function sanitize_dirname( $dirname ) {
			return preg_replace( '/[^A-Za-z]/', '', $dirname );
		}

		/**
		 * Set plugin url.
		 *
		 * @param string $file Plugin url.
		 */
		public static function include_plugin_url( $file ) {
			return esc_url( WP_TABS_URL . '/admin/partials/models' ) . '/' . ltrim( $file, '/' );
		}

		/**
		 * General includes.
		 *
		 * @return void
		 */
		public static function includes() {

			// includes helpers.
			self::include_plugin_file( 'functions/actions.php' );
			self::include_plugin_file( 'functions/helpers.php' );
			self::include_plugin_file( 'functions/sanitize.php' );
			self::include_plugin_file( 'functions/validate.php' );

			// includes classes.
			self::include_plugin_file( 'classes/abstract.class.php' );
			self::include_plugin_file( 'classes/fields.class.php' );
			self::include_plugin_file( 'classes/options.class.php' );
			self::include_plugin_file( 'classes/metabox.class.php' );

		}

		/**
		 * Include field.
		 *
		 * @param string $type File type.
		 * @return void
		 */
		public static function maybe_include_field( $type = '' ) {
			if ( ! class_exists( 'SP_WP_TABS_Field_' . $type ) && class_exists( 'SP_WP_TABS_Fields' ) ) {
				self::include_plugin_file( 'fields/' . $type . '/' . $type . '.php' );
			}
		}

		/**
		 * Get all of fields.
		 *
		 * @param array $sections All the sections used in the plugin.
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
		 * @param string $hook Hooks.
		 * @return void
		 */
		public static function add_admin_enqueue_scripts( $hook ) {

			$current_screen        = get_current_screen();
			$the_current_post_type = $current_screen->post_type;
			if ( 'sp_wp_tabs' === $the_current_post_type ) {

				// check for developer mode.
				$min = ( apply_filters( 'wptabspro_dev_mode', false ) || WP_DEBUG ) ? '' : '.min';

				// admin utilities.
				wp_enqueue_media();

				// wp color picker.
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );

				// font awesome 4 and 5.
				if ( apply_filters( 'wptabspro_fa4', false ) ) {
					wp_enqueue_style( 'wptabspro-fa', WP_TABS_URL . 'public/css/font-awesome.min.css', array(), '4.7.0', 'all' );
				} else {
					wp_enqueue_style( 'wptabspro-fa5', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.0/css/all' . $min . '.css', array(), WP_TABS_VERSION, 'all' );
					wp_enqueue_style( 'wptabspro-fa5-v4-shims', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.0/css/v4-shims' . $min . '.css', array(), WP_TABS_VERSION, 'all' );
				}

				// framework core styles.
				wp_enqueue_style( 'wptabspro', self::include_plugin_url( 'assets/css/wptabspro' . $min . '.css' ), array(), WP_TABS_VERSION, 'all' );

				// rtl styles.
				if ( is_rtl() ) {
					wp_enqueue_style( 'wptabspro-rtl', self::include_plugin_url( 'assets/css/wptabspro-rtl' . $min . '.css' ), array(), WP_TABS_VERSION, 'all' );
				}

				// framework core scripts.
				wp_enqueue_script( 'wptabspro-plugins', self::include_plugin_url( 'assets/js/wptabspro-plugins' . $min . '.js' ), array(), WP_TABS_VERSION, true );
				wp_enqueue_script( 'wptabspro', self::include_plugin_url( 'assets/js/wptabspro' . $min . '.js' ), array( 'wptabspro-plugins' ), WP_TABS_VERSION, true );

				wp_localize_script(
					'wptabspro',
					'wptabspro_vars',
					array(
						'pluginsUrl'    => WP_TABS_URL,
						'color_palette' => apply_filters( 'wptabspro_color_palette', array() ),
						'i18n'          => array(
							// global localize.
							'confirm'             => esc_html__( 'Are you sure?', 'wp-expand-tabs-free' ),
							'reset_notification'  => esc_html__( 'Restoring options.', 'wp-expand-tabs-free' ),
							'import_notification' => esc_html__( 'Importing options.', 'wp-expand-tabs-free' ),

							// chosen localize.
							'typing_text'         => esc_html__( 'Please enter %s or more characters', 'wp-expand-tabs-free' ),// phpcs:ignore
							'searching_text'      => esc_html__( 'Searching...', 'wp-expand-tabs-free' ),
							'no_results_text'     => esc_html__( 'No results match', 'wp-expand-tabs-free' ),
						),
					)
				);

				// load admin enqueue scripts and styles.
				$enqueued = array();

				if ( ! empty( self::$fields ) ) {
					foreach ( self::$fields as $field ) {
						if ( ! empty( $field['type'] ) ) {
							$classname = 'SP_WP_TABS_Field_' . $field['type'];
							self::maybe_include_field( $field['type'] );
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

				do_action( 'wptabspro_enqueue' );
			}

		}

		/**
		 * WP 5.2 fallback.
		 * This function has been created as temporary.
		 * It will be remove after stable version of wp 5.3.
		 *
		 * @return void
		 */
		public static function add_admin_head_css() {

			global $wp_version;

			$current_branch = implode( '.', array_slice( preg_split( '/[.-]/', $wp_version ), 0, 2 ) );

			if ( version_compare( $current_branch, '5.3', '<' ) ) {

				echo '<style type="text/css">
          .wptabspro-field-slider .wptabspro--unit,
          .wptabspro-field-border .wptabspro--label,
          .wptabspro-field-spacing .wptabspro--label,
          .wptabspro-field-dimensions .wptabspro--label,
          .wptabspro-field-spinner .ui-button-text-only{
            border-color: #ddd;
          }
          .wptabspro-warning-primary{
            box-shadow: 0 1px 0 #bd2130 !important;
          }
          .wptabspro-warning-primary:focus{
            box-shadow: none !important;
          }
        </style>';

			}

		}

		/**
		 * Add a new framework field.
		 *
		 * @param array  $field The fields array.
		 * @param string $value The field value.
		 * @param string $unique The unique string.
		 * @param string $where The position to show the fields.
		 * @param string $parent If the fields has parent.
		 * @return void
		 */
		public static function field( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {

			// Check for unallow fields.
			if ( ! empty( $field['_notice'] ) ) {

				$field_type = $field['type'];

				$field            = array();
				$field['content'] = sprintf( esc_html__( 'Ooops! This field type (%s) can not be used here, yet.', 'wp-expand-tabs-free' ), '<strong>' . $field_type . '</strong>' ); // phpcs:ignore
				$field['type']    = 'notice';
				$field['style']   = 'danger';

			}

			$depend     = '';
			$hidden     = '';
			$unique     = ( ! empty( $unique ) ) ? $unique : '';
			$class      = ( ! empty( $field['class'] ) ) ? ' ' . esc_attr( $field['class'] ) : '';
			$is_pseudo  = ( ! empty( $field['pseudo'] ) ) ? ' wptabspro-pseudo-field' : '';
			$field_type = ( ! empty( $field['type'] ) ) ? esc_attr( $field['type'] ) : '';

			if ( ! empty( $field['dependency'] ) ) {

				$dependency      = $field['dependency'];
				$hidden          = ' hidden';
				$data_controller = '';
				$data_condition  = '';
				$data_value      = '';
				$data_global     = '';

				if ( is_array( $dependency[0] ) ) {
					$data_controller = implode( '|', array_column( $dependency, 0 ) );
					$data_condition  = implode( '|', array_column( $dependency, 1 ) );
					$data_value      = implode( '|', array_column( $dependency, 2 ) );
					$data_global     = implode( '|', array_column( $dependency, 3 ) );
				} else {
					$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
					$data_condition  = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
					$data_value      = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
					$data_global     = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
				}

				$depend .= ' data-controller="' . esc_attr( $data_controller ) . '"';
				$depend .= ' data-condition="' . esc_attr( $data_condition ) . '"';
				$depend .= ' data-value="' . esc_attr( $data_value ) . '"';
				$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

			}

			if ( ! empty( $field_type ) ) {

				// These attributes has been sanitized above.
				echo '<div class="wptabspro-field wptabspro-field-' . esc_attr( $field_type ) . esc_attr( $is_pseudo ) . esc_attr( $class ) . esc_attr( $hidden ) . '"' . wp_kses_post( $depend ) . '>';

				if ( ! empty( $field['fancy_title'] ) ) {
					echo '<div class="wptabspro-fancy-title">' . wp_kses_post( $field['fancy_title'] ) . '</div>';
				}

				if ( ! empty( $field['title'] ) ) {
					$subtitle = ( ! empty( $field['subtitle'] ) ) ? '<p class="wptabspro-text-subtitle">' . $field['subtitle'] . '</p>' : '';

					$title_help = ( ! empty( $field['title_help'] ) ) ? '<span class="wptabspro-help wptabspro-title-help"><span class="wptabspro-help-text">' . $field['title_help'] . '</span> <span class="tooltip-icon"><img src="' . self::include_plugin_url( 'assets/images/info.svg' ) . '"></span></span>' : '';
					echo '<div class="wptabspro-title"><h4>' . wp_kses_post( $field['title'] ) . '</h4>' . wp_kses_post( $title_help . $subtitle ) . '</div>';
				}

				echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '<div class="wptabspro-fieldset">' : '';

				$value = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
				$value = ( isset( $field['value'] ) ) ? $field['value'] : $value;

				self::maybe_include_field( $field_type );

				$classname = 'SP_WP_TABS_Field_' . $field_type;

				if ( class_exists( $classname ) ) {
					$instance = new $classname( $field, $value, $unique, $where, $parent );
					$instance->render();
				} else {
					echo '<p>' . esc_html__( 'This field class is not available!', 'wp-expand-tabs-free' ) . '</p>';
				}
			} else {
				echo '<p>' . esc_html__( 'This type is not found!', 'wp-expand-tabs-free' ) . '</p>';
			}

			echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '</div>' : '';
			echo '<div class="clear"></div>';
			echo '</div>';

		}

	}

	SP_WP_TABS::init();
}
