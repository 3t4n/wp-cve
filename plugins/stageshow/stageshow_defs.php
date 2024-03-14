<?php

if (defined('STAGESHOW_DEFS_PHP'))
{
	return;
}
define('STAGESHOW_DEFS_PHP', true);
	
if (defined('STAGESHOW_FOLDER')) 
{
	if (STAGESHOW_FOLDER != basename(dirname(__FILE__)))
	{
		echo "ERROR Activating ".basename(dirname(__FILE__))."<br>\n";
		echo "Deactivate ".STAGESHOW_FOLDER." First<br>\n";
		die;
	}
	return;
}

define('STAGESHOW_FILE_PATH', dirname(__FILE__).'/');

/*
------------------------------------------------------------------------
	This section contains definitions that are usually set by
	Wordpress, but are set here when included by JQuery callbacks.
------------------------------------------------------------------------
*/
if (is_ssl())
	define('STAGESHOW_URLROOT', 'https');
else
	define('STAGESHOW_URLROOT', 'http');			

define('STAGESHOW_FOLDER', basename(STAGESHOW_FILE_PATH));
if (!defined('STAGESHOW_URL')) 
{
	define('STAGESHOW_FOLDER_URL', STAGESHOW_URLROOT.substr(WP_PLUGIN_URL, strpos(WP_PLUGIN_URL, ':')).'/'. STAGESHOW_FOLDER);
	define('STAGESHOW_URL', STAGESHOW_FOLDER_URL.'/');
}
define('STAGESHOW_UPLOADS_URL', STAGESHOW_URLROOT.substr(WP_CONTENT_URL, strpos(WP_CONTENT_URL, ':')).'/uploads/' . STAGESHOW_FOLDER .'/');
define('STAGESHOW_ADMIN_URL', STAGESHOW_URL . 'admin/');
define('STAGESHOW_ADMIN_IMAGES_URL', STAGESHOW_ADMIN_URL . 'images/');
define('STAGESHOW_IMAGES_URL', STAGESHOW_URL . 'images/');
if (!defined('STAGESHOW_UPLOADS_PATH'))
{
	define('STAGESHOW_UPLOADS_PATH', WP_CONTENT_DIR.'/uploads/'.STAGESHOW_FOLDER);				
}

define('STAGESHOWLIB_UPLOADS_PATH', STAGESHOW_UPLOADS_PATH);
define('STAGESHOWLIB_UPLOADS_URL', STAGESHOW_UPLOADS_URL);

if (defined('STAGESHOW_DATETIME_BOXOFFICE_FORMAT'))
{
	define('STAGESHOWLIB_DATETIME_IN_WP_CONFIG', true);
}

$stageshowSiteOptionsPath = STAGESHOW_UPLOADS_PATH.'/stageshow-wp-config.php';
if (file_exists($stageshowSiteOptionsPath))
{
	include $stageshowSiteOptionsPath;
}
else
{
	// Create an empty file
	@mkdir(STAGESHOW_UPLOADS_PATH, 0755, true);
	file_put_contents($stageshowSiteOptionsPath, "<?php\n// Add defines after this line\n\n?>\n");
}

define('STAGESHOWLIB_URL', STAGESHOW_URL);
define('STAGESHOWLIB_ADMIN_URL', STAGESHOW_ADMIN_URL);
define('STAGESHOWLIB_CALLBACK_BASENAME', 'stageshow_callback');
define('STAGESHOWLIB_CALLBACKROOT_URL', WP_PLUGIN_URL.'/'.STAGESHOWLIB_CALLBACK_BASENAME.'/');

if (!defined('STAGESHOW_STYLESHEET_URL'))
	define('STAGESHOW_STYLESHEET_URL', STAGESHOW_URL.'css/stageshow.css');

define('STAGESHOW_DIR_NAME', basename(STAGESHOW_FILE_PATH));
if (!defined('STAGESHOW_INCLUDE_PATH'))
{
	define('STAGESHOW_ADMIN_PATH', STAGESHOW_FILE_PATH . 'admin/');
	define('STAGESHOW_INCLUDE_PATH', STAGESHOW_FILE_PATH . 'include/');
	define('STAGESHOW_ADMINICON_PATH', STAGESHOW_ADMIN_PATH . 'images/');
	define('STAGESHOW_TEST_PATH', STAGESHOW_FILE_PATH . 'test/');
	define('STAGESHOWLIB_FILE_PATH', STAGESHOW_FILE_PATH);
	define('STAGESHOWLIB_INCLUDE_PATH', STAGESHOW_INCLUDE_PATH);
}

define('STAGESHOWLIB_DEFAULT_TEMPLATES_PATH', STAGESHOW_FILE_PATH . 'templates/');
define('STAGESHOW_LANG_RELPATH', STAGESHOW_FOLDER . '/lang/');
define('STAGESHOWLIB_DOMAIN', 'stageshow');

if (!defined('STAGESHOW_SHORTCODE_PREFIX'))
	define('STAGESHOW_SHORTCODE_PREFIX', 'sshow');

define('STAGESHOW_DEFAULT_SETUPUSER', 'administrator');

