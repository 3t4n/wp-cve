<?php
if (! defined ( 'WPINC' )) {
	exit ( 'Please do not access our files directly.' );
}
function buddypress_members_component_setting_free() {
	global $wpdb, $wp_roles;
	
	if (isset ( $_POST ['bpcomponentsubmit'] )) {
		check_admin_referer ( 'bpcomponentsubmitnonce' );
		if (isset ( $_POST ['bpstandardcomponent'] )) 
		{
		    //3.2.3
		    $m_bpstandardcomponent = array_map('sanitize_text_field', $_POST ['bpstandardcomponent']);
		    //$m_bpstandardcomponent = $_POST ['bpstandardcomponent'];
			update_option ( 'bpstandardcomponent', $m_bpstandardcomponent );
		} else {
			delete_option ( 'bpstandardcomponent' );
		}
		
	}
	
	
	$m_bpstandardcomponent = get_option ( 'bpstandardcomponent' );
	
	echo "<br />";
	
	$setting_panel_head = 'Buddypress Members Only Components Setting:';
	members_only_free_setting_panel_head ( $setting_panel_head );
	
	$saved_register_page_url = get_option ( 'bpmoregisterpageurl' );
	
	$bp_component_default_role = array ();
	$bp_component_default_role = 'default';
	buddypress_members_setting_panel_free ( $bp_component_default_role );

}
function buddypress_members_setting_panel_free($bp_component_role) {
	global $wpdb, $wp_roles;
	
	$m_bpstandardcomponent = get_option ( 'bpstandardcomponent' );
	
	if (empty ( $bp_component_role )) {
		$bp_component_role = array ();
		$bp_component_role = 'default';
	}
	
	$bp_component_role_id = str_replace ( ' ', '-', $bp_component_role );
	
	$rolebasedstandardcomponent = 'bpstandardcomponent_' . $bp_component_role_id;
	
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
		$eachrolestandardcompentname = 'bpstandardcomponent[]';
		$eachrolecustomizedcompentname = 'bpopenedcustomizedcomponent';
		$bpopenedcustomizedcomponentarray = get_option ( 'bpopenedcustomizedcomponent' );
		
		$m_bpstandardcomponent = get_option ( 'bpstandardcomponent' );
		$rolebasedstandardcomponentsubmit = 'bpcomponentsubmit';
		echo __ ( 'Restricts These BP Components to -- ' . '<strong>' . __ ( 'Non Members / Guest Users' ) . '</strong>', 'bp-members-only' );
	} 
	?>
									</h3>

						</div>
						<div class="inside bp-component-setting postbox"
							style='padding-left: 10px; border-top: 1px solid #eee;'
							id=<?php echo $bp_component_role_id ?>>
							<form id="bpmoform" name="bpmoform" action="" method="POST">
								<table id="bpmotable" width="100%">
									<tr>
										<td width="30%" style="padding: 30px 20px 20px 20px;"
											valign="top">
											<?php
	echo __ ( 'Opened BP Standard Components:', 'bp-members-only' );
	?>
											</td>
										<td width="70%" style="padding: 20px;">
											<p>
											<?php
	if ((! (empty ( $m_bpstandardcomponent ))) && (is_array ( $m_bpstandardcomponent )) && (count ( $m_bpstandardcomponent ) > 0) && (in_array ( 'activity', $m_bpstandardcomponent ))) {
		echo '<input type="checkbox" id="bpstandardcomponentactivity" name="' . $eachrolestandardcompentname . '"  style="" value="activity"  checked="checked"> Buddypress Activity Component';
	} else {
		echo '<input type="checkbox" id="bpstandardcomponentactivity" name="' . $eachrolestandardcompentname . '"  style="" value="activity" > Buddypress Activity Component';
	}
	?>
											</p>
											<p>
											<?php
	if ((! (empty ( $m_bpstandardcomponent ))) && (is_array ( $m_bpstandardcomponent )) && (count ( $m_bpstandardcomponent ) > 0) && (in_array ( 'members', $m_bpstandardcomponent ))) {
		echo '<input type="checkbox" id="bpstandardcomponentmembers" name="' . $eachrolestandardcompentname . '"  style="" value="members"   checked="checked"> Buddypress Members Component';
	} else {
		echo '<input type="checkbox" id="bpstandardcomponentmembers" name="' . $eachrolestandardcompentname . '"  style="" value="members" > Buddypress Members Component';
	}
	?>
											</p>
											<p>
											 <?php
	if ((! (empty ( $m_bpstandardcomponent ))) && (is_array ( $m_bpstandardcomponent )) && (count ( $m_bpstandardcomponent ) > 0) && (in_array ( 'profile', $m_bpstandardcomponent ))) {
		echo '<input type="checkbox" id="bpstandardcomponentprofile" name="' . $eachrolestandardcompentname . '"  style="" value="profile" checked="checked"> Buddypress Profile  Component';
	} else {
		echo '<input type="checkbox" id="bpstandardcomponentprofile" name="' . $eachrolestandardcompentname . '"  style="" value="profile" > Buddypress Profile  Component';
	}
	?>
											</p>
											<p>
											 <?php
											 //!!! 3.2.9
	if ((! (empty ( $m_bpstandardcomponent ))) && (is_array ( $m_bpstandardcomponent )) && (count ( $m_bpstandardcomponent ) > 0) && (in_array ( 'groups', $m_bpstandardcomponent ))) {
		echo '<input type="checkbox" id="bpstandardcomponentgroups" name="' . $eachrolestandardcompentname . '"  style="" value="groups"  checked="checked"> Buddypress Groups Component';
	} else {
		echo '<input type="checkbox" id="bpstandardcomponentgroups" name="' . $eachrolestandardcompentname . '"  style="" value="groups" > Buddypress Groups Component';
	}
	?>
											</p>
											<p>
												<font color="Gray"><i>
											<?php echo  __( 'Checked component  will opened to ', 'bp-members-only' ); ?>
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
	?>
											<input type="submit" id="bpcomponentsubmit"
									name="<?php echo $rolebasedstandardcomponentsubmit; ?>"
									value=" Submit " style="margin: 1px 20px;">
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
