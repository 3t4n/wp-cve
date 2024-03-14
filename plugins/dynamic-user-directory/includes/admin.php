<?php

/*** Register Menu Item *************************************************/

function DynamicUserDirectoryAdmin(){
	
add_submenu_page(
 'options-general.php',
 'Dynamic User Directory Settings',
 'Dynamic User Directory',
 'manage_options',
 'user_directory',
 'DynamicUserDirectoryAdminSettings'
 );
}
add_action('admin_menu', 'DynamicUserDirectoryAdmin'); //menu setup

/**** Display Page Content *********************************************/

function DynamicUserDirectoryAdminSettings() {

global $submenu;

// access page settings 
$page_data = array();
foreach($submenu['options-general.php'] as $i => $menu_item) {
 	if($submenu['options-general.php'][$i][2] == 'user_directory')
 		$page_data = $submenu['options-general.php'][$i];
}

/*** load scripts ***/    
wp_enqueue_style( 'wp-color-picker' ); 
//wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 
wp_register_script( 'FontAwesome', 'https://kit.fontawesome.com/2e95a9bac3.js' );
wp_enqueue_script('FontAwesome');
wp_enqueue_script( 'dud_custom_js', DYNAMIC_USER_DIRECTORY_URL . '/js/jquery.user-directory.js', array( 'jquery', 'wp-color-picker' ), '', true  );
wp_enqueue_script( 'jquery-ui-sortable');
wp_enqueue_style( 'select2_css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
wp_register_script( 'select2_js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery'), '4.0.3', true );
wp_enqueue_script('select2_js');

$dud_options = get_option( 'dud_plugin_settings' );
$dud_option_name = 'dud_plugin_settings'; 
$instance_name = "";

$custom_sort_active = false;
$meta_flds_srch_active = false;
$alpha_links_scroll_active = false;
$hide_dir_before_srch_active = false;
$hide_dir_before_srch_active = false;
$exclude_user_filter_active = false;
$export_active = false;
$custom_avatar_active = false;

if(! $dud_options ) {
		
	$dud_options = array(
		'user_directory_sort' => 'last_name',
		'ud_format_name' => 'fl',
		'ud_directory_type' => 'alpha-links',
		'ud_letter_divider' => 'nld',
		'ud_letter_divider_font_color' => '#FFFFFF',
		'ud_letter_divider_fill_color' => '#D3D3D3',
		'ud_show_srch' => '',
		'user_directory_show_avatars' => '1',
		'user_directory_avatar_style' => '',		
		'user_directory_border' => 'dividing_border',
		'user_directory_border_thickness' => '',
		'user_directory_border_color' => '#D3D3D3',
		'user_directory_border_length' => '60%',
		'user_directory_border_style' => '',
		'user_directory_letter_fs' => '15',
		'ud_alpha_link_spacer' => '12',
		'user_directory_listing_fs' => '15',
		'user_directory_listing_spacing' => '15',
		'ud_hide_roles' => null,
		'ud_roles_exclude_include_radio' => 'exclude',
		'ud_users_exclude_include' => null,
		'ud_exclude_include_radio' => 'exclude',
		'user_directory_email' => '1',
		'ud_email_format' => 'hyperlink',
		'user_directory_website' => '1',
		'user_directory_num_meta_flds' => '5',
		'user_directory_meta_field_1' => '',
		'user_directory_meta_label_1' => '',
		'user_directory_meta_field_2' => '',
		'user_directory_meta_label_2' => '',
		'user_directory_meta_field_3' => '',
		'user_directory_meta_label_3' => '',
		'user_directory_meta_field_4' => '',
		'user_directory_meta_label_4' => '',
		'user_directory_meta_field_5' => '',
		'user_directory_meta_label_5' => '',
		'user_directory_meta_field_6' => '',
		'user_directory_meta_label_6' => '',
		'user_directory_meta_field_7' => '',
		'user_directory_meta_label_7' => '',
		'user_directory_meta_field_8' => '',
		'user_directory_meta_label_8' => '',
		'user_directory_meta_field_9' => '',
		'user_directory_meta_label_9' => '',
		'user_directory_meta_label_10' => '',
		'user_directory_meta_field_10' => '',
		'user_directory_meta_link_1' => '',
		'user_directory_meta_link_2' => '',
		'user_directory_meta_link_3' => '',
		'user_directory_meta_link_4' => '',
		'user_directory_meta_link_5' => '',
		'user_directory_meta_link_6' => '',
		'user_directory_meta_link_7' => '',
		'user_directory_meta_link_8' => '',
		'user_directory_meta_link_9' => '',
		'user_directory_meta_link_10' => '',
		'user_directory_address' => '1',
		'user_directory_addr_1' => '',
		'user_directory_addr_2' => '',
		'user_directory_city' => '',
		'user_directory_state' => '',
		'user_directory_zip' => '',
		'user_directory_country' => '',
		'user_directory_num_meta_srch_flds' => '5',
		'user_directory_meta_srch_field_1' => '',
		'user_directory_meta_srch_label_1' => '',
		'user_directory_meta_srch_dd_values_1' => '',
		'user_directory_meta_srch_dd_labels_1' => '',
		'user_directory_meta_srch_type_1' => '',
		'user_directory_meta_srch_field_2' => '',
		'user_directory_meta_srch_label_2' => '',
		'user_directory_meta_srch_dd_values_2' => '',
		'user_directory_meta_srch_dd_labels_2' => '',
		'user_directory_meta_srch_type_2' => '',
		'user_directory_meta_srch_field_3' => '',
		'user_directory_meta_srch_label_3' => '',
		'user_directory_meta_srch_dd_values_3' => '',
		'user_directory_meta_srch_dd_labels_3' => '',
		'user_directory_meta_srch_type_3' => '',
		'user_directory_meta_srch_field_4' => '',
		'user_directory_meta_srch_label_4' => '',
		'user_directory_meta_srch_dd_values_4' => '',
		'user_directory_meta_srch_dd_labels_4' => '',
		'user_directory_meta_srch_type_4' => '',
		'user_directory_meta_srch_field_5' => '',
		'user_directory_meta_srch_label_5' => '',
		'user_directory_meta_srch_dd_values_5' => '',
		'user_directory_meta_srch_dd_labels_5' => '',
		'user_directory_meta_srch_type_5' => '',
		'user_directory_meta_srch_field_6' => '',
		'user_directory_meta_srch_label_6' => '',
		'user_directory_meta_srch_dd_values_6' => '',
		'user_directory_meta_srch_dd_labels_6' => '',
		'user_directory_meta_srch_type_6' => '',
		'user_directory_meta_srch_field_7' => '',
		'user_directory_meta_srch_label_7' => '',
		'user_directory_meta_srch_dd_values_7' => '',
		'user_directory_meta_srch_dd_labels_7' => '',
		'user_directory_meta_srch_type_7' => '',
		'user_directory_meta_srch_field_8' => '',
		'user_directory_meta_srch_label_8' => '',
		'user_directory_meta_srch_dd_values_8' => '',
		'user_directory_meta_srch_dd_labels_8' => '',
		'user_directory_meta_srch_type_8' => '',
		'user_directory_meta_srch_field_9' => '',
		'user_directory_meta_srch_label_9' => '',
		'user_directory_meta_srch_dd_values_9' => '',
		'user_directory_meta_srch_dd_labels_9' => '',
		'user_directory_meta_srch_type_9' => '',
		'user_directory_meta_srch_field_10' => '',
		'user_directory_meta_srch_label_10' => '',
		'user_directory_meta_srch_dd_values_10' => '',
		'user_directory_meta_srch_dd_labels_10' => '',
		'user_directory_meta_srch_type_10' => '',
		'user_directory_meta_srch_field_11' => '',
		'user_directory_meta_srch_label_11' => '',
		'user_directory_meta_srch_dd_values_11' => '',
		'user_directory_meta_srch_dd_labels_11' => '',
		'user_directory_meta_srch_type_11' => '',
		'user_directory_meta_srch_field_12' => '',
		'user_directory_meta_srch_label_12' => '',
		'user_directory_meta_srch_dd_values_12' => '',
		'user_directory_meta_srch_dd_labels_12' => '',
		'user_directory_meta_srch_type_12' => '',
		'user_directory_meta_srch_field_13' => '',
		'user_directory_meta_srch_label_13' => '',
		'user_directory_meta_srch_dd_values_13' => '',
		'user_directory_meta_srch_dd_labels_13' => '',
		'user_directory_meta_srch_type_13' => '',
		'user_directory_meta_srch_field_14' => '',
		'user_directory_meta_srch_label_14' => '',
		'user_directory_meta_srch_dd_values_14' => '',
		'user_directory_meta_srch_dd_labels_14' => '',
		'user_directory_meta_srch_type_14' => '',
		'user_directory_meta_srch_field_15' => '',
		'user_directory_meta_srch_label_15' => '',
		'user_directory_meta_srch_dd_values_15' => '',
		'user_directory_meta_srch_dd_labels_15' => '',
		'user_directory_meta_srch_type_15' => '',
		'ud_show_last_name_srch_fld' => '',
		'user_directory_sort_order' => '',
		'ud_debug_mode' => 'off',	
		'ud_author_page' => '',
		'ud_show_author_link' => '',
		'ud_auth_or_bp' => '',
		'ud_clear_search' => '',
		'ud_show_srch_results' => 'alpha-links',
		'ud_srch_icon_color' => 'dimgray',
		'dud_instance_name' => 'original',
		'ud_display_listings' => '',
		'ud_table_width' => '100%',
		'ud_table_cell_padding_top' => '8',
		'ud_table_cell_padding_bottom' => '8',
		'ud_table_cell_padding_left' => '8',
		'ud_table_cell_padding_right' => '8',
		'ud_table_stripe_color' => '#F0F0F0',
		'ud_show_table_stripes' => '',
		'ud_show_heading_labels' => '',
		'ud_heading_fs' => '',
		'user_directory_avatar_size' => '96',
		'user_directory_avatar_padding' => '120',
		'ud_col_width_name' => '', 
		'ud_col_width_email' => '', 
		'ud_col_width_website' => '',
		'ud_col_width_date' => '',
		'ud_col_width_address' => '', 
		'ud_col_width_social' => '', 
		'ud_col_width_roles' => '', 
		'ud_col_width_1' => '',
		'ud_col_width_2' => '',
		'ud_col_width_3' => '',
		'ud_col_width_4' => '',
		'ud_col_width_5' => '',
		'ud_col_width_6' => '',
		'ud_col_width_7' => '',
		'ud_col_width_8' => '',
		'ud_col_width_9' => '',
		'ud_col_width_10' => '',
		'ud_divider_border_thickness' => '',
		'ud_divider_border_color' => '',
		'ud_divider_border_length' => '',
		'ud_divider_border_style' => '',
		'ud_divider_font_size' => '',
		'ud_hide_before_srch' => '',
		'ud_facebook' => '',
		'ud_twitter' => '',
		'ud_linkedin' => '',
		'ud_google' => '',
		'ud_instagram' => '',
		'ud_pinterest' => '',
		'ud_youtube' => '',
		'ud_tiktok' => '',
		'ud_podcast' => '',
		'ud_social' => '1',
		'ud_icon_style' => '1',
		'ud_icon_size' => '22',
		'ud_icon_color' => '',
		'ud_col_label_name' => '',
		'ud_col_label_email' => '',
		'ud_col_label_website' => '',
		'ud_col_label_date' => '',
		'ud_col_label_roles' => '',
		'ud_col_label_address' => '',
		'ud_col_label_social' => '',
		'ud_col_meta_label_1' => '',
		'ud_col_meta_label_2' => '',
		'ud_col_meta_label_3' => '',
		'ud_col_meta_label_4' => '',
		'ud_col_meta_label_5' => '',
		'ud_col_meta_label_6' => '',
		'ud_col_meta_label_7' => '',
		'ud_col_meta_label_8' => '',
		'ud_col_meta_label_9' => '',
		'ud_col_meta_label_10' => '',
		'ud_break_email' => '',
		'ud_horizontal_responsive_601_767' => 'fixed',
		'ud_horizontal_responsive_768_1024' => 'fixed',
		'dud_fld_format_1' => '',
		'dud_fld_format_2' => '',
		'dud_fld_format_3' => '',
		'dud_fld_format_4' => '',
		'dud_fld_format_5' => '',
		'dud_fld_format_6' => '',
		'dud_fld_format_7' => '',
		'dud_fld_format_8' => '',
		'dud_fld_format_9' => '',
		'dud_fld_format_10' => '',
		'ud_sort_fld_key' => '',
		'ud_sort_fld_type' => '',
		'ud_users_per_page' => '',
		'ud_show_pagination_top_bottom' => '',
		'ud_pagination_font_size' => '',
		'ud_pagination_link_color' => '',
		'ud_pagination_link_clicked_color' => '',
		'ud_alpha_link_color' => '',
		'ud_alpha_link_clicked_color' => '',
		'ud_show_pagination_above_below' => '',
		'ud_pagination_padding_top' => '',
		'ud_pagination_padding_bottom' => '',
		'ud_sort_cat_header' => '',
		'ud_sort_cat_border_thickness' => '',
		'ud_sort_cat_border_color' => '',
		'ud_sort_cat_border_length' => '',
		'ud_sort_cat_border_style' => '',
		'ud_sort_cat_font_color' => '',
		'ud_sort_cat_fill_color' => '',
		'ud_sort_cat_font_size' => '',
		'ud_sort_links_per_row' => '',
		'ud_sort_cat_separator' => '',
		'ud_sort_cat_link_caps' => '',
		'ud_custom_sort' => '',
		'ud_sort_cat_header_caps' => '',
		'ud_sort_show_categories_as' => '',
		'ud_sort_dd_option_txt' => '',
		'ud_sort_cat_dd_width' => '200',
		'ud_sort_dd_label' => '',
		'ud_sort_dd_option_default_txt' => '',
		'ud_sort_show_cats_dd_hide_dir_before_srch' => 'yes',
		'ud_num_users_font_size' => '',
		'ud_num_users_top_bottom' => '',
		'ud_show_num_users' => '',
		'ud_txt_after_num_users' => '',
		'ud_txt_after_num_users_srch' => '',
		'ud_num_users_border' => '',
		'ud_num_users_border_color' => '',
		'ud_general_srch' => '',
		'ud_general_srch_placeholder_txt' => '',
		'ud_alpha_links_scroll' => '1',
		'ud_filter_fld_key' => '',
		'ud_filter_fld_type' => '',
		'ud_filter_fld_cond' => '',
		'ud_filter_fld_value' => '',
		'ud_wc_no_membership' => '',
		'ud_filter_bp_inactive' => '',
		'ud_filter_bp_spammer' => '',
		'ud_filter_bp_no_last_activity' => '',
		'ud_bp_last_activity_duration' => '',
		'ud_mp_one_time_txn' => '',
		'ud_mp_hide_statuses' => '',
		'ud_mp_hide_inactive' => '',
		'ud_exclude_user_filter_mp' => '',
		'ud_exclude_user_filter_bp' => '',
		'ud_exclude_user_filter_wc' => '',
		'ud_mp_hide_subs' => '',
		'ud_sort_cat_hide_username',
		'ud_sort_cat_sort_order',
		'ud_hide_username',
		'ud_wp_email_addr_fld' => '',
		'ud_meta_srch_legacy_style' => '',
		'ud_meta_srch_container_width' => '',
		'ud_meta_srch_input_width' => '',
		'ud_meta_srch_dropdown_width' => '',
		'ud_empty_dir_err' => '',
		'ud_invalid_val_err' => '',
		'ud_no_users_found_err' => '',
		'ud_err_msg_font_size' => '',
		'ud_filter_fld_performance' => '',
		'ud_mp_show_multiple' => '', 
		'ud_letter_spacing' => '1px',
		'dud_export_show_labels' => 'show',
		'dud_export_show_divider' => 'show', 
		'dud_export_directory' => 'off', 
		'dud_export_roles_show_full_link' => null,
		'dud_export_roles_show_srch_link' => null,
		'dud_export_directory_link_text' => '',
		'dud_export_srch_link_text' => '',
		'dud_export_initial_dir_link_text' => '',
		'dud_export_link_position' => '',
		'dud_export_performance' => '',
		'dud_export_file_prefix' => '',
		'ud_date_registered' => '', 
		'ud_date_registered_format' => '',
		'ud_date_lbl' => '', 
		'ud_roles_lbl' => '', 
		'ud_email_lbl' => '', 
		'ud_website_lbl' => '',
		'ud_custom_avatar' => '',
		'ud_show_fld_lbl_for_empty_fld' => 'no',
		'ud_website_format' => 'main',
		'ud_scroll_to_dir_top' => 'no',
		'ud_show_user_roles' => '',
		'ud_user_roles_format' => ''
	);

	// if old options exist, update to new system
	foreach( $dud_options as $key => $value ) {
		if( $existing = get_option( $key ) ) {
			$dud_options[$key] = $existing;
			delete_option( $key );
		}
	}
	
	add_option('dud_plugin_settings', $dud_options );
}

$dud_plugin_list = get_option('active_plugins');

if ( in_array( 'dynamic-user-directory-multiple-dirs/dynamic-user-directory-multiple-dirs.php' , $dud_plugin_list )) 
{
	$instance_name = "Original";
	$dud_option_name = "dud_plugin_settings";
	$dud_multi_instances_err = "";

	if(isset($_POST['dud_new_instance_name']))
	{
		$dud_total_instances = 0;
		$new_instance_name = $_POST['dud_new_instance_name'];
		
		if(!$new_instance_name)
			$dud_multi_instances_err = 'You must give the new directory instance a name!'; 
		else if(strlen($new_instance_name) > 20)
		{
			$dud_multi_instances_err = 'The directory instance name cannot be over 20 characters!'; 
		}
		else
		{
			$new_instance_name = sanitize_text_field($new_instance_name);
					
			if(strtoupper($new_instance_name) === "ORIGINAL")
			{
				$dud_multi_instances_err = 'The directory instance name "' . $new_instance_name . '" is reserved for your original settings. Please choose a different one.';
			}
			else
			{
				for($inc=0; $inc <= 99; $inc++) 
				{		  
					if( $dud_tmp_options = get_option( 'dud_plugin_settings_' . ($inc+1) ) )
					{
						if($dud_tmp_options['dud_instance_name'] && (strtoupper ($dud_tmp_options['dud_instance_name']) === strtoupper ($new_instance_name)) )  
						{
								$dud_multi_instances_err = 'The directory instance name "' . $new_instance_name . '" already exists. Please choose a different one.'; 
								break;
						} 
						
						if($dud_tmp_options['dud_instance_name'])
							$dud_total_instances++;
						else
						{
							unset($dud_options);
						    $dud_options = get_option( 'dud_plugin_settings' );
							$dud_options['dud_instance_name'] = $new_instance_name;
							update_option('dud_plugin_settings_' . ($inc+1), $dud_options );
							$dud_option_name = 'dud_plugin_settings_' . ($inc+1);
							$instance_name = $new_instance_name;
							
							break;
						}	
					}
					else
					{
						unset($dud_options);
						$dud_options = get_option( 'dud_plugin_settings' );
						$dud_options['dud_instance_name'] = $new_instance_name;
						add_option('dud_plugin_settings_' . ($inc+1), $dud_options );
						$dud_option_name = 'dud_plugin_settings_' . ($inc+1);
						$instance_name = $new_instance_name;
				
						break;
					}	
				}
				
				if($dud_total_instances >= 100)
					$dud_multi_instances_err = 'All available instances are taken! You must delete one before adding any more.'; 
			}
		}
	}
	else if(isset($_POST['load']) && isset($_POST['dud_load_dir_instance'])) 
	{
		$load_instance_name = $_POST['dud_load_dir_instance'];
		
		if(strtoupper($load_instance_name) === "ORIGINAL")
		{
			$instance_name = "Original";
			$dud_option_name = 'dud_plugin_settings';
		}
		else
		{
			$found_instance = false;
			
			for($inc=0; $inc <= 99; $inc++) 
			{		  
				if( $dud_tmp_options = get_option( 'dud_plugin_settings_' . ($inc+1) ) )
				{
					if($load_instance_name === $dud_tmp_options['dud_instance_name'])
					{
						unset($dud_options);
						$dud_options = $dud_tmp_options;
						$dud_option_name = 'dud_plugin_settings_' . ($inc+1);
						$instance_name = $load_instance_name;
						$found_instance = true;
						break;
					}	
				}	
			}
			
			if(!$found_instance)
				$dud_multi_instances_err = "Could not load instance " . $load_instance_name . " because it could not be found!"; 
		}
	}
	//else if(isset($_POST['delete']) && $_POST['delete'] === 'Delete')
	else if(isset($_POST['dud_delete_dir_instance']))
	{
		$load_instance_name = $_POST['dud_delete_dir_instance'];
		$deleted_instance = false;
		
		if(strtoupper($load_instance_name) === "ORIGINAL")
		{
			$dud_multi_instances_err = "The original settings cannot be deleted!"; 
		}
		else
		{
			
			for($inc=0; $inc <= 99; $inc++) 
			{		  
				if( $dud_tmp_options = get_option( 'dud_plugin_settings_' . ($inc+1) ) )
				{
					foreach($load_instance_name as $instance=>$name)
					{
						if($name === $dud_tmp_options['dud_instance_name'])
						{
							delete_option('dud_plugin_settings_' . ($inc+1));
							$deleted_instance = true;
							$dud_multi_instances_err = 'The selected directory instances have been deleted.'; 
							break;
						}
					}					
				}
			}
			
			if(!$deleted_instance)
				$dud_multi_instances_err = 'Could not delete instance ' . $load_instance_name . ' because it could not be found!'; 
		}
	}
	else if($updated_settings = get_option('dud_updated_settings'))
	{
		//echo "DUD UPDATED SETTINGS: " . $updated_settings . "<BR>";
		unset($dud_options);
		$dud_option_name = $updated_settings;
		$dud_options = get_option( $updated_settings );
		$instance_name = !empty($dud_options['dud_instance_name']) ? $dud_options['dud_instance_name'] : "Original";
		//if(!$instance_name) $instance_name = "Original"; 
	}
	
	delete_option('dud_updated_settings');
}

if(in_array( 'dynamic-user-directory-custom-sort-fld/dynamic-user-directory-custom-sort-fld.php' , $dud_plugin_list ))
	$custom_sort_active = true;
if ( in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $dud_plugin_list )) 
	$meta_flds_srch_active = true;
if ( in_array( 'dynamic-user-directory-alpha-links-scroll/dynamic-user-directory-alpha-links-scroll.php' , $dud_plugin_list ))
	$alpha_links_scroll_active = true;
if ( in_array( 'dynamic-user-directory-hide-dir-before-srch/dynamic-user-directory.php' , $dud_plugin_list ))
	$hide_dir_before_srch_active = true;
if (in_array( 'dynamic-user-directory-exclude-user-filter/dynamic-user-directory-exclude-user-filter.php' , $dud_plugin_list ))
	$exclude_user_filter_active = true;
if ( in_array( 'dynamic-user-directory-export/dynamic-user-directory-export.php' , $dud_plugin_list ))
	$export_active = true;
if ( in_array( 'dynamic-user-directory-custom-avatar/dynamic-user-directory-custom-avatar.php' , $dud_plugin_list ))
	$custom_avatar_active = true;

//var_dump($_POST);	

/*** display settings screen ***/ 
?>
<div class="wrap">
<h2><?php echo $page_data[3];?></h2>

<?php 

if ( in_array( 'dynamic-user-directory-multiple-dirs/dynamic-user-directory-multiple-dirs.php' , $dud_plugin_list )) 
{ do_action(dud_multiple_directories_settings($dud_multi_instances_err, $instance_name)); } 
else { ?> <BR> <?php } ?>

<form id="user_directory_options" action="options.php" method="post" onSubmit="return selectAll()">

<?php 

settings_fields('user_directory_options');
do_settings_sections('user_directory_options'); 
      
if (!wp_script_is( 'user-directory-style', 'enqueued' )) {
	wp_register_style('user-directory-style',  DYNAMIC_USER_DIRECTORY_URL . '/css/user-directory-admin-min.css', false, 0.1);	
	//wp_register_style('user-directory-style',  DYNAMIC_USER_DIRECTORY_URL . '/css/user-directory-admin.css', false, 0.1);	
	wp_enqueue_style( 'user-directory-style' );
}
		
