<?php
/**
 * Handles UTM Popup Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="paoc_utm_sett" class="paoc-vtab-cnt paoc-pro-feature paoc-utm-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('UTM Popup Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Choose UTM a URL parameter based popup settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<th>
					<label for="paoc-utm-enable"><?php esc_html_e('Enable', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="" value="1" class="paoc-checkbox paoc-utm-enable" id="paoc-utm-enable" disabled="disabled" /><br/>
					<span class="description"><?php esc_html_e('Check this box if you want to enable UTM based popup.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-utm-mode"><?php esc_html_e('UTM Mode', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<select name="" class="paoc-select paoc-utm-mode" id="paoc-utm-mode" disabled="disabled">
						<option value=""><?php esc_html_e('Direct UTM', 'popup-anything-on-click'); ?></option>
					</select><br/>
					<span class="description"><?php esc_html_e('Choose UTM mode.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-utm-params"><?php esc_html_e('UTM Params', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<textarea id="paoc-utm-params" name="" class="large-text paoc-textarea paoc-utm-params" disabled="disabled"></textarea>
					<span class="description"><?php esc_html_e('Enter one UTM params fragment per line. Example: popup | true. So popup will be displayed by below URL.', 'popup-anything-on-click'); ?></span><br/>
					<code><?php echo add_query_arg( 'popup', 'true', site_url() ); ?></code>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- End .paoc-utm-sett -->