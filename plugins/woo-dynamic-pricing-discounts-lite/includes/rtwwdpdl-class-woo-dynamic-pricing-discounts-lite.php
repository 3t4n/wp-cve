<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.redefiningtheweb.com
 * @since      1.0.0
 *
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/includes
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
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/includes
 * @author     RedefiningTheWeb <developer@redefiningtheweb.com>
 */
class Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
{
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Loader    $rtwwdpdl_loader    Maintains and registers all hooks for the plugin.
	 */
	protected $rtwwdpdl_loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $rtwwdpdl_plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $rtwwdpdl_plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $rtwwdpdl_version    The current version of the plugin.
	 */
	protected $rtwwdpdl_version;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_VERSION'))
		{
			$this->rtwwdpdl_version = Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_VERSION;
		}
		else
		{
			$this->rtwwdpdl_version = '1.0.0';
		}
		$this->rtwwdpdl_plugin_name = 'woo-dynamic-pricing-discounts-with-ai';
		$this->rtwwdpdl_load_dependencies();
		$this->rtwwdpdl_set_locale();
		$this->rtwwdpdl_define_admin_hooks();
		$this->rtwwdpdl_define_public_hooks();
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Loader. Orchestrates the hooks of the plugin.
	 * - Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_i18n. Defines internationalization functionality.
	 * - Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Admin. Defines all hooks for the admin area.
	 * - Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function rtwwdpdl_load_dependencies()
	{
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/rtwwdpdl-class-woo-dynamic-pricing-discounts-lite-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/rtwwdpdl-class-woo-dynamic-pricing-discounts-lite-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/rtwwdpdl-class-woo-dynamic-pricing-discounts-lite-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/rtwwdpdl-class-woo-dynamic-pricing-discounts-lite-public.php';
		$this->rtwwdpdl_loader = new Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Loader();
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function rtwwdpdl_set_locale()
	{
		$rtwwdpdl_plugin_i18n = new Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_i18n();
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('plugins_loaded', $rtwwdpdl_plugin_i18n, 'rtwwdpdl_load_plugin_textdomain');
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function rtwwdpdl_define_admin_hooks()
	{
		$rtwwdpdl_plugin_admin = new Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Admin($this->rtwwdpdl_get_plugin_name(), $this->rtwwdpdl_get_version());
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('admin_enqueue_scripts', $rtwwdpdl_plugin_admin, 'rtwwdpdl_enqueue_styles');
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('admin_enqueue_scripts', $rtwwdpdl_plugin_admin, 'rtwwdpdl_enqueue_scripts');
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('admin_menu', $rtwwdpdl_plugin_admin, 'rtwwdpdl_add_submenu');
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('wp_login', $rtwwdpdl_plugin_admin, 'rtwwdpdl_update_customer_visit', 10, 2);
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('wp_ajax_rtwwdpdl_plus_member', $rtwwdpdl_plugin_admin, 'rtwwdpdl_plus_member_callback');
		//////// add extra column in user list table wordpress /////
		$this->rtwwdpdl_loader->rtwwdpdl_add_filter('manage_users_columns', $rtwwdpdl_plugin_admin, 'rtwwdpdl_new_colmn_user', 10, 1);
		$this->rtwwdpdl_loader->rtwwdpdl_add_filter('manage_users_custom_column', $rtwwdpdl_plugin_admin, 'rtwwdpdl_user_data', 10, 3);
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('wp_ajax_rtw_cat_tbl', $rtwwdpdl_plugin_admin, 'rtwwdpdl_category_tbl_callback');
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function rtwwdpdl_define_public_hooks()
	{
		$rtwwdpdl_plugin_public = new Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Public($this->rtwwdpdl_get_plugin_name(), $this->rtwwdpdl_get_version());
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('wp_enqueue_scripts', $rtwwdpdl_plugin_public, 'rtwwdpdl_enqueue_styles');
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('wp_enqueue_scripts', $rtwwdpdl_plugin_public, 'rtwwdpdl_enqueue_scripts');
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_cart_calculate_fees', $rtwwdpdl_plugin_public, 'rtwwdpdl_discnt_on_pay_select');
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_cart_loaded_from_session', $rtwwdpdl_plugin_public, 'rtwwdpdl_cart_loaded_from_session', 98, 1);
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_calculate_totals', $rtwwdpdl_plugin_public, 'rtwwdpdl_before_calculate_totals', 98, 1);
		/////////////////// display offers on shop page //////////////////
		$rtw_priority_option = get_option('rtwwdpdl_setting_priority');
		if (isset($rtw_priority_option['rtw_offer_show']) && $rtw_priority_option['rtw_offer_show'] == 'rtw_price_yes')
		{
			if ($rtw_priority_option['rtwwdpdl_offer_tbl_pos'] == 'rtw_bfore_pro')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_shop_loop_item', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_pos'] == 'rtw_aftr_pro')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_shop_loop_item', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_pos'] == 'rtw_bfore_pro_sum')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_shop_loop_item_title', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_pos'] == 'rtw_in_pro_sum')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_shop_loop_item_title', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_pos'] == 'rtw_aftr_pro_sum')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_shop_loop_item', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_cart_table', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_cart_page', 5);
		}
		//////////////// display offers on product page ////////////////
		if (isset($rtw_priority_option['rtw_offer_on_product']) && $rtw_priority_option['rtw_offer_on_product'] == 'rtw_price_yes')
		{
			if ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_bfore_pro')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_single_product', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_aftr_pro')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_single_product', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_bfore_pro_sum')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_single_product_summary', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_in_pro_sum')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_single_product_summary', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_aftr_pro_sum')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_single_product_summary', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_bfre_add_cart_btn')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_add_to_cart_button', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_aftr_add_cart_btn')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_add_to_cart_button', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_bfre_add_cart_frm')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_add_to_cart_form', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_aftr_add_cart_frm')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_add_to_cart_form', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_pro_meta_strt')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_product_meta_start', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
			elseif ($rtw_priority_option['rtwwdpdl_offer_tbl_prodct'] == 'rtw_pro_meta_end')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_product_meta_end', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_product_page', 5);
			}
		}
		$message_settings = get_option('rtwwdpdl_message_settings', array());
		if (!empty($message_settings) && isset($message_settings['rtwwdpdl_enable_message']) && $message_settings['rtwwdpdl_enable_message'] == 1)
		{
			if ($message_settings['rtwwdpdl_message_position'] == 1)
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_shop_loop', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 10);
			}
			elseif ($message_settings['rtwwdpdl_message_position'] == 2)
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_shop_loop', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 10);
			}
			elseif ($message_settings['rtwwdpdl_message_position'] == 3)
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_archive_description', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 10);
			}
			elseif ($message_settings['rtwwdpdl_message_position'] == 4)
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_main_content', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 10);
			}
			if ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_bfore_pro')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_single_product', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_aftr_pro')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_single_product', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_bfore_pro_sum')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_single_product_summary', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_in_pro_sum')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_single_product_summary', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_aftr_pro_sum')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_single_product_summary', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_bfre_add_cart_btn')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_add_to_cart_button', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_aftr_add_cart_btn')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_add_to_cart_button', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_bfre_add_cart_frm')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_before_add_to_cart_form', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_aftr_add_cart_frm')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_after_add_to_cart_form', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_pro_meta_strt')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_product_meta_start', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
			elseif ($message_settings['rtwwdpdl_message_pos_propage'] == 'rtw_pro_meta_end')
			{
				$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_product_meta_end', $rtwwdpdl_plugin_public, 'rtwwdpdl_offers_message', 5);
			}
		}
		$this->rtwwdpdl_loader->rtwwdpdl_add_action('woocommerce_cart_calculate_fees', $rtwwdpdl_plugin_public, 'rtwwdpdl_sale_custom_price');
		
		$this->rtwwdpdl_loader->rtwwdpdl_add_filter('woocommerce_cart_item_price', $rtwwdpdl_plugin_public, 'rtwwdpdl_on_display_cart_item_price_html', 10, 3);
		$this->rtwwdpdl_loader->rtwwdpdl_add_filter('woocommerce_get_price_html', $rtwwdpdl_plugin_public, 'rtwwdpd_change_product_html', 10, 3);
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_run()
	{
		$this->rtwwdpdl_loader->rtwwdpdl_run();
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function rtwwdpdl_get_plugin_name()
	{
		return $this->rtwwdpdl_plugin_name;
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woo_Dynamic_Pricing_Discounts_With_Ai_Loader    Orchestrates the hooks of the plugin.
	 */
	public function rtwwdpdl_get_loader()
	{
		return $this->rtwwdpdl_loader;
	}
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function rtwwdpdl_get_version()
	{
		return $this->rtwwdpdl_version;
	}
}
