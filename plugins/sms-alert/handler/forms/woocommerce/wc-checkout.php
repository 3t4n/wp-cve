<?php
/**
 * Wc checkout
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */ 
if (! defined('ABSPATH') ) {
    exit;
}
if (! is_plugin_active('woocommerce/woocommerce.php') ) {
    return; 
}

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

 /**
  * PHP version 5
  *
  * @category Handler
  * @package  SMSAlert
  * @author   SMS Alert <support@cozyvision.com>
  * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
  * @link     https://www.smsalert.co.in/
  *
  * WooCommerceCheckOutForm class
  */
class WooCommerceCheckOutForm extends FormInterface
{
    /**
     * If OTP is enabled only for guest users.
     *
     * @var $guest_check_out_only If OTP is enabled only for guest users.
     */
    private $guest_check_out_only;

    /**
     * Show OTP verification button
     *
     * @var $show_button Show OTP verification button
     */
    private $show_button;

    /**
     * Woocommerce default registration form key
     *
     * @var $form_session_var Woocommerce default registration form key
     */
    private $form_session_var = FormSessionVars::WC_CHECKOUT;

    /**
     * Woocommerce block checkout registration form key
     *
     * @var $form_session_var2 Woocommerce checkout popup form key
     */
    private $form_session_var2 = FormSessionVars::WC_CHECKOUT_POPUP;

    /**
     * Woocommerce Post checkout form key
     *
     * @var $form_session_var3 Woocommerce Post checkout form key
     */
    private $form_session_var3 = FormSessionVars::WC_POST_CHECKOUT;

    /**
     * Popup enabled
     *
     * @var $popup_enabled Popup enabled
     */
    private $popup_enabled;

    /**
     * Payment methods
     *
     * @var $payment_methods Payment methods
     */
    private $payment_methods;
	
	/**
     * Allow otp countries
     *
     * @var $allow_otp_countries Allow otp countries
     */
    private $allow_otp_countries;

    /**
     * Verify OTP only for selected gateways
     *
     * @var $otp_for_selected_gateways Verify OTP only for selected gateways
     */
    private $otp_for_selected_gateways;

    /**
     * 
     * Handles form.
     *
     * @return void
     */
    public function handleForm()
    {
        add_filter('woocommerce_checkout_fields', array( $this, 'getCheckoutFields' ), 1, 1);
        add_action('woocommerce_after_checkout_validation', array( $this, 'woocommerceSiteCheckoutErrors' ), 10, 2);
        $checkout_otp_enabled = smsalert_get_option('buyer_checkout_otp', 'smsalert_general');		
        $post_verification = smsalert_get_option('post_order_verification', 'smsalert_general');
        if ('on' === $post_verification && 'on' === $checkout_otp_enabled) {
            add_action('woocommerce_thankyou_order_received_text', array( $this, 'sendPostOrderOtp' ), 10, 2);
            add_action('woocommerce_order_details_after_order_table', array( $this, 'orderDetailsAfterPostOrderOtp' ), 10);
        }

        add_action( 'woocommerce_blocks_enqueue_checkout_block_scripts_after', array( $this, 'showButtonOnBlockPage' ) );
        $this->allow_otp_countries = maybe_unserialize(smsalert_get_option('allow_otp_country', 'smsalert_general' , null)); 
        $this->payment_methods           = maybe_unserialize(smsalert_get_option('checkout_payment_plans', 'smsalert_general'));
        $this->otp_for_selected_gateways = ( smsalert_get_option('otp_for_selected_gateways', 'smsalert_general') === 'on' ) ? true : false;
        $this->popup_enabled             = ( (smsalert_get_option('checkout_show_otp_button', 'smsalert_general') !== 'on') || (smsalert_get_option('checkout_otp_popup', 'smsalert_general', 'off') === 'on') ||  (smsalert_get_option('buyer_checkout_otp', 'smsalert_general', 'off') !== 'on' && smsalert_get_option('checkout_show_otp_button', 'smsalert_general') === 'on')) ? true : false;
        $this->guest_check_out_only      = ( smsalert_get_option('checkout_show_otp_guest_only', 'smsalert_general') === 'on' ) ? true : false;
        $this->show_button               = ( smsalert_get_option('checkout_show_otp_button', 'smsalert_general') === 'on' ) ? true : false;
        $register_otp_enabled = smsalert_get_option('buyer_signup_otp', 'smsalert_general');

        if (( 'on' === $checkout_otp_enabled ) || ( 'on' !== $checkout_otp_enabled && 'on' === $register_otp_enabled ) ) {
            add_action('woocommerce_review_order_after_submit', array( $this, 'addShortcode' ), 1, 1);
        }
        add_action('woocommerce_after_checkout_billing_form', array( $this, 'myCustomCheckoutField' ), 99);
        //in aero checkout modal was not showing
        add_action('wfacp_footer_after_print_scripts', array( $this, 'aeroCheckoutPage' ), 99);
        add_action('wfacp_after_billing_phone_field', array( $this, 'myCustomCheckoutField' ), 99);
        add_action('woocommerce_order_partially_refunded', array( $this, 'refundedPartiallyAmount' ), 99, 2);add_filter( 'before_sa_campaign_send',array( $this, 'modifyMessage' ),10, 3 );                                                     
        $this->routeData();
    }
	
	/**
     * replace sms campaign text variable.
     *
     * @param string $message message.
     * @param string $type type.
     * @param int $id id.
     *
     * @return string
     */
	public function modifyMessage($message, $type, $post_id) {
		if( ('orders_data' === $type) || ('order_status_data' === $type))
		{
			$message = WooCommerceCheckOutForm::pharseSmsBody(array('sms_body'=>$message), $post_id);
			return $message['sms_body'];
		}
		return $message;
	}
	
