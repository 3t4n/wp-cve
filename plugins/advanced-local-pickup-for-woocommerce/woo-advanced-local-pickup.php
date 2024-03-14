<?php
/*
* Plugin Name: Advanced Local Pickup for WooCommerce
* Plugin URI:  https://www.zorem.com/shop
* Description: The Advanced Local Pickup (ALP) helps you handle local pickup orders more conveniently by extending the WooCommerce Local Pickup shipping method.
* Author: zorem
* Author URI: https://www.zorem.com/
* Version: 1.6.3
* Text Domain: advanced-local-pickup-for-woocommerce
* Domain Path: /lang/
* WC requires at least: 4.0
* WC tested up to: 8.5.2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woocommerce_Local_Pickup {
	
	/**
	 * Local Pickup for WooCommerce
	 *
	 * @var string
	 */
	public $version = '1.6.3';
	public $admin;
	public $install;
	public $table;
	public $plugin_path;
	public $customizer;
	
	/**
	 * Constructor
	 *
	 * @since  1.0.0
	*/
	public function __construct() {
		
		// Check if Wocoomerce is activated
		register_activation_hook( __FILE__, array( $this, 'on_activation' ) );
		if ( !$this->is_alp_pro_active() ) {
			if ( $this->is_wc_active() ) {
				$this->includes();
				$this->init();			
				add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
				add_action( 'admin_footer', array( $this, 'uninstall_notice') );
			}
		}
	}
	
	/**
	 * Callback on activation and allow to activate if pro deactivated
	 *
	 * @since  1.0.0
	*/
	public function on_activation() {

		// Require parent plugin
		if ( is_plugin_active( 'advanced-local-pickup-pro/advanced-local-pickup-pro.php' ) && current_user_can( 'activate_plugins' ) ) {
			
			//admin notice for not allow activate plugin
			wp_redirect( admin_url() . 'plugins.php?alp-not-allow=true' );
			exit;
		}
	}
	
	/**
	 * Check if ALP PRO is active
	 *
	 * @since  1.0.0
	 * @return bool
	*/
	private function is_alp_pro_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active( 'advanced-local-pickup-pro/advanced-local-pickup-pro.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}

			
		return $is_active;
	}
	
	/**
	 * Check if WooCommerce is active
	 *
	 * @since  1.0.0
	 * @return bool
	*/
	private function is_wc_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}		

		// Do the WC active check
		if ( false === $is_active ) {
			add_action( 'admin_notices', array( $this, 'notice_activate_wc' ) );
		}		
		return $is_active;
	}
	
	/**
	 * Display WC active notice
	 *
	 * @since  1.0.0
	*/
	public function notice_activate_wc() {
		?>
		<div class="error">
			<p><?php printf( esc_html( 'Please install and activate %sWooCommerce%s for WC local pickup to work!', 'advanced-local-pickup-for-woocommerce' ), '<a href="' . esc_url(admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' )) . '">', '</a>' ); ?></p>
		</div>
		<?php
	}
	
	/**
	 * Include plugin file.
	 *
	 * @since 1.0.0
	 *
	 */	
	public function includes() {		
		require_once $this->get_plugin_path() . '/include/wc-local-pickup-admin.php';
		$this->admin = WC_Local_Pickup_admin::get_instance();	

		require_once $this->get_plugin_path() . '/include/wc-local-pickup-installation.php';
		$this->install = WC_Local_Pickup_install::get_instance();

		//customizer
		require_once $this->get_plugin_path() . '/include/customizer/customizer-admin.php';	
		$this->customizer = WC_Local_Pickup_Customizer::get_instance();
	}

	/**
	 * Initialize plugin
	 *
	 * @since  1.0.0
	*/
	private function init() {
		
		//callback on activate plugin
		//register_activation_hook( __FILE__, array( $this, 'table_create' ) );
		
		// Load plugin textdomain
		add_action('plugins_loaded', array($this, 'load_textdomain'));
		
		//callback for migration function
		add_action( 'admin_init', array( $this->install , 'wclp_update_install_callback' ) );
		
		//load javascript in admin
		add_action('admin_enqueue_scripts', array( $this, 'alp_script_enqueue' ) );
		
		//callback for add action link for plugin page	
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this , 'my_plugin_action_links' ));
		
		add_action( 'wp_ajax_reassign_order_status', array( $this, 'reassign_order_status' ) );
		
		// Add to custom email for WC Order statuses
		add_filter( 'woocommerce_email_classes', array( $this, 'custom_init_emails' ) );
		add_action( 'woocommerce_order_status_ready-pickup', array( $this, 'email_trigger_ready_pickup' ), 10, 2 );
		add_action( 'woocommerce_order_status_pickup', array( $this, 'email_trigger_pickup' ), 10, 2 );
	}
	
	/**
	 * Database functions
	*/
	public function table_create() {
		
		global $wpdb;
		$this->table = $wpdb->prefix . 'alp_pickup_location';
		
		if ($wpdb->get_var($wpdb->prepare('show tables like %1s', $this->table)) != $this->table) {
			$create_table_query = "
				CREATE TABLE IF NOT EXISTS `{$this->table}` (
					`id` int NOT NULL AUTO_INCREMENT,
					`store_name` text NULL,
					`store_address` text NULL,
					`store_address_2` text NULL,
					`store_city` text NULL,
					`store_country` text NULL,
					`store_postcode` text NULL,
					`store_phone` text NULL,
					`store_time_format` text NULL,
					`store_days` text NULL,
					`store_instruction` text NULL,
					PRIMARY KEY (id)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
			";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $create_table_query );
		}

	}

	
	/*
	* Include file on plugin load
	*/
	public function on_plugins_loaded() {		
		require_once $this->get_plugin_path() . '/include/wclp-wc-admin-notices.php';	
	}
	
	/*
	* load text domain
	*/
	public function load_textdomain() {
		load_plugin_textdomain( 'advanced-local-pickup-for-woocommerce', false, plugin_dir_path( plugin_basename(__FILE__) ) . 'lang/' );
	}
	
	/**
	 * Gets the absolute plugin path without a trailing slash, e.g.
	 * /path/to/wp-content/plugins/plugin-directory.
	 *
	 * @return string plugin path
	 */
	public function get_plugin_path() {
		if ( isset( $this->plugin_path ) ) {
			return $this->plugin_path;
		}

		$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		return $this->plugin_path;
	}
	
	public static function get_plugin_domain() {
		return __FILE__;
	}
	
	/*
	* plugin file directory function
	*/	
	public function plugin_dir_url() {
		return plugin_dir_url( __FILE__ );
	}
	
	/**
	 * Add plugin action links.
	 *
	 * Add a link to the settings page on the plugins.php page.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */
	public function my_plugin_action_links( $links ) {
		$links = array_merge( array(
			'<a href="' . esc_url( admin_url( '/admin.php?page=local_pickup' ) ) . '">' . esc_html( 'Settings', 'woocommerce' ) . '</a>'
		), array(
			'<a href="' . esc_url( 'https://www.zorem.com/docs/advanced-local-pickup-for-woocommerce/?utm_source=wp-admin&utm_medium=ALP&utm_campaign=docs' ) . '" target="_blank">' . esc_html( 'Docs', 'woocommerce' ) . '</a>'
		), array(
			'<a href="' . esc_url( 'https://wordpress.org/support/plugin/advanced-local-pickup-for-woocommerce/reviews/#new-post' ) . '" target="_blank">' . esc_html( 'Review', 'woocommerce' ) . '</a>'
		), $links );
		
		if (!class_exists('Advanced_local_pickup_PRO')) {
			$links = array_merge( $links, array(
				'<a target="_blank" style="color: #45b450; font-weight: bold;" href="' . esc_url( 'https://www.zorem.com/product/advanced-local-pickup-pro/?utm_source=wp-admin&utm_medium=ALPPRO&utm_campaign=add-ons') . '">' . __( 'Go Pro', 'woocommerce' ) . '</a>'
			) );
		}
		
		return $links;
	}
	
	/*
	* Add admin javascript
	*/	
	public function alp_script_enqueue() {
		
		
		// Add condition for css & js include for admin page  
		if (!isset($_GET['page'])) {
				return;
		}
		if (  'local_pickup' != $_GET['page'] ) {
			return;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';	
		// Add the color picker css file       
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
			
		// Add the WP Media 
		wp_enqueue_media();
		
		// Add tiptip js and css file
		wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'woocommerce_admin_styles' );
	
		wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), WC_VERSION, true );
		wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );
		wp_enqueue_script( 'jquery-tiptip' );
		wp_enqueue_script( 'jquery-blockui' );
		
		wp_enqueue_style('select2-wclp', plugins_url('assets/css/select2.min.css', __FILE__ ), array(), $this->version);
		wp_enqueue_script('select2-wclp', plugins_url('assets/js/select2.min.js', __FILE__), array(), $this->version);
		
		wp_enqueue_script( 'alp-admin-js', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array(), $this->version );
		wp_enqueue_style( 'alp-admin-css', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array(), $this->version );
		
		wp_localize_script( 'alp-admin-js', 'alp_object', 
			array( 
				'admin_url' => admin_url(),
				'nonce' => wp_create_nonce('alp-ajax-nonce')
			) 
		);
	}
	
	// Add to custom email for WC Order statuses
	public function custom_init_emails( $emails ) {

		// Include the email class file if it's not included already
		if (!defined('WC_LOCAL_PICKUP_TEMPLATE_PATH')) {
			define('WC_LOCAL_PICKUP_TEMPLATE_PATH', wc_local_pickup()->get_plugin_path() . '/templates/');
		}
		$ready_for_pickup = get_option( 'wclp_status_ready_pickup', 0);
		if (true == $ready_for_pickup) {
			if ( ! isset( $emails[ 'WC_Email_Customer_Ready_Pickup_Order' ] ) ) {
				$emails[ 'WC_Email_Customer_Ready_Pickup_Order' ] = include_once( 'include/emails/ready-pickup-order.php' );
			}
		}
		$picked = get_option( 'wclp_status_picked_up', 0);
		if (true == $picked) {
			if ( ! isset( $emails[ 'WC_Email_Customer_Pickup_Order' ] ) ) {
				$emails[ 'WC_Email_Customer_Pickup_Order' ] = include_once( 'include/emails/pickup-order.php' );
			}
		}

		return $emails;		
	}
	
	/**
	 * Send email when order status change to "pickuped"
	 *
	*/
	public function email_trigger_ready_pickup( $order_id, $order = false ) {
		$ready_for_pickup = get_option( 'wclp_status_ready_pickup', 0);
		if (true == $ready_for_pickup) {
			WC()->mailer()->emails['WC_Email_Customer_Ready_Pickup_Order']->trigger( $order_id, $order );
		}
	}
	
	/**
	 * Send email when order status change to "pickuped"
	 *
	*/
	public function email_trigger_pickup( $order_id, $order = false ) {		
		$picked = get_option( 'wclp_status_picked_up', 0);
		if (true == $picked) {
			WC()->mailer()->emails['WC_Email_Customer_Pickup_Order']->trigger( $order_id, $order );
		}
	}
	
	/*
	* Plugin uninstall code 
	*/	
	public function uninstall_notice() {
		$screen = get_current_screen();
		
		if ('plugins.php' != $screen->parent_file) {
			return;
		}
		
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';	
		wp_enqueue_style( 'alp-admin-js', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array(), $this->version );
		wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI' . $suffix . '.js', array( 'jquery' ), '2.70', true );
		wp_enqueue_script( 'jquery-blockui' );
		
		$ready_pickup_count = wc_orders_count( 'ready-pickup' );
		$pickup_count = wc_orders_count( 'pickup' );
		
		$order_statuses = wc_get_order_statuses();
		unset($order_statuses['wc-ready-pickup']);				
		unset($order_statuses['wc-pickup']);
		
		if ($ready_pickup_count > 0 || $pickup_count > 0) { 
			?>
			<script>
				jQuery(document).on("click","[data-slug='advanced-local-pickup-for-woocommerce'] .deactivate a",function(e) {			
					e.preventDefault();
					jQuery('.alp_uninstall_popup').show();
					var theHREF = jQuery(this).attr("href");
					jQuery(document).on("click",".alp_uninstall_plugin",function(e) {
						jQuery("body").block({
							message: null,
							overlayCSS: {
								background: "#fff",
								opacity: .6
							}	
						});	
						var form = jQuery('#wplp_order_reassign_form');
						jQuery.ajax({
							url: ajaxurl,		
							data: form.serialize(),		
							type: 'POST',		
							success: function(response) {
								jQuery("body").unblock();			
								window.location.href = theHREF;
							},
							error: function(response) {
								console.log(response);			
							}
						});				
					});			
				});
				jQuery(document).on("click",".alp_popupclose",function(e) {
					jQuery('.alp_uninstall_popup').hide();
				});
				jQuery(document).on("click",".alp_uninstall_close",function(e) {
					jQuery('.alp_uninstall_popup').hide();
				});
			</script>
			<div id="" class="alp_popupwrapper alp_uninstall_popup" style="display:none;">
				<div class="alp_popuprow" style="text-align: left;max-width: 380px;">
					<h3 class="alp_popup_title">Advanced Local Pickup for WooCommerce</h3>
					<form method="post" id="wplp_order_reassign_form">					
					<?php if ( $ready_pickup_count > 0 ) { ?>
						
						<p><?php echo sprintf(esc_html('We detected %s orders that use the Ready for pickup order status, You can reassign these orders to a different status', 'advanced-local-pickup-for-woocommerce'), esc_html($ready_pickup_count)); ?></p>
						
						<select id="reassign_ready_pickup_order" name="reassign_ready_pickup_order" class="reassign_select">
							<option value=""><?php esc_html_e('Select', 'woocommerce'); ?></option>
							<?php foreach ($order_statuses as $key => $status) { ?>
								<option value="<?php echo esc_html($key); ?>"><?php echo esc_html($status); ?></option>
							<?php } ?>
						</select>
					
					<?php } ?>
					<?php if ( $pickup_count > 0 ) { ?>
						
						<p><?php echo sprintf(esc_html('We detected %s orders that use the Picked up order status, You can reassign these orders to a different status', 'advanced-local-pickup-for-woocommerce'), esc_html($pickup_count)); ?></p>					
						
						<select id="reassign_pickedup_order" name="reassign_pickedup_order" class="reassign_select">
							<option value=""><?php esc_html_e('Select', 'woocommerce'); ?></option>
							<?php foreach ($order_statuses as $key => $status) { ?>
								<option value="<?php echo esc_html($key); ?>"><?php echo esc_html($status); ?></option>
							<?php } ?>
						</select>
					
					<?php } ?>				
					<p class="" style="text-align:left;">
						<input type="hidden" name="action" value="reassign_order_status">
						<input type="hidden" name="nonce" value="<?php echo esc_html(wp_create_nonce('alp-ajax-nonce')); ?>">
						<input type="button" value="Deactivate" class="alp_uninstall_plugin button-primary btn_green">
						<input type="button" value="Close" class="alp_uninstall_close button-primary btn_red">				
					</p>
				</form>	
				</div>
				<div class="alp_popupclose"></div>
			</div>		
		<?php 
		} 
	}
	
	public function reassign_order_status() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field($_POST['nonce']) : '';
		if ( ! wp_verify_nonce( $nonce, 'alp-ajax-nonce' ) ) {
			die();
		}
		
		$reassign_ready_pickup_order = isset($_POST['reassign_ready_pickup_order']) ? sanitize_text_field($_POST['reassign_ready_pickup_order']) : '';
		$reassign_pickedup_order = isset($_POST['reassign_pickedup_order']) ? sanitize_text_field($_POST['reassign_pickedup_order']) : '';
		
		if ('' != $reassign_ready_pickup_order) {
			
			$args = array(
				'status' => 'ready-pickup',
				'limit' => '-1',
			);
			
			$orders = wc_get_orders( $args );
			
			foreach ($orders as $order) {				
				$order_id = $order->get_id();
				$order = new WC_Order($order_id);
				$order->update_status($reassign_ready_pickup_order);				
			}			
		}
		
		if ('' != $reassign_pickedup_order) {
			
			$args = array(
				'status' => 'pickup',
				'limit' => '-1',
			);
			
			$ps_orders = wc_get_orders( $args );
			
			foreach ($ps_orders as $order) {				
				$order_id = $order->get_id();
				$order = new WC_Order($order_id);
				$order->update_status($reassign_pickedup_order);				
			}			
		}
		exit;
		echo 1;
		die();		
	}
}

/**
 * Returns an instance of Woocommerce_local_pickup.
 *
 * @since 1.6.5
 * @version 1.6.5
 *
 * @return Woocommerce_local_pickup
*/
function wc_local_pickup() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new Woocommerce_local_pickup();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
wc_local_pickup();

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

if ( ! function_exists( 'zorem_tracking' ) ) {
	function zorem_tracking() {
		require_once dirname(__FILE__) . '/zorem-tracking/zorem-tracking.php';
		$plugin_name = 'Advanced Local Pickup for WooCommerce';
		$plugin_slug = 'advanced-local-pickup-for-woocommerce';
		$user_id = '1';
		$setting_page_type = 'submenu';
		$setting_page_location =  "A submenu under other plugin's top level menu";
		$parent_menu_type = 'A custom top-level admin menu (admin.php)';
		$menu_slug = 'local_pickup';
		$plugin_id = '14';
		$zorem_tracking = WC_Trackers::get_instance( $plugin_name, $plugin_slug, $user_id, $setting_page_type, $setting_page_location, $parent_menu_type, $menu_slug, $plugin_id );
		return $zorem_tracking;
	}
	
	'zorem_tracking'();
}
