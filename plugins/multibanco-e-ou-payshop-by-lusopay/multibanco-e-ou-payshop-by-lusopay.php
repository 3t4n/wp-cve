<?php
/**
 * Plugin Name: WooCommerce LusopayGateway
 * Plugin URI: https://wordpress.org/plugins/multibanco-e-ou-payshop-by-lusopay/
 * Description: Official Payment Gateway Plugin from LUSOPAY to WooCommerce for LUSOPAY Multibanco / Payshop / MBWay / Simplified Transfer / CofidisPay. In order to use this plugin you need to register in <a href="https://www.lusopay.com" target="_blank">LUSOPAY</a>. For more information how to join us <a href="https://www.lusopay.com" target="_blank">click here</a>.
 * Version: 4.0.5
 * Author: LUSOPAY
 * Author URI: https://www.lusopay.com
 * Text Domain: lusopaygateway
 * Domain Path: /languages
 * WC tested up to: 8.5.1
 * @package Lusopay
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'WC_Lusopay' ) ) :
	class WC_Lusopay {
		/**
		 * Lusopay Plugin Version
		 *
		 * @var string
		 */
		const VERSION = '4.0.5';

		/**
		* Instance of this class.
		*
		* @var object
		*/
		protected static $instance = null;


		private function __construct() {

			// Load plugin text domain
			add_action( 'init', array( $this, 'lusopaygateway_lang' ) );



			
			if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true ) || in_array( 'woocommerce/woocommerce.php', $this->lusopaygateway_active_nw_plugins(), true ) ) {
				$this->includes();
				$integration = new WC_Lusopay_Integration;
				/* Init Plugin */
				//add_action( 'plugins_loaded', 'woocommerce_lusopaygateway_init', 0 );
				add_filter( 'woocommerce_payment_gateways', array( $this, 'add_lusopaygateway_gateway' ) );
			
				/* Languages */
				//add_action( 'plugins_loaded', 'lusopaygateway_lang' );

				/* Actions Links*/
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

				
				$woocommerce_version = get_option('woocommerce_version');
				//echo $woocommerce_version;
				if ($woocommerce_version>=7){
					add_action('before_woocommerce_init', function () {
						\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
					});
				}
				/* Languages on Notes emails */
				add_action( 'woocommerce_new_customer_note', 'lusopaygateway_lang_notes', 1 );

				add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );

				add_action( 'add_meta_boxes', array( $this, 'lp_order_add_meta_box' ) );
			
				/* Add gateway do the list */
				//add_filter( 'woocommerce_payment_gateways', 'add_lusopaygateway_gateway' );

				//add_action( 'in_plugin_update_message-multibanco-e-ou-payshop-by-lusopay/multibanco-e-ou-payshop-by-lusopay.php', 'prefix_plugin_update_message', 10, 2 );
				//add_action( 'admin_notices', array( $this, 'admin_notice_lusopaygateway_instrutions_to_client' ));
				//add_action( 'admin_init', array( $this, 'admin_notice_lusopaygateway_instrutions_to_client_dismissed' ));
				if ($integration->check_if_option_name_exists("'woocommerce_lusopaygateway_settings'")) {
					add_action( 'admin_notices', array( $this, 'admin_notices_lusopaygateway_instrutions' ));
					add_action( 'admin_init', array( $this, 'admin_notices_lusopaygateway_instrutions_dismissed' ));
				} /*else {
					add_action( 'admin_notices', array( $this, 'admin_notice_lusopaygateway_instrutions_to_client' ));
					add_action( 'admin_init', array( $this, 'admin_notice_lusopaygateway_instrutions_to_client_dismissed' ));
				}*/
				//add_action('admin_notices', array($this, 'admin_notices_lusopaygateway_marketing'));
				add_action('admin_init', array($this, 'admin_notice_lusopaygateway_marketing_dismissed'));

			} else {
				add_action( 'admin_notices', array( $this, 'admin_notices_lusopaygateway_woocommerce_not_active' ) );
			}



		}

		public static function lusopaygateway_lang_fix_wpml_ajax( $locale, $domain ) {
            if ( 'class-wc-lusopaygateway' === $domain ) {
                if (function_exists('icl_get_languages_locales') && defined('ICL_LANGUAGE_CODE')) {
                    $locales = icl_get_languages_locales();
                    if ( isset( $locales[ ICL_LANGUAGE_CODE ] ) ) {
                        return $locales[ ICL_LANGUAGE_CODE ];
                    }
                }
            }
            return $locale;
    	}

		/**
		* Return an instance of this class.
		*
		* @return object A single instance of this class.
		*/
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}



