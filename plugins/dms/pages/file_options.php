<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //


// file_options.php

global $dmsdb, $dms_global, $dms_admin_flag;

require_once (DMS_DIR . "/includes/general/i_file_properties.php");

$option_button_width=" style='width: 6em;' ";

$obj_id = dms_get_var("hdn_obj_id");
if($obj_id == FALSE) $obj_id = dms_get_var("obj_id");

$this_file = $dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_options&obj_id=".$obj_id;

$obj_path = array();

// Permissions required to access this page:
//  BROWSE, READONLY, EDIT, OWNER
$perms_level = dms_perms_level($obj_id);
//$perms_level = dms_determine_admin_perms($perms_level)
if ($dms_admin_flag == 1) $perms_level = OWNER;

if ( ($perms_level != 1) && ($perms_level != 2) && ($perms_level != 3) && ($perms_level != 4) )
	{
	dms_header_redirect($dms_config['dms_url']);

	end();
	}


function dms_get_date($var_name, $current_value = -1)
	{
// If there is a current_value, convert it into the appropriate information
	if($current_value != -1)
		{
		$month  = (int)strftime("%m",$current_value);
		$day    = (int)strftime("%d",$current_value);
		$year   = (int)strftime("%Y",$current_value);
		}

// Get Month
	print "<select name='slct_".$var_name."_month'>\r";
	for($index = 1;$index <= 12; $index++)
		{
		$selected = "";
		if( ($current_value != -1) && ($index == $month) ) $selected = "SELECTED";
		print "  <option ".$selected.">".$index."</option>\r";
		}
	print "</select>\r";

	print "/&nbsp;";

// Get Day
	print "<select name='slct_".$var_name."_day'>\r";
	for($index = 1;$index <= 31; $index++)
		{
		$selected = "";
		if( ($current_value != -1) && ($index == $day) ) $selected = "SELECTED";
		print "  <option ".$selected.">".$index."</option>\r";
		}
	print "</select>\r";

	print "/&nbsp;";

// Get Year
	print "<select name='slct_".$var_name."_year'>\r";
	for($index = 2007;$index <= 2030; $index++)
		{
		$selected = "";
		if( ($current_value != -1) && ($index == $year) ) $selected = "SELECTED";
		print "  <option ".$selected.">".$index."</option>\r";
		}
	print "</select>\r";
	}


function dms_get_obj_path($obj_id)
	{
	global $dmsdb;

	$obj_path = array();

	// First get the obj_owner (parent folder) of the object.
	$query = "SELECT obj_owner FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_id."'";
	$obj_owner = $dmsdb->query($query,"obj_owner");

	$loop_flag = TRUE;
	$index = 0;

	while($loop_flag == TRUE)
		{
		$query  = "SELECT obj_owner,obj_name FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_owner."'";
		$result = $dmsdb->query($query,"ROW");
		if($dmsdb->getnumrows() == 0) break;

		$obj_path["obj_name"][$index] = $result->obj_name;
		$obj_path["obj_id"][$index] = $obj_owner;

		$obj_owner = $result->obj_owner;

		if($result->obj_owner == 0) $loop_flag = FALSE;

		$index++;
		}

	$obj_path["total_num_objects"] = $index;

	return $obj_path;
	}

if(dms_get_var("hdn_update_doc_exp") == "confirm")
	{
	$obj_id=dms_get_var("hdn_obj_id_doc_exp");

	$expire_month = dms_get_var("slct_time_stamp_expire_month");
	$expire_day = dms_get_var("slct_time_stamp_expire_day");
	$expire_year = dms_get_var("slct_time_stamp_expire_year");
	$time_stamp_expire = mktime(0,0,0,$expire_month,$expire_day,$expire_year);

	$enable_doc_expiration = dms_get_var_chk("chk_document_expiration_enable");
	if($enable_doc_expiration == 0) $time_stamp_expire = '0';

	$query  = "UPDATE ".$dmsdb->prefix('dms_objects')." SET ";
	$query .= "time_stamp_expire='".$time_stamp_expire."' WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);

	dms_auditing($obj_id,"document/update expiration:".$time_stamp_expire);

	dms_message("The document expiration settings have been updated.");
	}



