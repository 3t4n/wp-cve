<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://if-so.com
 * @since      1.0.0
 *
 * @package    IfSo
 * @subpackage IfSo/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    IfSo
 * @subpackage IfSo/includes
 * @author     Matan Green
 * @author Nick Martianov
 */

class If_So {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */


	public function __construct() {
		$this->plugin_name = 'if-so';

		$this->define_global_constants();

        $this->version = IFSO_WP_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - If_So_Loader. Orchestrates the hooks of the plugin.
	 * - If_So_i18n. Defines internationalization functionality.
	 * - If_So_Admin. Defines all hooks for the admin area.
	 * - If_So_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-if-so-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-if-so-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-if-so-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-if-so-public.php';

		/**
		 * The class responsible for defining all code necessary to activate /
		 deactivate / etc of IfSo's License.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/license-service/license-service.class.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/license-service/geo-license-service.class.php';

		/**
		 * Plugin settings service.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/plugin-settings-service/plugin-settings-service.class.php';

		/**
		 * For Extended Shortcodes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/ifso-extended-shortcodes/extended-shortcodes.php';

        /**
         * For various AJAX actions relating to license checks
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/license-ajax-service/license-ajax-service.class.php';

		 /**
		 * For Privacy Policy.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/privacy-policy/privacy-policy.php';
		add_action('admin_init', 'privacy_on_admin_init');
        /**
         * For modifying the admin(dashboard) interface
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/services/interface-modifier-service/interface-mod.class.php';

        /**
         * For checking whether the plugin was updated and preforming relevant actions if it was
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/after-upgrade-service/after-upgrade-service.class.php';

        /**
         * For checking whether the plugin was updated and preforming relevant actions if it was
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/after-upgrade-service/after-upgrade-service.class.php';

        /**
         * For registering an Ajax API for the analytics system
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/services/analytics-service/analytics-ajax-handler.class.php';

        /**
         * For importing and exporting triggers
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/trigger-port-service/trigger-port-handler.class.php';

        /**
         * For Gutenbeg Blocks
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/ifso-gutenberg-blocks/ifso-guntenberg-block/ifso-gutenberg-block.class.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/ifso-gutenberg-blocks/ifso-standalone-conditions-gutenberg-block/ifso-gutenberg-standalone-conditions-block.class.php';

        /**
         * For Elementor element
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'extensions/ifso-elementor-element/ifso-elementor-support.php';

        /**
         * For If-So Groups functionality's handler
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/services/groups-service/groups-handler.class.php';

        /**
         * For loading if-so triggers via AJAX
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/services/ajax-triggers-service/ajax-triggers-service.class.php';

        /**
         * For standalone conditions
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/services/standalone-condition-service/standalone-condition-service.class.php';
        /**
         * Helper classes
         */
        require_once(IFSO_PLUGIN_BASE_DIR . 'public/helpers/ifso-helpers.php');


        $this->loader = new If_So_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new If_So_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new If_So_Admin( 
				$this->get_plugin_name(),
				$this->get_version() );

		$plugin_settings = new If_So_Admin_Settings( 
				$this->get_plugin_name(),
				$this->get_version() );

		$license_service = IfSo\Services\LicenseService\LicenseService::get_instance();
		$geo_license_service = IfSo\Services\GeoLicenseService\GeoLicenseService::get_instance();
        $plugin_settings_service = IfSo\Services\PluginSettingsService\PluginSettingsService::get_instance();
        $license_ajax_service = IfSo\Services\LicenseAjaxService\LicenseAjaxService::get_instance();
        $interface_mod = IfSo\Admin\Services\InterfaceModService\InterfaceModService::get_instance();
        $after_upgrade = IfSo\Services\AfterUpgradeService\AfterUpgradeService::get_instance();
        $trigger_port_handler = \IfSo\Services\TriggerPortService\TriggerPortHandler::get_instance();
        $analytics_ajax_handler = IfSo\PublicFace\Services\AnalyticsService\AnalyticsAjaxHandler::get_instance();
        $triggers_gutenberg_block = IfSo\Extensions\IfSoGutenbergBlock\IfSoTriggerGutenbergBlock::get_instance();
        $standalone_conditions_gutenberg_block = IfSo\Extensions\IfSoGutenbergBlock\IfsoGutenbergStandaloneConditionBlock::get_instance();
        $groups_handler = IfSo\PublicFace\Services\GroupsService\GroupsHandler::get_instance();
        $elementor_support = Ifso\Extensions\Elementor\IFSO_Elementor_Widgets::get_instance();


        /**
         * For checking whether the plugin was upgraded and preform relevant actions if it was
         */

        $this->loader->add_action('admin_init', $after_upgrade,'handle');

		/**
		 * For Title ShortCodes.
		 */
		$shortcodes_in_title_checkbox = $plugin_settings_service->allowShortcodesInTitle->get();
        if($shortcodes_in_title_checkbox){
            add_filter( 'document_title_parts', function($atts){$atts['title'] = do_shortcode($atts['title']) ;return $atts; } );  //Apply to meta title
            add_filter( 'the_title', 'do_shortcode' );     //Apply to title.
            add_filter('wp_nav_menu_items', 'do_shortcode');    //apply to menu items
        }

        add_filter('wpseo_title','do_shortcode'); 	//Allow shorctodes in YOAST SEO titles
        add_filter('wpseo_metadesc','do_shortcode'); //Allow shortcodes in YOAST SEO meta description
        add_filter('aioseop_title','do_shortcode'); 	//Allow shorctodes in AIO SEO titles
        add_filter('rank_math/frontend/title','do_shortcode');  //Allow shorctodes in Rank Math titles
        add_filter('rank_math/frontend/description','do_shortcode');    //Allow shorctodes in Rank Math descriptions

		/**
		 * For Extended Shortcodes.
		 */
		$ext_shortcodes = IfSo\Extensions\IFSOExtendedShortcodes\ExtendedShortcodes\ExtendedShortcodes::get_instance();
		$this->loader->add_action('init', $ext_shortcodes, 'add_extended_shortcodes', 10);

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' , 99 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_post_types', 1 );
		
