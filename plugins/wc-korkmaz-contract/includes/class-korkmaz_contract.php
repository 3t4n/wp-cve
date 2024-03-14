<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://yemlihakorkmaz.com
 * @since      1.0.0
 *
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/includes
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
 * @package    Korkmaz_contract
 * @subpackage Korkmaz_contract/includes
 * @author     Yemliha KORKMAZ <yemlihakorkmaz@hotmail.com>
 */
class Korkmaz_contract
{
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Korkmaz_woo_sales_contract_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
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
	public function __construct()
	{
		if (defined('KORKMAZ_CONTRACT_VERSION')) {
			$this->version = KORKMAZ_CONTRACT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'korkmaz_contract';
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
	 * - Korkmaz_woo_sales_contract_Loader. Orchestrates the hooks of the plugin.
	 * - Korkmaz_contract_i18n. Defines internationalization functionality.
	 * - Korkmaz_woo_sales_contract_Admin. Defines all hooks for the admin area.
	 * - Korkmaz_contract_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-korkmaz_contract-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-korkmaz_contract-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-korkmaz_contract-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-korkmaz_contract-public.php';
		/**
		 * Admin Bölümü Taslak Ekrani
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-korkmaz_contract-taslak-ekrani.php';
		/**
		 * Pdf Classs
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-korkmaz_contract_pdf.php';
		$this->loader = new Korkmaz_woo_sales_contract_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Korkmaz_contract_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Korkmaz_contract_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_korkmaz_contract');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Korkmaz_woo_sales_contract_Admin($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('admin_init', $plugin_admin, 'alanlar_settings_init');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_menu', $plugin_admin, 'korkmaz_woo_sales_contract_menu');
		$this->loader->add_action('woocommerce_admin_order_data_after_shipping_address', $plugin_admin, 'butonlari_goster_admin');
		$this->loader->add_action('woocommerce_admin_order_data_after_billing_address', $plugin_admin, 'vergi_no_dairesi');
		$this->loader->add_action('admin_head', $plugin_admin, 'kisayol_ekle');
		$this->loader->add_filter('mce_external_plugins', $plugin_admin, 'js_buton_ekle');
		$this->loader->add_filter('mce_buttons', $plugin_admin, 'buton_ekle');
		if (get_option('sozlesme_ozellik_1') == 1) {
			$this->loader->add_action('manage_shop_order_posts_custom_column', $plugin_admin, 'custom_orders_list_column_content', 20, 2);
			$this->loader->add_action('manage_edit-shop_order_columns', $plugin_admin, 'custom_shop_order_column', 20);
		}
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Korkmaz_contract_Public($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('wp_footer', $plugin_public, 'sozlesme_goruntule');
		$this->loader->add_action('woocommerce_review_order_before_submit', $plugin_public, 'sozlesme_goster_odeme_sayfasi');
		$this->loader->add_action('woocommerce_checkout_process', $plugin_public, 'onayla_uyari');
		$this->loader->add_action('woocommerce_checkout_update_order_meta', $plugin_public, 'sozlesmeleri_html_olarak_kaydet');
		$this->loader->add_action('wp_ajax_bu_fonksiyon', $plugin_public, 'soz_fn_cagir');
		$this->loader->add_action('wp_ajax_nopriv_bu_fonksiyon', $plugin_public, 'soz_fn_cagir');
		$this->loader->add_action('wp_ajax_nopriv_metin_getir', $plugin_public, 'metin_getir');
		$this->loader->add_action('wp_ajax_metin_getir', $plugin_public, 'metin_getir');
		$this->loader->add_action('wp_head', $plugin_public, 'myplugin_ajaxurl');
		$this->loader->add_filter('woocommerce_checkout_fields', $plugin_public, 'odeme_ekrani_custom_input_ekle');
		$this->loader->add_action('woocommerce_thankyou', $plugin_public, 'front_sozlesme_goster');
		$this->loader->add_action('woocommerce_checkout_process', $plugin_public, 'tc_numara_dogrula');
		$this->loader->add_filter('woocommerce_email_attachments', $plugin_public, 'siparis_mail_ekle', 10, 4);
		// pdf oluşturma checkout metası update edilirken
		$this->loader->add_action('woocommerce_checkout_update_order_meta', $plugin_public, 'sozlesme_olustur_pdf');
		$this->loader->add_action('woocommerce_view_order', $plugin_public, 'view_order_sozlesme_goster', 20);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Korkmaz_woo_sales_contract_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader()
	{
		return $this->loader;
	}
}
