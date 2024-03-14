<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Setup Class
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Setup' ) ) {
	class ADMINIFY_Setup {

		// Default constants
		public static $premium  = true;
		public static $version  = '2.2.8';
		public static $dir      = '';
		public static $url      = '';
		public static $css      = '';
		public static $file     = '';
		public static $enqueue  = false;
		public static $webfonts = [];
		public static $subsets  = [];
		public static $inited   = [];
		public static $fields   = [];
		public static $args     = [
			'admin_options'     => [],
			'customize_options' => [],
			'metabox_options'   => [],
			'nav_menu_options'  => [],
			'profile_options'   => [],
			'taxonomy_options'  => [],
			'widget_options'    => [],
			'comment_options'   => [],
			'shortcode_options' => [],
		];

		// Shortcode instances
		public static $shortcode_instances = [];

		private static $instance = null;

		public static function init( $file = __FILE__, $premium = true ) {

			// Set file constant
			self::$file = $file;

			// Set file constant
			self::$premium = $premium;

			// Set constants
			self::constants();

			// Include files
			self::includes();

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		// Initalize
		public function __construct() {

			// Init action
			do_action( 'adminify_init' );

			// Setup textdomain
			self::textdomain();

			add_action( 'after_setup_theme', [ 'ADMINIFY', 'setup' ] );
			add_action( 'init', [ 'ADMINIFY', 'setup' ] );
			add_action( 'switch_theme', [ 'ADMINIFY', 'setup' ] );
			add_action( 'admin_enqueue_scripts', [ 'ADMINIFY', 'add_admin_enqueue_scripts' ] );
			add_action( 'wp_enqueue_scripts', [ 'ADMINIFY', 'add_typography_enqueue_styles' ], 80 );
			add_action( 'wp_head', [ 'ADMINIFY', 'add_custom_css' ], 80 );
			add_filter( 'admin_body_class', [ 'ADMINIFY', 'add_admin_body_class' ] );
		}

		// Setup frameworks
		public static function setup() {

			// Welcome
			self::include_plugin_file( 'views/welcome.php' );

			// Setup admin option framework
			$params = [];
			if ( class_exists( 'ADMINIFY_Options' ) && ! empty( self::$args['admin_options'] ) ) {
				foreach ( self::$args['admin_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {
						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						ADMINIFY_Options::instance( $key, $params );

						if ( ! empty( $value['show_in_customizer'] ) ) {
							  $value['output_css']                     = false;
							  $value['enqueue_webfont']                = false;
							  self::$args['customize_options'][ $key ] = $value;
							  self::$inited[ $key ]                    = null;
						}
					}
				}
			}

			// Setup customize option framework
			$params = [];
			if ( class_exists( 'ADMINIFY_Customize_Options' ) && ! empty( self::$args['customize_options'] ) ) {
				foreach ( self::$args['customize_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {
						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						ADMINIFY_Customize_Options::instance( $key, $params );
					}
				}
			}

			// Setup metabox option framework
			$params = [];
			if ( class_exists( 'ADMINIFY_Metabox' ) && ! empty( self::$args['metabox_options'] ) ) {
				foreach ( self::$args['metabox_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {
						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						ADMINIFY_Metabox::instance( $key, $params );
					}
				}
			}

			// Setup nav menu option framework
			$params = [];
			if ( class_exists( 'ADMINIFY_Nav_Menu_Options' ) && ! empty( self::$args['nav_menu_options'] ) ) {
				foreach ( self::$args['nav_menu_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {
						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						ADMINIFY_Nav_Menu_Options::instance( $key, $params );
					}
				}
			}

			// Setup profile option framework
			$params = [];
			if ( class_exists( 'ADMINIFY_Profile_Options' ) && ! empty( self::$args['profile_options'] ) ) {
				foreach ( self::$args['profile_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {
						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						ADMINIFY_Profile_Options::instance( $key, $params );
					}
				}
			}

			// Setup taxonomy option framework
			$params = [];
			if ( class_exists( 'ADMINIFY_Taxonomy_Options' ) && ! empty( self::$args['taxonomy_options'] ) ) {
				$taxonomy = ( isset( $_GET['taxonomy'] ) ) ? sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) ) : '';
				foreach ( self::$args['taxonomy_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {
						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						ADMINIFY_Taxonomy_Options::instance( $key, $params );
					}
				}
			}

			// Setup widget option framework
			if ( class_exists( 'ADMINIFY_Widget' ) && class_exists( 'WP_Widget_Factory' ) && ! empty( self::$args['widget_options'] ) ) {
				$wp_widget_factory = new WP_Widget_Factory();
				global $wp_widget_factory;
				foreach ( self::$args['widget_options'] as $key => $value ) {
					if ( ! isset( self::$inited[ $key ] ) ) {
						self::$inited[ $key ] = true;
						$wp_widget_factory->register( ADMINIFY_Widget::instance( $key, $value ) );
					}
				}
			}

			// Setup comment option framework
			$params = [];
			if ( class_exists( 'ADMINIFY_Comment_Metabox' ) && ! empty( self::$args['comment_options'] ) ) {
				foreach ( self::$args['comment_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {
						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						ADMINIFY_Comment_Metabox::instance( $key, $params );
					}
				}
			}

			// Setup shortcode option framework
			$params = [];
			if ( class_exists( 'ADMINIFY_Shortcoder' ) && ! empty( self::$args['shortcode_options'] ) ) {
				foreach ( self::$args['shortcode_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {
						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						ADMINIFY_Shortcoder::instance( $key, $params );
					}
				}

				// Once editor setup for gutenberg and media buttons
				if ( class_exists( 'ADMINIFY_Shortcoder' ) && ! empty( self::$shortcode_instances ) ) {
					foreach ( self::$shortcode_instances as $instance ) {
						if ( ! empty( $instance['show_in_editor'] ) ) {
							  ADMINIFY_Shortcoder::once_editor_setup();
							  break;
						}
					}
				}
			}

			do_action( 'adminify_loaded' );
		}

		// Create options
		public static function createOptions( $id, $args = [] ) {
			self::$args['admin_options'][ $id ] = $args;
		}

		// Create customize options
		public static function createCustomizeOptions( $id, $args = [] ) {
			self::$args['customize_options'][ $id ] = $args;
		}

		// Create metabox options
		public static function createMetabox( $id, $args = [] ) {
			self::$args['metabox_options'][ $id ] = $args;
		}

		// Create menu options
		public static function createNavMenuOptions( $id, $args = [] ) {
			self::$args['nav_menu_options'][ $id ] = $args;
		}

		// Create shortcoder options
		public static function createShortcoder( $id, $args = [] ) {
			self::$args['shortcode_options'][ $id ] = $args;
		}

		// Create taxonomy options
		public static function createTaxonomyOptions( $id, $args = [] ) {
			self::$args['taxonomy_options'][ $id ] = $args;
		}

		// Create profile options
		public static function createProfileOptions( $id, $args = [] ) {
			self::$args['profile_options'][ $id ] = $args;
		}

		// Create widget
		public static function createWidget( $id, $args = [] ) {
			self::$args['widget_options'][ $id ] = $args;
			self::set_used_fields( $args );
		}

		// Create comment metabox
		public static function createCommentMetabox( $id, $args = [] ) {
			self::$args['comment_options'][ $id ] = $args;
		}

		// Create section
		public static function createSection( $id, $sections ) {
			self::$args['sections'][ $id ][] = $sections;
			self::set_used_fields( $sections );
		}

		// Set directory constants
		public static function constants() {

			// We need this path-finder code for set URL of framework
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

		// Include file helper
		public static function include_plugin_file( $file, $load = true ) {
			$path     = '';
			$file     = ltrim( $file, '/' );
			$override = apply_filters( 'adminify_override', 'adminify-override' );

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

		// Is active plugin helper
		public static function is_active_plugin( $file = '' ) {
			return in_array( $file, (array) get_option( 'active_plugins', [] ) );
		}

		// Sanitize dirname
		public static function sanitize_dirname( $dirname ) {
			return preg_replace( '/[^A-Za-z]/', '', $dirname );
		}

		// Set url constant
		public static function include_plugin_url( $file ) {
			return esc_url( self::$url ) . '/' . ltrim( $file, '/' );
		}

		// Include files
		public static function includes() {

			// Include common functions
			self::include_plugin_file( 'functions/actions.php' );
			self::include_plugin_file( 'functions/helpers.php' );
			self::include_plugin_file( 'functions/sanitize.php' );
			self::include_plugin_file( 'functions/validate.php' );

			// Include free version classes
			self::include_plugin_file( 'classes/abstract.class.php' );
			self::include_plugin_file( 'classes/fields.class.php' );
			self::include_plugin_file( 'classes/admin-options.class.php' );

			// Include premium version classes
			if ( self::$premium ) {
				self::include_plugin_file( 'classes/customize-options.class.php' );
				self::include_plugin_file( 'classes/metabox-options.class.php' );
				self::include_plugin_file( 'classes/nav-menu-options.class.php' );
				self::include_plugin_file( 'classes/profile-options.class.php' );
				self::include_plugin_file( 'classes/shortcode-options.class.php' );
				self::include_plugin_file( 'classes/taxonomy-options.class.php' );
				self::include_plugin_file( 'classes/widget-options.class.php' );
				self::include_plugin_file( 'classes/comment-options.class.php' );
			}

			// Include all framework fields
			$fields = apply_filters(
				'adminify_fields',
				[
					'accordion',
					'background',
					'backup',
					'border',
					'button_set',
					'callback',
					'checkbox',
					'code_editor',
					'color',
					'color_group',
					'content',
					'date',
					'datetime',
					'dimensions',
					'fieldset',
					'gallery',
					'group',
					'heading',
					'icon',
					'image_select',
					'link',
					'link_color',
					'map',
					'media',
					'notice',
					'number',
					'palette',
					'radio',
					'repeater',
					'select',
					'slider',
					'sortable',
					'sorter',
					'spacing',
					'spinner',
					'subheading',
					'submessage',
					'switcher',
					'tabbed',
					'text',
					'textarea',
					'typography',
					'upload',
					'wp_editor',
				]
			);

			if ( ! empty( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( ! class_exists( 'ADMINIFY_Field_' . $field ) && class_exists( 'ADMINIFY_Fields' ) ) {
						self::include_plugin_file( 'fields/' . $field . '/' . $field . '.php' );
					}
				}
			}
		}

		// Setup textdomain
		public static function textdomain() {
			load_textdomain( 'adminify', self::$dir . '/languages/' . get_locale() . '.mo' );
		}

		// Set all of used fields
		public static function set_used_fields( $sections ) {
			if ( ! empty( $sections['fields'] ) ) {
				foreach ( $sections['fields'] as $field ) {
					if ( ! empty( $field['fields'] ) ) {
						self::set_used_fields( $field );
					}

					if ( ! empty( $field['tabs'] ) ) {
						self::set_used_fields( [ 'fields' => $field['tabs'] ] );
					}

					if ( ! empty( $field['accordions'] ) ) {
							self::set_used_fields( [ 'fields' => $field['accordions'] ] );
					}

					if ( ! empty( $field['type'] ) ) {
						  self::$fields[ $field['type'] ] = $field;
					}
				}
			}
		}

		// Enqueue admin and fields styles and scripts
		public static function add_admin_enqueue_scripts() {
			if ( ! self::$enqueue ) {

				// Loads scripts and styles only when needed
				$wpscreen = get_current_screen();

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

				if ( ! empty( self::$args['taxonomy_options'] ) ) {
					foreach ( self::$args['taxonomy_options'] as $argument ) {
						if ( in_array( $wpscreen->taxonomy, (array) $argument['taxonomy'] ) ) {
							  self::$enqueue = true;
						}
					}
				}

				if ( ! empty( self::$shortcode_instances ) ) {
					foreach ( self::$shortcode_instances as $argument ) {
						if ( ( $argument['show_in_editor'] && $wpscreen->base === 'post' ) || $argument['show_in_custom'] ) {
							  self::$enqueue = true;
						}
					}
				}

				if ( ! empty( self::$args['widget_options'] ) && ( $wpscreen->id === 'widgets' || $wpscreen->id === 'customize' ) ) {
					self::$enqueue = true;
				}

				if ( ! empty( self::$args['customize_options'] ) && $wpscreen->id === 'customize' ) {
					self::$enqueue = true;
				}

				if ( ! empty( self::$args['nav_menu_options'] ) && $wpscreen->id === 'nav-menus' ) {
					self::$enqueue = true;
				}

				if ( ! empty( self::$args['profile_options'] ) && ( $wpscreen->id === 'profile' || $wpscreen->id === 'user-edit' ) ) {
					self::$enqueue = true;
				}

				if ( ! empty( self::$args['comment_options'] ) && $wpscreen->id === 'comment' ) {
					self::$enqueue = true;
				}

				if ( $wpscreen->id === 'tools_page_adminify-welcome' ) {
					self::$enqueue = true;
				}
			}

			if ( ! apply_filters( 'adminify_enqueue_assets', self::$enqueue ) ) {
				return;
			}

			// Admin utilities
			wp_enqueue_media();

			// Wp color picker
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			// Font awesome 4 and 5 loader
			if ( apply_filters( 'adminify_fa4', false ) ) {
				wp_enqueue_style( 'adminify-fa', 'https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css', [], '4.7.0', 'all' );
			} else {
				wp_enqueue_style( 'adminify-fa5', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css', [], '5.15.5', 'all' );
				wp_enqueue_style( 'adminify-fa5-v4-shims', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/v4-shims.min.css', [], '5.15.5', 'all' );
			}

			// Check for developer mode
			$min = ( self::$premium && SCRIPT_DEBUG ) ? '' : '.min';

			// Main style
			wp_enqueue_style( 'adminify', self::include_plugin_url( 'assets/css/style' . $min . '.css' ), [], self::$version, 'all' );

			// Main RTL styles
			if ( is_rtl() ) {
				wp_enqueue_style( 'adminify-rtl', self::include_plugin_url( 'assets/css/style-rtl' . $min . '.css' ), [], self::$version, 'all' );
			}

			// Main scripts
			wp_enqueue_script( 'adminify-plugins', self::include_plugin_url( 'assets/js/plugins' . $min . '.js' ), [], self::$version, true );
			wp_enqueue_script( 'adminify', self::include_plugin_url( 'assets/js/main' . $min . '.js' ), [ 'adminify-plugins' ], self::$version, true );

			// Main variables
			wp_localize_script(
				'adminify',
				'adminify_vars',
				[
					'color_palette' => apply_filters( 'adminify_color_palette', [] ),
					'i18n'          => [
						'confirm'         => esc_html__( 'Are you sure?', 'adminify' ),
						'typing_text'     => esc_html__( 'Please enter %s or more characters', 'adminify' ),
						'searching_text'  => esc_html__( 'Searching...', 'adminify' ),
						'no_results_text' => esc_html__( 'No results found.', 'adminify' ),
					],
				]
			);

			// Enqueue fields scripts and styles
			$enqueued = [];

			if ( ! empty( self::$fields ) ) {
				foreach ( self::$fields as $field ) {
					if ( ! empty( $field['type'] ) ) {
						$classname = 'ADMINIFY_Field_' . $field['type'];
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

			do_action( 'adminify_enqueue' );
		}

		// Add typography enqueue styles to front page
		public static function add_typography_enqueue_styles() {
			if ( ! empty( self::$webfonts ) ) {
				if ( ! empty( self::$webfonts['enqueue'] ) ) {
					$query = [];
					$fonts = [];

					foreach ( self::$webfonts['enqueue'] as $family => $styles ) {
						$fonts[] = $family . ( ( ! empty( $styles ) ) ? ':' . implode( ',', $styles ) : '' );
					}

					if ( ! empty( $fonts ) ) {
						$query['family'] = implode( '%7C', $fonts );
					}

					if ( ! empty( self::$subsets ) ) {
						  $query['subset'] = implode( ',', self::$subsets );
					}

					$query['display'] = 'swap';

					wp_enqueue_style( 'adminify-google-web-fonts', esc_url( add_query_arg( $query, '//fonts.googleapis.com/css' ) ), [], null );
				}

				if ( ! empty( self::$webfonts['async'] ) ) {
					$fonts = [];

					foreach ( self::$webfonts['async'] as $family => $styles ) {
						$fonts[] = $family . ( ( ! empty( $styles ) ) ? ':' . implode( ',', $styles ) : '' );
					}

					wp_enqueue_script( 'adminify-google-web-fonts', esc_url( '//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js' ), [], null );

					wp_localize_script( 'adminify-google-web-fonts', 'WebFontConfig', [ 'google' => [ 'families' => $fonts ] ] );
				}
			}
		}

		// Add admin body class
		public static function add_admin_body_class( $classes ) {
			if ( apply_filters( 'adminify_fa4', false ) ) {
				$classes .= 'adminify-fa5-shims';
			}

			return $classes;
		}

		// Add custom css to front page
		public static function add_custom_css() {
			if ( ! empty( self::$css ) ) {
				echo '<style type="text/css">' . esc_attr( self::$css ) . '</style>';
			}
		}

		// Add a new framework field
		public static function field( $field = [], $value = '', $unique = '', $where = '', $parent = '' ) {

			// Check for unallow fields
			if ( ! empty( $field['_notice'] ) ) {
				$field_type = $field['type'];

				$field            = [];
				$field['content'] = esc_html__( 'Oops! Not allowed.', 'adminify' ) . ' <strong>(' . $field_type . ')</strong>';
				$field['type']    = 'notice';
				$field['style']   = 'danger';
			}

			$depend     = '';
			$visible    = '';
			$unique     = ( ! empty( $unique ) ) ? $unique : '';
			$class      = ( ! empty( $field['class'] ) ) ? ' ' . esc_attr( $field['class'] ) : '';
			$is_pseudo  = ( ! empty( $field['pseudo'] ) ) ? ' adminify-pseudo-field' : '';
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

				$depend .= ' data-controller="' . $data_controller . '"';
				$depend .= ' data-condition="' . $data_condition . '"';
				$depend .= ' data-value="' . $data_value . '"';
				$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

				$visible = ( ! empty( $depend_visible ) ) ? ' adminify-depend-visible' : ' adminify-depend-hidden';
			}

			// These attributes has been sanitized above.
			echo '<div class="adminify-field adminify-field-' . esc_attr( $field_type . $is_pseudo . $class . $visible ) . '"' . wp_kses_post( $depend ) . '>';

			if ( ! empty( $field_type ) ) {
				if ( ! empty( $field['title'] ) ) {
					echo '<div class="adminify-title">';
					echo '<h4>' . wp_kses_post( $field['title'] ) . '</h4>';
					echo ( ! empty( $field['subtitle'] ) ) ? '<div class="adminify-subtitle-text">' . wp_kses_post( $field['subtitle'] ) . '</div>' : '';
					echo '</div>';
				}

				echo ( ! empty( $field['title'] ) ) ? '<div class="adminify-fieldset">' : '';

				$value = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
				$value = ( isset( $field['value'] ) ) ? $field['value'] : $value;

				$classname = 'ADMINIFY_Field_' . $field_type;

				if ( class_exists( $classname ) ) {
					$instance = new $classname( $field, $value, $unique, $where, $parent );
					$instance->render();
				} else {
					echo '<p>' . esc_html__( 'Field not found!', 'adminify' ) . '</p>';
				}
			} else {
				  echo '<p>' . esc_html__( 'Field not found!', 'adminify' ) . '</p>';
			}

			echo ( ! empty( $field['title'] ) ) ? '</div>' : '';
			echo '<div class="clear"></div>';
			echo '</div>';
		}

	}

}

ADMINIFY_Setup::init( __FILE__, true );

/**
 *
 * Extended Setup Class for Shortland
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY' ) ) {
	class ADMINIFY extends ADMINIFY_Setup{}
}
