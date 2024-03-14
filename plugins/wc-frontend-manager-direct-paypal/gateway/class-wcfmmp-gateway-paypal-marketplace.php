<?php

/**
 * Payment gateway
 *
 * @category WooCommerce_Payment_Gateway
 * @package  WooCommerce/PayPal
 * @author   WCLovers <contact@wclovers.com>
 * @since    2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

use WCFM\PaypalMarketplace\Helper;
use WCFM\PaypalMarketplace\Client;
use WCFM\PaypalMarketplace\WebhookHandler as WebhookHandler;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;

/**
 * Payment gateway class
 */
class WCFMmp_Gateway_Paypal_Marketplace extends WC_Payment_Gateway {

    protected $test_mode;
    protected $client_id;
    protected $client_secret;
    protected $sandbox_client_id;
    protected $sandbox_client_secret;

    /**
     * Constructor for the gateway.
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function __construct() {
        $this->supports = array(
            'products',
            'refunds',
        );

        $this->init_fields();

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        $this->init_hooks();

        if (!$this->is_valid_for_use()) {
            $this->enabled = 'no';
        }

        if ('yes' === $this->enabled) {
            add_filter('woocommerce_thankyou_order_received_text', array($this, 'order_received_text'), 10, 2);
        }
    }

    /**
     * Init essential fields
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function init_fields() {
        $this->id                 = Helper::payment_gateway_id();
        $this->has_fields         = true;
        $this->method_title       = Helper::payment_gateway_title();
        $this->method_description = __('Pay Via PayPal Marketplace', 'wc-frontend-manager-direct-paypal');
        $this->icon               = $this->get_icon();

        $title                          = $this->get_option('title');
        $this->title                    = empty($title) ? __('PayPal Marketplace', 'wc-frontend-manager-direct-paypal') : $title;
        $this->test_mode                = $this->get_option('test_mode');
        $this->client_id                = $this->get_option('client_id');
        $this->client_secret            = $this->get_option('client_secret');
        $this->sandbox_client_id        = $this->get_option('sandbox_client_id');
        $this->sandbox_client_secret    = $this->get_option('sandbox_client_secret');
    }

    /**
     * Initialise Gateway Settings Form Fields
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function init_form_fields() {
        global $wcfmpgdp;

        $this->form_fields = include $wcfmpgdp->plugin_path . 'views/wcfmdp-view-paypal-marketplace-gateway-fields.php';
    }

    /**
     * Initialize necessary actions
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function init_hooks() {
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
        add_action('admin_enqueue_scripts', array(&$this, 'wcfm_admin_scripts'));
        add_action('wcfmmp_refund_status_completed', array(&$this, 'process_api_refund'), 50, 4);
    }

    /**
     * Check if this gateway is enabled and available in the user's country
     *
     * @since 2.0.0
     *
     * @return bool
     */
    public function is_valid_for_use() {
        if (!in_array(get_woocommerce_currency(), array_keys(get_supported_currencies()), true)) {
            return false;
        }

        return true;
    }

    /**
	 * Admin Panel Options.
	 * - Options for bits like 'title' and availability on a country-by-country basis.
	 *
	 * @since 2.0.0
	 */
	public function admin_options() {
		if ( $this->is_valid_for_use() ) {
			parent::admin_options();
		} else {
			?>
			<div class="inline error">
				<p>
					<strong><?php esc_html_e( 'Gateway disabled', 'wc-frontend-manager-direct-paypal' ); ?></strong>: <?php esc_html_e( 'PayPal Marketplace does not support your store currency.', 'wc-frontend-manager-direct-paypal' ); ?>
				</p>
			</div>
			<?php
		}
	}

