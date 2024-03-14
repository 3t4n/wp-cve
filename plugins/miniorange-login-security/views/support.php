<?php
/**
 * Support form of the plugin.
 *
 * @package miniorange-login-security/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
echo '<div class="momls_wpns_divided_layout_2">
		<div class="mo2f_table_layout mo2f_advertize">
			<div class="mo2f_advertize_head">
				<img src="' . esc_url( dirname( plugin_dir_url( __FILE__ ) ) ) . '/includes/images/mo2fa-logo.png" height="50px"></img>
				<h2 >Upgrade to Premium and get </h2>
			</div>		
			<div class="mo2f_advertize_body">
				<div>
					<ul>
						<li>10+ Authentication Methods</li>
						<li>Multisite compatible</li>
						<li>Custom Redirection URL</li>
						<li>Passwordless Login</li>
						<li>Custom SMS Gateway</li>
					</ul>
				</div>
				<div>
					<ul>
						<li>White labelling</li>
						<li>FIDO2/WebAuthn</li>
						<li>Remember Device </li>
						<li>Role-Based 2FA</li>
						<li><a href="https://plugins.miniorange.com/2-factor-authentication-for-wordpress-wp-2fa#pricing" target="_blank">More... </a>
						</li>
					</ul>
				</div>
			</div>
			<div class="mo2f_advertize_bottom">
				<div style="flex:1; padding:5px;display:flex;flex-direction:column;justify-content:center;align-item:center;gap:2px">
					<a href="https://plugins.miniorange.com/2-factor-authentication-for-wordpress-wp-2fa#pricing" target="_blank" class="button" style="background-color:#48b74b;border-color:#fff;color:#fff;text-align:center">Upgrade to Premium</a>
				</div>
			</div>
		</div>
		
		<div class="mo2f_table_layout" id="mo2f_open_support">
		<div class="mo2f_contact span" id="mo2f_toggle_support">
			Contact Us
			<div id="mo2f_close" style="position:absolute;top:0px;right:0px" class="dashicons"></div>
		</div>
		<div id="mo2f_support_form" hidden	>
				<img src="' . esc_url( dirname( plugin_dir_url( __FILE__ ) ) ) . '/includes/images/support3.png">
					<h1>Support</h1>
					<p>Need any help? We are available any time, Just send us a query so we can help you.</p>
						<form name="f" method="post" action="">
							<input type="hidden" name="option" value="momls_wpns_send_query"/>
							<input type="hidden" name="momls-support-nonce" value="' . esc_attr( wp_create_nonce( 'momls-support-form-nonce' ) ) . '" />
							<table class="momls_wpns_settings_table">
								<tr><td>
									<input type="email" class="momls_wpns_table_textbox" id="query_email" name="query_email" value="' . esc_attr( is_email( $email ) ) . '" placeholder="Enter your email" required />
									</td>
								</tr>
								<tr><td>
							<input type="tel" class="momls_wpns_table_textbox" name="query_phone" id="query_phone" value="' . esc_attr( $phone ) . '" placeholder="Enter your phone" />

							</td>
						</tr>
						<tr>
							<td>
								<textarea id="query" name="query" class="momls_wpns_settings_textarea" style="resize: vertical;width:100%" cols="52" rows="7" placeholder="Write your query here"></textarea>
							</td>
						</tr>
					</table>
					<input type="submit" name="send_query" id="send_query" value="Submit Query" style="margin-bottom:3%;" class="button button-primary button-large" />
				</form>	
		</div>
		</div>
		<div class="mo2f_table_layout mo2f_contact" id="mo2f_raise_ticket" >
			<span>Raise Support ticket</span>
		</div>
		</div>
		<script>
			jQuery("#mo2f_toggle_support").click((e)=>{
				jQuery(".mo2f_advertize").slideToggle();
				jQuery("#mo2f_support_form").slideToggle();
				jQuery("#mo2f_close").toggleClass("dashicons-no-alt");
				
			})
			jQuery("#mo2f_raise_ticket").click((e)=>{
				window.open("https://wordpress.org/support/plugin/miniorange-login-security/", "_blank");
			})
			function moSharingSizeValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:10>t&&(e.value=10)
			}
			function moSharingSpaceValidate(e){
				var t=parseInt(e.value.trim());t>50?e.value=50:0>t&&(e.value=0)
			}
			function moLoginSizeValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:20>t&&(e.value=20)
			}
			function moLoginSpaceValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:0>t&&(e.value=0)
			}
			function moLoginWidthValidate(e){
				var t=parseInt(e.value.trim());t>1000?e.value=1000:140>t&&(e.value=140)
			}
			function moLoginHeightValidate(e){
				var t=parseInt(e.value.trim());t>50?e.value=50:35>t&&(e.value=35)
			}
		</script>';
