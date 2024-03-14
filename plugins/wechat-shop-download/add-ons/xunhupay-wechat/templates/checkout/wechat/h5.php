<?php
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly
global $wpdb;
$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
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
	$url = $payment_gateway->get_option('gateway_url').'/pay/payment';
    $data = array(
    	'mchid'=>$mchid,
        'body'=>$order->get_title(),
        'total_fee' 	=>round($order->get_total_amount(false)*$exchange_rate,2)*100,
        'out_trade_no'=>$order->id,
        'notify_url'=>home_url('/wp-json/wshop/opwechat/notify'),
        'type'=> 'wechat',
		'trade_type'=>'WAP',
		'wap_url'=>home_url(),
		'wap_name'=>'迅虎网络',
        'nonce_str'=>str_shuffle(time())
    );
		$redirect_url=$order->get_received_url();
    	$wpdb->update( $wpdb->prefix.'wshop_order', array( 'sn' => $sn), array( 'id' => $order->id ));
	    $api = WShop_Add_On_Xunhupay_Wechat::instance();
        $data['sign']     = $api->generate_xh_hash($data,$private_key);
        $response   	  = $api->http_post_json($url, json_encode($data));
        $result     	  = $response?json_decode($response,true):null;
        if(!$result){
			 throw new Exception('Internal server error',500);
		 }
		 $sign       	  =$api->generate_xh_hash($result,$private_key);
		 if(!isset( $result['sign'])|| $sign!=$result['sign']){
			 throw new Exception('Invalid sign!',40029);
		 }
		 if($result['return_code']!='SUCCESS'){
			 throw new Exception($result['err_msg'],$result['err_code']);
		 }
        $pay_url =$result['mweb_url'].'&redirect_url='.urlencode($redirect_url);
        if (! $guessurl = site_url() ){
		        $guessurl = wp_guess_url();
		    }
       ?>
					<html>
					<head>
					<meta charset="UTF-8">
					<title>收银台付款</title>
					<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
					<meta name="format-detection" content="telephone=no">
					<link rel="stylesheet" href="<?php print WSHOP_URL;?>/add-ons/xunhupay-wechat/assets/style.css">
					</head>
					<body ontouchstart="" class="bggrey">
						<div class="xh-title"><img src="https://api.xunhupay.com/content/images/wechat-s.png" alt="" style="vertical-align: middle"> 微信支付收银台</div>

							<div class="xhpay ">
							   <img class="logo" alt="" src="<?php print WSHOP_URL;?>/assets/image/wechat.png">

								<span class="price"><?php echo $order->get_total_amount(true); ?></span>
							</div>
							<div class="xhpaybt">
								<a href="<?php print WSHOP_URL;?>/add-ons/xunhupay-wechat/h5.php?url=<?php echo urlencode($pay_url) ?>" class="xunhu-btn xunhu-btn-green" >微信支付</a>
							</div>
							<div class="xhpaybt">
								<a href="<?php echo $redirect_url;?>" class="xunhu-btn xunhu-btn-border-green" >取消支付</a>
							</div>
							<div class="xhtext" align="center">支付完成后，如需售后服务请联系客服</div>
							<div class="xhfooter" align="center">迅虎网络提供技术支持</div>
							<script src="<?php echo $guessurl.'/wp-includes/js/jquery/jquery.js'; ?>"></script>
							<script type="text/javascript">
							 (function($){
									window.view={
										query:function () {
											$.ajax({
												type: "POST",
												 url: '<?php print WShop::instance()->ajax_url(array(
									                'action'=>'wshop_checkout_v2',
									                'order_id'=>$order->id,
									                'tab'=>'is_paid'
									            ),true,true);?>',
												timeout:6000,
												cache:false,
												dataType:'json',
									            success:function(e){
									            	if (e && e.data.paid) {
						    			                $('#weixin-notice').css('color','green').text('已支付成功，跳转中...');
						    		                    location.href = e.data.received_url;
						    		                    return;
						    		                }
													
													setTimeout(function(){window.view.query();}, 2000);
												},
												error:function(){
													 setTimeout(function(){window.view.query();}, 2000);
												}
											});
										}
									};								
									  window.view.query();								
								})(jQuery);
								</script>
					</body>
					</html>
				 <?php
		         
		         exit;
} catch (Exception $e) {
    WShop_Log::error($e);
    WShop::instance()->WP->wp_die($e);
    exit;
}
?>