?>  
<div class="dud-settings-section-header">&nbsp; Main Directory Settings</div>
<div class="dud-settings-section">
<table class="form-table">

  <?php if ( !in_array( 'dynamic-user-directory-multiple-dirs/dynamic-user-directory-multiple-dirs.php' , $dud_plugin_list )) 
        { ?>
			 <tr>
				<td><b>Shortcode</b></td>
				<td><input class="dd-menu-no-chk-box-width" type="text" id="plugin_shortcode" name="plugin_shortcode" value="[DynamicUserDirectory]" size="32" readonly/></td>
				<td>Copy and paste this shortcode onto the page where the directory should be displayed. The <a href="http://sgcustomwebsolutions.com/wordpress-plugin-development/" target="_blank">Multiple Directories Add-on</a> will let you create and display up to 100 directories, each with its own settings.</td>
				<td></td>
			 </tr> 
  <?php }
        else 
        {?>	
	          <tr>
				<td><b>Loaded Instance</b></td>
				<td style="font-weight:bold;color:#008888;letter-spacing:1px;font-size:15px;"><?php echo $instance_name;?></td>
				<td></td>
				<td><input type="hidden" id="dud_instance_name" name="<?php echo $dud_option_name;?>[dud_instance_name]" value="<?php echo $instance_name;?>"></td>
			 </tr> 
  <?php } ?> 

  <?php if($custom_sort_active) 
        { ?>  
			  <tr>
					<td><b>Use Custom Sort Field</b></td>
					<td><input name="<?php echo $dud_option_name;?>[ud_custom_sort]" id="ud_custom_sort" type="checkbox" 
					   value="1" <?php if(!empty($dud_options['ud_custom_sort'])) { checked( '1', $dud_options['ud_custom_sort'] ); } ?> />
					</td>
					<td>Check this box to turn on the custom sort feature. You will configure your custom sort field in the "Custom Sort Field Settings" section below.</td>
					<td></td>
			  </tr>
			  <tr >
					<td><div id="ud-sort-fld"><b>Sort Field</b></div><div id="custom-sort-sub-sort-fld"><b>Subsort Field</b></div></td>
					<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[user_directory_sort]" id="user_directory_sort">
						<OPTION value="display_name">Display Name</OPTION>             
						<OPTION value="last_name" <?php echo ($dud_options['user_directory_sort'] == "last_name") ? "SELECTED" : ""; ?>>Last Name</OPTION> 
						</select> 
					</td>
					<td><div id="ud-sort-fld-desc">This field will always be shown first on each listing. You may sort by Last Name or Display Name. 
						If Last Name is selected, it will sort by last name but still display the full name.</div>
						<div id="custom-sort-sub-sort-fld-desc">Your directory will be subsorted on last name or display name. This field will always be shown first on each listing, whether or not a custom sort field is used.</div></td>
					<td></td>
			 </tr> 
    <?php }
          else 
          { ?> 
			 <tr >
					<td><b>Sort Field</b></td>
					<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[user_directory_sort]" id="user_directory_sort">
						<OPTION value="display_name">Display Name</OPTION>             
						<OPTION value="last_name" <?php echo ($dud_options['user_directory_sort'] == "last_name") ? "SELECTED" : ""; ?>>Last Name</OPTION> 
						</select> 
					</td>
					<td>This field will always be shown first on each listing. You may sort by Last Name or Display Name. 
						If Last Name is selected, it will sort by last name but still display the full name. 
						The <a href="http://sgcustomwebsolutions.com/wordpress-plugin-development/" target="_blank">Custom Sort Field Add-on</a> 
						will allow you to sort the directory by any meta field you wish.</td>
					<td></td>
			 </tr> 
	<?php } ?> 
	 			
    <tr>
        <td><b>Directory Type</b></td>
        <td>
        	<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_directory_type]" id="ud_directory_type">
            		<OPTION value="alpha-links">Alphabet Letter Links</OPTION> 
            		<OPTION value="all-users" <?php echo ($dud_options['ud_directory_type'] == "all-users") 
            			? "SELECTED" : ""; ?>>Single Page Directory</OPTION>             
            		
            	</select> 
        </td>
        <td>"Alphabet Letter Links" shows only the users for the selected letter. "Single Page Directory" shows all users on one page. Choose "Alphabet Letter Links" if page load time is an issue.</td>
        <td></td>
     </tr>	 
	 <tr>
        <td><b>Auto-Scroll to Top of Directory on Refresh</b></td>
        <td>
        	<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_scroll_to_dir_top]" id="ud_scroll_to_dir_top">
            		<OPTION value="no">No</OPTION> 
            		<OPTION value="yes" <?php echo ($dud_options['ud_scroll_to_dir_top'] == "yes") 
            			? "SELECTED" : ""; ?>>Yes</OPTION>             
            		
            	</select> 
        </td>
        <td>Auto-scroll down to the top of the directory when the page refreshes due to navigation within the directory. Use this if your directory is located at the middle or bottom of your page.</td>
        <td></td>
     </tr>	 
     <tr>
        <td><b>Directory Search</b></td>
        <td><input name="<?php echo $dud_option_name;?>[ud_show_srch]" id="ud_show_srch" type="checkbox" 
           value="1" <?php if(!empty($dud_options['ud_show_srch'])) { checked( '1', $dud_options['ud_show_srch'] ); } ?> />&nbsp;&nbsp;
           <select class="dd-menu-chk-box-width" name="<?php echo $dud_option_name;?>[ud_srch_style]" id="ud_srch_style">
            		<OPTION value="default">Default Background</OPTION>             
            		<OPTION value="transparent" <?php echo ($dud_options['ud_srch_style'] == "transparent") 
            			? "SELECTED" : ""; ?>>Transparent Bkg</OPTION>           		
           </select>
        </td>
        <td>Show a search box at the top of your directory to search by last name or display name depending on the Sort Field. The <a href="http://sgcustomwebsolutions.com/wordpress-plugin-development/" target="_blank">Meta Fields Search Add-on</a> lets the user search by ANY user meta field(s) you specify. The <a href="http://sgcustomwebsolutions.com/wordpress-plugin-development/" target="_blank">Hide Directory Before Search Add-on</a> hides directory listings until a search has been run.</td>
        <td></td>
     </tr>
	 <?php if ($hide_dir_before_srch_active) 
     { ?>
			<tr>
			<td style="line-height:45px;"><b>Hide Dir Before Search</b></td>
			<td><input name="<?php echo $dud_option_name;?>[ud_hide_before_srch]" id="ud_hide_before_srch" type="checkbox" 
			   value="1" <?php if(!empty($dud_options['ud_hide_before_srch'])) { checked( '1', $dud_options['ud_hide_before_srch'] ); } ?> />
			</td>
			<td>Show only the search box and hide the directory listings until a search has been run.<BR></td>
			<td></td>
		 </tr>
 
     <?php } ?>
	 <tr>
	    <td colspan="2"><div id='lb_hide_roles' style="position:relative;top:19px;"><b>Exclude or Include Users With These Roles</b>
		<?php echo !empty($dud_options['ud_hide_roles']) ? dynamic_ud_roles_listbox($dud_options['ud_hide_roles'], $dud_option_name) : dynamic_ud_roles_listbox("", $dud_option_name); ?></div><br><br>
		<input type="radio" name="<?php echo $dud_option_name;?>[ud_roles_exclude_include_radio]" 
        		value="exclude" <?php if(!empty($dud_options['ud_roles_exclude_include_radio'])) { checked( 'exclude', $dud_options['ud_roles_exclude_include_radio'] ); } else { checked( 'exclude', 'exclude' );}?> /><b>Exclude</b>&nbsp;         	
        			<input type="radio" name="<?php echo $dud_option_name;?>[ud_roles_exclude_include_radio]" value="include" 
        				<?php if(!empty($dud_options['ud_roles_exclude_include_radio'])) { checked( 'include', $dud_options['ud_roles_exclude_include_radio'] ); } ?> /><b>Include</b></td>
        <td style="font-size:13.5px;font-style:italic; line-height: 21px;padding-left:3%">"Include" creates a directory in which ONLY users with the selected roles are shown. "Exclude" hides all users with the selected roles. If no users are selected this setting will not be applied.</td>
        <td></td>
     </tr>
	  <tr>  
        <td colspan="2"><div id='lb_inc_exc'><b>Exclude or Include These Users </b>
        	<br>
        	<?php echo !empty($dud_options['ud_users_exclude_include']) ? dynamic_ud_users_listbox($dud_options['ud_users_exclude_include'], $dud_option_name) : dynamic_ud_users_listbox("", $dud_option_name); ?></div><br>
        	<input type="radio" name="<?php echo $dud_option_name;?>[ud_exclude_include_radio]" 
        		value="exclude" <?php if(!empty($dud_options['ud_exclude_include_radio'])) { checked( 'exclude', $dud_options['ud_exclude_include_radio'] ); } else { checked( 'exclude', 'exclude' );}?> /><b>Exclude</b>&nbsp;         	
        			<input type="radio" name="<?php echo $dud_option_name;?>[ud_exclude_include_radio]" value="include" 
        				<?php if(!empty($dud_options['ud_exclude_include_radio'])) { checked( 'include', $dud_options['ud_exclude_include_radio'] ); } ?> /><b>Include</b></td>
        <td style="font-size:13.5px;font-style:italic; line-height: 21px;padding-left:3%">"Include" creates a directory in which ONLY the selected users are shown. "Exclude" hides the selected users. If no users are selected this setting will not be applied. Note: Selected users will be included or excluded even if their user role was selected for hiding. The <a href="http://sgcustomwebsolutions.com/wordpress-plugin-development/" target="_blank">Exclude User Filter Add-on</a> allows you to exclude users based on a meta field value or membership status.</td>
        <td></td>
     </tr>
	  <tr>
        <td><b>Debug Mode</b></td>
        <td>
        	<input type="radio" name="<?php echo $dud_option_name;?>[ud_debug_mode]" 
        		value="off" <?php if(!empty($dud_options['ud_debug_mode'])) { checked( 'off', $dud_options['ud_debug_mode'] ); } else {checked( 'off', 'off' );} ?> /><b>Off</b>&nbsp;         	
        	<input type="radio" name="<?php echo $dud_option_name;?>[ud_debug_mode]" 
        		value="on" <?php if(!empty($dud_options['ud_debug_mode'])) { checked( 'on', $dud_options['ud_debug_mode'] ); } ?> /><b>On</b></td>
        <td>When debug mode is "on," a set of debug statements will be shown for admins *ONLY* at the top of the User Directory. Leave debug mode "off" unless instructed to turn on.</td>
        <td></td>
     </tr> 	
</table>
<br/>
</div><br/><br/>

<?php if ($exclude_user_filter_active) 
{ ?>
<div class="dud-settings-section-header">&nbsp; Exclude User Filter Settings</div>
<div class="dud-settings-section">
	<table class="form-table">
		<tr>
			<td colspan="3" style="line-height:22px;"><b>Instructions</b><br><hr>In the section below, fill out the criteria that must be met for a user to be *excluded* from the directory. To see which filter field states may be used with which HTML form element, see the list next to the "Filter Field State" field below. To check the contents of the filter field for each user, turn Debug Mode to "on," then visit the directory while logged in as admin. The filter field data is listed under the heading "Exclude User Filter."<hr></td>
		</tr>
		<tr>
			<td><b>Filter Field Meta Key Name</b></td>
			<td><input class="dd-menu-no-chk-box-width" type="text" id="ud_filter_fld_key" name="<?php echo $dud_option_name;?>[ud_filter_fld_key]" 
				value="<?php echo !empty($dud_options['ud_filter_fld_key']) ? esc_attr( $dud_options['ud_filter_fld_key'] ) : ""; ?>" maxlength="50"/></td>
			<td>Enter the  name of the meta field that indicates whether a user should be shown or not. This might be an "opt out of directory" checkbox, for example. </td>
			<td></td>
		</tr> 	
			 
		<tr>
			<td><b>Filter Field Type</b></td>
			<td>
			   <select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_filter_fld_type]" id="ud_filter_fld_type">          
					<OPTION value="">Standard Meta Field</OPTION> 
					<OPTION value="bp" <?php echo (!empty($dud_options['ud_filter_fld_type']) && $dud_options['ud_filter_fld_type'] == "bp") ? "SELECTED" : ""; ?>>BuddyPress Custom Field</OPTION> 
					<OPTION value="cimy" <?php echo (!empty($dud_options['ud_filter_fld_type']) && $dud_options['ud_filter_fld_type'] == "cimy") ? "SELECTED" : ""; ?>>Cimy Custom Field</OPTION> 
					<OPTION value="s2m" <?php echo (!empty($dud_options['ud_filter_fld_type']) && $dud_options['ud_filter_fld_type'] == "s2m") ? "SELECTED" : ""; ?>>S2Member Custom Field</OPTION> 
			   </select>
			</td>
			<td>Indicate what type of meta field this is. If you are not using BuddyPress, Cimy, or S2Member, leave it on Standard Meta Field. This MUST match the field type being used for the filter to work properly.</td>
			<td></td>
		</tr>
		 
		<tr>
			<td><b>Filter Field State</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_filter_fld_cond]" id="ud_filter_fld_cond">  
				<OPTION value="empty" <?php echo (!empty($dud_options['ud_filter_fld_cond']) && $dud_options['ud_filter_fld_cond'] == "empty") ? "SELECTED" : ""; ?>>Empty</OPTION> 
						<OPTION value="not_empty" <?php echo (!empty($dud_options['ud_filter_fld_cond']) && $dud_options['ud_filter_fld_cond'] == "not_empty") ? "SELECTED" : ""; ?>>Not Empty</OPTION> 
						<OPTION value="selected" <?php echo (!empty($dud_options['ud_filter_fld_cond']) && $dud_options['ud_filter_fld_cond'] == "selected") ? "SELECTED" : ""; ?>>Selected</OPTION> 
						<OPTION value="not_selected" <?php echo (!empty($dud_options['ud_filter_fld_cond']) && $dud_options['ud_filter_fld_cond'] == "not_selected") ? "SELECTED" : ""; ?>>Not Selected</OPTION> 
						<OPTION value="equal_to" <?php echo (!empty($dud_options['ud_filter_fld_cond']) && $dud_options['ud_filter_fld_cond'] == "equal_to") ? "SELECTED" : ""; ?>>Equal To</OPTION> 
						<OPTION value="not_equal_to" <?php echo (!empty($dud_options['ud_filter_fld_cond']) && $dud_options['ud_filter_fld_cond'] == "not_equal_to") ? "SELECTED" : ""; ?>>Not Equal To</OPTION> 
						<OPTION value="contain" <?php echo (!empty($dud_options['ud_filter_fld_cond']) && $dud_options['ud_filter_fld_cond'] == "contain") ? "SELECTED" : ""; ?>>Contains</OPTION> 
						<OPTION value="not_contain" <?php echo (!empty($dud_options['ud_filter_fld_cond']) && $dud_options['ud_filter_fld_cond'] == "not_contain") ? "SELECTED" : ""; ?>>Does Not Contain</OPTION> 					
				</select> </td>
			<td>Choose the state that the filter must be in for the user to be excluded. List of states that each HTML form element may use: Text Box: Empty, Not Empty, Equal To, Not Equal To * Drop Down box: Equal To, Not Equal To * Multiselect Box: Contains, Does Not Contain * Single Radio or Checkbox: Empty, Not Empty, Selected, Not Selected, Equal To, Not Equal To * Multiple Checkboxes or Radios: Contains, Does Not Contain</td>
			<td></td>
		</tr>
		<tr>
			<td><b>Filter Field Value</b></td>
			<td><input class="dd-menu-no-chk-box-width" type="text" id="ud_filter_fld_value" name="<?php echo $dud_option_name;?>[ud_filter_fld_value]" 
				value="<?php echo !empty($dud_options['ud_filter_fld_value']) || (!is_null($dud_options['ud_filter_fld_value']) && $dud_options['ud_filter_fld_value']==="0") ? esc_attr( $dud_options['ud_filter_fld_value'] ) : ""; ?>" maxlength="100"/></td>
			<td>Enter the *case sensitive* value that the filter field must be for the user to be excluded. This setting only will only be applied if you selected Equal To, Not Equal To, Contains, or Does Not Contain in the dropdown above.</td>
			<td></td>
		</tr> 
		<tr>
			<td><b>Performance Improvement</b></td>
			<td><input name="<?php echo $dud_option_name;?>[ud_filter_fld_performance]" id="ud_filter_fld_performance" type="checkbox" 
			   value="1" <?php if(!empty($dud_options['ud_filter_fld_performance'])) { checked( '1', $dud_options['ud_filter_fld_performance'] ); } ?> />
			</td>
			<td>Check this box if you are experiencing slow page load time due to a high volume of members (1000+). You MUST have the directory set to "Alphabet Letter Links" and you must NOT be using the Custom Sort Field add-on for this to improve performance. When this is checked, ALL alphabet letter links will be active whether or not there are members in the directory for a given letter. When this is NOT checked, letters that don't have members are automatically grayed out.</td>
			<td></td>
     </tr>
		<?php
	if(function_exists('bp_is_active'))
	{ ?> 
		 <tr>
			<td><b><span style='color:#08788c;'>BuddyPress &nbsp;&nbsp;<div id="bp-down-arrow" name="bp-down-arrow"><i class="fa fa-angle-down" aria-hidden="true"></i></div><div id="bp-up-arrow" name="bp-up-arrow"><i class="fa fa-angle-up" aria-hidden="true"></i></div>
	</span></b></td>
			<td><input name="<?php echo $dud_option_name;?>[ud_exclude_user_filter_bp]" id="ud_exclude_user_filter_bp" type="hidden" value="<?php echo $dud_options['ud_exclude_user_filter_bp'];?>"/></td>
			<td>Expand this section to exclude users based on specific BuddyPress criteria.</td>
			<td></td>
		 </tr> 
	
	<tr id="bp_last_activity_1">
        <td colspan="4" style="padding-bottom:0px !important;"><b>Hide users with BP last activity date greater than or equal to:</b></td>
    </tr>				
	<tr id="bp_last_activity_2">
        <td><input type="text" id="ud_bp_last_activity_duration" name="<?php echo $dud_option_name;?>[ud_bp_last_activity_duration]" 
            value="<?php echo !empty($dud_options['ud_bp_last_activity_duration']) ? esc_attr( $dud_options['ud_bp_last_activity_duration'] ) : ""; ?>" size="5" maxlength="4"/> day(s) ago</td>
        <td></td>
        <td>Enter the number of days of inactivity that should trigger the filter to hide the user. The "last activity date" field is used by the BP plugin to track the user's last login/activity.</td>
        <td></td>
   	</tr>		
	
	<tr id="bp_inactive">
		<td><b>Show/Hide Inactive Users</b></td>
		<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_filter_bp_inactive]" id="ud_filter_bp_inactive">  
				<OPTION value="show_inactive" <?php echo (!empty($dud_options['ud_filter_bp_inactive']) && $dud_options['ud_filter_bp_inactive'] == "show_inactive") ? "SELECTED" : ""; ?>>Show Inactive Users</OPTION> 
				<OPTION value="hide_inactive" <?php echo (!empty($dud_options['ud_filter_bp_inactive']) && $dud_options['ud_filter_bp_inactive'] == "hide_inactive") ? "SELECTED" : ""; ?>>Hide Inactive Users</OPTION> 
			</select> </td>
		<td>Both active and inactive users are shown by default.</td>
		<td></td>
	</tr>		 	 
	
	 <tr id="bp_no_last_activity">
		<td><b>Hide Users With No BP Last Activity Date</b></td>
		<td><input name="<?php echo $dud_option_name;?>[ud_filter_bp_no_last_activity]" id="ud_filter_bp_no_last_activity" type="checkbox" 
				   value="1" <?php if(!empty($dud_options['ud_filter_bp_no_last_activity'])) { checked( '1', $dud_options['ud_filter_bp_no_last_activity'] ); } ?> /></td>
		<td>Hide users who have not yet logged in for the first time.</td>
		<td></td>
	 </tr>	 
	
    <tr id="bp_spammer">
		<td><b>Hide Users Marked as Spammer</b></td>
		<td><input name="<?php echo $dud_option_name;?>[ud_filter_bp_spammer]" id="ud_filter_bp_spammer" type="checkbox" 
				   value="1" <?php if(!empty($dud_options['ud_filter_bp_spammer'])) { checked( '1', $dud_options['ud_filter_bp_spammer'] ); } ?> /></td>
		<td>Hide users whose BP extended profile has been flagged as a spammer.</td>
		<td></td>
	 </tr>
    <?php } //end if(function_exists('bp_is_active'))... 
	if(class_exists('MeprUser'))
	{ ?>
    <tr>
		<td><b><span style='color:#08788c;'>MemberPress &nbsp;&nbsp;<div id="mp-down-arrow" name="mp-down-arrow"><i class="fa fa-angle-down" aria-hidden="true"></i></div><div id="mp-up-arrow" name="mp-up-arrow"><i class="fa fa-angle-up" aria-hidden="true"></i></div>
		</span></b></td>
		<td><input name="<?php echo $dud_option_name;?>[ud_exclude_user_filter_mp]" id="ud_exclude_user_filter_mp" type="hidden" value="<?php echo $dud_options['ud_exclude_user_filter_mp'];?>"/></td>
		<td>Expand this section to exclude users based on specific MemberPress criteria.</td>
		<td></td>
	 </tr> 
		
	 <tr id="mp_active_membership">
		<td><b>Hide Users With No Active Memberships</b></td>
		<td><input name="<?php echo $dud_option_name;?>[ud_mp_hide_inactive]" id="ud_mp_hide_inactive" type="checkbox" 
				   value="1" <?php if(!empty($dud_options['ud_mp_hide_inactive'])) { checked( '1', $dud_options['ud_mp_hide_inactive'] ); } ?> /></td>
		<td>This will hide users who have not purchased any subscriptions (either one time or recurring) or whose subscriptions have all expired.</td>
		<td></td>
	 </tr>	
	 
	 <tr id="mp_one_time_txn">
		<td><b>Hide Users With No Recurring Subscriptions</b></td>
		<td><input name="<?php echo $dud_option_name;?>[ud_mp_one_time_txn]" id="ud_mp_one_time_txn" type="checkbox" 
				   value="1" <?php if(!empty($dud_options['ud_mp_one_time_txn'])) { checked( '1', $dud_options['ud_mp_one_time_txn'] ); } ?> /></td>
		<td>This will hide users who have an active memberhip but do not have any recurring subscriptions (i.e. they only have one time transactions).</td>
		<td></td>
	 </tr>	

	
	<?php try { ?>
		<tr id="mp_hide_membership">
			<td><b>Hide Users with the Following Subscription(s):</b></td>
			<td>
				<select multiple="multiple" class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_mp_hide_subs][]" id="ud_mp_hide_subs"  style="width:90%;">
				<?php 
					$membership = new MeprProduct(); //A MeprProduct object
					$all_memberships = $membership->get_all();
					
					foreach($all_memberships as $membership)
					{
						$ud_memberpress_sub .= "<option value='{$membership->ID}'";
					
						if(!empty($dud_options['ud_mp_hide_subs'])){
							foreach($dud_options['ud_mp_hide_subs'] as $sub)
							{
								if($sub == $membership->ID)
								{
									$ud_memberpress_sub .= " SELECTED";
									break;
								}
							}
						}	
						
						$ud_memberpress_sub .= ">" . $membership->post_title . "</option>";
					}
					echo $ud_memberpress_sub;
				?>
				</select>		
			</td>
			<td></td>
			<td></td>
		</tr>
	<?php } catch(Exception $e) {$err = true;} //do nothing ?>
	
	<tr id="mp_multiple_memberships">
		<td><b>Show users if they have at least one subscription that is NOT selected for hiding</b></td>
		<td><input name="<?php echo $dud_option_name;?>[ud_mp_show_multiple]" id="ud_mp_show_multiple" type="checkbox" 
				   value="1" <?php if(!empty($dud_options['ud_mp_show_multiple'])) { checked( '1', $dud_options['ud_mp_show_multiple'] ); } ?> /></td>
		<td>This will allow you to show members with multiple subscriptions if they have at least one subscription that is not selected for hiding. There is no need to check this box unless you have selected at least one subscription for hiding in the listbox above.</td>
		<td></td>
	 </tr>	
	 
	<tr id="mp_hide_status">
		<td><b>Hide User if Subscription has the Following Status(es):</b></td>
		<td>
		<select multiple="multiple" class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_mp_hide_statuses][]" id="ud_mp_hide_statuses"  style="width:90%;">
				<OPTION value="pending" <?php echo (!empty($dud_options['ud_mp_hide_statuses']) && in_array("pending", $dud_options['ud_mp_hide_statuses'])) ? "SELECTED" : ""; ?>>Pending</OPTION> 
				<OPTION value="suspended" <?php echo (!empty($dud_options['ud_mp_hide_statuses']) && in_array("suspended", $dud_options['ud_mp_hide_statuses'])) ? "SELECTED" : ""; ?>>Paused</OPTION>
				<OPTION value="cancelled" <?php echo (!empty($dud_options['ud_mp_hide_statuses']) && in_array("cancelled", $dud_options['ud_mp_hide_statuses']) )? "SELECTED" : ""; ?>>Cancelled</OPTION> 
			</select> </td>
		<td></td>
		<td></td>
	</tr>
	
    <?php } //end if(class_exists('MeprUser'))... ?> 
	<!--<tr>
		<td><b><span style='color:#08788c;'>WooCommerce &nbsp;&nbsp;<div id="wc-down-arrow" name="wc-down-arrow"><i class="fa fa-angle-down" aria-hidden="true"></i></div><div id="wc-up-arrow" name="wc-up-arrow"><i class="fa fa-angle-up" aria-hidden="true"></i></div>
		</span></b></td>
		<td><input name="<?php echo $dud_option_name;?>[ud_exclude_user_filter_wc]" id="ud_exclude_user_filter_wc" type="hidden" value="<?php echo $dud_options['ud_exclude_user_filter_wc'];?>"/> </td>
		<td>Expand this section if a formatted mailing address is needed. Note: the address fields will be cleared automatically if this section is minimized.</td>
		<td></td>
	 </tr> 
		
	 <tr id="wc_active_plan">
		<td><b>Hide Users With No Active Memberships</b></td>
		<td><input name="<?php echo $dud_option_name;?>[ud_wc_no_membership]" id="ud_wc_no_membership" type="checkbox" 
				   value="1" <?php if(!empty($dud_options['ud_wc_no_membership'])) { checked( '1', $dud_options['ud_wc_no_membership'] ); } ?> /></td>
		<td>Enter your address meta keys here to display a formatted mailing address. Use the Key Names list above for reference.</td>
		<td></td>
	 </tr>	-->
	</table>	
<br/><br/>
</div>
<br/><br/>
<?php } ?>

