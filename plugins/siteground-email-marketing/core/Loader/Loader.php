<?php
namespace SG_Email_Marketing\Loader;

use SG_Email_Marketing;
use SG_Email_Marketing\Pages\Dashboard as Dashboard_Page;
use SG_Email_Marketing\Pages\Forms as Forms_Page;
use SG_Email_Marketing\Pages\Settings as Settings_Page;

use SG_Email_Marketing\Rest\Server;

use SG_Email_Marketing\Post_Types\Forms as Forms_Post_Type;
use SG_Email_Marketing\Forms\Forms;

use SG_Email_Marketing\Integrations\Comment_Form as Comment_Form_Integration;
use SG_Email_Marketing\Integrations\Registration_Form as Registration_Form_Integration;
use SG_Email_Marketing\Integrations\Woo_Form as Woo_Form_Integration;
use SG_Email_Marketing\Integrations\Elementor\Elementor_Form as Elementor_Form_Integration;
use SG_Email_Marketing\Integrations\Elementor\Forms\Elementor_Pro_Forms as Elementor_Pro_Forms_Integration;
use SG_Email_Marketing\Integrations\Gutenberg as Gutenberg_Integration;
use SG_Email_Marketing\Integrations\ThirdParty\CF7 as CF7_Integration;
use SG_Email_Marketing\Integrations\ThirdParty\WPForms\WPForms as WPForms_Integration;
use SG_Email_Marketing\Services\Background_Process\Background_Process;
use SG_Email_Marketing\Services\Mailer_Api\Mailer_Api;
use SG_Email_Marketing\Services\Cron\Cron;
use SG_Email_Marketing\Renderer\Renderer;
use SG_Email_Marketing\Install_Service\Install_Service;
use SiteGround_i18n\i18n_Service;

/**
 * Loader functions and main initialization class.
 */
class Loader {
	/**
	 * The singleton instance.
	 *
	 * @since 1.0.0
	 *
	 * @var The singleton instance.
	 */
	private static $instance;

	/**
	 * Integrations list.
	 *
	 * @since 1.0.0
	 *
	 * @var array Integrations List.
	 */
	public $integrations;

	/**
	 * The constructor.
	 */
	public function __construct() {
		self::$instance     = $this;
		$this->integrations = (object) array();
		$this->load_dependencies();
		$this->add_hooks();
	}

	/**
	 * Get the singleton instance.
	 *
	 * @since 1.0.0
	 *
	 * @return  The singleton instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get all available integrations.
	 *
	 * @since 1.0.0
	 *
	 * @return array $available_itegrations Array of all integration available.
	 */
	public function get_integrations() {
		$available_integrations = array();

		foreach ( $this->integrations as $integration ) {
			$available_integrations[ $integration->id ] = $integration;
		}

		return $available_integrations;
	}
	/**
	 * Load the main plugin dependencies.
	 *
	 * @since  1.0.0
	 */
	private function load_dependencies() {
		$this->server                                        = new Server();
		$this->dashboard_page                                = new Dashboard_Page();
		$this->forms_page                                    = new Forms_Page();
		$this->settings_page                                 = new Settings_Page();
		$this->forms_post_type                               = new Forms_Post_Type();
		$this->mailer_api                                    = new Mailer_Api();
		$this->renderer                                      = new Renderer();
		$this->background_process                            = new Background_Process( $this->mailer_api );
		$this->forms                                         = new Forms( $this->mailer_api );
		$this->cron                                          = new Cron( $this->background_process );
		$this->i18n_service                                  = new i18n_Service( SG_Email_Marketing\PLUGIN_SLUG, SG_Email_Marketing\PLUGIN_SLUG );
		$this->integrations->comment_form_integration        = new Comment_Form_Integration( $this->mailer_api );
		$this->integrations->registration_form_integration   = new Registration_Form_Integration( $this->mailer_api );
		$this->integrations->woo_form_integration            = new Woo_Form_Integration( $this->mailer_api );
		$this->integrations->elementor_form_integration      = new Elementor_Form_integration( $this->mailer_api );
		$this->integrations->elementor_pro_forms_integration = new Elementor_Pro_Forms_Integration( $this->mailer_api );
		$this->integrations->gutenberg_integration           = new Gutenberg_Integration( $this->mailer_api, $this->renderer );
		$this->integrations->cf7                             = new CF7_Integration( $this->mailer_api );
		$this->integrations->wpforms                         = new WPForms_Integration( $this->mailer_api );
		$this->install_service                               = new Install_Service();
	}

