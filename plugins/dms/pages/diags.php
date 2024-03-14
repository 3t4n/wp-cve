<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// diags.php
// Diagnostic Page

global $dmsdb;


ini_set("upload_max_filesize",$dms_config['pi_upload_max_filesize']);
ini_set("post_max_size",$dms_config['pi_post_max_size']);
ini_set("max_execution_time",$dms_config['pi_max_et']);


global $dms_global;


global $dms_config, $dms_admin_flag, $dmsdb;

if ( $dms_admin_flag!=1 )
	{
	dms_redirect($dms_config['dms_url']);
	exit(0);
	}



print "<div ".$dms_config['class_content']." style='text-align: left' >\r";

print "<b>DMS Diagnostics</b><BR><BR>\r";

print "<b>General:</b><BR>\r";

print "&nbsp;&nbsp;DMS Plugin Version = ". DMS_VERSION . "<BR>\r";

print "&nbsp;&nbsp;DMS Plugin Directory = ". DMS_DIR . "<BR>\r";

print "&nbsp;&nbsp;DMS Plugin URL = ". DMS_URL . "<BR>\r";

print "&nbsp;&nbsp;DMS Pro Plugin = ";
if(defined("DMS_PRO")) {print "INSTALLED";} else {print "NOT INSTALLED";}

print "<BR>\r";
if(defined("DMS_PRO"))
    {
    print "&nbsp;&nbsp;DMS Pro Version = ";
    if(defined("DMS_PRO_VERSION")) {print DMS_PRO_VERSION;} else {print "1.01 or earlier";}

    print "<BR>\r";

    print "&nbsp;&nbsp;DMS Pro Plugin Directory = ". DMS_PRO_DIR . "<BR>\r";

    print "&nbsp;&nbsp;DMS Pro Plugin URL = ". DMS_PRO_URL . "<BR>\r";
    }

$query = 'SELECT data FROM '.$dmsdb->prefix("dms_config")." WHERE name='version'";
$db_version = $dmsdb->query($query,'data');

print "&nbsp;&nbsp;Database Version = ". $db_version . "<BR>\r";

print "&nbsp;&nbsp;Cached Database Version = ". $dms_config['version'] . "<BR>\r";

$query = "SELECT count(*) as num_docs FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_type='".FILE."'";
$num_docs = $dmsdb->query($query,'num_docs');

print "&nbsp;&nbsp;Number of Documents = ". $num_docs . "<BR>\r";


///////////////
//  File System
///////////////

print "<b>File System:</b><BR>\r";

print "&nbsp;&nbsp;".$dms_config['doc_path']."  =  ";
if(is_writeable($dms_config['doc_path'])) {print "OK";} else {print "FAILED";}

print "<BR>\r";

print "&nbsp;&nbsp;".DMS_DIR."config/config.php  =  ";
if(is_writeable(DMS_DIR . "config/config.php")) {print "OK";} else {print "FAILED";}

print "<BR>\r";

if(defined("DMS_PRO"))
    {
    print "&nbsp;&nbsp;".DMS_PRO_DIR."tn_cache  =  ";
    if(is_writeable(DMS_PRO_DIR . "tn_cache")) {print "OK";} else {print "FAILED";}
    print "<BR>\r";
    }

//////
//  WP
//////

print "<b>WordPress:</b><BR>\r";

print "&nbsp;&nbsp;WordPress Version = ". get_bloginfo('version') . "<BR>\r";

$theme = wp_get_theme();
$theme_name = $theme->get('Name');
$theme_version = $theme->get('Version');

print "&nbsp;&nbsp;Theme Name = " . $theme_name . "<BR>\r";
print "&nbsp;&nbsp;Theme Version = " . $theme_version . "<BR>\r";


print "&nbsp;&nbsp;Multisite = ";
$ms_output = "N/A";
if(defined('MULTISITE'))
    {
    if(MULTISITE == true) $ms_output = "CONFIGURED";
    }
print $ms_output . "<BR>\r";

///////
//  PHP
///////

print "<b>PHP:</b><BR>\r";

print "&nbsp;&nbsp;PHP Version = ". phpversion() . "<BR>\r";

