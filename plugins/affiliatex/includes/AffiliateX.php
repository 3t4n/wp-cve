<?php
/**
 * Main Plugin class
 *
 * @package AffiliateX
 */

namespace AffiliateX;

defined( 'ABSPATH' ) || exit;

/**
 * Final AffiliateX class.
 *
 * @package AffiliateX
 */
final class AffiliateX {

	/**
	 * Version
	 */
	public $version = '1.2.2';

	/**
	 * Single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Class Instance
	 *
	 * @return class instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Plugin constructor
	 */
	public function __construct() {
		$this->defineConstants();
		$this->includes();
		$this->init_hooks();

		$this->admin_settings = new AffiliateXAdmin();
		$this->public         = new AffiliateXPublic();
	}

	/**
	 * Fires during plugin activation
	 *
	 * @return void
	 */
	public function activate() {

	}

	/**
	 * Fires during plugin deactivation
	 *
	 * @return void
	 */
	public function deactivate() {

	}

	/**
	 * Init plugin hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		register_activation_hook( AFFILIATEX_PLUGIN_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( AFFILIATEX_PLUGIN_FILE, array( $this, 'deactivate' ) );

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
     * Init Wheel_Of_Life when WordPress initializes.
     *
     * @since 1.0.0
     * @access public
     */
    public function init() {
        // Before init action.
		do_action( 'before_affiliatex_init' );

        // Set up localization.
        $this->loadPluginTextdomain();
    }

	/**
	 * Define constants
	 *
	 * @return void
	 */
	public function defineConstants() {
		$this->define( 'AFFILIATEX_PLUGIN_NAME', 'affiliatex' );
		$this->define( 'AFFILIATEX_ABSPATH', dirname( AFFILIATEX_PLUGIN_FILE ) . '/' );
		$this->define( 'AFFILIATEX_VERSION', $this->version );
		$this->define( 'AFFILIATEX_TABLET_BREAKPOINT', '1024' );
		$this->define( 'AFFILIATEX_MOBILE_BREAKPOINT', '767' );
	}

	/**
	 * Plugin includes
	 *
	 * @return void
	 */
	public function includes() {
		require plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . '/includes/helpers/class-affiliatex-helpers.php';
		require plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . 'includes/helpers/class-affiliatex-block-helpers.php';
		require plugin_dir_path( AFFILIATEX_PLUGIN_FILE ) . 'includes/classes/class-ab-fonts-manager.php';
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name       Constant name.
	 * @param string|bool $value      Constant value.
	 * @return void
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 *
	 * Note: the first-loaded translation file overrides any following ones -
	 * - if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/affiliatex/affiliatex-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/affiliatex-LOCALE.mo
	 */
	public function loadPluginTextdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}

		$locale = apply_filters( 'plugin_locale', $locale, 'affiliatex' );

		unload_textdomain( 'affiliatex' );
		load_textdomain( 'affiliatex', WP_LANG_DIR . '/affiliatex/affiliatex-' . $locale . '.mo' );
		load_plugin_textdomain(
			'affiliatex',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

}
