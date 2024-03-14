<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //


// folder_move.php

if (dms_get_var("hdn_folder_move") != FALSE)
	{
	global $dmsdb, $dms_config, $dms_global;

	$dest_folder = dms_get_var("rad_selected_obj_id");
	$obj_id = dms_get_var("hdn_obj_id");

	$location = $dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_options&obj_id=".$obj_id;

	$query  = "UPDATE ".$dmsdb->prefix('dms_objects')." ";
	$query .= "SET ";
	$query .= "obj_owner='".$dest_folder."' ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);

	dms_auditing($obj_id,"folder/move/dest folder id=".$dest_folder);
	dms_message("The folder has been moved to the selected destination folder.");

	dms_redirect($location);
	}
else
	{
	global $dmsdb, $dms_global;

	require_once (DMS_DIR . '/includes/general/i_obj_select.php');

	if (dms_get_var("hdn_obj_id") != FALSE) $obj_id = dms_get_var("hdn_obj_id");
	else $obj_id = dms_get_var("obj_id");
	$dms_global['obj_id'] = $obj_id;

	// Permissions required to access this page:
	//  OWNER
	$perms_level = dms_perms_level($obj_id);

	if ( $perms_level != OWNER )
		{
        dms_redirect($dms_config['dms_url']);

		end();
		}

//	$location="folder_move.php";
    $dms_global['calling_file'] = $dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_move";

	// Get folder information
	$query  = "SELECT obj_name from ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$obj_name = $dmsdb->query($query,'obj_name');

	print "  <table width='100%'>\r";
	print "  <form method='post' name='frm_select_obj' action='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_move'>\r";

	dms_display_header(2,"","",FALSE);

	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <tr><td colspan='2' align='left'><b>"._DMS_L_MOVE_FOLDER."</b></td></tr>\r";
	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <tr>\r";
	print "    <td colspan='2' align='left'>"._DMS_L_MOVE_FOLDER_NAME.":&nbsp;&nbsp;&nbsp;";
	print "        ".$obj_name."</td>\r";
	print "  </tr>\r";
	print "  <tr><td colspan='2'><BR></td></tr>\r";

	print "  <tr>\r";
	print "    <td colspan='2' align='left'>\r";

	dms_select_object_id(SELECT_FOLDER,$obj_id,TRUE);

	print "    </td>\r";
	print "  </tr>\r";

	print "  <tr><td colspan='2'><BR></td></tr>\r";
/*
	print "  <td colspan='2' align='left'><input type=button name='btn_submit' value='" . _DMS_MOVE . "' onclick='obj_select_check_for_dest();'>";
	print "                               <input type=button name='btn_cancel' value='" . _DMS_CANCEL . "' onclick='location=\"folder_options.php?obj_id=".$obj_id."\";'></td>\r";
*/
	print "  <td colspan='2' align='left'><input type=button name='btn_submit' value='" . _DMS_L_MOVE . "' onclick='obj_select_check_for_dest();'>&nbsp;&nbsp;";
	print "                               <input type=button name='btn_cancel' value='" . _DMS_L_CANCEL . "' onclick='location=\"".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_options&obj_id=".$obj_id."\";'></td>\r";

	print "</table>\r";
	print "<input type='hidden' name='hdn_folder_move' value='confim'>\r";
	print "<input type='hidden' name='hdn_obj_id' value='".$obj_id."'>\r";
	print "<input type='hidden' name='hdn_destination_folder_id' value=''>\r";
	print "</form>\r";
	}
?>



