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
		register_rest_route ( $this->namespace, "/{$this->rest_base}/query", array (
				array (
						'methods' => WP_REST_Server::ALLMETHODS,
						'callback' => array ($this,'query')
				)
		) );
	}

	/**
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function back($request){
	       $request = shortcode_atts(array(
    				'sn'=>null,
    				'wshop-payment-return'=>null,
    				'notice_str'=>null,
    				'hash'=>null
    		),stripslashes_deep($_GET));
    		if($request['hash']==WShop_Helper::generate_hash($request, WShop::instance()->get_hash_key())){
    			$order = WShop::instance()->payment->get_order('sn', $request['sn']);
    			if(!$order){
    				WShop_Log::error('invalid order:'.print_r($request,true));
    				return;
    			}
    			
    			$api =WShop_Payment_Gateway_Wpopen_Alipay::instance();
    			$data=array(
    					'appid'     => $api->get_option('appid'),
    					'out_trade_order'=> $request['sn'],
    					'time'      => time(),
    					'nonce_str' => str_shuffle(time())
    			);
    			
    			$hashkey          = $api->get_option('appsecret');
    			$data['hash']     = $api->generate_xh_hash($data,$hashkey);
    			$url              = $api->get_option('gateway_url').'/payment/query.html';
    			
    			try {
    				$response     = WShop_Helper_Http::http_post($url, $data);
    				
    				$result       = $response?json_decode($response,true):null;
    				if(!$result){
    					throw new Exception('Internal server error',500);
    				}
    				
    				if(isset($result['errcode'])
    						&&$result['errcode']=='0'
    						&&isset($result['data']['status'])&&$result['data']['status']=='OD'){
    						
    					$error =$order->complete_payment($result['transaction_id']);
    					if(!WShop_Error::is_valid($error)){
    						WShop_Log::error('complete_payment fail:'.$error->errmsg);
    						WShop::instance()->WP->wp_die($error->errmsg);
    						exit;
    					}
    				}
    				
    				wp_redirect($order->get_received_url());
    				exit;
    			} catch (Exception $e) {
    				WShop_Log::error($e);
    				WShop::instance()->WP->wp_die($e->getMessage());
    				exit;
    			}
    		}
	}
	
	public function query(){
	    $sn = $_GET['sn'];
	    if(!$sn){
			WShop_Log::error('invalid order: '.$sn);
			return;
		}
		$api =WShop_Payment_Gateway_Wpopen_Alipay::instance();
		$data=array(
				'appid'     => $api->get_option('appid'),
				'out_trade_order'=> $sn,
				'time'      => time(),
				'nonce_str' => str_shuffle(time())
		);
		
		$hashkey          = $api->get_option('appsecret');
		$data['hash']     = $api->generate_xh_hash($data,$hashkey);
		$url              = $api->get_option('gateway_url').'/payment/query.html';
		try {
		    $response     = WShop_Helper_Http::http_post($url, $data);
			$result       = $response?json_decode($response,true):null;
			if(!$result){
				throw new Exception('Internal server error',500);
			}
			if($result['data']['status']=='OD'){
            	echo 'complete';
            	exit;
            }else{
            	echo 'paidding';
            	exit;
            }
		} catch (Exception $e) {
		    WShop_Log::error($e);
			WShop::instance()->WP->wp_die($e->getMessage());
			exit;
		}
	}
	
	/**
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function notify($request){
	    if(!isset($_POST)){
   			return;
   		}
   		$data = stripslashes_deep($_POST);
        if(!isset($data['hash'])||!isset($data['trade_order_id'])){
            return;
        }
        
        if(!isset($data['plugins'])||$data['plugins']!='wshop-alipay'){
            return;
        }
        
        $api =WShop_Payment_Gateway_Wpopen_Alipay::instance();
        $appkey =$api->get_option('appsecret');
        $hash =$api->generate_xh_hash($data,$appkey);
        if($data['hash']!=$hash){
            return;
        }
        
        if($data['status']=='OD'){
            $order = WShop::instance()->payment->get_order('sn', $data['trade_order_id']);
            if(!$order){
                WShop_Log::error('invalid order:'.print_r($data,true));
                return;
            }
            
            $error =$order->complete_payment($data['transaction_id']);
            if(!WShop_Error::is_valid($error)){
                WShop_Log::error('complete_payment fail:'.$error->errmsg);
                echo 'faild!';
                exit;
            }
        }
        
        $params = array(
            'action'=>'success',
            'appid'=>$api->get_option('appid')
        );
        
        $params['hash']=$api->generate_xh_hash($params, $appkey);
        print json_encode($params);
        exit;
	}
}