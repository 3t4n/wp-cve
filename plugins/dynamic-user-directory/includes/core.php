<?php

/*** LIST OF DUD FILTERS ********************************************************************************

* dud_after_load_letters        		= modify the alphabet letters corresponding to users in the directory
* dud_modify_letters           		    = same as above, but includes additional parameters for greater flexibility  
* dud_after_load_uids           		= modify user ids of everyone shown in the directory (if "single page directory") 
*                                		  or page (if "alphabet letter links" directory)
* dud_after_load_sort_order     		= modify sort order of directory meta fields 
* dud_after_load_meta_flds      		= modify array containing the meta field names, labels, keys, and links
* dud_after_load_meta_vals      		= modify array containing the meta field names, vals, labels, keys, and links
* dud_set_user_full_name        		= modify the display format of the user's full name
* dud_set_avatar_url           			= modify the url that points to the avatar image location
* dud_set_avatar_link           		= modify the link that points to the user profile/author page 
* dud_set_user_profile_link     		= modify the link that points to the user profile/author page
* dud_search_err               			= modify the search error that is displayed when no results are found
* dud_no_users_msg              		= modify the message that is displayed when the directory is empty
* dud_format_key_val_array      		= reformat any meta fld stored as a key/value array. Normally each key-value pair 
*                                 		  is displayed as "key: val<br>"            
* dud_srch_fld_placeholder_txt  		= modify the default DUD placeholder text placed in the search box
* dud_modify_address_flds      		    = modify the address field values
* dud_set_user_email            		= modify the user's email address
* dud_set_user_email_display            = modify the text of the email link wihtout changing the email address itself
* dud_modify_social_flds                = modify the social media field values
* dud_modify_social_fld_icons           = modify the social media field icons
* dud_directory_totals_notification     = modify the user and search result totals notification text
*
* META FIELDS SEARCH ADD-ON FILTERS
*
* dud_meta_fld_srch_load_alpha_links 
* dud_meta_fld_srch_print_alpha_links
* dud_meta_fld_srch_build_sql
* dud_build_srch_form
* dud_S2M_search
*
* ALPHA LINKS SCROLL ADD-ON FILTER
*
* dud_print_scroll_letter_links
*
* HIDE DIRECTORY BEFORE SEARCH ADD-ON FILTER
*
* dud_hide_dir_before_srch
*
*********************************************************************************************************/

function DynamicUserDirectory( $atts )
{	
global $wpdb;
global $userid;

$plugins = get_option('active_plugins');
$loaded_options = "";
$letters = "";
$user_sql = "";
$srch_err = "";
$empty_dir_err = "";
$no_users_found_err = "";
$invalid_val_err = "";
$dir_type_before_srch = "";
$ud_display_listings = "";
$custom_sort_active = false;
$meta_flds_srch_active = false;
$alpha_links_scroll_active = false;
$exclude_user_filter_active = false;
$export_active = false;
$load_S2M = false;

$dud_options = get_option( 'dud_plugin_settings' );

/*** If the Multiple Directories add-on is installed, load the appropriate directory instance ***/
if ( in_array( 'dynamic-user-directory-multiple-dirs/dynamic-user-directory-multiple-dirs.php' , $plugins ))
{
	$loaded_options = 'dud_plugin_settings'; //default unless changed below
	
	if(!empty($atts) && $atts['name'] != "original")
	{	
		for($inc=0; $inc <= 99; $inc++) 
		{	
			if( $dud_tmp_options = get_option( 'dud_plugin_settings_' . ($inc+1) ) )
			{
				if($atts['name'] === $dud_tmp_options['dud_instance_name'])
				{
					$dud_options = $dud_tmp_options;
					$loaded_options = 'dud_plugin_settings_' . ($inc+1);					
					break;
				}	
			}	
		}
	}
} 

/*** Load the scripts ***/
if (!wp_style_is( 'user-directory-style', 'enqueued' )) {
  
	wp_register_style('user-directory-style',  DYNAMIC_USER_DIRECTORY_URL . 'css/user-directory-min.css', false, 0.1);	
	wp_enqueue_style( 'user-directory-style' );
	//wp_register_style('user-directory-style',  DYNAMIC_USER_DIRECTORY_URL . '/css/user-directory.css', false, 0.1);	
	//wp_enqueue_style( 'user-directory-style' );	
}

//wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 

wp_register_script( 'FontAwesome', 'https://kit.fontawesome.com/2e95a9bac3.js' );
wp_enqueue_script('FontAwesome');

$dud_scroll_dir = !empty($dud_options['ud_scroll_to_dir_top']) ? $dud_options['ud_scroll_to_dir_top'] : ""; 
if ($dud_scroll_dir === "yes" && !wp_script_is( 'dud_scroll_to_top_js', 'enqueued' )) {
	
	wp_register_script( 'dud_scroll_to_top_js', DYNAMIC_USER_DIRECTORY_URL . '/js/jquery.scroll-to-top-of-directory.js', array('jquery'), '4.0.3', true );
	wp_enqueue_script( 'dud_scroll_to_top_js');	
}

/*** Turn debug on if debug mode is set to "on" ***/
global $dynamic_ud_debug; 
$dynamic_ud_debug = false;

if(current_user_can('administrator'))
	if($dud_options['ud_debug_mode'] === "on")
		$dynamic_ud_debug = true;
		
if($dynamic_ud_debug)
	dynamic_ud_dump_settings($loaded_options);

/*** Get sort, hide roles, search, and include/exclude fields ***/
$user_directory_sort  		  = !empty($dud_options['user_directory_sort']) ? $dud_options['user_directory_sort'] : null;
$ud_hide_roles        		  = !empty($dud_options['ud_hide_roles']) ? $dud_options['ud_hide_roles'] : null;
$exc_inc_radio       		  = !empty($dud_options['ud_exclude_include_radio']) ? $dud_options['ud_exclude_include_radio'] : null;
$inc_exc_user_ids    		  = !empty($dud_options['ud_users_exclude_include']) ? $dud_options['ud_users_exclude_include'] : null;
$ud_directory_type    		  = !empty($dud_options['ud_directory_type']) ? $dud_options['ud_directory_type'] : null;
$ud_show_srch         		  = !empty($dud_options['ud_show_srch']) ? $dud_options['ud_show_srch'] : null;
$ud_hide_before_srch  		  = !empty($dud_options['ud_hide_before_srch']) ? $dud_options['ud_hide_before_srch'] : null;
$ud_custom_sort       		  = !empty($dud_options['ud_custom_sort']) ? $dud_options['ud_custom_sort'] : null;
$ud_show_num_users            = !empty($dud_options['ud_show_num_users']) ? $dud_options['ud_show_num_users'] : "";
$ud_general_srch         	  = !empty($dud_options['ud_general_srch']) ? $dud_options['ud_general_srch'] : null;
$user_directory_srch_fld      = "";
$search_button_clicked        = ""; 
$dir_type_before_srch         = $ud_directory_type;

if(in_array( 'dynamic-user-directory-custom-sort-fld/dynamic-user-directory-custom-sort-fld.php' , $plugins ) && !empty($ud_custom_sort))
	$custom_sort_active = true;
if (in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $plugins )) 
	$meta_flds_srch_active = true;
if (in_array( 'dynamic-user-directory-alpha-links-scroll/dynamic-user-directory-alpha-links-scroll.php' , $plugins ))
	$alpha_links_scroll_active = true;
if (in_array( 'dynamic-user-directory-exclude-user-filter/dynamic-user-directory-exclude-user-filter.php' , $plugins ))
	$exclude_user_filter_active = true;
if (in_array( 'dynamic-user-directory-export/dynamic-user-directory-export.php' , $plugins ))
{
	$export_off_on  = !empty($dud_options['dud_export_directory']) ? $dud_options['dud_export_directory'] : "";
		
	if($export_off_on === "on")
		$export_active = true;
}

/*** Error Message Config & Styling ***/
$err_font_size       = !empty($dud_options['ud_err_msg_font_size'])  ? $dud_options['ud_err_msg_font_size'] : "20";
$empty_dir_err       = !empty($dud_options['ud_empty_dir_err'])      ? $dud_options['ud_empty_dir_err'] : "There are no users in the directory at this time.";
$invalid_val_err     = !empty($dud_options['ud_invalid_val_err'])    ? $dud_options['ud_invalid_val_err'] : "Please enter a valid search value.";
$no_users_found_err  = !empty($dud_options['ud_no_users_found_err']) ? $dud_options['ud_no_users_found_err'] : "No users were found matching your search criteria.";
$exclude_users_performance_err = "No members were found for the selected letter.";

$empty_dir_err       = "<div class='dud_err_msg' style='font-size:" . $err_font_size . "px;'>" . $empty_dir_err . "</div>";
$no_users_found_err  = "<div class='dud_err_msg' style='font-size:" . $err_font_size . "px;'>" . $no_users_found_err . "</div>";
$invalid_val_err     = "<div class='dud_err_msg' style='font-size:" . $err_font_size . "px;'>" . $invalid_val_err . "</div>";

/*** Custom Sort Add-on Stuff ***/
if($custom_sort_active)
{
	$ud_sort_fld_key     		  = !empty($dud_options['ud_sort_fld_key']) ? $dud_options['ud_sort_fld_key'] : null;
	$ud_sort_fld_type     		  = !empty($dud_options['ud_sort_fld_type']) ? $dud_options['ud_sort_fld_type'] : null;
	$ud_sort_cat_link_caps 		  = !empty($dud_options['ud_sort_cat_link_caps']) ? $dud_options['ud_sort_cat_link_caps'] : "";
	$ud_sort_show_categories_as   = !empty($dud_options['ud_sort_show_categories_as']) ? $dud_options['ud_sort_show_categories_as'] : "";
	$search_category 			  = !empty($_REQUEST ["dud_category"]) ? $_REQUEST ["dud_category"] : null;
}
else
{
	$ud_sort_fld_key      		  = null;
	$ud_sort_fld_type     		  = null; 
	$ud_sort_cat_link_caps 		  = "";
	$ud_sort_show_categories_as   = "";
	$search_category              = "";
}
	
/*** Get the search input field or search letter ***/
$dud_user_srch_key = !empty($_REQUEST ["dud_user_srch_key"]) ? $_REQUEST ["dud_user_srch_key"] : null; //For the meta flds srch add-on

if(is_null($dud_user_srch_key) || $dud_user_srch_key === "") 
	$dud_user_srch_key = '';

$dud_user_srch_name = !empty($_REQUEST ["dud_user_srch_val"]) ? $_REQUEST ["dud_user_srch_val"] : null;

if(is_null($dud_user_srch_name) || $dud_user_srch_name === "") 
	$dud_user_srch_name = '';

/*** Meta Fields Search Add-On: if a search value was entered & the results should be shown on single page ***/
if(in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $plugins ) &&
	!empty($dud_options['ud_show_srch_results']) && $dud_options['ud_show_srch_results'] === 'single-page' && $dud_user_srch_name)
{
	$ud_directory_type = "all-users";
}

/*** Input validation and sanitization ***/
if(strlen($dud_user_srch_name) > 0) 
{
	if(strlen($dud_user_srch_name) > 50)
		return apply_filters('dud_search_err', "<div style='font-size:20px;'>The search field is limited to 50 characters!</div>");
	
	else if (strlen(trim($dud_user_srch_name)) == 0)
		$srch_err = apply_filters('dud_search_err', $invalid_val_err);
			
	$dud_user_srch_name = sanitize_text_field(htmlspecialchars($dud_user_srch_name));
}

/*** Load an array with alphabet letters corresponding to existing user last names ***/
if ( (!$meta_flds_srch_active || !$dud_user_srch_name || !$ud_show_srch) )
{
	$letters = dynamic_ud_load_alpha_links($user_directory_sort, $ud_hide_roles, $exc_inc_radio, 
		$inc_exc_user_ids, $dud_user_srch_name, $ud_directory_type, $dud_options, $custom_sort_active);
}
else	
{
	$letters = apply_filters('dud_meta_fld_srch_load_alpha_links', $letters, $user_directory_sort, $ud_hide_roles, $exc_inc_radio, 
		$inc_exc_user_ids, $dud_user_srch_key, $dud_user_srch_name, $loaded_options, $custom_sort_active);
		
	// If meta field search came up empty
	if(count((array)$letters) == 0 && $dud_user_srch_name ) 
	{
		$letters = dynamic_ud_load_alpha_links($user_directory_sort, $ud_hide_roles, $exc_inc_radio, $inc_exc_user_ids, "", 
			$ud_directory_type, $dud_options, $custom_sort_active);
			
		$srch_err = apply_filters('dud_search_err', $no_users_found_err);
	}
}

$letters = apply_filters( 'dud_after_load_letters', $letters, $dud_user_srch_key, $dud_user_srch_name, 
	$user_directory_sort, $ud_hide_roles, $exc_inc_radio, $inc_exc_user_ids, $loaded_options );

if ( count((array)$letters) == 0 && !$dud_user_srch_name && empty($srch_err))	
	return apply_filters('dud_no_users_msg', $empty_dir_err);	
 
/*** Get last name letter ***/

$last_name_letter_empty = false;

// If NOT using the Meta Fields Search add-on 
if ( !$meta_flds_srch_active || !$ud_show_srch) 
{
	if(strlen($dud_user_srch_name) > 0) { // if a basic last name search --> get the first letter
		
		$last_name_letter = substr($dud_user_srch_name, 0, 1); 
		$last_name_letter = strtoupper($last_name_letter);
	}
	else // If not a last name search --> get letter from request or default to first available letter
	{
		$last_name_letter = !empty($_REQUEST ["letter"]) ? $_REQUEST ["letter"] : null;

		/*** SORT FLD ADD ON *******/
		if($custom_sort_active && 
			($ud_sort_show_categories_as === 'dd' || 
				(($ud_sort_show_categories_as === 'dd-srch' || $ud_sort_show_categories_as === 'dd-links') && $search_category !== "all")))
		{
			//BuddyPress stores special chars with html encoding
			if( function_exists('bp_is_active') && $ud_sort_fld_type === "bp" && $custom_sort_active)
				$last_name_letter = esc_html($search_category);
			else
				$last_name_letter = $search_category;
		}
		/***************************/	
	
		if(is_null($last_name_letter) || $last_name_letter === "") 
		{
			$last_name_letter = $letters[0];
			$last_name_letter_empty = true;
		}
	}	
}
else
{
	$last_name_letter = !empty($_REQUEST ["letter"]) ? $_REQUEST ["letter"] : null;
		
	$search_button_clicked = !empty($_REQUEST ["search_button_clicked"]) ? $_REQUEST ["search_button_clicked"] : null; 
	if(empty($search_button_clicked)) $search_button_clicked = "";	
	
	/*** SORT FLD ADD ON *******/
	if($custom_sort_active && 
		($ud_sort_show_categories_as === 'dd' || 
			($ud_sort_show_categories_as === 'dd-srch' || 
				($ud_sort_show_categories_as === 'dd-links' && $search_button_clicked) && $search_category !== "all")))
	{
			//BuddyPress stores special chars with html encoding
			if( function_exists('bp_is_active') && $ud_sort_fld_type === "bp" && $custom_sort_active)				
				$last_name_letter = esc_html($search_category);
			else
				$last_name_letter = $search_category;
	}
	/***************************/	
	
	if(is_null($last_name_letter) || $last_name_letter === "") 
	{
		$last_name_letter_empty = true;
	}
	
	if(is_null($last_name_letter) || $last_name_letter === "" || ($search_button_clicked && !($ud_directory_type === "all-users")))
	{	
		if(!$custom_sort_active)
			$last_name_letter = $letters[0];
		else
		{
			if(!empty($search_category) && $search_category !== "all")
			{
				//BuddyPress stores special chars with html encoding
				if( function_exists('bp_is_active') && $ud_sort_fld_type === "bp" && $custom_sort_active)
					$last_name_letter = esc_html($search_category);
				else
					$last_name_letter = $search_category;
			}
			else
				$last_name_letter = $letters[0];
		}
	}
	
	/*** SORT FLD ADD ON *******/
	else if(!empty($ud_sort_fld_key) && $custom_sort_active)
	{
		if(!($ud_directory_type === "all-users") && !empty($last_name_letter) && !empty($letters) && $search_button_clicked)
			$last_name_letter = $letters[0];
	}
    /***************************/	
	
	if(!empty($ud_sort_fld_key) && $custom_sort_active)
	{
		$letter_found = false;
		
		if(!empty($letters))
		{
			foreach($letters as $letter)
			{
				if(strtoupper($letter) === strtoupper($last_name_letter))
				{
					$letter_found = true;
				}
			}
		}
		
		if(!is_null($last_name_letter) && !$letter_found && !($ud_directory_type === "all-users"))
			$srch_err = apply_filters('dud_search_err', $no_users_found_err);
	}
	else if(!is_null($last_name_letter) && !in_array(esc_html($last_name_letter), (array) $letters) && !($ud_directory_type === "all-users"))
		$srch_err = apply_filters('dud_search_err', $no_users_found_err);		
}

/*** Validate request data ***/
if(empty($ud_sort_fld_key) || !$custom_sort_active)
{
	if(!ctype_alpha($last_name_letter) || strlen($last_name_letter) > 1) 
		return apply_filters('dud_no_users_msg', $empty_dir_err);	
}
else
{
	if(strlen($last_name_letter) > 75) 
		return apply_filters('dud_no_users_msg', $empty_dir_err);	
}

/*** BUILD SQL QUERY ****************************************************************/

$roles_sql = "";
$include_exclude_sql = "";
$total_users = "";
$S2M_keymatch = false;
$uids = array();
$tmp_uids = "";

// Flag if this is an S2Member srch fld
if(in_array( 's2member/s2member.php' , $plugins)) 
{
	$flds_arr = get_s2member_custom_fields();

    if(empty($ud_general_srch))
	{
		foreach($flds_arr as $key => $value)
			if(strtoupper($dud_user_srch_key) === strtoupper($key)) $S2M_keymatch = true;
	}
    else $S2M_keymatch = true;	
}

// If not running a meta field search other than last name 		
if ( !$meta_flds_srch_active || !$dud_user_srch_name || !$ud_show_srch) 
{	
	$user_sql = dud_build_directory_query($last_name_letter, $last_name_letter_empty, $ud_sort_fld_key, '', 
		$ud_sort_fld_type, $custom_sort_active, false, '', $dud_options); 
							
	$uids = $wpdb->get_results($user_sql);
	
	//Get the total users count	for a letter link directory
	if(($ud_show_num_users || ($exclude_user_filter_active && empty($dud_options['ud_filter_fld_performance'])) 
		&& $dud_options['ud_directory_type'] !== "all-users"))
	{
		$tmp_dir_type = $dud_options['ud_directory_type'];
		
		$dud_options['ud_directory_type'] = "all-users";
		
		$tmp_user_sql = dud_build_directory_query($last_name_letter, $last_name_letter_empty, $ud_sort_fld_key, '', 
			$ud_sort_fld_type, $custom_sort_active, false, '', $dud_options); 
		
		$tmp_uids = $wpdb->get_results($tmp_user_sql);
				
		$dud_options['ud_directory_type'] = $tmp_dir_type;
	}
}
// If running a meta field search on an S2Member custom field 
else if( $meta_flds_srch_active && $S2M_keymatch )
{
	// Only used if a Meta Fld search was run on an S2M field 
	$uids = apply_filters('dud_S2M_search', $uids, $loaded_options, $last_name_letter);
	
	//Get the total users count	for a letter link directory
	if(($ud_show_num_users || ($exclude_user_filter_active && empty($dud_options['ud_filter_fld_performance'])) 
		&& $dud_options['ud_directory_type'] !== "all-users"))
	{
		$tmp_uids = apply_filters('dud_S2M_search', $uids, $loaded_options, $last_name_letter, true);
	}
}
// If running any other kind of meta field search 		
else 
{
	$user_sql = apply_filters( 'dud_meta_fld_srch_build_sql', $user_sql, $last_name_letter, $dud_user_srch_key, 
		$dud_user_srch_name, $loaded_options, $letters, $custom_sort_active);
		
	$uids = $wpdb->get_results($user_sql);
	
	//Get the total users count	for a letter link directory
	if(($ud_show_num_users || ($exclude_user_filter_active && empty($dud_options['ud_filter_fld_performance'])) 
		&& $dud_options['ud_directory_type'] !== "all-users"))
	{
		$user_sql = apply_filters( 'dud_meta_fld_srch_build_sql', $user_sql, $last_name_letter, $dud_user_srch_key, 
			$dud_user_srch_name, $loaded_options, $letters, $custom_sort_active, true);
		
		$tmp_uids = $wpdb->get_results($user_sql);		
	}
}

/*** UID & LETTER FILTERS **************************************************************/
$uids = apply_filters( 'dud_after_load_uids', $uids);
$uids = apply_filters( 'dud_after_load_uids_multi_dirs', $uids, $loaded_options);

//Exclude User Filter Add-On
if($exclude_user_filter_active)
{	
	if($dud_options['ud_directory_type'] !== "all-users")
	{
		if(empty($dud_options['ud_filter_fld_performance']))
		{
			if(!empty($tmp_uids))
			{
				$tmp_uids = dud_filter_users($tmp_uids, $dud_options);
				$total_users = count($tmp_uids);
				$uids = dud_filter_users($uids, $dud_options);
				$letters = apply_filters( 'dud_filter_users_letter_links', $letters, $tmp_uids, $dud_options );
				
				if(empty($uids) && !empty($letters))
					$uids = dud_filter_users_get_first_letter($letters[0], $tmp_uids, $dud_options);
			}
			else
			{
				$uids = dud_filter_users($uids, $dud_options);
				$total_users = count($uids);
				$letters = apply_filters( 'dud_filter_users_letter_links', $letters, $uids, $dud_options );
			}
		}
		else
		{
			$uids = dud_filter_users($uids, $dud_options); 
			$total_users = count($uids);
			$letters = apply_filters( 'dud_filter_users_letter_links', $letters, $uids, $dud_options );
			
			if(empty($uids)) 
				$srch_err = apply_filters('dud_search_err', "No members were found for the selected letter.");
			else
			{
				if(empty($last_name_letter))
 					$last_name_letter = $letters[0];
			}			
		}
	}
	else
	{
		$uids = dud_filter_users($uids, $dud_options); 
		$total_users = count($uids);
		$letters = apply_filters( 'dud_filter_users_letter_links', $letters, $uids, $dud_options );
	}
}
else
{
	//Set the total user count after running all filters
	if(!empty($tmp_uids))
	{
		$tmp_uids = apply_filters( 'dud_after_load_uids', $tmp_uids);
		$tmp_uids = apply_filters( 'dud_after_load_uids_multi_dirs', $tmp_uids, $loaded_options);
		$total_users =  count($tmp_uids);
	}
	else
	{
		$total_users = count($uids);
	}
}