	/**
	 * Add the hooks that the plugin will use to do the magic.
	 *
	 * @since  1.0.0
	 */
	private function add_hooks() {
		$this->add_server_hooks();
		$this->add_menu_items_hooks();
		$this->add_post_types_hooks();
		$this->add_comment_form_integrations_hooks();
		$this->add_registration_form_integrations_hooks();
		$this->add_woo_form_integrations_hooks();
		$this->add_elementor_form_integrations_hooks();
		$this->add_elementor_pro_forms_integrations_hooks();
		$this->add_gutenberg_integration_hooks();
		$this->add_cf7_integration_hooks();
		$this->add_wpforms_integration_hooks();
		$this->add_cron_hooks();
		$this->add_form_hooks();
		$this->add_renderer_hooks();
		$this->add_i18n_hooks();
		$this->add_install_service_hooks();
	}

	/**
	 * Add localization hooks.
	 *
	 * @since 1.0.0
	 */
	public function add_i18n_hooks() {
		// Load the plugin textdomain.
		add_action( 'after_setup_theme', array( $this->i18n_service, 'load_textdomain' ), 9999 );
		// Generate JSON translations.
		add_action( 'upgrader_process_complete', array( $this->i18n_service, 'update_json_translations' ), 10, 2 );
	}

	/**
	 * Add the REST API hooks.
	 *
	 * @since 1.0.0
	 */
	public function add_server_hooks() {
		add_action( 'rest_api_init', array( $this->server, 'register_rest_routes' ) );
	}

	/**
	 * Add all hooks related to the menu and pages items.
	 */
	public function add_menu_items_hooks() {
		// Add the main menu item.
		add_action( 'admin_menu', array( $this->dashboard_page, 'add_plugin_main_menu_item' ) );
		// Reorder the sub-menu.
		add_filter( 'custom_menu_order', '__return_true' );
		// Reorder the sub-menu and remove the default page name.
		add_filter( 'menu_order', array( $this->dashboard_page, 'reorder_submenu_pages' ) );

		add_action( 'admin_print_styles', array( $this->dashboard_page, 'admin_print_styles' ) );

		// Add the Forms menu item.
		add_action( 'admin_menu', array( $this->forms_page, 'add_submenu_item' ) );
		// Register the styles for the Dashboard area.
		add_action( 'admin_enqueue_scripts', array( $this->forms_page, 'enqueue_styles' ) );
		// Register the JavaScript for the Dashboard area.
		add_action( 'admin_enqueue_scripts', array( $this->forms_page, 'enqueue_scripts' ) );
		// Add the React config.
		add_action( 'admin_print_styles', array( $this->forms_page, 'admin_print_styles' ) );
		// Remove error and notices on our pages.
		add_action( 'admin_init', array( $this->forms_page, 'hide_errors_and_notices' ), PHP_INT_MAX );

		// Add the Settings menu item.
		add_action( 'admin_menu', array( $this->settings_page, 'add_submenu_item' ) );
		// Remove error and notices on our pages.
		add_action( 'admin_init', array( $this->settings_page, 'hide_errors_and_notices' ), PHP_INT_MAX );
		// Register the styles for the Dashboard area.
		add_action( 'admin_enqueue_scripts', array( $this->settings_page, 'enqueue_styles' ) );
		// Register the JavaScript for the Dashboard area.
		add_action( 'admin_enqueue_scripts', array( $this->settings_page, 'enqueue_scripts' ) );
		// Add the React config.
		add_action( 'admin_print_styles', array( $this->settings_page, 'admin_print_styles' ) );
	}