define('STAGESHOWLIB_CAPABILITY_RESERVEUSER', 'StageShow_Reservations');	// A user that can reserve seats without paying online
define('STAGESHOWLIB_CAPABILITY_VALIDATEUSER', 'StageShow_Validate');		// A user that can view and validate sales
define('STAGESHOWLIB_CAPABILITY_SALESUSER', 'StageShow_Sales');			// A user that can view and edit sales
define('STAGESHOWLIB_CAPABILITY_VIEWSALESUSER', 'StageShow_ViewSales');	// A user that can view sales
define('STAGESHOWLIB_CAPABILITY_ADMINUSER', 'StageShow_Admin');			// A user that can edit shows, performances
define('STAGESHOWLIB_CAPABILITY_SETUPUSER', 'StageShow_Setup');			// A user that can edit stageshow settings
define('STAGESHOWLIB_CAPABILITY_VIEWSETTINGS', 'StageShow_ViewSettings');	// A user that can view stageshow settings
define('STAGESHOWLIB_CAPABILITY_DEVUSER', 'StageShow_Testing');			// A user that can use test pages

if (!defined('STAGESHOW_CODE_PREFIX'))
	define('STAGESHOW_CODE_PREFIX', 'stageshow');


define('STAGESHOW_MENUPAGE_ADMINMENU', STAGESHOW_CODE_PREFIX.'_adminmenu');
define('STAGESHOW_MENUPAGE_OVERVIEW', STAGESHOW_CODE_PREFIX.'_overview');
define('STAGESHOW_MENUPAGE_SHOWS', STAGESHOW_CODE_PREFIX.'_shows');
define('STAGESHOW_MENUPAGE_PERFORMANCES', STAGESHOW_CODE_PREFIX.'_performances');
define('STAGESHOW_MENUPAGE_PRICES', STAGESHOW_CODE_PREFIX.'_prices');
define('STAGESHOW_MENUPAGE_PRICEPLANS', STAGESHOW_CODE_PREFIX.'_priceplans');
define('STAGESHOW_MENUPAGE_SALES', STAGESHOW_CODE_PREFIX.'_sales');
define('STAGESHOW_MENUPAGE_SETTINGS', STAGESHOW_CODE_PREFIX.'_settings');
define('STAGESHOW_MENUPAGE_TOOLS', STAGESHOW_CODE_PREFIX.'_tools');
define('STAGESHOW_MENUPAGE_DEVTEST', STAGESHOW_CODE_PREFIX.'_devtest');
define('STAGESHOW_MENUPAGE_DIAGNOSTICS', STAGESHOW_CODE_PREFIX.'_diagnostics');
define('STAGESHOW_MENUPAGE_TESTSETTINGS', STAGESHOW_CODE_PREFIX.'_testsettings');

define('STAGESHOW_FILEPATH_TEXTLEN',255);
define('STAGESHOW_FILEPATH_EDITLEN', 95);

define('STAGESHOW_URL_TEXTLEN',110);
	
define('STAGESHOW_PRICE_UNKNOWN',-100);

define('STAGESHOWLIB_UPDATETROLLEY_TARGET', 'stageshow_jquery_trolley.php');
define('STAGESHOWLIB_SENDEMAIL_TARGET', 'stageshow_jquery_email.php');
define('STAGESHOW_EXPORT_TARGET', 'stageshow_export.php');
define('STAGESHOW_DBEXPORT_TARGET', 'stageshow_db_export.php');

define('STAGESHOW_SALEVALIDATE_TARGET', 'stageshow_jquery_validate.php');
define('STAGESHOW_TICKETPRINT_TARGET', 'stageshow_jquery_print.php');
define('STAGESHOW_SAMPLES_TARGET', 'stageshow_show_sample.php');

define('STAGESHOWLIB_VIEWEMAIL_TARGET', 'stageshow_showemail.php');
	
/*
------------------------------------------------------------------------
	This section contains definitions that have default values
	set here, but which can have site specific values defined 
	by an entry in the wp-config.php file which will then 
	replace this default value.
------------------------------------------------------------------------
*/
if (!defined('STAGESHOW_MAXTICKETCOUNT'))
	define('STAGESHOW_MAXTICKETCOUNT', 4);	// Default value for "Max Ticket Qty" in settings

if (!defined('STAGESHOW_MAX_TICKETSEATS'))
	define('STAGESHOW_MAX_TICKETSEATS', 8);	// Maximum number of tickets in drop down quantity selector (Prices and Price Plans pages)

if (!defined('STAGESHOW_BOXOFFICE_SORTFIELD'))
	define('STAGESHOW_BOXOFFICE_SORTFIELD', 'priceType');	// The database field used to sort entries in the Ticket List

if (!defined('STAGESHOWLIB_DATETIME_ADMIN_FORMAT'))
	define('STAGESHOWLIB_DATETIME_ADMIN_FORMAT', 'Y-m-d H:i');

if (!defined('STAGESHOWLIB_LOADING_URL'))
	define('STAGESHOWLIB_LOADING_URL', STAGESHOW_IMAGES_URL.'loading-segments.gif');

if (!defined('STAGESHOWLIB_ADMIN_EXPIRETIMEOFFSET'))
	define('STAGESHOWLIB_ADMIN_EXPIRETIMEOFFSET', 86400);		// Expire Time Offset when logged in .. 24 hours */

define('STAGESHOW_EMPTYBOXOFFICEMSG_DEFAULT', __('Sales Not Available Currently', 'stageshow'));

define('STAGESHOWLIB_PLUGIN_ID', 'StageShow');

$pluginID = STAGESHOWLIB_PLUGIN_ID;
