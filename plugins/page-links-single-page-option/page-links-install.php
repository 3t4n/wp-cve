<?php
/**
 * Page Links
 *
 * @category Page_Links_Install
 * @package Page_Links
 */

/**
 * Page Links installation
 *
 * @category Page_Links_Install
 * @package Page_Link
 */
class SH_PageLinks_Install
{
    /**
     * Creates default array of options for initial install
     *
     * @var array
     */
    protected static $default_options = array(
        'single_view' => array(
			'enabled_posts' => 'a:2:{i:0;s:4:"post";i:1;s:4:"page";}',
        )
    );

    /**
     * Current installed version. Used to differentiate from
     * version being installed
     *
     * @var string
     */
    protected static $version;

    /**
     * Method for running activation routine.
     *
     * @return void
     */
    public static function do_activate()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Check for capability
        if ( !current_user_can('activate_plugins') )
            wp_die( __('Sorry, you do not have suffient permissions to activate this plugin.', SH_PAGE_LINKS_DOMAIN) );

        self::$version = strval( get_option('sh_page_links_ver') );

        // version_compare will still evaluate against an empty string
        // so we have to tell it not to.
        if (version_compare(self::$version, SH_PAGE_LINKS_VER, '<') && !(self::$version == '')) {

            add_option( 'sh_page_links_upgrade', 'yes', '', 'no');
			update_option( 'sh_page_links_options', serialize( self::$default_options ), '', 'no' );

        } elseif (self::$version == '') {

            add_option( 'sh_page_links_ver', SH_PAGE_LINKS_VER, '', 'no');
            add_option( 'sh_page_links_options', serialize( self::$default_options ), '', 'no' );

        }

        flush_rewrite_rules();
    }

    /**
     * Static function for plugin deactivation.
     *
     * @return void
     */
    public static function do_deactivate()
    {	 
        flush_rewrite_rules();
		
		// Action to deactivate optional modules
		add_action('update_option_active_plugins', array('SH_PageLinks_Install','deactivate_dependents'));
   }

    /**
     * Static function for dependants deactivation.
     *
     * @return void
     */
	public static function deactivate_dependents(){
		$dependent = array(
			'auto-pagination/auto-pagination.php',
			'scrolling-pagination/scrolling-pagination.php',
                        'pagination-styles/pagination-styles.php'
		);
		deactivate_plugins($dependent);
	}
    

    /**
     * Returns default options
     *
     * @return array
     */
    public static function get_default_options()
    {
        return apply_filters('sh_page_links_add_default_options', self::$default_options);
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