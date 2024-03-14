<?php
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly

class WC_Snappay_Gateway extends WC_Payment_Gateway {

    public function __construct() {

        array_push($this->supports,'refunds');

        $this->id = C_WC_SNAPPAY_ID;
        $this->icon =C_WC_SNAPPAY_URL. '/images/snappay.png';
        $this->has_fields = false;
        
        $this->method_title = 'Payment Gateway for Alipay, WeChat Pay and UnionPay (支付宝, 微信支付, 银联支付北美版)';
        $this->method_description='Alipay, Wechat Payment and UnionPay provided by <a href="http://www.snappay.ca" target="_blank">SnapPay Inc.</a>';
       
        $this->init_form_fields ();
        
        $this->title = $this->get_option ( 'title' );
        $this->description = $this->get_option ( 'description' );
        $this->merchantId = $this->get_option ( 'merchantId' );
        $this->signKey = $this->get_option ( 'signKey' );
        $this->appId = $this->get_option ( 'appId' );
        $this->currency = $this->get_option ( 'currency' );
        $this->enable_UnionPay = $this->get_option('enable_UnionPay');

        $this->logging = $this->get_option( 'logging' );
        if ( 'yes' == $this->logging ) {
            $this->log = new WC_Logger();
        }

        add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) ); // WC <= 1.6.6
        add_action( 'woocommerce_update_options_payment_gateways_'.C_WC_SNAPPAY_ID, array( $this, 'process_admin_options' ) ); // WC >= 2.0
        add_action( 'woocommerce_receipt_'.C_WC_SNAPPAY_ID, array($this, 'receipt_page'));
        add_action( 'woocommerce_api_wc_snappay_notify', array( $this, 'wc_snappay_notify' ) );
        add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'custom_payment_update_order_meta') );
        add_action( 'woocommerce_thankyou', array( $this, 'thankyou_page' ) );
    }

    //插件选项
    function init_form_fields() {

        $enabled = array (
            'title' => __ ( 'Enable/Disable 启用/禁用', C_WC_SNAPPAY_ID ),
            'type' => 'checkbox',
            'label' => __ ( 'Alipay, Wechat Pay and UnionPay (启用支付宝, 微信支付, 银联支付)', C_WC_SNAPPAY_ID ),
            'default' => 'no'
        );

        $enable_UnionPay = array (
            'title' => __ ( 'Enable/Disable 启用/禁用', C_WC_SNAPPAY_ID ),
            'type' => 'checkbox',
            'label' => __ ( 'UnionPay (银联支付)', C_WC_SNAPPAY_ID ),
            'default' => 'no');

        $title = array (
            'title' => __ ( 'Title 标题', C_WC_SNAPPAY_ID ),
            'type' => 'text',
            'description' => __ ( 'This is the payment method title the user will see during checkout.', C_WC_SNAPPAY_ID ),
            'default' => __ ( 'Alipay, WeChat Pay and UnionPay', C_WC_SNAPPAY_ID ),
            'css' => 'width:400px'
        );

        $description = array (
            'title' => __ ( 'Description 描述', C_WC_SNAPPAY_ID ),
            'type' => 'textarea',
            'description' => __ ( 'This controls the description the user sees during checkout.', C_WC_SNAPPAY_ID ),
            'default' => __ ( 'Pay using your Alipay App,WeChat App or UnionPay.', C_WC_SNAPPAY_ID ),
            'css' => 'width:400px'
        );

        $merchantId = array (
            'title' => __ ( 'Merchant ID', C_WC_SNAPPAY_ID ),
            'type' => 'text',
            'description' => __ ( 'Register your merchant account from <a href="https://mp.snappay.ca/web/login" target="_blank">here</a> with SnapPay. You can find the Merchant ID in the merchant backoffice. For more information, please refer to <a href="https://youtu.be/1o2-8KApocA" target="_blank">here</a>.', C_WC_SNAPPAY_ID ),
            'css' => 'width:400px',
            'default' => __ ( '', C_WC_SNAPPAY_ID )
        );

        $signKey = array (
            'title' => __ ( 'Sign Key', C_WC_SNAPPAY_ID ),
            'type' => 'text',
            'description' => __ ( 'Register your merchant account from <a href="https://mp.snappay.ca/web/login" target="_blank">here</a> with SnapPay. You can find MD5 Sign Key in the merchant backoffice. For more information, please refer to <a href="https://youtu.be/1o2-8KApocA" target="_blank">here</a>.', C_WC_SNAPPAY_ID ),
            'css' => 'width:400px',
            'default' => __ ( '', C_WC_SNAPPAY_ID )
        );

        $appId = array (
            'title' => __ ( 'APP ID', C_WC_SNAPPAY_ID ),
            'type' => 'text',
            'description' => __ ( 'Register your merchant account from <a href="https://mp.snappay.ca/web/login" target="_blank">here</a> with SnapPay. You can find the APP ID in the merchant backoffice. For more information, please refer to <a href="https://youtu.be/1o2-8KApocA" target="_blank">here</a>.', C_WC_SNAPPAY_ID ),
            'css' => 'width:400px',
            'default' => __ ( '', C_WC_SNAPPAY_ID )
        );

        $currency = array (
            'title' => __ ( 'Currency', C_WC_SNAPPAY_ID ),
            'type' => 'select',
            'description' => __ ( 'SnapPay supports Canadian Dollars (CAD) and US Dollars (USD). Merchants must choose the currency they used when creating the merchant account.', C_WC_SNAPPAY_ID ),
            'options' => array(
                'CAD' => 'CAD',
                'USD' => 'USD'
            ),
            'default' => 'CAD'
        );

        $logging = array(
            'title'       => __('Logging', C_WC_SNAPPAY_ID),
            'type'        => 'checkbox',
            'label'       => __('Log debug messages', C_WC_SNAPPAY_ID),
            'default'     => 'no',
            'description' => __('Log payment events, such as trade status, inside <code>wp-content/uploads/wc-logs/wcsnappaygateway*.log</code>', C_WC_SNAPPAY_ID)
        );


        $this->form_fields = array();
        $this->form_fields['enabled'] = $enabled;
        $this->form_fields['enable_UnionPay'] = $enable_UnionPay;
        $this->form_fields['title'] = $title;
        $this->form_fields['description'] = $description;
        $this->form_fields['merchantId'] = $merchantId;
        $this->form_fields['signKey'] = $signKey;
        $this->form_fields['appId'] = $appId;
        $this->form_fields['currency'] = $currency;
        $this->form_fields['logging'] = $logging;

    }

    //处理付款
    public function process_payment( $order_id ) {
        $this->logging("process_payment.order_id: ".$order_id );
        $order = new WC_Order( $order_id );

        return array (
            'result' => 'success',
            'redirect' => $order->get_checkout_payment_url ( true )
        );
    }

    public function receipt_page($order_id) {
        $order = new WC_Order($order_id);

        //get pay type customer choose, and set method and payment_method paramters
        $payType = get_post_meta( $order_id, 'payType', true );
        $method = 'pay.qrcodepay';
        $payment_method = 'WECHATPAY';
        $browser_type = '';
        if($payType === 'AlipayWeb'){
            $method = 'pay.webpay';
            $payment_method = 'ALIPAY';
            $browser_type = 'PC';
        }else if($payType === 'AlipayWap'){
            $method = 'pay.webpay';
            $payment_method = 'ALIPAY';
            $browser_type = 'WAP';
        }else if($payType === 'WeChatH5'){
            $method = 'pay.h5pay';
            $payment_method = 'WECHATPAY';
            $browser_type = '';
        }else if($payType === 'WeChatQR'){
            $method = 'pay.qrcodepay';
            $payment_method = 'WECHATPAY';
            $browser_type = '';
        }else if($payType === 'UnionPayWeb'){
            $method = 'pay.webpay';
            $payment_method = 'UNIONPAY';
            $browser_type = '';
        }

        $snappayOrderId = $this->generate_snappay_order_id($order_id);
        update_post_meta($order_id, 'snappayOrderId', $snappayOrderId);

        $metadata = array(
            'customer' => array(
                'firstName' => $order->get_billing_first_name(),
                'lastName' => $order->get_billing_last_name(),
                'email' => $order->get_billing_email()
            )
        );
        $metadata_json = json_encode($metadata);

        $shopName = str_replace(' ', '_', get_bloginfo('name'));
        $shopName = substr($shopName, 0, 128);

        $timestamp = date_format(date_create('',timezone_open("UTC")), 'Y-m-d H:i:s');
        //prepare request json object
        $post_data = array(
            'app_id' => $this->appId,
            'format' => 'JSON',
            'charset' => 'UTF-8',
            'sign_type' => 'MD5',
            'version' => '1.0',
            'timestamp' => $timestamp,

            'method' => $method,
            'merchant_no' => $this->merchantId,
            'payment_method' => $payment_method,
            'out_order_no' => $snappayOrderId,
            'trans_amount' => $order->get_total(),
            'notify_url' => get_site_url().'/?wc-api=wc_snappay_notify',
            'return_url' => $this->get_return_url( $order ),
            'description' => $shopName,
            'extension_parameters' => $metadata_json,
            'trans_currency' => $this->currency,
            'browser_type' => $browser_type
        );

        $post_data_sign = snappay_sign_post_data($post_data, $this->signKey);

        $data_json =  json_encode($post_data_sign);
        $this->logging('receipt_page.in: '.$data_json);
        $json = $this->do_post_request(C_WC_SNAPPAY_OPENAPI_HOST, $data_json);
        $this->logging('receipt_page.out: '.$json['body']);
        $ret = json_decode($json['body'], true);

        if($ret['code'] === '0'){
            // Reduce stock levels
            if ( function_exists( 'wc_reduce_stock_levels' ) ) { 
                wc_reduce_stock_levels($order_id); 
            } else {
                $order->reduce_order_stock();
            }

            if($payType === 'AlipayWeb' || $payType === 'AlipayWap' || $payType === 'UnionPayWeb'){
                $webpay_url = $ret['data'][0]['webpay_url'];
                $this->redirect($webpay_url);
            }if($payType === 'WeChatH5'){
                $h5pay_url = $ret['data'][0]['h5pay_url'];
                $this->redirect($h5pay_url);
            }else if($payType === 'WeChatQR'){
                $returnUrl = $this->get_return_url( $order );
                $qrcode_url = $ret['data'][0]['qrcode_url'];
            ?>
                <p>请使用微信”扫一扫”扫描下方二维码进行支付。Please scan the QR code using the Wechat App to complete payment.</p>
                <div>
                    <div style="display: inline-block; margin: 0;">
                         <style type="text/css">
                            .codestyle *{
                                display: block;
                            }
                        </style>
                        <div id="code" class="codestyle"></div>
                        <script type="text/javascript" src="<?php echo C_WC_SNAPPAY_URL ?>/js/jquery-3.4.1.min.js"></script> 
                        <script type="text/javascript" src="<?php echo C_WC_SNAPPAY_URL ?>/js/jquery-migrate-3.1.0.min.js"></script>
                        <script type="text/javascript" src="<?php echo C_WC_SNAPPAY_URL ?>/js/jquery.qrcode.min.js"></script> 
                        <script type="text/javascript">
                            $("#code").qrcode({ 
                                width: 280,
                                height:280,
                                text: "<?php echo $qrcode_url ?>"
                            }); 
                        </script> 
                        <img style="display: block;" src="<?php echo C_WC_SNAPPAY_URL ?>/images/wechat_webscan01.png" />
                    </div>
                    <div style="display: inline-block;  margin: 0;">
                        <img style="display: block;" src="<?php echo C_WC_SNAPPAY_URL ?>/images/wechat_webscan02.png" />
                    </div>
                </div>

                <script>
                  jQuery(document).ready(function() {

                        jQuery(document).on('heartbeat-send', function(event, data) {
                            console.log('orderId: ' + '<?php echo $order_id ?>');
                            data['orderId'] = '<?php echo $order_id ?>'; 
                        });

                        jQuery(document).on('heartbeat-tick', function(event, data) {
                            if(data['status']){
                                console.log('status: ' + data['status']);
                                if(data['status'] === 'SUCCESS'){
                                    window.location.replace('<?php echo $returnUrl ?>');
                                }
                            }
                        });

                        // set the heartbeat interval
                        wp.heartbeat.interval( 'fast' );
                    });     
                </script>
            <?php
            }
        }else{
            wc_add_notice( 'Payment error:'.$ret['msg'], 'error' );
            $order->update_status('failed', $ret['msg']);
            wp_safe_redirect( wc_get_page_permalink( 'checkout' ) );
        }

    }

    public function process_refund( $order_id, $amount = null, $reason = ''){
        $this->logging('process_refund.order_id: '.$order_id.'|'.$amount);

        $order = new WC_Order ( $order_id );
        if(!$order){
            return new WP_Error( 'invalid_order', 'Invalid Order ID' );
        }
        $snappayOrderId = get_post_meta( $order->get_id(), 'snappayOrderId', true );
        if(!$snappayOrderId){
            return new WP_Error( 'invalid_order', 'Invalid SnapPay Order ID' );
        }
        $timestamp = date_format(date_create('',timezone_open("UTC")), 'Y-m-d H:i:s');

        //prepare request json
        $post_data = array(
            'app_id' => $this->appId,
            'format' => 'JSON',
            'charset' => 'UTF-8',
            'version' => '1.0',
            'timestamp' => $timestamp,
            'sign_type' => 'MD5',

            'method' => 'pay.orderrefund',
            'refund_amount' => $amount,
            'out_refund_no' => $this->generate_snappay_order_id($order_id),
            'out_order_no' => $snappayOrderId,
            'merchant_no' => $this->merchantId
        );
        $post_data_sign = snappay_sign_post_data($post_data, $this->signKey);

        $data_json =  json_encode($post_data_sign);
        $this->logging('process_refund.in: '.$data_json);
        $json = $this->do_post_request(C_WC_SNAPPAY_OPENAPI_HOST, $data_json);
        $this->logging('process_refund.out: '.$json['body']);
        $ret = json_decode($json['body'], true);

        if($ret['code'] === '0'){
            return true;
        }else{
            return new WP_Error( 'invalid_order', $ret['msg']);
        }
    }

    public function wc_snappay_notify() {
        $this->logging('wc_snappay_notify....');
        global $woocommerce;

        //get json request notify from snappay
        $json_data = file_get_contents("php://input");
        $this->logging( 'wc_snappay_notify.json_data: '.$json_data );
        $json_obj = json_decode($json_data, true);
        //sign verify
        if(snappay_sign_verify($json_obj, $this->signKey)){
            if($json_obj['trans_status'] === 'SUCCESS'){
                $merchantId = sanitize_text_field($json_obj['merchant_no']);
                $snappayOrderId = sanitize_text_field($json_obj['out_order_no']);

                $this->logging( 'wc_snappay_notify.merchantId|snappayOrderId: '.$merchantId.'|'.$snappayOrderId );

                if ( isset( $merchantId ) && $merchantId == $this->merchantId ) {
                    if ( empty($snappayOrderId) ){
                        wp_die( 'Invalid SnapPay Order ID' );
                    }
                    //get wordpress order
                    $order_id = $this->get_wp_order_id($snappayOrderId);
                    $order = new WC_Order( $order_id );
                    //change order status
                    if ( $order->get_status() != 'completed' || $order->get_status() != 'processing' ) {
                        $order->payment_complete();
                        // clear cart
                        $woocommerce->cart->empty_cart();
                    }
                } else {
                    wp_die( 'SnapPay Notification Request Failure' );
                }

                //return success code
                $return_data = array(
                    'code' => '0'
                );
                $json_str = json_encode( $return_data );
                $this->redirect(C_WC_SNAPPAY_URL.'/snappaynotifyresponse.php');
            }
        } else {
            wp_die('Illegal sign');
        }
    }

    public function custom_payment_update_order_meta( $order_id ) {
        if($_POST['payment_method'] != C_WC_SNAPPAY_ID){
            return;
        }
        $payType = sanitize_text_field($_POST['payType']);
        update_post_meta( $order_id, 'payType', $payType );
    }

    //heartbeat call this method to check order status
    public function is_order_completed($order_id){
        $this->logging("is_order_completed.orderId: " . $order_id);
        global $woocommerce;
        $order = new WC_Order( $order_id );
        $this->logging("is_order_completed.orderStauts: " . $order->get_status());
        $isCompleted = false;
        //call wordpress first, if not complated call snappay api
        if($order->get_status() == 'completed' || $order->get_status() == 'processing' || $order->get_status() == 'refunded'){
            $isCompleted = true;
        } else {
            if($this->snappay_query_order_status($order_id) === 'SUCCESS'){
                $isCompleted = true;
                //change order status
                if ( $order->get_status() != 'completed' || $order->get_status() != 'processing' || $order->get_status() != 'refunded') {
                    $order->payment_complete();
                    // clear cart
                    $woocommerce->cart->empty_cart();
                }
            }
        }
        return $isCompleted;
    }

    //自定义支付表格
    public function payment_fields(){
        if ( $description = $this->get_description() ) {
            echo wpautop( wptexturize( $description ) );
        }
        $isMobile = $this->isMobile();
        $isWeChat = $this->isWeChat();
        ?>
        <div id="custom_input">
            <p class="form-row form-row-wide">
                <?php 
                    if($isMobile){
                        if($isWeChat){
                ?> 
                            <label>Unavailable(不可选) <img src="<?php echo C_WC_SNAPPAY_URL ?>/images/Alipay_logo.png" /></label>
                <?php 
                        }else{
                ?> 
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="payType" value="AlipayWap" checked /> &nbsp; Alipay <img src="<?php echo C_WC_SNAPPAY_URL ?>/images/Alipay_logo.png" /></label>
                <?php 
                        }
                    }else{
                ?> 
                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="payType" value="AlipayWeb" checked /> &nbsp; Alipay <img src="<?php echo C_WC_SNAPPAY_URL ?>/images/Alipay_logo.png" /></label>
                <?php
                    }
                ?> 

                <br/>

                <?php 
                    if($isMobile){
                        if($isWeChat){
                ?> 
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="payType" value="WeChatH5" checked /> &nbsp; WeChat Pay <img src="<?php echo C_WC_SNAPPAY_URL ?>/images/Wechat_logo.png" /></label>
                <?php 
                        }else{
                ?> 
                            <label>Unavailable(不可选) <img src="<?php echo C_WC_SNAPPAY_URL ?>/images/Wechat_logo.png" /></label>
                <?php 
                        }
                    }else{
                ?> 
                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="payType" value="WeChatQR" /> &nbsp; WeChat Pay <img src="<?php echo C_WC_SNAPPAY_URL ?>/images/Wechat_logo.png" /></label>
                <?php
                    }
                ?> 

                <br/>
                <?php
                if ($this->enable_UnionPay=='yes'){
                    ?>
                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="payType" value="UnionPayWeb" /> &nbsp; Union Pay <img src="<?php echo C_WC_SNAPPAY_URL ?>/images/unionpay_logo.png" /></label>
                    <?php
                }
                ?>
            </p>
        </div>
        <?php
    }

    //on thankyou page will check order status. May call snappay api to retrive and update status.
    public function thankyou_page($order_id) {
        $this->logging("thankyou_page.orderId: " . $order_id);
        $this->is_order_completed($order_id);
        $order = new WC_Order( $order_id );
        $this->logging("thankyou_page.orderStauts: " . $order->get_status());
    }

    function snappay_query_order_status($order_id){
        $this->logging('snappay_query_order_status.order_id: '.$order_id);

        $order = new WC_Order ( $order_id );
        if(!$order){
            return new WP_Error( 'invalid_order', 'Invalid Order ID' );
        }
        $snappayOrderId = get_post_meta( $order->get_id(), 'snappayOrderId', true );
        if(!$snappayOrderId){
            return new WP_Error( 'invalid_order', 'Invalid SnapPay Order ID' );
        }

        //prepare request json
        $post_data = array(
            'app_id' => $this->appId,
            'format' => 'JSON',
            'charset' => 'UTF-8',
            'version' => '1.0',
            'sign_type' => 'MD5',

            'method' => 'pay.orderquery',
            'merchant_no' => $this->merchantId,
            'out_order_no' => $snappayOrderId
        );
        $post_data_sign = snappay_sign_post_data($post_data, $this->signKey);

        $data_json =  json_encode($post_data_sign);
        $this->logging('snappay_query_order_status.in: '.$data_json);
        $json = $this->do_post_request(C_WC_SNAPPAY_OPENAPI_HOST, $data_json);
        $this->logging('snappay_query_order_status.out: '.$json['body']);
        $ret = json_decode($json['body'], true);

        if($ret['code'] === '0'){
            return $ret['data'][0]['trans_status'];
        }else{
            return new WP_Error( 'invalid_order', $ret['msg']);
        }
    }

    function do_post_request($url, $post_data){
        $result = wp_remote_post( $url, array( 
            'headers' => array("Content-type" => "application/json;charset=UTF-8"),
            'body' => $post_data ) );
        return $result;
    }

    function logging($message) {
        wc_snappay_log($message);

        if ( 'yes' == $this->logging ) {
            $this->log->add(C_WC_SNAPPAY_ID, $message);
        }
    }

    function redirect($url){
        header('Location: '.$url);
        exit();
    }

    function isMobile() {
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }

        if (isset($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }

        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia','sony','samsung','htc','lg','lenovo','iphone','blackberry','meizu','android','netfront','ucweb','windowsce','palm','operamini','operamobi','openwave','nexus','pixel','wap','mobile','MicroMessenger','AlipayClient','HUAWEI','XiaoMi','OPPO','vivo');
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }

        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            if ( (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && 
                (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || 
                    (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))) ) {
                return true;
            }
        }
        return false;
    }

    function isWeChat(){ 
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }

    function generate_snappay_order_id( $wp_order_id ){
        $milliseconds = number_format(round(microtime(true) * 1000), 0, '', '');
        return 'SW'.$wp_order_id.substr($milliseconds, 3);
    }

    function get_wp_order_id( $sp_order_id ){
        return substr($sp_order_id, 2, -10);
    }

}

?>