<?php
/**
 *  Plugin Name:    Custom Scrollbar
 *  Plugin URI:     http://en.michaeluno.jp/custom-scrollbar
 *  Description:    Adds a custom scrollbar to specified HTML elements.
 *  Author:         Michael Uno (miunosoft)
 *  Author URI:     http://michaeluno.jp
 *  Version:        1.3.8
 */

/**
 * Provides the basic information about the plugin.
 * 
 * @since       1       
 */
class CustomScrollbar_Registry_Base {
 
	const VERSION        = '1.3.8';    // <--- DON'T FORGET TO CHANGE THIS AS WELL!!
	const NAME           = 'Custom Scrollbar';
	const DESCRIPTION    = 'Adds a custom scrollbar to specified HTML elements.';
	const URI            = 'http://en.michaeluno.jp/custom-scrollbar';
	const AUTHOR         = 'miunosoft (Michael Uno)';
	const AUTHOR_URI     = 'http://en.michaeluno.jp/';
	const PLUGIN_URI     = 'http://en.michaeluno.jp/custom-scrollbar';
	const COPYRIGHT      = 'Copyright (c) 2015, Michael Uno';
	const LICENSE        = 'GPL v2 or later';
	const CONTRIBUTORS   = '';
 
}

// Do not load if accessed directly
if ( ! defined( 'ABSPATH' ) ) { 
    return; 
}

/**
 * Provides the common data shared among plugin files.
 * 
 * To use the class, first call the setUp() method, which sets up the necessary properties.
 * 
 * @package     Custom Scrollbar
 * @since       1
*/
final class CustomScrollbar_Registry extends CustomScrollbar_Registry_Base {
    
	const TEXT_DOMAIN               = 'custom-scrollbar';
	const TEXT_DOMAIN_PATH          = '/language';
    
    /**
     * The hook slug used for the prefix of action and filter hook names.
     * 
     * @remark      The ending underscore is not necessary.
     */    
	const HOOK_SLUG                 = 'csb';    // without trailing underscore
    
    /**
     * The transient prefix. 
     * 
     * @remark      This is also accessed from uninstall.php so do not remove.
     * @remark      Up to 8 characters as transient name allows 45 characters or less ( 40 for site transients ) so that md5 (32 characters) can be added
     */    
	const TRANSIENT_PREFIX          = 'CSB';

    const PRO_URI                   = 'http://en.michaeluno.jp/custom-scrollbar/custom-scrollbar-pro';

    /**
     * 
     * @since       1
     */
    static public $sFilePath;  
    
    /**
     * 
     * @since       1
     */    
    static public $sDirPath;    
    
    /**
     * Stores used option keys.
     * @since        1
     */
    static public $aOptionKeys = array(    
        'setting'           => 'custom_scrollbar', 
    );

    /**
     * Stores the plugin option structures and their keys.
     * ```
     * array(
     *      'option_key1' => array( 'option structure' ),
     *      'option_key2' => array( 'option structure' ),
     * );
     * ```
     * @since       1.3.0
     */
    static public $aOptions = array(
        'custom_scrollbar'  => array(

            // 1.2.0+
            // @deprecated  1.3.0
//            'load'     => array(
//                'ajax_initialization'   => false,
//            ),

            'delete'    => array(
                'delete_upon_uninstall'    => false,
            ),

            'css'       => array(
                'custom_css' => '',
            ),

            'scrollbars' => array(
                0   => array(
                    'status'    => true,    // or false
                    'name'      => '', // just a label for the user to remember
                    'selector'  => '',
                    'width'     => array(
                        'size'  => null,
                        'unit'  => null,
                    ),
                    'height'    => array(
                        'size'  => null,
                        'unit'  => null,
                    ),
                    'position'  => 'inside', // or outside

                    'inline_css'    => array(), // 1.1+

                    // @see http://manos.malihu.gr/repository/custom-scrollbar/demo/examples/scrollbar_themes_demo.html
                    'theme'     => 'light',

                    // custom colors
                    'mCSB_draggerContainer' => '',
                    'mCSB_dragger'          => '',
                    'mCSB_dragger_bar'      => '',
                    'mCSB_draggerRail'      => '',
                    'mCSB_scrollTools'      => '',

                    // 1.3.0+
                    'scrollButtons'             => array(
                        'enable'        => 0,
                        'scrollAmount'  => '',  // auto
                        'scrollType'    => 'stepless',
                    ),
                    'mouseWheel'                => array(
                        'enable'        => 1,
                        'scrollAmount'  => '',  // auto
                    ),
                    'keyboard'                  => array(
                        'enable'        => 1,
                        'scrollAmount'  => '',  // auto
                    ),
                    'initialize_on_ajax_load'   => false,
                    'responsive'                => array(
                        'enable'             => 0,
                        'screen_width_range' => array(
                            array(
                                1,      // min
                                '',     // max (empty - no limit)
                            )
                        ),
                    ),
                ),
            ),
        ),
    );