    /**
     * Send sms partially refunded.
     *
     * @param int $order_id   order_id
     * @param int $refundedid refundedid
     *
     * @return void
     */
    public function refundedPartiallyAmount($order_id, $refundedid)
    {
    
        if (! $order_id ) {
            return;
        }
        $order                = wc_get_order($order_id);
        $admin_sms_data       = array();
		if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
          $refundamount         = get_post_meta($refundedid, '_refund_amount', true);
		  $buyerNumber          = get_post_meta( $order_id, '_billing_phone', true );
        } else {
         $refundamount         = $order->get_meta('_refund_amount'); 
         $buyerNumber          = $order->get_meta('_billing_phone');
		}
		$new_status           = 'partially_refunded';
        $buyer_sms_body       = smsalert_get_option('sms_body_'.$new_status, 'smsalert_message');
        $buyer_sms_body       = str_replace('[order_status]', 'partially refunded', $buyer_sms_body);    
        $buyer_sms_body       =str_replace('[refund_amount]', $refundamount, $buyer_sms_body);
        $sms_data['sms_body'] = $buyer_sms_body;
        $buyerMessage         =  WooCommerceCheckOutForm::pharseSmsBody($sms_data, $order_id);
        do_action('sa_send_sms', $buyerNumber, $buyerMessage['sms_body']);    
        /* Admin Notification  */
        $admin_phone_number    = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
        $admin_phone_number    = str_replace('postauthor', 'post_author', $admin_phone_number);    
        if (smsalert_get_option('admin_notification_' . $new_status, 'smsalert_general', 'on') === 'on' && ! empty($admin_phone_number) ) {
            // send sms to post author.
            $has_sub_order = metadata_exists('post', $order_id, 'has_sub_order');
            if (( strpos($admin_phone_number, 'post_author') !== false ) 
                && ( ( 0 !== $order->get_parent_id() ) || ( ( 0 === $order->get_parent_id() ) && empty($has_sub_order) ) ) 
            ) {
                $order_items = $order->get_items();
                $first_item  = current($order_items);
                $prod_id     = $first_item['product_id'];
                $product     = wc_get_product($prod_id);
                $author_no   = apply_filters('sa_post_author_no', $prod_id);

                if (0 === $order->get_parent_id() ) {
                    $admin_phone_number = str_replace('post_author', $author_no, $admin_phone_number);
                } else {
                    $admin_phone_number   = $author_no;
                }
            }
            if (( strpos($admin_phone_number, 'store_manager') !== false ) && ( ( 0 === $order->get_parent_id() ) && empty($has_sub_order) ) ) {

                $author_no              = apply_filters('sa_store_manager_no', $order);

                $admin_phone_number     = str_replace('store_manager', $author_no, $admin_phone_number);
            }
            $default_template           = SmsAlertMessages::showMessage('DEFAULT_ADMIN_SMS_' . str_replace('-', '_', strtoupper($new_status)));
            $default_admin_sms          = ( ( ! empty($default_template) ) ? $default_template : SmsAlertMessages::showMessage('DEFAULT_ADMIN_SMS_STATUS_CHANGED') );
            $admin_sms_body             = smsalert_get_option('admin_sms_body_' . $new_status, 'smsalert_message', $default_admin_sms);
            $admin_sms_body             = str_replace('[refund_amount]', $refundamount, $admin_sms_body);
            $admin_sms_body             = str_replace('[order_status]', 'partially refunded', $admin_sms_body);    
            $admin_sms_data['sms_body'] = $admin_sms_body;
            $admin_sms_data['number']   = $admin_phone_number;
            $adminMessage  =  WooCommerceCheckOutForm::pharseSmsBody($admin_sms_data, $order_id);
            do_action('sa_send_sms', $admin_phone_number, $adminMessage['sms_body']);    
            
            
        }    
    }
    
    /**
     * Routes data.
     *
     * @return void
     */
    public function aeroCheckoutPage()
    {
        echo '<script>
		jQuery(window).load(function(){
		  var modal = jQuery(".modal.smsalertModal");
		  jQuery("body").append(modal.detach());
		});</script>';
    }
    
    /**
     * This function shows checkout error message.
     *
     * @param array  $data   Data array.
     * @param string $errors Errors.
     *
     * @return void
     */
    public function woocommerceSiteCheckoutErrors( $data, $errors )
    {
        SmsAlertUtility::checkSession();
		$user_phone = ( ! empty($_REQUEST['billing_phone']) ) ? sanitize_text_field(wp_unslash($_REQUEST['billing_phone'])) : '';
		if (! SmsAlertcURLOTP::validateCountryCode($user_phone)){                
				return $errors;
		}
        if (isset($_SESSION['sa_mobile_verified']) ) {
            unset($_SESSION['sa_mobile_verified']);
            return $errors;
        }
        $verify = check_ajax_referer('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce', false);
        if (!$verify) {
            $errors->add('registration-error-invalid-nonce', __('Sorry, nonce did not verify.', 'sms-alert'));
        }
        if (! SmsAlertUtility::isBlank(array_filter($errors->errors)) ) {
            return $errors;
        }
        if (isset($_REQUEST['option']) && sanitize_text_field(wp_unslash($_REQUEST['option']) === 'smsalert-woocommerce-checkout-process') ) {
            SmsAlertUtility::initialize_transaction($this->form_session_var2);
        } 
        if (SmsAlertUtility::isBlank($user_phone)) {
            $errors->add('registration-error-invalid-phone', __('Please enter phone number.', 'sms-alert'));
        } else if (! isset($user_phone) || ! SmsAlertUtility::validatePhoneNumber($user_phone) ) {
            global $phoneLogic;
            $errors->add('registration-error-invalid-phone', str_replace('##phone##', $user_phone, $phoneLogic->_get_otp_invalid_format_message()));
        } 
		$guest_checkout = get_option('woocommerce_enable_guest_checkout');
        if ((isset($_POST['createaccount']) && $_POST['createaccount']) || $guest_checkout !== 'yes' && !is_user_logged_in()) {
            $username = isset($_POST['account_username'])?$_POST['account_username']:$data['billing_email'];
            $error = false;
            if (email_exists($data['billing_email']) ) {
                $errors->add('registration-error-email-exists', __('An account is already registered with your email address. <a href="#" class="showlogin">Please log in.</a>', 'woocommerce'));
            }
            if (isset($_POST['account_username'])) {
                if (username_exists($_POST['account_username'])) {
                    $errors->add('registration-error-username-exists', __('An account is already registered with that username. Please choose another.', 'woocommerce'));
                }
            }
            
            if (smsalert_get_option('allow_multiple_user', 'smsalert_general') !== 'on' ) {

                $getusers = SmsAlertUtility::getUsersByPhone('billing_phone', $user_phone);
                if (count($getusers) > 0 ) {
                    $errors->add('registration-error-number-exists', __('An account is already registered with this mobile number. Please login.', 'sms-alert'));
                }
            }
        }
        if ($errors->get_error_code() ) {
            throw new Exception($errors->get_error_message());
        }
        if (isset($_REQUEST['checkout'])) {
            return $this->processFormFields($errors);
        } else if (isset($_REQUEST['order_verify'])) {
            $this->myCustomCheckoutFieldProcess();
        }
    }

    /**
     * This function processed form fields.
     *
     * @param array $errors Errors array.
     *
     * @return void
     */
    public function processFormFields( $errors )
    {
        $phone_no  = ( ! empty($_POST['billing_phone']) ) ? sanitize_text_field(wp_unslash($_POST['billing_phone'])) : '';
        $phone_num = preg_replace('/[^0-9]/', '', $phone_no);
        smsalert_site_challenge_otp(null, null, $errors, $phone_num, 'phone', null);
    }

    /**
     * Autocomplete and changes in pincode fields.
     *
     * @return void
     */
    public function addShortcode()
    { 
        $checkout_otp_enabled = smsalert_get_option('buyer_checkout_otp', 'smsalert_general');
        $inline_script = 'var signup_checkout = "'.get_option('woocommerce_enable_signup_and_login_from_checkout').'";
	var guest_checkout = "'.get_option('woocommerce_enable_guest_checkout').'";
	var user_logged_in = "'.is_user_logged_in().'";
	var is_popup = "'.$this->popup_enabled.'";
	var selected_payment = "'.$this->otp_for_selected_gateways.'";
	var post_verify = "'.( ( smsalert_get_option('post_order_verification', 'smsalert_general') === 'on' ) ? true : false ).'";
	var enable_country = "'.( ( smsalert_get_option('checkout_show_country_code', 'smsalert_general') === 'on' ) ? true : false ).'";
	var allow_otp_verification = "'.( ( smsalert_get_option('allow_otp_verification', 'smsalert_general') === 'on' ) ? true : false ).'";
	var ask_otp = "'.( $this->guest_check_out_only && is_user_logged_in() ? false : (($checkout_otp_enabled === 'on' ) ? true : false) ).'";
	var paymentMethods = '.json_encode($this->payment_methods).';
	var allow_otp_countries = '.json_encode($this->allow_otp_countries).';
	var register_otp = "'.( ( smsalert_get_option('buyer_signup_otp', 'smsalert_general') === 'on' ) ? true : false ).'";
	var btn_text = "'.smsalert_get_option('otp_verify_btn_text', 'smsalert_general', '').'";
	function smsalert() {
	  if (signup_checkout == "yes" && guest_checkout != "yes" && register_otp && !user_logged_in)
	  {
		addShortcode();  
	  }		  
      if (ask_otp && !selected_payment && !post_verify)
	  {
		addShortcode();
	  } else {	jQuery("input[name=payment_method],input[name=radio-control-wc-payment-method-options]").each(function(e, t) {
		if (!post_verify)
		 {
            var o = jQuery(t).val();
            if (jQuery(t).is(":checked") && ask_otp && ((jQuery.inArray(o, paymentMethods) > -1) || !selected_payment) )
			{
				addShortcode();
			}
		 }
        });
	  }
	  jQuery(document).on("click","input[name=payment_method],input[name=radio-control-wc-payment-method-options]",function() { 
      var pay_method = jQuery("input[name=payment_method]:checked,input[name=radio-control-wc-payment-method-options]:checked").val();	
	  onChangePayment(pay_method); 
	});   
    jQuery(document).on("payment_method_selected",function() {
		 var payment = jQuery("input[name=payment_method]:checked").val(); 
		   onChangePayment(payment);
    });
	 jQuery(".woocommerce #createaccount,.wc-block-checkout__create-account .wc-block-components-checkbox__input").click(function() {
        if (1 == jQuery(this).prop("checked") && register_otp)
		{
			addShortcode();
		} else {
			if (post_verify)
			{
				removeShortcode();
			} else {
			var pay_method = jQuery("input[name=payment_method]:checked,input[name=radio-control-wc-payment-method-options]:checked").val();		
			onChangePayment(pay_method);
			}
		}
    });		
	if (1 == jQuery(".woocommerce #createaccount").prop("checked") && register_otp)
	{		
	  addShortcode();
	}
	} 
	jQuery(document).on("updated_checkout",function() {
    jQuery("#order_verify_field,#smsalert_otp_token_submit").addClass("sa-default-btn-hide");
    smsalert();
	if (enable_country && jQuery(\'.woocommerce [name="billing_phone"]:hidden\').length == 0)
	{
	initialiseCountrySelector(".woocommerce #billing_phone");
	jQuery(\'.woocommerce [name="billing_phone"]:hidden\').val(jQuery(\'.woocommerce [name="billing_phone"]\').intlTelInput("getNumber"));
	}
    });
	const targetNode = document.getElementsByClassName("wp-block-woocommerce-checkout");
	if(targetNode.length>0)
	{
		const config = { attributes: true, childList: true, subtree: true };
		const callback = (mutationList, observer) => {
		  for (const mutation of mutationList) {
			  if (mutation.type === "childList" &&  mutation.addedNodes.length>0 && mutation.addedNodes[0].classList != undefined && (mutation.addedNodes[0].classList.contains("wp-block-woocommerce-checkout-actions-block") || mutation.addedNodes[0].classList.contains("wc-block-checkout__payment-method"))) {  
				smsalert();
				var phone_selector = (jQuery("#billing-phone").length != 0)?"#billing-phone":"#shipping-phone";
				jQuery(phone_selector).addClass("phone-valid");
				initialiseCountrySelector(phone_selector);
			  }
		  }
		};
		const observer = new MutationObserver(callback);
		observer.observe(targetNode[0], config);
	}
	function onChangePayment(payment)
	{
		if (!post_verify)
		{
		if ((ask_otp && ((jQuery.inArray(payment, paymentMethods) > -1) || !selected_payment)) || ((1 == jQuery(".woocommerce #createaccount, .wc-block-checkout__create-account .wc-block-components-checkbox__input").prop("checked") || 0 == jQuery(".woocommerce #createaccount, .wc-block-checkout__create-account .wc-block-components-checkbox__input").length) && register_otp && !user_logged_in))
		{
			addShortcode();
		} else {
			removeShortcode();
		}
		}
	}
	function addShortcode()
	{
		if(allow_otp_verification && allow_otp_countries != "")
		{
			var billing_phone = jQuery(".woocommerce-checkout").find("input[name=billing_phone]");
			var country_code = (enable_country && typeof billing_phone.intlTelInput("getSelectedCountryData").dialCode !="undefined") ? billing_phone.intlTelInput("getSelectedCountryData").dialCode : "";

			if(country_code!=""){
			 if(jQuery.inArray(country_code,allow_otp_countries)=== -1){
				return;
			 }
			}
		}
		removeShortcode();
		reset_otp_val();
		if (is_popup)
		{
		var uniqueid = generateUniqueId();
		if (jQuery("form").hasClass("wc-block-components-form"))
		{
			var phone_selector = (jQuery("#billing-phone").length != 0)?"#billing-phone":"#shipping-phone";
			add_smsalert_button(".wc-block-components-checkout-place-order-button",phone_selector,uniqueid,btn_text);
			jQuery(document).on("click", "#sa_verify_"+uniqueid,function(event){
				event.preventDefault();
			send_otp(this,".wc-block-components-checkout-place-order-button",phone_selector,"","");
			});
		} else {
		add_smsalert_button("#place_order","#billing_phone",uniqueid,btn_text);
		jQuery(document).on("click", "#sa_verify_"+uniqueid,function(event){
				event.preventDefault();
		send_otp(this,"#place_order","#billing_phone","","");
		});
		}
		jQuery(".phone-valid,#billing_phone").trigger("keyup");
		jQuery(document).on("keypress", "input", function(e){
			var pform 	= jQuery(this).parents("form");
			if (e.which === 13 && pform.find("#sa_verify_"+uniqueid).length > 0)
			{
				e.preventDefault();
				pform.find("#sa_verify_"+uniqueid).trigger("click");
			}
		});
		} else {
		jQuery("#order_verify_field,#smsalert_otp_token_submit").removeClass("sa-default-btn-hide")	
		}
	}
	function removeShortcode()
	{
		if (is_popup)
		{
		jQuery(".place-order .smsalert_otp_btn_submit,.wc-block-components-checkout-place-order-button.smsalert_otp_btn_submit").remove();
		jQuery("#place_order,.wc-block-components-checkout-place-order-button").removeClass("sa-default-btn-hide");
		} else {
		jQuery("#order_verify_field,#smsalert_otp_token_submit").addClass("sa-default-btn-hide")	
		}
	}
		function generateUniqueId()
		{
			return Math.random().toString(36).substr(2, 9);
		}
		function reset_otp_val() 
		{
		   "11111" == jQuery("#order_verify").val() && jQuery("#order_verify").val("");
		}';
		if ( ! wp_script_is( 'sainlinescript-handle-footer', 'enqueued' ) ) {
         wp_register_script( 'sainlinescript-handle-footer', '', [], '', true );
         wp_enqueue_script( 'sainlinescript-handle-footer'  ); 
		}
        wp_add_inline_script( "sainlinescript-handle-footer", $inline_script);		
    }

    /**
     * Onpage load when customer logged in billing phone at checkout page when country code is enabled.
     *
     * @param array $fields Existing field array.
     *
     * @return void
     */
    public function getCheckoutFields( $fields )
    {

        $phone = empty($_POST['billing_phone']) ? '' : sanitize_text_field(wp_unslash($_POST['billing_phone']));
        if (! empty($phone) ) {
            $_POST['billing_phone'] = SmsAlertUtility::formatNumberForCountryCode($phone);
        }
        return $fields;
    }

    /**
     * Shows Verification button on block page.
     *
     * @return void
     */
    public function showButtonOnBlockPage()
    {
        add_action('wp_footer', array( $this, 'addShortcode' ), 1, 1);
		$otp_modal = '
        setTimeout(function() {
            if (jQuery(".modal.smsalertModal").length==0)    
            {            
            var popup = \''.str_replace(array("\n","\r","\r\n"), "", (get_smsalert_template("template/otp-popup.php", array(), true))).'\';
            jQuery("body").append(popup);
            }
        }, 200);
        ';		
		if ( ! wp_script_is( 'sainlinescript-handle-footer', 'enqueued' ) ) {
         wp_register_script( 'sainlinescript-handle-footer', '', [], '', true );
         wp_enqueue_script( 'sainlinescript-handle-footer'  );
		}		
		wp_add_inline_script( "sainlinescript-handle-footer", $otp_modal);
		
    }

    /**
     * Checks if Form is enabled.
     *
     * @return void
     */
    public static function isFormEnabled()
    {
        $user_authorize     = new smsalert_Setting_Options();
        $islogged           = $user_authorize->is_user_authorised();
        $signup_on_checkout = get_option('woocommerce_enable_signup_and_login_from_checkout');
        return ( $islogged && is_plugin_active('woocommerce/woocommerce.php') && ( 'on' === smsalert_get_option('buyer_checkout_otp', 'smsalert_general') || ( 'on' === smsalert_get_option('buyer_signup_otp', 'smsalert_general') && 'yes' === $signup_on_checkout ) ) ) ? true : false;
    }

    /**
     * Routes data.
     *
     * @return void
     */
    public function routeData()
    {
        if (! array_key_exists('option', $_GET) ) {
            return;
        }
        $option = trim(sanitize_text_field(wp_unslash($_GET['option'])));
        if (strcasecmp($option, 'smsalert-woocommerce-checkout') === 0 || strcasecmp($option, 'smsalert-woocommerce-post-checkout') === 0 ) {
            $this->handleWoocommereCheckoutForm($_POST);
        }

    }

    /**
     * Handles woocommerce checkout form.
     *
     * @param array $getdata Checkout form fields.
     *
     * @return void
     */
    public function handleWoocommereCheckoutForm( $getdata )
    {
        SmsAlertUtility::checkSession();
        if (! empty($_GET['option']) && sanitize_text_field(wp_unslash($_GET['option'])) === 'smsalert-woocommerce-post-checkout' ) {
            SmsAlertUtility::initialize_transaction($this->form_session_var3);
        } else {
            SmsAlertUtility::initialize_transaction($this->form_session_var);
        }
        $phone_num = SmsAlertcURLOTP::checkPhoneNos($getdata['user_phone']);
        $email = !empty($getdata['user_email']) ? $getdata['user_email'] : null;
        smsalert_site_challenge_otp('test', $email, null, trim($phone_num), 'phone');
    }

    /**
     * Checks if verification code is entered or not.
     *
     * @return void
     */
    public function checkIfVerificationCodeNotEntered()
    {
        if ($this->popup_enabled ) {
            return false;
        }

        SmsAlertUtility::checkSession();
        if (empty($_POST['order_verify']) ) {
            wc_add_notice(__('Your mobile number is not verified yet. Please verify your mobile number.', 'sms-alert'), 'error');
            return true;
        }
    }
    
    /**
     * Adds a custom checkout field.
     *
     * @param array $checkout Currently not in use.
     *
     * @return void
     */
    public function myCustomCheckoutField( $checkout )
    {
        $otp_modal = '
        setTimeout(function() {
            if (jQuery(".modal.smsalertModal").length==0)    
            {            
            var popup = \''.str_replace(array("\n","\r","\r\n"), "", (get_smsalert_template("template/otp-popup.php", array(), true))).'\';
            jQuery("body").append(popup);
            }
        }, 200);
        ';
		if ( ! wp_script_is( 'sainlinescript-handle-footer', 'enqueued' ) ) {
         wp_register_script( 'sainlinescript-handle-footer', '', [], '', true );
         wp_enqueue_script( 'sainlinescript-handle-footer'  );
		}		
		wp_add_inline_script( "sainlinescript-handle-footer", $otp_modal);
		
        if ($this->guest_check_out_only && is_user_logged_in() ) {
            return;
        }

        $checkout_otp_enabled = smsalert_get_option('buyer_checkout_otp', 'smsalert_general');
         
        if ('on' === $checkout_otp_enabled && ! $this->popup_enabled ) {
            $this->showValidationButtonOrText();
            echo '<input type="hidden" name="order_verify" id="order_verify">';

            $this->commonButtonOrLinkEnableDisableScript();

        }
    }

    /**
     * Checks if validation button is to be displayed or popup.
     *
     * @param string $popup Currently not in use.
     *
     * @return void
     */
    public function showValidationButtonOrText( $popup = false )
    {
        $this->showButtonOnPage();
    }

    /**
     * Shows a button on checkout page.
     *
     * @return void
     */
    public function showButtonOnPage()
    {
        $otp_verify_btn_text = smsalert_get_option('otp_verify_btn_text', 'smsalert_general', '');
        echo wp_kses_post(
            '<button type="submit" class="button alt" id="smsalert_otp_token_submit" value="'
            . $otp_verify_btn_text . '" ><span class="button__text">' . $otp_verify_btn_text . '</span></button>'
        );
    }

    /**
     * Common script to enable or disable button or link.
     *
     * @return void
     */
    public function commonButtonOrLinkEnableDisableScript()
    {
        $this->enableDisableScriptForButtonOnPage();
    }

    /**
     * Enable or disable verify button on page.
     *
     * @return void
     */
    public function enableDisableScriptForButtonOnPage()
    {
		$otp_resend_timer = !empty(SmsAlertUtility::get_elementor_data("sa_otp_re_send_timer"))?SmsAlertUtility::get_elementor_data("sa_otp_re_send_timer"):smsalert_get_option('otp_resend_timer', 'smsalert_general', '15');
        $inline_script = 'jQuery(document).ready(function() {
		var ischeckout = "'.is_checkout().'";
		var sa_country_enable = "'.smsalert_get_option("checkout_show_country_code", "smsalert_general").'";
		jQuery("div.woocommerce #billing_phone").addClass("phone-valid");
		jQuery(".woocommerce-message").length>0&&(jQuery("#order_verify").focus(),jQuery("#salert_message").addClass("woocommerce-message"));
        jQuery("#smsalert_otp_token_submit").click(function(o){
        var action_url = "'. esc_url(site_url()) . '/?option=smsalert-shortcode-ajax-verify";
        
        if (ischeckout && sa_country_enable === "on" ) {
            m=jQuery(this).parents("form").find("input[name=billing_phone]").intlTelInput("getNumber");
        } else {
            m=jQuery(this).parents("form").find("input[name=billing_phone]").val();
        }
        a=jQuery("div.woocommerce");
		a.addClass("processing").block({message:null,overlayCSS:{background:"#fff",opacity:.6}});
        jQuery(this).addClass("sa-otp-btn-init");
		saInitOTPProcess(
			this,
			action_url,
			{user_phone:m},
			' . esc_attr($otp_resend_timer) . ',
			function(resp){
				a.removeClass( "processing" ).unblock();
			},
			function(resp){
				a.removeClass( "processing" ).unblock();
			},
			"input[name=billing_phone]"
		);
		return false;
		});
        ""!=jQuery("input[name=billing_phone]").val()&&jQuery("#smsalert_otp_token_submit").prop( "disabled", false );
		jQuery(document).on("input change","input[name=billing_phone]",function() {
			jQuery(this).val(jQuery(this).val().replace(/^0+/, "").replace(/\s+/g, ""));
			
			var phone;
			if (typeof sa_otp_settings !=  "undefined" && sa_otp_settings["show_countrycode"]=="on" )
			{
				 phone = jQuery("input[name=billing_phone]:hidden").val();
			} else {
				 phone = jQuery(this).val();
			}
			
			if (typeof phone != "undefined" && phone.replace(/\s+/g, "").match(' . esc_attr(SmsAlertConstants::getPhonePattern()) . ') && (typeof jQuery(".sa_phone_error") == "undefined" || jQuery(".sa_phone_error").text()==""))  
			{
			
				jQuery("#smsalert_otp_token_submit").prop( "disabled", false );
			
		} else { jQuery("#smsalert_otp_token_submit").prop( "disabled", true ); }});
		jQuery("input[name=billing_phone]").trigger( "input").trigger( "change");
		});';
		if ( ! wp_script_is( 'sainlinescript-handle-footer', 'enqueued' ) ) {
         wp_register_script( 'sainlinescript-handle-footer', '', [], '', true );
         wp_enqueue_script( 'sainlinescript-handle-footer'  ); 
		}
        wp_add_inline_script( "sainlinescript-handle-footer", $inline_script);
    }

    /**
     * Process the custom checkout form.
     *
     * @return void
     */
    public function myCustomCheckoutFieldProcess()
    {
        $post_verification    = smsalert_get_option('post_order_verification', 'smsalert_general');
        $checkout_otp_enabled = smsalert_get_option('buyer_checkout_otp', 'smsalert_general');
        $buyer_checkout_otp   = smsalert_get_option('buyer_signup_otp', 'smsalert_general');

        if ('on' === $post_verification ) {
            return;
        }
            
        if (!isset($_REQUEST['order_verify']) ) {
            return;
        }    

        if (! isset($_SESSION[ $this->form_session_var ]) && ! isset($_SESSION[ $this->form_session_var2 ]) && ! isset($_SESSION[ $this->form_session_var3 ]) && ( 'on' !== $checkout_otp_enabled && 'on' === $buyer_checkout_otp && empty($_REQUEST['createaccount']) ) ) {
            return;
        }

        if ($this->guest_check_out_only && is_user_logged_in() ) {
            return;
        }

        if (empty($_REQUEST['createaccount']) && ! $this->isPaymentVerificationNeeded() ) {
            return;
        }

        if ($this->checkIfVerificationCodeNotEntered() ) {
            return;
        }
    }

    /**
     * Checks if OTP verification is required.
     *
     * @param string $payment_method Payment method selected.
     *
     * @return void
     */
    public function isPaymentVerificationNeeded( $payment_method = null )
    {
        if (! $this->otp_for_selected_gateways ) {
            return true;
        }

        $payment_method = ( ! empty($_POST['payment_method']) ? sanitize_text_field(wp_unslash($_POST['payment_method'])) : $payment_method );
        return in_array($payment_method, $this->payment_methods, true);
    }

    /**
     * Handles failed OTP verification
     *
     * @param string $user_login   User login.
     * @param string $user_email   Email Id.
     * @param string $phone_number Phone number of the user.
     *
     * @return void
     */
    public function handle_failed_verification( $user_login, $user_email, $phone_number )
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) && ! isset($_SESSION[ $this->form_session_var2 ]) && ! isset($_SESSION[ $this->form_session_var3 ]) ) {
            return;
        }
        if (isset($_SESSION[ $this->form_session_var2 ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
        } elseif (isset($_SESSION[ $this->form_session_var3 ]) ) {
			wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('INVALID_OTP'), 'error'));
			exit();
        } else {
            wc_add_notice(SmsAlertUtility::_get_invalid_otp_method(), 'error');
        }
    }

    /**
     * Handles Post OTP verification
     *
     * @param string $redirect_to  The url to be redirected to.
     * @param string $user_login   User login.
     * @param string $user_email   Email Id.
     * @param string $password     Password.
     * @param string $phone_number Phone number of the user.
     * @param array  $extra_data   Extra form data.
     *
     * @return void
     */
    public function handle_post_verification( $redirect_to, $user_login, $user_email, $password, $phone_number, $extra_data )
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) && ! isset($_SESSION[ $this->form_session_var2 ]) && ! isset($_SESSION[ $this->form_session_var3 ]) ) {
            return;
        }

        if (isset($_SESSION[ $this->form_session_var2 ]) ) {
            wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('VALID_OTP'), 'success'));
            $this->unsetOTPSessionVariables();
            exit();
        } elseif (isset($_SESSION[ $this->form_session_var3 ]) ) {
            $order_id = ! empty($_REQUEST['o_id']) ? sanitize_text_field(wp_unslash($_REQUEST['o_id'])) : '';
			if ( version_compare( WC_VERSION, '8.2', '<' ) ) {
			  $output   = update_post_meta($order_id, '_smsalert_post_order_verification', 1);
			} else {
				$order = wc_get_order( $order_id );
				$order->update_meta_data( '_smsalert_post_order_verification', 1 );
				$output = $order->save();
			}
            if ($output > 0 ) {
                wp_send_json(SmsAlertUtility::_create_json_response(SmsAlertMessages::showMessage('VALID_OTP'), 'success'));
                $this->unsetOTPSessionVariables();
                exit();
            } 
        } else {
            $this->unsetOTPSessionVariables();
        }
    }

    /**
     * Unset OTP session variables.
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {
        unset($_SESSION[ $this->tx_session_id ]);
        unset($_SESSION[ $this->form_session_var ]);
        unset($_SESSION[ $this->form_session_var2 ]);
        unset($_SESSION[ $this->form_session_var3 ]);
    }

    /**
     * Checks if ajax form is active.
     *
     * @param bool $is_ajax Whether the request is ajax request.
     *
     * @return void
     */
    public function is_ajax_form_in_play( $is_ajax )
    {
        SmsAlertUtility::checkSession();
        return ( isset($_SESSION[ $this->form_session_var ]) || isset($_SESSION[ $this->form_session_var2 ]) || isset($_SESSION[ $this->form_session_var3 ]) ) ? true : $is_ajax;
    }

    /**
     * Handle form options.
     *
     * @return void
     */
    public function handleFormOptions()
    {
        add_action('add_meta_boxes', array( $this, 'addSendSmsMetaBox' ));
        add_action('wp_ajax_wc_sms_alert_sms_send_order_sms', array( $this, 'sendCustomSms' ));

        if (is_plugin_active('woocommerce/woocommerce.php') ) {
            add_action('sa_addTabs', array( $this, 'addTabs' ), 1);
            add_filter('sAlertDefaultSettings', __CLASS__ . '::addDefaultSetting', 1);
        }
        add_action('woocommerce_admin_order_data_after_billing_address', array( $this, 'addAdminGeneralOrderVariationDescription' ), 10, 1);
    }

    /**
     * Add Post order verification status in admin section.
     *
     * @param object $order Order object.
     *
     * @return void
     */
    public function addAdminGeneralOrderVariationDescription( $order )
    {
        $order_id          = $order->get_id();
		if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
          $post_verification = get_post_meta($order_id, '_smsalert_post_order_verification', true);
        } else {
			$post_verification  = $order->get_meta('_smsalert_post_order_verification'); 
		}
        if ($post_verification ) {
            echo '
			<p><strong>SMS Alert Post Verified</strong></p>
			<span class="dashicons dashicons-yes" style="color: #fff;width: 22px;height: 22px;background: #07930b;border-radius: 25px;line-height: 22px;" title="SMS Alert Post Verified"></span>';
        }
    }

    /**
     * Get order variables.
     *
     * @return void
     */
    public static function getOrderVariables()
    {

        $variables = array(
        '[order_id]'             => 'Order Id',
        '[order_status]'         => 'Order Status',
        '[order_amount]'         => 'Order Amount',
        '[refund_amount]'         => 'Refund Amount',                            
        '[order_date]'           => 'Order Date',
        '[store_name]'           => 'Store Name',
        '[item_name]'            => 'Product Name',
        '[item_name_qty]'        => 'Product Name with Quantity',
        '[billing_first_name]'   => 'Billing First Name',
        '[billing_last_name]'    => 'Billing Last Name',
        '[billing_company]'      => 'Billing Company',
        '[billing_address_1]'    => 'Billing Address 1',
        '[billing_address_2]'    => 'Billing Address 2',
        '[billing_city]'         => 'Billing City',
        '[billing_state]'        => 'Billing State',
        '[billing_postcode]'     => 'Billing Postcode',
        '[billing_country]'      => 'Billing Country',
        '[billing_email]'        => 'Billing Email',
        '[billing_phone]'        => 'Billing Phone',
        '[shipping_first_name]'  => 'Shipping First Name',
        '[shipping_last_name]'   => 'Shipping Last Name',
        '[shipping_company]'     => 'Shipping Company',
        '[shipping_address_1]'   => 'Shipping Address 1',
        '[shipping_address_2]'   => 'Shipping Address 2',
        '[shipping_city]'        => 'Shipping City',
        '[shipping_state]'       => 'Shipping State',
        '[shipping_postcode]'    => 'Shipping Postcode',
        '[shipping_country]'     => 'Shipping Country',
        '[order_currency]'       => 'Order Currency',
        '[payment_method]'       => 'Payment Method',
        '[payment_method_title]' => 'Payment Method Title',
        '[shipping_method]'      => 'Shipping Method',
        '[shop_url]'             => 'Shop Url',
        '[customer_note]'        => 'Customer Note',
        );
        return $variables;
    }

    /**
     * Get Customer templates.
     *
     * @return void
     */
    public static function getCustomerTemplates()
    {
        $order_statuses = is_plugin_active('woocommerce/woocommerce.php') ? wc_get_order_statuses() : array();

        $order_statuses['partially_refunded'] = 'Partially Refunded';
        $smsalert_notification_status     = smsalert_get_option('order_status', 'smsalert_general', '');
        $smsalert_notification_onhold     = ( is_array($smsalert_notification_status) && array_key_exists('on-hold', $smsalert_notification_status) ) ? $smsalert_notification_status['on-hold'] : 'on-hold';
        $smsalert_notification_processing = ( is_array($smsalert_notification_status) && array_key_exists('processing', $smsalert_notification_status) ) ? $smsalert_notification_status['processing'] : 'processing';
        $smsalert_notification_completed  = ( is_array($smsalert_notification_status) && array_key_exists('completed', $smsalert_notification_status) ) ? $smsalert_notification_status['completed'] : 'completed';
        $smsalert_notification_cancelled  = ( is_array($smsalert_notification_status) && array_key_exists('cancelled', $smsalert_notification_status) ) ? $smsalert_notification_status['cancelled'] : 'cancelled';

        $smsalert_notification_notes = smsalert_get_option('buyer_notification_notes', 'smsalert_general', 'on');
        $sms_body_new_note           = smsalert_get_option('sms_body_new_note', 'smsalert_message', SmsAlertMessages::showMessage('DEFAULT_BUYER_NOTE'));

        $templates = array();
        foreach ( $order_statuses as $ks  => $order_status ) {

            $prefix = 'wc-';
            $vs     = $ks;
            if (substr($vs, 0, strlen($prefix)) === $prefix ) {
                $vs = substr($vs, strlen($prefix));
            }

            $current_val = ( is_array($smsalert_notification_status) && array_key_exists($vs, $smsalert_notification_status) ) ? $smsalert_notification_status[ $vs ] : $vs;

            $current_val = ( $current_val === $vs ) ? 'on' : 'off';

            $checkbox_name_id = 'smsalert_general[order_status][' . $vs . ']';
            $textarea_name_id = 'smsalert_message[sms_body_' . $vs . ']';

            $default_template = SmsAlertMessages::showMessage('DEFAULT_BUYER_SMS_' . str_replace('-', '_', strtoupper($vs)));
            $text_body        = smsalert_get_option('sms_body_' . $vs, 'smsalert_message', ( ( ! empty($default_template) ) ? $default_template : SmsAlertMessages::showMessage('DEFAULT_BUYER_SMS_STATUS_CHANGED') ));

            $templates[ $ks ]['title']          = 'When Order is ' . ucwords($order_status);
            $templates[ $ks ]['enabled']        = $current_val;
            $templates[ $ks ]['status']         = $vs;
            $templates[ $ks ]['chkbox_val']     = $vs;
            $templates[ $ks ]['text-body']      = $text_body;
            $templates[ $ks ]['checkboxNameId'] = $checkbox_name_id;
            $templates[ $ks ]['textareaNameId'] = $textarea_name_id;
            $templates[ $ks ]['moreoption']     = 1;
            $templates[ $ks ]['token']          = self::getvariables($vs);
        }

        $new_note                                = self::getOrderVariables();
        $new_note['[note]']                      = 'Order Note';
        $templates['new-note']['title']          = 'When a new note is added to order';
        $templates['new-note']['enabled']        = $smsalert_notification_notes;
        $templates['new-note']['status']         = 'new-note';
        $templates['new-note']['text-body']      = $sms_body_new_note;
        $templates['new-note']['checkboxNameId'] = 'smsalert_general[buyer_notification_notes]';
        $templates['new-note']['textareaNameId'] = 'smsalert_message[sms_body_new_note]';
        $templates['new-note']['token']          = $new_note;
        return $templates;
    }

    /**
     * Get multi vendor admin templates.
     *
     * @return void
     */
    public static function getMVAdminTemplates()
    {
        $order_statuses = is_plugin_active('woocommerce/woocommerce.php') ? self::multivendorstatuses() : array();

        $templates = array();
        foreach ( $order_statuses as $ks  => $order_status ) {

            $vs               = $ks;
            $current_val      = smsalert_get_option('multivendor_notification_' . $vs, 'smsalert_general', 'on');
            $checkbox_name_id = 'smsalert_general[multivendor_notification_' . $vs . ']';
            $textarea_name_id = 'smsalert_message[multivendor_sms_body_' . $vs . ']';
            $default_template = SmsAlertMessages::showMessage('DEFAULT_NEW_USER_' . str_replace('-', '_', strtoupper($vs)));
            $text_body        = smsalert_get_option('multivendor_sms_body_' . $vs, 'smsalert_message', ( ( ! empty($default_template) ) ? $default_template : SmsAlertMessages::showMessage('DEFAULT_ADMIN_SMS_STATUS_CHANGED') ));

            $templates[ $ks ]['title']          = 'When Vendor Account is ' . ucwords($order_status);
            $templates[ $ks ]['enabled']        = $current_val;
            $templates[ $ks ]['status']         = $vs;
            $templates[ $ks ]['text-body']      = $text_body;
            $templates[ $ks ]['checkboxNameId'] = $checkbox_name_id;
            $templates[ $ks ]['textareaNameId'] = $textarea_name_id;
            $templates[ $ks ]['moreoption']     = 1;
            $templates[ $ks ]['token']          = array(
            '[username]'   => 'Username',
            '[store_name]' => 'Store Name',
            '[shop_url]'   => 'Shop URL',
            );
        }
        return $templates;
    }

    /**
     * Get admin templates.
     *
     * @return void
     */
    public static function getAdminTemplates()
    {
        $order_statuses = is_plugin_active('woocommerce/woocommerce.php') ? wc_get_order_statuses() : array();

        $order_statuses['partially_refunded'] = 'Partially Refunded';
        $templates = array();
        foreach ( $order_statuses as $ks  => $order_status ) {

            $prefix = 'wc-';
            $vs     = $ks;
            if (substr($vs, 0, strlen($prefix)) === $prefix ) {
                $vs = substr($vs, strlen($prefix));
            }

            $current_val      = smsalert_get_option('admin_notification_' . $vs, 'smsalert_general', 'on');
            $checkbox_name_id = 'smsalert_general[admin_notification_' . $vs . ']';
            $textarea_name_id = 'smsalert_message[admin_sms_body_' . $vs . ']';
            $default_template = SmsAlertMessages::showMessage('DEFAULT_ADMIN_SMS_' . str_replace('-', '_', strtoupper($vs)));
            $text_body        = smsalert_get_option('admin_sms_body_' . $vs, 'smsalert_message', ( ( ! empty($default_template) ) ? $default_template : SmsAlertMessages::showMessage('DEFAULT_ADMIN_SMS_STATUS_CHANGED') ));

            $templates[ $ks ]['title']          = 'When Order is ' . ucwords($order_status);
            $templates[ $ks ]['enabled']        = $current_val;
            $templates[ $ks ]['status']         = $vs;
            $templates[ $ks ]['text-body']      = $text_body;
            $templates[ $ks ]['checkboxNameId'] = $checkbox_name_id;
            $templates[ $ks ]['textareaNameId'] = $textarea_name_id;
            $templates[ $ks ]['moreoption']     = 1;
            $templates[ $ks ]['token']          = self::getvariables($vs);
        }
        return $templates;
    }

    /**
     * 
     * Add tabs to smsalert settings at backend.
     *
     * @param array $tabs array of existing tabs.
     *
     * @return void
     */
    public static function addTabs( $tabs = array() )
    {
        $customer_param = array(
        'checkTemplateFor' => 'wc_customer',
        'templates'        => self::getCustomerTemplates(),
        );

        $admin_param = array(
        'checkTemplateFor' => 'wc_admin',
        'templates'        => self::getAdminTemplates(),
        );

        $multi_vendor_param = array(
        'checkTemplateFor' => 'wc_mv_vendor',
        'templates'        => self::getMVAdminTemplates(),
        );

        $tabs['woocommerce']['nav']                                     = 'Woocommerce';
        $tabs['woocommerce']['icon']                                    = 'dashicons-list-view';
        $tabs['woocommerce']['inner_nav']['wc_customer']['title']       = 'Customer Notifications';
        $tabs['woocommerce']['inner_nav']['wc_customer']['tab_section'] = 'customertemplates';

        $tabs['woocommerce']['inner_nav']['wc_customer']['tabContent'] = $customer_param;
        $tabs['woocommerce']['inner_nav']['wc_customer']['filePath']   = 'views/message-template.php';
        $tabs['woocommerce']['inner_nav']['wc_customer']['help_links']                        = array(
        'youtube_link' => array(
        'href'   => 'https://youtu.be/91ek7RjRavo',
        'target' => '_blank',
        'alt'    => 'Watch steps on Youtube',
        'class'  => 'btn-outline',
        'label'  => 'Youtube',
        'icon'   => '<span class="dashicons dashicons-video-alt3" style="font-size: 21px;"></span> ',

        ),
        'kb_link'      => array(
        'href'   => 'https://kb.smsalert.co.in/knowledgebase/woocommerce-sms-notifications/#notification-to-buyer',
        'target' => '_blank',
        'alt'    => 'Read how to use customer notifications',
        'class'  => 'btn-outline',
        'label'  => 'Documentation',
        'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
        ),

        );
        $tabs['woocommerce']['inner_nav']['wc_customer']['first_active'] = true;
        $tabs['woocommerce']['inner_nav']['wc_admin']['title']           = 'Admin Notifications';
        $tabs['woocommerce']['inner_nav']['wc_admin']['tab_section']     = 'admintemplates';
        $tabs['woocommerce']['inner_nav']['wc_admin']['tabContent']      = $admin_param;
        $tabs['woocommerce']['inner_nav']['wc_admin']['filePath']        = 'views/message-template.php';
        $tabs['woocommerce']['inner_nav']['wc_admin']['help_links']                        = array(
        'youtube_link' => array(
        'href'   => 'https://youtu.be/91ek7RjRavo',
        'target' => '_blank',
        'alt'    => 'Watch steps on Youtube',
        'class'  => 'btn-outline',
        'label'  => 'Youtube',
        'icon'   => '<span class="dashicons dashicons-video-alt3" style="font-size: 21px;"></span> ',

        ),
        'kb_link'      => array(
        'href'   => 'https://kb.smsalert.co.in/knowledgebase/woocommerce-sms-notifications/#notification-to-admin',
        'target' => '_blank',
        'alt'    => 'Read how to use admin notifications',
        'class'  => 'btn-outline',
        'label'  => 'Documentation',
        'icon'   => '<span class="dashicons dashicons-format-aside"></span>',
        ),

        );
        if (is_plugin_active('dc-woocommerce-multi-vendor/dc_product_vendor.php') || is_plugin_active('dokan-lite/dokan.php') || is_plugin_active('wc-frontend-manager/wc_frontend_manager.php') ) {
            $tabs['woocommerce']['inner_nav']['wc_mv_vendor']['title']       = 'Multi Vendor';
            $tabs['woocommerce']['inner_nav']['wc_mv_vendor']['tab_section'] = 'multivendortemplates';
            $tabs['woocommerce']['inner_nav']['wc_mv_vendor']['tabContent']  = $multi_vendor_param;
            $tabs['woocommerce']['inner_nav']['wc_mv_vendor']['filePath']    = 'views/message-template.php';
        } 
        return $tabs;
    }

    /**
     * 
     * Gets multivendor account status's.
     *
     * @return void
     */
    public static function multivendorstatuses()
    {
        return array(
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        );
    }

    /**
     * 
     * Adds default settings for plugin.
     *
     * @param array $defaults array of default settings.
     *
     * @return void
     */
    public static function addDefaultSetting( $defaults = array() )
    {
        $order_statuses = is_plugin_active('woocommerce/woocommerce.php') ? wc_get_order_statuses() : array();
        $order_statuses['partially_refunded'] = 'Partially Refunded';
        foreach ( $order_statuses as $ks => $vs ) {
            $prefix = 'wc-';
            if (substr($ks, 0, strlen($prefix)) === $prefix ) {
                $ks = substr($ks, strlen($prefix));
            }
            $defaults['smsalert_general'][ 'admin_notification_' . $ks ] = 'off';
            $defaults['smsalert_general']['order_status'][ $ks ]         = '';
            $defaults['smsalert_message'][ 'admin_sms_body_' . $ks ]     = '';
            $defaults['smsalert_message'][ 'sms_body_' . $ks ]           = '';
        }

        $mv_statuses = is_plugin_active('woocommerce/woocommerce.php') ? self::multivendorstatuses() : array();
        foreach ( $mv_statuses as $ks  => $mv_status ) {

            $defaults['smsalert_general'][ 'multivendor_notification_' . $ks ] = 'off';
            $defaults['smsalert_message'][ 'multivendor_sms_body_' . $ks ]     = '';
        }
        return $defaults;
    }

    /**
     * 
     * Adds default settings for plugin.
     *
     * @param array $sms_data array containing sms text and number.
     * @param int   $order_id Order Id.
     *
     * @return void
     */
    public static function pharseSmsBody( $sms_data, $order_id )
    {
        if (empty($sms_data['sms_body']) ) {
            return $sms_data;
        }

        $content         = $sms_data['sms_body'];
        $order_variables = get_post_custom($order_id);
        $order           = wc_get_order($order_id);
        $order_status    = $order->get_status();
        $order_items     = $order->get_items(array( 'line_item', 'shipping' ));
        $order_note      = ( ! empty($sms_data['note']) ? $sms_data['note'] : '' );
        $rma_status          = ( ! empty($sms_data['rma_status']) ? $sms_data['rma_status'] : '' );
        $rma_id          = ( ! empty($sms_data['rma_id']) ? $sms_data['rma_id'] : '' );
        
        
        if (strpos($content, 'orderitem') !== false ) {
            $content = self::saParseOrderItemData($order_items, $content);
        }
        if (strpos($content, 'shippingitem') !== false ) {
            $content = self::saParseOrderItemData($order_items, $content);
        }

        $order_item_products = array_filter(
            $order_items, function ($o) {
                return get_class($o) === 'WC_Order_Item_Product'; 
            }
        );

        $item_name          = implode(
            ', ',
            array_map(
                function ( $o ) {
                        return $o['name'];
                },
                $order_item_products
            )
        );
		
		preg_match_all('/\[item_name (.*?)\]/', $content, $matches);		
		if (!empty($matches[1][0])) {			
			$item_attr = $matches[1][0];	
			$atts = explode(" ",trim($item_attr));		
			foreach ($atts as $atts_val) {
				if (strpos($atts_val,"length")!==false) {
					 preg_match('/\d+/', $atts_val, $matchd);				 
					 $item_name = substr($item_name,0,$matchd[0]);					 
				}	
			}
			$content = str_replace($matches[0], $item_name, $content);			
		}
        $item_name_with_qty = implode(
            ', ',
            array_map(
                function ( $o ) {
                        return sprintf('%s [%u]', $o['name'], $o['qty']);
                },
                $order_item_products
            )
        );
        $store_name         = get_bloginfo();
        $shop_url           = get_site_url();
        $date_format        = 'F j, Y';
        $date_tag           = '[order_date]';

        if (preg_match_all('/\[order_date.*?\]/', $content, $matched) ) {
            $date_tag    = $matched[0][0];
            $date_params = SmsAlertUtility::parseAttributesFromTag($date_tag);
            $date_format = array_key_exists('format', $date_params) ? $date_params['format'] : 'F j, Y';
        }
        
        $order_date = (!empty($order->get_date_created()))? $order->get_date_created()->date($date_format) : '';
        $total_amount = $order->get_total();
        $find    = array(
        '[order_id]',
        $date_tag,
        '[order_status]',
        '[rma_status]',
        '[first_name]',
        '[item_name]',
        '[item_name_qty]',
        '[order_amount]',
        '[refund_amount]',        
        '[note]',
        '[rma_number]',
        '[order_pay_url]',
        '[wc_order_id]',
        '[customer_note]',
        '[shipping_method]'
        );
        $replace = array(
        $order->get_order_number(),
        $order_date,
        $order_status,
        $rma_status,
        '[billing_first_name]',
        wp_specialchars_decode($item_name),
        wp_specialchars_decode($item_name_with_qty),
        $total_amount,
        $total_amount,           
        $order_note,
        $rma_id,
        $order->get_checkout_payment_url(),
        $order_id,
        $order->get_customer_note(),
		$order->get_shipping_method()
        );
        $content = str_replace($find, $replace, $content);
		if ( version_compare( WC_VERSION, '8.2', '<' ) ) {
		    if(!empty($order_variables))
			{
				$content = self::saParseOrderVariableData($order_variables, $content);
				
				foreach ( $order_variables as &$value ) {
					$value = $value[0];
				}
				unset($value);
				$order_variables      = array_combine(
					array_map(
						function ( $key ) {
								return '[' . ltrim($key, '_') . ']'; 
						},
						array_keys($order_variables)
					),
					$order_variables
				);
				$sms_data['sms_body'] = str_replace(array_keys($order_variables), array_values($order_variables), $content);
			}
			else{	
			 $sms_data['sms_body'] = $content;
			}	
		}
		else {
		  $order_variables   = $order->get_data();
		  $content = self::saParseWcOrderVariableData($order_variables, $content);
		  $sms_data['sms_body'] = $content;
		}

        return $sms_data;
    }

    /**
     * 
     * Sends a custom SMS.
     *
     * @param array $data currently not in use.
     *
     * @return void
     */
    public function sendCustomSms( $data )
    {
        $order_id = empty($_POST['order_id']) ? '' : sanitize_text_field(wp_unslash($_POST['order_id']));
        $sms_body = empty($_POST['sms_body']) ? '' : sanitize_textarea_field(wp_unslash($_POST['sms_body']));

        $buyer_sms_data             = array();
		if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
          $buyer_sms_data['number']   = get_post_meta( $order_id, '_billing_phone', true );
		} else {
		  $order       = wc_get_order($order_id);
          $buyer_sms_data['number']   = !empty($order->get_meta('_billing_phone'))?$order->get_meta('_billing_phone'):$order->get_meta('_shipping_phone');
		}
        
        $buyer_sms_data['sms_body'] = $sms_body;
        $buyer_sms_data             = apply_filters('sa_wc_order_sms_customer_before_send', $buyer_sms_data, $order_id);
        wp_send_json(SmsAlertcURLOTP::sendsms($buyer_sms_data));
        exit();
    }

    /**
     * 
     * Adds default settings for plugin.
     *
     * @param array $data array containing order id and note.
     *
     * @return void
     */
    public static function trigger_new_customer_note( $data )
    {

        if (smsalert_get_option('buyer_notification_notes', 'smsalert_general') === 'on' ) {
            $order_id                   = $data['order_id'];
            $buyer_sms_body             = smsalert_get_option('sms_body_new_note', 'smsalert_message', SmsAlertMessages::showMessage('DEFAULT_BUYER_NOTE'));
            $buyer_sms_data             = array();
            $order       = wc_get_order($order_id );
			if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
              $buyer_sms_data['number']   = get_post_meta( $order_id , '_billing_phone', true );
			} else {
			  $buyer_sms_data['number']   = !empty($order->get_meta('_billing_phone'))?$order->get_meta('_billing_phone'):$order->get_meta('_shipping_phone');
            }
            $buyer_sms_data['sms_body'] = $buyer_sms_body;
            $buyer_sms_data['note']     = $data['customer_note'];

            $buyer_sms_data = apply_filters('sa_wc_order_sms_customer_before_send', $buyer_sms_data, $order_id);

            $buyer_response = SmsAlertcURLOTP::sendsms($buyer_sms_data);
            $response       = json_decode($buyer_response, true);

			if ('success' === $response['status'] ) {
                $order->add_order_note(__('Order note SMS Sent to buyer', 'sms-alert'));
            } else {
                if (is_array($response['description']) && array_key_exists('desc', $response['description']) ) {
                    $order->add_order_note($response['description']['desc']);
                } else {
                    $order->add_order_note($response['description']);
                }
            }
        }
    }

    /**
     * 
     * Adds a custom sms meta box.
     *
     * @return void
     */
    public function addSendSmsMetaBox()
    {
		$screen = wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()? wc_get_page_screen_id( 'shop-order' ): 'shop_order';
        add_meta_box(
            'wc_sms_alert_send_sms_meta_box',
            'SMS Alert (Custom SMS)',
            array( $this, 'displaySendSmsMetaBox' ),
            $screen,
            'side',
            'default'
        );
    }

    /**
     * 
     * Displays send sms meta box.
     *
     * @param object $data order object.
     *
     * @return void
     */
    public static function displaySendSmsMetaBox( $data )
    {
        global $woocommerce;
        
		$data = ( $data instanceof WP_Post ) ? wc_get_order( $data->ID ) : new WC_Order($data->ID); 
        $order_id = $data->get_id();

        $username  = smsalert_get_option('smsalert_name', 'smsalert_gateway');
        $password  = smsalert_get_option('smsalert_password', 'smsalert_gateway');
        $result    = SmsAlertcURLOTP::getTemplates($username, $password);
        $templates = (array)json_decode($result, true);
        $post_type = get_post_type($data);
        wp_enqueue_script('admin-smsalert-scripts', SA_MOV_URL . 'js/admin.js', array( 'jquery' ), SmsAlertConstants::SA_VERSION, true);

        wp_localize_script(
            'admin-smsalert-scripts',
            'smsalert',
            array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );

        if ('shop_order' !== $post_type  && 'post' !== $post_type) {
            echo '<style>.inside{position:relative}.woocommerce-help-tip{color:#666;display:inline-block;font-size:1.1em;font-style:normal;height:16px;line-height:16px;position:relative;vertical-align:middle;width:16px}.woocommerce-help-tip::after{font-family:Dashicons;speak:none;font-weight:400;font-variant:normal;text-transform:none;line-height:1;-webkit-font-smoothing:antialiased;margin:0;text-indent:0;position:absolute;top:0;left:0;width:100%;height:100%;text-align:center;content:"";cursor:help}</style>';

            echo '<div id="wc_sms_alert_send_sms_meta_box" class="postbox ">
				<h2 class="hndle ui-sortable-handle"><span style="font-size: 22px;">SMS Alert (Custom SMS)</span></h2>
					<div class="inside">';
        }
        ?>
                        <select name="smsalert_templates" id="smsalert_templates" style="width:87%;" onchange="return selecttemplate(this, '#wc_sms_alert_sms_order_message');">
                        <option value=""><?php esc_html_e('Select Template', 'sms-alert'); ?></option>
        <?php
        if (!empty($templates['description']) && is_array($templates['description']) && ( ! array_key_exists('desc', $templates['description']) ) ) {
            foreach ( $templates['description'] as $template ) {
                ?>
                        <option value="<?php echo esc_textarea($template['Smstemplate']['template']); ?>"><?php echo esc_attr($template['Smstemplate']['title']); ?></option>
                <?php
            }
        }
        ?>
                        </select>
                        <span class="woocommerce-help-tip" data-tip="You can add templates from your www.smsalert.co.in Dashboard" title="You can add templates&#13&#10from your&#13&#10www.smsalert.co.in Dashboard"></span>
                        <p><textarea type="text" name="wc_sms_alert_sms_order_message" id="wc_sms_alert_sms_order_message" class="input-text token-area" style="width: 100%;margin-top: 15px;" rows="4" value=""></textarea></p>
                        <div id="menu_custom" class="sa-menu-token" role="listbox"></div>
                        <input type="hidden" class="wc_sms_alert_order_id" id="wc_sms_alert_order_id" value="<?php echo esc_attr($order_id); ?>" >
                        <p><a class="button tips" id="wc_sms_alert_sms_order_send_message" data-tip="<?php esc_html_e('Send an SMS to the billing phone number for this order.', 'sms-alert'); ?>"><?php esc_html_e('Send SMS', 'sms-alert'); ?></a>
                        <span id="wc_sms_alert_sms_order_message_char_count" style="color: green; float: right; font-size: 16px;">0</span></p>
                        <div id="custom_token_list" style="display:none"></div>
        <?php
        if ('shop_order' !== $post_type && 'post' !== $post_type) {
            echo '</div></div>';
        }
        ?>
        <script>
        jQuery(document).ready(function(){
                    custom_sms_token('<?php echo esc_attr($order_id); ?>');
                });
        </script>
        <?php
    }

    /**
     * 
     * Gets order item meta.
     *
     * @param object $item Order item.
     * @param string $code meta key.
     *
     * @return void
     */
    public static function saWcGetOrderItemMeta( $item, $code )
    {
        $code      = str_replace('__', ' ', $code);
        $item_data = $item->get_data();

        foreach ( $item_data as $i_key => $i_val ) {

            if ($i_key === $code ) {
                $val = $i_val;
                break;
            } else {
                if ('meta_data' === $i_key ) {
                    $item_meta_data = $item->get_meta_data();
                    foreach ( $item_meta_data as $mkey => $meta ) {
                        if ($code === $mkey ) {
                            $meta_value = $meta->get_data();
                            $temp       = maybe_unserialize($meta_value['value']);
                            if (is_array($temp) ) {
                                $val = $temp;
                                break;
                            } else {
                                $val = $meta_value['value'];
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $val;
    }

    /**
     * 
     * Change keys recursively.
     *
     * @param object $arr array.
     * @param string $set key.
     *
     * @return void
     */
    public static function recursiveChangeKey( $arr, $set = '' )
    {
        if (is_numeric($set) ) {
            $set = '';
        }
        if (! empty($set) ) {
            $set = $set . '.';
        }
        if (is_array($arr) ) {
            $new_arr = array();
            foreach ( $arr as $k => $v ) {
                $new_arr[ $set . $k ] = is_array($v) ? self::recursiveChangeKey($v, $set . $k) : $v;
            }
            return $new_arr;
        }
        return $arr;
    }

    /**
     * Sa parse orderItem data.
     * attributes can be used : order_id,name,product_id,variation_id,quantity,tax_class,subtotal,subtotal_tax,total,total_tax.
     * properties : list="2" , format="%s,$d".
     * [orderitem list='2' name product_id quantity subtotal].
     *
     * @param array  $order_items array of order items.
     * @param string $content     Content.
     *
     * @return void
     */
    public static function saParseOrderItemData( $order_items, $content )
    {

        $pattern = get_shortcode_regex();

        preg_match_all('/\[orderitem(.*?)\]/', $content, $matches);
        $current_var_type = 'line_item';
        if (empty($matches[0]) ) {
            $current_var_type = 'shipping';
            preg_match_all('/\[shippingitem(.*?)\]/', $content, $matches);
        }

        $shortcode_tags = $matches[0];
        $parsed_codes   = array();
        foreach ( $shortcode_tags as $tag ) {
            $r_tag                = preg_replace('/\[|\]+/', '', $tag);
            $parsed_codes[ $tag ] = shortcode_parse_atts($r_tag);
        }

        $r_text       = '';
        $replaced_arr = array();

        foreach ( $parsed_codes as $token => &$parsed_code ) {
            $replace_text = '';
            $item_iterate = ( ! empty($parsed_code['list']) && $parsed_code['list'] > 0 ) ? (int) $parsed_code['list'] : 0;
            $format       = ( ! empty($parsed_code['format']) ) ? $parsed_code['format'] : '';
            $eq_index     = ( isset($parsed_code['eq']) ) ? (string) $parsed_code['eq'] : '';

            $prop = array();
            $tmp  = array();
            foreach ( $parsed_code as $kcode => $code ) {
                if (! in_array($kcode, array( 'orderitem', 'shippingitem', 'list', 'format', 'eq' ), true) ) {
                    $parts = array();
                    if (strpos($code, '.') !== false ) {
                        $parts = explode('.', $code);
                        $code  = array_shift($parts);
                    }

                    $sno = 0;

                    if (! empty($eq_index) && $eq_index > -1 ) {
                        $tmp_array    = array_keys($order_items);
                        $specific_key = $tmp_array[ $eq_index ];
                        if (array_key_exists($specific_key, $order_items) ) {
                            $temp_item                    = $order_items[ $specific_key ];
                            $order_items                  = array();
                            $order_items[ $specific_key ] = $temp_item;
                        }
                    }

                    foreach ( $order_items as $item_id => $item ) {
                        if ($item->get_type() === $current_var_type ) {
                            if (( $item_iterate > 0 ) && ( $sno >= $item_iterate ) ) {
                                break;
                            }

                            $tmp_code = str_replace('__', ' ', $code);

                            $attr_val = ( ! empty($item[ $tmp_code ]) ) ? $item[ $tmp_code ] : self::saWcGetOrderItemMeta($item, $code);

                            if (! empty($attr_val) ) {

                                if (! empty($parts) ) {
                                    $attr_val = self::getRecursiveVal($parts, $attr_val);
                                    $attr_val = is_array($attr_val) ? 'Array' : $attr_val;
                                }

                                if (! empty($format) ) {
                                    $prop[] = $attr_val;
                                } else {

                                    $tmp[] = $attr_val;
                                }
                            }
                            $sno++;
                        }
                    }
                }
            }

            if (! empty($format) ) {
                $tmp[] = vsprintf($format, $prop);
            }

            $replaced_arr[ $token ] = implode(', ', $tmp);
        }
        return str_replace(array_keys($replaced_arr), array_values($replaced_arr), $content);
    }
    
    
    /**
     * Sa parse orderVariable data.
     *
     * @param object $order_variables order_variables.
     * @param object $content         content.
     *
     * @return void
     */
    public static function saParseOrderVariableData($order_variables, $content)
    {
    
        foreach ( $order_variables as $meta_key => &$value ) {
            $temp = maybe_unserialize($value[0]);

            if (is_array($temp) ) {
                $variables[ $meta_key ] = $temp;
            } else {
                $variables[ $meta_key ] = $value[0];
            }
        }
        foreach ($variables as $key => $val) {
            if (gettype($val) == 'string') {
                
                $replaced_arr[ $key ] = $val;
            } elseif ($key == '_wc_shipment_tracking_items') {
                foreach ($val[0] as $k => $v) {
                    $replaced_arr[ $k ] = $v;
                }
            }            
        }
        
        preg_match_all('/\[_wc_shipment_tracking_items(.*?)\]/', $content, $matches);
        
        $shortcode_tags = $matches[0];
        $parsed_codes   = array();
        foreach ( $shortcode_tags as $tag ) {
            $r_tag                = preg_replace('/\[|\]+/', '', $tag);
            $parsed_codes[ $tag ] = shortcode_parse_atts($r_tag);
        }
        foreach ( $parsed_codes as $token => &$parsed_code ) {
            
            foreach ( $parsed_code as $kcode => $code ) {
                $parts = array();
                if (strpos($code, '.') !== false ) {
                    $parts = explode('.', $code);
                    $code  = array_shift($parts);
                }
            
                $find      = array_shift($parsed_code);        
                $content = str_replace('['.$find.']', $replaced_arr[$parts[1]], $content);
            }
        }
        $replace_keys = array_map(
            function ($k) {
                return '['.$k.']';
            }, array_keys($replaced_arr)
        );
        return str_replace($replace_keys, array_values($replaced_arr), $content);
    }
	
	/**
     * Sa parse order variable data.
     *
     * @param object $order_variables order_variables.
     * @param object $content         content.
     *
     * @return void
     */
    public static function saParseWcOrderVariableData($order_variables, $content)
    {
        foreach ( $order_variables as $meta_key => $value ) {
            if (is_array($value) ) {
				foreach($value as $key=>$val)
				{
					$variables[ $meta_key.'_'.$key ] = $val;
				}
            } else {
                $variables[ $meta_key ] = $value;
				if($meta_key == 'currency')
				{
					$variables[ 'order_currency' ] = $value;
				}
            }
        }
        foreach ($variables as $key => $val) {
            if (gettype($val) == 'string') {
                
                $replaced_arr[ $key ] = $val;
            } elseif ($key == '_wc_shipment_tracking_items') {
                foreach ($val[0] as $k => $v) {
                    $replaced_arr[ $k ] = $v;
                }
            }            
        }
        
        preg_match_all('/\[_wc_shipment_tracking_items(.*?)\]/', $content, $matches);
        
        $shortcode_tags = $matches[0];
        $parsed_codes   = array();
        foreach ( $shortcode_tags as $tag ) {
            $r_tag                = preg_replace('/\[|\]+/', '', $tag);
            $parsed_codes[ $tag ] = shortcode_parse_atts($r_tag);
        }
        foreach ( $parsed_codes as $token => &$parsed_code ) {
            
            foreach ( $parsed_code as $kcode => $code ) {
                $parts = array();
                if (strpos($code, '.') !== false ) {
                    $parts = explode('.', $code);
                    $code  = array_shift($parts);
                }
            
                $find      = array_shift($parsed_code);        
                $content = str_replace('['.$find.']', $replaced_arr[$parts[1]], $content);
            }
        }
        $replace_keys = array_map(
            function ($k) {
                return '['.$k.']';
            }, array_keys($replaced_arr)
        );
        return str_replace($replace_keys, array_values($replaced_arr), $content);
    }
    

    /**
     * 
     * Gets key value recursively from array.
     *
     * @param object $array array.
     * @param string $attr  attr.
     *
     * @return void
     */
    public static function getRecursiveVal( $array, $attr )
    {
        foreach ( $array as $part ) {
            if (is_array($part) ) {
                $attr = self::getRecursiveVal($part, $attr);
            } else {
                $attr = ( ! empty($attr[ $part ]) ) ? $attr[ $part ] : '';
            }
        }
        return $attr;
    }

    /**
     * 
     * This method is executed after order is placed.
     *
     * @param int    $order_id   Order id.
     * @param string $old_status Old Order status.
     * @param string $new_status New order status.
     *
     * @return void
     */
    public static function trigger_after_order_place( $order_id, $old_status, $new_status )
    {

        if (! $order_id ) {
            return;
        }

        $order          = wc_get_order($order_id);
        $admin_sms_data = array();
        $buyer_sms_data = array();

        $order_status_settings = smsalert_get_option('order_status', 'smsalert_general', array());
        $admin_phone_number    = smsalert_get_option('sms_admin_phone', 'smsalert_message', '');
        $admin_phone_number    = str_replace('postauthor', 'post_author', $admin_phone_number);

        if (count($order_status_settings) < 0 ) {
            return;
        }

        if (in_array($new_status, $order_status_settings, true) && ( 0 === $order->get_parent_id() ) ) {
            $default_buyer_sms = defined('SmsAlertMessages::DEFAULT_BUYER_SMS_' . str_replace(' ', '_', strtoupper($new_status))) ? constant('SmsAlertMessages::DEFAULT_BUYER_SMS_' . str_replace(' ', '_', strtoupper($new_status))) : SmsAlertMessages::showMessage('DEFAULT_BUYER_SMS_STATUS_CHANGED');

            $buyer_sms_body             = smsalert_get_option('sms_body_' . $new_status, 'smsalert_message', $default_buyer_sms);
			if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
              $buyer_sms_data['number']   = get_post_meta( $order_id, '_billing_phone', true );
			} else {
              $buyer_sms_data['number']   = !empty($order->get_meta('_billing_phone'))?$order->get_meta('_billing_phone'):$order->get_meta('_shipping_phone');
            }
            $buyer_sms_data['sms_body'] = $buyer_sms_body;

            $buyer_sms_data = apply_filters('sa_wc_order_sms_customer_before_send', $buyer_sms_data, $order_id);
            $buyer_response = SmsAlertcURLOTP::sendsms($buyer_sms_data);
            $response       = json_decode($buyer_response, true);

            if ('success' === $response['status'] ) {
                $order->add_order_note(__('SMS Send to buyer Successfully.', 'sms-alert'));
            } else {
                if (isset($response['description']) && is_array($response['description']) && array_key_exists('desc', $response['description']) ) {
                    $order->add_order_note($response['description']['desc']);
                } else {
                    $order->add_order_note($response['description']);
                }
            }
        }

        if (smsalert_get_option('admin_notification_' . $new_status, 'smsalert_general', 'on') === 'on' && ! empty($admin_phone_number) ) {
            // send sms to post author.
            $has_sub_order = metadata_exists('post', $order_id, 'has_sub_order');
            if (( strpos($admin_phone_number, 'post_author') !== false ) 
                && ( ( 0 !== $order->get_parent_id() ) || ( ( 0 === $order->get_parent_id() ) && empty($has_sub_order) ) ) 
            ) {
                $order_items = $order->get_items();
                $first_item  = current($order_items);
                $prod_id     = $first_item['product_id'];
                $product     = wc_get_product($prod_id);
                $author_no   = apply_filters('sa_post_author_no', $prod_id);

                if (0 === $order->get_parent_id() ) {
                    $admin_phone_number = str_replace('post_author', $author_no, $admin_phone_number);
                } else {
                    $admin_phone_number = $author_no;
                }
            }
            if (( strpos($admin_phone_number, 'store_manager') !== false ) && ( ( 0 === $order->get_parent_id() ) && empty($has_sub_order) ) ) {

                $author_no = apply_filters('sa_store_manager_no', $order);

                $admin_phone_number = str_replace('store_manager', $author_no, $admin_phone_number);
            }

            $default_template = SmsAlertMessages::showMessage('DEFAULT_ADMIN_SMS_' . str_replace('-', '_', strtoupper($new_status)));

            $default_admin_sms = ( ( ! empty($default_template) ) ? $default_template : SmsAlertMessages::showMessage('DEFAULT_ADMIN_SMS_STATUS_CHANGED') );

            $admin_sms_body             = smsalert_get_option('admin_sms_body_' . $new_status, 'smsalert_message', $default_admin_sms);
            $admin_sms_data['number']   = $admin_phone_number;
            $admin_sms_data['sms_body'] = $admin_sms_body;

            $admin_sms_data = apply_filters('sa_wc_order_sms_admin_before_send', $admin_sms_data, $order_id);

            $admin_response = SmsAlertcURLOTP::sendsms($admin_sms_data);
            $response       = json_decode($admin_response, true);
            if ('success' === $response['status'] ) {
                $order->add_order_note(__('SMS Sent Successfully.', 'sms-alert'));
            } else {
                if (is_array($response['description']) && array_key_exists('desc', $response['description']) ) {
                    $order->add_order_note($response['description']['desc']);
                } else {
                    $order->add_order_note($response['description']);
                }
            }
        }
    }

    /**
     * 
     * Gets variables.
     *
     * @param string $status Order status.
     *
     * @return void
     */
    public static function getvariables( $status = null )
    {
        $variables = self::getOrderVariables();
        if (in_array($status, array( 'pending', 'failed' ), true) ) {
            $variables = array_merge(
                $variables,
                array(
                '[order_pay_url]' => 'Order Pay URL',
                )
            );
        }

        $variables = apply_filters('sa_wc_variables', $variables, $status);
        return $variables;
    }

    /**
     * 
     * Gets order details for post orver verification.
     *
     * @param object $order Order object.
     *
     * @return void
     */
    public function orderDetailsAfterPostOrderOtp( $order )
    {
        if ($this->guest_check_out_only && is_user_logged_in() ) {
            return;
        }
        $order_id = $order->get_id();
        if (! $order_id ) {
            return;
        }
		if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
          $post_verification = get_post_meta($order_id, '_smsalert_post_order_verification', true);
        } else {
			$post_verification  = $order->get_meta('_smsalert_post_order_verification'); 
		}
        if (!$post_verification && is_wc_endpoint_url('view-order') && ( 'processing' === $order->get_status() ) ) {
            $this->sendPostOrderOtp('', $order);
        }
    }

    /**
     * 
     * Gets order details for post orver verification.
     *
     * @param string $title title.
     * @param object $order Order object.
     *
     * @return void
     */
    public function sendPostOrderOtp( $title = null, $order = array() )
    {
        $order_id                = $order->get_id();
        $post_order_verification = smsalert_get_option('post_order_verification', 'smsalert_general');

        wp_localize_script(
            'wccheckout',
            'otp_for_selected_gateways',
            array(
            'is_thank_you' => true,
            'post_verify'  => ( ( 'on' === smsalert_get_option('post_order_verification', 'smsalert_general') ) ? true : false ),

            )
        );
		
		$billing_phone       = $order->get_billing_phone();
        if( !SmsAlertcURLOTP::validateCountryCode($billing_phone)){
			return;
		}

        $verified = false;
        if ('on' !== $post_order_verification ) {
            return;
        }
        if ($this->guest_check_out_only && is_user_logged_in() ) {
            return;
        }
        if (! $order_id ) {
            return;
        }

        if (! $this->isPaymentVerificationNeeded($order->get_payment_method()) ) {
            return;
        }
        if ( version_compare( WC_VERSION, '7.1', '<' ) ) {
          $post_verification = get_post_meta($order_id, '_smsalert_post_order_verification', true);
        } else {
			$post_verification  = $order->get_meta('_smsalert_post_order_verification'); 
		}
        if ( !$post_verification ) {
            $billing_phone       = $order->get_billing_phone();
            $otp_verify_btn_text = smsalert_get_option('otp_verify_btn_text', 'smsalert_general', '');

            echo "<div class='post_verification_section'><p>Your order has been placed but your mobile number is not verified yet. Please verify your mobile number.</p>";

            echo "<form class='woocommerce-form woocommerce-post-checkout-form' method='post'>";
            echo "<p class='woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide' style='display:none;'>";
            echo "<input type='hidden' name='billing_phone' class='sa-phone-field' value=" . esc_attr($billing_phone) . '>';
            echo "<input type='hidden' name='billing_email' >";
            echo "<input type='hidden' name='o_id' value='" . esc_attr($order_id) . "'>";
            echo '</p>';
            $this->showValidationButtonOrText(true);
            echo '</form>';
            echo do_shortcode('[sa_verify id="form1" phone_selector=".sa-phone-field" submit_selector= "#smsalert_otp_token_submit" ]');
            echo '<script>';
            echo 'jQuery(".woocommerce-thankyou-order-received").hide();';
            echo '</script>';
            echo '</div>';
            echo '<style>.post_verification_section{padding: 1em 1.618em;border: 1px solid #f2f2f2;background: #fff;box-shadow: 10px 5px 5px -6px #ccc;}</style>';
        } else {
            return __('Thank you, Your mobile number has been verified successfully.', 'sms-alert');
        }
    }
}
new WooCommerceCheckOutForm();
?>
<?php
 /**
  * PHP version 5
  *
  * @category Handler
  * @package  SMSAlert
  * @author   SMS Alert <support@cozyvision.com>
  * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
  * @link     https://www.smsalert.co.in/
  * Sa_all_order_variable class
  */
class sa_all_order_variable
{

    /**
     * 
     * Constructor for class.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('woocommerce_after_register_post_type', array( $this, 'routeData' ), 10, 1);
    }

    /**
     * 
     * Routes data.
     *
     * @return void
     */
    public function routeData()
    {
        $order_id = isset($_REQUEST['order_id']) ? sanitize_text_field(wp_unslash($_REQUEST['order_id'])) : '';
        $option   = isset($_REQUEST['option']) ? sanitize_text_field(wp_unslash($_REQUEST['option'])) : '';

        if (! empty($option) && ( 'fetch-order-variable' === sanitize_text_field($option) ) && ! empty($order_id) ) {
            $tokens = array();

            global $woocommerce, $post;

            $order = new WC_Order($order_id);

            $order_variables = get_post_custom($order_id);

            $variables = array();
            foreach ( $order_variables as $meta_key => &$value ) {
                $temp = maybe_unserialize($value[0]);

                if (is_array($temp) ) {
                    $variables[ $meta_key ] = $temp;
                } else {
                    $variables[ $meta_key ] = $value[0];
                }
            }
            $variables['order_status'] = $order->get_status();
            $variables['order_date']   = $order->get_date_created();
            $tokens['Order details']   = $variables;

            $item_variables = array();
            foreach ( $order->get_items(array( 'line_item', 'shipping' )) as $item_key => $item ) {
                $item_data = $item->get_data();
                $item_type = ( 'shipping' === $item->get_type() ) ? 'shippingitem' : 'orderitem';

                $tmp1 = array();
                foreach ( $item_data as $i_key => $i_val ) {
                    if ('meta_data' === $i_key ) {
                        $item_meta_data = $item->get_meta_data();
                        foreach ( $item_meta_data as $mkey => $meta ) {

                            $meta_value = $meta->get_data();
                            $temp       = maybe_unserialize($meta_value['value']);

                            if (is_array($temp) ) {
                                $tmp1[ "$item_type " . $meta_value['key'] ] = $temp;
                            } else {
                                $tmp1[ "$item_type " . str_replace(' ', '__', $meta_value['key']) ] = $meta_value['value'];
                            }
                        }
                    } else {
                        $tmp1[ "$item_type " . $i_key ] = $i_val;
                    }
                }
                $item_variables[] = $tmp1;
            }
            $item_variables = WooCommerceCheckOutForm::recursiveChangeKey($item_variables);

            $tokens['Order details']['Order Items'] = $item_variables;
            wp_send_json($tokens);
            exit();
        }
    }
}
new sa_all_order_variable();

/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * SA_CodTOPrepaid class
 */
class SA_CodTOPrepaid
{
    /**
     * Construct function.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('sa_addTabs', array( $this, 'addTabs' ), 10);
        add_action('sa_tabContent', array( $this, 'tabContent' ), 1);
        add_filter('sAlertDefaultSettings', array( $this, 'addDefaultSetting' ), 1);
        $notification_enabled = smsalert_get_option('customer_notify', 'smsalert_cod_to_prepaid', 'off');
        if ('on' === $notification_enabled) {
            add_action('cod_to_prepaid_cart_notification_sendsms_hook', array( $this, 'sendSms' ), 10);
            add_filter('woocommerce_valid_order_statuses_for_payment', array( $this,'filterWoocommerceValidOrderStatusesForPayment'), 10, 2);
            add_action('admin_notices', array($this, 'displayWpCronWarnings'), 10); 
        }
        
    }
    
    /**
     * FilterWoocommerceValidOrderStatusesForPayment
     *
     * @param array  $array    array.
     * @param string $instance instance.
     *
     * @return void
     */
    function filterWoocommerceValidOrderStatusesForPayment( $array, $instance )
    {
        $order_status     = str_replace('wc-', '', smsalert_get_option('order_status', 'smsalert_cod_to_prepaid', ""));
        
        if ('' === $order_status) {
            return $array;
        }
        return array_unique(array_merge($array, array($order_status)));
    }
    
    /**
     * Add default settings to savesetting in setting-options.
     *
     * @param array $defaults defaults.
     *
     * @return array
     */
    public function addDefaultSetting( $defaults = array() )
    {
        
        $this->payment_methods           = maybe_unserialize(smsalert_get_option('checkout_payment_plans', 'smsalert_cod_to_prepaid'));
        $this->otp_for_selected_gateways = ( smsalert_get_option('otp_for_selected_gateways', 'smsalert_cod_to_prepaid') === 'on' ) ? true : false;         
        $defaults['smsalert_cod_to_prepaid']['order_status']                   = 'Processing';
        $defaults['smsalert_cod_to_prepaid']['notification_frequency']         = '10';
        $defaults['smsalert_cod_to_prepaid']['customer_notify']                = 'off';
        $defaults['smsalert_cod_to_prepaid_scheduler']['cron'][0]['frequency'] = '60';
        $defaults['smsalert_cod_to_prepaid_scheduler']['cron'][0]['message']   = '';
        $defaults['smsalert_cod_to_prepaid_scheduler']['cron'][1]['frequency'] = '120';
        $defaults['smsalert_cod_to_prepaid_scheduler']['cron'][1]['message']   = '';

        return $defaults;
    }

    /**
     * Add tabs to smsalert settings at backend.
     *
     * @param array $tabs tabs.
     *
     * @return array
     */
    public function addTabs( $tabs = array() )
    { 
        $smsalertcart_param = array(
        'checkTemplateFor' => 'Code_to_prepaid',
        'templates'        => $this->getSmsAlertCodTemplates(),
        );

        $tabs['woocommerce']['inner_nav']['code_to_prepaid']['title']       = 'COD To Prepaid';
        $tabs['woocommerce']['inner_nav']['code_to_prepaid']['tab_section'] = 'smsalertcarttemplates';
        $tabs['woocommerce']['inner_nav']['code_to_prepaid']['tabContent']  = $smsalertcart_param;
        $tabs['woocommerce']['inner_nav']['code_to_prepaid']['filePath']    = 'views/cod-to-prepaid-setting-template.php';
        $tabs['woocommerce']['inner_nav']['code_to_prepaid']['params']      = $smsalertcart_param;
        return $tabs;
    }

    /**
     * Get sms alert cod templates.
     *
     * @return array
     */
    public function getSmsAlertCodTemplates()
    {
        $current_val      = smsalert_get_option('customer_notify', 'smsalert_cod_to_prepaid', 'off');
        
        $checkbox_name_id = 'smsalert_cod_to_prepaid[customer_notify]';

        $scheduler_data = get_option('smsalert_cod_to_prepaid_scheduler');
        
        $templates      = array();
        $count          = 0;
        if (empty($scheduler_data) ) {
			$scheduler_data  = array();
            $scheduler_data['cron'][] = array(
            'frequency' => '60',
            'message'   => SmsAlertMessages::showMessage('DEFAULT_COD_PREPAID_CUSTOMER_MESSAGE'),  
            );
            $scheduler_data['cron'][] = array(
            'frequency' => '120',
            'message'   => SmsAlertMessages::showMessage('DEFAULT_COD_PREPAID_CUSTOMER_MESSAGE'),
            );            
        }

        foreach ( $scheduler_data['cron'] as $key => $data ) {
            $textarea_name_id = 'smsalert_cod_to_prepaid_scheduler[cron][' . $count . '][message]';
            
            $selectNameId     = 'smsalert_cod_to_prepaid_scheduler[cron][' . $count . '][frequency]';
            $text_body        = $data['message'];

            $templates[ $key ]['frequency']      = $data['frequency'];
            $templates[ $key ]['enabled']        = $current_val;
            $templates[ $key ]['title']          = 'Send message to customer when order is COD';
            $templates[ $key ]['checkboxNameId'] = $checkbox_name_id;
            $templates[ $key ]['text-body']      = $text_body;
            $templates[ $key ]['textareaNameId'] = $textarea_name_id;
            $templates[ $key ]['selectNameId']   = $selectNameId;
            
            $variables=WooCommerceCheckOutForm::getvariables();
            
            $variables['[order_pay_url]'] = "Order Pay Url";
            
            $templates[ $key ]['token']          = $variables;

            $count++;
        }
        return $templates;
    }
    
    
    /**
     * Send sms function.
     *
     * @return void
     */
    function sendSms()
    { 
        $order_statuses =    $order_status     = smsalert_get_option('order_status', 'smsalert_cod_to_prepaid', "processing");
        
        $payment_method     = smsalert_get_option('checkout_payment_plans', 'smsalert_cod_to_prepaid', "cod");
        $notification_enabled = smsalert_get_option('customer_notify', 'smsalert_cod_to_prepaid', 'off');
        if ('off' === $notification_enabled ) {
            return;
        }

        global $wpdb;
     
        $cron_frequency = CART_CRON_INTERVAL; // pick data from previous CART_CRON_INTERVAL min
       
        $scheduler_data = get_option('smsalert_cod_to_prepaid_scheduler');
        
        foreach ( $scheduler_data['cron'] as $sdata ) {
            
            $datetime = current_time('mysql');
            $fromdate = date('Y-m-d H:i:s', strtotime('-' . $sdata['frequency'] . ' minutes', strtotime($datetime)));
            $todate = date('Y-m-d H:i:s', strtotime('-' . ( $sdata['frequency'] + $cron_frequency ) . ' minutes', strtotime($datetime)));    
  
            $rows_to_phone = $wpdb->get_results('select * from wp_posts as p inner join wp_postmeta as pm1 on (p.ID = pm1.post_id) where (pm1.meta_key = "_payment_method" and pm1.meta_value = "'.$payment_method.'") and (p.post_status = "'.(strtolower($order_statuses)).'") AND (p.post_date >= "'.$todate.'" and p.post_date <="'.$fromdate.'")', ARRAY_A);

            if ($rows_to_phone ) { // If we have new rows in the database
            
                $customer_message = $sdata['message'];
                $frequency_time   = $sdata['frequency'];
                   
                if ('' !== $customer_message && 0 !== $frequency_time ) {
                    $obj = array();
                    foreach ( $rows_to_phone as $key=>$data ) {    
                    
                        $order_id = $data['ID'];
                        $order      =get_post_custom($order_id); 
                        
                        $buyerNumber   =  $order[ '_billing_phone'][0];
                        
                        
                        $sms_data['sms_body'] = $customer_message;
                        
                        if (!empty($buyerNumber) && !empty($sms_data['sms_body'])) {
                            
                               $buyerMessage  =  WooCommerceCheckOutForm::pharseSmsBody($sms_data, $order_id);
                                
                                
                               do_action('sa_send_sms', $buyerNumber, $buyerMessage['sms_body']);
                        }
                        
                    }
                    
                    
                }
            }
        }  
    }
    
    /**
     * Display wp cron warnings function.
     *
     * @return void
     */
    function displayWpCronWarnings()
    {
        global $pagenow;

        // Checking if we are on open plugin page
        if ('admin.php' === $pagenow && 'sms-alert' === sanitize_text_field($_GET['page']) ) {

            // Checking if WP Cron hooks are scheduled
            $missing_hooks = array();
            // $user_settings_notification_frequency = smsalert_get_option('customer_notify','smsalert_abandoned_cart');

            if (wp_next_scheduled('cod_to_prepaid_cart_notification_sendsms_hook') === false ) { // If we havent scheduled msg notifications and notifications have not been disabled
                $missing_hooks[] = 'cod_to_prepaid_cart_notification_sendsms_hook';
            }
            if (! empty($missing_hooks) ) { // If we have hooks that are not scheduled
                $hooks   = '';
                $current = 1;
                $total   = count($missing_hooks);
                foreach ( $missing_hooks as $missing_hook ) {
                    $hooks .= $missing_hook;
                    if ($current !== $total ) {
                        $hooks .= ', ';
                    }
                    $current++;
                }
                ?>
                <div class="warning notice updated">
                <?php
                echo sprintf(
                /* translators: %s - Cron event name */
                    _n('It seems that WP Cron event <strong>%s</strong> required for automation is not scheduled.', 'It seems that WP Cron events <strong>%s</strong> required for automation are not scheduled.', $total, 'sms-alert'),
                    $hooks
                );
                ?>
                <?php
                echo sprintf(
                /* translators: %1$s - Plugin name, %2$s - Link */
                    __('Please try disabling and enabling %1$s plugin. If this notice does not go away after that, please <a href="https://wordpress.org/support/plugin/sms-alert/" target="_blank">get in touch with us</a>.', 'sms-alert'),
                    SMSALERT_PLUGIN_NAME
                );
                ?>
                    </p>
                </div>
                <?php
            }

            // Checking if WP Cron is enabled
            if (defined('DISABLE_WP_CRON') ) {
                if (DISABLE_WP_CRON == true ) {
                    ?>
                    <div class="warning notice updated">
                        <p class="left-part"><?php esc_html_e('WP Cron has been disabled. Several WordPress core features, such as checking for updates or sending notifications utilize this function. Please enable it or contact your system administrator to help you with this.', 'sms-alert'); ?></p>
                    </div>
                    <?php
                }
            }
        }
    }
}
new SA_CodTOPrepaid();
if (! class_exists('WP_List_Table') ) {
    include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/ 
 * Class All_Order_List 
 */
class All_Order_List extends WP_List_Table
{

    /**
     * 
     * Class constructor
     *
     * @return void     
     */
    public function __construct()
    {
        parent::__construct(
            array(
            'singular' => 'allordervaribale',
            'plural'   => 'allordervariables',
            )
        );
    }

    /**
     * 
     * Get all subscriber info 
     *
     * @return void
     */
    public static function getAllOrder()
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}posts  WHERE post_type = 'shop_order' && post_status != 'auto-draft' ORDER BY post_date desc LIMIT 5";

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    /**
     * No items.
     *
     * @return void
     */
    public function no_items()
    {
        esc_html_e('No Order.', 'sms-alert');
    }

    /**
     * Column post checkbox.
     *
     * @param array $item        Item.
     * @param array $column_name Column Name.
     *
     * @return void
     */
    public function column_default( $item, $column_name )
    {
        return $item[ $column_name ];
    }

    /**
     * Column post checkbox.
     *
     * @param array $item Item.
     *
     * @return void
     */
    public function column_cb( $item )
    {
        return sprintf(
            '<input type="checkbox" name="ID[]" value="%s" />',
            $item['ID']
        );
    }

    /**
     * Column post status.
     *
     * @param array $item Item.
     *
     * @return void
     */
    public function column_post_status( $item )
    {
        $post_status = sprintf('<button class="button-primary"/>%s</a>', str_replace('wc-', '', $item['post_status']));
        return $post_status;
    }

    /**
     * Column post date.
     *
     * @param array $item Item.
     *
     * @return void
     */
    public function column_post_date( $item )
    {
        $date = date('d-m-Y', strtotime($item['post_date']));
        return $date;
    }

    /**
     * Get columns.
     *
     * @return void
     */
    public function get_columns()
    {
        $columns = array(
        'ID'          => __('Order'),
        'post_date'   => __('Date'),
        'post_status' => __('Status'),
        );

        return $columns;
    }

    /**
     * Prepare items.
     *
     * @return void
     */
    public function prepareItems()
    {

        $columns               = $this->get_columns();
        $this->items           = self::getAllOrder();
        $this->_column_headers = array( $columns );

        return $this->items;
    }
}

/**
 * Adds a sub menu page for all order variables.
 *
 * @return void
 */
function allArderVariableAdminMenu()
{
    add_submenu_page(null, 'All Order Variable', 'All Order Variable', 'manage_options', 'all-order-variable', 'all_order_variable_page_handler');
}

add_action('admin_menu', 'allArderVariableAdminMenu');

/**
 * All order variables page handler.
 *
 * @return void
 */
function all_order_variable_page_handler()
{
    global $wpdb;

    $table_data = new All_Order_List();
    $data       = $table_data->prepareItems();
    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2 class="title">Order List</h2>
    <form id="order-table" method="GET">
        <input type="hidden" name="page" value="<?php echo empty($_REQUEST['page']) ? '' : esc_attr($_REQUEST['page']); ?>"/>
    <?php $table_data->display(); ?>
    </form>
    <div id="sa_order_variable" class="sa_variables" style="display:none">
        <h3 class="h3-background">Select your variable <span id="order_id" class="alignright"><?php echo esc_attr($order_id); ?></span></h3>
        <ul id="order_list"></ul>
    </div>
</div>
<script>
jQuery(document).ready(function(){
    jQuery("tbody tr").addClass("order_click");

    jQuery(".order_click").click(function(){
        var id = jQuery(this).find(".ID").text().replace(/\D/g,'');
        jQuery("#order-table, .title").hide();
        jQuery("#sa_order_variable").show();
        jQuery("#order_id").html('Order Id: '+id);

        if (id != ''){
            jQuery.ajax({
                url         : "<?php echo esc_url(admin_url()); ?>?option=fetch-order-variable",
                data        : {order_id:id},
                dataType    : 'json',
                success: function(data)
                {
                    var arr1    = data;
                    var content1 = parseVariables(arr1);

                    jQuery('ul#order_list').html(content1);

                    jQuery("ul").prev("a").addClass("nested");

                    jQuery('ul#order_list, ul#order_item_list').css('textTransform', 'capitalize');

                    jQuery(".nested").parent("li").css({"list-style":"none"});

                    jQuery("ul#order_list li ul:first").show();
                    jQuery("ul#order_list").show();
                    jQuery("ul#order_list li a:first").addClass('nested-close');

                    toggleSubMenu();
                    addToken();
                },
                error:function (e,o){
                }
            });
        }

    });

     /**
     * ParseVariables.
     *
     * @param int $data data.
     * @param int $prefix       prefix.
     *
     * @return void
     */
    function parseVariables(data,prefix='')
    {
        text = '';
        jQuery.each(data,function(i,item){


            if (typeof item === 'object')
            {
                var nested_key = i.toString().replace(/_/g," ").replace(/orderitem/g,"");
                var key = i.toString().replace(/^_/i,"");



                if (nested_key != ''){
                    text+='<li><a href="#" value="['+key+']">'+nested_key+'</a><ul style="display:none">';
                    text+= parseVariables(item,prefix);
                    text+="</li></ul>";
                }
            } else {

                var j         = i.toString();
                var key     = i.toString().replace(/_/g," ").replace(/orderitem/g,"");
                var title     = item;
                var val     = j.toString().replace(/^_/i,"");


                text+='<li><a href="#" value="['+val+']" title="'+title+'">'+key+'</a></li>';
            }
        });
        return text;
    }
/**
     * ToggleSubMenu.
     *
     * @return void
     */
    function toggleSubMenu(){
        jQuery("a.nested").click(function(){
            jQuery(this).parent('li').find('ul:first').toggle();
            if (jQuery(this).hasClass("nested-close")){
                jQuery(this).removeClass("nested-close");
            } else {
                jQuery(this).addClass("nested-close");
            }
            return false;
        });
    }
/**
     * AddToken.
     *
     * @return void
     */
    function addToken(){
        jQuery('.sa_variables a').click( function() {
            if (jQuery(this).hasClass("nested")){
                return false;
            }
            var token = jQuery(this).attr('value');
            var datas = [];
            datas['token'] = token;
            datas['type'] = 'smsalert_token';
            window.parent.postMessage(datas, '*');
        });
    }
    return false;
});
</script>
<?php } ?>