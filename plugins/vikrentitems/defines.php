<?php
/** 
 * @package   	VikRentItems
 * @subpackage 	core
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2018 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

// Software version
define('VIKRENTITEMS_SOFTWARE_VERSION', '1.1.5');

// Base path
define('VIKRENTITEMS_BASE', dirname(__FILE__));

// Libraries path
define('VIKRENTITEMS_LIBRARIES', VIKRENTITEMS_BASE . DIRECTORY_SEPARATOR . 'libraries');

// Languages path
define('VIKRENTITEMS_LANG', basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'languages');
/**
 * The admin and site languages are no more used by the plugin.
 *
 * @deprecated 1.0.0
 */
define('VIKRENTITEMS_SITE_LANG', basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'language');
define('VIKRENTITEMS_ADMIN_LANG', basename(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'language');

// Assets URI
define('VIKRENTITEMS_SITE_ASSETS_URI', plugin_dir_url(__FILE__) . 'site/resources/');
define('VIKRENTITEMS_ADMIN_ASSETS_URI', plugin_dir_url(__FILE__) . 'admin/resources/');

// Debug flag
define('VIKRENTITEMS_DEBUG', false);

// URI Constants for admin and site sections (with trailing slash)
defined('VRI_ADMIN_URI') or define('VRI_ADMIN_URI', plugin_dir_url(__FILE__).'admin/');
defined('VRI_SITE_URI') or define('VRI_SITE_URI', plugin_dir_url(__FILE__).'site/');
defined('VRI_BASE_URI') or define('VRI_BASE_URI', plugin_dir_url(__FILE__));
defined('VRI_MODULES_URI') or define('VRI_MODULES_URI', plugin_dir_url(__FILE__));
defined('VRI_ADMIN_URI_REL') or define('VRI_ADMIN_URI_REL', plugin_dir_url(__FILE__).'admin/');
defined('VRI_SITE_URI_REL') or define('VRI_SITE_URI_REL', plugin_dir_url(__FILE__).'site/');

// Path Constants for admin and site sections (with NO trailing directory separator)
defined('VRI_ADMIN_PATH') or define('VRI_ADMIN_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'admin');
defined('VRI_SITE_PATH') or define('VRI_SITE_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'site');

// Other Constants that may not be available in the framework
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * Site pre-process flag.
 * When this flag is enabled, the plugin will try to dispatch the
 * site controller within the "init" action. This is made by 
 * fetching the shortcode assigned to the current URI.
 *
 * By disabling this flag, the site controller will be dispatched 
 * with the headers already sent.
 */
define('VIKRENTITEMS_SITE_PREPROCESS', true);