		$this->loader->add_action( 'admin_menu', $plugin_settings, 'add_plugin_menu_items' );
		//$this->loader->add_action( 'network_admin_menu', $plugin_settings, 'add_plugin_menu_items' );
		$this->loader->add_filter( 'manage_ifso_triggers_posts_columns', $plugin_settings, 'ifso_add_custom_column_title', 100, 1 );
		$this->loader->add_action( 'manage_ifso_triggers_posts_custom_column', $plugin_settings, 'ifso_add_custom_column_data', 10, 2 );

		$this->loader->add_action( 'add_meta_boxes_ifso_triggers', $plugin_settings, 'ifso_add_meta_boxes', 1 );
		$this->loader->add_action( 'save_post_ifso_triggers', $plugin_settings, 'ifso_save_post_type' );
		$this->loader->add_filter( 'wpseo_metabox_prio', $plugin_settings, 'move_yoast_metabox_down', 10 );
		
		$this->loader->add_filter( 'template_include', $plugin_settings, 'include_ifso_custom_triggers_template', 1 );

		/* Ajax Actions */		
		$this->loader->add_action( 'wp_ajax_load_tinymce_repeater', $plugin_settings, 'load_tinymce' );
        $this->loader->add_action( 'wp_ajax_ifso_analytics_req', $analytics_ajax_handler, 'handle' );
        $this->loader->add_action( 'wp_ajax_trigger_export_req', $trigger_port_handler, 'handle' );     //Import/Export/Duplicate actions handler
        $this->loader->add_action( 'wp_ajax_trigger_scan_req', $interface_mod, 'trigger_scan_page' );     //"Scan posts for if-so triggers" page
        $this->loader->add_action('wp_ajax_ifso_groups_req',$groups_handler,'handle');  //If-So groups actions handler

		$this->loader->add_action( 'wp_ajax_send_test_mail', $plugin_settings, 'send_test_mail' );
		$this->loader->add_action( 'wp_ajax_render_preview_content', $interface_mod, 'ajax_render_preview_content' );


		/* License Hooks */
		$this->loader->add_action('admin_init', $license_service,'edd_ifso_activate_license');
		$this->loader->add_action('admin_init', $license_service,'edd_ifso_clear_license');
		$this->loader->add_action('admin_init', $license_service,'edd_ifso_deactivate_license');
		$this->loader->add_action('admin_init', $license_service,'edd_ifso_is_license_valid',0);
        $this->loader->add_action( 'wp_ajax_get_license_message', $license_ajax_service, 'licenseAjaxController' );

		/* Geo License Hooks */
		$this->loader->add_action('admin_init', $geo_license_service,'edd_ifso_activate_geo_license');
		$this->loader->add_action('admin_init', $geo_license_service,'edd_ifso_deactivate_geo_license');
		$this->loader->add_action('admin_init', $geo_license_service,'edd_ifso_is_geo_license_valid',0);

		/* Settings Page Hook(s) */
		$this->loader->add_action(
				'admin_init',
				$plugin_settings_service,
				'settings_page_update',
				0);

		$this->loader->add_action('admin_notices', $plugin_settings,'edd_ifso_admin_notices');

