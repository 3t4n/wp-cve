<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

use SiteSEO\Helpers\PagesAdmin;

class siteseo_options {
	
	/**
	 * Holds the values to be used in the fields callbacks.
	 */
	private $options;

	/**
	 * Start up.
	 */
	public function __construct(){
		
		require_once dirname(__FILE__) . '/admin-dyn-variables-helper.php'; //Dynamic variables

		add_action('admin_menu', [$this, 'add_plugin_page'], 10);
		add_action('admin_init', [$this, 'set_default_values'], 10);
		add_action('admin_init', [$this, 'page_init']);
		add_action('admin_init', [$this, 'siteseo_feature_save'], 30);
		add_action('admin_init', [$this, 'siteseo_feature_title'], 20);
		add_action('admin_init', [$this, 'pre_save_options'], 50);
	}

	public function siteseo_feature_save(){
		
		$html = '';

		if (isset($_GET['settings-updated']) && 'true' === $_GET['settings-updated']) {
			$html .= '<div id="siteseo-notice-save" class="siteseo-components-snackbar-list">';
		} else {
			$html .= '<div id="siteseo-notice-save" class="siteseo-components-snackbar-list">';
		}
		
		$html .= '<div class="siteseo-components-snackbar">
				<div class="siteseo-components-snackbar__content">
					<span class="dashicons dashicons-yes"></span>
					' . esc_html__('Your settings have been saved.', 'siteseo') . '
				</div>
			</div>
		</div>';

		return $html;
	}

	public function siteseo_feature_title($feature){
		global $title;

		$html = '<h1>' . esc_html($title) .' - '. __('SiteSEO', 'siteseo');

		if (null !== $feature) {
			if ('1' == siteseo_get_toggle_option($feature)) {
				$toggle = '1';
			} else {
				$toggle = '0';
			}

			$html .= '<input type="checkbox" name="toggle-' . esc_attr($feature) . '" id="toggle-' . esc_attr($feature) . '" class="toggle" data-toggle="' . esc_attr($toggle) . '">';
			$html .= '<label for="toggle-' . esc_attr($feature) . '"></label>';

			$html .= $this->siteseo_feature_save();

			if ('1' == siteseo_get_toggle_option($feature)) {
				$html .= '<span id="titles-state-default" class="feature-state">' . __('Enabled', 'siteseo') . '</span>';
				$html .= '<span id="titles-state" class="feature-state feature-state-off">' . __('Disabled', 'siteseo') . '</span>';
			} else {
				$html .= '<span id="titles-state-default" class="feature-state">' . __('Disabled', 'siteseo') . '</span>';
				$html .= '<span id="titles-state" class="feature-state feature-state-off">' . __('Enabled', 'siteseo') . '</span>';
			}
		}

		$html .= '</h1>';

		return $html;
	}