    /**
     * Used admin pages.
     * @since        1
     */
    static public $aAdminPages = array(
        // key => 'page slug'        
        'setting'           => 'csb_settings', 
    );
    
    /**
     * Used post types.
     */
    static public $aPostTypes = array(
    );
    
    /**
     * Used post types by meta boxes.
     */
    static public $aMetaBoxPostTypes = array(
    );
    
    /**
     * Used taxonomies.
     * @remark      
     */
    static public $aTaxonomies = array(
    );
    
    /**
     * Used shortcode slugs
     */
    static public $aShortcodes = array(
    );
    
    /**
     * Stores custom database table names.
     * @remark      slug (part of class file name) => table name
     * @since       1
     */
    static public $aDatabaseTables = array(
    );
    /**
     * Stores the database table versions.
     * @since       1
     */
    static public $aDatabaseTableVersions = array(
    );
    
    /**
     * Sets up class properties.
     * @param  string $sPluginFilePath
     */
	static function setUp( $sPluginFilePath ) {
        self::$sFilePath = $sPluginFilePath; 
        self::$sDirPath  = dirname( self::$sFilePath );  
	}	
	
    /**
     * @param  string $sRelativePath
     * @return string
     */
	public static function getPluginURL( $sRelativePath='' ) {
	    $sRelativePath = ltrim( $sRelativePath, '/\\' );
        if ( isset( self::$_sPluginURLCache ) ) {
            return self::$_sPluginURLCache . $sRelativePath;
        }
        self::$_sPluginURLCache = trailingslashit( plugins_url( '', self::$sFilePath ) );
        return self::$_sPluginURLCache . $sRelativePath;
	}
        /**
         * @since       1.1.6
         */
        static private $_sPluginURLCache;

    /**
     * Requirements.
     * @since           1
     */    
    static public $aRequirements = array(
        'php' => array(
            'version'   => '5.2.4',
            'error'     => 'The plugin requires the PHP version %1$s or higher.',
        ),
        'wordpress'         => array(
            'version'   => '3.4',
            'error'     => 'The plugin requires the WordPress version %1$s or higher.',
        ),
        // 'mysql'             => array(
            // 'version'   => '5.0.3', // uses VARCHAR(2083) 
            // 'error'     => 'The plugin requires the MySQL version %1$s or higher.',
        // ),
        'functions'     => '', // disabled
        // array(
            // e.g. 'mblang' => 'The plugin requires the mbstring extension.',
        // ),
        // 'classes'       => array(
            // 'DOMDocument' => 'The plugin requires the DOMXML extension.',
        // ),
        'constants'     => '', // disabled
        // array(
            // e.g. 'THEADDONFILE' => 'The plugin requires the ... addon to be installed.',
            // e.g. 'APSPATH' => 'The script cannot be loaded directly.',
        // ),
        'files'         => '', // disabled
        // array(
            // e.g. 'home/my_user_name/my_dir/scripts/my_scripts.php' => 'The required script could not be found.',
        // ),
    );        
	
}
CustomScrollbar_Registry::setUp( __FILE__ );


include( dirname( __FILE__ ).'/include/library/apf/admin-page-framework.php' );
include( dirname( __FILE__ ).'/include/CustomScrollbar_Bootstrap.php' );
new CustomScrollbar_Bootstrap(
    CustomScrollbar_Registry::$sFilePath,
    CustomScrollbar_Registry::HOOK_SLUG    // hook prefix    
);