    /**
     * Display information in frontend
     * after checkout process button
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function payment_fields() {}

    /**
     * Process admin options
     *
     * @since 2.0.0
     *
     * @return bool|void
     */
    public function process_admin_options() {
        $saved = parent::process_admin_options();

        // delete token transient after settings is being updated.
        delete_transient('_wcfm_paypal_marketplace_access_token');

        $webhook_handler = new WebhookHandler();

        if ($saved && $webhook_handler instanceof WebhookHandler) {
            try {
                if (Helper::is_enabled()) {
                    $webhook_handler->register_webhooks();
                } else {
                    $webhook_handler->deregister_webhooks();
                }
            } catch (Exception $e) {
                wcfm_paypal_log('[WCFM Paypal Marketplace] Webhook Error: ' . print_r($e->getMessage(), true), 'error');
            }
        }

        return $saved;
    }

    /**
	 * Get gateway icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		// We need a base country for the link to work, bail if in the unlikely event no country is set.
		$base_country = WC()->countries->get_base_country();
		if ( empty( $base_country ) ) {
			return '';
		}
		$icon_html = '';
		$icon      = (array) $this->get_icon_image( $base_country );

		foreach ( $icon as $i ) {
			$icon_html .= '<img src="' . esc_attr( $i ) . '" alt="' . esc_attr__( 'PayPal acceptance mark', 'wc-frontend-manager-direct-paypal' ) . '" />';
		}

		$icon_html .= sprintf( '<a href="%1$s" class="about_paypal" onclick="javascript:window.open(\'%1$s\',\'WIPaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700\'); return false;">' . esc_attr__( 'What is PayPal?', 'wc-frontend-manager-direct-paypal' ) . '</a>', esc_url( $this->get_icon_url( $base_country ) ) );

		return apply_filters( 'wcfm_gateway_icon', $icon_html, $this->id );
	}

    /**
	 * Get the link for an icon based on country.
	 *
	 * @param  string $country Country two letter code.
	 * @return string
	 */
	protected function get_icon_url( $country ) {
		$url           = 'https://www.paypal.com/' . strtolower( $country );
		$home_counties = array( 'BE', 'CZ', 'DK', 'HU', 'IT', 'JP', 'NL', 'NO', 'ES', 'SE', 'TR', 'IN' );
		$countries     = array( 'DZ', 'AU', 'BH', 'BQ', 'BW', 'CA', 'CN', 'CW', 'FI', 'FR', 'DE', 'GR', 'HK', 'ID', 'JO', 'KE', 'KW', 'LU', 'MY', 'MA', 'OM', 'PH', 'PL', 'PT', 'QA', 'IE', 'RU', 'BL', 'SX', 'MF', 'SA', 'SG', 'SK', 'KR', 'SS', 'TW', 'TH', 'AE', 'GB', 'US', 'VN' );

		if ( in_array( $country, $home_counties, true ) ) {
			return $url . '/webapps/mpp/home';
		} elseif ( in_array( $country, $countries, true ) ) {
			return $url . '/webapps/mpp/paypal-popup';
		} else {
			return $url . '/cgi-bin/webscr?cmd=xpt/Marketing/general/WIPaypal-outside';
		}
	}

