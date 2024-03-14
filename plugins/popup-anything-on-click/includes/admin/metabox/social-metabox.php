<?php
/**
 * Handles Social Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="paoc_social_sett" class="paoc-vtab-cnt paoc-pro-feature paoc-social-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Social Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Set popup social settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl paoc-post-popup-table">
		<tbody>
			<tr>
				<th>
					<label for="paoc-social-type"><?php esc_html_e('Social Type', 'popup-anything-on-click'); ?></label>
				</th>
				<td>
					<select name="" class="paoc-select paoc-social-type" id="paoc-social-type" disabled="disabled" />
						<option value=""><?php esc_html_e('Only Icon', 'popup-anything-on-click'); ?></option>
						<option value=""><?php esc_html_e('Only Text', 'popup-anything-on-click'); ?></option>
						<option value=""><?php esc_html_e('Icon With Text', 'popup-anything-on-click'); ?></option>
					</select><br/>
					<span class="description"><?php esc_html_e('Choose social icon type.', 'popup-anything-on-click'); ?></span>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="paoc-no-padding">
					<hr/>
				</td>
			</tr>
			<tr class="paoc-social-title-row">
				<td class="paoc-no-padding">
					<select name="" class="paoc-select paoc-social-name" id="paoc-social-name" disabled="disabled">
						<option value=""><?php esc_html_e('Facebook', 'popup-anything-on-click'); ?></option>
					</select>
				</td>
				<td>
					<input type="text" name="" value="" class="paoc-text large-text paoc-social-link" disabled="disabled" />
				</td>
				<td>
					<span class="paoc-action-btn paoc-action-add-btn paoc-add-social-row" title="<?php esc_attr_e('Add', 'popup-anything-on-click'); ?>"><i class="dashicons dashicons-plus-alt"></i></span>
					<span class="paoc-action-btn paoc-action-del-btn paoc-del-social-row" title="<?php esc_attr_e('Delete', 'popup-anything-on-click'); ?>"><i class="dashicons dashicons-trash"></i></span>
				</td>
			</tr>
		</tbody>
	</table>
</div>