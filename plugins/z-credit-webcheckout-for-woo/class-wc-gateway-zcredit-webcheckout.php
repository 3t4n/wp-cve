<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 *
 * ZCredit Payment Gateway
 *
 * Provides a ZCredit Payment Gateway.
 *
 */
class WC_Gateway_ZCredit_Checkout extends WC_Payment_Gateway {
    const ZCREDIT_PAYMENT_GATEWAY_URL = 'https://pci.zcredit.co.il/webcheckout/api/WebCheckout/CreateSession';
	
	/*
		Constructor + Populate 
	*/
    public function __construct() {
        $this->id			= 'zcredit_checkout_payment';
        $this->icon 		= plugins_url( '/' , __FILE__ ).'images/payment_icon.png';
        $this->has_fields 	= false;
        $this->method_title = __( 'Z-Credit Checkout Payment', 'woocommerce_zcredit' );
        //$this->method_description = __( 'General Settings', 'woocommerce_zcredit' );
		$this->order_button_text = __( 'Pay With Card', 'woocommerce_zcredit');
		
		$this->supports           = array(
            'products',
            'refunds'
        );
		
		/*
		$this->supports           = array(
            'subscriptions',
            'products',
            'subscription_cancellation',
            'subscription_reactivation',
            'subscription_suspension',
            'subscription_amount_changes',
            'subscription_payment_method_change',
            'subscription_date_changes',
            'default_credit_card_form',
            'refunds',
            'pre-orders'
        );
		*/

        $site_url = explode('?', home_url( '/' ))[0];
        //$base_url = add_query_arg( 'wc-api', 'WC_Gateway_ZCredit_Checkout', $site_url );
		$base_url = $site_url . "wc-api/wc_gateway_zcredit_checkout";

        //$this->success_url = $base_url . "%26amp;target=success" . "%26amp;";
        $this->success_url = $base_url; 		//. "&target=success";
        //$this->error_url   = $base_url . "%26amp;target=error" . "%26amp;"; 
        //$this->cancel_url  = $base_url . "%26amp;target=cancel" . "%26amp;"; 

        $this->init_form_fields();		
        $this->init_settings();			//This is a public wooCommerce function
        //zcredit_payment_gateway_title
        // Define user set variables

        $this->zcredit_payment_gateway_url = self::ZCREDIT_PAYMENT_GATEWAY_URL;

        $this->title = $this->get_option( 'title' . self::get_lang_prefix() );
        $this->description = $this->get_option( 'description' . self::get_lang_prefix() );
        $this->terminal_number = $this->get_option( 'terminal_number' );
        $this->privateKey = $this->get_option( 'privateKey' );
        $this->password = $this->get_option( 'password' );
        $this->language = $this->get_option( 'language' . self::get_lang_prefix() );
        $this->redirect_link = $this->get_option( 'redirect_link' . self::get_lang_prefix() );
        $this->min_payments_number = $this->get_option( 'min_payments_number' ) ? $this->get_option( 'min_payments_number' ) : 1;
        $this->max_payments_number = $this->get_option( 'max_payments_number' ) ? $this->get_option( 'max_payments_number' ) : 1;
        $this->payments_type = $this->get_option( 'payments_type' );
        $this->use_installments_steps = $this->get_option( 'use_installments_steps' );
        $this->PaymentStep_Amt_1 = $this->get_option( 'PaymentStep_Amt_1' );
        $this->PaymentStep_Qtty_1 = $this->get_option( 'PaymentStep_Qtty_1' );
        $this->PaymentStep_Amt_2 = $this->get_option( 'PaymentStep_Amt_2' );
        $this->PaymentStep_Qtty_2 = $this->get_option( 'PaymentStep_Qtty_2' );
        $this->PaymentStep_Amt_3 = $this->get_option( 'PaymentStep_Amt_3' );
        $this->PaymentStep_Qtty_3 = $this->get_option( 'PaymentStep_Qtty_3' );
        $this->payment_authorized = $this->get_option( 'payment_authorized');
        $this->Hide_Amount = $this->get_option( 'Hide_Amount');
        $this->cancel_link = $this->get_option( 'cancel_link' . self::get_lang_prefix() );
        $this->holderid_attributes = $this->get_option( 'holderid_attributes' );
        $this->customerName_attributes = $this->get_option( 'customerName_attributes' );
        $this->customerPhone_attributes = $this->get_option( 'customerPhone_attributes' );
        $this->customerEmail_attributes = $this->get_option( 'customerEmail_attributes' );
        $this->ShowCart = $this->get_option( 'ShowCart' );
        $this->ThemeColor = $this->get_option( 'ThemeColor' );
        $this->BackgroundColor = $this->get_option( 'BackgroundColor' );
        $this->iframe = $this->get_option( 'iframe' );
        $this->iframe_height = $this->get_option( 'iframe_height' ) ? $this->get_option( 'iframe_height' ) : '610';
        $this->iframe_width = $this->get_option( 'iframe_width' ) ? $this->get_option( 'iframe_width' ) : '410';
        $this->create_invoice = $this->get_option( 'create_invoice' );
        $this->Taxes_Enabled = $this->get_option( 'Taxes_Enabled' );
        $this->ApplePay_Support = $this->get_option( 'ApplePay_Support' );
        $this->GooglePay_Support = $this->get_option( 'GooglePay_Support' );
        $this->UseLightMode = $this->get_option( 'UseLightMode' );

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'woocommerce_receipt_zcredit_checkout_payment', array( $this, 'receipt_zcredit_page' ), 10 );
        add_action( 'woocommerce_api_wc_gateway_zcredit_checkout', array( $this, 'check_zcredit_response' ) );
        add_filter( 'woocommerce_gateway_title', array($this, 'zcredit_gateway_title'), 10, 2 );
        add_filter( 'woocommerce_gateway_description', array($this, 'zcredit_gateway_description'), 10, 2 );
		