// For the Hide Dir Before Srch add-on
if($ud_hide_before_srch && $last_name_letter_empty 
	&& !($custom_sort_active && !empty($search_category) && $search_button_clicked && empty($dud_user_srch_name)))	
{
	$uids = apply_filters( 'dud_hide_dir_before_srch', $uids);
}

$letters = apply_filters( 'dud_modify_letters', $letters, $uids, $user_sql, $last_name_letter, 
	$dud_user_srch_key, $dud_user_srch_name, $loaded_options);
	
/**************************************************************************************/

if($dynamic_ud_debug) { echo "<PRE>Load Users SQL:<BR><BR>" . $user_sql . "<BR><BR></PRE>"; }

/*** If users were found ***/
if($uids)
{ 	
    /*** PASS OFF DISPLAY TO HORIZONTAL LAYOUT IF INSTALLED****************************/ 
	if ( in_array( 'dynamic-user-directory-horizontal-layout/dud_horizontal_layout.php' , $plugins ))
	{
		$ud_display_listings = !empty($dud_options['ud_display_listings']) ? $dud_options['ud_display_listings'] : null;
		
		if($ud_display_listings === "horizontally" && $srch_err !== $invalid_val_err)
		{
			return apply_filters('horizontal_display', $uids, $dud_options, $ud_directory_type, $dynamic_ud_debug, $ud_show_srch, 
				$letters, $user_directory_sort, $dud_user_srch_key, $dud_user_srch_name, $loaded_options, $total_users );
	    }
	}
	/*********************************************************************************/
	
	$inc = 1;
	$listing_cnt = 0;
	$user_fullname = "";
	$user_website = ""; 
	$user_first_name = "";
	$user_last_name = ""; 
	$user_approval = ""; 
	$user_avatar_url = "";
	$printed_letter = "";
	$user_contact_info = "";
	$first_user_idx = "";
	$last_user_idx = "";
		
	/*** OPTION SETTINGS ******************************************************************/
	
	/*** Display Settings ***/
	$user_directory_avatar_padding         = !empty($dud_options['user_directory_avatar_padding']) ? $dud_options['user_directory_avatar_padding'] : "90px";	
	$user_directory_avatar_size            = !empty($dud_options['user_directory_avatar_size']) ? $dud_options['user_directory_avatar_size'] : "";	
    $user_directory_show_avatar            = !empty($dud_options['user_directory_show_avatars']) ? $dud_options['user_directory_show_avatars'] : null;	     
    $user_directory_avatar_style           = !empty($dud_options['user_directory_avatar_style']) ? $dud_options['user_directory_avatar_style'] : null;	       
	$user_directory_border                 = !empty($dud_options['user_directory_border']) ? $dud_options['user_directory_border'] : null;
	$user_directory_border_length          = !empty($dud_options['user_directory_border_length']) ? $dud_options['user_directory_border_length'] : null;
	$user_directory_border_style           = !empty($dud_options['user_directory_border_style']) ? $dud_options['user_directory_border_style'] : null;
	$user_directory_border_color           = !empty($dud_options['user_directory_border_color']) ? $dud_options['user_directory_border_color'] : null;
	$user_directory_border_thickness       = !empty($dud_options['user_directory_border_thickness']) ? $dud_options['user_directory_border_thickness'] : null;
	$user_directory_listing_fs             = !empty($dud_options['user_directory_listing_fs']) ? $dud_options['user_directory_listing_fs'] : null;
	$user_directory_listing_sp             = !empty($dud_options['user_directory_listing_spacing']) ? $dud_options['user_directory_listing_spacing'] : null;
	$ud_author_page                        = !empty($dud_options['ud_author_page']) ? $dud_options['ud_author_page'] : null;
	$ud_auth_or_bp                         = !empty($dud_options['ud_auth_or_bp']) ? $dud_options['ud_auth_or_bp'] : null;
	$ud_show_author_link                   = !empty($dud_options['ud_show_author_link']) ? $dud_options['ud_show_author_link'] : null;
	$ud_target_window                      = !empty($dud_options['ud_target_window']) ? $dud_options['ud_target_window'] : null;
	$ud_letter_divider                     = !empty($dud_options['ud_letter_divider']) ? $dud_options['ud_letter_divider'] : null;
	$ud_letter_divider_font_color          = !empty($dud_options['ud_letter_divider_font_color']) ? $dud_options['ud_letter_divider_font_color'] : null;
	$ud_letter_divider_fill_color          = !empty($dud_options['ud_letter_divider_fill_color']) ? $dud_options['ud_letter_divider_fill_color'] : null;
	$ud_srch_style                         = !empty($dud_options['ud_srch_style']) ? $dud_options['ud_srch_style'] : null;
	$ud_format_name                        = !empty($dud_options['ud_format_name']) ? $dud_options['ud_format_name'] : null;
	$ud_divider_border_thickness           = !empty($dud_options['ud_divider_border_thickness']) ? $dud_options['ud_divider_border_thickness'] : "";
	$ud_divider_border_color               = !empty($dud_options['ud_divider_border_color']) ? $dud_options['ud_divider_border_color'] : "";
	$ud_divider_border_length              = !empty($dud_options['ud_divider_border_length']) ? $dud_options['ud_divider_border_length'] : "";
	$ud_divider_border_style               = !empty($dud_options['ud_divider_border_style']) ? $dud_options['ud_divider_border_style'] : "";
	$ud_divider_font_size                  = !empty($dud_options['ud_divider_font_size']) ? $dud_options['ud_divider_font_size'] : "";
	$ud_icon_color                         = !empty($dud_options['ud_icon_color']) ? $dud_options['ud_icon_color'] : "";
	$ud_icon_style                         = !empty($dud_options['ud_icon_style']) ? $dud_options['ud_icon_style'] : "";
	$ud_icon_size                          = !empty($dud_options['ud_icon_size']) ? $dud_options['ud_icon_size'] : "";
	$ud_users_per_page                     = !empty($dud_options['ud_users_per_page']) ? $dud_options['ud_users_per_page'] : "";
	$ud_show_pagination_top_bottom_page    = !empty($dud_options['ud_show_pagination_top_bottom']) ? $dud_options['ud_show_pagination_top_bottom'] : "both";
	$ud_show_pagination_above_below	       = !empty($dud_options['ud_show_pagination_above_below']) ? $dud_options['ud_show_pagination_above_below'] : "below";
	$ud_pagination_font_size		   	   = !empty($dud_options['ud_pagination_font_size']) ? $dud_options['ud_pagination_font_size'] : "14px";
	$ud_pagination_link_color		   	   = !empty($dud_options['ud_pagination_link_color']) ? $dud_options['ud_pagination_link_color'] : "";
	$ud_pagination_link_clicked_color	   = !empty($dud_options['ud_pagination_link_clicked_color']) ? $dud_options['ud_pagination_link_clicked_color'] : "";
	$ud_alpha_link_color		   	   	   = !empty($dud_options['ud_alpha_link_color']) ? $dud_options['ud_alpha_link_color'] : "";
	$ud_alpha_link_clicked_color   	   	   = !empty($dud_options['ud_alpha_link_clicked_color']) ? $dud_options['ud_alpha_link_clicked_color'] : "";
	$ud_num_users_font_size                = !empty($dud_options['ud_num_users_font_size']) ? $dud_options['ud_num_users_font_size'] : "";
	$ud_txt_after_num_users                = !empty($dud_options['ud_txt_after_num_users']) ? $dud_options['ud_txt_after_num_users'] : "";
	$ud_txt_after_num_users_srch		   = !empty($dud_options['ud_txt_after_num_users_srch']) ? $dud_options['ud_txt_after_num_users_srch'] : "";
	$ud_num_users_top_bottom               = !empty($dud_options['ud_num_users_top_bottom']) ? $dud_options['ud_num_users_top_bottom'] : "";
	$ud_num_users_border                   = !empty($dud_options['ud_num_users_border']) ? $dud_options['ud_num_users_border'] : "";
	$ud_num_users_border_color             = !empty($dud_options['ud_num_users_border_color']) ? $dud_options['ud_num_users_border_color'] : "";
	$ud_hide_username     		           = !empty($dud_options['ud_hide_username']) ? $dud_options['ud_hide_username'] : null;
	$ud_letter_spacing     		           = !empty($dud_options['ud_letter_spacing']) ? $dud_options['ud_letter_spacing'] : "1px";
	$ud_date_registered_format     		   = !empty($dud_options['ud_date_registered_format']) ? $dud_options['ud_date_registered_format'] : "";
	$ud_email_lbl			     		   = !empty($dud_options['ud_email_lbl']) ? $dud_options['ud_email_lbl'] : "";
	$ud_email_format		     		   = !empty($dud_options['ud_email_format']) ? $dud_options['ud_email_format'] : "hyperlink";
	$ud_website_lbl			     		   = !empty($dud_options['ud_website_lbl']) ? $dud_options['ud_website_lbl'] : "";
	$ud_website_format			     	   = !empty($dud_options['ud_website_format']) ? $dud_options['ud_website_format'] : "";
	$ud_date_lbl			     		   = !empty($dud_options['ud_date_lbl']) ? $dud_options['ud_date_lbl'] : "";
	$ud_roles_lbl			     		   = !empty($dud_options['ud_roles_lbl']) ? $dud_options['ud_roles_lbl'] : "";
	$ud_show_fld_lbl_for_empty_fld		   = !empty($dud_options['ud_show_fld_lbl_for_empty_fld']) ? $dud_options['ud_show_fld_lbl_for_empty_fld'] : "";	
	$ud_show_user_roles		   			   = !empty($dud_options['ud_show_user_roles']) ? $dud_options['ud_show_user_roles'] : "";	
	$ud_user_roles_format		   		   = !empty($dud_options['ud_user_roles_format']) ? $dud_options['ud_user_roles_format'] : "";	
	
	//Export Add-on 
	$dud_export_link_position              = !empty($dud_options['dud_export_link_position']) ? $dud_options['dud_export_link_position'] : "top";
	
	//Custom Sort Field Add-On
	$ud_sort_cat_font_color                		= !empty($dud_options['ud_sort_cat_font_color']) ? $dud_options['ud_sort_cat_font_color'] : null;
	$ud_sort_cat_fill_color          	   		= !empty($dud_options['ud_sort_cat_fill_color']) ? $dud_options['ud_sort_cat_fill_color'] : null;
	$ud_sort_cat_border_thickness          		= !empty($dud_options['ud_sort_cat_border_thickness']) ? $dud_options['ud_sort_cat_border_thickness'] : "";
	$ud_sort_cat_border_color              		= !empty($dud_options['ud_sort_cat_border_color']) ? $dud_options['ud_sort_cat_border_color'] : "";
	$ud_sort_cat_border_length             		= !empty($dud_options['ud_sort_cat_border_length']) ? $dud_options['ud_sort_cat_border_length'] : "";
	$ud_sort_cat_border_style              		= !empty($dud_options['ud_sort_cat_border_style']) ? $dud_options['ud_sort_cat_border_style'] : "";
	$ud_sort_cat_font_size                	    = !empty($dud_options['ud_sort_cat_font_size']) ? $dud_options['ud_sort_cat_font_size'] : "";
	$ud_sort_cat_header          		   		= !empty($dud_options['ud_sort_cat_header']) ? $dud_options['ud_sort_cat_header'] : null;
	$ud_sort_show_cats_dd_hide_dir_before_srch  = !empty($dud_options['ud_sort_show_cats_dd_hide_dir_before_srch']) ? $dud_options['ud_sort_show_cats_dd_hide_dir_before_srch'] : "Yes";
	
	$ud_printed_custom_sort_links			    = false;	
	
	$letter_div_shadow = ""; 
		
	if($ud_letter_divider === "ld-ds") $letter_div_shadow = " letter-div-shadow";
	
	if( ($ud_directory_type === "all-users" || (!empty($dud_options['ud_show_srch_results']) && $dud_options['ud_show_srch_results'] === 'single-page')) && $ud_letter_divider !== "nld") 
	{			
		if(!($user_directory_border === "surrounding_border" || $user_directory_border === "dividing_border"))
			$user_directory_border_length = "65%"; //set letter divider length	
	}
	
	$sort_order_items = dynamic_ud_sort_order( $dud_options['user_directory_sort_order'] );
		
	// For developers who want to modify the plugin 
	$sort_order_items = apply_filters( 'dud_after_load_sort_order', $sort_order_items);
		
	/*** Meta field names, keys, and labels ***/
	$user_directory_addr_1_op    = !empty($dud_options['user_directory_addr_1']) ? $dud_options['user_directory_addr_1'] : "";
	$user_directory_addr_2_op    = !empty($dud_options['user_directory_addr_2']) ? $dud_options['user_directory_addr_2'] : "";
	$user_directory_city_op      = !empty($dud_options['user_directory_city']) ? $dud_options['user_directory_city'] : "";
	$user_directory_state_op     = !empty($dud_options['user_directory_state']) ? $dud_options['user_directory_state'] : "";
	$user_directory_zip_op       = !empty($dud_options['user_directory_zip']) ? $dud_options['user_directory_zip'] : "";
	$user_directory_country_op   = !empty($dud_options['user_directory_country']) ? $dud_options['user_directory_country'] : "";
	
	$ud_facebook_op    = !empty($dud_options['ud_facebook']) ? $dud_options['ud_facebook'] : "";
	$ud_twitter_op     = !empty($dud_options['ud_twitter']) ? $dud_options['ud_twitter'] : "";
	$ud_linkedin_op    = !empty($dud_options['ud_linkedin']) ? $dud_options['ud_linkedin'] : "";
	$ud_google_op      = !empty($dud_options['ud_google']) ? $dud_options['ud_google'] : "";
	$ud_instagram_op   = !empty($dud_options['ud_instagram']) ? $dud_options['ud_instagram'] : "";
	$ud_pinterest_op   = !empty($dud_options['ud_pinterest']) ? $dud_options['ud_pinterest'] : "";
	$ud_youtube_op     = !empty($dud_options['ud_youtube']) ? $dud_options['ud_youtube'] : "";
	$ud_tiktok_op      = !empty($dud_options['ud_tiktok']) ? $dud_options['ud_tiktok'] : "";
	$ud_podcast_op     = !empty($dud_options['ud_podcast']) ? $dud_options['ud_podcast'] : "";
	
	$user_directory_meta_flds = array();

	$fldIdx = 0;
	for ($inc=0; $inc<10; $inc++)
	{
		$tmp_fld = $dud_options['user_directory_meta_field_' . ($inc+1)];
		
		if($tmp_fld) 
		{
			$user_directory_meta_flds[$fldIdx] = array();
			$user_directory_meta_flds[$fldIdx]['field'] = $tmp_fld;
			$user_directory_meta_flds[$fldIdx]['label'] = !empty($dud_options['user_directory_meta_label_' . ($inc+1)]) ? $dud_options['user_directory_meta_label_' . ($inc+1)] : null;
			$user_directory_meta_flds[$fldIdx]['key'] = "MetaKey" . ($inc+1);
			$user_directory_meta_flds[$fldIdx]['link'] = !empty($dud_options['user_directory_meta_link_' . ($inc+1)]) ? $dud_options['user_directory_meta_link_' . ($inc+1)] : null;
			$user_directory_meta_flds[$fldIdx]['format'] = !empty($dud_options['dud_fld_format_' . ($inc+1)]) ? $dud_options['dud_fld_format_' . ($inc+1)] : null;
			$fldIdx++;
		}	
		else
		{
			$idx = array_search( ("MetaKey" . ($inc+1) ), $sort_order_items);
			
			if($idx===false) continue;
			else unset($sort_order_items[$idx]); //if meta key has empty value, remove from sort list
		}
	}
		
	//Custom Sort Field Add-On: Check for category change. Used for printing category header only.
    if(!empty($ud_sort_fld_key) && $custom_sort_active && $ud_sort_cat_header !== "nch")
    {
		$fldIdx = sizeof($user_directory_meta_flds);
	    $user_directory_meta_flds[$fldIdx] = array();
		$user_directory_meta_flds[$fldIdx]['field'] = $ud_sort_fld_key;
    }
	
	if($dynamic_ud_debug) {
		echo "<PRE>";
		echo "Meta Fld Types<BR><BR>";
	
		for($inc=0; $inc < sizeof($user_directory_meta_flds); $inc++ ) 
		{
			echo "Fld: " . $user_directory_meta_flds[$inc]['field'] . "<BR>";		
	
			$fld_type = "WordPress";
			
			if(dud_chk_bp_field($user_directory_meta_flds[$inc]['field'])) $fld_type = "BuddyPress";
			else if(dud_chk_s2m_field($user_directory_meta_flds[$inc]['field'], false))  $fld_type = "S2Member";
			else if(dud_chk_cimy_field($user_directory_meta_flds[$inc]['field']))  $fld_type = "Cimy";
			
			echo "Field Type: " . $fld_type . "<BR><BR>";
						
		}
		echo "<BR></PRE>";
	}
	
	if(in_array( 's2member/s2member.php' , $plugins)) 
	{
		//See if there are S2Member Keys for this directory
		$flds_arr = get_s2member_custom_fields();
		
		foreach($flds_arr as $key => $value)
		{
			for($inc=0; $inc < sizeof($user_directory_meta_flds); $inc++) 
			{
				if(strtoupper($user_directory_meta_flds[$inc]['field']) === strtoupper($key)) 
				{
					$load_S2M = true;
					break;
				}	 
			}
			
			if(strtoupper($user_directory_addr_1_op) === strtoupper($key)) $load_S2M = true; 	 
			else if(strtoupper($user_directory_addr_2_op) === strtoupper($key)) $load_S2M = true; 
			else if(strtoupper($user_directory_city_op) === strtoupper($key)) $load_S2M = true;  	     
			else if(strtoupper($user_directory_state_op) === strtoupper($key)) $load_S2M = true;  	 
			else if(strtoupper($user_directory_zip_op) === strtoupper($key)) $load_S2M = true;  	 
			else if(strtoupper($user_directory_country_op) === strtoupper($key)) $load_S2M = true;  	 	
			else if(strtoupper($ud_facebook_op) === strtoupper($key)) $load_S2M = true;  	 	 
			else if(strtoupper($ud_twitter_op) === strtoupper($key)) $load_S2M = true;  	 
			else if(strtoupper($ud_linkedin_op) === strtoupper($key)) $load_S2M = true;  	 
			else if(strtoupper($ud_google_op) === strtoupper($key)) $load_S2M = true;  	 
			else if(strtoupper($ud_pinterest_op) === strtoupper($key)) $load_S2M = true;  	 
			else if(strtoupper($ud_instagram_op) === strtoupper($key)) $load_S2M = true;  
			else if(strtoupper($ud_youtube_op) === strtoupper($key)) $load_S2M = true;  
			else if(strtoupper($ud_tiktok_op) === strtoupper($key)) $load_S2M = true;  
			else if(strtoupper($ud_podcast_op) === strtoupper($key)) $load_S2M = true;  
		}
	}
	
	$user_directory_meta_flds = apply_filters('dud_after_load_meta_flds', $user_directory_meta_flds);
	
	/*** Meta fields from wp_users table ***/
	$user_directory_email = !empty($dud_options['user_directory_email']) ? $dud_options['user_directory_email'] : null;        //wp_users field
	$user_directory_website = !empty($dud_options['user_directory_website']) ? $dud_options['user_directory_website'] : null;  //wp_users field
	$ud_date_registered = !empty($dud_options['ud_date_registered']) ? $dud_options['ud_date_registered'] : null;  //wp_users field
	
	/*** Set defaults for empty options ***/
	if(!$user_directory_border_length) $user_directory_border_length = "100%";
	if(!$user_directory_border_style) $user_directory_border_style = "solid";
	if(!$user_directory_border_color) $user_directory_border_color = "#dddddd";
	if(!$user_directory_border_thickness) $user_directory_border_thickness = "1px";
	if(!$user_directory_listing_fs) $user_directory_listing_fs = "12px";
	if(!$user_directory_listing_sp) $user_directory_listing_sp = "20";
	if(!$ud_format_name) $ud_format_name = "fl";
	
	$user_contact_info .= "<a name='dud-top-of-directory'></a>";
	
	/*** LETTER LINKS, SEARCH BOX & PAGINATION ************************************************************/
	
	/*** Letter Links Dir ***/
	
	if(!($ud_directory_type === "all-users"))
	{
		/*** Meta Fld Search add-on ***/
		if ( $meta_flds_srch_active && $ud_show_srch && (empty($ud_sort_fld_key) || !$custom_sort_active)) 
		{
			if(has_filter( 'dud_meta_fld_srch_print_alpha_links_v_1_6', 'dud_meta_fld_srch_print_alpha_links_v_1_6' ))
			{
				$alpha_links = apply_filters('dud_meta_fld_srch_print_alpha_links_v_1_6', $letters, $dud_options, $dud_user_srch_key, 
					$dud_user_srch_name, $last_name_letter) . "<BR>";
			}
			else
			{
				$alpha_links = apply_filters('dud_meta_fld_srch_print_alpha_links', $letters, $dud_options['ud_alpha_link_spacer'], 
						$dud_options['user_directory_letter_fs'], $dud_user_srch_key, $dud_user_srch_name ) . "<BR>";
			}
		}			
		/*** Custom Sort Field Add-on ***/
		else if(!empty($ud_sort_fld_key) && $custom_sort_active) 
		{
			//echo "<br>About to call custom sort alpha links. DUD srch name is " . $dud_user_srch_name . "<BR>";
			$alpha_links = dud_print_alpha_links_custom_sort_fld($letters, $last_name_letter, false, $dud_user_srch_key, 
								$dud_user_srch_name, $plugins, $dud_options);
		}
		/*** Standard DUD alpha links ***/
		else
		{
			$alpha_links = dynamic_ud_print_alpha_links($letters, $dud_options['ud_alpha_link_spacer'], $dud_options['user_directory_letter_fs'], 
						$ud_alpha_link_clicked_color, $ud_alpha_link_color, $last_name_letter) . "<BR>";
		}
		
		$user_contact_info .= $alpha_links;
	}
	/*** Single Page Directory ***/
	else
	{
	    /* Alpha Links Scroll add-on */
		if(! (!empty($ud_users_per_page) && $ud_users_per_page > 0) ) //hide letter links if printing pagination instead (single page dir only)
		{
			//If doing a basic last name search, don't print the scroll links at the top.
			if ( ! (!$meta_flds_srch_active && !$custom_sort_active && $ud_show_srch && !empty($dud_user_srch_name))
						&& ! ($custom_sort_active && $ud_sort_cat_header === "nch") 
							&& ! ($custom_sort_active && !empty($dud_user_srch_name) && $ud_show_srch 
								&& !$meta_flds_srch_active && $ud_sort_show_categories_as == "links"))  
			{
					$user_contact_info .= apply_filters( 'dud_print_scroll_letter_links', $user_contact_info, $letters, $loaded_options );			
			}
			
			//If there was a basic last name search for custom sort field single page dir 
			if($custom_sort_active && $alpha_links_scroll_active && $dir_type_before_srch === "all-users" && !$meta_flds_srch_active &&
				$ud_sort_show_categories_as === 'links'  && !empty($ud_show_srch) && !empty($dud_user_srch_name))
			{
				$user_contact_info .= "<form id='dud_user_srch' method='post'>\n";		
				$user_contact_info .= dud_get_basic_srch_form_html($custom_sort_active, $dud_options);
				$user_contact_info .= "</form><BR>\n"; 
			}					
		}
	}
	
	/*** Print Pagination ***************************************************/
		
	if(!empty($ud_users_per_page) && $ud_users_per_page > 0 
		&& ($ud_show_pagination_top_bottom_page === "top" || $ud_show_pagination_top_bottom_page === "both")
			&& $ud_show_pagination_above_below === "above"
				&& ! (!in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $plugins ) && $dud_user_srch_name) )
	{	
		$user_contact_info .= dud_print_pagination(count((array)$uids), $ud_users_per_page, $ud_directory_type, $ud_show_srch, 
			$last_name_letter, $dud_user_srch_name, $dud_user_srch_key, $plugins, $ud_pagination_font_size, $ud_pagination_link_color, 
				$ud_pagination_link_clicked_color, $dud_options, true);
	}
					
	/*** Print Basic Search Box ********************************************/	
	
	if(!empty($ud_show_srch))
	{		
		//Print search box unless the custom sort field add-on is going to print it		
		if(!$custom_sort_active)
		{
			$user_directory_srch_fld .= "<form id='dud_user_srch' method='post'>\n";		
			$user_directory_srch_fld .= dud_get_basic_srch_form_html($custom_sort_active, $dud_options);
			$user_directory_srch_fld .= "</form><BR>\n"; 
		}
			
		$user_directory_srch_fld = apply_filters('dud_build_srch_form', $user_directory_srch_fld, $loaded_options, $dud_options, $letters);
		
		if(!empty($srch_err))
		{
			$user_contact_info .= $user_directory_srch_fld . $srch_err;
			return $user_contact_info;
		}
		else
		{
			 //Clear the break tag if we are showing pagination and the Meta Fields Search add-on is active	
			if((!empty($dud_options['ud_users_per_page']) && $dud_options['ud_users_per_page'] > 0 
				&& in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $plugins ) 
				&& ($dud_options['ud_show_pagination_top_bottom'] === "top" || $dud_options['ud_show_pagination_top_bottom'] === "both")
				&& $dud_options['ud_show_pagination_above_below'] === "below") )
			{
				$user_directory_srch_fld = str_replace ( "<BR>", "", $user_directory_srch_fld );
			}
	
			$user_contact_info .= $user_directory_srch_fld;
		}
    }
	
	/*** Print Pagination and Number of Users ***************************************************/
	
	/* Number of Users Stuff */
	$show_num_users = show_num_users(true, $dud_options);
		
	if($show_num_users && !empty($dud_user_srch_name) && !$meta_flds_srch_active) 
		$total_users = dud_cnt_last_name_srch_results($uids, $dud_user_srch_name, $user_directory_sort);
	
	if(!empty($ud_txt_after_num_users_srch) && dud_endswith($ud_txt_after_num_users_srch, "results") && $total_users === 1)
		$ud_txt_after_num_users_srch = substr($ud_txt_after_num_users_srch, 0, -1);
	
	if(($ud_show_pagination_top_bottom_page === "top" || $ud_show_pagination_top_bottom_page === "both") 
			&& empty($dud_user_srch_name))
		$ud_num_users_notification = $total_users . " " . $ud_txt_after_num_users;
	else
		$ud_num_users_notification = $total_users . " " . $ud_txt_after_num_users_srch;
	
	
	/* Print pagination */
	if(!empty($ud_users_per_page) && $ud_users_per_page > 0 
		&& ($ud_show_pagination_top_bottom_page === "top" || $ud_show_pagination_top_bottom_page === "both")
			&& $ud_show_pagination_above_below !== "above"
				&& ! (!in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $plugins ) && $dud_user_srch_name) )
	{
		$print_pagination = dud_print_pagination(count((array)$uids), $ud_users_per_page, $ud_directory_type, $ud_show_srch, 
			$last_name_letter, $dud_user_srch_name, $dud_user_srch_key, $plugins, $ud_pagination_font_size, $ud_pagination_link_color, 
				$ud_pagination_link_clicked_color, $dud_options, true);
		
		$user_contact_info .= $print_pagination;
		
		/* Print number of users/srch results if needed */	
		if((empty($print_pagination) || $ud_directory_type !== "all-users") && $show_num_users)
		{					
			if(substr($user_contact_info, -5, 4) == '<BR>' && $meta_flds_srch_active) { $user_contact_info = substr($user_contact_info, 0, -5); }
			
			$user_contact_info .= "<div style='font-size:" . $ud_num_users_font_size . "px;' class='dud_total_users'>" .  apply_filters('dud_directory_totals_notification', $ud_num_users_notification, $dud_options) . "</div>";
			
			if($ud_num_users_border)
				$user_contact_info .= "<div style='width: " . $ud_num_users_border . ";border-color: " . $ud_num_users_border_color . ";' class='dud_total_users_border'></div>"; 
		}
		else
		{
			/* if pagination is turned on but we are on a letter links page needing no pagination */
			if(empty($print_pagination))
				$user_contact_info .= "<BR>";		
		}		
	}
	else if($show_num_users)
	{			
		if(substr($user_contact_info, -5, 4) == '<BR>' && $meta_flds_srch_active) { $user_contact_info = substr($user_contact_info, 0, -5); }
		
		$user_contact_info .= "<div style='font-size:" . $ud_num_users_font_size . "px;' class='dud_total_users'>" .  apply_filters('dud_directory_totals_notification', $ud_num_users_notification, $dud_options) . "</div>";
	
		if($ud_num_users_border)
				$user_contact_info .= "<div style='width: " . $ud_num_users_border . ";border-color: " . $ud_num_users_border_color . ";' class='dud_total_users_border'></div>"; 
	}
	
	if($export_active && $dud_export_link_position === "top")
	{
		$export_html = dud_export_dir($loaded_options);
		if(!empty($export_html))
			$user_contact_info .= $export_html . "<BR>";
	}
	
	/*** Determine if Cimy User Extra Fields plugin is installed and active ***/
	if ( in_array( 'cimy-user-extra-fields/cimy_user_extra_fields.php' , $plugins ) ) 
		$user_directory_cimy = TRUE;  //installed & active
	else
		$user_directory_cimy = FALSE; //not installed or inactive
		
	$tmp_category = "";
	
	if(!empty($ud_users_per_page) && $ud_users_per_page > 0)
	{
		$first_user_idx = dud_get_pagination_idx(count((array)$uids), $ud_users_per_page, "first");
		$last_user_idx = dud_get_pagination_idx(count((array)$uids), $ud_users_per_page, "last");
		
		/*if($dynamic_ud_debug) {
			echo "<PRE>";
			echo "PAGINATION<BR><BR>";
			echo "Index of first user on current page: " . $first_user_idx . "<BR><BR>";
			echo "Index of last user on current page: " . $last_user_idx . "<BR><BR>";
			echo "UIDs Array: <BR>";
			
			if($user_directory_sort === "last_name")
				foreach ($uids as $key => $uid) echo "uids[" . $key . "]: " . $uid->user_id . "<BR>";
			else
				foreach ($uids as $key => $uid) echo "uids[" . $key . "]: " . $uid->ID . "<BR>";
			
			echo "</PRE>";
		}*/
	}
	
	/*** Loop through all users with last name or display name matching the selected letter ***/
	foreach ($uids as $key => $uid)
	{   		
	    $user_id = 0;
		$user_directory_csz = "";
		$user_fullname = "";
		$user_website = ""; 
		$user_email = "";
		$user_registered = "";
		$user_first_name = "";
		$user_last_name = ""; 
		$user_directory_addr_1 = "";
		$user_directory_addr_2 = "";
		$user_directory_city = "";
		$user_directory_state = "";
		$user_directory_zip = "";
		$user_directory_country = "";
		$ud_facebook = "";
		$ud_twitter = "";
		$ud_linkedin = "";
		$ud_google = "";     
		$ud_instagram = "";   
		$ud_pinterest = "";  
		$cimy_avatar_loc = "";
		$address_flds = array();
		$social_flds = array();
		$got_cimy_data = false;
		$letter_div_printed = false;
		$category_change = false;
		$empty_cat_fld = false;
		$category_mismatch = false;
		$var_1 = "";
		$var_2 = "";
		$user_roles = "";
		
		/*** Pagination: skip UIDs that are not within the index range for the current page ***/
		
		//Only do this if we are NOT running a basic last name search 
		if ( ! (!in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $plugins ) && $dud_user_srch_name) )
		{			
			if( !empty($ud_users_per_page) && $ud_users_per_page > 0 && !($key >= $first_user_idx && $key <= $last_user_idx))
			{
				//if($dynamic_ud_debug) 
				//	echo "<PRE>Skipping user at index " . $key . "<BR></PRE>";
								
				continue;
			}
		}
				
		//Remove old meta fld values from the previous iteration
		unset($user_directory_meta_flds_tmp);
		$user_directory_meta_flds_tmp = $user_directory_meta_flds;
		
		/*** GATHER THE DIRECTORY DATA ***************************************************************/	
		
		if($user_directory_sort === "last_name")
		{
			$user_id = $uid->user_id;
			$user_last_name = get_user_meta($user_id, 'last_name', true);
		}
		else
		{
			$user_id = $uid->ID; 
			$user_last_name = $uid->display_name;
		}
		
		/*** If running a last name srch and NOT using the Meta Search Add-on  ***/
		if ( ! in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $plugins ) ) 
		{
			if($dud_user_srch_name)
			{ 					
				 if ((strpos(strtoupper ($user_last_name), strtoupper ($dud_user_srch_name)) === false))
				 {
					  continue;
				 }	
			}
		}
		
		$ud_author_posts = dud_cnt_user_posts($user_id); //used to see if we should link to the WP author page
				
		/*** LOAD WP USER META DATA ***/
		
		if($user_directory_addr_1_op)   $address_flds[0] = get_user_meta($user_id, $user_directory_addr_1_op, true);	 
		if($user_directory_addr_2_op)   $address_flds[1] = get_user_meta($user_id, $user_directory_addr_2_op, true); 
		if($user_directory_city_op)     $address_flds[2] = get_user_meta($user_id, $user_directory_city_op, true); 
		if($user_directory_state_op)    $address_flds[3] = get_user_meta($user_id, $user_directory_state_op, true); 
		if($user_directory_zip_op)      $address_flds[4] = get_user_meta($user_id, $user_directory_zip_op, true);
		if($user_directory_country_op)  $address_flds[5] = get_user_meta($user_id, $user_directory_country_op, true);
			
		if($ud_facebook_op)  $social_flds[0] = get_user_meta($user_id, $ud_facebook_op, true);	 
		if($ud_twitter_op)   $social_flds[1] = get_user_meta($user_id, $ud_twitter_op, true); 
		if($ud_linkedin_op)  $social_flds[2] = get_user_meta($user_id, $ud_linkedin_op, true); 
		if($ud_google_op)    $social_flds[3] = get_user_meta($user_id, $ud_google_op, true); 
		if($ud_pinterest_op) $social_flds[4] = get_user_meta($user_id, $ud_pinterest_op, true);	
		if($ud_instagram_op) $social_flds[5] = get_user_meta($user_id, $ud_instagram_op, true);
		if($ud_youtube_op)   $social_flds[6] = get_user_meta($user_id, $ud_youtube_op, true);
		if($ud_tiktok_op)    $social_flds[7] = get_user_meta($user_id, $ud_tiktok_op, true);
		if($ud_podcast_op)   $social_flds[8] = get_user_meta($user_id, $ud_podcast_op, true);
			
		for($inc=0; $inc < sizeof($user_directory_meta_flds_tmp); $inc++) 
		{		  
		   if($user_directory_meta_flds_tmp[$inc]['field']) 
		   {
			   //calling get_user_meta() this way so that we can parse meta fields than contain arrays
			   $user_meta_fld = get_user_meta($user_id, $user_directory_meta_flds_tmp[$inc]['field']);	
			   			   
			   $user_meta_fld = !empty($user_meta_fld[0]) ? $user_meta_fld[0] : null; //it will always be an array even for single values
			   $fld_format    = !empty($user_directory_meta_flds_tmp[$inc]['format']) ? $user_directory_meta_flds_tmp[$inc]['format'] : null; 
			   $fld_label     = !empty($user_directory_meta_flds_tmp[$inc]['label']) ? $user_directory_meta_flds_tmp[$inc]['label'] : null; 	
				
			   $user_directory_meta_flds_tmp[$inc]['value'] =  dynamic_ud_format_meta_val($user_meta_fld, $dud_options, $fld_format, $fld_label); 	
			}
		} 
		
		/*** LOAD USER META DATA STORED IN SEPARATE TABLES BY OTHER PLUGINS ***/
		
		if ( $user_directory_cimy ) //Cimy fields
			$user_directory_meta_flds_tmp = dud_load_cimy_vals($user_id, $dud_options, $user_directory_meta_flds_tmp);

		else if( function_exists('bp_is_active') ) //BuddyPress fields
			$user_directory_meta_flds_tmp = dud_load_bp_vals($user_id, $dud_options, $user_directory_meta_flds_tmp);

		// Load S2Member fields if there is at least one matching key
		if(in_array( 's2member/s2member.php' , $plugins) && $load_S2M) 
			$user_directory_meta_flds_tmp = dud_load_s2m_vals($user_id, $dud_options, $user_directory_meta_flds_tmp);
		
	   //Custom Sort Field Add-On: Check for a category change and skip blank category fields 
	   //or category search mismatches
	   		
	   if(!empty($ud_sort_fld_key) && $custom_sort_active)
	   {	
			for($inc=0; $inc < sizeof($user_directory_meta_flds_tmp); $inc++) 
			{
				if($ud_sort_fld_key === $user_directory_meta_flds_tmp[$inc]['field'] 
					&& !empty($user_directory_meta_flds_tmp[$inc]['value'] ) 
						&& $tmp_category !== $user_directory_meta_flds_tmp[$inc]['value'])
				{
					$tmp_category = $user_directory_meta_flds_tmp[$inc]['value'];
					$category_change = true;
					break;
				}
			}
			
			for($inc=0; $inc < sizeof($user_directory_meta_flds_tmp); $inc++) 
			{
				if($ud_sort_fld_key === $user_directory_meta_flds_tmp[$inc]['field'])
				{
					if(empty($user_directory_meta_flds_tmp[$inc]['value']))
						$empty_cat_fld = true;
					else 
					{
						//If doing a basic last name search and using category dropdown
						if(($ud_sort_show_categories_as === 'dd' || $ud_sort_show_categories_as === 'dd-srch') && 
								(!empty($dud_user_srch_name) || (!empty($search_category) && $search_category !== 'all' ) ) &&
									!$meta_flds_srch_active)
						{
							//Skip this user if selected category doesn't match the user's category
							if( !empty($search_category) && $search_category !== 'all' 
								&& strtoupper($search_category) !== strtoupper($user_directory_meta_flds_tmp[$inc]['value']) )
							{
								$category_mismatch = true;
							}
						}
						
						$empty_cat_fld = false;
					}
					break;
				}
			}
			
			//Skip this member since the sort field is blank
			if($empty_cat_fld) continue;
			if($category_mismatch) continue;
	   }
			   		
		//Look at the last two items in the array to see if address and/or social fld arrays were populated
		if(!empty($user_directory_meta_flds_tmp))
		{
			$var_1 = $user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-1)]['field'];
			$var_2 = "";
			
			if( (sizeof($user_directory_meta_flds_tmp)-2) > -1)
				$var_2 = $user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-2)]['field'];
		}
			
		if($var_1 && dud_endswith($var_1, "ADDRESS"))
		{
			$address_flds = $user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-1)]['value'];
			unset($user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-1)]);
		}
		else if($var_1 && dud_endswith($var_1, "SOCIAL"))
		{
			$social_flds = $user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-1)]['value'];
			unset($user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-1)]);
		}
			
		if($var_2 && dud_endswith($var_2, "ADDRESS"))
		{
			$address_flds = $user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-1)]['value'];
			unset($user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-1)]);
		}
		else if($var_2 && dud_endswith($var_2, "SOCIAL"))
		{
			$social_flds = $user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-1)]['value'];
			unset($user_directory_meta_flds_tmp[(sizeof($user_directory_meta_flds_tmp)-1)]);
		}
		
		// For developers who want to modify the address or social flds
		$address_flds = apply_filters('dud_modify_address_flds', $address_flds, $user_id);
		
		$social_flds = apply_filters('dud_modify_social_flds', $social_flds, $user_id);
		
		// For developers who want to modify the other meta flds 
		$user_directory_meta_flds_tmp = apply_filters('dud_after_load_meta_vals', $user_directory_meta_flds_tmp, $user_id);
		$user_directory_meta_flds_tmp = apply_filters('dud_after_load_meta_vals_multi_dirs', $user_directory_meta_flds_tmp, $user_id, $loaded_options);

		if($dynamic_ud_debug) {
			echo "<PRE>";
			echo "Meta Flds for User: " . $user_last_name . "<BR><BR>";
		
			for($inc=0; $inc < sizeof($user_directory_meta_flds_tmp); $inc++ ) 
			{
				echo "<b>Fld:</b> " . $user_directory_meta_flds_tmp[$inc]['field'] . "&nbsp;&nbsp;";	
				echo "<b>Lbl:</b> " . $user_directory_meta_flds_tmp[$inc]['label'] . "&nbsp;&nbsp;";	
				echo "<b>Key:</b> " . $user_directory_meta_flds_tmp[$inc]['key'] . "&nbsp;&nbsp;";	
				echo "<b>Val:</b> " . $user_directory_meta_flds_tmp[$inc]['value'] . "&nbsp;&nbsp;";	
				echo "<b>Link:</b> " . $user_directory_meta_flds_tmp[$inc]['link'] . "<BR>";
				echo "<b>Format:</b> " . $user_directory_meta_flds_tmp[$inc]['format'] . "<BR>";
			}
			echo "<BR></PRE>";
		}
		
		$userdata = get_userdata($user_id);	//wp_users fields
		
		if(!empty($user_directory_website) && !empty($userdata))
			$user_website =  $userdata->user_url;
		if(!empty($user_directory_email) && !empty($userdata))
			$user_email =  $userdata->user_email;
		if(!empty($ud_date_registered) && !empty($userdata))
			$user_registered = $userdata->user_registered;
		
		$user_email = apply_filters('dud_set_user_email', $user_email, $user_id); 
		
		if(!empty($userdata))
			$username = $userdata->user_login; // For cimy plugin - may not be needed	

		if($ud_show_user_roles)	
			$user_roles = dud_get_user_roles($user_id, $ud_user_roles_format, $ud_roles_lbl, $ud_display_listings, false);
					
		/*** PREPARE THE DIRECTORY DATA ****************************************************************/
		
		if(!empty($address_flds[0])) $user_directory_addr_1   = $address_flds[0];	 
		if(!empty($address_flds[1])) $user_directory_addr_2   = $address_flds[1];
		if(!empty($address_flds[2])) $user_directory_city     = $address_flds[2];
		if(!empty($address_flds[3])) $user_directory_state    = $address_flds[3];
		if(!empty($address_flds[4])) $user_directory_zip      = $address_flds[4];
		if(!empty($address_flds[5])) $user_directory_country  = $address_flds[5];
				
		if($user_directory_city && $user_directory_state && $user_directory_zip)
		    $user_directory_csz = $user_directory_city . ", " . $user_directory_state . " " . $user_directory_zip;	
		else if($user_directory_city && $user_directory_state)
		    $user_directory_csz = $user_directory_city . ", " . $user_directory_state;	
		else
		{        
		        if($user_directory_city)
		             $user_directory_csz .= $user_directory_city . " ";	
		        if($user_directory_state)
		             $user_directory_csz .= $user_directory_state . " ";	
		        if($user_directory_zip)
		             $user_directory_csz .= $user_directory_zip;		
		}
		    
		if($user_directory_sort === "last_name")	
		{
			$user_first_name = get_user_meta($user_id, 'first_name', true);	
      		$user_fullname = "<b>" . $user_first_name . " " . $user_last_name . "</b>";
      			
      		if($ud_format_name === "lf")
      		{
      			$user_fullname = "<b>" . $user_last_name . ", " . $user_first_name . "</b>";
      		}
      			
      		// Change the user's full name
			$user_fullname = apply_filters('dud_set_user_full_name', $user_fullname, $user_first_name, $user_last_name);
			$user_fullname = apply_filters('dud_set_user_full_name_uid', $user_fullname, $user_first_name, $user_last_name, $user_id);
      	}
      	else
      	{
      		$user_fullname = "<b>". $uid->display_name . "</b>";
      	} 
				
		$user_fullname = dud_build_username_profile_link($dud_options, $user_id, $user_fullname);

		// Configure the user profile page link
		$user_fullname = apply_filters('dud_set_user_profile_link', $user_fullname, $user_first_name, $user_last_name, $user_id);
		
		/*** PRINT THE DIRECTORY DATA *************************************************************************/	
		
		// LETTER DIVIDER  	
		if( ($ud_directory_type === "all-users" && $ud_letter_divider !== "nld") 
				|| (!empty($dud_options['ud_show_srch_results']) && $dud_options['ud_show_srch_results'] === 'single-page' && $dud_user_srch_name ) 
				|| (!empty($ud_sort_fld_key) && $custom_sort_active) )	
		{
			/*** No Custom Sort Add-On *****************************************************/
			if(empty($ud_sort_fld_key) || !$custom_sort_active)
			{
				// if we're on a new alphabet letter 
				if(strtoupper($printed_letter) !== strtoupper(substr($user_last_name, 0, 1)))
				{
					// space between each listing 	
					if($user_directory_border === "surrounding_border")
						$user_contact_info .= "<DIV style=\"height:" . $user_directory_listing_sp . "px;\"></DIV>";	
			
					$printed_letter = substr($user_last_name, 0, 1);
					
					$user_contact_info .= dud_get_letter_divider($printed_letter, $dud_options, false);
											
					$letter_div_printed = true;
				}
				// if showing a dividing border 
				else if($user_directory_border === "dividing_border" && $listing_cnt !== 0)
				{
					$user_contact_info .= "<DIV style=\"width:" . $user_directory_border_length 
						. ";border-style:" . $user_directory_border_style . ";border-width:" . $user_directory_border_thickness . ";border-color:" . 	
							 $user_directory_border_color . ";\" class=\"dir-listing-border-2\" ></DIV>\n";
				}
			}
			/*** Custom Sort Add-On *******************************************************/
			else if( !empty($ud_sort_fld_key) && $custom_sort_active)
			{
				// if we're on a new category
				if($category_change && $ud_sort_cat_header !== 'nch')
				{
					// space between each listing 	
					if($user_directory_border === "surrounding_border")
						$user_contact_info .= "<DIV style=\"height:" . $user_directory_listing_sp . "px;\"></DIV>";	
			
					$printed_letter = $tmp_category;
					
					$user_contact_info .= dud_get_letter_divider($printed_letter, $dud_options, true);
											
					$letter_div_printed = true;
				}
				// if showing a dividing border 
				else if($user_directory_border === "dividing_border" && $listing_cnt !== 0)
				{
					$user_contact_info .= "<DIV style=\"width:" . $user_directory_border_length 
						. ";border-style:" . $user_directory_border_style . ";border-width:" . $user_directory_border_thickness . ";border-color:" . 	
							 $user_directory_border_color . ";\" class=\"dir-listing-border-2\" ></DIV>\n";
				}
			}
		}
		
		// DIVIDING BORDER 
		else if($user_directory_border === "dividing_border" && $listing_cnt !== 0)
		{
			$user_contact_info .= "<DIV style=\"width:" . $user_directory_border_length 
				. ";border-style:" . $user_directory_border_style . ";border-width:" . $user_directory_border_thickness . ";border-color:" . 	
					 $user_directory_border_color . ";\" class=\"dir-listing-border-2\" ></DIV>\n";
		}
		
		// SPACE BETWEEN LISTINGS			
		if(!$letter_div_printed && $listing_cnt !== 0)
			$user_contact_info .= "<DIV style=\"height:" . $user_directory_listing_sp . "px;\"></DIV>";	
		
		// SURROUNDING BORDER
		if($user_directory_border === "surrounding_border")
		{
			$user_contact_info .= "\n<DIV style=\"width:" . $user_directory_border_length . "; border-style:" 
				. $user_directory_border_style . ";border-width:" . $user_directory_border_thickness . ";border-color:" 
					. $user_directory_border_color . ";\" class=\"dir-listing-border\" >";
		}


		if($user_directory_show_avatar)
			$user_contact_info .= "\n<DIV class=\"dir-listing\" style=\"min-height:" . $user_directory_avatar_size . "px;\">\n";
		else
			$user_contact_info .= "\n<DIV class=\"dir-listing\">\n";
					
		/*** Print Avatar ***/		
	    if($user_directory_show_avatar)
	    {          	
	       	if($user_directory_avatar_style === "rounded-edges")
	       	{
	       	 	$atts = array('class' => 'avatar-rounded-edges');
	       	 	$img_style = "avatar-rounded-edges";
	       	}
        	else if($user_directory_avatar_style === "circle")
        	{
               	$atts = array('class' => 'avatar-circle');
              	$img_style  = "avatar-circle";
            }
            else
            {
              	$atts = array('class' => '');
               	$img_style  = "";
            }
                         	
            if($user_directory_cimy)
               	$user_avatar_url = dynamic_ud_get_cimy_avatar($user_id, $username, $atts, $img_style, $cimy_avatar_loc );
            else
           		$user_avatar_url = get_avatar( $user_id, '', '', '', $atts );
			
			if(!empty($user_directory_avatar_size))
			{
				$user_avatar_url = str_replace ( "96", $user_directory_avatar_size, $user_avatar_url );
				$user_avatar_url = str_replace ( ">", " style=\"height:" . $user_directory_avatar_size . "px;width:100%;\">", $user_avatar_url );
			}
			
			/* Use this filter if your theme places the avatar somewhere other than the default path */
			$user_avatar_url = apply_filters('dud_set_avatar_url', $user_avatar_url, $user_id, $atts, $img_style);
            
			$user_avatar_url = apply_filters('dud_custom_avatar_url', $user_avatar_url, $user_id, $atts, $img_style, $dud_options);
			
			$user_avatar_url_path = $user_avatar_url;
						
			$user_avatar_url = dud_build_avatar_profile_link($dud_options, $user_id, $user_avatar_url);
			
			/* Use this filter if you need to manually build the avatar url link to a different profile/author page */
			$user_avatar_url = apply_filters('dud_set_avatar_link', $user_avatar_url, $user_avatar_url_path, $user_id);
								   	
			if(!empty($user_directory_avatar_size))
				$user_contact_info .= "\t<DIV id='avatar-size' style='position: absolute; width: " . $user_directory_avatar_size . "px;'>". $user_avatar_url . "</DIV>\n\t";
			else
				$user_contact_info .= "\t<DIV style='position: absolute;'>". $user_avatar_url . "</DIV>\n\t";
			
			if(!empty($user_directory_avatar_size) && empty($user_directory_avatar_padding)) $user_directory_avatar_padding = "90";
			
			if($user_directory_border === "surrounding_border")
				$user_contact_info .= "<DIV style='font-size:" . $user_directory_listing_fs . "px;padding-left: " . $user_directory_avatar_padding . "px;pointer-events:none;letter-spacing: " . $ud_letter_spacing . ";' class='dir-listing-text-surr-border'>\n\t\t";
			else
			    $user_contact_info .= "<DIV style='font-size:" . $user_directory_listing_fs . "px;padding-left: " . $user_directory_avatar_padding . "px;pointer-events:none;letter-spacing: " . $ud_letter_spacing . ";' class='dir-listing-text'>\n\t\t";
		}
		else	
			$user_contact_info .= "\n\t<DIV style='font-size:" 
				. $user_directory_listing_fs . "px;pointer-events:none;letter-spacing: " . $ud_letter_spacing . ";' class='dir-listing-text-no-avatar'>\n\t\t";
			
		/*** Sort Field field is always displayed first ***/
		if($user_fullname !== '' && !($ud_hide_username === "hide"))
			$user_contact_info .= "\t<div style='pointer-events:all;' class='dud_field_name'>" . $user_fullname . "</div>\n";
			 
		/*** Print remaining fields in the chosen display order ***/	 
		$css_line_cnt = 0;
		$meta_fld_cnt = 1;
	    $has_label = false;
		
		foreach ($sort_order_items as $item)
		{
			if($item === "Email")
			{
				if(!empty($user_email) || (!empty($ud_email_lbl) && $ud_show_fld_lbl_for_empty_fld === "yes")) {
					
					if(!empty($ud_email_lbl)) $ud_email_lbl = "<strong>" . $ud_email_lbl . "</strong> ";
					
					if($ud_email_format === "hyperlink" && ($user_directory_email && $user_email !== ''))
					{
						$user_contact_info .= "\t\t\t<div style='pointer-events:all;' class='dud_field_email'>$ud_email_lbl <a href=\"mailto:" . $user_email . "\" target=\"_blank\">" . apply_filters('dud_set_user_email_display', $user_email, $user_id) . "</a></div>\n";	
					}
					else
					{
						try
						{
							$a = $user_email;
							$b = "<!-- zke@unp -->";

							//get a random position in a
							$randPos = rand(0,strlen($a));
							//insert $b in $a
							$user_email = substr($a, 0, $randPos).$b.substr($a, $randPos);
						}
						catch(Exception $e)
						{;}
						
						$user_contact_info .= "\t\t\t<div style='pointer-events:all;' class='dud_field_email'>$ud_email_lbl " . apply_filters('dud_set_user_email_display', $user_email, $user_id) . "</div>\n";	
					}
				}
			}
			else if($item === "Website")
			{
				if(!empty($user_website) || (!empty($ud_website_lbl) && $ud_show_fld_lbl_for_empty_fld === "yes")) {
	
					$user_contact_info .= "\t\t\t<div style='pointer-events:all;' class='dud_field_website'>";
					
					if(!empty($ud_website_lbl)) $user_contact_info .= "<strong>" . $ud_website_lbl . "</strong> ";
						
					if($ud_website_format === "main") 
						$user_contact_info .= "<a href=\"" . $user_website . "\">" . $user_website . "</a></div>\n";
					else 
						$user_contact_info .= "<a href=\"" . $user_website . "\" target = \"_blank\">" . $user_website . "</a></div>\n";	
				}
			}
			else if($item === "DateRegistered")
			{
				if(!empty($user_registered ) || (!empty($ud_date_lbl) && $ud_show_fld_lbl_for_empty_fld === "yes")) {
									
					$user_registered = formatDateTime($user_registered, $ud_date_registered_format);
					
					if(!empty($ud_date_lbl)) 
						$user_registered = "<strong>" . $ud_date_lbl . "</strong> " . $user_registered;
					
					$user_contact_info .= "\t\t\t<div class='dud_field_date_registered'>" . $user_registered . "</div>\n";	
				}
			}
			else if($item === "UserRoles")
			{
				if(!empty($user_roles) || (!empty($ud_roles_lbl) && $ud_show_fld_lbl_for_empty_fld === "yes")) {
				
					if(!empty($ud_roles_lbl)) 
						$user_roles = "<strong>" . $ud_roles_lbl . "</strong> " . $user_roles;
					
					$user_contact_info .= "\t\t\t<div class='dud_field_user_roles'>" . $user_roles . "</div>\n";	
				}
			}
			else if($item === "Address")
			{
				if($user_directory_addr_1)  { $user_contact_info .= "\t\t\t<div class='dud_field_addr1'>" . $user_directory_addr_1 . "</div>\n";}
				if($user_directory_addr_2)  { $user_contact_info .= "\t\t\t<div class='dud_field_addr2'>" . $user_directory_addr_2 . "</div>\n";}
				if($user_directory_csz)     { $user_contact_info .= "\t\t\t<div class='dud_field_city_st_zip'>" .$user_directory_csz . "</div>\n";}
				if($user_directory_country) { $user_contact_info .= "\t\t\t<div class='dud_field_country'>" .$user_directory_country . "</div>\n";}
			}
			else if($item === "Social")
			{	
                $social_icons = "";
				
				if(!empty($social_flds[0]) || !empty($social_flds[1]) || !empty($social_flds[2]) || !empty($social_flds[3]) || !empty($social_flds[4]) || !empty($social_flds[5]) || !empty($social_flds[6]) || !empty($social_flds[7]) || !empty($social_flds[8]))
				{
					$social_flds = format_social_links($social_flds);
					
					$social_icons .= "\t\t\t<div style='pointer-events:all;padding-top:4px;' class='dud_field_social' style='font-size:" . $ud_icon_size . "px !important;'>";
					
					if(!empty($social_flds[0]))
					{						
						if($ud_icon_style === "1") $social_icons .= "<a href= '" . $social_flds[0] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-facebook-square' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
						else                       $social_icons .= "<a href= '" . $social_flds[0] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-facebook-f' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
					}
					if(!empty($social_flds[1]))
					{
						if($ud_icon_style === "1") $social_icons .= "<a href= '" . $social_flds[1] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fa-brands fa-square-x-twitter' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
						else                       $social_icons .= "<a href= '" . $social_flds[1] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fa-brands fa-x-twitter' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
					}
					if(!empty($social_flds[2]))
					{
						if($ud_icon_style === "1") $social_icons .= "<a href= '" . $social_flds[2] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-linkedin' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
						else                       $social_icons .= "<a href= '" . $social_flds[2] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-linkedin-in' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
					}
					if(!empty($social_flds[3]))
					{
						if($ud_icon_style === "1") $social_icons .= "<a href= '" . $social_flds[3] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-google-plus-square' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
						else                       $social_icons .= "<a href= '" . $social_flds[3] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-google-plus-g' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
					}
					if(!empty($social_flds[4]))
					{
						if($ud_icon_style === "1") $social_icons .= "<a href= '" . $social_flds[4] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-pinterest-square' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
						else                       $social_icons .= "<a href= '" . $social_flds[4] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-pinterest-p' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
					}
					if(!empty($social_flds[5]))
					{
						if($ud_icon_style === "1") $social_icons .= "<a href= '" . $social_flds[5] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-instagram-square' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
						else                       $social_icons .= "<a href= '" . $social_flds[5] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-instagram' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
					}
					if(!empty($social_flds[6]))
					{
						if($ud_icon_style === "1") $social_icons .= "<a href= '" . $social_flds[6] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-youtube-square' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
						else                       $social_icons .= "<a href= '" . $social_flds[6] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-youtube-square' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
					}
					if(!empty($social_flds[7]))
					{
						if($ud_icon_style === "1") $social_icons .= "<a href= '" . $social_flds[7] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-tiktok' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
						else                       $social_icons .= "<a href= '" . $social_flds[7] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fab fa-tiktok' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
					}
					if(!empty($social_flds[8]))
					{
						if($ud_icon_style === "1") $social_icons .= "<a href= '" . $social_flds[8] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fas fa-podcast' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
						else                       $social_icons .= "<a href= '" . $social_flds[8] . "' target='_blank' style='color:" . $ud_icon_color . ";'><i class='fas fa-podcast' style='color:" . $ud_icon_color . "!important;font-size:" . $ud_icon_size . "px !important;' aria-hidden='true'></i></a>&nbsp;";
					}
									
					$user_contact_info .= apply_filters('dud_modify_social_fld_icons', $social_icons, $ud_icon_style, $ud_icon_color, $ud_icon_size, $user_id, $loaded_options);
					
					$user_contact_info .= "</div>";
				}
				else 
				{
					
					$icons_tmp = apply_filters('dud_modify_social_fld_icons', $social_icons, $ud_icon_style, $ud_icon_color, $ud_icon_size, $user_id, $loaded_options);
					
					if(!empty($icons_tmp))
					{
						$user_contact_info .= "\t\t\t<div style='pointer-events:all;' class='dud_field_social' style='font-size:" . $ud_icon_size . "px !important;'>" . $icons_tmp . "</div>";
					}
				}
			}
			else
			{
				foreach ( $user_directory_meta_flds_tmp as $ud_mflds )
				{
					if(!empty($ud_mflds['key']) && $item === $ud_mflds['key'])			
					{
						//Print the field label						
						if((!empty($ud_mflds['value']) || $ud_mflds['value'] === "0" || ($ud_show_fld_lbl_for_empty_fld === "yes" && !empty($ud_mflds['label']))) && !empty($ud_mflds['label'])) 
						{
							$css_line_cnt++;
							$user_contact_info .= "\t\t\t<div style='pointer-events:all;' class='dud_line_" . $css_line_cnt . "'><span class='dud_label'><b>" . $ud_mflds['label'] . "</b></span>\n";
							$has_label = true;
							
							if($ud_mflds['format'] === '24')      //Multi-line Text Box
								$user_contact_info .= "<br>";
						}
							
						//Print the field
						if(!empty($ud_mflds['value']) || $ud_mflds['value'] === "0" || ($ud_show_fld_lbl_for_empty_fld === "yes" && $has_label))
						{
							$dud_line = "";
								
							if(!$has_label) 
							{
								$css_line_cnt++;								
								$dud_line = "<div style='pointer-events:all;' class='dud_line_" . $css_line_cnt . "'>";
							}
							else if($has_label && empty($ud_mflds['value']) && $ud_mflds['value'] !== "0")
							{
								$meta_fld_cnt++;	
								$user_contact_info .= "</div>";
								break;
							}								
								
							if($ud_mflds['format'] === '2')       //Hyperlink
							{
								if(strlen($ud_mflds['value']) >= 8 && substr($ud_mflds['value'], 0, 4) !== "http")
									$ud_mflds['value'] = "http://" . $ud_mflds['value'];
									
								$user_contact_info .= "\t\t\t$dud_line<span class='dud_field_" . $meta_fld_cnt . "'><a href=\"" .$ud_mflds['value'] . "\">" . $ud_mflds['value'] . "</a></span><br>\n";
							}								
							else if($ud_mflds['format'] === '3')  //Hyperlink in a new window
							{
								if(strlen($ud_mflds['value']) >= 8 && substr($ud_mflds['value'], 0, 4) !== "http")
									$ud_mflds['value'] = "http://" . $ud_mflds['value'];
									
								$user_contact_info .= "\t\t\t$dud_line<span class='dud_field_" . $meta_fld_cnt . "'><a href=\"" .$ud_mflds['value'] . "\" target=\"_blank\">" . $ud_mflds['value'] . "</a></span><br>\n";
							}								
							else if($ud_mflds['format'] === '25') //Email hyperlink 
								$user_contact_info .= "\t\t\t$dud_line<span class='dud_field_" . $meta_fld_cnt . "'><a href=\"mailto:" . $ud_mflds['value'] . "\" target=\"_top\">" . apply_filters('dud_set_user_email_display', $ud_mflds['value'], $user_id) . "</a></span><br>\n";	
							else
								$user_contact_info .= "\t\t\t$dud_line<span class='dud_field_" . $meta_fld_cnt . "'>" . $ud_mflds['value'] . "</span><br>\n"; 
												
							$meta_fld_cnt++;
							
							$user_contact_info .= "</div>";
							break;
						}
					}
				}
			}
		
			$has_label = false;
		}
				 	
		/*** Close the proper divs and print the dividing border if that is being used ***/					
		$user_contact_info .= "\t</DIV>\n</DIV>\n";	
										
		if($user_directory_border === "surrounding_border")
			$user_contact_info .= "</DIV>\n"; 	

		$listing_cnt++;
	  			
	} //END foreach ($uids as $uid)
	
	if($dud_user_srch_name && $listing_cnt < 1)
	{		
		if($custom_sort_active && !$meta_flds_srch_active && !empty($ud_show_srch) && !empty($ud_hide_before_srch) && 
			($ud_sort_show_categories_as === 'dd' || $ud_sort_show_categories_as === 'dd-srch') && 
				$ud_sort_show_cats_dd_hide_dir_before_srch === 'no')
		{
			$user_contact_info = "<form id='dud_user_srch' method='post'>\n";		
			$user_contact_info .= dud_get_basic_srch_form_html($custom_sort_active, $dud_options);
			$user_contact_info .= "</form><BR>\n"; 
			$user_contact_info .= apply_filters('dud_search_err', $no_users_found_err);
		}
		else
			$user_contact_info .= apply_filters('dud_search_err', $no_users_found_err);
	}		
	
	/* Number of Users Stuff */
	$show_num_users = show_num_users(false, $dud_options);
	
	if($show_num_users && !empty($dud_user_srch_name) && !$meta_flds_srch_active) 
		$total_users = dud_cnt_last_name_srch_results($uids, $dud_user_srch_name, $user_directory_sort);
	
	if(!empty($ud_txt_after_num_users_srch) && dud_endswith($ud_txt_after_num_users_srch, "results") && $total_users === 1)
		$ud_txt_after_num_users_srch = substr($ud_txt_after_num_users_srch, 0, -1);
		
	if(($ud_show_pagination_top_bottom_page === "bottom" || $ud_show_pagination_top_bottom_page === "both") 
			&& empty($dud_user_srch_name))
		$ud_num_users_notification = $total_users . " " . $ud_txt_after_num_users;
	else
		$ud_num_users_notification = $total_users . " " . $ud_txt_after_num_users_srch;
	
	/* Print pagination at bottom */
	if(!empty($ud_users_per_page) && $ud_users_per_page > 0 
		&& ($ud_show_pagination_top_bottom_page === "bottom" || $ud_show_pagination_top_bottom_page === "both")
		&& ! (!in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $plugins ) && $dud_user_srch_name) )
	{
		$print_pagination = dud_print_pagination(count((array)$uids), $ud_users_per_page, $ud_directory_type, $ud_show_srch, $last_name_letter, 
			$dud_user_srch_name, $dud_user_srch_key, $plugins, $ud_pagination_font_size, $ud_pagination_link_color, 
				$ud_pagination_link_clicked_color, $dud_options, false);
		
		$user_contact_info .= $print_pagination;
		
        /* Print total num users if needed */		
		if((empty($print_pagination) || $ud_directory_type !== "all-users") && $show_num_users)
		{	
			if(substr($user_contact_info, -5, 4) == '<BR>' && $meta_flds_srch_active) { $user_contact_info = substr($user_contact_info, 0, -5); }
			
			if($ud_num_users_border)
				$user_contact_info .= "<div style='width: " . $ud_num_users_border . ";border-color: " . $ud_num_users_border_color . ";' class='dud_total_users_border'></div>"; 
			
			$user_contact_info .= "<div style='font-size:" . $ud_num_users_font_size . "px;' class='dud_total_users'>" .  apply_filters('dud_directory_totals_notification', $ud_num_users_notification, $dud_options) . "</div>";
		}
	}
	else if($show_num_users)
	{
		if(substr($user_contact_info, -5, 4) == '<BR>' && $meta_flds_srch_active) { $user_contact_info = substr($user_contact_info, 0, -5); }
		
		if($ud_num_users_border)
				$user_contact_info .= "<div style='width: " . $ud_num_users_border . ";border-color: " . $ud_num_users_border_color . ";' class='dud_total_users_border'></div>"; 
			
		$user_contact_info .= "<div style='font-size:" . $ud_num_users_font_size . "px;' class='dud_total_users'>" .  apply_filters('dud_directory_totals_notification', $ud_num_users_notification, $dud_options) . "</div>";
	}
		
	if($export_active && $dud_export_link_position === "bottom")
	{
		$export_html = dud_export_dir($loaded_options);
		if(!empty($export_html))
			$user_contact_info .= $export_html . "<BR>";
	}
	
	return $user_contact_info;
}
else // No uids were found
{
	$user_contact_info                		   = "";
	$user_directory_srch_fld                   = "";
	$ud_srch_style                     		   = !empty($dud_options['ud_srch_style']) ? $dud_options['ud_srch_style'] : null;
	$user_directory_border_length      	       = !empty($dud_options['user_directory_border_length']) ? $dud_options['user_directory_border_length'] : null;
	$ud_hide_before_srch			   		   = !empty($dud_options['ud_hide_before_srch']) ? $dud_options['ud_hide_before_srch'] : null;
	$ud_sort_show_cats_dd_hide_dir_before_srch = !empty($dud_options['ud_sort_show_cats_dd_hide_dir_before_srch']) ? $dud_options['ud_sort_show_cats_dd_hide_dir_before_srch'] : "Yes";
	$ud_sort_cat_header          		   	   = !empty($dud_options['ud_sort_cat_header']) ? $dud_options['ud_sort_cat_header'] : null;
	$dir_is_hidden 							   = false;
	$printed_custom_sort_links                 = false;
		
	if($uids == 0 && $ud_hide_before_srch) $dir_is_hidden = true;           //flag fld indicating if the directory is being hidden
	
	$user_contact_info .= "<a name='dud-top-of-directory'></a>";
	
	if((!empty($dud_user_srch_name) || !empty($search_category)) || $srch_err === $invalid_val_err || $srch_err === $exclude_users_performance_err ||
				($uids == 0 && $ud_hide_before_srch)) 					   //checking for 0 allows the dud_after_load_uids filter to clear out the directory data but still  
	{                                     							       //show the meta fld search box, for those who only want to show the directory when a search is run
		if(count((array)$uids) == 0 && (!empty($dud_user_srch_name) || !empty($search_category)) && empty($srch_err))
		{
			$srch_err = $no_users_found_err;
		}		
		
		if($uids != 0)
		{
			/*** Letter links dir ***/
			if(!($ud_directory_type === "all-users"))
			{
				/*** Meta fld search add-on ***/
				if ( $meta_flds_srch_active && !$custom_sort_active ) 
				{
					if(has_filter( 'dud_meta_fld_srch_print_alpha_links_v_1_6', 'dud_meta_fld_srch_print_alpha_links_v_1_6' ))
					{
						$alpha_links = apply_filters('dud_meta_fld_srch_print_alpha_links_v_1_6', $letters, $dud_options, $dud_user_srch_key, 
							$dud_user_srch_name, $last_name_letter) . "<BR>";
					}
					else
					{
						$alpha_links = apply_filters('dud_meta_fld_srch_print_alpha_links', $letters, $dud_options['ud_alpha_link_spacer'], 
								$dud_options['user_directory_letter_fs'], $dud_user_srch_key, $dud_user_srch_name ) . "<BR>";
					}
				}
				/*** Custom sort fld add-on ***/
				else if($custom_sort_active) 
				{
					$printed_custom_sort_links = true;
					$alpha_links = dud_print_alpha_links_custom_sort_fld($letters, $last_name_letter, false, $dud_user_srch_key, $dud_user_srch_name, $plugins, $dud_options);
				}				
				/*** Standard DUD alpha links ***/
				else
				{
					$alpha_links = dynamic_ud_print_alpha_links($letters, $dud_options['ud_alpha_link_spacer'], $dud_options['user_directory_letter_fs'], $ud_alpha_link_clicked_color, $ud_alpha_link_color, $letter) . "<BR>";
				}
				$user_contact_info .= $alpha_links;
			}
			/*** Single Page Dir ***/
			else
			{
				 /* Alpha Links Scroll add-on */
				if(! (!empty($ud_users_per_page) && $ud_users_per_page > 0) ) //hide letter links if printing pagination instead (single page dir only)
				{
					//If doing a basic last name search, don't print the scroll links at the top.
					if ( !(!$meta_flds_srch_active && !$custom_sort_active && $ud_show_srch && !empty($dud_user_srch_name))
						&& ! ($custom_sort_active && $ud_sort_cat_header === "nch") 
						&& ! ($custom_sort_active && !empty($dud_user_srch_name) && $ud_show_srch 
								&& !$meta_flds_srch_active 
									&& $ud_sort_show_categories_as == "links"))  
						$user_contact_info .= apply_filters( 'dud_print_scroll_letter_links', $user_contact_info, $letters, $loaded_options );
				}
			}	
		}
			
        /* Print Basic Search Box */			
		$user_srch_placeholder_txt = "Last Name";
	
		if($user_directory_sort !== "last_name")
			$user_srch_placeholder_txt = "Display Name";
	
		if($ud_srch_style === "transparent") $ud_srch_style = "background:none;";
		else $ud_srch_style = "";   
				
		//Show the custom sort categories dropdown if that option is set to Yes in the Custom Sort settings
		
		//If there was a basic last name search and the custom sort field add-on is active
		if($custom_sort_active && !$meta_flds_srch_active)
		{
			if(!$printed_custom_sort_links && (( $ud_sort_show_categories_as === "dd" || $ud_sort_show_categories_as === "dd-srch" || $ud_sort_show_categories_as === "dd-links" ) 
					&& (empty($ud_hide_before_srch) || $ud_sort_show_cats_dd_hide_dir_before_srch === "yes")))
			{
				$printed_custom_sort_links = true;
				$user_directory_srch_fld .= dud_print_alpha_links_custom_sort_fld($letters, $last_name_letter, false, $dud_user_srch_key, $dud_user_srch_name, $plugins, $dud_options);		
			}
			
			if( ($ud_sort_show_categories_as === 'links' && empty($srch_err)) || 
					(!empty($ud_show_srch) && !empty($ud_hide_before_srch) && $ud_sort_show_cats_dd_hide_dir_before_srch === 'no' && !$printed_custom_sort_links) )
			{
				$user_directory_srch_fld .= "<form id='dud_user_srch' method='post'>\n";		
				$user_directory_srch_fld .= dud_get_basic_srch_form_html($custom_sort_active, $dud_options);
				$user_directory_srch_fld .= "</form><BR>\n"; 
			}
		}
		
		if(!$custom_sort_active)
		{
			$user_directory_srch_fld .= "<form id='dud_user_srch' method='post'>\n";		
			$user_directory_srch_fld .= dud_get_basic_srch_form_html($custom_sort_active, $dud_options);
			$user_directory_srch_fld .= "</form><BR>\n"; 
		}
		
		//Meta Fields Search form
		$user_directory_srch_fld = apply_filters('dud_build_srch_form', $user_directory_srch_fld, $loaded_options, $dud_options, $letters, $dir_is_hidden);
		
		if($srch_err)
		{
			$user_contact_info .= $user_directory_srch_fld . apply_filters('dud_no_users_msg', $srch_err);
		}
		else
			$user_contact_info .= $user_directory_srch_fld;
				
		return $user_contact_info;
		
	}
	else  
	{
		if(empty($srch_err))
			return apply_filters('dud_no_users_msg', $empty_dir_err);
		else
			return apply_filters('dud_no_users_msg', $srch_err);	
	}
}
	
}
add_shortcode( 'DynamicUserDirectory', 'DynamicUserDirectory' );

