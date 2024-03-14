<?php
/*
/**
 * Plugin Name: Notification Bar Pro
 * Plugin URI: https://zotabox.com/zbv2/promobar?utm_source=wordpress.com&utm_medium=Header%20Bar&utm_campaign=ecommerce%20plugins&authuser=anonymous
 * Description: Responsive and Fully Customizable Bar. Promote Free Shipping, Free Gifts, Coupons, +10 more popular website marketing tools included.
 * Version: 1.3.0
 * Author: Zotabox
 * Author URI: https://zotabox.com/dashboard/?utm_source=wordpress.com&utm_medium=Header Bar&utm_campaign=ecommerce%20plugins&authuser=anonymous
 * License: SMB 1.0
 */

//Add some free tools

add_action( 'admin_init', 'zb_pb_admin_init' );
function zb_pb_admin_init(){
	/* Register stylesheet. */
	wp_register_style( 'css_main', plugins_url('assets/css/style.css', __FILE__) );
	wp_enqueue_style('css_main');
    /* Register js. */
	wp_register_script( 'zb_pb_admin_init', plugins_url('assets/js/main.js?v='.time(), __FILE__) );
	wp_enqueue_script('zb_pb_admin_init');

    //Create options
    add_option( 'ztb_source', '', '', 'yes' );
    add_option( 'ztb_id', '', '', 'yes' );
    add_option( 'ztb_domainid', '', '', 'yes' );
    add_option( 'ztb_email', '', '', 'yes' );
    add_option( 'access_key', '', '', 'yes' );
    add_option( 'ztb_access_key', '', '', 'yes' );
    add_option( 'ztb_status_message', 1, '', 'yes' );
    add_option( 'ztb_status_disconnect', 2, '', 'yes' );
}

register_deactivation_hook( __FILE__, 'zb_pb_deactivate' );
function zb_pb_deactivate(){
	update_option( 'ztb_status_message', 2 );
	update_option( 'ztb_status_disconnect', 1 );
}

register_activation_hook( __FILE__, 'zb_pb_activate' );
function zb_pb_activate() {
	update_option( 'ztb_status_message', 1 );
}

add_action('admin_notices', 'zb_pb_show_admin_messages');
function zb_pb_show_admin_messages()
{
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$domain_action = 'https://zotabox.com';
	$token_key = get_option( 'ztb_token_key', '' );
	$public_key = get_option( 'access_key', '' );
	$ztb_status_message = get_option( 'ztb_status_message', '' );
	$ztb_status_disconnect = get_option( 'ztb_status_disconnect', '' );
	$ztb_id = get_option( 'ztb_id', '' );
	$message_intall = '<div id="message" class="updated fade">
		    <p>Thanks for installing <strong>Zotabox plugin!</strong>  
		    <a href="/wp-admin/admin.php?page=zb_pb">
		    <strong>Click to configure.</strong></a></p>
	    </div>';
	$message_disconnect = '<div id="message" class="updated fade">
			    <p>Disconnected from <strong>Zotabox </strong>successfully! </p>
		    </div>';
	if ( is_plugin_active( 'responsive-welcome-bar/responsive-welcome-bar.php') ) {
		if($ztb_status_message == 1){
	  		echo $message_intall;
		   if($ztb_status_message == 1){
		   		update_option( 'ztb_status_message', 2 );
		   }
		}
	}
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'zb_pb_add_action_links' );
function zb_pb_add_action_links ( $links ) {
	 $mylinks = array(
	 '<a href="' . admin_url( 'admin.php?page=zb_pb' ) . '">Settings</a>',
	 );
	return array_merge( $links, $mylinks );
}

add_action('admin_menu', 'zb_pb_admin_menu');
function zb_pb_admin_menu() {
	add_menu_page('Notification Bar Pro', 'Notification Bar', 'administrator', 'zb_pb', 'zb_pb_setting',plugins_url( 'zotabox.png', __FILE__ ));
}

