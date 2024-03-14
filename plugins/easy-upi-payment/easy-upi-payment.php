<?php
/*
Plugin Name: Easy UPI Payment
Plugin URI: https://noobs.group/easy-upi-payment-plugin-woocommerce/
Description: Accept Instant & Direct Payments through BHIM UPI.
Version: 1.0.0
Author: Noobs.Group
Author URI: https://noobs.group/
Text Domain: easy-upi-payment
Domain Path: /languages
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
Credit: 
This plugin use some functions from this plugin: 
https://wordpress.org/plugins/woo-upi-payment/
Copyright: (c) 2018, Kiasa LLP
Auhor: Kiasa
Author URI: http://kiasa.in/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}

function eupip_hook_css() {	
global $wp;
$order_id = $wp->query_vars['order-received'];
$order = new WC_Order( $order_id );
$payment_method = $order->get_payment_method();
$payment_gateway_data = WC()->payment_gateways->payment_gateways()['eupip'];

?>

<?php if( $payment_gateway_data->get_option('ewac') == 'yes' ): ?>
	
	<?php if( $payment_gateway_data->get_option('dpaymod') == 'yes' ): ?>
	
	<style>
	#TB_ajaxContent {
		height: 95.3% !important;
	}
	</style>

	<?php elseif( $payment_gateway_data->get_option('dpaymod') == 'no' ): ?>
	
	<style>
	#TB_ajaxContent {    
		height: 100% !important;
	}
	</style>
	
	<?php endif; ?>

<?php elseif( $payment_gateway_data->get_option('ewac') == 'no' ): ?>

<style>
#TB_ajaxContent {    
	height: 100% !important;
}
</style>

<?php else: ?>

<style>
#TB_ajaxContent {    
	height: 100% !important;
}
</style>

<?php endif; ?>

<style>

.paysite-logo{width: auto !important; height: 72px !important; margin: 16px auto 16px  auto !important; display: block !important;}

.woocommerce-order-received h1, h2{
	
}

.woocommerce-order-received .storefront-breadcrumb {
    margin: 0 0 0 !important;
}

#payment .place-order {
    margin-top: 4px !important;
}
#payment .payment_methods li .payment_box {
	padding: 8px 16px 8px 16px !important;
	}
#payment .payment_methods li img {
    max-height: 10px !important;
    margin-top: 10px !important;
    margin-bottom: 10px !important;
}

.upiqrcode{	
	background-repeat: no-repeat;
	background-size: cover;
	background-position: center;  
	width:100% !important; 
	height:200px !important; 
	max-width:200px !important; 
	display:block !important;
	margin:auto !important;
	border: 1px solid lightgray !important;
	border-radius: 2px !important;
}

.pyt-upi{	
	background-repeat: no-repeat;
	background-size: cover;
	background-position: center;  
	width:100% !important; 
	height:49px !important; 
	max-width:250px !important; 
	display:block !important;
	margin:auto !important;
	border: 5px solid lightgray !important;
	border-radius: 50px !important;
	border-style: double !important;
}

@media all and (max-width: 640px){
    #TB_window {
        top: 1% !important;
        left: 0 !important;
        margin-top: 0 !important;
        margin-left: 0 !important;
        height: 98% !important;
        width: 100% !important;
    }
}
@media all and (min-width: 640px){
#TB_window {
top: 35% !important;
height: 99% !important;
}
}

#TB_ajaxWindowTitle,#TB_ajaxContent {
    text-align: center !important;
}
#TB_ajaxWindowTitle {
    padding: 0 4px 0 4px !important;
}
#TB_ajaxContent {
    padding: 0px 16px 0px 16px !important;	
}	

#TB_iframeContent, #TB_ajaxContent, #TB_ajaxContent form *{
	max-width:100% !important; 
	width:100% !important; 	
}

#TB_ajaxContent p {
    margin: 11px 0 11px 0 !important;
}

#TB_ajaxContent details{
	margin: 16px 0 0 0!important;padding:0!important;
	}
#TB_ajaxContent details summary{
	font-size:12px !important; color:darkgray !important;margin:0 !important;padding:0!important;cursor:pointer!important;
	}	
#my_centered_buttons { 
display: flex; 
justify-content: center;
}		
.a2a_svg, .a2a_count { 
border-radius: 0 !important; 
}
</style>

<?php if ( $payment_method === 'eupip'){ ?>

<?php 
if( $payment_gateway_data->get_option('dpaymod') == 'no' ): 
if( is_wc_endpoint_url( 'order-received' ) && ! isset($_GET['tbox']) ): 
?>
<style>section, header, footer, article, aside, .storefront-breadcrumb, .woocommerce-store-notice, main, #primary, #secondary, .widget-area, .storefront-primary-navigation{display: none !important;}</style>
<?php 
endif;
endif;
 ?>

<style>
/* blinking div */
.blinking {
  animation: blinker 1s step-start infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>

<?php
 }
}
add_action('wp_head', 'eupip_hook_css');