	/**
	 * Add options page.
	 */
	public function add_plugin_page(){
		
		if (has_filter('siteseo_seo_admin_menu')) {
			$siteseo_seo_admin_menu['icon'] = '';
			$siteseo_seo_admin_menu['icon'] = apply_filters('siteseo_seo_admin_menu', $siteseo_seo_admin_menu['icon']);
		} else {
			$siteseo_seo_admin_menu['icon'] = SITESEO_ASSETS_DIR.'/img/logo-24.svg';
		}

		$siteseo_seo_admin_menu['title'] = __('SiteSEO', 'siteseo');
		if (has_filter('siteseo_seo_admin_menu_title')) {
			$siteseo_seo_admin_menu['title'] = apply_filters('siteseo_seo_admin_menu_title', $siteseo_seo_admin_menu['title']);
		}		

		add_menu_page(__('SiteSEO Option Page', 'siteseo'), $siteseo_seo_admin_menu['title'], siteseo_capability('manage_options', 'menu'), 'siteseo', [$this, 'create_admin_page'], $siteseo_seo_admin_menu['icon'], 90);
		
		add_submenu_page('siteseo', __('Dashboard', 'siteseo'), __('Dashboard', 'siteseo'), siteseo_capability('manage_options', 'menu'), 'siteseo', [$this, 'create_admin_page']);
		
		$siteseo_titles_help_tab = add_submenu_page('siteseo', __('Titles & Metas', 'siteseo'), __('Titles & Metas', 'siteseo'), siteseo_capability('manage_options', PagesAdmin::TITLE_METAS), 'siteseo-titles', [$this, 'siteseo_titles_page']);
		
		$siteseo_xml_sitemaps_help_tab = add_submenu_page('siteseo', __('Sitemaps', 'siteseo'), __('Sitemaps', 'siteseo'), siteseo_capability('manage_options', PagesAdmin::XML_HTML_SITEMAP), 'siteseo-xml-sitemap', [$this, 'siteseo_xml_sitemap_page']);
		
		$siteseo_social_networks_help_tab = add_submenu_page('siteseo', __('Social Networks', 'siteseo'), __('Social Networks', 'siteseo'), siteseo_capability('manage_options', PagesAdmin::SOCIAL_NETWORKS), 'siteseo-social', [$this, 'siteseo_social_page']);
		
		$siteseo_google_analytics_help_tab = add_submenu_page('siteseo', __('Analytics', 'siteseo'), __('Analytics', 'siteseo'), siteseo_capability('manage_options', PagesAdmin::ANALYTICS), 'siteseo-google-analytics', [$this, 'siteseo_google_analytics_page']);
		
		add_submenu_page('siteseo', __('Instant Indexing', 'siteseo'), __('Instant Indexing', 'siteseo'), siteseo_capability('manage_options', PagesAdmin::INSTANT_INDEXING), 'siteseo-instant-indexing', [$this, 'siteseo_instant_indexing_page']);
		
		add_submenu_page('siteseo', __('Image SEO & Advanced settings', 'siteseo'), __('Advanced', 'siteseo'), siteseo_capability('manage_options', PagesAdmin::ADVANCED), 'siteseo-advanced', [$this, 'siteseo_advanced_page']);
		
		add_submenu_page('siteseo', __('Tools', 'siteseo'), __('Tools', 'siteseo'), siteseo_capability('manage_options', PagesAdmin::TOOLS), 'siteseo-import-export', [$this, 'siteseo_import_export_page']);
		
		// Universal Meta Box Settings Page
		add_submenu_page('admin.php', __('Universal Meta Box'), __('Universal Meta Box'), 'edit_posts', 'siteseo-metabox-wizard', [$this, 'siteseo_metabox_wizard']);

		if (function_exists('siteseo_get_toggle_white_label_option')) {
			$white_label_toggle = siteseo_get_toggle_white_label_option();
			if ('1' === $white_label_toggle) {
				if (function_exists('siteseo_white_label_help_links_option') && '1' === siteseo_white_label_help_links_option()) {
					return;
				}
			}
		}
	}

	// Universal Meta Box Settings Page
	public function siteseo_metabox_wizard(){
		require_once(SITESEO_MAIN.'/admin/metaboxes/admin-universal-metaboxes.php');
	}

	//Admin Pages
	public function siteseo_titles_page(){
		require_once dirname(__FILE__) . '/admin-pages/Titles.php';
	}

	public function siteseo_xml_sitemap_page(){
		require_once dirname(__FILE__) . '/admin-pages/Sitemaps.php';
	}

	public function siteseo_social_page(){
		require_once dirname(__FILE__) . '/admin-pages/Social.php';
	}

	public function siteseo_google_analytics_page(){
		require_once dirname(__FILE__) . '/admin-pages/Analytics.php';
	}

	public function siteseo_advanced_page(){
		require_once dirname(__FILE__) . '/admin-pages/Advanced.php';
	}

	public function siteseo_import_export_page(){
		require_once dirname(__FILE__) . '/admin-pages/Tools.php';
	}

