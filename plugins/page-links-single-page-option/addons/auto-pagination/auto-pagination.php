<?php

/**
 * Auto Pagination module
 *
 * @category Auto_Pagination
 * @package Page_Links
 */



global $sh_autopag;
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define('SH_AUTOPAGE_VER', '2.4');


/**
 * Includes
 */
include_once 'auto-pagination-options.php';
include_once 'auto-pagination-functions.php';
add_action('init', array('SH_PageLinks_AutoPag_Bootstrap', 'init'));



/**
 * Auto Pagination Plugin Bootstrap
 *
 * @category Auto_Pagination
 * @package Page_Links
 */
class SH_PageLinks_AutoPag_Bootstrap {



    /**
     * Options array
     *
     * @var array
     */
    protected static $options;



    /**
     * Plugin version
     *
     * @var string
     */
    protected static $version;



    /**
     * Default options for plugin installation
     *
     * @var array
    */
    protected static $default_options = array(
        'paragraph_count' => '5',
        'inline_nextpage' => '0',
    );



    /**
     * Initialization method.
     *
     * @return void
    */
    public static function init() {

        global $sh_autopag_options, $sh_autopag_functions;
        
        $sh_autopag_options = new SH_AutoPag_Options();
        $sh_autopag_functions = new SH_AutoPag_Functions();

    }



    /**
     * Set plugin options. This method should run every time
     * plugin options are updated.
     *
     * @return void
     */
    public static function set_options() {
        $options = get_option('sh_page_links_options');
		
        if (empty($options['auto_pagination'])) {
            $options['auto_pagination'] = self::get_default_options();
        }		
		
        self::$options = $options;
    }



    /**
     * Get plugin options
     *
     * @return array
    */
    public function get_options() {
	
        return self::$options;
    }



    /**
     * Returns plugin's default options. Used on activation
     *
     * @return array
     */
    public static function get_default_options() {
        return self::$default_options;
    }



    /**
     * Adds to, and returns new default options array
     *
     * @param array $new_options
     * @return array
     */
    public static function add_to_default_options($new_options = array()) {
        return array_merge((array) $new_options, self::$default_options);
    }

}