function eupip_pg_files() {	
global $wp;
$order_id = $wp->query_vars['order-received'];
$order = new WC_Order( $order_id );
$payment_method = $order->get_payment_method();
$payment_gateway_data = WC()->payment_gateways->payment_gateways()['eupip'];

	wp_enqueue_script( 'addtoany-script', 'https://static.addtoany.com/menu/page.js', '', '', true );

if ( $payment_method === 'eupip'){
	
if( $payment_gateway_data->get_option('dpaymod') == 'no' ): 	

if( is_wc_endpoint_url( 'order-received' ) && ! isset($_GET['tbox']) ):

if ( ! wp_script_is( 'jquery', 'done' ) ) {
   wp_enqueue_script( 'jquery' );
}

wp_add_inline_script( 'jquery', 'jQuery(document).ready(function($) {
$(window).on("load", function() {	
   $("#pay_for_order_link").trigger("click");	
});		
});' );

endif;
endif;

}
	
}
add_action( 'wp_enqueue_scripts', 'eupip_pg_files' );

add_action('admin_footer', 'eupip_custom_admin_scripts', 10, 1);

function eupip_custom_admin_scripts() {
  echo '
	<script>
	function copyToClipboard(paylink){
    window.prompt("Copy to clipboard: Ctrl+C, Enter", paylink);
	}
	</script>
	';
}

function eupip_order_column_style() {
$payment_gateway_data = WC()->payment_gateways->payment_gateways()['eupip'];
if( $payment_gateway_data->get_option( 'xtras' ) == 'yes' ){
    $css = '
a.sqborder {
    display: inline-block;
    border: 1px solid #0071a1;
    border-radius: 4px;
    padding: 2px 4px 2px 4px;
    background: #f3f5f6;
    margin-right: 2px;
}
a.sqborder span.dashicons {
    margin-top: 2px;
    margin-bottom: 2px;
	}
.waicon::before {
/*source: https://api.iconify.design/fa-whatsapp.svg?color=%230073aa&width=20&height=20*/	
content: url(' . plugins_url( '/whatsapp-icon.svg' , __FILE__ ) . ');
}
.column-order_number {width:100% !important;}

.order-preview {width: auto !important; overflow: visible !important;}
	';
} else{
	    $css = '
a.sqborder {
    display: inline-block;
    border: 1px solid #0071a1;
    border-radius: 4px;
    padding: 2px 4px 2px 4px;
    background: #f3f5f6;
    margin-right: 2px;
}
a.sqborder span.dashicons {
    margin-top: 2px;
    margin-bottom: 2px;
	}
.waicon::before {
/*source: https://api.iconify.design/fa-whatsapp.svg?color=%230073aa&width=20&height=20*/	
content: url(' . plugins_url( '/whatsapp-icon.svg' , __FILE__ ) . ');
}
	';
}	

    wp_add_inline_style( 'woocommerce_admin_styles', $css );
}
add_action( 'admin_print_styles', 'eupip_order_column_style' );

if (!function_exists('filter_woocommerce_valid_order_statuses_for_payment')) {    
    function filter_woocommerce_valid_order_statuses_for_payment( $array, $instance ) {
        $upipg_order_status = array('on-hold', 'awaiting-payment');
        return array_merge($array, $upipg_order_status);
    }
    add_filter('woocommerce_valid_order_statuses_for_payment', 'filter_woocommerce_valid_order_statuses_for_payment', 10, 2);
}

