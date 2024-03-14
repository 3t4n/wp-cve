<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function rs_eucc_admin_init() {
	$update = rs_eucc_update_settings();
	$option = rs_eucc_get_option(); ?>
	<div class="wrap">
		<h1><?php echo RS_EUCC__PLUGIN_NAME; ?></h1>
		<?php if( $update == TRUE ) { ?><div id="message" class="updated notice is-dismissible"><p>Settings updated.</p></div><?php } ?>
		<form method="post" action="<?php echo RS_EUCC__PLUGIN_ADMIN_URL; ?>">
			<table class="form-table">
				<tr>
					<th scope="row"><label for="cookie_consent_status">Cookie Consent status</label></th>
					<td><p><label><input name="cookie_consent_status" type="radio" value="on" class="tog"<?php if( $option['cookie_consent_status'] == 'on' ) { ?> checked="checked"<?php } ?> /> On</label></p>
					<p><label><input name="cookie_consent_status" type="radio" value="off" class="tog"<?php if( $option['cookie_consent_status'] == 'off' ) { ?> checked="checked"<?php } ?> /> Off</label></p></td>
				</tr>
				<tr>
					<th scope="row"><label for="colour_scheme">Colour scheme</label></th>
					<td><select name="colour_scheme" id="colour_scheme">
						<option value="light"<?php if( $option['colour_scheme'] == 'light' ) { ?> selected="selected"<?php } ?>>Light</option>
						<option value="dark"<?php if( $option['colour_scheme'] == 'dark' ) { ?> selected="selected"<?php } ?>>Dark</option>
					</select></td>
				</tr>
				<tr>
					<th scope="row"><label for="notice_position">Notice position</label></th>
					<td><select name="notice_position" id="notice_position">
						<option value="top"<?php if( $option['notice_position'] == 'top' ) { ?> selected="selected"<?php } ?>>Top</option>
						<option value="floating"<?php if( $option['notice_position'] == 'floating' ) { ?> selected="selected"<?php } ?>>Floating</option>
						<option value="bottom"<?php if( $option['notice_position'] == 'bottom' ) { ?> selected="selected"<?php } ?>>Bottom</option>
					</select></td>
				</tr>
				<tr>
					<th scope="row"><label for="visitor_message">Visitor message</label></th>
					<td><input name="visitor_message" type="text" id="visitor_message" value="<?php echo esc_html( stripslashes( $option['visitor_message'] ) ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="dismiss_text">Dismiss text</label></th>
					<td><input name="dismiss_text" type="text" id="dismiss_text" value="<?php echo esc_html( stripslashes( $option['dismiss_text'] ) ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="policy_link_text">Cookie policy link text</label></th>
					<td><input name="policy_link_text" type="text" id="policy_link_text" value="<?php echo esc_html( stripslashes( $option['policy_link_text'] ) ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="policy_link_url">Cookie policy link address</label></th>
					<td><input name="policy_link_url" type="text" id="policy_link_url" value="<?php echo esc_html( stripslashes( $option['policy_link_url'] ) ); ?>" class="regular-text" />
					<p>This should be a complete web address.<br />Eg: <strong><?php echo home_url( '/cookie-policy/' ); ?></strong></p></td>
				</tr>
				<tr>
					<th scope="row">Delete settings on deactivation?</th>
					<td><label for="delete_option_on_deactivate"><input name="delete_option_on_deactivate" type="checkbox" id="delete_option_on_deactivate" value="1"<?php if( $option['delete_option_on_deactivate'] == '1' ) { ?> checked="checked"<?php } ?> /> Check this box to delete your settings above when you deactivate the plugin.</label></td>
				</tr>
			</table>
			<p class="beer">Do you find this plugin useful? If you do and you'd like to buy me a beer to say thanks, <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VDDQ9FHE9CL8J" onclick="window.open( this ); return false;">click here</a>. Thanks!</p>
			<?php wp_nonce_field( 'rs_eucc_update_settings' ); ?>
			<?php submit_button(); ?>
		</form>
	</div><?php
}