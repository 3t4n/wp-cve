<?php
/**
 * Plugin Name: UPI QR Code Payment Gateway
 * Description: It enables a WooCommerce site to accept payments through UPI apps like Google Pay, Paytm, AmazonPay, BHIM, PhonePe or any Banking UPI app. Avoid payment gateway charges.
 * Version: 1.3.0
 * Plugin URI: http://dewtechnolab.com/project/
 * Author: Dew Technolab
 * Author URI: http://dewtechnolab.com/
 * Requires at least: 4.5
 * WC requires at least: 4.0
 * WC tested up to: 6.8.2
 * Text Domain: dew-upi-qr-code
 * Domain Path: /languages
 * License: GPLv3 or later License
 * URI: http://www.gnu.org/licenses/gpl-3.0.html
**/
/**
 * UPI QR Code Payment Gateway is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with UPI QR Code Payment Gateway plugin. If not, see <http://www.gnu.org/licenses/>.
 * 
 * @category WooCommerce
 * @package  Woo UPI QR Code Payment Gateway
 * @author   Dew technolab <dewtechnolab@gmail.com>
 * @license  http://www.gnu.org/licenses/ GNU General Public License
 * @link     http://dewtechnolab.com/
**/

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if( !defined( 'DEW_WOO_UPI_DIR_NAME' ) ) {
	define( 'DEW_WOO_UPI_DIR_NAME', 'upi-qr-code-payment-gateway' );
	define( 'DEW_WOO_UPI_DIR', WP_PLUGIN_DIR . '/' . DEW_WOO_UPI_DIR_NAME );

	if ( is_ssl() ) {
		define( 'DEW_WOO_UPI_URL', str_replace( 'http://', 'https://', WP_PLUGIN_URL . '/' . DEW_WOO_UPI_DIR_NAME ) );
		define( 'DEW_WOO_UPI_HOME_URL', home_url( '', 'https' ) );
	} else {
		define( 'DEW_WOO_UPI_URL', WP_PLUGIN_URL . '/' . DEW_WOO_UPI_DIR_NAME );  
		define( 'DEW_WOO_UPI_HOME_URL', home_url() );
	}
	define( 'DEW_WOO_UPI_CORE_DIR', DEW_WOO_UPI_DIR . '/core' );
	define( 'DEW_WOO_UPI_CLASSES_DIR', DEW_WOO_UPI_DIR . '/core/classes' );
	define( 'DEW_WOO_UPI_CLASSES_URL', DEW_WOO_UPI_URL . '/core/classes' );
	define( 'DEW_WOO_UPI_IMAGES_DIR', DEW_WOO_UPI_DIR . '/images' );
	define( 'DEW_WOO_UPI_IMAGES_URL', DEW_WOO_UPI_URL . '/images' );
	define( 'DEW_WOO_UPI_DEBUG_LOG', false );
	define( 'DEW_WOO_UPI_DEBUG_LOG_TYPE', 'DEW_ALL' );
}

if( !defined( 'FS_METHOD' ) ) {
	@define( 'FS_METHOD', 'direct' );
}

/* Defining Membership Plugin Version */ 
global $dwu_version;
$dwu_version = '1.0.0';
define( 'DEW_WOO_UPI_VERSION', $dwu_version);

global $dwu_ajaxurl;
$dwu_ajaxurl = admin_url('admin-ajax.php');

global $dwu_errors;
$dwu_errors = new WP_Error();

/**
 * Plugin Main Class
 */
global $DEW_Woo_upi;
$DEW_Woo_upi = new DEW_Woo_upi();

if( file_exists( DEW_WOO_UPI_CLASSES_DIR . "/class.notice.php")){
	require_once( DEW_WOO_UPI_CLASSES_DIR . "/class.notice.php");
}

if( file_exists( DEW_WOO_UPI_CLASSES_DIR . "/class.payment.php")){
	require_once( DEW_WOO_UPI_CLASSES_DIR . "/class.payment.php");
}

if( file_exists( DEW_WOO_UPI_CLASSES_DIR . "/class.donate.php")){
	require_once( DEW_WOO_UPI_CLASSES_DIR . "/class.donate.php");
}

