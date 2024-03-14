<?php
/**
 * Handles Referrer Popup Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="paoc_referrer_sett" class="paoc-vtab-cnt paoc-pro-feature paoc-referrer-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Referrer Popup Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Choose referrer popup settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<th>
					<label for="paoc-referrer-enable"><?php esc_html_e('Enable', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="" value="1" class="paoc-checkbox paoc-referrer-enable" id="paoc-referrer-enable" disabled="disabled" /><br/>
					<span class="description"><?php esc_html_e('Check this box if you want to display referrer popup.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-referrer-mode"><?php esc_html_e('Referrer Mode', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<select name="" class="paoc-select paoc-referrer-mode" id="paoc-referrer-mode" disabled="disabled">
						<option value=""><?php esc_html_e('Direct Referrer', 'popup-anything-on-click'); ?></option>
					</select><br/>
					<span class="description"><?php esc_html_e('Choose referrer mode.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label for="paoc-referrer-url"><?php esc_html_e('Referrer URLs', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<textarea id="paoc-referrer-url" name="" class="large-text paoc-textarea paoc-referrer-url" disabled="disabled"></textarea>
					<span class="description"><?php esc_html_e('Enter Referrer URLs for which you want to show popup.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- End .paoc-referrer-sett -->