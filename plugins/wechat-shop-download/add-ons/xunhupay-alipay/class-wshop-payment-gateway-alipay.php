<?php

if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly
class WShop_Payment_Gateway_Xunhupay_Alipay extends Abstract_WShop_Payment_Gateway{
    /**
     * The single instance of the class.
     *
     * @since 1.0.0
     * @var WShop_Payment_Gateway_Alipay
     */
    private static $_instance = null;

    /**
     * Main Social Instance.
     *
     * @since 1.0.0
     * @static
     * @return WShop_Payment_Gateway_Xunhupay_Alipay
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(){
        $this->id='xunhupay_alipay';
        $this->group = 'alipay';
        $this->title=__('支付宝',WSHOP);
        $this->description ='当前支付插件专为个人用户使用，如果您是企业用户，请使用 <a href="https://www.wpweixin.net/product/1462.html" target="_blank">企业版插件</a>';
        $this->icon=WSHOP_URL.'/assets/image/alipay-l.png';
        $this->icon_small=WSHOP_URL.'/assets/image/alipay.png';

        $this->init_form_fields ();
        $this->enabled ='yes'==$this->get_option('enabled');
    }

    /**
     *
     * {@inheritDoc}
     * @see Abstract_WShop_Settings::init_form_fields()
     */
    public function init_form_fields() {
        $mchid ='2ddfa6b4325542979d55f90ffe0216bd';
        $private_key ='ceb557e114554c56ad665b52f1cb3d8b';
        $this->form_fields = array (
            'enabled' => array (
                'title' => __ ( 'Enable/Disable', WSHOP ),
                'type' => 'checkbox',
                'label' => __ ( 'Enable alipay payment', WSHOP ),
                'default' => 'no'
            ),
            'mchid' => array (
                'title' => __ ( 'MCH ID', WSHOP ),
                'type' => 'text',
                'description' => '迅虎支付 <a href="https://admin.xunhuweb.com" target="_blank">签约获取mchid</a>',
                'required' => true,
                'default'=>$mchid,
                'css' => 'width:400px'
            ),
            'private_key' => array (
                'title' => __ ( 'Private Key', WSHOP ),
                'type' => 'text',
                'css' => 'width:400px',
                'required' => true,
                'default'=>$private_key,
                'desc_tip' => false
            ),
            'gateway_url' => array (
                'title' =>  '支付网关地址',
                'type' => 'text',
                'css' => 'width:400px',
                'required' => true,
                'default'=>'https://admin.xunhuweb.com',
                'desc_tip' => false,
                'description'=>'帮助文档：https://www.xunhupay.com/114.html'
            )
        );
    }

    /**
     * {@inheritDoc}
     * @see Abstract_WShop_Payment_Gateway::process_payment()
     */
     public function process_payment($order){
        $api = WShop_Add_On_Xunhupay_Alipay::instance();
        return WShop_Error::success(WShop::instance()->ajax_url(array(
            'action'=>"wshop_{$api->id}",
            'tab'=>'pay',
            'order_id'=>$order->id
        ),true,true));
    }
    /**
     * {@inheritDoc}
     * @see Abstract_WShop_Payment_Gateway::query_order_transaction()
     */
     public function query_order_transaction($order_id, $order){
        if($order_id){
            return $order_id;
        }
        try {
        	 $data=array(
		            'mchid'     	=> $this->get_option('mchid'),
		            'out_trade_no'	=> $order->id,
		            'nonce_str' 	=> str_shuffle(time()),
	        	);
        	$hashkey=$this->get_option('private_key');
    		$url = $this->get_option('gateway_url').'/pay/query';
        	$api = WShop_Add_On_Xunhupay_Alipay::instance();
        	$data['sign']	  = $api->generate_xh_hash($data,$hashkey);
            $response   	  = $api->http_post_json($url, json_encode($data));
    		$result     	  = $response?json_decode($response,true):null;
            if(isset($result['status'])
                &&$result['status']=='complete'){
                return $result['transaction_id'];
            }

        }catch (Exception $e){
        }
        return $order_id;
    }

}
?>
