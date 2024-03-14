<?php
/*
 * Plugin Name: درگاه بانک پاسارگاد برای ووکامرس
 * Plugin URI: http://blog.alafalaki.ir/%D8%A7%D9%81%D8%B2%D9%88%D9%86%D9%87-%D9%BE%D8%B1%D8%AF%D8%A7%D8%AE%D8%AA-%D8%A8%D8%A7%D9%86%DA%A9-%D9%BE%D8%A7%D8%B3%D8%A7%D8%B1%DA%AF%D8%A7%D8%AF-%D9%88%D9%88%DA%A9%D8%A7%D9%85%D8%B1%D8%B3/
 * Description: جهت تطبیق درگاه پرداخت بانک پاسارگاد با سیستم مدیریت محتوای ووکامرس
 * Version: 2.5.2
 * Author: AlaFalaki
 * Author URI: http://AlaFalaki.ir
 * 
 */

add_action('plugins_loaded', 'pasargad_bwg', 0); // Make The Plugin Work...

function pasargad_bwg() {
    if ( !class_exists( 'WC_Payment_Gateway' ) ) return; // import your gate way class extends/
	
    class pasargad_bwg_full_payment extends WC_Payment_Gateway {
        public function __construct(){
        	
            $this -> id 			 	 = 'pasargad_bwg';
            $this -> method_title 	  	 = 'درگاه بانک پاسارگاد';
			$this->icon 				 = WP_PLUGIN_URL . "/" . plugin_basename(dirname(__FILE__)) . '/images/logo.png';
            $this -> has_fields 	   	 = false;
            $this -> init_form_fields();
            $this -> init_settings();
			
			$this -> title					= $this-> settings['title'];
			$this -> description			= $this-> settings['description'];
			$this -> merchantCode			= $this-> settings['merchantCode'];
			$this -> terminalCode			= $this-> settings['terminalCode'];
			$this -> redirect_page_id		= $this-> settings['redirect_page_id'];
			$this -> privateKey 			= $this-> settings['privateKey'];
 
			$this -> msg['pasargad_bwg_message'] = "";
			$this -> msg['pasargad_bwg_class'] = "";
 
			add_action('woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'pasargad_bwg_check_response' ) );

  		    if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) { // Compatibalization plugin for diffrent versions.
                add_action( 'woocommerce_update_options_payment_gateways_pasargad_bwg', array( &$this, 'process_admin_options' ) );
             } else {
                add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
            }
			 
			add_action('woocommerce_receipt_pasargad_bwg', array(&$this, 'pasargad_bwg_receipt_page'));
        }

		/**
		 * Declaring admin page fields.
		 */
       function init_form_fields(){
            $this -> form_fields = array(
                'enabled' => array(
                    'title' => 'فعال سازی/غیر فعال سازی :',
                    'type' => 'checkbox',
                    'label' => 'فعال سازی درگاه پرداخت بانک پاسارگاد',
                    'description' => 'برای امکان پرداخت کاربران از طریق این درگاه باید تیک فعال سازی زده شده باشد .',
                    'default' => 'no'),
                'merchantCode' => array(
                    'title' => 'شماره پذیزنده :',
                    'type' => 'text',
                    'description' => 'شما میتوانید این کد را از بانک ارائه دهنده درگاه دریافت نمایید .'),
                'terminalCode' => array(
                    'title' => 'شماره ترمینال :',
                    'type' => 'text',
                    'description' => 'شما میتوانید این کد را از بانک ارائه دهنده درگاه دریافت نمایید .'),
                'privateKey' => array(
                    'title' => 'کد مخفی PrivateKey :',
                    'type' => 'textarea',
                    'description' => 'این کد در زمان دریافت درگاه توسط نرم‌افزاری از طرف بانک پاسارگاد تولید می‌شود .'),
                'title' => array(
                    'title' => 'عنوان درگاه :',
                    'type'=> 'text',
                    'description' => 'این عتوان در سایت برای کاربر نمایش داده می شود .',
                    'default' => 'بانک پاسارگاد'),
                'description' => array(
                    'title' => 'توضیحات درگاه :',
                    'type' => 'textarea',
                    'description' => 'این توضیحات در سایت، بعد از انتخاب درگاه توسط کاربر نمایش داده می شود .',
                    'default' => 'پرداخت وجه از طریق درگاه بانک پاسارگاد توسط تمام کارت های عضو شتاب .'),
				'redirect_page_id' => array(
                    'title' => 'آدرس بازگشت',
                    'type' => 'select',
                    'options' => $this -> pasargad_bwg_get_pages('صفحه مورد نظر را انتخاب نمایید'),
                    'description' => "صفحه‌ای که در صورت پرداخت موفق نشان داده می‌شود را نشان دهید."),
            );
        }
        public function admin_options(){
            echo '<h3>درگاه پرداخت بانک پاسارگاد</h3>';
			echo '<table class="form-table">';
			echo 
			// IRR
			// IRT
			$this -> generate_settings_html();
			echo '</table>';
			echo '
				<div>
					<a href="http://blog.alafalaki.ir/%D9%BE%D9%84%D8%A7%DA%AF%DB%8C%D9%86-jppayment-%D8%A8%D8%B1%D8%A7%DB%8C-woocommerce-%D9%81%D8%B1%D9%88%D8%B4%DA%AF%D8%A7%D9%87-%D8%B3%D8%A7%D8%B2/">صفحه رسمی پلاگین + مستندات .</a><br />
					<a href="https://github.com/AlaFalaki/Jppayment" target="_blank">حمایت از پروژه در GitHub .</a><br />
					<a href="https://twitter.com/AlaFalaki" target="_blank">من را در تویتر دنبال کنید .</a>
				</div>
			
				<script>
					// Set Key To Text For Human To Understand
					key = document.getElementById("woocommerce_pasargad_bwg_privateKey").value;
					document.getElementById("woocommerce_pasargad_bwg_privateKey").value = decodeURI(key);				

					// Perform URL endoce to WP wont filter XML characters
					var ele = document.getElementById("mainform");
					if(ele.addEventListener){
					    ele.addEventListener("submit", keyMaker, false);  //Modern browsers
					}else if(ele.attachEvent){
					    ele.attachEvent("onsubmit", keyMaker);            //Old IE
					}

					function keyMaker()
					{
						key = document.getElementById("woocommerce_pasargad_bwg_privateKey").value;
						document.getElementById("woocommerce_pasargad_bwg_privateKey").value = encodeURI(key);
					}
				</script>
			';
		}
        /**
         * Receipt page.
         **/
		function pasargad_bwg_receipt_page($order_id){
			if (!class_exists('PasargadBank_GateWay')) { 
				require_once("pasargadGatewayClass.php"); // Add Pasargad class To Plugin
			}
            
            global $woocommerce;
			
            $order = new WC_Order($order_id);
			
			
            $callback 				= ($this -> redirect_page_id=="" || $this -> redirect_page_id==0)?get_site_url() . "/":get_permalink($this -> redirect_page_id);
			$callback 				= add_query_arg( 'wc-api', get_class( $this ), $callback );

			$merchantCode			= $this->merchantCode;
			$terminalCode			= $this->terminalCode;
			$privateKey				= str_replace("[AlaFalaki]", "+", urldecode( str_replace("+", "[AlaFalaki]", $this->privateKey )));
			$order_total			= round($order -> order_total);

			if(get_woocommerce_currency() == "IRT")
			{
				$order_total = $order_total*10;
			}
			
				$gateWay = new PasargadBank_GateWay();
				$gateWay->SendOrder($order_id,date("Y/m/d H:i:s"),$order_total, $merchantCode, $terminalCode, $callback, $privateKey);
        }
        
        /**
         * Process_payment Function.
         **/
        function process_payment($order_id){
            $order = new WC_Order($order_id);
            return array('result' => 'success', 'redirect' => add_query_arg('order',
                $order->id, add_query_arg('key', $order->order_key, $this->get_return_url($this->order)))
            );
        }
 

		/**
		 * Check for valid payu server callback
		 **/
		function pasargad_bwg_check_response(){
			global $woocommerce;
			
			if (!class_exists('PasargadBank_GateWay')) { 
				require_once ("pasargadGatewayClass.php");
			}
			session_start();
			$order_id 				= $_GET['iN'];
			$tref 					= $_GET['tref'];
			$order 					= new WC_Order($order_id);

			$merchantCode			= $this -> merchantCode;
			$terminalCode			= $this -> terminalCode;
			$privateKey				= str_replace("[AlaFalaki]", "+", urldecode( str_replace("+", "[AlaFalaki]", $this->privateKey )));

			$OrderStatus 			= new PasargadBank_GateWay();

			$order_total			= round($order -> order_total);

			if(get_woocommerce_currency() == "IRT")
			{
				$order_total = $order_total*10;
			}
			
			$result = $OrderStatus->getOrder($_GET['tref']);

			if(($_SESSION['pasargadAmount']) == $order_total){

				if($result['resultObj']['result'] == "True"){ // Check the result.

					if($OrderStatus->verifyOrder($merchantCode, $terminalCode, $privateKey)){
						if($order->status !=='completed'){
			                    $this -> msg['pasargad_bwg_class'] = 'woocommerce_message';
					            $this -> msg['pasargad_bwg_message'] = "پرداخت شما با موفقیت انجام شد.";

								$order->payment_complete();
			                    $order->add_order_note('پرداخت موفق، کد پرداخت: '.$tref);
			                    $woocommerce->cart->empty_cart();
						}
					}else{
	                    $this -> msg['pasargad_bwg_class'] = 'woocommerce_error';
			            $this -> msg['pasargad_bwg_message'] = "پرداخت شما تایید نشد.";

						$order -> add_order_note('پرداخت تایید نشد.');
					}

				}else{
	                $this -> msg['pasargad_bwg_class'] = 'woocommerce_error';
			        $this -> msg['pasargad_bwg_message'] = "پرداخت ناموفق بود.";

					$order -> add_order_note('پرداخت ناموفق بود.');
				}

			}else{
	            $this -> msg['pasargad_bwg_class'] = 'woocommerce_error';
			    $this -> msg['pasargad_bwg_message'] = "پرداخت نامعتبر.";

				$order -> add_order_note('پرداخت نا معتبر.');
			}

			unset($_SESSION['pasargadAmount']);

			$redirect_url = ($this->redirect_page_id=="" || $this->redirect_page_id==0)?get_site_url() . "/":get_permalink($this->redirect_page_id);
			$redirect_url = add_query_arg( array('pasargad_bwg_message'=> urlencode($this->msg['pasargad_bwg_message']), 'pasargad_bwg_class'=>$this->msg['pasargad_bwg_class'], 'tref' => $tref), $redirect_url );
			wp_redirect( $redirect_url );
			exit;
		}

		// get all pages
		public function pasargad_bwg_get_pages($title = false, $indent = true) {
		    $wp_pages = get_pages('sort_column=menu_order');
		    $page_list = array();
		    if ($title) $page_list[] = $title;
		    foreach ($wp_pages as $page) {
		        $prefix = '';
		        // show indented child pages?
		        if ($indent) {
		            $has_parent = $page->post_parent;
		            while($has_parent) {
		                $prefix .=  ' - ';
		                $next_page = get_page($has_parent);
		                $has_parent = $next_page->post_parent;
		            }
		        }
		        // add to page list array array
		        $page_list[$page->ID] = $prefix . $page->post_title;
		    }
		    return $page_list;
		}

	}
    /**
     * Add the Gateway to WooCommerce.
     **/
    function woocommerce_add_pasargad_bwg_gateway($methods) {
        $methods[] = 'pasargad_bwg_full_payment';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_pasargad_bwg_gateway' );


}


if( isset($_GET['pasargad_bwg_message']) )
{
	add_action('the_content', 'pasargad_bwg_show_message');

	function pasargad_bwg_show_message($content)
	{
		return '<div class="'.htmlentities($_GET['pasargad_bwg_class']).'">'.urldecode($_GET['pasargad_bwg_message']).'<br />شماره پیگیری پرداخت: ' . urldecode($_GET['tref']) . '</div>'.$content;
	}
}


?>