/**
 * Get active network plugins
 **/
function lusopaygateway_active_nw_plugins() {
	if ( ! is_multisite() ) {
		return false;
	}

	return ( get_site_option( 'active_sitewide_plugins' ) ) ? array_keys( get_site_option( 'active_sitewide_plugins' ) ) : array();
}
//Obselete
/**
 * Plugin Initialization Callback
 */
function woocommerce_lusopaygateway_init() {
	require_once( dirname( __FILE__ ) . '/includes/class-wc-order-lusopay.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-wc-lusopaygateway.php' );
}

/**
 * Language Callback
 */
public function lusopaygateway_lang() {
	/*If WPML is present and we're loading via ajax, let's try to fix the locale*/
	if ( function_exists( 'icl_object_id' ) && function_exists( 'wpml_is_ajax' ) ) {
		if ( wpml_is_ajax() ) {
			if ( ICL_LANGUAGE_CODE !== 'en' ) {
				//add_filter( 'plugin_locale', 'lusopaygateway_lang_fix_wpml_ajax', 1, 2 );
				array( $this, 'lusopaygateway_lang_fix_wpml_ajax' );
			}
		}
	}

	load_plugin_textdomain( 'lusopaygateway', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
}

/**
 * Email Languages Callback
 *
 * @param array $order_id Order Id.
 */
public function lusopaygateway_lang_notes( $order_id ) {
	if ( is_array( $order_id ) ) {
		if ( isset( $order_id['order_id'] ) ) {
			$order_id = $order_id['order_id'];
		} else {
			return;
		}
	}
	if ( function_exists( 'icl_object_id' ) ) {
		global $sitepress;
		$lang = get_post_meta( $order_id, 'wpml_language', true );
		if ( ! empty( $lang ) && $lang !== $sitepress->get_default_language() ) {
			/* Set global to be used on lusopaygateway_lang_fix_wpml_ajax below */
			$GLOBALS['lusopaygateway_locale'] = $sitepress->get_locale( $lang );
			//add_filter( 'plugin_locale', 'lusopaygateway_lang_fix_wpml_ajax', 1, 2 );
			array( $this, 'lusopaygateway_lang_fix_wpml_ajax' );
			load_plugin_textdomain( 'lusopaygateway', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
	}
}

/**
 * This should NOT be needed! - Check with WooCommerce Multilingual team
 *
 * @param mixed $locale Locale.
 * @param mixed $domain Domain.
 *
 * @return mixed
 */

/*
function lusopaygateway_lang_fix_wpml_ajax( $locale, $domain ) {
	if ( 'class-wc-lusopaygateway' === $domain ) {
		$locales = icl_get_languages_locales();
		if ( isset( $locales[ ICL_LANGUAGE_CODE ] ) ) {
			$locale = $locales[ ICL_LANGUAGE_CODE ];
		}
		//But if it's notes
		if ( isset( $GLOBALS['lusopaygateway_locale'] ) ) {
			$locale = $GLOBALS['lusopaygateway_locale'];
		}
	}

	return $locale;
}
*/
/**
* Includes.
*/
private function includes() {
		include_once 'includes/class-wc-lusopaygateway.php';
		include_once 'includes/class-wc-lusopay-payshop.php';
		include_once 'includes/class-wc-lusopay-mbway.php';
		include_once 'includes/class-wc-lusopay-integration.php';
		include_once 'includes/class-wc-order-lusopay.php';
		include_once 'includes/class-wc-lusopay-pisp.php';
		include_once 'includes/class-wc-lusopay-cofi.php';

}

/**
* Action links.
*
* @param  array $links
*
* @return array
*/
public function plugin_action_links( $links ) {
	$plugin_links = [
		'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=integration&section=multibanco-e-ou-payshop-by-lusopay' ) ) . '">' . __( 'Activation Settings', 'lusopaygateway' ) . '</a>',
		'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=lusopaygateway' ) ) . '">' . __( 'Multibanco', 'lusopaygateway' ) . '</a>',
		'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=lusopay_payshop' ) ) . '">' . __( 'PayShop', 'lusopaygateway' ) . '</a>',
		'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_lusopay_mbway' ) ) . '">' . __( 'MB WAY', 'lusopaygateway' ) . '</a>',
		'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_lusopay_pisp' ) ) . '">' . __( 'Simplified Transfer', 'lusopaygateway' ) . '</a>',
		'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_lusopay_cofi' ) ) . '">' . __( 'Cofidis Pay', 'lusopaygateway' ) . '</a>'

	];

	return array_merge( $plugin_links, $links );
}

