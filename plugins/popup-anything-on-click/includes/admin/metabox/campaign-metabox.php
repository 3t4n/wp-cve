<?php
/**
 * Handles Campaign Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="paoc_campaign_sett" class="paoc-vtab-cnt paoc-pro-feature paoc-campaign-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('A/B Testing Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Check popup A/B testing campaign settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<td colspan="2" class="paoc-no-lr-padding">
					<?php esc_html_e('Campaign', 'popup-anything-on-click'); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>