        /*Interface modification hook(s)*/
        $this->loader->add_filter('enter_title_here',$interface_mod,'replace_newtrigger_title_placeholder',10,2);
        $this->loader->add_filter( 'post_row_actions', $interface_mod, 'add_export_button', 10, 2 );
        $this->loader->add_filter( 'post_row_actions', $interface_mod, 'add_scan_button', 10, 2 );
        $this->loader->add_filter( 'tiny_mce_before_init', $interface_mod, 'tinymce_modify_settings', 10 );
        $this->loader->add_action( 'views_edit-ifso_triggers', $interface_mod, 'add_import_button');
        $this->loader->add_action('admin_notices', $interface_mod,'trigger_imported_notice');
        $this->loader->add_action('media_buttons', $interface_mod,'add_editor_modal_button');
        //$this->loader->add_action('wp_insert_post_data', $ext_shortcodes,'modify_ifso_shorcode_add_edit',99,1);  //--remove for now--Add "edit" button to if-so shortcodes on save
        $this->loader->add_filter( 'post_row_actions', $interface_mod, 'add_duplicate_button', 10, 2 );  //Add duplicate button to trigger action bar
        $this->loader->add_filter('the_content',$interface_mod,'do_shortcode',999);  //Prevent external themes/plugins from striping the_content filter out before internal shorcodes can be rendered
        $this->loader->add_action('admin_notices', $interface_mod,'groups_page_notices');
        $this->loader->add_action('admin_notices', $interface_mod,'admin_notices_presistant');
        $this->loader->add_action('show_pagebuilders_noticebox', $interface_mod,'show_pagebuilders_noticebox');
        $this->loader->add_action('et_builder_load_actions',$interface_mod,'allow_divi_shortcodes_in_ajax_calls');  //Fix conflict - Divi - Allow divi shortcodes to be rendered inside an if-so shortcode rendered via AJAX
        $this->loader->add_action('plugin_row_meta',$interface_mod,'add_plugin_links',10,2);  //Add our custom links to the plugin description area
        $this->loader->add_action('admin_footer',$interface_mod,'menu_links_new_tab',10);

        /*Elementor widget hooks*/
        $this->loader->add_action('init',$elementor_support,'init_elementor_widget');
        $this->loader->add_action('elementor/init',$elementor_support,'register_elementor_category');


        /*Prevent the emails from going to spam by setting the value of the sender header to equal to "FROM" - USELESS/BREAKS OTHER PLUGINS AS OF 1.6.3*/
        //$this->loader->add_action( 'phpmailer_init', $plugin_settings, 'fix_email_return_addr' );

        /*Enqueue ifso block assets*/
        $this->loader->add_action( 'init', $triggers_gutenberg_block, 'enqueue_block_assets' );
        $this->loader->add_action( 'enqueue_block_editor_assets', $standalone_conditions_gutenberg_block, 'enqueue_block_assets' , 1 );
        $this->loader->add_action('wp_loaded',$standalone_conditions_gutenberg_block,'add_ifso_standalone_attributes_to_all_block_types');
	}

	private function define_global_constants() {
        require(plugin_dir_path( __FILE__ ) . 'ifso-constants.php');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new If_So_Public( $this->get_plugin_name(), $this->get_version());
        $analytics_ajax_handler = IfSo\PublicFace\Services\AnalyticsService\AnalyticsAjaxHandler::get_instance();
        $ajax_triggers_service = IfSo\PublicFace\Services\AjaxTriggersService\AjaxTriggersService::get_instance();
        $standalone_condition_service = IfSo\PublicFace\Services\StandaloneConditionService\StandaloneConditionService::get_instance();
        $standalone_conditions_gutenberg_block = IfSo\Extensions\IfSoGutenbergBlock\IfsoGutenbergStandaloneConditionBlock::get_instance();

        $this->loader->add_action('wp_loaded', $plugin_public, 'start_sesh' ); //session_start

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_ifso_add_page_visit', $plugin_public, 'wp_ajax_ifso_visit_handler' );
		$this->loader->add_action( 'wp_ajax_nopriv_ifso_add_page_visit', $plugin_public, 'wp_ajax_ifso_visit_handler' );
        $this->loader->add_action( 'wp_ajax_nopriv_ifso_analytics_req', $analytics_ajax_handler, 'public_handle' );

        $this->loader->add_action( 'wp_ajax_render_ifso_shortcodes', $ajax_triggers_service, 'handle_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_render_ifso_shortcodes', $ajax_triggers_service, 'handle_ajax' );

        $this->loader->add_action( 'init', $plugin_public, 'set_ifso_group_cookie' );   //Set if-so group cookie if the relevant get/post variable is set

        $this->loader->add_filter('render_block',$standalone_conditions_gutenberg_block,'filter_gutenberg_block_through_condition',10,3);

        //$this->loader->add_action('init',$plugin_public, 'update_visit_count');       //Visits counted only via ajax for now

        $this->loader->add_filter('wpseo_sitemap_exclude_post_type',$plugin_public,'exclude_triggers_from_sitemap',10,2);
        $this->loader->add_filter('ifso_shortcode_content',$plugin_public,'render_ifso_shortcode_by_name',10,2);

        $this->loader->add_action('plugins_loaded',$plugin_public, 'builders_shortcodes_ajax_compat');
		// create shortcode
		$this->loader->add_shortcode( 'ifso', $plugin_public, 'add_if_so_shortcode' );
		// standalone condition shortcode
        $this->loader->add_shortcode( 'ifso_condition', $standalone_condition_service, 'render_ifso_condition_shortcode' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}