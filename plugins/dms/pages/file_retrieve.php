<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// file_retrieve.php

session_start();

require_once( "../config/config.php" );

$init_data_loaded = false;

$fake_data_loaded = false;

$dms_wp_user_id = 0;

if(isset($_SESSION))
    {
    $dms_wp_user_id = $_SESSION['dms_wp_var']['dms_wp_user_id'];

    if(isset($_SESSION['dms_global']['normal_init']))
        {
        if($_SESSION['dms_global']['normal_init'] == true) $init_data_loaded = true;
        }
    }

if($init_data_loaded == false)
    {
    $fake_data_loaded = true;
    //  Load session variables in the event that file_retrieve.php is called directly from a page in WP.

    $dms_global['dms_user_id'] = 0;
    $dms_global['dms_admin_flag'] = 0;

    $dms_global['dms_pro_dir'] = "FALSE";
    $dms_global['dms_pro_url'] = "FALSE";

    $dms_global['normal_init'] = false;

    $_SESSION['dms_global'] = $dms_global;
    }

//  Load saved $dms_global array
$dms_global = $_SESSION['dms_global'];
$dms_global["first_separator"] = "?";

if($dms_wp_user_id != 0)
    {
    $dms_global['dms_user_id'] = $dms_wp_user_id;
    }

$dms_admin_flag = $dms_global['dms_admin_flag'];
$dms_wp_admin_flag = $dms_global['dms_admin_flag'];

if( $dms_global['dms_pro_dir'] != "FALSE" )
    {
    //print "dms_global dms_pro_dir != false<br>";


    define("DMS_PRO", "INSTALLED");
    define("DMS_PRO_DIR", $dms_global['dms_pro_dir'] );
    define("DMS_PRO_URL", $dms_global['dms_pro_url'] );
    }

//  Load the main dms function library.
$dms_on_admin_page = false;


//print "DMS_DIR = " . DMS_DIR . "<BR>";

//  Ugly hack to use the i_dms_functions.php file from DMS Pro if DMS Pro is installed and the session data is fake.
if (!defined("DMS_PRO_DIR") )
    {
    if(file_exists("../../dms_pro"))
        {
        //define("DMS_PRO_DIR",DMS_DIR . "//dms_pro");
        $dms_dir = DMS_DIR;
        $dms_pro_dir = str_replace("/dms","/dms_pro",$dms_dir);
        define("DMS_PRO_DIR",$dms_pro_dir);
        }
    }


$includes_dir = DMS_DIR . "includes/general/";
if( defined("DMS_PRO_DIR") ) $includes_dir = DMS_PRO_DIR . "includes/";

//print $includes_dir . "i_dms_functions.php<BR>";
//    exit(0);

require_once( DMS_DIR . "/includes/general/i_defines.php" );
require_once( DMS_DIR . "/includes/general/i_pal.php" );
require_once( $includes_dir . "i_dms_functions.php" );

//  Connect to the database.
$dmsdb->connect();

dms_initialize();
//dms_get_user_data();

function file_ext_exists($filename)
	{
	$position = strrpos($filename,'.');
	if($position===FALSE)
		{
		return FALSE;
		}
	else
		{
		$ext = substr($filename,$position+1);

		if (strlen($ext) == 3) return TRUE;
		else return FALSE;
		}

	return FALSE;
	}


// Permissions required to access this page:
//  READONLY, EDIT, OWNER

$function = "";
$obj_id = "";
$ver_id = "";

$cd = "";

$function = dms_get_var("function");
$obj_id = dms_get_var("obj_id");
$ver_id = dms_get_var("ver_id");

$perms_level = dms_perms_level($obj_id);

if ( ($perms_level != READONLY) && ($perms_level != EDIT) && ($perms_level != OWNER) )
	{
	dms_auditing($obj_id,"document/open--FAILED");
    print "Unable to retrieve file due to insufficient permissions.<BR>\r";

//    print "User ID = " . $dms_global['dms_user_id'];

    exit(0);
	}

if($function == "") $function="VIEW";              // If a function is not specified, default to "VIEW."
$function = strtoupper($function);

// If the object type is a WEB_PAGE, redirect to the web page.
$query = "SELECT obj_type FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_id."'";
$obj_type = $dmsdb->query($query,"obj_type");

