<?php
/*
Plugin Name: Product Price by Formula for WooCommerce
Plugin URI: https://wordpress.org/plugins/product-price-by-formula-for-woocommerce/
Description: Set formula for automatic WooCommerce product price calculation.
Version: 2.4.8
Author: ProWCPlugins
Author URI: https://prowcplugins.com
Text Domain: product-price-by-formula-for-woocommerce
Domain Path: /langs
WC requires at least: 4.8
WC tested up to: 8.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define('PPBF_FILE', __FILE__);
define('PPBF_TEXTDOMAIN', 'product-price-by-formula-for-woocommerce');
define('PPBF_DIR', plugin_dir_path(PPBF_FILE));
define('PPBF_URL', plugins_url('/', PPBF_FILE));

if ( ! class_exists( 'ProWC_PPBF' ) ) :

/**
 * Main ProWC_PPBF Class
 *
 * @class   ProWC_PPBF
 * @version 2.3.2
 * @since   1.0.0
 */
final class ProWC_PPBF {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '2.4.7';

	/**
	 * @var   ProWC_PPBF The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main ProWC_PPBF Instance
	 *
	 * Ensures only one instance of ProWC_PPBF is loaded or can be loaded
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  ProWC_PPBF - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * ProWC_PPBF Constructor
	 *
	 * @version 2.2.0
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'product-price-by-formula-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}
	}

	/**
	 * Include required core files used in admin and on the frontend
	 *
	 * @version 2.3.2
	 * @since   1.0.0
	 */
	public $core;
	public function includes() {
		// Lib
		require_once( 'includes/lib/PHPMathParser/Math.php' );
		// Functions
		require_once( 'includes/prowc-ppbf-functions.php' );
		// Core
		$this->core = require_once( 'includes/class-prowc-ppbf-core.php' );
	}

	/**
	 * Add Admin settings tab
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	public $settings;
	public function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		require_once( 'includes/settings/class-prowc-ppbf-metaboxes.php' );
		require_once( 'includes/settings/class-prowc-ppbf-settings-section.php' );
		$this->settings = array();
		$this->settings['general']           = require_once( 'includes/settings/class-prowc-ppbf-settings-general.php' );
		$this->settings['default_formula']   = require_once( 'includes/settings/class-prowc-ppbf-settings-default-formula.php' );
		// Version updated
		if ( get_option( 'prowc_ppbf_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}

		add_action('admin_enqueue_scripts', array($this, 'prowc_ppbf_admin_style'));
		add_action('admin_init',  array($this,'prowc_ppbf_notice_update'));
		add_action('admin_init',  array($this,'prowc_ppbf_plugin_notice_remindlater'));
		add_action('admin_init',  array($this,'prowc_ppbf_plugin_notice_review'));
		add_action('admin_notices', array($this,'prowc_ppbf_admin_upgrade_notice'));
		add_action('admin_notices', array($this,'prowc_ppbf_admin_review_notice'));
		add_action('plugins_loaded', array($this,'prowc_ppbf_check_version'));
		register_activation_hook( __FILE__, array($this,'prowc_ppbf_check_activation_hook'));

		// Admin notice
		if (!class_exists('WooCommerce')) {
			add_action('admin_notices', array( $this, 'fail_load') );
			return;
		}
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=prowc_ppbf' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'product-price-by-formula-for-woocommerce.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a href="https://prowcplugins.com/downloads/product-price-by-formula-for-woocommerce/">' .
				__( 'Unlock All', PPBF_TEXTDOMAIN ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Add Product Price by Formula settings tab to WooCommerce settings
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'includes/settings/class-prowc-settings-ppbf.php' );
		return $settings;
	}

	/**
	 * Update Plugin version
	 *
	 * @version 2.2.0
	 * @since   1.1.0
	 */
	function version_updated() {
		update_option( 'prowc_ppbf_version', $this->version );
	}

	/**
	 * Plugin active date.
	 *
	 * @version 2.4.2
	 * @since   2.4.2
	 */
	function prowc_ppbf_check_activation_hook() {
		$get_activation_time = date('Y-m-d', strtotime('+ 3 days'));
		add_option('prowc_ppbf_activation_time', $get_activation_time ); 
	}

