<?php

if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly

class WShop_Payment_Gateway_Wpopen_Wechat extends Abstract_WShop_Payment_Gateway{
    /**
     * The single instance of the class.
     *
     * @since 1.0.0
     * @var WShop_Payment_Gateway_Wechat
     */
    private static $_instance = null;

    /**
     * Main Social Instance.
     *
     * @since 1.0.0
     * @static
     * @return WShop_Payment_Gateway_Wpopen_Wechat
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(){
        $this->id='wpopen_wechat';
        $this->group = 'wechat';
        $this->title=__('Wechat Pay',WSHOP);
        $this->description ='当前支付插件专为个人用户使用，如果您是企业用户，请使用 <a href="https://www.wpweixin.net/product/201.html" target="_blank">企业版插件</a>';
        $this->icon=WSHOP_URL.'/assets/image/wechat-l.png';
        $this->icon_small=WSHOP_URL.'/assets/image/wechat.png';

        $this->init_form_fields ();
        $this->enabled ='yes'==$this->get_option('enabled');
    }

    /**
     *
     * {@inheritDoc}
     * @see Abstract_WShop_Settings::init_form_fields()
     */
    public function init_form_fields() {
        $appid ='2147483647';
        $appsecret ='160130736b1ac0d54ed7abe51e44840b';
        $this->form_fields = array (
            'enabled' => array (
                'title' => __ ( 'Enable/Disable', WSHOP ),
                'type' => 'checkbox',
                'label' => __ ( 'Enable wechat payment', WSHOP ),
                'default' => 'no'
            ),
            'appid' => array (
                'title' => __ ( 'APP ID', WSHOP ),
                'type' => 'text',
                'description' => '虎皮椒 <a href="https://www.xunhupay.com" target="_blank">签约获取Appid</a>',
                'required' => true,
                'default'=>$appid,
                'css' => 'width:400px'
            ),
            'appsecret' => array (
                'title' => __ ( 'APP Secret', WSHOP ),
                'type' => 'text',
                'css' => 'width:400px',
                'required' => true,
                'default'=>$appsecret,
                'desc_tip' => false
            ),
            'gateway_url' => array (
                'title' =>  '支付网关地址',
                'type' => 'text',
                'css' => 'width:400px',
                'required' => true,
                'default'=>'https://api.xunhupay.com',
                'desc_tip' => false,
                'description'=>'帮助文档：https://www.xunhupay.com/114.html'
            )
        );
    }