<?php if($custom_sort_active) { ?>
	<div class="dud-settings-section-header" id="dud-custom-sort-field-settings-header">&nbsp; Custom Sort Field Settings</div>
	<div class="dud-settings-section" id="dud-custom-sort-field-settings" style="margin-bottom:40px;">
		<table class="form-table">
			<tr id="custom-sort-meta-key-name">
					<td><b>Sort Field Meta Key Name</b></td>
					<td><input class="dd-menu-no-chk-box-width" type="text" id="ud_sort_fld_key" name="<?php echo $dud_option_name;?>[ud_sort_fld_key]" 
						value="<?php echo !empty($dud_options['ud_sort_fld_key']) ? esc_attr( $dud_options['ud_sort_fld_key'] ) : ""; ?>" maxlength="50"/></td>
					<td>Enter the meta key name of the meta field to sort the directory on. You do not need to display this field in your directory, but the key name must appear in one of the the Meta Key Names listboxes under the Meta Fields Settings section above.</td>
					<td><input class="dd-menu-no-chk-box-width" type="hidden" id="meta_flds_srch_active" name="meta_flds_srch_active" value="<?php echo $meta_flds_srch_active; ?>">
						<input class="dd-menu-no-chk-box-width" type="hidden" id="alpha_links_scroll_active" name="alpha_links_scroll_active" value="<?php echo $alpha_links_scroll_active; ?>"></td>
						<input class="dd-menu-no-chk-box-width" type="hidden" id="hide_dir_before_srch_active" name="hide_dir_before_srch_active" value="<?php echo $hide_dir_before_srch_active; ?>"></td>
			</tr> 	
				 
			<tr id="custom-sort-field-type">
					<td><b>Sort Field Type</b></td>
					<td>
					   <select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_sort_fld_type]" id="ud_sort_fld_type">          
							<OPTION value="">Standard Meta Field</OPTION> 
							<OPTION value="bp" <?php echo (!empty($dud_options['ud_sort_fld_type']) && $dud_options['ud_sort_fld_type'] == "bp") ? "SELECTED" : ""; ?>>BuddyPress Custom Field</OPTION> 
							<OPTION value="cimy" <?php echo (!empty($dud_options['ud_sort_fld_type']) && $dud_options['ud_sort_fld_type'] == "cimy") ? "SELECTED" : ""; ?>>Cimy Custom Field</OPTION> 
					   </select>
					</td>
					<td>Indicate what type of meta field this is. If you are not using BuddyPress or Cimy, leave it on Standard Meta Field. This MUST match the field type being used or the custom sort will not work properly.</td>
					<td></td>
			</tr>
			
			<tr>
					<td><b>Sort Order</b></td>
					<td>
					   <select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_sort_cat_sort_order]" id="ud_sort_cat_sort_order">          
							<OPTION value="">Standard Sort Order</OPTION> 
							<OPTION value="reverse" <?php echo (!empty($dud_options['ud_sort_cat_sort_order']) && $dud_options['ud_sort_cat_sort_order'] == "reverse") ? "SELECTED" : ""; ?>>Reverse Sort Order</OPTION> 
							<OPTION value="random" <?php echo (!empty($dud_options['ud_sort_cat_sort_order']) && $dud_options['ud_sort_cat_sort_order'] == "random") ? "SELECTED" : ""; ?>>Random Sort Order</OPTION> 
					   </select>
					</td>
					<td>Choose how you would like to order your display listings. Most setups will use the Standard Sort Order. Reverse and random sort orders are specialized options to accommodate unusual setups.</td>
					<td></td>
			</tr>
			
			<!--<tr>
				<td><b>Show/Hide User Name</b></td>
				<td>
					<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_sort_cat_hide_username]" id="ud_sort_cat_hide_username">
							<OPTION value="show">Show Username</OPTION> 
							<OPTION value="hide" <?php echo (!empty($dud_options['ud_sort_cat_hide_username']) && $dud_options['ud_sort_cat_hide_username'] == "hide") ? "SELECTED" : ""; ?>>Hide Username</OPTION>  							  
					</select> 
				</td>
				<td>You may wish to hide the user name that is shown by default at the beginning of each listing. This is useful if your directory contains businesses instead of individuals.</td>
				<td></td>
			</tr>-->
			
			<tr>
				<td><b>Show Category Header</b></td>
				<td>
					<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_sort_cat_header]" id="ud_sort_cat_header">
							<OPTION value="nch">No Category Header</OPTION> 
							<OPTION value="ch-fl" <?php echo (!empty($dud_options['ud_sort_cat_header']) && $dud_options['ud_sort_cat_header'] == "ch-fl") ? "SELECTED" : ""; ?>>Category Inside Bar</OPTION>  
							<OPTION value="ch-bb" <?php echo (!empty($dud_options['ud_sort_cat_header']) && $dud_options['ud_sort_cat_header'] == "ch-bb") ? "SELECTED" : ""; ?>>Category w/Bottom Border</OPTION>  
							<OPTION value="ch-lo" <?php echo (!empty($dud_options['ud_sort_cat_header']) && $dud_options['ud_sort_cat_header'] == "ch-lo") ? "SELECTED" : ""; ?>>Category Text Only</OPTION>  								  
					</select> 
				</td>
				<td>You can show a category header at the top of the directory that indicates the category being viewed.</td>
				<td></td>
			</tr>
		 
			 <tr id="category-header-border-settings">
				<td><b>Border Thickness</b><br><select name="<?php echo $dud_option_name;?>[ud_sort_cat_border_thickness]" id="ud_sort_cat_border_thickness" style="width:65%">
						<OPTION value="1px" <?php echo !empty($dud_options['ud_sort_cat_border_thickness']) && $dud_options['ud_sort_cat_border_thickness'] == "1px" ? "SELECTED" : ""; ?>>1px</OPTION> 
						<OPTION value="2px" <?php echo !empty($dud_options['ud_sort_cat_border_thickness']) && $dud_options['ud_sort_cat_border_thickness'] == "2px" ? "SELECTED" : ""; ?>>2px</OPTION>             
						<OPTION value="3px" <?php echo !empty($dud_options['ud_sort_cat_border_thickness']) && $dud_options['ud_sort_cat_border_thickness'] == "3px" ? "SELECTED" : ""; ?>>3px</OPTION>  
						<OPTION value="4px" <?php echo !empty($dud_options['ud_sort_cat_border_thickness']) && $dud_options['ud_sort_cat_border_thickness'] == "4px" ? "SELECTED" : ""; ?>>4px</OPTION>              
						</select> </td>
				<td><b>Border Color</b><br><input type="text" name="<?php echo $dud_option_name;?>[ud_sort_cat_border_color]" 
							value="<?php echo !empty($dud_options['ud_sort_cat_border_color']) ? esc_attr( $dud_options['ud_sort_cat_border_color'] ) : ""; ?>" class="cpa-color-picker"></td>
				<td></td>
				<td></td>
			 </tr>
	
			 <tr id="category-header-border-settings-2">
				<td><b>Border Length</b><br><select name="<?php echo $dud_option_name;?>[ud_sort_cat_border_length]" id="ud_sort_cat_border_length" style="width:65%">
							<OPTION value="100%" <?php echo !empty($dud_options['ud_sort_cat_border_length']) && $dud_options['ud_sort_cat_border_length'] == "100%" ? "SELECTED" : ""; ?>>100%</OPTION> 
							<OPTION value="90%" <?php echo !empty($dud_options['ud_sort_cat_border_length']) && $dud_options['ud_sort_cat_border_length'] == "90%" ? "SELECTED" : "";   ?>>90%</OPTION> 
							<OPTION value="80%" <?php echo !empty($dud_options['ud_sort_cat_border_length']) && $dud_options['ud_sort_cat_border_length'] == "80%" ? "SELECTED" : "";   ?>>80%</OPTION> 
							<OPTION value="70%" <?php echo !empty($dud_options['ud_sort_cat_border_length']) && $dud_options['ud_sort_cat_border_length'] == "70%" ? "SELECTED" : "";   ?>>70%</OPTION> 
							<OPTION value="60%" <?php echo !empty($dud_options['ud_sort_cat_border_length']) && $dud_options['ud_sort_cat_border_length'] == "60%" ? "SELECTED" : "";   ?>>60%</OPTION> 
							<OPTION value="50%" <?php echo !empty($dud_options['ud_sort_cat_border_length']) && $dud_options['ud_sort_cat_border_length'] == "50%" ? "SELECTED" : "";   ?>>50%</OPTION> 
					 </select></td>
				<td><b>Border Style</b><br><select name="<?php echo $dud_option_name;?>[ud_sort_cat_border_style]" id="ud_sort_cat_border_style" style="width:42%">
							<OPTION value="solid" <?php echo !empty($dud_options['ud_sort_cat_border_style']) && $dud_options['ud_sort_cat_border_style'] == "solid"   ? "SELECTED" : ""; ?>>solid</OPTION> 
							<OPTION value="dotted" <?php echo !empty($dud_options['ud_sort_cat_border_style']) && $dud_options['ud_sort_cat_border_style'] == "dotted" ? "SELECTED" : ""; ?>>dotted</OPTION> 
							<OPTION value="dashed" <?php echo !empty($dud_options['ud_sort_cat_border_style']) && $dud_options['ud_sort_cat_border_style'] == "dashed" ? "SELECTED" : ""; ?>>dashed</OPTION> 
							<OPTION value="double" <?php echo !empty($dud_options['ud_sort_cat_border_style']) && $dud_options['ud_sort_cat_border_style'] == "double" ? "SELECTED" : ""; ?>>double</OPTION> 
							<OPTION value="groove" <?php echo !empty($dud_options['ud_sort_cat_border_style']) && $dud_options['ud_sort_cat_border_style'] == "groove" ? "SELECTED" : ""; ?>>groove</OPTION> 
							<OPTION value="ridge" <?php echo !empty($dud_options['ud_sort_cat_border_style']) && $dud_options['ud_sort_cat_border_style'] == "ridge"   ? "SELECTED" : ""; ?>>ridge</OPTION> 
					 </select> </td>
				<td></td>
				<td></td>
			 </tr>				
								
			 <tr id="category-header-border-settings-3">
				<td>         
				   <b>Font Color</b>
				   <input type="text" name="<?php echo $dud_option_name;?>[ud_sort_cat_font_color]" 
						value="<?php echo esc_attr( $dud_options['ud_sort_cat_font_color'] ); ?>" class="cpa-color-picker">
				</td>
				<td>
					<div id="sort-cat-fill-color"><b>Bar Fill Color</b><br>
						<input type="text" name="<?php echo $dud_option_name;?>[ud_sort_cat_fill_color]" 
							value="<?php echo esc_attr( $dud_options['ud_sort_cat_fill_color'] ); ?>" class="cpa-color-picker"></div>
					<div id="sort-cat-font-size">
						<b>Font Size</b><br><input type="text" size="9" maxlength="3" id="ud_sort_cat_font_size" name="<?php echo $dud_option_name;?>[ud_sort_cat_font_size]" 
						value="<?php echo (!empty($dud_options['ud_sort_cat_font_size'] )) ? esc_attr( $dud_options['ud_sort_cat_font_size'] ) : ""; ?>" /> px</div>
				</td>
				<td></td>
				<td></td>
			</tr>
			
			<tr id="category-header-border-settings-4">
				<td><b>Header Capitalization</b></td>
				<td>
				   <select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_sort_cat_header_caps]" id="ud_sort_cat_header_caps">          
							<OPTION value="all">All Caps</OPTION> 
							<OPTION value="first-letter" <?php echo (!empty($dud_options['ud_sort_cat_header_caps']) && $dud_options['ud_sort_cat_header_caps'] == "first-letter") ? "SELECTED" : ""; ?>>First Letter Only</OPTION> 
				   </select>
				</td>
				<td>Choose the capitalization style for the category header.</td>
				<td></td>
			</tr>
			
			<tr id="show-cats-as">
				<td><b>Show Categories As</b></td>
				<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_sort_show_categories_as]" id="ud_sort_show_categories_as">          	
						<OPTION value="links" <?php echo (!empty($dud_options['ud_sort_show_categories_as']) && $dud_options['ud_sort_show_categories_as'] == "links") ? "SELECTED" : ""; ?>>Links</OPTION> 
						<OPTION value="dd" <?php echo (!empty($dud_options['ud_sort_show_categories_as']) && $dud_options['ud_sort_show_categories_as'] == "dd") ? "SELECTED" : ""; ?>>Dropdown (Auto-Refresh)</OPTION> 
						<OPTION value="dd-srch" <?php echo (!empty($dud_options['ud_sort_show_categories_as']) && $dud_options['ud_sort_show_categories_as'] == "dd-srch") ? "SELECTED" : ""; ?>>Dropdown (Search Field)</OPTION> 
						<OPTION value="dd-links" <?php echo (!empty($dud_options['ud_sort_show_categories_as']) && $dud_options['ud_sort_show_categories_as'] == "dd-links") ? "SELECTED" : ""; ?>>Links + Dropdown Search Field</OPTION> 
						<OPTION value="no-show" <?php echo (!empty($dud_options['ud_sort_show_categories_as']) && $dud_options['ud_sort_show_categories_as'] == "no-show") ? "SELECTED" : ""; ?>>Don't Show Categories</OPTION> 
					 </select></td>
				<td>Display your categories as links, a dropdown search field, or a dropdown that auto-refreshes when a new category is selected. The available options in this field depend upon the directory type selected and the DUD add-ons installed. 
				</td>
				<td></td>
			</tr> 	
			<?php //if($hide_dir_before_srch_active) { ?>
				<tr id="show-cats-dd-hide-dir-before-srch">
					<td><b>Show Category Drop Down When Hiding Directory?</b></td>
					<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_sort_show_cats_dd_hide_dir_before_srch]" id="ud_sort_show_cats_dd_hide_dir_before_srch">          	
							<OPTION value="yes" <?php echo (!empty($dud_options['ud_sort_show_cats_dd_hide_dir_before_srch']) && $dud_options['ud_sort_show_cats_dd_hide_dir_before_srch'] == "yes") ? "SELECTED" : ""; ?>>Yes</OPTION> 
							<OPTION value="no" <?php echo (!empty($dud_options['ud_sort_show_cats_dd_hide_dir_before_srch']) && $dud_options['ud_sort_show_cats_dd_hide_dir_before_srch'] == "no") ? "SELECTED" : ""; ?>>No</OPTION> 
						 </select></td>
					<td>Choose whether or not to show the categories drop down when hiding the directory before a search. This only applies if the Hide Dir Before Search add-on is installed and active. Note: The drop down will still be shown once a search has been run.</td>
					<td></td>
				</tr> 	
			<? //} ?>
			<tr id="category-dd-1">
				<td><b>'Search All Categories' Dropdown Search Option</b></td>
				<td><input class="dd-menu-no-chk-box-width" type="text" id="ud_sort_dd_option_txt" name="<?php echo $dud_option_name;?>[ud_sort_dd_option_txt]" 
					value="<?php echo !empty($dud_options['ud_sort_dd_option_txt']) ? esc_attr( $dud_options['ud_sort_dd_option_txt'] ) : "Search All Categories"; ?>" maxlength="50"/></td>
				<td>This required drop down option will be always be shown when you select to show categories as a search dropdown. If you are sorting by department, for example, you might customize the text to say 'Search All Departments'.</td>
				<td></td>
			</tr> 	
			
			<tr id="category-dd-2">
				<td><b>'Select a Category' Default Dropdown Option Text</b></td>
				<td><input class="dd-menu-no-chk-box-width" type="text" id="ud_sort_dd_option_default_txt" name="<?php echo $dud_option_name;?>[ud_sort_dd_option_default_txt]" 
					value="<?php echo !empty($dud_options['ud_sort_dd_option_default_txt']) ? esc_attr( $dud_options['ud_sort_dd_option_default_txt'] ) : ""; ?>" maxlength="50"/></td>
				<td>Fill out this field to show 'Select a Category' as the default Categories dropdown option. E.g. if sorting by department you might enter 'Select a Department.' Leave blank if you don't want this option shown.</td>
				<td></td>
			</tr>	
			
			<tr id="category-dd-3">
				<td><b>Category Dropdown Label</b></td>
				<td><input class="dd-menu-no-chk-box-width" type="text" id="ud_sort_dd_label" name="<?php echo $dud_option_name;?>[ud_sort_dd_label]" 
					value="<?php echo !empty($dud_options['ud_sort_dd_label']) ? esc_attr( $dud_options['ud_sort_dd_label'] ) : ""; ?>" maxlength="50"/></td>
				<td>Optionally show a label in bold above the Categories dropdown.</td>
				<td></td>
			</tr> 	
			
			<tr id="category-links-1">
				<td><b>Category Links Per Row</b></td>
				<td><input class="dd-menu-no-chk-box-width" type="text" id="ud_sort_links_per_row" name="<?php echo $dud_option_name;?>[ud_sort_links_per_row]" 
					value="<?php echo !empty($dud_options['ud_sort_links_per_row']) ? esc_attr( $dud_options['ud_sort_links_per_row'] ) : ""; ?>" maxlength="50"/></td>
				<td>Configure how many category links to show on one line at the top of the directory. E.g. if you are sorting by department and you have 10 departments, you may wish to show 5 department name links on one line and 5 on the next. Leave blank to show all on one line. 
					Note: Category link font size may be adjusted in the Alphabet and Pagination Link Settings section.</td>
				<td></td>
			</tr> 	
			
			<tr id="category-links-2">
				<td><b>Link Separator</b></td>
				<td>
				   <select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_sort_cat_separator]" id="ud_sort_cat_separator">          
							<OPTION value="">None</OPTION> 
							<OPTION value="dot" <?php echo (!empty($dud_options['ud_sort_cat_separator']) && $dud_options['ud_sort_cat_separator'] == "dot") ? "SELECTED" : ""; ?>>Dot</OPTION> 
							<OPTION value="pipe" <?php echo (!empty($dud_options['ud_sort_cat_separator']) && $dud_options['ud_sort_cat_separator'] == "pipe") ? "SELECTED" : ""; ?>>Pipe</OPTION> 
				   </select>
				</td>
				<td>Show a separator icon between each category link, such as a dot or pipe.</td>
				<td></td>
			</tr>
			
			<tr id="category-links-3">
				<td><b>Link Capitalization</b></td>
				<td>
				   <select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_sort_cat_link_caps]" id="ud_sort_cat_link_caps">          
							<OPTION value="all">All Caps</OPTION> 
							<OPTION value="first-letter" <?php echo (!empty($dud_options['ud_sort_cat_link_caps']) && $dud_options['ud_sort_cat_link_caps'] == "first-letter") ? "SELECTED" : ""; ?>>First Letter Only</OPTION> 
				   </select>
				</td>
				<td>Choose the capitalization style for the category links shown at the top of the page.</td>
				<td></td>
			</tr>
			
		</table>	
	<br/><br/>
	</div>
<?php } ?>

