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
 $sn = $order->generate_sn();
if($sn instanceof WShop_Error){
   throw new Exception($sn->errmsg);
}
$exchange_rate = round(floatval(WShop_Settings_Default_Basic_Default::instance()->get_option('exchange_rate')),3);
if($exchange_rate<=0){
    $exchange_rate = 1;
}
try{
	$api = WShop_Add_On_Xunhupay_Wechat::instance();
	$payment_gateway = WShop_Payment_Gateway_Xunhupay_Wechat::instance();
	$mchid = $payment_gateway->get_option('mchid');
	$private_key = $payment_gateway->get_option('private_key');
	$url = $payment_gateway->get_option('gateway_url').'/pay/cashier';
	$total_fee=round($order->get_total_amount(false)*100*$exchange_rate,2)?round($order->get_total_amount(false)*100*$exchange_rate,2):null;
    $data = array(
        'body'=>$order->get_title(),
        'total_fee'=>$total_fee,
        'out_trade_no'=>$order->id,
        'notify_url'=>home_url('/wp-json/wshop/opwechat/notify'),
        'redirect_url'=>$order->get_received_url(),
        'type'=> 'wechat',
        'mchid'=>$mchid,
        'nonce_str'=>str_shuffle(time())
    );
    	$wpdb->update( $wpdb->prefix.'wshop_order', array( 'sn' => $sn), array( 'id' => $order->id ));
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
