<?php

/**
 * @since      3.0.1
 * @package    DirectoryPress
 * @subpackage DirectoryPress/includes
 * @author     Designinvento <developers@designinvento.net>
 */
class DirectoryPress {
	protected $loader;
	protected $plugin_name;
	protected $version;
	public $admin;
	public $listings_handler_property;
	public $locations_handler;
	public $locations_depths_manager;
	public $terms_validator;
	public $fields_handler_property;
	public $media_handler_property;
	public $settings_manager;
	public $directorytypes_manager; // remove in free version
	public $demo_data_manager;
	public $packages_manager;
	public $csv_manager;
	public $updater; // remove in free version

	public $current_directorytype = null;
	public $directorytypes;
	public $current_listing; // this is object of listing under edition right now
	public $packages;
	public $locations_depths;
	public $fields;
	public $search_fields;
	public $ajax_handler;
	public $directorypress_archive_page_id;
	public $directorypress_archive_slug;
	public $directorypress_archive_page_url;
	public $directorypress_all_archive_pages = array();
	public $directorypress_all_listing_pages = array();
	public $directorypress_defult_archive_page_id;
	public $listing_page_id;
	public $listing_page_slug;
	public $directorypress_post_Page_url;
	public $public_handlers = array();
	public $_public_handlers = array(); // this duplicate property needed because we unset each handler when we render shortcodes, but WP doesn't really know which shortcode already was processed
	public $action;
	
	public $radius_values_array = array();
	
	public $order_by_date = false; // special flag, used to display or hide has_sticky pin
	public function __construct() {
		if ( defined( 'DIRECTORYPRESS_VERSION' ) ) {
			$this->version = DIRECTORYPRESS_VERSION;
		}else{
			$this->version = '3.6.7';
		}
		$this->plugin_name = 'directorypress';
		define("DIRECTORYPRESS_OPTIONS_BUILD", $this->plugin_name . '_dirctorypress_options_build');
		$this->load_dependencies();
		$this->set_locale();
		//$this->define_admin_hooks();
		$this->define_public_hooks();

	}
	
