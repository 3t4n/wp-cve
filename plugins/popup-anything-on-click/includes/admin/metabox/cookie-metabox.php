<?php
/**
 * Handles Cookie Popup Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="paoc_cookie_sett" class="paoc-vtab-cnt paoc-pro-feature paoc-cookie-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Cookie Popup Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Choose cookie popup settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<th>
					<label for="paoc-cookie-enable"><?php esc_html_e('Enable', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="" value="1" class="paoc-checkbox paoc-cookie-enable" id="paoc-cookie-enable" disabled="disabled" /><br/>
					<span class="description"><?php esc_html_e('Check this box if you want to enable cookie based popup.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-cookie-params"><?php esc_html_e('Cookie Params', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<textarea id="paoc-cookie-params" name="" class="large-text paoc-textarea paoc-cookie-params" disabled="disabled"></textarea>
					<span class="description"><?php esc_html_e('Enter one Cookie params fragment per line. Example: test_cookie | test. So popup will be displayed if visitor browser have cookie set.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- End .paoc-cookie-sett -->