	/**
	 * Update Plugin Notification settings
	 *
	 * @version 2.4.1
	 * @since   1.1.0
	 */
	function prowc_ppbf_notice_update() {
		$remdate = date('Y-m-d', strtotime('+ 7 days'));
		$rDater = get_option('prowc_ppbf_plugin_notice_nopemaybelater');
		if(!get_option('prowc_ppbf_plugin_notice_remindlater')){
			update_option('prowc_ppbf_plugin_notice_remindlater',$remdate);
			update_option('prowc_ppbf_plugin_reviewtrack', 0);
		}
		
		if($rDater && date('Y-m-d') >= $rDater) {
			update_option('prowc_ppbf_plugin_notice_remindlater',$remdate);
		}
	}

	/**
	 * Include admin script
	 *
	 * @version 2.4.1
	 * @since   1.1.0
	 */
	public function prowc_ppbf_admin_style(){
		wp_enqueue_style('prowc-wc-ppbf-admin-style', PPBF_URL . 'includes/css/admin-style.css');
		wp_enqueue_script('prowc-wc-ppbf-script', PPBF_URL . '/includes/js/admin-script.js', array ( 'jquery' ), 1.2, true);
		
		//admin rating popup js
		wp_enqueue_script('prowc-ppbf-sweetalert-min', PPBF_URL . '/includes/js/sweetalert.min.js', array ( 'jquery' ), 1.2, true);
	}

	/**
	 * Get the plugin url
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Admin Notice for WooCommerce Install & Active
	 *
	 * @version 2.3.6
	 * @since   2.3.6
	 * @return  string
	 */
	function prowc_ppbf_installed() {

		$file_path = 'woocommerce/woocommerce.php';
		$installed_plugins = get_plugins();

		return isset($installed_plugins[$file_path]);
	}

	/**
	 * Admin Notice for WooCommerce Install & Active
	 *
	 * @version 2.3.6
	 * @since   2.3.6
	 * @return  string
	 */
	function fail_load() {
		if(function_exists('WC')){
			return;
		}
		$screen = get_current_screen();
		if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
			return;
		}

		$plugin = 'woocommerce/woocommerce.php';
		if ($this->prowc_ppbf_installed()) {
			if (!current_user_can('activate_plugins')) {
				return;
			}
			$activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);

