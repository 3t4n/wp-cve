<?php
/*
 * @link              http://wpthemespace.com
 * @since             1.0.0
 * @package           Magical products Display
 *
 * @wordpress-plugin
 * Plugin Name:       Magical products Display
 * Plugin URI:        http://wpthemespace.com
 * Description:       Customizable Elementor Widgets for Beautiful WooCommerce Product Displays.
 * Version:           1.1.16
 * Author:            Noor alam
 * Author URI:        http://wpthemespace.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       magical-products-display
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


/**
 * Main Magical Products Display Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class magicalProductsDisplay
{

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.1.16';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.6.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.6';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var magicalProductsDisplay The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return magicalProductsDisplay An instance of the class.
	 */
	public static function instance()
	{

		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct()
	{
		$this->define_constants();
		add_action('init', [$this, 'i18n']);
		add_action('plugins_loaded', [$this, 'init']);
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n()
	{

		load_plugin_textdomain('magical-products-display');
	}

	public function define_constants()
	{
		define('MAGICAL_PRODUCTS_DISPLAY_VERSION', self::VERSION);
		define('MAGICAL_PRODUCTS_DISPLAY_FILE', __FILE__);
		define('MAGICAL_PRODUCTS_DISPLAY_DIR', plugin_dir_path(__FILE__));
		define('MAGICAL_PRODUCTS_DISPLAY_URL', plugins_url('', MAGICAL_PRODUCTS_DISPLAY_FILE));
		define('MAGICAL_PRODUCTS_DISPLAY_ASSETS', MAGICAL_PRODUCTS_DISPLAY_URL . '/assets/');
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init()
	{

		// Check if Elementor installed and activated
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
			return;
		}
		// Check if woocommerce3 installed and activated
		if (!class_exists('WooCommerce')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_woo_plugin']);
			return;
		}
		// Check if Elementor installed and activated
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
			return;
		}

		// Check for required Elementor version
		if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
			return;
		}

		// Check for required PHP version
		if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
			add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
			return;
		}
		$is_plugin_activated = get_option('mpd_plugin_activated');
		if ('yes' !== $is_plugin_activated) {
			update_option('mpd_plugin_activated', 'yes');
		}
		$mpd_install_date = get_option('mpd_install_date');
		if (empty($mpd_install_date)) {
			update_option('mpd_install_date', current_time('mysql'));
		}
		//Check pro version
		if ((in_array('magical-products-display-pro/magical-products-display-pro.php', apply_filters('active_plugins', get_option('active_plugins'))) && get_option('mgppro_has_valid_lic') == 'yes') || (get_option('space_has_pro') == 'yes')) {
			update_option('mgppro_is_active', 'yes');
		} else {
			update_option('mgppro_is_active', 'no');
		}

		// Load all style & scripts
		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/assets-managment.php');
		// Load function file
		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/functions.php');

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/helplink.php');


		if (get_option('mgppro_is_active') != 'yes') {
			// Load admin info
			require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/admin-info.php');
			require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/pro-widgets.php');
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'admin_adminpro_link']);
		}

		// Add Plugin actions
		//admin scritps added
		add_action('elementor/widgets/register', [$this, 'init_widgets']);
		add_action('elementor/elements/categories_registered', [$this, 'register_new_category']);
	}
	/*
		public function register_new_category($manager){
		$manager->add_category('mgproductwoo',[
			'title' => esc_html__('Magical Products Display','magical-products-display'),
			'icon' => 'eicon-products',
		]);

		
	}
	*/

	public function register_new_category(\Elementor\Elements_Manager $elements_manager)
	{
		if (get_option('mgppro_is_active', 'no') == 'yes') {
			$title_text = esc_html__('Magical Products Display Pro', 'magical-products-display');
		} else {
			$title_text = esc_html__('Magical Products Display', 'magical-products-display');
		}

		//add our categories
		$category_prefix = 'mpd-';

		$elements_manager->add_category(
			$category_prefix . 'productwoo',
			[
				'title' => $title_text,
				'icon' => 'eicon-products',
			]
		);
		$reorder_cats = function () use ($category_prefix) {
			uksort($this->categories, function ($keyOne, $keyTwo) use ($category_prefix) {
				if (substr($keyOne, 0, 4) == $category_prefix) {
					return -1;
				}
				if (substr($keyTwo, 0, 4) == $category_prefix) {
					return 1;
				}
				return 0;
			});
		};
		$reorder_cats->call($elements_manager);
	}



	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);


		if (file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php')) {
			$magial_eactive_url = wp_nonce_url('plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php');
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
				esc_html__('%1$s requires %2$s plugin, which is currently NOT RUNNING  %3$s', 'magical-products-display'),
				'<strong>' . esc_html__('Magical Products Display', 'magical-products-display') . '</strong>',
				'<strong>' . esc_html__('Elementor', 'magical-products-display') . '</strong>',
				'<a class="button button-primary" style="margin-left:20px" href="' . $magial_eactive_url . '">' . __('Activate Elementor', 'magical-products-display') . '</a>'

			);
		} else {

			$magial_einstall_url =  wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
				esc_html__('%1$s requires %2$s plugin, which is currently NOT RUNNING  %3$s', 'magical-products-display'),
				'<strong>' . esc_html__('Magical Products Display', 'magical-products-display') . '</strong>',
				'<strong>' . esc_html__('Elementor', 'magical-products-display') . '</strong>',
				'<a class="button button-primary" style="margin-left:20px" href="' . $magial_einstall_url . '">' . __('Install Elementor', 'magical-products-display') . '</a>'

			);
		}



		printf('<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $message);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have WooCommerce installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_woo_plugin()
	{

		if (file_exists(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php')) {
			$magial_eactive_url = wp_nonce_url('plugins.php?action=activate&plugin=woocommerce/woocommerce.php&plugin_status=all&paged=1', 'activate-plugin_woocommerce/woocommerce.php');
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
				esc_html__('%1$s requires %2$s plugin, which is currently NOT RUNNING  %3$s', 'magical-products-display'),
				'<strong>' . esc_html__('Magical Products Display', 'magical-products-display') . '</strong>',
				'<strong>' . esc_html__('woocommerce', 'magical-products-display') . '</strong>',
				'<a class="button button-primary" style="margin-left:20px" href="' . $magial_eactive_url . '">' . __('Activate WooCommerce', 'magical-products-display') . '</a>'

			);
		} else {

			$magial_einstall_url =  wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=woocommerce'), 'install-plugin_woocommerce');
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
				esc_html__('%1$s requires %2$s plugin, which is currently NOT RUNNING  %3$s', 'magical-products-display'),
				'<strong>' . esc_html__('Magical Products Display', 'magical-products-display') . '</strong>',
				'<strong>' . esc_html__('woocommerce', 'magical-products-display') . '</strong>',
				'<a class="button button-primary" style="margin-left:20px" href="' . $magial_einstall_url . '">' . __('Install woocommerce', 'magical-products-display') . '</a>'

			);
		}



		printf('<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', $message);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'magical-products-display'),
			'<strong>' . esc_html__('Magical Products Display', 'magical-products-display') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'magical-products-display') . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version()
	{

		if (isset($_GET['activate'])) unset($_GET['activate']);

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'magical-products-display'),
			'<strong>' . esc_html__('Magical Products Display', 'magical-products-display') . '</strong>',
			'<strong>' . esc_html__('PHP', 'magical-products-display') . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
	}


	public function admin_adminpro_link($links)
	{
		$newlink = sprintf("<a target='_blank' href='%s'><span style='color:red;font-weight:bold'>%s</span></a>", esc_url('https://mp.wpteamx.com/magical-products-display-pro-pricing/#pricing-mpd'), __('Upgrade Now', 'magical-products-display'));
		if (get_option('mgppro_is_active') != 'yes') {
			$links[] = $newlink;
		}
		return $links;
	}


	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init_widgets($widgets_manager)
	{

		// Register widget
		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/products-grid.php');
		$widgets_manager->register(new \mgProducts_Grid());

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/products-list.php');
		$widgets_manager->register(new \mgProducts_List());

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/products-slider.php');
		$widgets_manager->register(new \mgProducts_slider());

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/products-carousel.php');
		$widgets_manager->register(new \mgProducts_carousel());

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/testimonial-carousel.php');
		$widgets_manager->register(new \mgp_TestimonialCarousel());

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/products-tab.php');
		$widgets_manager->register(new \mgProducts_Tab());

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/porducts-cat.php');
		$widgets_manager->register(new \mgProducts_cats());

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/products-awesome-list.php');
		$widgets_manager->register(new \mgProducts_AwesomeList());

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/pricing-table.php');
		$widgets_manager->register(new \mgProduct_Pricing_Table());

		require_once(MAGICAL_PRODUCTS_DISPLAY_DIR . '/includes/widgets/accordion-widget.php');
		$widgets_manager->register(new \mgProduct_Accordion());
	}
}

magicalProductsDisplay::instance();