	private function load_dependencies() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/includes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/register-shortcodes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/register-widgets.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/minify/dynamic-helper.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/minify/dynamic.php';
		$this->loader = new DirectoryPress_Loader();

	}
	
	private function set_locale() {

		$plugin_i18n = new DirectoryPress_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	
	private function define_admin_hooks() {

		$plugin_admin = new DirectoryPress_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}
	
	private function define_public_hooks() {

		$plugin_public = new DirectoryPress_Public_Handler( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}
	
	public function run() {
		global $directorypress_object, $directorypress_shortcodes, $directorypress_google_maps_styles, $wpdb;

		if (isset($_REQUEST['directorypress_action'])) {
			$this->action = sanitize_text_field($_REQUEST['directorypress_action']);
		}

		add_action('plugins_loaded', array($this, 'load_textdomains'));

		if (!isset($wpdb->directorypress_fields))
			$wpdb->directorypress_fields = $wpdb->prefix . 'directorypress_fields';
		if (!isset($wpdb->directorypress_fields_groups))
			$wpdb->directorypress_fields_groups = $wpdb->prefix . 'directorypress_fields_groups';
		if (!isset($wpdb->directorypress_directorytypes))
			$wpdb->directorypress_directorytypes = $wpdb->prefix . 'directorypress_directorytypes';
		if (!isset($wpdb->directorypress_packages))
			$wpdb->directorypress_packages = $wpdb->prefix . 'directorypress_packages';
		if (!isset($wpdb->directorypress_packages_relation))
			$wpdb->directorypress_packages_relation = $wpdb->prefix . 'directorypress_packages_relation';
		if (!isset($wpdb->directorypress_locations_depths))
			$wpdb->directorypress_locations_depths = $wpdb->prefix . 'directorypress_locations_depths';
		if (!isset($wpdb->directorypress_locations_relation))
			$wpdb->directorypress_locations_relation = $wpdb->prefix . 'directorypress_locations_relation';

		add_action('sheduled_events', array($this, 'directorypress_draft_listing_on_expiry'));
		
		foreach ($directorypress_shortcodes AS $shortcode=>$function) {
			add_shortcode($shortcode, array($this, 'directorypress_shortcode_display'));
		}
		add_action('init', 'directorypress_init_session');
		add_action('init', 'directorypress_register_post_type', 0);
		add_action('init', array($this, 'directorypress_get_system_pages'), 1);
		add_action('wp', array($this, 'directorypress_init_directorytypes_pages'), 1);
		add_action('admin_init', array($this, 'directorypress_init_directorytypes_pages'), 1);
		add_action('init', array($this, 'redux_include'), 0);
		add_action('plugins_loaded', array($this, 'remove_account_navigation'), 10);
		
		
		add_action('wp', array($this, 'directorypress_init_public_handler'), 1);

		// needs workaround, sheduled_events not working
		add_action('wp', array($this, 'directorypress_draft_listing_on_expiry_call'), 1);
		
		if (!get_option('directorypress_installed_directory')) {
			//$this->directorypress_init_classes();
			add_action('init', 'directorypress_install_directory', 0);
		} else {
			$this->directorypress_init_classes();
		}
		
		add_action('wp', array($this, 'wp_loaded'));
		add_filter('query_vars', array($this, 'add_query_vars'));
		add_filter('rewrite_rules_array', array($this, 'rewrite_rules'));
		
		add_filter('redirect_canonical', array($this, 'directorypress_stop_invalid_redirection'), 10, 2);
		add_filter('post_type_link', array($this, 'directorypress_post_permalinks'), 10, 3);
		add_filter('term_link', array($this, 'category_permalink'), 10, 3);
		add_filter('term_link', array($this, 'location_permalink'), 10, 3);
		add_filter('term_link', array($this, 'tag_permalink'), 10, 3);
		
		// adapted for Polylang
		add_action('init', array($this, 'pll_setup'));

		add_filter('comments_open', array($this, 'filter_comment_status'), 100, 2);
		
		add_filter('wp_unique_post_slug_is_bad_flat_slug', array($this, 'reserve_slugs'), 10, 2);
		
		add_filter('no_texturize_shortcodes', array($this, 'directorypress_no_texturize'));

		add_filter('wpseo_sitemap_post_type_archive_link', array($this, 'exclude_post_type_archive_link'), 10, 2);
		
		$directorypress_google_maps_styles = apply_filters('directorypress_google_maps_styles', $directorypress_google_maps_styles);

		add_filter( 'body_class', array($this, 'directorypress_body_classes'));
		//var_dump($directorypress_object->public_handlers);
		$this->loader->run();
		
	}
	public function remove_account_navigation(){
		$remove_navigation = remove_action('woocommerce_account_navigation', 'woocommerce_account_navigation', 10);
		
		return $remove_navigation;
	}
	public function directorypress_body_classes($classes){
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		if(directorypress_is_listing_page()){
			if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style'] == 1){
				$classes[] = 'directorypress-single-default-style';
			}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style'] == 2){
				$classes[] = 'directorypress-single-radius-style';
			}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_listing_style'] == 3){
				$classes[] = 'directorypress-single-directory-style';
			}
			if(wp_is_mobile()){
				$classes[] = 'directorypress-single-mobile';
			}
		}
		if(directorypress_is_archive_page()){
			if($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 3 || $DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 4){
				$classes[] = 'directorypress-archive_sticky_map';
			}
		}
		
		return $classes;
	}
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	
	public function get_loader() {
		return $this->loader;
	}
	
	public function get_version() {
		return $this->version;
	}
	
	public function redux_include() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/redux-core/framework.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/redux-core/directorypress-settings.php';
	}
	
	public function load_textdomains() {
		load_plugin_textdomain('DIRECTORYPRESS', '', dirname(plugin_basename( __FILE__ )) . '/languages');
	}
	
	public function directorypress_init_classes() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'elementorWidgetCategories'] );
		$this->directorytypes = new directorypress_directorytypes;
		$this->packages = new directorypress_packages;
		$this->locations_depths = new directorypress_locations_depths;
		$this->fields = new directorypress_fields;
		$this->search_fields = new directorypress_search_fields;
		$this->ajax_handler = new directorypress_ajax;
		$this->admin = new DirectoryPress_Admin;
		$this->listings_packages = new directorypress_listings_packages;
		$this->author_profile = new DirectoryPress_ProfilePage;
		do_action( 'directorypress_loaded' );
		do_action( 'directorypress_after_loaded' );
	}
	
	public function elementorWidgetCategories( $elements_manager ) {

		$elements_manager->add_category(
			'directorypress',
			[
				'title' => __( 'DirectoryPress', 'plugin-name' ),
				'icon' => 'fa fa-plug',
			]
		);

	}
	public function register_widgets() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/directorypress-listing.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/directorypress-categories.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/directorypress-locations.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/directorypress-main.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/directorypress-terms.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/directorypress-search.php';

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register( new DirectoryPress_Elementor_Listing_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new DirectoryPress_Elementor_Category_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new DirectoryPress_Elementor_Location_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new DirectoryPress_Elementor_Main_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new DirectoryPress_Elementor_Search_Widget() );
		\Elementor\Plugin::instance()->widgets_manager->register( new DirectoryPress_Elementor_Terms_Widget() );

	}
	
	public function directorypress_no_texturize($shortcodes) {
		global $directorypress_shortcodes;
		
		foreach ($directorypress_shortcodes AS $shortcode=>$function)
			$shortcodes[] = $shortcode;
		
		return $shortcodes;
	}

	public function directorypress_shortcode_display() {
		
		if (!is_admin()) {
			global $directorypress_shortcodes;
			
			$filters_to_remove = array(
					'wpautop',
					'wptexturize',
					'shortcode_unautop',
					'convert_chars',
					'prepend_attachment',
					'convert_smilies',
			);
			foreach ($filters_to_remove AS $filter) {
				while (($priority = has_filter('the_content', $filter)) !== false) {
					remove_filter('the_content', $filter, $priority);
				}
			}
	
			$attrs = func_get_args();
			$shortcode = $attrs[2];
	
			$filters_where_not_to_display = array(
					'wp_head',
					'init',
					'wp',
					'edit_attachment',
			);
			
			//var_dump(current_filter());
			if (isset($this->_public_handlers[$shortcode]) && !in_array(current_filter(), $filters_where_not_to_display)) {
				$shortcode_handlers = $this->_public_handlers[$shortcode];
				foreach ($shortcode_handlers AS $key=>&$directorypress_handler) {
					unset($this->_public_handlers[$shortcode][$key]); // there are possible more than 1 same shortcodes on a page, so we have to unset which already was displayed
					if (method_exists($directorypress_handler, 'display'))
						return $directorypress_handler->display();
				}
			}
	
			if (isset($directorypress_shortcodes[$shortcode])) {
				$shortcode_class = $directorypress_shortcodes[$shortcode];
				if ($attrs[0] === '')
					$attrs[0] = array();
				$shortcode_instance = new $shortcode_class();
				$this->public_handlers[$shortcode][] = $shortcode_instance;
				$shortcode_instance->init($attrs[0], $shortcode);
	
				if (method_exists($shortcode_instance, 'display'))
					return $shortcode_instance->display();
			}
		}
	}

	public function directorypress_init_public_handler() {
		global $post, $wp_query;

		if ($wp_query->posts) {
			$pattern = get_shortcode_regex();
			foreach ($wp_query->posts AS $archive_post) {
				if (isset($archive_post->post_content))
					$this->directorypress_init_inherit_public_handler($pattern, $archive_post->post_content);
			}
		} elseif ($post && isset($post->post_content)) {
			$pattern = get_shortcode_regex();
			$this->directorypress_init_inherit_public_handler($pattern, $post->post_content);
		}
	}

	public function directorypress_init_inherit_public_handler($pattern, $content) {
		global $directorypress_shortcodes_init, $directorypress_shortcodes;

		if (preg_match_all('/'.$pattern.'/s', $content, $matches) && array_key_exists(2, $matches)) {
			foreach ($matches[2] AS $key=>$shortcode) {
				if ($shortcode != 'shortcodes') {
					if (isset($directorypress_shortcodes_init[$shortcode]) && class_exists($directorypress_shortcodes_init[$shortcode])) {
						$shortcode_class = $directorypress_shortcodes_init[$shortcode];
						if (!($attrs = shortcode_parse_atts($matches[3][$key])))
							$attrs = array();
						$shortcode_instance = new $shortcode_class();
						$this->public_handlers[$shortcode][] = $shortcode_instance;
						$this->_public_handlers[$shortcode][] = $shortcode_instance;
						$shortcode_instance->init($attrs, $shortcode);
					} elseif (isset($directorypress_shortcodes[$shortcode]) && class_exists($directorypress_shortcodes[$shortcode])) {
						$shortcode_class = $directorypress_shortcodes[$shortcode];
						$this->public_handlers[$shortcode][] = $shortcode_class;
					}
					if ($shortcode_content = $matches[5][$key])
						$this->directorypress_init_inherit_public_handler($pattern, $shortcode_content);
				}
			}
		}
	}
	
	public function directorypress_get_system_pages() {
		$this->directorypress_all_archive_pages = directorypress_get_system_pages();
		$this->directorypress_all_listing_pages = directorypress_get_all_listing_related_pages();
	}
	
	public function directorypress_init_directorytypes_pages() {
		$this->directorypress_get_archive_page();
		$this->setup_current_page_directorytype();

		do_action('directorypress_load_pages_directorytypes');
	}

	public function directorypress_get_archive_page() {
		if ($array = directorypress_get_archive_page()) {
			$this->directorypress_archive_page_id = $array['id'];
			$this->directorypress_archive_slug = $array['slug'];
			$this->directorypress_archive_page_url = $array['url'];
		}
		
		if ($array = directorypress_get_listingPage()) {
			$this->listing_page_id = $array['id'];
			$this->listing_page_slug = $array['slug'];
			$this->directorypress_post_Page_url = $array['url'];
		}
	}
	
	public function directorypress_draft_listing_on_expiry_call() {
		$this->directorypress_draft_listing_on_expiry();
	}
	
	public function setup_current_page_directorytype($current_directorytype = null) {
		global $pagenow;

		if (isset($_GET['directorytype']) && is_numeric($_GET['directorytype']) && ($directorytype = $this->directorytypes->directory_by_id($_GET['directorytype']))) {
			$current_directorytype = $directorytype;
		}
		if (is_admin() && $pagenow == 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] == DIRECTORYPRESS_POST_TYPE && isset($_GET['directory_id']) && is_numeric($_GET['directory_id']) && ($directorytype = $this->directorytypes->directory_by_id($_GET['directory_id']))) {
			$current_directorytype = $directorytype;
		}

		if (!$current_directorytype && $this->public_handlers && ($listing = directorypress_is_listing_page())) {
			$current_directorytype = $listing->directorytype;
		}

		if (!$current_directorytype && get_query_var('directorytype-directorypress')) {
			$current_directorytype = $this->directorytypes->directory_by_id(get_query_var('directorytype-directorypress'));
		}

		if (!$current_directorytype) {
			if (($this->directorypress_archive_page_id == get_queried_object_id()) || (wp_doing_ajax() && $this->directorypress_is_ajax_archive_page()) ) {
				$current_directorytype = $this->directorytypes->get_current_page_directory($this->directorypress_archive_page_id);
			}
		}
		if (!$current_directorytype) {
			$current_directorytype = $this->directorytypes->directorypress_get_base_directorytype();
		}
		return ($this->current_directorytype = $current_directorytype);
	}
	
	public function directorypress_is_ajax_archive_page() {
		global $wp_rewrite;

		if ($wp_rewrite->using_permalinks()) {
			if (isset($_REQUEST['base_url'])) {
				$base_url = sanitize_url($_REQUEST['base_url']);
				if (strtok($base_url, '?') == $this->directorypress_archive_page_url) {
					return true;
				}
			}
		} else {
			if (
				isset($_REQUEST['base_url']) && 
				($base_url = wp_parse_args($_REQUEST['base_url'])) &&
				isset($base_url['homepage']) &&
				$base_url['homepage'] == $this->directorypress_archive_page_id
			) {
				return true;
			}
		}
		return false;
	}
	
	public function add_query_vars($vars) {
		$vars[] = 'directorytype-directorypress';
		$vars[] = 'listing-directorypress';
		$vars[] = 'category-directorypress';
		$vars[] = 'location-directorypress';
		$vars[] = 'tag-directorypress';
		$vars[] = 'tax-slugs-directorypress';
		$vars[] = 'homepage';

		if (!is_admin()) {
			$key = array_search('order', $vars);
			unset($vars[$key]);
		}

		return $vars;
	}
	
	public function rewrite_rules($rules) {
		return $this->directorypress_insert_new_rules() + $rules;
	}
	
	public function directorypress_insert_new_rules() {
		$rules = array();
		foreach ($this->directorypress_all_archive_pages AS $page) {
			$this->directorypress_archive_page_id = $page['id'];
			$this->directorypress_archive_slug = $page['slug'];
			$this->directorypress_archive_page_url = get_permalink($page['id']);
				
			
			global $sitepress;
			if (function_exists('wpml_object_id_filter') && $sitepress && ($languages = $sitepress->get_active_languages()) && count($languages) > 1) {
				$this->directorypress_defult_archive_page_id = $this->directorypress_archive_page_id;
				
				foreach ($languages AS $lang_code=>$lang) {
					if ($this->directorypress_archive_page_id = apply_filters('wpml_object_id', $this->directorypress_defult_archive_page_id, 'page', false, $lang_code)) {
						$post = get_post($this->directorypress_archive_page_id);
						$this->directorypress_archive_slug = $post->post_name;
						
	
						$rules = $rules + $this->directorypress_create_rules($lang_code);
					}
				}
			} else {
				$rules = $rules + $this->directorypress_create_rules();
			}
		}
		$this->directorypress_get_archive_page();
		return $rules;
	}
	
	public function directorypress_create_rules($lang_code = '') {
		global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS, $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			if (
			$sitepress->get_setting('language_negotiation_type') == 1 &&
			$lang_code != ICL_LANGUAGE_CODE &&
			get_option('show_on_front') != 'posts' &&
			get_option('page_on_front') == $this->directorypress_defult_archive_page_id
			) {
				return array();
			}
		}
		
		global $wp_rewrite;

		$lang_param = '';
		global $sitepress;
		if ($lang_code && function_exists('wpml_object_id_filter') && $sitepress) {
			if ($sitepress->get_setting('language_negotiation_type') == 3 && $lang_code != $sitepress->get_default_language()) {
				$lang_param = '';
			}
		}

		$page_url = $this->directorypress_archive_slug;

		foreach (get_post_ancestors($this->directorypress_archive_page_id) AS $parent_id) {
			$parent = get_page($parent_id);
			$page_url = $parent->post_name . '/' . $page_url;
		}
		
		$rules['(' . $page_url . ')/' . $wp_rewrite->pagination_base . '/?([0-9]{1,})/?' . $lang_param . '$'] = 'index.php?page_id=' .  $this->directorypress_archive_page_id . '&paged=$matches[2]';
		$rules['(' . $page_url . ')/?' . $lang_param . '$'] = 'index.php?page_id=' .  $this->directorypress_archive_page_id;
		
		$category_page_id = $this->directorypress_archive_page_id;
		$location_page_id = $this->directorypress_archive_page_id;
		$tag_page_id = $this->directorypress_archive_page_id;

		if (!($directorytype = $directorypress_object->directorytypes->get_current_page_directory($this->directorypress_archive_page_id))) {
			$directorytype = $directorypress_object->directorytypes->directorypress_get_base_directorytype();
		}

		if (isset($this->directorypress_all_listing_pages[$directorytype->id])) {
			$listing_page_id = $this->directorypress_all_listing_pages[$directorytype->id];
		} elseif (isset($this->directorypress_all_listing_pages[$this->directorytypes->directorypress_get_base_directorytype()->id])) {
			$listing_page_id = $this->directorypress_all_listing_pages[$this->directorytypes->directorypress_get_base_directorytype()->id];
		} else {
			$listing_page_id = $this->directorypress_archive_page_id;
		}
		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			$listing_page_id = apply_filters('wpml_object_id', $listing_page_id, 'page', true, $lang_code);
		}
		
		$listing_slug = $directorytype->listing_slug;
		$category_slug = $directorytype->category_slug;
		$location_slug = $directorytype->location_slug;
		$tag_slug = $directorytype->tag_slug;

		$rules['(' . $page_url . ')?/?' . $category_slug . '/(.+?)/' . $wp_rewrite->pagination_base . '/?([0-9]{1,})/?' . $lang_param . '$'] = 'index.php?page_id=' .  $category_page_id . '&category-directorypress=$matches[2]&paged=$matches[3]';
		$rules['(' . $page_url . ')?/?' . $category_slug . '/(.+?)/?' . $lang_param . '$'] = 'index.php?page_id=' .  $category_page_id . '&category-directorypress=$matches[2]&directorytype-directorypress=' . $directorytype->id;
		
		$rules['(' . $page_url . ')?/?' . $location_slug . '/(.+?)/' . $wp_rewrite->pagination_base . '/?([0-9]{1,})/?' . $lang_param . '$'] = 'index.php?page_id=' .  $location_page_id . '&location-directorypress=$matches[2]&paged=$matches[3]';
		$rules['(' . $page_url . ')?/?' . $location_slug . '/(.+?)/?' . $lang_param . '$'] = 'index.php?page_id=' .  $location_page_id . '&location-directorypress=$matches[2]&directorytype-directorypress=' . $directorytype->id;
	
		$rules['(' . $page_url . ')?/?' . $tag_slug . '/([^\/.]+)/' . $wp_rewrite->pagination_base . '/?([0-9]{1,})/?' . $lang_param . '$'] = 'index.php?page_id=' .  $tag_page_id . '&tag-directorypress=$matches[2]&paged=$matches[3]';
		$rules['(' . $page_url . ')?/?' . $tag_slug . '/([^\/.]+)/?' . $lang_param . '$'] = 'index.php?page_id=' .  $tag_page_id . '&tag-directorypress=$matches[2]&directorytype-directorypress=' . $directorytype->id;

		
		$rules['(' . $page_url . ')?/?' . $listing_slug . '/(.+?)/([^\/.]+)/?' . $lang_param . '$'] = 'index.php?page_id=' . $listing_page_id . '&tax-slugs-directorypress=$matches[2]&listing-directorypress=$matches[3]';
		$rules['(' . $page_url . ')?/?' . $listing_slug . '/([^\/.]+)/?' . $lang_param . '$'] = 'index.php?page_id=' . $listing_page_id . '&listing-directorypress=$matches[2]';
		
		$rules[$page_url . '/([^\/.]+)/?' . $lang_param . '$'] = 'index.php?page_id=' . $listing_page_id . '&listing-directorypress=$matches[1]';
		if (
			strpos(get_option('permalink_structure'), '/%post_id%/%postname%') === FALSE &&
			strpos(get_option('permalink_structure'), '/%year%/%postname%') === FALSE
		) {
			
			$rules['(' . $page_url . ')?/?(?!(?:199[0-9]|20[012][0-9])/(?:0[1-9]|1[012]))([0-9]+)/([^\/.]+)/?' . $lang_param . '$'] = 'index.php?page_id=' . $listing_page_id . '&listing-directorypress=$matches[3]';
		}
		
		return $rules;
	}
	
	public function wp_loaded() {
		if ($rules = get_option('rewrite_rules'))
			foreach ($this->directorypress_insert_new_rules() as $key=>$value) {
				if (!isset($rules[$key]) || $rules[$key] != $value) {
					global $wp_rewrite;
					$wp_rewrite->flush_rules();
					return;
				}
			}
	}
	
	public function directorypress_stop_invalid_redirection($redirect_url, $requested_url) {
		
		if ($this->public_handlers) {
			
			$user_home = @parse_url(home_url());
			if (!empty($user_home['host'])) {
				if (strpos($user_home['host'], 'www.') === 0) {
					$requested_home = @parse_url($requested_url);
					if (!empty($requested_home['host'])) {
						if (strpos($requested_home['host'], 'www.') !== 0) {
							$requested_url = str_replace($requested_home['host'], 'www.'.$requested_home['host'], $requested_url);
						}
					}
				} else {
					$requested_home = @parse_url($requested_url);
					if (!empty($requested_home['host'])) {
						if (strpos($requested_home['host'], 'www.') === 0) {
							$pos = strpos($requested_url, 'www.');
							$requested_url = substr_replace($requested_url, '', $pos, 4);
						}
					}
				}
			}
			return $requested_url;
		}
	
		return $redirect_url;
	}

	public function directorypress_post_permalinks($permalink, $post, $leavename) {
		if ($post->post_type == DIRECTORYPRESS_POST_TYPE) {
			global $wp_rewrite, $DIRECTORYPRESS_ADIMN_SETTINGS;
			if ($wp_rewrite->using_permalinks()) {
				if ($leavename)
					$postname = '%postname%';
				else
					$postname = $post->post_name;
				
				$post_directorytype = directorypress_directory_type_of_listing($post->ID);
				$listing_slug = $post_directorytype->listing_slug;
				
				if (!$post_directorytype->url)
					return false;

				switch ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_permalinks_structure']) {
					case 'post_id':
						return directorypress_directorytype_url($post->ID . '/' . $postname, $post_directorytype);
						break;
					case 'postname':
						if (get_option('page_on_front') == $this->directorypress_archive_page_id)
							return directorypress_directorytype_url($post->ID . '/' . $postname, $post_directorytype);
						else
							return directorypress_directorytype_url($postname, $post_directorytype);
						break;
					case 'listing_slug':
						if ($listing_slug)
							return directorypress_directorytype_url($listing_slug . '/' . $postname, $post_directorytype);
						else
							if (get_option('page_on_front') == $this->directorypress_archive_page_id)
								return directorypress_directorytype_url($post->ID . '/' . $postname, $post_directorytype);
							else
								return directorypress_directorytype_url($postname, $post_directorytype);
						break;
					case 'category_slug':
						if ($listing_slug && ($terms = get_the_terms($post->ID, DIRECTORYPRESS_CATEGORIES_TAX))) {
							$term = array_shift($terms);
							if ($cur_term = directorypress_get_term_by_path(get_query_var('category-directorypress'))) {
								foreach ($terms AS $lterm) {
									$term_path_ids = directorypress_get_term_parents_ids($lterm->term_id, DIRECTORYPRESS_CATEGORIES_TAX);
									if ($cur_term->term_id == $lterm->term_id) { $term = $lterm; break; }  // exact term much more better
									if (in_array($cur_term->term_id, $term_path_ids)) { $term = $lterm; break; }
								}
							}
							$uri = '';
							if ($parents = directorypress_get_term_parents_slugs($term->term_id, DIRECTORYPRESS_CATEGORIES_TAX))
								$uri = implode('/', $parents);
							return directorypress_directorytype_url($listing_slug . '/' . $uri . '/' . $postname, $post_directorytype);
						} else
							if (get_option('page_on_front') == $this->directorypress_archive_page_id)
								return directorypress_directorytype_url($post->ID . '/' . $postname, $post_directorytype);
							else
								return directorypress_directorytype_url($postname, $post_directorytype);
						break;
					case 'location_slug':
						if ($listing_slug && ($terms = get_the_terms($post->ID, DIRECTORYPRESS_LOCATIONS_TAX)) && ($term = array_shift($terms))) {
							if ($cur_term = directorypress_get_term_by_path(get_query_var('location-directorypress'))) {
								foreach ($terms AS $lterm) {
									$term_path_ids = directorypress_get_term_parents_ids($lterm->term_id, DIRECTORYPRESS_LOCATIONS_TAX);
									if ($cur_term->term_id == $lterm->term_id) { $term = $lterm; break; }  // exact term much more better
									if (in_array($cur_term->term_id, $term_path_ids)) { $term = $lterm; break; }
								}
							}
							$uri = '';
							if ($parents = directorypress_get_term_parents_slugs($term->term_id, DIRECTORYPRESS_LOCATIONS_TAX))
								$uri = implode('/', $parents);
							return directorypress_directorytype_url($listing_slug . '/' . $uri . '/' . $postname, $post_directorytype);
						} else {
							if (get_option('page_on_front') == $this->directorypress_archive_page_id)
								return directorypress_directorytype_url($post->ID . '/' . $postname, $post_directorytype);
							else
								return directorypress_directorytype_url($postname, $post_directorytype);
						}
						break;
					case 'tag_slug':
						if ($listing_slug && ($terms = get_the_terms($post->ID, DIRECTORYPRESS_TAGS_TAX)) && ($term = array_shift($terms))) {
							return directorypress_directorytype_url($listing_slug . '/' . $term->slug . '/' . $postname, $post_directorytype);
						} else
							if (get_option('page_on_front') == $this->directorypress_archive_page_id)
								return directorypress_directorytype_url($post->ID . '/' . $postname, $post_directorytype);
							else
								return directorypress_directorytype_url($postname, $post_directorytype);
						break;
					default:
						if (get_option('page_on_front') == $this->directorypress_archive_page_id)
							return directorypress_directorytype_url($post->ID . '/' . $postname, $post_directorytype);
						else
							return directorypress_directorytype_url($postname, $post_directorytype);
				}
			} else {
				if ($this->directorypress_post_Page_url) {
					$directorypress_post_Page_url = $this->directorypress_post_Page_url;
				} else {
					$directorypress_post_Page_url = $this->directorypress_archive_page_url;
				}
					
				return directorypress_templatePageUri(array('listing-directorypress' => $post->post_name), $directorypress_post_Page_url);
			}
		}
		return $permalink;
	}

	public function category_permalink($permalink, $category, $tax) {
		if ($tax == DIRECTORYPRESS_CATEGORIES_TAX) {
			global $wp_rewrite;
			if ($wp_rewrite->using_permalinks()) {
				/* if ($this->current_directorytype) {
					$category_slug = $this->current_directorytype->category_slug;
				} else {
					$category_slug = get_option('directorypress_category_slug');
				} */
				global $directorypress_directory_flag;
				if ($directorypress_directory_flag) {
					$directorytype = $this->directorytypes->directory_by_id($directorypress_directory_flag);
				} else {
					$directorytype = directorypress_directory_type_of_listing(get_the_ID());
				}
				$category_slug = $directorytype->category_slug;

				$uri = '';
				if ($parents = directorypress_get_term_parents_slugs($category->term_id, DIRECTORYPRESS_CATEGORIES_TAX))
					$uri = implode('/', $parents);
				return directorypress_directorytype_url($category_slug . '/' . $uri, $directorytype);
			} else
				return directorypress_templatePageUri(array('category-directorypress' => $category->slug), $this->directorypress_archive_page_url);
		}
		return $permalink;
	}
	
	public function location_permalink($permalink, $location, $tax) {
		if ($tax == DIRECTORYPRESS_LOCATIONS_TAX) {
			global $wp_rewrite;
			if ($wp_rewrite->using_permalinks()) {
				/* if ($this->current_directorytype) {
					$location_slug = $this->current_directorytype->location_slug;
				} else {
					$location_slug = get_option('directorypress_location_slug');
				} */
				global $directorypress_directory_flag;
				if ($directorypress_directory_flag) {
					$directorytype = $this->directorytypes->directory_by_id($directorypress_directory_flag);
				} else {
					$directorytype = directorypress_directory_type_of_listing(get_the_ID());
				}
				$location_slug = $directorytype->location_slug;

				$uri = '';
				if ($parents = directorypress_get_term_parents_slugs($location->term_id, DIRECTORYPRESS_LOCATIONS_TAX))
					$uri = implode('/', $parents);
				return directorypress_directorytype_url($location_slug . '/' . $uri, $directorytype);
			} else
				return directorypress_templatePageUri(array('location-directorypress' => $location->slug), $this->directorypress_archive_page_url);
		}
		return $permalink;
	}

	public function tag_permalink($permalink, $tag, $tax) {
		if ($tax == DIRECTORYPRESS_TAGS_TAX) {
			global $wp_rewrite;
			if ($wp_rewrite->using_permalinks()) {
				/* if ($this->current_directorytype) {
					$tag_slug = $this->current_directorytype->tag_slug;
				} else {
					$tag_slug = get_option('directorypress_tag_slug');
				} */
				$directorytype = directorypress_directory_type_of_listing(get_the_ID());
				$tag_slug = $directorytype->tag_slug;

				return directorypress_directorytype_url($tag_slug . '/' . $tag->slug, $directorytype);
			} else {
				return directorypress_templatePageUri(array('tag-directorypress' => $tag->slug), $this->directorypress_archive_page_url);
			}
		}
		return $permalink;
	}
	
	public function reserve_slugs($is_bad_flat_slug, $slug) {
		$slugs_to_check = array();
		foreach ($this->directorytypes->directorypress_array_of_directorytypes AS $directorytype) {
			$slugs_to_check[] = $directorytype->listing_slug;
			$slugs_to_check[] = $directorytype->category_slug;
			$slugs_to_check[] = $directorytype->location_slug;
			$slugs_to_check[] = $directorytype->tag_slug;
		}

		if (in_array($slug, $slugs_to_check))
			return true;
		return $is_bad_flat_slug;
	}

	public function directorypress_draft_listing_on_expiry() {
		global $wpdb, $DIRECTORYPRESS_ADIMN_SETTINGS;

		$posts_ids = $wpdb->get_col($wpdb->prepare("
				SELECT
					wp_pm1.post_id
				FROM
					{$wpdb->postmeta} AS wp_pm1
				LEFT JOIN
					{$wpdb->postmeta} AS wp_pm2 ON wp_pm1.post_id=wp_pm2.post_id
				LEFT JOIN
					{$wpdb->posts} AS wp_posts ON wp_pm1.post_id=wp_posts.ID
				LEFT JOIN
					{$wpdb->directorypress_packages_relation} AS wp_lr ON wp_lr.post_id=wp_pm1.post_id
				LEFT JOIN
					{$wpdb->directorypress_packages} AS wp_l ON wp_l.id=wp_lr.package_id
				WHERE
					wp_pm1.meta_key = '_expiration_date' AND
					wp_pm1.meta_value < %d AND
					wp_pm2.meta_key = '_listing_status' AND
					(wp_pm2.meta_value = 'active' OR wp_pm2.meta_value = 'stopped') AND
					(wp_l.package_no_expiry = '0')
			", current_time('timestamp')));
		$listings_ids_to_suspend = $posts_ids;
		foreach ($posts_ids AS $post_id) {
			if (!get_post_meta($post_id, '_expiration_notification_sent', true) && $listing = directorypress_get_listing($post_id)) {
				if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_expiration_notification']) {
					$listing_owner = get_userdata($listing->post->post_author);
			
					$subject = __('Expiration notification', 'DIRECTORYPRESS');
			
					$body = str_replace('[listing]', $listing->title(),
							str_replace('[link]', ((isset($this->dashboard_page_url) && $this->dashboard_page_url) ? directorypress_dashboardUrl(array('directorypress_action' => 'renew_listing', 'listing_id' => $post_id)) : admin_url('options.php?page=directorypress_renew&listing_id=' . $post_id)),
							$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_expiration_notification']));
					directorypress_mail($listing_owner->user_email, $subject, $body);
					
					$to = $listing_owner->user_phone;
					if(directorypress_is_directorypress_twilio_active() && !empty($to)){
						directorypress_send_sms($to, $body);
					}
					add_post_meta($post_id, '_expiration_notification_sent', true);
				}
			}

			// adapted for WPML
			global $sitepress;
			if (function_exists('wpml_object_id_filter') && $sitepress) {
				$trid = $sitepress->get_element_trid($post_id, 'post_' . DIRECTORYPRESS_POST_TYPE);
				$translations = $sitepress->get_element_translations($trid, 'post_' . DIRECTORYPRESS_POST_TYPE, false, true);
				foreach ($translations AS $lang=>$translation) {
					$listings_ids_to_suspend[] = $translation->element_id;
				}
			} else {
				$listings_ids_to_suspend[] = $post_id;
			}
		}
		$listings_ids_to_suspend = array_unique($listings_ids_to_suspend);
		foreach ($listings_ids_to_suspend AS $listing_id) {
			update_post_meta($listing_id, '_listing_status', 'expired');
			wp_update_post(array('ID' => $listing_id, 'post_status' => 'draft')); // This needed in order terms counts were always actual
			
			$listing = directorypress_get_listing($listing_id);
			if ($listing->package->change_package_id && ($new_package = $this->packages->get_package_by_id($listing->package->change_package_id))) {
				if ($wpdb->query("UPDATE {$wpdb->directorypress_packages_relation} SET package_id=" . $new_package->id . "  WHERE post_id=" . $listing->post->ID)) {
					$listing->set_package_by_post_id($listing->post->ID);
				}
			}
		}

		$posts_ids = $wpdb->get_col($wpdb->prepare("
				SELECT
					wp_pm1.post_id
				FROM
					{$wpdb->postmeta} AS wp_pm1
				LEFT JOIN
					{$wpdb->postmeta} AS wp_pm2 ON wp_pm1.post_id=wp_pm2.post_id
				LEFT JOIN
					{$wpdb->posts} AS wp_posts ON wp_pm1.post_id=wp_posts.ID
				LEFT JOIN
					{$wpdb->directorypress_packages_relation} AS wp_lr ON wp_lr.post_id=wp_pm1.post_id
				LEFT JOIN
					{$wpdb->directorypress_packages} AS wp_l ON wp_l.id=wp_lr.package_id
				WHERE
					wp_pm1.meta_key = '_expiration_date' AND
					wp_pm1.meta_value < %d AND
					wp_pm2.meta_key = '_listing_status' AND
					(wp_pm2.meta_value = 'active' OR wp_pm2.meta_value = 'stopped') AND
					(wp_l.package_no_expiry = '0')
			", current_time('timestamp')+($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_send_expiration_notification_days']*86400)));

		$listings_ids = $posts_ids;

		// adapted for WPML
		global $sitepress;
		if (function_exists('wpml_object_id_filter') && $sitepress) {
			foreach ($posts_ids AS $post_id) {
				$trid = $sitepress->get_element_trid($post_id, 'post_' . DIRECTORYPRESS_POST_TYPE);
				$listings_ids[] = $trid;
			}
		} else {
			$listings_ids = $posts_ids;
		}

		$listings_ids = array_unique($listings_ids);
		foreach ($listings_ids AS $listing_id) {
			if (!get_post_meta($listing_id, '_preexpiration_notification_sent', true) && ($listing = directorypress_get_listing($listing_id))) {
				if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_preexpiration_notification']) {
					$listing_owner = get_userdata($listing->post->post_author);

					$subject = __('Expiration notification', 'DIRECTORYPRESS');
					
					$body = str_replace('[listing]', $listing->title(),
							str_replace('[days]', $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_send_expiration_notification_days'],
							str_replace('[link]', ((isset($this->dashboard_page_url) && $this->dashboard_page_url) ? directorypress_dashboardUrl(array('directorypress_action' => 'renew_listing', 'listing_id' => $listing_id)) : admin_url('options.php?page=directorypress_renew&listing_id=' . $listing_id)),
							$DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_preexpiration_notification'])));
					directorypress_mail($listing_owner->user_email, $subject, $body);
					
					$to = $listing_owner->user_phone;
					if(directorypress_is_directorypress_twilio_active() && !empty($to)){
						directorypress_send_sms($to, $body);
					}
					add_post_meta($listing_id, '_preexpiration_notification_sent', true);
				}
			}
		}
	}
	
	function filter_comment_status($open, $post_id) {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$post = get_post($post_id);
		if ($post->post_type == DIRECTORYPRESS_POST_TYPE) {
			if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_comments_mode'] == 'enabled')
				return true;
			elseif ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listings_comments_mode'] == 'disabled')
				return false;
		}

		return $open;
	}

	public function directorypress_get_property_of_shortcode($shortcode, $property = false) {
		if (!isset($this->public_handlers[$shortcode]) || !isset($this->public_handlers[$shortcode][0]))
			return false;

		if ($property && !isset($this->public_handlers[$shortcode][0]->$property))
			return false;

		if ($property)
			return $this->public_handlers[$shortcode][0]->$property;
		else 
			return $this->public_handlers[$shortcode][0];
	}
	
	public function directorypress_get_unique_shortcode_object($id) {
		if (!isset($this->public_handlers) || !is_array($this->public_handlers) || empty($this->public_handlers))
			return false;

		foreach ($this->public_handlers AS $shortcodes)
			foreach ($shortcodes AS $directorypress_handler)
				if (is_object($directorypress_handler) && $directorypress_handler->hash == $id)
					return $directorypress_handler;
	}
	
	public function getListingsShortcodeByuID($uid) {
		foreach ($this->public_handlers AS $shortcodes)
			foreach ($shortcodes AS $directorypress_handler)
				if (is_object($directorypress_handler) && get_class($directorypress_handler) == 'directorypress_listings_handler' && $directorypress_handler->args['uid'] == $uid)
					return $directorypress_handler;
	}
	
	

	public function exclude_post_type_archive_link($archive_url, $post_type) {
		if ($post_type == DIRECTORYPRESS_POST_TYPE) {
			return false;
		}
		
		return $archive_url;
	}
	
	// adapted for Polylang
	public function pll_setup() {
		if (defined("POLYLANG_VERSION")) {
			add_filter('post_type_link', array($this, 'pll_stop_add_lang_to_url_post'), 0, 2);
			add_filter('post_type_link', array($this, 'pll_start_add_lang_to_url_post'), 100, 2);
			add_filter('term_link', array($this, 'pll_stop_add_lang_to_url_term'), 0, 3);
			add_filter('term_link', array($this, 'pll_start_add_lang_to_url_term'), 100, 3);
			add_filter('rewrite_rules_array', array($this, 'pll_rewrite_rules'));
		}
	}
	public function pll_stop_add_lang_to_url_post($permalink, $post) {
		$this->pll_force_lang = false;
		if ($post->post_type == DIRECTORYPRESS_POST_TYPE) {
			global $polylang;
			if (isset($polylang->links->links_model->model->options['force_lang']) && $polylang->links->links_model->model->options['force_lang']) {
				$this->pll_force_lang = true;
				$polylang->links->links_model->model->options['force_lang'] = 0;
			}
		}
		return $permalink;
	}
	public function pll_start_add_lang_to_url_post($permalink, $post) {
		if ($this->pll_force_lang && $post->post_type == DIRECTORYPRESS_POST_TYPE) {
			global $polylang;
			$polylang->links->links_model->model->options['force_lang'] = 1;
		}
		return $permalink;
	}
	public function pll_stop_add_lang_to_url_term($permalink, $term, $tax) {
		$this->pll_force_lang = false;
		if ($tax == DIRECTORYPRESS_CATEGORIES_TAX || $tax == DIRECTORYPRESS_TYPE_TAX || $tax == DIRECTORYPRESS_LOCATIONS_TAX || $tax == DIRECTORYPRESS_TAGS_TAX) {
			global $polylang;
			if (isset($polylang->links->links_model->model->options['force_lang']) && $polylang->links->links_model->model->options['force_lang']) {
				$this->pll_force_lang = true;
				$polylang->links->links_model->model->options['force_lang'] = 0;
			}
		}
		return $permalink;
	}
	public function pll_start_add_lang_to_url_term($permalink, $term, $tax) {
		if ($this->pll_force_lang && ($tax == DIRECTORYPRESS_CATEGORIES_TAX || $tax == DIRECTORYPRESS_TYPE_TAX || $tax == DIRECTORYPRESS_LOCATIONS_TAX || $tax == DIRECTORYPRESS_TAGS_TAX)) {
			global $polylang;
			$polylang->links->links_model->model->options['force_lang'] = 1;
		}
		return $permalink;
	}
	public function pll_rewrite_rules($rules) {
		global $polylang, $wp_current_filter;
		$wp_current_filter[] = 'directorypress_listing';
		return $polylang->links->links_model->rewrite_rules($this->directorypress_create_rules()) + $rules;
	}

}
