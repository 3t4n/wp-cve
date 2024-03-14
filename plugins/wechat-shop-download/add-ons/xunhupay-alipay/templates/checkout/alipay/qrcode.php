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
            'total_fee' 	=>round($order->get_total_amount(false)*$exchange_rate,2)*100,
            'body'  		=> $order->get_title(),
            'notify_url'	=> home_url('/wp-json/wshop/opalipay/notify'),
            'nonce_str' 	=> str_shuffle(time())
        );		
        $wpdb->update( $wpdb->prefix.'wshop_order', array( 'sn' => $sn), array( 'id' => $order->id ));
		$url = $payment_gateway->get_option('gateway_url').'/pay/payment';
		$api = WShop_Add_On_Xunhupay_Alipay::instance();
        $data['sign']     = $api->generate_xh_hash($data,$private_key);
        $response   	  = $api->http_post_json($url, json_encode($data));
        $result     	  = $response?json_decode($response,true):null;
        if(!$result){
            throw new Exception('Internal server error',500);
        }
        $sign       	  = $api->generate_xh_hash($result,$private_key);
		if(!isset( $result['sign'])|| $sign!=$result['sign']){
			throw new Exception($result['err_msg'],$result['err_code']);
		}
        $url =$result['code_url'];
	     if ( ! $guessurl = site_url() ){
		        $guessurl = wp_guess_url();
		    }
		   ?>
			<!DOCTYPE html>
			<html>
			<head>
		    <meta charset="utf-8">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		    <meta name="keywords" content="">
		    <meta name="description" content="">   
		    <title>支付宝收银台</title>
		    <style>
		         *{margin:0;padding:0;}
		          body{background: #f2f2f4;}
		         .clearfix:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }
		        .clearfix { display: inline-block; }
		        * html .clearfix { height: 1%; }
		        .clearfix { display: block; }
		          .xh-title{height:75px;line-height:75px;text-align:center;font-size:30px;font-weight:300;border-bottom:2px solid #eee;background: #fff;}
		          .qrbox{max-width: 900px;margin: 0 auto;padding:85px 20px 20px 50px;}
		          
		          .qrbox .left{width: 40%;
		            float: left;    
		             display: block;
		            margin: 0px auto;}
		          .qrbox .left .qrcon{
		            border-radius: 10px;
		            background: #fff;
		            overflow: visible;
		            text-align: center;
		            padding-top:25px;
		            color: #555;
		            box-shadow: 0 3px 3px 0 rgba(0, 0, 0, .05);
		            vertical-align: top;
		            -webkit-transition: all .2s linear;
		            transition: all .2s linear;
		          }
		            .qrbox .left .qrcon .logo{width: 100%;}
		            .qrbox .left .qrcon .title{font-size: 16px;margin: 10px auto;width: 100%;}
		            .qrbox .left .qrcon .price{font-size: 22px;margin: 0px auto;width: 100%;}
		            .qrbox .left .qrcon .bottom{border-radius: 0 0 10px 10px;
		            width: 100%;
		            background: #32343d;
		            color: #f2f2f2;padding:15px 0px;text-align: center;font-size: 14px;}
		           .qrbox .sys{width: 60%;float: right;text-align: center;padding-top:20px;font-size: 12px;color: #ccc}
		           .qrbox img{max-width: 100%;}
		           @media (max-width : 767px){
		        .qrbox{padding:20px;}
		            .qrbox .left{width: 90%;float: none;}   
		            .qrbox .sys{display: none;}
		           }
		           
		           @media (max-width : 320px){
		
		          }
		          @media ( min-width: 321px) and ( max-width:375px ){
		
		          }
		    </style>
		    </head>
		    
		    <body>
		     <div class="xh-title"><img src="<?php print WSHOP_URL;?>/assets/image/alipay.png" alt="" style="vertical-align: middle"> 微信支付收银台</div>
		      <div class="qrbox clearfix">
		      <div class="left">
		         <div class="qrcon">
		           <h5><img src="<?php print WSHOP_URL;?>/assets/image/alipay/logo.png" alt=""></h5>
		             <div class="title"><?php print $order->get_title();?></div>
		             <div class="price"><?php echo $order->get_total_amount(true);?></div>
		             <div align="center"><div id="alipay_qrcode" style="width: 250px;height: 250px;"></div></div>
		             <div class="bottom">
		                 	请使用支付宝扫一扫<br/>
		    				扫描二维码支付
		             </div>
		         </div>
		         
		  </div>
		     <div class="sys"><img src="<?php print WSHOP_URL;?>/assets/image/alipay/alipay-sys.png" alt=""></div>
		  </div>
			<script src="<?php echo $guessurl.'/wp-includes/js/jquery/jquery.js'; ?>"></script>
			<script src="<?php print WSHOP_URL?>/assets/js/qrcode.js"></script>	
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
		    		var qrcode = new QRCode(document.getElementById("alipay_qrcode"), {
		              width : 220,
		              height : 220
		            });
		            
		            <?php if(!empty($url)){
		              ?>
		              qrcode.makeCode("<?php print $url?>");
		              window.view.query();
		            <?php 
		            }?>
		    		
		    	})(jQuery);
		    	</script>
			</body>
		</html>
			<?php  
    } catch (Exception $e) {
      WShop_Log::error($e);
      return WShop_Error::error_custom($e->getMessage());
      exit;
}

?>