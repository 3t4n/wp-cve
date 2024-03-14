<div class="notice notice-error no-format-notice inline"><p>Please select an export format in order to configure the following page.</p></div>

<h3><?php echo __( 'Additional Field Mapping', 'houzezpropertyfeed' ); ?></h3>

<p><?php echo __( 'Here you can do any additional field mapping to cater for non-standard mapping or to export into any custom fields you\'ve set up in the <a href="' . admin_url('admin.php?page=houzez_fbuilder') . '" target="_blank">Houzez Field Builder</a>', 'houzezpropertyfeed' ); ?>.</p>

<?php
	$houzez_fields = get_houzez_fields_for_field_mapping();

	// convert old-style rules to new style
	if ( isset($import_settings['field_mapping_rules']) && !empty($import_settings['field_mapping_rules']) )
	{
		$import_settings['field_mapping_rules'] = convert_old_field_mapping_to_new( $import_settings['field_mapping_rules'] );
	}
?>

<div class="rules-table-available-fields">
	<div class="rules-table">

		<div id="no_field_mappings" style="display:none; border:3px dashed #CCC; text-align:center; font-size:1.1em; padding:40px 30px">
			No field mapping rules exist. Create your first one below.
		</div>

		<div id="field_mapping_rules">
			<?php
				if ( isset($export_settings['field_mapping_rules']) && !empty($export_settings['field_mapping_rules']) )
				{
					foreach ( $export_settings['field_mapping_rules'] as $i => $and_rules )
					{
			?>
			<div class="rule-accordion">
				<div class="rule-accordion-header">

					<span class="dashicons dashicons-arrow-down-alt2"></span>
					&nbsp; 
					<span class="rule-description">
						Rule description here
					</span>

					<div class="icons">
						<span class="delete-rule dashicons dashicons-trash" title="<?php echo esc_html(__( 'Delete Rule', 'houzezpropertyfeed' )); ?>"></span>
					</div>

				</div>
				<div class="rule-accordion-contents">
					<div class="field-mapping-rule no-border no-margin">
						<div class="and-rules">
							<?php $rule_i = 0; foreach ( $and_rules['rules'] as $or_rule ) { ?>
							<div class="or-rule">
								<div style="padding:20px 0; font-weight:600" class="and-label">AND</div>
								<div>
									If 
									<select name="field_mapping_rules[<?php echo $i; ?>][houzez_field][]" style="width:270px;">
										<option value=""></option>
										<?php
											if ( !empty($houzez_fields) )
											{
												foreach ( $houzez_fields as $key => $value )
												{
													echo '<option value="' . esc_attr($key) . '"';
													if ( $key == $or_rule['houzez_field'] ) { echo ' selected'; }
													echo '>' . esc_html($value['label']) . '</option>';
												}
											}
										?>
									</select> 
									field in Houzez
								</div>
								<div>
									Is equal to 
									<input type="text" name="field_mapping_rules[<?php echo $i; ?>][equal][]" value="<?php echo esc_attr($or_rule['equal']); ?>" placeholder="Value in feed, or use * wildcard">
								</div>
								<div class="rule-actions">
									<a href="" class="add-and-rule-action"><span class="dashicons dashicons-plus-alt2"></span> Add AND Rule</a><a href="" class="delete-action"><span class="dashicons dashicons-trash"></span> Delete Rule</a>
								</div>
							</div>
							<?php ++$rule_i; } // end foreach AND rules ?>
						</div>
						<div class="then">
							<div style="padding:20px 0; font-weight:600">THEN</div>
							<div>
								Set <span class="hpf-import-format-name"></span> field
								<select name="field_mapping_rules[<?php echo $i; ?>][field]" style="width:270px;">
									<option value=""></option>
								</select> 
							</div>
							<div>
								To
								<input type="text" name="field_mapping_rules[<?php echo $i; ?>][result]" style="width:100%; max-width:380px;" value="<?php echo esc_attr($and_rules['result']); ?>" placeholder="Enter value or {field_name_here} to use value in Houzez">
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
					}
				}
			?>
		</div>

		<br>

		<hr>
		<h3>Create New Field Mapping</h3>

		<div id="field_mapping_rule_template">
			<div class="field-mapping-rule no-border no-margin">
				<div class="and-rules">
					<div class="or-rule">
						<div>
							If 
							<select name="field_mapping_rules[{rule_count}][houzez_field][]" style="width:270px;">
								<option value=""></option>
								<?php
									if ( !empty($houzez_fields) )
									{
										foreach ( $houzez_fields as $key => $value )
										{
											echo '<option value="' . esc_attr($key) . '">' . esc_html($value['label']) . '</option>';
										}
									}
								?>
							</select>
							field in Houzez
						</div>
						<div>
							Is equal to 
							<input type="text" name="field_mapping_rules[{rule_count}][equal][]" placeholder="Value in feed, or use * wildcard">
						</div>
						<div class="rule-actions">
							<a href="" class="add-and-rule-action"><span class="dashicons dashicons-plus-alt2"></span> Add AND Rule</a><a href="" class="delete-action"><span class="dashicons dashicons-trash"></span> Delete Rule</a>
						</div>
					</div>
				</div>
				<div class="then">
					<div style="padding:20px 0; font-weight:600">THEN</div>
					<div>
						Set <span class="hpf-import-format-name"></span> field
						<select name="field_mapping_rules[{rule_count}][field]" style="width:270px;">
							<option value=""></option>
						</select>
					</div>
					<div>
						To
						<input type="text" name="field_mapping_rules[{rule_count}][result]" style="width:100%; max-width:380px;" value="" placeholder="Enter value or {field_name_here} to use value in Houzez">
					</div>
				</div>
			</div>
		</div>

		<br>
		<a href="" class="button button-primary field-mapping-add-or-rule-button">Add Rule</a>

	</div>

</div>

<script>
	var hpf_rule_count = <?php echo ( isset($export_settings['field_mapping_rules']) && !empty($export_settings['field_mapping_rules']) ) ? count($export_settings['field_mapping_rules']) : 0; ?>;
</script>