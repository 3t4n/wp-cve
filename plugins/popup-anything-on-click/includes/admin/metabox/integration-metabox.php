<?php
/**
 * Handles Integration Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="paoc_integration_sett" class="paoc-vtab-cnt paoc-pro-feature paoc-integration-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Integration Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Choose popup email integration settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<th>
					<label for="paoc-mc-enable"><?php esc_html_e('Enable','popup-anything-on-click'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="" value="1" id="paoc-mc-enable" disabled="disabled" /><br/>
					<span class="description"><?php esc_html_e('Check this box to enable MailChimp integration.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>

			<tr>
				<th>
					<label><?php esc_html_e('MailChimp Lists','popup-anything-on-click'); ?></label>
				</th>
				<td>
					<div class="paoc-loop-irow">
						<label>
							<input type="checkbox" name="" value="" class="paoc-checkbox" disabled="disabled" />
							<?php esc_html_e('Essential Plugin', 'popup-anything-on-click'); ?>
						</label>
					</div>
					<br/>
					<span class="description"><?php esc_html_e('Check this box to enable entries for the respective lists.', 'popup-anything-on-click'); ?></span>
					<br/><br/>
					<div class="paoc-info">
						<?php esc_html_e('Did not find your list? Please refresh the account from', 'popup-anything-on-click'); ?>
						<a href="javascript:void(0);"><?php esc_html_e('here', 'popup-anything-on-click'); ?></a>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- end .paoc-integration-sett -->