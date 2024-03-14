<?php
if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly
class XH_Alipay_Payment_WC_Payment_Gateway extends WC_Payment_Gateway {
    private $instructions;
	public function __construct() {
		$this->id                 = XH_Alipay_Payment_ID;
		$this->icon               = XH_Alipay_Payment_URL . '/images/logo/alipay.png';
		$this->has_fields         = false;

		$this->method_title       = __('Alipay Payment',XH_Alipay_Payment);
		$this->method_description = __('Helps to add Alipay payment gateway that supports the features including QR code payment.',XH_Alipay_Payment);

		$this->title              = $this->get_option ( 'title' );
		$this->description        = $this->get_option ( 'description' );
		$this->instructions       = $this->get_option('instructions');

		$this->init_form_fields ();
		$this->init_settings ();

		$this->enabled            = $this->get_option ( 'enabled' );

		add_filter ( 'woocommerce_payment_gateways', array($this,'woocommerce_add_gateway') );
		add_action ( 'woocommerce_update_options_payment_gateways_' .$this->id, array ($this,'process_admin_options') );
		add_action ( 'woocommerce_update_options_payment_gateways', array ($this,'process_admin_options') );
		add_action ( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		add_action ( 'woocommerce_thankyou_'.$this->id, array( $this, 'thankyou_page' ) );
	}

	public function notify(){
	    global $XH_Alipay_Payment_WC_Payment_Gateway;

	    $data = $_POST;
	    if(!isset($data['hash'])
	        ||!isset($data['trade_order_id'])){
	            return;
	    }
	    if(isset($data['plugins'])&&$data['plugins']!='woo-alipay'){
	        return;
	    }
	    $appkey =$XH_Alipay_Payment_WC_Payment_Gateway->get_option('appsecret');
	    $hash =$XH_Alipay_Payment_WC_Payment_Gateway->generate_xh_hash($data,$appkey);
	    if($data['hash']!=$hash){
	        return;
	    }

	    $order = wc_get_order($data['trade_order_id']);
	    try{
	        if(!$order){
	            throw new Exception('Unknow Order (id:'.$data['trade_order_id'].')');
	        }

	        if(!(method_exists($order, 'is_paid')?$order->is_paid():in_array($order->get_status(),  array( 'processing', 'completed' )))&&$data['status']=='OD'){
	            $order->payment_complete(isset($data['transacton_id'])?$data['transacton_id']:'');
	        }
	    }catch(Exception $e){
	        //looger
	        $logger = new WC_Logger();
	        $logger->add( 'xh_wedchat_payment', $e->getMessage() );

	        $params = array(
	            'action'=>'fail',
	            'appid'=>$XH_Alipay_Payment_WC_Payment_Gateway->get_option('appid'),
	            'errcode'=>$e->getCode(),
	            'errmsg'=>$e->getMessage()
	        );

	        $params['hash']=$XH_Alipay_Payment_WC_Payment_Gateway->generate_xh_hash($params, $appkey);
	        ob_clean();
	        print json_encode($params);
	        exit;
	    }

	    $params = array(
	        'action'=>'success',
	        'appid'=>$XH_Alipay_Payment_WC_Payment_Gateway->get_option('appid')
	    );

	    $params['hash']=$XH_Alipay_Payment_WC_Payment_Gateway->generate_xh_hash($params, $appkey);
	    ob_clean();
	    print json_encode($params);
	    exit;
	}
	public function woocommerce_add_gateway($methods) {
	    $methods [] = $this;
	    return $methods;
	}

	public function process_payment($order_id) {
	    $turl = $this->get_option('tranasction_url');
// 	    if(stripos($turl, 'https://pay.xunhupay.com/v2')===0){
// 	        $request = array(
// 	            'action'=>'-hpj-alipay-do-pay',
// 	            'order_id'=>$order_id,
// 	            'time'=>time(),
// 	            'notice_str'=>str_shuffle(time())
// 	        );
// 	        ksort($request);
// 	        reset($request);
// 	        $request['hash'] = md5(http_build_query($request).AUTH_KEY);
// 	        $pay_url = home_url('/?'.http_build_query($request));
// 	        return array(
// 	            'result'  => 'success',
// 	            'redirect'=> $pay_url
// 	        );
// 	    }

		$order            = wc_get_order ( $order_id );
		if(!$order||(method_exists($order, 'is_paid')?$order->is_paid():in_array($order->get_status(),  array( 'processing', 'completed' )))){
		    return array (
		        'result' => 'success',
		        'redirect' => $this->get_return_url($order)
		    );
		}

		$expire_rate      = floatval($this->get_option('exchange_rate',1));
		if($expire_rate<=0){
		    $expire_rate=1;
		}

		$total_amount     = round($order->get_total()*$expire_rate,2);
		$siteurl = rtrim(home_url(),'/');
		$posi =strripos($siteurl, '/');
		//若是二级目录域名，需要以“/”结尾，否则会出现403跳转
		if($posi!==false&&$posi>7){
		    $siteurl.='/';
		}
		$data=array(
		      'version'   => '1.1',//api version
		      'lang'       => get_option('WPLANG','zh-cn'),
		      'is_app'    => $this->isWebApp()?'Y':'N',
		      'plugins'   => 'woo-alipay',
		      'appid'     => $this->get_option('appid'),
		      'trade_order_id'=> $order_id,
		      'payment'   => 'alipay',
		      'total_fee' => $total_amount,
		      'title'     => $this->get_order_title($order),
		      'description'=> null,
		      'time'      => time(),
		      'notify_url'=> $siteurl,
		      'return_url'=> $this->get_return_url($order),
		      'callback_url'=>wc_get_checkout_url(),
		      'nonce_str' => str_shuffle(time())
		);

		$hashkey          = $this->get_option('appsecret');
		$data['hash']     = $this->generate_xh_hash($data,$hashkey);
		$url              = rtrim($this->get_option('transaction_url'),'/').'/payment/do.html';

		try {
		    $response     = $this->http_post($url, json_encode($data));
		    $result       = $response?json_decode($response,true):null;
		    if(!$result){
		        throw new Exception('Internal server error',500);
		    }

		    $hash         = $this->generate_xh_hash($result,$hashkey);
		    if(!isset( $result['hash'])|| $hash!=$result['hash']){
		        throw new Exception(__('Invalid sign!',XH_Alipay_Payment),40029);
		    }

		    if($result['errcode']!=0){
		        throw new Exception($result['errmsg'],$result['errcode']);
		    }

		    return array(
		        'result'  => 'success',
		        'redirect'=> $result['url']
		    );
		} catch (Exception $e) {
		    wc_add_notice("errcode:{$e->getCode()},errmsg:{$e->getMessage()}",'error');
		    return array(
		        'result' => 'fail',
		        'redirect' => $this->get_return_url($order)
		    );
		}
	}
	public  function isWebApp(){
	    if(!isset($_SERVER['HTTP_USER_AGENT'])){
	        return false;
	    }

	    $u=strtolower($_SERVER['HTTP_USER_AGENT']);
	    if($u==null||strlen($u)==0){
	        return false;
	    }

	    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/',$u,$res);

	    if($res&&count($res)>0){
	        return true;
	    }

	    if(strlen($u)<4){
	        return false;
	    }

	    preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/',substr($u,0,4),$res);
	    if($res&&count($res)>0){
	        return true;
	    }

	    $ipadchar = "/(ipad|ipad2)/i";
	    preg_match($ipadchar,$u,$res);
	    return $res&&count($res)>0;
	}
	private function http_post($url,$data){
	    if(!function_exists('curl_init')){
	        throw new Exception('php未安装curl组件',500);
	    }

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	    curl_setopt($ch,CURLOPT_URL, $url);
	    curl_setopt($ch,CURLOPT_REFERER,get_option('siteurl'));
	    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
	    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    $response = curl_exec($ch);
	    $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    $error=curl_error($ch);
	    curl_close($ch);
	    if($httpStatusCode!=200){
	        throw new Exception("invalid httpstatus:{$httpStatusCode} ,response:$response,detail_error:".$error,$httpStatusCode);
	    }

	    return $response;
	}

	public function generate_xh_hash(array $datas,$hashkey){
	    ksort($datas);
	    reset($datas);

	    $pre =array();
	    foreach ($datas as $key => $data){
	        if(is_null($data)||$data===''){continue;}
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

	private function is_alipay_app(){
	    return strripos($_SERVER['HTTP_USER_AGENT'],'micromessenger');
	}

	public function thankyou_page() {
	    if ( $this->instructions ) {
	        echo wpautop( wptexturize( $this->instructions ) );
	    }
	}

	/**
	 * Add content to the WC emails.
	 *
	 * @access public
	 * @param WC_Order $order
	 * @param bool $sent_to_admin
	 * @param bool $plain_text
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
	    $method = method_exists($order ,'get_payment_method')?$order->get_payment_method():$order->payment_method;
	    if ( $this->instructions && ! $sent_to_admin && $this->id === $method) {
	        echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
	    }
	}
	/**
	 * Initialise Gateway Settings Form Fields
	 *
	 * @access public
	 * @return void
	 */
	function init_form_fields() {
		$this->form_fields = array (
				'enabled' => array (
						'title'       => __('Enable/Disable',XH_Alipay_Payment),
						'type'        => 'checkbox',
						'label'       => __('Enable/Disable the alipay payment',XH_Alipay_Payment),
						'default'     => 'no',
						'section'     => 'default'
				),
				'title' => array (
						'title'       => __('Payment gateway title',XH_Alipay_Payment),
						'type'        => 'text',
						'default'     =>  __('Alipay Payment',XH_Alipay_Payment),
						'desc_tip'    => true,
						'css'         => 'width:400px',
						'section'     => 'default'
				),
				'description' => array (
						'title'       => __('Payment gateway description',XH_Alipay_Payment),
						'type'        => 'textarea',
						'default'     => __('QR code payment or OA native payment, credit card',XH_Alipay_Payment),
						'desc_tip'    => true,
						'css'         => 'width:400px',
						'section'     => 'default'
				),
				'instructions' => array(
    					'title'       => __( 'Instructions', XH_Alipay_Payment ),
    					'type'        => 'textarea',
    					'css'         => 'width:400px',
    					'description' => __( 'Instructions that will be added to the thank you page.', XH_Alipay_Payment ),
    					'default'     => '',
    					'section'     => 'default'
				),
				'appid' => array(
    					'title'       => __( 'APP ID', XH_Alipay_Payment ),
    					'type'        => 'text',
    					'css'         => 'width:400px',
    					'section'     => 'default',
				        'default'=>'201906120423',
                        'description'=>'帮助文档：https://www.xunhupay.com/147.html'
				),
				'appsecret' => array(
    					'title'       => __( 'APP Secret', XH_Alipay_Payment ),
    					'type'        => 'text',
    					'css'         => 'width:400px',
    				    'default'=>'011bec80cd19c154d152d1bda4b584ea',
    					'section'     => 'default'
				),
				'transaction_url' => array(
    					'title'       => __( 'Transaction Url', XH_Alipay_Payment ),
    					'type'        => 'text',
    					'css'         => 'width:400px',
    				    'default'=>'https://api.xunhupay.com',
    					'section'     => 'default',
				        'description'=>''
				),
				'exchange_rate' => array (
    					'title'       => __( 'Exchange Rate',XH_Alipay_Payment),
    					'type'        => 'text',
    					'default'     => '1',
    					'description' => __(  'Set the exchange rate to RMB. When it is RMB, the default is 1',XH_Alipay_Payment),
    					'css'         => 'width:400px;',
    					'section'     => 'default'
				)
		);
	}

	private function is_wechat_app(){
	    return strripos($_SERVER['HTTP_USER_AGENT'],'micromessenger');
	}

	public function get_order_title($order, $limit = 98) {
	    $order_id = method_exists($order, 'get_id')? $order->get_id():$order->id;
		$title ="#{$order_id}";

		$order_items = $order->get_items();
		if($order_items){
		    $qty = count($order_items);
		    foreach ($order_items as $item_id =>$item){
		        $title.="|{$item['name']}";
		        break;
		    }
		    if($qty>1){
		        $title.='...';
		    }
		}

		$title = mb_strimwidth($title, 0, $limit,'utf-8');
		return apply_filters('xh-payment-get-order-title', $title,$order);
	}
}

?>