	/**
	 * Get PayPal images for a country.
	 *
	 * @param string $country Country code.
	 * @return array of image URLs
	 */
	protected function get_icon_image( $country ) {
        global $wcfmpgdp;

		switch ( $country ) {
			case 'US':
			case 'NZ':
			case 'CZ':
			case 'HU':
			case 'MY':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
				break;
			case 'TR':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_odeme_secenekleri.jpg';
				break;
			case 'GB':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png';
				break;
			case 'MX':
				$icon = array(
					'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_visa_mastercard_amex.png',
					'https://www.paypal.com/es_XC/Marketing/i/banner/paypal_debit_card_275x60.gif',
				);
				break;
			case 'FR':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_paypal_moyens_paiement_fr.jpg';
				break;
			case 'AU':
				$icon = 'https://www.paypalobjects.com/webstatic/en_AU/mktg/logo/Solutions-graphics-1-184x80.jpg';
				break;
			case 'DK':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/logo_PayPal_betalingsmuligheder_dk.jpg';
				break;
			case 'RU':
				$icon = 'https://www.paypalobjects.com/webstatic/ru_RU/mktg/business/pages/logo-center/AM_mc_vs_dc_ae.jpg';
				break;
			case 'NO':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo-center/banner_pl_just_pp_319x110.jpg';
				break;
			case 'CA':
				$icon = 'https://www.paypalobjects.com/webstatic/en_CA/mktg/logo-image/AM_mc_vs_dc_ae.jpg';
				break;
			case 'HK':
				$icon = 'https://www.paypalobjects.com/webstatic/en_HK/mktg/logo/AM_mc_vs_dc_ae.jpg';
				break;
			case 'SG':
				$icon = 'https://www.paypalobjects.com/webstatic/en_SG/mktg/Logos/AM_mc_vs_dc_ae.jpg';
				break;
			case 'TW':
				$icon = 'https://www.paypalobjects.com/webstatic/en_TW/mktg/logos/AM_mc_vs_dc_ae.jpg';
				break;
			case 'TH':
				$icon = 'https://www.paypalobjects.com/webstatic/en_TH/mktg/Logos/AM_mc_vs_dc_ae.jpg';
				break;
			case 'JP':
				$icon = 'https://www.paypal.com/ja_JP/JP/i/bnr/horizontal_solution_4_jcb.gif';
				break;
			case 'IN':
				$icon = 'https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg';
				break;
			default:
				$icon = WC_HTTPS::force_https_url( $wcfmpgdp->plugin_url . 'assets/images/paypal-marketplace.svg' );
				break;
		}
		return apply_filters( 'wcfm_paypal_icon', $icon );
	}

    /**
     * Load admin scripts.
     *
     * @since 2.0.0
     */
    public function wcfm_admin_scripts() {
        global $wcfmpgdp;

        $screen    = get_current_screen();
        $screen_id = $screen ? $screen->id : '';

        if ('woocommerce_page_wc-settings' !== $screen_id) {
            return;
        }

        $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

        wp_enqueue_script('wcfm_paypal_admin', $wcfmpgdp->plugin_url . 'assets/js/wcfm-paypal-admin' . $suffix . '.js', array('jquery'), $wcfmpgdp->version, true);
        wp_localize_script(
            'wcfm_paypal_admin',
            'wcfm_paypal_admin_l10n',
            array(
                'payment_id_prefix' => 'woocommerce_' . Helper::payment_gateway_id() . '_',
            )
        );
    }