	public function siteseo_instant_indexing_page(){
		require_once dirname(__FILE__) . '/admin-pages/InstantIndexing.php';
	}

	public function create_admin_page(){
		require_once dirname(__FILE__) . '/admin-pages/Main.php';
	}

	public function set_default_values(){
		
		if (defined('SITESEO_WPMAIN_VERSION')) {
			return;
		}

		// IndewNow
		$siteseo_instant_indexing_option_name = get_option('siteseo_instant_indexing_option_name');

		// Init if option doesn't exist
		if (false === $siteseo_instant_indexing_option_name) {
			$siteseo_instant_indexing_option_name = [];

			if ('1' == siteseo_get_toggle_option('instant-indexing')) {
				siteseo_instant_indexing_generate_api_key_fn(true);
			}

			$siteseo_instant_indexing_option_name['instant_indexing_automate_submission'] = '1';
		}

		// Check if the value is an array (important!)
		if (is_array($siteseo_instant_indexing_option_name)) {
			add_option('siteseo_instant_indexing_option_name', $siteseo_instant_indexing_option_name);
		}
	}

	public function page_init(){

		register_setting(
			'siteseo_option_group', // Option group
			'siteseo_option_name', // Option name
			[$this, 'sanitize'] // Sanitize
		);

		register_setting(
			'siteseo_titles_option_group', // Option group
			'siteseo_titles_option_name', // Option name
			[$this, 'sanitize'] // Sanitize
		);

		register_setting(
			'siteseo_xml_sitemap_option_group', // Option group
			'siteseo_xml_sitemap_option_name', // Option name
			[$this, 'sanitize'] // Sanitize
		);

		register_setting(
			'siteseo_social_option_group', // Option group
			'siteseo_social_option_name', // Option name
			[$this, 'sanitize'] // Sanitize
		);

		register_setting(
			'siteseo_google_analytics_option_group', // Option group
			'siteseo_google_analytics_option_name', // Option name
			[$this, 'sanitize'] // Sanitize
		);

		register_setting(
			'siteseo_advanced_option_group', // Option group
			'siteseo_advanced_option_name', // Option name
			[$this, 'sanitize'] // Sanitize
		);

		register_setting(
			'siteseo_tools_option_group', // Option group
			'siteseo_tools_option_name', // Option name
			[$this, 'sanitize'] // Sanitize
		);

		register_setting(
			'siteseo_import_export_option_group', // Option group
			'siteseo_import_export_option_name', // Option name
			[$this, 'sanitize'] // Sanitize
		);

		register_setting(
			'siteseo_instant_indexing_option_group', // Option group
			'siteseo_instant_indexing_option_name', // Option name
			[$this, 'sanitize'] // Sanitize
		);
	}

	public function sanitize($input){

		require_once dirname(__FILE__) . '/sanitize/Sanitize.php';

		if(isset($_POST['option_page']) && $_POST['option_page'] === 'siteseo_advanced_option_group'){
			if(!isset($input['siteseo_advanced_option_group'])){
				$input['siteseo_advanced_option_group'] = '';
			}
		}

		return siteseo_sanitize_options_fields($input);
	}

	public function pre_save_options(){
		add_filter( 'pre_update_option_siteseo_instant_indexing_option_name', [$this, 'pre_siteseo_instant_indexing_option_name'], 10, 2 );
	}

	public function pre_siteseo_instant_indexing_option_name( $new_value, $old_value ){
		//If we are saving data from SEO, PRO, Google Search Console tab, we have to save all Indexing options!
		if (!array_key_exists('instant_indexing_bing_api_key', $new_value)) {
			$options = get_option('siteseo_instant_indexing_option_name');
			$options['instant_indexing_google_api_key'] = $new_value['instant_indexing_google_api_key'];
			return $options;
		}
		return $new_value;
	}
}

