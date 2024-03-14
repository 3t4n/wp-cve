<?php
/**
 * Plugin Name: zahls.ch Credit Cards, PostFinance and TWINT for WooCommerce
 * Description: Integration of payment options from zahls.ch like MasterCard, Visa, TWINT and PostFinance in WooCommerce
 * Author: siebenberge gmbh
 * Author URI: https://www.siebenberge.com
 * Text Domain: zahls-ch-payment-gateway
 * Version: 2.0.3
 * Requires at least: 4.6
 * Tested up to: 6.5
 * WC requires at least: 8.0
 * WC tested up to: 8.3
 */

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return;

define('ZAHLS_VERSION', '2.0.3');
define('ZAHLS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ZAHLS_PLUGIN_PATH', plugin_dir_path(__FILE__));

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );



add_action('plugins_loaded', 'WC_Zahls_offline_gateway_init', 11);


use ZahlsPaymentGateway\Service\ZahlsApiService;
use ZahlsPaymentGateway\Service\SubscriptionService;
use ZahlsPaymentGateway\Service\OrderService;
use ZahlsPaymentGateway\Controller\PaymentController;
use ZahlsPaymentGateway\Webhook\Dispatcher;
use ZahlsPaymentGateway\Util\CartUtil;

use Automattic\WooCommerce\Utilities\OrderUtil;
use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;