    /**
     * Process the payment and return the result
     *
     * @param int $order_id WC_Order id.
     *
     * @since 2.0.0
     *
     * @return array
     */
    public function process_payment($order_id) {
        global $WCFMmp;

        $result     = false;
        $return_url = '';

        $order = wc_get_order($order_id);

        if (!is_a($order, 'WC_Order')) {
            return;
        }

        $split_payers = [];
        $total_vendor_commission = 0;

        $vendor_wise_gross_sales = $WCFMmp->wcfmmp_commission->wcfmmp_split_pay_vendor_wise_gross_sales($order);

        foreach ($vendor_wise_gross_sales as $vendor_id => $gross_sales) {
            $vendor_order_amount = $WCFMmp->wcfmmp_commission->wcfmmp_calculate_vendor_order_commission($vendor_id, $order_id, $order);
            $vendor_commission = wc_format_decimal($vendor_order_amount['commission_amount'], 2);

            if ($vendor_commission >= 0) {
                $split_payers[$vendor_id] = array(
                    'commission'  => $vendor_commission,
                );

                if (isset($vendor_wise_gross_sales[$vendor_id])) {
                    $split_payers[$vendor_id]['gross_sales'] = wc_format_decimal($gross_sales, 2);
                }
            }

            $total_vendor_commission += $vendor_commission;
        }

        $purchase_units = [];

        foreach ($split_payers as $vendor_id => $distribution_info) {
            $purchase_units[] =  [
                'reference_id'        => $order->get_order_key() . '__' . $vendor_id,
                'amount'              => [
                    'currency_code' => $order->get_currency(),
                    'value'         => wc_format_decimal($distribution_info['gross_sales'], 2)
                ],
                'payee'               => [
                    'merchant_id' => Helper::get_paypal_merchant_id($vendor_id),
                ],
                'shipping'            => [
                    'address' => [
                        'name'           => [
                            'given_name' => $order->get_billing_first_name(),
                            'surname'    => $order->get_billing_last_name(),
                        ],
                        'address_line_1' => $order->get_billing_address_1(),
                        'address_line_2' => $order->get_billing_address_2(),
                        'admin_area_2'   => $order->get_billing_city(),
                        'admin_area_1'   => $order->get_billing_state(),
                        'postal_code'    => $order->get_billing_postcode(),
                        'country_code'   => $order->get_billing_country(),
                    ],
                ],
                'payment_instruction' => [
                    'disbursement_mode' => Helper::get_settings('disbursement_mode'),
                    'platform_fees'     => [
                        [
                            'amount' => [
                                'currency_code' => $order->get_currency(),
                                'value'         => wc_format_decimal($distribution_info['gross_sales'] - $distribution_info['commission'], 2),
                            ],
                        ],
                    ],
                ],
                'invoice_id' => $order_id,
            ];
        }

        $payload = [
            'intent'              => 'CAPTURE',
            'payer'               => [
                'email_address' => $order->get_billing_email(),
                'name'  => [
                    'given_name' => $order->get_billing_first_name(),
                    'surname'    => $order->get_billing_last_name(),
                ],
                'address' => [
                    'address_line_1' => $order->get_billing_address_1(),
                    'address_line_2' => $order->get_billing_address_2(),
                    'admin_area_2'   => $order->get_billing_city(),
                    'admin_area_1'   => $order->get_billing_state(),
                    'postal_code'    => $order->get_billing_postcode(),
                    'country_code'   => $order->get_billing_country(),
                ],
            ],
            'purchase_units'      => $purchase_units,
            'application_context' => [
                'brand_name'          => get_bloginfo('name'),
                'shipping_preference' => 'SET_PROVIDED_ADDRESS',
                'user_action'         => 'PAY_NOW',
                'payment_method'      => [
                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                ],
                'return_url'          => $this->get_return_url($order),
                'cancel_url'          => $order->get_cancel_order_url_raw(),
            ],
        ];

        $client         = Client::init();
        $paypal_order   = $client->create_paypal_order($payload);

        if (is_wp_error($paypal_order)) {
            throw new Exception($paypal_order->get_error_message());
        }

        if (
            isset($paypal_order->status)
            && 'CREATED' === $paypal_order->status
            && isset($paypal_order->links[1])
            && 'approve' === $paypal_order->links[1]->rel
        ) {
            $result = 'success';
            $return_url = $paypal_order->links[1]->href;
        }

        return array(
            'result'              => $result,
            'id'                  => $order_id,
            'paypal_redirect_url' => $return_url,
            'paypal_order_id'     => $paypal_order->id,
            'redirect'            => $return_url,
            'success_redirect'    => $this->get_return_url($order),
            'cancel_redirect'     => $order->get_cancel_order_url_raw(),
        );
    }