function zb_pb_setting(){
	$domain_action = 'https://zotabox.com';
	$token_key = wp_create_nonce('update_zb_pb_code');
	$access_key = get_option( 'ztb_access_key', '' );
	if(empty($access_key)){
		$access_key = get_option( 'access_key', '' );
	}
	$ztb_id = get_option( 'ztb_id', '' );
	$domain = get_option('ztb_domainid','');
	$zbEmail = get_option('ztb_email','');
	$ztb_source = get_option('ztb_source','');
	$button = '';
	$adminEmail = get_option('admin_email');
	//Check empty ztb email
	if(empty($zbEmail)){
		$zbEmail = $adminEmail;
	}
	global $current_user;
    wp_get_current_user();

	$ztb_status_disconnect = get_option( 'ztb_status_disconnect', '' );
	$connected = 2;
	if(isset($access_key) && !empty($access_key) && strlen($access_key) > 0 && $ztb_status_disconnect == $connected){
	
		$button = '<a  target="zotabox" href="'.$domain_action.'/customer/access/PluginLogin/?customer='.$ztb_id.'&access='.$access_key.'&domain_secure_id='.$domain.'&app=pb&platform=wordpress&utm_source=wordpress.com">
			Configure your tools
		</a>';
		$form = '';
	}else{
		$form = '<form class="ztb-register-form" target="_blank" method="POST" action="'.$domain_action.'/customer/access/PluginAuth?app=pb&utm_source=wordpress.com&utm_medium=Header Bar&utm_campaign=ecommerce%20plugins&token='.$token_key.'&platform=wordpress&access='.$access_key.'" id="account-info">
					<div class="form-group">
						<label>Website:</label>
						<input class="form-control" readonly type="text" name="domain" value="'.home_url().'" />
						<input type="hidden" name="name" value="'.$current_user->display_name.'" />
						<input type="hidden" name="utm_medium" value="Header Bar" />
						<input type="hidden" name="utm_campaign" value="ecommerce plugins" />
					</div>
					<div class="form-group">
						<label>Email:</label>
						<input class="form-control" type="text" name="email" value="'.$zbEmail.'" />
					</div>
					
					<div class="form-group button-wrapper">
						<input zb-plugin="zb_pb" class="ztb-submit-button" type="submit" value="Start Using Your New Tools Now" /><br><br>
						<div style="width: 80%;margin: auto;color: #888; padding-top: 10px;"><strong>Note:</strong> Zotabox is a 3rd party service provider. A Zotabox account will be created automatically and you can delete it at any time.<br><br>
							You will receive important account, informational and promotional emails from us and remarketing ads via Google Adwords. For information to opt out of these ads and emails at any time, please visit <a href="https://info.zotabox.com/privacy-policy/" target="_blank">our privacy page</a>.
						</div>
					</div>
					</form>';
		$button = '';
	} 

	$html = '
	<script type="text/javascript">
		var ZBT_WP_ADMIN_URL = "'.admin_url().'";
		var ZTB_BASE_URL = "'.$domain_action.'";
	</script>
	<div class="ztb-wrapper">
		<div class="ztb-logo">
			<a href="https://zotabox.com/dashboard/?utm_source=wordpress.com&utm_medium=Header Bar&utm_campaign=ecommerce%20plugins&authuser=anonymous" title="Zotabox" target="zotabox">
				<img title="Zotabox" alt="Zotabox" src="'.plugins_url( 'assets/images/logo-zotabox.png', __FILE__ ).'">
			</a>
		</div>
		<div class="ztb-code-wrapper wrap">
			<div class="ztb-title">
				Zotabox includes 20+ Promotional Sales tools to grow your website’s traffic, boost your sales and get more subscribers.
			</div>
			<div class="account-input">
				'.$form.'
			</div>
			<div class="ztb-button">'.$button.'</div>
			<div style="clear:both"></div>
		</div>
	</div>';
	echo $html;
}

function insert_zb_pb_code(){
	if(!is_admin()){
		$domain = get_option( 'ztb_domainid', '' );
		$ztb_source = get_option('ztb_source','');
		$ztb_status_disconnect = get_option('ztb_status_disconnect','');
		$connected = 2;
		if(!empty($domain) && strlen($domain) > 0 && $ztb_status_disconnect == $connected){
			print_r(html_entity_decode(print_zb_pb_code($domain)));
		}
	}
}
add_action( 'wp_head', 'insert_zb_pb_code' );


add_action("wp_ajax_update_zb_pb_code", "update_zb_pb_code");
add_action("wp_ajax_nopriv_update_zb_pb_code", "update_zb_pb_code");

function update_zb_pb_code(){
	if(isset($_REQUEST['token']) && wp_verify_nonce($_REQUEST['token'], 'update_zb_pb_code')){
		$domain = sanitize_text_field(addslashes($_REQUEST['domain']));
		$public_key = sanitize_text_field(addslashes($_REQUEST['access']));
		$token = sanitize_text_field(addslashes($_REQUEST['token']));
		$id = intval($_REQUEST['customer']);
		if(!isset($domain) || empty($domain)){
			$redirect = admin_url('admin.php?page=zb_pb');
        	wp_safe_redirect($redirect);
		}else{
			if(wp_verify_nonce($_REQUEST['token'], 'update_zb_pb_code')){
				update_option( 'ztb_domainid', $domain );
				update_option( 'ztb_access_key', $public_key );
				update_option( 'ztb_id', $id );
				update_option( 'ztb_status_disconnect', 2 );
				wp_send_json( array(
					'error' => false,
					'message' => 'Update Zotabox embedded code successful !' 
					)
				);
			}else{
				wp_send_json( array(
					'error' => true,
					'message' => 'Wrong nonce!' 
					)
				);
			}
		}
	}
}

function print_zb_pb_code($domainSecureID = "", $isHtml = false) {

	$ds1 = substr($domainSecureID, 0, 1);
	$ds2 = substr($domainSecureID, 1, 1);
	$baseUrl = '//static.zotabox.com';
	$code = <<<STRING
<script async src="{$baseUrl}/{$ds1}/{$ds2}/{$domainSecureID}/widgets.js"></script>
STRING;
	return $code;
}
?>