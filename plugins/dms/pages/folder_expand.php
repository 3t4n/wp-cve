<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// folder_expand.php

global $dmsdb, $dms_user_id;

$location = dms_get_var("ret_location");
if($location == FALSE) $location = $dms_config['dms_url'];

$test = dms_get_var("obj_id");
if ($test != FALSE) $location .= "&obj_id=".$test;
else $location = $dms_config['dms_url'];

// Reset the location if the obj_id is flagged as being bad.
if(dms_get_var("obj_id") == "-1") $location = dms_get_var("ret_location");

$change_active_folder = "TRUE";
if(dms_get_var("active") == "FALSE") $change_active_folder = "FALSE";

$folder_id = dms_get_var("folder_id");
if($folder_id != FALSE)
	{
	if($dms_user_id == 0)
        {
        //  User is not logged in.
        $dms_var_cache['public_user_folder'] = (int) $folder_id;
        }
    else
        {
        //  User is logged in.


    /*
        //Make sure that this folder is not marked as expanded in order to prevent multiple entries.
        $query  = "DELETE FROM ".$dmsdb->prefix("dms_exp_folders");
        $query .= " WHERE user_id='".$dms_user_id."' and folder_id='".$folder_id."'";
        $dmsdb->query($query);
    */
        dms_set_inbox_status($folder_id);

        // Make sure that this folder, or any other folder, is not marked as active.
        if ($change_active_folder == "TRUE")
            {
            $query = "DELETE FROM ".$dmsdb->prefix("dms_active_folder")." WHERE user_id='".$dms_user_id."'";
            $dmsdb->query($query);
            }
    /*
        // Set the folder as expanded
        $query  = "INSERT INTO ".$dmsdb->prefix("dms_exp_folders")." (user_id,folder_id) VALUES ('".$dms_user_id."','".$folder_id."')";
        $dmsdb->query($query);
    */
        // Set the folder as active
        if ($change_active_folder == "TRUE")
            {
            $query = "INSERT INTO ".$dmsdb->prefix("dms_active_folder")." (user_id,folder_id) VALUES ('".$dms_user_id."','".$folder_id."')";
            $dmsdb->query($query);
            }
        }
	}
else
	{
	print "Error:  Please contact your system administrator.";
	exit(0);
	}

$dms_var_cache['doc_alpha_sort'] = "ALL";
dms_var_cache_save();

dms_redirect($location);
?>