    /**
     * Process refund via PayPal API
     *
     * @param int $refund_id
     * @param int $order_id
     * @param int $vendor_id
     * @param WC_Order_Refund|WP_Error $refund
     */
    public function process_api_refund($refund_id, $order_id, $vendor_id, $refund) {
        global $WCFMmp, $wpdb;

        if( $WCFMmp->refund_processed ) return;

        $order = wc_get_order($order_id);

        if (!is_a($order, 'WC_Order')) {
            return;
        }

        if( $order->get_payment_method() != Helper::payment_gateway_id() ) return;

        $sql = "SELECT ID, item_id, commission_id, vendor_id, order_id, is_partially_refunded, refunded_amount, refund_reason FROM {$wpdb->prefix}wcfm_marketplace_refund_request";
		$sql .= " WHERE 1=1";
		$sql .= " AND ID = %d";
		$refund_infos = $wpdb->get_results( $wpdb->prepare($sql, $refund_id) );

        if( !empty( $refund_infos ) ) {
			foreach( $refund_infos as $refund_info ) {
				$refunded_amount       = (float) $refund_info->refunded_amount;
				$refund_reason         = $refund_info->refund_reason;

                $capture_id = $order->get_meta('_wcfm_paypal_payment_charge_captured__for_vendor_' . $vendor_id, true);

                $has_refund_id = $order->get_meta('_wcfm_paypal_payment_refund_id_' . $refund_id);

                if ($has_refund_id) return;

                $is_refund_processed = $order->get_meta('_wcfm_paypal_payment_refund_processed_' . $refund_id);

                if ($is_refund_processed) return;

                $WCFMmp->refund_processed = true;

                $payload = [
                    'amount'        => [
                        'value' => $refunded_amount,
                        'currency_code' => $order->get_currency()
                    ],
                    'note_to_payer' => $refund_reason
                ];

                $client                 = Client::init();
                $paypal_auth_assertion  = $client->create_paypal_auth_assertion($vendor_id);

                $request = new CapturesRefundRequest($capture_id);
                $request->payPalRequestId($refund_id);
                $request->prefer('return=representation');
                $request->headers['PayPal-Auth-Assertion'] = $paypal_auth_assertion;
                $request->headers['Authorization'] = 'Bearer ' . $client->get_access_token();
                $request->body = $payload;

                $response = $client->do_api_request($request);

                if (is_wp_error($response)) {
                    $error_message = '';
                    $error_data = $response->get_error_data();

                    if( is_array( $error_data ) ) {
                        if( isset( $error_data['details'] ) && is_array( $error_data['details'] ) ) {
                            $details = $error_data['details'][0];
                            if( isset( $details['description'] ) ) {
                                $error_message = $details['description'];
                            }

                            if( isset( $error_data['debug_id'] ) ) {
                                $error_message .= ' [debug_id: ' . $error_data['debug_id'] . ']';
                            }
                        }
                    }

                    wcfm_paypal_log('[WCFM Paypal Marketplace] Paypal Refund(#' . $refund_id . ') Failed for order #' . $order_id . ': ' . print_r($error_message, true), 'error');

                    echo '{"status": false, "message": "' . __('Refund processing failed, please check wcfm log.', 'wc-multivendor-marketplace') . '"}';
					die;
                }

                wcfm_paypal_log('[WCFM Paypal Marketplace] Paypal Refund(#' . $refund_id . ') Initiated for order #' . $order_id, 'info');
                $order->update_meta_data('_wcfm_paypal_payment_refund_id_' . $refund_id, $response->id);
                $order->update_meta_data('_wcfm_paypal_payment_refund_status_' . $refund_id, $response->status);
                $order->update_meta_data('_wcfm_paypal_payment_refund_processed_' . $refund_id, 'yes');
                $order->add_order_note(sprintf(__('Refund Processed Via Paypal Marketplace ( Refund ID: #%s )', 'wc-frontend-manager-direct-paypal'), $refund_id));
                $order->save();
            }
        }
    }

    /**
	 * Custom PayPal order received text.
	 *
	 * @since 2.0.0
	 * @param string   $text Default text.
	 * @param WC_Order $order Order data.
	 * @return string
	 */
	public function order_received_text( $text, $order ) {
		if ( $order && $this->id === $order->get_payment_method() ) {
			return esc_html__( 'Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you. Log into your PayPal account to view transaction details.', 'wc-frontend-manager-direct-paypal' );
		}

		return $text;
	}
}
