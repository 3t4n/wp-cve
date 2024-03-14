<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// obj_restore.php

global $dms_global, $dms_admin_flag;

// Permissions required to access this page:
//  Administrator

if ($dms_admin_flag != 1)
	{
	dms_redirect($dms_config['dms_url']);
	exit(0);
	}

$obj_id = dms_get_var("obj_id");

if ($obj_id != FALSE)
	{
	$query  = "UPDATE ".$dmsdb->prefix("dms_objects")." ";
	$query .= "SET obj_status='0', time_stamp_delete='0' ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);

	$query = "SELECT obj_type FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_id."'";
	$obj_type = $dmsdb->query($query,"obj_type");

	switch($obj_type)
		{
		case FILE:
			$message = "document/restore";
			break;
		case FOLDER:
			$message = "folder/restore";
			break;
		case WEBPAGE:
			$message = "URL/restore";
			break;
		}

	dms_auditing($obj_id,$message);
	}

dms_redirect($dms_config['dms_url']);



?>