/////////////////////////////////
//Loads the JS/CSS in admin
/////////////////////////////////
add_action('admin_enqueue_scripts', 'siteseo_admin_enqueue_scripts', 10, 1);
function siteseo_admin_enqueue_scripts() {
	
	$option_page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
	$current_file = basename(sanitize_text_field(wp_unslash($_SERVER['PHP_SELF'])));
	
	if(!preg_match('/^siteseo/is', $option_page) && !in_array($current_file, array('post.php', 'post-new.php', 'term.php', 'edit-tags.php'))){
		return;
	}
	
	$prefix = '';
	wp_register_style('siteseo-admin', SITESEO_DIR_URL.'/assets/css/siteseo' . $prefix . '.css', [], SITESEO_VERSION);
	wp_enqueue_style('siteseo-admin');

	if (!empty($_GET['page']) && 'siteseo-network-option' === $_GET['page']) {
		wp_enqueue_script('siteseo-network-tabs', SITESEO_DIR_URL.'/assets/js/siteseo-network-tabs' . $prefix . '.js', ['jquery'], SITESEO_VERSION, true);
	}

	//Toggle / Notices JS
	$_pages = [
		'siteseo' => true,
		'siteseo-network-option' => true,
		'siteseo-titles' => true,
		'siteseo-xml-sitemap' => true,
		'siteseo-social' => true,
		'siteseo-google-analytics' => true,
		'siteseo-pro-page' => true,
		'siteseo-instant-indexing' => true,
		'siteseo-advanced' => true,
		'siteseo-import-export' => true,
		'siteseo-bot-batch' => true,
		'siteseo-license' => true,
	];

	if (isset($_pages[siteseo_opt_get('page')])) {
		wp_enqueue_script('siteseo-toggle-ajax', SITESEO_DIR_URL.'/assets/js/siteseo-dashboard' . $prefix . '.js', ['jquery', 'jquery-ui-sortable'], SITESEO_VERSION, true);

		//Features
		$siteseo_toggle_features = [
			'siteseo_nonce'		   => wp_create_nonce('siteseo_toggle_features_nonce'),
			'siteseo_toggle_features' => admin_url('admin-ajax.php'),
			'i18n'					 => __('has been successfully updated!', 'siteseo'),
		];
		wp_localize_script('siteseo-toggle-ajax', 'siteseoAjaxToggleFeatures', $siteseo_toggle_features);

		//Drag and drop
		$siteseo_dnd_features = [
			'siteseo_nonce' => wp_create_nonce('siteseo_dnd_features_nonce'),
			'siteseo_dnd_features' => admin_url('admin-ajax.php'),
		];
		wp_localize_script('siteseo-toggle-ajax', 'siteseoAjaxDndFeatures', $siteseo_dnd_features);
	}
	unset($_pages);

	if (!empty($_GET['page']) && 'siteseo' === $_GET['page']) {
		//Notices
		$siteseo_hide_notices = [
			'siteseo_nonce'		=> wp_create_nonce('siteseo_hide_notices_nonce'),
			'siteseo_hide_notices' => admin_url('admin-ajax.php'),
		];
		wp_localize_script('siteseo-toggle-ajax', 'siteseoAjaxHideNotices', $siteseo_hide_notices);

		//News panel
		$siteseo_news = [
			'siteseo_nonce'		=> wp_create_nonce('siteseo_news_nonce'),
			'siteseo_news'		 => admin_url('admin-ajax.php'),
		];
		wp_localize_script('siteseo-toggle-ajax', 'siteseoAjaxNews', $siteseo_news);

		//Display panel
		$siteseo_display = [
			'siteseo_nonce'		=> wp_create_nonce('siteseo_display_nonce'),
			'siteseo_display'	  => admin_url('admin-ajax.php'),
		];
		wp_localize_script('siteseo-toggle-ajax', 'siteseoAjaxDisplay', $siteseo_display);

		// Admin Tabs
		wp_enqueue_script('siteseo-reverse-ajax', SITESEO_DIR_URL.'/assets/js/siteseo-tabs' . $prefix . '.js', ['jquery-ui-tabs'], SITESEO_VERSION);
	}

	// Migration
	if (!empty($_GET['page']) && ('siteseo' === $_GET['page'] || 'siteseo-import-export' === $_GET['page'])) {
		wp_enqueue_script('siteseo-migrate-ajax', SITESEO_DIR_URL.'/assets/js/siteseo-migrate' . $prefix . '.js', ['jquery'], SITESEO_VERSION, true);

		$siteseo_migrate = [
			'siteseo_aio_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_aio_migrate_nonce'),
				'siteseo_aio_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_yoast_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_yoast_migrate_nonce'),
				'siteseo_yoast_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_seo_framework_migrate'	=> [
				'siteseo_nonce' => wp_create_nonce('siteseo_seo_framework_migrate_nonce'),
				'siteseo_seo_framework_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_rk_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_rk_migrate_nonce'),
				'siteseo_rk_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_squirrly_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_squirrly_migrate_nonce'),
				'siteseo_squirrly_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_seo_ultimate_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_seo_ultimate_migrate_nonce'),
				'siteseo_seo_ultimate_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_wp_meta_seo_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_meta_seo_migrate_nonce'),
				'siteseo_wp_meta_seo_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_premium_seo_pack_migrate'	=> [
				'siteseo_nonce' => wp_create_nonce('siteseo_premium_seo_pack_migrate_nonce'),
				'siteseo_premium_seo_pack_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_wpseo_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_wpseo_migrate_nonce'),
				'siteseo_wpseo_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_platinum_seo_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_platinum_seo_migrate_nonce'),
				'siteseo_platinum_seo_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_smart_crawl_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_smart_crawl_migrate_nonce'),
				'siteseo_smart_crawl_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_seopressor_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_seopressor_migrate_nonce'),
				'siteseo_seopressor_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_slim_seo_migrate' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_slim_seo_migrate_nonce'),
				'siteseo_slim_seo_migration' => admin_url('admin-ajax.php'),
			],
			'siteseo_metadata_csv' => [
				'siteseo_nonce' => wp_create_nonce('siteseo_export_csv_metadata_nonce'),
				'siteseo_metadata_export' => admin_url('admin-ajax.php'),
			],
			'i18n' => [
				'migration' => __('Migration completed!', 'siteseo'),
				'video' => __('Regeneration completed!', 'siteseo'),
				'export' => __('Export completed!', 'siteseo'),
			],
		];
		wp_localize_script('siteseo-migrate-ajax', 'siteseoAjaxMigrate', $siteseo_migrate);

		// Force regenerate video xml sitemap
		$siteseo_video_regenerate = [
			'siteseo_nonce' => wp_create_nonce('siteseo_video_regenerate_nonce'),
			'siteseo_video_regenerate' => admin_url('admin-ajax.php'),
		];
		wp_localize_script('siteseo-migrate-ajax', 'siteseoAjaxVdeoRegenerate', $siteseo_video_regenerate);
	}

	// Tabs
	if (!empty($_GET['page']) && ('siteseo-titles' === $_GET['page'] || 'siteseo-xml-sitemap' === $_GET['page'] || 'siteseo-social' === $_GET['page'] || 'siteseo-google-analytics' === $_GET['page'] || 'siteseo-advanced' === $_GET['page'] || 'siteseo-import-export' === $_GET['page'] || 'siteseo-instant-indexing' === $_GET['page'] || 'siteseo-insights-settings' === $_GET['page'])) {
		wp_enqueue_script('siteseo-admin-tabs-js', SITESEO_DIR_URL.'/assets/js/siteseo-tabs' . $prefix . '.js', ['jquery-ui-tabs'], SITESEO_VERSION);
	}

	if (!empty($_GET['page']) && ('siteseo-xml-sitemap' === $_GET['page'] || 'siteseo-pro-page' === $_GET['page'] || 'siteseo-network-option' === $_GET['page'])) {
		wp_enqueue_script('siteseo-xml-ajax', SITESEO_DIR_URL.'/assets/js/siteseo-sitemap-ajax' . $prefix . '.js', ['jquery'], SITESEO_VERSION, true);

		$siteseo_ajax_permalinks = [
			'siteseo_nonce'			=> wp_create_nonce('siteseo_flush_permalinks_nonce'),
			'siteseo_ajax_permalinks' 	=> admin_url('admin-ajax.php'),
		];
		wp_localize_script('siteseo-xml-ajax', 'siteseoAjaxResetPermalinks', $siteseo_ajax_permalinks);
	}

	if (!empty($_GET['page']) && 'siteseo-google-analytics' === $_GET['page']) {
		wp_enqueue_style('wp-color-picker');

		wp_enqueue_script('wp-color-picker-alpha', SITESEO_DIR_URL.'/assets/js/wp-color-picker-alpha.min.js', ['wp-color-picker'], SITESEO_VERSION, true);
		$color_picker_strings = [
			'clear'			=> __('Clear', 'siteseo'),
			'clearAriaLabel'   => __('Clear color', 'siteseo'),
			'defaultString'	=> __('Default', 'siteseo'),
			'defaultAriaLabel' => __('Select default color', 'siteseo'),
			'pick'			 => __('Select Color', 'siteseo'),
			'defaultLabel'	 => __('Color value', 'siteseo'),
		];
		wp_localize_script('wp-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings);
	}

	if (!empty($_GET['page']) && 'siteseo-social' === $_GET['page']) {
		wp_enqueue_script('siteseo-media-uploader-js', SITESEO_DIR_URL.'/assets/js/siteseo-media-uploader' . $prefix . '.js', ['jquery'], SITESEO_VERSION, false);
		wp_enqueue_media();
	}

	// Instant Indexing
	if (!empty($_GET['page']) && 'siteseo-instant-indexing' === $_GET['page']) {
		$siteseo_instant_indexing_post = [
			'siteseo_nonce'			   => wp_create_nonce('siteseo_instant_indexing_post_nonce'),
			'siteseo_instant_indexing_post' => admin_url('admin-ajax.php'),
		];
		wp_localize_script('siteseo-admin-tabs-js', 'siteseoAjaxInstantIndexingPost', $siteseo_instant_indexing_post);

		$siteseo_instant_indexing_generate_api_key = [
			'siteseo_nonce'			   => wp_create_nonce('siteseo_instant_indexing_generate_api_key_nonce'),
			'siteseo_instant_indexing_generate_api_key' => admin_url('admin-ajax.php'),
		];
		wp_localize_script('siteseo-admin-tabs-js', 'siteseoAjaxInstantIndexingApiKey', $siteseo_instant_indexing_generate_api_key);
	}

	// CSV Importer
	if (!empty($_GET['page']) && 'siteseo_csv_importer' === $_GET['page']) {
		wp_enqueue_style('siteseo-setup', SITESEO_DIR_URL.'/assets/css/setup' . $prefix . '.css', ['dashicons'], SITESEO_VERSION);
	}
	
}

