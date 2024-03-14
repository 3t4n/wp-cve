<?php
if(!defined('WPINC'))
{
	exit ('Please do not access our files directly.');
}

function show_member_user_roles()
{
	global $wp_roles;
	$tomas_roles_all_array =  $wp_roles->roles;
	$return_members_user_roles_text = '';
	$is_enabled_post_type_statu = '';
	
	if (isset($_POST['saved_allowed_user_roles_in_member_directory']))
	{
		check_admin_referer('fucwpexpertglobalsettings','_wpnonce');
		if(isset($_POST['saved_allowed_user_roles_in_member_directory']) != "") 
		{
		    $post_saved_allowed_user_roles_in_member_directory =  array_map('sanitize_text_field', $_POST ['saved_allowed_user_roles_in_member_directory']);
		    update_option('saved_allowed_user_roles_in_member_directory',$post_saved_allowed_user_roles_in_member_directory );
		} 
		else 
		{
			delete_option('saved_allowed_user_roles_in_member_directory');
		}
	}
	
	$saved_allowed_user_roles_in_member_directory = get_option('saved_allowed_user_roles_in_member_directory');
	$saved_enable_disable_select_statu_user_roles_in_member_directory = get_option('saved_enable_disable_select_statu_user_roles_in_member_directory');
	
	if (empty($saved_allowed_user_roles_in_member_directory))
	{
		$saved_allowed_user_roles_in_member_directory = array();
	}
	
	
	if ((!(empty($tomas_roles_all_array))) && (is_array($tomas_roles_all_array)) && (count($tomas_roles_all_array) > 0))
	{
	    $return_members_user_roles_text .= '<div style="margin-top:12px;">';
	
		foreach ( $tomas_roles_all_array as $tomas_roles_single_key => $tomas_roles_single_array )
		{
			$tomas_roles_single_name = $tomas_roles_single_key;
			
			if (in_array($tomas_roles_single_name, $saved_allowed_user_roles_in_member_directory))
			{
				$is_enabled_post_type_statu = 'checked = checked';
			}
			else
			{
				$is_enabled_post_type_statu = '';
			}

			$return_members_user_roles_text .= '<span style="margin:0 12px; line-height:24px;">';
			$return_members_user_roles_text .= '<input type="checkbox" '. $is_enabled_post_type_statu .'  name="saved_allowed_user_roles_in_member_directory[]"  value="'. esc_attr($tomas_roles_single_name) .'">'.$tomas_roles_single_name ;
			
			$return_members_user_roles_text .= '</span>';
		}
	
		$return_members_user_roles_text .= '</div">';
	}
	return $return_members_user_roles_text;
}