<div class="dud-settings-section-header">&nbsp; Listing Display Settings</div>
<div class="dud-settings-section">
	<table class="form-table">
	
	<?php if ( in_array( 'dynamic-user-directory-horizontal-layout/dud_horizontal_layout.php' , $dud_plugin_list ) ) 
    { ?>
          <tr>
			<td><b>Display Listings</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_display_listings]" id="ud_display_listings">
						<OPTION value="vertically">Vertically</OPTION>             
						<OPTION value="horizontally" <?php echo (!empty($dud_options['ud_display_listings']) && $dud_options['ud_display_listings'] == "horizontally") ? "SELECTED" : ""; ?>>Horizontally</OPTION> 
			   </select>
			</td>
			<td>Choose between a horizontal and vertical directory layout.</td>
			<td></td>
		  </tr>  

    <?php 
	} ?>
	
	<tr>
		<td><b>Show User's Full Name/Display Name</b></td>
		<td>
			<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_hide_username]" id="ud_hide_username">
					<OPTION value="show">Yes</OPTION> 
					<OPTION value="hide" <?php echo (!empty($dud_options['ud_hide_username']) && $dud_options['ud_hide_username'] == "hide") ? "SELECTED" : ""; ?>>No</OPTION>  							  
			</select> 
		</td>
		<td>Optionally hide the user's full name or display name (depending on which sort field you're using) that is shown by default at the beginning of each listing. IMPORTANT NOTE: All users must still have a Last Name or Display Name filled out in their WordPress profile.</td>
		<td></td>
	</tr>
	<tr>
		<td><b>Show Field Label When Field Is Empty</b></td>
		<td>
			<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_show_fld_lbl_for_empty_fld]" id="ud_show_fld_lbl_for_empty_fld">
					<OPTION value="no">No</OPTION> 
					<OPTION value="yes" <?php echo (!empty($dud_options['ud_show_fld_lbl_for_empty_fld']) && $dud_options['ud_show_fld_lbl_for_empty_fld'] == "yes") ? "SELECTED" : ""; ?>>Yes</OPTION>  							  
			</select> 
		</td>
		<td>Choose whether to show or hide a meta field's label when that meta field is empty. It will hide the label by default. This setting will not apply when using the horizontal layout add-on.</td>
		<td></td>
	</tr>	
	  <tr>
		<td><b>Show Avatars</b></td>
		<td><input name="<?php echo $dud_option_name;?>[user_directory_show_avatars]" id="user_directory_show_avatars" type="checkbox" 
		   value="1" <?php if(!empty($dud_options['user_directory_show_avatars'])) { checked( '1', $dud_options['user_directory_show_avatars'] ); } ?> />&nbsp;&nbsp;
		   <select class="dd-menu-chk-box-width" name="<?php echo $dud_option_name;?>[user_directory_avatar_style]" id="user_directory_avatar_style">
					<OPTION value="standard">Standard Style</OPTION>             
					<OPTION value="rounded-edges" <?php echo ($dud_options['user_directory_avatar_style'] == "rounded-edges") 
						? "SELECTED" : ""; ?>>Rounded edges</OPTION> 
					<OPTION value="circle" <?php echo ($dud_options['user_directory_avatar_style'] == "circle") ? "SELECTED" : ""; ?>>Circle</OPTION> 
		   </select>
		</td>
		<td>Show avatars in your directory. Note: Some themes enforce a certain avatar shape. In those cases, DUD will *not* alter the site-wide avatar shape settings.</td>
		<td></td>
	 </tr>
	 <?php if ($custom_avatar_active) 
	 { ?>
	  <tr id="custom_avatar">
		<td><b>Avatar Meta Key</b></td>
		<td><input class="dd-menu-chk-box-width" type="text" maxlength="100" id="ud_custom_avatar" name="<?php echo $dud_option_name;?>[ud_custom_avatar]" 
				value="<?php echo (!empty($dud_options['ud_custom_avatar'] )) ? esc_attr( $dud_options['ud_custom_avatar'] ) : ""; ?>" /> </td>
		<td>To show a custom avatar, enter the meta key name that stores the URL of the user's profile image. If the user does NOT have a custom avatar but DOES have a WordPress avatar, the WordPress avatar will be shown instead.</td>
		<td></td>
	 </tr>
     <?php 
	 } ?>
	 
	 <tr id="ud_avatar_size_and_padding">
		<td><b>Avatar Size</b></td>
		<td><input class="dd-menu-chk-box-width" type="text" size="3" maxlength="3" id="user_directory_avatar_size" name="<?php echo $dud_option_name;?>[user_directory_avatar_size]" 
				value="<?php echo (!empty($dud_options['user_directory_avatar_size'] )) ? esc_attr( $dud_options['user_directory_avatar_size'] ) : "96"; ?>" /> px</td>
		<td>96px is the WordPress and DUD default avatar size.</td>
		<td></td>
	 </tr>
	 
	 <tr id="avatar_padding">
		<td><b>Avatar Padding</b></td>
		<td><input class="dd-menu-chk-box-width" type="text" size="3" maxlength="3" id="user_directory_avatar_padding" name="<?php echo $dud_option_name;?>[user_directory_avatar_padding]" 
				value="<?php echo (!empty($dud_options['user_directory_avatar_padding'] )) ? esc_attr( $dud_options['user_directory_avatar_padding'] ) : "120"; ?>" /> px</td>
		<td>Avatar Padding: The amount of space (in pixels) between the avatar on the left and the rest of the user information in your listing on the right. The default for DUD is 120px.</td>
		<td></td>
	 </tr>
		 	
	<?php if(function_exists('bp_is_active')) { ?>
			 <tr>
				<td><b>Link to Author Page<br>or BP Profile</b></td>
				<td><input name="<?php echo $dud_option_name;?>[ud_author_page]" id="ud_author_page" type="checkbox" 
				   value="1" <?php if(!empty($dud_options['ud_author_page'])) { checked( '1', $dud_options['ud_author_page'] ); } ?> />&nbsp;&nbsp;
				   <select class="dd-menu-chk-box-width" name="<?php echo $dud_option_name;?>[ud_auth_or_bp]" id="ud_auth_or_bp">
							<OPTION value="auth">WP Author Page</OPTION>             
							<OPTION value="bp" <?php echo (!empty($dud_options['ud_auth_or_bp']) && $dud_options['ud_auth_or_bp'] == "bp") 
								? "SELECTED" : ""; ?>>BP Member Activity Page</OPTION> 
							<OPTION value="bpp" <?php echo (!empty($dud_options['ud_auth_or_bp']) && $dud_options['ud_auth_or_bp'] == "bpp") 
								? "SELECTED" : ""; ?>>BP Member Profile Page</OPTION> 
				   </select>
				</td>
				<td>Hyperlink the user name & avatar to the user&lsquo;s WP author page or BuddyPress profile pages.</td>
				<td></td>
			 </tr>
			 <tr id="open_linked_page">
				<td><b>Open Linked Page</b></td>
				<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_target_window]" id="ud_target_window">
							<OPTION value="separate">In new tab</OPTION>             
							<OPTION value="main" <?php echo ($dud_options['ud_target_window'] == "main") 
								? "SELECTED" : ""; ?>>In same window</OPTION> 
							
				   </select>
				</td>
				<td></td>
				<td></td>
			 </tr>
    <?php } else { ?> 
				 <tr>
					<td><b>Link to Author Page</b></td>
					<td><input name="<?php echo $dud_option_name;?>[ud_author_page]" id="ud_author_page" type="checkbox" 
					   value="1" <?php if(!empty($dud_options['ud_author_page'])) { checked( '1', $dud_options['ud_author_page'] ); } ?> />&nbsp;&nbsp;
					   <select class="dd-menu-chk-box-width" name="<?php echo $dud_option_name;?>[ud_target_window]" id="ud_target_window">
								<OPTION value="separate">Open in new window</OPTION>             
								<OPTION value="main" <?php echo ($dud_options['ud_target_window'] == "main") 
									? "SELECTED" : ""; ?>>Open in main window</OPTION> 
								
					   </select>
					</td>
					<td>Hyperlink the user name and avatar to the user&lsquo;s WordPress author page.</td>
					<td></td>
				 </tr>
    <?php } ?>
	
		  <tr id="show-auth-pg-lnk">
			<td><b>Show Author Page Link</b></td>
			<td>
			   <select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_show_author_link]" id="ud_show_author_link">          
						<OPTION value="posts-exist">If Posts Exist</OPTION> 
						<OPTION value="always" <?php echo ($dud_options['ud_show_author_link'] == "always") 
							? "SELECTED" : ""; ?>>Always</OPTION> 
			   </select>
			</td>
			<td>Select "Always" ONLY if you have a custom author.php page that is shown whether or not the author has posts. Otherwise you'll get a Page Not Found error for authors with no posts.</td>
			<td></td>
		 </tr>
		 
		 <tr>
			<td><b>User Name Display Format</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_format_name]" id="ud_format_name">
						<OPTION value="fl">First Last</OPTION> 
						<OPTION value="lf" <?php echo ($dud_options['ud_format_name'] == "lf") 
							? "SELECTED" : ""; ?>>Last, First</OPTION>            		                 		
					</select> 
			</td>
			<td> <i>First Last</i> shows the user name like "Sally Smith." <i>Last, First</i> shows it like "Smith, Sally."</td>
			<td></td>
		 </tr>
		 
		 <tr id="one-page-dir-type-a">
			<td><b>Letter Divider</b></td>
			<td>
				<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_letter_divider]" id="ud_letter_divider">
						<OPTION value="nld">No letter divider</OPTION> 
						<OPTION value="ld-fl" <?php echo ($dud_options['ud_letter_divider'] == "ld-fl") ? "SELECTED" : ""; ?>>Letter Inside Bar</OPTION>  
						<OPTION value="ld-bb" <?php echo ($dud_options['ud_letter_divider'] == "ld-bb") ? "SELECTED" : ""; ?>>Letter w/Bottom Border</OPTION>  
						<OPTION value="ld-tb" <?php echo ($dud_options['ud_letter_divider'] == "ld-tb") ? "SELECTED" : ""; ?>>Letter w/Top & Bottom Border</OPTION> 
						<OPTION value="ld-lo" <?php echo ($dud_options['ud_letter_divider'] == "ld-lo") ? "SELECTED" : ""; ?>>Letter Only</OPTION>  							
						  
				</select> 
			</td>
			<td>You can show a divider for each alphabet letter in a Single Page Directory. The <a href="http://sgcustomwebsolutions.com/wordpress-plugin-development/" target="_blank">Alpha Links Scroll Add-on</a> displays clickable letter links at the top that will smoothly scroll to the matching letter divider.</td>
			<td></td>
		 </tr>
		 
		 <tr id="letter-divider-border-settings">
			<td><b>Border Thickness</b><br><select name="<?php echo $dud_option_name;?>[ud_divider_border_thickness]" id="ud_divider_border_thickness" style="width:65%">
					<OPTION value="1px" <?php echo !empty($dud_options['ud_divider_border_thickness']) && $dud_options['ud_divider_border_thickness'] == "1px" ? "SELECTED" : ""; ?>>1px</OPTION> 
					<OPTION value="2px" <?php echo !empty($dud_options['ud_divider_border_thickness']) && $dud_options['ud_divider_border_thickness'] == "2px" ? "SELECTED" : ""; ?>>2px</OPTION>             
					<OPTION value="3px" <?php echo !empty($dud_options['ud_divider_border_thickness']) && $dud_options['ud_divider_border_thickness'] == "3px" ? "SELECTED" : ""; ?>>3px</OPTION>  
					<OPTION value="4px" <?php echo !empty($dud_options['ud_divider_border_thickness']) && $dud_options['ud_divider_border_thickness'] == "4px" ? "SELECTED" : ""; ?>>4px</OPTION>              
					</select> </td>
			<td><b>Border Color</b><br><input type="text" name="<?php echo $dud_option_name;?>[ud_divider_border_color]" 
						value="<?php echo !empty($dud_options['ud_divider_border_color']) ? esc_attr( $dud_options['ud_divider_border_color'] ) : ""; ?>" class="cpa-color-picker"></td>
			<td></td>
			<td></td>
		 </tr>	
		  <tr id="letter-divider-border-settings-2">
			<td><b>Border Length</b><br><select name="<?php echo $dud_option_name;?>[ud_divider_border_length]" id="ud_divider_border_length" style="width:65%">
						<OPTION value="100%" <?php echo !empty($dud_options['ud_divider_border_length']) && $dud_options['ud_divider_border_length'] == "100%" ? "SELECTED" : ""; ?>>100%</OPTION> 
						<OPTION value="90%" <?php echo !empty($dud_options['ud_divider_border_length']) && $dud_options['ud_divider_border_length'] == "90%" ? "SELECTED" : "";   ?>>90%</OPTION> 
						<OPTION value="80%" <?php echo !empty($dud_options['ud_divider_border_length']) && $dud_options['ud_divider_border_length'] == "80%" ? "SELECTED" : "";   ?>>80%</OPTION> 
						<OPTION value="70%" <?php echo !empty($dud_options['ud_divider_border_length']) && $dud_options['ud_divider_border_length'] == "70%" ? "SELECTED" : "";   ?>>70%</OPTION> 
						<OPTION value="60%" <?php echo !empty($dud_options['ud_divider_border_length']) && $dud_options['ud_divider_border_length'] == "60%" ? "SELECTED" : "";   ?>>60%</OPTION> 
						<OPTION value="50%" <?php echo !empty($dud_options['ud_divider_border_length']) && $dud_options['ud_divider_border_length'] == "50%" ? "SELECTED" : "";   ?>>50%</OPTION> 
				 </select></td>
			<td><b>Border Style</b><br><select name="<?php echo $dud_option_name;?>[ud_divider_border_style]" id="ud_divider_border_style" style="width:42%">
						<OPTION value="solid" <?php echo !empty($dud_options['ud_divider_border_style']) && $dud_options['ud_divider_border_style'] == "solid"   ? "SELECTED" : ""; ?>>solid</OPTION> 
						<OPTION value="dotted" <?php echo !empty($dud_options['ud_divider_border_style']) && $dud_options['ud_divider_border_style'] == "dotted" ? "SELECTED" : ""; ?>>dotted</OPTION> 
						<OPTION value="dashed" <?php echo !empty($dud_options['ud_divider_border_style']) && $dud_options['ud_divider_border_style'] == "dashed" ? "SELECTED" : ""; ?>>dashed</OPTION> 
						<OPTION value="double" <?php echo !empty($dud_options['ud_divider_border_style']) && $dud_options['ud_divider_border_style'] == "double" ? "SELECTED" : ""; ?>>double</OPTION> 
						<OPTION value="groove" <?php echo !empty($dud_options['ud_divider_border_style']) && $dud_options['ud_divider_border_style'] == "groove" ? "SELECTED" : ""; ?>>groove</OPTION> 
						<OPTION value="ridge" <?php echo !empty($dud_options['ud_divider_border_style']) && $dud_options['ud_divider_border_style'] == "ridge"   ? "SELECTED" : ""; ?>>ridge</OPTION> 
				 </select> </td>
			<td></td>
			<td></td>
		 </tr>				
							
     	 <tr id="one-page-dir-type-b">
				<td>         
				   <b>Letter Font Color</b>
				   <input type="text" name="<?php echo $dud_option_name;?>[ud_letter_divider_font_color]" 
						value="<?php echo esc_attr( $dud_options['ud_letter_divider_font_color'] ); ?>" class="cpa-color-picker">
				</td>
				<td>
					<div id="divider-fill-color"><b>Bar Fill Color</b>
						<input type="text" name="<?php echo $dud_option_name;?>[ud_letter_divider_fill_color]" 
							value="<?php echo esc_attr( $dud_options['ud_letter_divider_fill_color'] ); ?>" class="cpa-color-picker"></div>
					<div id="divider-font-size">
						<b>Letter Font Size</b><br><input type="text" size="9" maxlength="3" id="ud_divider_font_size" name="<?php echo $dud_option_name;?>[ud_divider_font_size]" 
						value="<?php echo (!empty($dud_options['ud_divider_font_size'] )) ? esc_attr( $dud_options['ud_divider_font_size'] ) : ""; ?>" /> px</div>
				</td>
				<td></td>
				<td></td>
			 </tr>

			 <tr>
				<td><b>Listing Border</b>
				<td>
					<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[user_directory_border]" id="user_directory_border">
							<OPTION value="dividing_border">Dividing border</OPTION> 
							<OPTION value="surrounding_border" <?php echo ($dud_options['user_directory_border'] == "surrounding_border") 
								? "SELECTED" : ""; ?>>Surrounding border</OPTION>             
							<OPTION value="no_border" <?php echo ($dud_options['user_directory_border'] == "no_border") 
								? "SELECTED" : ""; ?>>No border</OPTION> 
						</select> 
				</td>
				<td>Show a border around or between each listing.</td>
				<td></td>
			 </tr>
			<tr id="border-settings">
				<td><b>Border Thickness</b><br><select name="<?php echo $dud_option_name;?>[user_directory_border_thickness]" id="user_directory_border_thickness" style="width:65%">
						<OPTION value="1px" <?php echo (!empty($dud_options['user_directory_border_thickness']) && $dud_options['user_directory_border_thickness'] == "1px") ? "SELECTED" : ""; ?>>1px</OPTION> 
						<OPTION value="2px" <?php echo (!empty($dud_options['user_directory_border_thickness']) && $dud_options['user_directory_border_thickness'] == "2px") ? "SELECTED" : ""; ?>>2px</OPTION>             
						<OPTION value="3px" <?php echo (!empty($dud_options['user_directory_border_thickness']) && $dud_options['user_directory_border_thickness'] == "3px") ? "SELECTED" : ""; ?>>3px</OPTION>  
						<OPTION value="4px" <?php echo (!empty($dud_options['user_directory_border_thickness']) && $dud_options['user_directory_border_thickness'] == "4px") ? "SELECTED" : ""; ?>>4px</OPTION>              
						</select> </td>
				<td><b>Border Color</b><br><input type="text" name="<?php echo $dud_option_name;?>[user_directory_border_color]" 
							value="<?php echo esc_attr( $dud_options['user_directory_border_color'] ); ?>" class="cpa-color-picker"></td>
				<td></td>
				<td></td>
			 </tr>	
			 <tr id="border-settings-2">
				<td><b>Border Length</b><br><select name="<?php echo $dud_option_name;?>[user_directory_border_length]" id="user_directory_border_length" style="width:65%">
						<OPTION value="100%" <?php echo (!empty($dud_options['user_directory_border_length']) && $dud_options['user_directory_border_length'] == "100%") ? "SELECTED" : ""; ?>>100%</OPTION> 
						<OPTION value="90%" <?php echo (!empty($dud_options['user_directory_border_length']) && $dud_options['user_directory_border_length'] == "90%") ? "SELECTED" : ""; ?>>90%</OPTION> 
						<OPTION value="80%" <?php echo (!empty($dud_options['user_directory_border_length']) && $dud_options['user_directory_border_length'] == "80%") ? "SELECTED" : ""; ?>>80%</OPTION> 
						<OPTION value="70%" <?php echo (!empty($dud_options['user_directory_border_length']) && $dud_options['user_directory_border_length'] == "70%") ? "SELECTED" : ""; ?>>70%</OPTION> 
						<OPTION value="60%" <?php echo (!empty($dud_options['user_directory_border_length']) && $dud_options['user_directory_border_length'] == "60%") ? "SELECTED" : ""; ?>>60%</OPTION> 
						<OPTION value="50%" <?php echo (!empty($dud_options['user_directory_border_length']) && $dud_options['user_directory_border_length'] == "50%") ? "SELECTED" : ""; ?>>50%</OPTION> 
				 </select></td>
				<td><b>Border Style</b><br><select name="<?php echo $dud_option_name;?>[user_directory_border_style]" id="user_directory_border_style" style="width:42%">
						<OPTION value="solid" <?php echo (!empty($dud_options['user_directory_border_style']) && $dud_options['user_directory_border_style'] == "solid") ? "SELECTED" : ""; ?>>solid</OPTION> 
						<OPTION value="dotted" <?php echo (!empty($dud_options['user_directory_border_style']) && $dud_options['user_directory_border_style'] == "dotted") ? "SELECTED" : ""; ?>>dotted</OPTION> 
						<OPTION value="dashed" <?php echo (!empty($dud_options['user_directory_border_style']) && $dud_options['user_directory_border_style'] == "dashed") ? "SELECTED" : ""; ?>>dashed</OPTION> 
						<OPTION value="double" <?php echo (!empty($dud_options['user_directory_border_style']) && $dud_options['user_directory_border_style'] == "double") ? "SELECTED" : ""; ?>>double</OPTION> 
						<OPTION value="groove" <?php echo (!empty($dud_options['user_directory_border_style']) && $dud_options['user_directory_border_style'] == "groove") ? "SELECTED" : ""; ?>>groove</OPTION> 
						<OPTION value="ridge" <?php echo (!empty($dud_options['user_directory_border_style']) && $dud_options['user_directory_border_style'] == "ridge") ? "SELECTED" : ""; ?>>ridge</OPTION> 
				 </select> </td>
				<td></td>
				<td></td>
			 </tr>								  
		 <tr>
			<td><div id="top"><b>Listing Font Size</b></div><BR>
				<input type="text" size="2" maxlength="2" id="user_directory_listing_fs" name="<?php echo $dud_option_name;?>[user_directory_listing_fs]" 
					value="<?php echo !empty($dud_options['user_directory_listing_fs']) ? esc_attr( $dud_options['user_directory_listing_fs'] ) : ""; ?>" /> px
			</td>
			<td><div id="top"><b>Space Between Listings</b></div><BR>
				<input type="text" size="2" maxlength="2" id="user_directory_listing_spacing" name="<?php echo $dud_option_name;?>[user_directory_listing_spacing]" 
					value="<?php echo !empty($dud_options['user_directory_listing_spacing']) || $dud_options['user_directory_listing_spacing'] === "0" ? esc_attr( $dud_options['user_directory_listing_spacing'] ) : ""; ?>" /> px</td>
			<td>Space Between Listings: how much space (in pixels) to insert between each directory listing.</td>
			<td></td>
		 </tr>
		 <tr>
		        <?php 
					if(empty($dud_options['ud_letter_spacing'])) $dud_options['ud_letter_spacing'] = "1px";	
				?>
				<td><b>Letter Spacing</b>
				<td>
					<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_letter_spacing]" id="ud_letter_spacing">
							<OPTION value="0px">0px</OPTION> 
							<OPTION value="1px" <?php echo ($dud_options['ud_letter_spacing'] == "1px") 
								? "SELECTED" : ""; ?>>1px</OPTION>             
							<OPTION value="2px" <?php echo ($dud_options['ud_letter_spacing'] == "2px") 
								? "SELECTED" : ""; ?>>2px</OPTION> 
					</select> 
				</td>
				<td>How much space to show between letters in each directory listing.</td>
				<td></td>
			 </tr>
		 <tr>
			<td colspan="4"><b><span style='color:#08788c;'>Error Message Settings</span></b></td>
			<td></td>
			<td></td>
			<td></td>
		 </tr>
		 <tr>
			<td><b>Empty Directory Error</b></td>
			<td><input style="width:331px;" type="text" maxlength="150" id="ud_empty_dir_err" name="<?php echo $dud_option_name;?>[ud_empty_dir_err]" 
					value="<?php echo (!empty($dud_options['ud_empty_dir_err'] )) ? esc_attr( $dud_options['ud_empty_dir_err'] ) : "There are no users in the directory at this time."; ?>" /></td>
			<td>If there are no users in the directory.</td>
			<td></td>
		 </tr>
		 <tr>
			<td><b>Invalid Value Search Error</b></td>
			<td><input style="width:331px;" type="text" maxlength="150" id="ud_invalid_val_err" name="<?php echo $dud_option_name;?>[ud_invalid_val_err]" 
					value="<?php echo (!empty($dud_options['ud_invalid_val_err'] )) ? esc_attr( $dud_options['ud_invalid_val_err'] ) : "Please enter a valid search value."; ?>" /></td>
			<td>If an invalid search value is entered.</td>
			<td></td>
		 </tr>
		 <tr>
			<td><b>No Users Found Search Error</b></td>
			<td><input style="width:331px;" type="text" maxlength="150" id="ud_no_users_found_err" name="<?php echo $dud_option_name;?>[ud_no_users_found_err]" 
					value="<?php echo (!empty($dud_options['ud_no_users_found_err'] )) ? esc_attr( $dud_options['ud_no_users_found_err'] ) : "No users were found matching your search criteria."; ?>" /></td>
			<td>If no results are returned for a search.</td>
			<td></td>
		 </tr>
		 <tr>
			<td><b>Error Message Font Size</b></td>
			<td><input type="text" size="2" maxlength="2" id="ud_err_msg_font_size" name="<?php echo $dud_option_name;?>[ud_err_msg_font_size]" 
					value="<?php echo (!empty($dud_options['ud_err_msg_font_size'] )) ? esc_attr( $dud_options['ud_err_msg_font_size'] ) : "20"; ?>" /> px</td>
			<td></td>
			<td></td>
		 </tr>
		 </div>
	</table>
<br/><br/>
</div>
<br/><br/>

<div class="dud-settings-section-header">&nbsp; Directory Totals Settings</div>
<div class="dud-settings-section">
	<table class="form-table">
		<tr>
			<td><b>Directory Totals</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_show_num_users]" id="ud_show_num_users">
						<OPTION value="">Don't Show</OPTION> 
						<OPTION value="both" <?php echo (!empty($dud_options['ud_show_num_users']) && $dud_options['ud_show_num_users'] === "both") 
							? "SELECTED" : ""; ?>>Show User & Search Result Totals</OPTION> 
						<OPTION value="results" <?php echo (!empty($dud_options['ud_show_num_users']) && $dud_options['ud_show_num_users'] === "results") 
							? "SELECTED" : ""; ?>>Show Search Result Totals Only</OPTION> 
						
				</select> </td>
			<td>Display the number of members in the directory and/or the number of search results returned. When set to "Show User & Search Result Totals," the total members will be shown by default and the total search results will be shown when a search is run.</td>
			<td></td>
		 </tr>
		 
		 <tr id="ud_directory_totals_1">
			<td><b>Text After Number of Members</b></td>
			<td><input type="text" maxlength="70" id="ud_txt_after_num_users" class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_txt_after_num_users]" 
					value="<?php echo !empty($dud_options['ud_txt_after_num_users']) ? esc_attr( $dud_options['ud_txt_after_num_users'] ) : "total members"; ?>" /></td>
			<td>Customize the text that follows the number of members. Default is "total members."</td><td></td>
		 </tr>		
		<tr id="ud_directory_totals_2">
			<td><b>Text After Number of Search Results</b></td>
			<td><input type="text" maxlength="70" id="ud_txt_after_num_users_srch" class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_txt_after_num_users_srch]" 
					value="<?php echo !empty($dud_options['ud_txt_after_num_users_srch']) ? esc_attr( $dud_options['ud_txt_after_num_users_srch'] ) : "search results"; ?>" /></td>
			<td>Customize the text that follows the number of members when a search is run. Default is "search results." This setting only applies if your directory has a user search and you have chosen to show search result totals.</td><td></td>
		 </tr>				 
		  <tr id="ud_directory_totals_3">
			<td><b>Position</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_num_users_top_bottom]" id="ud_num_users_top_bottom">
						<OPTION value="top">Top of Directory</OPTION> 
						<OPTION value="bottom" <?php echo (!empty($dud_options['ud_num_users_top_bottom']) && $dud_options['ud_num_users_top_bottom'] === "bottom") 
							? "SELECTED" : ""; ?>>Bottom of Directory</OPTION> 
						<OPTION value="both" <?php echo (!empty($dud_options['ud_num_users_top_bottom']) && $dud_options['ud_num_users_top_bottom'] === "both") 
							? "SELECTED" : ""; ?>>Top & Bottom of Directory</OPTION> 
				</select> </td>
			<td>Choose where to show the directory totals.</td>
			<td></td>
		</tr>	
		<tr id="ud_directory_totals_4">
			<td><b>Font Size</b></td>
			<td><input type="text" size="2" maxlength="3" id="ud_num_users_font_size" class="dd-menu-chk-box-width" name="<?php echo $dud_option_name;?>[ud_num_users_font_size]" 
					value="<?php echo !empty($dud_options['ud_num_users_font_size']) ? esc_attr( $dud_options['ud_num_users_font_size'] ) : ""; ?>"/> px</td>
			<td></td>
			<td></td>
		 </tr>
		 <tr id="ud_directory_totals_5">
			<td><b>Border</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_num_users_border]" id="ud_num_users_border">
						<OPTION value="">No Border</OPTION> 
						<OPTION value="100%" <?php echo (!empty($dud_options['ud_num_users_border']) && $dud_options['ud_num_users_border'] === "100%") 
							? "SELECTED" : ""; ?>>100% Width</OPTION> 
						<OPTION value="90%" <?php echo (!empty($dud_options['ud_num_users_border']) && $dud_options['ud_num_users_border'] === "90%") 
							? "SELECTED" : ""; ?>>90% Width</OPTION> 
						<OPTION value="80%" <?php echo (!empty($dud_options['ud_num_users_border']) && $dud_options['ud_num_users_border'] === "80%") 
							? "SELECTED" : ""; ?>>80% Width</OPTION> 
						<OPTION value="70%" <?php echo (!empty($dud_options['ud_num_users_border']) && $dud_options['ud_num_users_border'] === "70%") 
							? "SELECTED" : ""; ?>>70% Width</OPTION> 
						<OPTION value="60%" <?php echo (!empty($dud_options['ud_num_users_border']) && $dud_options['ud_num_users_border'] === "60%") 
							? "SELECTED" : ""; ?>>60% Width</OPTION> 
						<OPTION value="50%" <?php echo (!empty($dud_options['ud_num_users_border']) && $dud_options['ud_num_users_border'] === "50%") 
							? "SELECTED" : ""; ?>>50% Width</OPTION> 
				</select> </td>
			<td>Show a dividing border below the directory totals (if showing the totals at the top) or above the directory totals (if showing the totals at the bottom).</td>
			<td></td>
		 </tr>	
		 <tr id="ud_directory_totals_6">
			<td><b>Border Color</b></td>
			<td>
				<input type="text" name="<?php echo $dud_option_name;?>[ud_num_users_border_color]" 
						value="<?php echo esc_attr( $dud_options['ud_num_users_border_color'] ); ?>" class="cpa-color-picker"></div>
			</td>
			<td></td>
			<td></td>
		 </tr>
	</table>	
<br/><br/>
</div>
<br/><br/>
	
<?php if ($export_active) 
{ ?>
<div class="dud-settings-section-header">&nbsp; Directory Export Settings</div>
<div class="dud-settings-section">
	<table class="form-table">
		<tr>
			<td><b>Directory Export</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[dud_export_directory]" id="dud_export_directory">
						<OPTION value="off">Off</OPTION> 
						<OPTION value="on" <?php echo (!empty($dud_options['dud_export_directory']) && $dud_options['dud_export_directory'] === "on") 
							? "SELECTED" : ""; ?>>On</OPTION> 						
				</select> </td>
			<td>Select "on" to enable csv file exporting for this directory. The download link will appear on the front end.</td>
			<td></td>
		</tr>
		<tr>
			<td><b>Export File Prefix</b></td>
			<td><input style="width:331px;" type="text" maxlength="150" id="dud_export_file_prefix" name="<?php echo $dud_option_name;?>[dud_export_file_prefix]" 
					value="<?php echo (!empty($dud_options['dud_export_file_prefix'] )) ? esc_attr( $dud_options['dud_export_file_prefix'] ) : "Directory-Export-"; ?>" /></td>
			<td>Customize the export filename prefix that is added to the timestamp. Default is "Directory-Export-"</td>
			<td></td>
		</tr>
		<tr>
			<td><b>Only Show Directory Export Link For These Roles</b></td>
			<td><?php echo !empty($dud_options['dud_export_roles_show_full_link']) ? dynamic_ud_export_roles_listbox($dud_options['dud_export_roles_show_full_link'], $dud_option_name, "dud_export_roles_show_full_link") : dynamic_ud_export_roles_listbox("", $dud_option_name, "dud_export_roles_show_full_link"); ?>
			</td>
			<td style="font-size:13.5px;font-style:italic; line-height: 21px;padding-left:3%">Choose which roles have permission to export the directory (you may select multiple roles). The download link will be shown on the front end only for users with these roles. If nothing is selected, the link will be shown to ALL viewers.</td>
			<td></td>
		</tr>
		<tr>
			<td><b>Only Show Search Results Export Link For These Roles</b></td>
			<td><?php echo !empty($dud_options['dud_export_roles_show_srch_link']) ? dynamic_ud_export_roles_listbox($dud_options['dud_export_roles_show_srch_link'], $dud_option_name, "dud_export_roles_show_srch_link") : dynamic_ud_export_roles_listbox("", $dud_option_name, "dud_export_roles_show_srch_link"); ?>
			</td>
			<td style="font-size:13.5px;font-style:italic; line-height: 21px;padding-left:3%">Choose which roles have permission to export the directory search results (you may select multiple roles). The download link will be shown on the front end only for users with these roles. If nothing is selected, the link will be shown to ALL viewers.</td>
			<td></td>
		</tr>
		<tr>
			<td><b>Link Position</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[dud_export_link_position]" id="dud_export_link_position">
						<OPTION value="top">Top of Directory</OPTION> 
						<OPTION value="bottom" <?php echo (!empty($dud_options['dud_export_link_position']) && $dud_options['dud_export_link_position'] === "bottom") 
							? "SELECTED" : ""; ?>>Bottom of Directory</OPTION> 						
				</select> </td>
			<td>Choose whether to show the export and search results link at the top or bottom of the directory.</td>
			<td></td>
		 </tr>
		<tr>
			<td><b>Directory Download Link Text</b></td>
			<td><input style="width:331px;" type="text" maxlength="150" id="dud_export_directory_link_text" name="<?php echo $dud_option_name;?>[dud_export_directory_link_text]" 
					value="<?php echo (!empty($dud_options['dud_export_directory_link_text'] )) ? esc_attr( $dud_options['dud_export_directory_link_text'] ) : "Download Directory CSV"; ?>" /></td>
			<td>Set the text for the directory download link.</td>
			<td></td>
		</tr>
		<tr>
			<td><b>Search Results Download Link Text</b></td>
			<td><input style="width:331px;" type="text" maxlength="150" id="dud_export_srch_link_text" name="<?php echo $dud_option_name;?>[dud_export_srch_link_text]" 
					value="<?php echo (!empty($dud_options['dud_export_srch_link_text'] )) ? esc_attr( $dud_options['dud_export_srch_link_text'] ) : "Download Search Results CSV"; ?>" /></td>
			<td>Set the text for the search results download link.</td>
			<td></td>
		</tr>
		<tr>
			<td><b>Show Labels</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[dud_export_show_labels]" id="dud_export_show_labels">
						<OPTION value="show">Show</OPTION> 
						<OPTION value="hide" <?php echo (!empty($dud_options['dud_export_show_labels']) && $dud_options['dud_export_show_labels'] === "hide") 
							? "SELECTED" : ""; ?>>Hide</OPTION> 						
				</select> </td>
			<td>Show the directory labels in your export file. These will appear horizontally across the top row.</td>
			<td></td>
		 </tr>
		 <tr>
			<td><b>Show Letter/Category Divider</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[dud_export_show_divider]" id="dud_export_show_divider">
						<OPTION value="show">Show</OPTION> 
						<OPTION value="hide" <?php echo (!empty($dud_options['dud_export_show_divider']) && $dud_options['dud_export_show_divider'] === "hide") 
							? "SELECTED" : ""; ?>>Hide</OPTION> 						
				</select> </td>
			<td>Show a letter divider (normal directory) or category divider (Custom Sort Field add-on) in the export listings.</td>
			<td></td>
		</tr>
		<tr>
			<td><b>Performance Improvement: Show Directory Export Link</b></td>
			<td><input name="<?php echo $dud_option_name;?>[dud_export_performance]" id="dud_export_performance" type="checkbox" 
			   value="1" <?php if(!empty($dud_options['dud_export_performance'])) { checked( '1', $dud_options['dud_export_performance'] ); } ?> />
			</td>
			<td>Check this box if you are experiencing slow page load time or a high number of export files are appearing in your root directory. Instead of auto-generating the export file each time the page loads, an "export" link will be shown that must be clicked before the export file is created. When the page refreshes, the "download" link will then be shown.</td>
			<td></td>
        </tr>
		<tr>
			<td><b>Directory Export Link Text</b></td>
			<td><input style="width:331px;" type="text" maxlength="150" id="dud_export_initial_dir_link_text" name="<?php echo $dud_option_name;?>[dud_export_initial_dir_link_text]" 
					value="<?php echo (!empty($dud_options['dud_export_initial_dir_link_text'] )) ? esc_attr( $dud_options['dud_export_initial_dir_link_text'] ) : "Export Directory to CSV"; ?>" /></td>
			<td>This link is only shown if the "Performance Improvement" box above is checked.</td>
			<td></td>
		</tr>
	</table>	
<br/><br/>
</div>
<br/><br/>	
<?php } ?>
		
