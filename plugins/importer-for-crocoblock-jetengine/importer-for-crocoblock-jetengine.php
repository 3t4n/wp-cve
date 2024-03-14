<?php
/*
Plugin Name: Coding Chicken - JetEngine Importer
Description: A handy importer for JetEngine Meta Box fields - Requires WP All Import and JetEngine
Version: 1.2.2
Author: Coding Chicken
Author URI: https://codingchicken.com
*/

namespace CodingChicken\Importer\JetEngine;

/**
 * Plugin root dir with using forward slashes.
 * @var string
 */
define('IMPORTER_JETENGINE_ROOT_DIR', str_replace('\\', '/', dirname(__FILE__)));
/**
 * Plugin root url for referencing static content.
 * @var string
 */
define('IMPORTER_JETENGINE_ROOT_URL', rtrim(plugin_dir_url(__FILE__), '/'));
/**
 * Plugin prefix for making names unique (be aware that this variable is used in conjuction with naming convention,
 * i.e. in order to change it one must not only modify this constant but also rename all constants, classes and functions which
 * names composed using this prefix)
 * @var string
 */
const IMPORTER_JETENGINE_PREFIX = 'cc_jetengine_importer_';

const IMPORTER_JETENGINE_VERSION = '1.2.2';

// Require Composer autoloader.
require IMPORTER_JETENGINE_ROOT_DIR . '/vendor/autoload.php';

final class IMPORTER_JETENGINE_Plugin {
    /**
     * Singletone instance
     * @var IMPORTER_JETENGINE_Plugin
     */
    protected static $instance;

	/**
	 * Add On instance.
	 * @var \Soflyy\WpAllImport\RapidAddon
	 */
	protected static $add_on;

    public static $version = IMPORTER_JETENGINE_VERSION;

	protected $notices;

    protected static $slug = 'cc_jetengine_importer_';

    /**
     * Plugin root dir
     * @var string
     */
    const ROOT_DIR = IMPORTER_JETENGINE_ROOT_DIR;
    /**
     * Plugin root URL
     * @var string
     */
    const ROOT_URL = IMPORTER_JETENGINE_ROOT_URL;
    /**
     * Prefix used for names of shortcodes, action handlers, filter functions etc.
     * @var string
     */
    const PREFIX = IMPORTER_JETENGINE_PREFIX;
    /**
     * Plugin file path
     * @var string
     */
    const FILE = __FILE__;

