<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //


// obj_delete.php


global $dms_global;

// Functions

function dms_delete_file($obj_id)
	{
	global $dms_config,$dmsdb;

	dms_doc_history_delete($obj_id);

	if(($dms_config['purge_enable'] == 1) && ($dms_config['purge_delay'] == 0))
		{
		dms_purge_document($obj_id);
		}
	else
		{
		dms_set_obj_status($obj_id,DELETED);
		}

	dms_folder_subscriptions($obj_id);
	}

function dms_delete_file_link($obj_id)
	{
	global $dmsdb;

	$query  = "DELETE FROM ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id=".$obj_id;
	$dmsdb->query($query);

	$query  = "DELETE FROM ".$dmsdb->prefix("dms_object_perms")." ";
	$query .= "WHERE ptr_obj_id=".$obj_id;
	$dmsdb->query($query);

	$query = "DELETE FROM ".$dmsdb->prefix("dms_object_properties")." WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);
	}

function dms_delete_folder($obj_id)
	{
	global $dms_config,$dmsdb,$dms_user_id;

	// Contract the folder
	$query  = "DELETE FROM ".$dmsdb->prefix("dms_exp_folders");
	$query .= " WHERE user_id='".$dms_user_id."' AND folder_id='".$obj_id."'";
	$dmsdb->query($query);

	// Make sure that this folder cannot be marked as active
	$query  = "DELETE FROM ".$dmsdb->prefix("dms_active_folder");
	$query .= " WHERE user_id='".$dms_user_id."' AND folder_id='".$obj_id."'";
	$dmsdb->query($query);

	// If purge is enabled and the purge delay is 0 then immediately delete the folder if there aren't any child
	// objects.
	if(($dms_config['purge_enable'] == 1) && ($dms_config['purge_delay'] == 0))
		{
		// Check for child objects
		$query  = "SELECT obj_id FROM ".$dmsdb->prefix("dms_objects")." ";
		$query .= "WHERE obj_owner = ".$obj_id." ";
		$query .= "LIMIT 2";
		$dmsdb->query($query);
		$num_rows = $dmsdb->getnumrows();

		if($num_rows > 0)
			{
			// Mark the folder as deleted
			dms_set_obj_status($obj_id,DELETED);
			}
		else
			{
			// Permanently delete the folder
			$query  = "DELETE FROM ".$dmsdb->prefix("dms_objects")." ";
			$query .= "WHERE obj_id=".$obj_id;
			$dmsdb->query($query);

			$query  = "DELETE FROM ".$dmsdb->prefix("dms_object_perms")." ";
			$query .= "WHERE ptr_obj_id=".$obj_id;
			$dmsdb->query($query);

			$query  = "DELETE FROM ".$dmsdb->prefix('dms_object_misc')." WHERE ";
			$query .= "obj_id='".$obj_id."' AND ";
			$query .= "data_type='".PATH."'";
			$dmsdb->query($query);
			}
		}
	else
		{
		// Mark the folder as deleted
		dms_set_obj_status($obj_id,DELETED);
		}
	}

function dms_delete_folder_link($obj_id)
	{
	global $dmsdb;

	$query  = "DELETE FROM ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id=".$obj_id;
	$dmsdb->query($query);

	$query  = "DELETE FROM ".$dmsdb->prefix("dms_object_perms")." ";
	$query .= "WHERE ptr_obj_id=".$obj_id;
	$dmsdb->query($query);

	$query = "DELETE FROM ".$dmsdb->prefix("dms_object_properties")." WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);
	}

function dms_delete_url($obj_id)
	{
	global $dms_config,$dmsdb,$dms_user_id;

	if(($dms_config['purge_enable'] == 1) && ($dms_config['purge_delay'] == 0))
		{
		$query = "DELETE FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);

		$query = "DELETE FROM ".$dmsdb->prefix("dms_object_perms")." WHERE ptr_obj_id='".$obj_id."'";
		$dmsdb->query($query);

		$query = "DELETE FROM ".$dmsdb->prefix("dms_object_misc")." WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);

		$query = "DELETE FROM ".$dmsdb->prefix("dms_object_properties")." WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);
		}
	else
		{
		// Mark the url as being deleted.
		dms_set_obj_status($obj_id,DELETED);
		}
	}

function dms_delete_link($obj_id)
	{
	global $dmsdb;

	// Get the obj_id of the inbox
	$query = "SELECT obj_owner FROM ".$dmsdb->prefix('dms_objects')." WHERE obj_id='".$obj_id."'";
	$obj_owner = $dmsdb->query($query,"obj_owner");

	// Delete the link
	$query = "DELETE from ".$dmsdb->prefix('dms_objects')." WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);

	$query = "DELETE from ".$dmsdb->prefix('dms_object_perms')." WHERE ptr_obj_id='".$obj_id."'";
	$dmsdb->query($query);

	$query = "DELETE from ".$dmsdb->prefix('dms_routing_data')." WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);

	dms_set_inbox_status($obj_owner);
	}

