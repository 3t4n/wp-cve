<?php 
if (!defined('ABSPATH'))
{
	exit;
}

function bpMembersOnlyFreePostTypesSettings()
{
	global $wpdb;

	$bpmemonlypro_enabled_post_type_ = get_option("bpmemonlypro_enabled_post_type");
	if (empty($bpmemonlypro_enabled_post_type_))
	{
		$bpmemonlypro_enabled_post_type_ = array();
	}
	
	$all_original_post_types = get_post_types();
	$passed_default_post_types = array('attachment' => 'attachment','revision' => 'revision','nav_menu_item' => 'nav_menu_item','custom_css' => 'custom_css','customize_changeset' => 'customize_changeset','tooltips' => 'tooltips');
	$all_post_types = array_diff($all_original_post_types, $passed_default_post_types);

	if (isset ( $_POST ['bpmemonlypro_enabled_post_type_submit'] )) 
	{
		check_admin_referer ( 'bpcomponentsubmitnonce' );
		if (isset ( $_POST ['bpmemonlypro_enabled_post_type_default'] )) {
			//$m_bpstandardcomponent = $_POST ['bpmemonlypro_enabled_post_type_default'];
			$m_bpstandardcomponent = array_map('sanitize_text_field',$_POST['bpmemonlypro_enabled_post_type_default']);
			update_option ( 'bpmemonlypro_enabled_post_type', $m_bpstandardcomponent );
		} else {
			delete_option ( 'bpmemonlypro_enabled_post_type' );
		}
		$bpmoMessageString = __ ( 'Your changes has been saved.', 'bp-members-only' );
		buddypress_members_only_message ( $bpmoMessageString );
	}

	$m_bpstandardcomponent = get_option ( 'bpmemonlypro_enabled_post_type' );

	echo "<br />";

	$setting_panel_head = 'BuddyPress Members Only Custom Post Types Settings:';
	?>
	<div style='margin:10px 5px;'>
	<div style='float:left;margin-right:10px;'>
	<img src='<?php echo get_option('siteurl');  ?>/wp-content/plugins/buddypress-members-only/images/new.png' style='width:30px;height:30px;'>
	</div>
	<div style='padding-top:5px; font-size:22px;'> <i></>BuddyPress Members Only Restrict Custom Post Types Settings:</i></div>
	</div>
	<div style='clear:both'></div>
	<?php 
	$saved_register_page_url = get_option ( 'bpmoregisterpageurl' );

	$bp_component_default_role = array ();
	$bp_component_default_role = 'default';
	buddypress_members_free_post_type_setting_panel ( $bp_component_default_role );

}
function buddypress_members_free_post_type_setting_panel($bp_component_role) {
	global $wpdb;

	$all_original_post_types = get_post_types();
	$passed_default_post_types = array('attachment' => 'attachment','revision' => 'revision','nav_menu_item' => 'nav_menu_item','custom_css' => 'custom_css','customize_changeset' => 'customize_changeset','tooltips' => 'tooltips');
	$all_post_types = array_diff($all_original_post_types, $passed_default_post_types);
	
	$m_bpstandardcomponent = get_option ( 'bpmemonlypro_enabled_post_type' );

	if (empty ( $bp_component_role )) {
		$bp_component_role = array ();
		$bp_component_role = 'default';
	}

	$bp_component_role_id = str_replace ( ' ', '-', $bp_component_role );

	$rolebasedstandardcomponent = 'bpmemonlypro_enabled_post_type_' . $bp_component_role_id;

	$rolebasedstandardcomponentoption = get_option ( $rolebasedstandardcomponent );
	?>
<div class="wrap">
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div id="post-body">
				<div id="dashboard-widgets-main-content">
					<div class="postbox-container" style="width: 90%;">
						<div
							class="postbox bp-members-pro-componet-each-role-bar close-bar"
							data-user-role="<?php echo $bp_component_role_id ?>">
							<h3 class='hndle'
								style='padding: 10px; ! important; border-bottom: 0px solid #eee !important;'>
	<?php
	if ('default' == $bp_component_role_id) {
		$tomas_roles_single_name = 'default';
		$eachrolestandardcompentname = 'bpmemonlypro_enabled_post_type[]';
		
		$bpmemonlypro_enabled_post_type = get_option ( 'bpmemonlypro_enabled_post_type' );
		$rolebasedstandardcomponentsubmit = 'bpmemonlypro_enabled_post_type_submit';
		echo __ ( 'Restricts these Post Types to -- ' . '<strong>' . __ ( 'Non Members / Guest Users' ) . '</strong>', 'bp-members-only' );
	} 
	?>
									</h3>


							<form id="bpmoform" name="bpmoform" action="" method="POST">
								<table id="bpmotable" width="100%">
									<tr>
										<td width="30%" style="padding: 30px 20px 20px 20px;"
											valign="top">
											<?php
	
	echo  __( 'Opened Post Types:', 'bp-members-only' );
	?>
											</td>
										<td width="70%" style="padding: 20px;">
											<p>

										<?php
										echo __( 'Open these post types to guest:', 'bp-members-only' );
										?>
										<hr />
										
										<div style='margin-bottom: 20px;'>
										<?php 
										if ((isset($all_post_types)) && (is_array($all_post_types)) && (count($all_post_types) > 0))
										{
											?>
											<ul>
											<?php 
											foreach ($all_post_types as $single_post_types)
											{
												if ((isset($bpmemonlypro_enabled_post_type)) && (is_array($bpmemonlypro_enabled_post_type)) && (count($bpmemonlypro_enabled_post_type) > 0))
												{
													if (in_array($single_post_types, $bpmemonlypro_enabled_post_type))
													{
														$is_enabled_post_type_statu = 'checked = checked';
													}
													else
													{
														$is_enabled_post_type_statu = '';
													}													
												}
												else 
												{
														$is_enabled_post_type_statu = '';
												}
												?>
												
												<li style="display: inline; margin-right:20px; line-height: 36px;">
												<?php //3.2.3  ?>
												<input type="checkbox" <?php echo esc_attr($is_enabled_post_type_statu); ?>  name="bpmemonlypro_enabled_post_type_<?php echo esc_attr($tomas_roles_single_name); ?>[]"  value="<?php echo esc_attr($single_post_types); ?>"><?php echo $single_post_types; ?>
												<?php
												/*
												<input type="checkbox" <?php echo $is_enabled_post_type_statu; ?>  name="bpmemonlypro_enabled_post_type_<?php echo $tomas_roles_single_name; ?>[]"  value="<?php echo $single_post_types; ?>"><?php echo $single_post_types; ?>
												*/
												?>
												</li>
												
												<?php 
											}
											?>
											</ul>
											<?php 
										}
										
										?>
										</div>
											<p>
												<font color="Gray"><i>
											<?php echo  __( 'Checked post types will opened to ', 'bp-members-only' ); ?>
											<?php
	if ($tomas_roles_single_name == 'default') {
		$tomas_roles_single_name = 'guest';
	}
	echo $tomas_roles_single_name;
	?>
											</i>
											</p>
										</td>
									</tr>
								</table>
								<br />
											<?php
	wp_nonce_field ( 'bpcomponentsubmitnonce' );
	//3.2.3
	?>
								<input type="submit" id="bpmemonlypro_enabled_post_type_submit" name="<?php echo esc_attr($rolebasedstandardcomponentsubmit); ?>" value=" Submit " style="margin: 1px 20px;">	
	<?php
	/*
											<input type="submit" id="bpmemonlypro_enabled_post_type_submit"
									name="<?php echo $rolebasedstandardcomponentsubmit; ?>"
									value=" Submit " style="margin: 1px 20px;">
    */
    ?>
							</form>
							<br />
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="clear: both"></div>
<?php
}