	/**
	 * Add the custom post types.
	 *
	 * @since 1.0.0
	 */
	public function add_post_types_hooks() {
		add_action( 'init', array( $this->forms_post_type, 'register_forms_post_type' ), 0 );
	}

	/**
	 * Add comment form integrations hooks
	 *
	 * @since 1.0.0
	 */
	public function add_comment_form_integrations_hooks() {
		// Check if integration is active.
		if ( ! $this->integrations->comment_form_integration->is_active() ) {
			return;
		}

		// Add the comment form consent field.
		add_filter( 'comment_form_fields', array( $this->integrations->comment_form_integration, 'add_comment_form_consent' ) );
		// Handle the comment form data after the comment is posted.
		add_action( 'comment_post', array( $this->integrations->comment_form_integration, 'handle_comment_submission' ), 10, 3 );
	}

	/**
	 * Add the registration form integration hooks.
	 *
	 * @since 1.0.0
	 */
	public function add_registration_form_integrations_hooks() {
		// Check if integration is active.
		if ( ! $this->integrations->registration_form_integration->is_active() ) {
			return;
		}

		// Add the registration form consent field.
		add_action( 'register_form', array( $this->integrations->registration_form_integration, 'add_registration_form_consent' ) );
		// Handle the registration form data after the user is registered is posted.
		add_action( 'user_register', array( $this->integrations->registration_form_integration, 'handle_user_registration' ) );
	}

	/**
	 * Add the woo checkout integration hooks.
	 *
	 * @since 1.0.0
	 */
	public function add_woo_form_integrations_hooks() {
		// Check if integration is active.
		if ( ! $this->integrations->woo_form_integration->is_active() ) {
			return;
		}

		add_filter( 'woocommerce_checkout_fields', array( $this->integrations->woo_form_integration, 'add_checkout_form_consent' ) );
		add_action( 'woocommerce_checkout_order_processed', array( $this->integrations->woo_form_integration, 'add_create_order_form_consent' ), 10, 3 );

		// Actions for Woo Checkout block integration.
		add_action( 'woocommerce_blocks_loaded', array( $this->integrations->woo_form_integration, 'sg_wc_store_api_register_endpoint_data' ) );
		add_action( 'woocommerce_blocks_checkout_block_registration', array( $this->integrations->woo_form_integration, 'sg_wc_block_integration_registry' ) );
		// Fetch the value from the checkbox.
		add_action( 'woocommerce_store_api_checkout_update_order_from_request', array( $this->integrations->woo_form_integration, 'fetch_sg_woo_checkbox_value' ), 10, 2 );
		// Change the checkbox text as per the integration settings.
		add_filter( 'sg_email_marketing_woo_checkbox_label', array( $this->integrations->woo_form_integration, 'sg_checkbox_label_filter' ) );
	}

	/**
	 * Add elementor forms integration hooks.
	 *
	 * @since 1.0.0
	 */
	public function add_elementor_form_integrations_hooks() {
		if ( ! $this->integrations->elementor_form_integration->is_active() ) {
			return;
		}

		// Add actions for frontend and editor mode.
		add_action( 'elementor/frontend/after_enqueue_scripts', array( $this->integrations->elementor_form_integration, 'enqueue_frontend_scripts' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this->integrations->elementor_form_integration, 'enqueue_editor_styles' ) );

		// Integrate the form.
		add_action( 'elementor/widgets/register', array( $this->integrations->elementor_form_integration, 'integrate_form' ) );
		add_action( 'wp_ajax_sgforms_admin_get_form_selector_options', array( $this->integrations->elementor_form_integration, 'ajax_selector' ) );
	}

