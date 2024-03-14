<?php
function qhp_is_JSON(...$args) {
    if(is_array(...$args)) return true;
    json_decode(...$args);
    return (json_last_error()===JSON_ERROR_NONE);
}
function qhp_valid_options(&$array) {
	foreach ($array as $key => &$value) {
        if (is_object($value)) {
            unset($array[$key]);
        } elseif (is_array($value)) {
            qhp_valid_options($value);
        }
    }
}

function qhp_recursive_sanitize_text_field($array) {
    foreach ( $array as $key => &$value ) {
        if ( is_array( $value ) ) {
            $value = qhp_recursive_sanitize_text_field($value);
        }
        else {
            $value = sanitize_text_field( $value );
        }
    }

    return $array;
}
function qhp_generate_random_string($length = 10, $characters=null) {
	if($characters==null) $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function qhp_getHeader(){
	$headers = array();

    $copy_server = array(
        'CONTENT_TYPE'   => 'Content-Type',
        'CONTENT_LENGTH' => 'Content-Length',
        'CONTENT_MD5'    => 'Content-Md5',
    );

    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) === 'HTTP_') {
            $key = substr($key, 5);
            if (!isset($copy_server[$key]) || !isset($_SERVER[$key])) {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                $headers[$key] = $value;
            }
        } elseif (isset($copy_server[$key])) {
            $headers[$copy_server[$key]] = $value;
        }
    }

    if (!isset($headers['Authorization'])) {
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $headers['Authorization'] = sanitize_text_field($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        } elseif (isset($_SERVER['PHP_AUTH_USER'])) {
            $basic_pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
            $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
        } elseif (isset($_SERVER['PHP_AUTH_DIGEST'])) {
            $headers['Authorization'] = sanitize_text_field($_SERVER['PHP_AUTH_DIGEST']);
        }
    }

    return $headers;
}
function qhp_parse_code($des, $prefix, $insensitive){
	//TODO : Rewrite this function.
	//phân biệt
	if ($insensitive=='yes') {
		$re = '/'.$prefix.'\d+/m';
	}else{
		$re = '/'.$prefix.'\d+/mi';	//$this->get_option( 'transaction_prefix' )
	}

	preg_match_all($re, $des, $matches, PREG_SET_ORDER, 0);

	if (count($matches) == 0 )
		return null;
	// Print the entire match result
	$orderCode = $matches[0][0];
	return $orderCode;
}
function qhp_parse_order_id($des, $prefix, $insensitive){
	$orderCode = qhp_parse_code($des, $prefix, $insensitive);
	$prefixLength = strlen($prefix);

	$orderId = intval(substr($orderCode, $prefixLength ));
	return $orderId ;

}
function qhp_clean_prefix($string)
{
	$string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
	if (strlen($string) > 15) {
		$string = substr($string, 0, 15);
	}
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function qhp_getCurrentDomain()
{
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

	$url = sanitize_url($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	return $url; // Outputs: Full URL

	//$query = $_SERVER['QUERY_STRING'];
	//echo $query; // Outputs: Query String
}

function qhp_reset_token() {
	$opt = QHPayPayment::get_settings();
	if(!empty($opt['bank_transfer']['secure_token'])) {
		unset($opt['bank_transfer']['secure_token']);
		QHPayPayment::update_settings($opt);
	}
}

add_action( 'rest_api_init', 'qhp_rest' ); 
function qhp_rest() {
	register_rest_route('qhtp/v1','/qrcode',array(
		'methods' => 'GET',
		'callback' => 'qhp_rest_qrcode',
    	'permission_callback'=>'__return_true'
	));
}

function qhp_rest_qrcode() {
	include QHTP_DIR."/lib/phpqrcode/qrlib.php";
	$app = isset($_GET['app'])? sanitize_text_field($_GET['app']): '';
	$phone = isset($_GET['phone']) ?   sanitize_text_field($_GET['phone']) : "";
	$price = isset($_GET['price']) ?   sanitize_text_field($_GET['price']) : "";
	$content = isset($_GET['content'])? sanitize_text_field($_GET['content']): '';
	//filter_var(, FILTER_SANITIZE_STRING)
	if($phone && $price){
		if($app=='momo') {
			$text = sprintf("2|99|%s|||0|0|%d", $phone, $price);
			$img = QHTP_DIR.'/assets/momo.png';
			QRcode::png($text, false, QR_ECLEVEL_Q, 10); 
		}
		if($app=='viettelpay') {
			$text = json_encode([
				"bankCode"=>'VTT',
				"bankcodeList"=>["VTT"],
				"cust_mobile"=>$phone,
				'transAmountList'=>[$price],
				"trans_amount"=> $price,
				'trans_content'=> $content,
				"transfer_type"=>"MYQR",
			]);
			QRcode::png($text,false, QR_ECLEVEL_Q, 10); 
		}
	}else{
		$name = plugin_dir_path( __FILE__ ) . 'assets/qr-fail.png';
		$fp = fopen($name, 'rb');

		header("Content-Type: image/png");
		header("Content-Length: " . filesize($name));

		fpassthru($fp);
	}
	
	
	die();
}

add_filter( 'wp_kses_allowed_html', function($allowed_html){
	$atts = array(
		'class' => array(),
		'href'  => array(),
		'rel'   => array(),
		'title' => array(),
		'onclick'=>array(),'value'=>array(),'src'=>array(),
		'name'=>array(),'id'=>array(),'style'=>array(),'type'=>array(),'class'=>array(),
	);
	$allowed_html['style'] = $atts;
	$allowed_html['script'] = $atts;
	foreach(['button','img'] as $tag) $allowed_html[$tag] = $atts;
	return $allowed_html;
}, 999999);

add_filter( 'safe_style_css', function( $styles ) {
    $styles[] = 'display';
    return $styles;
} );

/**
 * admin columns
*/
add_action('woocommerce_admin_order_data_after_shipping_address', function($order){
    //$order_data = $order->get_data();
    $payment = $order->get_payment_method();
    $content = $order->get_meta('qhtp_ndck');

    $ui = '<div>';
    $ui.= QHPayPayment::get_bank_icon(WC_Base_QHPay::payment_name($payment),true);
    if($content) $ui.= '<div><code>'.$content.'</code></div>';
    $ui.= '</div>';

    echo wp_kses_post($ui);
});

add_filter('woocommerce_my_account_my_orders_columns', function($columns){
	$new_columns = array();
	$i=0;$n = count($columns);
	foreach($columns as $id=> $text) {
		
		if(++$i==$n) {
			$new_columns['qhtp_bank'] = __('Bank', 'qh-testpay');
		}
		$new_columns[$id] = $text;
	}
	
	return $new_columns;
	
},20);

add_action('woocommerce_my_account_my_orders_column_qhtp_bank', function( $order ){
	$payment = $order->get_payment_method();
	if($payment) echo QHPayPayment::get_bank_icon(WC_Base_QHPay::payment_name($payment),true);
}, 20 );

add_filter( 'manage_edit-shop_order_columns', function($columns){
	$new_columns = array();
	$i=0;$n = count($columns);
	foreach($columns as $id=> $text) {
		
		if(++$i==$n-1) {
			$new_columns['qhtp_bank'] = __('Bank', 'qh-testpay');
		}
		$new_columns[$id] = $text;
	}
	
	return $new_columns;
}, 20 );

add_action( 'manage_shop_order_posts_custom_column' , function($column, $post_id){
	if($column=='qhtp_bank') {
		$order = wc_get_order($post_id);
		$payment = $order->get_payment_method();
		if($payment) {
			printf('<a href="%s" target="_blank">%s</a>', $order->get_checkout_order_received_url(), QHPayPayment::get_bank_icon(WC_Base_QHPay::payment_name($payment),true));
		}
	}

}, 20, 2 );

add_action( 'admin_notices', function () {
	global $pagenow;
	if($pagenow=='admin.php' && $_GET['page']=='qhtp') {//is-dismissible 
    ?>
    <div class="notice notice-success qhtp-notice">
    	<h3>Tích hợp Thanh Toán Quét Mã QR Code Tự Động - MoMo, ViettelPay, VNPay và 40 ngân hàng Việt Nam</h3>
    	<div style="display: table-cell;width: 65%">
    	<ul>
    		<li>Không cần giấy phép kinh doanh.</li>
    		<li><b >Không yêu cầu nhập user/pass hay mã OTP, an toàn tuyệt đối !</b><br>
    			<b style="font-style: italic;color: #FFA500;display: none">**Cảnh giác: Không đăng nhập user/pass hay mã OTP cho bất cứ dịch vụ không chính thống. Bạn luôn được các ngân hàng khuyến cáo vì sẽ lộ thông tin và bị chiếm quyền truy cập tài khoản..</b>
    		</li>
    		<li>Hỗ trợ QR code tự nhập tiền và nội dung đơn hàng (API tiêu chuẩn của Napas)</li>
    		<li><b>Xác nhận thanh toán tự động & kích hoạt đơn hàng từ 1~3 giây</b>.</li>
    		<li>Xử lý đa luồng, không giới hạn số lượng giao dịch.</li>
    		
    	</ul>
    	<strong style="text-decoration: underline;font-size: 18px">Yêu cầu:</strong>
    	<ul>
    		<li>Tải app "Xác nhận thanh toán tự động" trên Google Play. <a href="https://bck.haibasoft.com" target="_blank">Xem hướng dẫn</a></li>
    	</ul>

    	<p>Với 1 điện thoại cá nhân, bạn tích hợp <b style="color:red">KHÔNG GIỚI HẠN</b> website và tài khoản ngân hàng.</p>
    	</div>
    	<div style="display: table-cell;position: relative;">
    	<iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 90%;" width="824" height="464" src="https://www.youtube.com/embed/gWEuOxYW_mk" title="Tích hợp thanh Toán Quét Mã QR Code Tự Động" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    	</div>
    </div>
    <?php
	}
}, 9999999);

add_action('qhtp_admin_page_footer', function(){
	$ga_measure_id = 'G-K3JRPVQLG6';
	?>
	<a href="https://chat.zalo.me/?phone=0868292303" id="linkzalo" target="_blank" rel="noopener noreferrer"><div id="fcta-zalo-tracking" class="fcta-zalo-mess">
<span id="fcta-zalo-tracking">Chat hỗ trợ</span></div><div class="fcta-zalo-vi-tri-nut"><div id="fcta-zalo-tracking" class="fcta-zalo-nen-nut"><div id="fcta-zalo-tracking" class="fcta-zalo-ben-trong-nut"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460.1 436.6"><path fill="currentColor" class="st0" d="M82.6 380.9c-1.8-.8-3.1-1.7-1-3.5 1.3-1 2.7-1.9 4.1-2.8 13.1-8.5 25.4-17.8 33.5-31.5 6.8-11.4 5.7-18.1-2.8-26.5C69 269.2 48.2 212.5 58.6 145.5 64.5 107.7 81.8 75 107 46.6c15.2-17.2 33.3-31.1 53.1-42.7 1.2-.7 2.9-.9 3.1-2.7-.4-1-1.1-.7-1.7-.7-33.7 0-67.4-.7-101 .2C28.3 1.7.5 26.6.6 62.3c.2 104.3 0 208.6 0 313 0 32.4 24.7 59.5 57 60.7 27.3 1.1 54.6.2 82 .1 2 .1 4 .2 6 .2H290c36 0 72 .2 108 0 33.4 0 60.5-27 60.5-60.3v-.6-58.5c0-1.4.5-2.9-.4-4.4-1.8.1-2.5 1.6-3.5 2.6-19.4 19.5-42.3 35.2-67.4 46.3-61.5 27.1-124.1 29-187.6 7.2-5.5-2-11.5-2.2-17.2-.8-8.4 2.1-16.7 4.6-25 7.1-24.4 7.6-49.3 11-74.8 6zm72.5-168.5c1.7-2.2 2.6-3.5 3.6-4.8 13.1-16.6 26.2-33.2 39.3-49.9 3.8-4.8 7.6-9.7 10-15.5 2.8-6.6-.2-12.8-7-15.2-3-.9-6.2-1.3-9.4-1.1-17.8-.1-35.7-.1-53.5 0-2.5 0-5 .3-7.4.9-5.6 1.4-9 7.1-7.6 12.8 1 3.8 4 6.8 7.8 7.7 2.4.6 4.9.9 7.4.8 10.8.1 21.7 0 32.5.1 1.2 0 2.7-.8 3.6 1-.9 1.2-1.8 2.4-2.7 3.5-15.5 19.6-30.9 39.3-46.4 58.9-3.8 4.9-5.8 10.3-3 16.3s8.5 7.1 14.3 7.5c4.6.3 9.3.1 14 .1 16.2 0 32.3.1 48.5-.1 8.6-.1 13.2-5.3 12.3-13.3-.7-6.3-5-9.6-13-9.7-14.1-.1-28.2 0-43.3 0zm116-52.6c-12.5-10.9-26.3-11.6-39.8-3.6-16.4 9.6-22.4 25.3-20.4 43.5 1.9 17 9.3 30.9 27.1 36.6 11.1 3.6 21.4 2.3 30.5-5.1 2.4-1.9 3.1-1.5 4.8.6 3.3 4.2 9 5.8 14 3.9 5-1.5 8.3-6.1 8.3-11.3.1-20 .2-40 0-60-.1-8-7.6-13.1-15.4-11.5-4.3.9-6.7 3.8-9.1 6.9zm69.3 37.1c-.4 25 20.3 43.9 46.3 41.3 23.9-2.4 39.4-20.3 38.6-45.6-.8-25-19.4-42.1-44.9-41.3-23.9.7-40.8 19.9-40 45.6zm-8.8-19.9c0-15.7.1-31.3 0-47 0-8-5.1-13-12.7-12.9-7.4.1-12.3 5.1-12.4 12.8-.1 4.7 0 9.3 0 14v79.5c0 6.2 3.8 11.6 8.8 12.9 6.9 1.9 14-2.2 15.8-9.1.3-1.2.5-2.4.4-3.7.2-15.5.1-31 .1-46.5z"></path></svg></div><div id="fcta-zalo-tracking" class="fcta-zalo-text">Chat ngay</div></div></div></a>

<style>
@keyframes zoom{0%{transform:scale(.5);opacity:0}50%{opacity:1}to{opacity:0;transform:scale(1)}}@keyframes lucidgenzalo{0% to{transform:rotate(-25deg)}50%{transform:rotate(25deg)}}.jscroll-to-top{bottom:100px}.fcta-zalo-ben-trong-nut svg path{fill:#fff}.fcta-zalo-vi-tri-nut{position:fixed;bottom:24px;right:20px;z-index:999}.fcta-zalo-nen-nut,div.fcta-zalo-mess{box-shadow:0 1px 6px rgba(0,0,0,.06),0 2px 32px rgba(0,0,0,.16)}.fcta-zalo-nen-nut{width:50px;height:50px;text-align:center;color:#fff;background:#0068ff;border-radius:50%;position:relative}.fcta-zalo-nen-nut::after,.fcta-zalo-nen-nut::before{content:"";position:absolute;border:1px solid #0068ff;background:#0068ff80;z-index:-1;left:-20px;right:-20px;top:-20px;bottom:-20px;border-radius:50%;animation:zoom 1.9s linear infinite}.fcta-zalo-nen-nut::after{animation-delay:.4s}.fcta-zalo-ben-trong-nut,.fcta-zalo-ben-trong-nut i{transition:all 1s}.fcta-zalo-ben-trong-nut{position:absolute;text-align:center;width:60%;height:60%;left:10px;bottom:25px;line-height:70px;font-size:25px;opacity:1}.fcta-zalo-ben-trong-nut i{animation:lucidgenzalo 1s linear infinite}.fcta-zalo-nen-nut:hover .fcta-zalo-ben-trong-nut,.fcta-zalo-text{opacity:0}.fcta-zalo-nen-nut:hover i{transform:scale(.5);transition:all .5s ease-in}.fcta-zalo-text a{text-decoration:none;color:#fff}.fcta-zalo-text{position:absolute;top:6px;text-transform:uppercase;font-size:12px;font-weight:700;transform:scaleX(-1);transition:all .5s;line-height:1.5}.fcta-zalo-nen-nut:hover .fcta-zalo-text{transform:scaleX(1);opacity:1}div.fcta-zalo-mess{position:fixed;bottom:29px;right:58px;z-index:99;background:#fff;padding:7px 25px 7px 15px;color:#0068ff;border-radius:50px 0 0 50px;font-weight:700;font-size:15px}.fcta-zalo-mess span{color:#0068ff!important}
span#fcta-zalo-tracking{font-family:Roboto;line-height:1.5}.fcta-zalo-text{font-family:Roboto}
</style>

<script>
if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
{document.getElementById("linkzalo").href="https://zalo.me/0868292303";}
</script>
	<!-- ga4 -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_measure_id?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag() {
  dataLayer.push(arguments);
}
gtag("js", new Date());
gtag("config", "<?php echo $ga_measure_id?>");
//gtag("config", "TRACKING_ID_2");
gtag('event', 'page_view', {
    'user_id': location.hostname,
	page_title: location.hostname,
      page_location: null,
      page_path: '/',
      send_to: '<?php echo $ga_measure_id?>',
	user_properties:{}  
});
</script>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $ga_measure_id?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<?php
});

//test
//if(file_exists(dirname(__DIR__).'/test/test.func.php')) include dirname(__DIR__).'/test/test.func.php';