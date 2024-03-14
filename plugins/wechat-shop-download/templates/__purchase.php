<?php 
/**
 * 提供三种模式的结算样式
 * 
 * 1.弹窗选择多支付方式
 * 2.弹窗扫码支付
 * 3.购物车模式的结算页面
 * 
 * @version 1.0.3
 */
if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly

$data = WShop_Temp_Helper::clear('atts','templates');
$context = isset($data['context'])?$data['context']:null;
if(empty($context)){
    $context = WShop_Helper::generate_unique_id();
}

$style = isset($data['style'])?$data['style']:null;
$class = isset($data['class'])?$data['class']:'xh-btn xh-btn-danger xh-btn-lg';
$location = isset($data['location'])&&!empty($data['location'])?esc_url_raw($data['location']):WShop_Helper_Uri::get_location_uri();
$content = isset($data['content'])&&!empty($data['content'])?$data['content']:__('Pay now',WSHOP);


$modal = isset($data['modal'])&&!empty($data['modal'])?$data['modal']:WShop_Settings_Checkout_Options::instance()->get_option('modal','shopping_list');

//定义支付接口
$section = isset($data['section'])&&!empty($data['section'])?$data['section']:  null;
//移动端，不出现扫码
if($modal=='shopping_one_step'&&WShop_Helper_Uri::is_app_client()){$modal = 'shopping_list';}
$tab = isset($data['tab'])&&!empty($data['tab'])?$data['tab']:"purchase_modal_{$modal}";

//判断是否符合支付条件
if(!is_user_logged_in()&&!WShop::instance()->WP->is_enable_guest_purchase()){
    $request_url=wp_login_url($location);
    ?>
    <a href="<?php echo $request_url;?>" class="xunhu-btn xunhu-btn-green xunhu-btn-block xunhu-btn-lg <?php echo $class?>" style="<?php echo $style?>">请登录后，进行支付</a>
    <?php
    return;
}

?>
 <script type="text/javascript">
 	(function($){
    	$(document).bind('wshop_form_<?php echo $context?>_submit',function(e,settings){
    		 settings.ajax.url = '<?php echo esc_url_raw(WShop::instance()->ajax_url(array('action'=>'wshop_checkout_v2','tab'=>$tab,'section'=>$section),true,true))?>';
    		 settings.location='<?php echo esc_js($location);?>';
    	});
	 })(jQuery);
 </script>
<?php 

$sections = array(
    'shopping_list'=>'<a id="btn-pay-button-'.esc_attr($context).'" onclick="window.wshop_jsapi.shopping_list(\''.esc_attr($context).'\');" href="javascript:void(0);" class="xunhu-btn xunhu-btn-border-green '.esc_attr($class).'" style="'.esc_attr($style).'">'.do_shortcode($content).'</a>',
    'shopping_one_step'=>'<a id="btn-pay-button-'.esc_attr($context).'" onclick="window.wshop_jsapi.shopping_one_step(\''.esc_attr($context).'\');" href="javascript:void(0);" class="xunhu-btn xunhu-btn-border-green '.esc_attr($class).'" style="'.esc_attr($style).'">'.do_shortcode($content).'</a>',
    'shopping_cart'=>'<a id="btn-pay-button-'.esc_attr($context).'" onclick="window.wshop_jsapi.shopping_cart(\''.esc_attr($context).'\');" href="javascript:void(0);" class="xunhu-btn xunhu-btn-border-green '.esc_attr($class).'" style="'.esc_attr($style).'">'.do_shortcode($content).'</a>',
    'shopping'=>'<a id="btn-pay-button-'.esc_attr($context).'" onclick="window.wshop_jsapi.shopping(\''.esc_attr($context).'\');" href="javascript:void(0);" class="xunhu-btn xunhu-btn-green xunhu-btn-block xunhu-btn-lg '.esc_attr($class).'" style="'.esc_attr($style).'">'.do_shortcode($content).'</a>'
);

echo isset($sections[$modal])?$sections[$modal]:$sections['shopping'];