//print "<b>PHP Configuration:</b><BR>\r";
print "&nbsp;&nbsp;max_execution_time = ". ini_get("max_execution_time") . "<BR>\r";
print "&nbsp;&nbsp;post_max_size = ". ini_get("post_max_size") . "<BR>\r";
print "&nbsp;&nbsp;upload_max_filesize = ". ini_get("upload_max_filesize") . "<BR>\r";

$error_reporting_settings_int = error_reporting();

$error_reporting_string = "";
$er_spaces = "&nbsp;&nbsp;&nbsp;";

$er_beginning_spaces = "";

for($space_loop = 0; $space_loop < 25; $space_loop++)
{
    $er_beginning_spaces .= "&nbsp;";
}

if($error_reporting_settings_int & E_ERROR) $error_reporting_string .= "E_ERROR" . $er_spaces;
if($error_reporting_settings_int & E_WARNING) $error_reporting_string .= "E_WARNING" . $er_spaces;
if($error_reporting_settings_int & E_PARSE) $error_reporting_string .= "E_PARSE" . $er_spaces;
if($error_reporting_settings_int & E_NOTICE) $error_reporting_string .= "E_NOTICE" . $er_spaces;
if($error_reporting_settings_int & E_CORE_ERROR) $error_reporting_string .= "E_CORE_ERROR" . $er_spaces;
if($error_reporting_settings_int & E_CORE_WARNING) $error_reporting_string .= "E_CORE_WARNING" . "<BR>" . $er_beginning_spaces;
if($error_reporting_settings_int & E_COMPILE_ERROR) $error_reporting_string .= "E_COMPILE_ERROR" . $er_spaces;
if($error_reporting_settings_int & E_COMPILE_WARNING) $error_reporting_string .= "E_COMPILE_WARNING" . $er_spaces;
if($error_reporting_settings_int & E_USER_ERROR) $error_reporting_string .= "E_USER_ERROR" . $er_spaces;
if($error_reporting_settings_int & E_USER_WARNING) $error_reporting_string .= "E_USER_WARNING" . $er_spaces;
if($error_reporting_settings_int & E_USER_NOTICE) $error_reporting_string .= "E_USER_NOTICE" . $er_spaces;
if($error_reporting_settings_int & E_STRICT) $error_reporting_string .= "E_STRICT" . "<BR>" . $er_beginning_spaces;
if($error_reporting_settings_int & E_RECOVERABLE_ERROR) $error_reporting_string .= "E_RECOVERABLE_ERROR" . $er_spaces;
if($error_reporting_settings_int & E_DEPRECATED) $error_reporting_string .= "E_DEPRECATED" . $er_spaces;
if($error_reporting_settings_int & E_USER_DEPRECATED) $error_reporting_string .= "E_USER_DEPRECATED" . $er_spaces;
if($error_reporting_settings_int & E_ALL) $error_reporting_string .= "E_ALL";

print "&nbsp;&nbsp;error_reporting = " . $error_reporting_string . "<BR>\r";

/*
print "&nbsp;&nbsp;&nbsp;&nbsp;<table><tr><td width = 125>error_reporting = </td><td>";
print $error_reporting_string;
print "</td></tr></table><BR>\r";
*/
///////////////////////
//  Database Connection
///////////////////////

print "<b>Database Connection:</b><BR>\r";
print "&nbsp;&nbsp;MySQL Extension = ". $dmsdb->mysql_extension() . "<BR>\r";
print "&nbsp;&nbsp;MySQL Server = ". DB_HOST . "<BR>\r";
print "&nbsp;&nbsp;MySQL User Name = ". DB_USER . "<BR>\r";
print "&nbsp;&nbsp;MySQL Database Name = ". DB_NAME . "<BR>\r";

////////////////////
//  Operating System
////////////////////

print "<b>Operating System:</b><BR>\r";
print "&nbsp;&nbsp;PHP_OS = ". PHP_OS . "<BR>\r";
print "&nbsp;&nbsp;php_uname() = ". php_uname() . "<BR>\r";


print "<BR><BR><BR>\r";

print "<input type='button' value='"._DMS_L_EXIT."' onclick='location=\"".$dms_config['dms_url']."\";'>\r";

print "</div>";
?>