function eupip_enable_gateway_order_pay( $available_gateways ) {

if($available_gateways['eupip']) {

   $available_gateways['eupip']->order_button_text = 'Pay for Order';

}	
	
if ( is_wc_endpoint_url( 'order-pay' ) ) {
	
	if( is_checkout() ):
		if( isset($_GET['eupip']) ):
			foreach ( $available_gateways as $gateway_id => $gateway ) {
				if( $gateway_id != 'eupip' )
					unset($available_gateways[$gateway_id]);
			}
		endif;
	endif;
}
return $available_gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'eupip_enable_gateway_order_pay', 10, 1 );

add_filter( 'woocommerce_admin_order_actions', 'add_custom_order_status_actions_button', 100, 2 );
function add_custom_order_status_actions_button( $actions, $order ) {
$payment_method = $order->get_payment_method();
if ( $payment_method === 'eupip'){
    if ( $order->has_status( array( 'on-hold' ) ) ) {

        $action_slug = 'cancelled';

        $actions[$action_slug] = array(
            'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=cancelled&order_id=' . $order->get_id() ), 'woocommerce-mark-order-status' ),
            'name'      => __( 'Cancel Order', 'woocommerce' ),
            'action'    => $action_slug,
        );
    }
}	
    return $actions;
}

add_action( 'admin_head', 'add_custom_order_status_actions_button_css' );
function add_custom_order_status_actions_button_css() {
    $action_slug = "cancelled";

    echo '<style>.wc-action-button-'.$action_slug.'::after { font-family: woocommerce !important; content: "\e013" !important;color:red !important; vertical-align: middle !important; } a.button.wc-action-button.wc-action-button-'.$action_slug.'.'.$action_slug.'{border-color: red !important;}</style>';
}

add_filter( 'woocommerce_admin_order_preview_actions', 'additional_admin_order_preview_buttons_actions', 25, 2 );
function additional_admin_order_preview_buttons_actions( $actions, $order ){
$payment_method = $order->get_payment_method();

if ( $payment_method === 'eupip'){

if ( $order->has_status( array( 'on-hold' ) ) ) {

$action_slug = 'cancelled';

            $actions['status']['actions'][$action_slug] = array(
                'url'    => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status='.$action_slug.'&order_id=' . $order->get_id() ), 'woocommerce-mark-order-status' ),
                'name'   => __( 'Cancel Order ', 'woocommerce' ),
                'title'  => __( 'Change order status to', 'woocommerce' ) . ' ' . strtolower('Cancelled'),
                'action' => $action_slug,
            );
     
	}
		
}		
		
    return $actions;
}

