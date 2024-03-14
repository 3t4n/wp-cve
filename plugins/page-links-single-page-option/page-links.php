<?php
/**
 * Page Links Plus
 *
 * @category Page_Links
 * @package Page_Links
 */


/*
Plugin Name: Page-Links Plus
Plugin URI: http://pagelinksplus.com
Description: WordPress pagination plugin. Paginate content easily & efficiently.
Version: 2.5.3
Author: Studio Hyperset, Inc.
Author URI: http://studiohyperset.com
License: GPL3
*/


define('SH_PAGE_LINKS_URL', plugin_dir_url(__FILE__));
define('SH_PAGE_LINKS_PATH', plugin_dir_path(__FILE__));
if (!defined('SH_PAGE_LINKS_DOMAIN')){
    define('SH_PAGE_LINKS_DOMAIN', basename(dirname(__FILE__)));
}
define('SH_PAGE_LINKS_VER', '2.5.1');


/**
 * @global SH_PageLinks_Bootstrap $sh_page_links
 * @global SH_PageLinks_Options $sh_page_links_options
 */
global $sh_page_links, $sh_page_links_options;
 	// Add settings link on plugin page
function single_page_styles($links) { 
  $settings_link = '<a href="options-general.php?page=sh-page-links-options">'. __('Settings', SH_PAGE_LINKS_DOMAIN) .'</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'single_page_styles' );


function sh_wp_link_page($i, $class = '', $attr = '') {

    return str_replace('href=', $attr . ' class="'. $class .'" href=', _wp_link_page($i) );
    
}


include_once 'page-links-install.php';
include_once 'page-links-options.php';
include_once 'single-view/single-view.php';
include_once 'single-view/single-view-options.php';


add_action('init', array('SH_PageLinks_Bootstrap', 'init'));
register_activation_hook(__FILE__, array('SH_PageLinks_Install', 'do_activate'));
register_deactivation_hook( __FILE__, array('SH_PageLinks_Install', 'do_deactivate'));
/**
 * Plugin bootstrap class_alias
 *
 * @category Page_Links
 * @package Page_Links_Bootstrap
 */
class SH_PageLinks_Bootstrap {
    /**
     * Options array
     *
     * @var array
     */
    protected static $options;

    /**
     * Plugin initialization. We use a static function to avoid using
     * create_function() in the hook.
     *
     * @return void;
     */
    public static function init()
    {
        global $sh_page_links, $sh_singleview_options;
        
        load_plugin_textdomain('page-links-single-page-option', false, dirname(plugin_basename(__FILE__)) . '/languages/');

        self::set_options();
        $sh_page_links = new SH_PageLinks_Bootstrap();
    }



    /**
     * PHP5 Constructor function
     *
     * @return void
     */
    public function __construct()
    {
        global $sh_single_view;
        wp_register_style(
            'jquery-ui-smoothness',
            SH_PAGE_LINKS_URL . '/css/ui-smoothness.css',
            null,
            SH_PAGE_LINKS_VER,
            'screen'
        );
        
        wp_register_style( 'plp-global', SH_PAGE_LINKS_URL . '/css/global.css', null, SH_PAGE_LINKS_VER, 'screen' );
		
		add_filter('transient_update_plugins', array($this, 'checkForUpdate'));
		add_filter('site_transient_update_plugins', array($this, 'checkForUpdate'));
        $sh_page_links_options = new SH_PageLinks_Options();
        $sh_single_view        = new SH_PageLinks_SingleView();
    }



    /**
     * Set plugin options. This method should run every time
     * plugin options are updated.
     *
     * @return void
     */
    public static function set_options()
    {
        $options = maybe_unserialize(get_option('sh_page_links_options'));
        if (empty($options)) {
            $options = SH_PageLinks_Install::get_default_options();
        }
        self::$options = $options;
    }

    /**
     * Get plugin options
     *
     * @return array
     */
    public function get_options()
    {
        return self::$options;
    }
	
	/**
	 * Check for Updates
	 *
	 */	
	public function checkForUpdate($option) {
		
		
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $gitUriValue = 'https://github.com/studiohyperset/page-links-single-page-option/';
		
		$url = 'https://api.github.com/repos/studiohyperset/page-links-single-page-option/tags';
		
        $response = get_transient(md5($url)); // Note: WP transients fail if key is long than 45 characters
        
		if(empty($response)){
            $raw_response = wp_remote_get($url, array('sslverify' => false, 'timeout' => 10));
			if ( is_wp_error( $raw_response ) ){
				return;
            }

            //Error. Probably Exceed Limit
            $response = json_decode($raw_response['body']);
            if (isset($response->message)) {
                unset($option->response[$plugin]);
                return $option;
            }
            $response = $response[0];
			
			//set cache
			set_transient(md5($url), $response, 6000);
		}
		
        // check and generate download link
        $data = get_plugin_data( __FILE__ );
        $plugin = plugin_basename(__FILE__);
		if(version_compare($data['Version'],  $response->name, '>=')){
			// up-to-date!  
			unset($option->response[$plugin]);
		} else {
            $option->response[$plugin] = (object)array(
                'url' => $gitUriValue,
                'slug' => 'page-links-single-page-option',
                'package' => $response->zipball_url,
                'new_version' => $response->name,
                'id' => "0"
            );
        }

        return $option;
	}
}


/*
 * Modules All In One
 */
include_once 'addons/auto-pagination/auto-pagination.php';
include_once 'addons/pagination-styles/pagination-styles.php';
include_once 'addons/scrolling-pagination/scrolling-pagination.php';
