<div class="notice notice-error no-format-notice inline"><p>Please select an import format in order to configure the following page.</p></div>

<h3><?php echo __( 'Property Contact Information', 'houzezpropertyfeed' ); ?></h3>

<p><?php echo __( 'There are a few ways in Houzez to determine what contact details show on a property. The settings below allow you to choose which contact details you want to use, and to match the information provided in the <span class="hpf-import-format-name"></span> feed to the display type selected', 'houzezpropertyfeed' ); ?>.</p>

<table class="form-table">
	<tbody>
		<tr>
			<th>Contact Details To Display For Properties</th>
			<td>

				<?php
					$houzez_ptype_settings = get_option('houzez_ptype_settings', array() );
				?>
				
				<?php
					$checked = false;
					if ( !isset($import_settings['agent_display_option']) || ( isset($import_settings['agent_display_option']) && $import_settings['agent_display_option'] == 'author_info' ) )
					{
						$checked = true;
					}
				?>
				<div style="padding:3px 0"><label><input<?php echo $checked ? ' checked' : ''; ?> type="radio" value="author_info" name="agent_display_option" id="agent_display_option_author_info"> Author / WordPress User</label></div>

				<?php
					if ( !isset($houzez_ptype_settings['houzez_agents_post']) || ( isset($houzez_ptype_settings['houzez_agents_post']) && $houzez_ptype_settings['houzez_agents_post'] != 'disabled' ) )
					{
						$checked = false;
						if ( isset($import_settings['agent_display_option']) && $import_settings['agent_display_option'] == 'agent_info' )
						{
							$checked = true;
						}
				?>
				<div style="padding:3px 0"><label><input<?php echo $checked ? ' checked' : ''; ?> type="radio" value="agent_info" name="agent_display_option" id="agent_display_option_agent_info"> Houzez Agent</label></div>
				<?php
					}
				?>

				<?php
					if ( !isset($houzez_ptype_settings['houzez_agencies_post']) || ( isset($houzez_ptype_settings['houzez_agencies_post']) && $houzez_ptype_settings['houzez_agencies_post'] != 'disabled' ) )
					{
						$checked = false;
						if ( isset($import_settings['agent_display_option']) && $import_settings['agent_display_option'] == 'agency_info' )
						{
							$checked = true;
						}
				?>
				<div style="padding:3px 0"><label><input<?php echo $checked ? ' checked' : ''; ?> type="radio" value="agency_info" name="agent_display_option" id="agent_display_option_agency_info"> Houzez Agency</label></div>
				<?php
					}
				?>

				<?php
					$checked = false;
					if ( isset($import_settings['agent_display_option']) && $import_settings['agent_display_option'] == 'none' )
					{
						$checked = true;
					}
				?>
				<div style="padding:3px 0"><label><input<?php echo $checked ? ' checked' : ''; ?> type="radio" value="none" name="agent_display_option" id="agent_display_option_none"> Do Not Display</label></div>

			</td>
		</tr>
	</tbody>
</table>