    /**
     * {@inheritDoc}
     * @see Abstract_WShop_Payment_Gateway::process_payment()
     */
    public function process_payment($order)
    {
        $api = WShop_Add_On_Wpopen_Wechat::instance();
        if(!$order->can_pay()){
            return WShop_Error::error_custom(__('Current order is paid or expired!',WSHOP));
        }

        $t = $this->get_option('gateway_url');
        //启用离线模式
//         if(stripos($t, 'https://pay2.xunhupay.com/v2')===0){
//             return WShop_Error::success(WShop::instance()->ajax_url(array(
//                 'action'=>"wshop_{$api->id}",
//                 'tab'=>'pay',
//                 'order_id'=>$order->id
//             ),true,true));
//         }

        //创建订单支付编号
        $sn = $order->generate_sn();
        if($sn instanceof WShop_Error){
            return $sn;
        }
        $exchange_rate = round(floatval(WShop_Settings_Default_Basic_Default::instance()->get_option('exchange_rate')),3);
        if($exchange_rate<=0){
            $exchange_rate = 1;
        }

        $p = WShop::instance()->generate_request_params(array(
            'sn'=>$sn,
            'wshop-payment-return'=>'wpopen-wechat-return'
        ),false);

        $data=array(
            'version'   => '1.1',//api version
            'lang'       => get_option('WPLANG','zh-cn'),
            'is_app'    => WShop_Helper_Uri::is_wechat_app()?'Y':'N',
            'plugins'   => 'wshop-wechat',
            'appid'     => $this->get_option('appid'),
            'trade_order_id'=> $sn,
            'payment'   => 'wechat',
            'total_fee' => round($order->get_total_amount(false)*$exchange_rate,2),
            'title'     => $order->get_title(),
            'description'=> null,
            'time'      => time(),
            'notify_url'=> home_url('/wp-json/wshop/opwechat/notify'),
        	'return_url'=> home_url('/wp-json/wshop/opwechat/back?'.http_build_query($p)),
            'callback_url'=>$order->get_back_url(),
            'nonce_str' => str_shuffle(time())
        );
        if(WShop_Helper_Uri::is_app_client()){
            $data['type']='WAP';
            $data['wap_url']=home_url();
            $data['wap_name']=home_url();
        }
        $hashkey          = $this->get_option('appsecret');
        $data['hash']     = $this->generate_xh_hash($data,$hashkey);
        $url              = $t.'/payment/do.html';
        try {
            $response     = WShop_Helper_Http::http_post($url, json_encode($data));
            $result       = $response?json_decode($response,true):null;
            if(!$result){
                throw new Exception('Internal server error',500);
            }

            $hash         = $this->generate_xh_hash($result,$hashkey);
            if(!isset( $result['hash'])|| $hash!=$result['hash']){
                throw new Exception(__('Invalid sign!',WSHOP),40029);
            }

            if($result['errcode']!=0){
                throw new Exception($result['errmsg'],$result['errcode']);
            }

            if(WShop_Helper_Uri::is_app_client()){
               return WShop_Error::success($result['url']);
            }
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
                <title>微信支付收银台</title>
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
                 <div class="xh-title"><img src="<?php print WSHOP_URL;?>/assets/image/wechat.png" alt="" style="vertical-align: middle"> 微信支付收银台</div>
                  <div class="qrbox clearfix">
                  <div class="left">
                     <div class="qrcon">
                       <h5><img src="<?php print WSHOP_URL;?>/assets/image/wechat/logo.png" alt=""></h5>
                         <div class="title"><?php print $order->get_title();?></div>
                         <div class="price"><?php echo $order->get_total_amount(true);?></div>
                         <div align="center"><div id="wechat_qrcode" style="width: 250px;height: 250px;"><img src="<?php echo $result['url_qrcode'];?>"/></div></div>
                         <div class="bottom">
                            请使用微信扫一扫<br/>
                            扫描二维码支付
                         </div>
                     </div>
              </div>
                 <div class="sys"><img src="<?php print WSHOP_URL;?>/assets/image/wechat/wechat-sys.png" alt=""></div>
              </div>
            	<script src="<?php echo $guessurl.'/wp-includes/js/jquery/jquery.js'; ?>"></script>
                 <script type="text/javascript">
                    (function($){
		    		window.view={
						query:function () {
					        $.ajax({
					            type: "GET",
					            url: '<?php echo home_url('/wp-json/wshop/opwechat/query?sn='.$sn); ?>',
					            timeout:6000,
					            cache:false,
					            dataType:'text',
					            success:function(e){
					            	if (e && e.indexOf('complete')!==-1) {
		    			                $('#weixin-notice').css('color','green').text('已支付成功，跳转中...');
		    			                location.href = '<?php echo home_url('/wp-json/wshop/opwechat/back?'.http_build_query($p)); ?>';
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
		            <?php if(!empty($result['url_qrcode'])){
		              ?>
		              window.view.query();
		            <?php
		            }?>
		    	})(jQuery);
                </script>
            	</body>
            </html>
            <?php 
            exit;
        } catch (Exception $e) {
            WShop_Log::error($e);
            return WShop_Error::error_custom($e->getMessage());
        }
    }

    public function generate_xh_hash(array $datas,$hashkey){
        ksort($datas);
        reset($datas);

        $pre =array();
        foreach ($datas as $key => $data){
            if(is_null($data)||$data===''){
                continue;
            }
            if($key=='hash'){
                continue;
            }
            $pre[$key]=$data;
        }

        $arg  = '';
        $qty = count($pre);
        $index=0;

        foreach ($pre as $key=>$val){
            $arg.="$key=$val";
            if($index++<($qty-1)){
                $arg.="&";
            }
        }

        return md5($arg.$hashkey);
    }

}
?>
