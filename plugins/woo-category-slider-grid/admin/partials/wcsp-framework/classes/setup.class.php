<?php

/**
 * Framework setup.class file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/framework
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WCS' ) ) {
	/**
	 *
	 * Setup Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WCS {

		/**
		 * Version.
		 *
		 * @var string
		 */
		public static $version = '2.0.6';
		/**
		 * Premium.
		 *
		 * @var string
		 */
		public static $premium = true;
		/**
		 * Dir.
		 *
		 * @var string
		 */
		public static $dir = null;
		/**
		 * Url.
		 *
		 * @var string
		 */
		public static $url = null;
		/**
		 * Init.
		 *
		 * @var array
		 */
		public static $inited = array();
		/**
		 * Field.
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
		 * Init
		 *
		 * @return void
		 */
		public static function init() {

			// init action.
			do_action( 'spf_init' );

			// set constants.
			self::constants();

			// include files.
			self::includes();

			// setup textdomain.
			self::textdomain();

			add_action( 'after_setup_theme', array( 'SP_WCS', 'setup' ) );
			add_action( 'init', array( 'SP_WCS', 'setup' ) );
			add_action( 'switch_theme', array( 'SP_WCS', 'setup' ) );
			add_action( 'admin_enqueue_scripts', array( 'SP_WCS', 'add_admin_enqueue_scripts' ), 20 );

		}

		/**
		 * Setup
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

						SP_WCS_Options::instance( $key, $params );

						if ( ! empty( $value['show_in_customizer'] ) ) {
							self::$args['customize_options'][ $key ] = ( is_array( $value['show_in_customizer'] ) ) ? $value['show_in_customizer'] : $value;
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

						SP_WCS_Metabox::instance( $key, $params );

					}
				}
			}

			do_action( 'spf_loaded' );

		}

		/**
		 * Create Options
		 *
		 * @param  mixed $id ID.
		 * @param  mixed $args Args.
		 * @return void
		 */
		public static function createOptions( $id, $args = array() ) {
			self::$args['options'][ $id ] = $args;
		}

		/**
		 * Create metabox options.
		 *
		 * @param  mixed $id ID.
		 * @param  mixed $args Args.
		 * @return void
		 */
		public static function createMetabox( $id, $args = array() ) {
			self::$args['metaboxes'][ $id ] = $args;
		}

		/**
		 * Create section.
		 *
		 * @param  mixed $id ID.
		 * @param  mixed $sections Sections.
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

			// we need this path-finder code for set URL of framework.
			$dirname        = wp_normalize_path( dirname( dirname( __FILE__ ) ) );
			$theme_dir      = wp_normalize_path( get_theme_file_path() );
			$plugin_dir     = wp_normalize_path( WP_PLUGIN_DIR );
			$located_plugin = ( preg_match( '#' . self::sanitize_dirname( $plugin_dir ) . '#', self::sanitize_dirname( $dirname ) ) ) ? true : false;
			$directory      = ( $located_plugin ) ? $plugin_dir : $theme_dir;
			$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_theme_file_uri();
			$foldername     = str_replace( $directory, '', $dirname );

			self::$dir = $dirname;
			self::$url = $directory_uri . $foldername;

		}

		/**
		 * Include plugin files
		 *
		 * @param  mixed $file file.
		 * @param  mixed $load load.
		 * @return array
		 */
		public static function include_plugin_file( $file, $load = true ) {

			$path     = '';
			$file     = ltrim( $file, '/' );
			$override = apply_filters( 'spf_override', 'spf-override' );

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
			return esc_url( SP_WCS_URL . '/admin/partials/wcsp-framework' ) . '/' . ltrim( $file, '/' );
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

			// includes free version classes.
			self::include_plugin_file( 'classes/abstract.class.php' );
			self::include_plugin_file( 'classes/fields.class.php' );
			self::include_plugin_file( 'classes/options.class.php' );

			// includes premium version classes.
			if ( self::$premium ) {
				self::include_plugin_file( 'classes/metabox.class.php' );
			}

		}

		/**
		 * Include field.
		 *
		 * @param  mixed $type type.
		 * @return void
		 */
		public static function maybe_include_field( $type = '' ) {
			if ( ! class_exists( 'SP_WCS_Field_' . $type ) && class_exists( 'SP_WCS_Fields' ) ) {
				self::include_plugin_file( 'fields/' . $type . '/' . $type . '.php' );
			}
		}

		/**
		 * Load textdomain.
		 *
		 * @return void
		 */
		public static function textdomain() {
			require_once SP_WCS_INCLUDES . '/class-woo-category-slider-i18n.php';
			$plugin_i18n = new Woo_Category_Slider_i18n();
			$plugin_i18n->load_plugin_textdomain();
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
			$current_screen        = get_current_screen();
			$the_current_taxonomy  = $current_screen->taxonomy;
			$the_current_post_type = $current_screen->post_type;
			if ( 'sp_wcslider' === $the_current_post_type || 'product_cat' === $the_current_taxonomy ) {

				// check for developer mode.
				$min = ( apply_filters( 'spf_dev_mode', false ) || WP_DEBUG ) ? '' : '.min';

				// admin utilities.
				wp_enqueue_media();

				// wp color picker.
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );

				// Framework core styles.
				wp_enqueue_style( 'sp-wcs-font-awesome', SP_WCS_URL . 'public/css/font-awesome.min.css', array(), SP_WCS_VERSION, 'all' );
				wp_enqueue_style( 'spf', SP_WCS_URL . 'admin/partials/wcsp-framework/assets/css/spf' . $min . '.css', array(), SP_WCS_VERSION, 'all' );

				// rtl styles.
				if ( is_rtl() ) {
					wp_enqueue_style( 'spf-rtl', SP_WCS_URL . 'admin/partials/wcsp-framework/assets/css/spf-rtl' . $min . '.css', array(), SP_WCS_VERSION, 'all' );
				}

				// framework core scripts.
				wp_enqueue_script( 'spf-plugins', SP_WCS_URL . 'admin/partials/wcsp-framework/assets/js/spf-plugins' . $min . '.js', array(), SP_WCS_VERSION, true );
				wp_enqueue_script( 'spf', SP_WCS_URL . 'admin/partials/wcsp-framework/assets/js/spf' . $min . '.js', array( 'spf-plugins' ), SP_WCS_VERSION, true );

				wp_localize_script(
					'spf',
					'spf_vars',
					array(
						'color_palette' => apply_filters( 'spf_color_palette', array() ),
						'i18n'          => array(
							'confirm'             => esc_html__( 'Are you sure?', 'woo-category-slider-grid' ),
							'reset_notification'  => esc_html__( 'Restoring options.', 'woo-category-slider-grid' ),
							'import_notification' => esc_html__( 'Importing options.', 'woo-category-slider-grid' ),
						),
					)
				);

				// load admin enqueue scripts and styles.
				$enqueued = array();

				if ( ! empty( self::$fields ) ) {
					foreach ( self::$fields as $field ) {
						if ( ! empty( $field['type'] ) ) {
								$classname = 'SP_WCS_Field_' . $field['type'];
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

				do_action( 'spf_enqueue' );
			} // Check screen ID.

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

			// Check for unallow fields.
			if ( ! empty( $field['_notice'] ) ) {

				$field_type = $field['type'];

				$field = array();
				/* translators: %s: field content */
				$field['content'] = sprintf( esc_html__( 'Ooops! This field type (%s) can not be used here, yet.', 'woo-category-slider-grid' ), '<strong>' . $field_type . '</strong>' );
				$field['type']    = 'notice';
				$field['style']   = 'danger';

			}

			$depend     = '';
			$hidden     = '';
			$unique     = ( ! empty( $unique ) ) ? $unique : '';
			$class      = ( ! empty( $field['class'] ) ) ? ' ' . $field['class'] : '';
			$is_pseudo  = ( ! empty( $field['pseudo'] ) ) ? ' spf-pseudo-field' : '';
			$field_type = ( ! empty( $field['type'] ) ) ? $field['type'] : '';

			if ( ! empty( $field['dependency'] ) ) {
				$hidden  = ' hidden';
				$depend .= ' data-controller="' . $field['dependency'][0] . '"';
				$depend .= ' data-condition="' . $field['dependency'][1] . '"';
				$depend .= ' data-value="' . $field['dependency'][2] . '"';
				$depend .= ( ! empty( $field['dependency'][3] ) ) ? ' data-depend-global="true"' : '';
			}

			if ( ! empty( $field_type ) ) {

				echo '<div class="spf-field spf-field-' . esc_attr( $field_type . $is_pseudo . $class . $hidden ) . '"' . wp_kses_post( $depend ) . '>';

				if ( ! empty( $field['title'] ) ) {
					$subtitle   = ( ! empty( $field['subtitle'] ) ) ? '<p class="spf-text-subtitle">' . $field['subtitle'] . '</p>' : '';
					$title_help = ( ! empty( $field['title_help'] ) ) ? '<span class="spf-help spf-title-help"><span class="spf-help-text">' . $field['title_help'] . '</span><span class="fa fa-question-circle"></span></span>' : '';
					echo '<div class="spf-title"><h4>' . wp_kses_post( $field['title'] ) . '</h4>' . wp_kses_post( $title_help . $subtitle ) . '</div>';

				}

				echo ( ! empty( $field['title'] ) ) ? '<div class="spf-fieldset">' : '';

				$value = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
				$value = ( isset( $field['value'] ) ) ? $field['value'] : $value;

				self::maybe_include_field( $field_type );

				$classname = 'SP_WCS_Field_' . $field_type;

				if ( class_exists( $classname ) ) {
					$instance = new $classname( $field, $value, $unique, $where, $parent );
					$instance->render();
				} else {
					echo '<p>' . esc_html__( 'This field class is not available!', 'woo-category-slider-grid' ) . '</p>';
				}
			} else {
				echo '<p>' . esc_html__( 'This type is not found!', 'woo-category-slider-grid' ) . '</p>';
			}

			echo ( ! empty( $field['title'] ) ) ? '</div>' : '';
			echo '<div class="clear"></div>';
			echo '</div>';

		}

	}

	SP_WCS::init();
}
