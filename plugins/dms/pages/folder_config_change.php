<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //



// folder_config_change.php

/*
print "oi: ".$_POST['hdn_obj_id']."<BR>";
print "ot: ".$_POST['slct_folder_type']."<br>";
print "fd: ".$_POST['txt_directory']."<br>";
exit(0);
*/

if (dms_get_var("hdn_obj_id") != FALSE)
	{
	global $dmsdb, $dms_global;

	$obj_id = dms_get_var("hdn_obj_id");

	// Delete the filesystem path
	$query  = "DELETE FROM ".$dmsdb->prefix('dms_object_misc')." WHERE ";
	$query .= "obj_id='".$obj_id."' AND ";
	$query .= "data_type='".PATH."'";
	$dmsdb->query($query);

	// Set the folder to the new type
	$query  = "UPDATE ".$dmsdb->prefix('dms_objects')." SET obj_type='".dms_get_var("slct_folder_type")."' ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);

	if(dms_get_var("slct_folder_type") == DISKDIR)
		{
		$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_misc')." (obj_id,data_type,data) VALUES ";
		$query .= "('".$obj_id."','".PATH."','".dms_get_var("txt_directory")."')";
		$dmsdb->query($query);
		}

	// Delete the auto lifecycle number
	$query  = "DELETE FROM ".$dmsdb->prefix('dms_object_misc')." WHERE ";
	$query .= "obj_id='".$obj_id."' AND ";
	$query .= "data_type='".FOLDER_AUTO_LIFECYCLE_NUM."'";
//print $query;exit(0);
	$dmsdb->query($query);

	if(dms_get_var("txt_folder_auto_lifecycle_num") > 0)
		{
		$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_misc')." (obj_id,data_type,data) VALUES ";
		$query .= "('".$obj_id."','".FOLDER_AUTO_LIFECYCLE_NUM."','".dms_get_var("txt_folder_auto_lifecycle_num")."')";
		$dmsdb->query($query);
		}

	// Set the flags, if applicable.

	// Delete the folder archive flag
	$query  = "DELETE FROM ".$dmsdb->prefix('dms_object_misc')." WHERE ";
	$query .= "obj_id='".$obj_id."' AND ";
	$query .= "data_type='".FLAGS."'";
	$dmsdb->query($query);

	// If applicable, set the folder archive flag
	$flags = 0;
	if(dms_get_var("chk_folder_archive_flag") == 'on') $flags += 1;
	if(dms_get_var("chk_doc_name_sync_flag") == 'on') $flags += 2;
	if(dms_get_var("chk_disp_file_comments_flag") == 'on') $flags += 4;

	if($flags > 0)
		{
		$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_misc')." (obj_id,data_type,data) VALUES ";
		$query .= "('".$obj_id."','".FLAGS."','".$flags."')";
		$dmsdb->query($query);
		}

	dms_redirect($dms_config['dms_url'].$dms_global['first_separator']."dms_page=folder_options&obj_id=".$obj_id);
	}


