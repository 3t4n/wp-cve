<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //


// file_import.php

require_once (DMS_DIR . "includes/general/i_dest_path_and_file.php");
require_once (DMS_DIR . "includes/general/i_file_properties.php");
require_once (DMS_DIR . "includes/general/i_file_copy.php");
require_once (DMS_DIR . "includes/general/i_file_upload.php");

global $dmsdb;


ini_set("upload_max_filesize",$dms_config['pi_upload_max_filesize']);
ini_set("post_max_size",$dms_config['pi_post_max_size']);
ini_set("max_execution_time",$dms_config['pi_max_et']);


if(dms_get_var('hdn_temp_file_name') != FALSE)
	{
	global $dms_global;

	$temp_file_name = dms_get_var('hdn_temp_file_name');

//print "TFN (final):".$temp_file_name."<BR>";

	// Check for an upload error.
	$error_code = $_FILES[$temp_file_name]['error'];
	if($error_code > 0)
		{
		$error_message = dms_get_file_upload_error_message($error_code);

		print "<table>\r";
		print "  <tr><td align='left'>Error:  The document has not been imported into the system.</td></tr>\r";
		print "  <tr><td align='left'>".$error_message."</td></tr>\r";
		print "  <tr><td><BR></td></tr>\r";
		print "  <tr><td align='left'><input type='button' name='btn_continue' value='Continue' onclick='location=\"".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_import\";'></td></tr>\r";

		exit(0);

		}

	$partial_path_and_file = dest_path_and_file();
	$time_stamp = time();

	$active_folder = dms_active_folder();

	// Get the location of the document repository
	$file_sys_root = $dms_config['doc_path'];

	$dest_path_and_file    = $file_sys_root."/".$partial_path_and_file;

	// Get the temporary path and file of the recent upload.
	$source_path_and_file  = $_FILES[$temp_file_name]["tmp_name"];  //$_FILES[dms_get_var("hdn_temp_file_name")]["tmp_name"];

	if(is_uploaded_file($source_path_and_file))
		{
		move_uploaded_file($source_path_and_file,$dest_path_and_file) or die("Error:  Unable to move document.  Ensure that the repository is available and that the web server is able to access the repository.<BR>SP:".$source_path_and_file."<BR>DP:".$dest_path_and_file);
		}
	else
		{
		die("Error:  Uploaded document is not unavailable.<BR>SP:".$source_path_and_file."<BR>DP:".$dest_path_and_file);
		}

	//dms_strprep($HTTP_POST_VARS['txt_file_name']);
	// Get the name of the document.  If a name has not been entered, use the filename of the document.
	$obj_name =  dms_get_var("txt_file_name");
	if(0 == strlen($obj_name))
		$obj_name = $_FILES[$temp_file_name]["name"];  //$_FILES[dms_get_var("hdn_temp_file_name")]["name"];

	$obj_name = dms_strprep($obj_name);
//print $obj_name;
//exit(0);

	//determine the file mime type.
	$file_type = $_FILES[$temp_file_name]["type"];
	//if($dms_config['OS']=="Linux") $file_type = trim(exec('file -bi '. escapeshellarg($dest_path_and_file)));

	// Create the new object in dms_objects
	$query  = "INSERT INTO ".$dmsdb->prefix('dms_objects')." (obj_type,obj_name,obj_owner,time_stamp_create,file_type)";
	$query .= " VALUES ('";
	$query .= "0','";
	$query .= $obj_name."','";
	$query .= $active_folder."','";
	$query .= $time_stamp."','";
	$query .= $file_type."')";

	//print $query;
	//print "<BR>";
	$dmsdb->query($query);

	// Get the obj_id of the new object
	$obj_id = $dmsdb->getid();


	dms_perms_set_init($obj_id,$active_folder);

	// Create an entry in dms_object_properties.
	$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_properties')." (obj_id)";
	$query .= " VALUES ('";
	$query .= $obj_id."')";

	$dmsdb->query($query);

	// Add all additional document properties
	update_file_properties($obj_id);

	// Create an entry in dms_object_versions and store the appropriate information.
	$file_name = dms_strprep($_FILES[$temp_file_name]["name"]);

    //  Clean up the file name for "modern" web browsers
    $file_name = rtrim($file_name);   //  Remove <space>, <tab>, \n, \r, \0, \x0b
    $file_name = rtrim($file_name,"`~!@#$%^&*()-=_+[]{}|;':\",.<>/?");

	$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_versions')." (obj_id,file_path,file_name,file_type,file_size,";
	$query .= "major_version,minor_version,sub_minor_version,time_stamp,file_location)";
	$query .= " VALUES ('";
	$query .= $obj_id."','";
	$query .= $partial_path_and_file."','";
	$query .= $file_name."','";
	//$query .= $_FILES[$HTTP_POST_VARS['hdn_temp_file_name']]['name']."','";
	$query .= $file_type."','";
	$query .= $_FILES[$temp_file_name]["size"]."','";
	$query .= dms_get_var("slct_version_major")."','";
	$query .= dms_get_var("slct_version_minor")."','";
	$query .= dms_get_var("slct_version_sub_minor")."','";
	$query .= $time_stamp."','";
	$query .= DMS."')";


//print $query;
	$dmsdb->query($query);

	// Find the row_id of the entry just created in dms_object_versions.
	$dms_object_versions_row_id = $dmsdb->getid();

	// Enter the row_id of the entry for the current version into dms_objects
	$query  = "UPDATE ".$dmsdb->prefix('dms_objects');
	$query .= " SET current_version_row_id='".$dms_object_versions_row_id."' ";
	$query .= " WHERE obj_id='".$obj_id."'";

	$dmsdb->query($query);

	dms_auditing($obj_id,"document/import");

	// Add the document number to the document.  If the ADN system is not enabled, the function will return without making changes.
//	dms_adn_system($obj_id);

	// Add the version number to the document.  If the ADV system is not enabled, the function will return without making changes.
//	dms_adv_system($obj_id);

	dms_folder_subscriptions($obj_id);

	// Synchronize the Document name and the File Name
	dms_document_name_sync($obj_id);

	// Check to see if an automatic lifecycle exists for this document.  If it exists, apply it.
	$query  = "SELECT data FROM ".$dmsdb->prefix("dms_object_misc")." ";
	$query .= "WHERE obj_id=".$active_folder." AND data_type='".FOLDER_AUTO_LIFECYCLE_NUM."'";
	$folder_auto_lifecycle_num = $dmsdb->query($query,'data');

	if($dmsdb->getnumrows() == 1) dms_apply_lifecycle($obj_id,$folder_auto_lifecycle_num);

	dms_message("The selected document has been imported into the document management system.");


	dms_doc_history($obj_id);

	dms_redirect($dms_config['dms_url']);
/*
	foreach ($HTTP_POST_VARS as $key=>$value)
        {
        print "\$HTTP_POST_VARS[\"$key\"]==$value<br>";
        }


	foreach ($GLOBALS as $key=>$value)
        {
		print "\$GLOBALS[\"$key\"]==$value<br>";
        }
*/
//exit(0);
	}
