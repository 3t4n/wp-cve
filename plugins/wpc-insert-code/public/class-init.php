<?php
/**
 * WPC_Insert_Code
 *
 * @package   WPC_Insert_Code
 * @author    Chris Baldelomar <chris@webplantmedia.com>
 * @license   GPL-2.0+
 * @link      http://webplantmedia.com
 * @copyright 2014 Chris Baldelomar
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package WPC_Insert_Code
 * @author  Chris Baldelomar <chris@webplantmedia.com>
 */
class WPC_Insert_Code {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.2';
	const DB_VERSION = '1.1';

	/**
	 * @TODO - Rename "plugin-name" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'wpc-insert-code';
	protected $plugin_prefix = 'wpc_insert_code';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	public $helper = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		define( 'WPC_INSERT_CODE_IS_ACTIVATED', true );

		$this->helper = WPC_Insert_Code_Helper::get_instance();
		$this->helper->plugin_slug = $this->plugin_slug;
		$this->helper->plugin_prefix = $this->plugin_prefix;

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load public-facing style sheet and JavaScript.
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_head', array( $this, 'insert_code_head' ), 9999 );
		add_action( 'wp_footer', array( $this, 'insert_code_footer' ), 9999 );
		add_action( 'wpc_insert_code_top_of_page', array( $this, 'insert_code_top_of_page' ) );
		add_action( 'wpc_insert_code_above_header', array( $this, 'insert_code_above_header' ) );
		add_action( 'wpc_insert_code_below_header', array( $this, 'insert_code_below_header' ) );
		add_action( 'wpc_insert_code_above_content', array( $this, 'insert_code_above_content' ) );
		add_action( 'wpc_insert_code_below_content', array( $this, 'insert_code_below_content' ) );

		add_filter( 'wpc_insert_code_value', array( $this, 'wrap_code_value' ), 10, 2 );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return the plugin prefix.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_prefix() {
		return $this->plugin_prefix;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	/**
	 * Insert code in head section of HTML document
	 *
	 * @since 3.9
	 * @access public
	 *
	 * @return void
	 */
	public function insert_code_head() {
		if ( $value = get_option( $this->plugin_prefix . '_head' ) ) {
			if ( ! empty( $value ) ) {
				$value = apply_filters( 'wpc_insert_code_value', $value, 'head' );
				echo $value;
			}
		}
	}

	/**
	 * Insert code in footer section of HTML document
	 *
	 * @since 3.9
	 * @access public
	 *
	 * @return void
	 */
	public function insert_code_footer() {
		if ( $value = get_option( $this->plugin_prefix . '_footer' ) ) {
			if ( ! empty( $value ) ) {
				$value = apply_filters( 'wpc_insert_code_value', $value, 'footer' );
				echo $value;
			}
		}
	}

	/**
	 * Insert code at top of page
	 *
	 * @since 3.9
	 * @access public
	 *
	 * @return void
	 */
	public function insert_code_top_of_page() {
		if ( $this->helper->test_theme_support_for_insert( 'top-of-page' ) ) {
			if ( $value = get_option( $this->plugin_prefix . '_top_of_page' ) ) {
				if ( ! empty( $value ) ) {
					$value = apply_filters( 'wpc_insert_code_value', $value, 'top-of-page' );
					echo $value;
				}
			}
		}
	}

	/**
	 * Insert code above header
	 *
	 * @since 3.9
	 * @access public
	 *
	 * @return void
	 */
	public function insert_code_above_header() {
		if ( $this->helper->test_theme_support_for_insert( 'above-header' ) ) {
			if ( $value = get_option( $this->plugin_prefix . '_above_header' ) ) {
				if ( ! empty( $value ) ) {
					$value = apply_filters( 'wpc_insert_code_value', $value, 'above-header' );
					echo $value;
				}
			}
		}
	}

	/**
	 * Insert code below header
	 *
	 * @since 3.9
	 * @access public
	 *
	 * @return void
	 */
	public function insert_code_below_header() {
		if ( $this->helper->test_theme_support_for_insert( 'below-header' ) ) {
			if ( $value = get_option( $this->plugin_prefix . '_below_header' ) ) {
				if ( ! empty( $value ) ) {
					$value = apply_filters( 'wpc_insert_code_value', $value, 'below-header' );
					echo $value;
				}
			}
		}
	}

	/**
	 * Insert code above content
	 *
	 * @since 3.9
	 * @access public
	 *
	 * @return void
	 */
	public function insert_code_above_content() {
		if ( $this->helper->test_theme_support_for_insert( 'above-content' ) ) {
			if ( $value = get_option( $this->plugin_prefix . '_above_content' ) ) {
				if ( ! empty( $value ) ) {
					$value = apply_filters( 'wpc_insert_code_value', $value, 'above-content' );
					echo $value;
				}
			}
		}
	}

	/**
	 * Insert code below content
	 *
	 * @since 3.9
	 * @access public
	 *
	 * @return void
	 */
	public function insert_code_below_content() {
		if ( $this->helper->test_theme_support_for_insert( 'below-content' ) ) {
			if ( $value = get_option( $this->plugin_prefix . '_below_content' ) ) {
				if ( ! empty( $value ) ) {
					$value = apply_filters( 'wpc_insert_code_value', $value, 'below-content' );
					echo $value;
				}
			}
		}
	}

	/**
	 * Wrap certain code snippets in div tag
	 *
	 * @since 3.9
	 * @access public
	 *
	 * @param string $value
	 * @param string $id
	 * @return string
	 */
	public function wrap_code_value( $value, $id ) {
		$value = trim( $value );

		if ( empty( $value ) )
			return $value;

		switch ( $id ) {
			case 'above-header':
			case 'below-header':
			case 'above-content':
			case 'below-content':
				$value = '<div class="wpc-insert-code wpc-insert-code-' . $id . '">' . $value . '</div>';
				break;
		}

		return $value;
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	public static function single_activate() {
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	public static function single_deactivate() {
	}
}