function WC_Zahls_offline_gateway_init()
{
    class WC_Zahls_Gateway extends WC_Payment_Gateway
    {
		
		const LANG = ['en', 'de', 'it', 'fr', 'nl', 'pt', 'tr'];

        protected $zahlsApiService;
        protected $subscriptionService;
        protected $orderService;
        protected $paymentController;
        protected $webhookDispatcher;
		
        public $enabled;
        public $title;
        public $description;
        public $instance;
        public $sid;
		public $platform;
        public $apiKey;
        public $prefix;
        public $logos;
		public $subscriptionLogos;
        public $lookAndFeelId;
        public $subscriptionsEnabled;
        public $subscriptionTitle;
        public $subscriptionUserDesc;

        public function __construct()
        {
			if(defined('ZAHLS_PLUGIN_DIR') == false){
			define( 'ZAHLS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
          //  $this->lang = ['en', 'de', 'it', 'fr', 'nl', 'pt', 'tr'];
            $this->id = 'zahls';
            $this->method_title = __('zahls.ch', 'zahls-ch-payment-gateway');
            $this->method_description = __('Accept payments with zahls.ch.', 'zahls-ch-payment-gateway');
			
			$this->load_dependencies();
            $this->init_components();
            $this->register_hooks();

            if (isset($_GET['zahls_error'])) {
                $this->handleError();
			}
		}
		
		protected function load_dependencies() {
            require_once ZAHLS_PLUGIN_DIR . '/src/Service/ZahlsApiService.php';
            require_once ZAHLS_PLUGIN_DIR . '/src/Service/SubscriptionService.php';
            require_once ZAHLS_PLUGIN_DIR . '/src/Service/OrderService.php';
            require_once ZAHLS_PLUGIN_DIR . '/src/Controller/PaymentController.php';
            require_once ZAHLS_PLUGIN_DIR . '/src/Util/CartUtil.php';
			require_once ZAHLS_PLUGIN_DIR . '/src/Util/StatusUtil.php';
            require_once ZAHLS_PLUGIN_DIR . '/src/Webhook/Dispatcher.php';
        }

        protected function init_components() {  
            $this->init_form_fields();
            $this->init_settings();
			
			$this->zahlsApiService = new ZahlsApiService($this->instance, $this->apiKey, $this->platform, $this->prefix, $this->lookAndFeelId);
            $this->subscriptionService = new SubscriptionService($this->zahlsApiService);
            $this->orderService = new OrderService();
            $this->paymentController = new PaymentController();
            $this->webhookDispatcher = new Dispatcher($this->zahlsApiService, $this->orderService, $this->prefix);
		}
		
		
	 /**
	 * Initialize Gateway Settings Form Fields
	 */
        public function init_form_fields()
        {
            $this->form_fields = include('includes/settings-zahls.php');
        }

        public function init_settings() {
            parent::init_settings();

            $this->enabled = $this->get_option('enabled');
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            
            
            $this->instance  	= $this->get_option('instance');
			$this->platform = "zahls.ch";
            
            $this->instance = str_replace(".zahls.ch", "", $this->instance);
            $this->instance = str_replace(".ch", "", $this->instance);
            
            $this->sid = $this->get_option('sid');
			$this->apiKey = !empty($this->get_option('apiKey')) ? $this->get_option('apiKey') : $this->get_option('sid');
            $this->prefix = $this->get_option('prefix');
            $this->logos = $this->get_option('logos');
			$this->subscriptionsEnabled = $this->get_option('subscriptions_enabled');
            $this->subscriptionTitle = $this->get_option('subscriptions_title');
           
			$this->subscriptionLogos = $this->get_option('subscription_logos');
            $this->lookAndFeelId = $this->get_option('lookAndFeelId');
			$this->subscriptionsEnabled = $this->get_option('subscriptions_enabled');
			
		}
		
		protected function register_hooks() {
            // Get config data
            // Add subscription support if activated
            if ($this->subscriptionsEnabled === 'yes') {
                $this->supports = array_merge($this->supports, array(
                    'subscriptions',
                    'subscription_cancellation',
                    'subscription_suspension',
                    'subscription_reactivation',
                    'subscription_amount_changes',
                    'subscription_date_changes',
                    'subscription_payment_method_change',
                    'subscription_payment_method_change_customer',
                    'multiple_subscriptions',
                ));

                // Filter to extend description and title in case of recurring payment
                add_filter('woocommerce_gateway_title', array($this, 'mutateTitle'), 10, 2);
                add_filter('woocommerce_gateway_description', array($this, 'mutateDescription'), 10, 2);
            }





            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_api_wc_zahls_gateway', array($this->webhookDispatcher, 'check_webhook_response'));
            add_action('woocommerce_scheduled_subscription_payment_' . $this->id, array($this->subscriptionService, 'process_recurring_payment'), 10, 2);
        }
        
		
		
		public function process_payment($orderId)
        {
            $cart = WC()->cart;
            $order = new WC_Order($orderId);

            $paymentMethodChangeGET = !empty($_GET['change_payment_method']) ? $_GET['change_payment_method'] : null;

            // $orderId is actually the subscriptionId in case of payment method change
            if (CartUtil::isPaymentMethodChange($paymentMethodChangeGET)) {
                $subscription = new \WC_Subscription($orderId);
                $order = new \WC_Order($subscription->get_last_order());
            }

            $totalAmount = floatval($order->get_total());
            $reference = ($this->prefix ? $this->prefix . '_' :  '') . $orderId;
            $currency = get_woocommerce_currency();
            $basket = $this->paymentController->createBasketByCart($cart);
            $basketAmount = $this->paymentController->getBasketAmount($basket);

            $successRedirectUrl = $this->get_return_url($order);

            $purpose = '';
            // If the basket amount does not match the totalAmount, the basket is not correct
            if (!$totalAmount || $totalAmount !== floatval($basketAmount)) {
                // Basket backup as purpose
                // ToDo: Remove this as soon as "createBasketByCart" works flawless
                $purpose = $this->paymentController->createPurposeByBasket($basket);

                // Clear basket because the data is not correct
                $basket = [];
            }

            $cancelRedirectUrl = wc_get_cart_url() . '?zahls_error=1&order_id=' . $order->get_id();
            $preAuthorization = false;
            $chargeOnAuth = false;

            switch (CartUtil::getOrderType($cart, $paymentMethodChangeGET, $_POST['zahls-allow-recurring']) ) {
                case CartUtil::ORDER_SUBSCRIPTION_AUTO:
                    $preAuthorization = true;

                    // No immmediate charge should happen for payment method changes
                    // Also not if totalAmount is 0 and must be artificially elevated for tokenizations to work
                    if ($totalAmount) {
                        $chargeOnAuth = true;
                    } else {
                        // The amount is artificially elevated because the Gateway creation always needs an amount
                        $totalAmount = 0.50;
						$customButtonText = [
                            1 => 'Autorisieren',
                            2 => 'Authorize',
                            3 => 'Autoriser',
                            4 => 'Autorizzare',
                            7 => 'Autoriseer',
                        ];
                    }

                    break;
                case CartUtil::ORDER_SUBSCRIPTION_MANUAL:

                    // Set all subscriptions connected to this order to manual
                    $subscriptions = wcs_get_subscriptions_for_order($order->get_id(), array( 'order_type' => 'parent' ));
                    foreach ($subscriptions as $subscription) {
                        $subscription->set_requires_manual_renewal(true);
                        $subscription->save();
                    }

                    // In case of manual subscription and no amount, the order is completed
                    if (!$totalAmount) {
                        $order->payment_complete();
                        WC()->cart->empty_cart();
                        return [
                            'result' => 'success',
                            'redirect' => $this->get_return_url($order)
                        ];
                    }

                    break;
                case CartUtil::ORDER_SUBSCRIPTION_METHOD_CHANGE:
                    $preAuthorization = true;
                    $cancelRedirectUrl = wp_nonce_url(add_query_arg(['change_payment_method' => $subscription->get_id()], $subscription->get_checkout_payment_url()));

                    // The amount is artificially elevated because the Gateway creation always needs an amount
                    $totalAmount = 0.50;
					$customButtonText = [
                            1 => 'Autorisieren',
                            2 => 'Authorize',
                            3 => 'Autoriser',
                            4 => 'Autorizzare',
                            7 => 'Autoriseer',
                        ];
                    break;
            }

            $gateway = $this->zahlsApiService->createZahlsGateway($order, $totalAmount, $basket, $purpose, $reference, $successRedirectUrl, $cancelRedirectUrl, $preAuthorization, $chargeOnAuth, $currency, $customButtonText);

            $order->update_meta_data('zahls_gateway_id', $gateway->getId());
            $order->save();

            $language = substr(get_locale(), 0, 2);
            !in_array($language, self::LANG) ? $language = self::LANG[0] : null;
            $redirect = str_replace('?', $language . '/?', $gateway->getLink());

            // Return redirect
            return [
                'result' => 'success',
                'redirect' => $redirect
            ];
        }
        
		
		
		 public function mutateTitle($title, $id) {
            // Ignore if this is not a Zahls based transaction/subscription
            if ($id !== 'zahls') {
                return $title;
            }

            if (empty($_GET['change_payment_method']) || !CartUtil::isPaymentMethodChange($_GET['change_payment_method'])) return $title;
			return $this->subscriptionTitle;
        }

        /**
         * @param $description
         * @param $id
         * @return mixed
         */
        public function mutateDescription($description, $id)
        {
            $paymentMethodChangeGET = !empty($_GET['change_payment_method']) ? $_GET['change_payment_method'] : null;

            // Ignore if this is not a Zahls based transaction/subscription
            if ($id !== 'zahls') {
                return $description;
            }

            $html = '';
            if (strlen($description) > 0) {
                $html .= wpautop($description);
            }

            // Handle change payment method action
            if (CartUtil::isPaymentMethodChange($paymentMethodChangeGET)) {
                $html .= '<input type="hidden" name="zahls-allow-recurring" id="zahls-allow-recurring" value="1" />';
                return $html;
            }

            if (!CartUtil::isSubscription(WC()->cart, $paymentMethodChangeGET)) return false;

            // do it the messy way as WCS is using wpautop on this string
            $html .= '<label for="zahls-allow-recurring">';		
	
			 if (get_option(\WC_Subscriptions_Admin::$option_prefix . '_accept_manual_renewals', 'no') === 'yes') {
                $html .= '<input type="checkbox" checked name="zahls-allow-recurring" id="zahls-allow-recurring" value="1" />';
            } else {
                $html .= '<input type="hidden" checked name="zahls-allow-recurring" id="zahls-allow-recurring" value="1" />';
            }

            $html .= $this->subscriptionUserDesc . '</label>';
			
            // Add logic to show cards depending on checkbox
            $html .= '<script type="text/javascript">
                  jQuery(function() {
                    jQuery("#zahls-allow-recurring").on("change", function() {
                      var checked = jQuery(this).is(":checked");
                        jQuery(".onetime").each(function() {
                            checked ? jQuery(this).hide() : jQuery(this).show();
                        });
                        jQuery(".tokenization").each(function() {
                            checked ? jQuery(this).show() : jQuery(this).hide();
                        });
                    });
                  });
                </script>';

            return $html;
        }
        
        
        
    /**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_options() {
        
            $urlparts = parse_url(home_url());
            $domain = $urlparts['host'];
        
		?>
		<h2><?php _e( 'zahls.ch Settings', 'zahls-ch-payment-gateway' ); wc_back_link( __( 'Return to payments', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ); ?></h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<table class="form-table">
							<?php $this->generate_settings_html();?>
	
						</table><!--/.form-table-->
						
						
							
						<div style="display: block; margin: 20px 0 0 0; padding: 10px; background-color: #3f245c; color: #fff;">
						
						<div style="font-size: 20px; padding: 10px 0 10px 0;"><i class="dashicons dashicons-arrow-right-alt"></i> <b><?php _e('Order status sync with zahls.ch', 'zahls-ch-payment-gateway'); ?></b></div>
							<p><?php _e('Please copy this Webhook URL to the section "Webhooks" in your zahls.ch backend to sync the payment status of your orders.', 'zahls-ch-payment-gateway'); ?> </p>
							<p><input style="background-color:  #fff; border: 1px solid #000; width: 100%; padding: 5px; font-size: 20px;" type="input" value="<?php echo get_site_url()."/?wc-api=wc_zahls_gateway"; ?>" readonly></p>
							<p><a href="https://login.zahls.ch" target="_blank" style="color: #fff;"><?php _e('Open your zahls.ch backend.', 'zahls-ch-payment-gateway'); ?></a></p>
					</div>
					</div>
					<div id="postbox-container-1" class="postbox-container">
	                        <div id="side-sortables" class="meta-box-sortables ui-sortable"> 
                                
                                
                                
                                        <div class="postbox">
	                                <h3 class="hndle"><span><i class="dashicons dashicons-superhero-alt"></i>&nbsp;&nbsp;<?php _e('zahls.ch for credit cards and TWINT', 'zahls-ch-payment-gateway'); ?></span></h3>
                                    <hr>
	                                <div class="inside">
	                                    <div class="support-widget">
	                                        <p><?php _e('Thank you for using zahls.ch. You need to have an account from zahls.ch to use this payment gateway for your transcations. You can get a free account on', 'zahls-ch-payment-gateway'); ?> <a href="https://www.zahls.ch" target="_blank">www.zahls.ch</a>.</p>                                        

	                                    </div>
	                                </div>
	                            </div>
								
							                          

	                            <div class="postbox">
	                                <h3 class="hndle"><span><i class="dashicons dashicons-editor-help"></i>&nbsp;&nbsp;<?php _e('Support', 'zahls-ch-payment-gateway'); ?></span></h3>
                                    <hr>
	                                <div class="inside">
	                                    <div class="support-widget">
	                                        <p>
	                                        <img style="width: 70%;margin: 0 auto;position: relative;display: inherit;" src="https://static.siebenberge.com/media/plugins/woocommerce/<?php echo $domain; ?>/zahls.svg">
	                                        <br/>
                                            <?php _e('Do you have a question, idea, problem or praise? Please feel free to contact us.', 'zahls-ch-payment-gateway'); ?>    
	                                        </p>
                                            <p><a href="mailto:support@zahls.ch" target="_blank">support@zahls.ch</a><br>
<a href="https://www.zahls.ch" target="_blank">www.zahls.ch</a></p>
                                            
                                            
	                                        <p style="margin-bottom: 0;"><?php _e('Please leave us a ', 'zahls-ch-payment-gateway'); ?>  <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/zahls-ch-payment-gateway?filter=5#postform">★★★★★</a> <?php _e('rating', 'zahls-ch-payment-gateway'); ?>.</p>

	                                    </div>
	                                </div>
	                            </div>
	                       
	    <?php /*   coming soon                      <div class="postbox rss-postbox">
	    								<h3 class="hndle"><span><i class="fa fa-wordpress"></i>&nbsp;&nbsp;sb_zahls Blog</span></h3>
                                        <hr>
	    								<div class="inside">
											<div class="rss-widget">
												<?php
	    											wp_widget_rss_output(array(
	    													'url' => 'https://www.zahls.ch/feed/',
	    													'title' => 'zahls.ch Blog',
	    													'items' => 3,
	    													'show_summary' => 0,
	    													'show_author' => 0,
	    													'show_date' => 1,
	    											));
	    										?>
	    									</div>
	    								</div>
	    						</div> */?>

	                        </div>
	                    </div>
                    </div>
				</div>
				<div class="clear"></div>
				<style type="text/css">
				.sb_zahls_button{
					background-color:#4CAF50 !important;
					border-color:#4CAF50 !important;
					color:#ffffff !important;
					width:100%;
					text-align:center;
					height:35px !important;
					font-size:12pt !important;
				}
                .sb_zahls_button .dashicons {
                    padding-top: 5px;
                }
				</style>
				<?php
	}
        
        
        
        
        
        

     /*    public function register_autoloader()
        {
            spl_autoload_register(function ($class) {
                $root = __DIR__ . '/zahls-php-master';
                $classFile = $root . '/lib/' . str_replace('\\', '/', $class) . '.php';
                if (file_exists($classFile)) {
                    require_once $classFile;
                }
            });
        } */

         public function get_icon()
        {
            $style = version_compare(WC()->version, '2.6', '>=') ? 'margin-left: 0.3em;' : '';
            $paymentMethodChangeGET = !empty($_GET['change_payment_method']) ? $_GET['change_payment_method'] : null;

            $subscriptionPayment = CartUtil::isSubscription(WC()->cart, $paymentMethodChangeGET);
            $paymentMethodChange = CartUtil::isPaymentMethodChange($paymentMethodChangeGET);

            $hideStyle = $subscriptionPayment ? 'display: none' : '';
            $logos = $paymentMethodChange ? $this->subscriptionLogos : $this->logos;

            $icon = '';
            //if $logos is an array¨
            if(is_array($logos)){
           
            foreach ($logos as $logo) {
                if ($subscriptionPayment && !$paymentMethodChange && in_array($logo, $this->subscriptionLogos)) {
                    $icon .= '<img class="tokenization" src="' . WC_HTTPS::force_https_url(plugins_url('cardicons/card_' . $logo . '.svg', __FILE__)) . '" alt="' . $logo . '" id="' . $logo . '" width="32" style="'.$style.'" />';
                }
                $icon .= '<img class="onetime" src="' . WC_HTTPS::force_https_url(plugins_url('cardicons/card_' . $logo . '.svg', __FILE__)) . '" alt="' . $logo . '" id="' . $logo . '" width="32" style="' . $style . $hideStyle . '" />';
            }}

            // Add a wrapper around the images to allow styling
            return apply_filters('woocommerce_gateway_icon', '<span class="icon-wrapper">' . $icon . '</span>', $this->id);
        }
		
		private function handleError() {
            // Delete old Gateway using order metadata
			
			

            if (isset($_GET['order_id'])) {
				if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
	// HPOS usage is enabled.
	$order = wc_get_order($_GET['order_id']);
	$gatewayId = $order->get_meta('zahls_gateway_id');
					
} else {
	// Traditional CPT-based orders are in use.
	$gatewayId = intval(get_post_meta($_GET['order_id'], 'zahls_gateway_id', true));
}
				
                
                $this->zahlsApiService->deleteGatewayById($gatewayId);
            }
            wc_print_notice(__('Payment failed. Please choose another method.', 'zahls-ch-payment-gateway'), 'error');
        }

    }
}

function wc_zahls_add_to_gateways($gateways)
{
    $gateways[] = 'WC_Zahls_Gateway';
    return $gateways;
}

add_filter('woocommerce_payment_gateways', 'wc_zahls_add_to_gateways');



/* siebenberge plugin_action_links */

function sb_zahls_plugin_action_links( $links ) {

	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/admin.php?page=wc-settings&tab=checkout&section=zahls' ) ) . '">' . __( 'Settings', 'zahls-ch-payment-gateway' ) . '</a>'
	), $links );

	return $links;

}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'sb_zahls_plugin_action_links' );







/**
 * Registers WooCommerce Blocks integration.
 */

add_action( 'woocommerce_blocks_loaded', 'zahls_woocommerce_blocks_support' );

function zahls_woocommerce_blocks_support() {
  if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
	  
	  if(defined('ZAHLS_PLUGIN_DIR') == false){
			define( 'ZAHLS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
	  
     require_once ZAHLS_PLUGIN_DIR .  '/includes/blocks-checkout.php';
	  
        add_action(
            'woocommerce_blocks_payment_method_type_registration',
            function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
                $payment_method_registry->register( new WC_Zahls_Blocks_Support );
            }
        );
	  
	  
  }
}










?>