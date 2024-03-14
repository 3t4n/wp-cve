<?php
// We need the ABSPATH
if (!defined('ABSPATH')) exit;

define('SITESEO_VERSION', '1.0.4');
define('SITESEO_AUTHOR', 'Softaculous');
define('SITESEO_WEBSITE', 'https://siteseo.io/');
define('SITESEO_API', 'https://api.siteseo.io/');
define('SITESEO_DOCS', 'https://siteseo.io/docs/');
define('SITESEO_SUPPORT', 'https://softaculous.deskuss.com/open.php');
define('SITESEO_DIR_PATH', plugin_dir_path(SITESEO_FILE));
define('SITESEO_DIR_URL', plugin_dir_url(SITESEO_FILE));
define('SITESEO_ASSETS_DIR', SITESEO_DIR_URL . 'assets');
define('SITESEO_TEMPLATE_DIR', SITESEO_DIR_PATH . 'templates');
define('SITESEO_TEMPLATE_SITEMAP_DIR', SITESEO_TEMPLATE_DIR . '/sitemap');
define('SITESEO_TEMPLATE_JSON_SCHEMAS', SITESEO_TEMPLATE_DIR . '/json-schemas');
define('SITESEO_MAIN', SITESEO_DIR_PATH . '/main');
define('SITESEO_CLASSES', SITESEO_DIR_PATH . '/classes');
define('SITESEO_URL_PUBLIC', SITESEO_DIR_URL . 'main/public');
define('SITESEO_DIR_LANGUAGES', dirname(plugin_basename(SITESEO_FILE)) . '/languages/');

if(file_exists(__DIR__ . '/DEV.php')){
	include_once __DIR__ . '/DEV.php';
}

// Hooks activation
register_activation_hook(SITESEO_FILE, 'siteseo_activation');
function siteseo_activation() {
	add_option('siteseo_activated', 'yes');
	flush_rewrite_rules(false);

	add_option('siteseo_version', SITESEO_VERSION);
	
	if(!defined('SITESEO_PREMIUM')){
		do_action('siteseo_activation');
		return;
	}
	
	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	if(!function_exists('activate_plugins')){
		return;
	}

	if(!function_exists('get_plugins')){
		return;
	}
	
	if(!function_exists('get_editable_roles')){
		include_once ABSPATH . 'wp-admin/includes/user.php';
	}
	
	// Activation code for Pro starts here
	//Add Redirections caps to user with "manage_options" capability
	$roles = get_editable_roles();
	if ( ! empty($roles)) {
		foreach ($GLOBALS['wp_roles']->role_objects as $key => $role) {
			if (isset($roles[$key]) && $role->has_cap('manage_options')) {
				$role->add_cap('edit_redirection');
				$role->add_cap('edit_redirections');
				$role->add_cap('edit_others_redirections');
				$role->add_cap('publish_redirections');
				$role->add_cap('read_redirection');
				$role->add_cap('read_private_redirections');
				$role->add_cap('delete_redirection');
				$role->add_cap('delete_redirections');
				$role->add_cap('delete_others_redirections');
				$role->add_cap('delete_published_redirections');
			}
			if (isset($roles[$key]) && $role->has_cap('manage_options')) {
				$role->add_cap('edit_schema');
				$role->add_cap('edit_schemas');
				$role->add_cap('edit_others_schemas');
				$role->add_cap('publish_schemas');
				$role->add_cap('read_schema');
				$role->add_cap('read_private_schemas');
				$role->add_cap('delete_schema');
				$role->add_cap('delete_schemas');
				$role->add_cap('delete_others_schemas');
				$role->add_cap('delete_published_schemas');
			}
		}
	}
	
	$plugins = get_plugins();
	if ( ! empty($plugins['siteseo/siteseo.php'])){
		flush_rewrite_rules(false);
	}

	add_option('siteseo_pro_activated', 'yes');

	do_action('siteseo_pro_activation');
}