if (dms_get_var("hdn_update_options") == "confirm")
	{
	dms_auditing($obj_id,"document/update properties");

	$obj_name = dms_strprep(dms_get_var("txt_obj_name"));

	$query  = "UPDATE ".$dmsdb->prefix('dms_objects')." SET ";
	$query .= "obj_name='".$obj_name."' ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);

	dms_document_name_sync($obj_id);

	update_file_properties($obj_id);

	dms_message("The document properties have been updated.");

	dms_redirect($dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_options&obj_id=".$obj_id);
	}
else
	{
	// Get object information
	$query  = "SELECT template_obj_id,obj_name,obj_status,obj_checked_out_user_id,lifecycle_id, lifecycle_stage, ";
	$query .= "time_stamp_create,time_stamp_expire,current_version_row_id ";
	$query .= "FROM ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$object = $dmsdb->query($query,'ROW');

	// Get current version information
	$query  = "SELECT major_version,minor_version,sub_minor_version,file_type,file_size,time_stamp ";
	$query .= "FROM ".$dmsdb->prefix("dms_object_versions")." ";
	$query .= "WHERE row_id='".$object->current_version_row_id."'";
	$current_version = $dmsdb->query($query,'ROW');
	//mysql_fetch_object(mysql_query($query));

	if ($object->obj_status == CHECKEDOUT)
		{
		$checked_out = TRUE;
		}
	else
		{
		$checked_out = FALSE;
		}

	// Determine if the user is subscribed to this document.
	$query  = "SELECT count(row_id) as num from ".$dmsdb->prefix("dms_subscriptions")." ";
	$query .= "WHERE obj_id='".$obj_id."' and user_id='".$dms_user_id."'";
	$subscribed = $dmsdb->query($query,'num');

	if($subscribed > 0) $subscribed = TRUE;
	else $subscribed = FALSE;




// Message Box
//include_once 'inc_message_box.php';
//dms_message_box();
//dms_dhtml_mb_functions();


// Options Menu



	// Javascript to check if a Document Name Exists
	print "<SCRIPT LANGUAGE='Javascript'>\r";
	print "function exit_to_main_page()\r";
	print "  {\r";
	print "  var exit_page = 1;\r";
	if ( ($perms_level == EDIT) || ($perms_level == OWNER) )
		{
		print "  //var frm_options = document.forms.namedItem(\"frm_options\");\r";
		print "  if ( document.frm_options.txt_obj_name.value == \"\" )\r";
		print "    {\r";
		print "    alert('Please enter a document name.');\r";
		print "    document.frm_options.txt_obj_name.focus();\r";
		print "    exit_page = 0;\r";
		print "    }\r";
		}
	print "  if (exit_page == 1) location=\"index.php\";\r";
	print "  }\r";
	print "</SCRIPT>\r";

	// Add the version_view() javascript function
	print "<SCRIPT LANGUAGE='Javascript'>\r";
	print "function version_view()\r";
	print "  {\r";
	print "  if (document.frm_ver_view.slct_version_view.value == 0) return;\r";

	print "  var url = '".DMS_URL."pages/file_retrieve.php?function=vv&obj_id=".$obj_id."&ver_id=';\r";
	print "  url = url + document.frm_ver_view.slct_version_view.value;\r";

	print "  window.open(url);\r";
	print "  }\r";
	print "</SCRIPT>\r";

	// Add to the document history
	dms_doc_history($obj_id);

	print "<table cellpadding='0' cellspacing='0' border='0' width='100%'>\r";

	print "  <tr>\r";

	print "    <td valign='top'>\r";
	print "      <table border='0' valign='top'>\r";

	print "      <tr><td><table valign='top' cellpadding='0' cellspacing='0' width='100%'>\r";

	dms_display_header(2);

	print "        <tr><td colspan='2'><BR></td></tr>\r";

	// Display the document name (link to view it) and display the version view drop-down box.
	print "        <form name='frm_ver_view'>\r";

	print "        <tr>\r";
	print "          <td align='left' style='text-align: left' width='65%'>\r";

	if ($perms_level > BROWSE)
		print "<a href='#' title='View Document' onclick='javascript:void(window.open(\"".DMS_URL."pages/file_retrieve.php?function=view&obj_id=".$obj_id."\"))'><font size='3'><b>".$object->obj_name."</b></font></a>\r";
	else
		print "<font size='3'><b>".$object->obj_name."</b></font>\r";

	print "          </td>\r";

	print "          <td style='text-align: right'>\r";
	print "          </td>\r";
	print "        </tr>\r";

	print "        </form>\r";


	print "        <tr><td colspan='2'><BR></td></tr>\r";

	print "    <td align='left' valign='top' colspan='2' ".$dms_config["class_content"].">\r";

    //  Start of button menu table.
    print "<table>";
    print "<tr><td>";

	if ( ($perms_level == READONLY) || ($perms_level == EDIT) || ($perms_level == OWNER) )
		{
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  OPTIONS MENU
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        print "<div class='dms_drop_down_div'>\r";
        print "     <button class='dms_drop_down_button'>" . _DMS_L_OPTIONS . "</button>\r";
        print "     <div class='dms_drop_down_content'>\r";

        print "<BR>";



        if ( ($perms_level == EDIT) || ($perms_level == OWNER) )
            {
            // Copy/Move/Delete Buttons
            if ( ( $checked_out==FALSE) || ($dms_admin_flag == 1) )
                {
                if ( ($checked_out==FALSE) && ( ($object->lifecycle_id == 0) || ($dms_admin_flag == 1) ) )
                    {
                    $link = $dms_config['dms_url'] . $dms_global["first_separator"]."dms_page=file_copy&obj_id=".$obj_id."&return_page=file_options";
                    print "<a href='".$link."'>" . _DMS_L_COPY . "</a>";

                    print "&nbsp;&nbsp;";

                    $link = $dms_config['dms_url'] . $dms_global["first_separator"]."dms_page=file_move&obj_id=".$obj_id."&return_page=file_options";
                    print "<a href='".$link."'>" . _DMS_L_MOVE . "</a>";

                    print "&nbsp;&nbsp;";
                    }


                $link = $dms_config['dms_url'] . $dms_global["first_separator"]."dms_page=obj_delete&obj_id=".$obj_id."&return_page=file_options";
                print "<a href='".$link."'>" . _DMS_L_DELETE . "</a>";
                }

                print "<BR>&nbsp;";

            }

        print "     </div>\r";
        print "</div>\r";

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// Options Menu End
		}

	// Information Button
	print "&nbsp;&nbsp;<input type='button' name='btn_info' value='Information' onclick='location=\"".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_options&obj_id=".$obj_id."#info\";'>";
    print "&nbsp;&nbsp;";

	// Permissions Button
	if ( $perms_level == OWNER )
        {
		print "<input type='button' name='btn_perms' value='"._DMS_L_PERMISSIONS."' onclick='location=\"".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_options&obj_id=".$obj_id."#perms_set\";'>";
        print "&nbsp;&nbsp;";
        }
	// Optional Help Button
	//dms_help_system("file_options",10);

	// Exit Button
	print "<input type='button' name='btn_exit' value='"._DMS_L_EXIT."' onclick='location=\"".$dms_config['dms_url']."\";'>";

    print "</td></tr>";

    print "</table>";


    //  End of Button Menu Table


	print "     </table></td></tr>\r";



	print "      <tr><td><table border='0' valign='top' cellpadding='0' cellspacing='0' width='100%'>\r";

	// Display the properties
	print "        <form method='post' name='frm_options' action='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_options'>\r";

	print "        <tr>\r";
	print "          <td colspan='1' align='left' ".$dms_config['class_subheader'].">&nbsp;" . _DMS_L_PROPERTIES . "</td>\r";
	print "          <td align='right' ".$dms_config['class_subheader'].">";
	dms_help_system("file_options_properties");
	print "          </td>\r";
	print "        </tr>\r";

	print "        <tr><td colspan='2'><BR></td></tr>\r";
	print "        <tr>\r";
	print "          <td align='left'>&nbsp;&nbsp;&nbsp;" . _DMS_L_NAME_DOT . "</td>";

	if ( ($perms_level == EDIT) || ($perms_level == OWNER) )
		{
		print "          <td align='left'><input type='text' name=txt_obj_name value=\"".$object->obj_name."\" size='40' maxlength='250' tabindex='100'></td>\r";
		}
	else
		{
		print "          <td align='left'>".$object->obj_name."</td>\r";
		}

	print "        </tr>\r";

	if ( ($perms_level == READONLY) || ($perms_level == EDIT) || ($perms_level == OWNER) )
		{
		display_file_properties($obj_id,3);

		print "        <tr><td colspan='2'><BR></td></tr>\r";

		if ( ($perms_level == EDIT) || ($perms_level == OWNER) )
			{
			print "        <tr>\r";
			print "          <td colspan='2' align='left'>\r";
			print "            &nbsp;&nbsp;&nbsp;<input type=submit name='btn_update' value='" . _DMS_L_UPDATE_PROPERTIES . "' tabindex='200'>";
			print "          </td>\r";
			print "        </tr>\r";
			print "        <tr><td colspan='2'><BR></td></tr>\r";
			}

		print "        <input type='hidden' name='hdn_update_options' value='confirm'>\r";
		print "        <input type='hidden' name='hdn_obj_id' value='".$obj_id."'>\r";
		print "        <input type='hidden' name='hdn_cancel_checkout' value='false'>\r";
		}
	print "        <tr><td colspan='2'><BR></td></tr>\r";

	print "        </form>\r";

	// Display document information
	print "        <tr><td colspan='2' align='left' ".$dms_config['class_subheader'].">&nbsp;";
	print "<a name='info'></a>\r";
	print "Information:</td></tr>\r";
	print "        <tr><td colspan='2'><BR></td></tr>\r";

	//if ( ($perms_level == BROWSE) || ($perms_level == READONLY) )
		//{
	print "        <tr>\r";
	print "          <td align='left' width='30%'>&nbsp;&nbsp;&nbsp;" . _DMS_L_DOC_OWNER . "</td>";
	print "          <td align='left'>".$dms_users->get_username(dms_perms_owner_user_id($obj_id))."</td>\r";
	print "        </tr>\r";
		//}


	print "        <tr>\r";
	print "          <td align='left' width='30%'>&nbsp;&nbsp;&nbsp;Location:</td>";
	print "          <td align='left'>";

	$obj_path = dms_get_obj_path($obj_id);

	$index = $obj_path['total_num_objects'];
	$index--;

	while($index >= 0)
		{
		print $obj_path['obj_name'][$index];
		if($index != 0) print ", ";

		$index--;
		}


	print "          </td>\r";
	print "        </tr>\r";

	print "        <tr>\r";
	print "          <td align='left' width='30%'>&nbsp;&nbsp;&nbsp;" . _DMS_L_PERMISSION_LEVEL . "</td>";
	print "          <td align='left'>";

	switch ($perms_level)
		{
		case BROWSE:
			print _DMS_L_BROWSE;
			break;
		case READONLY:
			print _DMS_L_READ_ONLY;
			break;
		case EDIT:
			print _DMS_L_EDIT;
			break;
		case OWNER:
			print _DMS_L_OWNER;
			break;
		}

	print "          </td>\r";
	print "        </tr>\r";

	if($dms_config['checkinout_enable'] == 1)
		{
		print "        <tr>\r";
		print "          <td align='left' width='30%'>&nbsp;&nbsp;&nbsp;" . _DMS_L_CURRENT_VERSION . "</td>";
		print "          <td align='left'>".$current_version->major_version.".".$current_version->minor_version.$current_version->sub_minor_version."</td>\r";
		print "        </tr>\r";
		}

	if($object->lifecycle_id >0)
		{
		$query  = "SELECT lifecycle_name FROM ".$dmsdb->prefix("dms_lifecycles")." WHERE ";
		$query .= "lifecycle_id = '".$object->lifecycle_id."'";
		$lifecycle_name = $dmsdb->query($query,'lifecycle_name');

		$query  = "SELECT lifecycle_stage_name FROM ".$dmsdb->prefix("dms_lifecycle_stages")." WHERE ";
		$query .= "lifecycle_id = '".$object->lifecycle_id."' AND lifecycle_stage = '".$object->lifecycle_stage."'";
		$lifecycle_stage_name = $dmsdb->query($query,'lifecycle_stage_name');

		print "        <tr>\r";
		print "          <td align='left'>&nbsp;&nbsp;&nbsp;Lifecycle Name:</td>\r";
		print "          <td align='left'>".$lifecycle_name."</td>\r";
		print "        </tr>\r";

		print "        <tr>\r";
		print "          <td align='left'>&nbsp;&nbsp;&nbsp;Lifecycle Stage:</td>\r";
		print "          <td align='left'>".$lifecycle_stage_name."</td>\r";
		print "        </tr>\r";
		}

	if ( ($perms_level == READONLY) || ($perms_level == EDIT) || ($perms_level == OWNER) )
		{
		print "        <tr>\r";
		print "          <td align='left'>&nbsp;&nbsp;&nbsp;" . _DMS_L_CREATED . "</td>";

		if ($object->time_stamp_create == 0)
			{
			print "          <td align='left'>" . _DMS_L_NA . "</td>\r";
			}
		else
			{
			print "          <td align='left'>".strftime("%d-%B-%Y %I:%M%p",$object->time_stamp_create)."</td>\r";
    			}

		print "        </tr>\r";

		print "        <tr>\r";
		print "          <td align='left'>&nbsp;&nbsp;&nbsp;" . _DMS_L_MODIFIED . "</td>";

		if ($current_version->time_stamp == 0)
			{
			print "          <td align='left'>" . _DMS_L_NA . "</td>\r";
			}
		else
			{
    		print "          <td align='left'>".strftime("%d-%B-%Y %I:%M%p",$current_version->time_stamp)."</td>\r";
			}

		print "        </tr>\r";

		print "        <tr>\r";
		print "          <td align='left'>&nbsp;&nbsp;&nbsp;" . _DMS_L_SIZE . "</td>";
		print "          <td align='left'>".$current_version->file_size." bytes</td>\r";
		print "        </tr>\r";

		// If this document was created with a template, display the name of the template
		if ($object->template_obj_id > 0)
			{
			// Get object information
			$query  = "SELECT obj_name ";
			$query .= "FROM ".$dmsdb->prefix("dms_objects")." ";
			$query .= "WHERE obj_id='".$object->template_obj_id."'";
			$template_object = $dmsdb->query($query,'obj_name'); //mysql_fetch_object(mysql_query($query));

			print "        <tr>\r";
			print "          <td align='left'>&nbsp;&nbsp;&nbsp;" . _DMS_L_TEMPLATE_NAME . "</td>";
			print "          <td align='left'>".$template_object."</td>\r";
			print "        </tr>\r";
			}

		// If subscriptions are enabled and the user is subscribed, print "Subscribed"
		if( ($dms_config['sub_email_enable']=='1') && ( ($perms_level == EDIT) ||  ($perms_level == OWNER) ) )
			{
			print "        <tr>\r";
			print "          <td align='left'>&nbsp;&nbsp;&nbsp;Subscribed:</td>\r";
			if($subscribed==TRUE) 	print "          <td align='left'>Yes</td>\r";
			else 					print "          <td align='left'>No</td>\r";
			print "        </tr>\r";
			}

		print "        <tr><td colspan='2'><BR></td></tr>\r";
		}


	print "        </table></td></tr>\r";

	if ( $perms_level == OWNER )
		{
		print "        <tr>\r";
		print "          <td colspan='2'>\r";

		require ( DMS_DIR . "/includes/general/i_perms_set.php" );

		print "          </td>\r";
		print "        </tr>\r";
		}

	print "      </table>\r";

	print "    </td>\r";
	print "  </tr>\r";
	print "</table>\r";


/*
foreach ($GLOBALS as $key=>$value)
	{
	print "\$GLOBALS[\"$key\"]==$value<br>";
	}
*/
	}
?>