	/**
	 * Add Elementor Pro Forms integration hooks.
	 *
	 * @since 1.1.3
	 */
	public function add_elementor_pro_forms_integrations_hooks() {
		add_action( 'elementor/frontend/after_enqueue_scripts', array( $this->integrations->elementor_pro_forms_integration, 'enqueue_frontend_scripts' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this->integrations->elementor_pro_forms_integration, 'enqueue_editor_styles' ) );

		add_action( 'elementor_pro/forms/actions/register', array( $this->integrations->elementor_pro_forms_integration, 'add_sgwpmail_form_action' ) );
		add_action( 'elementor_pro/forms/fields/register', array( $this->integrations->elementor_pro_forms_integration, 'add_sgwpmail_form_fields' ) );
	}

	/**
	 * Cron job hooks.
	 *
	 * @since 1.0.0
	 */
	public function add_cron_hooks() {
		$this->cron->schedule();
		add_action( 'sg_email_marketing_send_data', array( $this->cron, 'prepare_request' ) );
	}

	/**
	 * Add FE form hooks.
	 *
	 * @since 1.0.0
	 */
	public function add_form_hooks() {
		add_action( 'wp_ajax_sg_mail_marketing_form_submission', array( $this->forms, 'handle_form_submission' ) );
		add_action( 'wp_ajax_nopriv_sg_mail_marketing_form_submission', array( $this->forms, 'handle_form_submission' ) );
		add_action( 'wp_enqueue_scripts', array( $this->forms, 'enqueue_frontend_scripts' ) );
	}

	/**
	 * Add the gutenberg integration hooks.
	 *
	 * @since 1.0.0
	 */
	public function add_gutenberg_integration_hooks() {
		add_action( 'init', array( $this->integrations->gutenberg_integration, 'register_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this->integrations->gutenberg_integration, 'enqueue_block_editor_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this->integrations->gutenberg_integration, 'enqueue_block_editor_assets' ) );
	}

	/**
	 * Add the Renderer integration hooks.
	 *
	 * @since 1.0.0
	 */
	public function add_renderer_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this->renderer, 'enqueue_form_styling' ) );
		add_shortcode( 'sgforms', array( $this->renderer, 'register_sgform_shortcode' ) );
	}

	/**
	 * Add the cf7 integration hooks.
	 *
	 * @since 1.1.0
	 */
	public function add_cf7_integration_hooks() {
		add_action( 'wpcf7_init', array( $this->integrations->cf7, 'init' ) );
		add_action( 'wpcf7_mail_sent', array( $this->integrations->cf7, 'process' ), 1 );
		add_action( 'wpcf7_posted_data', array( $this->integrations->cf7, 'alter_cf7_data' ) );
		add_filter( 'wpcf7_editor_panels', array( $this->integrations->cf7, 'add_sg_panel' ) );
		add_action( 'wpcf7_after_save', array( $this->integrations->cf7, 'maybe_update_post_content' ) );
		add_action( 'load-toplevel_page_wpcf7', array( $this->integrations->cf7, 'cf7_form_save_meta' ), 0, 10 );
		add_action( 'admin_enqueue_scripts', array( $this->integrations->cf7, 'enqueue_styles_scripts' ) );

	}
	/**
	 * Add install service hooks.
	 *
	 * @since 1.1.1
	 */
	public function add_install_service_hooks() {
		add_action( 'upgrader_process_complete', array( $this->install_service, 'install' ) );
	}

	/**
	 * Add the WPForms integration hooks.
	 *
	 * @since 1.1.4
	 */
	public function add_wpforms_integration_hooks() {
		add_filter( 'init', array( $this->integrations->wpforms, 'register_custom_checkbox_field' ) );
		add_action( 'wpforms_builder_enqueues', array( $this->integrations->wpforms, 'enqueue_styles_scripts' ) );
		add_action( 'wp_ajax_sg_email_marketing_wpforms_save_post', array( $this->integrations->wpforms, 'save_form' ) );
		add_action( 'wp_ajax_nopriv_sg_email_marketing_wpforms_save_post', array( $this->integrations->wpforms, 'save_form' ) );
		add_action( 'wpforms_builder_save_form', array( $this->integrations->wpforms, 'update_groups' ), 10, 2 );
		add_action( 'wpforms_process', array( $this->integrations->wpforms, 'process_wpforms' ), 20, 3 );
	}
}
