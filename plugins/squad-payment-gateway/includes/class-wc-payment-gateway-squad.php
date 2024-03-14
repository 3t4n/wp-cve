<?php


if (!defined('ABSPATH')) {
	exit;
}
class WC_Gateway_Squad extends WC_Payment_Gateway
{

	/**
	 * Constructor for the gateway.
	 */
	public function __construct()
	{
		// Setup general properties.
		$this->setup_properties();

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Get setting values (from my created input values)
		$this->title       = $this->get_option('title');
		$this->description = $this->get_option('description');
		$this->enabled     = $this->get_option('enabled');
		$this->testmode    = $this->get_option('testmode') === 'yes' ? true : false;
		$this->autocomplete_order = $this->get_option('autocomplete_order') === 'yes' ? true : false;

		$this->test_public_key = $this->get_option('test_public_key');
		$this->test_secret_key = $this->get_option('test_secret_key');

		$this->live_public_key = $this->get_option('live_public_key');
		$this->live_secret_key = $this->get_option('live_secret_key');

		$this->custom_metadata = $this->get_option('custom_metadata') === 'yes' ? true : false;

		$this->public_key = $this->testmode ? $this->test_public_key : $this->live_public_key;
		$this->secret_key = $this->testmode ? $this->test_secret_key : $this->live_secret_key;

		$this->base_url = $this->testmode == true ? "https://sandbox-developer.squadco.com" : "https://api-d.squadco.com";


		$this->webhook_url = $this->get_option('webhook_url');
		$this->payment_options = $this->get_option('payment_options');
		$this->instructions = $this->get_option('instructions');

		// Hooks
		add_action('wp_enqueue_scripts', array($this, 'load_payment_scripts'));
		// $this->load_payment_scripts();
		add_action('woocommerce_receipt_' . $this->id, array($this, 'receipt_page'));

		// Payment(Webhook) listener/API hook 
		/**
		 * format=> $woocommerce_api_[:class name in lowercase]
		 * eg: woocommerce_api_wc_gateway_squad
		 */
		// add_action( 'woocommerce_api_wc_gateway_squad', array( $this, 'squad_verify_transaction' ) );
		add_action('woocommerce_api_' . strtolower(get_class($this)), array(&$this, 'squad_verify_transaction'));
		add_action('woocommerce_api_squad_wc_payment_webhook', array($this, 'process_webhooks'));
		add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));

		add_action('admin_notices', array($this, 'admin_notices'));
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
	}

	/**
	 * Setup general properties for the gateway.
	 */
	protected function setup_properties()
	{
		$this->id                 = 'squad';
		$this->icon               = apply_filters('woocommerce_squad_icon', plugins_url('../assets/icon.png', __FILE__));
		$this->method_title       = __('Squad', 'squad-payment-gateway');
		$this->method_description = sprintf(__('Squad provide merchants with the tools and services needed to accept online payments from local and international customers using 
		Mastercard, Visa, Verve Cards and Bank Accounts. <a href="%1$s" target="_blank">Sign up</a> for a Squad account, and 
		<a href="%2$s" target="_blank">get your API keys</a>.', 'squad-payment-gateway'), 'https://squadco.com', 'https://dashboard.squadco.com/settings');
		$this->has_fields         = false;


		$this->subaccount_list = [];
		$this->product_id_list = [];
		$this->subtotal_charge = [];
		$this->transaction_charge = [];
		$this->subaccount_ratio = [];

		$this->icon = plugins_url('assets/img/squad.png', WC_SQUAD_MAIN_FILE);

		// declare support for Woocommerce subscription
		$this->supports = array(
			'products',
			'refunds',
			'tokenization',
			'subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change',
			'subscription_payment_method_change_customer',
			'subscription_payment_method_change_admin',
			'multiple_subscriptions',
		);
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields()
	{
		$this->form_fields = array(
			'enabled'                          => array(
				'title'       => __('Enable/Disable', 'squad-payment-gateway'),
				'label'       => __('Enable Squad Payment Gateway', 'squad-payment-gateway'),
				'type'        => 'checkbox',
				'description' => __('Enable Squad Payment Gateway as a payment option on the checkout page.', 'squad-payment-gateway'),
				'default'     => 'no',
				'desc_tip'    => true,
			),
			'title'              => array(
				'title'       => __('Title', 'squad-payment-gateway'),
				'type'        => 'text',
				'description' => __('This controls the payment method title which the user sees during checkout.', 'squad-payment-gateway'),
				'default'     => __('Squad Payment Gateway', 'squad-payment-gateway'),
				'desc_tip'    => true,
			),

			'description'        => array(
				'title'       => __('Description', 'squad-payment-gateway'),
				'type'        => 'textarea',
				'description' => __('This controls the payment method description which the user sees during checkout.', 'squad-payment-gateway'),
				'default'     => __('Make payment using your debit and credit cards', 'squad-payment-gateway'),
				'desc_tip'    => true,
			),
			'testmode'                         => array(
				'title'       => __('Test mode', 'squad-payment-gateway'),
				'label'       => __('Enable Test Mode', 'squad-payment-gateway'),
				'type'        => 'checkbox',
				'description' => __('Test mode enables you to test payments before going live. <br />Once the LIVE MODE is enabled on your Squad account uncheck this.', 'squad-payment-gateway'),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'test_secret_key'                  => array(
				'title'       => __('Test Secret Key', 'squad-payment-gateway'),
				'type'        => 'text',
				'description' => __('Enter your TEST Secret Key here. Get it on https://sandbox.squadco.com', 'squad-payment-gateway'),
				'default'     => '',
			),
			'test_public_key'                  => array(
				'title'       => __('Test Public Key', 'squad-payment-gateway'),
				'type'        => 'text',
				'description' => __('Enter your TEST Public Key here. Get it on https://sandbox.squadco.com', 'squad-payment-gateway'),
				'default'     => '',
			),
			'live_secret_key'                  => array(
				'title'       => __('Live Secret Key', 'squad-payment-gateway'),
				'type'        => 'text',
				'description' => __('Enter your LIVE Secret Key here. Get it on https://dashboard.squadco.com', 'squad-payment-gateway'),
				'default'     => '',
			),
			'live_public_key'                  => array(
				'title'       => __('Live Public Key', 'squad-payment-gateway'),
				'type'        => 'text',
				'description' => __('Enter your LIVE Public Key here. Get it on https://dashboard.squadco.com', 'squad-payment-gateway'),
				'default'     => '',
			),
			'autocomplete_order'               => array(
				'title'       => __('Autocomplete Order After Payment', 'squad-payment-gateway'),
				'label'       => __('Autocomplete Order', 'squad-payment-gateway'),
				'type'        => 'checkbox',
				'class'       => 'wc-squad-autocomplete-order',
				'description' => __('If enabled, the order will be marked as complete after successful payment', 'squad-payment-gateway'),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'custom_metadata'                  => array(
				'title'       => __('Custom Metadata', 'squad-payment-gateway'),
				'label'       => __('Enable Custom Metadata', 'squad-payment-gateway'),
				'type'        => 'checkbox',
				'class'       => 'wc-squad-metadata',
				'description' => __('If enabled, you will be able to send customer information about the order to Squad.', 'squad-payment-gateway'),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'payment_options' => array(
				'title'             => __('Payment Options', 'squad-payment-gateway'),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select',
				'default'           => ['card'],
				'description'       => __('Choice of payment method to use. Card, Transfer etc.', 'squad-payment-gateway'),
				'options'           => array(
					'card' => __("Card", "squad-payment-gateway"),
					'transfer' => __("Transfer", "squad-payment-gateway"),
					'ussd' => __("USSD", "squad-payment-gateway"),
					'bank' => __("Bank", "squad-payment-gateway"),
				),
				'custom_attributes' => array(
					'data-placeholder' => __('Select payment options', 'squad-payment-gateway'),
				),
			),
			'instructions'       => array(
				'title'       => __('Instructions', 'squad-payment-gateway'),
				'type'        => 'textarea',
				'description' => __('Message before delivery. eg Your order will be delivered soon.', 'squad-payment-gateway'),
				'default'     => __('Your order will be delivered soon.', 'squad-payment-gateway'),
				'desc_tip'    => true,
			),
		);
	}

	/**
	 * Admin Panel Options.
	 */
	public function admin_options()
	{

?>

		<h2><?php _e('Squad', 'squad-payment-gateway'); ?>
			<?php
			if (function_exists('wc_back_link')) {
				wc_back_link(__('Return to payments', 'squad-payment-gateway'), admin_url('admin.php?page=wc-settings&tab=checkout'));
			}
			?>
		</h2>

		<h4>
			<strong><?php printf(__('Optional: To avoid situations where bad network makes it impossible to verify transactions, set your webhook URL <a href="%1$s" target="_blank" rel="noopener noreferrer">here</a> to the URL below<span style="color: red"><pre><code>%2$s</code></pre></span>', 'squad-payment-gateway'), 'https://dashboard.squadco.com/profile/api-webhooks', WC()->api_request_url('Squad_WC_Payment_Webhook')); ?></strong>
		</h4>

		<?php

		// if ($this->is_valid_for_use()) {
		if (true) {

			echo '<table class="form-table">';
			$this->generate_settings_html();
			echo '</table>';
		} else {
		?>
			<div class="inline error">
				<p><strong><?php _e('Squad Payment Gateway Disabled', 'squad-payment-gateway'); ?></strong>: <?php echo $this->msg; ?></p>
			</div>

<?php
		}
	}

	/** 
	 * Outputs scripts used for squad payment.
	 */
	public function load_payment_scripts()
	{

		if (!is_checkout_pay_page()) {
			return;
		}

		if ($this->enabled === 'no') {
			return;
		}

		$order_key = urldecode(sanitize_text_field(isset($_GET['key']) ? $_GET['key'] : ''));
		$order_id  = absint(get_query_var('order-pay'));

		$order = wc_get_order($order_id);

		//check payment method
		$payment_method = method_exists($order, 'get_payment_method') ? $order->get_payment_method() : $order->payment_method;

		//exit script if mine is not selected
		if ($this->id !== $payment_method) {
			return;
		}

		wp_enqueue_script('jquery');

		wp_enqueue_script('squad', "https://checkout.squadco.com/widget/squad.min.js?t=" . time(), array('jquery'), WC_SQUAD_VERSION, false);

		//wc_squad--> js key name
		wp_enqueue_script('wc_squad', plugins_url('assets/js/squad.js', WC_SQUAD_MAIN_FILE), array('jquery', 'squad'), WC_SQUAD_VERSION, false);

		$squad_params = array(
			'public_key' => $this->public_key,
		);

		if (is_checkout_pay_page() && get_query_var('order-pay')) {

			$email         	= method_exists($order, 'get_billing_email') ? $order->get_billing_email() : $order->billing_email;
			$amount        	= $order->get_total() * 100;
			$txnref        	= 'WOO' . $order_id . 'T' . time(); //gen txnref from order id
			$txnref    		= sanitize_text_field($txnref); //sanitizr=e this field

			$the_order_id  = method_exists($order, 'get_id') ? $order->get_id() : $order->id;
			$the_order_key = method_exists($order, 'get_order_key') ? $order->get_order_key() : $order->order_key;

			if ($the_order_id == $order_id && $the_order_key == $order_key) {

				$squad_params['email']        = $email;
				$squad_params['amount']       = $amount;
				$squad_params['order_id']     = $order_id;
				$squad_params['txnref']       = $txnref;
				$squad_params['webhook_url']  = $this->webhook_url;
				$squad_params['payment_options']  = $this->payment_options;
				$squad_params['currency']     = get_woocommerce_currency();
			}

			if ($this->custom_metadata) {

				//--> Include order id meta
				$squad_params['meta_order_id'] = $order_id;

				//include name
				$first_name = method_exists($order, 'get_billing_first_name') ? $order->get_billing_first_name() : $order->billing_first_name;
				$last_name  = method_exists($order, 'get_billing_last_name') ? $order->get_billing_last_name() : $order->billing_last_name;

				$squad_params['meta_name'] = $first_name . ' ' . $last_name;


				//Include phone
				$billing_phone = method_exists($order, 'get_billing_phone') ? $order->get_billing_phone() : $order->billing_phone;
				$squad_params['meta_phone'] = $billing_phone;

				//Include products
				$line_items = $order->get_items();
				$products = '';

				foreach ($line_items as $item_id => $item) {
					$name      = $item['name'];
					$quantity  = $item['qty'];
					$products .= $name . ' (Qty: ' . $quantity . ')';
					$products .= ' | ';
				}

				$products = rtrim($products, ' | ');
				$squad_params['meta_products'] = $products;


				//--> Billing address
				$billing_address = $order->get_formatted_billing_address();
				$billing_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $billing_address));

				$squad_params['meta_billing_address'] = $billing_address;

				//--> Shipping address
				$shipping_address = $order->get_formatted_shipping_address();
				$shipping_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $shipping_address));

				if (empty($shipping_address)) {

					$billing_address = $order->get_formatted_billing_address();
					$billing_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $billing_address));

					$shipping_address = $billing_address;
				}

				$squad_params['meta_shipping_address'] = $shipping_address;
			}

			//--> register/add '_squad_txn_ref' variable to the(this) current order
			update_post_meta($order_id, '_squad_txn_ref', $txnref);
		}

		//--> retrieve "wc_squad" as set above and include the params
		//--> also, post 'squad_params' as 'wc_squad_params' on 'squad.js' page
		wp_localize_script('wc_squad', 'wc_squad_params', $squad_params);
	}

	/**
	 * Load admin scripts
	 */
	public function admin_scripts()
	{
		if ('woocommerce_page_wc-settings' !== get_current_screen()->id) {
			// return;
		}

		$squad_admin_params = array(
			// 'plugin_url' => FLW_WC_ASSET_URL,
			'countSubaccount' => $this->get_option('subaccount_count_saved')
		);
		wp_enqueue_script('wc_squad_admin', plugins_url('assets/js/squad-admin.js', WC_SQUAD_MAIN_FILE), array(), WC_SQUAD_VERSION, true);

		//post 'squad_admin_params' as 'wc_squad_admin_params' on 'squad-admin.js' page
		wp_localize_script('wc_squad_admin', 'wc_squad_admin_params', $squad_admin_params);
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment($order_id)
	{
		$order = wc_get_order($order_id);


		return array(
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url(true),
		);
	}


	/**
	 * Displays the payment page
	 */
	public function receipt_page($order_id)
	{
		$order = wc_get_order($order_id);
		// $order = new WC_Order( $order_id );

		echo ('<div id="wc-squad-form">');

		echo ('<p>' . __('Thank you for your order, please click the button below to pay with Squad.', 'squad-payment-gateway') . '</p>');

		echo ('<div id="squad_form"><form id="order_review" method="post" action="' . WC()->api_request_url('WC_Gateway_Squad') . '"></form><button class="button" id="squad-payment-button">' . __('Make Payment', 'squad-payment-gateway') . '</button>');

		echo ('  <a class="button cancel" id="squad-cancel-payment-button" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'squad-payment-gateway') . '</a></div>');

		echo ('</div>');
	}


	/**
	 * Check if Sqaud merchant details is filled.
	 */
	public function admin_notices()
	{

		if ($this->enabled == 'no') {
			return;
		}

		// Check required fields.
		if (!($this->public_key && $this->secret_key)) {
			echo esc_html('<div class="error"><p>' . sprintf(__('Please enter your Sqaud merchant details <a href="%s">here</a> to be able to use the Sqaud WooCommerce plugin.', 'squad-payment-gateway'), admin_url('admin.php?page=wc-settings&tab=checkout&section=squad')) . '</p></div>');
			return;
		}
	}

	/**
	 * Check If The Gateway Is Available For Use(enabled).
	 *
	 * @return bool
	 */
	public function is_available()
	{

		if ('yes' === $this->enabled) {
			if (!($this->public_key && $this->secret_key)) {
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * Display Squad payment icon.
	 */
	public function get_icon()
	{
		// $base_location = wc_get_base_location();

		// if ( 'NG' === $base_location['country'] ) {
		// 	$icon = '<img src="' . WC_HTTPS::force_https_url( plugins_url( 'assets/images/logo.png', WC_SQUAD_MAIN_FILE ) ) . '" alt="Squad Payment Options" />';
		// }else {
		// 	$icon = '<img src="' . WC_HTTPS::force_https_url( plugins_url( 'assets/images/logo.png', WC_SQUAD_MAIN_FILE ) ) . '" alt="Squad Payment Options" />';
		// }

		// return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );


		//--> Empty icon for now 
		return apply_filters('woocommerce_gateway_icon', "", $this->id);
	}

	/**
	 * Output for the order received page.
	 */
	public function thankyou_page($orderid)
	{
		if ($this->instructions) {
			echo wp_kses_post(wpautop(wptexturize($this->instructions)));
			return;
		}

		$order = wc_get_order($orderid);
	}

	/**
	 * Change payment complete order status to completed for squad orders.
	 *
	 * @since  1.0.1
	 * @param  string         $status Current order status.
	 * @param  int            $order_id Order ID.
	 * @param  WC_Order|false $order Order object.
	 * @return string
	 */
	public function change_payment_complete_order_status($status, $order_id = 0, $order = false)
	{
		if ($order && 'squad' === $order->get_payment_method()) {
			$status = 'completed';
		}
		return $status;
	}


	/**
	 * Verify Squad payment
	 */
	public function squad_verify_transaction()
	{
		sleep(4);
		if (isset($_REQUEST['squad_txnref'])) {
			$squad_txn_ref = sanitize_text_field($_REQUEST['squad_txnref']);
		} else {
			$squad_txn_ref = false;
		}

		@ob_clean();
		if ($squad_txn_ref) {
			$squad_verify_url =	$this->base_url . "/transaction/verify/${squad_txn_ref}";

			$headers = array(
				'Authorization' => 'Bearer ' . $this->secret_key,
			);

			$args = array(
				'headers' => $headers,
				'timeout' => 60,
			);

			$request = wp_remote_get($squad_verify_url, $args);

			if (!is_wp_error($request) && 200 === wp_remote_retrieve_response_code($request)) {

				$squad_response = json_decode(wp_remote_retrieve_body($request));

				$transStatus = $squad_response->data->transaction_status;
				$success = strtolower($transStatus) == "success" ? true : false;

				if ($success) {

					$transaction_ref = sanitize_text_field($squad_response->data->transaction_ref);
					$order_details = explode('T', $transaction_ref);
					$order_id      = (int) str_replace('WOO', '', $order_details[0]);

					$order         = wc_get_order($order_id);

					if (in_array($order->get_status(), array('processing', 'completed', 'on-hold'))) {
						wp_redirect($this->get_return_url($order));

						exit;
					}


					$order_total      = (float) $order->get_total();
					$order_currency   = method_exists($order, 'get_currency') ? $order->get_currency() : $order->get_order_currency();
					$currency_symbol  = get_woocommerce_currency_symbol($order_currency);
					$amount_paid      = $squad_response->data->transaction_amount / 100;
					$squad_ref     = $transaction_ref;
					$payment_currency = strtoupper($squad_response->data->transaction_currency_id);
					$gateway_symbol   = get_woocommerce_currency_symbol($payment_currency);


					// check if the amount paid is equal to the order amount.
					if ($amount_paid < $order_total) {

						$order->update_status('on-hold', '');

						add_post_meta($order_id, '_transaction_id', $squad_ref, true);

						$notice      = sprintf(__('Thank you for shopping with us.%1$sYour payment transaction was successful, but the amount paid is not the same as the total order amount.%2$sYour order is currently on hold.%3$sKindly contact us for more information regarding your order and payment status.', 'squad-payment-gateway'), '<br />', '<br />', '<br />');
						$notice_type = 'notice';

						// Add Customer Order Note
						$order->add_order_note($notice, 1);

						// Add Admin Order Note
						$admin_order_note = sprintf(__('<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Amount paid is less than the total order amount.%3$sAmount Paid was <strong>%4$s (%5$s)</strong> while the total order amount is <strong>%6$s (%7$s)</strong>%8$s<strong>Squad Transaction Reference:</strong> %9$s', 'squad-payment-gateway'), '<br />', '<br />', '<br />', $currency_symbol, $amount_paid, $currency_symbol, $order_total, '<br />', $squad_ref);
						$order->add_order_note($admin_order_note);

						function_exists('wc_reduce_stock_levels') ? wc_reduce_stock_levels($order_id) : $order->reduce_order_stock();

						wc_add_notice($notice, $notice_type);
					} else {

						if ($payment_currency !== $order_currency) {

							$order->update_status('on-hold', '');

							update_post_meta($order_id, '_transaction_id', $squad_ref);

							$notice      = sprintf(__('Thank you for shopping with us.%1$sYour payment was successful, but the payment currency is different from the order currency.%2$sYour order is currently on-hold.%3$sKindly contact us for more information regarding your order and payment status.', 'squad-payment-gateway'), '<br />', '<br />', '<br />');
							$notice_type = 'notice';

							// Add Customer Order Note
							$order->add_order_note($notice, 1);

							// Add Admin Order Note
							$admin_order_note = sprintf(__('<strong>Look into this order</strong>%1$sThis order is currently on hold.%2$sReason: Order currency is different from the payment currency.%3$sOrder Currency is <strong>%4$s (%5$s)</strong> while the payment currency is <strong>%6$s (%7$s)</strong>%8$s<strong>Squad Transaction Reference:</strong> %9$s', 'squad-payment-gateway'), '<br />', '<br />', '<br />', $order_currency, $currency_symbol, $payment_currency, $gateway_symbol, '<br />', $squad_ref);
							$order->add_order_note($admin_order_note);

							function_exists('wc_reduce_stock_levels') ? wc_reduce_stock_levels($order_id) : $order->reduce_order_stock();

							wc_add_notice($notice, $notice_type);
						} else {

							$order->payment_complete($squad_ref);
							$order->add_order_note(sprintf(__('Payment via Squad successful (Transaction Reference: %s)', 'squad-payment-gateway'), $squad_ref));

							if ($this->is_autocomplete_order_enabled($order)) {
								$order->update_status('completed');
							}
						}
					}

					WC()->cart->empty_cart();
				} else {
					$order_details = explode('T', $squad_txn_ref);
					$order_id      = (int) str_replace('WOO', '', $order_details[0]);

					$order = wc_get_order($order_id);

					$order->update_status('failed', __('Payment was declined by Squad.', 'squad-payment-gateway'));
				}
			}

			if (!empty($this->webhook_url)) {
				$body = $this->getOrderMeta($order_id, $amount_paid, $squad_ref);

				$data = wp_remote_post($this->webhook_url, array(
					'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
					'body'        => json_encode($body),
					'method'      => 'POST',
					'data_format' => 'body',
				));
			}

			wp_redirect($this->get_return_url($order));

			exit;
		}

		wp_redirect(wc_get_page_permalink('cart'));

		exit;
	}

	protected function getOrderMeta($order_id, $amount, $txnref)
	{
		$order_params = [];
		$order         = wc_get_order($order_id);

		$email         	= method_exists($order, 'get_billing_email') ? $order->get_billing_email() : $order->billing_email;

		$order_params['customer_email']        = $email;
		$order_params['amount']       = $amount;
		$order_params['order_id']     = $order_id;
		$order_params['txnref']       = $txnref;
		$order_params['currency']     = get_woocommerce_currency();

		//include name
		$first_name = method_exists($order, 'get_billing_first_name') ? $order->get_billing_first_name() : $order->billing_first_name;
		$last_name  = method_exists($order, 'get_billing_last_name') ? $order->get_billing_last_name() : $order->billing_last_name;

		$order_params['customer_name'] = $first_name . ' ' . $last_name;

		//Include phone
		$billing_phone = method_exists($order, 'get_billing_phone') ? $order->get_billing_phone() : $order->billing_phone;
		$order_params['customer_phone'] = $billing_phone;

		//Include products
		$line_items = $order->get_items();
		$products = [];

		foreach ($line_items as $item_id => $item) {
			$name      = $item['name'];
			$quantity  = $item['qty'];
			$product_id  = $item['product_id'];
			$quantity  = $item['qty'];

			// get product_tags of the current product
			$current_tags = get_the_terms($product_id, 'product_tag');
			$tags = [];
			if ($current_tags && !is_wp_error($current_tags)) {

				foreach ($current_tags as $tag) {
					$tag_title = $tag->name; // tag name
					$tag_id = $tag->term_id; // tag id
					$tag_link = get_term_link($tag); // tag archive link

					$newTag = [
						"tag_title" => $tag_title,
						"tag_id" => $tag_id,
						"tag_link" => $tag_link,
					];
					array_push($tags, $newTag);
				}
			}
			$newElement = [
				"Name" => $name,
				"Qty" => $quantity,
				"PiD" => $product_id,
				"tags" => $tags,
			];
			array_push($products, $newElement);
		}
		$order_params['products'] = $products;

		//--> Billing address
		$billing_address = $order->get_formatted_billing_address();
		$billing_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $billing_address));
		$order_params['customer_billing_address'] = $billing_address;

		//--> Shipping address
		$shipping_address = $order->get_formatted_shipping_address();
		$shipping_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $shipping_address));

		if (empty($shipping_address)) {
			$billing_address = $order->get_formatted_billing_address();
			$billing_address = esc_html(preg_replace('#<br\s*/?>#i', ', ', $billing_address));

			$shipping_address = $billing_address;
		}

		$order_params['customer_shipping_address'] = $shipping_address;

		return $order_params;
	}

	/**
	 * Checks if autocomplete order is enabled for the payment method.
	 *
	 * @since 1.0
	 * @param WC_Order $order Order object.
	 * @return bool
	 */
	protected function is_autocomplete_order_enabled($order)
	{
		$autocomplete_order = false;

		$payment_method = $order->get_payment_method();

		$squad_settings = get_option('woocommerce_' . $payment_method . '_settings');

		if (isset($squad_settings['autocomplete_order']) && 'yes' === $squad_settings['autocomplete_order']) {
			$autocomplete_order = true;
		}

		return $autocomplete_order;
	}

	private function logToFile($data)
	{
		$filename = time() . "-logs.txt";
		$filename = "98726763276-logs.txt"; // remove later
		$fh = fopen($filename, "a");
		fwrite($fh, "\n");
		fwrite($fh, $data);
		fclose($fh);
	}
	/**
	 * Process Webhook
	 */
	public function process_webhooks()
	{
		if ((strtoupper(sanitize_text_field(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '')) != 'POST') || !array_key_exists('HTTP_X_SQUAD_ENCRYPTED_BODY', $_SERVER)) {
			exit;
		}
		// Retrieve the request's body
		$json = @file_get_contents('php://input');

		// $body = trim(str_replace("\n", '', str_replace(' ', '', $json)));
		// $this->logToFile($body);
		// $this->logToFile(strtoupper(hash_hmac('sha512', $body, 'sandbox_sk_94f2b798466408ef4d19e848ee1a4d1a3e93f104046f'))); //remove later
		// validate event do all at once to avoid timing attack
		if (hash_hmac('sha512', $json, $this->secret_key) !== $_SERVER['HTTP_X_SQUAD_ENCRYPTED_BODY']) {
			exit;
		}

		$event = json_decode($json);

		if ('charge_successful' == $event->Event) {

			sleep(10);

			$order_details = explode('T', $event->Body->transaction_ref);
			$order_id      = (int) str_replace('WOO', '', $order_details[0]);

			$order = wc_get_order($order_id);

			$squad_txn_ref = get_post_meta($order_id, '_squad_txn_ref', true);

			if ($event->Body->transaction_ref != $squad_txn_ref) {
				exit;
			}

			http_response_code(200);

			if (in_array($order->get_status(), array('processing', 'completed', 'on-hold'))) {
				exit;
			}

			$order_currency = method_exists($order, 'get_currency') ? $order->get_currency() : $order->get_order_currency();

			$currency_symbol = get_woocommerce_currency_symbol($order_currency);

			$order_total = $order->get_total();

			$amount_paid = $event->Body->amount / 100;

			$squad_ref = $event->Body->transaction_ref;

			// check if the amount paid is equal to the order amount.
			if ($amount_paid < $order_total) {

				$order->update_status('on-hold', '');

				add_post_meta($order_id, '_transaction_id', $squad_ref, true);

				$notice      = 'Thank you for shopping with us.<br />Your payment transaction was successful, but the amount paid is not the same as the total order amount.<br />Your order is currently on-hold.<br />Kindly contact us for more information regarding your order and payment status.';
				$notice_type = 'notice';

				// Add Customer Order Note
				$order->add_order_note($notice, 1);

				// Add Admin Order Note
				$order->add_order_note('<strong>Look into this order</strong><br />This order is currently on hold.<br />Reason: Amount paid is less than the total order amount.<br />Amount Paid was <strong>' . $currency_symbol . $amount_paid . '</strong> while the total order amount is <strong>' . $currency_symbol . $order_total . '</strong><br />Squad Transaction Reference: ' . $squad_ref);

				$order->reduce_order_stock();

				wc_add_notice($notice, $notice_type);

				wc_empty_cart();
			} else {
				$order->payment_complete($squad_ref);

				$order->add_order_note(sprintf(__('Payment via Squad successful (Transaction Reference: %s)', 'squad-payment-gateway'), $squad_ref));

				WC()->cart->empty_cart();

				if ($this->is_autocomplete_order_enabled($order)) {
					$order->update_status('completed');
				}
			}

			// $this->save_card_details($event, $order->get_user_id(), $order_id);
			exit;
		}

		exit;
	}


	/**
	 * Checks if WC version is less than passed in version.
	 *
	 * @param string $version Version to check against.
	 *
	 * @return bool
	 */
	public function is_wc_lt($version)
	{
		return version_compare(WC_VERSION, $version, '<');
	}
	/**
	 * Process a refund request from the Order details screen.
	 *
	 * @param int    $order_id WC Order ID.
	 * @param null   $amount   WC Order Amount.
	 * @param string $reason   Refund Reason
	 *
	 * @return bool|WP_Error
	 */
	public function process_refund($order_id, $amount = null, $reason = '')
	{


		if (!($this->public_key && $this->secret_key)) {
			return false;
		}

		$order = wc_get_order($order_id);

		if (!$order) {
			return false;
		}


		if ($this->is_wc_lt('3.0')) {
			$order_currency = get_post_meta($order_id, '_order_currency', true);
			$transaction_id = get_post_meta($order_id, '_transaction_id', true);
		} else {
			$order_currency = $order->get_currency();
			$transaction_id = $order->get_transaction_id();
		}

		$verify_url =	$this->base_url . "/transaction/verify/${transaction_id}";

		$headers = array(
			'Authorization' => 'Bearer ' . $this->secret_key,
			'Content-Type' => 'application/json'
		);

		$args = array(
			'headers' => $headers,
			'timeout' => 60,
		);

		$request = wp_remote_get($verify_url, $args);

		if (!is_wp_error($request) && 200 === wp_remote_retrieve_response_code($request)) {

			$squad_response = json_decode(wp_remote_retrieve_body($request));

			$transStatus = $squad_response->data->transaction_status;
			$success = $transStatus == "Success" ? true : false;

			if ($success) {

				$merchant_note = sprintf(__('Refund for Order ID: #%1$s on %2$s', 'squad-payment-gateway'), $order_id, get_site_url());

				$body = wp_json_encode(array(
					// 'transaction'   => $transaction_id,
					// 'amount'        => $amount * 100,
					// 'currency'      => $order_currency,
					// 'customer_note' => $reason,
					// 'merchant_note' => $merchant_note,
					'transaction_ref' => $transaction_id,
					'refund_amount' => $amount * 100,
				));

				$args['body'] = $body;
				$refund_url   = $this->base_url . '/transaction/refund';


				$refund_request = wp_remote_post($refund_url, $args);

				if (!is_wp_error($refund_request) && 200 === wp_remote_retrieve_response_code($refund_request)) {

					$refund_response = json_decode(wp_remote_retrieve_body($refund_request));

					if ($refund_response->success) {
						$amount         = wc_price($amount, array('currency' => $order_currency));
						$refund_id      = $refund_response->data->refund_id ?? "";
						$refund_message = sprintf(__('Refunded %1$s. Refund ID: %2$s. Reason: %3$s', 'squad-payment-gateway'), $amount, $refund_id, $reason);
						$order->add_order_note($refund_message);

						return true;
					}
				} else {

					$refund_response = json_decode(wp_remote_retrieve_body($refund_request));

					if (isset($refund_response->message)) {
						return new WP_Error('error', $refund_response->message);
					} else {
						return new WP_Error('error', __('Can&#39;t process refund at the moment. Try again later.', 'squad-payment-gateway'));
					}
				}
			}
		}
	}

	public function console_log($output, $with_script_tags = true)
	{
		$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
			');';
		if ($with_script_tags) {
			$js_code = '<script>' . $js_code . '</script>';
		}
		echo esc_html($js_code);
	}
}