<div class="dud-settings-section-header">&nbsp; Alphabet and Pagination Link Settings</div>
<div class="dud-settings-section">
	<table class="form-table">
		 
		 <tr>
			<td colspan="3" style="line-height:22px;"><b><span style='color:#08788c;'>LETTER LINKS</span></b><br><hr>
			This section only applies if you have selected "Alphabet Letter Links" as the directory type or you have installed the Alpha Links Scroll add-on. 
			<hr></td>
		</tr>
				
		<?php 
		 if ($alpha_links_scroll_active) { ?> 
		 <tr>
				<td><b>Alpha Links Scroll Add-on</b></td>
				<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_alpha_links_scroll]" id="ud_alpha_links_scroll">
						<OPTION value="on">On</OPTION> 
						<OPTION value="off" <?php echo ($dud_options['ud_alpha_links_scroll'] == "off") 
							? "SELECTED" : ""; ?>>Off</OPTION> 
				</select> </td>
				</td>
				<td>Show or hide the scrollable alpha links created by the Alpha Links Scroll add-on for a "Single Page" directory. NOTE: Selecting "On" will automatically clear the "Number of Users Per Page" pagination field below. This is because the Alpha Links Scroll add-on requires the entire directory to be on one page.</td>
				<td></td>
		 </tr>
		 <?php } ?>
		 <tr>
			<td><div id="top"><b>Letter Links Font Size</b></div><br>
				<input type="text" size="2" maxlength="2" id="user_directory_letter_fs" name="<?php echo $dud_option_name;?>[user_directory_letter_fs]" 
					value="<?php echo !empty($dud_options['user_directory_letter_fs']) ? esc_attr( $dud_options['user_directory_letter_fs'] ) : ""; ?>" /> px
			</td>
			<td><div id="top"><b>Letter Links Spacing</b></div><BR>
				<input type="text" size="2" maxlength="2" id="ud_alpha_link_spacer" name="<?php echo $dud_option_name;?>[ud_alpha_link_spacer]" 
					value="<?php echo !empty($dud_options['ud_alpha_link_spacer']) ? esc_attr( $dud_options['ud_alpha_link_spacer'] ) : ""; ?>" /> px
			</td>
			<td>Letter Links Spacing: how much space (in pixels) to insert between each of the alphabetic links.</td>
			<td></td>
		 </tr>		
		 <tr>
			<td><div id="top"><b>Letter Link Color</b></div><br>
			<input type="text" name="<?php echo $dud_option_name;?>[ud_alpha_link_color]" 
							value="<?php echo !empty($dud_options['ud_alpha_link_color']) ? esc_attr( $dud_options['ud_alpha_link_color'] ) : ""; ?>" class="cpa-color-picker">
							
			</td>
			<td><div id="top"><b>Letter Link Clicked Color</b></div><BR>
				<input type="text" name="<?php echo $dud_option_name;?>[ud_alpha_link_clicked_color]" 
							value="<?php echo !empty($dud_options['ud_alpha_link_clicked_color']) ? esc_attr( $dud_options['ud_alpha_link_clicked_color'] ) : ""; ?>" class="cpa-color-picker">
			</td>
			<td><i>Letter Link Color</i>: the color of the alphabet letter links. Leave blank to use your theme's default link color.<br><BR> 
				<i>Letter Link Clicked Color</i>: the color of the alphabet letter link that is currently being viewed. Leave blank if you do not want to highlight the selected letter link.</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="3" style="line-height:22px;"><b><span style='color:#08788c;'>PAGINATION</span></b><br><hr>Pagination works for both Single Page & Alphabet Letter Links directory types. 
			     On Letter Links directories, pagination will only be shown for a selected letter if that letter has more listings than the number of users per page. 
			<hr></td>
		</tr>
		 <tr>
			<td><b>Number of Users Per Page</b></td>
			<td><input type="text" size="3" maxlength="4" id="ud_users_per_page" class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_users_per_page]" 
					value="<?php echo !empty($dud_options['ud_users_per_page']) ? esc_attr( $dud_options['ud_users_per_page'] ) : ""; ?>" /></td>
			<td>Enter a number here to activate pagination on your directory. If you do not want pagination, leave this blank. NOTE: Turning on pagination will automatically uncheck (deactivate) the Alpha Links Scroll add-on if it is installed and activated.</td>
			<td></td>
		 </tr>		 
		  <tr>
			<td><b>Show Pagination Links</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_show_pagination_top_bottom]" id="ud_show_pagination_top_bottom">
						<OPTION value="top">Top of Directory</OPTION> 
						<OPTION value="bottom" <?php echo ($dud_options['ud_show_pagination_top_bottom'] == "bottom") 
							? "SELECTED" : ""; ?>>Bottom of Directory</OPTION> 
						<OPTION value="both" <?php echo ($dud_options['ud_show_pagination_top_bottom'] == "both") 
							? "SELECTED" : ""; ?>>Top & Bottom of Directory</OPTION>  
				</select> </td>
			<td></td>
			<td></td>
		 </tr>		 
		 <tr id="pagination_above_below">
			<td><b>Pagination Top Position</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_show_pagination_above_below]" id="ud_show_pagination_above_below">
						<OPTION value="above">Above Search Box</OPTION> 
						<OPTION value="below" <?php echo ($dud_options['ud_show_pagination_above_below'] === "below") 
							? "SELECTED" : ""; ?>>Below Search Box</OPTION> 
				</select> </td>
			<td>This only applies if your directory is showing the last name/display name search box, or you have installed the Meta Fields Search add-on.</td>
			<td></td>
		 </tr>		 
		 <tr>
			<td><b>Pagination Link Font Size</b></td>
			<td><input type="text" size="2" maxlength="3" id="ud_pagination_font_size" class="dd-menu-chk-box-width" name="<?php echo $dud_option_name;?>[ud_pagination_font_size]" 
					value="<?php echo !empty($dud_options['ud_pagination_font_size']) ? esc_attr( $dud_options['ud_pagination_font_size'] ) : ""; ?>"/> px</td>
			<td></td>
			<td></td>
		 </tr>
		  <tr>
			<td><b>Padding Top</b></td>
			<td><input type="text" size="2" maxlength="3" id="ud_pagination_padding_top" class="dd-menu-chk-box-width" name="<?php echo $dud_option_name;?>[ud_pagination_padding_top]" 
					value="<?php echo !empty($dud_options['ud_pagination_padding_top']) ? esc_attr( $dud_options['ud_pagination_padding_top'] ) : "0"; ?>"/> px</td>
			<td>Increase the padding above the pagination links. Set to 0 or leave blank for no additional padding.</td>
			<td></td>
		 </tr>
		  <tr>
			<td><b>Padding Bottom</b></td>
			<td><input type="text" size="2" maxlength="3" id="ud_pagination_padding_bottom" class="dd-menu-chk-box-width" name="<?php echo $dud_option_name;?>[ud_pagination_padding_bottom]" 
					value="<?php echo !empty($dud_options['ud_pagination_padding_bottom']) ? esc_attr( $dud_options['ud_pagination_padding_bottom'] ) : "10"; ?>"/> px</td>
			<td>Increase the padding below the pagination links. Set to 0 or leave blank for no additional padding.</td>
			<td></td>
		 </tr>
		 <tr>
			<td><div id="top"><b>Pagination Link Color</b></div><br>
			<input type="text" name="<?php echo $dud_option_name;?>[ud_pagination_link_color]" 
							value="<?php echo !empty($dud_options['ud_pagination_link_color']) ? esc_attr( $dud_options['ud_pagination_link_color'] ) : ""; ?>" class="cpa-color-picker">
							
			</td>
			<td><div id="top"><b>Pagination Link Clicked Color</b></div><BR>
				<input type="text" name="<?php echo $dud_option_name;?>[ud_pagination_link_clicked_color]" 
							value="<?php echo !empty($dud_options['ud_pagination_link_clicked_color']) ? esc_attr( $dud_options['ud_pagination_link_clicked_color'] ) : ""; ?>" class="cpa-color-picker">
			</td>
			<td><i>Pagination Link Color</i>: the color of the numeric page links. Leave blank to use your theme's default link color.
				<i>Pagination Link Clicked Color</i>: the color of the page link that is currently being viewed. Leave blank if you do not want to highlight the selected page link.
				<br><br>It is recommended that the pagination link colors match their letter link counterparts when using an Alphabet Letter Links directory type.</td>
			<td></td>
		 </tr> 
	</table>	
<br/><br/>
</div>
<br/><br/>

<div class="dud-settings-section-header">&nbsp; Meta Fields Settings</div>
<div class="dud-settings-section">
	<table class="form-table">
		<tr>
			<td colspan="3" style="line-height:22px;"><b>Instructions</b><br><hr>This is where you will build the content of your directory. The key name listbox(es) below contain the names of the user meta fields available for use in your directory. 
			Find the meta key names corresponding to the user profile fields you want to display, then copy and paste each one into the Meta Key Name input fields. The Address and Social Meta Fields sections may be used instead if you would like DUD 
			to format them for you.<hr></td>
		</tr>
		<tr>
			<td><b>Show Email Addr</b>&nbsp;&nbsp;<input name="<?php echo $dud_option_name;?>[user_directory_email]" id="user_directory_email" type="checkbox" value="1" 
				<?php if(!empty($dud_options['user_directory_email'])) { checked( '1', $dud_options['user_directory_email'] ); } ?> /></td>
			<td><b>Show Website</b>&nbsp;&nbsp;<input name="<?php echo $dud_option_name;?>[user_directory_website]" id="user_directory_website" type="checkbox" value="1" 
				<?php if(!empty($dud_options['user_directory_website'])) { checked( '1', $dud_options['user_directory_website'] ); } ?> /></td>
			<td>Check the boxes to show these built-in WordPress user profile fields in the directory. If you wish to show an email or website address that is stored in a meta field instead, do *not* check these boxes, and simply add the email and/or website meta field key names below. </td>
			<td></td>
		 </tr>
		 <tr>
			<td><b>Show User Roles</b>&nbsp;&nbsp;<input name="<?php echo $dud_option_name;?>[ud_show_user_roles]" id="ud_show_user_roles" type="checkbox" value="1" 
				<?php if(!empty($dud_options['ud_show_user_roles'])) { checked( '1', $dud_options['ud_show_user_roles'] ); } ?> /></td>
			<td><b>User Roles Format</b><br>
						<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_user_roles_format]">
							<OPTION value="1" <?php echo (!empty($dud_options['ud_user_roles_format']) && $dud_options['ud_user_roles_format'] == "1") ? "SELECTED" : ""; ?>>Comma Delimited List</OPTION>
							<OPTION value="2" <?php echo (!empty($dud_options['ud_user_roles_format']) && $dud_options['ud_user_roles_format'] == "2") ? "SELECTED" : ""; ?>>Bulleted List</OPTION>
						</select></td>
			<td>Check this box to show a list of the WordPress user roles assigned to each user in the directory listing.</td>
			<td></td>
		 </tr>
		<tr>
			<td><b>Show Date Registered</b>&nbsp;&nbsp;<input name="<?php echo $dud_option_name;?>[ud_date_registered]" id="ud_date_registered" type="checkbox" value="1" 
				<?php if(!empty($dud_options['ud_date_registered'])) { checked( '1', $dud_options['ud_date_registered'] ); } ?> /></td>
			<td><b>Date Format</b><br>
						<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_date_registered_format]">
							<OPTION value="" <?php echo (!empty($dud_options['ud_date_registered_format']) && $dud_options['ud_date_registered_format'] == "") ? "SELECTED" : ""; ?>>Default</OPTION>
							<OPTION value="16" <?php echo (!empty($dud_options['ud_date_registered_format']) && $dud_options['ud_date_registered_format'] == "16") ? "SELECTED" : ""; ?>>dd.mm.yyyy hh:mm:ss</OPTION>
							<OPTION value="17" <?php echo (!empty($dud_options['ud_date_registered_format']) && $dud_options['ud_date_registered_format'] == "17") ? "SELECTED" : ""; ?>>dd.mm.yy hh:mm:ss</OPTION>
							<OPTION value="18" <?php echo (!empty($dud_options['ud_date_registered_format']) && $dud_options['ud_date_registered_format'] == "18") ? "SELECTED" : ""; ?>>dd.mm.yy</OPTION>
							<OPTION value="19" <?php echo (!empty($dud_options['ud_date_registered_format']) && $dud_options['ud_date_registered_format'] == "19") ? "SELECTED" : ""; ?>>dd.mm.yyyy</OPTION>
							<OPTION value="20" <?php echo (!empty($dud_options['ud_date_registered_format']) && $dud_options['ud_date_registered_format'] == "20") ? "SELECTED" : ""; ?>>mm/dd/yyyy hh:mm:ss</OPTION>
							<OPTION value="21" <?php echo (!empty($dud_options['ud_date_registered_format']) && $dud_options['ud_date_registered_format'] == "21") ? "SELECTED" : ""; ?>>mm/dd/yy hh:mm:ss</OPTION>
							<OPTION value="22" <?php echo (!empty($dud_options['ud_date_registered_format']) && $dud_options['ud_date_registered_format'] == "22") ? "SELECTED" : ""; ?>>mm/dd/yy</OPTION>
							<OPTION value="23" <?php echo (!empty($dud_options['ud_date_registered_format']) && $dud_options['ud_date_registered_format'] == "23") ? "SELECTED" : ""; ?>>mm/dd/yyyy</OPTION>
						</select>
			</td>
			<td>Check the box to show the date registered from the built-in WordPress user profile field in the directory.</td>
			<td></td>
		</tr>
		<tr id="date_lbl_row">
			<td><b>Date Registered Label</b></td>
			<td><input type="text" id="ud_date_lbl" class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_date_lbl]" 
					value="<?php echo !empty($dud_options['ud_date_lbl']) ? esc_attr( $dud_options['ud_date_lbl'] ) : ""; ?>" /></td>
			<td>Enter a label for the date registered. If left blank, no label will be shown.</td>
			<td></td>
		</tr>	
		<tr id="roles_lbl_row">
			<td><b>User Roles Label</b></td>
			<td><input type="text" id="ud_roles_lbl" class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_roles_lbl]" 
					value="<?php echo !empty($dud_options['ud_roles_lbl']) ? esc_attr( $dud_options['ud_roles_lbl'] ) : ""; ?>" /></td>
			<td>Enter a label for the user roles. If left blank, no label will be shown.</td>
			<td></td>
		</tr>		
		<tr id="email_lbl_row">
			<td><b>Email Addr Label</b></td>
			<td><input type="text" id="ud_email_lbl" class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_email_lbl]" 
					value="<?php echo !empty($dud_options['ud_email_lbl']) ? esc_attr( $dud_options['ud_email_lbl'] ) : ""; ?>" /></td>
			<td>Enter a label for the email address. If left blank, no label will be shown.</td>
			<td></td>
		</tr>
		<tr id="email_format_row">
			<td><b>Email Addr Format</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_email_format]">
							<OPTION value="hyperlink" <?php echo (!empty($dud_options['ud_email_format']) && $dud_options['ud_email_format'] == "hyperlink") ? "SELECTED" : ""; ?>>Hyperlink in new tab</OPTION>
							<OPTION value="text" <?php echo (!empty($dud_options['ud_email_format']) && $dud_options['ud_email_format'] == "text") ? "SELECTED" : ""; ?>>Plain Text (Anti Spam)</OPTION>
				</select>
			</td>
			<td></td>
			<td></td>
		</tr>
		<tr id="website_format_row">
			<td><b>Open Website Link</b></td>
			<td><select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_website_format]">
							<OPTION value="main" <?php echo (!empty($dud_options['ud_website_format']) && $dud_options['ud_website_format'] == "main") ? "SELECTED" : ""; ?>>In same window</OPTION>
							<OPTION value="separate" <?php echo (!empty($dud_options['ud_website_format']) && $dud_options['ud_website_format'] == "separate") ? "SELECTED" : ""; ?>>In new tab</OPTION>
				</select>
			</td>
			<td></td>
			<td></td>
		</tr>		
		<tr id="website_lbl_row">
			<td><b>Website Label</b></td>
			<td><input type="text" id="ud_website_lbl" class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_website_lbl]" 
					value="<?php echo !empty($dud_options['ud_website_lbl']) ? esc_attr( $dud_options['ud_website_lbl'] ) : ""; ?>" /></td>
			<td>Enter a label for the website. If left blank, no label will be shown.</td>
			<td></td>
		</tr>		
		<tr>
			<td colspan="2"><b>WordPress Meta Key Names</b><br><?php echo dynamic_ud_load_meta_keys("wp"); ?></td> 
			<td id="list-box-instructions">A listing of the meta key fields <u>for reference only</u>. You must type or copy & paste the key name into the appropriate meta field below for the key field value to be displayed in the directory. Enter the key name using the SAME capitalization shown in the key names list.</td>
			<td></td>
		 </tr>
	 
	 <?php 
	 $dud_plugin_list = get_option('active_plugins');
	 
	 if ( in_array( 'cimy-user-extra-fields/cimy_user_extra_fields.php' , $dud_plugin_list ) || function_exists('bp_is_active') || in_array( 's2member/s2member.php' , $dud_plugin_list ) ) { ?>
		 <tr>
			<td colspan="2"><?php if ( in_array( 'cimy-user-extra-fields/cimy_user_extra_fields.php' , $dud_plugin_list ) ) { ?>
										<b>Cimy Field Names</b><br><?php echo dynamic_ud_load_meta_keys("cimy"); } 
								  else if(function_exists('bp_is_active')) { ?>
										<b>BuddyPress Extended Profile Field Names</b><BR><?php echo dynamic_ud_load_meta_keys("bp"); } 
								  else if(in_array( 's2member/s2member.php' , $dud_plugin_list ) ) { ?>
										<b>s2Member Custom Field Names</b><BR><?php echo dynamic_ud_load_meta_keys("s2m"); } ?>
			</td>
			<td id="list-box-instructions">You may also include any of these custom fields in your directory. <?php if(function_exists('bp_is_active')) { ?> Note: BuddyPress may clear the WordPress "last name" profile field in certain circumstances. Please ensure this field is not blank if sorting by last name, or the user will NOT appear in the directory.<?php } ?></td>
			<td></td>
		 </tr>
	 <?php } ?>
	 
     <tr>
        <td><b><span style='color:#08788c;'># OF USER META FIELDS</span></b></td>
        <td>
        	<select name="<?php echo $dud_option_name;?>[user_directory_num_meta_flds]" id="user_directory_num_meta_flds">
	            	<OPTION value="1" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "1") ? "SELECTED" : ""; ?>>1</OPTION> 
	            	<OPTION value="2" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "2") ? "SELECTED" : ""; ?>>2</OPTION> 
					<OPTION value="3" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "3") ? "SELECTED" : ""; ?>>3</OPTION> 
	            	<OPTION value="4" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "4") ? "SELECTED" : ""; ?>>4</OPTION> 
	            	<OPTION value="5" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "5") ? "SELECTED" : ""; ?>>5</OPTION> 
	            	<OPTION value="6" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "6") ? "SELECTED" : ""; ?>>6</OPTION>
	            	<OPTION value="7" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "7") ? "SELECTED" : ""; ?>>7</OPTION>
	            	<OPTION value="8" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "8") ? "SELECTED" : ""; ?>>8</OPTION>
	            	<OPTION value="9" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "9") ? "SELECTED" : ""; ?>>9</OPTION>
	            	<OPTION value="10" <?php echo (!empty($dud_options['user_directory_num_meta_flds']) && $dud_options['user_directory_num_meta_flds'] == "10") ? "SELECTED" : ""; ?>>10</OPTION> 
		</select> 
	</td>
        <td>Use the dropdown to show extra meta fields or hide unneeded ones. If you hide a meta key name/label field, that field will automatically be cleared.</td>
        <td></td>
     </tr>	
	 </table>
	 <table class="meta-flds">
	 <?php 
			for($inc = 1; $inc < 11; $inc++)
			{ 
				if( !empty($dud_options['user_directory_meta_link_' . $inc]) && $dud_options['user_directory_meta_link_' . $inc] === '#'
						&& empty($dud_options['dud_fld_format_' . $inc])) 
							$dud_options['dud_fld_format_' . $inc] = "2";		
		?>
				 <tr id="meta_fld_<?php echo $inc; ?>">
					<td><b>Meta Key Name <?php echo $inc; ?></b><br><input type="text" id="user_directory_meta_field_<?php echo $inc; ?>" name="<?php echo $dud_option_name;?>[user_directory_meta_field_<?php echo $inc; ?>]" 
						value="<?php echo !empty($dud_options['user_directory_meta_field_' . $inc]) ? esc_attr( $dud_options['user_directory_meta_field_' . $inc]) : ""; ?>" maxlength="75" /></td>
					
					<td><b>Format Meta Field <?php echo $inc; ?> As</b><br>
						<select name="<?php echo $dud_option_name;?>[dud_fld_format_<?php echo $inc; ?>]" id="dud_hyperlink_flds">
							<OPTION value="1" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "1") ? "SELECTED" : ""; ?>>Plain Text</OPTION>
							<OPTION value="30" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "30") ? "SELECTED" : ""; ?>>Hide Hyphens</OPTION>
							<OPTION value="37" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "37") ? "SELECTED" : ""; ?>>Hide Hyphens (All Caps)</OPTION>
							<OPTION value="38" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "38") ? "SELECTED" : ""; ?>>Hide Hyphens (All Lowercase)</OPTION>
							<OPTION value="34" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "34") ? "SELECTED" : ""; ?>>Image (Field should be a URL)</OPTION>
							<OPTION value="6" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "6") ? "SELECTED" : ""; ?>>Phone Number</OPTION>
							<OPTION value="31" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "31") ? "SELECTED" : ""; ?>>Phone Number (Australian)</OPTION>				
							<OPTION value="32" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "32") ? "SELECTED" : ""; ?>>Mobile Phone Hyperlink</OPTION>
							<OPTION value="33" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "33") ? "SELECTED" : ""; ?>>Mobile Phone Hyperlink (Australian)</OPTION>
							<OPTION value="25" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "25") ? "SELECTED" : ""; ?>>Email Address (Hyperlink)</OPTION> 
							<OPTION value="55" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "55") ? "SELECTED" : ""; ?>>Email Address (Plain Text, Anti-Spam)</OPTION> 
							<OPTION value="24" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "24") ? "SELECTED" : ""; ?>>Multi-line Text Box</OPTION> 
							<OPTION value="2" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "2") ? "SELECTED" : ""; ?>>Hyperlink => Open in Same Window</OPTION> 
							<OPTION value="3" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "3") ? "SELECTED" : ""; ?>>Hyperlink => Open in New Tab</OPTION> 
							<OPTION value="49" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "49") ? "SELECTED" : ""; ?>>Multiselect => Show Values and Labels (Keep Hyphens)</OPTION>
							<OPTION value="51" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "51") ? "SELECTED" : ""; ?>>Multiselect => Show Values (Keep Hyphens)</OPTION>
							<OPTION value="35" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "35") ? "SELECTED" : ""; ?>>Multiselect => Show Labels (Bulleted)</OPTION>
							<OPTION value="5" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "5") ? "SELECTED" : ""; ?>>Multiselect => Show Values (Bulleted)</OPTION>
							<OPTION value="39" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "39") ? "SELECTED" : ""; ?>>Multiselect => Show Values (All Caps and Bulleted)</OPTION>
							<OPTION value="40" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "40") ? "SELECTED" : ""; ?>>Multiselect => Show Values (All Lowercase and Bulleted)</OPTION>
							<OPTION value="36" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "36") ? "SELECTED" : ""; ?>>Multiselect => Show Labels (Comma Delimited)</OPTION>
							<OPTION value="4" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "4") ? "SELECTED" : ""; ?>>Multiselect => Show Values (Comma Delimited)</OPTION>	
							<OPTION value="41" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "41") ? "SELECTED" : ""; ?>>Multiselect => Show Values (All Caps and Comma Delimited)</OPTION>		
							<OPTION value="42" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "42") ? "SELECTED" : ""; ?>>Multiselect => Show Values (All Lowercase and Comma Delimited)</OPTION>	
							<OPTION value="50" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "50") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Values and Labels (Keep Hyphens)</OPTION> 
							<OPTION value="52" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "52") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Values (Keep Hyphens)</OPTION> 
							<OPTION value="11" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "11") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Labels (Bulleted)</OPTION> 								
							<OPTION value="45" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "45") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Values (Bulleted)</OPTION> 	
							<OPTION value="43" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "43") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Values (All Caps and Bulleted)</OPTION> 
							<OPTION value="47" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "47") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Values (All Lowercase and Bulleted)</OPTION> 
							<OPTION value="8" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "8") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Labels (Comma Delimited)</OPTION>							
							<OPTION value="46" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "46") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Values (Comma Delimited)</OPTION>							
							<OPTION value="44" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "44") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Values (All Caps and Comma Delimited)</OPTION>	
							<OPTION value="48" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "48") ? "SELECTED" : ""; ?>>Multiple Checkboxes => Show Values (All Lowercase and Comma Delimited)</OPTION>	
							<OPTION value="13" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "13") ? "SELECTED" : ""; ?>>Single Checkbox => Show Label and Value</OPTION>
							<OPTION value="14" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "14") ? "SELECTED" : ""; ?>>Single Checkbox => Show Label</OPTION>
							<OPTION value="15" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "15") ? "SELECTED" : ""; ?>>Single Checkbox => Show Value</OPTION>
							<OPTION value="53" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "53") ? "SELECTED" : ""; ?>>Single Checkbox => Show Value (All Caps)</OPTION>
							<OPTION value="54" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "54") ? "SELECTED" : ""; ?>>Single Checkbox => Show Value (All Lowercase)</OPTION>
							<OPTION value="56" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "56") ? "SELECTED" : ""; ?>>Birthdate => Show Current Age</OPTION>
							<OPTION value="16" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "16") ? "SELECTED" : ""; ?>>Date => dd.mm.yyyy hh:mm:ss</OPTION>
							<OPTION value="17" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "17") ? "SELECTED" : ""; ?>>Date => dd.mm.yy hh:mm:ss</OPTION>
							<OPTION value="18" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "18") ? "SELECTED" : ""; ?>>Date => dd.mm.yy</OPTION>
							<OPTION value="19" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "19") ? "SELECTED" : ""; ?>>Date => dd.mm.yyyy</OPTION>
							<OPTION value="20" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "20") ? "SELECTED" : ""; ?>>Date => mm/dd/yyyy hh:mm:ss</OPTION>
							<OPTION value="21" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "21") ? "SELECTED" : ""; ?>>Date => mm/dd/yy hh:mm:ss</OPTION>
							<OPTION value="22" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "22") ? "SELECTED" : ""; ?>>Date => mm/dd/yy</OPTION>
							<OPTION value="23" <?php echo (!empty($dud_options['dud_fld_format_' . $inc]) && $dud_options['dud_fld_format_' . $inc] == "23") ? "SELECTED" : ""; ?>>Date => mm/dd/yyyy</OPTION>
						</select>
					</td>
					
					<td><div id="meta_label_<?php echo $inc; ?>"><b>Meta Field Label <?php echo $inc; ?></b><br><input type="text" id="user_directory_meta_label_<?php echo $inc; ?>" name="<?php echo $dud_option_name;?>[user_directory_meta_label_<?php echo $inc; ?>]" 
						value="<?php echo !empty($dud_options['user_directory_meta_label_' . $inc]) ? esc_attr( $dud_options['user_directory_meta_label_' . $inc] ) : ""; ?>" maxlength="75"/>  
						</div>
					</td>
                    <td></td>					
				 </tr>
			
	  <?php } ?>
			<tr><td></td></tr>
	  </table>
	  <table class="form-table">
       <tr>
        <td><b><span style='color:#08788c;'>ADDRESS META FIELDS &nbsp;&nbsp;<div id="address-down-arrow" name="address-down-arrow"><i class="fa fa-angle-down" aria-hidden="true"></i></div><div id="address-up-arrow" name="address-up-arrow"><i class="fa fa-angle-up" aria-hidden="true"></i></div>