//** DUD UTILITY FUNCTIONS *********************************************************************************/

function show_num_users($is_top, $dud_options)
{
	$ud_show_num_users              = !empty($dud_options['ud_show_num_users']) ? $dud_options['ud_show_num_users'] : "";
	$ud_num_users_top_bottom        = !empty($dud_options['ud_num_users_top_bottom']) ? $dud_options['ud_num_users_top_bottom'] : "";
	
	$dud_user_srch_name 			= !empty($_REQUEST ["dud_user_srch_val"]) ? $_REQUEST ["dud_user_srch_val"] : null;
	
	if((($ud_show_num_users === "results" && !empty($dud_user_srch_name)) ||  $ud_show_num_users === "both" )
		&& (($is_top && $ud_num_users_top_bottom === "top" || $ud_num_users_top_bottom === "both")
			|| (!$is_top && $ud_num_users_top_bottom === "bottom" || $ud_num_users_top_bottom === "both")))
	{
		return true;
	}
	
	return false;
}

function dud_get_basic_srch_form_html($custom_sort_active, $dud_options)
{
	$ud_srch_style 			= !empty($dud_options['ud_srch_style']) ? $dud_options['ud_srch_style'] : null;
	$user_directory_sort  	= !empty($dud_options['user_directory_sort']) ? $dud_options['user_directory_sort'] : null;
	$ud_hide_before_srch	= !empty($dud_options['ud_hide_before_srch']) ? $dud_options['ud_hide_before_srch'] : null;
	$ud_sort_show_cats_dd_hide_dir_before_srch = !empty($dud_options['ud_sort_show_cats_dd_hide_dir_before_srch']) ? $dud_options['ud_sort_show_cats_dd_hide_dir_before_srch'] : "Yes";
	
	$user_srch_placeholder_txt = "Last Name";
	$user_directory_srch_fld = "";
	
	if($user_directory_sort !== "last_name")
		$user_srch_placeholder_txt = "Display Name";

	$user_srch_placeholder_txt = apply_filters( 'dud_srch_fld_placeholder_txt', $user_srch_placeholder_txt );
	
	if($ud_srch_style === "transparent") $ud_srch_style = "background:none;";
	else $ud_srch_style = "";
	
	$user_directory_srch_fld .= "    \t<DIV id='user-srch' style='width:350px;'>\n";
	$user_directory_srch_fld .= "          \t\t<input type='hidden' name='export_dir' id='export_dir' value='y'/><input type='text' id='dud_user_srch_val' name='dud_user_srch_val' style='"  . $ud_srch_style . "'"; 
	$user_directory_srch_fld .= " value='' maxlength='50' placeholder='" . $user_srch_placeholder_txt . "'/>\n";
	$user_directory_srch_fld .= "        \t\t<button type='submit' id='dud_user_srch_submit' name='dud_user_srch_submit' value=''>\n";
	$user_directory_srch_fld .= "             \t\t\t<i class='fa fa-search fa-lg' aria-hidden='true'></i>\n";
	$user_directory_srch_fld .= "        \t\t</button>\n";
	$user_directory_srch_fld .= "     \t</DIV>\n";
		
	return $user_directory_srch_fld;
}