/**
 * Callback to add gateway
 *
 * @param array $methods List of Gateways.
 *
 * @return array
 */
function add_lusopaygateway_gateway( $methods ) {
	$methods[] = 'WC_Lusopaygateway';
	$methods[] = 'WC_Lusopay_PS';
	$methods[] = 'WC_Lusopay_MBWAY';
	$methods[] = 'WC_LUSOPAY_PISP';
	$methods[] = 'WC_LUSOPAY_COFI';

	return $methods;
}

/* Order metabox to show Multibanco payment details */
public function lp_order_add_meta_box() {
	add_meta_box( 'woocommerce_lusopaygateway', __( 'LusopayGateway Payment Details', 'lusopaygateway' ), array( $this, 'lp_order_meta_box_html' ), 'shop_order', 'side', 'core');
}

public function lp_order_meta_box_html($post) {
	include 'includes/views/order-meta-box.php';
}

/* Add a new integration to WooCommerce. */
public function add_integration( $integrations ) {
	$integrations[] = 'WC_Lusopay_Integration';
	return $integrations;
}

function prefix_plugin_update_message( $data, $response ) {
	if( isset( $data['upgrade_notice'] ) ) {
		printf(
			'<div class="update-message">%s</div>',
			wpautop( $data['upgrade_notice'] )
		);
	}
}


/**
 * Notifies the admin that woocommerce is not installed
 */
function admin_notices_lusopaygateway_woocommerce_not_active() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php esc_html_e( "Multibanco / Payshop / MB WAY / Transferência Simplificada(by LUSOPAY) for WooCommerce is installed and active but WooCommerce isn't.", 'lusopaygateway' ); ?></p>
	</div>
	<?php
}

/**
 * Notifies new client to flow the first step
 */
function admin_notice_lusopaygateway_instrutions_to_client() {

	$user_id = get_current_user_id();
	$integrations_settings = admin_url() . 'admin.php?page=wc-settings&tab=integration&section=multibanco-e-ou-payshop-by-lusopay';
	

	if ( !get_user_meta( $user_id, 'admin_notice_lusopaygateway_instrutions_to_client_dismissed' ) ) {

		?>
		<div id="notice" class="notice notice-info">
			<p><?php printf(__('If you are a new customer, you must activate our services <a href="%s">HERE</a>, in order to create one anti phishing-Key to be used in callbacks URLs.', 'lusopaygateway'), esc_url($integrations_settings));?></p>
			<p><a href="?my-plugin-dismissed_to"><?php printf(__('Dismiss', 'lusopaygateway'));?></a></p>
		</div>
		<?php
	}
}


/**
 * Dismiss the admin instrutions
 */
function admin_notice_lusopaygateway_instrutions_to_client_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['my-plugin-dismissed_to'] ) )
        add_user_meta( $user_id, 'admin_notice_lusopaygateway_instrutions_to_client_dismissed', 'true', true );
}



/**
 * Notifies the admin to follow the instrutions
 */