add_action( 'manage_shop_order_posts_custom_column' , 'eupip_custom_orders_list_column_content', 50, 2 );
function eupip_custom_orders_list_column_content( $column, $post_id ) {
    if ( $column == 'order_number' )
    {
global $the_order;
$wc_order_id = $the_order->get_id();
$wc_order_total = $the_order->get_total();
$phone = $the_order->get_billing_phone();
$order_pay_later_link = esc_url( $the_order->get_checkout_payment_url() );
$payment_method = $the_order->get_payment_method();
$payment_gateway_data = WC()->payment_gateways->payment_gateways()['eupip'];
$email = $the_order->get_billing_email();
$email_wp_dashicon = '<span class="dashicons dashicons-email-alt"></span> '.$email.'';
$link_wp_dashicon = '<span class="dashicons dashicons-admin-links"></span>';
$phone_wp_dashicon = '<span class="dashicons dashicons-phone"></span> '.$phone.'';
$sms_wp_dashicon = '<span class="dashicons dashicons-admin-comments"></span>';
$wa_wp_dashicon = '<span class="dashicons waicon"></span>';
	
if( wp_is_mobile() ):
	$wa_url = '<a class="sqborder" href="https://wa.me/91'.$phone.'" style="margin-right: 5px;">';
 else:
	$wa_url = '<a class="sqborder" href="https://web.whatsapp.com/send?phone=91'.$phone.'&text&source&data" target="_blank" style="margin-right: 5px;">';
endif;

if ( $payment_method === 'eupip'){

		echo ' - ₹<b>'.$the_order->get_total().'</b>';

		echo '<div>';

		if( $the_order->has_status( array( 'pending', 'on-hold' ) ) ):
			echo '<span style="font-weight:600;color:red;">&excl; Payment Confirmation pending - Please confirm!</span>';
		elseif( $the_order->has_status( 'cancelled' ) ):
			echo '<span style="font-weight:600;color:red;">&excl; Order Cancelled!</span>';	
		elseif( $the_order->has_status( 'refunded' ) ):
			echo '<span style="font-weight:600;color:red;">&excl; Order Refunded!</span>';	
		elseif( $the_order->has_status( 'failed' ) ):
			echo '<span style="font-weight:600;color:red;">&excl; Order failed!</span>';	
		else:
			echo '<span style="font-weight:600;color:green;">&check; Payment is Successful - Confirmed!</span>';	
		endif;
		
		echo '</div>';

if ( $payment_gateway_data->get_option( 'xtras' ) == 'yes' ){

if(!empty($phone) && !empty($email)){ 
           
echo'<div>&starf; <strong>Contact Customer:</strong><br/>';
	
	echo '<a class="sqborder" href="mailto:'.$email.'">'.$email_wp_dashicon.'</a></strong>				
	<a class="sqborder" href="tel:'.$phone.'">'.$phone_wp_dashicon.'</a></strong>
	<a class="sqborder" href="sms:'.$phone.'">'.$sms_wp_dashicon.'</a></strong>
	'.$wa_url.$wa_wp_dashicon.'</a></strong>';

if( $the_order->has_status( array( 'pending', 'on-hold' ) ) ):

	echo '<a class="sqborder" href="javascript:copyToClipboard( &apos;'.$order_pay_later_link.'&eupip=&apos; )">'.$link_wp_dashicon.' Copy Payment Link</a>';

endif;

echo '</div>';

	}
   }
  }
 }
}

add_filter( 'woocommerce_endpoint_order-received_title', 'eupip_thank_you_title' );
 
function eupip_thank_you_title( $old_title ){

$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
$order = new WC_Order( $order_id );
$payment_method = $order->get_payment_method();
if ( $payment_method === 'eupip'): 	
 	return 'Order Received! &check;';
else:
	return $old_title;
endif;	
 
}

function eupip_order_received_text( $text, $order ) {

$payment_method = $order->get_payment_method();

$payment_gateway_data = WC()->payment_gateways->payment_gateways()['eupip'];

if( $payment_gateway_data->get_option('dpaymod') == 'yes' && ! isset($_GET['tbox']) ):

if( $payment_gateway_data->get_option('ewac') == 'yes' ):

	$modtrue = '<a id="pay_for_order_link" href="#TB_inline?&inlineId=eupip-thickbox" class="thickbox" title="Complete payment for your Order">';

elseif( $payment_gateway_data->get_option('ewac') == 'no' ):

	$modtrue = '<a id="pay_for_order_link" href="#TB_inline?&inlineId=eupip-thickbox&modal=true" class="thickbox" title="Complete payment for your Order">';

endif;

$dpaymod = '<div id="pay_for_order" style="text-align:center !important;background:#fff !important;border: 1px solid lightgray;border-style: dashed;padding: 16px 8px 16px 8px;" align="center">
<div>Complete payment for your Order:</div>
<img src="' . plugins_url( '/pay-through-upi.png' , __FILE__ ) . '" style="margin:auto !important;"/>
'.$modtrue.'
<button class="button alt" style="white-space: normal !important;">
Pay for order
</button>
</a>
</div>
';

endif;

$oid = $order->get_id();

$custtq = $payment_gateway_data->get_option('custtq');

if ( $payment_method === 'eupip'): 

	if( $payment_gateway_data->get_option('custtq') != '' ):
		$new = str_replace("{order_id}",$oid,$custtq).$dpaymod;	
	else:	
		$new = $text . '<br/><br/>We&apos;re now checking your payment.<br/><br/>We will notify you about status of your payment & order shortly!<br/><br/>Now, You can close this page & Do Enjoy your rest of the day! :)'.$dpaymod;
	endif;	

		return $new;

else:
	return $text;
endif;	
}
add_filter('woocommerce_thankyou_order_received_text', 'eupip_order_received_text', 10, 2 );

