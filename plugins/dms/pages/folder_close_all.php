<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// folder_close_all.php

global $dmsdb;

$location= $dms_config['dms_url'];

/*
// Remove all folders marked as expanded for this user.
$query  = "DELETE FROM ".$dmsdb->prefix("dms_exp_folders");
$query .= " WHERE user_id='".$dms_user_id."'";
$dmsdb->query($query);
*/

if($dms_user_id == 0)
    {
    //  Public User

    $dms_var_cache['public_user_folder'] = 0;
    }
else
    {
    //  Logged in user

    // Make sure that this folder cannot be marked as active
    $query = "DELETE FROM ".$dmsdb->prefix("dms_active_folder")." WHERE user_id='".$dms_user_id."'";
    $dmsdb->query($query);
    }



$dms_var_cache['doc_alpha_sort'] = "ALL";
dms_var_cache_save();

dms_redirect($location);
?>
