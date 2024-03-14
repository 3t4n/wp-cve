<?php
/*
/**
 * Plugin Name: Zotabox
 * Plugin URI: https://zotabox.com/dashboard/?utm_source=wordpress.com&utm_medium=Zotabox&utm_campaign=ecommerce%20plugins&authuser=anonymous
 * Description: Boost your subscribers and sales with 20+ popular on-site marketing tools: Email List Builder, Social Coupon, Countdown Timers, Contact Forms, Popups
 * Version: 1.9.1
 * Author: Zotabox
 * Author URI: https://zotabox.com/dashboard/?utm_source=wordpress.com&utm_medium=Zotabox&utm_campaign=ecommerce%20plugins&authuser=anonymous
 * License: SMB 1.0
 */

//Add some free tools

add_action( 'admin_init', 'zb_zbapp_admin_init' );
function zb_zbapp_admin_init() {
	/* Register stylesheet. */
	wp_register_style('css_main', plugins_url('assets/css/style.css', __FILE__), array(), '1.0');
	wp_enqueue_style('css_main');
	/* Register js. */
	wp_register_script('zb_zbapp_admin_init', plugins_url('assets/js/main.js', __FILE__), array(), time());
	wp_enqueue_script('zb_zbapp_admin_init');

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

register_deactivation_hook( __FILE__, 'zb_zbapp_deactivate' );
function zb_zbapp_deactivate() {
	update_option( 'ztb_status_message', 2 );
	update_option( 'ztb_status_disconnect', 1 );
}

register_activation_hook( __FILE__, 'zb_zbapp_activate' );
function zb_zbapp_activate() {
	update_option( 'ztb_status_message', 1 );
}

add_action('admin_notices', 'zb_zbapp_show_admin_messages');
function zb_zbapp_show_admin_messages() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$domain_action = 'https://zotabox.com';
	$token_key = get_option( 'ztb_token_key', '' );
	$public_key = get_option( 'access_key', '' );
	$ztb_status_message = get_option( 'ztb_status_message', '' );
	$ztb_status_disconnect = get_option( 'ztb_status_disconnect', '' );
	$ztb_id = get_option( 'ztb_id', '' );
	$message_intall = '<div id="message" class="updated fade">
		    <p>Thanks for installing <strong>Zotabox plugin!</strong>  
		    <a href="/wp-admin/admin.php?page=zb_zbapp">
		    <strong>Click to configure.</strong></a></p>
	    </div>';
	$message_disconnect = '<div id="message" class="updated fade">
			    <p>Disconnected from <strong>Zotabox </strong>successfully! </p>
		    </div>';
	if ( is_plugin_active( 'zotabox/zotabox.php')) {
		if ( 1 == $ztb_status_message ) {
			print_r($message_intall);
			if (1 == $ztb_status_message) {
				update_option( 'ztb_status_message', 2);
			}
		}
	}
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'zb_zbapp_add_action_links' );
function zb_zbapp_add_action_links ( $links ) {
	 $mylinks = array(
	 '<a href="' . admin_url( 'admin.php?page=zb_zbapp' ) . '">Settings</a>',
	 );
	return array_merge( $links, $mylinks );
}

add_action('admin_menu', 'zb_zbapp_admin_menu');
function zb_zbapp_admin_menu() {
	add_menu_page('Zotabox', 'Zotabox', 'administrator', 'zb_zbapp', 'zb_zbapp_setting', plugins_url( 'zotabox.png', __FILE__ ));
}

