<?php
/**
 * General Settings
 *
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Taking some variable
$add_js			= popupaoc_get_option('add_js');
$cookie_prefix	= popupaoc_get_option( 'cookie_prefix', 'paoc_popup' );
?>

<div class="postbox paoc-no-toggle">

	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'General Settings', 'popup-anything-on-click' ); ?></span>
		</h3>
	</div>

	<div class="inside">
		<table class="form-table paoc-tbl">
			<tbody>
				<tr>
					<th>
						<label for="paoc-enable"><?php esc_html_e('Enable', 'popup-anything-on-click'); ?></label>
					</th>
					<td>
						<input type="checkbox" name="popupaoc_options[enable]" value="1" <?php checked( popupaoc_get_option('enable'), 1 ); ?> id="paoc-enable" class="paoc-checkbox paoc-enable" /><br/>
						<span class="description"><?php esc_html_e('Check this box if you want to enable popup.', 'popup-anything-on-click'); ?></span>
					</td>
				</tr>

				<tr>
					<th>
						<label for="paoc-cookie-prefix"><?php esc_html_e('Cookie Prefix', 'popup-anything-on-click'); ?></label>
					</th>
					<td>
						<input type="text" name="popupaoc_options[cookie_prefix]" value="<?php echo esc_attr( $cookie_prefix ); ?>" id="paoc-cookie-prefix" class="paoc-text paoc-cookie-prefix"><br/>
						<span class="description"><?php esc_html_e('Enter cookie prefix. Changing the value will display the cookie based popup again. Default cookie prefix is "paoc_popup".', 'popup-anything-on-click'); ?></span>
					</td>
				</tr>

				<tr>
					<th>
						<label for="paoc-pro-add-js"><?php esc_html_e('Manage Polyfill JS', 'popup-anything-on-click'); ?></label>
					</th>
					<td>
						<select name="popupaoc_options[add_js]" class="paoc-pro-add-js" id="paoc-pro-add-js">
							<option value=""><?php esc_html_e('Select Option', 'popup-anything-on-click'); ?></option>
							<option value="1" <?php selected( $add_js, 1 ); ?>><?php esc_html_e('Disable polyfill js file to load from this plugin', 'popup-anything-on-click'); ?></option>
							<option value="2" <?php selected( $add_js, 2 ); ?>><?php esc_html_e('Include polyfill js file in header', 'popup-anything-on-click'); ?></option>
						</select><br>
						<span class="description"><?php esc_html_e( 'Note : If you are facing any error related Polyfill JS eg : Uncaught Error: only one instance of babel-polyfill is allowed, than select above option to hide plugin polyfill js or inluclude polyfill js in header so no conflict arise with default js of WordPress. You are getting this error because of there are two version of polyfill.js loading in your website.', 'popup-anything-on-click'); ?></span>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<input type="submit" name="paoc_settings_submit" class="button button-primary right paoc-btn paoc-sett-submit" value="<?php esc_attr_e('Save Changes', 'popup-anything-on-click'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div><!-- end .inside -->
</div><!-- end .postbox -->