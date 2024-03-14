<?php
/*
Plugin Name: CM Footnotes
Plugin URI:
Description: FREE Version! Adds the footnote with the definitions for post/page and custom post types.
Version: 2.1.2
Author: CreativeMindsSolutions
Author URI: https://www.cminds.com/
*/

if( !ini_get('max_execution_time') || ini_get('max_execution_time') < 300 )
{
    /*
     * Setup the high max_execution_time to avoid timeouts during lenghty operations like importing big glossaries,
     * or rebuilding related articles index
     */
    ini_set('max_execution_time', 300);
    set_time_limit(300);
}

/**
 * Define Plugin Version
 *
 * @since 1.0
 */
if( !defined('CMF_VERSION') )
{
    define('CMF_VERSION', '2.1.2');
}

/**
 * Define Plugin name
 *
 * @since 1.0
 */
if( !defined('CMF_NAME') )
{
    define('CMF_NAME', 'CM Footnotes');
}

/**
 * Define Plugin canonical name
 *
 * @since 1.0
 */
if( !defined('CMF_CANONICAL_NAME') )
{
    define('CMF_CANONICAL_NAME', 'CM Footnotes');
}

/**
 * Define Plugin license name
 *
 * @since 1.0
 */
if( !defined('CMF_LICENSE_NAME') )
{
    define('CMF_LICENSE_NAME', 'CM Footnotes');
}

/**
 * Define Plugin File Name
 *
 * @since 1.0
 */
if( !defined('CMF_PLUGIN_FILE') )
{
    define('CMF_PLUGIN_FILE', __FILE__);
}

/**
 * Define Package
 *
 * @since 1.0
 */
if( !defined('CMF_PACKAGE') )
{
    define('CMF_PACKAGE', 'cminds-free.php');
}

/**
 * Define Plugin release notes url
 *
 * @since 1.0
 */
if( !defined('CMF_RELEASE_NOTES') )
{
    define('CMF_RELEASE_NOTES', 'https://footnote.cminds.com/release-notes/');
}

include_once plugin_dir_path(__FILE__) . "CMF_Free.php";
register_activation_hook(__FILE__, array('CMF_Free', '_install'));

CMF_Free::init();