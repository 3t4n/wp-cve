<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //


global $dms_global;

// folder_options.php

$option_button_width=" style='width: 6em;' ";
//$folder_flag = "TRUE";

$obj_id = dms_get_var("hdn_obj_id");
if($obj_id == FALSE) $obj_id = dms_get_var("obj_id");

$this_file = $dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_options&obj_id=".$obj_id;

$perms_level = dms_perms_level($obj_id);

if (dms_get_var("hdn_update_options") == "confirm" )
	{
	global $dmsdb;

	$obj_name = dms_strprep(dms_get_var("txt_obj_name") );

	$query  = "UPDATE ".$dmsdb->prefix('dms_objects')." SET ";
	$query .= "obj_name='".$obj_name."' ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);

	dms_auditing($obj_id,"folder/update properties & permissions");

	dms_redirect($dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_options&obj_id=".$obj_id);
	}
else
	{
	global $dmsdb, $dms_admin_flag;

	// Get object information
	$query  = "SELECT obj_status, obj_type, obj_name from ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$result = $dmsdb->query($query,'ROW');

	// Get the folder_archive_flag, doc_name_sync_flag, and disp_file_comments_flag.
	$folder_archive_flag = FALSE;
	$doc_name_sync_flag = FALSE;
	$disp_file_comments_flag = FALSE;
	$query  = "SELECT data FROM ".$dmsdb->prefix("dms_object_misc")." ";
	$query .= "WHERE obj_id='".$obj_id."' AND data_type='".FLAGS."'";
	$flags = $dmsdb->query($query,'data');

	if($dmsdb->getnumrows() == 0) $flags = 0;

	if ( ($flags & 1) == 1 ) $folder_archive_flag = TRUE;
	if ( ($flags & 2) == 2 ) $doc_name_sync_flag = TRUE;
	if ( ($flags & 4) == 4 ) $disp_file_comments_flag = TRUE;

	// Message Box                                                                     DISABLED!!!!!!
/*
	include_once 'inc_message_box.php';
	dms_message_box();
	dms_dhtml_mb_functions();
*/
	// Options Menu
    //  Restore, Archive, Copy, Move, Delete

	print "<table width='100%' border='0'>\r";

	print "  <tr>\r";

	print "    <td>\r";
	print "      <table border='0' cellpadding='0' cellspacing='0'>\r";

	dms_display_header(2);

	print "      <tr><td colspan='2'><BR></td></tr>\r";

    //  Display the type and name of the folder.
    $object_type = "Folder";
    if( ($result->obj_type == INBOXEMPTY) || ($result->obj_type == INBOXFULL) )	$object_type = "Inbox";


	switch ($result->obj_type)
    {
        case INBOXEMPTY:
            $object_type = _DMS_L_INBOX;
            $object_icon = DMS_ICONS."/custom/inbox_empty_32.png";;
            break;
        case INBOXFULL:
            $object_type = _DMS_L_INBOX;
            $object_icon = DMS_ICONS."/custom/inbox_full_32.png";;
            break;

        default:     // Folder
            $object_type = _DMS_L_FOLDER;
            $object_icon = DMS_ICONS."/tango_icons/32x32/folder.png";;
    }


    print "      <tr><td colspan='2'><img src=\"".$object_icon."\" alt=\"".$object_type."\" title=\"".$object_type."\">&nbsp;&nbsp;<b>".$result->obj_name."</b></td></tr>";
    //print "      <tr><td colspan='2'>".$object_type.":&nbsp;&nbsp;<b>".$result->obj_name."</b></td></tr>";

	print "      <tr><td colspan='2'><BR></td></tr>\r";

	// Options Menu
	print "      <tr>\r";
	print "        <td align='left' valign='top' colspan='2' ".$dms_config['class_content']." >\r";

    print "          <table>";
    print "            <tr><td>\r";

	if($perms_level == OWNER || $dms_admin_flag == 1)
		{

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  OPTIONS MENU
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        print "<div class='dms_drop_down_div'>\r";
        print "     <button class='dms_drop_down_button'>" . _DMS_L_OPTIONS . "</button>\r";
        print "     <div class='dms_drop_down_content'>\r";

        print "<BR>";

        $link = $dms_config['dms_url'] . $dms_global["first_separator"]."dms_page=folder_move&obj_id=".$obj_id."&return_page=folder_options";
        print "<a href='".$link."'>" . _DMS_L_MOVE . "</a>";

        print "&nbsp;&nbsp;";

        $link = $dms_config['dms_url'] . $dms_global["first_separator"]."dms_page=obj_delete&obj_id=".$obj_id."&return_page=folder_options";
        print "<a href='".$link."'>" . _DMS_L_DELETE . "</a>";

        print "<BR>&nbsp;";

        print "     </div>\r";
        print "</div>\r";

        print "&nbsp;&nbsp;";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}

	// Permissions Button
	if($perms_level == OWNER || $dms_admin_flag == 1)
	  print "<input type='button' name='btn_perms' value='"._DMS_L_PERMISSIONS."' onclick='location=\"".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_options&obj_id=".$obj_id."#perms_set\";'>";


      print "&nbsp;&nbsp;";
	// Optional Help Button
	//dms_help_system("folder_options",10);

    //  Exit Button
	print "<input type='button' name='btn_exit' value='"._DMS_L_EXIT."' onclick='location=\"".$dms_config['dms_url']."\";'>";
    print "            </td></tr>\r";


    print "          </table>\r";

	print "        </td>\r";
	print "      </tr>\r";

	if($dms_admin_flag == 1)
		{
		print "        <tr><td colspan='2'><BR></td></tr>\r";
		print "        <tr><td colspan='2' align='left' ".$dms_config['class_subheader'].">Configuration</td></tr>\r";


        if($dms_admin_flag == 1 && $result->obj_type != INBOXFULL)
            {
            print "      <tr>\r";
            print "        <td></td>\r";
            print "        <td align='right' valign='top' ".$dms_config['class_content'].">\r";

            print "          <form name='frm_change_config' method='post' action='".$dms_config['dms_url'].$dms_global['first_separator']."dms_page=folder_config_change'>\r";

            print "            <tr>\r";
            print "                <td align='left' nowrap>\r";

            $slct_folder_type_option[FOLDER] = "";
            $slct_folder_type_option[INBOXEMPTY] = "";
            $slct_folder_type_option[DISKDIR] = "";
            $slct_folder_type_option[$result->obj_type] = " SELECTED";

            print "          &nbsp;&nbsp;&nbsp;Folder Type:  \r";
            print "          <select name='slct_folder_type' ".$dms_config['class_content'].">\r";
            print "            <option value='".FOLDER."' ".$slct_folder_type_option[FOLDER].">Document Folder</option>\r";
            print "          </select>\r";

            print "          <BR>\r";

            $query  = "SELECT data FROM ".$dmsdb->prefix("dms_object_misc")." ";
            $query .= "WHERE obj_id=".$obj_id." AND data_type='".FOLDER_AUTO_LIFECYCLE_NUM."'";
            $folder_auto_lifecycle_num = $dmsdb->query($query,'data');

            if($dmsdb->getnumrows() == 0) $folder_auto_lifecycle_num = "";

            print "<BR>";

//            print "<BR><BR>";

            print "&nbsp;&nbsp;&nbsp;<input type='button' name='btn_change_config' value='Update Configuration' onclick='frm_change_config.submit();'>\r";

            print "<input type='hidden' name='hdn_obj_id' value='".$obj_id."'>\r";

            print "                </td>\r";
            print "              </tr>\r";
            print "          </form>\r";

            print "        </td>\r";
            print "      </tr>\r";
            }
        else
            {
            print "        <tr><td colspan='2'>"._DMS_L_CONFIG_INBOX_NOT_EMPTY."</td></tr>\r";
            }

		}


	// Options Menu End

	print "        <tr><td colspan='2'><BR></td></tr>\r";
	// Display properties
	print "        <form method='post' name='frm_properties' action='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_options'>\r";

	print "        <tr><td colspan='1' align='left' ".$dms_config['class_subheader'].">&nbsp;" . _DMS_L_PROPERTIES . "</td>\r";
	print "          <td align='right' ".$dms_config['class_subheader'].">";
	dms_help_system("file_options_properties");
	print "          </td>\r";
	print "        </tr>\r";


	print "        <tr><td colspan='2'><BR></td></tr>\r";
	print "        <tr>\r";
	print "          <td align='left' colspan='2'>&nbsp;&nbsp;&nbsp;" . _DMS_L_NAME_DOT . "";
	dms_display_spaces(5);
	//print '          <td align="left">
	print '          <input type="text" name=txt_obj_name value="'.$result->obj_name.'" size="40" maxlength="250">'."\r";
	print "          </td>\r";
	print "        </tr>\r";

	if($perms_level == OWNER)
		{
		print "        <tr><td colspan='2'><BR></td></tr>\r";
		print "        <tr>\r";
		print "          <td colspan='2' align='left'>\r";
		print "            &nbsp;&nbsp;&nbsp;<input type=submit name='btn_submit' value='" . _DMS_L_UPDATE_PROPERTIES . "'>";
		print "          </td>\r";
		print "        </tr>\r";
		}

	print "        <tr><td colspan='2'><BR></td></tr>\r";

	print "        <input type='hidden' name='hdn_update_options' value='confirm'>\r";
	print "        <input type='hidden' name='hdn_obj_id' value='".$obj_id."'>\r";
	print "        <input type='hidden' name='hdn_cancel_checkout' value='false'>\r";
	print "        </form>\r";

	if($perms_level == OWNER || $dms_admin_flag == 1)  // Only allow changes to the permissions if the user is the owner of the folder.
		{
		print "        <tr>\r";
		print "          <td colspan='2'>\r";

        include( DMS_DIR . "/includes/general/i_perms_set.php" );

		print "          </td>\r";
		print "        </tr>\r";
		}

	print "      </table>\r";
	print "    </td>\r";
	print "  </tr>\r";
	print "</table>\r";
	}

//dms_show_mb();

?>
