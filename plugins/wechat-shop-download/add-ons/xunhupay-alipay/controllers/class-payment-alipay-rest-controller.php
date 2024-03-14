<?php
if (! defined ( 'ABSPATH' )) {
	exit ();
}

class WShop_Payment_OPAlipay_Rest_Controller extends WP_REST_Controller {
	public function __construct() {
	    $this->namespace = 'wshop';
		$this->rest_base = 'opalipay';
	}
	public function register_routes() {
		register_rest_route ( $this->namespace, "/{$this->rest_base}/notify", array (
				array (
						'methods' => WP_REST_Server::ALLMETHODS,
						'callback' => array ($this,'notify')
				)
		) );
		
		register_rest_route ( $this->namespace, "/{$this->rest_base}/back", array (
				array (
						'methods' => WP_REST_Server::ALLMETHODS,
						'callback' => array ($this,'back')
				)
		) );
	}

	/**
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function back($request){
	//     $addon = WShop_Add_On_Xunhupay_Alipay::instance();
	//     $request = shortcode_atts(array(
	// 			 'sn'=>null,
	//              'notice_str'=>null,
	//              'action'=>null,
	//              'hash'=>null
	// 	),stripslashes_deep($_REQUEST));
		
	// 		$order = WShop::instance()->payment->get_order('sn', $request['sn']);
	// 		if(!$order){
	// 			WShop_Log::error('invalid order:'.print_r($request,true));
	// 			return;
	// 		}
			
	// 		$api =WShop_Payment_Gateway_Xunhupay_Alipay::instance();
	// 		$data=array(
	// 	            'mchid'     	=> $api->get_option('mchid'),
	// 	            'out_trade_no'	=> $order->sn,
	// 	            'nonce_str' 	=> str_shuffle(time())
	// 	        	);
	// 		$hashkey          = $api->get_option('private_key');
	// 		$url              = $api->get_option('gateway_url').'/pay/query';
	// 		$api=WShop_Add_On_Xunhupay_Alipay::instance();
	// 		$data['sign']     = $api->generate_xh_hash($data,$hashkey);
	// 		try {
	// 			$response     = $api->http_post_json($url, $data);
	// 			$result       = $response?json_decode($response,true):null;
	// 			if(!$result){
	// 				throw new Exception('Internal server error',500);
	// 			}
				
	// 			if(isset($result['status'])&&$result['status']=='complete'){
	// 				$error =$order->complete_payment($result['order_id']);
	// 				if(!WShop_Error::is_valid($error)){
	// 					wp_redirect($order->get_received_url());
	// 					exit;
	// 					}
	// 				}else{
	// 					WShop::instance()->WP->wp_die($error);
 //               		exit;
	// 			}
	// 		} catch (Exception $e) {
	// 			WShop_Log::error($e);
	// 			 WShop::instance()->WP->wp_die($e->getMessage());
	// 			exit;
	// 		}
	// 	wp_redirect($order->get_back_url());
 //       exit;
	}
	
	/**
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
public function notify($request){
		    $data=(array)json_decode(file_get_contents('php://input'));
		    $api = WShop_Add_On_Xunhupay_Alipay::instance();
		    $payment_gateway = WShop_Payment_Gateway_Xunhupay_Alipay::instance();
		    $private_key = $payment_gateway->get_option('private_key');
			if(!$data){
				 exit('faild!');
			}
			$out_trade_no = isset($data['out_trade_no'])?$data['out_trade_no']:null;
			$order_id=isset($data['order_id'])?$data['order_id']:null;
			$transaction_id=isset($data['transaction_id'])?$data['transaction_id']:null;
			$hash =$api->generate_xh_hash($data,$private_key);
			if($data['sign']!=$hash){
			    //签名验证失败
			    echo '签名错误';exit;
			}
	        if($data['status']=='complete'){
	    	    $order = WShop::instance()->payment->get_order('id', $out_trade_no);
	            $error =$order->complete_payment($transaction_id);
	        }else{
	        	exit('faild!');
	        }
	        print 'success';
        	exit;
	}
}
?>