<?php
/**
 * Plugin Name: Konnect Payment Gateway for WooCommerce
 * Plugin URI: https://www.konnect.network
 * Description: WooCommerce Plugin to enable digital payments.
 * Version: 2.8.1   
 * Author: Konnect
 * Author URI: https://konnect.network
 * Contributors: Konnect
 * Requires at least: 4.0
 * Tested up to: 6.4.3
 * WC requires at least: 6.0
 * WC tested up to: 8.6.1
 * Text Domain: konnect-payments
 * Domain Path: /lang/
 *
 * @package Konnect Payment Gateway for WooCommerce
 * @author Konnect Networks
 */
// session_start();
add_action('plugins_loaded', 'init_wc_konnect_gateway', 0);

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, false );
    }
} );

function init_wc_konnect_gateway()
{
    if (! class_exists('WC_Payment_Gateway')) {
        return;
    }

    load_plugin_textdomain('konnect-payments', false, dirname(plugin_basename(__FILE__)) . '/lang');

    class wc_konnect_gateway extends WC_Payment_Gateway
    {
        public function __construct() {
            global $woocommerce;

            $this->id			= 'wc_konnect_gateway';
            $this->method_title = __('Konnect', 'konnect-payments');
            $this->icon			= apply_filters('wc_konnect_gateway_icon', 'konnect-logo.png');
            $this->has_fields 	= false;

            // Load the form fields.
            $this->init_form_fields();

            // Load the settings.
            $this->init_settings();

            // Define user set variables
            $this->title 			        = "Paiement en ligne";
            $this->description 		        = "Choisissez votre moyen de paiement en ligne favoris";
            $this->mode         	        = $this->settings['mode'];
            $this->wallet_id   	            = $this->settings['wallet_id'];
            $this->private_key              = $this->settings['private_key'];
            $this->credit_card              = $this->settings['Credit-Card'];
            $this->wallet                   = $this->settings['Wallet'];
            $this->e_dinar                  = $this->settings['E-dinar'];
            $this->flouci                   = $this->settings['Flouci'];
            $this->addPaymentFeesToAmount   = $this->settings['addPaymentFeesToAmount'];
            $this->auto_complete_order      = $this->settings['auto-complete-order'];
            $this->notify_url               = add_query_arg('wc-api', 'wc_gateway_konnect', home_url('/'));
            $this->cancel_url 	            = add_query_arg('wc-api', 'wc_gateway_konnect', home_url('/'));

            // Actions
            add_action('init', array( $this, 'successful_request' ));
            add_action('woocommerce_api_wc_gateway_konnect', array( $this, 'successful_request' ));
            add_action('woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ));
            add_action('woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ));
        }

        /**
         * get_icon function.
         *
         * @access public
         * @return string
         */
        public function get_icon() {
            global $woocommerce;

            $icon = '';
            if ($this->icon) {
                $icon = '<img src="' . plugins_url('images/' . $this->icon, __FILE__)  . '" alt="' . $this->title . '" />';
            }

            return apply_filters('woocommerce_gateway_icon', $icon, $this->id);
        }

        /**
         * Admin Panel Options
         * - Options for bits like 'title' and availability on a country-by-country basis
         *
         * @since 1.0.0
         */
        public function admin_options() {
            ?>
            <h3><?php _e('<a href="https://konnect.network" target="_blank"><img style="width: 200px" src="' . plugins_url('images/konnect-logo-text.png', __FILE__) . '"></a>', 'konnect-payments');
             ?></h3>
	    	<table class="form-table">
	    	  <?php $this->generate_settings_html(); ?>
			</table>
			<p>
                <?php _e('<br> <hr> <br>
				<div style="float:right;text-align:right;">
					<a href="https://konnect.network" target="_blank">Konnect</a> | Besoin d\'aide? <a href="mailto:support@konnect.network" target="_blank">Contactez-nous</a><br><br>
					<a href="https://konnect.network" target="_blank"><img style="width: 120px" src="' . plugins_url('images/konnect-logo-text.png', __FILE__) . '">
					</a>
				</div>', 'konnect-payments'); ?>
            </p>
	    	<?php
        }

        /**
         * Initialise Gateway Settings Form Fields
         */
        public function init_form_fields()
        {
            $this->form_fields = array(
                    'enabled' => array(
                        'title' => __('Activer/Désactiver', 'konnect-payments'),
                        'type' => 'checkbox',
                        'label' => __('Activer Konnect', 'konnect-payments'),
                        'default' => 'yes'
                    ),
                    'mode' => array(
                        'title' => __('Mode', 'konnect-payments'),
                        'type' => 'select',
                        'options' => array(
                            'sandbox' => 'Sandbox',
                            'live' => 'Production'
                        ),
                        'default' => 'Sandbox'
                    ),
                    'wallet_id' => array(
                        'title' => __('Konnect Wallet ID', 'konnect-payments'),
                        'type' => 'text',
                        'description' => __('* Pour récupérer la référence du compte Konnect de production, il faut s\'inscrire sur <a href="https://konnect.network/admin" target="_blank">Konnect</a> et créer une organisation.
                                    </br> ** Pour la Sandbox, il faut s\'inscrire sur <a href="https://preprod.konnect.network/admin" target="_blank">Sandbox Konnect</a> et créer une organisation.', 'konnect-payments'),
                        'default' => ''
                    ),
                    'private_key' => array(
                        'title' => __('Clé API', 'konnect-payments'),
                        'type' => 'text',
                        'description' => __('Clé API fournie par Konnect.</br> Savoir plus ici <a href="https://api.dev.konnect.network/api/v2/konnect-gateway" target="_blank">Konnect Gateway</a>', 'konnect-payments'),
                        'default' => ''
                    ),
                    'Credit-Card' => array(
                        'title' => __('Credit-Card', 'konnect-payments'),
                        'type' => 'checkbox',
                        'label' => __('Activer paiement par carte bancaire', 'konnect-payments'),
                        'default' => 'yes'
                    ),
                    'E-dinar' => array(
                        'title' => __('E-dinar', 'konnect-payments'),
                        'type' => 'checkbox',
                        'label' => __('Activer paiement par E-Dinar', 'konnect-payments'),
                        'default' => 'yes'
                    ),
                    'Wallet' => array(
                        'title' => __('Wallet', 'konnect-payments'),
                        'type' => 'checkbox',
                        'label' => __('Activer paiement par Wallet Konnect', 'konnect-payments'),
                        'default' => 'yes'
                    ),
                    'Flouci' => array(
                        'title' => __('Flouci', 'konnect-payments'),
                        'type' => 'checkbox',
                        'label' => __('Activer paiement par Wallet Flouci', 'konnect-payments'),
                        'default' => 'no'
                    ),
                    'addPaymentFeesToAmount' => array(
                        'title' => __('Frais de paiement', 'konnect-payments'),
                        'type' => 'checkbox',
                        'label' => __('Les frais seront ajoutés au montant total du paiement et seront à la charge de l\'acheteur', 'konnect-payments'),
                        'default' => 'no'
                    ),
                    'auto-complete-order' => array(
                        'title' => __('auto-complete-order', 'konnect-payments'),
                        'type' => 'checkbox',
                        'label' => __('Changer le statut de la commande en terminé après le paiement', 'konnect-payments'),
                        'default' => 'no'
                    ),
                );
        }

        /**
         * Not payment fields, but show the description of the payment.
         **/
        public function payment_fields() {
            if ($this->description) {
                echo wpautop(wptexturize($this->description));
            }
        }

        /**
         * Generate the form with the params
         **/

        public function generate_konnect_form($order_id) {
            session_start();
            global $woocommerce;

            $payment_methods = array();
            $addPaymentFeesToAmount = false;            
            $order = new WC_Order($order_id);
            
            $endpoint = 'https://api.dev.konnect.network/api/v2/payments/init-payment';

            if ($this->mode == 'sandbox') {
                $endpoint = 'https://api.preprod.konnect.network/api/v2/payments/init-payment';
            }
            elseif ($this->mode == 'live') {
                $endpoint = 'https://api.konnect.network/api/v2/payments/init-payment';
            }

            if($this->credit_card == 'yes'){
                $payment_methods[] = 'bank_card' ;
            }
            if($this->e_dinar == 'yes'){
                $payment_methods[] = 'e-DINAR' ;
            }
            if($this->wallet == 'yes'){
                $payment_methods[] = 'wallet' ;
            }
            if($this->flouci == 'yes'){
                $payment_methods[] = 'flouci' ;
            }
            if($this->addPaymentFeesToAmount == 'yes'){
                $addPaymentFeesToAmount = true ;
            }

            $currency_code = $order->get_currency();

            if (in_array($currency_code, array("EUR", "GBP", "USD"))) {
                $amount = $order->get_total() * 100;
            } elseif ($currency_code == "TND") {
                $amount = $order->get_total() * 1000;
            } else {
                wc_add_notice(sprintf(__("La Devise ".$currency_code." n'est pas encore prise en charge par Konnect. Veuillez choisir un autre mode de paiement.", 'konnect-payments')), $notice_type = 'error');
                wp_redirect(get_permalink(get_option('woocommerce_checkout_page_id')));
                exit;
            }

            $products = '';
            foreach ($order->get_items() as $item  ):
                $products = $products."\r\n - ".$item->get_name().' x '.$item->get_quantity().' = '.$item->get_subtotal().' '.$order->get_currency();
            endforeach;
   

            $headers = array(
                'Content-type' => 'application/json',
                'x-api-key' => $this->private_key,
            );

            $body = array(
                'receiverWalletId' => $this->wallet_id,
                'amount' => $amount,
                'orderId' => $order_id,
                'successUrl' => $this->notify_url,
                'failUrl' => $this->cancel_url,
                'token' => $currency_code,
                'addPaymentFeesToAmount' => $addPaymentFeesToAmount,
                'acceptedPaymentMethods' => $payment_methods,
                'type' => "immediate",
                'message' => $products,
                'channel' => 'Wordpress',
            );

            $args = array(
                'body'        => json_encode($body),
                'timeout'     => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => $headers,
                'cookies'     => array(),
              );

            $rawRespose = wp_remote_post($endpoint, $args);

            if ( is_wp_error( $rawRespose ) || !in_array(wp_remote_retrieve_response_code($rawRespose), array(200, 401))) {
                $response = json_decode( wp_remote_retrieve_body($rawRespose), true );
                wc_add_notice(sprintf(__(strval($response['errors'][0]['message']))), $notice_type = 'error');
            }

            $response = json_decode( wp_remote_retrieve_body($rawRespose), true );

            $payUrl= $response['payUrl'];

            wp_redirect($payUrl);
        }

        /**
         * Process the payment and return the result
         **/
        public function process_payment($order_id) {
            session_start();
            $order = new WC_Order($order_id);
            $_SESSION['konnect_order_id'] = $order_id;
            return array(
                'result' 	=> 'success',
                'redirect'	=> $order->get_checkout_payment_url(true)
            );
        }

        /**
         * receipt_page
         **/
        public function receipt_page($order) {
            $this->generate_konnect_form($order);
        }

        /**
         * Successful Payment!
         **/
        public function successful_request() {
            session_start();
            global $woocommerce;

            $payment_ref = sanitize_text_field($_GET['payment_ref']);

            $endpoint = 'https://api.dev.konnect.network/api/v2/payments/'. $payment_ref;
            if ($this->mode == 'sandbox') {
                $endpoint = 'https://api.preprod.konnect.network/api/v2/payments/'. $payment_ref;
            }
            elseif ($this->mode == 'live') {
                $endpoint = 'https://api.konnect.network/api/v2/payments/'. $payment_ref;
            }

            $headers = array(
                'Content-type' => 'application/json',
                'x-api-key' => $this->private_key,
            );

            $args = array('headers' => $headers);

            $rawRespose = wp_remote_get($endpoint, $args);

            if ( is_wp_error( $rawRespose ) || !in_array(wp_remote_retrieve_response_code($rawRespose), array(200, 401))) {
                $response = json_decode( wp_remote_retrieve_body($rawRespose), true );
                wc_add_notice(sprintf(__($response['errors'][0]['message'])), $notice_type = 'error');
            }

            $response = json_decode( wp_remote_retrieve_body($rawRespose), true );

            $order = new WC_Order($response['payment']['orderId']);

            if (in_array($response['payment']['token'], array("EUR", "GBP", "USD"))) {
                $convertedOrderAmount = $order->get_total()*100;
            } elseif ($response['payment']['token'] == "TND") {
                $convertedOrderAmount = $order->get_total()*1000;
            } else {
                wc_add_notice(sprintf(__("La Devise ".$response['payment']['token']." n'est pas encore prise en charge par Konnect. Veuillez choisir un autre mode de paiement.", 'konnect-payments')), 'error');
                wp_redirect(get_permalink(get_option('woocommerce_checkout_page_id')));
                exit;
            }

            if ($response['payment']['amount'] == $convertedOrderAmount
            && $response['payment']['status'] == 'completed') {
                $order = new WC_Order($_SESSION['konnect_order_id']);
                $order->add_order_note(sprintf(__('Payée avec Konnect. Numéro de la transaction %s.', 'konnect-payments'), $response['payment']['id'] ));
                $order->payment_complete();

                if( $this->auto_complete_order == 'yes'){
                    $order = wc_get_order( $response['payment']['orderId'] );
                    $order->update_status( 'completed' );
                }
                

                wp_redirect($this->get_return_url($order));
                exit;
            }
            wc_add_notice(sprintf(__('Erreur de paiement.', 'konnect-payments')), 'error');
            wp_redirect(get_permalink(get_option('woocommerce_checkout_page_id')));
            exit;
        }
    }

    /**
     * Add the gateway to WooCommerce
     **/
    function add_konnect_gateway($methods) {
        $methods[] = 'wc_konnect_gateway';
        return $methods;
    }
    add_filter('woocommerce_payment_gateways', 'add_konnect_gateway');
}