// Hooks deactivation
register_deactivation_hook(SITESEO_FILE, 'siteseo_deactivation');
function siteseo_deactivation() {

	delete_option('siteseo_activated');
	delete_option('siteseo_version');
	flush_rewrite_rules(false);

	if(!defined('SITESEO_PREMIUM')){
		do_action('siteseo_deactivation');
		return;
	}
	
	// PRO Deactivation
	delete_option('siteseo_pro_activated');
	wp_clear_scheduled_hook('siteseo_404_cron_cleaning');
	wp_clear_scheduled_hook('siteseo_google_analytics_cron');
	wp_clear_scheduled_hook('siteseo_page_speed_insights_cron');
	wp_clear_scheduled_hook('siteseo_404_email_alerts_cron');
	wp_clear_scheduled_hook('siteseo_insights_gsc_cron');
	wp_clear_scheduled_hook('siteseo_matomo_analytics_cron');
	do_action('siteseo_pro_deactivation');

}

use SiteSEO\Core\Kernel;

spl_autoload_register('siteseo_autoloader');
function siteseo_autoloader($class){
	
	// project-specific namespace prefix
	$prefix = 'SiteSEO\\';

	// base directory for the namespace prefix
	$base_dir = SITESEO_CLASSES.'/';

	// does the class use the namespace prefix?
	$len = strlen($prefix);
	if (0 !== strncmp($prefix, $class, $len)) {
		// no, move to the next registered autoloader
		return;
	}

	// get the relative class name
	$relative_class = substr($class, $len);

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
	
	// if the file exists, require it
	if (file_exists($file)) {
		require $file;
	}
}

if(file_exists(SITESEO_DIR_PATH . '/vendor/autoload.php')){

	require_once SITESEO_MAIN.'/functions.php';
	
	// TODO: check
	// This build array of all actions and services
	// And also this execute all action, on plugins_loaded, activation, deactivation hook; 	
	Kernel::execute([
		'file' => SITESEO_FILE,
		'slug' => defined('SITESEO_PREMIUM') ? 'siteseo-pro' : 'siteseo',
		'main_file' => defined('SITESEO_PREMIUM') ? 'siteseo-pro' : 'siteseo',
		'root' => __DIR__,
	]);
}

function siteseo_titles_single_cpt_enable_option($cpt) {
	$current_cpt = null;
	$options = get_option('siteseo_titles_option_name');

	if( ! empty($options) && isset($options['titles_single_titles'][$cpt]['enable'])){
		$current_cpt = $options['titles_single_titles'][$cpt]['enable'];
	}

	return $current_cpt;
}

// Archive CPT Titles
function siteseo_titles_archive_titles_option() {
	global $post;
	$siteseo_get_current_cpt = get_post_type($post);

	$options = get_option('siteseo_titles_option_name');
	if ( ! empty($options) && isset($options['titles_archive_titles'][$siteseo_get_current_cpt]['title'])) {
		return $options['titles_archive_titles'][$siteseo_get_current_cpt]['title'];
	}
}

// Checks if we are to update ?
function siteseo_update_check(){

	global $wpdb;

	$current_version = get_option('siteseo_version');
	$version = (int) str_replace('.', '', $current_version);

	// No update required
	if($current_version == SITESEO_VERSION){
		return true;
	}

	// Is it first run ?
	if(empty($current_version)){

		// Reinstall
		siteseo_activation();

		// Trick the following if conditions to not run
		$version = (int) str_replace('.', '', SITESEO_VERSION);
		
		// Allow to prevent plugin first install hooks to fire.
		if( ! apply_filters( 'siteseo_prevent_first_install', false ) ){
			do_action( 'siteseo_first_install' );
		}

	}
	
	do_action( 'siteseo_upgrade', SITESEO_VERSION, $current_version );
	
	// Save the new Version
	update_option('siteseo_version', SITESEO_VERSION);

}

// Add the action to load the plugin 
add_action('plugins_loaded', 'siteseo_plugins_loaded', 999);

