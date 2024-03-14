<?php
/**
 * Main plugin class file.
 *
 * @package Bricksable/Includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Bricksable {

	/**
	 * The single instance of Bricksable.
	 *
	 * @var     object
	 * @access  private
	 * @since   1.0.0
	 */
	private static $instance = null; //phpcs:ignore

	/**
	 * Local instance of Bricksable_Admin_API
	 *
	 * @var Bricksable_Admin_API|null
	 */
	public $admin = null;

	/**
	 * Settings class object
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_version; //phpcs:ignore

	/**
	 * The token.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $_token; //phpcs:ignore

	/**
	 * The main plugin file.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for JavaScripts.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor funtion.
	 *
	 * @param string $file File constructor.
	 * @param string $version Plugin version.
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		// Check if Bricks Builder is installed.
		if ( 'bricks' !== wp_get_theme()->get( 'Template' ) ) {
			if ( 'Bricks' !== wp_get_theme()->get( 'Name' ) ) {
				return;
			}
		}

		$this->_version = $version;
		$this->_token   = 'bricksable';

		// Load plugin environment variables.
		$this->file       = $file;
		$this->dir        = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions.
		if ( is_admin() ) {
			$this->admin = new Bricksable_Admin_API();
		}

		// Handle localisation.
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	} // End __construct ()

	/**
	 * Register post type function.
	 *
	 * @param string $post_type Post Type.
	 * @param string $plural Plural Label.
	 * @param string $single Single Label.
	 * @param string $description Description.
	 * @param array  $options Options array.
	 *
	 * @return bool|string|Bricksable_Post_Type
	 */
	public function register_post_type( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

		if ( ! $post_type || ! $plural || ! $single ) {
			return false;
		}

		$post_type = new Bricksable_Post_Type( $post_type, $plural, $single, $description, $options );

		return $post_type;
	}

	/**
	 * Wrapper function to register a new taxonomy.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @param string $plural Plural Label.
	 * @param string $single Single Label.
	 * @param array  $post_types Post types to register this taxonomy for.
	 * @param array  $taxonomy_args Taxonomy arguments.
	 *
	 * @return bool|string|Bricksable_Taxonomy
	 */
	public function register_taxonomy( $taxonomy = '', $plural = '', $single = '', $post_types = array(), $taxonomy_args = array() ) {

		if ( ! $taxonomy || ! $plural || ! $single ) {
			return false;
		}

		$taxonomy = new Bricksable_Taxonomy( $taxonomy, $plural, $single, $post_types, $taxonomy_args );

		return $taxonomy;
	}

	/**
	 * Load frontend CSS.
	 *
	 * @access  public
	 * @return void
	 * @since   1.0.0
	 */
	public function enqueue_styles() {
		$bricks_version_ba_check = substr( BRICKS_VERSION, 0, 3 ) > '1.3' ? 'style.1.4.css' : 'style.min.css';
		// Tippy.
		wp_register_style( 'ba-image-hotspot-scale', plugins_url( 'elements/image-hotspots/assets/css/scale.css', __FILE__ ), array(), $this->_version );
		wp_register_style( 'ba-image-hotspot-shift-away', plugins_url( 'elements/image-hotspots/assets/css/shift-away.css', __FILE__ ), array(), $this->_version );
		wp_register_style( 'ba-image-hotspot-shift-toward', plugins_url( 'elements/image-hotspots/assets/css/shift-toward.css', __FILE__ ), array(), $this->_version );
		wp_register_style( 'ba-image-hotspot-perspective', plugins_url( 'elements/image-hotspots/assets/css/perspective.css', __FILE__ ), array(), $this->_version );

		$elements = $this->get_elements();
		foreach ( $elements as $element ) {
			$asset_url = esc_url( trailingslashit( plugins_url( '/includes/elements/' . $element . '/assets/css', $this->file ) ) );
			$asset_src = esc_url( $asset_url ) . $bricks_version_ba_check;

			$element_dir = __DIR__ . '/elements/' . $element . '/assets/css/';
			if ( is_dir( $element_dir ) ) {
				$scan = scandir( $element_dir );

				if ( array_search( $bricks_version_ba_check, $scan, true ) ) {
					wp_register_style( 'ba-' . $element, $asset_src, array(), $this->_version );
					if ( function_exists( 'bricks_is_builder_iframe' ) && bricks_is_builder_iframe() ) {
						if ( 'on' === get_option( 'bricksable_' . $element ) || false === get_option( 'bricksable_' . $element ) ) {
							wp_enqueue_style( 'ba-' . $element );
						}
						// Enqueue Tippy in builder.
						if ( 'on' === get_option( 'bricksable_image-hotspots' ) || false === get_option( 'bricksable_image-hotspots' ) ) {
							wp_enqueue_style( 'ba-image-hotspot-scale' );
							wp_enqueue_style( 'ba-image-hotspot-shift-away' );
							wp_enqueue_style( 'ba-image-hotspot-shift-toward' );
							wp_enqueue_style( 'ba-image-hotspot-perspective' );
						}
					}
				}
			}
		}
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function enqueue_scripts() {
		// Tippy.
		wp_register_script( 'ba-popper', plugins_url( 'elements/image-hotspots/assets/js/popper.min.js', __FILE__ ), array(), $this->_version, true );
		wp_register_script( 'ba-tippy', plugins_url( 'elements/image-hotspots/assets/js/tippy-bundle.umd.min.js', __FILE__ ), array( 'ba-popper' ), $this->_version, true );

		$elements = $this->get_elements();
		foreach ( $elements as $element ) {
			$filename  = 'frontend.min.js';
			$asset_url = esc_url( trailingslashit( plugins_url( '/includes/elements/' . $element . '/assets/js/', $this->file ) ) );
			$asset_src = $asset_url . $filename;

			$element_dir = __DIR__ . '/elements/' . $element . '/assets/js/';
			if ( is_dir( $element_dir ) ) {
				$scan = scandir( $element_dir );

				if ( array_search( $filename, $scan, true ) ) {
					wp_register_script( 'ba-' . $element, $asset_src, array( 'bricks-scripts' ), $this->_version, true );
					if ( function_exists( 'bricks_is_builder_iframe' ) && bricks_is_builder_iframe() ) {
						if ( 'on' === get_option( 'bricksable_' . $element ) || false === get_option( 'bricksable_' . $element ) ) {
							wp_enqueue_script( 'ba-' . $element );

							wp_localize_script(
								'ba-tilt-image',
								'bricksableTiltImageData',
								array(
									'tiltImageInstances' => array(),
								)
							);
							wp_localize_script(
								'ba-text-notation',
								'BricksabletextNotationData',
								array(
									'textNotationInstances' => array(),
								)
							);
							wp_localize_script(
								'ba-lottie',
								'bricksableLottieData',
								array(
									'lottieInstances' => array(),
								)
							);
							wp_localize_script(
								'ba-before-after-image',
								'bricksableBeforeAfterImageData',
								array(
									'BeforeAfterImageInstances' => array(),
								)
							);
							wp_localize_script(
								'ba-read-more',
								'bricksableReadMoreData',
								array(
									'ReadMoreInstances' => array(),
								)
							);
							wp_localize_script(
								'ba-sticky-video',
								'bricksableStickyVideoData',
								array(
									'StickyVideoInstances' => array(),
								)
							);
						}
					}
				}
			}
		}
	} // End enqueue_scripts ()

	/**
	 * Admin enqueue style.
	 *
	 * @param string $hook Hook parameter.
	 *
	 * @return void
	 */
	public function admin_enqueue_styles( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.min.css', array(), $this->_version );
		if ( 'bricks_page_bricksable_settings' === $hook ) {
			wp_enqueue_style( $this->_token . '-admin' );
		}
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 *
	 * @access  public
	 *
	 * @param string $hook Hook parameter.
	 *
	 * @return  void
	 * @since   1.0.0
	 */
	public function admin_enqueue_scripts( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version, true );
		if ( 'bricks_page_bricksable_settings' === $hook ) {
			wp_enqueue_script( $this->_token . '-admin' );
		}
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'bricksable', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin textdomain
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = 'bricksable';

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main Bricksable Instance
	 *
	 * Ensures only one instance of Bricksable is loaded or can be loaded.
	 *
	 * @param string $file File instance.
	 * @param string $version Version parameter.
	 *
	 * @return Object Bricksable instance
	 * @see Bricksable()
	 * @since 1.0.0
	 * @static
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $file, $version );
		}

		return self::$instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Cloning of Bricksable is forbidden' ) ), esc_attr( $this->_version ) );

	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( __( 'Unserializing instances of Bricksable is forbidden' ) ), esc_attr( $this->_version ) );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function install() {
		$this->_log_version_number();
		Bricksable_Review::insert_install_date();
	} // End install ()

	/**
	 * Log the plugin version number.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	private function _log_version_number() { //phpcs:ignore
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()

	/**
	 * Get Elements
	 */
	public function get_elements() {
		$elements     = array();
		$elements_dir = __DIR__ . '/elements';
		$scan         = scandir( $elements_dir );
		foreach ( $scan as $result ) {
			if ( '.' === $result || '..' === $result ) {
				continue;
			}
			if ( is_dir( $elements_dir . '/' . $result ) ) {
				$elements[] = $result;
			}
		}
		return $elements;
	}
}