class DEW_Woo_upi { 
	function __construct() {
		register_activation_hook( __FILE__, array( 'DEW_Woo_upi', 'install' ) );
		register_activation_hook( __FILE__, array( 'DEW_Woo_upi', 'dwu_check_network_activation' ) );
		add_action( 'plugins_loaded', array( $this, 'dwu_load_textdomain' ) );
		add_action( 'admin_bar_menu', array( $this, 'dwu_add_debug_bar_menu' ), 999, 1 );
		add_filter( 'plugin_action_links', array( $this, 'dwu_add_action_links' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( $this, 'dew_upi_plugin_meta_links' ), 10, 2 );
		add_filter( 'woocommerce_payment_gateways', array( $this, 'dwu_woocommerce_payment_add_gateway_class' ) );
		register_uninstall_hook( __FILE__, array( 'DEW_Woo_upi', 'uninstall' ) );
	}
	public static function install() {
		global $DEW_Woo_upi, $dwu_version;
		$_version = get_option('dwu_version');
		if ( empty( $_version ) || $_version == '' ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			@set_time_limit(0);
			update_option( 'dwu_version', $dwu_version );
			update_option( 'dwu_plugin_activated', 1 );
			/* Plugin Action Hook After Install Process */
			do_action( 'dwu_after_activation_hook' );
			do_action( 'dwu_after_install' );
		}
		set_transient( 'dwu-admin-notice-on-activation', true, 5 );
	}
	/**
	 * Restrict Network Activation
	 */
	public static function dwu_check_network_activation( $network_wide ) {
		if (!$network_wide)
			return;

		deactivate_plugins( plugin_basename( __FILE__ ), TRUE, TRUE );
		header( 'Location: ' . network_admin_url( 'plugins.php?deactivate=true' ) );
		exit;
	}
	function dwu_load_textdomain() {
		load_plugin_textdomain( 'dew-upi-qr-code', false, dirname( DEW_WOO_UPI_DIR_NAME ) . '/languages/' ); 
	}
	function dwu_add_debug_bar_menu( $wp_admin_bar ) {
		/* Admin Bar Menu */
		if ( !current_user_can( 'administrator' ) || DEW_WOO_UPI_DEBUG_LOG == false) {
			return;
		}
		$args = array(
			'id' => 'dwu_debug_menu',
			'title' => __('WOO UPI Debug', 'dew-upi-qr-code'),
			'parent' => 'top-secondary',
			'href' => '#',
			'meta' => array(
				'class' => 'dwu_admin_bar_debug_menu'
			)
		);
		echo "<style type='text/css'>";
		echo ".dwu_admin_bar_debug_menu{
				background:#ff9a8d !Important;
			}";
		echo "</style>";
		$wp_admin_bar->add_menu($args);
	}
	function dwu_add_action_links( $links, $file ) {
		if ( $file == plugin_basename(__FILE__) ) {
			$dwu_links = array(
				'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=dew-wc-upi' ) . '">' . __( 'Settings', 'dew-upi-qr-code' ) . '</a>',
			);
			return array_merge( $dwu_links, $links );
		}
		return $links;
	}
	function dew_upi_plugin_meta_links( $links, $file ) {
		if ( $file == plugin_basename(__FILE__) )
			return array_merge( $links, 
				array( '<a href="https://wordpress.org/support/plugin/upi-qr-code-payment-gateway/" target="_blank">' . __( 'Support', 'dew-upi-qr-code' ) . '</a>' ),
				array( '<a href="https://wordpress.org/plugins/upi-qr-code-payment-gateway/#faq" target="_blank">' . __( 'FAQ', 'dew-upi-qr-code' ) . '</a>' ),
				//array( '<a href="https://www.paypal.me/dewtecholab/" target="_blank">' . __( 'Donate', 'dew-upi-qr-code' ) . '</a>' )
			);
		return $links;
	}
	//add Gateway to woocommerce
	function dwu_woocommerce_payment_add_gateway_class( $gateways ) {
		$gateways[] = 'DWU_Payment_Gateway'; // class name
		return $gateways;
	}
	function dwu_write_response( $response_data, $file_name = '' ) {
		global $wp, $wpdb, $wp_filesystem;
		if ( !empty( $file_name ) ) {
			$file_path = DEW_WOO_UPI_DIR . '/log/' . $file_name;
		} else {
			$file_path = DEW_WOO_UPI_DIR . '/log/response.txt';
		}
		if (file_exists(ABSPATH . 'wp-admin/includes/file.php')) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			if (false === ($creds = request_filesystem_credentials($file_path, '', false, false) )) {
				/**
				 * if we get here, then we don't have credentials yet,
				 * but have just produced a form for the user to fill in,
				 * so stop processing for now
				 */
				return true; /* stop the normal page form from displaying */
			}
			/* now we have some credentials, try to get the wp_filesystem running */
			if (!WP_Filesystem($creds)) {
				/* our credentials were no good, ask the user for them again */
				request_filesystem_credentials($file_path, $method, true, false);
				return true;
			}
			@$file_data = $wp_filesystem->get_contents($file_path);
			$file_data .= $response_data;
			$file_data .= "\r\n===========================================================================\r\n";
			$breaks = array("<br />", "<br>", "<br/>");
			$file_data = str_ireplace($breaks, "\r\n", $file_data);
			
			@$write_file = $wp_filesystem->put_contents($file_path, $file_data, 0755);
			if (!$write_file) {
				/* _e('Error Saving Log.', 'ARMember'); */
			}
		}
		return;
	}
	public static function uninstall() {
		global $wpdb;
		$dwu_uninstall = true;
		if ( is_multisite() ) {
			$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
			if ($blogs) {
				foreach ($blogs as $blog) {
					switch_to_blog($blog['blog_id']);
					delete_option("dwu_version");
					if( $dwu_uninstall ){
						self::dwu_uninstall();
					}
				}
				restore_current_blog();
			}
		} else {
			if( $dwu_uninstall ){
				self::dwu_uninstall();
			}
		}
		/* Plugin Action Hook After Uninstall Process */
		do_action('dwu_after_uninstall');
	}
	public static function dwu_uninstall() {
		global $wpdb;
		delete_option( 'dwu_version' );
		delete_option( 'dwu_no_thanks_rating_notice' );
		delete_option( 'dwu_dismiss_rating_notice' );
		delete_option( 'dwu_dismissed_time' );
		delete_option( 'dwu_installed_time' );
		return true;
	}
}