<?php
	$agent_display_options = array(
		'author_info' => array(
			'name' => 'WP User / Author',
			'plural' => 'WP Users / Authors',
			'items' => $wp_users,
			'manage_link' => admin_url('users.php'),
		),
	);

	if ( !isset($houzez_ptype_settings['houzez_agents_post']) || ( isset($houzez_ptype_settings['houzez_agents_post']) && $houzez_ptype_settings['houzez_agents_post'] != 'disabled' ) )
	{
		$agent_display_options['agent_info'] = array(
			'name' => 'Houzez Agent',
			'plural' => 'Houzez Agents',
			'items' => $houzez_agents,
			'manage_link' => admin_url('edit.php?post_type=houzez_agent'),
		);
	}

	if ( !isset($houzez_ptype_settings['houzez_agencies_post']) || ( isset($houzez_ptype_settings['houzez_agencies_post']) && $houzez_ptype_settings['houzez_agencies_post'] != 'disabled' ) )
	{
		$agent_display_options['agency_info'] = array(
			'name' => 'Houzez Agency',
			'plural' => 'Houzez Agencies',
			'items' => $houzez_agencies,
			'manage_link' => admin_url('edit.php?post_type=houzez_agency'),
		);
	}

	foreach ( $agent_display_options as $agent_display_option => $details )
	{
?>

<div class="agent-display-option-rules" id="agent_display_option_rules_container_<?php echo $agent_display_option; ?>" style="display:none">

	<hr>

	<?php
		if ( empty($details['items']) )
		{
			echo '<div class="notice notice-info inline"><p>No <a href="' . esc_attr($details['manage_link']) . '" target="_blank">' . esc_html($details['plural']) . '</a> exist. You\'ll need to add some to use this option.</p></div>';
		}
	?>

	<table class="form-table">
		<tbody>
			<tr>
				<th>Rules</th>
				<td>
	 				<div id="agent_display_option_rule_template_<?php echo $agent_display_option; ?>" style="display:none">
						<div class="agent-display-option-rule">
							<div>
								If 
								<select name="<?php echo esc_attr($agent_display_option); ?>_rules_field[]">
									<option value=""></option>
								</select> 
								field in <span class="hpf-import-format-name"></span> feed
							</div>
							<div>
								Is equal to 
								<input type="text" name="<?php echo esc_attr($agent_display_option); ?>_rules_equal[]" placeholder="Value in feed, or use * wildcard">
							</div>
							<div>
								Then set Houzez agent to
								<select name="<?php echo esc_attr($agent_display_option); ?>_rules_result[]">
									<option value=""></option>
									<?php
										if ( !empty($details['items']) )
										{
											foreach ( $details['items'] as $item_id => $item_name )
											{
												echo '<option value="' . esc_attr($item_id) . '">' . esc_html( $item_name ) . '</option>';
											}
										}
									?>
								</select>
							</div>
							<div class="delete-rule">
								<a href=""><span class="dashicons dashicons-trash"></span> Delete Rule</a>
							</div>
						</div>
					</div>

					<div id="agent_display_option_rules_<?php echo esc_attr($agent_display_option); ?>">
						<?php
							if ( isset($import_settings['agent_display_option']) && $import_settings['agent_display_option'] == $agent_display_option )
							{
								if ( isset($import_settings['agent_display_option_rules']) && is_array($import_settings['agent_display_option_rules']) && !empty($import_settings['agent_display_option_rules']) )
								{
									foreach ( $import_settings['agent_display_option_rules'] as $rule )
									{
						?>
						<div class="agent-display-option-rule">
							<div>
								If 
								<select name="<?php echo esc_attr($agent_display_option); ?>_rules_field[]">
									<option value=""></option>
								</select> 
								field in <span class="hpf-import-format-name"></span> feed
							</div>
							<div>
								Is equal to 
								<input type="text" name="<?php echo esc_attr($agent_display_option); ?>_rules_equal[]" value="<?php echo esc_attr($rule['equal']); ?>" placeholder="Value in feed, or use * wildcard">
							</div>
							<div>
								Then set Houzez agent to
								<select name="<?php echo esc_attr($agent_display_option); ?>_rules_result[]">
									<option value=""></option>
									<?php
										if ( !empty($details['items']) )
										{
											foreach ( $details['items'] as $item_id => $item_name )
											{
												echo '<option value="' . esc_attr($item_id) . '"';
												if ( $rule['result'] == $item_id ) { echo ' selected'; }
												echo '>' . esc_html( $item_name ) . '</option>';
											}
										}
									?>
								</select>
							</div>
							<div class="delete-rule">
								<a href=""><span class="dashicons dashicons-trash"></span> Delete Rule</a>
							</div>
						</div>
						<?php
									}
								}
							}
						?>
					</div>

					<a href="" class="button agent-display-option-add-rule-button" id="agent_display_option_add_rule_button_<?php echo esc_attr($agent_display_option); ?>">Add Rule</a>
				
				</td>
			</tr>
		</tbody>
	</table>

</div>

<?php
	}
?>