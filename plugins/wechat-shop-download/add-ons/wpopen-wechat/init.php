<?php

if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly

require_once 'class-wshop-payment-gateway-wechat.php';
/**
 * @author rain
 *
 */
class WShop_Add_On_Wpopen_Wechat extends Abstract_WShop_Add_Ons{
    /**
     * The single instance of the class.
     *
     * @since 1.0.0
     * @var WShop_Add_On_Wechat
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
     * @return WShop_Add_On_Wechat
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(){
        $this->id='wshop_add_ons_wpopen_wechat';
        $this->title='虎皮椒 - 个人微信支付';
        $this->description='个人微信支付官方支付接口';
        $this->version='1.0.0';
        $this->min_core_version = '1.0.0';
        $this->author=__('xunhuweb',WSHOP);
        $this->author_uri='https://www.wpweixin.net';
        $this->domain_url = WShop_Helper_Uri::wp_url(__FILE__) ;
        $this->domain_dir = WShop_Helper_Uri::wp_dir(__FILE__) ;
        $this->setting_uris=array(
            'settings'=>array(
                'title'=>__('Settings',WSHOP),
                'url'=>admin_url("admin.php?page=wshop_page_default&section=menu_default_checkout&sub=wpopen_wechat")
            )
        );
    }
    public function do_ajax(){
        $action ="wshop_{$this->id}";


        switch (isset($_REQUEST['tab'])?$_REQUEST['tab']:null){
            case 'pay':
                $datas=WShop_Async::instance()->shortcode_atts(array(
                    'notice_str'=>null,
                    'action'=>$action,
                    $action=>null,
                    'tab'=>null,
                    'order_id'=>0
                ), stripslashes_deep($_REQUEST));
                $order = new WShop_Order($datas['order_id']);
                if(!$order->is_load()){
                    WShop::instance()->WP->wp_die("订单信息异常！");
                    exit;
                }
                $payment = WShop_Payment_Gateway_Wpopen_Wechat::instance();
                $sn = $order->generate_sn();
                if($sn instanceof WShop_Error){
                    WShop::instance()->WP->wp_die($sn);
                    exit;
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
                    'appid'     => $payment->get_option('appid'),
                    'trade_order_id'=> $sn,
                    'payment'   => 'wechat',
                    'total_fee' => round($order->get_total_amount(false)*$exchange_rate,2),
                    'title'     => $order->get_title(),
                    'description'=> null,
                    'time'      => time(),
                    'modal'=>'full',
                    'notify_url'=> home_url('/'),
                    'return_url'=> WShop_Helper_Uri::get_new_uri(home_url('/'),$p),
                    'callback_url'=>$order->get_back_url(),
                    'nonce_str' => str_shuffle(time())
                );
                if(WShop_Helper_Uri::is_app_client()){
                    $data['type']='WAP';
                    $data['wap_url']=home_url();
                    $data['wap_name']=home_url();
                }
                $hashkey          = $payment->get_option('appsecret');
                $data['hash']     = $payment->generate_xh_hash($data,$hashkey);
                $t = $payment->get_option('gateway_url');
                $url              = $t.'/payment/do.html';

                try {
                    $response     = WShop_Helper_Http::http_post($url, json_encode($data));
                    $result       = $response?json_decode($response,true):null;
                    if(!$result){
                        throw new Exception('Internal server error',500);
                    }

                    $hash         = $payment->generate_xh_hash($result,$hashkey);
                    if(!isset( $result['hash'])|| $hash!=$result['hash']){
                        throw new Exception(__('Invalid sign!',WSHOP),40029);
                    }

                    if($result['errcode']!=0){
                        throw new Exception($result['errmsg'],$result['errcode']);
                    }

                    echo $result['html'];exit;
                } catch (Exception $e) {
                    WShop_Log::error($e);
                    WShop::instance()->WP->wp_die($e->getMessage());
                    exit;
                }
        }
    }
    /**
     *
     * {@inheritDoc}
     * @see Abstract_WShop_Add_Ons::on_init()
     */
    public function on_init(){
        add_filter('wshop_admin_menu_menu_default_checkout', function($menu){
            $menu[]= WShop_Payment_Gateway_Wpopen_Wechat::instance();
            return $menu;
        },10,1);

        add_filter('wshop_payments', function($payment_gateways){
            $payment_gateways[] =WShop_Payment_Gateway_Wpopen_Wechat::instance();
            return $payment_gateways;
        },10,1);

        add_action( 'rest_api_init', function(){
            require_once 'controllers/class-payment-wechat-rest-controller.php';
            $controller = new WShop_Payment_OPWechat_Rest_Controller();
            $controller->register_routes();
        });
    }

}

return WShop_Add_On_Wpopen_Wechat::instance();
?>
