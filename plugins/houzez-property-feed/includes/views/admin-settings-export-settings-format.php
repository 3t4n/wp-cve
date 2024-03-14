<h3><?php echo __( 'Export Format', 'houzezpropertyfeed' ); ?></h3>

<p><?php echo __( 'Select the format that you want to export in using below', 'houzezpropertyfeed' ); ?>:</p>

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
							echo ( ( isset($export_settings['format']) && $export_settings['format'] == $key ) ? ' selected' : '' );
							echo '>' . esc_html($format['name']) . '</option>';
						}
					?>
				</select>
			</td>
		</tr>
		<tr id="export_name_row" style="display:none">
			<th><label for="export_name"><?php echo __( 'Name', 'houzezpropertyfeed' ); ?></label></th>
			<td>
				<input type="text" name="export_name" id="export_name" style="width:100%; max-width:400px;" value="<?php echo isset($export_settings['name']) ? esc_attr($export_settings['name']) : ''; ?>" placeholder="">
				<div style="color:#999; font-size:13px; margin-top:5px;">Name given to export for internal purposes. Useful if using the same format for multiple exports</div>
			</td>
		</tr>
	</tbody>
</table>

<?php
	foreach ( $formats as $key => $format )
	{
?>

<div id="export_settings_<?php echo esc_attr($key); ?>" class="import-settings-format" style="display:none">

	<?php
		if ( isset($format['fields']) && !empty($format['fields']) )
		{
	?>

<h3><?php echo esc_html($format['name']) . ' ' . __( 'Settings', 'houzezpropertyfeed' ); ?></h3>

<table class="form-table">
	<tbody>
		<?php
			foreach ( $format['fields'] as $field )
			{
				if ( $field['type'] == 'hidden' )
				{
					$value = ( ( isset($export_settings[$field['id']]) ) ? esc_attr($export_settings[$field['id']]) : ( isset($field['default']) ? esc_attr($field['default']) : '' ) );
					if ( substr($field['id'], 0, 9) == 'previous_' && empty($value) )
					{
						$field_id_check = str_replace('previous_', '', $field['id']);
						$value = ( ( isset($export_settings[$field_id_check]) ) ? esc_attr($export_settings[$field_id_check]) : '' );
					}

					echo '<input 
						type="' . esc_attr($field['type']) . '" 
						name="' . esc_attr($key . '_' . $field['id']) . '" 
						value="' . $value . '" 
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
								value="' . ( ( isset($export_settings[$field['id']]) ) ? esc_attr($export_settings[$field['id']]) : ( isset($field['default']) ? esc_attr($field['default']) : '' ) ) . '" 
								placeholder="' . ( isset($field['placeholder']) ? esc_attr($field['placeholder']) : '' ) . '"
								style="width:100%; max-width:450px;"
							>';
							echo ( isset($field['tooltip']) ? '<div style="color:#999; font-size:13px; margin-top:5px;">' . wp_kses($field['tooltip'], array('br' => array())) . '</div>' : '' );
							break;
						}
						case "file":
						{
							if ( isset($export_settings[$field['id']]) && !empty($export_settings[$field['id']]) )
	                        {
	                            $uploads_dir = wp_upload_dir();
	                            $uploads_dir = $uploads_dir['baseurl'] . '/houzez_property_feed_export/';
	                            echo '<a href="' . $uploads_dir . $export_settings[$field['id']] . '" target="_blank">Download Uploaded ' . $field['label'] . '</a><br><br>';
	                        }
							echo '<input 
								type="' . esc_attr($field['type']) . '" 
								name="' . esc_attr($key . '_' . $field['id']) . '" 
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
								' . ( ( isset($export_settings[$field['id']]) && $export_settings[$field['id']] == 'yes' ) ? 'checked' : ( ( isset($field['default']) && $field['default'] == 'yes' ) ? 'checked' : '' ) ) . '
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
										( isset($export_settings[$field['id']]) && $export_settings[$field['id']] == $option_key )
										||
										( !isset($export_settings[$field['id']]) && ( isset($field['default']) && $field['default'] == $option_key ) )
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
							elseif ( isset($export_settings[$field['id'] . '_options']) && !empty($export_settings[$field['id'] . '_options']) )
							{
								$options = json_decode($export_settings[$field['id'] . '_options']);

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
								if ( isset($export_settings['property_node_options']) && !empty($export_settings['property_node_options']) )
								{
									// use options from property_node_options
									$options = json_decode($export_settings['property_node_options']);

									$new_options = array();
									if ( !empty($options) )
									{
										foreach ( $options as $option_key => $option_value )
										{
											$node_name = $option_value;
											if ( isset($export_settings['property_node']) && !empty($export_settings['property_node']) )
											{
												if ( strpos($node_name, $export_settings['property_node']) === false )
												{
													continue;
												}

												$node_name = str_replace($export_settings['property_node'], '', $node_name);
											}

											$new_options[$node_name] = $node_name;
										}
									}

									$options = $new_options; 
								}
							}

							if ( $field['id'] == 'property_id_field' )
							{
								if ( isset($export_settings['property_field_options']) && !empty($export_settings['property_field_options']) )
								{
									// use options from property_field_options
									$options = json_decode($export_settings['property_field_options']);

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
										( isset($export_settings[$field['id']]) && $export_settings[$field['id']] == $option_key )
										||
										( !isset($export_settings[$field['id']]) && ( isset($field['default']) && $field['default'] == $option_key ) )
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
	}

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
		echo '<p style="color:#999"><span class="dashicons dashicons-editor-help"></span> <strong>Need help?</strong> Read our documentation for instructions on <a href="' . esc_attr($format['help_url']) . '" target="_blank">setting up an export in the ' . esc_html($format['name']) . ' format</a></p>';
	}
?>

</div>

<?php
	}
?>