function dud_cnt_user_posts($user_id)
{
	global $wpdb;
	
	$sql = "SELECT COUNT(ID) as num_posts
			FROM " . DUD_WPDB_PREFIX . "posts
			WHERE ( ( post_type = 'post'
			AND ( post_status = 'publish'
			OR post_status = 'private' ) ) )
			AND post_author = " . $user_id;
	
	$dud_posts = $wpdb->get_results($sql);
	
	if(empty($dud_posts))
		return 0;
	
	else
	{
		foreach ($dud_posts as $dud_post)
		{
			return $dud_post->num_posts;
		}
	}
}

function dynamic_ud_format_meta_val($user_meta_fld, $dud_options, $format, $label = null)
{	
	$parsed_val = "";
	$numeric_idx = false;
	$key_val_array = false;
	$ud_display_listings = !empty($dud_options['ud_display_listings']) ? $dud_options['ud_display_listings'] : "";
	$inc = 1;
				
	//*** Image ***********************************************
	if(!is_array($user_meta_fld) && $format === "34")
	{
		if(empty($user_meta_fld)) return "";
		
		return "<img class='dud_img' src='" .  $user_meta_fld . "'>";		
	}
	//*** Phone ************************************************
	if(!is_array($user_meta_fld) && (($format === "6") || ($format === "31") || ($format === "32") || ($format === "33")))
	{
		if(($format === "6"))
			return formatPhoneNumber($user_meta_fld);
		else if($format === "31")
			return formatAustralianPhoneNumber($user_meta_fld);
		else if($format === "32" || $format === "33")
		{
			if($format === "32")
				$phone = formatPhoneNumber($user_meta_fld);
			else
				$phone = formatMobileAusPhoneNumber($user_meta_fld);
			
			if(!empty($phone))
				return "<a href='tel:" . $phone . "'>" . $phone . "</a>";
		}	
	}
	//*** Date *************************************************
	else if(!is_array($user_meta_fld) && ($format === "16" || $format === "17" || $format === "18" || $format === "19" 
										  || $format === "20" || $format === "21" || $format === "22" || $format === "23"))
	{
		if(empty($user_meta_fld)) return "";
		
		try
		{
			//return formatDateTime(new DateTime($user_meta_fld), $format);
			return formatDateTime($user_meta_fld, $format);
		}
		catch(Exception $e)
		{
			return $user_meta_fld;
		}
	}
	else if($format === "56")
	{
		try
		{
			$birthday = new DateTime($user_meta_fld);
			$interval = $birthday->diff(new DateTime);
			return $interval->y;
		}
		catch(Exception $e)
		{
			return $user_meta_fld;
		}
	}
	//*** Email Address Plain Text Anti-Spam *************************
	else if($format === "55")
	{
		try
		{
			$a = $user_meta_fld;
			$b = "<!-- zke@unp -->";

			//get a random position in a
			$randPos = rand(0,strlen($a));
			//insert $b in $a
			return substr($a, 0, $randPos).$b.substr($a, $randPos);
		}
		catch(Exception $e)
		{
			return $user_meta_fld;
		}
	}
	//*** Multi-line Text *********************************************
	else if(!is_array($user_meta_fld) && $format === "24")
	{
		return str_replace ( "\n", "<BR>", $user_meta_fld);
	}
	//*** Hide Hyphens *************************************************
	else if(!is_array($user_meta_fld) && ($format === "30" || $format === "37" || $format === "38"))
	{
		$user_meta_fld = str_replace ( "-", " ", $user_meta_fld);
		
		if($format === "30") //first letter in caps
			return ucwords($user_meta_fld);
		else if ($format === "37") //all caps
			return strtoupper($user_meta_fld);	
		else if ($format === "38") //all lower case
			return strtolower($user_meta_fld);	
	}
	
	//*** Multiselect Boxes & Checkboxes *******************************
	else if(!is_array($user_meta_fld))
	{
		$non_array_numeric_idx = false;
				
		if(strlen($user_meta_fld) > 2 && substr($user_meta_fld, 0, 2) === "a:")
		{
			$list_items = unserialize(stripslashes($user_meta_fld));
			
			if(empty($list_items)) return "";
			
			$inc = 1;
			
			foreach($list_items  as $key => $value)
			{
				//Hide Hyphens & Capitalization
				if($format === "5" || $format === "39" || $format === "40"
				   || $format === "4" || $format === "41" || $format === "42"
				   || $format === "45" || $format === "43" | $format === "44"
				   || $format === "47" || $format === "13" || $format === "15" 
				   || $format === "48" || $format === "49" || $format === "50"
				   || $format === "53" || $format === "54")
				{
					$value = str_replace ( "-", " ", $value);
					
					if($format === "39" || $format === "41" || $format === "43" || $format === "44" || $format === "53")
						$value = strtoupper($value);	
					else if($format === "40" || $format === "42" || $format === "47" || $format === "48" || $format === "54")
						$value = strtolower($value);
				    else 
						$value = ucwords($value);
				}
								
				if (is_string($key))
				{					
					//Comma delimited options
					if($format === "4" || $format === "36" || $format === "41" || $format === "42" ||
						$format === "8" || $format === "46" || $format === "44" || $format === "48")
					{
						if($inc > 1)
							$parsed_val .= ", ";
					}
					else if($inc > 1)
						$parsed_val .= "<BR>";	
					else if($ud_display_listings !== "horizontally" && !empty($label) &&
						$format !== "13" && $format !== "14" && $format !== "15" && $format !== "53" && $format !== "54" && $inc == 1)
						$parsed_val .= "<BR>";	
				
					if($format === "5" || $format === "39" || $format === "40" || $format === "43" || $format === "45")
						$parsed_val .= "<i class=\"fas fa-circle dud-fa-bullet\" aria-hidden=\"true\"></i>" . $value;
					else if($format === "11" || $format === "35")
						$parsed_val .= "<i class=\"fas fa-circle dud-fa-bullet\" aria-hidden=\"true\"></i>" . $key;
					else if($format === "49")
						$parsed_val .= "<i class=\"fas fa-circle dud-fa-bullet\" aria-hidden=\"true\"></i>" . $key . ": " . $value;
					else if($format === "13" || $format === "44" || $format === "49" || $format === "50")
						$parsed_val .= $key . ": " . $value;
					else if($format === "14" || $format === "8" || $format === "36" )
						$parsed_val .= $key;
					else
						$parsed_val .= $value;
				}
				else 
				{
					$non_array_numeric_idx = true;					
					break;
				}
				
				$inc++;
			}
			
			if($non_array_numeric_idx)
			{					
				foreach($list_items  as $listitem)
				{
					//Hide Hyphens & Capitalization
					if($format === "5" || $format === "39" || $format === "40"
					   || $format === "4" || $format === "41" || $format === "42"
					   || $format === "45" || $format === "43" | $format === "44"
					   || $format === "47" || $format === "13" || $format === "15" 
					   || $format === "48" || $format === "50"
					   || $format === "35" || $format === "36" || $format === "11" || $format === "8"
					   || $format === "53" || $format === "54")
					{
						$listitem = str_replace ( "-", " ", $listitem);
						
						if($format === "39" || $format === "41" || $format === "43" || $format === "44" || $format === "53")
							$listitem = strtoupper($listitem);	
						else if($format === "40" || $format === "42" || $format === "47" || $format === "48" || $format === "54")
							$listitem = strtolower($listitem);	
						else 
							$listitem = ucwords($listitem);
					}
				
					//Comma delimited options
					if($format === "4" || $format === "36" || $format === "41" || $format === "42" ||
						$format === "8" || $format === "46" || $format === "44" || $format === "48")
					{
						if($inc > 1)
							$parsed_val .= ", ";
					}
					else if($inc > 1)
						$parsed_val .= "<BR>";	
					else if($ud_display_listings !== "horizontally" && !empty($label) &&
						$format !== "13" && $format !== "14" && $format !== "15" && $format !== "53" && $format !== "54" && $inc == 1)
						$parsed_val .= "<BR>";	
					
					if($format === "5" || $format === "35" || $format === "39" || $format === "40" || $format === "11"
						|| $format === "43" || $format === "45" || $format === "47" )
						$parsed_val .= "<i class=\"fas fa-circle dud-fa-bullet\" aria-hidden=\"true\"></i>" . $listitem;
					else
						$parsed_val .= $listitem; 
					
					$inc++;
				}
			}
			
			return $parsed_val;
		}
	}
	else if(is_array($user_meta_fld))
	{
		$inc = 1;
		
		foreach ($user_meta_fld as $key => $value) 
		{
			if (is_string($key))
			{			
				if(is_array($value)) //there are nested arrays
					$parsed_val .= "<BR>" . var_export($value, true);	
				else                 //add key-value pair to the meta fld var
				{
					//Hide Hyphens & Capitalization
					if($format === "5" || $format === "39" || $format === "40"
					   || $format === "4" || $format === "41" || $format === "42"
					   || $format === "45" || $format === "43" | $format === "44"
					   || $format === "47" || $format === "13" || $format === "15" 
					   || $format === "48" || $format === "49" || $format === "50"
					   || $format === "35" || $format === "36" || $format === "11" || $format === "8"
					   || $format === "53" || $format === "54")
					{
						$value = str_replace ( "-", " ", $value);
						
						if($format === "39" || $format === "41" || $format === "43" || $format === "44" || $format === "53")
							$value = strtoupper($value);	
						else if($format === "40" || $format === "42" || $format === "47" || $format === "48" || $format === "54")
							$value = strtolower($value);
						else 
							$value = ucwords($value);
					}
						
					//Add comma for comma delimited options
					if($format === "4" || $format === "36" || $format === "41" || $format === "42" ||
						$format === "8" || $format === "46" || $format === "44" || $format === "48")
					{
						if($inc > 1)
							$parsed_val .= ", ";
					}
					//Add BR tag for multi value flds
					else if($inc > 1)		
						$parsed_val .= "<BR>";	
					else if($ud_display_listings !== "horizontally" && !empty($label) &&
						$format !== "13" && $format !== "14" && $format !== "15" && $format !== "53" && $format !== "54" && $inc == 1)
						$parsed_val .= "<BR>";	
					
					$key_val_array = true;
										
					if($format === "5" || $format === "39" || $format === "40" || $format === "43" || $format === "45") 
						$parsed_val .= "<i class=\"fas fa-circle dud-fa-bullet\" aria-hidden=\"true\"></i>" . $value;
					else if($format === "11" || $format === "35") 
						$parsed_val .= "<i class=\"fas fa-circle dud-fa-bullet\" aria-hidden=\"true\"></i>" . $key ;
					else if($format === "49")
						$parsed_val .= "<i class=\"fas fa-circle dud-fa-bullet\" aria-hidden=\"true\"></i>" . $key . ": " . $value;
					else if($format === "14" || $format === "8" || $format === "36" ) 
						$parsed_val .= $key; 
					else if($format === "13" || $format === "44" || $format === "49" || $format === "50")
						$parsed_val .= $key . ": " . $value;
					else 			
						$parsed_val .= $value;
					
					$inc++;
				}
			} 
			else
			{
				$numeric_idx = true;
				break;
			}
		}
		
		$inc = 1;
		
		if($numeric_idx)
		{
			for($met=0; $met < sizeof($user_meta_fld); $met++) 
			{
				if($user_meta_fld[$met])
				{
					if(is_array($user_meta_fld[$met])) //there are nested arrays
						$parsed_val .= var_export($user_meta_fld[$met], true);
					else                               //add the item to the meta fld var
					{
						
						//Hide Hyphens & Capitalization
						if($format === "5" || $format === "39" || $format === "40"
						   || $format === "4" || $format === "41" || $format === "42"
						   || $format === "45" || $format === "43" | $format === "44"
						   || $format === "47" || $format === "13" || $format === "15" 
						   || $format === "48" || $format === "50"
						   || $format === "53" || $format === "54")
						{
							$listitem = str_replace ( "-", " ", $listitem);
							
							if($format === "39" || $format === "41" || $format === "43" || $format === "44")
								$user_meta_fld[$met] = strtoupper($user_meta_fld[$met]);	
							else if($format === "40" || $format === "42" || $format === "47" || $format === "48")
								$user_meta_fld[$met] = strtolower($user_meta_fld[$met]);	
							else 
								$user_meta_fld[$met] = ucwords($user_meta_fld[$met]);
						}
						
						//Comma delimited options
						if($format === "4" || $format === "36" || $format === "41" || $format === "42" ||
							$format === "8" || $format === "46" || $format === "44" || $format === "48")
						{
							if($inc > 1)
								$parsed_val .= ", ";
						}
						else if($inc > 1)
							$parsed_val .= "<BR>";	
						else if($ud_display_listings !== "horizontally" && !empty($label) &&
							$format !== "13" && $format !== "14" && $format !== "15" && $format !== "53" && $format !== "54" && $inc == 1)
							$parsed_val .= "<BR>";	
											
						if($format === "5" || $format === "35" || $format === "39" || $format === "40" || $format === "11"
							|| $format === "43" || $format === "45" || $format === "47" )
							$parsed_val .= "<i class=\"fas fa-circle dud-fa-bullet\" aria-hidden=\"true\"></i>" . $user_meta_fld[$met];
						else
							$parsed_val .= $user_meta_fld[$met]; 
					
						$inc++;
					}
				}
			}
		}
				
		if($key_val_array)
			$parsed_val = apply_filters('dud_format_key_val_array', $parsed_val, $user_meta_fld, $format);
		
		return stripslashes($parsed_val);
	}
	
	return stripslashes($user_meta_fld);	
}