else
	{
    global $dms_admin_flag, $dms_global, $dms_tab_index;

	print "<script language='JavaScript'>\r";
	//print "<!--\r";

	print "function paste_doc_name()\r";
	print "  {\r";
	print "  var file_name,start_position,path;\r";
	print "  if(document.frm_file_import.txt_file_name.length > 0) return(0);\r";
	print "  path = document.frm_file_import.".$temp_file_name.".value;\r";
	print "  start_position = path.lastIndexOf(\"\\\\\");\r";
	print "  if(start_position == -1) start_position = path.lastIndexOf(\"/\");\r";
	print "  start_position++;\r";
	print "  file_name = path.substring(start_position,path.length);\r";
	print "  document.frm_file_import.txt_file_name.value = file_name;\r";
	print "  //document.frm_file_import.txt_file_name.length = file_name.length;\r";
	print "  }\r";

	print "function set_doc_length()\r";
	print "  {\r";
	print "  var file_name = document.frm_file_import.txt_file_name.value;\r";
	print "  document.frm_file_import.txt_file_name.length = file_name.length;\r";
	print "  }\r";

	//print "// -->\r";
	print "</script>\r";

	// Get active folder
	$active_folder = dms_active_folder();

	if(!$dms_admin_flag)
		{
		$active_folder_perms = dms_perms_level($active_folder);
		if( ($active_folder_perms != EDIT) && ($active_folder_perms != OWNER) )
			{
			print("<SCRIPT LANGUAGE='Javascript'>\r");
			print("location='".$dms_config['dms_url']."';");
			print("</SCRIPT>");
			}
		}

	print "<form name='frm_file_import' method='post' action='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_import' enctype='multipart/form-data'>\r";
	print "<table width='100%'>\r";

	dms_display_header(2,"","",FALSE);
	print "  <tr><td colspan='2' align='left'><BR></td></tr>\r";
	print "  <tr><td colspan='2' align='left'><b>" . _DMS_L_IMPORT_DOCUMENT . ":</b></td></tr>\r";

	print "  <tr><td colspan='2'><BR></td></tr>\r";

	print "  <tr>\r";
	print "    <td align='left' colspan='2'>"._DMS_L_SELECT_FILE.":</td>";
	print "  </tr>\r";
	print "  <tr>\r";
	//print "<input type='hidden' name='MAX_FILE_SIZE' value='".$upload_max_filesize."'>\r";
	print "    <td align='left' colspan='2'><input name='".$temp_file_name."' size='30' type='file' tabindex='".$dms_tab_index++."' onchange='paste_doc_name();'></td>\r";
	print "  </tr>\r";


	print "  <tr><td colspan='2' align='left'><BR></td></tr>\r";
	print "  <tr>\r";
	print "    <td align='left'>"._DMS_L_FILE_NAME.":</td>\r";
	print "    <td align='left'><input type='text' name='txt_file_name' size='40' maxlength='250' class='".$dms_config['class_content']."' tabindex='".$dms_tab_index++."' onchange='set_doc_length();'></td>\r";
	print "  </tr>\r";

	print "  <tr><td><BR></td></tr>\r";

	initial_file_properties();

	//print "  <tr><td><BR></td></tr>\r";

	print "  <tr>\r";
	print "    <td align='left'>"._DMS_L_INITIAL_VERSION.":</td>\r";

	print "    <td align='left'>\r";
	dms_select_version_number("slct_version",1,0,0);
	print "    </td>\r";

	print "  </tr>\r";

	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='"._DMS_L_SUBMIT."' tabindex='".$dms_tab_index++."'>&nbsp;&nbsp;";
	print "    <input type=button name='btn_cancel' value='"._DMS_L_CANCEL."' onclick='location=\"".$dms_config['dms_url']."\";' tabindex=".$dms_tab_index++."></td>\r";
	print "</table>\r";
	print "<input type='hidden' name='hdn_temp_file_name' value='".$temp_file_name."'>\r";
	//print "<input type='hidden' name='hdn_active_folder' value='".$active_folder."'>\r";
	print "</form>\r";

	print("<SCRIPT LANGUAGE='Javascript'>\r");
	print("  document.frm_file_import.txt_file_name.focus();");
	print("</SCRIPT>");
  }
?>
