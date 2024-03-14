<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// set_wp_page.php

global $dmsdb, $dms_global, $dms_users;

//$return_url = $dms_config['dms_url'];
$return_url = $dms_config['dms_url'].$dms_global["first_separator"]."dms_page=config";

//  Configure for Permalinks
//chmod($file,0755);

require_once( DMS_DIR . "/includes/general/i_set_config.php");


dms_redirect($return_url);
?>
