<?php
/**
 * Plugin Name: Adaptive Learning with LearnDash
 * Plugin URI: http://wooninjas.com/
 * Description: Adaptive Learning with LearnDash
 * Version: 1.7
 * Author: WooNinjas
 * Author URI: http://wooninjas.com/
 * Text Domain: ld-adaptive-learning
 * @package WooNinjas
 */

if ( !defined ( 'ABSPATH' ) ) exit;
/**
 * Check if LearnDash is enabled
 */
function require_dependency ( ) {
	if ( ! class_exists('SFWD_LMS') ) {
		deactivate_plugins ( plugin_basename ( __FILE__ ), true );
		unset($_GET['activate']);
		$class = 'notice notice-error is-dismissible';
		$message = sprintf( __( '<strong>%s</strong> requires <a href="https://www.learndash.com" target="_blank">LearnDash LMS</a> plugin to be active.', 'ld-adaptive-learning'),'Adaptive Learning with LearnDash');
		printf ( "<div class='%s'> <p>%s</p></div>", $class, $message );
	}
}
add_action ( 'admin_notices', __NAMESPACE__ . "\\require_dependency" );

// Directory
define ( 'AL_DIR', plugin_dir_path ( __FILE__ ) );
define ( 'Al_DIR_FILE', AL_DIR . basename ( __FILE__ ) );
define ( 'AL_INCLUDES_DIR', trailingslashit ( AL_DIR . 'includes' ) );

// URLS
define ( 'AL_URL', trailingslashit ( plugins_url ( '', __FILE__ ) ) );
define ( 'AL_ASSETS_URL', trailingslashit ( AL_URL . 'assets' ) );

// Autoload classes for the plugin
//spl_autoload_register ( __NAMESPACE__ . "\Main::autoloader" );
//require_once INCLUDES_DIR . "functions.php";
//require_once INCLUDES_DIR . "AL_Core_Adaptive_Learning.php";

/**
 * Class Main for plugin initiation
 *
 * @since 1.0.0
 */
final class Main {
    public static $version = '1.7';

    // Main instance
    protected static $_instance = null;

    protected function __construct () {
        register_activation_hook ( __FILE__, array ( $this, 'activation' ) );
        register_deactivation_hook ( __FILE__, array ( $this, 'deactivation' ) );

        // Upgrade
        add_action ( 'plugins_loaded', array ( $this, 'upgrade' ) );
	add_action ( 'admin_menu', array( $this, 'dashboard_menu' ), 1 );
        add_action ( 'admin_enqueue_scripts', array ( $this, 'admin_enqueue_scripts' ) );
        add_action ( 'wp_enqueue_scripts', array ( $this, 'enqueue_scripts' ) );
        $this->includes();

        // new AL_Core_Adaptive_Learning();
        // new AL_Post_Types();
        // new AL_Localize_Script();
        // new AL_Install();
    }
    
    // dashboard menu
    public static function dashboard_menu () {

        if( ! class_exists( 'WN_DASHBOARD_Page' ) )
            require_once AL_INCLUDES_DIR . 'wn-dashboard.php';

        // Create main menu
        global $menu;
        $menuExist = false;
        foreach($menu as $item) {
            if(strtolower($item[2]) == 'wooninjas-dashboard') {
                $menuExist = true;
            }
        }
        
        if( ! $menuExist ) {
            if( class_exists( 'WN_DASHBOARD_Page' ) ) {
                $obj_dashboard = new WN_DASHBOARD_Page();
                // add_menu_page( __( 'Adaptive Learning', 'ld-adaptive-learning' ), __( 'WooNinjas', 'ld-adaptive-learning' ), 'manage_options', 'sfwd-courses-levels', [ $obj_dashboard, 'dashboard_page' ],  'https://wordpress-475011-1491998.cloudwaysapps.com/wn_dashboard_icon.png' );
                // add_submenu_page( 'wooninjas-dashboard', __( 'Dashboard', 'ld-adaptive-learning' ), __( 'Dashboard', 'ld-adaptive-learning' ), 'manage_options', 'wooninjas-dashboard', [ $obj_dashboard, 'dashboard_page' ] );
            }
        }
    }

    public static function includes () {

        if ( file_exists( AL_INCLUDES_DIR . 'admin/AL_Localize_Script.php' ) ) {
            require_once( AL_INCLUDES_DIR . 'admin/AL_Localize_Script.php' );
        }

        if ( file_exists( AL_INCLUDES_DIR . 'admin/AL_Post_Types.php' ) ) {
            require_once( AL_INCLUDES_DIR . 'admin/AL_Post_Types.php' );
        }

        if ( file_exists( AL_INCLUDES_DIR . 'AL_Core_Adaptive_Learning.php' ) ) {
            require_once( AL_INCLUDES_DIR . 'AL_Core_Adaptive_Learning.php' );
        }

        if ( file_exists( AL_INCLUDES_DIR . 'AL_Install.php' ) ) {
            require_once( AL_INCLUDES_DIR . 'AL_Install.php' );
        }

        if ( file_exists( AL_INCLUDES_DIR . 'functions.php' ) ) {
            require_once( AL_INCLUDES_DIR . 'functions.php' );
        }
    }

    public static function autoloader ( $class ) {
        $class = str_replace ( __NAMESPACE__ . "\\" , "" , $class );
        if ( file_exists ( INCLUDES_DIR  . $class . ".php" ) ) {
            include INCLUDES_DIR  . $class . ".php";
        } elseif ( file_exists( INCLUDES_DIR  . "admin" . DIRECTORY_SEPARATOR . $class . ".php" ) ) {
            include INCLUDES_DIR  . "admin/" . $class . ".php";
        }
    }

    /**
     * @return $this
     */
    public static function instance () {
        if ( is_null ( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Activation function hook
     *
     * @return void
     */
    public static function activation () {
        if ( !current_user_can ( 'activate_plugins' ) )
            return;

        update_option ( 'al_version', self::$version );
    }

    /**
     * Deactivation function hook
     * No used in this plugin
     *
     * @return void
     */
    public static function deactivation () {}

    public static function upgrade () {
        if ( get_option ( 'al_version' ) != self::$version ) {
            al_upgrade();
        }
    }

    /**
     * Enqueue scripts on admin
     */
    public static function admin_enqueue_scripts () {

	$obj = new WN_DASHBOARD_Page();
        $obj->enquey_scripts();
        wp_enqueue_style ( 'al-admin-css', AL_ASSETS_URL . 'css/al-admin.css', array(), self::$version );

        $deps = array (
            'jquery',
            'jquery-ui-core',
            'backbone',
            'editor'
        );
        wp_enqueue_script ( 'al-admin-js', AL_ASSETS_URL . 'js/al-admin.js', $deps, self::$version, true );
    }

    /**
     * Enqueue scripts on frontend
     */
    public static function enqueue_scripts () {
        wp_enqueue_style ( 'toastr', AL_ASSETS_URL . 'css/toastr.min.css', array(), self::$version );
        wp_enqueue_style ( 'al-css', AL_ASSETS_URL . 'css/al.css', array(), self::$version );

        $deps = array (
            'jquery',
            'jquery-ui-core',
            'backbone',
            'editor'
        );

        wp_enqueue_script ( 'toastr', AL_ASSETS_URL . 'js/toastr.min.js', $deps, self::$version, true );
        wp_enqueue_script ( 'al-js', AL_ASSETS_URL . 'js/al.js', $deps, self::$version, true );
    }
}

/**
 * Main instance
 *
 * @return Main
 */
function AL() {
    return Main::instance();
}

AL();