/*
if($obj_type==WEBPAGE)
	{
	$query  = "SELECT data FROM ".$dmsdb->prefix("dms_object_misc")." ";
	$query .= "WHERE obj_id='".$obj_id."' AND data_type='".URL."'";
	$url = $dmsdb->query($query,"data");

	// Add to the document history.
	//dms_doc_history($obj_id);

	// Audit
	dms_auditing($obj_id,"url/open");

	$url = "Location:".$url;
	header($url);
	exit(0);
	}
*/

// Get the file properties so the browser can properly handle the file.
switch($function)
	{
	case "EXPORT":
		dms_auditing($obj_id,"document/export");

		dms_get_rep_file_props($obj_id);
		$cd = "attachment";
		break;
	case "OPEN":
		dms_auditing($obj_id,"document/open");

		dms_get_rep_file_props($obj_id);
		//$cd = "inline";
		$cd = "attachment";
		break;
	case "VIEW":
		dms_auditing($obj_id,"document/view");

		dms_get_rep_file_props($obj_id);
		$cd = "inline";
		//$cd = "attachment";
//print "A:  ".$dms_rep_file_props['file_path']."<BR>\r";
		dms_view_counter_increment($obj_id);
		break;
	case "VV":
		dms_auditing($obj_id,"document/view version/version_row_id=".$ver_id);

		$query  = "SELECT obj_id,file_name,file_type,file_size,file_path,file_location,alt_file_location_path from ".$dmsdb->prefix('dms_object_versions')." ";
		$query .= "WHERE row_id='".$ver_id."'";
		$result = $dmsdb->query($query,'ROW');

		$obj_id = $result->obj_id;
		$dms_rep_file_props['file_name'] = $result->file_name;
		$dms_rep_file_props['file_size'] = $result->file_size;
		$dms_rep_file_props['file_type'] = $result->file_type;

		switch($result->file_location)
            {
            case DIR:
                {
                $file_sys_dir = $result->alt_file_location_path;
                //$file_sys_dir = str_replace("|","'",$file_sys_dir);   //  Replace | with ' as ' is permitted in filenames.

                $query = "SELECT obj_owner FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_id."'";
                $obj_owner = $dmsdb->query($query,"obj_owner");

                $dms_rep_file_props['file_path'] = $file_sys_dir."/".$result->file_path;
                break;
                }

            default:
                {
                $dms_rep_file_props['file_path'] = $dms_config['doc_path']."/".$result->file_path;
                }
            }
        $cd = "inline";

		dms_view_counter_increment($obj_id);
		break;
	}

// Add to the document history
dms_doc_history($obj_id);



// The following is for compatibility with documents migrated by the DMS migration program and for documents without extensions in the filename.
if(file_ext_exists($dms_rep_file_props['file_name']) == FALSE)
  $dms_rep_file_props['file_name'] = dms_filename_plus_ext($dms_rep_file_props['file_name'],$dms_rep_file_props['file_type']);



// If a document $dms_rep_file_props['file_name'] does not have an extension, one will have to be added based upon
// the $dms_rep_file_props['file_type']
//if(!strrchr($dms_rep_file_props['file_name'],"."))
//  $dms_rep_file_props['file_name'] = dms_filename_plus_ext($dms_rep_file_props['file_name'],$dms_rep_file_props['file_type']);