    /**
     * Return singleton instance
     * @return IMPORTER_JETENGINE_Plugin
     */
    static public function getInstance(): IMPORTER_JETENGINE_Plugin {
        if (self::$instance == NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	/**
	 * @return \Soflyy\WpAllImport\RapidAddon
	 */
    static public function getAddon(): \Soflyy\WpAllImport\RapidAddon {
        if(self::$add_on == NULL){
            self::$add_on = new \Soflyy\WpAllImport\RapidAddon( 'Coding Chicken - JetEngine Importer', self::$slug );
        }

        return self::$add_on;
    }

    /**
     * Common logic for requesting plugin info fields
     */
    public function __call($method, $args) {
        if (preg_match('%^get(.+)%i', $method, $mtch)) {
            $info = get_plugin_data(self::FILE);
            if (isset($info[$mtch[1]])) {
                return $info[$mtch[1]];
            }
        }
        throw new \Exception("Requested method " . get_class($this) . "::$method doesn't exist.");
    }

    /**
     * Get path to plugin dir relative to WordPress root
     * @param bool[optional] $noForwardSlash Whether path should be returned without forwarding slash
     * @return string
     */
    public function getRelativePath($noForwardSlash = false) {
        $wp_root = str_replace('\\', '/', ABSPATH);
        return ($noForwardSlash ? '' : '/') . str_replace($wp_root, '', self::ROOT_DIR);
    }

    /**
     * Check whether plugin is activated as network one
     * @return bool
     */
    public function isNetwork() {
        if ( !is_multisite() )
            return false;

        $plugins = get_site_option('active_sitewide_plugins');
        if (isset($plugins[plugin_basename(self::FILE)]))
            return true;

        return false;
    }

    /**
     * Class constructor containing dispatching logic
     * @param string $rootDir Plugin root dir
     * @param string $pluginFilePath Plugin main file
     */
    protected function __construct() {

        register_activation_hook( self::FILE, array( $this, 'activation' ) );

        // register admin page pre-dispatcher
        add_action( 'admin_init', array( $this, 'adminInit' ) );
        add_action( 'init', array( $this, 'init' ), 12 ); // Priority 12 so we can tie into it with Pro.

    }

    public function init(){

	    // Make sure the required plugins are active before we do anything else.
	    if( !function_exists('\jet_engine') || !class_exists('\PMXI_Plugin') ){

		    $notice_message = 'The Coding Chicken - JetEngine Importer requires WP All Import <a href="http://www.wpallimport.com/order-now/?utm_source=coding-chicken&utm_medium=addon&utm_campaign=jetengine-importer" target="_blank">Pro</a> or <a href="https://wordpress.org/plugins/wp-all-import/" target="_blank">Free</a>, and the <a href="https://crocoblock.com/plugins/jetengine/?utm_source=coding-chicken&utm_medium=addon&utm_campaign=jetengine-importer" target="_blank">Crocoblock JetEngine plugin.</a>';
		    $this->notices = [$notice_message];

	        add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
            

		    return false;
	    }

        $this->load_plugin_textdomain();

	    // only run in admin, wp_cli, Scheduling, or cron
	    if( is_admin() || php_sapi_name() === 'cli' || isset($_GET['import_key'])) {
            // set reimport options
            //pmgi_reimport filter
		    //$this->constants();
		    $this->includes();

		    // Check for any other notices and display them if needed.
		    $special_notice = apply_filters('ifcj_special_notice', '');

		    if( !empty($special_notice) ){
			    $this->notices = [$special_notice];

			    add_action( 'admin_notices', array($this, 'display_admin_message'));
		    }
            
		    //add_action( 'admin_enqueue_scripts', [ $this, 'cc_importer_jetengine_admin_scripts' ] );

		    $fields = new FieldFactory\Builder();

		    $fields->render();

		    self::$add_on->set_import_function( [ $this, 'import' ] );

			// Run if the JetEngine plugin is installed and active.
		    self::$add_on->run();

	    }

        return true;

    }

	/**
	 * Import function.
	 *
	 * @param $post_id
	 * @param $data
	 * @param $import_options
	 * @param $article
	 */
	public function import( $post_id, $data, $import_options, $article ) {

		$importer = new Importer( self::$add_on );
		$importer->import($post_id, $data, $import_options, $article);

	}

	public function includes(){
		// Register action handlers.
		if ( is_dir( self::ROOT_DIR . '/actions' ) ) {
			foreach ( Helpers\General::safe_glob( self::ROOT_DIR . '/actions/*.php', Helpers\General::GLOB_RECURSE | Helpers\General::GLOB_PATH ) as $filePath ) {
				require_once $filePath;
				$function = $actionName = basename( $filePath, '.php' );
				if ( preg_match( '%^(.+?)[_-](\d+)$%', $actionName, $m ) ) {
					$actionName = $m[1];
					$priority   = intval( $m[2] );
				} else {
					$priority = 10;
				}
				add_action( $actionName, self::PREFIX . str_replace( '-', '_', $function ), $priority, 99 ); // since we don't know at this point how many parameters each plugin expects, we make sure they will be provided with all of them (it's unlikely any developer will specify more than 99 parameters in a function)
			}
		}
		// Register filter handlers.
		if ( is_dir( self::ROOT_DIR . '/filters' ) ) {
			foreach ( Helpers\General::safe_glob( self::ROOT_DIR . '/filters/*.php', Helpers\General::GLOB_RECURSE | Helpers\General::GLOB_PATH ) as $filePath ) {
				require_once $filePath;
				$function = $actionName = basename( $filePath, '.php' );
				if ( preg_match( '%^(.+?)[_-](\d+)$%', $actionName, $m ) ) {
					$actionName = $m[1];
					$priority   = intval( $m[2] );
				} else {
					$priority = 10;
				}

				add_filter( $actionName, self::PREFIX . str_replace( '-', '_', $function ), $priority, 99 ); // since we don't know at this point how many parameters each plugin expects, we make sure they will be provided with all of them (it's unlikely any developer will specify more than 99 parameters in a function)


			}
		}
	}

    /**
     * Load Localisation files.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present
     *
     * @access public
     * @return void
     */
    public function load_plugin_textdomain() {
        $locale = apply_filters( 'plugin_locale', get_locale(), 'utility_pack_for_wpae' );
        load_plugin_textdomain( 'utility_pack_for_wpae', false, dirname( plugin_basename( __FILE__ ) ) . "/i18n/languages" );
    }

    public function adminInit() {

    }

    public function replace_callback($matches){
        return strtoupper($matches[0]);
    }

    /**
     * Plugin activation logic
     */
    public function activation() {
        // Uncaught exception doesn't prevent plugin from being activated, therefore replace it with fatal error so it does.
        set_exception_handler(function($e){trigger_error($e->getMessage(), E_USER_ERROR);});
    }

	public function display_admin_notice(){
		foreach($this->notices as $notice_text) {


			if ( ! get_option( sanitize_key( self::$slug ) . '_notice_ignore' ) ) {

				?>

				<div class="error notice is-dismissible wpallimport-dismissible" style="margin-top: 10px;"
			         rel="<?php echo esc_attr(sanitize_key( self::$slug )); ?>">
					<p><?php _e(
						sprintf(
							$notice_text,
							'?' . self::$slug . '_ignore=0'
						),
						'rapid_addon_' . self::$slug
					); ?></p>
				</div>

				<?php

			}
		}
	}

	public function display_admin_message(){
		foreach($this->notices as $notice_text) {

			$unique_notice_id = sanitize_key(md5(self::$slug . $notice_text));


			if ( ! get_option( $unique_notice_id . '_notice_ignore' ) ) {

				?>

                <div class="notice-info notice is-dismissible wpallimport-dismissible" style="margin-top: 10px;"
                     rel="<?php echo esc_attr($unique_notice_id); ?>">
                    <p><?php _e(
							sprintf(
								$notice_text,
								'?' . $unique_notice_id . '_ignore=0'
							),
							'rapid_addon_' . self::$slug
						); ?></p>
                </div>

				<?php

			}
		}
	}

	/**
	 * @param $hook
	 */
	public function cc_importer_jetengine_admin_scripts($hook ) {

		if( isset($_GET['page']) && in_array($_GET['page'], ['pmxi-admin-manage', 'pmxi-admin-import'])) {
			//wp_enqueue_script('codingchicken_jetengine_importer_script', plugin_dir_url(__FILE__) . 'static/js/fields.js', array('jquery'), self::$version);

		}

	}

}

IMPORTER_JETENGINE_Plugin::getInstance();