function eupip_admin_notices() {
$payment_gateway_data = WC()->payment_gateways->payment_gateways()['eupip'];	
if( $payment_gateway_data->get_option('enabled') == 'no' ):
ob_start(); 
?>
<div class="notice notice-success is-dismissible">
	<p>Thanks for installing <a href="https://noobs.group/easy-upi-payment-plugin-woocommerce/" target="_blank"><strong>Easy UPI Payment</strong></a> plugin for WooCommerce. Now, It's time to enable & setup this gateway for use. from here: &rarr; <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=checkout&section=eupip' ); ?>">Easy UPI Payments (Setup)</a> &larr;</p>
</div>
<?php
echo ob_get_clean();
endif;
}
add_action('admin_notices', 'eupip_admin_notices');

add_filter( 'plugin_action_links', 'eupip_settings_link', 10, 2 ); 
function eupip_settings_link( $links_array, $plugin_file_name ){

	if( strpos( $plugin_file_name, basename(__FILE__) ) ) {
		array_unshift( $links_array, '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=eupip' ). '">Settings</a>' );
	}
 
	return $links_array;
}

add_action('plugins_loaded', 'woocommerce_eupip_init', 0);
function woocommerce_eupip_init() {
	if ( !class_exists( 'WC_Payment_Gateway' ) ) return;
	
	load_plugin_textdomain('wc-eupip', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');	

class WC_Gateway_EUPIP extends WC_Payment_Gateway {

	public function __construct() {
		$this->id                 = 'eupip';
		$this->icon               = apply_filters( 'woocommerce_eupip_icon', plugins_url( '/eupip-icon.svg' , __FILE__ ) );
		$this->has_fields         = false;
		$this->method_title       = _x( 'Easy UPI Payments', 'Easy UPI Payment method', 'woocommerce' );
		$this->method_description = __( 'Collect payments through BHIM UPI applications. e.g. BHIM app, Google pay, Paytm, Phonepe, Whatsapp, etc', 'woocommerce' );
		$this->eupip_init_form_fields();
		$this->init_settings();
		$this->title        = $this->get_option( 'title' );
		$this->description  = $this->get_option( 'description' );
		$this->instructions = $this->get_option( 'instructions' );
		$this->custtq		= $this->get_option( 'custtq' );
		$this->logobiz 		= $this->get_option( 'logobiz' );
		$this->anyname      = $this->get_option( 'anyname' );		
		$this->vpa        	= $this->get_option( 'vpa' );		
		$this->xtras        = $this->get_option( 'xtras' );	
		$this->blarrow      = $this->get_option( 'blarrow' );	
		$this->dtopen       = $this->get_option( 'dtopen' );	
		$this->dpaymod      = $this->get_option( 'dpaymod' );	
		$this->ewac      	= $this->get_option( 'ewac' );	
		$this->wanum      	= $this->get_option( 'wanum' );	

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_eupip', array( $this, 'thankyou_page' ) );

		add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
	}

//source:  https://wordpress.org/plugins/woo-upi-payment/

	public function eupip_init_form_fields() {

		$this->form_fields = array(
			'enabled'      => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Easy UPI Payments', 'woocommerce' ),
				'default' => 'no',
			),
			
			'title'        => array(
				'title'       => __( 'Title', 'woocommerce' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
				'default'     => _x( 'Easy UPI Payments', 'Easy UPI Payment method', 'woocommerce' ),
				'desc_tip'    => true,
			),
			
			'description'  => array(
				'title'       => __( 'Description', 'woocommerce' ),
				'type'        => 'textarea',
				'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce' ),
				'default'     => __( 'Pay using any UPI application installed on your mobile phone (e.g. BHIM app, Google pay, Paytm, Phonepe, Whatsapp, etc)', 'woocommerce' ),
				'desc_tip'    => true,
			),
			
			'instructions' => array(
				'title'       => __( 'Instructions', 'woocommerce' ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page and emails.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			
			'custtq' => array(
				'title'       => __( 'Custom thank you page Text for Customers<span style="color:red;"> (HTML & CSS are allowed) (Optional)</span>', 'woocommerce' ),
				'type'        => 'textarea',
				'description' => __( 'Show Custom thank you page Text to your customers. use: {order_id} to show order id anywhere in text.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,
			),
			
			'logobiz' => array(
				'title'       => __( 'Business Logo Image URL<span style="color:red;"> (Optional)</span>', 'woocommerce' ),
				'type'        => 'url',
				'description' => __( 'Enter your Business Logo Image URL to Show in Header of Payment Page.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,			
			),
			
			'anyname'        => array(
				'title'       => __( 'Store name OR Your name<span style="color:red;">*</span>', 'woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Enter Store name OR Your name here.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,			
				'custom_attributes' => array( 'required'    => '', ),
			),			
			
			'vpa'        => array(
				'title'       => __( 'UPI ID (VPA)<span style="color:red;">*</span>', 'woocommerce' ),
				'type'        => 'email',
				'description' => __( 'Enter your UPI ID or VPA here, e.g. siradhana@upi.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,			
				'custom_attributes' => array( 'required'    => '', ),
			),
			
			'xtras'        	  => array(
				'title'       => __( 'Show Extra Options in Orders list (admin)', 'woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'Check to Enable, Uncheck to Disable.', 'woocommerce' ),
				'default'     => 'yes', //enabled by default
				'desc_tip'    => true,							
			),
			
			'blarrow'         => array(
				'title'       => __( 'Show blinking arrows on top & bottom of Payment button to Customers (on mobile)', 'woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'Check to Enable, Uncheck to Disable.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,							
			),
			
			'dtopen'          => array(
				'title'       => __( 'Show QR Code to Customers (on mobile)', 'woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'Check to Enable, Uncheck to Disable.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,							
			),
			
			'dpaymod'         => array(
				'title'       => __( 'Disable Payment in Modal', 'woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'Check to Disable, Uncheck to Enable.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,							
			),
			
			'ewac'            => array(
				'title'       => __( 'Enable WhatsApp Payment Confirmation', 'woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'Check to Enable, Uncheck to Disable.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,							
			),
			
			'wanum'            => array(
				'title'       => __( 'WhatsApp Number (Regual/Business) - without +91', 'woocommerce' ),
				'type'        => 'tel',
				'description' => __( 'Customers will send you messages to know about status of their Payment.', 'woocommerce' ),
				'default'     => '',
				'desc_tip'    => true,							
			),
		);
	}

public function thankyou_page($order_id) {	

$order = wc_get_order( $order_id );				
$website_title = get_bloginfo( 'name' );
$orderid = $order->get_id();
$totalamount = $order->get_total();
$bhim_upi_pay_url = 'upi://pay?pn=' . ucwords( $this->anyname ) . '&pa=' . lcfirst( $this->vpa ) . '&tn=OrderID'.$orderid.'&am=' . $totalamount . '&cu=INR';
$upiqrcode = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . urlencode($bhim_upi_pay_url) . '&choe=UTF-8&chld=L|0';
$upiqrcode_with_css = '<style>.upiqrcode{ background-image: url("'.$upiqrcode.'"); }</style><div class="upiqrcode"></div>';
$upipaybtn_with_css = '<style>.pyt-upi{ background-image: url("' . plugins_url( '/pay-through-upi.png' , __FILE__ ) . '"); }</style><a href="#" onclick="window.location=&apos;'.$bhim_upi_pay_url.'&apos;;return false;"><div class="pyt-upi"></div></a>';
$orcurl = $order->get_checkout_order_received_url();

$bfirst_name = $order->get_billing_first_name();
$blast_name  = $order->get_billing_last_name();	
$wamsg1 = 'I am *' . $bfirst_name . '' . $blast_name .'* Have Successfully paid *₹'.$totalamount.'* for *OrderID:* '.$orderid.'';
$wamsg2 = 'Please Ship/Deliver my Order as soon as Possible';
$wamsg3 = 'Thank you!';

if( $this->ewac == 'yes' ):

	if( wp_is_mobile() ):

		$WA_CONFIRM = '<a href="https://wa.me/91' . $this->wanum . '?text=' . rawurlencode($wamsg1) . '%0D%0A%0D%0A' . rawurlencode($wamsg2) . '%0D%0A%0D%0A' . rawurlencode($wamsg3) . '">';

	else:	

		$WA_CONFIRM = '<a target="_blank" href="https://web.whatsapp.com/send?phone=91' . $this->wanum . '&text=' . rawurlencode($wamsg1) . '%0A%0D%0A%0D' . rawurlencode($wamsg2) . '%0A%0D%0A%0D' . rawurlencode($wamsg3) . '&source=&data=">';

	endif;

endif;

$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );

add_thickbox();

echo '<div id="eupip-thickbox" style="display:none;" >';

if ( has_custom_logo() ):
	echo '<img class="paysite-logo" src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '">';
elseif( $this->logobiz ):
	echo '<img class="paysite-logo" src="' . esc_url( $this->logobiz ) . '" alt="' . get_bloginfo( 'name' ) . '">';
else:
	echo '<img class="paysite-logo" src="' . plugins_url( '/bhim-upi-logo.webp' , __FILE__ ) . '" alt="' . get_bloginfo( 'name' ) . '">';
endif;
		
echo '<h2>Pay for order!</h2>';

if( !wp_is_mobile() ):

	echo '
	<p><strong>Scan</strong> & <strong>Pay</strong> using any UPI application installed on your mobile phone<br/>
	<span style="font-size:12px !important;"><i>(e.g. BHIM app, Google pay, Paytm, Phonepe, Whatsapp, etc)</i></span></p>	
	<div><strong>₹'.$totalamount.'</strong></div>
	'.$upiqrcode_with_css.'
	<div><strong>'.lcfirst( $this->vpa ).'</strong></div>';

else:

	echo '<p><strong>Click</strong> & <strong>Pay</strong> using any UPI application installed on your mobile phone<br/>
	<span style="font-size:12px !important;"><i>(e.g. BHIM app, Google pay, Paytm, Phonepe, Whatsapp, etc)</i></span></p>';
	
	if( $this->blarrow == 'yes' ):
	
		echo '<div class="blinking" style="color:red !important;">&darr;</div>';
	
	endif;
	
	echo $upipaybtn_with_css;
	
	if( $this->blarrow == 'yes' ):
	
		echo '<div class="blinking" style="color:red !important;">&uarr;</div>';
	
	endif;
	
	if( $this->dtopen == 'yes' ):
	
		echo '<details open>';
	
	elseif( $this->dtopen == 'no' ):
	
		echo '<details>';
	
	endif;
	
		echo '<summary>QR Code (Optional)</summary>
		<div style="margin-top:16px;">
		<div><strong>₹'.$totalamount.'</strong></div>
		'.$upiqrcode_with_css.'
		</div>	
		<div><strong>'.lcfirst( $this->vpa ).'</strong></div>
	</details>';
	
endif;

if( $this->ewac == 'no' ):
	
		echo '<div style="margin:8px 0 8px 0">&bull;</div>
			<div style="display: inline-flex;">
			<a href="#" class="button alt" onclick="paymentconfirm();return false;" style="margin:4px 4px 8px 0;">Confirm Payment</a>
			</div>';
	
	elseif( $this->ewac == 'yes' ):
	
		if( $this->dpaymod == 'yes' ):
	
			echo '<div class="blinking" style="color:red !important;font-size: 2.5em !important;margin: 16px 0 16px 0;">&darr;</div>
				'.$WA_CONFIRM.'
				<button class="button alt" style="white-space: normal !important;background:#25d366 !important;">
				Confirm Payment (on WhatsApp)
				</button>
				</a>';
		
		elseif( $this->dpaymod == 'no' ):	
					
			echo '<div style="margin:8px 0 8px 0">&bull;</div>
				<div style="display: inline-flex;">
				<a href="#" class="button alt" onclick="paymentconfirm();return false;" style="margin:4px 4px 8px 0;">Confirm Payment</a>
				</div>';		
						
		endif;
		
endif;

	if ( $this->instructions ) {
		echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) );
	}
	
echo '
<div style="margin:8px 0 8px 0">&bull;</div>
<div style="margin:0 0 16px 0"><strong>Don&apos;t want to Pay for this Order by Yourself ?</strong><br/>Share this Payment Page <strong>&</strong> Let any one of your friends/relatives pay for your Order:</div>
<div class="a2a_kit a2a_kit_size_32 a2a_default_style" data-a2a-url="' . $orcurl . '" id="my_centered_buttons">
<a class="a2a_button_copy_link"></a>
<a class="a2a_button_whatsapp"></a>
<a class="a2a_button_facebook_messenger"></a>
<a class="a2a_dd" href="https://www.addtoany.com/share"></a>
</div>	
';	

echo '
<p style="color:darkgray;">&copy; '.$website_title.' &bull; '.date('Y').'</p>
</div>
';

if( $this->dpaymod == 'no' ):

echo '
<div id="pay_for_order" style="display:none !important;">
<a id="pay_for_order_link" href="#TB_inline?&inlineId=eupip-thickbox&modal=true" class="thickbox" title="Pay  for your order!">
<button class="button alt" style="white-space: normal !important;">
Pay for order
</button>
</a>
</div>';

endif;

if( ! isset($_GET['tbox']) ):

$myaccount_orders = home_url( '/my-account/orders' );

echo '
<script>
function paymentconfirm(){
var r = confirm("Click CONFIRM PAYMENT, Only after amount deducted from your account :\n\nClick OK, to Confirm Payment.\n\nClick CANCEL, to Cancel Payment.");
if (r == true) {
	jQuery(document).ready(function($){	    
		window.location = "'.$orcurl.'&tbox=";		
	});	
  } else if(r == false){
	window.alert("Payment Cancelled!");
	window.location = "'.$myaccount_orders.'"; 
  }  
}
</script>
';

endif;
		
	}

	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
			
		$order_pay_later_link = esc_url( $order->get_checkout_payment_url() );
		
		if ( ! $sent_to_admin && 'eupip' === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
		
			echo '<div align="center" style="text-align:center !important;margin-bottom:16px !important;padding:16px 0 16px 0 !important;border:1px solid lightgray !important;border-style: dotted !important;">';
			
			echo '<p><strong>Didn&apos;t paid yet?</strong> If so, You can pay by Clicking below payment button:</p>';
			
			echo '<p>(Ignore this option, If you already paid us)</p>';

			echo '<p><button style="background:#333 !important;border:1px solid transparent !important;border-radius:50px !important;"><a href="'.$order_pay_later_link.'&eupip=" target="_blank" style="display:block !important;color: #fff !important;cursor: pointer !important;text-decoration:none !important;padding:16px !important;">Pay now!</a></button></p>';
			
			echo '</div>';
		
		}		

		if ( $this->instructions && ! $sent_to_admin && 'eupip' === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
		
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) . PHP_EOL );

		}
	}

	public function process_payment( $order_id ) {

		$order = wc_get_order( $order_id );

		if ( $order->get_total() > 0 ) {
			$order->update_status( apply_filters( 'woocommerce_eupip_process_payment_order_status', 'on-hold', $order ), _x( 'Awaiting Easy UPI Payment', 'Easy UPI Payment method', 'woocommerce' ) );
		} else {
			$order->payment_complete();
		}

		WC()->cart->empty_cart();

		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order ),
		);
	}
}

	function woocommerce_add_eupip_gateway($methods) {
		$methods[] = 'WC_Gateway_EUPIP';
		return $methods;
	}
	
	add_filter('woocommerce_payment_gateways', 'woocommerce_add_eupip_gateway' );
}

?>