//  If missing, add a file_type (mime type) to make Chrome happy.....
if($dms_rep_file_props['file_type'] == "unchecked")
    {
	$position = strrpos($dms_rep_file_props['file_name'],'.');
	if($position===FALSE)
		{
		}
	else
		{
		$ext = substr($dms_rep_file_props['file_name'],$position+1);
//print "ext= |" . $ext . "|<BR>";

//print "strlen:  " . strlen($ext) . "<BR>";

		if (strlen($ext) == 1 || strlen($ext) == 2 || strlen($ext) == 3 || strlen($ext) == 4)
            {
            switch ($ext)
                {
                case "avi":     $dms_rep_file_props['file_type'] = "video/x-msvideo";           break;
                case "docx":    $dms_rep_file_props['file_type'] = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";   break;
                case "dwg":     $dms_rep_file_props['file_type'] = "image/vnd.dwg";             break;
                case "dxf":     $dms_rep_file_props['file_type'] = "image/vnd.dxf";             break;
                case "dwf":     $dms_rep_file_props['file_type'] = "model/vnd.dwf";             break;
                case "bmp":     $dms_rep_file_props['file_type'] = "image/bmp";                 break;
                case "c":       $dms_rep_file_props['file_type'] = "text/x-c";                  break;
                case "csv":     $dms_rep_file_props['file_type'] = "text/csv";                  break;
                case "gif":     $dms_rep_file_props['file_type'] = "image/gif";                 break;
                case "jpg":		$dms_rep_file_props['file_type'] = "jpeg";	                	break;
                case "ppt":     $dms_rep_file_props['file_type'] = "application/vnd.ms-powerpoint";     break;
                case "vsd":     $dms_rep_file_props['file_type'] = "application/vnd.visio";             break;
                case "vsdx":    $dms_rep_file_props['file_type'] = "application/vnd.visio2013";         break;


                case "odp":     $dms_rep_file_props['file_type'] = "application/vnd.oasis.opendocument.presentation";       break;
                case "ots":     $dms_rep_file_props['file_type'] = "application/vnd.oasis.opendocument.spreadsheet";        break;
                case "odt":     $dms_rep_file_props['file_type'] = "application/vnd.oasis.opendocument.text";               break;

                case "psd":     $dms_rep_file_props['file_type'] = "image/vnd.adobe.photoshop";     break;

                case "png":     $dms_rep_file_props['file_type'] = "image/png";      break;

                case "doc":     $dms_rep_file_props['file_type'] = "application/msword";    	break;
                case "xls":     $dms_rep_file_props['file_type'] = "application/xls";       	break;
                case "gif":     $dms_rep_file_props['file_type'] = "image/gif";         		break;

                case "txt":     $dms_rep_file_props['file_type'] = "text/plain";		        break;
                case "pdf":     $dms_rep_file_props['file_type'] = "application/pdf";           break;

                case "zip":     $dms_rep_file_props['file_type'] = "application/zip";           break;

                default:    $dms_rep_file_props['file_type'] = "";  break;
                }
            }
		}
    }


// Trim characters off of the end of $dms_rep_file_props['file_name'] to make Chrome and other, newer, browsers happy.
$dms_rep_file_props['file_name'] = rtrim($dms_rep_file_props['file_name']);   //  Remove <space>, <tab>, \n, \r, \0, \x0b
$dms_rep_file_props['file_name'] = rtrim($dms_rep_file_props['file_name'],"`~!@#$%^&*()-=_+[]{}|;':\",.<>/?");


$dms_rep_file_props['file_name'] = str_replace("|","'",$dms_rep_file_props['file_name']);   //  Replace | with ' as ' is permitted in filenames.
$dms_rep_file_props['file_path'] = str_replace("|","'",$dms_rep_file_props['file_path']);   //  Replace | with ' as ' is permitted in filenames.


$debug_file_retrieve = false;

if($debug_file_retrieve == true)
    {
    // Debugging

    print "User ID = " . $dms_global['dms_user_id'] . "<BR>\r";
    print "DMS Admin Flag = " . $dms_admin_flag . "<BR>\r";

    if($fake_data_loaded == true) print "FAKE SESSION DATA USED";
    if($fake_data_loaded == false) print "REAL SESSION DATA USED";
    print "<BR>\r";

    print "N:  ".$dms_rep_file_props['file_name']."<BR>\r";
    print "S:  ".$dms_rep_file_props['file_size']."<BR>\r";
    print "T:  ".$dms_rep_file_props['file_type']."<BR>\r";
    print "P:  ".$dms_rep_file_props['file_path']."<BR>\r";

    //var_dump($_SESSION);
    }
else
    {
    // send headers to browser to initiate file download

    header('Content-Length: '.$dms_rep_file_props['file_size']);
    header('Cache-control: private');
    header('Content-Type: ' . $dms_rep_file_props['file_type']);
    header('Content-Disposition: '.$cd.'; filename="'.$dms_rep_file_props['file_name'].'"');
    header('Pragma: public');   // Apache/IE/SSL download fix.
    header('Content-Transfer-Encoding:  binary');

    // Read the file
    readfile($dms_rep_file_props['file_path']);
    }

?>