</span></b></td>
        <td><input name="<?php echo $dud_option_name;?>[user_directory_address]" id="user_directory_address" type="hidden" value="<?php echo $dud_options['user_directory_address'];?>"/>        	
        </td>
        <td>Expand this section if a formatted mailing address is needed. Note: the address fields will be cleared automatically if this section is minimized.</td>
        <td></td>
     </tr> 
        
     <tr id="street1">
        <td><b>Street 1 Meta Key Name</b></td>
        <td><input type="text" id="user_directory_addr_1" name="<?php echo $dud_option_name;?>[user_directory_addr_1]" 
            value="<?php echo !empty($dud_options['user_directory_addr_1']) ? esc_attr( $dud_options['user_directory_addr_1'] ) : ""; ?>" maxlength="50"/></td>
        <td>Enter your address meta keys here to display a formatted mailing address. Use the Key Names list above for reference.</td>
        <td></td>
     </tr>
     
     <tr id="street2">
        <td><b>Street 2 Meta Key Name</b></td>
        <td><input type="text" id="user_directory_addr_2" name="<?php echo $dud_option_name;?>[user_directory_addr_2]" 
            value="<?php echo !empty($dud_options['user_directory_addr_2']) ? esc_attr( $dud_options['user_directory_addr_2'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr>
     
     <tr id="city">
        <td><b>City Meta Key Name</b></td>
        <td><input type="text" id="user_directory_city" name="<?php echo $dud_option_name;?>[user_directory_city]" 
            value="<?php echo !empty($dud_options['user_directory_city']) ? esc_attr( $dud_options['user_directory_city'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr>
          
     <tr id="state">
        <td><b>State Meta Key Name</b></td>
        <td><input type="text" id="user_directory_state" name="<?php echo $dud_option_name;?>[user_directory_state]" 
        	value="<?php echo !empty($dud_options['user_directory_state']) ? esc_attr( $dud_options['user_directory_state'] ) : ""; ?>" /></td>
        <td></td>
     </tr> 
         
     <tr id="zip">
        <td><b>Zip Meta Key Name</b></td>
        <td><input type="text" id="user_directory_zip" name="<?php echo $dud_option_name;?>[user_directory_zip]" 
            value="<?php echo !empty($dud_options['user_directory_zip']) ? esc_attr( $dud_options['user_directory_zip'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr> 
	  <tr id="country">
        <td><b>Country Meta Key Name</b></td>
        <td><input type="text" id="user_directory_country" name="<?php echo $dud_option_name;?>[user_directory_country]" 
            value="<?php echo !empty($dud_options['user_directory_country']) ? esc_attr( $dud_options['user_directory_country'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr> 
     <tr>
        <td><b><span style='color:#08788c;'>SOCIAL META FIELDS &nbsp;&nbsp;<div id="social-down-arrow" name="social-down-arrow"><i class="fa fa-angle-down" aria-hidden="true"></i></div><div id="social-up-arrow" name="social-up-arrow"><i class="fa fa-angle-up" aria-hidden="true"></i></div>
</span></b></td>
        <td></td>
        <td>Expand this section if you have social media meta fields to display. These will be shown as a row of icons. Note: the social media fields will be cleared automatically if this section is minimized.</td>
        <td><input name="<?php echo $dud_option_name;?>[ud_social]" id="ud_social" type="hidden" value="<?php echo $dud_options['ud_social'];?>"/> </td>
     </tr> 
        
     <tr id="facebook">
        <td><b>Facebook Meta Key Name</b></td>
        <td><input type="text" id="ud_facebook" name="<?php echo $dud_option_name;?>[ud_facebook]" 
            value="<?php echo !empty($dud_options['ud_facebook']) ? esc_attr( $dud_options['ud_facebook'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr>
     
     <tr id="twitter">
        <td><b>X (formerly Twitter) Meta Key Name</b></td>
        <td><input type="text" id="ud_twitter" name="<?php echo $dud_option_name;?>[ud_twitter]" 
            value="<?php echo !empty($dud_options['ud_twitter']) ? esc_attr( $dud_options['ud_twitter'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr>
     
     <tr id="linkedin">
        <td><b>LinkedIn Meta Key Name</b></td>
        <td><input type="text" id="ud_linkedin" name="<?php echo $dud_option_name;?>[ud_linkedin]" 
            value="<?php echo !empty($dud_options['ud_linkedin']) ? esc_attr( $dud_options['ud_linkedin'] ) : "";  ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr>
                   
     <tr id="google">
        <td><b>Google+ Meta Key Name</b></td>
        <td><input type="text" id="ud_google" name="<?php echo $dud_option_name;?>[ud_google]" 
            value="<?php echo !empty($dud_options['ud_google']) ? esc_attr( $dud_options['ud_google'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr> 

     <tr id="pintrest">
        <td><b>Pinterest Meta Key Name</b></td>
        <td><input type="text" id="ud_pinterest" name="<?php echo $dud_option_name;?>[ud_pinterest]" 
            value="<?php echo !empty($dud_options['ud_pinterest']) ? esc_attr( $dud_options['ud_pinterest'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr> 
	 
	 <tr id="instagram">
        <td><b>Instagram Meta Key Name</b></td>
        <td><input type="text" id="ud_instagram" name="<?php echo $dud_option_name;?>[ud_instagram]" 
            value="<?php echo !empty($dud_options['ud_instagram']) ? esc_attr( $dud_options['ud_instagram'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr> 	
	 
	 <tr id="youtube">
        <td><b>YouTube Meta Key Name</b></td>
        <td><input type="text" id="ud_youtube" name="<?php echo $dud_option_name;?>[ud_youtube]" 
            value="<?php echo !empty($dud_options['ud_youtube']) ? esc_attr( $dud_options['ud_youtube'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr> 	
	 
	 <tr id="tiktok">
        <td><b>Tiktok Meta Key Name</b></td>
        <td><input type="text" id="ud_tiktok" name="<?php echo $dud_option_name;?>[ud_tiktok]" 
            value="<?php echo !empty($dud_options['ud_tiktok']) ? esc_attr( $dud_options['ud_tiktok'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr> 	
	 
	  <tr id="podcast">
        <td><b>Podcast Meta Key Name</b></td>
        <td><input type="text" id="ud_podcast" name="<?php echo $dud_option_name;?>[ud_podcast]" 
            value="<?php echo !empty($dud_options['ud_podcast']) ? esc_attr( $dud_options['ud_podcast'] ) : ""; ?>" maxlength="50"/></td>
        <td></td>
        <td></td>
     </tr> 	
	 
     <tr id="icon_size">
			<td><b>Icon Size</b></td>
			<td><input type="text" size="3" maxlength="3" id="ud_icon_size" name="<?php echo $dud_option_name;?>[ud_icon_size]" 
					value="<?php echo (!empty($dud_options['ud_icon_size'] )) ? esc_attr( $dud_options['ud_icon_size'] ) : "22"; ?>" /> px</td>
			<td></td>
			<td></td>
	</tr>
	
	 <tr id="icon_color">
			<td><b>Icon Link Color</b></td>
			<td><input type="text" name="<?php echo $dud_option_name;?>[ud_icon_color]" 
						value="<?php echo !empty($dud_options['ud_icon_color']) ? esc_attr( $dud_options['ud_icon_color'] ) : ""; ?>" class="cpa-color-picker"></td>
			<td></td>
			<td></td>
	</tr>
	
	<tr id="icon_style">
	    <?php if(empty($dud_options['ud_icon_style'])) $dud_options['ud_icon_style'] = '1';?>
        <td><b>Icon Style</b></td>
        <td>
		
        	<input type="radio" name="<?php echo $dud_option_name;?>[ud_icon_style]" 
        		value="1" <?php checked( '1', $dud_options['ud_icon_style'] ); ?> />
				    <i style="font-size:22px;" class='fab fa-facebook-square'></i>&nbsp;  
				    <i style="font-size:22px;" class='fa-brands fa-square-x-twitter'></i>&nbsp;
					<i style="font-size:22px;" class="fab fa-linkedin"></i>&nbsp;  
					<i style="font-size:22px;" class="fab fa-google-plus-square"></i>&nbsp;  
					<BR><BR>	
					<i style="font-size:22px;" class="fab fa-pinterest-square"></i>&nbsp;  
					<i style="font-size:22px;" class="fab fa-instagram-square"></i>&nbsp;
					<i style="font-size:22px;" class="fab fa-youtube-square"></i>&nbsp;
					<i style="font-size:22px;" class="fab fa-tiktok"></i>&nbsp;
					<i style="font-size:22px;" class="fas fa-podcast"></i>
					<BR><BR><BR>   
        	<input type="radio" name="<?php echo $dud_option_name;?>[ud_icon_style]" 
        		value="2" <?php checked( '2', $dud_options['ud_icon_style'] ); ?> />
				    <i style="font-size:22px;" class="fab fa-facebook-f"></i>&nbsp;  
				    <i style="font-size:22px;" class="fa-brands fa-x-twitter"></i>&nbsp;
					<i style="font-size:22px;" class="fab fa-linkedin-in"></i>&nbsp;  
					<i style="font-size:22px;" class="fab fa-google-plus-g"></i>&nbsp; 
                    <BR><BR>						
					<i style="font-size:22px;" class="fab fa-pinterest-p"></i>&nbsp;  
					<i style="font-size:22px;" class="fab fa-instagram"></i>&nbsp;
					<i style="font-size:22px;" class="fab fa-youtube-square"></i>&nbsp;
					<i style="font-size:22px;" class="fab fa-tiktok"></i>&nbsp;
					<i style="font-size:22px;" class="fas fa-podcast"></i>
					
		</td>
        <td></td>
        <td></td>
     </tr> 
	</table>	
<br/><br/>
</div>
<br/><br/>

<div class="dud-settings-section-header">&nbsp; Layout Settings</div>
<div class="dud-settings-section">
	<table class="form-table">
		<?php if ( in_array( 'dynamic-user-directory-horizontal-layout/dud_horizontal_layout.php' , $dud_plugin_list ) 
						&& $dud_options['ud_display_listings'] !== "vertically") 
		{ ?>
			<tr>
				<td colspan="3" style="line-height:22px;"><b>Instructions</b><br><hr>This is where you configure your horizontal directory layout. 
				The directory formatting may be adjusted to achieve the best fit for your data and theme in the following ways: 
				1) Adjust the listing font size (in the "Listings Display Settings" section), 2) Adjust column padding, 3) Adjust the column widths, 
				4) Check the box to split the email address into two lines 5) Adjust the overall directory width, 
				6) Change the column order. It is recommended to place the directory on a full-size page rather than one with a sidebar if you
				have a lot of columns to display. 
				<hr></td>
			</tr>
		<?php } ?>
		<tr>
			<td><b>Display Order</b></td>
			<td>
				<ul id="sortable"> 
				<?php 
				$sort_order_items = dynamic_ud_sort_order_admin( $dud_options['user_directory_sort_order'] );
				foreach ($sort_order_items as $item)
				{ ?> 
					<li class="sort-order-list-item" id="<?php echo esc_attr($item);?>">
						<div class="sort-order-text"><?php echo esc_attr($item);?></div></li>
		 <?php  } ?>
				 </ul> 
				 <input type="hidden" id="user_directory_sort_order" name="<?php echo $dud_option_name;?>[user_directory_sort_order]" 
					 value="<?php echo esc_attr( $dud_options['user_directory_sort_order'] ); ?>" />
			</td>	
			<td>Drag the list items up or down using your mouse to rearrange the display order. Note that the Sort Field (Last Name or Display Name) 
				will always be the first field shown. For horizontal directories, the order from top to bottom will be shown from left to right (you can show directory listings in a horizontal tabular format with the <a href="http://sgcustomwebsolutions.com/wordpress-plugin-development/" target="_blank">Horizontal Layout Add-on</a>).</td>
			<td></td>
	    </tr>
	</table>
	
		 <?php if ( in_array( 'dynamic-user-directory-horizontal-layout/dud_horizontal_layout.php' , $dud_plugin_list ) ) 
		{ ?>			
			<table class="form-table" id="horizontal-width-settings-3">	 
				<tr id="ud_directory_width">
					<td><b>Directory Width</b></td>
					<td><input class="dd-menu-chk-box-width" type="text" size="3" maxlength="3" id="ud_dir_width" name="<?php echo $dud_option_name;?>[ud_dir_width]" 
							value="<?php echo !empty($dud_options['ud_dir_width']) ? esc_attr( $dud_options['ud_dir_width'] ) : 100; ?>" /> %</td>
					<td>The overall width (in percentage format) of your horizontal directory.</td>
					<td></td>
				</tr>
				
				<tr>
					<td><div id="stripes-n-header-checkboxes-2"><b>Show Row Stripes</b>&nbsp;&nbsp;<input name="<?php echo $dud_option_name;?>[ud_show_table_stripes]" id="ud_show_table_stripes" type="checkbox" value="1" 
						<?php if(!empty($dud_options['ud_show_table_stripes'])) { checked( '1', $dud_options['ud_show_table_stripes'] ); } ?> /></div></td>
					<td> <div id="divider-colors"><b>Row Stripes Color</b></div>
					   <input type="text" name="<?php echo $dud_option_name;?>[ud_table_stripe_color]" 
							value="<?php echo !empty($dud_options['ud_table_stripe_color']) ? esc_attr( $dud_options['ud_table_stripe_color'] ) : ""; ?>" class="cpa-color-picker"></td>
					<td>Display alternating table row stripes of the selected color (a very light shade is recommended).</td>
					<td></td>
				</tr>	
		  </table>
		  
		  <table class="form-table" id="horizontal-width-settings-4">
			<tr>
				<td><div id="stripes-n-header-checkboxes-1"><b>Show Heading Labels</b>&nbsp;&nbsp;<input name="<?php echo $dud_option_name;?>[ud_show_heading_labels]" id="ud_show_heading_labels" type="checkbox" value="1" 
					<?php if(!empty($dud_options['ud_show_heading_labels'])) { checked( '1', $dud_options['ud_show_heading_labels'] ); } ?> /></div></td>
				<td><div id="top"><b>Heading Labels Font Size</b></div><br>
					<input type="text" size="9" maxlength="2" id="ud_heading_fs" name="<?php echo $dud_option_name;?>[ud_heading_fs]" 
						value="<?php echo !empty($dud_options['ud_heading_fs']) ? esc_attr( $dud_options['ud_heading_fs'] ) : ""; ?>" /> px</td>
				<td>Display meta field labels as column headings on your horizontal directory. Note: For single page directories with letter dividers, the column headings will be reprinted under each letter divider.</td>
				<td></td>
			 </tr>
			 
			<tr id="col_width_name">
				<td><b>User Name Col Width</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_width_name" name="<?php echo $dud_option_name;?>[ud_col_width_name]" 
					value="<?php echo !empty($dud_options['ud_col_width_name']) ? esc_attr( $dud_options['ud_col_width_name'] ) : ""; ?>" size="2" maxlength="2"/> %</td>
				<td><div id="user_name_label"><b>User Name Heading Label</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_label_name" name="<?php echo $dud_option_name;?>[ud_col_label_name]" 
					value="<?php echo !empty($dud_options['ud_col_label_name']) ? esc_attr( $dud_options['ud_col_label_name'] ) : ""; ?>" size="2" maxlength="40"/></div></td>
				<td><u>Col Width</u>: the width of each column in percentage format. The total sum of the column widths should not exceed 100%, regardless of the directory width. Default widths will be used for any column left blank.</td>
				<td></td>
			</tr>
			
			<tr id="col_width_email">
				<td><b>Email Col Width</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_width_email" name="<?php echo $dud_option_name;?>[ud_col_width_email]" 
					value="<?php echo !empty($dud_options['ud_col_width_email']) ? esc_attr( $dud_options['ud_col_width_email'] ) : ""; ?>" size="2" maxlength="2"/> %</td>
				<td><div id="email_label"><b>Email Heading Label</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_label_email" name="<?php echo $dud_option_name;?>[ud_col_label_email]" 
					value="<?php echo !empty($dud_options['ud_col_label_email']) ? esc_attr( $dud_options['ud_col_label_email'] ) : ""; ?>" size="2" maxlength="40"/></div></td>
				<td></td>
				<td></td>
			</tr>
			
			<tr id="col_width_website">
				<td><b>Website Col Width</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_width_website" name="<?php echo $dud_option_name;?>[ud_col_width_website]" 
					value="<?php echo !empty($dud_options['ud_col_width_website']) ? esc_attr( $dud_options['ud_col_width_website'] ) : ""; ?>" size="2" maxlength="2"/> %</td>
				<td><div id="website_label"><b>Website Heading Label</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_label_website" name="<?php echo $dud_option_name;?>[ud_col_label_website]" 
					value="<?php echo !empty($dud_options['ud_col_label_website']) ? esc_attr( $dud_options['ud_col_label_website'] ) : ""; ?>" size="2" maxlength="40"/></div></td>
				<td></td>
				<td></td>
			</tr>
			
			<tr id="col_width_date">
				<td><b>Date Registered Col Width</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_width_date" name="<?php echo $dud_option_name;?>[ud_col_width_date]" 
					value="<?php echo !empty($dud_options['ud_col_width_date']) ? esc_attr( $dud_options['ud_col_width_date'] ) : ""; ?>" size="2" maxlength="2"/> %</td>
				<td><div id="date_registered_label"><b>Date Registered Heading Label</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_label_date" name="<?php echo $dud_option_name;?>[ud_col_label_date]" 
					value="<?php echo !empty($dud_options['ud_col_label_date']) ? esc_attr( $dud_options['ud_col_label_date'] ) : ""; ?>" size="2" maxlength="40"/></div></td>
				<td></td>
				<td></td>
			</tr>
			
			<tr id="col_width_user_roles">
				<td><b>User Roles Col Width</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_width_roles" name="<?php echo $dud_option_name;?>[ud_col_width_roles]" 
					value="<?php echo !empty($dud_options['ud_col_width_roles']) ? esc_attr( $dud_options['ud_col_width_roles'] ) : ""; ?>" size="2" maxlength="2"/> %</td>
				<td><div id="roles_label"><b>User Roles Heading Label</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_label_roles" name="<?php echo $dud_option_name;?>[ud_col_label_roles]" 
					value="<?php echo !empty($dud_options['ud_col_label_roles']) ? esc_attr( $dud_options['ud_col_label_roles'] ) : ""; ?>" size="2" maxlength="40"/></div></td>
				<td></td>
				<td></td>
			</tr>
			
			<tr id="col_width_address">
				<td><b>Address Col Width</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_width_address" name="<?php echo $dud_option_name;?>[ud_col_width_address]" 
					value="<?php echo !empty($dud_options['ud_col_width_address']) ? esc_attr( $dud_options['ud_col_width_address'] ) : ""; ?>" size="2" maxlength="2"/> %</td>
				<td><div id="address_label"><b>Address Heading Label</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_label_address" name="<?php echo $dud_option_name;?>[ud_col_label_address]" 
					value="<?php echo !empty($dud_options['ud_col_label_address']) ? esc_attr( $dud_options['ud_col_label_address'] ) : ""; ?>" size="2" maxlength="40"/></div></td>
				<td></td>
				<td></td>
			</tr>
			
			<tr id="col_width_social">
				<td><b>Social Col Width</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_width_social" name="<?php echo $dud_option_name;?>[ud_col_width_social]" 
					value="<?php echo !empty($dud_options['ud_col_width_social']) ? esc_attr( $dud_options['ud_col_width_social'] ) : ""; ?>" size="2" maxlength="2"/> %</td>
				<td><div id="social_label"><b>Social Heading Label</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_label_social" name="<?php echo $dud_option_name;?>[ud_col_label_social]" 
					value="<?php echo !empty($dud_options['ud_col_label_social']) ? esc_attr( $dud_options['ud_col_label_social'] ) : ""; ?>" size="2" maxlength="40"/></div></td>
				<td></td>
				<td></td>
			</tr>
			
			<?php 
				for($inc = 1; $inc < 11; $inc++)
				{ ?> 
					 <tr id="col_width_<?php echo $inc; ?>">
						<td><b>Meta Key <?php echo $inc; ?> Col Width</b><br><input class="dd-menu-chk-box-width" type="text" id="ud_col_width_<?php echo $inc; ?>" name="<?php echo $dud_option_name;?>[ud_col_width_<?php echo $inc; ?>]" 
							value="<?php echo !empty($dud_options['ud_col_width_' . $inc]) ? esc_attr( $dud_options['ud_col_width_' . $inc]) : ""; ?>" size="2" maxlength="2" /> %</td>
						<td><div id="col_meta_label_<?php echo $inc; ?>"><b>Meta Field Label <?php echo $inc; ?></b><br><input type="text" id="ud_col_meta_label_<?php echo $inc; ?>" name="<?php echo $dud_option_name;?>[ud_col_meta_label_<?php echo $inc; ?>]" 
							value="<?php echo !empty($dud_options['ud_col_meta_label_' . $inc]) ? esc_attr( $dud_options['ud_col_meta_label_' . $inc] ) : ""; ?>" maxlength="50"/>  
						</div></td>
						<td></td>
						<td></td>	
					 </tr>
		  <?php } ?> <!--end for loop-->
		    <tr>
				<td><div id="top"><b>Col Padding Top</b></div><br>
				
				<?php {
					  $col_padding_top = $dud_options['ud_table_cell_padding_top'];
					  $col_padding_bottom = $dud_options['ud_table_cell_padding_bottom'];
				} ?>
					<input type="text" size="9" maxlength="2" id="ud_table_cell_padding_top" name="<?php echo $dud_option_name;?>[ud_table_cell_padding_top]" 
						value="<?php echo !empty($col_padding_top) || $col_padding_top === "0" ? esc_attr( $dud_options['ud_table_cell_padding_top'] ) : ""; ?>" /> px
				</td>
				<td><div id="top"><b>Col Padding Bottom</b></div><BR>
					<input type="text" size="9" maxlength="2" id="ud_table_cell_padding_bottom" name="<?php echo $dud_option_name;?>[ud_table_cell_padding_bottom]" 
						value="<?php echo !empty($col_padding_bottom) || $col_padding_bottom === "0" ? esc_attr( $dud_options['ud_table_cell_padding_bottom'] ) : ""; ?>" /> px
				</td>
				<td>How much space (in pixels) to pad around the top and bottom of each column in the horizontal display.</td>
				<td></td>
			</tr>
			<?php {
				  $col_padding_left = $dud_options['ud_table_cell_padding_left'];
			      $col_padding_right = $dud_options['ud_table_cell_padding_right']; 
			} ?>
			<tr>
				<td><div id="top"><b>Col Padding Left</b></div><br>
					<input type="text" size="9" maxlength="2" id="ud_table_cell_padding_left" name="<?php echo $dud_option_name;?>[ud_table_cell_padding_left]" 
						value="<?php echo !empty($col_padding_left) || $col_padding_left === "0" ? esc_attr( $dud_options['ud_table_cell_padding_left'] ) : ""; ?>" /> px
				</td>
				<td><div id="top"><b>Col Padding Right</b></div><BR>
					<input type="text" size="9" maxlength="2" id="ud_table_cell_padding_right" name="<?php echo $dud_option_name;?>[ud_table_cell_padding_right]" 
						value="<?php echo !empty($col_padding_right) || $col_padding_right === "0" ? esc_attr( $dud_options['ud_table_cell_padding_right'] ) : ""; ?>" /> px
				</td>
				<td>How much space (in pixels) to pad around the left and right of each column in the horizontal display.</td>
				<td></td>
			</tr>
			
			<tr>
				<td><b>Split WordPress Email Field Into 2 Lines</b>&nbsp;&nbsp;</td>
				<td><input name="<?php echo $dud_option_name;?>[ud_break_email]" id="ud_break_email" type="checkbox" value="1" 
					<?php if(!empty($dud_options['ud_break_email'])) { checked( '1', $dud_options['ud_break_email'] ); } ?> /></td>
				<td>If you are showing the WordPress profile email address in your directory, you can check this box to conserve space by neatly formatting it into two lines. E.g."JohnathanDoe@longwebsitename.com" will be shown as<br><br> JohnathanDoe<br>@longwebsitename.com</td>
				<td></td>
			</tr>	
			<tr>
				<td><b>Responsive Styling for 601px - 767px</b></td>
				<td>
					<input type="radio" name="<?php echo $dud_option_name;?>[ud_horizontal_responsive_601_767]" 
						value="fixed" <?php if(!empty($dud_options['ud_horizontal_responsive_601_767'])) { checked( 'fixed', $dud_options['ud_horizontal_responsive_601_767'] ); } else {checked( 'fixed', 'fixed' );} ?> />Horizontal fixed width table<br>         	
					<input type="radio" name="<?php echo $dud_option_name;?>[ud_horizontal_responsive_601_767]" 
						value="vertical" <?php if(!empty($dud_options['ud_horizontal_responsive_601_767'])) { checked( 'vertical', $dud_options['ud_horizontal_responsive_601_767'] ); } ?> />Vertical Layout</td>
				<td>The default fixed width table ensures that all columns in your horizontal directory are shown legibly at smaller screen sizes. However, this makes all column widths equal and data will be pushed downward as columns narrow. This may not be visually appealing if you have a lot of columns. In this case you can choose "vertical layout" for these screen sizes.  </td>
				<td></td>
			 </tr> 
			 <tr>
				<td><b>Responsive Styling for 768px - 1024px</b></td>
				<td>
					<input type="radio" name="<?php echo $dud_option_name;?>[ud_horizontal_responsive_768_1024]" 
						value="fixed" <?php if(!empty($dud_options['ud_horizontal_responsive_768_1024'])) { checked( 'fixed', $dud_options['ud_horizontal_responsive_768_1024'] ); } else {checked( 'fixed', 'fixed' );} ?> />Horizontal fixed width table<br>         	
					<input type="radio" name="<?php echo $dud_option_name;?>[ud_horizontal_responsive_768_1024]" 
						value="vertical" <?php if(!empty($dud_options['ud_horizontal_responsive_768_1024'])) { checked( 'vertical', $dud_options['ud_horizontal_responsive_768_1024'] ); } ?> />Vertical Layout</td>
				<td></td>
				<td></td>
			 </tr> 		
		  </table>
		 
  <?php } ?>
		
<br/><br/>
</div>

