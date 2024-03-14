<?php

if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly

require_once 'class-wshop-payment-gateway-alipay.php';
/**
 * @author rain
 *
 */
class WShop_Add_On_Xunhupay_Alipay extends Abstract_WShop_Add_Ons{
    /**
     * The single instance of the class.
     *
     * @since 1.0.0
     * @var WShop_Add_On_Alipay
     */
    private static $_instance = null;

    /**
     * 插件跟路径url
     * @var string
     * @since 1.0.0
     */
    public $domain_url;
    public $domain_dir;

    /**
     * Main Social Instance.
     *
     * @since 1.0.0
     * @static
     * @return WShop_Add_On_Alipay
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(){
        $this->id='wshop_add_ons_xunhupay_alipay';
        $this->title='Xunhupay迅虎支付 - 个人支付宝支付通道';
        $this->description='个人支付宝官方支付接口，支持唤醒app支付';
        $this->version='1.0.0';
        $this->min_core_version = '1.0.0';
        $this->author=__('xunhuweb',WSHOP);
        $this->author_uri='https://www.wpweixin.net';
        $this->domain_url = WShop_Helper_Uri::wp_url(__FILE__) ;
        $this->domain_dir = WShop_Helper_Uri::wp_dir(__FILE__) ;
        $this->setting_uris=array(
            'settings'=>array(
                'title'=>__('Settings',WSHOP),
                'url'=>admin_url("admin.php?page=wshop_page_default&section=menu_default_checkout&sub=xunhupay_alipay")
            )
        );
    }
    public function do_ajax(){

        $action ="wshop_{$this->id}";
        $datas=WShop_Async::instance()->shortcode_atts(array(
            'notice_str'=>null,
            'action'=>$action,
            $action=>null,
            'tab'=>null
        ), stripslashes_deep($_REQUEST));

        switch ($datas['tab']){
            case 'pay':
                $datas['order_id']=isset($_REQUEST['order_id'])?WShop_Helper_String::sanitize_key_ignorecase($_REQUEST['order_id']):'';
                if(!WShop::instance()->WP->ajax_validate($datas, isset($_REQUEST['hash'])?$_REQUEST['hash']:null,true)){
                    WShop::instance()->WP->wp_die(WShop_Error::err_code(701));
                    exit;
                }
                 if(WShop_Helper_Uri::is_app_client()){
                    require WShop::instance()->WP->get_template($this->domain_dir, 'checkout/alipay/wap.php');
                }else{
                    require WShop::instance()->WP->get_template($this->domain_dir, 'checkout/alipay/qrcode.php');
                }
                exit;
        }
    }

    /**
     * {@inheritDoc}
     * @see Abstract_WShop_Add_Ons::on_init()
     */
       public function on_init(){
       	 add_filter('wshop_admin_menu_menu_default_checkout', function($menu){
            $menu[]= WShop_Payment_Gateway_Xunhupay_Alipay::instance();
            return $menu;
        },10,1);

        add_filter('wshop_payments', function($payment_gateways){
            $payment_gateways[] =WShop_Payment_Gateway_Xunhupay_Alipay::instance();
            return $payment_gateways;
        },10,1);

        add_action( 'rest_api_init', function(){
            require_once 'controllers/class-payment-alipay-rest-controller.php';
            $controller = new WShop_Payment_OPAlipay_Rest_Controller();
            $controller->register_routes();
        });
    }

   /**
     * 签名方法
     * @param array $datas
     * @param string $hashkey
     */
    public static function generate_xh_hash(array $datas,$hashkey){
        ksort($datas);
        reset($datas);

        $pre =array();
        foreach ($datas as $key => $data){
            if(is_null($data)||$data===''){
                continue;
            }
            if($key=='sign'){
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
        return strtoupper(md5($arg.'&key='.$hashkey));
    }
    /**
     * http_post传输
     * @param array $url
     * @param string $jsonStr
     */
    public static function http_post_json($url, $jsonStr){	    $ch = curl_init();	    curl_setopt($ch, CURLOPT_POST, 1);	    curl_setopt($ch, CURLOPT_URL, $url);	    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);	    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(	            'Content-Type: application/json; charset=utf-8',	            'Content-Length: ' . strlen($jsonStr)	        )	    );	    $response = curl_exec($ch);	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);	    curl_close($ch);	 	    return $response;	}
	 /**
     * url拼接
     * @param array $url
     * @param string $datas
     */
	public function data_link($url,$datas){
		ksort($datas);
        reset($datas);
        $pre =array();
        foreach ($datas as $key => $data){
            if(is_null($data)||$data===''){
                continue;
            }
            if($key=='body'){
                continue;
            }
            $pre[$key]=$data;
        }

        $arg  = '';
        $qty = count($pre);
        $index=0;
		 foreach ($pre as $key=>$val){
		 		$val=urlencode($val);
			 	$arg.="$key=$val";
	            if($index++<($qty-1)){
	                $arg.="&amp;";
	            }
        }
        return $url.'?'.$arg;
	}
}

return WShop_Add_On_Xunhupay_Alipay::instance();
?>