		if ($this->iframe) {
			//add_action('woocommerce_before_thankyou', array( $this, 'zcredit_exit_iframe_OK' ), 4);
			add_action('woocommerce_thankyou', array( $this, 'zcredit_exit_iframe_OK' ), 4);
			add_action('woocommerce_before_checkout_form', array( $this, 'zcredit_exit_iframe_Cancel' ), 4);
		}
    }
	
	function redirect_to_zcredit_on_iframe( $order_id ) { 
		?>
		<script type="text/javascript">
			if (window != top) {
				console.log('zcredit_exit_iframe2');
				window.top.location.href = window.location.href;

			}
		</script>
		<?php
	}
	
	public function zcredit_exit_iframe_OK()
	{
		$redirect = "'" . WC()->session->get('zcredit_iframe_redirect_url') . "'";
		if( $redirect == '' ) {
			$redirect = 'location.href';
		}
		self::zcredit_exit_iframe($redirect);
	}
	
	public function zcredit_exit_iframe_Cancel()
	{
		$redirect = "'" . WC()->session->get('zcredit_iframe_cancel_url') . "'";
		if( $redirect == '' ) {
			$redirect = 'location.href';
		}
		self::zcredit_exit_iframe($redirect);
	}	
	
	public function zcredit_exit_iframe( $redirect )
	{
		echo "\n<script type=\"text/javascript\">";
		echo "\n<!--";
		//echo "\nif (window != top) {top.location.href = location.href;";
		echo "\nif (window != top) {top.location.href = " . $redirect . ";";
		echo "\nwindow.stop();";
		echo "\nif ($.browser.msie) {document.execCommand('Stop');}}";
		echo "\n-->";
		echo "\n</script>\n\n";
	}

    public function init_form_fields() {
        $this->form_fields = array(
            'general_settings' => array(
                'title'	=> __( 'General Settings', 'woocommerce_zcredit' ),
                'type'	=> 'title'
            ),
            'enabled' => array(
                'title'   => __( 'Enable/Disable', 'woocommerce_zcredit' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable Z-Credit Payment', 'woocommerce_zcredit' ),
                'default' => 'no'
            ),
            'title' . self::get_lang_prefix() => array(
                'title'       => __( 'Payment Method Title', 'woocommerce_zcredit' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce_zcredit' ),
                'default'     => __( 'Z-Credit WebCheckout', 'woocommerce_zcredit' )
            ),
            'description' . self::get_lang_prefix() => array(
                'title'       => __( 'Payment Method Description', 'woocommerce_zcredit' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce_zcredit' ),
                'default'     => __( 'Pay with Z-Credit WebCheckout - secured payment page.', 'woocommerce_zcredit' ),
                'class'       => 'zitem-textarea'
            ),
            'privateKey' => array(
                'title'             => __( 'privateKey', 'woocommerce_zcredit' ),
                'type'              => 'text',
                'description'     =>  __( 'Your Z-Credit Private Key.', 'woocommerce_zcredit' ),
                'custom_attributes' => array(
                    'data-error' => __( 'This field is required to be filled.', 'woocommerce_zcredit' )
                ),
                'class'             => 'required-field zcredit-field'
            ),	
            'terminal_number' => array(
                'title'             => __( 'Terminal Number', 'woocommerce_zcredit' ),
                'type'              => 'text',
                'description'     =>  __( 'Your Z-Credit terminal number.', 'woocommerce_zcredit' )
            ),
            'password' => array(
                'title'             => __( 'Password', 'woocommerce_zcredit' ),
                'type'              => 'text',
                'description'     =>  __( 'Your Z-Credit terminal password.', 'woocommerce_zcredit' )
            ),			
            'language' . self::get_lang_prefix() => array(
                'title'	  => __( 'Language', 'woocommerce_zcredit' ),
                'type'	  => 'select',
                'label'   => '',
                'options' => array(
                    0       => __( 'Automatic Language', 'woocommerce_zcredit' ),
                    'He' => __( 'Hebrew', 'woocommerce_zcredit' ),
                    'En' => __( 'English', 'woocommerce_zcredit' )
                ),
                'default' => 'He'
            ),
            'redirect_link' . self::get_lang_prefix() => array(
                'title'       => __( 'Success Url', 'woocommerce_zcredit' ),
                'type'        => 'text',
                'description' =>  __( 'URL which the customer is redirected to, after a successfull transaction. (http://)', 'woocommerce_zcredit' )
            ),
            'cancel_link' . self::get_lang_prefix() => array(
                'title' => __( 'Cancel URL', 'woocommerce_zcredit' ),
                'type'  => 'text',
                'description' =>  __( 'URL which the customer is redirected to, When he presess the back link. (http://)', 'woocommerce_zcredit' )
            ),
            'create_invoice' => array(
                'title'   => __( 'Create invoice', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'false' => __( 'No', 'woocommerce_zcredit' ),
                    'true' => __( 'Yes', 'woocommerce_zcredit' )
                ),
				'description' =>  __( 'Use this option only if you purchased our invoicing module.', 'woocommerce_zcredit' ),
                'default' => 'false'
            ),
			'Taxes_Enabled' => array(
                'title'   => __( 'Taxes are enabled', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'false' => __( 'No', 'woocommerce_zcredit' ),
                    'true' => __( 'Yes', 'woocommerce_zcredit' )
                ),
				'description' =>  __( 'Did you enable taxes module in WooCommerce.', 'woocommerce_zcredit' ),
                'default' => 'false'
            ),
			'payment_authorized' => array(
                'title'   => __( 'Transaction Mode', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'regular' => __( 'Charge only', 'woocommerce_zcredit' ),
                    'authorize' => __( 'Authorize Charge (J5)', 'woocommerce_zcredit' )
                ),
                'default' => 'regular',
            ),
            'Hide_Amount' => array(
                'title'   => __( 'Hide Amount', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'false' => __( 'No', 'woocommerce_zcredit' ),
                    'true' => __( 'Yes', 'woocommerce_zcredit' )
                ),
                'description' =>  __( 'Hide amount on payment button when using the J5 (authorize) method', 'woocommerce_zcredit' ),
				'default' => 'false'
            ),			
            'payments_title' => array(
                'title'	=> __( 'Installments Settings', 'woocommerce_zcredit' ),
                'type'	=> 'title'
            ),
            'payments_type' => array(
                'title'   => __( 'Installments Type', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'none' => __( 'No Installments', 'woocommerce_zcredit' ),
                    'regular' => __( 'Regular Installments', 'woocommerce_zcredit' ),
                    'credit' => __( 'Credit Installments', 'woocommerce_zcredit' )
                ),
                'default' => 'none'
            ),
			'min_payments_number' => array(
                'title'       => __( 'Minimum Payments Number', 'woocommerce_zcredit' ),
                'type'        => 'number',
                'default'     => 1,
                //'description' =>  __( 'Number of payments for this transaction. Caution: if left empty the customer will be able to choose the number of payments himself.', 'woocommerce_zcredit' ),
                'custom_attributes' => array(
                    'step'       => 1,
                    //'min'        => 1,
                    //'max'        => 99,
                    'data-error' => __( 'You must select a number between 1 and 99.', 'woocommerce_zcredit' )
                ),
                'class'       => 'required-field zcredit-field payments-field'
            ),
            'max_payments_number' => array(
                'title'       => __( 'Maximum Payments Number', 'woocommerce_zcredit' ),
                'type'        => 'number',
                'default'     => 1,
                //'description' =>  __( 'Number of payments for this transaction. Caution: if left empty the customer will be able to choose the number of payments himself.', 'woocommerce_zcredit' ),
                'custom_attributes' => array(
                    'step'       => 1,
                    //'min'        => 1,
                    //'max'        => 99,
                    'data-error' => __( 'You must select a number between 1 and 99.', 'woocommerce_zcredit' )
                ),
                'class'       => 'required-field zcredit-field payments-field'
            ),
            'use_installments_steps' => array(
                'title'   => __( 'Installments Steps', 'woocommerce_zcredit' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable Installments Steps', 'woocommerce_zcredit' ),
                'description' =>  __( 'Use this setting to setup maximum installements per amount. For instance you can allow 3 installemts for orders up to 100 NIS', 'woocommerce_zcredit' ),
				//'desc_tip' => true,
				'class'       => 'zcredit-field payments-field',
				'default' => 'no'
            ),
            'payments_steps_title_1' => array(
                'title'	=> __( 'Payments Step#1 Setup', 'woocommerce_zcredit' ),
                //'description' =>  __( 'Use this setting to setup maximum installements per amount. For instance if you want to allow 3 installemts for orders up to 100 NIS, put 100 on the Step Amount field, and 3 on the Step Qtty field', 'woocommerce_zcredit' ),
                'type'	=> 'title',
				'class'       => 'zcredit-field payments-field payments-title steps-fields'
            ),
            'PaymentStep_Amt_1' => array(
                'title'       => __( '#1 Step Amount', 'woocommerce_zcredit' ),
                'type'        => 'number',
                'default'     => '',
                'description' =>  __( 'Insert the amount installemts limit', 'woocommerce_zcredit' ),
                //'desc_tip' => true,
                'class'       => 'zcredit-field payments-field steps-fields'
            ),
            'PaymentStep_Qtty_1' => array(
                'title'       => __( '#1 Step Qtty', 'woocommerce_zcredit' ),
                'type'        => 'number',
                'default'     => '',
                'description' =>  __( 'Insert the installemts limit Quantity', 'woocommerce_zcredit' ),
                //'desc_tip' => true,
                'class'       => 'zcredit-field payments-field steps-fields'
            ),
            'payments_steps_title_2' => array(
                'title'	=> __( 'Payments Step#2 Setup', 'woocommerce_zcredit' ),
                //'description' =>  __( 'Use this setting to setup maximum installements per amount. For instance if you want to allow 3 installemts for orders up to 100 NIS, put 100 on the Step Amount field, and 3 on the Step Qtty field', 'woocommerce_zcredit' ),
                'type'	=> 'title',
				'class'       => 'zcredit-field payments-field payments-title steps-fields'
            ),
            'PaymentStep_Amt_2' => array(
                'title'       => __( '#2 Step Amount', 'woocommerce_zcredit' ),
                'type'        => 'number',
                'default'     => '',
                'description' =>  __( 'Insert the amount installemts limit', 'woocommerce_zcredit' ),
                //'desc_tip' => true,
                'class'       => 'zcredit-field payments-field steps-fields'
            ),
            'PaymentStep_Qtty_2' => array(
                'title'       => __( '#2 Step Qtty', 'woocommerce_zcredit' ),
                'type'        => 'number',
                'default'     => '',
                'description' =>  __( 'Insert the installemts limit Quantity', 'woocommerce_zcredit' ),
                //'desc_tip' => true,
                'class'       => 'zcredit-field payments-field steps-fields'
            ),
            'payments_steps_title_3' => array(
                'title'	=> __( 'Payments Step#3 Setup', 'woocommerce_zcredit' ),
                //'description' =>  __( 'Use this setting to setup maximum installements per amount. For instance if you want to allow 3 installemts for orders up to 100 NIS, put 100 on the Step Amount field, and 3 on the Step Qtty field', 'woocommerce_zcredit' ),
                'type'	=> 'title',
				'class'       => 'zcredit-field payments-field payments-title steps-fields'
            ),
            'PaymentStep_Amt_3' => array(
                'title'       => __( '#3 Step Amount', 'woocommerce_zcredit' ),
                'type'        => 'number',
                'default'     => '',
                'description' =>  __( 'Insert the amount installemts limit', 'woocommerce_zcredit' ),
                //'desc_tip' => true,
                'class'       => 'zcredit-field payments-field steps-fields'
            ),
            'PaymentStep_Qtty_3' => array(
                'title'       => __( '#3 Step Qtty', 'woocommerce_zcredit' ),
                'type'        => 'number',
                'default'     => '',
                'description' =>  __( 'Insert the installemts limit Quantity', 'woocommerce_zcredit' ),
                //'desc_tip' => true,
                'class'       => 'zcredit-field payments-field steps-fields'
            ),
            'customer_settings' => array(
                'title'	=> __( 'Customer Data', 'woocommerce_zcredit' ),
                'type'	=> 'title'
            ),
			'holderid_attributes' => array(
                'title'   => __( 'HolderID Attributes', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'none' => __( 'Hide', 'woocommerce_zcredit' ),
                    'optional' => __( 'Optional', 'woocommerce_zcredit' ),
                    'required' => __( 'Required', 'woocommerce_zcredit' )
                ),
                'default'     => 'optional',
            ),
			'customerName_attributes' => array(
                'title'   => __( 'Customer Name Attributes', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'none' => __( 'Hide', 'woocommerce_zcredit' ),
                    'optional' => __( 'Optional', 'woocommerce_zcredit' ),
                    'required' => __( 'Required', 'woocommerce_zcredit' )
                ),
                'default'     => 'optional',
            ),
			'customerPhone_attributes' => array(
                'title'   => __( 'Phone Number Attributes', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'none' => __( 'Hide', 'woocommerce_zcredit' ),
                    'optional' => __( 'Optional', 'woocommerce_zcredit' ),
                    'required' => __( 'Required', 'woocommerce_zcredit' )
                ),
                'default'     => 'optional',
            ),
			'customerEmail_attributes' => array(
                'title'   => __( 'Email Attributes', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'none' => __( 'Hide', 'woocommerce_zcredit' ),
                    'optional' => __( 'Optional', 'woocommerce_zcredit' ),
                    'required' => __( 'Required', 'woocommerce_zcredit' )
                ),
                'default'     => 'optional',
            ),
            'page_settings' => array(
                'title'	=> __( 'Page Settings', 'woocommerce_zcredit' ),
                'type'	=> 'title'
            ),
			'ApplePay_Support' => array(
                'title'   => __( 'ApplePay Supported', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'false' => __( 'No', 'woocommerce_zcredit' ),
                    'true' => __( 'Yes', 'woocommerce_zcredit' )
                ),
				'description' =>  __( 'Use this option if you wish to support ApplePay in your page.', 'woocommerce_zcredit' ),
                'default' => 'false'
            ),
			'GooglePay_Support' => array(
                'title'   => __( 'GooglePay Supported', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'false' => __( 'No', 'woocommerce_zcredit' ),
                    'true' => __( 'Yes', 'woocommerce_zcredit' )
                ),
				'description' =>  __( 'Use this option if you wish to support GooglePay in your page.', 'woocommerce_zcredit' ),
                'default' => 'false'
            ),
            'ShowCart' => array(
                'title'   => __( 'Show Cart On Checkout', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
					'auto'       => __( 'Automatic', 'woocommerce_zcredit' ),
                    'false' => __( 'Never', 'woocommerce_zcredit' ),
                    'true' => __( 'Always', 'woocommerce_zcredit' )
                ),
                'default' => 'auto',
				'description' =>  __( 'Automatic means that wooCommerce will show your page in the best way', 'woocommerce_zcredit' )
            ),
            'ThemeColor' => array(
                'title' => __( 'Theme Color', 'woocommerce_zcredit' ),
                'type'  => 'text',
                'description' =>  __( 'Use a Hex value to set the theme color (leave empty for default color)', 'woocommerce_zcredit' )
            ),            
			'BackgroundColor' => array(
                'title' => __( 'Background Color', 'woocommerce_zcredit' ),
                'type'  => 'text',
                'description' =>  __( 'Use a Hex value to set the background color of the payment frame(leave empty for default color)', 'woocommerce_zcredit' )
            ),
            'iframe' => array(
                'title'   => __( 'Is IFrame', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    0 => __( 'Full Page', 'woocommerce_zcredit' ),
                    1 => __( 'IFrame', 'woocommerce_zcredit' )
                ),
                'default' => 0
            ),
            'iframe_width' => array(
                'title'   => __( 'iFrame Width', 'woocommerce_zcredit' ),
                'type'    => 'number',
                'default' => 410,
                'custom_attributes' => array(
                    'step' => 1,
                    'min'  => 0
                ),
                'description' =>  __( 'Please enter a number only.', 'woocommerce_zcredit' )
            ),
            'iframe_height' => array(
                'title'   => __( 'iFrame Height', 'woocommerce_zcredit' ),
                'type'    => 'number',
                'default' => 610,
                'custom_attributes' => array(
                    'step' => 1,
                    'min'  => 0
                ),
                'description' =>  __( 'Please enter a number only.', 'woocommerce_zcredit' )
            ),
			'UseLightMode' => array(
                'title'   => __( 'UseLightMode', 'woocommerce_zcredit' ),
                'type'    => 'select',
                'options' => array(
                    'false' => __( 'No', 'woocommerce_zcredit' ),
                    'true' => __( 'Yes', 'woocommerce_zcredit' )
                ),
				'description' =>  __( 'Use this option if you wish to hide the payment caption.', 'woocommerce_zcredit' ),
                'default' => 'false'
            )			
        );
    }
	
	private function get_browser_name($user_agent){
		$t = strtolower($user_agent);
		$t = " " . $t;
		if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return 'Opera'            ;   
		elseif (strpos($t, 'edge'      )                           ) return 'Edge'             ;   
		elseif (strpos($t, 'chrome'    )                           ) return 'Chrome'           ;   
		elseif (strpos($t, 'safari'    )                           ) return 'Safari'           ;   
		elseif (strpos($t, 'firefox'   )                           ) return 'Firefox'          ;   
		elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return 'Internet Explorer';
		else return 'Unknown';
	}	
	
	private function is_IOS_device(){
		if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad')) 
		{ 
			return true;
		}		
 		else 
		{ 
			return false;
		}
	}
	
    public function process_payment( $order_id ) {
        global $woocommerce;	
		
		if (false)
		{
			wc_add_notice($this->is_IOS_device() , 'notice' );
			return;			
		}		
		
		if ($this->ApplePay_Support  == 'true')
		{
			if ($this->get_browser_name($_SERVER['HTTP_USER_AGENT']) == 'Safari')
			{
				$this->iframe = false;
			}	
		}
		
	
		$currency = get_woocommerce_currency();
		if ($currency != 'USD' && $currency != 'ILS') {
			$currency = 'ILS';
		}

        $order = new WC_Order( $order_id );
		
        //$arr_success_url = explode('?', $this->success_url);
        //$notify_link = $arr_success_url[0] . ( $arr_success_url[1] ? '?' . urlencode($arr_success_url[1]) : '' );
		$notify_link = $this->success_url;
        //$notify_link = esc_url($this->success_url);

		/* START - Parse cart items */
        //$qty = 0;
        $order_items = $order->get_items();
        $products = array();
		$items_Total = 0;
        foreach( $order_items as $order_item ) {
            //$qty += $order_item['qty'];
			
			$itemQtty = $order_item->get_quantity();
			$itemLineSubTotal = $order_item->get_subtotal();
			$itemLineTaxSubTotal = $order_item->get_subtotal_tax();
			
            $line_total = ($itemLineTaxSubTotal > 0)?number_format( ($itemLineSubTotal + $itemLineTaxSubTotal)/$itemQtty, 2, '.', ''): number_format($itemLineSubTotal/$itemQtty, 2, '.', '');
			$items_Total += ($line_total*$itemQtty);
			
			//wc_add_notice("Item: " . json_encode($order_item['name']) . " , Total: " . json_encode(($order_item['line_total'])) , 'notice' );
			
            if( $order_item['variation_id'] ) {
                $temp_product = new WC_Product_Variation($order_item['variation_id']);
            }
            else {
                $temp_product = new WC_Product($order_item['product_id']);
            }
			
			if ($this->Taxes_Enabled == 'true')
			{
				$IsTaxFree = ($order_item['line_tax'] > 0)? 'false' : 'true';
			}
			else
			{
				$IsTaxFree = 'false';
			}

            $products[] = array(
                "Name" => $order_item['name'],
                "Image" => ($this->isSecured()? (get_the_post_thumbnail_url($temp_product->get_id()) ? get_the_post_thumbnail_url($temp_product->get_id()) : wc_placeholder_img_src()) : "") ,
                "Description" => $temp_product->get_sku() ? $temp_product->get_sku() : '',
                "Quantity" => $itemQtty,
                "Amount" => str_replace(',','',$line_total),
                "Currency" => $currency ,
				"IsTaxFree" => $IsTaxFree
            );
        }

		//Coupons handeling for WooCommerce Smart coupons
        $coupon_items = $order->get_coupons();
        foreach( $coupon_items as $coupon_item ) {
			$itemQtty = $coupon_item->get_quantity();
			$itemLineSubTotal = $coupon_item->get_discount();
			$itemLineTaxSubTotal = $coupon_item->get_discount_tax();
			
            $line_total = ($itemLineTaxSubTotal > 0)?number_format( ($itemLineSubTotal + $itemLineTaxSubTotal)/$itemQtty, 2, '.', ''): number_format($itemLineSubTotal/$itemQtty, 2, '.', '');
			$itemQtty = -1*$itemQtty;
			$items_Total += ($line_total*$itemQtty);
		
						
            if( $coupon_item['variation_id'] ) {
                $temp_product = new WC_Product_Variation($coupon_item['variation_id']);
            }
            else {
                $temp_product = new WC_Product($coupon_item['product_id']);
            }
			
			if ($this->Taxes_Enabled == 'true')
			{
				$IsTaxFree = ($coupon_item['line_tax'] > 0)? 'false' : 'true';
			}
			else
			{
				$IsTaxFree = 'false';
			}

            $products[] = array(
                "Name" => $coupon_item['name'],
                "Image" => ($this->isSecured()? (get_the_post_thumbnail_url($temp_product->get_id()) ? get_the_post_thumbnail_url($temp_product->get_id()) : wc_placeholder_img_src()) : "") ,
                "Description" => $temp_product->get_sku() ? $temp_product->get_sku() : '',
                "Quantity" => $itemQtty,
                "Amount" => str_replace(',','',$line_total),
                "Currency" => $currency ,
				"IsTaxFree" => $IsTaxFree
            );
        }

				
        $shipping = $order->get_shipping_method();
        if( $shipping) {
			if ($order->get_shipping_total() > 0)
			{
				$shippingTotal = number_format($order->get_shipping_total() + $order->get_shipping_tax(), 2, '.', '');
				//wc_add_notice("shipping: " . json_encode($shippingTotal) , 'notice' );
				$products[] = array(
					"Name"       => $shipping,
					"Image" => ($this->isSecured()? wc_placeholder_img_src() : ""),
					"Description"         => '',
					"Quantity"     => 1,
					"Amount"  => str_replace(',','',$shippingTotal),
					"Currency" => $currency ,
					"IsTaxFree" => 'false'
				);
				
				$items_Total += $shippingTotal;
			}
        }
		
/* 		//discount is not needed, because the discount is already appear in the line_item
		$discountItems = array();
		$discounts = $order->get_discount_total();
        if( $discounts ) {
			$discountTotal = number_format($order->get_discount_total() + $order->get_discount_tax(), 2, '.', '');
			//wc_add_notice("discount: " . json_encode($discountTotal) , 'notice' );
            $discountItems[] = array(
                "Name"       => 'הנחה',	//TODO - get discount real name
                "Image" => $this->isSecured()? wc_placeholder_img_src() : "",
                "Description"         => '',
                "Quantity"     => -1,
                "Amount"  => str_replace(',','',$discountTotal),
				"Currency" => $currency
			);
			
			//$items_Total -= $discountTotal;
        } */	
	

        //$products_json = json_encode($products);
        //$payment_sum = $order->get_total();
		$payment_sum = number_format($order->get_total(), 2, '.', '');
		$items_Total = number_format($items_Total, 2, '.', '');
		
		
		//Adjust the cart to the payment sum
		if ($payment_sum < $items_Total){
			
			$disc_price = number_format($items_Total - $payment_sum , 2, '.', '');
			
			$products[] = array(
					"Name"  => __('Discount Row', 'woocommerce_zcredit'),
					"Image" => ($this->isSecured()? wc_placeholder_img_src() : ""),
					"Description"  => '',
					"Quantity"     => -1,
					"Amount"  => str_replace(',','',$disc_price),
					"Currency" => $currency ,
					"IsTaxFree" => 'false'
				);
		}

		//That means there is a missing item on cart , should raise an error
		if ($payment_sum > $items_Total){		
			//wc_add_notice( __('Total amount does not match items amount: ', 'woocommerce_zcredit') . '<br>Items: ' . $items_Total . " <br> Order: " . $payment_sum , 'error' );
			//wc_add_notice( 'Products<br>' . json_encode($products) , 'notice' );
			//return;
			
			$products = array();
			$products[] = array(
					"Name"  => __('Generic Sale Item', 'woocommerce_zcredit'),
					"Image" => ($this->isSecured()? wc_placeholder_img_src() : ""),
					"Description"  => '',
					"Quantity"     => 1,
					"Amount"  => str_replace(',','',$payment_sum),
					"Currency" => $currency ,
					"IsTaxFree" => 'false'
				);
		}		
		
		/* END -  Parse cart items */
		
        $customer_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        $customer_phone = $order->get_billing_phone();
        $customer_email = $order->get_billing_email();

        if( $this->redirect_link ) {
            $redirect_url = $this->redirect_link;
			$redirect_url = add_query_arg( 'key', $order->get_order_key(), $redirect_url );
        }
        else {
            $redirect_url = $order->get_checkout_order_received_url();
        }

        $cancel_url = '';
        if( $this->cancel_link ) {
            $cancel_url = $this->cancel_link;
        }
		else {
			$cancel_url = wc_get_checkout_url();
		}

		$redirect_url = htmlspecialchars_decode($redirect_url);
		$cancel_url = htmlspecialchars_decode($cancel_url);
		
        // if iframe, save the final redirect url and use redirect.php.
        if ( $this->iframe ){
            WC()->session->set( 'zcredit_iframe_redirect_url', $redirect_url );
            $redirect_url = htmlspecialchars_decode($order->get_checkout_order_received_url());

            if( $this->cancel_link ) {
                WC()->session->set( 'zcredit_iframe_cancel_url', $cancel_url );
				$cancel_url = htmlspecialchars_decode(wc_get_checkout_url());
            }
        }
		
        if( $this->language ) {
            $lang = $this->language;
        }
        else{
            $lang = 'En';
			$my_current_lang = apply_filters( 'wpml_current_language', NULL );
            if( ( $my_current_lang && $my_current_lang == 'he' ) || get_locale() == 'he_IL' ) {
                $lang = 'He';
            }
        }
		

		/****************************************************** 
			START Checkout WEBAPI New Data 
		******************************************************/		
		$Installments = array(
			'Type' => $this->payments_type,
			'MinQuantity' => ($this->payments_type == 'none')? 1 : $this->min_payments_number,
			'MaxQuantity' => ($this->payments_type == 'none')? 1 : $this->GetMaxInstallments($order->get_total())
		);
				
		
		if (false)
		{
			wc_add_notice( json_encode($Installments) , 'notice' );
			return;			
		}
		
		$Customer = array(
			'Email' => $customer_email,
			'Name' => $customer_name,
			'PhoneNumber' => $customer_phone,
			'Attributes' => array(
				'HolderId' => $this->holderid_attributes,
				'Name' => $this->customerName_attributes,
				'PhoneNumber' => $this->customerPhone_attributes,
				'Email' => $this->customerEmail_attributes
			)
		);
		
		$showcart = $this->ShowCart;
		if ($showcart == "auto"){
			$showcart = ($this->iframe)? 'false' : 'true';
		}
		
		If ($this->payment_authorized == 'authorize'){
			$this->create_invoice = false;
		}
		
		
		$CheckoutData = array(
			'Key' => $this->privateKey, 
			'Local'   => $lang, 
			'UniqueId'   => $order_id, 
			'SuccessUrl'   => $redirect_url, 
			'CancelUrl'   => $cancel_url, 
			'CallbackUrl'   => $notify_link, 
			'PaymentType'   => $this->payment_authorized, 
			'CreateInvoice'   => $this->create_invoice, 
			'ShowCart'   => $showcart, 
			'ThemeColor' => $this->ThemeColor,
			'BackgroundColor' => $this->BackgroundColor,
			'AdditionalText' => 'WC Order ID ' . $order_id,
			'ApplePayButtonEnabled' => $this->ApplePay_Support, 
			'GooglePayButtonEnabled' => $this->GooglePay_Support, 
			'UseLightMode' => $this->UseLightMode, 
			'ShowTotalSumInPayButton' => ($this->Hide_Amount == 'true')? 'false' : 'true', 
			'Installments' => $Installments,
			'Customer' => $Customer,
			'CartItems' => $products
		);

		if (false)
		{
			wc_add_notice( json_encode($CheckoutData) , 'notice' );
			return;			
		}
		
		/*******************************
		//WP HTTP API CALL
		********************************/
		$args = array(
			'timeout'     => 30,
			'headers' => array(
				'Content-Type' => 'application/json; charset=utf-8'
			),
			'body' => json_encode($CheckoutData)
		);
		
		$full_response = wp_remote_post($this->zcredit_payment_gateway_url, $args );		
		
		if (false)
		{
			wc_add_notice( json_encode($full_response) , 'error' );
			return;			
		}		

		// Check if any error occurred
		if (wp_remote_retrieve_response_code($full_response) >= 400) {
		  wc_add_notice('Error Posting JSON: ' . wp_remote_retrieve_response_code($full_response) . '(' . wp_remote_retrieve_response_message($full_response) . ')', 'error' );
		  return;
		}
				
		$response = wp_remote_retrieve_body($full_response);
		$response = json_decode($response, true);	
		$response_data = $response['Data'];
		
	
		if( $response_data['HasError'] ) {
			wc_add_notice( __('Payment error:', 'woocommerce_zcredit') . $response_data['ReturnMessage'] . " (" . $response_data['ReturnCode'] . ")", 'error' );
			return;
		}
		elseif (!filter_var($response_data['SessionUrl'], FILTER_VALIDATE_URL)) {
			wc_add_notice( __('Payment error:', 'woocommerce_zcredit') . "</br>URL is invalid: " . $response_data['SessionUrl'], 'error' );
			return;
		}
		else {		
			$payment_url = $response_data['SessionUrl'];
			$SessionID = $response_data['SessionId'];
			WC()->session->set('zcredit_payment_url', $payment_url);
			update_post_meta( $order_id, 'order_guid', $SessionID );
			update_post_meta( $order_id, 'session_url', $payment_url );

			$this->reset_payment_session();
			if( $this->iframe ) {
				$payment_url = $order->get_checkout_payment_url(true);		//Generates a URL for payment that contains the iframe
			}
			
			return array(
				'result'   => 'success',
				'redirect' => $payment_url //$checkout SessionUrl
			);
		}
		
		return;	//Fallback if needed - just in case
		
    }
	
	private function GetMaxInstallments($order_total)
	{
		$order_total = number_format($order_total, 2, '.', '');		
		$maxInstallments = $this->max_payments_number;
		
		if ($this->use_installments_steps == 'yes') {
			$stepAmount1 = number_format(floatval($this->PaymentStep_Amt_1), 2, '.', '');
			$stepAmount2 = number_format(floatval($this->PaymentStep_Amt_2), 2, '.', '');
			$stepAmount3 = number_format(floatval($this->PaymentStep_Amt_3), 2, '.', '');
			$maxAmount = max($stepAmount1,$stepAmount2,$stepAmount3);
			
			if ($order_total <= $stepAmount1 && $stepAmount1 <= $maxAmount)
			{
				$maxAmount = $stepAmount1;
				$maxInstallments = number_format((int)$this->PaymentStep_Qtty_1);
			}
			if ($order_total <= $stepAmount2 && $stepAmount2 <= $maxAmount)
			{
				$maxAmount = $stepAmount2;
				$maxInstallments = number_format((int)$this->PaymentStep_Qtty_2);
			}
			if ($order_total <= $stepAmount3 && $stepAmount3 <= $maxAmount)
			{
				$maxAmount = $stepAmount3;
				$maxInstallments = number_format((int)$this->PaymentStep_Qtty_3);
			}

		}
		
		return min($maxInstallments,$this->max_payments_number);

	}
	
	public function process_refund( $order_id, $amount = null, $reason = '' ) {		
	
		$order = new WC_Order( $order_id );
		
		if( $order && $order && $order->get_status() != 'processing' && $order->get_status() != 'completed' ) {
			return new WP_Error( 'error', __( 'Cannot refund an order that is not \'Processing\' or \'Completed\'', 'woocommerce_zcredit' ) );
		}
		
		if(is_null($amount) || $amount == 0 ) {
			return new WP_Error( 'error', __( 'Cannot refund a zero or empty amount', 'woocommerce_zcredit' ) );
		}
				
		$order_sum = $amount;
		$zc_response = self::get_zc_response( $order_id );
		$zc_terminal = $this->terminal_number;
		$zc_password = $this->password;
		
					
		if( $zc_terminal == "" || $zc_password == "" ) {
			return new WP_Error( 'error', __('Terminal Number or password values are missing in settings.', 'woocommerce_zcredit') );
		}
		
		$order_currency = $order->get_order_currency();
		$list_currency = array( 'USD' => 2, 'ILS' => 1 );
		$currency = $list_currency[$order_currency] ? $list_currency[$order_currency] : 1;
		$data = array(
			'TerminalNumber'                 => $zc_terminal,
			'Password'                       => $zc_password,
			'CurrencyType'                   => $currency,
			'CardNumber'                     => $zc_response['Token'],
			'TransactionSum'                 => $order_sum,
			'TransactionType'                 => '53',
			'ApplicationType'				 => 5
		);
		if( $zc_response['CustomerName'] ) $data['CustomerName'] = $zc_response['CustomerName'];
		if( $zc_response['CustomerPhone'] ) $data['PhoneNumber'] = $zc_response['CustomerPhone'];
		if( $zc_response['CustomerEmail'] ) $data['CustomerEmail'] = $zc_response['CustomerEmail'];
		
		
		//Create invoice
		if ($this->create_invoice == 'true')
		{
			$products[] = array(
					"ItemDescription" => ($reason == '' ? 'זיכוי כללי' : $reason),
					"ItemQuantity" => -1,
					"ItemPrice" => str_replace(',','',$order_sum)
			);
			
			//Create main array
			$invoiceData = array(
				'Address'                 		 => '',
				'City'                       	 => '',
				'EmailDocumentToReceipient'      => ($zc_response['CustomerEmail'] ? 'true' : 'false'),
				'FaxNum'                     	 => '',
				'PhoneNum'                  	 => $zc_response['CustomerPhone'],
				'ReceipientEmail' 				 => $zc_response['CustomerEmail'],
				'RecepientCompanyID'             => $zc_response['HolderId'],
				'RecepientName'             	 => $zc_response['CustomerName'],
				'ReturnDocumentInResponse'       => 'false',
				'TaxRate'             			 => '-1',
				'ZipCode'             			 => '',
				'Type'             				 => '0',
				'Items' 						 => $products
			);
			
			$data['ZCreditInvoiceReceipt'] = $invoiceData;
		}

	
		if (false){
			return new WP_Error( 'error', json_encode($data) );
		}
		
		
		/*******************************
		//WP HTTP API CALL
		********************************/
		$args = array(
			'headers' => array(
				'Content-Type' => 'application/json; charset=utf-8'
			),
			'body' => json_encode($data)
		);
		
		$full_response = wp_remote_post("https://pci.zcredit.co.il/ZCreditWS/api/Transaction/CommitFullTransaction", $args );		
		$response = wp_remote_retrieve_body($full_response);		
		
		$response = json_decode($response, true);
		if( $response['HasError'] ) {
			return new WP_Error( 'error', $response['ReturnMessage'] . '(#' . $response['ReturnCode'] . ')' );
		}

		$order->add_order_note( __( 'Z-Credit Payment Refund completed for the amount of ', 'woocommerce_zcredit' ) . $order_sum );		
		return true;
	}
	
	private static function get_zc_response( $order_id ) {
        $json = get_post_meta( $order_id, 'zc_response', true );
        $json = $json ? unserialize(base64_decode($json)) : "";
		
		// Converts it into a PHP object
		$zc_response = json_decode($json, true);		
        return $zc_response;
    }

	
	private function isSecured() {
	  return
		(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		|| $_SERVER['SERVER_PORT'] == 443;
	}

	
	
    public function reset_payment_session(){
        WC()->session->set('zcredit_iframe_displayed', false);
    }

    public function receipt_zcredit_page( $order_id ){

        WC()->session->set('zcredit_iframe_displayed', true);

        $order = new WC_Order( $order_id );
        $zcredit_payment_url = WC()->session->get('zcredit_payment_url');
		
		if( !$zcredit_payment_url ) 
		{
			$zcredit_payment_url = get_post_meta($order_id, 'session_url', true );
		}
		
        if( $zcredit_payment_url ) 
		{
            $width = $this->iframe_width ? $this->iframe_width : '100%';
            $height = $this->iframe_height? $this->iframe_height : '100%' ?>
            <div class="checkout-iframe-<?php echo $order_id; ?>">
                 <iframe id="zcredit-iframe-<?php echo $order_id; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="<?php echo $zcredit_payment_url; ?>" allowpaymentrequest scrolling="yes" frameborder="no"></iframe>
            </div>
        <?php }
		else {			
				?>
            <div class="checkout-noiframe-<?php echo $order_id; ?>">
                 <p style="color:red;font-size:24px;text-align:center;font-weight:bold"><?php echo __('Payment error_session:', 'woocommerce_zcredit'); ?></p>
            </div>
        <?php			
			wc_add_notice( __('Payment error_session:', 'woocommerce_zcredit') , 'error' );
		}
		
    }

    protected static function get_lang_prefix(){
        $lang_prefix = '';
		$my_current_lang = apply_filters( 'wpml_current_language', NULL );
        //if( function_exists('icl_object_id') ) {
        //    if(ICL_LANGUAGE_CODE && ICL_LANGUAGE_CODE != 'all' ) {
        //        $lang_prefix = '_' . ICL_LANGUAGE_CODE;
        //    }
        //}
        if($my_current_lang && $my_current_lang != 'all' ) {
                $lang_prefix = '_' . $my_current_lang;
        }
        return $lang_prefix;
    }

    public function zcredit_gateway_title( $title, $gateway_id ) {
        if( $gateway_id == 'zcredit_checkout_payment' ) {
            $title = $this->get_option( 'title' . self::get_lang_prefix() );
        }
        return $title;
    }

    public function zcredit_gateway_description( $description, $gateway_id ) {
        if( $gateway_id == 'zcredit_checkout_payment' ) {
            $description = $this->get_option( 'description' . self::get_lang_prefix() );
        }
        return $description;
    }
	
	
	//TODO - try and catch ,and handle errors
    public function check_zcredit_response() {	

		// Takes raw data from the request
		$json = file_get_contents('php://input');
		// Converts it into a PHP object
		
		
		//return;
		
		$response = json_decode($json, true);		
		$data = $response;
		
		$guid = $data['SessionId'];		//This is the session ID
		$uniqueID = $data['UniqueID'];		//This is the order ID
		$J = $data['J'];
		$token = $data['Token'];
		$referenceID = $data['ReferenceNumber'];
		
        $order_guid = get_post_meta($uniqueID, 'order_guid', true );
		
        if( $order_guid && $order_guid == $guid ) {
            $order = new WC_Order( $uniqueID );
			update_post_meta( $uniqueID, 'zc_payment_token', $token );
			update_post_meta( $uniqueID, 'zc_transaction_id', $referenceID );
            update_post_meta( $uniqueID, 'zc_response', base64_encode(serialize($json)) );
			
            if( isset($J) && $J == 5 ) {
                //$order->update_status('paymentauthorized');
				$order->add_order_note( __( 'Payment authorized, waiting for capture.', 'woocommerce_zcredit' ) );
				$order->update_status('on-hold');
            }
            else{
                $order->add_order_note( __( 'Z-Credit Payment Complete.', 'woocommerce_zcredit' ) );
                $order->payment_complete();                
            }
			
            WC()->session->set('zcredit_iframe_displayed', false);
            WC()->session->set('zcredit_iframe_redirect_url', false);
        }
        //exit;
        //wp_die();
    }
}