// Permissions required to access this page:
//  EDIT, OWNER

global $dmsdb, $dms_config;

$obj_id = dms_get_var("obj_id");
if($obj_id == FALSE) $obj_id=dms_get_var("hdn_obj_id");
if($obj_id == FALSE) $obj_id = 0;

$perms_level = dms_perms_level($obj_id);

if ( ($perms_level != EDIT) && ($perms_level != OWNER) )
	{
	dms_redirect($dms_config['dms_url']);
	end();
	}

// Get object information
$query  = "SELECT obj_name,obj_type from ".$dmsdb->prefix("dms_objects")." ";
$query .= "WHERE obj_id='".$obj_id."'";
$obj_info = $dmsdb->query($query,"ROW");
$obj_name = $obj_info->obj_name;

if($obj_info->obj_type == DOCLINK)
	{
	// Get actual object ID
	$query  = "SELECT ptr_obj_id from ".$dmsdb->prefix('dms_objects')." ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$actual_obj_id = $dmsdb->query($query,"ptr_obj_id");

	// Get actual file information
	$query  = "SELECT obj_name from ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id='".$actual_obj_id."'";
	$obj_name = $dmsdb->query($query,'obj_name');
	}

switch($obj_info->obj_type)
	{
	case FILE:
		$confirm_string = _DMS_L_DOCUMENT;
		$confirm_name = _DMS_L_DOCUMENT_NAME;
		$return_url = $dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_options";
		$audit_string = "document/delete";
		break;
	case FILELINK:
		$confirm_string = "Document Link";
		$confirm_name = "Document Name";
		$return_url = "file_link_options.php";
		$audit_string = "document link/delete";
		break;
    case INBOXEMPTY:
    case INBOXFULL:
	case FOLDER:
		//$confirm_string = "Folder";
		//$confirm_name = "Folder name";
		//$return_url = "folder_options.php";

		$confirm_string = _DMS_L_FOLDER;
		$confirm_name = _DMS_L_FOLDER_NAME;
		$return_url = $dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_options";

		$audit_string = "folder/delete";
		break;
	case FOLDERLINK:
		$confirm_string = "Folder Link";
		$confirm_name = "Folder link name";
		$return_url = "folder_link_options.php";
		$audit_string = "folder_link/delete";
		break;
	case DOCLINK:
		$confirm_string = "Routed Document";
		$confirm_name = "Routed document name";
		$return_url = "link_options.php";
		$audit_string = "link/delete";
		break;
	case WEBPAGE:
		$confirm_string = "Web Page";
		$confirm_name = "URL Name";
		$return_url = "url_options.php";
		$audit_string = "url/delete";
		break;
	}

if (dms_get_var("hdn_delete_object_confirm") == "confirm")
	{
	switch($obj_info->obj_type)
		{
		case FILE:		    dms_delete_file($obj_id);		break;
		case FILELINK:		dms_delete_file_link($obj_id);		break;
		case INBOXEMPTY:
		case INBOXFULL:
		case FOLDER:		dms_delete_folder($obj_id);		break;
		case FOLDERLINK:	dms_delete_folder_link($obj_id);	break;
		case WEBPAGE:		dms_delete_url($obj_id);		break;
		case DOCLINK:		dms_delete_link($obj_id);		break;
		}

	dms_auditing($obj_id,$audit_string,$obj_name);

    dms_redirect($dms_config['dms_url']);
	}
else
	{
	print "<form method='post' action='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=obj_delete'>\r";
	print "<table width='100%'>\r";

	dms_display_header();

	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <tr><td colspan='2' align='left'><b>"._DMS_L_CONFIRM." ".$confirm_string." "._DMS_L_DELETION."</b></td></tr>\r";
	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <tr>\r";
	print "    <td colspan='2' align='left'>".$confirm_name.":&nbsp;&nbsp;&nbsp;";
	print "        ".$obj_name."</td>\r";
	print "  </tr>\r";
	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='"._DMS_L_DELETE."'>&nbsp;&nbsp;\r";
	print "                               <input type=button name='btn_cancel' value='"._DMS_L_CANCEL."' onclick='location=\"".$return_url."&obj_id=".$obj_id."\";'>\r";
	print "</table>\r";
	print "<input type='hidden' name='hdn_delete_object_confirm' value='confirm'>\r";
	print "<input type='hidden' name='hdn_obj_id' value='".$obj_id."'>\r";
	print "</form>\r";
	}
?>