<?php if ( in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $dud_plugin_list ) ) 
 { ?>
<br/><br/>
<div class="dud-settings-section-header">&nbsp; Meta Fields Search Settings</div>
<div class="dud-settings-section">
	<table class="form-table">
            <tr>
				<td colspan="3" style="line-height:22px;"><b>Instructions</b><br><hr>Enter up to fifteen user meta search fields in addition to the last name/display name. If there is only one total search field, the label for that field will appear as placeholder text in the search input box at the top of your directory. 
				If there are two or more total search fields, the labels for these fields will be shown in a dropdown box next to the search input box at the top of your directory. Note: the "Directory Search" box *must* be checked under the "Main Directory Settings" section.<hr></td>
			</tr>
			<tr>
				<td><b>General Search of All Fields</b></td>
				<td><input name="<?php echo $dud_option_name;?>[ud_general_srch]" id="ud_general_srch" type="checkbox" 
				   value="1" <?php if(!empty($dud_options['ud_general_srch'])) { checked( '1', $dud_options['ud_general_srch'] ); } ?> />
				</td>
				<td>This hides the meta field selector dropdown on the front end and automatically looks for the search value within ALL the meta fields configured below. 
                    Please note: if you have an alpha links directory, the "Show Search Results" setting below *must* be "Single Page Format."</td>
				<td></td>
			</tr>	
			<tr>
				<td><b>WordPress Last Name/Display Name</b></td>
				<td>
					<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_show_last_name_srch_fld]" id="ud_show_last_name_srch_fld">
						<OPTION value="first" <?php echo (!empty($dud_options['ud_show_last_name_srch_fld']) && $dud_options['ud_show_last_name_srch_fld'] == "first") ? "SELECTED" : ""; ?>>Show</OPTION> 
						<OPTION value="never" <?php echo (!empty($dud_options['ud_show_last_name_srch_fld']) && $dud_options['ud_show_last_name_srch_fld'] == "never") ? "SELECTED" : ""; ?>>Hide</OPTION> 
					</select> 
				</td>
				<td>Choose whether to show the user's Last Name / Display Name as a search field. To search on first name, simply enter the standard WordPress "first_name" meta key name below.</td>
				<td></td>
			</tr>
			<tr>
				<td><b>WordPress Email Address Field</b></td>
				<td>
					<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_wp_email_addr_fld]" id="ud_wp_email_addr_fld">
						<OPTION value="hide" <?php echo (!empty($dud_options['ud_wp_email_addr_fld']) && $dud_options['ud_wp_email_addr_fld'] == "hide") ? "SELECTED" : ""; ?>>Hide</OPTION> 
						<OPTION value="show" <?php echo (!empty($dud_options['ud_wp_email_addr_fld']) && $dud_options['ud_wp_email_addr_fld'] == "show") ? "SELECTED" : ""; ?>>Show</OPTION> 
					</select> 
				</td>
				<td>Choose whether to show the user's WordPress email address as a search field. If you have multiple search fields, this one will be shown last.</td>
				<td></td>
			</tr> 
			<tr>
				<td><b><span style='color:#08788c;'># OF META SEARCH FIELDS</span></b></td>
				<td>
					<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[user_directory_num_meta_srch_flds]" id="user_directory_num_meta_srch_flds">
						<OPTION value="1" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "1") ? "SELECTED" : ""; ?>>1</OPTION> 
						<OPTION value="2" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "2") ? "SELECTED" : ""; ?>>2</OPTION> 
						<OPTION value="3" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "3") ? "SELECTED" : ""; ?>>3</OPTION> 
						<OPTION value="4" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "4") ? "SELECTED" : ""; ?>>4</OPTION> 
						<OPTION value="5" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "5") ? "SELECTED" : ""; ?>>5</OPTION> 
						<OPTION value="6" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "6") ? "SELECTED" : ""; ?>>6</OPTION>
						<OPTION value="7" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "7") ? "SELECTED" : ""; ?>>7</OPTION>
						<OPTION value="8" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "8") ? "SELECTED" : ""; ?>>8</OPTION>
						<OPTION value="9" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "9") ? "SELECTED" : ""; ?>>9</OPTION>
						<OPTION value="10" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "10") ? "SELECTED" : ""; ?>>10</OPTION> 
						<OPTION value="11" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "11") ? "SELECTED" : ""; ?>>11</OPTION> 
						<OPTION value="12" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "12") ? "SELECTED" : ""; ?>>12</OPTION> 
						<OPTION value="13" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "13") ? "SELECTED" : ""; ?>>13</OPTION> 
						<OPTION value="14" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "14") ? "SELECTED" : ""; ?>>14</OPTION> 
						<OPTION value="15" <?php echo (!empty($dud_options['user_directory_num_meta_srch_flds']) && $dud_options['user_directory_num_meta_srch_flds'] == "15") ? "SELECTED" : ""; ?>>15</OPTION> 
					</select> 
				</td>
				<td>Select the number of meta fields you want to permit users to search on.</td>
				<td></td>
			</tr>
			 <tr id="gen_srch_placeholder_txt">
				<td><b>Placeholder Text</b></td>
				<td><input class="meta-flds-srch-key-input" type="text" id="ud_general_srch_placeholder_txt" name="<?php echo $dud_option_name;?>[ud_general_srch_placeholder_txt]" 
					value="<?php echo !empty($dud_options['ud_general_srch_placeholder_txt']) ? esc_attr( $dud_options['ud_general_srch_placeholder_txt'] ) : ""; ?>" maxlength="50"/></div></td>
				<td>Customize the search box placeholder text for a general search. Leave blank for no placeholder text.</td>
				<td></td>
			</tr>
			</table>
			<table class="search-meta-flds">
				<tr>
				<td colspan="5" style="line-height:22px;"><b>Search Meta Fields Configuration</b><br>
				<i>For a General Search, check the "General Search" box above and then simply enter the meta key names of the fields to be searched in below. To allow searching on Last Name/Display Name or Email Address for a general search, select "show" in the dropdowns above. If not doing a General Search, enter the Meta Key Name (required), Meta Field Label (required), Search Type (required), Search Dropdown Values (optional), and Search Dropdown Labels (optional) for each field below.<BR><BR><u><i>Search Type</u></i>: Specify whether the meta field should start with, contain, or exactly match the search value entered. 
				<u><i>Search Dropdown Values</u></i>: Configure a predefined list of values to create a dropdown box that will replace the regular search input box. These are the values the search will look for in the database. <u><i>Search Dropdown Labels</u></i>: Configure a predefined list of labels for the search dropdown box. These are the options the viewer will see. Both the Search Dropdown Values and Labels should be entered with each one <b>separated by a comma</b> and should <b>NOT</b> be surrounded by quotes. Example Values or Labels entry: Red,Green,Blue.</i> 
				</td>
				</tr>
				<?php 
						for($inc = 1; $inc < 16; $inc++)
						{
							$dud_srch_fld_name      =  !empty($dud_options['user_directory_meta_srch_field_' . $inc]) ? $dud_options['user_directory_meta_srch_field_' . $inc] : null;
							$dud_srch_fld_label     =  !empty($dud_options['user_directory_meta_srch_label_' . $inc]) ? $dud_options['user_directory_meta_srch_label_' . $inc] : null;
							$dud_srch_fld_dd_values =  !empty($dud_options['user_directory_meta_srch_dd_values_' . $inc]) ? $dud_options['user_directory_meta_srch_dd_values_' . $inc] : null;
							$dud_srch_fld_dd_labels =  !empty($dud_options['user_directory_meta_srch_dd_labels_' . $inc]) ? $dud_options['user_directory_meta_srch_dd_labels_' . $inc] : null;
							$dud_srch_type          =  !empty($dud_options['user_directory_meta_srch_type_' . $inc]) ? $dud_options['user_directory_meta_srch_type_' . $inc] : null;
							$dud_cimy_flag          =  !empty($dud_options['ud_meta_srch_cimy_flag_' . $inc]) ? $dud_options['ud_meta_srch_cimy_flag_' . $inc] : null;
							$dud_bp_flag            =  !empty($dud_options['ud_meta_srch_bp_flag_' . $inc]) ? $dud_options['ud_meta_srch_bp_flag_' . $inc] : null;
						    ?>
							<tr id="meta_srch_fld_<?php echo $inc; ?>">
								<td><b>*Meta Key Name <?php echo $inc; ?></b><br><input class="meta-flds-srch-key-input" type="text" id="user_directory_meta_srch_field_<?php echo $inc; ?>" 
									name="<?php echo $dud_option_name;?>[user_directory_meta_srch_field_<?php echo $inc; ?>]" value="<?php echo esc_attr( $dud_srch_fld_name ); ?>" maxlength="75"/>
									<input type="hidden" id="ud_meta_srch_cimy_flag_<?php echo $inc; ?>" name="<?php echo $dud_option_name;?>[ud_meta_srch_cimy_flag_<?php echo $inc; ?>]" 
										value="<?php echo esc_attr( $dud_cimy_flag ); ?>" maxlength="2"/>
									<input type="hidden" id="ud_meta_srch_bp_flag_<?php echo $inc; ?>" name="<?php echo $dud_option_name;?>[ud_meta_srch_bp_flag_<?php echo $inc; ?>]" 
										value="<?php echo esc_attr( $dud_bp_flag ); ?>" maxlength="2"/>
								</td>
								<td id="meta_srch_lbl_<?php echo $inc; ?>"><b>*Meta Field Label <?php echo $inc; ?></b><br><input class="meta-flds-srch-label-input" type="text" id="user_directory_meta_srch_label_<?php echo $inc; ?>" name="<?php echo $dud_option_name;?>[user_directory_meta_srch_label_<?php echo $inc; ?>]" 
									value="<?php echo esc_attr( $dud_srch_fld_label ); ?>" maxlength="75"/>
									
								</td>		
								<td id="search_type_<?php echo $inc; ?>">
									<b>*Search Type <select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[user_directory_meta_srch_type_<?php echo $inc; ?>]" id="user_directory_meta_srch_type_<?php echo $inc; ?>">
										<OPTION value="contains" <?php echo ($dud_srch_type == "contains") ? "SELECTED" : ""; ?>>Contains search value</OPTION> 
										<OPTION value="starts" <?php echo ($dud_srch_type == "starts") ? "SELECTED" : ""; ?>>Starts with search value</OPTION> 
										<OPTION value="exact" <?php echo ($dud_srch_type == "exact") ? "SELECTED" : ""; ?>>Matches search value</OPTION> 
									</select> 
								</td>	
								<td id="dd_option_values_<?php echo $inc; ?>"><b>Search Dropdown Values <?php echo $inc; ?></b><br><input class="meta-flds-srch-key-input" type="text" id="user_directory_meta_srch_dd_values_<?php echo $inc; ?>" 
									name="<?php echo $dud_option_name;?>[user_directory_meta_srch_dd_values_<?php echo $inc; ?>]" value="<?php echo esc_attr( $dud_srch_fld_dd_values ); ?>" maxlength="1000"/>
								</td>
								<td id="dd_option_labels_<?php echo $inc; ?>"><b>Search Dropdown Labels <?php echo $inc; ?></b><br><input class="meta-flds-srch-key-input" type="text" id="user_directory_meta_srch_dd_labels_<?php echo $inc; ?>" 
									name="<?php echo $dud_option_name;?>[user_directory_meta_srch_dd_labels_<?php echo $inc; ?>]" value="<?php echo esc_attr( $dud_srch_fld_dd_labels ); ?>" maxlength="1000"/>
								</td>
								
							</tr>
						
				  <?php } ?>
				<tr><td></td></tr>
			</table>
			<table class="form-table">	
			<tr>
					<td><b>CSS Search Box Styling</b></td>
					<td>
						<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_meta_srch_legacy_style]" id="ud_meta_srch_legacy_style">
							<OPTION value="legacy" <?php echo (!empty($dud_options['ud_meta_srch_legacy_style']) && $dud_options['ud_meta_srch_legacy_style'] == "legacy") ? "SELECTED" : ""; ?>>Legacy Search Box Style</OPTION>
							<OPTION value="new" <?php echo (!empty($dud_options['ud_meta_srch_legacy_style']) && $dud_options['ud_meta_srch_legacy_style'] == "new") ? "SELECTED" : ""; ?>>New Search Box Style</OPTION> 
						</select> 
					</td>
					<td>The new CSS style stacks the search boxes instead of placing them side by side. You can also choose the search field widths for greater flexibility. IMPORTANT NOTE: If you have configured pre-defined search dropdown values above, the new CSS style will be applied *automatically* and may not be changed to legacy.</td>
					<td></td>
			</tr>
			<tr id="srch_container_width">
					<td><b>Meta Field Label Dropdown Width</b></td>
					<td>
						<input type="text" id="ud_meta_srch_container_width" name="<?php echo $dud_option_name;?>[ud_meta_srch_container_width]" 
								value="<?php echo !empty($dud_options['ud_meta_srch_container_width']) ? esc_attr( $dud_options['ud_meta_srch_container_width'] ) : "60"; ?>" maxlength="3"/> %
					</td>
					<td>The percentage width of the meta field label dropdown shown next to the search input box. Default width is 60%.</td>
					<td></td>
			</tr>
			<tr id="srch_container_width_2">
					<td><b>Search Input Box Width</b></td>
					<td>
						<input type="text" id="ud_meta_srch_input_width" name="<?php echo $dud_option_name;?>[ud_meta_srch_input_width]" 
								value="<?php echo !empty($dud_options['ud_meta_srch_input_width']) ? esc_attr( $dud_options['ud_meta_srch_input_width'] ) : "53"; ?>" maxlength="3"/> %
					</td>
					<td>The percentage width of the search input box. Default width is 53%.</td>
					<td></td>
			</tr>
			<tr id="srch_container_width_3">
					<td><b>Search Dropdown Width</b></td>
					<td>
						<input type="text" id="ud_meta_srch_dropdown_width" name="<?php echo $dud_option_name;?>[ud_meta_srch_dropdown_width]" 
								value="<?php echo !empty($dud_options['ud_meta_srch_dropdown_width']) ? esc_attr( $dud_options['ud_meta_srch_dropdown_width'] ) : "51"; ?>" maxlength="3"/> %
					</td>
					<td>The percentage width of the search dropdown box. This only applies if you have configured search dropdown options above. Default width is 51%.</td>
					<td></td>
			</tr>
			<tr id="show_srch_results">
					<td><b>Show Search Results</b></td>
					<td>
						<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_show_srch_results]" id="ud_show_srch_results">
							<OPTION value="alpha-links" <?php echo (!empty($dud_options['ud_show_srch_results']) && $dud_options['ud_show_srch_results'] == "alpha-links") ? "SELECTED" : ""; ?>>Letter Links Format</OPTION>
							<OPTION value="single-page" <?php echo (!empty($dud_options['ud_show_srch_results']) && $dud_options['ud_show_srch_results'] == "single-page") ? "SELECTED" : ""; ?>>Single Page Format</OPTION> 
						</select> 
					</td>
					<td>The search results may be displayed either on a single page or by alphabet letter links. If page load time is an issue, select 'Letter Links Format' for improved performance.</td>
					<td></td>
			</tr>
			 <tr>
				<td><b>Search Icon Color</b></td>
				<td>
					<select class="dd-menu-no-chk-box-width" name="<?php echo $dud_option_name;?>[ud_srch_icon_color]" id="ud_srch_icon_color">
						<OPTION value="dimgray" <?php echo (!empty($dud_options['ud_srch_icon_color']) && $dud_options['ud_srch_icon_color'] == "DimGray") ? "SELECTED" : ""; ?>>DimGray</OPTION> 
						<OPTION value="white" <?php echo (!empty($dud_options['ud_srch_icon_color']) && $dud_options['ud_srch_icon_color'] == "white") ? "SELECTED" : ""; ?>>White</OPTION> 
					</select> 
				</td>
				<td>Choose the color of the magnifying glass icon on the Search button.</td>
				<td></td>
			 </tr>
			 <tr>
				<td><b>Show 'Clear' link</b></td>
				<td><input name="<?php echo $dud_option_name;?>[ud_clear_search]" id="ud_clear_search" type="checkbox" 
				   value="1" <?php if(!empty($dud_options['ud_clear_search'])) { checked( '1', $dud_options['ud_clear_search'] ); } ?> />
				</td>
				<td>Check this box to show a 'Clear' link next to the search box. This provides an easy way to clear the search box and refresh the directory.</td>
				<td></td>
			 </tr>	
	</table>	
<br/><br/>
</div>
<?php } ?>
<script type="text/javascript">jQuery(document).ready(function() {jQuery('.js-example-basic-multiple').select2();});</script>    
<?php submit_button('Save options', 'primary', 'user_directory_options_submit'); ?>

 </form>
</div>
<?php
}

/*** Settings Link on Plugin Management Screen ************************************/

function user_directory_settings_link($actions, $file) {

if(false !== strpos($file, 'user-directory'))
 $actions['settings'] = '<a href="options-general.php?page=user_directory">Settings</a>';
return $actions; 
}
add_filter('plugin_action_links', 'user_directory_settings_link', 2, 2);

/*** Register Settings on Page Init ***********************************************/

function user_directory_settings_init(){
	
	$dud_plugin_list = get_option('active_plugins');

	if ( in_array( 'dynamic-user-directory-multiple-dirs/dynamic-user-directory-multiple-dirs.php' , $dud_plugin_list )) 
		do_action('dud_register_loaded_directory_setting');
	else
		register_setting( 'user_directory_options', 'dud_plugin_settings', 'dynamic_ud_validate');
	
}
add_action('admin_init', 'user_directory_settings_init');

/*** Validation Functions ***********************************************************/ 

function dynamic_ud_validate( $input ) 
{
    //var_dump($_POST);
    $dud_option_name = 'dud_plugin_settings';
	$dud_plugin_list = get_option('active_plugins');
	$found_error = false;
		
	if ( in_array( 'dynamic-user-directory-multiple-dirs/dynamic-user-directory-multiple-dirs.php' , $dud_plugin_list )) 
	{
		for($inc=0; $inc <= 99; $inc++) 
		{
			if(!empty($_POST['dud_plugin_settings_' . ($inc+1)])) 
			{	
				$dud_option_name = 'dud_plugin_settings_' . ($inc+1);
				add_option('dud_updated_settings', $dud_option_name  );
				break;
			}
		}
	}
		
    $input['user_directory_border_color'] = dynamic_ud_validate_hex( $input['user_directory_border_color'], $dud_option_name );
    if($input['user_directory_border_color'] === null) return get_option( $dud_option_name );

    $input['ud_letter_divider_font_color'] = dynamic_ud_validate_hex( $input['ud_letter_divider_font_color'], $dud_option_name );
    if($input['ud_letter_divider_font_color'] === null) return get_option( $dud_option_name );
    
    $input['ud_letter_divider_fill_color'] = dynamic_ud_validate_hex( $input['ud_letter_divider_fill_color'], $dud_option_name );
    if($input['ud_letter_divider_fill_color'] === null) return get_option( $dud_option_name );
	
	if(in_array( 'dynamic-user-directory-horizontal-layout/dud_horizontal_layout.php' , $dud_plugin_list ) && $input['ud_display_listings'] === 'horizontally')
	{
		$input['ud_col_label_name']    = sanitize_text_field($input['ud_col_label_name']);
		$input['ud_col_label_address'] = sanitize_text_field($input['ud_col_label_address']);
		$input['ud_col_label_email']   = sanitize_text_field($input['ud_col_label_email']);
		$input['ud_col_label_website'] = sanitize_text_field($input['ud_col_label_website']);
		$input['ud_col_label_social']  = sanitize_text_field($input['ud_col_label_social']);
		
		for($inc = 1; $inc < 11; $inc++)
		{ 
			$input['ud_col_width_' . $inc]      = sanitize_text_field($input['ud_col_width_' . $inc]);
			$input['ud_col_meta_label_' . $inc] = sanitize_text_field($input['ud_col_meta_label_' . $inc]);
		}	
		
		$input['ud_col_width_name']    = sanitize_text_field($input['ud_col_width_name']);
		$input['ud_col_width_address'] = sanitize_text_field($input['ud_col_width_address']);
		$input['ud_col_width_email']   = sanitize_text_field($input['ud_col_width_email']);
		$input['ud_col_width_website'] = sanitize_text_field($input['ud_col_width_website']);
		$input['ud_col_width_social']  = sanitize_text_field($input['ud_col_width_social']);
		
		$input['ud_facebook']          = sanitize_text_field($input['ud_facebook']);
		$input['ud_twitter']           = sanitize_text_field($input['ud_twitter']);
		$input['ud_linkedin']          = sanitize_text_field($input['ud_linkedin']);
		$input['ud_google']            = sanitize_text_field($input['ud_google']);
		$input['ud_instagram']         = sanitize_text_field($input['ud_instagram']);
		$input['ud_pinterest']         = sanitize_text_field($input['ud_pinterest']);
		
		if(!empty($input['ud_show_table_stripes']))
		{
			$input['user_directory_listing_spacing'] = "0";
		}		
		
		if(!empty($input['ud_table_stripe_color'])) 
		{
			$input['ud_table_stripe_color'] = dynamic_ud_validate_hex( $input['ud_table_stripe_color'], $dud_option_name );
			if($input['ud_table_stripe_color'] === null) return get_option( $dud_option_name );
		}
		if(!empty($input['ud_divider_border_color'])) 
		{
			$input['ud_divider_border_color'] = dynamic_ud_validate_hex( $input['ud_divider_border_color'], $dud_option_name );
			if($input['ud_divider_border_color'] === null) return get_option( $dud_option_name );
		}
		if(!empty($input['ud_divider_font_size'])) 
		{
			$input['ud_divider_font_size'] = dynamic_ud_check_numeric( (!empty($input['ud_divider_font_size']) ? $input['ud_divider_font_size'] : ""), $dud_option_name );
			if($input['ud_divider_font_size'] === null) return get_option( $dud_option_name );
		}
		if(!empty($input['ud_table_cell_padding_top'])) 
		{
			$input['ud_table_cell_padding_top'] = dynamic_ud_check_numeric( $input['ud_table_cell_padding_top'], $dud_option_name );
			if($input['ud_table_cell_padding_top'] === null) return get_option( $dud_option_name );
		}
		if(!empty($input['ud_table_cell_padding_bottom'])) 
		{
			$input['ud_table_cell_padding_bottom'] = dynamic_ud_check_numeric( $input['ud_table_cell_padding_bottom'], $dud_option_name );
			if($input['ud_table_cell_padding_bottom'] === null) return get_option( $dud_option_name );
		}
		if(!empty($input['ud_table_cell_padding_left'])) 
		{
			$input['ud_table_cell_padding_left'] = dynamic_ud_check_numeric( $input['ud_table_cell_padding_left'], $dud_option_name );
			if($input['ud_table_cell_padding_left'] === null) return get_option( $dud_option_name );
		}
		if(!empty($input['ud_table_cell_padding_right'])) 
		{
			$input['ud_table_cell_padding_right'] = dynamic_ud_check_numeric( $input['ud_table_cell_padding_right'], $dud_option_name );
			if($input['ud_table_cell_padding_right'] === null) return get_option( $dud_option_name );
		}
		if(!empty($input['ud_heading_fs']))
		{
			$input['ud_heading_fs'] = dynamic_ud_check_numeric( $input['ud_heading_fs'], $dud_option_name );
			if($input['ud_heading_fs'] === null) return get_option( $dud_option_name );
		}
	} 
	
	if(!empty($input['ud_avatar_padding'])) 
	{
		$input['ud_avatar_padding'] = dynamic_ud_check_numeric( $input['ud_avatar_padding'], $dud_option_name );
		if($input['ud_avatar_padding'] === null) return get_option( $dud_option_name );
	}
	
	if(!empty($input['user_directory_avatar_size']))
	{	
		$input['user_directory_avatar_size'] = dynamic_ud_check_numeric( $input['user_directory_avatar_size'], $dud_option_name );
		if($input['user_directory_avatar_size'] === null) return get_option( $dud_option_name );
	}
			
	if(!empty($input['ud_pagination_font_size']))
	{	
		$input['ud_pagination_font_size'] = dynamic_ud_check_numeric( $input['ud_pagination_font_size'], $dud_option_name );
		if($input['ud_pagination_font_size'] === null) return get_option( $dud_option_name );
	}
	if(!empty($input['ud_users_per_page']))
	{	
		$input['ud_users_per_page'] = dynamic_ud_check_numeric( $input['ud_users_per_page'], $dud_option_name, 'ud_users_per_page' );
		if($input['ud_users_per_page'] === null) return get_option( $dud_option_name );
	}
	if(!empty($input['ud_pagination_link_color'])) 
	{
		$input['ud_pagination_link_color'] = dynamic_ud_validate_hex( $input['ud_pagination_link_color'], $dud_option_name );
		if($input['ud_pagination_link_color'] === null) return get_option( $dud_option_name );
	}
	if(!empty($input['ud_pagination_link_clicked_color'])) 
	{
		$input['ud_pagination_link_clicked_color'] = dynamic_ud_validate_hex( $input['ud_pagination_link_clicked_color'], $dud_option_name );
		if($input['ud_pagination_link_clicked_color'] === null) return get_option( $dud_option_name );
	}
	
	if(!empty($input['ud_alpha_link_color'])) 
	{
		$input['ud_alpha_link_color'] = dynamic_ud_validate_hex( $input['ud_alpha_link_color'], $dud_option_name );
		if($input['ud_alpha_link_color'] === null) return get_option( $dud_option_name );
	}
	
	if(!empty($input['ud_alpha_link_clicked_color'])) 
	{
		$input['ud_alpha_link_clicked_color'] = dynamic_ud_validate_hex( $input['ud_alpha_link_clicked_color'], $dud_option_name );
		if($input['ud_alpha_link_clicked_color'] === null) return get_option( $dud_option_name );
	}
			
	if(empty($input['ud_txt_after_num_users'])) $input['ud_txt_after_num_users'] = "total members";
	
	if(empty($input['ud_txt_after_num_users_srch'])) $input['ud_txt_after_num_users_srch'] = "search results";
	
    $input['user_directory_letter_fs'] = dynamic_ud_check_numeric( $input['user_directory_letter_fs'], $dud_option_name );
    if($input['user_directory_letter_fs'] === null) return get_option( $dud_option_name );
    
    $input['ud_alpha_link_spacer'] = dynamic_ud_check_numeric( $input['ud_alpha_link_spacer'], $dud_option_name );
    if($input['ud_alpha_link_spacer'] === null) return get_option( $dud_option_name );
    
    $input['user_directory_listing_fs'] = dynamic_ud_check_numeric( $input['user_directory_listing_fs'], $dud_option_name );
    if($input['user_directory_listing_fs'] === null) return get_option( $dud_option_name );
    
    $input['user_directory_listing_spacing'] = dynamic_ud_check_numeric( $input['user_directory_listing_spacing'], $dud_option_name );
    if($input['user_directory_listing_spacing'] === null) return get_option( $dud_option_name );
    			
    $input['user_directory_addr_1'] = sanitize_text_field($input['user_directory_addr_1']);
    $input['user_directory_addr_2'] = sanitize_text_field($input['user_directory_addr_2']);
    $input['user_directory_city']   = sanitize_text_field($input['user_directory_city']);
    $input['user_directory_state']  = sanitize_text_field($input['user_directory_state']);
    $input['user_directory_zip']    = sanitize_text_field($input['user_directory_zip']);
    
	for($inc = 1; $inc < 11; $inc++)
	{ 
		$input['user_directory_meta_field_' . $inc] = sanitize_text_field($input['user_directory_meta_field_' . $inc]);
		$input['user_directory_meta_label_' . $inc] = sanitize_text_field($input['user_directory_meta_label_' . $inc]);
		
		if($input['user_directory_meta_label_' . $inc])
		{
			if ($input['user_directory_meta_label_' . $inc][0] === '#')
			{
				if(strlen($input['user_directory_meta_label_' . $inc]) > 1)
					$input['user_directory_meta_label_' . $inc] = substr($input['user_directory_meta_label_' . $inc], 1);
				else
					$input['user_directory_meta_label_' . $inc] = "";
				
				$input['user_directory_meta_link_' . $inc] = '#';
			}
		}
	}	
   
	if ( in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $dud_plugin_list ) ) 
	{
		$found_srch_fld = false;
		
		//Clear out the flag fields...
		for($inc = 1; $inc < 16; $inc++)
		{ 
			$input['ud_meta_srch_cimy_flag_'. $inc] = null;
			$input['ud_meta_srch_bp_flag_'. $inc] = null;
		}
		
		$dynamic_srch_box = false;
		
		//Now determine the new flag fields
		for($inc = 1; $inc < 16; $inc++)
		{ 
			$input['user_directory_meta_srch_field_' . $inc] = sanitize_text_field($input['user_directory_meta_srch_field_' . $inc]);
			$input['user_directory_meta_srch_label_'. $inc] = sanitize_text_field($input['user_directory_meta_srch_label_' . $inc]);
			$input['user_directory_meta_srch_dd_values_' . $inc] = sanitize_text_field($input['user_directory_meta_srch_dd_values_' . $inc]);
			$input['user_directory_meta_srch_dd_labels_' . $inc] = sanitize_text_field($input['user_directory_meta_srch_dd_labels_' . $inc]);
			$input['ud_meta_srch_cimy_flag_'. $inc] = dud_check_cimy_field($input['user_directory_meta_srch_field_' . $inc]);
			$input['ud_meta_srch_bp_flag_'. $inc] = dud_check_bp_field($input['user_directory_meta_srch_field_' . $inc]);		
			
			if(!empty($input['user_directory_meta_srch_field_' . $inc])) $found_srch_fld = true;
			
			if($found_srch_fld)
			{
				try
				{
					if(!empty($input['user_directory_meta_srch_dd_labels_' . $inc]))
					{
						$dd_srch_fld_labels = explode(",", $input['user_directory_meta_srch_dd_labels_' . $inc]);
					
						if(!is_array($dd_srch_fld_labels))
						{
							add_settings_error( $dud_option_name, 'user_directory_meta_srch_dd_labels', 'An invalid Dropdown Labels list was entered for Meta Search Field ' . $inc, 'error' ); 
							return get_option( $dud_option_name );
						}
					}
				}
				catch(Exception $e)
				{
					add_settings_error( $dud_option_name, 'user_directory_meta_srch_dd_labels', 'An invalid Dropdown Labels list was entered for Meta Search Field ' . $inc, 'error' ); 
					return get_option( $dud_option_name );
				}
				
				try
				{
					if(!empty($input['user_directory_meta_srch_dd_values_' . $inc]))
					{
						$dd_srch_fld_values = explode(",", $input['user_directory_meta_srch_dd_values_' . $inc]);
						
						if(!is_array($dd_srch_fld_values))
						{
							add_settings_error( $dud_option_name, 'user_directory_meta_srch_dd_values', 'An invalid Dropdown Values list was entered for Meta Search Field ' . $inc, 'error' ); 
							return get_option( $dud_option_name );
						}
					}
				}
				catch(Exception $e)
				{
					add_settings_error( $dud_option_name, 'user_directory_meta_srch_dd_labels', 'An invalid Dropdown Labels list was entered for Meta Search Field ' . $inc, 'error' ); 
					return get_option( $dud_option_name );
				}
				
				if(!empty($dd_srch_fld_values) && !empty($dd_srch_fld_labels))
				{
					if(count($dd_srch_fld_values)!== count($dd_srch_fld_labels))
					{
						add_settings_error( $dud_option_name, 'user_directory_meta_srch_dd_labels', 'Error for Meta Search Field ' . $inc . ': Dropdown Option Labels had ' . count($dd_srch_fld_labels) . ' items but Dropdown Option Values had ' . count($dd_srch_fld_values) . ' items. These fields must have the same number of items.', 'error' ); 
						return get_option( $dud_option_name );
					}
				}
				
				if((empty($dd_srch_fld_values) && !empty($dd_srch_fld_labels)) ||
					!empty($dd_srch_fld_values) && empty($dd_srch_fld_labels))
				{
					add_settings_error( $dud_option_name, 'user_directory_meta_srch_dd_labels', 'Error for Meta Search Field ' . $inc . ': You must enter values for both Dropdown Option Labels AND Dropdown Option Values.', 'error' ); 
					return get_option( $dud_option_name );
				}
				
				if($input['user_directory_meta_srch_field_' . $inc] && !$input['user_directory_meta_srch_label_'. $inc] && empty($input['ud_general_srch']))
				{
					add_settings_error( $dud_option_name, 'user_directory_bc_error', 'Please add a label for Meta Search Field ' . $inc, 'error' ); 
					return get_option( $dud_option_name );
				}
				
				if(!empty($dd_srch_fld_values) && !empty($dd_srch_fld_labels))
				{
					if(!update_dynamic_srch_box_jquery( $input )) 
					{
						add_settings_error( $dud_option_name, 'user_directory_bc_error', 'The dynamic search values javascript file could not be generated due to an unexpected error.', 'error' ); 
						//return get_option( $dud_option_name );
					}
				}
			}
				
		}	
		
		if(!$found_srch_fld && $input['ud_show_last_name_srch_fld'] === "never" && $input['ud_wp_email_addr_fld'] === "hide")
		{
			add_settings_error( $dud_option_name, 'user_directory_bc_error', 'Please enter at least one Meta Search Field or uncheck the Show Search Box option.', 'error' ); 
			return get_option( $dud_option_name );
		}
	}
	
	if(in_array( 'dynamic-user-directory-custom-sort-fld/dynamic-user-directory-custom-sort-fld.php' , $dud_plugin_list ))
	{
		if(empty($input['ud_sort_dd_option_txt']))
			$input['ud_sort_dd_option_txt'] = 'Search All Categories';	
		
		if(!empty($input['ud_custom_sort']))
		{
			if(empty($input['ud_sort_fld_key']))
			{
				add_settings_error( $dud_option_name, 'user_directory_bc_error', 'When you check the "Use Custom Sort Field" box, you must enter a Custom Sort Field Meta Key Name!' ); 
				return get_option( $dud_option_name );
			}
		}
	}

    return $input;
}