// SITESEO INIT = Admin + Core + API + Translation
function siteseo_plugins_loaded(){

	global $pagenow, $typenow, $wp_version, $siteseo;
	
	$siteseo = new StdClass();

	// i18n
	load_plugin_textdomain('siteseo', false, dirname(plugin_basename(SITESEO_FILE)) . '/languages/');
	
	// Any update on new version?
	siteseo_update_check();
	
	if (is_admin() || is_network_admin()) {
		require_once SITESEO_MAIN.'/admin/admin.php';
		require_once SITESEO_MAIN.'/admin/migrate/MigrationTools.php';
		require_once SITESEO_MAIN.'/admin/docs/DocsLinks.php';
		
		// data: is seen as a protocol by wp_kses_post and its not included in allowed protocols by Default
		// So we need to add it to make our base64 images to work even after being ksesed.					
		add_filter('kses_allowed_protocols', function($protocols){
			if(!in_array('data', $protocols)){
				$protocols[] = 'data';
			}
			return $protocols;
		}, 10);

		if ('post-new.php' == $pagenow || 'post.php' == $pagenow) {
			if ('siteseo_schemas' != $typenow) {
				require_once SITESEO_MAIN.'/admin/metaboxes/admin-metaboxes.php';
			}
		}
		if ('term.php' == $pagenow || 'edit-tags.php' == $pagenow) {
			require_once SITESEO_MAIN.'/admin/metaboxes/admin-term-metaboxes.php';
		}
		
		// Load ajax
		require_once SITESEO_MAIN.'/admin/ajax.php';
		
		if (defined('SITESEO_WL_ADMIN_HEADER') && SITESEO_WL_ADMIN_HEADER === false) {
			//do not load the SiteSEO admin header
		} else {
			require_once SITESEO_MAIN.'/admin/admin-bar/admin-header.php';
		}
	}

	require_once SITESEO_MAIN.'/options.php';

	require_once SITESEO_MAIN.'/admin/admin-bar/admin-bar.php';

	remove_action('wp_head', 'rel_canonical'); //remove default WordPress Canonical

	// Setup/welcome
	if( ! empty($_GET['page'])){
		switch ($_GET['page']) {
			case 'siteseo-setup':
				include_once SITESEO_MAIN.'/admin/wizard/admin-wizard.php';
				break;
			default:
				break;
		}
	}

	// Elementor
	if (did_action('elementor/loaded')) {
		include_once SITESEO_MAIN.'/admin/page-builders/elementor/elementor-addon.php';
	}

	// Block Editor
	include_once SITESEO_MAIN.'/admin/page-builders/gutenberg/blocks.php';


	// Classic Editor
	if ( is_admin() ) {
		include_once SITESEO_MAIN.'/admin/page-builders/classic/classic-editor.php';
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads dynamic variables for titles, metas, schemas...
///////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Render dynamic variables
 * @param array $variables
 * @param object $post
 * @param boolean $is_oembed
 * @return array $variables
 * author Softaculous
 */
function siteseo_dyn_variables_init($variables, $post = '', $is_oembed = false) {
	include_once SITESEO_MAIN.'/dynamic-variables.php';
	return SiteSEO\Helpers\CachedMemoizeFunctions::memoize('siteseo_get_dynamic_variables')($variables, $post, $is_oembed);
}
add_filter('siteseo_dyn_variables_fn', 'siteseo_dyn_variables_init', 10, 3);

// Quick Edit
function siteseo_add_admin_options_scripts_quick_edit() {
	$prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
	wp_enqueue_script('siteseo-quick-edit', plugins_url('assets/js/siteseo-quick-edit' . $prefix . '.js', __FILE__), ['jquery', 'inline-edit-post'], SITESEO_VERSION, true);
}
add_action('admin_print_scripts-edit.php', 'siteseo_add_admin_options_scripts_quick_edit');

///////////////////////////////////////////////////////////////////////////////////////////////////
//WP compatibility
///////////////////////////////////////////////////////////////////////////////////////////////////
/*
 * Remove WP default meta robots (added in WP 5.7)
 *
 * @since 4.4.0.7
 */
remove_filter('wp_robots', 'wp_robots_max_image_preview_large');

/*
 * Remove WC default meta robots (added in WP 5.7)
 *
 * @since 4.6
 * @todo use wp_robots API
 * @updated 5.8
 */
function siteseo_robots_wc_pages($robots) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	if (is_plugin_active('woocommerce/woocommerce.php')) {
		if (function_exists('wc_get_page_id')) {
			if (is_page(wc_get_page_id('cart')) || is_page(wc_get_page_id('checkout')) || is_page(wc_get_page_id('myaccount'))) {
				if ('0' === get_option('blog_public')) {
					return $robots;
				} else {
					unset($robots);
					$robots = [];

					return $robots;
				}
			}
		}
	}
	//remove noindex on search archive pages
	if (is_search()) {
		if ('0' === get_option('blog_public')) {
			return $robots;
		} else {
			unset($robots);
			$robots = [];

			return $robots;
		}
	}

	return $robots;
}
add_filter('wp_robots', 'siteseo_robots_wc_pages', 20);

///////////////////////////////////////////////////////////////////////////////////////////////////
//3rd plugins compatibility
///////////////////////////////////////////////////////////////////////////////////////////////////
//Jetpack
function siteseo_compatibility_jetpack() {
	if (function_exists('is_plugin_active')) {
		if (is_plugin_active('jetpack/jetpack.php') && ! is_admin()) {
			add_filter('jetpack_enable_open_graph', '__return_false');
			add_filter('jetpack_disable_seo_tools', '__return_true');
		}
	}
}
add_action('wp_head', 'siteseo_compatibility_jetpack', 0);

/**
 * Remove default WC meta robots.
 *
 * @since 3.8.1
 */
function siteseo_compatibility_woocommerce() {
	if (function_exists('is_plugin_active')) {
		if (is_plugin_active('woocommerce/woocommerce.php') && ! is_admin()) {
			remove_action('wp_head', 'wc_page_noindex');
		}
	}
}
add_action('wp_head', 'siteseo_compatibility_woocommerce', 0);

/**
 * Remove WPML home url filter.
 *
 * @since 3.8.6
 *
 * @param mixed $home_url
 * @param mixed $url
 * @param mixed $path
 * @param mixed $orig_scheme
 * @param mixed $blog_id
 */
function siteseo_remove_wpml_home_url_filter($home_url, $url, $path, $orig_scheme, $blog_id) {
	return $url;
}

/*
 * Remove third-parties metaboxes on our CPT
 * @author Softaculous
 * @since 4.2
 */
add_action('do_meta_boxes', 'siteseo_remove_metaboxes', 10);
function siteseo_remove_metaboxes() {
	//Oxygen Builder
	remove_meta_box('ct_views_cpt', 'siteseo_404', 'normal');
	remove_meta_box('ct_views_cpt', 'siteseo_schemas', 'normal');
	remove_meta_box('ct_views_cpt', 'siteseo_bot', 'normal');
}

/**
 * Global check if a feature is ON
 *
 * @since 3.8
 *
 * @param string $feature
 *
 * @return string 1 if true
 *
 * author Softaculous
 */
function siteseo_get_toggle_option($feature) {
	$options = get_option('siteseo_toggle');
	if( ! empty($options) && isset($options['toggle-' . $feature])) {
		return $options['toggle-' . $feature];
	}
}

/*
 * Rewrite Rules for XML Sitemaps
 */
if ('1' == siteseo_get_service('SitemapOption')->isEnabled() && '1' == siteseo_get_toggle_option('xml-sitemap')) {
	function siteseo_sitemaps_headers() {
		siteseo_get_service('SitemapHeaders')->printHeaders();
	}

	// WPML compatibility
	if (defined('ICL_SITEPRESS_VERSION')) {
		add_filter('request', 'siteseo_wpml_block_secondary_languages');
	}

	function siteseo_wpml_block_secondary_languages($q) {
		$current_language = apply_filters('wpml_current_language', false);
		$default_language = apply_filters('wpml_default_language', false);
		if ($current_language !== $default_language) {
			unset($q['siteseo_sitemap']);
			unset($q['siteseo_cpt']);
			unset($q['siteseo_paged']);
			unset($q['siteseo_author']);
			unset($q['siteseo_sitemap_xsl']);
			unset($q['siteseo_sitemap_video_xsl']);
		}

		return $q;
	}
}
