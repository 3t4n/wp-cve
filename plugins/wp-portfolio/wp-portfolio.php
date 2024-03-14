<?php
/**
 * Plugin Name: WordPress Portfolio Plugin
 * Plugin URI: http://wordpress.org/extend/plugins/wp-portfolio/
 * Description: A plugin that allows you to show off your portfolio through a single page on your WordPress website with automatically generated thumbnails. To show your portfolio, create a new page and paste [wp-portfolio] into it. The plugin requires you to have a free account with <a href="https://shrinktheweb.com/">Shrink The Web</a> to generate the thumbnails.
 * Version: 1.43.2
 * Text Domain: wp-portfolio
 * Domain Path: /languages

 * This plugin is licensed under the Apache 2 License
 * http://www.apache.org/licenses/LICENSE-2.0
 */


// Admin Only
if (is_admin()) 
{
	include_once('wplib/utils_pagebuilder.inc.php');
	include_once('wplib/utils_formbuilder.inc.php');
	include_once('wplib/utils_tablebuilder.inc.php');
		
	include_once('lib/admin_only.inc.php');
}

// Common 
include_once('wplib/utils_sql.inc.php');

// Common
include_once('lib/thumbnailer.inc.php');
include_once('lib/widget.inc.php');
include_once('lib/utils.inc.php');