function dynamic_ud_validate_txt_fld( $input ) {

    if(isset($input))
    {
    	//our text fields will never be larger than 50 characters.
		if(strlen($input) > 50)
			$input = substr( $input, 0, 50 );
		
    	return sanitize_text_field($input);
    }
    
    return $input;
}

function dynamic_ud_validate_hex( $input, $dud_option_name ) {

   if(isset($input))
   {
		if( !dynamic_ud_check_color( sanitize_text_field($input) ) ) 
		{
        	// $setting, $code, $message, $type
       		add_settings_error( $dud_option_name, 'user_directory_bc_error', 'All colors must be a valid hexadecimal value!', 'error' ); 
         
       		return null;
		} 
		else
			return sanitize_text_field($input);
   }  
}

function dynamic_ud_check_color( $value ) { 
     
    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
        return true;
    }
     
    return false;
}

function dynamic_ud_check_numeric($input, $dud_option_name, $dud_fld_name='') {

	if (!is_numeric($input)) {
		
			if($dud_fld_name === 'ud_users_per_page')
				add_settings_error( $dud_option_name, 'user_directory_fs_error', 'The number of users per page must be a numeric value!', 'error' ); 
		    else
				add_settings_error( $dud_option_name, 'user_directory_fs_error', 'All pixel sizes must be a numeric value!', 'error' ); 
         
        	// Return the previous valid value
       		return null;
	}
	
	//our numeric fields will never be larger than two digits.
	//if(strlen($input) > 3)
	//	$input = substr( $input, 0, 3 );
		
	return sanitize_text_field($input);
}

function dynamic_ud_sort_order_admin( $input ) {
       
     $output = "";
     
     if($input) 
     {
     	 //append the newly added Meta Flds to list
     	 if(strpos($input, 'MetaKey5') === FALSE) $input .= ',MetaKey5'; 
     	 if(strpos($input, 'MetaKey6') === FALSE) $input .= ',MetaKey6'; 
     	 if(strpos($input, 'MetaKey7') === FALSE) $input .= ',MetaKey7'; 
     	 if(strpos($input, 'MetaKey8') === FALSE) $input .= ',MetaKey8'; 
     	 if(strpos($input, 'MetaKey9') === FALSE) $input .= ',MetaKey9'; 
     	 if(strpos($input, 'MetaKey10') === FALSE)$input .= ',MetaKey10'; 
		 if(strpos($input, 'Social') === FALSE)   $input .= ',Social'; 
     	 if(strpos($input, 'DateRegistered') === FALSE)   $input .= ',DateRegistered'; 
		 if(strpos($input, 'UserRoles') === FALSE)   $input .= ',UserRoles'; 
		 
         $output = explode(',', $input);  
     }
     else
     {
     	$output = "Address,Social,Email,Website,DateRegistered,UserRoles,MetaKey1,MetaKey2,MetaKey3,MetaKey4,MetaKey5,MetaKey6,MetaKey7,MetaKey8,MetaKey9,MetaKey10";
     	$output = explode(',', $output);
     }
     
     return $output;
}

function dynamic_ud_load_meta_keys($meta_type) {

	global $wpdb;
	$list_box = "";
	
	if($meta_type === "cimy" && defined("DUD_CIMY_FIELDS_TABLE")) 
	{
		$results = $wpdb->get_results("SELECT distinct NAME FROM " . DUD_CIMY_FIELDS_TABLE );
		
		if($results)
		{			
			$meta_key_list = "<textarea id='styled' class='dud_meta_keys' style='font-size:15px;line-height:25px;' spellcheck='false' rows='4' cols='40'>";
			
			$list_length = count((array)$results);
			$cnt = 1;	
			
			foreach ($results as $result)
			{ 
				$meta_key_list .= $result->NAME; 
				if($cnt !== $list_length) $meta_key_list .= "\n";
   				$cnt++;
    			}
    				
    			$meta_key_list .= "</textarea>";
    			return $meta_key_list;
    		}
	}
	else if($meta_type === "bp" && defined("DUD_BP_PLUGIN_FIELDS_TABLE")) 
	{
		$results = $wpdb->get_results("SELECT distinct name FROM " . DUD_BP_PLUGIN_FIELDS_TABLE . " where type <> 'option'");
		
		if($results)
		{			
			$meta_key_list = "<textarea id='styled' class='dud_meta_keys' style='font-size:15px;line-height:25px;' spellcheck='false' rows='4' cols='40'>";
			
			$list_length = count((array)$results);
			$cnt = 1;	
			
			foreach ($results as $result)
			{ 
				$meta_key_list .= $result->name; 
				if($cnt !== $list_length) $meta_key_list .= "\n";
   				$cnt++;
    			}
    				
    			$meta_key_list .= "</textarea>";
    			return $meta_key_list;
    		}
	}
	else if($meta_type === "s2m") 
	{		
		$meta_key_list = "<textarea id='styled' class='dud_meta_keys' style='font-size:15px;line-height:25px;' spellcheck='false' rows='4' cols='40'>";
		
		$flds_arr = get_s2member_custom_fields();
		
		if(!empty($flds_arr))
		{
			$list_length = count((array)$flds_arr);
			$cnt = 1;
			
			foreach($flds_arr as $key => $value) {
				$meta_key_list .= $key;
				if($cnt !== $list_length) $meta_key_list .= "\n";
   				$cnt++;
			}
			
			$meta_key_list .= "</textarea>";
			return $meta_key_list;
		}
	}
	else
	{
		$user_meta_key_val_list = array();
		$user_meta_key_list = array();
		
		$results = $wpdb->get_results("SELECT user_id FROM " . DUD_WPDB_PREFIX . "usermeta ORDER BY RAND() LIMIT 300");
		
		if($results)
		{
		        // Skip known WordPress meta fields that do not apply 
			$skip_me = "last_name*rich_editing*comment_shortcuts*admin_color*use_ssl*show_admin_bar_front
                        		*dismissed_wp_pointers*session_tokens*wp_user-settings*wp_user-settings-time
                        			*default_password_nag*wp_capabilities*wp_user_level*wporg_favorites
                        				*closedpostboxes_dashboard*metaboxhidden_dashboard*meta-box-order_dashboard";
                        		
			foreach ($results as $result)
			{ 		
				$all_meta_for_user = array_map( function( $a ){ return $a[0]; }, get_user_meta( $result->user_id ) );
							
				foreach ($all_meta_for_user as $key => $value) 
				{
					$key_exists = false;
					foreach ($user_meta_key_val_list as $key1 => $value1) 
					{
						if($key === $key1) $key_exists = true;
					}
					
					if(!$key_exists)
					{					 
						$pos = strpos($skip_me, $key);
   					
   						if($pos === false) 
   						{
   							if($value) $user_meta_key_val_list[$key] = $value;						
   							if($value) array_push($user_meta_key_list, $key);
    					}
    				}
				}
			}	
			
			$meta_key_list = "<textarea id='styled' class='dud_meta_keys' style='font-size:15px;line-height:25px;' spellcheck='false' rows='4' cols='40'>";
			
			$list_length = count((array)$user_meta_key_list);
			$cnt = 1;
			
			asort($user_meta_key_list, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);

			if($user_meta_key_list) 
			{		
				foreach ($user_meta_key_list as $key2) 
				{			
   					$meta_key_list .= $key2;
   					if($cnt !== $list_length) $meta_key_list .= "\n";
   					$cnt++;
				}
				
				$meta_key_list .= "</textarea>";
				return $meta_key_list;
			}		
		}
	}
	
	return "";
}

function dud_check_cimy_field($fld) {

	global $wpdb;
	
	$dud_plugin_list = get_option('active_plugins');
		
	if ( in_array( 'cimy-user-extra-fields/cimy_user_extra_fields.php' , $dud_plugin_list ) ) 
	{
		if(defined("DUD_CIMY_FIELDS_TABLE")) {
			
			$results = $wpdb->get_results("SELECT distinct NAME FROM " . DUD_CIMY_FIELDS_TABLE . " where NAME = '" . $fld . "'");
			
			if($results)
				return "1";
		}
	}
	
	return "";
}

function dud_check_bp_field($fld) {

	global $wpdb;
	
	$dud_plugin_list = get_option('active_plugins');
    	
	if( function_exists('bp_is_active'))
	{
		if(defined("DUD_BP_PLUGIN_FIELDS_TABLE")) {
			
			$results = $wpdb->get_results("SELECT distinct name FROM " . DUD_BP_PLUGIN_FIELDS_TABLE . " where name = '" . $fld . "'");
			
			if($results)
			{
				return "1";
			}
		}
	}
	
	return "";
}

function dud_check_s2m_field($fld, $fld_type) {

	global $wpdb;
	$dud_plugin_list = get_option('active_plugins');
    	
	if(in_array( 's2member/s2member.php' , $dud_plugin_list ) )
	{
		$s2member_custom_fields = get_s2member_custom_fields();

		foreach ($s2member_custom_fields as $key => $value) 
		{
			if($fld === $key && !$fld_type) return "1";
			else if($fld === $key && $fld_type)
			{
				if(is_array($value)) return "a";
				else return "s";
			}
		}	
	}
	
	return "";
}

function dynamic_ud_roles_listbox($selected_roles_arr, $dud_option_name) 
{
	global $wp_roles;

	$wproles = $wp_roles->get_names();

	$ud_listbox = "<SELECT class='js-example-basic-multiple' style='height:100%;width:98%;font-size:14px;letter-spacing:1px' name='" . $dud_option_name . "[ud_hide_roles][]' size='5' multiple='multiple'>";
		
	foreach($wproles as $role_name)
	{
		$ud_listbox .= "<option value='{$role_name}'";
		
		if($selected_roles_arr){
			if(in_array($role_name, $selected_roles_arr))
				$ud_listbox .= " SELECTED";
		}
				
		$ud_listbox .= ">{$role_name}</option>";
	}	
	
	$ud_listbox .= "</SELECT>";

	return $ud_listbox;
}

function dynamic_ud_export_roles_listbox($selected_roles_arr, $dud_option_name, $dud_fld_name) 
{
	global $wp_roles;

	$wproles = $wp_roles->get_names();

	$ud_listbox = "<SELECT class='js-example-basic-multiple' style='height:100%;width:98%;font-size:14px;letter-spacing:1px' name='" . $dud_option_name . "[" . $dud_fld_name . "][]' size='5' multiple='multiple'>";
		
	foreach($wproles as $role_name)
	{
		$ud_listbox .= "<option value='{$role_name}'";
		
		if($selected_roles_arr){
			if(in_array($role_name, $selected_roles_arr))
				$ud_listbox .= " SELECTED";
		}
				
		$ud_listbox .= ">{$role_name}</option>";
	}	
	
	$ud_listbox .= "</SELECT>";

	return $ud_listbox;
}

function dynamic_ud_users_listbox($selected_users_arr, $dud_option_name) 
{
	global $wpdb;
	$ud_listbox = "";
	$total_users = 0;
	
	$results = $wpdb->get_results("SELECT count(user_id) as total_users from " . DUD_WPDB_PREFIX . "usermeta WHERE meta_key = 'last_name'");
	
	if($results)
	{
		foreach($results as $result)
		{
			$total_users = $result->total_users;
		}
	}
	
	if($total_users > 1000)
		$results = $wpdb->get_results("SELECT DISTINCT user_login, ID as user_id from " . DUD_WPDB_PREFIX . "users order by user_login ASC");
	else
		$results = $wpdb->get_results("SELECT DISTINCT user_id from " . DUD_WPDB_PREFIX . "usermeta WHERE meta_key = 'last_name' order by meta_value");
			
	if($results)
	{           
		$ud_listbox = "<SELECT class='js-example-basic-multiple' style='height:100%;width:98%;font-size:14px;letter-spacing:1px' name='" . $dud_option_name . "[ud_users_exclude_include][]' size='5' multiple='multiple'>";
		
		foreach($results as $result)
		{
			$ud_listbox .= "<option value='{$result->user_id}'";
		
			if($selected_users_arr){
				if(in_array($result->user_id, $selected_users_arr))
					$ud_listbox .= " SELECTED";
			}
			
			if($total_users > 1000)
			{
				$user_login = $result->user_login;
				$ud_listbox .= ">{$user_login}</option>";
			}
			else 
			{
				$user_first_name = get_user_meta($result->user_id, 'first_name', true);
				$user_last_name = get_user_meta($result->user_id, 'last_name', true);
				
				$ud_listbox .= ">{$user_last_name}, {$user_first_name}</option>";
			}
		}
	}	
	else 
		return "";
	
	$ud_listbox .= "</SELECT>";

	return $ud_listbox;
}

function update_dynamic_srch_box_jquery( $input )
{
	global $wpdb;
	
	try
	{
		$plugins_dir_path = plugin_dir_path(__FILE__);
		$pos = stripos($plugins_dir_path,"plugins");
		$pos += 8;
		$plugins_dir_path = substr($plugins_dir_path, 0, $pos);
				
		if (!file_exists($plugins_dir_path . 'dynamic-user-directory-srch-js')) 
			mkdir($plugins_dir_path . 'dynamic-user-directory-srch-js', 0777, true);
		
		//if (!file_exists(plugin_dir_path(__FILE__) . 'dynamic-srch-js')) 
		//	mkdir(plugin_dir_path(__FILE__) . 'dynamic-srch-js', 0777, true);
		
		else if(file_exists($plugins_dir_path . 'dynamic-user-directory-srch-js/jquery.dynamic-srch-box.js'))
			unlink($plugins_dir_path . 'dynamic-user-directory-srch-js/jquery.dynamic-srch-box.js');

		$newfile = fopen($plugins_dir_path . 'dynamic-user-directory-srch-js/jquery.dynamic-srch-box.js','w'); 
		
		if(!$newfile) 
		{	
			//var_dump(error_get_last());
			return false;
		}
		
		//echo "About to write the file...<BR><BR>";
		
		fwrite($newfile, '(function( $ ) {' . PHP_EOL);
		fwrite($newfile, ' ' . PHP_EOL);
		fwrite($newfile, ' ' . PHP_EOL);
		fwrite($newfile, '	$(document).ready(function() {' . PHP_EOL);
		fwrite($newfile, ' ' . PHP_EOL);	
		fwrite($newfile, '		//When Directory Search Button is Clicked' . PHP_EOL);
		fwrite($newfile, '		$("#dud_user_srch_submit").click(function(event){' . PHP_EOL);
		fwrite($newfile, ' ' . PHP_EOL);
		$cnt = 1;
		for($inc = 1; $inc < 16; $inc++)
		{ 
			if(!empty($input['user_directory_meta_srch_field_' . $inc]) && !empty($input['user_directory_meta_srch_dd_values_' . $inc]))
			{
				if($cnt == 1)
					fwrite($newfile, '		   if($("#dud_user_srch_key").val() == "' . $input['user_directory_meta_srch_field_' . $inc] . '" && $( "#dud_meta_srch_dd_values_' . $cnt . '" ).val() !== "")' . PHP_EOL);
				else
					fwrite($newfile, '		   else if($("#dud_user_srch_key").val() == "' . $input['user_directory_meta_srch_field_' . $inc] . '" && $( "#dud_meta_srch_dd_values_' . $cnt . '" ).val() !== "")' . PHP_EOL);
		
				fwrite($newfile, '				$( "#dud_user_srch_val" ).val($( "#dud_meta_srch_dd_values_' . $cnt . '" ).val());' . PHP_EOL); 
		
				$cnt++;
			}
		}
		fwrite($newfile, ' ' . PHP_EOL);
		fwrite($newfile, '			$("#search_button_clicked").val("1");' . PHP_EOL);
		fwrite($newfile, '			$("#dud_user_srch").submit();' . PHP_EOL); 
		fwrite($newfile, ' ' . PHP_EOL);		   
		fwrite($newfile, '		});' . PHP_EOL);
		fwrite($newfile, ' ' . PHP_EOL);
		fwrite($newfile, ' ' . PHP_EOL);
		fwrite($newfile, '		$("#dud_user_srch_key").change(function() {' . PHP_EOL); 
		fwrite($newfile, ' ' . PHP_EOL);
		
		$cnt = 1;
		$cnt_2 = 1;
		
		for($inc = 1; $inc < 16; $inc++)
		{ 
			if(!empty($input['user_directory_meta_srch_field_' . $inc]) && !empty($input['user_directory_meta_srch_dd_values_' . $inc]))
			{
				if($cnt == 1)
					fwrite($newfile, '			  if($("#dud_user_srch_key").val() == "' . $input['user_directory_meta_srch_field_' . $inc] . '")' . PHP_EOL);	
				else
					fwrite($newfile, '			  else if($("#dud_user_srch_key").val() == "' . $input['user_directory_meta_srch_field_' . $inc] . '") ' . PHP_EOL);
				
				
				fwrite($newfile, '			  {' . PHP_EOL);
				fwrite($newfile, '					$("#dud_meta_srch_dd_values_' . $cnt . '_div").show();' . PHP_EOL); 
				
				$cnt_2 = 1;
				for($inc_2 = 1; $inc_2 < 16; $inc_2++)
				{				
					if(!empty($input['user_directory_meta_srch_field_' . $inc_2]) && !empty($input['user_directory_meta_srch_dd_values_' . $inc_2]))
					{
						if($cnt_2 != $cnt)
							fwrite($newfile, '					$("#dud_meta_srch_dd_values_' . $cnt_2 . '_div").hide();' . PHP_EOL); 
						
						$cnt_2++;
					}
				}
				
				fwrite($newfile, '				    $("#dud_text_input").hide();' . PHP_EOL); 				
				fwrite($newfile, '			  }' . PHP_EOL);
				
				$cnt++;
			}
		}
		
		fwrite($newfile, '			  else' . PHP_EOL);
		fwrite($newfile, '			  {' . PHP_EOL);
		
		$cnt = 1;
		for($inc = 1; $inc < 16; $inc++)
		{				
			if(!empty($input['user_directory_meta_srch_field_' . $inc]) && !empty($input['user_directory_meta_srch_dd_values_' . $inc]))
			{
				fwrite($newfile, '					$("#dud_meta_srch_dd_values_' . $cnt . '_div").hide();' . PHP_EOL); 
				
				$cnt++;
			}
		}
		
		fwrite($newfile, '				    $( "#dud_user_srch_val" ).val("");' . PHP_EOL);
		fwrite($newfile, '				    $("#dud_text_input").show();' . PHP_EOL); 
		fwrite($newfile, '			  }' . PHP_EOL);
		fwrite($newfile, ' ' . PHP_EOL);		
		fwrite($newfile, '		});' . PHP_EOL);
		fwrite($newfile, ' ' . PHP_EOL);
		fwrite($newfile, ' ' . PHP_EOL);
		
		//Same thing as above, but outside the change fcn
		$cnt = 1;
		$cnt_2 = 1;
		
		for($inc = 1; $inc < 16; $inc++)
		{ 
			if(!empty($input['user_directory_meta_srch_field_' . $inc]) && !empty($input['user_directory_meta_srch_dd_values_' . $inc]))
			{
				if($cnt == 1)
					fwrite($newfile, '		if($("#dud_user_srch_key").val() == "' . $input['user_directory_meta_srch_field_' . $inc] . '")' . PHP_EOL);	
				else
					fwrite($newfile, '		else if($("#dud_user_srch_key").val() == "' . $input['user_directory_meta_srch_field_' . $inc] . '") ' . PHP_EOL);
				
				
				fwrite($newfile, '		{' . PHP_EOL);
				fwrite($newfile, '			$("#dud_meta_srch_dd_values_' . $cnt . '_div").show();' . PHP_EOL); 
				
				$cnt_2 = 1;
				for($inc_2 = 1; $inc_2 < 16; $inc_2++)
				{				
					if(!empty($input['user_directory_meta_srch_field_' . $inc_2]) && !empty($input['user_directory_meta_srch_dd_values_' . $inc_2]))
					{
						if($cnt_2 != $cnt)
							fwrite($newfile, '		    $("#dud_meta_srch_dd_values_' . $cnt_2 . '_div").hide();' . PHP_EOL); 
						
						$cnt_2++;
					}
				}
				
				fwrite($newfile, '			$("#dud_text_input").hide();' . PHP_EOL); 				
				fwrite($newfile, '		}' . PHP_EOL);
				
				$cnt++;
			}
		}
		
		fwrite($newfile, '		else' . PHP_EOL);
		fwrite($newfile, '		{' . PHP_EOL);
		
		$cnt = 1;
		for($inc = 1; $inc < 16; $inc++)
		{				
			if(!empty($input['user_directory_meta_srch_field_' . $inc]) && !empty($input['user_directory_meta_srch_dd_values_' . $inc]))
			{
				fwrite($newfile, '			$("#dud_meta_srch_dd_values_' . $cnt . '_div").hide();' . PHP_EOL); 
				
				$cnt++;
			}
		}
		
		fwrite($newfile, '			$("#dud_text_input").show();' . PHP_EOL); 
		fwrite($newfile, '		}' . PHP_EOL);	
		
		fwrite($newfile, ' ' . PHP_EOL);
		
		fwrite($newfile, '	});' . PHP_EOL);   //closing stuff
				
		fwrite($newfile, '	})( jQuery );' . PHP_EOL);
				
		fclose($newfile);
		
		return true;
	}
	catch(Exception $e)
	{
		//echo $e->getMessage();
		//var_dump(error_get_last());
		return false;
	}
}


/*function my_plugin_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'complete_1_6_5_update_notice_dismissed' ) && current_user_can('administrator') )
	{		
		$current_url = esc_url( home_url( '/' ) ) . 'wp-admin/options-general.php?page=user_directory&';
		//echo '<div class="notice notice-warning"><p>Dynamic User Directory has a new <a href="http://sgcustomwebsolutions.com/wordpress-plugin-development/" target="_blank">Custom Avatar Add-on</a> available now!&nbsp;&nbsp;<a href="' . $current_url . 'dud-plugin-dismissed">Dismiss</a></p></div>';
		echo '<div class="notice notice-warning"><p>To complete your update to Dynamic User Directory 1.6.5, please open the DUD settings page and click "Save options" without making any changes.&nbsp;&nbsp;<a href="' . $current_url . 'dud-plugin-dismissed">Dismiss</a></p></div>';
	}
}
add_action( 'admin_notices', 'my_plugin_notice' );

function my_plugin_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['dud-plugin-dismissed'] ) )
        add_user_meta( $user_id, 'complete_1_6_5_update_notice_dismissed', 'true', true );
}
add_action( 'admin_init', 'my_plugin_notice_dismissed' );*/