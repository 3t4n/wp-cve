<div class="notice notice-error no-format-notice inline"><p>Please select an import format in order to configure the following page.</p></div>

<h3><?php echo __( 'Additional Field Mapping', 'houzezpropertyfeed' ); ?></h3>

<p><?php echo __( 'Here you can do any additional field mapping to cater for non-standard mapping or to import into any custom fields you\'ve set up in the <a href="' . admin_url('admin.php?page=houzez_fbuilder') . '" target="_blank">Houzez Field Builder</a>', 'houzezpropertyfeed' ); ?>.</p>

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
		<div class="notice notice-info inline" id="missing_mandatory_xml_field_mapping" style="display:none"><p><?php echo __( 'No title, excerpt or content fields mapped. At least one of these is mandatory for a property to import.', 'houzezpropertyfeed' ); ?></p></div>
		<div class="notice notice-info inline" id="missing_mandatory_csv_field_mapping" style="display:none"><p><?php echo __( 'No title, excerpt or content fields mapped. At least one of these is mandatory for a property to import.', 'houzezpropertyfeed' ); ?></p></div>
		
		<br>
		
		<div id="no_field_mappings" style="display:none; border:3px dashed #CCC; text-align:center; font-size:1.1em; padding:40px 30px">
			No field mapping rules exist. Create your first one below.
		</div>
			
		<div id="field_mapping_rules">
			<?php
				if ( isset($import_settings['field_mapping_rules']) && !empty($import_settings['field_mapping_rules']) )
				{
					foreach ( $import_settings['field_mapping_rules'] as $i => $and_rules )
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
						<span class="duplicate-rule dashicons dashicons-admin-page" title="<?php echo esc_html(__( 'Duplicate Rule', 'houzezpropertyfeed' )); ?>"></span>
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
									<input type="text" name="field_mapping_rules[<?php echo $i; ?>][field][]" value="<?php echo esc_attr($or_rule['field']); ?>">
									field in <span class="hpf-import-format-name"></span> feed
								</div>
								<div>
									Is <select name="field_mapping_rules[<?php echo $i; ?>][operator][]">
										<option value="="<?php if ( !isset($or_rule['operator']) || ( isset($or_rule['operator']) && $or_rule['operator'] == '=' ) ) { echo ' selected'; } ?>>equal to</option>
										<option value="!="<?php if ( isset($or_rule['operator']) && $or_rule['operator'] == '!=' ) { echo ' selected'; } ?>>not equal to</option>
									</select>
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
								Set Houzez field
								<select name="field_mapping_rules[<?php echo $i; ?>][houzez_field]" style="width:250px;">
									<option value=""></option>
									<?php
										$houzez_field_delimited = false;
										$houzez_field_options = array();
										if ( !empty($houzez_fields) )
										{
											foreach ( $houzez_fields as $key => $value )
											{
												echo '<option value="' . esc_attr($key) . '"';
												if ( $key == $and_rules['houzez_field'] ) 
												{ 
													echo ' selected'; 
													if ( isset($value['options']) && is_array($value['options']) && !empty($value['options']) )
													{
														$houzez_field_options = $value['options'];
													}
													if ( isset($value['delimited']) && $value['delimited'] === true )
													{
														$houzez_field_delimited = true;
													}
												}
												echo '>' . esc_html($value['label']) . '</option>';
											}
										}
									?>
								</select> 
								<div class="notice notice-info inline already-mapped-warning" style="margin-top:15px; display:none"><p>The <span class="already-mapped-field"></span> field is already mapped by default in the <span class="hpf-import-format-name"></span> feed. Creating a mapping here will overwrite this.</p></div>
							</div>
							<div>
								To
								<span class="result-text"<?php if ( !empty($houzez_field_options) ) { echo ' style="display:none"'; } ?>>
									<input type="text" name="field_mapping_rules[<?php echo $i; ?>][result]" style="width:100%; max-width:340px;" value="<?php echo esc_attr($and_rules['result']); ?>" placeholder="Enter value or {field_name_here} to use value sent">
								</span>
								<span class="result-dropdown"<?php if ( empty($houzez_field_options) ) { echo ' style="display:none"'; } ?>>
									<select name="field_mapping_rules[<?php echo $i; ?>][result_option]"><?php
										$result_type = 'text';
										if ( !empty($houzez_field_options) )
										{
											$result_type = 'dropdown';
											echo '<option value=""></option>';
											foreach ( $houzez_field_options as $key => $value )
											{
												echo '<option value="' . $key . '"';
												if ( $and_rules['result'] == $key )
												{
													echo ' selected';
												}
												echo '>' . $value . '</option>';
											}
										}
									?></select>
								</span>
								<input type="hidden" name="field_mapping_rules[<?php echo $i; ?>][result_type]" value="<?php echo esc_attr($result_type); ?>">
							</div>
							<div style="display:<?php if ( $houzez_field_delimited ) { echo 'block'; }else{ echo 'none'; } ?>" class="delimited">
								<label><input type="checkbox" name="field_mapping_rules[<?php echo $i; ?>][delimited]" value="1"<?php if ( isset($and_rules['delimited']) && $and_rules['delimited'] === true ) { echo ' checked'; } ?>> Delimited?</label>
								<span class="delimited-character" style="display:<?php if ( isset($and_rules['delimited']) && $and_rules['delimited'] === true ) { echo 'inline'; }else{ echo 'none'; } ?>;">By character <input type="text" name="field_mapping_rules[<?php echo $i; ?>][delimited_character]" style="max-width:50px;" value="<?php echo ( isset($and_rules['delimited_character']) ? esc_attr($and_rules['delimited_character']) : ',' ); ?>"></span>
								<div style="font-style:italic; margin-top:6px; color:#AAA"><span class="dashicons dashicons-info"></span> Tick 'Delimited' if all features are provided in one single field separated by a specific character. If features are provided as individual fields in the third party data use the 'Property Feature [1-9]' field(s)</div>
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
							<input type="text" name="field_mapping_rules[{rule_count}][field][]" value="">
							field in <span class="hpf-import-format-name"></span> feed
						</div>
						<div>
							Is <select name="field_mapping_rules[{rule_count}][operator][]">
								<option value="=">equal to</option>
								<option value="!=">not equal to</option>
							</select>
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
						Set Houzez field
						<select name="field_mapping_rules[{rule_count}][houzez_field]" style="width:250px;">
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
						<div class="notice notice-info inline already-mapped-warning" style="margin-top:15px; display:none"><p>The <span class="already-mapped-field"></span> field is already mapped by default in the <span class="hpf-import-format-name"></span> feed. Creating a mapping here will overwrite this.</p></div>
					</div>
					<div>
						To
						<span class="result-text"><input type="text" name="field_mapping_rules[{rule_count}][result]" style="width:100%; max-width:340px;" value="" placeholder="Enter value or {field_name_here} to use value sent"></span>
						<span class="result-dropdown" style="display:none"><select name="field_mapping_rules[{rule_count}][result_option]"></select></span>
						<input type="hidden" name="field_mapping_rules[{rule_count}][result_type]" value="text">
					</div>
					<div style="display:none" class="delimited">
						<label><input type="checkbox" name="field_mapping_rules[{rule_count}][delimited]" value="1"> Delimited?</label>
						<span class="delimited-character" style="display:none;">By character <input type="text" name="field_mapping_rules[{rule_count}][delimited_character]" style="max-width:50px;" value=","></span>
						<div style="font-style:italic; margin-top:6px; color:#AAA"><span class="dashicons dashicons-info"></span> Tick 'Delimited' if all features are provided in one single field separated by a specific character. If features are provided as individual fields in the third party data use the 'Property Feature [1-9]' field(s)</div>
					</div>
				</div>
				
			</div>

		</div>

		<br>
		<a href="" class="button button-primary field-mapping-add-or-rule-button">Add Rule</a>

	</div>

	<div class="xml-rules-available-fields" style="display:none">
		<h3 style="margin-top:0">Fields found in the XML</h3>
		<p>Below is a list of fields found in the XML using the <a href="https://www.w3schools.com/xml/xpath_syntax.asp" target="_blank">XPath syntax</a>.</p>
		<p>You can <strong>click and drag</strong> the fields below into the rule.</p>
		<hr>
		<?php echo '<p id="no_nodes_found"' . ( ( !isset($import_settings['property_node_options']) || ( isset($import_settings['property_node_options']) && empty($import_settings['property_node_options']) ) ) ? '' : ' style="display:none"' ) . '><em>' . __( 'No XML fields found. Please go to the \'Import Format\' tab and click \'Fetch XML\' to obtain a list of these.', 'houzezpropertyfeed' ) . '</em></p>'; ?>
		<div id="xml-nodes-found">
			<?php
			if ( isset($import_settings['property_node_options']) && !empty($import_settings['property_node_options']) )
			{
				$options = json_decode($import_settings['property_node_options']);

				if ( !empty($options) )
				{
					foreach ( $options as $option )
					{
						$node_name = $option;
						if ( isset($import_settings['property_node']) && !empty($import_settings['property_node']) )
						{
							if ( strpos($node_name, $import_settings['property_node']) === false )
							{
								continue;
							}

							$node_name = str_replace($import_settings['property_node'], '', $node_name);
						}

						if ( !empty($node_name) )
						{
							echo '<a href="#">' . $node_name . '</a>';
						}
					}	
				}
			}
		?></div>
	</div>

	<div class="csv-rules-available-fields" style="display:none">
		<h3 style="margin-top:0">Fields found in the CSV</h3>
		<p>Below is a list of the fields we found in the CSV provided.</p>
		<p>You can <strong>click and drag</strong> the fields below into the rule.</p>
		<hr>
		<?php echo '<p id="no_fields_found"' . ( ( !isset($import_settings['property_field_options']) || ( isset($import_settings['property_field_options']) && empty($import_settings['property_node_options']) ) ) ? '' : ' style="display:none"' ) . '><em>' . __( 'No CSV fields found. Please go to the \'Import Format\' tab and click \'Fetch CSV\' to obtain a list of these.', 'houzezpropertyfeed' ) . '</em></p>'; ?>
		<div id="csv-fields-found">
			<?php
			if ( isset($import_settings['property_field_options']) && !empty($import_settings['property_field_options']) )
			{
				$options = json_decode($import_settings['property_field_options']);

				if ( !empty($options) )
				{
					foreach ( $options as $option )
					{
						$field_name = $option;

						if ( !empty($field_name) )
						{
							echo '<a href="#">' . $field_name . '</a>';
						}
					}	
				}
			}
		?></div>
	</div>
</div>

<script>
	var hpf_rule_count = <?php echo ( isset($import_settings['field_mapping_rules']) && !empty($import_settings['field_mapping_rules']) ) ? count($import_settings['field_mapping_rules']) : 0; ?>;
</script>