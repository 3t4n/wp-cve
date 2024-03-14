<?php


/**
 * Pagination Styles module
 *
 * @category Pagination_Styles
 * @package Page_Links
 */
global $sh_pagstyles;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

define('SH_PAGSTYLES_VER', '2.4');


/**
 * Includes
 */
include_once 'pagination-styles-options.php';
include_once 'pagination-styles-functions.php';

add_action('init', array('SH_PageLinks_PagStyles_Bootstrap', 'init'));


/**
 * Pagination Styles Bootstrap
 *
 * @category Pagination_Styles
 * @package Page_Links
 */
class SH_PageLinks_PagStyles_Bootstrap
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
        'before'             => '<p>Pages:',
        'after'              => '</p>',
        'link_before'        => '',
        'link_after'         => '',
		'echo-tag'           => 1,
		'seperator' 		 => '|',
        'wrapper_tag'        => 'div',
        'wrapper_id'         => 'post-pagination',
        'wrapper_class'      => '',
        'link_wrapper'       => 'span',
        'link_wrapper_class' => '',
        'pagelink'           => '%',
        'archive_pages'      => 0,
		
    );

    /**
     * Initialization method.
     *
     * @return void
     */
    public static function init()
    {
        global $sh_pagstyles_options, $sh_pagstyles_functions;

		$sh_pagstyles_options   = new SH_PagStyles_Options();
		$sh_pagstyles_functions = new SH_PagStyles_Functions();

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

        if (empty($options['pagination_styles'])) {
            $options['pagination_styles'] = self::get_default_options();
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

