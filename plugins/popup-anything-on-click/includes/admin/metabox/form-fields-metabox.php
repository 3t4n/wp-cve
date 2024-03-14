<?php
/**
 * Handles Form Fields Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div id="paoc_form_fields_sett" class="paoc-vtab-cnt paoc-pro-feature paoc-form-fields-sett paoc-clearfix">

	<div class="paoc-tab-info-wrap">
		<div class="paoc-tab-title"><?php esc_html_e('Form Field Settings', 'popup-anything-on-click'); ?></div>
		<span class="paoc-tab-desc"><?php esc_html_e('Choose Popup form field settings.', 'popup-anything-on-click'); ?></span>
	</div>

	<table class="form-table paoc-tbl">
		<tbody>
			<tr>
				<td colspan="2" class="paoc-form-field-row-wrp paoc-no-padding">
					<table class="form-table paoc-form-field-row">
						<tbody>
							<tr>
								<th><label for="paoc-field-type"><?php esc_html_e('Field Type', 'popup-anything-on-click'); ?></label></th>
								<td>
									<select name="" class="paoc-select paoc-field-type" disabled="disabled">
										<option value=""><?php esc_html_e('Email', 'popup-anything-on-click'); ?></option>
									</select><br/>
									<span class="description paoc-email-fields"><?php esc_html_e('Select form field type.', 'popup-anything-on-click'); ?></span>
								</td>
								<td align="right" class="paoc-action-btn-wrp" style="width: 100px;">
									<span class="paoc-action-btn paoc-action-add-btn paoc-add-form-field-row" title="<?php esc_attr_e('Add', 'popup-anything-on-click'); ?>"><i class="dashicons dashicons-plus-alt"></i></span>
									<span class="paoc-action-btn paoc-action-del-btn paoc-del-form-field-row" title="<?php esc_attr_e('Delete', 'popup-anything-on-click'); ?>"><i class="dashicons dashicons-trash"></i></span>
									<span class="paoc-action-btn paoc-action-drag-btn paoc-drag-form-field-row" title="<?php esc_attr_e('Drag', 'popup-anything-on-click'); ?>"><i class="dashicons dashicons-move"></i></span>
								</td>
							</tr>
							<tr>
								<th><label for="paoc-field-label"><?php esc_html_e('Field Label', 'popup-anything-on-click'); ?></label></th>
								<td>
									<input type="text" name="" value="<?php esc_attr_e('Email', 'popup-anything-on-click'); ?>" class="paoc-text large-text paoc-field-label" id="paoc-field-label" disabled="disabled" />
									<span class="description paoc-email-fields"><?php esc_html_e('Enter form field label.', 'popup-anything-on-click'); ?></span>
								</td>
							</tr>
							<tr class="paoc-field-show paoc-field-hide-checkbox paoc-field-hide-date paoc-field-hide-math_captcha">
								<th><label for="paoc-field-plch"><?php esc_html_e('Field Placeholder', 'popup-anything-on-click'); ?></label></th>
								<td>
									<input type="text" name="" value="<?php esc_attr_e('Enter Your Email', 'popup-anything-on-click'); ?>" class="paoc-text large-text paoc-field-placeholder" id="paoc-field-plch" disabled="disabled" />
									<span class="description paoc-email-fields"><?php esc_html_e('Enter form field placeholder.', 'popup-anything-on-click'); ?></span>
								</td>
							</tr>
							<tr class="paoc-field-show paoc-field-hide-math_captcha">
								<th><label for="paoc-field-require"><?php esc_html_e('Required', 'popup-anything-on-click'); ?></label></th>
								<td>
									<input type="checkbox" name="" value="1" class="paoc-checkbox regular-text paoc-field-require" id="paoc-field-require" disabled="disabled" /><br/>
									<span class="description paoc-email-fields"><?php esc_html_e('Check this check box to enable required field.', 'popup-anything-on-click'); ?></span>
								</td>
							</tr>
							<tr>
								<th><label><?php esc_html_e('Field Key', 'popup-anything-on-click'); ?></label></th>
								<td>0</td>
							</tr>
							<tr>
								<th colspan="3"><hr/></th>
							</tr>
						</tbody>
					</table><!-- end email form fields -->
				</td>
			</tr>
		</tbody>
	</table>
</div><!-- .paoc-form-fields-sett -->