function formatPhoneNumber($phoneNumber) 
{
	try
	{
		$phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);

		if(strlen($phoneNumber) > 10) {
			$countryCode = substr($phoneNumber, 0, strlen($phoneNumber)-10);
			$areaCode = substr($phoneNumber, -10, 3);
			$nextThree = substr($phoneNumber, -7, 3);
			$lastFour = substr($phoneNumber, -4, 4);

			$phoneNumber = '+'.$countryCode.' ('.$areaCode.') '.$nextThree.'-'.$lastFour;
		}
		else if(strlen($phoneNumber) == 10) {
			$areaCode = substr($phoneNumber, 0, 3);
			$nextThree = substr($phoneNumber, 3, 3);
			$lastFour = substr($phoneNumber, 6, 4);

			$phoneNumber = '('.$areaCode.') '.$nextThree.'-'.$lastFour;
		}
		else if(strlen($phoneNumber) == 7) {
			$nextThree = substr($phoneNumber, 0, 3);
			$lastFour = substr($phoneNumber, 3, 4);

			$phoneNumber = $nextThree.'-'.$lastFour;
		}

		return $phoneNumber;
	}
	catch(Exception $e)
	{
		return $phoneNumber;
	} 
}

function formatAustralianPhoneNumber($phone) 
{
	//Australia's format is (XX)-XXXX-XXXX. May be prefixed by country code 61. 
	try
	{
		if(!empty($phone) && strlen($phone) == 10)
		{			
			$firstTwo = substr($phone, 0, 2);
			$nextFour = substr($phone, 2, 4);
			$lastFour = substr($phone, 6, 4);
		
			$phone = "(" . $firstTwo . ") " . $nextFour . " " . $lastFour;	
		}
		else if(!empty($phone) && strlen($phone) == 12 && substr($phone, 0, 2) === "61")
		{
			$firstTwo = substr($phone, 2, 2);
			$nextFour = substr($phone, 4, 4);
			$lastFour = substr($phone, 8, 4);	
			
			$phone = "+61 (" . $firstTwo . ") " . $nextFour . " " . $lastFour;
		}
		else if(!empty($phone) && strlen($phone) == 13 && substr($phone, 0, 3) === "+61")
		{
			$firstTwo = substr($phone, 3, 2);
			$nextFour = substr($phone, 5, 4);
			$lastFour = substr($phone, 9, 4);	
			
			$phone = "+61 (" . $firstTwo . ") " . $nextFour . " " . $lastFour;
		}

		return $phone;
	}
	catch(Exception $e)
	{
		return $phone;
	} 
}

