<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://activetrail.com
 * @since      1.0.0
 *
 * @package    Activetrail_Cfs
 * @subpackage Activetrail_Cfs/admin/partials
 */
?>

<h3>Contact Form 7 / ACTIVE<span style="font-weight: normal;">TRAIL<sub style="font-size: 23px; color: #388bcf;">&bull;</sub></span> Settings</h3>

<?php if(count($error_stack) > 0): ?>
<div>
	<?php foreach($error_stack as $msg): ?>
		<p><strong>ERROR: </strong><?php echo esc_html($msg); ?></p>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<div id="at-cf7-form-cntr">
	<div class="clearfix flexbox-container">
		<div class="at-width-50">
			<h4 class="at-h4">Required Fields</h4>
			<fieldset>
				<?php foreach($this->at_cf7_metafields['form']['required'] as $key => $value): ?>
					<legend><?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?> field</legend>
					<input type="text" placeholder="" id="<?php echo sanitize_text_field($value); ?>_tag" name="wpcf7-activetrail[<?php echo sanitize_text_field($value); ?>]" 
							value="<?php echo isset($settings_values[$value]) ?  esc_attr($settings_values[$value]) : ''; ?>" />
				<?php endforeach; ?>
			</fieldset>
			<br />
			<hr />
			<h4 class="at-h4">Optional Fields</h4>
			<fieldset>
				<?php foreach($this->at_cf7_metafields['form']['optional'] as $key => $value): ?>
					<legend><?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?> field</legend>
					<input type="text" placeholder="" id="<?php echo sanitize_text_field($value); ?>_tag" name="wpcf7-activetrail[<?php echo sanitize_text_field($value); ?>]" 
							value="<?php echo isset($settings_values[$value]) ?  esc_attr($settings_values[$value]) : ''; ?>" />
				<?php endforeach; ?>
			</fieldset>
		</div>
		<div class="at-width-50 at-text-center">
			<div class="">
				<img src="<?php echo esc_url($plugin_url . '/images/activetrail-logo.png'); ?>" style="max-width: 80%;" />
			</div>
		</div>
	</div>
	<br />
	<hr />
	<h4 class="at-h4">ActiveTrail Custom Fields</h4>
	<?php if(isset($settings_values['token_id']) && $settings_values['token_id'] != ''): ?>
	<div>
		<table style="width: 100%;">
			<thead style="text-align: left;">
				<tr>
					<th>
						Contact Form 7 Tag
					</th>
					<th>
					</th>
					<th>
						ActiveTrail Field
					</th>
					<th class="at-text-center">
						Action
					</th>
					<th style="text-align: right;">
						<button type="button" class="button-primary" id="at-opt-add">Add</button>
					</th>
				</tr>
			</thead>
			<tbody style="text-align: left;" id="at-opt-cntr">
				<tr id="at-opt-template" class="at-hidden">
					<td>
						<input style="width: 100%" type="text" placeholder="" name="" />
						<input type="hidden" value="replace" name="" />
					</td>
					<td style="text-align: center;">
						<strong>map to</strong>
					</td>
					<td>
						<select style="width: 100%">
							<?php foreach($this->at_cf7_metafields['form']['api'] as $key => $field_data): ?>
							<option value="<?php echo esc_attr(strtolower($field_data['field_name'])); ?>">
								<?php echo esc_html($field_data['field_display_name']); ?>
							</option>
							<?php endforeach; ?>
						</select>
					</td>
					<td class="at-text-center">
						<label>
							<input type="checkbox" value="merge" class="regular-checkbox" /> Merge
						</label>
					</td>
					<td style="text-align: right;">
						<button type="button" class="button-secondary at-opt-remove">Remove</button>
					</td>
				</tr>
				<?php if(count($optional_settings_values) > 0): ?>
					<?php foreach($optional_settings_values as $field => $value_pair): ?>
						<?php $parts = explode('|', $value_pair); ?>
						<?php if(!isset($parts[0]) || preg_replace('/\s+/', '', $parts[0]) == '') { $parts[0] = ''; } ?>
						<?php if(!isset($parts[1]) || preg_replace('/\s+/', '', $parts[1]) == '') { $parts[1] = 'replace'; } ?>
						<tr>
							<td>
								<input style="width: 100%" type="text" placeholder="" value="<?php echo sanitize_text_field($parts[0]); ?>" 
									   name="wpcf7-activetrail-optional[<?php echo esc_attr($field); ?>][src]" />
							</td>
							<td style="text-align: center;">
								<strong>map to</strong>
							</td>
							<td>
								<select style="width: 100%" name="wpcf7-activetrail-optional[<?php echo $field; ?>][dst]">
									<?php foreach($this->at_cf7_metafields['form']['api'] as $key => $field_data): ?>
									<option <?php if($field == strtolower($field_data['field_name']) ){ echo 'selected="selected"';} ?> 
										value="<?php echo sanitize_text_field(strtolower($field_data['field_name'])); ?>">
										<?php echo esc_html($field_data['field_display_name']); ?>
									</option>
									<?php endforeach; ?>
								</select>
							</td>
							<td class="at-text-center">
								<label>
									<input type="checkbox" value="merge" <?php if(sanitize_text_field($parts[1]) == 'merge'): ?>checked="checked"<?php endif; ?> class="regular-checkbox" name="wpcf7-activetrail-optional[<?php echo $field; ?>][action]" /> Merge
								</label>
							</td>
							<td style="text-align: right;">
								<button type="button" class="button-secondary at-opt-remove">Remove</button>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		
	</div>
	<?php else: ?>
	<p style="color: red;"> 
		<strong><i>Input a valid ActiveTrail AppIdToken and save this form to enable this section!</i></strong>
	</p>
	<?php endif; ?>
	<br />
	<hr />
	<h3>ActiveTrail Settings</h3>
	<fieldset>
		<?php foreach($this->at_cf7_metafields['activetrail'] as $key => $value): ?>
			<legend><?php echo esc_html(ucwords(str_replace('_', ' ', $value))); ?> <?php if ($value == 'mailing_list_id') { echo "(Optional)"; } ?></legend>
			<input style="width: 100%" type="text" placeholder="" id="<?php echo sanitize_text_field($value); ?>_tag" name="wpcf7-activetrail[<?php echo sanitize_text_field($value); ?>]" 
				   value="<?php echo (isset($settings_values[$value]) && preg_replace('/\s+/', '', $settings_values[$value]) != '') ? esc_attr($settings_values[$value]) : ''; ?>" />

		<?php endforeach; ?>
	</fieldset>
	
</div>