function zb_zbapp_setting() {
	$domain_action = 'https://zotabox.com';
	$token_key = wp_create_nonce('update_zb_zbapp_code');
	$access_key = get_option( 'ztb_access_key', '' );
	if (empty($access_key)) {
		$access_key = get_option( 'access_key', '' );
	}
	$ztb_id = get_option( 'ztb_id', '');
	$domain = get_option('ztb_domainid', '');
	$zbEmail = get_option('ztb_email', '');
	$ztb_source = get_option('ztb_source', '');
	$button = '';
	$adminEmail = get_option('admin_email');
	//Check empty ztb email
	if (empty($zbEmail)) {
		$zbEmail = $adminEmail;
	}
	global $current_user;
	wp_get_current_user();

	$ztb_status_disconnect = get_option( 'ztb_status_disconnect', '' );
	$connected = 2;
	if ( isset($access_key) && !empty($access_key) && strlen($access_key) > 0 && $ztb_status_disconnect == $connected ) {
	
		$button = '<a  target="zotabox" href="' . $domain_action . '/customer/access/PluginLogin/?customer=' . $ztb_id . '&access=' . $access_key . '&domain_secure_id=' . $domain . '&app=zotabox&platform=wordpress&utm_source=wordpress.com">
			Configure your tools
		</a>';
		$form = '';
	} else {
		$form = '<form class="ztb-register-form" target="_blank" method="POST" action="' . $domain_action . '/customer/access/PluginAuth?app=zotabox&utm_source=wordpress.com&utm_medium=Zotabox&utm_campaign=ecommerce%20plugins&token=' . $token_key . '&platform=wordpress&access=' . $access_key . '" id="account-info">
					<div class="form-group">
						<label>Website:</label>
						<input class="form-control" readonly type="text" name="domain" value="' . home_url() . '" />
						<input type="hidden" name="name" value="' . $current_user->display_name . '" />
						<input type="hidden" name="utm_medium" value="Zotabox" />
						<input type="hidden" name="utm_campaign" value="ecommerce plugins" />
					</div>
					<div class="form-group">
						<label>Email:</label>
						<input class="form-control" type="text" name="email" value="' . $zbEmail . '" />
					</div>
					
					<div class="form-group button-wrapper">
						<input zb-plugin="zb_zbapp" class="ztb-submit-button" type="submit" value="Start Using Your New Tools Now" /><br><br>
						<div style="width: 80%;margin: auto;color: #888; padding-top: 10px;"><strong>Note:</strong> Zotabox is a 3rd party service provider. A Zotabox account will be created automatically and you can delete it at any time.<br><br>
							You will receive important account, informational and promotional emails from us and remarketing ads via Google Adwords. For information to opt out of these ads and emails at any time, please visit <a href="https://info.zotabox.com/privacy-policy/" target="_blank">our privacy page</a>.
						</div>
					</div>
					</form>';
		$button = '';
	} 

	$html = '
	<script type="text/javascript">
		var ZBT_WP_ADMIN_URL = "' . admin_url() . '";
		var ZTB_BASE_URL = "' . $domain_action . '";
	</script>
	<div class="ztb-wrapper">
		<div class="ztb-logo">
			<a href="https://zotabox.com/dashboard/?utm_source=wordpress.com&utm_medium=Zotabox&utm_campaign=ecommerce%20plugins&authuser=anonymous" title="Zotabox" target="zotabox">
				<img title="Zotabox" alt="Zotabox" src="' . plugins_url( 'assets/images/logo-zotabox.png', __FILE__ ) . '">
			</a>
		</div>
		<div class="ztb-code-wrapper wrap">
			<div class="ztb-title">
				Zotabox includes 20+ Promotional Sales tools to grow your website’s traffic, boost your sales and get more subscribers.
			</div>
			<div class="account-input">
				' . $form . '
			</div>
			<div class="ztb-button">' . $button . '</div>
			<div style="clear:both"></div>
		</div>
	</div>';
	print_r($html);
}

function insert_zb_zbapp_code() {
	if ( !is_admin() ) {
		$domain = get_option( 'ztb_domainid', '' );
		$ztb_source = get_option('ztb_source', '');
		$ztb_status_disconnect = get_option('ztb_status_disconnect', '');
		$connected = 2;
		if ( !empty($domain) && strlen($domain) > 0 && $ztb_status_disconnect == $connected ) {
			print_r(html_entity_decode(print_zb_zbapp_code($domain)));
		}
	}
}
add_action( 'wp_head', 'insert_zb_zbapp_code' );


add_action('wp_ajax_update_zb_zbapp_code', 'update_zb_zbapp_code');
add_action('wp_ajax_nopriv_update_zb_zbapp_code', 'update_zb_zbapp_code');

function update_zb_zbapp_code() {
	$tokenData = isset($_REQUEST['token']) ? sanitize_text_field( wp_unslash($_REQUEST['token'])) : false;
	$domain = isset($_REQUEST['domain']) ? sanitize_text_field( wp_unslash($_REQUEST['domain'])) : false;
	$access = isset($_REQUEST['access']) ? sanitize_text_field( wp_unslash($_REQUEST['access'])) : false;
	$customer = isset($_REQUEST['customer']) ? sanitize_text_field( wp_unslash($_REQUEST['customer'])) : false;
	if ( false != $tokenData && wp_verify_nonce($tokenData, 'update_zb_zbapp_code') ) {
		// $tokenData = esc_attr($tokenData);
		// $domain = sanitize_text_field($domain);
		// $public_key = sanitize_text_field($access);
		$token = sanitize_text_field($tokenData);
		$id = intval($customer);
		if ( !isset($domain) || empty($domain) ) {
			$redirect = admin_url('admin.php?page=zb_zbapp');
			wp_safe_redirect($redirect);
		} else {
			if ( wp_verify_nonce($tokenData, 'update_zb_zbapp_code') ) {
				update_option( 'ztb_domainid', $domain );
				update_option( 'ztb_access_key', $public_key );
				update_option( 'ztb_id', $id );
				update_option( 'ztb_status_disconnect', 2 );
				wp_send_json( array(
					'error' => false,
					'message' => 'Update Zotabox embedded code successful !' 
					)
				);
			} else {
				wp_send_json( array(
					'error' => true,
					'message' => 'Wrong nonce!' 
					)
				);
			}
		}
	}
}

function print_zb_zbapp_code ($domainSecureID = '', $isHtml = false) {

	$ds1 = substr($domainSecureID, 0, 1);
	$ds2 = substr($domainSecureID, 1, 1);
	$baseUrl = '//static.zotabox.com';
	$code = <<<STRING
<script async src="{$baseUrl}/{$ds1}/{$ds2}/{$domainSecureID}/widgets.js"></script>
STRING;
	return $code;
}
