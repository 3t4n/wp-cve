<?php
/*
 * Plugin Name: Xunhu Alipay Payment For WooCommerce
 * Plugin URI: http://www.wpweixin.net
 * Description: 支付宝个人扫码支付、支付宝H5支付
 * Author: 重庆迅虎网络有限公司
 * Version: 1.0.7
 * Author URI:  http://www.wpweixin.net
 * Text Domain: Alipay payment for woocommerce
 * WC tested up to: 9.9.9
 */

if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly

if (! defined ( 'XH_Alipay_Payment' )) {define ( 'XH_Alipay_Payment', 'XH_Alipay_Payment' );} else {return;}
define ( 'XH_Alipay_Payment_VERSION', '1.0.7');
define ( 'XH_Alipay_Payment_ID', 'xh-alipay-payment-wc');
define ( 'XH_Alipay_Payment_FILE', __FILE__);
define ( 'XH_Alipay_Payment_DIR', rtrim ( plugin_dir_path ( XH_Alipay_Payment_FILE ), '/' ) );
define ( 'XH_Alipay_Payment_URL', rtrim ( plugin_dir_url ( XH_Alipay_Payment_FILE ), '/' ) );
load_plugin_textdomain( XH_Alipay_Payment, false,dirname( plugin_basename( __FILE__ ) ) . '/lang/'  );

add_filter ( 'plugin_action_links_'.plugin_basename( XH_Alipay_Payment_FILE ),'xh_alipay_payment_plugin_action_links',10,1 );
function xh_alipay_payment_plugin_action_links($links) {
    return array_merge ( array (
        'settings' => '<a href="' . admin_url ( 'admin.php?page=wc-settings&tab=checkout&section='.XH_Alipay_Payment_ID ) . '">'.__('Settings',XH_Alipay_Payment).'</a>'
    ), $links );
}


if(!class_exists('WC_Payment_Gateway')){
    return;
}

require_once XH_Alipay_Payment_DIR.'/class-alipay-wc-payment-gateway.php';
global $XH_Alipay_Payment_WC_Payment_Gateway;
$XH_Alipay_Payment_WC_Payment_Gateway=new XH_Alipay_Payment_WC_Payment_Gateway();

add_action('init', array($XH_Alipay_Payment_WC_Payment_Gateway,'notify'),10);

add_action('init', function(){
    $request = shortcode_atts(array(
        'action'=>null,
        'order_id'=>null,
        'time'=>null,
        'notice_str'=>null,
        'hash'=>null
    ), stripslashes_deep($_REQUEST));

    if(empty($request['action'])||$request['action']!='-hpj-alipay-do-pay'){
        return;
    }

    $hash = $request['hash'];
    unset($request['hash']);
    ksort($request);
    reset($request);
    if(md5(http_build_query($request).AUTH_KEY)!=$hash){
        return;
    }
    $order_id = $request['order_id'];
    global $XH_Alipay_Payment_WC_Payment_Gateway;
    $payment = $XH_Alipay_Payment_WC_Payment_Gateway;

    $order            = wc_get_order ( $order_id );
    if(!$order||(method_exists($order, 'is_paid')?$order->is_paid():in_array($order->get_status(),  array( 'processing', 'completed' )))){
        wp_redirect($payment->get_return_url($order));exit;
    }

    $expire_rate      = floatval($payment->get_option('exchange_rate',1));
    if($expire_rate<=0){
        $expire_rate=1;
    }

    $siteurl = rtrim(home_url(),'/');
    $posi =strripos($siteurl, '/');
    //若是二级目录域名，需要以“/”结尾，否则会出现403跳转
    if($posi!==false&&$posi>7){
        $siteurl.='/';
    }

    $total_amount     = round($order->get_total()*$expire_rate,2);
    $data=array(
        'version'   => '1.1',//api version
        'lang'       => get_option('WPLANG','zh-cn'),
        'plugins'   => 'woo-alipay',
        'appid'     => $payment->get_option('appid'),
        'trade_order_id'=> $order_id,
        'payment'   => 'alipay',
        'is_app'    => $payment->isWebApp()?'Y':'N',
        'total_fee' => $total_amount,
        'title'     => $payment->get_order_title($order),
        'description'=> null,
        'time'      => time(),
        'modal'=>'full',
        'notify_url'=>  $siteurl,
        'return_url'=> $payment->get_return_url($order),
        'callback_url'=>wc_get_checkout_url(),
        'nonce_str' => str_shuffle(time())
    );

    $hashkey          = $payment->get_option('appsecret');
    $data['hash']     = $payment->generate_xh_hash($data,$hashkey);
    $turl = $payment->get_option('tranasction_url');
    $url              = $turl.'/payment/do.html';

    try {
        $response     = $payment->http_post($url, json_encode($data));
        $result       = $response?json_decode($response,true):null;
        if(!$result){
            throw new Exception('Internal server error',500);
        }

        $hash         = $payment->generate_xh_hash($result,$hashkey);
        if(!isset( $result['hash'])|| $hash!=$result['hash']){
            throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
        }

        if($result['errcode']!=0){
            throw new Exception($result['errmsg'],$result['errcode']);
        }

        echo $result['html'];
        exit;
    } catch (Exception $e) {
        wc_add_notice("errcode:{$e->getCode()},errmsg:{$e->getMessage()}",'error');
        wp_redirect($payment->get_return_url($order));
        exit;
    }
});