function admin_notices_lusopaygateway_instrutions() {
	$user_id = get_current_user_id();
	$payshop_settings = admin_url() . 'admin.php?page=wc-settings&tab=checkout&section=lusopay_payshop';

	if ( !get_user_meta( $user_id, 'admin_notices_lusopaygateway_instrutions_dismissed' ) ) {

		?>
		<div id="notice" class="notice notice-info">
			<h3><?php printf(__('Now you can receive by Simplified Transfers!', 'lusopaygateway'));?></h3>
			<p><?php printf(__('Activate the new method of payment made avaiable by LUSOPAY: the simplified transfers! Its economic for you and super simple for your clients.', 'lusopaygateway'));?></p>
			<p><?php printf(__('If you are already our client and you have your clientGUID and vatNumber inserted in the plugin, to receive payments by simplified transfer, you can enable the method in our plugin and put the callback address we indicate in the client area LUSOPAY (<a href=https://app.lusopay.com:8443/web/#login>LUSOPAY</a>). Click the menu "Pessoal", then on "Edit" and then insert the callback address in the field "URL callback transferência simplificada" And its done! Nothing more!', 'lusopaygateway'));?></p>
			<p><?php printf(__('If you are still not our client, join us at <a href="https://lusopay.com">LUSOPAY</a>. Only after inserting in the plugin the ClientGUID and vatNumber that we will send you, you can active the methods of payment.', 'lusopaygateway'));?></p>
			<h3><?php printf(__('What are simplified transfers and how do they work?', 'lusopaygateway'));?></h3>
			<p><?php printf(__('At the checkout, there is a moment where it shows the methods of payment that your store offers. If your client chooses the simplified transfer, a new window will show up where the user selects the bank of choosing bank account to do the payment. Our plugin will send the client to a page of that bank where its shown a summary of the payment he is about to do. The client only has to accept or refuse. Super simple, easy and intuitive.', 'lusopaygateway'));?></p>
			<p><a href="?my-plugin-dismissed"><?php printf(__('Dismiss', 'lusopaygateway'));?></a></p>
		</div>
		<?php
	}
}
/**
 * Dismiss the admin instrutions
 */
function admin_notices_lusopaygateway_instrutions_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['my-plugin-dismissed'] ) )
        add_user_meta( $user_id, 'admin_notices_lusopaygateway_instrutions_dismissed', 'true', true );
}

function admin_notices_lusopaygateway_marketing() {
	$user_id = get_current_user_id();
	$marketing_link = 'https://cutt.ly/Tv1cYMT';
	$logo = 'https://www.lusopay.com/App_Files/cms/documents/images/logo_lusopay_100x32_sem_margem.png';

	if (!get_user_meta( $user_id, 'admin_notice_lusopaygateway_marketing_dismissed' )) {

		?>
		<div id="notice" class="notice notice-info">
			<p><b><h1><?php printf(__('Gift from LUSOPAY', 'lusopaygateway'));?></h1></b></p>
			<p><b><?php printf(__('Increase your orders by exporting for free your products to the Iberic marketplace Trataki.', 'lusopaygateway'));?></b></p>
			<p><?php printf(__('Sell your products in Trataki marketplace and reach a market of more than 60 million consumers and companies. Without fidelization. Without sign up costs. Free monthly payment for one year.', 'lusopaygateway'));?></p>
			<p><?php printf(__('Click <a href="%s">here</a>.', 'lusopaygateway'), esc_url( $marketing_link ));?></p>
			<p><?php printf(__('Note: Trataki will make available a plug-in that does an automatic synchronization of your products to the Trataki marketplace.', 'lusopaygateway'));?></p>
			<p><a href="?my-plugin-dismissed_market"><?php printf(__('Dismiss', 'lusopaygateway'));?></a></p>
		</div>
		<?php

	}

}

function admin_notice_lusopaygateway_marketing_dismissed() {
	$user_id = get_current_user_id();
    if ( isset( $_GET['my-plugin-dismissed_market'] ) )
        add_user_meta( $user_id, 'admin_notice_lusopaygateway_marketing_dismissed', 'true', true );
}


	}
	add_action( 'plugins_loaded', array( 'WC_Lusopay', 'get_instance' ) );
	
endif;