function formatMobileAusPhoneNumber($phone) 
{
	//Mobile numbers are conventionally written 04xx xxx xxx. 
	//If a landline or mobile number is written where it may be viewed by an international audience 
	//(e.g. in an email signature or on a website) then the number is often written as +61 x xxxx xxxx 
	//or +61 04xx xxx xxx respectively.
	
	try
	{
		//echo "Before if Aus mobile phone is " . $phone . "<BR>";
		
		if(!empty($phone) && strlen($phone) == 10)
		{
			$firstFour = substr($phone, 0, 4);
			$nextThree = substr($phone, 4, 3);
			$lastThree = substr($phone, 7, 3);

			$phone = "+61 " . $firstFour . " " . $nextThree . " " . $lastThree;	
		}
		else if(!empty($phone) && strlen($phone) == 12 && substr($phone, 0, 2) === "61")
		{
			$firstFour = substr($phone, 2, 4);
			$nextThree = substr($phone, 6, 3);
			$lastThree = substr($phone, 9, 3);	
			
			$phone = "+61 " . $firstFour . " " . $nextThree . " " . $lastThree;
		}
		else if(!empty($phone) && strlen($phone) == 13 && substr($phone, 0, 3) === "+61")
		{
			$firstFour = substr($phone, 3, 4);
			$nextThree = substr($phone, 7, 3);
			$lastThree = substr($phone, 10, 3);	
			
			$phone = "+61 " . $firstFour . " " . $nextThree . " " . $lastThree;
		}
		
		return $phone;
	}
	catch(Exception $e)
	{
		return $phone;
	} 
}

function formatDateTime($user_meta_fld, $format) 
{ 

	//If this is a Unix timestamp
    if( is_numeric($user_meta_fld) && (int)$user_meta_fld == $user_meta_fld  && strlen ($user_meta_fld) >= 10)
	{
		$date = date_create();
		
		date_timestamp_set($date, $user_meta_fld);
		
		if(empty($date)) return "";
		
		if($format === "16")
			return date_format($date, 'd.m.Y H:i:s');
			//output: 24-03-2019 17:45:12
		else if($format === "17")
			return date_format($date, 'd.m.y H:i:s');
			//output: 24-03-19 17:45:12
		else if($format === "18")
			return date_format($date, 'd.m.y');
			//output: 24-03-19
		else if($format === "19")
			return date_format($date, 'd.m.Y');
			//output: 24-03-2019
		else if($format === "20")
			return date_format($date, 'm/d/Y H:i:s');
			//output: 03/24/2019 17:45:12
		else if($format === "21")
			return date_format($date, 'm/d/y H:i:s');
			//output: 03/24/19 17:45:12
		else if($format === "22")
			return date_format($date, 'm/d/y');
				//output: 03/24/19
		else if($format === "23")
			return date_format($date, 'm/d/Y');
			//output: 03/24/2019			
		else	
			return $user_meta_fld;
	} 
	else
	{
		$dud_datetime = new DateTime($user_meta_fld);
			
		if(empty($dud_datetime)) return "";
		
		if($format === "16")
			return date_format($dud_datetime, 'd.m.Y H:i:s');
			//output: 24-03-2019 17:45:12
		else if($format === "17")
			return date_format($dud_datetime, 'd.m.y H:i:s');
			//output: 24-03-19 17:45:12
		else if($format === "18")
			return date_format($dud_datetime, 'd.m.y');
			//output: 24-03-19
		else if($format === "19")
			return date_format($dud_datetime, 'd.m.Y');
			//output: 24-03-2019
		else if($format === "20")
			return date_format($dud_datetime, 'm/d/Y H:i:s');
			//output: 03/24/2019 17:45:12
		else if($format === "21")
			return date_format($dud_datetime, 'm/d/y H:i:s');
			//output: 03/24/19 17:45:12
		else if($format === "22")
			return date_format($dud_datetime, 'm/d/y');
				//output: 03/24/19
		else if($format === "23")
			return date_format($dud_datetime, 'm/d/Y');
			//output: 03/24/2019
		else	
			return $user_meta_fld;
	}
	
}

/*** Format meta fields that contain an array of items. If nested arrays are encountered, dump the contents.
     This is an old function kept for backwards compatibility on customized Sites  ***/
function dynamic_ud_parse_meta_val($user_meta_fld)
{
	$parsed_val = "";
	$numeric_idx = false;
	$key_val_array = false;
	
	//echo "Format is: " . $format . "<BR>";
	
	if(is_array($user_meta_fld))
	{
		foreach ($user_meta_fld as $key => $value) 
		{
			if (is_string($key))
			{	
				if(is_array($value)) //there are nested arrays
					$parsed_val .= "<BR>" . var_export($value, true);	
				else                 //add key-value pair to the meta fld var
				{
					$key_val_array = true;
					
					if(sizeof($user_meta_fld) == 1)
						$parsed_val .= $value;
					else
						$parsed_val .= "<BR> " . $key . ": " . $value;
				}
			} 
			else
			{
				$numeric_idx = true;
				break;
			}
		}
		
		if($numeric_idx)
		{
			for($met=0; $met < sizeof($user_meta_fld); $met++) 
			{
				if($user_meta_fld[$met])
				{
					if(is_array($user_meta_fld[$met])) //there are nested arrays
						$parsed_val .= var_export($user_meta_fld[$met], true);
					else                               //add the item to the meta fld var
					{
						if(sizeof($user_meta_fld) == 1)
							$parsed_val .= $user_meta_fld[$met];
						else
							$parsed_val .= "<BR> " . $user_meta_fld[$met];
					}
				}
			}
		}
		
		if($key_val_array)
			$parsed_val = apply_filters('dud_format_key_val_array', $user_meta_fld);
		
		return $parsed_val;
	}
	
	return $user_meta_fld;	
}

function dynamic_ud_sort_order( $input ) {
       
     $output = "";
        
     if($input) 
         $output = explode(',', $input);
         
     else
     {
     	$output = "Address,Social,Email,Website,DateRegistered,UserRoles,MetaKey1,MetaKey2,MetaKey3,MetaKey4,MetaKey5,MetaKey6,MetaKey7,MetaKey8,MetaKey9,MetaKey10";
     	$output = explode(',', $output);
     }
     
     return $output;
}

function dud_get_letter_divider($printed_letter, $dud_options, $ud_custom_sort_fld)  
{
	if($ud_custom_sort_fld)
	{
		$ud_letter_divider              = !empty($dud_options['ud_sort_cat_header']) ? $dud_options['ud_sort_cat_header'] : null;             
		$ud_letter_divider_font_color   = !empty($dud_options['ud_sort_cat_font_color']) ? $dud_options['ud_sort_cat_font_color'] : null;
		$ud_letter_divider_fill_color   = !empty($dud_options['ud_sort_cat_fill_color']) ? $dud_options['ud_sort_cat_fill_color'] : null;
		$ud_divider_border_length       = !empty($dud_options['ud_sort_cat_border_length']) ? $dud_options['ud_sort_cat_border_length'] : ""; 
		$user_directory_border_length   = !empty($dud_options['user_directory_border_length']) ? $dud_options['user_directory_border_length'] : "";	   
		$ud_divider_border_thickness    = !empty($dud_options['ud_sort_cat_border_thickness']) ? $dud_options['ud_sort_cat_border_thickness'] : "";
		$ud_divider_border_color        = !empty($dud_options['ud_sort_cat_border_color']) ? $dud_options['ud_sort_cat_border_color'] : "";
		$ud_divider_border_style        = !empty($dud_options['ud_sort_cat_border_style']) ? $dud_options['ud_sort_cat_border_style'] : "";
		$ud_divider_font_size           = !empty($dud_options['ud_sort_cat_font_size']) ? $dud_options['ud_sort_cat_font_size'] : "";
		$ud_sort_cat_header_caps        = !empty($dud_options['ud_sort_cat_header_caps']) ? $dud_options['ud_sort_cat_header_caps'] : "";
		$ud_sort_cat_link_caps          = !empty($dud_options['ud_sort_cat_link_caps']) ? $dud_options['ud_sort_cat_link_caps'] : "";
		
		$printed_letter					= apply_filters('dud_custom_sort_fld_header', $printed_letter, $dud_options);
	}
	else
	{
		$ud_letter_divider              = !empty($dud_options['ud_letter_divider']) ? $dud_options['ud_letter_divider'] : null;
		$ud_letter_divider_font_color   = !empty($dud_options['ud_letter_divider_font_color']) ? $dud_options['ud_letter_divider_font_color'] : null;
		$ud_letter_divider_fill_color   = !empty($dud_options['ud_letter_divider_fill_color']) ? $dud_options['ud_letter_divider_fill_color'] : null;
		$ud_divider_border_thickness    = !empty($dud_options['ud_divider_border_thickness']) ? $dud_options['ud_divider_border_thickness'] : "";
		$ud_divider_border_color        = !empty($dud_options['ud_divider_border_color']) ? $dud_options['ud_divider_border_color'] : "";
		$ud_divider_border_length       = !empty($dud_options['ud_divider_border_length']) ? $dud_options['ud_divider_border_length'] : "";
		$ud_divider_border_style        = !empty($dud_options['ud_divider_border_style']) ? $dud_options['ud_divider_border_style'] : "";
		$ud_divider_font_size           = !empty($dud_options['ud_divider_font_size']) ? $dud_options['ud_divider_font_size'] : "";		
		$user_directory_border_length   = !empty($dud_options['user_directory_border_length']) ? $dud_options['user_directory_border_length'] : "";		
	}
	
	$custom_srt_font_size = "";
    $user_contact_info = "";
	$divider_id_suffix = $printed_letter;
	
	if($ud_custom_sort_fld)
	{
		if($ud_sort_cat_header_caps === 'all')
			$printed_letter = strtoupper($printed_letter);
		else
			$printed_letter = ucwords($printed_letter);
			
		if($ud_sort_cat_link_caps === 'all')
			$divider_id_suffix = replace_spaces_with_dash(strtoupper($divider_id_suffix));
		else
			$divider_id_suffix = replace_spaces_with_dash(ucwords($divider_id_suffix));		
	}
	else
		$printed_letter = strtoupper($printed_letter);
		
	if($ud_letter_divider === "ld-fl" || $ud_letter_divider === "ch-fl") // Letter Inside Bar
	{
		$user_contact_info .= "\n<DIV style=\"width:" . $user_directory_border_length 
			. "; background-color: " . $ud_letter_divider_fill_color 
				. ";\" class=\"printed-letter-div";
		
		if($ud_custom_sort_fld)
			$custom_srt_font_size = "font-size: " . $ud_divider_font_size . "px;";
		
		$user_contact_info .= "\"><DIV id=\"letter-divider-" . $divider_id_suffix . "\" style=\"padding-top:10px;padding-bottom:10px;padding-left:5px;"  . $custom_srt_font_size . "color:" . $ud_letter_divider_font_color 
			. ";\" class=\"printed-letter\">" . $printed_letter . "</DIV></DIV>";
	}
	else if($ud_letter_divider === "ld-bb" || $ud_letter_divider === "ch-bb") // Letter w/Bottom Border
	{	
		
		$user_contact_info .= "\n<DIV style=\"width:" . $ud_divider_border_length 
			. ";border-bottom: " . $ud_divider_border_style . " " . $ud_divider_border_thickness . " " . $ud_divider_border_color . ";!important;\" class=\"custom-letter-div-vertical-dir";
		
		if($ud_letter_divider === "ld-bb")
		{		
			$user_contact_info .= "\"><DIV id=\"letter-divider-" . $divider_id_suffix . "\" style=\"position:relative;top:5px;vertical-align:middle;font-size: " . $ud_divider_font_size . "px;color:" . $ud_letter_divider_font_color 
				. ";\" class=\"printed-letter\">" . $printed_letter . "</DIV></DIV>";
		}
		else
		{
			$user_contact_info .= "\"><DIV id=\"letter-divider-" . $divider_id_suffix . "\" style=\"position:relative;left:-4px;top:-3px;vertical-align:middle;font-size: " . $ud_divider_font_size . "px;color:" . $ud_letter_divider_font_color 
				. ";\" class=\"printed-letter\">" . $printed_letter . "</DIV></DIV>";
		}
	}
	else if($ud_letter_divider === "ld-tb" || $ud_letter_divider === "ch-tb" ) // Letter w/Top & Bottom Border
	{					
		$user_contact_info .= "\n<DIV style=\"width:" . $ud_divider_border_length 
			. ";border-bottom: " . $ud_divider_border_style . " " . $ud_divider_border_thickness . " " . $ud_divider_border_color 
			. ";!important;border-top:" . $ud_divider_border_style . " " . $ud_divider_border_thickness . " " . $ud_divider_border_color . ";!important;\" class=\"custom-letter-div-vertical-dir";
			
		$user_contact_info .= "\"><DIV id=\"letter-divider-" . $divider_id_suffix . "\" style=\"font-size: " . $ud_divider_font_size . "px;color:" . $ud_letter_divider_font_color 
			. ";\" class=\"printed-letter\">" . $printed_letter . "</DIV></DIV>";
	}
	else // Letter Only
	{
		$padding_bottom = "";
		if($ud_custom_sort_fld) $padding_bottom = "padding-bottom:15px;";
		
		$user_contact_info .= "\n<DIV style=\"margin-bottom:5px !important;width:" . $ud_divider_border_length 
			. ";!important;\" class=\"custom-letter-div-vertical-dir\"><DIV id=\"letter-divider-" . $divider_id_suffix . "\" style=\"font-size: " . $ud_divider_font_size . "px;" . $padding_bottom . "color:" . $ud_letter_divider_font_color 
			. ";\" class=\"printed-letter\">" . $printed_letter . "</DIV></DIV>";
	}

	return $user_contact_info;
     
}

