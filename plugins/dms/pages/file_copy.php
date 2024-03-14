<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// file_copy.php

require_once (DMS_DIR . '/includes/general/i_dest_path_and_file.php');
require_once (DMS_DIR . '/includes/general/i_file_copy.php');

// Include the select object system.
//include_once 'inc_obj_select.php';

global $dmsdb, $dms_config, $dms_global;


if (dms_get_var("hdn_file_copy") == "confirm")
	{
	$dest_obj_owner = dms_get_var("rad_selected_obj_id");

	$obj_id = dms_get_var("hdn_obj_id");

	$location = $dms_config['dms_url']. $dms_global["first_separator"] ."dms_page=file_options&obj_id=".$obj_id;

	$new_obj_id = dms_file_copy($obj_id,$dest_obj_owner);

	//dms_document_name_sync($new_obj_id);

	dms_auditing($obj_id,"document/copy/dest obj id=".$obj_id."/dest folder id=".$dest_obj_owner);
	dms_auditing($new_obj_id,"document/copy/source obj id=".$obj_id);

	dms_folder_subscriptions($new_obj_id);

	dms_message("The document has been copied to the selected destination directory.");

	dms_redirect($location);

	exit(0);
	}
else
	{
	global $dms_global;

	$obj_id = dms_get_var("hdn_obj_id");
	if($obj_id == FALSE) $obj_id = dms_get_var("obj_id");
	$dms_global['obj_id'] = $obj_id;

	// Permissions required to access this page:
	//  EDIT, OWNER
	$perms_level = dms_perms_level($obj_id);

	if ( ($perms_level != 3) && ($perms_level != 4) )
		{
		print("<SCRIPT LANGUAGE='Javascript'>\r");
		print("location='index.php';");
		print("</SCRIPT>");
		end();
		}

    $dms_global['calling_file'] = $dms_config['dms_url']. $dms_global["first_separator"] ."dms_page=file_copy";

	require_once (DMS_DIR . '/includes/general/i_obj_select.php');

	// Get file information
	$query  = "SELECT obj_name from ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$doc_name = $dmsdb->query($query,'obj_name');

	print "  <form method='post' name='frm_select_obj' action='".$dms_config['dms_url']. $dms_global["first_separator"] ."dms_page=file_copy'>\r";
	print "  <table width='100%'>\r";

	//display_dms_header(2);
	dms_display_header(2,"","",FALSE);

	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <tr><td colspan='2' align='left'><b>Copy Document:</b></td></tr>\r";
	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <tr>\r";
	print "    <td colspan='2' align='left'>Name:&nbsp;&nbsp;&nbsp;";
	print "        ".$doc_name."</td>\r";
	print "  </tr>\r";
	print "  <tr><td colspan='2'><BR></td></tr>\r";

	print "  <tr>\r";
	print "    <td colspan='2' align='left'>\r";

	dms_select_object_id(SELECT_FOLDER,$obj_id);

	print "    </td>\r";
	print "  </tr>\r";

	print "  <tr><td colspan='2'><BR></td></tr>\r";

	print "  <td colspan='2' align='left'><input type=button name='btn_submit' value='" ._DMS_L_COPY. "' onclick='obj_select_check_for_dest();'>";
	print "                               <input type=button name='btn_cancel' value='" ._DMS_L_CANCEL. "' onclick='location=\"".$dms_config['dms_url']. $dms_global["first_separator"] ."dms_page=file_options&obj_id=".$obj_id."\";'></td>\r";
	print "</table>\r";
	print "<input type='hidden' name='hdn_file_copy' value='confirm'>\r";
	print "<input type='hidden' name='hdn_obj_id' value='".$obj_id."'>\r";
	print "<input type='hidden' name='hdn_destination_folder_id' value=''>\r";
	print "</form>\r";
	}
?>