			$message = '<p><strong>' . esc_html__('Product Price by Formula for WooCommerce', PPBF_TEXTDOMAIN) . '</strong>' . esc_html__(' plugin is not working because you need to activate the Woocommerce plugin.', PPBF_TEXTDOMAIN) . '</p>';
			$message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, __('Activate Woocommerce Now', PPBF_TEXTDOMAIN)) . '</p>';
		} else {
			if (!current_user_can('install_plugins')) {
				return;
			}

			$install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=Woocommerce'), 'install-plugin_Woocommerce');

			$message = '<p><strong>' . esc_html__('Product Price by Formula for WooCommerce', PPBF_TEXTDOMAIN) . '</strong>' . esc_html__(' plugin is not working because you need to install the WooCoomerce plugin', PPBF_TEXTDOMAIN) . '</p>';
			$message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, __('Install WooCoomerce Now', PPBF_TEXTDOMAIN)) . '</p>';
		}

		echo '<div class="error"><p>' . $message . '</p></div>';
	}

	/**
	 * Database options upgrade
	 *
	 * @version 2.4.1
	 */
	function prowc_ppbf_check_version() {
		if ( version_compare( $this->version, '2.4.0', '<' )) {
			global $wpdb;
			$table_options = $wpdb->prefix.'options';
			$old_keys = $wpdb->get_results( "SELECT *  FROM `". $table_options ."` WHERE `option_name` LIKE '%alg_wc_%'");
			if (is_array($old_keys) || is_object($old_keys)){
				foreach($old_keys as $val) {
					$new_key = str_replace("alg_wc_","prowc_", $val->option_name);
					$wpdb->query( $wpdb->prepare( "UPDATE $table_options SET option_name = %s WHERE option_name = %s", $new_key, $val->option_name ) );
				}
			}
		}
	}

	/* Admin Notice for upgrade plan Start */
	function prowc_ppbf_admin_upgrade_notice() {
		$rDate = get_option('prowc_ppbf_plugin_notice_remindlater');
		if (date('Y-m-d') >= $rDate && !get_option('prowc_ppbf_plugin_notice_dismissed')) {
			?>
			<div class="notice is-dismissible prowc_ppbf_prowc_notice">
				<div class="prowc_ppbf_wrap">
					<div class="prowc_ppbf_gravatar">
						<img alt="" src="<?php echo PPBF_URL . '/includes/img/prowc_logo.png' ?>">
					</div>
					<div class="prowc_ppbf_authorname">
						<div class="notice_texts">
							<a href="<?php echo esc_url('https://prowcplugins.com/downloads/product-price-by-formula-for-woocommerce/'); ?>" target="_blank"><?php esc_html_e('Upgrade Product Price by Formula for WooCommerce', PPBF_TEXTDOMAIN); ?> </a> <?php esc_html_e('to get additional features, security, and support. ', PPBF_TEXTDOMAIN); ?> <strong><?php esc_html_e('Get 20% OFF', PPBF_TEXTDOMAIN); ?></strong><?php esc_html_e(' your upgrade, use coupon code', PPBF_TEXTDOMAIN); ?> <strong><?php esc_html_e('WP20', PPBF_TEXTDOMAIN); ?></strong>
						</div>
						<div class="prowc_ppbf_desc">
							<div class="notice_button">
								<a class="prowc_ppbf button-primary" href="<?php echo esc_url('https://prowcplugins.com/downloads/product-price-by-formula-for-woocommerce/#prowcplugin-buy-now'); ?>" target="_blank"><?php echo _e('Buy Now', PPBF_TEXTDOMAIN); ?></a>
								<a href="?prowc-ppfb-plugin-remindlater"><?php echo _e('Remind me later', PPBF_TEXTDOMAIN); ?></a>
								<a href="?prowc-ppfb-plugin-dismissed"><?php echo _e('Dismiss Notice', PPBF_TEXTDOMAIN); ?></a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"></span>
				</button>
			</div>
	<?php }
	}
	function prowc_ppbf_plugin_notice_remindlater() {
		$curDate = date('Y-m-d', strtotime(' + 7 days'));
		$rlDate = date('Y-m-d', strtotime(' + 15 days'));
		if (isset($_GET['prowc-ppfb-plugin-remindlater'])) {
			update_option('prowc_ppbf_plugin_notice_remindlater', $curDate);
			update_option('prowc_ppbf_plugin_reviewtrack', 1);
			update_option('prowc_ppbf_plugin_notice_nopemaybelater', $rlDate);
		}
		if (isset($_GET['prowc-ppfb-plugin-dismissed'])) {
			update_option('prowc_ppbf_plugin_reviewtrack', 1);
			update_option('prowc_ppbf_plugin_notice_nopemaybelater', $rlDate);
			update_option('prowc_ppbf_plugin_notice_dismissed', 'true');
		}
		if(isset($_GET['prowc-wc-ppbf-plugin-remindlater-rating'])){
			update_option('prowc_ppbf_notice_remindlater_rating', $curDate);
		}
		if (isset($_GET['prowc-wc-ppbf-plugin-dismissed-rating'])) {
			update_option('prowc_ppbf_notice_dismissed_rating', 'true');
		}
	}
	/* Admin Notice for upgrade plan End */

	/**
	 * Admin Notice for Plugin Review
	 *
	 * @version 2.4.1
	 */
	function prowc_ppbf_admin_review_notice() {

		$plugin_data = get_plugin_data( __FILE__ );	
		$plugin_name = $plugin_data['Name'];
		$rating_rDate = get_option('prowc_ppbf_notice_remindlater_rating');
		$activationDate = get_option('prowc_ppbf_activation_time');

		$rDater = get_option('prowc_ppbf_plugin_notice_nopemaybelater');
		$prowctrack = get_option('prowc_ppbf_plugin_reviewtrack');
		
		if (date('Y-m-d') >= $activationDate && date('Y-m-d') >= $rating_rDate && !get_option('prowc_ppbf_notice_dismissed_rating')) {
			?>
				<div class="notice notice-info  is-dismissible">
					<p><?php  printf( __( 'How are you liking the %s?', PPBF_TEXTDOMAIN ), esc_html( $plugin_name ) ); ?></p>
					<div class="ppbf_starts_main_div">
						<div class="stars ppbf-star">
							<input type="radio" name="star" class="star-1 ppbf" id="ppbf-star-1" value="1" />
							<label class="star-1" for="ppbf-star-1">1</label>
							<input type="radio" name="star" class="star-2 ppbf" id="ppbf-star-2" value="2" />
							<label class="star-2" for="ppbf-star-2">2</label>
							<input type="radio" name="star" class="star-3 ppbf" id="ppbf-star-3" value="3" />
							<label class="star-3" for="ppbf-star-3">3</label>
							<input type="radio" name="star" class="star-4 ppbf" id="ppbf-star-4" value="4" />
							<label class="star-4" for="ppbf-star-4">4</label>
							<input type="radio" name="star" class="star-5 ppbf" id="ppbf-star-5" value="5" />
							<label class="star-5" for="ppbf-star-5">5</label>
							<span></span>
						</div>
						<div class="notice_button">
							<a href="?prowc-wc-ppbf-plugin-remindlater-rating" class="button-secondary" ><?php _e('Remind me later', PPBF_TEXTDOMAIN); ?></a>
							<a href="?prowc-wc-ppbf-plugin-dismissed-rating" class="button-secondary" ><?php _e('Dismiss Notice', PPBF_TEXTDOMAIN); ?></a>
						</div>
					</div>
				</div>
			<?php
		}

		if ($rDater != "")
			if (date('Y-m-d') >= $rDater && $prowctrack && !get_option('prowc_ppbf_plugin_notice_alreadydid')) {
			?>
			<div class="notice is-dismissible prowc_ppbf_prowc_notice">
				<div class="prowc_ppbf_wrap">
					<div class="prowc_ppbf_gravatar">
						<img alt="" src="<?php echo PPBF_URL . '/includes/img/prowc_logo.png' ?>">
					</div>
					<div class="prowc_ppbf_authorname">
						<div class="notice_texts">
							<strong><?php esc_html_e('Are you enjoying Product Price by Formula for WooCommerce?', PPBF_TEXTDOMAIN); ?></strong>
						</div>
						<div class="prowc_ppbf_desc">
							<div class="notice_button">
								<button class="prowc_ppbf button-primary prowc_ppbf_yes"><?php echo _e('Yes!', PPBF_TEXTDOMAIN); ?></button>
								<a class="prowc_ppbf button action" href="?prowc-wc-ppfb-plugin-alreadydid"><?php echo _e('Not Really!', PPBF_TEXTDOMAIN); ?></a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>

				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"></span>
				</button>
				<div class="prowc_ppbf_prowc_notice_review_yes">
					<div class="notice_texts">
						<?php esc_html_e('That\'s awesome! Could you please do me a BIG favor and give it 5-star rating on WordPress to help us spread the word and boost our motivation?' , PPBF_TEXTDOMAIN); ?>
					</div>
					<div class="prowc_ppbf_desc">
						<div class="notice_button">
							<a class="prowc_ppbf_button button-primary" href="<?php echo esc_url('https://wordpress.org/support/plugin/product-price-by-formula-for-woocommerce/reviews/?filter=5#new-post'); ?>" target="_blank"><?php echo _e('Okay You Deserve It', PPBF_TEXTDOMAIN); ?></a>
							<a class="prowc_ppbf_button button action" href="?prowc-wc-ppfb-plugin-nopemaybelater"><?php echo _e('Nope Maybe later', PPBF_TEXTDOMAIN); ?></a>
							<a class="prowc_ppbf_button button action" href="?prowc-wc-ppfb-plugin-alreadydid"><?php echo _e('I Already Did', PPBF_TEXTDOMAIN); ?></a>
						</div>
					</div>
				</div>
			</div>
			
		<?php } ?>
	<?php }

	/**
	 * plugin notice review
	 *
	 * @version 2.4.1
	 */
	function prowc_ppbf_plugin_notice_review() {
		$curDate = date('Y-m-d', strtotime(' + 7 Days'));
		if (isset($_GET['prowc-wc-ppfb-plugin-nopemaybelater'])) {
			update_option('prowc_ppbf_plugin_notice_nopemaybelater', $curDate);
		}
		if (isset($_GET['prowc-wc-ppfb-plugin-alreadydid'])) {
			update_option('prowc_ppbf_plugin_notice_alreadydid', 'true');
		}
	}
}

endif;

if (!function_exists('prowc_product_price_free_activation')) {

	/**
	 * Add action on plugin activation
	 * 
	 * @version 2.4.3
	 * @since   2.4.3
	 */
	function prowc_product_price_free_activation() {

		// Deactivate Product Price by Formula Pro for WooCommerce
		deactivate_plugins('product-price-by-formula-pro-for-woocommerce/product-price-by-formula-pro-for-woocommerce.php'); 
		
	}
}
register_activation_hook(__FILE__, 'prowc_product_price_free_activation');


if ( ! function_exists( 'prowc_ppbf' ) ) {
	/**
	 * Returns the main instance of ProWC_PPBF to prevent the need to use globals
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  ProWC_PPBF
	 */
	function prowc_ppbf() {
		return ProWC_PPBF::instance();
	}
}

prowc_ppbf();
