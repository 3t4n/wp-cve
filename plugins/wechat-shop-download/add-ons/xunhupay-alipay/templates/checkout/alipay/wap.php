<?php
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly
global $wpdb; 
$order_id =isset($_GET['order_id'])?$_GET['order_id']:'';
$order = WShop::instance()->payment->get_order('id', $order_id);
if(!$order){
    WShop::instance()->WP->wp_die(WShop_Error::err_code(404));
    exit;
}

if(!$order->can_pay()){
	wp_redirect($order->get_received_url());
	exit;
}

try {    
    
    $payment_gateway = WShop_Payment_Gateway_Xunhupay_Alipay::instance();
    $mchid = $payment_gateway->get_option('mchid');
    $private_key = $payment_gateway->get_option('private_key');
    //创建订单支付编号
    $sn = $order->generate_sn();
    if($sn instanceof WShop_Error){
       throw new Exception($sn->errmsg);
    }
    
    $exchange_rate = round(floatval(WShop_Settings_Default_Basic_Default::instance()->get_option('exchange_rate')),3);
    if($exchange_rate<=0){
        $exchange_rate = 1;
    }
     $data=array(
            'mchid'     	=> $mchid,
            'out_trade_no'	=> $order->id,
            'type'  		=> 'alipay',
            'total_fee' 	=> round($order->get_total_amount(false)*$exchange_rate,2)*100,
            'body'  		=> $order->get_title(),
            'notify_url'	=> home_url('/wp-json/wshop/opalipay/notify'),
			'redirect_url'  => $order->get_received_url(),
            'nonce_str' 	=> str_shuffle(time())
        );
        $wpdb->update( $wpdb->prefix.'wshop_order', array( 'sn' => $sn), array( 'id' => $order->id ));
		$url = $payment_gateway->get_option('gateway_url').'/alipaycashier';
		$api = WShop_Add_On_Xunhupay_Alipay::instance();
        $data['sign']     = $api->generate_xh_hash($data,$private_key);
        $pay_url   		  = $api->data_link($url, $data);
		header("Location:". htmlspecialchars_decode($pay_url,ENT_NOQUOTES));
	    exit;
} catch (Exception $e) {
    WShop_Log::error($e);
    WShop::instance()->WP->wp_die($e);
    exit;
}
?>