// SiteSEO post meta page view handler
add_action('admin_enqueue_scripts', 'siteseo_post_meta_page');
function siteseo_post_meta_page() {
	
	// Set Current screen
	$screen = get_current_screen();
	$meta_id = 'admin_page_siteseo-metabox-wizard';
	
	if( (!empty(get_the_ID()) && !current_user_can('edit_post', get_the_ID())) || trim($screen->id) != $meta_id ) {
		return;
	}
	
	if(!isset($_REQUEST['post'])){
		return;		
	}
	
	// Remove all the notice hooks
	remove_all_actions('admin_notices');
	remove_all_actions('all_admin_notices');

}

// SITESEO Admin bar
function siteseo_admin_bar_css() {
	$prefix = '';
	if (is_user_logged_in() && function_exists('siteseo_advanced_appearance_adminbar_option') && '1' != siteseo_advanced_appearance_adminbar_option()) {
		if (is_admin_bar_showing()) {
			wp_register_style('siteseo-admin-bar', SITESEO_DIR_URL.'assets/css/admin-bar' . $prefix . '.css', [], SITESEO_VERSION);
			wp_enqueue_style('siteseo-admin-bar');
		}
	}
}
add_action('init', 'siteseo_admin_bar_css', 12, 1);

// Admin Body Class
add_filter('admin_body_class', 'siteseo_admin_body_class', 100);
function siteseo_admin_body_class($classes) {
	
	if(! isset($_GET['page'])){
		return $classes;
	}
	
	$_pages = [
		'siteseo_csv_importer' => true,
		'siteseo' => true,
		'siteseo-network-option' => true,
		'siteseo-titles' => true,
		'siteseo-xml-sitemap' => true,
		'siteseo-social' => true,
		'siteseo-google-analytics' => true,
		'siteseo-advanced' => true,
		'siteseo-import-export' => true,
		'siteseo-pro-page' => true,
		'siteseo-instant-indexing' => true,
		'siteseo-bot-batch' => true,
		'siteseo-license' => true
	];
	
	if(isset($_pages[siteseo_opt_get('page')])){
		$classes .= ' siteseo-styles ';
	}
	
	if(isset($_pages[siteseo_opt_get('page')]) && 'siteseo_csv_importer' === $_GET['page']){
		$classes .= ' siteseo-setup ';
	}

	return $classes;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Shortcut settings page
///////////////////////////////////////////////////////////////////////////////////////////////////
add_filter('plugin_action_links', 'siteseo_plugin_action_links', 10, 2);

function siteseo_plugin_action_links($links, $file) {
	static $this_plugin;
	
	if(!$this_plugin){
		$this_plugin = plugin_basename(SITESEO_FILE);
	}
	
	if($file != $this_plugin) {
		return $links;
	}
	
	$settings_link = '<a href="' . admin_url('admin.php?page=siteseo') . '">' . __('Settings', 'siteseo') . '</a>';
	
	$website_link = '<a href="'.SITESEO_DOCS.'" target="_blank">' . __('Docs', 'siteseo') . '</a>';
	
	$wizard_link = '<a href="' . admin_url('admin.php?page=siteseo-setup') . '">' . __('Configuration', 'siteseo') . '</a>';
	
	if( ! is_plugin_active('siteseo-pro/siteseo-pro.php')) {
		$pro_link = '<a href="'.SITESEO_WEBSITE.'siteseo-pro/" style="color:red;font-weight:bold" target="_blank">' . __('Go Pro', 'siteseo') . '</a>';
		array_unshift($links, $pro_link);
	}
	
	if(is_plugin_active('siteseo-pro/siteseo-pro.php')) {
		if (array_key_exists('deactivate', $links) && in_array($file, [
			'siteseo/siteseo.php',
		]));
		// TODO: we need this?
		//unset($links['deactivate']);
	}

	if(function_exists('siteseo_get_toggle_white_label_option') && '1' == siteseo_get_toggle_white_label_option() && function_exists('siteseo_white_label_help_links_option') && '1' === siteseo_white_label_help_links_option()) {
		array_unshift($links, $settings_link, $wizard_link);
	}else{
		array_unshift($links, $settings_link, $wizard_link, $website_link);
	}

	return $links;
}

if (is_admin()) {
	$my_settings_page = new siteseo_options();
}

/**
 * Automatically flush permalinks after saving XML sitemaps global settings
 * @since 1.0.0
 *
 * @param string $option
 * @param string $old_value
 * @param string $value
 *
 * @return void
 */
add_action('updated_option', function( $option, $old_value, $value ) {
	if ($option ==='siteseo_xml_sitemap_option_name') {
		flush_rewrite_rules(false);
	}
}, 10, 3);
