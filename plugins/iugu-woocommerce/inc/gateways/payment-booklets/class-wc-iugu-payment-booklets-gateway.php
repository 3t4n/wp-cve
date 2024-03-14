<?php
if (!defined('ABSPATH')) {
	exit;
} // end if;

/**
 * iugu Payment payment-booklets Gateway class.
 *
 * Extended by individual payment gateways to handle payments.
 *
 * @class   WC_Iugu_Payment_Booklets_Gateway
 * @extends WC_Iugu_Gateway
 */
class WC_Iugu_Payment_Booklets_Gateway extends WC_Iugu_Gateway
{

	const gateway_id = 'iugu-payment-booklets';
	/**
	 * Constructor for the gateway.
	 */
	public function __construct()
	{
		$this->id = WC_Iugu_Payment_Booklets_Gateway::gateway_id;
		parent::__construct();
		$this->view_transaction_url = 'https://alia.iugu.com/receive/payment_booklets/%s';
		global $woocommerce;
		$this->icon = apply_filters('iugu_woocommerce_payment_booklets_icon', '');
		$this->method_title = __('iugu - Payment Booklets', IUGU);
		$this->method_description = __('Accept payment booklets payments using iugu.', IUGU);
		// Load the form fields.
		$this->init_form_fields();
		// Load the settings.
		$this->init_settings();
		// Options.
		$this->pass_interest = $this->get_option('pass_interest');
		$this->smallest_installment = $this->get_option('smallest_installment', 5);
		$this->origin_installment = $this->get_option('origin_installment', 'product');
		$this->iugu_number_installments_general = $this->get_option('iugu_number_installments_general', 1);
		$this->deadline = $this->get_option('deadline');
		$this->accepts_payment_pix = $this->get_option('accepts_payment_pix');
		// /**
		//  * Actions.
		//  */
		add_action('woocommerce_api_wc_iugu_payment_booklets_gateway', array($this, 'notification_handler'));
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
		add_action('woocommerce_email_after_order_table', array($this, 'email_instructions'), 10, 3);
		add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'), 9999);
		add_action('woocommerce_order_details_after_order_table', array($this, 'woocommerce_order_details_after_order_table'));
		add_filter('woocommerce_my_account_my_orders_actions', array($this, 'my_orders_payment_booklets_link'), 10, 2);
		if (is_admin()) {
			add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
		} // end if;
	} // end __construct;

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields()
	{
		$this->form_fields = array(
			'enabled' => array(
				'title' => __('Enable/Disable', IUGU),
				'type' => 'checkbox',
				'label' => __('Enable payment booklets payments with iugu', IUGU),
				'default' => 'no'
			),
			'title' => array(
				'title' => __('Title', IUGU),
				'type' => 'text',
				'description' => __('Payment method title seen on the checkout page.', IUGU),
				'default' => __('Payment booklets', IUGU)
			),
			'description' => array(
				'title' => __('Description', IUGU),
				'type' => 'textarea',
				'description' => __('Payment method description seen on the checkout page.', IUGU),
				'default' => __('Pay with payment booklets', IUGU)
			),
			'ignore_due_email' => array(
				'title' => __('Ignore due email', IUGU),
				'type' => 'checkbox',
				'label' => __('When checked, Iugu will not send billing emails to the payer', IUGU),
				'default' => 'no'
			),
			'payment' => array(
				'title' => __('Payment options', IUGU),
				'type' => 'title',
				'description' => ''
			),
			'origin_installment' => array(
				'title' => __('Origin of installment', IUGU),
				'type' => 'select',
				'description' => '',
				'default' => 'product',
				'options' => array(
					'product' => __('Product', IUGU),
					'general' => __('General', IUGU)
				),
			),
			'iugu_number_installments_general' => array(
				'title' => __('Number of Installments', IUGU),
				'type' => 'select',
				'description' => '',
				'default' => '01',
				'options' => array(
					'01' => '01',
					'02' => '02',
					'03' => '03',
					'04' => '04',
					'05' => '05',
					'06' => '06',
					'07' => '07',
					'08' => '08',
					'09' => '09',
					'10' => '10',
					'11' => '11',
					'12' => '12',
					'13' => '13',
					'14' => '14',
					'15' => '15',
					'16' => '16',
					'17' => '17',
					'18' => '18',
					'19' => '19',
					'20' => '20',
					'21' => '21',
					'22' => '22',
					'23' => '23',
					'24' => '24',
				),
			),
			'deadline' => array(
				'title' => __('Default payment deadline', IUGU),
				'type' => 'number',
				'description' => __('Number of days the customer will have to pay on first.', IUGU),
				'default' => '5',
				'custom_attributes' => array(
					'step' => '1',
					'min' => '1'
				)
			),
			'accepts_payment_pix' => array(
				'title' => __('Accepts installment payment with PIX', IUGU),
				'type' => 'checkbox',
				'label' => __('Accepts installment payment with PIX', IUGU),
				'default' => 'no'
			),
			'smallest_installment' => array(
				'title' => __('Smallest installment value', IUGU),
				'type' => 'text',
				'description' => __('Smallest value of each installment. Value can\'t be lower than 5.', IUGU),
				'default' => '5',
			),
			'pass_interest' => array(
				'title' => __('Uses interest in installments', IUGU),
				'type' => 'checkbox',
				'label' => __('Pass on the installments\' interest to the customer.', IUGU),
				'description' => __('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => 'no'
			),
			'interest_rate_on_installment_1' => array(
				'title' => __('Interest rate on installment 1', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '2.51',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_2' => array(
				'title' => __('Interest rate on installment 2', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.21',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_3' => array(
				'title' => __('Interest rate on installment 3', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.21',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_4' => array(
				'title' => __('Interest rate on installment 4', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.21',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_5' => array(
				'title' => __('Interest rate on installment 5', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.21',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_6' => array(
				'title' => __('Interest rate on installment 6', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.21',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_7' => array(
				'title' => __('Interest rate on installment 7', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_7' => array(
				'title' => __('Interest rate on installment 7', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_8' => array(
				'title' => __('Interest rate on installment 8', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_9' => array(
				'title' => __('Interest rate on installment 9', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_10' => array(
				'title' => __('Interest rate on installment 10', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_11' => array(
				'title' => __('Interest rate on installment 11', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_12' => array(
				'title' => __('Interest rate on installment 12', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_13' => array(
				'title' => __('Interest rate on installment 13', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_14' => array(
				'title' => __('Interest rate on installment 14', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_15' => array(
				'title' => __('Interest rate on installment 15', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_16' => array(
				'title' => __('Interest rate on installment 16', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_17' => array(
				'title' => __('Interest rate on installment 17', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_18' => array(
				'title' => __('Interest rate on installment 18', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_19' => array(
				'title' => __('Interest rate on installment 19', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_20' => array(
				'title' => __('Interest rate on installment 20', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_21' => array(
				'title' => __('Interest rate on installment 21', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_22' => array(
				'title' => __('Interest rate on installment 22', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_23' => array(
				'title' => __('Interest rate on installment 23', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
			'interest_rate_on_installment_24' => array(
				'title' => __('Interest rate on installment 24', IUGU),
				'type' => 'number',
				'description' => __('Enter the interest rate set in your iugu plan.', IUGU) . ' ' .
					__('This option is only for display and should mimic your iugu account\'s settings.', IUGU),
				'desc_tip' => true,
				'default' => '3.55',
				'custom_attributes' => array(
					'step' => 'any'
				)
			),
		);
	}

	/**
	 * Call plugin scripts in front-end.
	 */
	public function frontend_scripts()
	{
		if (is_checkout() && 'yes' == $this->enabled) {
			$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_style('iugu-woocommerce-payment-booklets-css', plugins_url('assets/css/payment-booklets' . $suffix . '.css', WC_IUGU_PLUGIN_FILE));
			wp_enqueue_script('iugu-woocommerce-payment-booklets-js', plugins_url('assets/js/payment-booklets' . $suffix . '.js', WC_IUGU_PLUGIN_FILE), array('jquery', 'wc-payment-booklets-form'), WC_Iugu::CLIENT_VERSION, true);
			wp_localize_script(
				'iugu-woocommerce-payment-booklets-js',
				'iugu_wc_payment_booklets_params',
				array(
				)
			);
		}
	}

	/**
	 * Get iugu interest rates.
	 *
	 * @return array
	 */
	public function get_interest_rate()
	{
		$p1 = $this->get_option('interest_rate_on_installment_1', 2.51);
		$p2 = $this->get_option('interest_rate_on_installment_2', 3.21);
		$p3 = $this->get_option('interest_rate_on_installment_3', 3.21);
		$p4 = $this->get_option('interest_rate_on_installment_4', 3.21);
		$p5 = $this->get_option('interest_rate_on_installment_5', 3.21);
		$p6 = $this->get_option('interest_rate_on_installment_6', 3.21);
		$p7 = $this->get_option('interest_rate_on_installment_7', 3.55);
		$p8 = $this->get_option('interest_rate_on_installment_8', 3.55);
		$p9 = $this->get_option('interest_rate_on_installment_9', 3.55);
		$p10 = $this->get_option('interest_rate_on_installment_10', 3.55);
		$p11 = $this->get_option('interest_rate_on_installment_11', 3.55);
		$p12 = $this->get_option('interest_rate_on_installment_12', 3.55);
		$p13 = $this->get_option('interest_rate_on_installment_13', 3.55);
		$p14 = $this->get_option('interest_rate_on_installment_14', 3.55);
		$p15 = $this->get_option('interest_rate_on_installment_15', 3.55);
		$p16 = $this->get_option('interest_rate_on_installment_16', 3.55);
		$p17 = $this->get_option('interest_rate_on_installment_17', 3.55);
		$p18 = $this->get_option('interest_rate_on_installment_18', 3.55);
		$p19 = $this->get_option('interest_rate_on_installment_19', 3.55);
		$p20 = $this->get_option('interest_rate_on_installment_20', 3.55);
		$p21 = $this->get_option('interest_rate_on_installment_21', 3.55);
		$p22 = $this->get_option('interest_rate_on_installment_22', 3.55);
		$p23 = $this->get_option('interest_rate_on_installment_23', 3.55);
		$p24 = $this->get_option('interest_rate_on_installment_24', 3.55);
		$rates = apply_filters('iugu_woocommerce_interest_rates', array(
			'1' => $p1,
			'2' => $p2,
			'3' => $p3,
			'4' => $p4,
			'5' => $p5,
			'6' => $p6,
			'7' => $p7,
			'8' => $p8,
			'9' => $p9,
			'10' => $p10,
			'11' => $p11,
			'12' => $p12,
			'13' => $p13,
			'14' => $p14,
			'15' => $p15,
			'16' => $p16,
			'17' => $p17,
			'18' => $p18,
			'19' => $p19,
			'20' => $p20,
			'21' => $p21,
			'22' => $p22,
			'23' => $p23,
			'24' => $p24,
		));
		return $rates;
	}

	public function get_order_total_local($remove_juros)
	{
		$total = 0;
		$order_id = absint(get_query_var('order-pay'));
		// Gets order total from "pay for order" page.
		if (0 < $order_id) {
			$order = wc_get_order($order_id);
			if ($order) {
				$total = (float) $order->get_total();
				if ($remove_juros) {
					foreach ($order->get_fees() as $fee) {
						if ($fee['name'] == __('Fees', IUGU)) {
							$total -= $fee['line_total'];
						}
					}
				}
			}
			// Gets order total from cart/checkout.
		} else if (isset(WC()->cart) && (0 < WC()->cart->total)) {
			$total = (float) WC()->cart->total;
			if ($remove_juros) {
				foreach (WC()->cart->get_fees() as $fee) {
					if ($fee->name == __('Fees', IUGU)) {
						$total -= $fee->amount;
					}
				}
			}
		} else if (isset(WC()->cart) && isset(WC()->cart->recurring_carts)) {
			foreach (WC()->cart->recurring_carts as $cart) {
				$total += $cart->total;
				if ($remove_juros) {
					foreach ($cart->get_fees() as $fee) {
						if ($fee->name == __('Fees', IUGU)) {
							$total -= $fee->amount;
						}
					}
				}
			}
		}
		return $total;
	}

	/**
	 * payment fields.
	 *
	 * @return void
	 */
	public function payment_fields()
	{
		if ($description = $this->get_description()) {
			echo wpautop(wptexturize($description));
		} // end if;
		// /**
		//  * Get order total.
		//  */
		$order_total = $this->get_order_total_local(true);
		$template_params = array();
		$registration_required = false;
		if ($this->existe_subscriptions) {
			if (WC_Subscriptions_Cart::cart_contains_subscription()) {
				if (WC() && WC()->checkout()) {
					$registration_required = WC()->checkout()->is_registration_required();
				}
			}
			if (!$registration_required) {
				if (function_exists('wcs_cart_contains_renewal')) {
					$registration_required = wcs_cart_contains_renewal();
				}
			}
		}
		$registration_required = apply_filters('iugu-payment-gateway-registration_required', $registration_required);
		wp_enqueue_script('wc-payment-booklets-form');
		$iugu_payment_booklets_installments = 0;
		$order_id = absint(get_query_var('order-pay'));
		if ($order_id > 0) {
			$iugu_payment_booklets_installments = get_post_meta($order_id, 'iugu_payment_booklets_installments', true);
			if (!$registration_required) {
				if (function_exists('wcs_is_subscription')) {
					$registration_required = wcs_is_subscription($order_id);
				}
			}
		}
		$installments = $this->api->get_max_installments(false);
		if ($installments < $iugu_payment_booklets_installments) {
			$installments = $iugu_payment_booklets_installments;
		}
		if ($installments == 1 && $iugu_payment_booklets_installments == 0) {
			$iugu_payment_booklets_installments = 1;
		}
		$template_params = array(
			'fixed_installments' => $iugu_payment_booklets_installments,
			'order_total' => $order_total,
			'installments' => $installments,
			'smallest_installment' => 5 <= $this->smallest_installment ? $this->smallest_installment : 5,
			'pass_interest' => $this->pass_interest,
			'rates' => $this->get_interest_rate(),
			'registration_required' => $registration_required,
		);
		wc_get_template(
			'payment-booklets/payment-form.php',
			$template_params,
			'woocommerce/iugu/',
			WC_Iugu::get_templates_path()
		);
	} // end payment_fields;

	/**
	 * Process the payment and return the result.
	 *
	 * @param  int $order_id Order ID.
	 * @return array         Redirect.
	 */
	public function process_payment($order_id)
	{
		$order = wc_get_order($order_id);
		$customer_id = $this->api->get_customer_id($order);
		if (isset($_POST['iugu_payment_booklets_installments'])) {
			update_post_meta($order->get_id(), 'iugu_payment_booklets_installments', sanitize_text_field($_POST['iugu_payment_booklets_installments']));
		}
		/**
		 * Processamento do pagamento.
		 */
		$api_return = $this->api->process_payment_payment_booklets($order_id, $customer_id);
		return $api_return;
	}

	/**
	 * Thank You page message.
	 *
	 * @param  int    $order_id Order ID.
	 *
	 * @return string
	 */
	public function thankyou_page($order_id)
	{
		$order = wc_get_order($order_id);
		// WooCommerce 3.0 or later.
		if (is_callable(array($order, 'get_meta'))) {
			$iugu_payment_booklets_installments = $order->get_meta('iugu_payment_booklets_installments');
		} else {
			$iugu_payment_booklets_installments = get_post_meta($order_id, 'iugu_payment_booklets_installments', true);
		}
		if (isset($iugu_payment_booklets_installments)) {
			wc_get_template(
				'payment-booklets/payment-instructions.php',
				array(
					'installments' => $iugu_payment_booklets_installments,
					'secure_url' => $order->get_meta('_iugu_wc_transaction_1_secure_url')
				),
				'woocommerce/iugu/',
				WC_Iugu::get_templates_path()
			);
		}
	}

	/**
	 * Add content to the WC emails.
	 *
	 * @param  object $order         Order object.
	 * @param  bool   $sent_to_admin Send to admin.
	 * @param  bool   $plain_text    Plain text or HTML.
	 *
	 * @return string                Payment instructions.
	 */
	public function email_instructions($order, $sent_to_admin, $plain_text = false)
	{
		// WooCommerce 3.0 or later.
		if (is_callable(array($order, 'get_meta'))) {
			if ($sent_to_admin || !$order->has_status(array('processing', 'on-hold')) || $this->id !== $order->get_payment_method()) {
				return;
			}
			$data = $order->get_meta('_iugu_wc_transaction_data');
		} else {
			if ($sent_to_admin || !$order->has_status(array('processing', 'on-hold')) || $this->id !== $order->get_payment_method()) {
				return;
			}
			$data = get_post_meta($order->get_id(), '_iugu_wc_transaction_data', true);
		}
		if (isset($data['installments'])) {
			if ($plain_text) {
				wc_get_template(
					'payment-booklets/emails/plain-instructions.php',
					array(
						'installments' => $data['installments']
					),
					'woocommerce/iugu/',
					WC_Iugu::get_templates_path()
				);
			} else {
				wc_get_template(
					'payment-booklets/emails/html-instructions.php',
					array(
						'installments' => $data['installments']
					),
					'woocommerce/iugu/',
					WC_Iugu::get_templates_path()
				);
			}
		}
	}

	/**
	 * Notification handler.
	 */
	public function notification_handler()
	{
		$this->api->notification_handler();
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @param string $hook Page slug.
	 * @return void.
	 */
	public function admin_scripts($hook)
	{
		if (
			in_array($hook, array('woocommerce_page_wc-settings', 'woocommerce_page_woocommerce_settings')) &&
			((isset($_GET['section']) && strtolower($this->id) == strtolower($_GET['section'])) || (isset($_GET['section']) && strtolower(get_class($this)) == strtolower($_GET['section'])))
		) {
			$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script('iugu-payment-booklets-admin', plugins_url('assets/js/admin-payment-booklets' . $suffix . '.js', WC_IUGU_PLUGIN_FILE), array('jquery'), WC_Iugu::CLIENT_VERSION, true);
		} // end if;
	} // end admin_scripts.

	public function my_orders_payment_booklets_link($actions, $order)
	{
		if ('iugu-payment-booklets' !== $order->get_payment_method()) {
			return $actions;
		}
		$iugu_payment_booklets_installments = get_post_meta($order->get_id(), 'iugu_payment_booklets_installments', true);
		if (isset($iugu_payment_booklets_installments)) {
			$actions2 = [];
			foreach ($actions as $key => $value) {
				if ($key != 'pay') {
					$actions2[$key] = $value;
				}
			}
			if (isset($actions2['view']) && isset($actions2['view']['name'])) {
				$actions2['view']['name'] = __('View / Invoices', IUGU);
			}
			$actions = $actions2;
		}
		return $actions;
	}
	function woocommerce_order_details_after_order_table($order)
	{
		if ('iugu-payment-booklets' == $order->get_payment_method()) {
			echo '<h2 class="woocommerce-column__title">' . __('Invoices', IUGU) . '</h2>';
			echo '<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">';
			echo '	<tfoot>';
			$iugu_payment_booklets_installments = $order->get_meta('iugu_payment_booklets_installments', true);
			for ($i = 1; $i <= $iugu_payment_booklets_installments; $i++) {
				echo '		<tr>';
				echo '			<td>'.sprintf(__('Invoice %s of %s', IUGU), $i, $iugu_payment_booklets_installments). '</td>';
				$tmp = wcs_format_datetime(wc_string_to_datetime($order->get_meta('_iugu_wc_transaction_' . $i . '_due_date', true)));
				echo '			<td>' . $tmp . '</td>';
				$tmp = wc_price(($order->get_meta('_iugu_wc_transaction_' . $i . '_total_cents', true) / 100), array('currency' => $order->get_currency()));
				echo '			<td>' . $tmp . '</td>';
				$tmp = $order->get_meta('_iugu_wc_transaction_' . $i . '_status', true);
				if ($tmp == 'pending') {
					$tmp = '<a href="' . $order->get_meta('_iugu_wc_transaction_' . $i . '_secure_url', true) . '" target="_blank">' .
						__('Pay', IUGU) .
						'</a>';

				} else if ($tmp == 'paid') {
					$tmp = __('Paid', IUGU);
				}
				echo '			<td>' . $tmp . '</td>';
				echo '		</tr>';
			}
			echo '	</tfoot>';
			echo '</table>';
		}
	}

} // end WC_Iugu_Payment_Booklets_Gateway;
