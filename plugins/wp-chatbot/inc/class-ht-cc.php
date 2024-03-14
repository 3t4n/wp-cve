<?php
/**
 * Starter..
 * 
 * Include files - admin - front end 
 * 
 * add hooks
 * 
 * added variable to declare other instance if needed 
 * ( in some cases in this plugin, using static methods and calling with out creating instance )
 * 
 * @since 2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CC' ) ) :

class HT_CC {

    /**
     * HTCC_VERSION
     * 
     * plugin version
     * no need to call this using instance.. 
     * call using 'constanct'
     * 
     * out side of this class @use defined constant
     * HTCC_VERSION
     * 
     * @uses to define constant  -  HTCC_VERSION
     * 
     * @var float
     * 
     * if Version changed dont forgot to change in plugin header content 
     * wp-chatbot.php - Version
     */
    // directly using with constant ..
    // private $version = '3.5';


    /**
     * singleton instance
     *
     * @var HT_CC
     */
    private static $instance = null;
    

    /**
     * ht-cc-ismobile - ismobile - yes ? no
     *
     * @var int if mobile, tab .. then 1, else 2
     */
    public $device_type;


    /**
     * instance of HT_CC_Variables
     * 
     * database values , .. . options .. 
     *
     * @var HT_CC_Variables
     */
    public $variables = null;


    /**
     * main instance - HT_CCW
     *
     * @return HT_CCW instance
     * @since 1.0
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function __clone() {
		wc_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'click-to-chat-for-whatsapp' ), '1.0' );
    }
    
    public function __wakeup() {
		wc_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'click-to-chat-for-whatsapp' ), '1.0' );
    }



    /**
     * constructor 
     * calling to - includes - which include files
     * calling to - hooks  - which run hooks 
     */
    public function __construct() {
        $this->define_constants();

        $this->basic();

        $this->includes();
        $this->hooks();
    }
    

    /**
     * add the basic things
     * 
     * calling this before include, initilize other things
     * 
     * because this things may useful before initilize other things
     * 
     *  e.g. include, initialize files based on device, user settings
     */
    private function basic() {

        require_once HTCC_PLUGIN_DIR .'inc/commons/class-ht-cc-ismobile.php';
        require_once HTCC_PLUGIN_DIR .'inc/commons/class-ht-cc-variables.php';
        
        $this->device_type = new HT_CC_IsMobile();
        $this->variables = new HT_CC_Variables();

    }
    

    /**
     * Define Constants
     *
     * @return void
     */
    private function define_constants() {
        
        // $this->define( 'HTCC_VERSION', $this->version );

        $this->define( 'HTCC_WP_MIN_VERSION', '4.6' );
        $this->define( 'HTCC_PHP_MIN_VERSION', '5.4' );

        $this->define( 'HTCC_PLUGIN_DIR', plugin_dir_path( HTCC_PLUGIN_FILE ) );
        $this->define( 'HTCC_PLUGIN_BASENAME', plugin_basename( HTCC_PLUGIN_FILE ) );

        $this->define( 'HTCC_PLUGIN_MAIN_MENU', 'wp-chatbot' );
        $this->define( 'HTCC_PLUGIN_LICENSE_MENU', 'wp-chatbot-license' );

        $this->define( 'HTCC_SL_STORE_URL', 'https://www.holithemes.com/shop/' );
        $this->define( 'HTCC_SL_ITEM_ID', 272 );

    }




    /**
     * @uses this->define_constants
     *
     * @param string $name Constant name
     * @param string.. $value Constant value
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }



    
    /**
     * include plugin file
     */
    private function includes() {

        // include in admin and front pages
        require_once HTCC_PLUGIN_DIR .'inc/class-htcc-register.php';


        //  is_admin ? include file to admin area : include files to non-admin area 
        if ( is_admin() ) {
            require_once HTCC_PLUGIN_DIR . 'admin/admin.php';
			require_once HTCC_PLUGIN_DIR . 'inc/class-htcc-test-chatbot.php';
        } else {

            require_once HTCC_PLUGIN_DIR . 'inc/MobileMonkeyApi.php';

            require_once HTCC_PLUGIN_DIR . 'inc/class-htcc-chatbot.php';
            require_once HTCC_PLUGIN_DIR . 'inc/class-htcc-shortcode.php';

            #premium
            if ( 'true' == HTCC_PRO ) {
                include_once HTCC_PLUGIN_DIR . 'inc/pro/class-htcc-pro.php';
            }
            
        }
    }



    /**
     * Register hooks - when plugin activate, deactivate, uninstall
     * commented deactivation, uninstall hook - its not needed as now
     * 
     * plugins_loaded  - Check Diff - uses when plugin updates.
     */

    private function hooks() {

        register_activation_hook( HTCC_PLUGIN_FILE, array( 'HTCC_Register', 'activate' )  );
        register_deactivation_hook( HTCC_PLUGIN_FILE, array( 'HTCC_Register', 'deactivate' )  );
        register_uninstall_hook(HTCC_PLUGIN_FILE, array( 'HTCC_Register', 'uninstall' ) );
		    add_action( 'activated_plugin', array( 'HTCC_Register', 'activate_plugin' ) );
        // initilaze classes
        if ( ! is_admin() ) {
            add_action( 'init', array( $this, 'init' ), 0 );
        }     
        
        // settings page link
        add_filter( 'plugin_action_links_' . HTCC_PLUGIN_BASENAME, array( 'HTCC_Register', 'plugin_action_links' ) );

        // when plugin updated - check version diff
        add_action('plugins_loaded', array( 'HTCC_Register', 'plugin_update' ) );

    }


    public static function view($name, array $args = [])
    {
        $args = apply_filters('plugin_view_arguments', $args, $name);

        foreach ($args as $key => $val) {
            $$key = $val;
        }

        $file = HTCC_PLUGIN_DIR . 'admin/commons/' . $name . '.php';

        include($file);
    }


    /**
     * create instance
     * @uses this->hooks() - using init hook - priority 0
     */
    public function init() {
        
        // $this->variables = new HT_CCW_Variables();

    }



}

endif; // END class_exists check