/*** Loads an array with the alphabet letters for the existing users based on the filters selected on the settings page  ***/
function dynamic_ud_load_alpha_links($sort_fld, $ud_hide_roles, $exc_inc_radio, $inc_exc_user_ids, $dud_user_srch_name, 
	$ud_directory_type, $dud_options, $custom_sort_active)
{
	global $dynamic_ud_debug;
	global $wpdb;
	$roles_sql = "";
	$include_exclude_sql = "";
	
	/*** Custom Sort Field Add-on ***/
	$ud_sort_fld_key     		      = !empty($dud_options['ud_sort_fld_key']) ? $dud_options['ud_sort_fld_key'] : null;
	$ud_sort_fld_type     		      = !empty($dud_options['ud_sort_fld_type']) ? $dud_options['ud_sort_fld_type'] : null;
	$ud_sort_cat_link_caps 		  	  = !empty($dud_options['ud_sort_cat_link_caps']) ? $dud_options['ud_sort_cat_link_caps'] : "";
	$ud_sort_show_categories_as 	  = !empty($dud_options['ud_sort_show_categories_as']) ? $dud_options['ud_sort_show_categories_as'] : "";
	/********************************/
	
	$ud_roles_exclude_include_radio   = !empty($dud_options['ud_roles_exclude_include_radio']) ? $dud_options['ud_roles_exclude_include_radio'] : "";
	
	if(!empty($ud_sort_fld_key) && $custom_sort_active)
	{
		$ud_sql = dud_build_directory_query('', false, $ud_sort_fld_key, '', $ud_sort_fld_type, $custom_sort_active, true, '', $dud_options);
	}
	else
	{
		if($ud_hide_roles && !($inc_exc_user_ids && $exc_inc_radio === 'include' )) //if including users, no need to build hide roles query
			$roles_sql = dynamic_ud_build_roles_query($sort_fld, $ud_hide_roles, $ud_roles_exclude_include_radio);
			
		if($inc_exc_user_ids)
			$include_exclude_sql = dynamic_ud_build_inc_exc_query($sort_fld, $ud_hide_roles, $exc_inc_radio, $inc_exc_user_ids);	
		
		
		if($sort_fld === "last_name")
		{
			$ud_sql = "Select COUNT(*) as cnt,SUBSTRING(meta_value,1,1) as letter FROM " . DUD_WPDB_PREFIX . "usermeta where meta_key = 'last_name' ";
			
			if($ud_hide_roles && !($inc_exc_user_ids && $exc_inc_radio === 'include' )) $ud_sql .= " AND " . $roles_sql; 
			
			if($inc_exc_user_ids) $ud_sql .= " AND " . $include_exclude_sql;
				
			$ud_sql .= " GROUP BY SUBSTRING(meta_value,1,1)";
			
			if($dynamic_ud_debug) { echo "<PRE>Load Alpha Links SQL:<BR><BR>" . $ud_sql . "<BR><BR></PRE>"; }
		}
		else
		{
			$ud_sql = "Select COUNT(*) as cnt, SUBSTRING(display_name,1,1) as letter FROM " . DUD_WPDB_PREFIX . "users ";
			
			if( ($ud_hide_roles && !($inc_exc_user_ids && $exc_inc_radio === 'include' )) || $inc_exc_user_ids) $ud_sql .= " where ";
		
			if($ud_hide_roles && !($inc_exc_user_ids && $exc_inc_radio === 'include' )) $ud_sql .= $roles_sql;
			
			if($inc_exc_user_ids)
			{
				if($ud_hide_roles && !($inc_exc_user_ids && $exc_inc_radio === 'include' )) $ud_sql .= " AND ";
				
				$ud_sql .= $include_exclude_sql; 
			}
		
			$ud_sql .= " GROUP BY SUBSTRING(display_name,1,1)";
			
			if($dynamic_ud_debug) { echo "<PRE>Load Alpha Links SQL:<BR><BR>" . $ud_sql . "<BR><BR></PRE>"; }
		}
	}
		
	$results = $wpdb->get_results($ud_sql);
	
	if(!empty($results))
	{
		foreach ($results as $key => $row)
		{
			$vc_array_name[$key] = $row->letter;
		}
		array_multisort($vc_array_name, SORT_ASC, $results);
	}
			
	if($results)
	{	
		//if($dynamic_ud_debug) { echo "<PRE>Existing Users Sorted By Letter<BR>"; }
		
		$letter_exists = array();
	
		foreach($results as $result) {
			
			//if($dynamic_ud_debug) { echo strtoupper($result->letter) . "&nbsp;&nbsp;(" . $result->cnt . ")"; }
			
			/*** The code below only affects users who are using meta flds srch and alpha links scroll add-ons ***/
			
			//user search got results, hide unnecessary letters on single page dir
			if($ud_directory_type === "all-users" && !empty($dud_user_srch_name)) 
			{
				if(empty($ud_sort_fld_key) || !$custom_sort_active) //only do this if custom sort addon is not loaded
				{
					if( !(strtoupper($dud_user_srch_name[0]) === strtoupper($result->letter)) )
						continue;
				}
			}
			/************************************************************************************************/	
			
			if(empty($ud_sort_fld_key) || !$custom_sort_active)
			{
				if(ctype_alpha($result->letter)) 
				{
					//if($dynamic_ud_debug) echo " *";
					array_push($letter_exists, strtoupper($result->letter));
				}
			}
			else
			{
				//if($dynamic_ud_debug) echo " *";
				
				if($ud_sort_cat_link_caps === 'all')
					array_push($letter_exists, strtoupper($result->letter));
				else
					array_push($letter_exists, ucwords($result->letter));
			}
			
			//if($dynamic_ud_debug) echo "<BR>";
		}
		//if($dynamic_ud_debug) echo "</PRE>";
	
		return $letter_exists;	
	}

	return null; 
}
	
/*** Formats social media links with proper url if necessary ***/
function format_social_links($social_flds)
{
	if(!empty($social_flds[0])) 
	{
		if( !dud_startsWith($social_flds[0], "http") )
			$social_flds[0] = "https://" . $social_flds[0];
	}
	
	if(!empty($social_flds[1]))
	{
		if($social_flds[1][0] === '@')
			$social_flds[1] = "https://twitter.com/" . substr($social_flds[1], 1);
		
		else if( !dud_startsWith($social_flds[1], "http") )
			$social_flds[1] = "https://" . $social_flds[1];
	}
	
	if(!empty($social_flds[2])) 
	{
		if( !dud_startsWith($social_flds[2], "http") )
			$social_flds[2] = "https://" . $social_flds[2];
	}	

	if(!empty($social_flds[3])) 
	{
		if( !dud_startsWith($social_flds[3], "http") )
			$social_flds[3] = "https://" . $social_flds[3];
	}	

	if(!empty($social_flds[4])) 
	{
		if( !dud_startsWith($social_flds[4], "http") )
			$social_flds[4] = "https://" . $social_flds[4];
	}		

	if(!empty($social_flds[5])) 
	{
		
		if( !dud_startsWith($social_flds[5], "http") )
			$social_flds[5] = "https://" . $social_flds[5];
	}
	
	if(!empty($social_flds[6])) 
	{
		
		if( !dud_startsWith($social_flds[6], "http") )
			$social_flds[6] = "https://" . $social_flds[6];
	}
	
	if(!empty($social_flds[7])) 
	{
		
		if( !dud_startsWith($social_flds[7], "http") )
			$social_flds[7] = "https://" . $social_flds[7];
	}
	
	if(!empty($social_flds[8])) 
	{
		
		if( !dud_startsWith($social_flds[8], "http") )
			$social_flds[8] = "https://" . $social_flds[8];
	}
	
	return $social_flds;
}

function dud_startsWith($haystack, $needle)
{
	return $needle === "" || strpos($haystack, $needle) === 0;
}

/***  Prints the letters of alphabet as links that will be used by the MembersListing function ***/
function dynamic_ud_print_alpha_links($ud_existing_letters, $ud_alpha_link_spacer, $user_directory_letter_fs, 
	$ud_alpha_link_clicked_color=null, $ud_alpha_link_color=null, $letter=null)
{
	global $wp;
	global $dynamic_ud_debug;
	
	if(!$user_directory_letter_fs) $user_directory_letter_fs = "14px";
		
	if(!$ud_alpha_link_spacer) $ud_alpha_link_spacer = "8px";
	else $ud_alpha_link_spacer .= "px";
		
	//If there is no custom permalink structure
	if ( !get_option('permalink_structure') )
	{	
		//This accommodates certain intranet configurations
		$current_url = esc_url( home_url( '/' ) ) . basename(get_permalink());
		$url_param = "/?";
	}
	else
	{
		$current_url = esc_url( get_permalink()); 
		$url_param = "?";
	}
	
	if ((strpos($current_url, "?") !== false)) $url_param = "&";
	        
	$ud_letters_links = "\n<DIV class=\"alpha-links\" style=\"font-size:" . $user_directory_letter_fs . "px;\">\n";

	/*** alphabet array ***/
		
	$ud_alpha_string = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
	$ud_alpha_array = explode(',', $ud_alpha_string);
	
	$ud_highlight_link_1 = "";
	$ud_highlight_link_2 = "";
	
	if($dynamic_ud_debug) echo "<PRE>";	
	for( $inc = 0; $inc<26; $inc++ )
	{	
		$ud_letter = $ud_alpha_array[$inc];
		
		if($dynamic_ud_debug) echo "Checking letter " . $ud_letter;	
		
		if($ud_letter === $letter && !empty($ud_alpha_link_clicked_color))
		{
			$ud_highlight_link_1 = "style='color: " .  $ud_alpha_link_clicked_color . ";'";
			$ud_highlight_link_2 = "color: " .  $ud_alpha_link_clicked_color . ";";
		}
		else if($ud_letter !== $letter && !empty($ud_alpha_link_color))
		{
			$ud_highlight_link_1 = "style='color: " .  $ud_alpha_link_color . ";'";
			$ud_highlight_link_2 = "color: " .  $ud_alpha_link_color . ";";
		}
		else
		{
			$ud_highlight_link_1 = "";
			$ud_highlight_link_2 = "";
		}
	    	
		if(in_array ( $ud_letter, $ud_existing_letters ))
		{	
			if($dynamic_ud_debug) echo " *";

			if($ud_letter !== 'Z')
				$ud_letters_links .= "\t\t<a " . $ud_highlight_link_1 . " href=\"" . $current_url . $url_param . "letter=" . $ud_letter . "\"><b>" . $ud_letter . "</b></a><span style=\"padding-right:" 
					. $ud_alpha_link_spacer . ";\"></span>\n";
			else
				$ud_letters_links .= "\t\t<a style=\"font-weight: 400;" . $ud_highlight_link_2 . "\" href=\"" 
					. $current_url . $url_param . "letter=" . $ud_letter . "\"><b>" . $ud_letter . "</b></a>\n";
		}
		else
		{
			if($ud_letter !== 'Z')
				$ud_letters_links .= "\t\t<span style=\"color:gray;padding-right:" 
					. $ud_alpha_link_spacer . ";\">". $ud_letter . "</span>\n";
			else
				$ud_letters_links .= "\t\t<span style=\"color:gray\">". $ud_letter . "</span>\n";
		}
		if($dynamic_ud_debug) echo "<BR>";	
	}

	if($dynamic_ud_debug) echo "</PRE>";	
	$ud_letters_links .= "</DIV>\n\n";
	
	return $ud_letters_links;
}

/***  Prints Directory Pagination ***/
function dud_print_pagination($total_users, $users_per_page, $ud_directory_type, $ud_show_srch, $letter, $dud_search_val, $dud_search_key, $plugins, 
							  $ud_pagination_font_size, $ud_pagination_link_color, $ud_pagination_link_clicked_color, $dud_options, $is_top=true)
{
	global $wp;
	global $dynamic_ud_debug;
	
	$dud_left_arrow_clicked	        = !empty($_REQUEST ["dud_left_arrow_clicked"]) ? $_REQUEST ["dud_left_arrow_clicked"] : 0;
	$dud_right_arrow_clicked        = !empty($_REQUEST ["dud_right_arrow_clicked"]) ? $_REQUEST ["dud_right_arrow_clicked"] : 0;
	$dud_page_number_clicked        = !empty($_REQUEST ["dud_page_number_clicked"]) ? $_REQUEST ["dud_page_number_clicked"] : 0;
	$search_button_clicked          = !empty($_REQUEST ["search_button_clicked"]) ? $_REQUEST ["search_button_clicked"] : null;	
	$dud_user_srch_name 			= !empty($_REQUEST ["dud_user_srch_val"]) ? $_REQUEST ["dud_user_srch_val"] : null;
	
	$ud_pagination_padding_top      = !empty($dud_options['ud_pagination_padding_top']) ? $dud_options['ud_pagination_padding_top'] : "0";
	$ud_pagination_padding_bottom   = !empty($dud_options['ud_pagination_padding_bottom']) ? $dud_options['ud_pagination_padding_bottom'] : "0";
	$ud_num_users_font_size         = !empty($dud_options['ud_num_users_font_size']) ? $dud_options['ud_num_users_font_size'] : "";
	$ud_txt_after_num_users         = !empty($dud_options['ud_txt_after_num_users']) ? $dud_options['ud_txt_after_num_users'] : "";
	$ud_txt_after_num_users_srch	= !empty($dud_options['ud_txt_after_num_users_srch']) ? $dud_options['ud_txt_after_num_users_srch'] : "";
	$ud_show_pagination_above_below	= !empty($dud_options['ud_show_pagination_above_below']) ? $dud_options['ud_show_pagination_above_below'] : "below";
	$ud_num_users_border            = !empty($dud_options['ud_num_users_border']) ? $dud_options['ud_num_users_border'] : "";
	$ud_num_users_border_color      = !empty($dud_options['ud_num_users_border_color']) ? $dud_options['ud_num_users_border_color'] : "";
	$ud_show_num_users              = !empty($dud_options['ud_show_num_users']) ? $dud_options['ud_show_num_users'] : "";
	
	$ud_num_users_notification      = "";
	$current_url = "";
	$url_param = "";
	$other_params = "";
	
	//If there is no custom permalink structure
	if ( !get_option('permalink_structure') )
	{	
		//This accommodates certain intranet configurations
		$current_url = esc_url( home_url( '/' ) ) . basename(get_permalink());
		$url_param = "/?";
	}
	else
	{
		$current_url = esc_url( get_permalink()); 
		$url_param = "?";
	}
		
	if ((strpos($current_url, "?") !== false)) $url_param = "&";
	
	if($total_users <= $users_per_page) 
	{
		return "";
	}
	else 
		$last_page = ceil( ((float)$total_users/(float)$users_per_page) );
	
	if($last_page <= 1) return "";
		
	if(($dud_page_number_clicked == 0 && $dud_left_arrow_clicked == 0 && $dud_right_arrow_clicked == 0) || $search_button_clicked)
		$current_page = 1;	
	else if($dud_page_number_clicked !== 0)
		$current_page = $dud_page_number_clicked;
	else if($dud_left_arrow_clicked !== 0)
		$current_page = $dud_left_arrow_clicked;
	else if($dud_right_arrow_clicked !== 0)
		$current_page = $dud_right_arrow_clicked;
	
	$dud_right_arrow_clicked = $current_page + 1;
	$dud_left_arrow_clicked = $current_page - 1;
	
	if($dud_right_arrow_clicked > $last_page) $dud_right_arrow_clicked = 0;
	if($dud_left_arrow_clicked < 1) $dud_left_arrow_clicked = 0;
	
	$startPage = ($current_page < 5)? 1 : $current_page - 4;
	$endPage = 8 + $startPage;
	$endPage = ($last_page < $endPage) ? $last_page : $endPage;
	$diff = $startPage - $endPage + 8;
	$startPage -= ($startPage - $diff > 0) ? $diff : 0;
	
	/*if($dynamic_ud_debug) {
		echo "<PRE>";
		echo "PAGINATION<BR><BR>";
		echo "Users Per Page: " . $users_per_page . "<BR>";		
		echo "Current Page: " . $current_page . "<BR>";	
		echo "Start Page: " . $startPage . "<BR>";
		echo "End Page: " . $endPage . "<BR>";		
		echo "<BR></PRE>";
	}*/
	
	if($ud_directory_type !== "all-users")
		$other_params .= "&letter=" . $letter; 
	if(in_array( 'dynamic-user-directory-meta-flds-srch/dud_meta_flds_srch.php' , $plugins ) && $ud_show_srch)
		$other_params .= "&dud_user_srch_val=" . $dud_search_val . "&dud_user_srch_key=" . $dud_search_key;
		
	if(!empty($ud_pagination_link_color))
	{
		$ud_pagination_link_color = "style=\"color:" . $ud_pagination_link_color . ";\"";
	}
	if(!empty($ud_pagination_link_clicked_color))
	{
		$ud_pagination_link_clicked_color = "style=\"color:" . $ud_pagination_link_clicked_color . ";\"";
	}
	
	$dud_pagination = "";
	
	$dud_pagination .= "\n<nav data-pagination style=\"font-size:" . $ud_pagination_font_size . "px!important;";
		
	$dud_pagination .= "padding-bottom:" . $ud_pagination_padding_bottom . "px;";
		
	$dud_pagination .= "padding-top:" . $ud_pagination_padding_top . "px;";
	
	$dud_pagination .= "\">";
	
	/*** Print Links ***/
	if($dud_left_arrow_clicked == 0)
		$dud_pagination .= "\n<a " . $ud_pagination_link_color . " href=# disabled><i class=\"fa fa-chevron-left\" " . $ud_pagination_link_color . " aria-hidden=\"true\"></i></a>";
	else
		$dud_pagination .= "\n<a " . $ud_pagination_link_color . " href=\"" . $current_url . $url_param . "dud_left_arrow_clicked=" . $dud_left_arrow_clicked . $other_params . "\"><i class=\"fa fa-chevron-left\" " . $ud_pagination_link_color . "aria-hidden=\"true\"></i></a>";
	
	$dud_pagination .= "\n<ul>";
	
	if ($startPage > 1) 
	{
		if($startPage == $current_page)
		{
			$dud_pagination .= "\n	<li><a " . $ud_pagination_link_clicked_color . " href=\"" . $current_url . $url_param . "dud_page_number_clicked=1" . $other_params . "\">1</a>";	
		}
		else
		{
			$dud_pagination .= "\n	<li><strong><a " . $ud_pagination_link_color . " href=\"" . $current_url . $url_param . "dud_page_number_clicked=1" . $other_params . "\">1</a></strong>";
		}
		
		$dud_pagination .= "\n	<li><strong><a " . $ud_pagination_link_color . " href=#>...</a></strong>";
	}
	
	for($i=$startPage; $i<=$endPage; $i++)
	{
		if($i == $current_page)
			$dud_pagination .= "\n	<li><strong><a " . $ud_pagination_link_clicked_color . " href=\"" . $current_url . $url_param . "dud_page_number_clicked=" . $i . $other_params . "\">" . $i . "</strong></a>";
		else
			$dud_pagination .= "\n	<li><strong><a " . $ud_pagination_link_color . "  href=\"" . $current_url . $url_param . "dud_page_number_clicked=" . $i . $other_params . "\">" . $i . "</strong></a>";
	}
	
	if ($endPage < $last_page)
	{
		$dud_pagination .= "\n	<li><strong><a " . $ud_pagination_link_color . " href=#>…</a></strong>";
		$dud_pagination .= "\n	<li><strong><a " . $ud_pagination_link_color . " href=\"" . $current_url . $url_param . "dud_page_number_clicked=" . $last_page . $other_params . "\">" . $last_page . "</a></strong>";
	}		
	
	$dud_pagination .= "\n</ul>";
	
	if($dud_right_arrow_clicked == 0)
		$dud_pagination .= "\n<a " . $ud_pagination_link_color . " href=# disabled><i class=\"fa fa-chevron-right\" " . $ud_pagination_link_color . " aria-hidden=\"true\"></i></a>";
	else
		$dud_pagination .= "\n<a " . $ud_pagination_link_color . " href=\"" . $current_url . $url_param . "dud_right_arrow_clicked=" . $dud_right_arrow_clicked . $other_params . "\"><i class=\"fa fa-chevron-right\" " . $ud_pagination_link_color . "aria-hidden=\"true\"></i></a>";
	
	/* Directory Totals *******************************************************************/
	$user_start_number = ((($current_page-1) * $users_per_page) + 1);
	
	if($current_page == $endPage)
		$user_end_number = $user_start_number + ($total_users - $user_start_number);
	else
		$user_end_number = $user_start_number + ($users_per_page - 1);
		
	$show_num_users = show_num_users($is_top, $dud_options);
	
	if($show_num_users && $ud_directory_type === "all-users")
	{
		$ud_num_users_notification = $user_start_number . " - " . $user_end_number . " of " . $total_users . " ";
		
		if($ud_show_num_users ==="both" && empty($dud_user_srch_name))
			$ud_num_users_notification .= $ud_txt_after_num_users;
		else
			$ud_num_users_notification .= $ud_txt_after_num_users_srch;

		$dud_pagination .= "<div style='margin-top:10px;font-size:" . $ud_num_users_font_size . "px;' class='dud_total_users'>" .  apply_filters('dud_directory_totals_notification', $ud_num_users_notification, $dud_options) . "</div>";
	
		if($is_top && $ud_num_users_border)
				$dud_pagination .= "<div style='width: " . $ud_num_users_border . ";border-color: " . $ud_num_users_border_color . ";' class='dud_total_users_border'></div>"; 
	}
	
	$dud_pagination .= "\n</nav>";
			
	return $dud_pagination;
}

/*** Returns the index of the first or last user id to be displayed for the current page ***/
function dud_get_pagination_idx($total_users, $users_per_page, $fisrt_or_last_user)
{
	global $wp;
	global $dynamic_ud_debug;
	
	$dud_left_arrow_clicked	  = !empty($_REQUEST ["dud_left_arrow_clicked"]) ? $_REQUEST ["dud_left_arrow_clicked"] : 0;
	$dud_right_arrow_clicked  = !empty($_REQUEST ["dud_right_arrow_clicked"]) ? $_REQUEST ["dud_right_arrow_clicked"] : 0;
	$dud_page_number_clicked  = !empty($_REQUEST ["dud_page_number_clicked"]) ? $_REQUEST ["dud_page_number_clicked"] : 0;
	$search_button_clicked    = !empty($_REQUEST ["search_button_clicked"]) ? $_REQUEST ["search_button_clicked"] : null; 
	
	if(empty($search_button_clicked)) $search_button_clicked = "";
	
	$last_user_idx = -1;
	
	//$last_page = ceil( ((float)$total_users/(float)$users_per_page) );
	
	//if($last_page <= 1) return "";
	
	if($total_users <= $users_per_page)
	{
		if($fisrt_or_last_user === "last")
			return ($total_users - 1);
		else
			return 0;		
	}
		
	if(($dud_page_number_clicked == 0 && $dud_left_arrow_clicked == 0 && $dud_right_arrow_clicked == 0) || $search_button_clicked)
		$current_page = 1;	
	else if($dud_page_number_clicked !== 0)
		$current_page = $dud_page_number_clicked;
	else if($dud_left_arrow_clicked !== 0)
		$current_page = $dud_left_arrow_clicked;
	else if($dud_right_arrow_clicked !== 0)
		$current_page = $dud_right_arrow_clicked;
	
	if($fisrt_or_last_user === "last")
	{
		if( (($current_page * $users_per_page) - 1) < 0)
			return $users_per_page;
		else 
			return ($current_page * $users_per_page) - 1;
	}
	else 
	{
		if( (($current_page * $users_per_page) - 1) < 0)
			return 0;
		else
		{
			$last_user_idx = ($current_page * $users_per_page) - 1;	
			
			if( ($last_user_idx - ($users_per_page-1) < 0)) 
				return 0;
			else
				return ($last_user_idx - ($users_per_page-1));
		}
	}
}

/*** SQL Utilities ***/

