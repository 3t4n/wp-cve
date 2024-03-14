<h3><?php echo __( 'Import Format', 'houzezpropertyfeed' ); ?></h3>

<p><?php echo __( 'Select the CRM or format that you want to import using below', 'houzezpropertyfeed' ); ?>:</p>

<table class="form-table">
	<tbody>
		<tr>
			<th><label for="format"><?php echo __( 'Choose Format', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<select name="format" id="format" style="width:250px;">
					<option value=""></option>
					<?php
						foreach ( $formats as $key => $format )
						{
							echo '<option value="' . $key . '"';
							echo ( ( isset($import_settings['format']) && $import_settings['format'] == $key ) ? ' selected' : '' );
							echo '>' . esc_html($format['name']) . '</option>';
						}
					?>
				</select>
				<input type="hidden" name="previous_format" value="<?php echo (isset($import_settings['format']) ? $import_settings['format'] : ''); ?>">
			</td>
		</tr>
	</tbody>
</table>

<?php
	foreach ( $formats as $key => $format )
	{
?>

<div id="import_settings_<?php echo esc_attr($key); ?>" class="import-settings-format" style="display:none">

<h3><?php echo esc_html($format['name']) . ' ' . __( 'Settings', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table">
	<tbody>
		<?php
			foreach ( $format['fields'] as $field )
			{
				if ( $field['type'] == 'hidden' )
				{
					echo '<input 
						type="' . esc_attr($field['type']) . '" 
						name="' . esc_attr($key . '_' . $field['id']) . '" 
						value="' . ( ( isset($import_settings[$field['id']]) ) ? esc_attr($import_settings[$field['id']]) : ( isset($field['default']) ? esc_attr($field['default']) : '' ) ) . '" 
					>';
					continue;
				}
		?>
			<tr>
				<th><?php echo isset($field['label']) ? esc_html($field['label']) : ''; ?></th>
				<td><?php
					switch ($field['type'])
					{
						case "text":
						case "number":
						{
							echo '<input 
								type="' . esc_attr($field['type']) . '" 
								name="' . esc_attr($key . '_' . $field['id']) . '" 
								value="' . ( ( isset($import_settings[$field['id']]) ) ? esc_attr($import_settings[$field['id']]) : ( isset($field['default']) ? esc_attr($field['default']) : '' ) ) . '" 
								placeholder="' . ( isset($field['placeholder']) ? esc_attr($field['placeholder']) : '' ) . '"
								style="width:100%; max-width:450px;' . ( isset($field['css']) ? ' ' . esc_attr($field['css']) : '' ) . '"
							>';
							echo ( isset($field['tooltip']) ? '<div style="color:#999; font-size:13px; margin-top:5px;">' . wp_kses($field['tooltip'], array('br' => array())) . '</div>' : '' );
							break;
						}
						case "checkbox":
						{
							echo '<input 
								type="checkbox" 
								name="' . esc_attr($key . '_' . $field['id']) . '" 
								value="yes"
								' . ( ( isset($import_settings[$field['id']]) && $import_settings[$field['id']] == 'yes' ) ? 'checked' : ( ( isset($field['default']) && $field['default'] == 'yes' ) ? 'checked' : '' ) ) . '
							>';
							echo ( isset($field['tooltip']) ? '<div style="color:#999; font-size:13px; margin-top:5px;">' . wp_kses($field['tooltip'], array('br' => array())) . '</div>' : '' );
							break;
						}
						case "radio":
						{
							$options = array();
							if ( isset($field['options']) && is_array($field['options']) && !empty($field['options']) )
							{
								$options = $field['options'];
							}

							if ( !empty($options) )
							{
								foreach ( $options as $option_key => $option_value )
								{
									echo '<div style="margin-bottom:5px;"><label><input type="radio" name="' . esc_attr($key . '_' . $field['id']) . '" value="' . $option_key . '"';
									if (
										( isset($import_settings[$field['id']]) && $import_settings[$field['id']] == $option_key )
										||
										( !isset($import_settings[$field['id']]) && ( isset($field['default']) && $field['default'] == $option_key ) )
									)
									{
										echo ' checked';
									}
									echo '> ' . esc_html($option_value) . '</label></div>';
								}
							}
							echo ( isset($field['tooltip']) ? '<div style="color:#999; font-size:13px; margin-top:5px;">' . wp_kses($field['tooltip'], array('br' => array())) . '</div>' : '' );
							break;
						}
						case "select":
						{
							echo '<select 
								name="' . esc_attr($key . '_' . $field['id']) . '" 
							>';
							$options = array();
							if ( isset($field['options']) && is_array($field['options']) && !empty($field['options']) )
							{
								$options = $field['options'];
							}
							elseif ( isset($import_settings[$field['id'] . '_options']) && !empty($import_settings[$field['id'] . '_options']) )
							{
								$options = json_decode($import_settings[$field['id'] . '_options']);

								$new_options = array();
								if ( !empty($options) )
								{
									foreach ( $options as $option_key => $option_value )
									{
										$new_options[$option_value] = $option_value;
									}
								}

								$options = $new_options; 
							}

							if ( $field['id'] == 'property_id_node' )
							{
								if ( isset($import_settings['property_node_options']) && !empty($import_settings['property_node_options']) )
								{
									// use options from property_node_options
									$options = json_decode($import_settings['property_node_options']);

									$new_options = array();
									if ( !empty($options) )
									{
										foreach ( $options as $option_key => $option_value )
										{
											$node_name = $option_value;
											if ( isset($import_settings['property_node']) && !empty($import_settings['property_node']) )
											{
												if ( strpos($node_name, $import_settings['property_node']) === false )
												{
													continue;
												}

												$node_name = str_replace($import_settings['property_node'], '', $node_name);
											}

											$new_options[$node_name] = $node_name;
										}
									}

									$options = $new_options; 
								}
							}

							if ( $field['id'] == 'property_id_field' )
							{
								if ( isset($import_settings['property_field_options']) && !empty($import_settings['property_field_options']) )
								{
									// use options from property_field_options
									$options = json_decode($import_settings['property_field_options']);

									$new_options = array();
									if ( !empty($options) )
									{
										foreach ( $options as $option_key => $option_value )
										{
											$field_name = $option_value;

											$new_options[$field_name] = $field_name;
										}
									}

									$options = $new_options; 
								}
							}

							if ( !empty($options) )
							{
								foreach ( $options as $option_key => $option_value )
								{
									echo '<option value="' . $option_key . '"';
									if (
										( isset($import_settings[$field['id']]) && $import_settings[$field['id']] == $option_key )
										||
										( !isset($import_settings[$field['id']]) && ( isset($field['default']) && $field['default'] == $option_key ) )
									)
									{
										echo ' selected';
									}
									echo '>' . esc_html($option_value) . '</option>';
								}
							}
							else
							{
								if ( isset($field['no_options_value']) && !empty($field['no_options_value']) )
								{
									echo '<option value="">' . $field['no_options_value'] . '</option>';
								}
							}
							echo '</select>';
							echo ( isset($field['tooltip']) ? '<div style="color:#999; font-size:13px; margin-top:5px;">' . wp_kses($field['tooltip'], array('br' => array())) . '</div>' : '' );
							break;
						}
						case "html":
						{
							echo $field['html'];
							break;
						}
					}
				?></td>
			</tr>
		<?php
			}
		?>
	</tbody>
</table>

<?php
	if ( isset($format['warnings']) && is_array($format['warnings']) && !empty($format['warnings']) )
	{
		foreach ( $format['warnings'] as $warning )
		{
			echo '<div class="notice notice-error inline"><p>' . esc_html($warning) . '</p></div>';
		}
	}
?>

<?php
	if ( isset($format['help_url']) && !empty($format['help_url']) )
	{
		echo '<p style="color:#999"><span class="dashicons dashicons-editor-help"></span> <strong>Need help?</strong> Read our documentation for instructions on <a href="' . esc_attr($format['help_url']) . '" target="_blank">setting up an import from ' . esc_html($format['name']) . '</a></p>';
	}
?>

</div>

<?php
	}
?>