/* Load translation files */
add_action('init', 'wpp_load_textdomain');
function wpp_load_textdomain() {
	load_plugin_textdomain( 'wp-portfolio', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}


/** Constant: The current version of the database needed by this version of the plugin.  */
define('WPP_VERSION', 							'1.43.2');



/** Constant: The string used to determine when to render a group name. */
define('WPP_STR_GROUP_NAME', 					'%GROUP_NAME%');

/** Constant: The string used to determine when to render a group description. */
define('WPP_STR_GROUP_DESCRIPTION', 	 		'%GROUP_DESCRIPTION%');

/** Constant: The string used to determine when to render a website name. */
define('WPP_STR_WEBSITE_NAME', 	 				'%WEBSITE_NAME%');

/** Constant: The string used to determine when to render a website thumbnail image. */
define('WPP_STR_WEBSITE_THUMBNAIL', 	 		'%WEBSITE_THUMBNAIL%');

/** Constant: The string used to determine when to render a website thumbnail image URL. */
define('WPP_STR_WEBSITE_THUMBNAIL_URL', 	 	'%WEBSITE_THUMBNAIL_URL%');

/** Constant: The string used to determine when to render a website url. */
define('WPP_STR_WEBSITE_URL', 	 				'%WEBSITE_URL%');

/** Constant: The string used to determine when to render a website description. */
define('WPP_STR_WEBSITE_DESCRIPTION', 	 		'%WEBSITE_DESCRIPTION%');

/** Constant: The string used to determine when to render a custom field value. */
define('WPP_STR_WEBSITE_CUSTOM_FIELD', 	 		'%WEBSITE_CUSTOM_FIELD%');

/** Constant: The string used to determine when to render a website date added value. */
define('WPP_STR_WEBSITE_DATE', 	 				'%WEBSITE_DATE%');

/** Constant: Default HTML to render a group. */
define('WPP_DEFAULT_GROUP_TEMPLATE',
"<h2>%GROUP_NAME%</h2>
<p>%GROUP_DESCRIPTION%</p>");

/** Constant: Default HTML to render a website. */
define('WPP_DEFAULT_WEBSITE_TEMPLATE',
"<div class=\"portfolio-website\">
	<div class=\"portfolio-website-container\">
		<div class=\"website-thumbnail\">%WEBSITE_THUMBNAIL%</div>
		<div class=\"website-name\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_NAME%</a></div>
		<div class=\"website-url\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_URL%</a></div>
		<div class=\"website-description\">%WEBSITE_DESCRIPTION%</div>
		<div class=\"website-clear\"></div>
	</div>
</div>");

/** Constant: Default HTML to render a website in the widget area. */
define('WPP_DEFAULT_WIDGET_TEMPLATE',
"<div class=\"widget-portfolio\">
    <div class=\"widget-website-thumbnail\">
    	<a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_THUMBNAIL%</a>
    </div>
    <div class=\"widget-website-name\">
    	<a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_NAME%</a>
    </div>
    <div class=\"widget-website-description\">
    	%WEBSITE_DESCRIPTION%
    </div>
    <div class=\"widget-website-clear\"></div>
</div>");

/** Constant: Default HTML to render the paging for the websites. */
define('WPP_DEFAULT_PAGING_TEMPLATE',
'<div class="portfolio-paging">
	<div class="page-count">Showing page %PAGING_PAGE_CURRENT% of %PAGING_PAGE_TOTAL%</div>
	%LINK_PREVIOUS% %PAGE_NUMBERS% %LINK_NEXT%
</div>
');


define('WPP_DEFAULT_PAGING_TEMPLATE_PREVIOUS', 	__('Previous', 'wp-portfolio'));
define('WPP_DEFAULT_PAGING_TEMPLATE_NEXT', 		__('Next', 'wp-portfolio'));

/** Constant: Default CSS to style the portfolio. */
define('WPP_DEFAULT_CSS',
".portfolio-website {
	margin-bottom: 10px;
	box-sizing: border-box;
}
.portfolio-website-container {
	padding: 10px;
}
.website-thumbnail {
	float: left;
	margin: 0 20px 20px 0;
}
.website-thumbnail img {
	border: 1px solid #555;
	margin: 0;
	padding: 0;
}
.website-name {
	font-size: 12pt;
	font-weight: bold;
	margin-bottom: 3px;
}
.website-name a,.website-url a {
	text-decoration: none;
}
.website-name a:hover,.website-url a:hover {
	text-decoration: underline;
}
.website-url {
	font-size: 9pt;
	font-weight: bold;
}
.website-url a {
	color: #777;
}
.website-description {
	margin-top: 15px;
}
.website-clear {
	clear: both;
}");

/** Constant: Default CSS to style the paging feature. */
define('WPP_DEFAULT_CSS_PAGING',
".portfolio-paging {
	text-align: center;
	padding: 4px 10px 4px 10px;
	margin: 0 10px 20px 10px;
}
.portfolio-paging .page-count {
	margin-bottom: 5px;
}
.portfolio-paging .page-jump b {
	padding: 5px;
}
.portfolio-paging .page-jump a {
	text-decoration: none;
}");


/** Constant: Default CSS to style the widget feature. */
define('WPP_DEFAULT_CSS_WIDGET',
".wp-portfolio-widget-des {
	margin: 8px 0;
	font-size: 110%;
}
.widget-website {
	border: 1px solid #AAA;
	padding: 3px 10px;
	margin: 0 5px 10px;
}
.widget-website-name {
	font-size: 120%;
	font-weight: bold;
	margin-bottom: 5px;
}
.widget-website-description {
	line-height: 1.1em;
}
.widget-website-thumbnail {
	margin: 10px auto 6px auto;
	width: 102px;
}
.widget-website-thumbnail img {
	width: 100px;
	border: 1px solid #555;
	margin: 0;
	padding: 0;
}
.widget-website-clear {
	clear: both;
	height: 1px;
}");


/** Constant: The name of the table to store the website information. */
define('TABLE_WEBSITES', 						'WPPortfolio_websites');

/** Constant: The name of the table to store the custom site information. */
define('TABLE_WEBSITES_META', 						TABLE_WEBSITES.'_meta');

/** Constant: The name of the table to store the website information. */
define('TABLE_WEBSITE_GROUPS', 					'WPPortfolio_groups');

/** Constant: The name of the table to store the debug information. */
define('TABLE_WEBSITE_DEBUG', 					'WPPortfolio_debuglog');

/** Constant: The name of the table to store group-website relation. */
define('TABLE_GROUPS_WEBSITES',                 'WPPortfolio_groups_websites');

/** Constant: The name of the table to store custom fields. */
define('TABLE_CUSTOM_FIELDS',                 'WPPortfolio_custom_fields');

/** Contstant: The path to use to store the cached thumbnails. */
define('WPP_THUMBNAIL_PATH',					'wp-portfolio/cache');

/** Contstant: The name of the setting with the cache setting. */
define('WPP_CACHE_SETTING', 					'WPPortfolio_cache_location');

/** Contstant: The name of the setting with the thumbnail refresh time. */
define('WPP_STW_REFRESH_TIME', 					'WPPortfolio_thumbnail_refresh_time');


/** Contstant: The path to use to store the cached thumbnails. */
define('WPP_THUMB_DEFAULTS',					'wp-portfolio/imgs/thumbnail_');

/** Constant: URL location for settings page. */
define('WPP_SETTINGS', 							'admin.php?page=WPP_show_settings');

/** Constant: URL location for settings page. */
define('WPP_DOCUMENTATION', 					'admin.php?page=WPP_show_documentation');

/** Constant: URL location for website summary. */
define('WPP_WEBSITE_SUMMARY', 					'admin.php?page=wp-portfolio/wp-portfolio.php');

/** Constant: URL location for modifying a website entry. */
define('WPP_MODIFY_WEBSITE', 					'admin.php?page=WPP_modify_website');

/** Constant: URL location for showing the list of groups in the portfolio. */
define('WPP_GROUP_SUMMARY', 					'admin.php?page=WPP_website_groups');

/** Constant: URL location for modifying a group entry. */
define('WPP_MODIFY_GROUP', 						'admin.php?page=WPP_modify_group');



/**
 * Function: WPPortfolio_menu()
 *
 * Creates the menu with all of the configuration settings.
 */

function WPPortfolio_menu()
{
	add_menu_page('WP Portfolio - '.__('Summary of Websites in your Portfolio', 'wp-portfolio'), 'WP Portfolio', 'manage_options', __FILE__, 'WPPortfolio_show_websites');

	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Modify Website', 'wp-portfolio'), 		__('Add New Website', 'wp-portfolio'), 		'manage_options', 'WPP_modify_website', 'WPPortfolio_modify_website');
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Modify Group', 'wp-portfolio'), 		__('Add New Group', 'wp-portfolio'), 		'manage_options', 'WPP_modify_group', 'WPPortfolio_modify_group');
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Groups', 'wp-portfolio'), 				__('Website Groups', 'wp-portfolio'), 		'manage_options', 'WPP_website_groups', 'WPPortfolio_show_website_groups');

	// Spacer
	add_submenu_page(__FILE__, false, '<span class="wpp_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #CCC;"></span>', 'manage_options', '#', false);

	add_submenu_page(__FILE__, 'WP Portfolio - '.__('General Settings', 'wp-portfolio'), 	__('Portfolio Settings', 'wp-portfolio'), 	'manage_options', 'WPP_show_settings', 'WPPortfolio_pages_showSettings');
	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Layout Settings', 'wp-portfolio'), 	__('Layout Settings', 'wp-portfolio'), 		'manage_options', 'WPP_show_layout_settings', 'WPPortfolio_pages_showLayoutSettings');

	// Spacer
	add_submenu_page(__FILE__, false, '<span class="wpp_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #CCC;"></span>', 'manage_options', '#', false);

	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Refresh Thumbnails', 'wp-portfolio'), 	__('Refresh Thumbnails', 'wp-portfolio'), 	'manage_options', 'WPP_show_refreshThumbnails', 'WPPortfolio_pages_showRefreshThumbnails');

	// Spacer
	add_submenu_page(__FILE__, false, '<span class="wpp_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #CCC;"></span>', 'manage_options', '#', false);

	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Documentation', 'wp-portfolio'), 		__('Documentation', 'wp-portfolio'), 		'manage_options', 'WPP_show_documentation', 'WPPortfolio_pages_showDocumentation');

	$errorCount = WPPortfolio_errors_getErrorCount();
	$errorCountMsg = false;
	if ($errorCount > 0) {
		$errorCountMsg = sprintf('<span title="%d Error" class="update-plugins"><span class="update-count">%d</span></span>', $errorCount, $errorCount);
	}

	add_submenu_page(__FILE__, 'WP Portfolio - '.__('Error Logs', 'wp-portfolio'), 		__('Error Logs', 'wp-portfolio').$errorCountMsg, 'manage_options', 'WPP_show_error_page', 'WPPortfolio_showErrorPage');
}


/**
 * Functions called when plugin initialises with WordPress.
 */
function WPPortfolio_init()
{
	// Backend
	if (is_admin())
	{
		// Warning boxes in admin area only
		// Not needed, no messages currently.
		//add_action('admin_notices', 'WPPortfolio_messages');

		// Menus
		add_action('admin_menu', 'WPPortfolio_menu');

		// Scripts and styles
		add_action('admin_print_scripts', 'WPPortfolio_scripts_Backend');
		add_action('admin_print_styles',  'WPPortfolio_styles_Backend');

		if (current_user_can('administrator') && !empty($_GET['page']) && $_GET['page'] === 'WPP_show_settings') {
			$dismiss_notice = get_option('WPPortfolio_dismiss_notice');
			$dismiss_notice_time = get_option('WPPortfolio_dismiss_notice_time');
			if (!$dismiss_notice && (time() - $dismiss_notice_time) > 45 * 24 * 60 * 60) {
				add_action('admin_notices', 'WPPortfolio_show_thanks_admin_notice');
			}
		}
	}

	// Frontend
	else {

		// Scripts and styles
		add_action('wp_head', 'WPPortfolio_styles_frontend_renderCSS');
		WPPortfolio_scripts_Frontend();
	}

	// Common
	// Add custom links to plugins page
	add_filter( 'plugin_action_links', 'WPPortfolio_add_custom_plugin_actions', 10, 5 );
}
add_action('init', 'WPPortfolio_init');



function WPPortfolio_show_thanks_admin_notice()
{
	$wpportfolio_dismiss_notice_nonce = wp_create_nonce('wpportfolio_dismiss_notice_nonce');
	echo '<div id="wpp-thanks-notice" class="updated notice">

	<h2>'. __("Thank you for using WP-Portfolio! We see that you've been using this plugin for 45 days, so we hope you are thrilled with it!", 'wp-portfolio').'</h2>	
	<a href="https://www.shrinktheweb.com/"><img alt="Shrink The Web" src="'.WPPortfolio_getPluginPath().'/imgs/stw_logo.jpg"></a>
	<p>'.sprintf(__('First, we wanted to mention that if you have a need to show web page screenshots anywhere, then please take a look at the %s plugin.', 'wp-portfolio'), '<a href="https://wordpress.org/plugins/shrinktheweb-website-preview-plugin/">ShrinkTheWeb (STW) Website Previews</a>').'</p>
	<p>'.__('Now, if you LOVE this plugin, we\'d like to ask you to take a moment and leave a very positive review. :)', 'wp-portfolio').'</p>

	<p>
		<a href="https://wordpress.org/plugins/wp-portfolio/#reviews">'.__('Sure, I\'ll write a review', 'wp-portfolio').'</a> | 
		<a href="#" onclick="jQuery(\'#wpp-thanks-notice\').slideUp(\'10\'); jQuery.post(ajaxurl, {action: \'wpportfolio_dismiss_notice_ajax\', subaction: \'dismiss_notice\', nonce: \'' . $wpportfolio_dismiss_notice_nonce . '\' });">'.__('I\'ve already reviewed this plugin', 'wp-portfolio').'</a> | 
		<a href="#" onclick="jQuery(\'#wpp-thanks-notice\').slideUp(\'10\'); jQuery.post(ajaxurl, {action: \'wpportfolio_dismiss_notice_ajax\', subaction: \'dismiss_notice_later\', nonce: \'' . $wpportfolio_dismiss_notice_nonce . '\' });">'.__('Maybe later', 'wp-portfolio').'</a> | 
		<a href="http://feedback.shrinktheweb.com">'.__('Request a new feature or enhancement', 'wp-portfolio').'</a> | 
		<a href="#" onclick="jQuery(\'#wpp-thanks-notice\').slideUp(\'10\'); jQuery.post(ajaxurl, {action: \'wpportfolio_dismiss_notice_ajax\', subaction: \'dismiss_notice\', nonce: \'' . $wpportfolio_dismiss_notice_nonce . '\' });">'.__('Dismiss this notice', 'wp-portfolio').'</a>
	</p>

	<p>&nbsp;</p>
	<button type="button" class="notice-dismiss" onclick="jQuery(\'#wpp-thanks-notice\').slideUp(\'10\'); jQuery.post(ajaxurl, {action: \'wpportfolio_dismiss_notice_ajax\', subaction: \'dismiss_notice_later\', nonce: \'' . $wpportfolio_dismiss_notice_nonce . '\' });"><span class="screen-reader-text">' . __('Dismiss this notice.', 'wp-portfolio') . '</span></button>
	</div>';
}


/**
 * Messages to show the user in the admin area.
 */
function WPPortfolio_messages()
{
}


/**
 * Determine if we're on a page just related to WP Portfolio in the admin area.
 * @return Boolean True if we're on a WP Portfolio admin page, false otherwise.
 */
function WPPortfolio_areWeOnWPPPage()
{
	if (isset($_GET) && isset($_GET['page']))
	{
		$currentPage = $_GET['page'];

		// This handles any WPPortfolio page.
		if ($currentPage == 'wp-portfolio/wp-portfolio.php' || substr($currentPage, 0, 4) == 'WPP_') {
			return true;
		}
	}

	return false;
}







/**
 * Return the list of settings for this plugin.
 * @return Array The list of settings and their default values.
 */
function WPPortfolio_getSettingList($general = true, $style = true, $lightbox = true)
{
	$generalSettings = array(
		'setting_stw_access_key' 		=> false,
		'setting_stw_secret_key' 		=> false,
		'setting_stw_account_type'		=> false,
		'setting_stw_render_type'		=> 'embedded',
		'setting_stw_thumb_size' 		=> 'lg',
		'setting_stw_thumb_size_type'	=> 'standard',
		'setting_stw_thumb_size_custom' => '300',
		'setting_cache_days'	 		=> 7,
		'setting_show_credit' 			=> 'on',
		'setting_enable_debug'			=> false,
		'setting_scale_type'			=> 'scale-both',
		'setting_stw_enable_https'      => 0,
		'setting_stw_enable_https_set_automatically' => 0,
        'setting_stw_thumb_resolution_custom' => '1366',
        'setting_stw_thumb_full_length' => false,
        'setting_stw_enable_create_pages_of_groups' => false,
	);

	$styleSettings = array(
		'setting_template_website'			=> WPP_DEFAULT_WEBSITE_TEMPLATE,
		'setting_template_group'			=> WPP_DEFAULT_GROUP_TEMPLATE,
		'setting_template_css'				=> WPP_DEFAULT_CSS,
		'setting_template_css_paging'		=> WPP_DEFAULT_CSS_PAGING,
		'setting_template_css_widget'		=> WPP_DEFAULT_CSS_WIDGET,
		'setting_disable_plugin_css'		=> false,
		'setting_template_paging'			=> WPP_DEFAULT_PAGING_TEMPLATE,
		'setting_template_paging_previous'	=> WPP_DEFAULT_PAGING_TEMPLATE_PREVIOUS,
		'setting_template_paging_next'		=> WPP_DEFAULT_PAGING_TEMPLATE_NEXT,
		'setting_show_in_lightbox'			=> false,
		'setting_show_sort_buttons' 		=> true,
		'setting_show_filter_buttons' 		=> true,
		'setting_show_expand_button' 		=> true,
		'setting_expanded_website' 			=> false,
	);

	$lightboxSettings = array(
		'setting_lightbox_style' 			=> 1,
		'setting_lightbox_speed' 			=> '300',
		'setting_lightbox_overlay_close' 	=> true,
		'setting_lightbox_esckey_close' 	=> true,
		'setting_lightbox_transition' 		=> 'elastic',
		'setting_lightbox_close_button' 	=> true,
		'setting_lightbox_close_button_text'=> 'X',
		'setting_lightbox_sitename_as_title'=> true
	);

	$settingsList = array();

	// Want to add general settings?
	if ($general) {
		$settingsList = array_merge($settingsList, $generalSettings);
	}

	// Want to add style settings?
	if ($style) {
		$settingsList = array_merge($settingsList, $styleSettings);
	}

	// Want to add lightbox settings?
	if ($lightbox) {
		$settingsList = array_merge($settingsList, $lightboxSettings);
	}

	return $settingsList;
}


/**
 * Install the WP Portfolio plugin, initialise the default settings, and create the tables for the websites and groups.
 */
function WPPortfolio_install()
{
	// ### Create Default Settings
	$settingsList = WPPortfolio_getSettingList();

	// Check the current version of the database
	$installed_ver  = get_option('WPPortfolio_version');
	$current_ver    = WPP_VERSION;
	$upgrade_tables = ($current_ver > $installed_ver);

	// Are we upgrading an old version? If so, then we change
	// the default render type to cache locally as this is a new
	// setting.
	if ($current_ver > '0' && $current_ver < '1.36')
	{
		$settingsList['setting_stw_render_type'] = 'cache_locally';
	}

	if ($current_ver >= '1.42.3' && $installed_ver < '1.42.3') {
		$setting_template_website = trim(str_replace(array("\t", "\r", "\n"), '', get_option('WPPortfolio_setting_template_website')));
		$setting_template_css = trim(str_replace(array("\t", "\r", "\n"), '', get_option('WPPortfolio_setting_template_css')));
		$default_setting_template_website = trim(str_replace(array("\t", "\r", "\n"), '',
			"<div class=\"portfolio-website\">
				<div class=\"website-thumbnail\">%WEBSITE_THUMBNAIL%</div>
				<div class=\"website-name\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_NAME%</a></div>
				<div class=\"website-url\"><a href=\"%WEBSITE_URL%\" target=\"_blank\">%WEBSITE_URL%</a></div>
				<div class=\"website-description\">%WEBSITE_DESCRIPTION%</div>
				<div class=\"website-clear\"></div>
			</div>"));
		$default_setting_template_css = trim(str_replace(array("\t", "\r", "\n"), '',
			".portfolio-website {
				padding: 10px;
				margin-bottom: 10px;
			}
			.website-thumbnail {
				float: left;
				margin: 0 20px 20px 0;
			}
			.website-thumbnail img {
				border: 1px solid #555;
				margin: 0;
				padding: 0;
			}
			.website-name {
				font-size: 12pt;
				font-weight: bold;
				margin-bottom: 3px;
			}
			.website-name a,.website-url a {
				text-decoration: none;
			}
			.website-name a:hover,.website-url a:hover {
				text-decoration: underline;
			}
			.website-url {
				font-size: 9pt;
				font-weight: bold;
			}
			.website-url a {
				color: #777;
			}
			.website-description {
				margin-top: 15px;
			}
			.website-clear {
				clear: both;
			}"));

		if (strcmp($setting_template_website, $default_setting_template_website) === 0) {
			delete_option('WPPortfolio_setting_template_website');
		}
		if (strcmp($setting_template_css, $default_setting_template_css) === 0) {
			delete_option('WPPortfolio_setting_template_css');
		}
	}

	// Initialise all settings in the database
	foreach ($settingsList as $settingName => $settingDefault) {
		if (get_option('WPPortfolio_' . $settingName) === FALSE) {
			// Set the default option
			update_option('WPPortfolio_' . $settingName, $settingDefault);
		}
	}

	if ($current_ver >= '1.42' && !get_option('WPPortfolio_setting_stw_enable_create_pages_of_groups')) {
		WPPortfolio_deleteGroupsPages();
	}

	WPPortfolio_check_scheme_options();

	// Upgrade tables
	WPPortfolio_install_upgradeTables($upgrade_tables);


	// Update the version regardless
	update_option('WPPortfolio_version', WPP_VERSION);

	if (get_option('WPPortfolio_dismiss_notice') === FALSE) {
		update_option('WPPortfolio_dismiss_notice', 0);
	}

	if (get_option('WPPortfolio_dismiss_notice_time') === FALSE) {
		update_option('WPPortfolio_dismiss_notice_time', time());
	}

	// Create cache directory
	WPPortfolio_createCacheDirectory();
}
register_activation_hook(__FILE__,'WPPortfolio_install');


/**
 * On deactivation, remove all functions from the scheduled action hook.
 */
function WPPortfolio_plugin_cleanupForDeactivate() {
	wp_clear_scheduled_hook('wpportfolio_schedule_refresh_thumbnails');
}
register_deactivation_hook( __FILE__, 'WPPortfolio_plugin_cleanupForDeactivate');


/**
 * The cron job to refresh thumbnails.
 */
function WPPortfolio_plugin_runThumbnailRefresh()
{
	WPPortfolio_thumbnails_refreshAll(0, false, false);
}
add_action('wpportfolio_schedule_refresh_thumbnails', 'WPPortfolio_plugin_runThumbnailRefresh');


/**
 * Function to upgrade tables.
 * @param Boolean $upgradeNow If true, upgrade tables now.
 */
function WPPortfolio_install_upgradeTables($upgradeNow, $showErrors = false, $addSampleData = true)
{
	global $wpdb;

	// Table names
	$table_websites		 = $wpdb->prefix . TABLE_WEBSITES;
	$table_websites_meta = $wpdb->prefix . TABLE_WEBSITES_META;
	$table_groups 		 = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$table_debug    	 = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	$table_groups_websites = $wpdb->prefix . TABLE_GROUPS_WEBSITES;
	$table_custom_fields = $wpdb->prefix . TABLE_CUSTOM_FIELDS;

	if ($showErrors) {
		$wpdb->show_errors();
	}

	// Check tables exist
	$table_websites_exists		= ($wpdb->get_var("SHOW TABLES LIKE '$table_websites'") == $table_websites);
	$table_websites_meta_exists	= ($wpdb->get_var("SHOW TABLES LIKE '$table_websites_meta'") == $table_websites_meta);
	$table_groups_exists		= ($wpdb->get_var("SHOW TABLES LIKE '$table_groups'") == $table_groups);
	$table_debug_exists			= ($wpdb->get_var("SHOW TABLES LIKE '$table_debug'") == $table_debug);
	$table_groups_websites_exists = ($wpdb->get_var("SHOW TABLES LIKE '$table_groups_websites'") == $table_groups_websites);
	$table_custom_fields_exists = ($wpdb->get_var("SHOW TABLES LIKE '$table_custom_fields'") == $table_custom_fields);

	// Check versions to provide version dependent table updates.
	$installed_ver  = get_option('WPPortfolio_version');// + 0;
	$current_ver    = WPP_VERSION;// + 0;

	// Only enable if debugging
	//$wpdb->show_errors();

	// #### Create Tables - Websites
	if (!$table_websites_exists || $upgradeNow)
	{
		$sql = "CREATE TABLE `$table_websites` (
  				   siteid INT(10) unsigned NOT NULL auto_increment,
				   sitename varchar(150),
				   siteurl varchar(255),
				   sitedescription TEXT,
				   customthumb varchar(255),
				   siteactive TINYINT NOT NULL DEFAULT '1',
				   displaylink varchar(10) NOT NULL DEFAULT 'show_link',
				   siteorder int(10) unsigned NOT NULL DEFAULT '0',	
				   siteadded datetime default NULL,
				   last_updated datetime default NULL,
				   PRIMARY KEY  (siteid) 
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$table_websites_exists = true;
	}

	// Set default date if there isn't one
	$results = $wpdb->query("UPDATE `$table_websites` SET `siteadded` = NOW() WHERE `siteadded` IS NULL OR `siteadded` = '0000-00-00 00:00:00'");

	if (!$table_websites_meta_exists || $upgradeNow)
	{
		$sql = "CREATE TABLE `$table_websites_meta` (
					`tagid` INT(10) unsigned NOT NULL auto_increment,
					`siteid` INT(10) unsigned NOT NULL,
					`tagname` VARCHAR(150) NOT NULL,
					`templatetag` VARCHAR(150),
					`tagvalue` text,
					PRIMARY KEY  (tagid)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$table_websites_meta_exists = true;
	}


	// #### Create Tables - GroupsALTER TABLE $table_groups ADD `groupactive` TINYINT(4) NOT NULL DEFAULT '1' AFTER `groupdescription`
	if (!$table_groups_exists || $upgradeNow)
	{
		$sql = "CREATE TABLE `$table_groups` (
  				   `groupid` int(10) UNSIGNED NOT NULL auto_increment,
				   `groupname` varchar(150),
				   `groupdescription` TEXT,
				   `groupactive` TINYINT(4) NOT NULL DEFAULT '1',
				   `groupdefault` TINYINT(4) NOT NULL DEFAULT '0',
				   `grouporder` INT(1) UNSIGNED NOT NULL DEFAULT '0',
				   `postid` BIGINT(20) NULL DEFAULT '0',
				   PRIMARY KEY  (groupid)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$table_groups_exists = true;

		// Creating new table? Add default group that has ID of 1
		if ($addSampleData) {
			$group_name = __('My Websites', 'wp-portfolio');
			$group_id = 1;
			if (!$wpdb->get_results("SELECT * FROM `$table_groups` WHERE `groupid` = '$group_id' OR `groupname` = '$group_name';")) {
				$insert = array(
					'groupid' => $group_id,
					'groupname' => $group_name,
					'groupdescription' => __('These are all my websites.', 'wp-portfolio'),
					'groupdefault' => 1,
				);
				$query = arrayToSQLInsert($table_groups, $insert);
				if ($wpdb->query($query) !== false && get_option('WPPortfolio_setting_stw_enable_create_pages_of_groups')) {
					$group_post_id = WPPortfolio_createGroupPage($group_id, $group_name);
					if (!empty($group_post_id)) {
						$update = array(
							'groupid' => $group_id,
							'postid' => $group_post_id
						);
						$query = arrayToSQLUpdate($table_groups, $update, 'groupid');
						if ($wpdb->query($query) === false) {
							wp_delete_post($group_post_id, true);
						}
					}
				}
			}
		}
	}

	// Needed for hard upgrade - existing method of trying to update
	// the table seems to be failing.
	// $wpdb->query("DROP TABLE IF EXISTS $table_debug");

	// #### Create Tables - Debug Log
	if (!$table_debug_exists || $upgradeNow)
	{
		$sql = "CREATE TABLE $table_debug (
  				  `logid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				  `request_url` varchar(255) NOT NULL,
				  `request_param_hash` varchar(35) NOT NULL,
				  `request_result` tinyint(4) NOT NULL DEFAULT '0',
				  `request_error_msg` varchar(30) NOT NULL,
				  `request_detail` text NOT NULL,
				  `request_type` varchar(25) NOT NULL,
				  `request_date` datetime NOT NULL,
  				  PRIMARY KEY  (logid)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$table_debug_exists = true;
	}

	// #### Create Tables - Groups-websites relation
	if (!$table_groups_websites_exists || $upgradeNow)
	{
		$sql = "CREATE TABLE $table_groups_websites (
				  `group_id` int(11) NOT NULL,
				  `website_id` int(11) NOT NULL
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$table_groups_websites_exists = true;
	}

	// #### Create Tables - Custom fields
	if (!$table_custom_fields_exists || $upgradeNow)
	{
		$sql = "CREATE TABLE $table_custom_fields (
  				  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  				  `website_id` int(11) NOT NULL,
				  `field_name` varchar(255) NOT NULL,
  				  `field_value` varchar(255) NOT NULL,
  				  `is_hidden` BOOLEAN,
  				  PRIMARY KEY  (id)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$table_custom_fields_exists = true;
	}

    if ($installed_ver > '0' && $installed_ver < '1.41' && $current_ver >= '1.41'
        && $table_groups_websites_exists && $table_groups_exists && $table_websites_exists && $table_custom_fields_exists) {

		$chunk_size = 1000;

		$offset = 0;
		while($websites_groups = $wpdb->get_results("SELECT siteid, sitegroup FROM $table_websites limit $offset,$chunk_size")) {
			foreach ($websites_groups as $website_group) {
				$data = array(
					'group_id' => $website_group->sitegroup,
					'website_id' => $website_group->siteid
				);
				$query = arrayToSQLInsert($table_groups_websites, $data);
				$wpdb->query($query);
				unset($data);
			}
			unset($websites_groups);
			$offset += $chunk_size;
		}

		$offset = 0;
		while($websites_custom_fields = $wpdb->get_results("SELECT siteid, customfield FROM $table_websites limit $offset,$chunk_size")) {
			foreach ($websites_custom_fields as $website_custom_field) {
				$data = array(
					'website_id' => $website_custom_field->siteid,
					'field_name' => __('Custom Field', 'wp-portfolio'),
					'field_value' => $website_custom_field->customfield,
				);
				$query = arrayToSQLInsert($table_custom_fields, $data);
				$wpdb->query($query);
				unset($data);
			}
			unset($websites_custom_fields);
			$offset += $chunk_size;
		}

		$wpdb->query("ALTER TABLE `$table_websites` DROP `sitegroup`;");
		$wpdb->query("ALTER TABLE `$table_websites` DROP `customfield`;");

		$offset = 0;
		while($groups = $wpdb->get_results("SELECT * FROM $table_groups limit $offset,$chunk_size;")) {
			foreach ($groups as $group) {
				$update = array(
					'groupid' => $group->groupid,
					'groupname' => $group->groupname,
					'groupdescription' => $group->groupdescription
				);
				$query = arrayToSQLUpdate($table_groups, $update, 'groupid');
				if ($wpdb->query($query) !== false && empty($group->postid)
					&& get_option('WPPortfolio_setting_stw_enable_create_pages_of_groups')) {
					$group_post_id = WPPortfolio_createGroupPage($group->groupid, $group->groupname);
					if (!empty($group_post_id)) {
						$update = array(
							'groupid' => $group->groupid,
							'postid' => $group_post_id
						);
						$query = arrayToSQLUpdate($table_groups, $update, 'groupid');
						if ($wpdb->query($query) === false) {
							wp_delete_post($group_post_id, true);
						}
					}
					unset($group_post_id);
				}
				unset($update, $query);
			}
			unset($groups);
			$offset += $chunk_size;
		}

		$offset = 0;
		while($websites = $wpdb->get_results("SELECT * FROM $table_websites limit $offset,$chunk_size")) {
			foreach ($websites as $website) {
				$website_url = (preg_match('/^https?:\/\//', $website->siteurl) !== 1 && trim($website->siteurl) != '' ? 'http://' : '') . trim($website->siteurl);
				$update = array(
					'siteid' => $website->siteid,
					'siteurl' => $website_url,
					'sitename' => $website->sitename,
					'sitedescription' => $website->sitedescription
				);
				$query = arrayToSQLUpdate($table_websites, $update, 'siteid');
				$wpdb->query($query);
				unset($website_url, $update, $query);
			}
			unset($websites);
			$offset += $chunk_size;
		}

		$offset = 0;
		while($websites_meta = $wpdb->get_results("SELECT * FROM $table_websites_meta limit $offset,$chunk_size")) {
			foreach ($websites_meta as $website_meta) {
				$update = array(
					'tagid' => $website_meta->tagid,
					'tagname' => $website_meta->tagname,
					'templatetag' => $website_meta->templatetag,
					'tagvalue' => $website_meta->tagvalue
				);
				$query = arrayToSQLUpdate($table_websites_meta, $update, 'tagid');
				$wpdb->query($query);
				unset($update, $query);
			}
			unset($websites_meta);
			$offset += $chunk_size;
		}
	}

	if ($installed_ver > '0' && $installed_ver < '1.43' && $current_ver >= '1.43' && $table_debug_exists) {
		$chunk_size = 1000;
		$offset = 0;
		while ($debug_logs = $wpdb->get_results("SELECT logid, request_detail FROM $table_debug limit $offset,$chunk_size")) {
			foreach ($debug_logs as $debug_log) {
				$update = array(
					'logid' => $debug_log->logid,
					'request_detail' => preg_replace('/(<span class="wpp-debug-detail-info">)(<\?xml.*?)(<\\/span>)$/si', '<textarea class="wpp_debug_raw" readonly="readonly">' . htmlentities("$2") . '</textarea>', $debug_log->request_detail)
				);
				$query = arrayToSQLUpdate($table_debug, $update, 'logid');
				$wpdb->query($query);
				unset($update, $query);
			}
			unset($debug_logs);
			$offset += $chunk_size;
		}
	}
}


/**
 * Add the custom stylesheet for this plugin.
 */
function WPPortfolio_styles_Backend()
{
	// Only show our stylesheet on a WP Portfolio page to avoid breaking other plugins.
	if (!WPPortfolio_areWeOnWPPPage()) {
		return;
	}

	wp_enqueue_style('wpp-portfolio', 			WPPortfolio_getPluginPath() . 'portfolio.css', false, WPP_VERSION);
}



/**
 * Add the scripts needed for the page for this plugin.
 */
function WPPortfolio_scripts_Backend()
{
	if (!WPPortfolio_areWeOnWPPPage())
		return;

	// Plugin-specific JS
	wp_enqueue_script('wpl-admin-js', WPPortfolio_getPluginPath() .  'js/wpp-admin.js', array('jquery'), WPP_VERSION);
}


/**
 * Scripts used on front of website.
 */
function WPPortfolio_scripts_Frontend()
{
	wp_enqueue_style('wpp-frontend', WPPortfolio_getPluginPath() . 'frontend.css', array(), WPP_VERSION);
}




/**
 * Get the URL for the plugin path including a trailing slash.
 * @return String The URL for the plugin path.
 */
function WPPortfolio_getPluginPath() {
	return trailingslashit(trailingslashit(WP_PLUGIN_URL) . plugin_basename(dirname(__FILE__)));
}


/**
 * Method called when we want to uninstall the portfolio plugin to remove the database tables.
 */
function WPPortfolio_uninstall()
{
	// Remove all options from the database
	delete_option('WPPortfolio_setting_stw_access_key');
	delete_option('WPPortfolio_setting_stw_secret_key');
	delete_option('WPPortfolio_setting_stw_account_type');
	delete_option('WPPortfolio_setting_stw_render_type');
	delete_option('WPPortfolio_setting_stw_thumb_size');
	delete_option('WPPortfolio_setting_stw_thumb_size_type');
	delete_option('WPPortfolio_setting_stw_thumb_size_custom');
	delete_option('WPPortfolio_setting_cache_days');
	delete_option('WPPortfolio_setting_show_credit');
	delete_option('WPPortfolio_setting_enable_debug');
	delete_option('WPPortfolio_setting_scale_type');
	delete_option('WPPortfolio_setting_stw_enable_https');
	delete_option('WPPortfolio_setting_stw_enable_https_set_automatically');
	delete_option('WPPortfolio_setting_stw_thumb_resolution_custom');
	delete_option('WPPortfolio_setting_stw_thumb_full_length');
	delete_option('WPPortfolio_setting_stw_enable_create_pages_of_groups');

	delete_option('WPPortfolio_setting_template_website');
	delete_option('WPPortfolio_setting_template_group');
	delete_option('WPPortfolio_setting_template_css');
	delete_option('WPPortfolio_setting_template_css_paging');
	delete_option('WPPortfolio_setting_template_css_widget');
	delete_option('WPPortfolio_setting_disable_plugin_css');
	delete_option('WPPortfolio_setting_template_paging');
	delete_option('WPPortfolio_setting_template_paging_previous');
	delete_option('WPPortfolio_setting_template_paging_next');
	delete_option('WPPortfolio_setting_show_in_lightbox');
	delete_option('WPPortfolio_setting_show_sort_buttons');
	delete_option('WPPortfolio_setting_show_filter_buttons');
	delete_option('WPPortfolio_setting_show_expand_button');
	delete_option('WPPortfolio_setting_expanded_website');

	delete_option('WPPortfolio_setting_lightbox_style');
	delete_option('WPPortfolio_setting_lightbox_speed');
	delete_option('WPPortfolio_setting_lightbox_overlay_close');
	delete_option('WPPortfolio_setting_lightbox_esckey_close');
	delete_option('WPPortfolio_setting_lightbox_transition');
	delete_option('WPPortfolio_setting_lightbox_close_button');
	delete_option('WPPortfolio_setting_lightbox_close_button_text');
	delete_option('WPPortfolio_setting_lightbox_sitename_as_title');

	delete_option('WPPortfolio_dismiss_notice_time');
	delete_option('WPPortfolio_dismiss_notice');
	delete_option('WPPortfolio_version');


	// Remove all tables for the portfolio
	global $wpdb;
	$table_name    = $wpdb->prefix . TABLE_WEBSITES;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);

	$table_name    = $wpdb->prefix . TABLE_WEBSITES_META;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);

	$table_name    = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$group_post_ids = $wpdb->get_results('SELECT postid from '.$table_name, OBJECT);
	if(is_array($group_post_ids)) {
		foreach ($group_post_ids as $group_post_id) {
			if (!empty($group_post_id->postid)) {
				wp_delete_post($group_post_id->postid, true);
			}
		}
	}
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);

	$table_name    = $wpdb->prefix . TABLE_CUSTOM_FIELDS;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);

	$table_name    = $wpdb->prefix . TABLE_GROUPS_WEBSITES;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);

	$table_name    = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	$uninstall_sql = "DROP TABLE IF EXISTS ".$table_name;
	$wpdb->query($uninstall_sql);


	// Remove cache
	$actualThumbPath = WPPortfolio_getThumbPathActualDir();
	WPPortfolio_unlinkRecursive($actualThumbPath, false);

	WPPortfolio_showMessage(__('Deleted WP Portfolio database entries.', 'wp-portfolio'));
}




/**
 * This method is called just before the <head> tag is closed. We inject our custom CSS into the
 * webpage here.
 */
function WPPortfolio_styles_frontend_renderCSS()
{
	// Only render CSS if we've enabled the option
	$setting_disable_plugin_css = strtolower(trim(get_option('WPPortfolio_setting_disable_plugin_css')));

	// on = disable, anything else is enable
	if ($setting_disable_plugin_css != 'on')
	{
		$setting_template_css 		 = trim(stripslashes(get_option('WPPortfolio_setting_template_css')));
		$setting_template_css_paging = trim(stripslashes(get_option('WPPortfolio_setting_template_css_paging')));
		$setting_template_css_widget = trim(stripslashes(get_option('WPPortfolio_setting_template_css_widget')));

		echo "\n<!-- WP Portfolio Stylesheet -->\n";
		echo "<style type=\"text/css\">\n";

		echo $setting_template_css;
		echo $setting_template_css_paging;
		echo $setting_template_css_widget;

		echo "\n</style>";
		echo "\n<!-- WP Portfolio Stylesheet -->\n";
	}
}



/**
 * Turn the portfolio of websites in the database into a single page containing details and screenshots using the [wp-portfolio] shortcode.
 * @param $atts The attributes of the shortcode.
 * @return String The updated content for the post or page.
 */
function WPPortfolio_convertShortcodeToPortfolio($atts)
{
	// Process the attributes
	extract(shortcode_atts(array(
		'groups' 		=> '',
		'hidegroupinfo' => 0,
		'sitesperpage'	=> 0,
		'orderby' 		=> 'asc',
		'ordertype'		=> 'normal',
		'single'		=> 0,
		'columns'       => 1,
		'grouplist'     => false,
		'defaultgroup'	=> 'all'
	), $atts));

	$single = 0;
	if (isset($atts['single'])) {
		$single = $atts['single'];
	}

	// Check if single contains a valid item ID
	if (is_numeric($single) && $single > 0)
	{
		$websiteDetails = WPPortfolio_getSingleWebsiteDetails($single, OBJECT);

		// Portfolio item not found, abort
		if (!$websiteDetails) {
			return sprintf('<p>'.__('Portfolio item <b>ID %d</b> does not exist.', 'wp-portfolio').'</p>', $single);
		}

		// Item found, so render it
		else  {
			return WPPortfolio_renderPortfolio($websiteDetails, false, false, false, false, false, false, 'normal', false, false);
		}

	}

	// If hidegroupinfo is 1, then hide group details by passing in a blank template to the render portfolio function
	$grouptemplate = false; // If false, then default group template is used.
	if (isset($atts['hidegroupinfo']) && $atts['hidegroupinfo'] == 1) {
		$grouptemplate = '';
	}

	// Sort ASC or DESC?
	$orderAscending = true;
	if (isset($atts['orderby']) && strtolower(trim($atts['orderby'])) == 'desc') {
		$orderAscending = false;
	}

	// Convert order type to use normal as default
	$orderType = strtolower(trim(WPPortfolio_getArrayValue($atts, 'ordertype')));
	if ($orderType == '') {
		$orderType = 'normal';
	}

	// Groups
	$groups = false;
	if (isset($atts['groups'])) {
		$groups = $atts['groups'];
	}

	// Sites per page
	$sitesperpage = 0;
	if (isset($atts['sitesperpage'])) {
		$sitesperpage = $atts['sitesperpage'] + 0;
	}

	// Group list
	$groupList = false;
	if (isset($atts['grouplist'])) {
		$groupList = $atts['grouplist'];
	}

	// Columns
	$columns = 1;
	if (isset($atts['columns'])) {
		$columns = $atts['columns'];
	}

	// Default filter
	$defaultFilter = 'all';
	if (isset($atts['defaultfilter'])) {
		$defaultFilter = $atts['defaultfilter'];
	}

	return WPPortfolio_getAllPortfolioAsHTML($groups, false, $grouptemplate, $sitesperpage, $orderAscending, $orderType, false, false, $columns, $groupList, $defaultFilter);
}
add_shortcode('wp-portfolio', 'WPPortfolio_convertShortcodeToPortfolio');



/**
 * Method to get the portfolio using the specified list of groups and return it as HTML.
 *
 * @param $groups The comma separated string of group IDs to show.
 * @param $template_website The template used to render each website. If false, the website template defined in the settings is used instead.
 * @param $template_group The template used to render each group header. If false, the group template defined in the settings is used instead.
 * @param $sitesperpage The number of sites to show per page, or false if showing all sites at once.
 * @param $orderAscending Order websites in ascending order, or if false, order in descending order.
 * @param $orderBy How to order the results (choose from 'normal' or 'dateadded'). Default option is 'normal'. If 'dateadded' is chosen, group names are not shown.
 * @param $count If > 0, only show the specified number of websites. This overrides $sitesperpage.
 * @param $isWidgetTemplate If true, then we're rendering this as a widget layout.
 *
 * @return String The HTML which contains the portfolio as HTML.
 */
function WPPortfolio_getAllPortfolioAsHTML($groups = '', $template_website = false, $template_group = false, $sitesperpage = false, $orderAscending = true, $orderBy = 'normal', $count = false, $isWidgetTemplate = false, $columns = false, $groupList = false, $defaultFilter = 'all')
{
	if (!$groupList) {
		// Get portfolio from database
		global $wpdb;
		$websites_table        = $wpdb->prefix . TABLE_WEBSITES;
		$groups_table          = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
		$groups_websites_table = $wpdb->prefix . TABLE_GROUPS_WEBSITES;

		// Determine if we only want to show certain groups
		$WHERE_CLAUSE = '';

		// Add initial where if needed
		$WHERE_CLAUSE .= 'WHERE (siteactive = 1)';

		$GROUPBYCLAUSE = $isWidgetTemplate ? "GROUP BY $websites_table.siteid" : '';

		$ORDERBY_ORDERING = 'ASC';
		if ( ! $orderAscending ) {
			$ORDERBY_ORDERING = 'DESC';
		}

		// How to order the results
		switch($orderBy){
			case 'dateadded':
				$ORDERBY_CLAUSE = "ORDER BY siteadded $ORDERBY_ORDERING, sitename ASC";
				$template_group = ''; // Disable group names
				break;
			case 'name':
				$ORDERBY_CLAUSE = "ORDER BY groupid $ORDERBY_ORDERING, sitename $ORDERBY_ORDERING, siteadded ASC";
				break;
			case 'description':
				$ORDERBY_CLAUSE = "ORDER BY groupid $ORDERBY_ORDERING, sitedescription $ORDERBY_ORDERING, sitename ASC";
				break;
			case 'random':
				$ORDERBY_CLAUSE = "ORDER BY groupid ASC, rand()";
				break;
			default:
				$ORDERBY_CLAUSE = "ORDER BY grouporder $ORDERBY_ORDERING, groupname $ORDERBY_ORDERING, groupid $ORDERBY_ORDERING, siteorder $ORDERBY_ORDERING, sitename $ORDERBY_ORDERING";
			break;
		}

		$selectedGroups = false;
		$not_empty_groups = false;
		if ($groups) {
			$selectedGroups = is_array($selectedGroups = explode(',', $groups)) ? array_unique($selectedGroups) : array();
			foreach ($selectedGroups as $key => $selectedGroupID) {
				if (!is_numeric($selectedGroupID)) {
					unset($selectedGroups[$key]);
				}
			}

			$WHERE_CLAUSE .= ' ' . (!empty($selectedGroups) ? "AND $groups_websites_table.group_id IN ('" . implode("','", $selectedGroups) . "')"
					: "AND 0=1");
		}

		$SQL = "SELECT $websites_table.*, $groups_table.* FROM $websites_table
						RIGHT JOIN $groups_websites_table
						ON $groups_websites_table.website_id=$websites_table.siteid
						LEFT JOIN $groups_table
						ON $groups_table.groupid = $groups_websites_table.group_id
						$WHERE_CLAUSE
						$GROUPBYCLAUSE
						$ORDERBY_CLAUSE
						";

		$wpdb->show_errors();

		$paginghtml = false;


		$LIMIT_CLAUSE = false;

		// Convert to a number
		$count        = $count + 0;
		$sitesperpage = $sitesperpage + 0;

		// Show a limited number of websites
		if ( $count > 0 ) {
			$LIMIT_CLAUSE = 'LIMIT ' . $count;
		} // Limit the number of sites shown on a single page.
		else if ( $sitesperpage ) {
			// How many sites do we have?
			$websites      = $wpdb->get_results( $SQL, OBJECT );
			$website_count = $wpdb->num_rows;

			if ($groups) {
				$not_empty_groups = array();
				foreach ($websites as $website) {
					if (!empty($website->groupid) && !in_array($website->groupid, $not_empty_groups)) {
						$not_empty_groups[] = $website->groupid;
					}
				}
			}

			// Paging is needed, as we have more websites than sites/page.
			if ( $website_count > $sitesperpage ) {
				$numofpages = ceil( $website_count / $sitesperpage );

				// Pick up the page number from the GET variable
				$currentpage = 1;
				if ( isset( $_GET['portfolio-page'] ) && ( $_GET['portfolio-page'] + 0 ) > 0 ) {
					$currentpage = $_GET['portfolio-page'] + 0;
				}

				// Load paging defaults from the DB
				$setting_template_paging          = stripslashes( get_option( 'WPPortfolio_setting_template_paging' ) );
				$setting_template_paging_next     = stripslashes( get_option( 'WPPortfolio_setting_template_paging_next' ) );
				$setting_template_paging_previous = stripslashes( get_option( 'WPPortfolio_setting_template_paging_previous' ) );


				// Add Previous Jump Links
				if ( $numofpages > 1 && $currentpage > 1 ) {
					$html_previous = sprintf( '&nbsp;<span class="page-jump"><a href="?portfolio-page=%s"><b>%s</b></a></span>&nbsp;', $currentpage - 1, $setting_template_paging_previous );
				} else {
					$html_previous = sprintf( '&nbsp;<span class="page-jump"><b>%s</b></span>&nbsp;', $setting_template_paging_previous );
				}


				// Render the individual pages
				$html_pages = false;
				for ( $i = 1; $i <= $numofpages; $i ++ ) {
					// No link for current page.
					if ( $i == $currentpage ) {
						$html_pages .= sprintf( '&nbsp;<span class="page-jump page-current"><b>%s</b></span>&nbsp;', $i, $i );
					} // Link for other pages
					else {
						// Avoid parameter if first page
						if ( $i == 1 ) {
							$html_pages .= sprintf( '&nbsp;<span class="page-jump"><a href="?"><b>%s</b></a></span>&nbsp;', $i, $i );
						} else {
							$html_pages .= sprintf( '&nbsp;<span class="page-jump"><a href="?portfolio-page=%s"><b>%s</b></a></span>&nbsp;', $i, $i );
						}
					}
				}
				// Add Next Jump Links
				if ( $currentpage < $numofpages ) {
					$html_next = sprintf( '&nbsp;<span class="page-jump"><a href="?portfolio-page=%s"><b>%s</b></a></span>&nbsp;', $currentpage + 1, $setting_template_paging_next );
				} else {
					$html_next = sprintf( '&nbsp;<span class="page-jump"><b>%s</b></span>&nbsp;', $setting_template_paging_next );
				}


				// Update the SQL for the pages effect
				// Show first page and set limit to start at first record.
				if ( $currentpage <= 1 ) {
					$firstresult  = 1;
					$LIMIT_CLAUSE = sprintf( 'LIMIT 0, %s', $sitesperpage );
				} // Show websites only for current page for inner page
				else {
					$firstresult  = ( ( $currentpage - 1 ) * $sitesperpage );
					$LIMIT_CLAUSE = sprintf( 'LIMIT %s, %s', $firstresult, $sitesperpage );
				}

				// Work out the number of the website being shown at the end of the range.
				$website_endNum = ( $currentpage * $sitesperpage );
				if ( $website_endNum > $website_count ) {
					$website_endNum = $website_count;
				}


				// Create the paging HTML using the templates.
				$paginghtml = $setting_template_paging;

				// Summary info
				$paginghtml = str_replace( '%PAGING_PAGE_CURRENT%', $currentpage, $paginghtml );
				$paginghtml = str_replace( '%PAGING_PAGE_TOTAL%', $numofpages, $paginghtml );

				$paginghtml = str_replace( '%PAGING_ITEM_START%', $firstresult, $paginghtml );
				$paginghtml = str_replace( '%PAGING_ITEM_END%', $website_endNum, $paginghtml );
				$paginghtml = str_replace( '%PAGING_ITEM_TOTAL%', $website_count, $paginghtml );

				// Navigation
				$paginghtml = str_replace( '%LINK_PREVIOUS%', $html_previous, $paginghtml );
				$paginghtml = str_replace( '%LINK_NEXT%', $html_next, $paginghtml );
				$paginghtml = str_replace( '%PAGE_NUMBERS%', $html_pages, $paginghtml );

			} // end of if ($website_count > $sitesperpage)
		}

		// Add the limit clause.
		$SQL .= $LIMIT_CLAUSE;

		$websites = $wpdb->get_results( $SQL, OBJECT );

		$message = false;
		if (!$isWidgetTemplate) {
			if ($groups && is_array($selectedGroups)) {
				if (!is_array($not_empty_groups)) {
					$not_empty_groups = array();
					foreach ($websites as $website) {
						if (!empty($website->groupid) && !in_array($website->groupid, $not_empty_groups)) {
							$not_empty_groups[] = $website->groupid;
						}
					}
				}
				$empty_groups = array_diff($selectedGroups, $not_empty_groups);
				if (!empty($empty_groups) && is_array($empty_groups)) {
					$message = sprintf('<p>' . __('Portfolio websites in groups with such IDs (%s) does not exist.', 'wp-portfolio') . '</p>', implode(',', $empty_groups));
				}
			} elseif (empty($websites)) {
				$message = '<p>' . __('There are no portfolio websites.', 'wp-portfolio') . '</p>';
			}
		}

		// Get the current list of custom data fields
		$custom_data = WPPortfolio_websites_getCustomData();

		// If there are custom custom data fields (is array but not empty array)
		if ( is_array( $custom_data ) && ( $custom_data != array() ) ) {
			// Create string of tags to retrieve
			$wanted_data = '';
			foreach ( $custom_data as $field_data ) {
				$wanted_data .= $wpdb->prepare( '%s, ', $field_data['name'] );
			}
			$wanted_data = rtrim( $wanted_data, ', ' );

			// Extracts the custom field data for each site
			foreach ( $websites as $websitedetails ) {
				// Get the custom fields from the database
				$websitedetails->customData = WPPortfolio_getCustomDetails( $websitedetails->siteid, $wanted_data );

				// Ensure that most recent template tags are assigned
				foreach ( $custom_data as $field_data ) {
					$websitedetails->customData[ $field_data['name'] ]['templatetag'] = $field_data['template_tag'];
				}

			}

		}

		// Render websites into HTML
		$portfolioHTML = WPPortfolio_renderPortfolio( $websites, $template_website, $template_group, $paginghtml, $isWidgetTemplate, $columns, $message, $orderBy, $ORDERBY_ORDERING, $defaultFilter );
	}
	else {
		// Group list.
		global $wpdb;
		$groups_table = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
		$groups = $wpdb->get_results("SELECT * FROM $groups_table WHERE `groupactive`='1' ORDER BY grouporder, groupname", ARRAY_A);

		$list = '';
		if (count($groups)>0) {
			$create_pages_of_groups_option = get_option('WPPortfolio_setting_stw_enable_create_pages_of_groups');
			foreach ($groups as $group) {
				if ($create_pages_of_groups_option) {
					$group_post_id = $group['postid'];
					if (empty($group_post_id)) {
						$group_post_id = WPPortfolio_createGroupPage($group['groupid'], addslashes($group['groupname']));
						if (!empty($group_post_id)) {
							$update = array(
								'postid' => $group_post_id,
								'groupid' => $group['groupid']
							);
							$query = arrayToSQLUpdate($groups_table, $update, 'groupid');
							if (!$wpdb->query($query)) {
								wp_delete_post($group_post_id, true);
							}
						}
					}
					$list .= '<li class="wp-portfolio-group-' . $group['groupid'] . '"><a href="' . get_permalink($group_post_id) . '">' . htmlspecialchars($group['groupname']) . '</a></li>';

				}
				else {
					$list .= '<li class="wp-portfolio-group-' . $group['groupid'] . '">' . htmlspecialchars($group['groupname']) . '</li>';
				}
			}

			$portfolioHTML = '<ul id="wp-portfolio-grouplist">' . $list . '</ul>';
		}
		else {
			return '<p>'.__('There are no active groups.', 'wp-portfolio') . '</p>';
		}
	}

	return $portfolioHTML;
}


/**
 * Method to get a random selection of websites from the portfolio using the specified list of groups and return it as HTML. No group details are
 * returned when showing a random selection of the portfolio.
 *
 * @param $groups The comma separated string of group IDs to use to find which websites to show. If false, websites are selected from the whole portfolio.
 * @param $count The number of websites to show in the output.
 * @param $template_website The template used to render each website. If false, the website template defined in the settings is used instead.
 * @param $isWidgetTemplate If true, then we're rendering this as a widget layout.
 *
 * @return String The HTML which contains the portfolio as HTML.
 */
function WPPortfolio_getRandomPortfolioSelectionAsHTML($groups = '', $count = 3, $template_website = false, $isWidgetTemplate = false, $columns = false)
{
	// Get portfolio from database
	global $wpdb;
	$websites_table = $wpdb->prefix . TABLE_WEBSITES;
	$groups_table   = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$groups_websites_table = $wpdb->prefix . TABLE_GROUPS_WEBSITES;

	// Validate the count is a number
	$count = $count + 0;

	// Determine if we only want to show certain groups
	$WHERE_CLAUSE = '';

	// Add initial where if needed
	$WHERE_CLAUSE .= 'WHERE (siteactive = 1)';

	$selectedGroups = false;
	$not_empty_groups = false;
	if ($groups) {
		$selectedGroups = is_array($selectedGroups = explode(',', $groups)) ? array_unique($selectedGroups) : array();
		foreach ($selectedGroups as $key => $selectedGroupID) {
			if (!is_numeric($selectedGroupID)) {
				unset($selectedGroups[$key]);
			}
		}

		$WHERE_CLAUSE .= ' ' . (!empty($selectedGroups) ? "AND $groups_websites_table.group_id IN ('" . implode("','", $selectedGroups) . "')"
				: "AND 0=1");
	}

	// Limit the number of websites if requested
	$LIMITCLAUSE = false;
	if ($count > 0) {
		$LIMITCLAUSE = 'LIMIT '.$count;
	}

	$GROUPBYCLAUSE = $isWidgetTemplate ? "GROUP BY $websites_table.siteid" : '';

	// Get website details, merge with group details
	$SQL = "SELECT $websites_table.*, $groups_table.* FROM $websites_table
						RIGHT JOIN $groups_websites_table
						ON $groups_websites_table.website_id=$websites_table.siteid
						LEFT JOIN $groups_table
						ON $groups_table.groupid = $groups_websites_table.group_id
						$WHERE_CLAUSE
						$GROUPBYCLAUSE
						ORDER BY RAND()
						$LIMITCLAUSE
						";

	$wpdb->show_errors();
	$websites = $wpdb->get_results($SQL, OBJECT);

	$message = false;
	if (!$isWidgetTemplate) {
		if ($groups && is_array($selectedGroups)) {
			if (!is_array($not_empty_groups)) {
				$not_empty_groups = array();
				foreach ($websites as $website) {
					if (!empty($website->groupid) && !in_array($website->groupid, $not_empty_groups)) {
						$not_empty_groups[] = $website->groupid;
					}
				}
			}
			$empty_groups = array_diff($selectedGroups, $not_empty_groups);
			if (!empty($empty_groups) && is_array($empty_groups)) {
				$message = sprintf('<p>' . __('Portfolio websites in groups with such IDs (%s) does not exist.', 'wp-portfolio') . '</p>', implode(',', $empty_groups));
			}
		} elseif (empty($websites)) {
			$message = '<p>' . __('There are no portfolio websites.', 'wp-portfolio') . '</p>';
		}
	}

	// Get the current list of custom data fields
	$custom_data = WPPortfolio_websites_getCustomData();

	// If there are custom custom data fields (is array but not empty array)
	if(is_array($custom_data) && ($custom_data != array()))
	{
		// Create string of tags to retrieve
		$wanted_data = '';
		foreach($custom_data as $field_data) {
			$wanted_data .= $wpdb->prepare('%s, ', $field_data['name']);
		}
		$wanted_data = rtrim($wanted_data, ', ');

		// Extracts the custom field data for each site
		foreach($websites as $websitedetails)
		{
			// Get the custom fields from the database
			$websitedetails->customData = WPPortfolio_getCustomDetails($websitedetails->siteid, $wanted_data);

			// Ensure that most recent template tags are assigned
			foreach($custom_data as $field_data)
			{
				$websitedetails->customData[$field_data['name']]['templatetag'] = $field_data['template_tag'];
			}

		}

	}

	// Render websites into HTML. Use blank group to avoid rendering group details.
	$portfolioHTML = WPPortfolio_renderPortfolio($websites, $template_website, '', false, $isWidgetTemplate, $columns, $message, 'random', false, false);

	return $portfolioHTML;
}



/**
 * Convert the website details in the database object into the HTML for the portfolio.
 *
 * @param Array $websites The list of websites as objects.
 * @param String $template_website The template used to render each website. If false, the website template defined in the settings is used instead.
 * @param String $template_group The template used to render each group header. If false, the group template defined in the settings is used instead.
 * @param String $paging_html The HTML used for paging the portfolio. False by default.
 * @param Boolean $isWidgetTemplate If true, then we're rendering this as a widget layout.
 *
 * @return String The HTML for the portfolio page.
 */
function WPPortfolio_renderPortfolio($websites, $template_website = false, $template_group = false, $paging_html = false, $isWidgetTemplate = false, $columns = false, $message = false, $orderType = 'normal', $orderBy = 'asc', $defaultFilter = 'all')
{
	if (!$websites)
		return $message;

	// Just put some space after other content before rendering portfolio.
	$content = "<br /><br /><div class = 'wp-portfolio-wrapper'>";

	$show_sort_buttons = get_option('WPPortfolio_setting_show_sort_buttons');
	$show_filter_buttons = get_option('WPPortfolio_setting_show_filter_buttons');
	$show_expand_button = get_option('WPPortfolio_setting_show_expand_button');
	$expanded_websites = get_option('WPPortfolio_setting_expanded_website');

	if (!$isWidgetTemplate && $show_sort_buttons) {
		$content .= '%SORT_BUTTONS_BLOCK%';
	}

	if (!$isWidgetTemplate && $show_filter_buttons) {
		$content .= '%FILTER_BUTTONS_BLOCK%';
	}

	// Used to track what group we're working with.
	$prev_group_id = false;

	// Get templates to use for rendering the website details. Use the defined options if the parameters are false.
	if (!$template_website) {
		$setting_template_website = stripslashes(get_option('WPPortfolio_setting_template_website'));
	} else {
		$setting_template_website = $template_website;
	}

	if ($template_group === false) {
		$setting_template_group = stripslashes(get_option('WPPortfolio_setting_template_group'));
	} else {
		$setting_template_group = $template_group;
	}

	if (!$isWidgetTemplate && !trim(str_replace('&nbsp;','',$setting_template_group))) {
		$content .= '<div class="portfolio-grid">';
	}
	global $wpdb;

	$groups = array();
	// Render all the websites, but look after different groups
	foreach ($websites as $index => $websitedetails)
	{
		$table_custom_fields = $wpdb->prefix . TABLE_CUSTOM_FIELDS;

		// If we're rendering a new group, then show the group name and description
		if ($prev_group_id != $websitedetails->groupid) {
			$groups[$websitedetails->groupid] = $websitedetails->groupname;
			if (trim(str_replace('&nbsp;', '', $setting_template_group))) {
				if (!$isWidgetTemplate && $prev_group_id) {
					$content .= '</div>';
				}
				// Replace group name and description.
				$renderedstr = WPPortfolio_replaceString(WPP_STR_GROUP_NAME, stripslashes($websitedetails->groupname), $setting_template_group);
				$renderedstr = WPPortfolio_replaceString(WPP_STR_GROUP_DESCRIPTION, stripslashes($websitedetails->groupdescription), $renderedstr);

				// Update content with templated group details
				$content .= "\n\n<div class=\"portfolio-group-info group-{$websitedetails->groupid}\">$renderedstr</div>\n";
				if (!$isWidgetTemplate) {
                    $content .= '<div class="portfolio-grid">';
                }
			}
		}

		$websites_custom_fields = $wpdb->get_results( "SELECT id, field_name, field_value, is_hidden FROM $table_custom_fields WHERE website_id =" . $websitedetails->siteid, ARRAY_A);

		$data = '';
		if ($websites_custom_fields) {
			$fields ='';
			foreach ($websites_custom_fields as $cf) {
				$fields .= '<div id="custom_field_' . $cf['id'] . '" class="custom_field_'. $cf['field_name'] . '" ' . ($cf['is_hidden'] ? 'style="visibility:hidden;"' : '') . '><div class="custom_field_name">' . htmlspecialchars($cf['field_name']) . '</div><div class="custom_field_value">' . htmlspecialchars($cf['field_value']) . '</div></div>';
			}
			$data .= '<span class="wpp-custom-field">' . $fields . '</span>';
		}

		// Render the website details
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_NAME, 		 	$websitedetails->sitename, $setting_template_website);
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_DESCRIPTION, 	$websitedetails->sitedescription, $renderedstr);
		$renderedstr = WPPortfolio_replaceString('class="portfolio-website"', 	'class="portfolio-website" date="' . strtotime($websitedetails->siteadded) . '"', $renderedstr);
		$renderedstr = WPPortfolio_replaceString('class="portfolio-website"', 	'class="portfolio-website" group-order="' . $websitedetails->grouporder . '"', $renderedstr);
		$renderedstr = WPPortfolio_replaceString('class="portfolio-website"', 	'class="portfolio-website" group-name="' . $websitedetails->groupname . '"', $renderedstr);
		$renderedstr = WPPortfolio_replaceString('class="portfolio-website"', 	'class="portfolio-website" group-id="' . $websitedetails->groupid . '"', $renderedstr);
		$renderedstr = WPPortfolio_replaceString('class="portfolio-website"', 	'class="portfolio-website" site-order="' . $websitedetails->siteorder . '"', $renderedstr);
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_CUSTOM_FIELD, 	$data, $renderedstr);
		$renderedstr = preg_replace('/portfolio-website/', 'portfolio-website group-' . $websitedetails->groupid, $renderedstr, 1);

		if (!$show_expand_button || $show_expand_button && $expanded_websites) {
			$renderedstr = preg_replace('/portfolio-website/', 'portfolio-website expanded', $renderedstr, 1);
		}

		if(isset($websitedetails->customData))
		{
			// Add the custom data to it's given tags
			foreach($websitedetails->customData as $field_data) {
				$renderedstr = WPPortfolio_replaceString($field_data['templatetag'], WPPortfolio_getArrayValue($field_data, 'tagvalue'), $renderedstr);
			}
		}

		// Remove website link if requested to
		if ($websitedetails->displaylink == 'hide_link')
		{
			$renderedstr = preg_replace('/<a\shref="%WEBSITE_URL%"[^>]+>%WEBSITE_URL%<\/a>/i', '', $renderedstr);
		}

		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_URL, 		 	$websitedetails->siteurl, $renderedstr);



		// Handle the thumbnails - use custom if provided.
		$imageURL = false;
		if ($websitedetails->customthumb)
		{
			$imageURL = WPPortfolio_getAdjustedCustomThumbnail($websitedetails->customthumb);
			$imagetag = sprintf('<img src="%s" alt="%s"/>', $imageURL, $websitedetails->sitename);
		}
		// Standard thumbnail
		else {
			$imagetag = WPPortfolio_getThumbnailHTML($websitedetails->siteurl, false, $websitedetails->sitename);
		}
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_THUMBNAIL_URL, $imageURL, $renderedstr); /// Just URLs
		$renderedstr = WPPortfolio_replaceString(WPP_STR_WEBSITE_THUMBNAIL, $imagetag, $renderedstr);  // Full image tag

		// Handle any shortcodes that we have in the template
		$renderedstr = do_shortcode($renderedstr);

		if (!$isWidgetTemplate && $show_expand_button) {
			$pattern = '/(.*)([\\r\\n\\t]*<\\/div>)$/';
			if (strpos($renderedstr, 'portfolio-website-container') !== false) {
				$pattern = '/(.*)(<\\/div>[\\r\\n\\t]*<\\/div>)$/';
			}
			$renderedstr = preg_replace($pattern, "$1<div class=\"expand-button\"></div>$2", $renderedstr);
		}
		$content .= "\n$renderedstr\n";

		if (!$isWidgetTemplate && trim(str_replace('&nbsp;','',$setting_template_group)) && $index == count($websites) - 1) {
			$content .= '</div>';
		}

		// If fetching thumbnails, this might take a while. So flush.
		flush();

		// Track the groups
		$prev_group_id = $websitedetails->groupid;
	}

	if (!$isWidgetTemplate && $show_sort_buttons) {
		$sort_buttons_block = '<div class="button-group sort-by-button-group">' .
			'<button class="button ' . ($orderType == 'normal' ? 'is-checked' : '') . '" data-sort-value="grouporder,groupname,groupid,siteorder,name">' . __('Original order', 'wp-portfolio') . '</button>' .
			'<button class="button ' . ($orderType == 'name' ? 'is-checked' . (strtolower($orderBy) == 'asc' ? ' desc' : '') : '') . '" data-sort-value="name">' . __('Name', 'wp-portfolio') . '</button>' .
			'<button class="button ' . ($orderType == 'description' ? 'is-checked' . (strtolower($orderBy) == 'asc' ? ' desc' : '') : '') . '" data-sort-value="description">' . __('Description', 'wp-portfolio') . '</button>' .
			'<button class="button ' . ($orderType == 'dateadded' ? 'is-checked' . (strtolower($orderBy) == 'asc' ? ' desc' : '') : '') . '" data-sort-value="date">' . __('Date', 'wp-portfolio') . '</button>' .
			'<button class="button shuffle-button ' . ($orderType == 'random' ? 'is-checked' : '') . '">' . __('Random', 'wp-portfolio') . '</button>' .
			'</div>';
		$content = WPPortfolio_replaceString('%SORT_BUTTONS_BLOCK%', $sort_buttons_block, $content);
	}

	if (!$isWidgetTemplate && $show_filter_buttons) {
		$filter_buttons_block = '<div class="button-group filters-button-group">
					  <button class="button ' . (!in_array($defaultFilter, array_keys($groups)) ? 'is-checked' : '') . '" data-filter="*">' . __('Show all', 'wp-portfolio') . '</button>';
		foreach ($groups as $group_id => $group_name) {
			$filter_buttons_block .= '<button class="button ' . ($defaultFilter == $group_id ? 'is-checked' : '') . '" data-filter=".group-' . $group_id . '">' . $group_name . '</button>';
		}
		$filter_buttons_block .= '</div>';
		$content = WPPortfolio_replaceString('%FILTER_BUTTONS_BLOCK%', $filter_buttons_block, $content);
	}

    if (!$isWidgetTemplate && ($columns > 1 || $columns == 'fill'))
    {
        wp_enqueue_style('wpp-portfolio', WPPortfolio_getPluginPath() . 'columns.css', false, WPP_VERSION);
        $content = preg_replace('/(portfolio-website[^-])/', 'wpp_columns-' . $columns . ' \\1', $content);
        wp_enqueue_script('wpp-frontend-columns', WPPortfolio_getPluginPath() .  'js/wpp-frontend-columns.js', array('jquery'), WPP_VERSION);
    }

	if (!$isWidgetTemplate && ($show_sort_buttons || $show_filter_buttons || $show_expand_button)) {
		wp_enqueue_script('wpp-isotope', WPPortfolio_getPluginPath() . 'js/wpp-isotope.pkgd.min.js', array('jquery'), WPP_VERSION);
		wp_enqueue_script('wpp-masonry', WPPortfolio_getPluginPath() . 'js/masonry.pkgd.min.js', array('jquery'), WPP_VERSION);
        wp_enqueue_script('wpp-frontend', WPPortfolio_getPluginPath() . 'js/wpp-frontend.js', array('jquery'), WPP_VERSION);
	}

	$content .= $paging_html;

	if (!empty($message)) {
		$content .= $message;
	}

	if (!trim(str_replace('&nbsp;','',$setting_template_group))) {
		$content .= '</div>';
	}

	// Credit link on portfolio.
	if (!$isWidgetTemplate && get_option('WPPortfolio_setting_show_credit') == 'on') {
		$content .= sprintf('<div style="clear: both;"></div><div class="wpp-creditlink" style="font-size: 8pt; font-family: Verdana; float: right; clear: both;">'.__('Created using %1$s by %2$s', 'wp-portfolio'), '<a href="http://wordpress.org/extend/plugins/wp-portfolio" target="_blank">WP Portfolio</a>', '<a href="https://shrinktheweb.com/" target="_blank">ShrinkTheWeb</a></div>');
	}

	// Add some space after the portfolio HTML
	$content .= "</div><br /><br />";

	$setting_show_in_lightbox = strtolower(trim(get_option('WPPortfolio_setting_show_in_lightbox')));

	if ($setting_show_in_lightbox == 'on')
	{
		$lightboxSettingsList = WPPortfolio_getSettingList(false, false, true);

		$lightboxSettings = array();
		foreach ($lightboxSettingsList as $settingName => $settingDefault) {
			$lightboxSettings[$settingName] = stripslashes(get_option('WPPortfolio_'.$settingName));
		}
		wp_enqueue_style('wpp-colorbox-css', WPPortfolio_getPluginPath() . 'colorbox.css', array(), WPP_VERSION);
		wp_enqueue_script('wpp-colorbox', WPPortfolio_getPluginPath() .  'js/jquery.colorbox-min.js', array('jquery'), WPP_VERSION);
		wp_enqueue_script('wpp-lightbox-init', WPPortfolio_getPluginPath() .  'js/wpp-colorbox.init.js', array('jquery'), WPP_VERSION);
		wp_localize_script( 'wpp-lightbox-init', 'lightbox_settings', $lightboxSettings);
		$content = str_replace('website-thumbnail', 'website-thumbnail wpp-lightbox', $content);
	}

	return $content;
}



/**
 * Create the cache directory if it doesn't exist.
 * $pathType If specified, the particular cache path to create. If false, use the path stored in the settings.
 */
function WPPortfolio_createCacheDirectory($pathType = false)
{
	// Cache directory
	$actualThumbPath = WPPortfolio_getThumbPathActualDir($pathType);

	// Create cache directory if it doesn't exist
	if (!file_exists($actualThumbPath)) {
		@mkdir($actualThumbPath, 0755, true);
	} else {
		// Try to make the directory writable
		@chmod($actualThumbPath, 0755);
	}
}

/**
 * Gets the full directory path for the thumbnail directory with a trailing slash.
 * @param $pathType The type of directory to fetch, or just return the one specified in the settings if false.
 * @return String The full directory path for the thumbnail directory.
 */
function WPPortfolio_getThumbPathActualDir($pathType = false)
{
	// If no path type is specified, then get the setting from the options table.
	if ($pathType == false) {
		$pathType = WPPortfolio_getCacheSetting();
	}

	switch ($pathType)
	{
		case 'wpcontent':
			return trailingslashit(trailingslashit(WP_CONTENT_DIR).WPP_THUMBNAIL_PATH);
			break;

		default:
			return trailingslashit(trailingslashit(WP_PLUGIN_DIR).WPP_THUMBNAIL_PATH);
			break;
	}
}


/**
 * Gets the full URL path for the thumbnail directory with a trailing slash.
 * @param $pathType The type of directory to fetch, or just return the one specified in the settings if false.
 * @return String The full URL for the thumbnail directory.
 */
function WPPortfolio_getThumbPathURL($pathType = false)
{
	// If no path type is specified, then get the setting from the options table.
	if ($pathType == false) {
		$pathType = WPPortfolio_getCacheSetting();
	}

	switch ($pathType)
	{
		case 'wpcontent':
			return trailingslashit(trailingslashit(WP_CONTENT_URL).WPP_THUMBNAIL_PATH);
			break;

		default:
			return trailingslashit(trailingslashit(WP_PLUGIN_URL).WPP_THUMBNAIL_PATH);
			break;
	}
}


/**
 * Get the type of cache that we need to use. Either 'wpcontent' or 'plugin'.
 * @return String The type of cache we need to use.
 */
function WPPortfolio_getCacheSetting()
{
	$cacheSetting = get_option(WPP_CACHE_SETTING);

	if ($cacheSetting == 'setting_cache_wpcontent') {
		return 'wpcontent';
	}
	return 'plugin';
}


/**
 * Get the full URL path of the pending thumbnails.
 * @return String The full URL path of the pending thumbnails.
 */
function WPPortfolio_getPendingThumbURLPath() {
	return trailingslashit(WP_PLUGIN_URL).WPP_THUMB_DEFAULTS;
}






/**
 * Get the details for the specified Website ID.
 * @param $siteid The ID of the Website to get the details for.
 * @return Array An array of the Website details.
 */
function WPPortfolio_getWebsiteDetails($siteid, $dataType = ARRAY_A)
{
	global $wpdb;
	$websites_table = $wpdb->prefix . TABLE_WEBSITES;
	$groups_websites_table = $wpdb->prefix . TABLE_GROUPS_WEBSITES;

	$SQL = $wpdb->prepare("
			SELECT *,
				   GROUP_CONCAT($groups_websites_table.group_id) as group_ids
			  FROM $websites_table
			  LEFT JOIN $groups_websites_table
			ON $websites_table.siteid = $groups_websites_table.website_id
			WHERE siteid = %d
			LIMIT 1
		", $siteid);

		$data = $wpdb->get_row($SQL, $dataType);
	if(empty($data) || ($dataType == OBJECT && empty($data->siteid) || $dataType == ARRAY_A && empty($data['siteid']))) {
		return false;
	}

	// Get data for custom elements from meta table
	$custom_fields = WPPortfolio_getCustomDetails($siteid);
	if($dataType == ARRAY_A)
	{
		foreach($custom_fields as $field_name=>$field_data) {
				$data[$field_name] = $field_data;
		}
		$data['group_ids'] = is_string($data['group_ids']) ? array_flip(explode(',', $data['group_ids'])) : array();
	} elseif ($dataType == OBJECT) {
		$data->customData = $custom_fields;
		$data->group_ids = is_string($data->group_ids) ? array_flip(explode(',', $data->group_ids)) : array();
	}


	return $data;
}

/**
 * Get the details for the specified Website ID.
 * @param $siteid The ID of the Website to get the details for.
 * @return Array An array of the Website details.
 */
function WPPortfolio_getSingleWebsiteDetails($siteid, $dataType = ARRAY_A)
{
	global $wpdb;
	$websites_table = $wpdb->prefix . TABLE_WEBSITES;
	$groups_websites_table = $wpdb->prefix . TABLE_GROUPS_WEBSITES;
	$groups_table = $wpdb->prefix . TABLE_WEBSITE_GROUPS;

	$SQL = $wpdb->prepare("
			SELECT $websites_table.*, $groups_table.*
			FROM $websites_table
			RIGHT JOIN $groups_websites_table
			ON $groups_websites_table.website_id=$websites_table.siteid
			LEFT JOIN $groups_table
			ON $groups_table.groupid = $groups_websites_table.group_id
			WHERE siteid = %d
		", $siteid);

	$data = $wpdb->get_results($SQL, $dataType);
	if(empty($data)) {
		return false;
	}

	return $data;
}

/**
 * Grab details of custom fields for a given site
 * @param $siteid site to get data for
 * @param $wanted_data array of custom fields to extract
 * @return Associative array tagname=>tagvalue
 */
function WPPortfolio_getCustomDetails($siteid, $wanted_data = false) {
	if(empty($siteid)) {
		return array();
	}
	global $wpdb;

	$table_name = $wpdb->prefix . TABLE_WEBSITES_META;

	$custom_data = WPPortfolio_websites_getCustomData();

	// Query the information for the given site
	$SQL = $wpdb->prepare("
			SELECT tagname, templatetag, tagvalue
			FROM $table_name
			WHERE (siteid = %d)
		", $siteid);

	// If particular tags requested don't bother with others
	if(is_string($wanted_data))
	{
	// Add clause for tags
	$SQL .=  "
	AND (tagname
	IN($wanted_data))
	";
	}

	$custom_data = $wpdb->get_results($SQL, ARRAY_A);

	// Initilise return value
	$data = array();

	// Jiggle output around (index by tagname)
	foreach($custom_data as $field_data) {
	$field_name = $field_data['tagname'];
	unset($field_data['tagname']);
	$data[$field_name] = $field_data;
	}

	return $data;
}


/**
 * AJAX callback function that refreshes a thumbnail.
 */
function WPPortfolio_ajax_handleForcedThumbnailRefresh()
{
	$siteid = false;
	if (isset($_POST['siteid'])) {
		$siteid = $_POST['siteid'];
	}

	echo WPPortfolio_refresh_forceThumbnailRefresh($siteid);
	die();
}
add_action('wp_ajax_thumbnail_refresh', 'WPPortfolio_ajax_handleForcedThumbnailRefresh');


/**
 * AJAX callback function.
 */
function wpportfolio_dismiss_notice_ajax_handler()
{
	$nonce = empty($_POST['nonce']) ? '' : $_POST['nonce'];
	if (!wp_verify_nonce($nonce, 'wpportfolio_dismiss_notice_nonce') || empty($_POST['subaction'])) die(__('Error', 'wp-portfolio'));

	$subaction = $_POST['subaction'];

	// Some commands that are available via AJAX only
	if ('dismiss_notice' == $subaction) {
		update_option('WPPortfolio_dismiss_notice', 1);

	} elseif ('dismiss_notice_later' == $subaction) {
		update_option('WPPortfolio_dismiss_notice_time', time());
	}
	die;
}
add_action('wp_ajax_wpportfolio_dismiss_notice_ajax', 'wpportfolio_dismiss_notice_ajax_handler');


/**
 * Function that removes the physical cached files of the specified URL.
 * @param $fileurl The URL of the file that has been cached.
 */
function WPPortfolio_removeCachedPhotos($fileurl)
{
	$allCached = md5($fileurl).'*';
	$cacheDir = trailingslashit(WPPortfolio_getThumbPathActualDir());

	foreach (glob($cacheDir.$allCached) AS $filename) {
		unlink($filename);
	}
}


/**
 * Determine if an account has a specific account feature using the STW Account API to check. This
 * will cache the settings found through the Account API in the WordPress transients database.
 *
 * @param String $featureNeeded The field name of the feature to check for.
 * @param Mixed $expectedValue The expected value for this feature that will determine if it exists or not.
 *
 * @return Boolean True if the feature exists, false otherwise.
 */
function WPPortfolio_hasCustomAccountFeature($featureNeeded, $expectedValue = 1)
{
	$protocol = stripslashes(get_option('WPPortfolio_setting_stw_enable_https')) ? 'https:' : 'http:';
	// See if we have the account details in the database already to use.
	$aResponse = get_transient('WPPortfolio_account_api_status');

	// No account details, fetch them
	if ($aResponse === FALSE || empty($aResponse))
	{
		$args = array(
			'stwaccesskeyid' 	=> stripslashes(get_option('WPPortfolio_setting_stw_access_key')),
			'stwu'				=> stripslashes(get_option('WPPortfolio_setting_stw_secret_key'))
		);

		// Fetch details about this account
		$accountAPIURL = $protocol . '//images.shrinktheweb.com/account.php?' . http_build_query($args);
		$resp = wp_remote_get($accountAPIURL);

        $gotAccountData = false;

        if (!is_wp_error($resp))
        {
            $http_code = wp_remote_retrieve_response_code($resp);
            if ($http_code == 200)
            {
                $response_body = wp_remote_retrieve_body($resp);
                if (!$response_body || 'offline' == $response_body)
                {
                    // Maintenance Mode or offline
                    if (is_admin())
                    {
                        WPPortfolio_showMessage(__('Failed to retrieve Shrinktheweb account data. Service is Offline or in Maintenance Mode', 'wp-portfolio'), true);
                    }
                    return false;
                }
                // All worked, got raw XML to process.
                else
                {
                    $gotAccountData = wp_remote_retrieve_body($resp);
                }
            }
            else
            {
                if (is_admin())
                {
                    WPPortfolio_showMessage(sprintf(__('Failed to retrieve Shrinktheweb account data. Http code:', 'wp-portfolio').' %s', $http_code), true);
                }
                return false;
            }
        }
        else
        {
            if (is_admin())
            {
                $err = $resp->get_error_code();
                $errmsg = $resp->get_error_message();
                WPPortfolio_showMessage(sprintf(__('Failed to retrieve Shrinktheweb account data.', 'wp-portfolio').' (%s) %s', $err, $errmsg), true);
            }
            return false;
        }
        if ($gotAccountData)
        {
            // Process the return data.
            $oDOM = new DOMDocument;
            $oDOM->loadXML($gotAccountData);
            $sXML = simplexml_import_dom($oDOM);
            $sXMLLayout = 'http://www.shrinktheweb.com/doc/stwacctresponse.xsd';


            $aResponse = array();

            // Pull response codes from XML feed
            $aResponse['stw_response_status'] = (String)$sXML->children($sXMLLayout)->Response->Status->StatusCode; // Response Code
            $aResponse['stw_account_level'] = (Integer)$sXML->children($sXMLLayout)->Response->Account_Level->StatusCode; // Account level

            // check for enabled upgrades
            $aResponse['stw_inside_pages'] = (Integer)$sXML->children($sXMLLayout)->Response->Inside_Pages->StatusCode; // Inside Pages
            $aResponse['stw_custom_size'] = (Integer)$sXML->children($sXMLLayout)->Response->Custom_Size->StatusCode; // Custom Size
            $aResponse['stw_full_length'] = (Integer)$sXML->children($sXMLLayout)->Response->Full_Length->StatusCode; // Full Length
            $aResponse['stw_refresh_ondemand'] = (Integer)$sXML->children($sXMLLayout)->Response->Refresh_OnDemand->StatusCode; // Refresh OnDemand
            $aResponse['stw_custom_delay'] = (Integer)$sXML->children($sXMLLayout)->Response->Custom_Delay->StatusCode; // Custom Delay
            $aResponse['stw_custom_quality'] = (Integer)$sXML->children($sXMLLayout)->Response->Custom_Quality->StatusCode; // Custom Quality
            $aResponse['stw_custom_resolution'] = (Integer)$sXML->children($sXMLLayout)->Response->Custom_Resolution->StatusCode; // Custom Resolution
            $aResponse['stw_custom_messages'] = (Integer)$sXML->children($sXMLLayout)->Response->Custom_Messages->StatusCode; // Custom Messages

            // Cache this data in the database.
            set_transient('WPPortfolio_account_api_status', json_encode($aResponse), 60 * 60 * 24);
        }
        else return false;
	}

	// Decode the settings back into an array
	else
	{
		$aResponse = json_decode($aResponse, true);
	}

	// Return if the feature exists, and is valid.
	return (isset($aResponse[$featureNeeded]) && $aResponse[$featureNeeded] == $expectedValue);
}




/**
 * Determine if there's a custom size option that's been selected.
 * @return The custom size, or false.
 */
function WPPortfolio_getCustomSizeOption()
{
	// Feature not present
	if (!WPPortfolio_hasCustomAccountFeature('stw_custom_size'))
	{
		return false;
	}


    // Do we want to use custom thumbnail types?
    if (get_option('WPPortfolio_setting_stw_thumb_size_type') != 'custom')
    {
    	return false;
    }

    // Custom Size is valid.
    $custom_size = get_option('WPPortfolio_setting_stw_thumb_size_custom');
    if (!preg_match('/^(\d+)x(\d+)$/', $custom_size) && !is_numeric($custom_size))
    {
        return false;
    }

    return $custom_size;
}


/**
 * Determine if there's a custom resolution option that's been selected.
 * @return The custom resolution, or false.
 */
function WPPortfolio_getCustomResolutionOption()
{
    // Feature not present.
    if (!WPPortfolio_hasCustomAccountFeature('stw_custom_resolution'))
    {
        return false;
    }

    // Do we want to use custom thumbnail types?
    if (get_option('WPPortfolio_setting_stw_thumb_size_type') != 'custom')
    {
        return false;
    }

    // Custom Resolution is valid.
    $custom_resolution = get_option('WPPortfolio_setting_stw_thumb_resolution_custom');
    if (!preg_match('/^(\d+)x(\d+)$/', $custom_resolution) && !is_numeric($custom_resolution))
    {
        return false;
    }

    return $custom_resolution;
}

/**
 * Determine if there's a full-length option that's been selected.
 * @return The custom resolution, or false.
 */
function WPPortfolio_getFullLengthOption()
{
    // Feature not present
    if (!WPPortfolio_hasCustomAccountFeature('stw_full_length'))
    {
        return false;
    }

    // Do we want to use custom thumbnail types?
    if (get_option('WPPortfolio_setting_stw_thumb_size_type') != 'custom')
    {
        return false;
    }

    return get_option('WPPortfolio_setting_stw_thumb_full_length');
}

/**
 * Delete all error messages relating to this URL.
 * @param String $url The URL to purge from the error logs.
 */
function WPPortfolio_errors_removeCachedErrors($url)
{
	global $wpdb;
	$wpdb->show_errors;

	$table_debug = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	$SQL = $wpdb->prepare("
		DELETE FROM $table_debug
		WHERE request_url = %s
		", $url);

	$wpdb->query($SQL);
}


/**
 * Function checks to see if there's been an error in the last 12 hours for
 * the requested thumbnail. If there has, then return the error associated
 * with that fetch.
 *
 * @param Array $args The arguments used to fetch the thumbnail
 * @param String $pendingThumbPath The path for images when a thumbnail cannot be loaded.
 * @return String The URL to the error image, or false if there's no cached error.
 */
function WPPortfolio_errors_checkForCachedError($args, $pendingThumbPath)
{
	global $wpdb;
	$wpdb->show_errors;

	$argHash = md5(serialize($args));

	$table_debug    = $wpdb->prefix . TABLE_WEBSITE_DEBUG;
	$SQL = $wpdb->prepare("
		SELECT * 
		FROM $table_debug
		WHERE request_param_hash = %s
		  AND request_date > NOW() - INTERVAL 12 HOUR
		  AND request_result = 0
		ORDER BY request_date DESC
		", $argHash);

	$errorCache = $wpdb->get_row($SQL);

	if ($errorCache)  {
		return WPPortfolio_error_getErrorStatusImg($args, $pendingThumbPath, $errorCache->request_error_msg);
	}

	return false;
}

/**
 * Get a total count of the errors currently logged.
 */
function WPPortfolio_errors_getErrorCount()
{
	global $wpdb;
	$wpdb->show_errors;
	$table_debug    = $wpdb->prefix . TABLE_WEBSITE_DEBUG;

	return $wpdb->get_var("SELECT COUNT(*) FROM $table_debug WHERE request_result = 0");
}

/**
 * Cleans unauthorised characters from a template tag
 * @param String $inString The string to make safe.
 * @return String A safe string for internal use
 */
function WPPortfolio_cleanInputData($inString)
{
	$inString = trim(strtoupper($inString));

	// Remove brackets and quotes completely
	$inString = preg_replace('%[\(\[\]\)\'\"]%', '', $inString);

	// Remove non-alpha characters
	$inString = preg_replace('%[^0-9A-Z\_]+%', '_', $inString);

	// Remove the first and last underscores (if there is one)
	$inString = trim($inString, '_');

	return '%'.$inString.'%';
}

/**
 * Retrieves and validates data from the filter for custom data
 * @param Boolean $warn The warning
 * @return list of custom data elements
 */
function WPPortfolio_websites_getCustomData($warn = true)
{
	$custom_fields = apply_filters('wpportfolio_filter_portfolio_custom_fields', array());

	// Sanity check. have we been given an array?
	if(empty($custom_fields) || !is_array($custom_fields)) {
		return array();
	}

	$problems = "";
	// Sanity check for each array element
	foreach($custom_fields as $field_key=>$field_data)
	{
		// Does the field have a name and template-tag?
		if(!empty($field_data['name']) && !empty($field_data['template_tag']))
		{
			// Special sanitization for name and template_tag
			$custom_fields[$field_key]['name']			= preg_replace('/[^A-Za-z0-9_-]/', '', $field_data['name']);

			// Generate full template tag
			$custom_fields[$field_key]['template_tag']	= WPPortfolio_cleanInputData($field_data['template_tag']);

			// Only display errors if we are an admin (clean front-end)
		} else
		{
			if(is_admin() && ($warn !== false))
			{
				if(empty($field_data['name'])) {
					$problems .= '<br/>'.sprintf(__('Field %d doesn\'t have a name.', 'wp-portfolio'), ($field_key+1));
				} else {
					$problems .= '<br/>'.sprintf(__('Field %d doesn\'t have a template tag.', 'wp-portfolio'), ($field_key+1));
				}
			}
			unset($custom_fields[$field_key]);
		}
	}
	if($problems != '')
	{
		WPPortfolio_showMessage(__('You have added some custom fields but we\'ve had a problem, here\'s what we found:', 'wp-portfolio')
		.$problems, true);
	}

	return $custom_fields;
}

/**
 * Create a new page for the group.
 * @param Int $group_id Group id
 * @param String $group_name Group name
 * @return Int Created post id or 0
 */
function WPPortfolio_createGroupPage($group_id, $group_name)
{
	$group_post = array(
		'post_title' => 'Group ' . $group_name,
		'post_content' => '[wp-portfolio groups="' . $group_id . '"]',
		'post_status' => 'publish',
		'post_author' => 1,
		'comment_status' => 'closed',
		'post_type' => 'page'
	);
	return wp_insert_post($group_post);
}

/**
 * Create pages of groups.
 */
function WPPortfolio_createGroupsPages()
{
	global $wpdb;
	$groups_table = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$created_pages_count = 0;

	$SQL = "SELECT groupid, groupname FROM $groups_table WHERE `postid` IS NULL OR `postid` = '0'";

	// DEBUG Uncomment if needed
	// $wpdb->show_errors();
	$groups = $wpdb->get_results($SQL, OBJECT);
	foreach ($groups as $group) {
		$group_post_id = WPPortfolio_createGroupPage($group->groupid, $group->groupname);
		if (!empty($group_post_id)) {
			$update = array(
				'groupid' => $group->groupid,
				'postid' => $group_post_id
			);
			$query = arrayToSQLUpdate($groups_table, $update, 'groupid');
			if ($wpdb->query($query) === false) {
				wp_delete_post($group_post_id, true);
			}
			else{
				$created_pages_count++;
			}
		}
	}
	return $created_pages_count;
}

/**
 * Delete pages of groups.
 */
function WPPortfolio_deleteGroupsPages()
{
	global $wpdb;
	$groups_table = $wpdb->prefix . TABLE_WEBSITE_GROUPS;
	$deleted_pages_count = 0;

	$SQL = "SELECT groupid, postid FROM $groups_table WHERE `postid` IS NOT NULL AND `postid` <> '0'";

	// DEBUG Uncomment if needed
	// $wpdb->show_errors();
	$groups = $wpdb->get_results($SQL, OBJECT);
	foreach ($groups as $group) {
		if (wp_delete_post($group->postid, true)) {
			$deleted_pages_count++;
		}
		$update = array(
			'groupid' => $group->groupid,
			'postid' => 0
		);
		$query = arrayToSQLUpdate($groups_table, $update, 'groupid');
		$wpdb->query($query);
	}
	return $deleted_pages_count;
}


/**
 * Register TinyMCE Button
 *
 * @param array $buttons List of TinyMCE buttons.
 * @return array Modified list of TinyMCE buttons.
 */
function register_wpp_mce_button( $buttons ) {
	array_push( $buttons, '|', 'wpp' );
	return $buttons;
}

/**
 * Register TinyMCE Plugin
 *
 * @param array $plugins List of tinyMCE plugins already available.
 * @return array Modified list of TinyMCE plugins
 */
function register_wpptinymce_mce_plugin( $plugins ) {
	$plugins['wpptinymce'] = plugins_url( 'js/wpp-tinymce-plugin.js' , __FILE__ );
	return $plugins;
}

/**
 * Add translations to TinyMCE plugin
 *
 * @param array $locales List of TinyMCE plugin locales.
 * @return array Modified list of TinyMCE plugin locales.
 */
function mce_wpptinymce_plugin_locale( $locales )
{
	$locales['wpptinymce'] = plugin_dir_path(__FILE__) . 'wp-portfolio-tinymce-translation.php';
	return $locales;
}

function setup_wpptinymce_plugin()
{
	// Check if the logged in WordPress User can edit Posts or Pages
	// If not, don't register our TinyMCE plugin
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
		return;
	}

	// Check if the logged in WordPress User has the Visual Editor enabled
	// If not, don't register our TinyMCE plugin
	if (get_user_option('rich_editing') !== 'true') {
		return;
	}

	// Setup some filters
	add_filter('mce_buttons', 'register_wpp_mce_button');
	add_filter('mce_external_plugins', 'register_wpptinymce_mce_plugin');
	add_filter('mce_external_languages', 'mce_wpptinymce_plugin_locale', 10, 1);
}

add_action('init', 'setup_wpptinymce_plugin');


function check_wpp_plugin_version()
{
	$installed_ver = get_option('WPPortfolio_version');
	$current_ver = WPP_VERSION;
	if ($installed_ver > '0' && $current_ver > $installed_ver) {
		WPPortfolio_install();
	}
}

add_action('init', 'check_wpp_plugin_version');


function wpp_delete_post_processing_init()
{
	add_action('wp_trash_post', 'wpp_delete_post_processing', 10);
}

function wpp_delete_post_processing($pid)
{
	global $wpdb;
	$groups_table = $wpdb->prefix . TABLE_WEBSITE_GROUPS;

	$SQL = "SELECT groupid FROM $groups_table WHERE `postid` = $pid";

	// DEBUG Uncomment if needed
	// $wpdb->show_errors();
	$groups = $wpdb->get_results($SQL, OBJECT);
	foreach ($groups as $group) {
		$update = array(
			'groupid' => $group->groupid,
			'postid' => 0
		);
		$query = arrayToSQLUpdate($groups_table, $update, 'groupid');
		$wpdb->query($query);
	}
}

add_action( 'admin_init', 'wpp_delete_post_processing_init' );

function WPPortfolio_is_ssl()
{
	if (!empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'off') != 0) {
		return true;
	} elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
		return true;
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') == 0) {
		return true;
	}
	return false;
}

function WPPortfolio_check_scheme_options()
{
	if (WPPortfolio_is_ssl()) {
		update_option('WPPortfolio_setting_stw_enable_https', '1');
		update_option('WPPortfolio_setting_stw_enable_https_set_automatically', '1');
	} else {
		if (get_option('WPPortfolio_setting_stw_enable_https_set_automatically')) {
			update_option('WPPortfolio_setting_stw_enable_https_set_automatically', '0');
			update_option('WPPortfolio_setting_stw_enable_https', '0');
		}
	}
}

/**
 * Adds custom links to the plugin page.
 */
function WPPortfolio_add_custom_plugin_actions( $actions, $plugin_file )
{
	static $plugin;

	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file) {
		$custom_actions = array(
			'donate' => sprintf('<a href="%1$s" target="_blank">%2$s</a>', 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ZBNAT7HJACUAG&lc=US&item_name=ShrinkTheWeb&no_note=0&cn=Add%20special%20instructions%20to%20the%20seller%3a&no_shipping=1&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted', __('Donate', 'wp-portfolio')),
			'settings' => sprintf('<a href="%1$s">%2$s</a>', admin_url('admin.php?page=WPP_show_settings'), __('Settings', 'wp-portfolio')));
		$actions = array_merge($custom_actions, $actions);
	}

	return $actions;
}
?>