/*** 
* Constructs the query that pulls the user ids from the database
*
* Returns: SQL query string
* 
* Called By: core.php (twice), dynamic-user-directory-custom-sort-field.php (once), dud_meta_flds_srch.php (once)
*
***/
function dud_build_directory_query($last_name_letter, $last_name_letter_empty, $ud_sort_fld_key, $ud_sort_fld_letter, 
				$ud_sort_fld_type, $custom_sort_active, $load_alpha_links, $meta_key_sql, $dud_options) 
{

	global $wpdb;
	
	/*** Get sort, hide roles, and include/exclude fields ***/
	$user_directory_sort              = !empty($dud_options['user_directory_sort']) ? $dud_options['user_directory_sort'] : null;
	$ud_hide_roles                    = !empty($dud_options['ud_hide_roles']) ? $dud_options['ud_hide_roles'] : null;
	$exc_inc_radio                    = !empty($dud_options['ud_exclude_include_radio']) ? $dud_options['ud_exclude_include_radio'] : null;
	$inc_exc_user_ids                 = !empty($dud_options['ud_users_exclude_include']) ? $dud_options['ud_users_exclude_include'] : null;
	$ud_directory_type                = !empty($dud_options['ud_directory_type']) ? $dud_options['ud_directory_type'] : null;
	$ud_roles_exclude_include_radio   = !empty($dud_options['ud_roles_exclude_include_radio']) ? $dud_options['ud_roles_exclude_include_radio'] : "";
	
	if($ud_hide_roles && !($exc_inc_radio === 'include' && $inc_exc_user_ids))
		$roles_sql = dynamic_ud_build_roles_query($user_directory_sort, $ud_hide_roles, $ud_roles_exclude_include_radio);
			
	if($inc_exc_user_ids)
		$include_exclude_sql = dynamic_ud_build_inc_exc_query($user_directory_sort, $ud_hide_roles, $exc_inc_radio, $inc_exc_user_ids);	
	
	/*** SORT FIELD ADD_ON ********************************************************************************/
	if($custom_sort_active)
	{
		$user_sql = dud_build_directory_query_custom_sort_fld($user_directory_sort, $ud_hide_roles, $exc_inc_radio, $inc_exc_user_ids, 
						$ud_directory_type, $last_name_letter, $last_name_letter_empty, $ud_sort_fld_key, $ud_sort_fld_letter, $ud_sort_fld_type, 
							$load_alpha_links, $meta_key_sql, false, $dud_options); 
	}
	/*** NORMAL DIRECTORY SQL ****************************************************************************/
	else
	{	
		if($user_directory_sort === "last_name") 
		{    
			if(!($ud_directory_type === "all-users"))
			{
				//$user_sql = "SELECT DISTINCT user_id from " . DUD_WPDB_PREFIX 
				//	. "usermeta WHERE meta_key = 'last_name' and meta_value like '" . $last_name_letter . "%' ";
					
				$user_sql = "SELECT DISTINCT t2.user_id, t2.meta_value, " . DUD_WPDB_PREFIX . "usermeta.meta_value from " . DUD_WPDB_PREFIX . "usermeta 
					INNER JOIN " . DUD_WPDB_PREFIX . "usermeta t2 ON " . DUD_WPDB_PREFIX . "usermeta.user_id = t2.user_id 
						WHERE " . DUD_WPDB_PREFIX . "usermeta.meta_key = 'last_name' and t2.meta_key = 'first_name' 
							and " . DUD_WPDB_PREFIX . "usermeta.meta_value like '" . $last_name_letter . "%'"; 
								
			}
			else
			{
				//$user_sql = "SELECT DISTINCT user_id from " . DUD_WPDB_PREFIX . "usermeta WHERE meta_key = 'last_name'";
				
				$user_sql = "SELECT DISTINCT t2.user_id, t2.meta_value, " . DUD_WPDB_PREFIX . "usermeta.meta_value from " . DUD_WPDB_PREFIX . "usermeta 
					INNER JOIN " . DUD_WPDB_PREFIX . "usermeta t2 ON " . DUD_WPDB_PREFIX . "usermeta.user_id = t2.user_id 
						WHERE " . DUD_WPDB_PREFIX . "usermeta.meta_key = 'last_name' and t2.meta_key = 'first_name'"; 
			}
			
			if($ud_hide_roles && !($exc_inc_radio === 'include' && $inc_exc_user_ids)) 
				$user_sql .= " AND " . $roles_sql;
				
			if($inc_exc_user_ids) 
				$user_sql .= " AND " . $include_exclude_sql;
				
			//$user_sql .= " ORDER BY meta_value, t2.meta_value"; 
			$user_sql .= " ORDER BY " . DUD_WPDB_PREFIX . "usermeta.meta_value, t2.meta_value";
		}		
		else
		{ 
			if(!($ud_directory_type === "all-users"))
				$user_sql = "SELECT DISTINCT ID, display_name from " . DUD_WPDB_PREFIX . "users WHERE display_name like '" . $last_name_letter . "%'" ;
			else
				$user_sql = "SELECT DISTINCT ID, display_name from " . DUD_WPDB_PREFIX . "users" ;
			
			if($ud_hide_roles && !($exc_inc_radio === 'include' && $inc_exc_user_ids)) 
			{     
				if($ud_directory_type === "all-users")
					$user_sql .= " WHERE " . $roles_sql;
				else
					$user_sql .= " AND " . $roles_sql;
			}
			if($inc_exc_user_ids) 
			{
				if($ud_directory_type === "all-users" && !($ud_hide_roles && !($exc_inc_radio === 'include' && $inc_exc_user_ids))) 
					$user_sql .= " WHERE " . $include_exclude_sql;
				else
					$user_sql .= " AND " . $include_exclude_sql;
			}

			$user_sql .= " ORDER BY display_name"; 	
		}
	}
	
	//if(!empty($ud_sort_fld_type))
		//echo "SQL is: " . $user_sql . "<BR><BR>";
	
	return $user_sql;
}

function dynamic_ud_build_roles_query($sort_fld, $ud_hide_roles, $ud_roles_exc_inc = null) {

	global $wpdb;
	global $wp_roles;
	
	$roles_sql = "";
	$role_cnt = 1;
	$user_id_col_name = "ID";
	$tbl_name = DUD_WPDB_PREFIX . "users"; 
	
	if($sort_fld === "last_name") 
	{
		$user_id_col_name = "user_id"; 
		$tbl_name = DUD_WPDB_PREFIX . "usermeta";
	}
	
	if(!empty($ud_roles_exc_inc) && $ud_roles_exc_inc === "include")
	{
		$roles_sql .= $tbl_name . "." . $user_id_col_name . " IN (SELECT user_id FROM " . DUD_WPDB_PREFIX . "usermeta WHERE ((
			" . DUD_WPDB_PREFIX . "usermeta.meta_key like '" . DUD_WPDB_PREFIX . "%' AND " . DUD_WPDB_PREFIX . "usermeta.meta_key like '%capabilities' AND (";
	}
	else
	{
		$roles_sql .= $tbl_name . "." . $user_id_col_name . " NOT IN (SELECT user_id FROM " . DUD_WPDB_PREFIX . "usermeta WHERE ((
			" . DUD_WPDB_PREFIX . "usermeta.meta_key like '" . DUD_WPDB_PREFIX . "%' AND " . DUD_WPDB_PREFIX . "usermeta.meta_key like '%capabilities' AND (";
	}
			
	$roles_arr_len = count((array)$ud_hide_roles);
	
	$wproles = $wp_roles->get_names();
	
	foreach($ud_hide_roles as $role)
	{
		foreach($wproles as $key => $val)
			if(strtoupper($val) === strtoupper($role))
				$role = $key;
			
		$roles_sql .= " " . DUD_WPDB_PREFIX . "usermeta.meta_value like '%" . $role . "%'";
		
		if($role_cnt < $roles_arr_len)
			$roles_sql .= " OR ";
		
		$role_cnt++;
	}	
	
	$roles_sql .= ")))) ";
	
	return $roles_sql;
}

function dud_get_user_roles($user_id, $ud_user_roles_format, $ud_roles_lbl, $ud_display_listings, $ud_export) {

	global $wpdb;
	global $wp_roles;
	
	$roles_sql = "";
	$role_cnt = 1;
	
	$roles_sql = "SELECT meta_value FROM " . DUD_WPDB_PREFIX . "usermeta WHERE " . DUD_WPDB_PREFIX . "usermeta.meta_key like '%capabilities' AND user_id = $user_id";
			
	$roles = $wpdb->get_results($roles_sql);
	
	$wproles = $wp_roles->get_names();
	
	$list_of_roles = "";
	
	foreach($roles as $user_roles)
	{
		$role_list = $user_roles->meta_value;
			
		foreach($wproles as $key => $val)
		{			
			if (!stristr($role_list, $key))
				continue;
			
			if($ud_user_roles_format == "1" || $ud_export)
			{
				if($role_cnt > 1)
					$list_of_roles .= ", ";
				
				$list_of_roles .= $val;
			}
			else
			{
				if($role_cnt == 1 && !empty($ud_roles_lbl) && $ud_display_listings !== "horizontally")
					$list_of_roles .= "<BR>";
				
				$list_of_roles .= "<i class=\"fas fa-circle dud-fa-bullet\" aria-hidden=\"true\"></i>" . $val . "<BR>";
			}
			
			$role_cnt++;
		}
	}
			
	return $list_of_roles;
}

function dynamic_ud_build_inc_exc_query($sort_fld, $ud_hide_roles, $exc_inc_radio, $inc_exc_user_ids) {

	global $wpdb;
	$users_sql = "";
	$user_cnt = 1;
	$user_id_col_name = "ID";
	$tbl_name = DUD_WPDB_PREFIX . "users"; 
	
	if($sort_fld === "last_name") 
	{
		$user_id_col_name = "user_id"; 
		$tbl_name = DUD_WPDB_PREFIX . "usermeta";
	}
		
	$users_arr_len = count((array)$inc_exc_user_ids);
	
	if($exc_inc_radio === "include") {
		
		$users_sql .= "( ";	
		
		foreach($inc_exc_user_ids as $user_id)
		{
			$users_sql .= $tbl_name . "." . $user_id_col_name . " = " . $user_id;
		
			if($user_cnt < $users_arr_len)
				$users_sql .= " OR ";
		
			$user_cnt++;
		}	
		
		$users_sql .= " ) ";
	}
	else if($exc_inc_radio === "exclude") {
		
		$users_sql .= "(" . $tbl_name . "." . $user_id_col_name . " NOT IN (SELECT " . DUD_WPDB_PREFIX . "usermeta.user_id FROM " . DUD_WPDB_PREFIX . "usermeta WHERE ( ";	
		
		foreach($inc_exc_user_ids as $user_id)
		{
			$users_sql .=  DUD_WPDB_PREFIX . "usermeta.user_id = " . $user_id;
		
			if($user_cnt < $users_arr_len)
				$users_sql .= " OR ";
		
			$user_cnt++;
		}	
		
		$users_sql .= " ))) ";
	}
	
	return $users_sql;
}

/*** Check Field Types ***/

function dud_chk_cimy_field($fld) {

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

function dud_chk_bp_field($fld) {

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

function dud_chk_s2m_field($fld, $fld_type) {

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
/*** String Utilities ***/

function dynamic_ud_before ($instr, $inthat)
{
        return substr($inthat, 0, strpos($inthat, $instr));
};

function dynamic_ud_after ($instr, $inthat)
{
        if (!is_bool(strpos($inthat, $instr)))
        return substr($inthat, strpos($inthat,$instr)+strlen($instr));
};
        
function dynamic_ud_after_last ($instr, $inthat)
{
        if (!is_bool(dynamic_ud_strrevpos($inthat, $instr)))
        	return substr($inthat, dynamic_ud_strrevpos($inthat, $instr)+strlen($instr));
}
    
function dynamic_ud_before_last ($instr, $inthat)
{
        return substr($inthat, 0, dynamic_ud_strrevpos($inthat, $instr));
}

function dynamic_ud_between_last ($instr, $that, $inthat)
{
        return dynamic_ud_after_last($instr, dynamic_ud_before_last($that, $inthat));
}    

function dynamic_ud_strrevpos($instr, $needle)
{
    $rev_pos = strpos (strrev($instr), strrev($needle));
    if ($rev_pos===false) return false;
    else return strlen($instr) - $rev_pos - strlen($needle);
};

function dud_endswith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
}

//For backwards compatibility on sites with customizations
if( !function_exists('endswith'))
{
	function endswith($string, $test) {
		$strlen = strlen($string);
		$testlen = strlen($test);
		if ($testlen > $strlen) return false;
		return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
	}
}

function dud_build_avatar_profile_link($dud_options, $user_id, $user_avatar_url)
{
	$ud_author_page      = !empty($dud_options['ud_author_page']) ? $dud_options['ud_author_page'] : null;
	$ud_auth_or_bp       = !empty($dud_options['ud_auth_or_bp']) ? $dud_options['ud_auth_or_bp'] : null;
	$ud_show_author_link = !empty($dud_options['ud_show_author_link']) ? $dud_options['ud_show_author_link'] : null;
	$ud_target_window    = !empty($dud_options['ud_target_window']) ? $dud_options['ud_target_window'] : null;
	$user_avatar_url_tmp = "";
	
	$ud_author_posts = dud_cnt_user_posts($user_id);
	if(empty($ud_author_posts)) $ud_author_posts = 0;
			
	//If BuddyPress is installed and the avatar should be linked to the WP Author Page or BP Profile page
	if( function_exists('bp_is_active') && $ud_author_page)
	{
		//if linking to BP profile page
		if($ud_auth_or_bp === "bpp")
		{	
			$user_avatar_url_tmp = "<a href=\"" . bp_core_get_user_domain( $user_id ) . "profile\"";
		}
		else if($ud_auth_or_bp === "bp")
		{	
			$user_avatar_url_tmp = "<a href=\"" . bp_core_get_user_domain( $user_id ) . "\"";
		}
		else if( $ud_author_posts > 0 || $ud_show_author_link === "always")
		{
			$user_avatar_url_tmp = "<a href=\"" . get_author_posts_url( get_the_author_meta( 'ID', $user_id ), 
			get_the_author_meta( 'user_nicename', $user_id ) ) . "\"";
		}
		
		if($ud_auth_or_bp === "bp" || $ud_auth_or_bp === "bpp" || ($ud_auth_or_bp === "auth" && ($ud_author_posts > 0 || $ud_show_author_link === "always")))
		{					
			if($ud_target_window === "separate") $user_avatar_url_tmp .= " target='_blank'";	
			$user_avatar_url_tmp .= ">" . $user_avatar_url . "</a>";
			$user_avatar_url = $user_avatar_url_tmp;
		}
	}
	//If no BuddyPress
	else if($ud_author_page && ($ud_author_posts > 0 || $ud_show_author_link === "always"))
	{     				
		$user_avatar_url_tmp = "<a href=\"" . get_author_posts_url( get_the_author_meta( 'ID', $user_id ), 
			get_the_author_meta( 'user_nicename', $user_id ) ) . "\"";
	
		if($ud_target_window === "separate") $user_avatar_url_tmp .= " target='_blank'";
		$user_avatar_url_tmp .= ">" . $user_avatar_url . "</a>";
		$user_avatar_url = $user_avatar_url_tmp;
	} 
	
	return $user_avatar_url;
}

function dud_build_username_profile_link($dud_options, $user_id, $user_fullname)
{
	$ud_author_page      = !empty($dud_options['ud_author_page']) ? $dud_options['ud_author_page'] : null;
	$ud_auth_or_bp       = !empty($dud_options['ud_auth_or_bp']) ? $dud_options['ud_auth_or_bp'] : null;
	$ud_show_author_link = !empty($dud_options['ud_show_author_link']) ? $dud_options['ud_show_author_link'] : null;
	$ud_target_window    = !empty($dud_options['ud_target_window']) ? $dud_options['ud_target_window'] : null;
	$user_fullname_tmp   = "";
	
	$ud_author_posts = dud_cnt_user_posts($user_id);
	if(empty($ud_author_posts)) $ud_author_posts = 0;
	
	if( function_exists('bp_is_active') && $ud_author_page)
	{
		//if linking to BP profile page
		if($ud_auth_or_bp === "bpp")
		{	
			$user_fullname_tmp = "<a href=\"" . bp_core_get_user_domain( $user_id ) . "profile\"";
		}
		else if($ud_auth_or_bp === "bp")
		{
			$user_fullname_tmp = "<a href=\"" . bp_core_get_user_domain( $user_id ) . "\"";
		}
		//if linking to WP Author Page
		else if( ($ud_author_posts > 0 || $ud_show_author_link === "always") )
		{
			$user_fullname_tmp = "<a href=\"" . get_author_posts_url( get_the_author_meta( 'ID', $user_id ), 
				get_the_author_meta( 'user_nicename', $user_id ) ) . "\"";	
		}
		
		if($ud_auth_or_bp === "bp" || $ud_auth_or_bp === "bpp" || ($ud_auth_or_bp === "auth" && ($ud_author_posts > 0 || $ud_show_author_link === "always")) )
		{
			if($ud_target_window === "separate") $user_fullname_tmp .= " target='_blank'";	
			$user_fullname_tmp .= ">" . $user_fullname . "</a>";	
			$user_fullname = $user_fullname_tmp;
		}
	}
	/*** If no BuddyPress and linking to WP author page ***/
	else if( $ud_author_page && ($ud_author_posts > 0 || $ud_show_author_link === "always") )
	{ 
		$user_fullname_tmp = "<a href=\"" . get_author_posts_url( get_the_author_meta( 'ID', $user_id ), 
			get_the_author_meta( 'user_nicename', $user_id ) ) . "\"";
		
		if($ud_target_window === "separate") $user_fullname_tmp .= " target='_blank'";	
		$user_fullname_tmp .= ">" . $user_fullname . "</a>";
		$user_fullname = $user_fullname_tmp;
	} 
	
	return $user_fullname;
}

function replace_spaces_with_dash($string)
{
	$string = str_replace(' ', '-', $string);
	$string = str_replace('&', 'and', $string);
	return $string;
}

function dud_cnt_last_name_srch_results($uids, $dud_user_srch_name, $user_directory_sort)
{
	$cnt = 0;
	foreach ($uids as $key => $uid)
	{
		if($user_directory_sort === "last_name")
		{
			$user_id = $uid->user_id;
			$user_last_name = get_user_meta($user_id, 'last_name', true);
		}
		else
		{
			$user_id = $uid->ID; 
			$user_last_name = $uid->display_name;
		}
		
		//$user_last_name = get_user_meta($uid, 'last_name', true);
		
		if($dud_user_srch_name)
		{ 
			 if (!(strpos(strtoupper ($user_last_name), strtoupper ($dud_user_srch_name)) === 0))
			 {	  		 
				  continue;
			 }	
			 
			 $cnt++;
		}
	}
	
	return $cnt;
}

/*** Debug Utility ***/
function dynamic_ud_dump_settings($loaded_options)
{
	global $wpdb;
	
	if($loaded_options)
		$dud_options = get_option( $loaded_options );
	else
		$dud_options = get_option( 'dud_plugin_settings' );
	
	echo "<PRE>";
	
	if($loaded_options)
	{
		echo "Loaded option: " . $loaded_options . "<BR><BR>";
		
		echo "Instance name: " . $dud_options['dud_instance_name'] . "<BR><BR>";
	}
		    	
    echo "Users Table Name: " . DUD_WPDB_PREFIX . "users " . "<BR><BR>";
    	
    echo "User Meta Table Name: " . DUD_WPDB_PREFIX . "usermeta " . "<BR><BR>";
     
    echo "Directory Type: " . $dud_options['ud_directory_type'] . "<BR><BR>";
     	    	
	echo "Sort Field: " . $dud_options['user_directory_sort'] . "<BR>"; 
	
	//$sort_order_items = dynamic_ud_sort_order( $dud_options['user_directory_sort_order'] );
	
	/*echo "<BR>Sort Order:<BR><BR>";
		foreach($sort_order_items as $sort_item) {
			echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $sort_item. "<BR>";
		}*/
		
	echo "<BR>Include/Exclude: " . $dud_options['ud_exclude_include_radio'] . "<BR>"; 
	
	$ud_hide_roles_array = !empty($dud_options['ud_hide_roles']) ? $dud_options['ud_hide_roles'] : null;
	$ud_uids_array = !empty($dud_options['ud_users_exclude_include']) ? $dud_options['ud_users_exclude_include'] : null;
	
	if($ud_uids_array)
		echo "<BR>Size of Include/Exclude UIDs Array: " . sizeof($ud_uids_array) . "<BR>";
	else
		echo "<BR>UIDs Selected for Include/Exclude: none<BR>";
		
	
	if($ud_hide_roles_array)
	{
		$ud_roles_exclude_include_radio   = !empty($dud_options['ud_roles_exclude_include_radio']) ? $dud_options['ud_roles_exclude_include_radio'] : "";
	
		if($ud_roles_exclude_include_radio === "include")
			echo "<BR>Roles selected to Include in Directory:<BR><BR>";
		else
			echo "<BR>Roles selected to Exclude from Directory:<BR><BR>";
		
		foreach($ud_hide_roles_array as $ud_role)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;" . $ud_role . "<BR>";
	}
	else
		echo "<BR>Roles selected for hiding: none<BR>";
		
	/*echo "<BR>Show avatars: " . $dud_options['user_directory_show_avatars'] . "<BR>";     
    echo "<BR>Avatar Style: " . $dud_options['user_directory_avatar_style'] . "<BR>";     
	echo "<BR>Border: " . $dud_options['user_directory_border'] . "<BR>";
	echo "<BR>Border Len: " . $dud_options['user_directory_border_length'] . "<BR>";
	echo "<BR>Border Style: " . $dud_options['user_directory_border_style'] . "<BR>";
	echo "<BR>Border Color: " . $dud_options['user_directory_border_color'] . "<BR>";
	echo "<BR>Border Thickness: " . $dud_options['user_directory_border_thickness'] . "<BR>";
	echo "<BR>Directory Font Size: " . $dud_options['user_directory_listing_fs'] . "<BR>";
	echo "<BR>Directory Listing Spacing: " . $dud_options['user_directory_listing_spacing'] . "<BR>";
	echo "<BR>Link to Author Page: " . $dud_options['ud_author_page'] . "<BR>";
	echo "<BR>Author Page Target Window: " . $dud_options['ud_target_window'] . "<BR>";
	echo "<BR>Users per page: " . $dud_options['ud_users_per_page'] . "<BR>";*/
	
	echo "</PRE>";
}
