<?php
/*
Plugin Name: Custom Related Posts
Plugin URI: http://bootstrapped.ventures
Description: Manually define related posts for any custom post type
Version: 1.7.2
Author: Bootstrapped Ventures
Author URI: http://bootstrapped.ventures
Text Domain: custom-related-posts
License: GPLv3
*/
define( 'CRP_VERSION', '1.7.2' );

class CustomRelatedPosts {

    private static $instance;
    private static $instantiated_by_premium;
    private static $addons = array();

    /**
     * Return instance of self
     */
    public static function get( $instantiated_by_premium = false )
    {
        // Instantiate self only once
        if( is_null( self::$instance ) ) {
            self::$instantiated_by_premium = $instantiated_by_premium;
            self::$instance = new self;
            self::$instance->init();
        }

        return self::$instance;
    }

    /**
     * Returns true if we are using the Premium version
     */
    public static function is_premium_active()
    {
        return self::$instantiated_by_premium;
    }

    /**
     * Add loaded addon to array of loaded addons
     */
    public static function loaded_addon( $addon, $instance )
    {
        if( !array_key_exists( $addon, self::$addons ) ) {
            self::$addons[$addon] = $instance;
        }
    }

    /**
     * Returns true if the specified addon has been loaded
     */
    public static function is_addon_active( $addon )
    {
        return array_key_exists( $addon, self::$addons );
    }

    public static function addon( $addon )
    {
        if( isset( self::$addons[$addon] ) ) {
            return self::$addons[$addon];
        }

        return false;
    }

    public static function setting( $setting )
    {
        return self::get()->helper( 'settings' )->get( $setting );
    }

    public $pluginName = 'custom-related-posts';
    public $coreDir;
    public $corePath;
    public $coreUrl;
    public $pluginFile;

    protected $helper_dirs = array();
    protected $helpers = array();

    /**
     * Initialize
     */
    public function init()
    {
        // Update plugin version
        update_option( $this->pluginName . '_version', CRP_VERSION );

        // Set core directory, URL and main plugin file
        $this->corePath = str_replace( '/custom-related-posts.php', '', plugin_basename( __FILE__ ) );
        $this->coreDir = apply_filters( 'crp_core_dir', WP_PLUGIN_DIR . '/' . $this->corePath );
        $this->coreUrl = apply_filters( 'crp_core_url', plugins_url() . '/' . $this->corePath );
        $this->pluginFile = apply_filters( 'crp_plugin_file', __FILE__ );

        // Load textdomain
        if( !self::is_premium_active() ) {
            $domain = 'custom-related-posts';
            $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

            load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
            load_plugin_textdomain( $domain, false, $this->corePath . '/lang/' );
        }

        // Add core helper directory
        $this->add_helper_directory( $this->coreDir . '/helpers' );

        // Migrate first if needed, then load settings.
        $this->helper( 'migration' );
        $this->helper( 'settings' );

        // Load required helpers
        $this->helper( 'activate' );
        $this->helper( 'ajax' );
        $this->helper( 'api' );
        $this->helper( 'blocks' );
        $this->helper( 'cache' );
        $this->helper( 'css' );
        $this->helper( 'giveaway' );
        $this->helper( 'meta_box' );
        $this->helper( 'output' );
        $this->helper( 'overview_page' );
        $this->helper( 'plugin_action_link' );
        $this->helper( 'relations' );
        $this->helper( 'shortcode' );
        $this->helper( 'support_tab' );
        $this->helper( 'widget' );

        // Include required helpers but don't instantiate
        $this->include_helper( 'addons/addon' );
        $this->include_helper( 'addons/premium_addon' );

        // Load core addons
        $this->helper( 'addon_loader' )->load_addons( $this->coreDir . '/addons' );

        // Load default assets
        $this->helper( 'assets' );
    }

    /**
     * Access a helper. Will instantiate if helper hasn't been loaded before.
     */
    public function helper( $helper )
    {
        // Lazy instantiate helper
        if( !isset( $this->helpers[$helper] ) ) {
            $this->include_helper( $helper );

            // Get class name from filename
            $class_name = 'CRP';

            $dirs = explode( '/', $helper );
            $file = end( $dirs );
            $name_parts = explode( '_', $file );
            foreach( $name_parts as $name_part ) {
                $class_name .= '_' . ucfirst( $name_part );
            }

            // Instantiate class if exists
            if( class_exists( $class_name ) ) {
                $this->helpers[$helper] = new $class_name();
            }
        }

        // Return helper instance
        return $this->helpers[$helper];
    }

    /**
     * Include a helper. Looks through all helper directories that have been added.
     */
    public function include_helper( $helper )
    {
        foreach( $this->helper_dirs as $dir )
        {
            $file = $dir . '/'.$helper.'.php';

            if( file_exists( $file ) ) {
                require_once( $file );
            }
        }
    }

    /**
     * Add a directory to look for helpers.
     */
    public function add_helper_directory( $dir )
    {
        if( is_dir( $dir ) ) {
            $this->helper_dirs[] = $dir;
        }
    }

    /*
     * Quick access functions
     */
    public function relation_data( $post )
    {
        return $this->helper( 'relations' )->get_data( $post );
    }

    public function relations_from( $post_id )
    {
        return $this->helper( 'relations' )->get_from( $post_id );
    }

	public function relations_to( $post_id )
	{
		return $this->helper( 'relations' )->get_to( $post_id );
	}
}

// Premium version is responsible for instantiating if available
if( !class_exists( 'CustomRelatedPostsPremium' ) ) {
    CustomRelatedPosts::get();
}