<?php
/**
 * Handle Email Integration HTML
 *
 * Handles the third party integration
 *
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="postbox paoc-pro-feature">
	<div class="postbox-header">
		<h3 class="hndle">
			<span><?php esc_html_e( 'MailChimp Settings', 'popup-anything-on-click' ); ?></span>
		</h3>
	</div>

	<!-- Pro Notice -->
	<div class="paoc-pro-notice">
		<i class="dashicons dashicons-money-alt"></i>
		<?php echo sprintf( __( 'Utilize these <a href="%s" target="_blank">Premium Features with Risk-Free 30 days money back guarantee</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'popup-anything-on-click'), POPUPAOC_PLUGIN_LINK_UNLOCK); ?>
	</div>

	<div class="inside">
		<table class="form-table paoc-tbl">
			<tbody>
				<tr>
					<th scope="row">
						<label for="paoc-intgs-mc-api-key"><?php esc_html_e('API Key', 'popup-anything-on-click'); ?></label>
					</th>
					<td>
						<input type="text" name="" class="large-text" id="paoc-intgs-mc-api-key" value="" disabled="disabled" /><br/>
						<span class="description"><?php echo sprintf( __('The API key for connecting with your Mailchimp account. <a href="%s" target="_blank">Get your API key here</a>.', 'popup-anything-on-click'), 'https://admin.mailchimp.com/account/api' ); ?></span>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<input type="submit" name="paoc_intgs_mc_sett_submit" class="button button-primary right paoc-btn paoc-intgs-sett-submit paoc-intgs-mc-sett-submit paoc-sett-submit" value="<?php esc_attr_e('Save Changes', 'popup-anything-on-click'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>