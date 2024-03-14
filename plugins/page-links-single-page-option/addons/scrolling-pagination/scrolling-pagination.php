<?php
/**
 *
 * @category ScrollingPag
 * @package Page_Links
 */

global $sh_autopag;
define('SH_SCROLLINGPAG_VER', '2.4');


/**
 * Includes
 */
include_once 'scrolling-pagination-options.php';
include_once 'scrolling-pagination-functions.php';
add_action('init', array('SH_PageLinks_ScrollingPagination_Bootstrap', 'init'));


/**
 * Scrolling Pagination Setup
 *
 * @category ScrollingPagSetup
 * @package ScrollingPag
 */
class SH_PageLinks_ScrollingPagination_Bootstrap
{
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
        'pages_to_scroll'  => '3',
        'nextpagelink'     => 'Next &rarr;',
        'nextpageclass'     => '',
        'previouspagelink' => '&larr; Previous',
        'previouspageclass' => '&larr; Previous',
		'firstpage'        => '',
		'firstpageclass'        => '',
		'lastpage'         => '',
		'lastpageclass'         => '',
		'elipsis'          => '',
	);
    /**
     * Initialization function
     *
     *  @return void
     */
    public static function init()
    {
        global $sh_scrolling_options, $sh_scrolling_functions, $auto_pag_active, $main_active;
		
        $sh_scrolling_options   = new SH_ScrollingPagination_Options();
		$sh_scrolling_functions = new SH_ScrollingPagination_Functions();
		
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
        if (empty($options['scrolling_pagination'])) {
            $options['scrolling_pagination'] = self::get_default_options();
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
     * Returns plugin's default options. Used on activation
     *
     * @return array
     */
    public static function get_default_options()
    {
        return self::$default_options;
    }
    /**
     * Adds to, and returns new default options array
     *
     * @param array $new_options
     * @return array
     */
    public static function add_to_default_options($new_options = array())
    {
        return array_merge((array)$new_options, self::$default_options);
    }
	
}
