<?php
/*
Plugin Name: WooCom SecurePay Payment Gateway
Plugin URI: https://hemthapa.com/product/woocommerce-securepay-payment-gateway-plugin/
Description: This lightweight plugin allows WooCommerce store owners to quickly process credit card payment via SecurePay payment gateway.
Author: Hem Thapa
Version: 1.8
Author URI: https://hemthapa.com/product/woocommerce-securepay-payment-gateway-plugin/
WC tested up to: 8.0.2
*/

add_action('plugins_loaded', 'woocommerce_securepay_payment_gateway_init');
add_action('wp_enqueue_scripts', 'securepay_scripts_enqueue');

function securepay_scripts_enqueue(){
    wp_enqueue_style( 'securepay-style', plugins_url( 'woocom_securepay.css', __FILE__ ) );
}

function woocommerce_securepay_payment_gateway_init(){

    if(!class_exists('WC_Payment_Gateway')){
        return;
    }

    class woocommerce_securepay_p_gateway extends WC_Payment_Gateway{

        public function __construct(){
            global $woocommerce;

            $this->id = 'woocommerce_securepay_p_gateway';
            $this->has_fields = true;
            $this->method_title = 'SecurePay payment gateway';
            $this->method_description = 'SecurePay payment description goes here.';

            $this->init_form_fields();
            $this->init_settings();

            // user setting variables
            $this->title = $this->settings['sTitle'];

            $this->sDevelopmentMode = $this->settings['sDevelopmentMode'];
            $this->sCholderName = $this->settings['sCholderName'];
            $this->sShowLogo = $this->settings['sShowLogo'];
            $this->sDescription = $this->settings['sDescription'];
            $this->sMerchantId = $this->settings['sMerchantId'];
            $this->sPassword = $this->settings['sPassword'];

            if($this->sShowLogo == 'yes'){
                $this->icon = plugins_url().'/woocom-securepay-payment-gateway/securepay_logo.png';
            }
            //add woocommerce payment option
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        }

        //load gateway setting options
        public function admin_options(){ ?>

            <h3><?php _e('SecurePay payment gateway options', 'woocommerce'); ?></h3>
            <p>
            <?php _e('<strong>Note:</strong> For test transactions, please use Merchant ID : <strong>ABC0001</strong> and Password: <strong>abc123</strong><br>

            <div style="background: #C8E6C9; padding: 15px; max-width:1000px;"><strong>FraudGuard settings</strong><br>This plugin doesn\'t support FraudGuard Settings. If FraudGuard setting is enabled on your SecurePay account to minimize the fraudulent transactions or you need an option for Pre Authorization Payments, please <a href="https://hemthapa.com/product/woocommerce-securepay-payment-gateway-plugin/?pl=securepay&ref='.$_SERVER['HTTP_HOST'].'" target="_blank">use this plugin</a> instead.</div>', 'woocommerce'); ?>

            </p>




            <table class="form-table">
                <?php $this->generate_settings_html(); ?>
            </table>
            <?php
        }

        //woocommerce user setting fields
        function init_form_fields(){

            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable SecurePay payment gateway', 'woocommerce'),
                    'default' => 'yes'
                ),
                'sTitle' => array(
                    'title' => __('Payment method name', 'woocommerce'),
                    'type' => 'text',
                    'placeholder' => 'For eg: Pay by Credit Card',
                    'description' => __('Customer will see this method name during checkout.', 'woocommerce'),
                    'default' => __('SecurePay payment', 'woocommerce')
                ),
                'sDescription' => array(
                    'title' => __('Checkout Description', 'woocommerce'),
                    'type' => 'textarea',
                    'placeholder' => 'For eg: Please enter your Credit Card details',
                    'description' => __('Customer will see this description along with payment method during checkout.', 'woocommerce'),
                    'default' => __("Enter your credit card details below", 'woocommerce')
                ),
                'sMerchantId' => array(
                    'title' => __('SecurePay Merchant ID', 'woocommerce'),
                    'type' => 'text',
                    'placeholder' => 'Eg: ABC0001',
                    'description' => __('Enter SecurePay merchant ID', 'woocommerce'),
                    'default' => ''
                ),
                'sPassword' => array(
                    'title' => __('API Transaction Password', 'woocommerce'),
                    'type' => 'password',
                    'placeholder' => '********',
                    'description' => __('API Transaction Password and your SecurePay user account password are different.<br>You can find the API transaction password from SecurePay portal under <u>Manage</u> > <u>API Transaction Password</u> menu.', 'woocommerce'),
                    'default' => ''
                ),
                'sShowLogo' => array(
                    'title' => __('SecurePay icon', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Show SecurePay icon on checkout page', 'woocommerce'),
                    'default' => 'yes'
                ),
                'sDevelopmentMode' => array(
                    'title' => __('Test environment', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable SecurePay test environment (Please untick for Live transaction)', 'woocommerce'),
                    'default' => 'yes'
                ),
                'sCholderName' => array(
                    'title' => __('Card holder name field', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable credit card holder name field', 'woocommerce'),
                    'default' => 'no'
                )
            );

        }

        function apiUrl($mode){

            if($mode == 'yes'){
                //Test url
                return 'https://test.securepay.com.au/xmlapi/payment';
            }else {
                //Live url
                return 'https://api.securepay.com.au/xmlapi/payment';
            }

        }

        function process_payment($order_id){

            global $woocommerce;
            $order = new WC_Order($order_id);

            $apiUrl = $this->apiUrl($this->sDevelopmentMode);

            $timeStamp = date("YdmHisB" . "000+660");

            $mId = time();
            $xmlRequest = '<?xml version="1.0" encoding="UTF-8"?>
            <SecurePayMessage>
                <MessageInfo>
                    <messageID>'.$mId.'</messageID>
                    <messageTimestamp>' . $timeStamp . '</messageTimestamp>
                    <timeoutValue>60</timeoutValue>
                    <apiVersion>xml-4.2</apiVersion>
                </MessageInfo>
                <MerchantInfo>
                    <merchantID>' . $this->sMerchantId . '</merchantID>
                    <password>' . $this->sPassword . '</password>
                </MerchantInfo>
                <RequestType>Payment</RequestType>
                <Payment>
                <TxnList count="1">
                    <Txn ID="1">
                    <txnType>0</txnType>
                    <txnSource>23</txnSource>
                    <amount>'.($order->get_total() * 100).'</amount>
                    <currency>'.get_option('woocommerce_currency').'</currency>
                    <purchaseOrderNo>'.$order_id.'</purchaseOrderNo>
                    <CreditCardInfo>
                        <cardNumber>'.$_POST["ccardNumber"].'</cardNumber>
                        <expiryDate>'.$_POST["exmonth"].'/'. $_POST["exyear"].'</expiryDate>
                        <cvv>'.$_POST["ccvv"].'</cvv>
                        <cardHolderName>'.$_POST["cardHolderName"].'</cardHolderName>
                    </CreditCardInfo>
                    </Txn>
                </TxnList>
                </Payment>
            </SecurePayMessage>';

            $sresponse = wp_remote_post($apiUrl, array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array('content-type' => 'text/xml'),
                'body' => $xmlRequest,
                'cookies' => array()
            )
            );


            if(!is_wp_error($sresponse) && $sresponse['response']['code'] >= 200 && $sresponse['response']['code'] < 300) {

                $apiResp = $sresponse['body'];

                $xml = simplexml_load_string($apiResp);

                if(isset($xml->Status->statusCode) && $xml->Status->statusCode != '000'){

                    $responsecode = $xml->Status->statusCode;
                    $responsetext = $xml->Status->statusDescription;

                }elseif(isset($xml->Payment->TxnList->Txn->approved)){

                    $responsecode = $xml->Payment->TxnList->Txn->responseCode;
                    $responsetext = $xml->Payment->TxnList->Txn->responseText;
                    $transactionId = $xml->Payment->TxnList->Txn->txnID;

                }else{
                    $responsecode = false;
                }

                $successResponseCode = array('00', '08', '11', '16', '77');
                if(in_array($responsecode, $successResponseCode) == true){

                    $order->add_order_note(sprintf(__('Payment is successfully completed. Transaction ID: %s.', 'woocommerce'), $transactionId));
                    $order->payment_complete();
                    $woocommerce->cart->empty_cart();
                    $redirectionUrl = $this->get_return_url($order);

                    return array(
                        'result' => 'success',
                        'redirect' => $redirectionUrl
                    );

                } else {
                    wc_add_notice(__('Payment can not be processed ', 'woocommerce').'(' . $responsetext.')', $notice_type = 'error');
                }

            } else {
                wc_add_notice(__('Payment Gateway Error.', 'woocommerce'), $notice_type = 'error' );
            }
        }

        function payment_fields()
        {

            if(!empty($this->sDescription)){
                echo wpautop(wptexturize($this->sDescription));
            }
            ?>

            <?php if($this->sCholderName == 'yes'){ ?>
            <p class="form-row form-row-wide">
                <label><?php echo __("Name on the Card", 'woocommerce'); ?>
                <span class="required">*</span>
                </label>
                <input class="input-text" type="text" name="cardHolderName"/>
            </p>
            <?php } ?>
            <div class="clear"></div>
            <p class="form-row form-row-wide">
                <label><?php echo __("Credit card Number", 'woocommerce') ?>
                <span class="required">*</span>
                </label>
                <input class="input-text" type="number" maxlength="20" name="ccardNumber"/>
            </p>
            <div class="clear"></div>

            <p class="form-row form-row-first">
            <label><?php echo __("Card Expiration Date", 'woocommerce') ?>
            <span class="required">*</span>
            </label>
            <select name="exyear" class="woocommerce-select woocommerce-cc-year">
                <option selected="true" disabled="disabled"><?php _e('Year', 'woocommerce') ?></option>

                <?php
                $currentYr = (int)date('y', time());
                $yearOptionList = (int)date('Y', time());

                for ($i=0; $i<10; $i++) { ?>
                    <option value="<?php echo $currentYr; ?>"><?php echo $yearOptionList; ?></option>
                    <?php $currentYr++;  $yearOptionList++;
                } ?>
            </select>
            <select name="exmonth" class="woocommerce-select woocommerce-cc-month">
                <option  selected="true" disabled="disabled"><?php _e('Month', 'woocommerce') ?></option>
                <option value=01> January</option>
                <option value=02> February</option>
                <option value=03> March</option>
                <option value=04> April</option>
                <option value=05> May</option>
                <option value=06> June</option>
                <option value=07> July</option>
                <option value=08> August</option>
                <option value=09> September</option>
                <option value=10> October</option>
                <option value=11> November</option>
                <option value=12> December</option>
            </select>
            </p>


            <div class="clear"></div>
            <p class="form-row form-row-wide">
                <label><?php echo __("Card CVV number", 'woocommerce') ?>
                <span class="required">*</span>
                </label>
                <input type="number" class="input-text" maxlength="5" name="ccvv"  style="width:100px;" />
            </p>
            <div class="clear"></div>
        <?php

        }

    }

    function woocommerce_add_securepay_payment_gateway($methods){
        $methods[] = 'woocommerce_securepay_p_gateway';
        return $methods;
    }


    add_filter('woocommerce_payment_gateways', 'woocommerce_add_securepay_payment_gateway');

}
