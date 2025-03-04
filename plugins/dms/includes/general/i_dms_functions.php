<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //


// DMS Functions
// i_dms_functions.php


//  NOTE:  The following file counter with the limit as DMS_MAX_FILES can be changed.  However,
//  if enough documents are uploaded system performance will suffer.  This has not been tested
//  with more than 1,000 documents in a single directory.
define('DMS_MAX_FILES',500);
$dms_file_counter = 0;
$dms_file_counter_flag = TRUE;  //  Set to FALSE to disable file counter.


$dms_config = array();
$dms_var_cache = array();

$dms_admin_flag = "";
$dms_anon_flag = "";
$dms_user_id = "";
$dms_disp_flag = "TRUE";


$active_folder = 0;
$active_folder_type = 0;
$active_folder_perms = 0;

$class_content = "";
$class_header = "";
$class_subheader = "";
$class_narrow_header = "";
$class_narrow_content = "";
$dms_tab_index = 1;



function dms_initialize()
    {
    global $dms_config, $dms_groups;

    dms_get_config();
    dms_var_cache_load();
    dms_document_deletion();

    $dms_groups->group_source = $dms_config['group_source'];

    dms_get_user_data();
    }


//dms_admin_menu();
//dms_search_menu();
//dms_dhtml_menu_functions();

$file_type_update_counter = 0;




function dms_active_folder()
{
	global $dmsdb,$dms_user_id;
    global $dms_var_cache;


    if($dms_user_id == 0)
        {
        //  User is a public user.
        $active_folder = 0;
        if(isset($dms_var_cache['public_user_folder'])) $active_folder = $dms_var_cache['public_user_folder'];

        //$active_folder = dms_var_cache_get('public_user_folder');
        }
    else
        {
        //  User is logged in.

        // Get active folder
        $query = "SELECT folder_id FROM ".$dmsdb->prefix("dms_active_folder")." WHERE user_id='".$dms_user_id."'";
        $active_folder = $dmsdb->query($query,'folder_id');

        if($dmsdb->getnumrows() < 1) return 0;
        }

	// If the user doesn't have access, return them to the top level.
	$perms_level = dms_perms_level($active_folder);
	if($perms_level < BROWSE) return 0;

	return $active_folder;
}


function dms_alpha_move($obj_id)
{
	global $dms_config, $dmsdb;

	if($dms_config['lifecycle_alpha_move'] == 1)
		{
		//  Get the first character of the name of the document
		$query  = "SELECT obj_name,obj_owner FROM ".$dmsdb->prefix('dms_objects')." ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$result = $dmsdb->query($query,"ROW");

		$first_char = $result->obj_name;
		$first_char = substr($first_char,0,1);
		$first_char_upper = strtoupper($first_char);
		$first_char_lower = strtolower($first_char);

		$obj_location = $result->obj_owner;
		//  Look for the folder name with the same first character.
		$query  = "SELECT obj_id FROM ".$dmsdb->prefix('dms_objects')." ";
		$query .= "WHERE (obj_name = '".$first_char_upper."' OR obj_name = '".$first_char_lower."') AND obj_status = '0'";
		$query .= "AND obj_owner = '".$obj_location."' ";
		$query .= "AND obj_type = '1'";
		$char_dest_folder = $dmsdb->query($query,"obj_id");

		//  If the folder is found, move the document into this folder.
		if($dmsdb->getnumrows() > 0)
			{
			$query  = "UPDATE ".$dmsdb->prefix('dms_objects')." ";
			$query .= "SET ";
			$query .= "obj_owner = '".$char_dest_folder."' ";
			$query .= "WHERE obj_id='".$obj_id."'";
			$dmsdb->query($query);
			}
		}
}


function dms_auditing($obj_id, $descript, $obj_name = "")
{
	global $dmsdb, $dms_user_id;

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_audit_log")." ";
	$query .= "(time_stamp,user_id,obj_id,descript,obj_name) VALUES ('";
	$query .= time()."','";
	$query .= $dms_user_id."','";
	$query .= $obj_id."','";
	$query .= $descript."','";
	$query .= $obj_name."')";

	$dmsdb->query($query);
}


function dms_display_header($number_of_columns=3,$pre_options="",$post_options="",$dmio = TRUE)
	{
	global $dms_config;

	if(strlen($dms_config['dms_title']) > 0)
		{
		print "  <tr><td colspan='".$number_of_columns."'><table cellpadding='0' cellspacing='0' border='0' width='100%'>\r";
		print "    <tr>\r";
		print "      <td ".$dms_config['class_header']." width='25'>&nbsp;&nbsp;".$pre_options."</td>\r";
		print "      <td ".$dms_config['class_header']."><center><b><font size='2'><div title='Version ".DMS_VERSION."'>".$dms_config['dms_title']."</div></font></b></center></td>\r";
		print "      <td ".$dms_config['class_header']." width='25'>".$post_options."&nbsp;&nbsp;</td>\r";
		print "    </tr>\r";
		print "  </table></td></tr>\r";
		}

	//if($dmio == TRUE) dms_display_main_interface_options($number_of_columns);
	}


function dms_get_document_icon($obj_id,$file_type = 0,$status = 0)
	{
	global $dmsdb,$file_type_update_counter;

	$update_flag = FALSE;

	if( ($file_type == "unchecked") && ($file_type_update_counter < 50) )              //  Update 50 documents, at a time.
		{
		$query  = "SELECT current_version_row_id FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_id."'";
		$cv_row_id = $dmsdb->query($query,"current_version_row_id");

		$query  = "SELECT file_type FROM ".$dmsdb->prefix("dms_object_versions")." WHERE row_id='".$cv_row_id."'";
		$file_type = $dmsdb->query($query,"file_type");

		$update_flag = TRUE;
		$file_type_update_counter++;
		}

	switch($file_type)
		{
        case "application/vnd.ms-powerpoint":
        case "application/vnd.openxmlformats-officedocument.presentationml.presentation":
            $image = "/tango_icons/16x16/x-office-presentation.png";
            break;

		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.word":
			$image = "/tango_icons/16x16/x-office-document.png";
			if($status == CHECKEDOUT) $image = "/custom/x-office-document_locked.png";
			break;

		case "application/pdf":
			$image = "/custom/pdf.png";
			break;

		case "application/rtf":
			$image = "/tango_icons/16x16/x-office-document.png";
			if($status == CHECKEDOUT) $image = "/custom/x-office-document_locked.png";
			break;

		case "application/vnd.ms-excel":
		case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
			$image = "/tango_icons/16x16/x-office-spreadsheet.png";
			if($status == CHECKEDOUT) $image = "/custom/x-office-spreadsheet_locked.png";
			break;

		case "application/x-zip-compresssed":
			$image = "/tango_icons/16x16/package-x-generic.png";
			break;

		case "image/bmp":
		case "image/gif":
		case "image/jpeg":
		case "image/png":
			$image = "/tango_icons/16x16/image-x-generic.png";
			break;

		case "text/plain":
			$image = "/tango_icons/16x16/text-x-generic.png";
			if($status == CHECKEDOUT) $image = "/custom/text-x-generic_locked.png";
			break;

		default:
			$image = "/tango_icons/16x16/image-missing.png";
			$file_type = "unknown";
		}

	$image = DMS_ICONS.$image;

	if($update_flag == TRUE)
		{
		$query = "UPDATE ".$dmsdb->prefix("dms_objects")." SET file_type='".$file_type."' WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);
		}

	return $image;
	}


function dms_display_main_interface_options($number_of_columns = 3)
	{
	global $active_folder_type, $active_folder, $active_folder_perms, $dms_admin_flag, $dms_config, $dms_global;

	//if($dms_config['disp_main_int_options'] == 0) return;

	//print "  <tr><td colspan='3'><BR></td></tr>\r";

	print "  <tr><td colspan='".$number_of_columns."'><table width='100%'>\r";

	print "  <tr>\r";
	//print "    <td width='60%'><img src='images/help.gif' title='Help'><BR></td>\r";
	print "    <td width='40%' style='text-align: left'>";
	dms_help_system("index",3);
	print "    </td>\r";

	if( ( ($active_folder_type == FOLDER)
	&& ( ( ($active_folder!=0) && ( ($active_folder_perms == EDIT) || ($active_folder_perms == OWNER) ) ) )
	&& ($active_folder_type != DISKDIR) )
	|| ( ($active_folder == 0) && ($dms_admin_flag == 1) )
	)
		{
		print "  <td width='35%' align='right' valign='top' style='text-align: right'>";

        if(dms_file_limit() == FALSE)
            {
            if ($dms_config['template_root_obj_id'] != 0)
                print "    <a href='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_new'><img src='".DMS_ICONS."/tango_icons/32x32/document-new.png' title='Create Document'></a>&nbsp;&nbsp;";

            print "    <a href='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_import'><img src='".DMS_ICONS."/tango_icons/32x32/document-save.png' title='Import Document'></a>&nbsp;&nbsp;";
            }
/*
//  DISABLE BATCH IMPORT
		if ($dms_config['OS'] == "Linux")
			{
			print "    <a href='file_batch_import.php' title='Import Multiple Documents'><img src='wp-content/plugins/dms/images/menu/batchimport.gif'></a>&nbsp;&nbsp;";
			}
*/
//  DISABLE WEB PAGE LINKS
//		print "    <a href='url_add.php'><img src='wp-content/plugins/dms/images/menu/www.gif' title='Add Web Page'></a>&nbsp;&nbsp;";
		print "    <a href='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=folder_new'><img src='".DMS_ICONS."/tango_icons/32x32/folder-new.png' title='Create Folder'></a>";

		if( ($dms_admin_flag == 1) && ($dms_admin_flag == 0) )  // Disabled.     *******************************************
			{
			print "    &nbsp;&nbsp;<a href='link_create.php'><img src='link_create.gif' title='Create Link'></a>";
			}

		print "  </td>\r";
		}
	else
		{
		print "    <td width='25%' align='left'><BR></td>";
		}

    print "    <td width='25%' style='text-align: right'>";

	print "    <a href='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=search_prop'><img src='".DMS_ICONS."/tango_icons/32x32/system-search.png' title ='Search'></a>&nbsp;&nbsp;";

	if ( ($dms_config['full_text_search'] == '1') && ($dms_global['dms_fts_dir'] != "FALSE") )
		{
		require_once ( $dms_global['dms_fts_dir'] . "/menu/search.php");
		}

	print "      </td></tr>\r";

	print "    </table></td></tr>\r";



    if ($dms_admin_flag == 1)
        {
        print "    <tr><td colspan='".$number_of_columns."'><table width='100%'>\r";
        print "      <tr>\r";

        print "        <td>\r";
        $button  = "<input type='button' name='btn_group_editor' value='"._DMS_L_GROUP_EDITOR."' onclick='location=\"".$dms_config['dms_url'];
        $button .= $dms_global["first_separator"]."dms_page=group_editor\";'>";
        print $button;

        print "        </td>\r";

        print "      </tr>\r";
        print "    </table></td></tr>\r";
        }

	}


function dms_display_spaces($number_of_spaces=1)
{
	$index=0;

	while($index < $number_of_spaces)
		{
		print "&nbsp;";
		$index++;
		}
}


function dms_determine_admin_perms($current_perm)
{
	global $dms_admin_flag;

	if ($dms_admin_flag == 1)
		{
		$current_perm = OWNER;
		}

	return($current_perm);
}


function dms_doc_history($obj_id)
{
	global $dms_config, $dms_user_id, $dmsdb;

	// If the object is already in the history table, delete it.
	$query  = "DELETE FROM ".$dmsdb->prefix("dms_user_doc_history")." WHERE ";
	$query .= "user_id='".$dms_user_id."' AND obj_id='".$obj_id."'";
	$dmsdb->query($query);

	// If there are more than $dms_config['doc_hist_block_rows'] - 1 objects in the history table, delete any past the ninth.
	$query  = "SELECT * FROM ".$dmsdb->prefix("dms_user_doc_history")." WHERE user_id='".$dms_user_id."' ORDER BY time_stamp DESC";
	$result = $dmsdb->query($query);
	$num_rows = $dmsdb->getnumrows();

	if($num_rows > ($dms_config['doc_hist_block_rows'] - 1) )
		{
		$counter = 0;
		while($result_data = $dmsdb->getarray($result))
			{
			if($counter >= ($dms_config['doc_hist_block_rows'] - 1) )
				{
				$query  = "DELETE FROM ".$dmsdb->prefix("dms_user_doc_history")." WHERE ";
				$query .= "user_id='".$dms_user_id."' AND obj_id='".$result_data['obj_id']."'";
				$dmsdb->query($query);
				}
			$counter++;
			}
		}

	// Get the name of the object.
	$query  = "SELECT obj_name FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_id."'";
	$obj_name = $dmsdb->query($query,"obj_name");


	// If the object name is longer than 25 characters, truncate it.
	if(strlen($obj_name) > 25)
		{
		$obj_name = substr($obj_name,0,25);
		$obj_name = $obj_name."...";
		}

	// Insert the object information in the history table.
	$query  = "INSERT INTO ".$dmsdb->prefix("dms_user_doc_history")." (user_id,obj_id,time_stamp,obj_name) VALUES ('";
	$query .= $dms_user_id."','";
	$query .= $obj_id."','";
	$query .= time()."','";
	$query .= $obj_name."')";
	$dmsdb->query($query);
}

function dms_doc_history_delete($obj_id)
{
	global $dmsdb;

	$query  = "DELETE FROM ".$dmsdb->prefix("dms_user_doc_history")." WHERE ";
	$query .= "obj_id='".$obj_id."'";
	$dmsdb->query($query);
}


function dms_filename_plus_ext($filename, $filetype)
{
	switch ($filetype)
		{
		case "application/msword":		    $ext = "doc";		break;
		case "msw8":				        $ext = "doc";		break;
		case "application/vnd.ms-excel":	$ext = "xls";		break;
		case "application/xls":			    $ext = "xls";		break;
		case "image/gif":			        $ext = "gif";		break;
		case "jpeg":				        $ext = "jpg";		break;
		case "text/plain":			        $ext = "txt";		break;
		default:				            $ext = "";		break;
		}

	$filename = $filename.".".$ext;

	return $filename;
}


function dms_folder_query($admin_display,$folder_owner,$group_query)
{
	global $dmsdb,$dms_admin_flag,$dms_user_id;

	// If the user is an administrator, ignore the permissions entirely.
	if ( ($dms_admin_flag == 1) && ($admin_display=='1') )
		{
		$query  = "SELECT * FROM ".$dmsdb->prefix("dms_objects")." ";
		$query .= "WHERE ( (obj_owner='".$folder_owner."') ";
		$query .= " AND (obj_status < 3) )";
//		$query .= " AND (obj_type='1' OR obj_type='2' OR obj_type='3') ) ";
		$query .= "ORDER BY obj_type desc,obj_name ";
		//$query .= "LIMIT ".$dms_config['doc_display_limit'];
		}
	else
		{
		$query  = "SELECT obj_id, ".$dmsdb->prefix("dms_objects").".ptr_obj_id, obj_type, obj_name, ";
		$query .= "obj_status, obj_owner, obj_checked_out_user_id, lifecycle_id, ";
		$query .= "user_id, group_id, user_perms, group_perms, everyone_perms ";
		$query .= "FROM ".$dmsdb->prefix("dms_object_perms")." ";
		$query .= "INNER JOIN ".$dmsdb->prefix("dms_objects")." ON ";
		$query .= $dmsdb->prefix("dms_object_perms").".ptr_obj_id = obj_id ";
		$query .= "WHERE (obj_owner='".$folder_owner."') ";
		$query .= " AND (";
		$query .= "    everyone_perms !='0'";
		$query .= $group_query;
		$query .= " OR user_id='".$dms_user_id."'";
		$query .= ")";
		$query .= " AND ( obj_status < 2 ) ";
		//$query .= " AND (obj_type='".FOLDER."' OR obj_type='".INBOXEMPTY."' OR obj_type='".INBOXFULL."' OR obj_type='".DISKDIR."') ";
		$query .= "GROUP BY obj_id ";
		$query .= "ORDER BY obj_type desc,obj_name ";
//		$query .= "LIMIT ".$dms_config['doc_display_limit'];
		//print "<BR>".$query."<BR>";
		//exit(0);
		}

//	print "<BR>".$query."<BR>";
	return($query);
}


function dms_fts_doc_maintenance($obj_id)               // Changes the file names for full text search.
{
	global $dms_config, $dmsdb;

	if($dms_config['full_text_search_cdo'] == 0) return;

	$query = "SELECT current_version_row_id FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_id."'";
	$current_version_row_id = $dmsdb->query($query,"current_version_row_id");

	$query  = "SELECT row_id,file_path FROM ".$dmsdb->prefix("dms_object_versions")." WHERE ";
	$query .= "obj_id='".$obj_id."' AND ";
	$query .= "file_path LIKE '%.dat' AND ";
	$query .= "row_id !='".$current_version_row_id."'";
	$result = $dmsdb->query($query);
	$num_rows = $dmsdb->getnumrows();

	if($num_rows > 0)
		{
		while($result_data = $dmsdb->getarray($result))
			{
			$new_partial_path_and_file =str_replace(".dat",".old",$result_data['file_path']);
			$old_file = $dms_config['doc_path']."/".$result_data['file_path'];
			$new_file = $dms_config['doc_path']."/".$new_partial_path_and_file;

			$query  = "UPDATE ".$dmsdb->prefix("dms_object_versions")." SET ";
			$query .= "file_path='".$new_partial_path_and_file."' WHERE row_id='".$result_data['row_id']."'";
			$dmsdb->query($query);

			rename($old_file,$new_file);
			}
		}

}


function dms_document_deletion($purge_limit = 0)
{
	global $dms_config, $dmsdb; // removed for php 7 compatibility $HTTP_SERVER_VARS


	// If document purging is not enabled, then exit.
	if($dms_config['purge_enable'] == 0) return;

	// If the page being displayed is the main page (index.php) exit.
	//if(strpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"index.php")) return;    Removed for PHP 7 compatibility....probably not needed anyhow.

	// Determine the time_stamp_delete parameter to query before
	$time_stamp_delete = time() - ($dms_config['purge_delay'] * 86400);  // Where 86400 is the number of seconds in a day

	// Determine where clause for dms_objects.status query
	$where_clause = "( ";
	switch ($dms_config['purge_level'])
		{
		case TOTAL:
			$where_clause .= " (obj_status = '".PURGED_FD."') OR ";
		case FILES:
			$where_clause .= " (obj_status = '".PURGED_FS."') OR ";
		case FLAGGING:
			$where_clause .= " (obj_status = '".DELETED."') )";
			break;
		default:
			return;
			break;
		}

	if($purge_limit == 0) $purge_limit = $dms_config['purge_limit'];

	// Get a list of all deleted documents limited by $dms_config['purge_limit']
	// Only a small number of documents will be purged at any one time to prevent the server from hanging.
	$query  = "SELECT obj_id,obj_type from ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE ".$where_clause." ";
	$query .= "AND (time_stamp_delete < ".$time_stamp_delete.") ";
	$query .= "LIMIT ".$purge_limit;
	$result = $dmsdb->query($query);

	while($result_data = $dmsdb->getarray($result))
		{
		// At this time, only purge files.
		if($result_data['obj_type'] == FILE) dms_purge_document($result_data['obj_id']);
		}
}

function dms_document_name_sync($obj_id)
{
	global $dms_config, $dmsdb;

	if($dms_config['doc_name_sync'] == 0) return(0);

	$query = "SELECT obj_owner FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$obj_id."'";
	$parent_folder_id = $dmsdb->query($query,"obj_owner");

	$doc_name_sync_flag = FALSE;
	$query = "SELECT data FROM ".$dmsdb->prefix("dms_object_misc")." WHERE obj_id='".$parent_folder_id."' AND data_type='".FLAGS."'";
	$flags = $dmsdb->query($query,"data");
	if ( ($flags & 2) == 2 ) $doc_name_sync_flag = TRUE;

	if($doc_name_sync_flag == FALSE) return(0);

	//  Get the name of the document
	$query = "SELECT obj_name FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id = '".$obj_id."'";
	$obj_name = $dmsdb->query($query,"obj_name");

	//  Look for a valid file extension
	$ext_flag = 0;
	$ext_array = array(0 => "doc",1 => "xls", 2=> "jpg", 3 => "txt");

	// Check for a valid file extension as listed in $ext_array.  If one is found, $flag will be > 0.
	$index = 0;

	while($ext_array[$index])
		{
		$search_ext = ".".$ext_array[$index];
		if (!(stristr($obj_name,$search_ext) == FALSE)) $ext_flag++;

		$index++;
		}

	//  If the $obj_name does not have a valid filename extension, add one.
	if($ext_flag == 0)
		{
		$file_props = dms_get_rep_file_props($obj_id);

		$file_name = dms_filename_plus_ext($obj_name,$file_props['file_type']);
		}
	else $file_name = $obj_name;

	//  Update the file name(s)
	$query  = "UPDATE ".$dmsdb->prefix("dms_object_versions")." ";
	$query .= "SET file_name='".$file_name."' WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);
}


function dms_email_subscribers($obj_id,$message)
	{
	global $dms_config, $dmsdb;

	// If the subscriptions system is enabled, get a list of users subscribed to this document and e-mail them.
	if($dms_config['sub_email_enable']=='1')
		{
		// Get a list of users subscribed to this document
		$query  = "SELECT user_id FROM ".$dmsdb->prefix('dms_subscriptions')." ";
		$query .= "WHERE obj_id = '".$obj_id."'";
		$result = $dmsdb->query($query);
		$num_rows = $dmsdb->getnumrows($result);
		if($num_rows > 0)
			{
			while($result_data = $dmsdb->getarray($result))
				{
				// Get e-mail address
				$query  = "SELECT email FROM ".$dmsdb->prefix('users')." ";
				$query .= "WHERE uid='".$result_data['user_id']."'";
				$dest_user_email = $dmsdb->query($query,'email');

				// Compose and send email
				dms_send_email($dest_user_email,$dms_config['sub_email_from'],$dms_config['sub_email_subject'],$message);
				}
			}
		}
	}


function dms_file_limit()
{
    global $dmsdb, $dms_file_counter, $dms_file_counter_flag;

    if($dms_file_counter_flag == TRUE)
        {
        $query = "SELECT count(*) as num_docs FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_type='".FILE."'";
        $dms_file_counter = $dmsdb->query($query,'num_docs');
//print $dms_file_counter;
        if($dms_file_counter > DMS_MAX_FILES) return TRUE;
        }

    return FALSE;
}


function dms_folder_create($obj_name,$obj_owner,$obj_type = FOLDER)
	{
	global $dmsdb, $dms_user_id;

	$obj_name = dms_strprep($obj_name);

	$query  = "INSERT INTO ".$dmsdb->prefix("dms_objects")." (obj_type,obj_name,obj_owner) VALUES (";
	$query .= "'".$obj_type."',";
	$query .= "'".$obj_name."',";
	$query .= "'".$obj_owner."')";
	$dmsdb->query($query);

	// Get the obj_id of the new object
	$obj_id = $dmsdb->getid();

	dms_perms_set_init($obj_id, $obj_owner);

	dms_auditing($obj_id,"folder/new");
	}

function dms_folder_subscriptions($doc_obj_id, $opt_message="", $opt_folder_id=0)
{
	global $dms_config, $dmsdb, $dms_groups, $dms_users;

	// If the notification system is not enabled, exit.
	if($dms_config['notify_enable'] != 1) return;

	// Get the name of the document, parent obj_id, and current_version_row_id
	$query  = "SELECT obj_name,obj_owner,current_version_row_id FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$doc_obj_id."'";

	$result = $dmsdb->query($query,"ROW");
	$cvr_id = $result->current_version_row_id;
	$doc_name = $result->obj_name;
	$folder_obj_id = $result->obj_owner;

	//  Get the users and groups to notify
	$query  = "SELECT user_id,group_id FROM ".$dmsdb->prefix("dms_notify")." WHERE obj_id='".$folder_obj_id."'";

	$notify_list = $dmsdb->query($query);

	//  If there aren't any users or groups to notify, then exit.
	if($dmsdb->getnumrows() < 1) return;

	// Get the name of the document, parent obj_id, and current_version_row_id
	$query  = "SELECT obj_name,current_version_row_id FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$doc_obj_id."'";

	$result = $dmsdb->query($query,"ROW");
	$cvr_id = $result->current_version_row_id;
	$doc_name = $result->obj_name;

	// Get the name of the folder
	if($opt_folder_id > 0) $folder_obj_id = $opt_folder_id;

	if($folder_obj_id > 0)
		{
		$query  = "SELECT obj_name FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$folder_obj_id."'";
		$folder_name = $dmsdb->query($query,"obj_name");
		}
	else
		{
		$folder_name = "Root";
		}

	// Get the comments
	$query  = "SELECT comment FROM ".$dmsdb->prefix("dms_object_version_comments")." WHERE dov_row_id = '".$cvr_id."'";
	$comments = $dmsdb->query($query,"comment");
	if($dmsdb->getnumrows() == 0) $comments = "";

	$message  = "The contents of a folder have changed:<BR><BR>";
	$message .= "&nbsp;&nbsp;Folder:&nbsp;&nbsp;".$folder_name."<BR>";
	$message .= "&nbsp;&nbsp;Document:&nbsp;&nbsp;".$doc_name."<BR>";
	$message .= "<BR><BR>";
	if(0 != strlen($opt_message))
		{
		$message .= $opt_message."<BR><BR>";
		}
	$message .= "Comments:<BR><BR>";
	$message .= $comments;

	while($notify_data = $dmsdb->getarray($notify_list))
		{
		//  If a user, e-mail the user with the message.
		if($notify_data['user_id'] > 0)
			{
			$user_email = $dms_users->get_email_addr($notify_data['user_id']);

			if(strlen($user_email) > 0)
				dms_send_email($user_email,$dms_config['notify_email_from'],$dms_config['notify_email_subject'],$message);
			}

		// If a group, get the members of the group and e-mail them.
		if($notify_data['group_id'] > 0)
			{
			$user_list = $dms_groups->usr_list($notify_data['group_id']);

			foreach ($user_list as $u_id => $u_name)
				{
				$user_email = $dms_users->get_email_addr($u_id);

				if(strlen($user_email) > 0)
					dms_send_email($user_email,$dms_config['notify_email_from'],$dms_config['notify_email_subject'],$message);
				}

			}
		}
}


function dms_get_config()
{
	global $class_content,$class_header,$class_subheader,$class_narrow_header,$class_narrow_content;
	global $dmsdb, $dms_anon_flag, $dms_user_id, $dms_admin_flag, $dms_disp_flag;

	global $dms_config;

	global $dms_users;

	$dms_anon_flag = 0;
	$timestamps_match = FALSE;

	// Configuration caching.  In an effort to reduce the size of SQL queries, the configuration is cached in the
	// session variable $_SESSION['dms_config'].  If the configuration is current, $dms_config[] is loaded from this
	// session variable.  In the future, the session variable may be read directly.

	// Check to see if the configuration has already been read.
	if(isset($_SESSION['dms_config']))
		{
		// Get only the timestamp from the database.
		$query  = "SELECT data from ".$dmsdb->prefix("dms_config")." ";
		$query .= "WHERE name='time_stamp'";
		$result = $dmsdb->query($query,'data');

//print "Retreived TS:  ".$result."<BR>";
//print "Stored TS:  ".$_SESSION['dms_config']['time_stamp']."<BR>";

		// If the timestamps match and are not zero, load the config from the config session array.
		if( ($result != 0) && ($_SESSION['dms_config']['time_stamp'] == $result) )
//		if($_SESSION['dms_config']['time_stamp'] == $result)
			{
			$timestamps_match = TRUE;

			foreach ($_SESSION['dms_config'] as $key=>$value)
				{
				$dms_config[$key] = $value;
//print "\$_SESSION['dms_config'][\"$key\"]==$value<br>";
				}
			}
		}
	else
		{
		$_SESSION['dms_config'] = array();
		}

	// If $timestamps_match = FALSE, Obtain the configuration from the database and set the time_stamp
	if($timestamps_match == FALSE)
		{
		dms_update_config_time_stamp();

		$query = "SELECT * from ".$dmsdb->prefix("dms_config");
		$result = $dmsdb->query($query);

		while($result_data = $dmsdb->getarray($result))
			{
			$dms_config[$result_data['name']] = $result_data['data'];
			$_SESSION['dms_config'][$result_data['name']] = $result_data['data'];
			}
		}

	// Set the stylesheet classes
	if(strlen($dms_config['class_content']) > 2) $dms_config['class_content'] = " class='".$dms_config['class_content']."'";
	if(strlen($dms_config['class_header']) > 2) $dms_config['class_header'] = " class='".$dms_config['class_header']."'";
	if(strlen($dms_config['class_subheader']) > 2) $dms_config['class_subheader'] = " class='".$dms_config['class_subheader']."'";
	if(strlen($dms_config['class_narrow_header']) > 2) $dms_config['class_narrow_header'] = " class='".$dms_config['class_narrow_header']."'";
	if(strlen($dms_config['class_narrow_content']) > 2) $dms_config['class_narrow_content'] = " class='".$dms_config['class_narrow_content']."'";

// Legacy class strings...to be removed later
	$class_content = $dms_config['class_content'];
	$class_header = $dms_config['class_header'];
	$class_subheader = $dms_config['class_subheader'];
	$class_narrow_header = $dms_config['class_narrow_header'];
	$class_narrow_content = $dms_config['class_narrow_content'];

	// Get the user id and determine if the user is an administrator
    $dms_user_id = $dms_users->get_current_user_id();

    $dms_admin_flag = 0;
    if($dms_users->admin() == TRUE) $dms_admin_flag = 1;

    $dms_disp_flag = "TRUE";

/*
	if($dms_users->get_current_user_id() > 0)
		{
		$dms_user_id = $dms_users->get_current_user_id();
		// Determine if the user is an administrator
		if($dms_users->admin() == TRUE)
			{
			$dms_admin_flag = 1;
			}
		else
			{
			$dms_admin_flag = 0;
			}
		}
	else
		{
		if($dms_config['anon_user_id'] >= 1)
			{
			$dms_user_id = $dms_config['anon_user_id'];
			$dms_admin_flag = 0;
			$dms_anon_flag = 1;
			}
		else
			{
			$dms_disp_flag = "FALSE";
			//print "DMS is not configured to allow anonymous users.  Please login to access this module.\r";
			//exit (0);
			}
		}
*/

}


$dms_rep_file_props = array("file_name" => "", "file_type" => "", "file_size" => "", "file_path" => "");

function dms_get_rep_file_props($file_id)
{
	global $dms_rep_file_props, $dms_config, $dmsdb;

	// Get file information
	$query  = "SELECT obj_name,ptr_obj_id,obj_type,current_version_row_id from ".$dmsdb->prefix('dms_objects')." ";
	$query .= "WHERE obj_id='".$file_id."'";
	$first_result = $dmsdb->query($query,"ROW");

	// If this object is a link, get the real information.
	if ($first_result->obj_type == 4)
		{
		$query  = "SELECT current_version_row_id from ".$dmsdb->prefix('dms_objects')." ";
		$query .= "WHERE obj_id='".$first_result->ptr_obj_id."'";
		$first_result = $dmsdb->query($query,"ROW");
		}

	$query  = "SELECT file_name,file_type,file_size,file_path from ".$dmsdb->prefix('dms_object_versions')." ";
	$query .= "WHERE row_id='".$first_result->current_version_row_id."'";
	$second_result = $dmsdb->query($query,"ROW");

	//$dms_rep_file_props['file_name'] = $first_result->obj_name;
	$dms_rep_file_props['file_name'] = $second_result->file_name;
	$dms_rep_file_props['file_type'] = $second_result->file_type;
	$dms_rep_file_props['file_size'] = $second_result->file_size;
	$dms_rep_file_props['file_path'] = $dms_config['doc_path']."/".$second_result->file_path;

	// If the OS is Linux, get the mime type directly.
	//if($dms_config['OS']=="Linux") $dms_rep_file_props['file_type'] = trim(exec('file -bi '. escapeshellarg($dms_rep_file_props['file_path'])));

	return($dms_rep_file_props);
}

function dms_get_user_data()
	{
	global $active_folder, $active_folder_type, $active_folder_perms, $admin_display, $template_root_folder, $dms_admin_flag, $dmsdb;
    global $dms_config;

	// Get active folder
	$active_folder = dms_active_folder();
	$active_folder_perms = dms_perms_level($active_folder);

	// Get the object type of the active folder, if applicable
	if ($active_folder!=0)
		{
		$query = "SELECT obj_type from ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$active_folder."'";
		$active_folder_type = $dmsdb->query($query,'obj_type');
		}
	else
		{
		$active_folder_type = 0;
		}

	// If the user is an Admin, get the admin_display value
	if ($dms_admin_flag == 1)
		{
		$admin_display = $dms_config['admin_display'];
		}
	else
		{
		$admin_display = '0';
		}
	}


// Get a variable that has been POSTed or is in the query string (GET).  Return the contents of the variable.
// If the variable does not exist, returns FALSE;
function dms_get_var($var_name)
{
	$value = FALSE;
	if(isset($_GET[$var_name])) $value = $_GET[$var_name];
	if(isset($_POST[$var_name])) $value = $_POST[$var_name];

	return ($value);
}

// Get a checkbox variable that has been POSTed or is in the query string (GET).  Return either 1 or 0.
// If the variable does not exist, returns 0;
function dms_get_var_chk($var_name)
{
	$value = 0;
	if(isset($_GET[$var_name])) if($_GET[$var_name] == "on") $value = 1;
	if(isset($_POST[$var_name])) if($_POST[$var_name] == "on") $value = 1;

	return ($value);
}


function dms_graph_single_bar($value,$total,$yellow_limit = 75,$red_limit = 90)
{
    $image_dir = DMS_ICONS."/custom/";

	global $dms_config;

	$percent = round(($value / $total) * 100);

	if($percent < 1) $percent = 1;

	$color_file = "graph_green.png";
	if ($percent >= $yellow_limit) $color_file = "graph_yellow.png";
	if ($percent >= $red_limit)    $color_file = "graph_red.png";

	print "<img src='".$image_dir."graph_end.png'>";

	for($index = 0; $index < 100; $index++)
		{
		if((int)$index <= (int)$percent) print "<img src='".$image_dir.$color_file."'>";
		else print "<img src='".$image_dir."graph_grey.png'>";
		}

	print "<img src='".$image_dir."graph_end.png'>";
	print " ".$percent."%";
}


function dms_help_system($id,$control=1)
	{
//  DISABLED!!
	return;

	global $dmsdb, $dms_admin_flag;

	// Determine Help Icon
	switch ($control)
		{
		case 1:
			$icon_text = "<font color='blue'>?</font>";
			break;
		case 2:
			$icon_text = "<img src='images/help2.gif'>";
			break;
		case 3:
			$icon_text = "<img src='images/help.gif'>";
			break;
		}

	// Get the object id of the help file
	$query  = "SELECT obj_id_ptr FROM ".$dmsdb->prefix("dms_help_system")." ";
	$query .= "WHERE help_id='".$id."'";
	$obj_id_ptr = $dmsdb->query($query,"obj_id_ptr");

	if($dmsdb->getnumrows() > 0)
		{
		if($control <=9)
		  print "<a href='#' title='Help' onclick='javascript:void(window.open(\"file_retrieve.php?function=view&obj_id=".$obj_id_ptr."\",null,\"width=650,scrollbars=yes,resizable=yes\"))'>".$icon_text."</a>\r";

		if($control == 10)
		  print "    <input type='button' name='btn_help' value='Help' onclick='javascript:void(window.open(\"file_retrieve.php?function=view&obj_id=".$obj_id_ptr."\",null,\"width=650,scrollbars=yes,resizable=yes\"))'>&nbsp;&nbsp;";
		}

	if($dms_admin_flag == 1)
		{
		print "<font color='red'><a href='#' title='Admin' onclick='javascript:void(location=\"config_help_system.php?id=".$id."\")'>A</a></font>\r";
		}
	}

function dms_inbox_id($user_id)
	{
	global $dmsdb;

	// Get Destination Inbox obj_id (this will be the object_owner of the new object)
	$query  = "SELECT obj_id "; //, obj_type, obj_status, ";
	$query .= "FROM ".$dmsdb->prefix("dms_object_perms")." ";
	$query .= "INNER JOIN ".$dmsdb->prefix("dms_objects")." ON ";
	$query .= $dmsdb->prefix("dms_object_perms").".ptr_obj_id = obj_id ";
	$query .= "WHERE (obj_type='".INBOXEMPTY."' OR obj_type='".INBOXFULL."') ";
	$query .= "AND (user_id='".$user_id."') ";
	$query .= "AND (user_perms='".OWNER."')";

	//print "<BR>".$query."<BR>";
	//exit(0);

	$inbox_obj_id = $dmsdb->query($query,'obj_id');
	if($dmsdb->getnumrows() == 0) $inbox_obj_id = 0;
	return $inbox_obj_id;
	}


/*
function dms_javascript_clock()
{
	// Adds a javascript clock to the page for the purposes of locking out

}
*/

function dms_inc_functions_test()
	{
	print "inc_dms_functions.php loaded<BR>\r";
	}

function dms_message($message)
{
	$_SESSION['dms_message'] = $message;
}

function dms_purge_document($obj_id)
{
	global $dms_config, $dmsdb;

	// Always delete linked objects (routed documents)
	$query  = "DELETE from ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE ptr_obj_id='".$obj_id."'";
	$dmsdb->query($query);

	// if purge_level is FLAGGING (0) then just set dms_objects.obj_status to PURGED_FS (3)
	if($dms_config['purge_level'] == FLAGGING)
		{
		$query  = "UPDATE ".$dmsdb->prefix("dms_objects")." ";
		$query .= "SET obj_status='".PURGED_FS."', time_stamp_delete='".time()."' ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);
		}

	// if purge_level is FILES (1) then just set dms_objects.obj_status to PURGED_FD (4)
	if($dms_config['purge_level'] == FILES)
		{
		$query  = "UPDATE ".$dmsdb->prefix("dms_objects")." ";
		$query .= "SET obj_status='".PURGED_FD."', current_version_row_id='0', time_stamp_delete='".time()."' ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);
		}

	// if purge_level is TOTAL (2) then delete all related database entries
	if($dms_config['purge_level'] == TOTAL)
		{
		$query  = "DELETE from ".$dmsdb->prefix("dms_objects")." ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);

		$query  = "DELETE from ".$dmsdb->prefix("dms_object_perms")." ";
		$query .= "WHERE ptr_obj_id='".$obj_id."'";
		$dmsdb->query($query);

		$query  = "DELETE from ".$dmsdb->prefix("dms_object_properties")." ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);

		$query  = "DELETE from ".$dmsdb->prefix("dms_audit_log")." ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);

		$query  = "DELETE from ".$dmsdb->prefix("dms_routing_data")." ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);
		}

	// If purge_level is FILES (1) or TOTAL (2) then delete the files in the repository.
	if(($dms_config['purge_level'] == FILES) || ($dms_config['purge_level'] == TOTAL))
		{
		$query  = "SELECT file_path FROM ".$dmsdb->prefix("dms_object_versions")." ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$result = $dmsdb->query($query);

		while($result_data=$dmsdb->getarray($result))
			{
			$file_path = $dms_config['doc_path']."/".$result_data['file_path'];
			unlink($file_path);
			}

		$query  = "DELETE from ".$dmsdb->prefix("dms_object_versions")." ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);
		}
}

function dms_perms_apply_group($pg_obj_id,$obj_id)
	{
	global $dmsdb;

	// Get the permissions categories to change
	// 0 = OWNER, 1 = EVERYONE, 2 = GROUPS, 3 = USERS
	$query = "SELECT data FROM ".$dmsdb->prefix("dms_object_misc")." WHERE obj_id='".$pg_obj_id."' AND data_type='".PERMS_GROUP."'";
	$change_perms_data = $dmsdb->query($query,"data");

	$where_clause = "";
	$or_clause = FALSE;

	$mask = 1;
	for($index = 0; $index < 4; $index++)
		{
		if( ($change_perms_data & $mask) == $mask)
			{
			if($or_clause == TRUE) $where_clause .= " OR ";
			if($mask == 1) $where_clause .= "(user_id > 0 AND user_perms = 4)";
			if($mask == 2) $where_clause .= "( (user_id = 0) && (group_id = 0) )";
			if($mask == 4) $where_clause .= "(group_id > 0)";
			if($mask == 8) $where_clause .= "(user_id > 0 AND user_perms < 4)";
			$or_clause = TRUE;
			}
		$mask *= 2;
		}

	if($or_clause == TRUE) $where_clause = " AND (".$where_clause.")";
	else return(0);   // If there aren't any permissions selected to change, exit this function.

	// Delete the permissions for the object.
	$query  = "DELETE FROM ".$dmsdb->prefix("dms_object_perms")." ";
	$query .= "WHERE ptr_obj_id='".$obj_id."' ".$where_clause;
	$dmsdb->query($query);

	// Copy the lifecycle stage permissions to the object.
	$query  = "SELECT * FROM ".$dmsdb->prefix("dms_object_perms")." ";
	$query .= "WHERE ptr_obj_id='".$pg_obj_id."' ".$where_clause;
	$result = $dmsdb->query($query);

	while($result_data = $dmsdb->getarray($result))
		{
		$query  = "INSERT INTO ".$dmsdb->prefix("dms_object_perms")." ";
		$query .= "(ptr_obj_id,user_id,group_id,user_perms,group_perms,everyone_perms) VALUES (";
		$query .= "'".$obj_id."',";
		$query .= "'".$result_data['user_id']."',";
		$query .= "'".$result_data['group_id']."',";
		$query .= "'".$result_data['user_perms']."',";
		$query .= "'".$result_data['group_perms']."',";
		$query .= "'".$result_data['everyone_perms']."')";
		$dmsdb->query($query);
		}
	}

/*
function dms_perms_cache_clear()
	{

	global $dms_config;

	for($index = 0; $index <= $dms_config['pc_cache_size']; $index++)
		{
		$_SESSION['perms_cache_obj_id'][$index] = 0;
		$_SESSION['perms_cache_perms_level'][$index] = 0;
		}

	}
*/
// This function returns the permissions level that a user has to a particular object.
function dms_perms_level($obj_id)
	{
	global $dms_config;
	global $group_array, $dmsdb, $dms_groups, $dms_user_id, $dms_anon_flag, $dms_admin_flag;

	static $pl_group_list = array();
	static $pl_group_list_flag = 0;

  // Obtain a list of groups the user is a member of.  This is retained as a static variable in order prevent multiple look-ups of
  // the group_list.
	if ($pl_group_list_flag == 0)
		{
		$pl_group_list = $dms_groups->grp_list();
		$pl_group_list_flag = 1;
		}

	//  Obtain the entire list of permissions for the object.
	$query  = "SELECT user_id, group_id, user_perms, group_perms, everyone_perms ";
	$query .= "FROM ".$dmsdb->prefix("dms_object_perms")." ";
	$query .= "WHERE ptr_obj_id='".$obj_id."'";

	$perms = $dmsdb->query($query);
	$max_perm = 0;

	while($perms_data = $dmsdb->getarray($perms))
    	{
		if ( ($dms_user_id == $perms_data['user_id']) && ($max_perm < $perms_data['user_perms']) )
		  $max_perm = $perms_data['user_perms'];

//print "u".$max_perm;

		$index = 0;
		//while($pl_group_list[$index])
		while($index < $pl_group_list['num_rows'])
			{
			if( ($pl_group_list[$index] == $perms_data['group_id']) && ($max_perm < $perms_data['group_perms']) )
			  $max_perm = $perms_data['group_perms'];
			$index++;
			}

//print "g".$max_perm;

        //  If the user is logged in, check the everyone_perms
        if ( ( $dms_user_id !=0 ) && ($perms_data['everyone_perms'] > $max_perm) ) $max_perm = $perms_data['everyone_perms'];

        //  If the user is not logged in, only check the everyone_perms if configured to do so.
        if ( ( $dms_user_id == 0 )
            && ($dms_config['everyone_perms'] == 1)
            && ($perms_data['everyone_perms'] > $max_perm) )
                $max_perm = $perms_data['everyone_perms'];


//		if ($perms_data['everyone_perms'] > $max_perm) $max_perm = $perms_data['everyone_perms'];
//print "e".$max_perm;
		}

	// If the user is anonymous, grant them a maximum of readonly perms.
	// if( ($dms_anon_flag >= 1) && ($max_perm > READONLY) ) $max_perm = READONLY;
//print "a".$max_perm;

	// If the user is an administrator and $dms_config['admin_display'] == 1, set the perm level to OWNER
	if( ($dms_admin_flag == 1) && ($dms_config['admin_display'] == '1')) $max_perm = OWNER;
//exit(0);

	return $max_perm;
	}

function dms_perms_owner_user_id($obj_id)
{
	global $dmsdb;

	//  Obtain user_id for the owner for the object.
	$query  = "SELECT user_id ";
	$query .= "FROM ".$dmsdb->prefix("dms_object_perms")." ";
	$query .= "WHERE ptr_obj_id='".$obj_id."' and user_perms='4'";

	return $dmsdb->query($query,'user_id');
}

// Set the initial permissions for an object.
function dms_perms_set_init($obj_id, $parent_folder_id)
	{
	global $dms_config, $dmsdb, $dms_user_id;

	// If there is a parent folder and the permissions are inherited from the parent folder, use them.
	if( ($dms_config['inherit_perms'] == 1) && ($parent_folder_id > 0) )
		{
		// Copy only the non-owner permissions, if they exist.
		$query = "SELECT * from ".$dmsdb->prefix('dms_object_perms')." WHERE ptr_obj_id='".$parent_folder_id."'";
		$result = $dmsdb->query($query);
		$num_rows = $dmsdb->getnumrows();

		if($num_rows > 0)
			{
			while($result_data = $dmsdb->getarray($result))
				{
				if($result_data['user_perms'] != '4')
					{
					$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_perms')." ";
					$query .= "(ptr_obj_id,user_id, group_id, user_perms, group_perms, everyone_perms) VALUES ('";
					$query .= $obj_id."','";
					$query .= $result_data['user_id']."','";
					$query .= $result_data['group_id']."','";
					$query .= $result_data['user_perms']."','";
					$query .= $result_data['group_perms']."','";
					$query .= $result_data['everyone_perms']."')";

					$dmsdb->query($query);
					}
				}
			}
		}

	// Store the owner permissions in dms_object_perms
	$query  = "INSERT INTO ".$dmsdb->prefix('dms_object_perms')." ";
	$query .= "(ptr_obj_id,user_id, group_id, user_perms, group_perms, everyone_perms) VALUES ('";
	$query .= $obj_id."','";
	$query .= $dms_user_id."','";
	$query .= "0','";
	$query .= "4','";
	$query .= "0','";
	$query .= "0')";

	$dmsdb->query($query);
	}

function dms_redirect($url)
	{
	print "<SCRIPT LANGUAGE='Javascript'>\r";
	print "    location=\"".$url."\";\r";
	print "</SCRIPT>\r";
	}

function dms_select_version_number($select_box_naming = 'slct_version',$major_num = 1, $minor_num = 0, $sub_minor_num = 0)
{
	global $dms_tab_index;

	print "<select name='".$select_box_naming."_major' tabindex='".$dms_tab_index++."'>\r";

	$index=0;
	while($index < 10)
	{
		print "<option value='".$index."' ";
		if ($index == $major_num) print " selected";
		print ">".$index."</option> \r";

		$index++;
		}
	print "</select>\r";

	print "&nbsp;.&nbsp;\r";

	print "<select name='".$select_box_naming."_minor' tabindex='".$dms_tab_index++."'>\r";
	$index=0;
	while($index < 10)
	{
		print "<option value='".$index."' ";
		if ($index == $minor_num) print " selected";
		print ">".$index."</option> \r";

		$index++;
		}
	print "</select>\r";

	print "&nbsp;.&nbsp;\r";

	print "<select name='".$select_box_naming."_sub_minor' tabindex='".$dms_tab_index++."'>\r";
	$index=0;
	while($index < 10)
	{
		print "<option value='".$index."' ";
		if ($index == $sub_minor_num) print " selected";
		print ">".$index."</option> \r";

		$index++;
		}
	print "</select>\r";
}

function dms_send_email($to="", $from="", $subject="", $message_text="", $attachment_obj_id=0, $uploaded_file_data=0 )
{
	global $dms_rep_file_props;

	// If there is an attachment, get all of the file information.
	if($attachment_obj_id > 0)
		{
		dms_get_rep_file_props($attachment_obj_id);
		$file = fopen($dms_rep_file_props['file_path'],'rb');
		$data = fread($file,filesize($dms_rep_file_props['file_path']));
		fclose($file);

		$data = chunk_split(base64_encode($data));
		}

	// If there is an uploaded file, get the file information...if there is an $attachment_obj_id, ignore this
	if( ($attachment_obj_id == 0) && ($uploaded_file_data != 0) )
		{
		$file = fopen($uploaded_file_data['path'],'rb');
		$data = fread($file,filesize($uploaded_file_data['path']));
		fclose($file);

		$data = chunk_split(base64_encode($data));
		}

	$mime_boundary = "==Multipart_Boundary_x".md5(mt_rand())."x";

	$headers  = "To:  ".$to."\n";
	$headers .= "From:  ".$from."\n";
	$headers .= "X-Mailer: PHP mailer\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type: multipart/mixed;\r\n boundary=\"".$mime_boundary."\"";

	$message  = "This is a multi-part message in MIME format.\n\n";
	$message .= "--".$mime_boundary."\n";
	$message .= "Content-Type: text/html; charset=iso-8859-1\n";
	$message .= "Content-Transfer-Encoding: 7bit\n\n";
	$message .= $message_text."\n\n";

	// Add timestamp
	$message .= strftime("<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>Message Sent:  %d-%B-%Y %I:%M%p",time())."\n\n";
	// End Add timestamp

	if($attachment_obj_id > 0)
		{
		$message .= "--".$mime_boundary."\n";
		$message .= "Content-Type: ".$dms_rep_file_props['file_type'].";\n";
		$message .= " name=\"".$dms_rep_file_props['file_name']."\"\n";
		$message .= "Content-Transfer-Encoding: base64\n\n";
		$message .= $data."\n\n";
		}

	if( ($attachment_obj_id == 0) && ($uploaded_file_data != 0) )
		{
		$message .= "--".$mime_boundary."\n";
		$message .= "Content-Type: ".$uploaded_file_data['type'].";\n";
		$message .= " name=\"".$uploaded_file_data['name']."\"\n";
		$message .= "Content-Transfer-Encoding: base64\n\n";
		$message .= $data."\n\n";
		}

	$message .= "--".$mime_boundary."--\n";
	mail($to, $subject, $message, $headers);
}

function dms_set_inbox_status($obj_id)
{
	global $dmsdb;

	// Check to see if this $obj_id is an inbox
	$query  = "SELECT obj_type FROM ".$dmsdb->prefix('dms_objects')." ";
	$query .= "WHERE obj_id = '".$obj_id."'";
	$obj_type = $dmsdb->query($query,'obj_type');

	if( ($obj_type == INBOXFULL) || ($obj_type == INBOXEMPTY) )
		{
		// Get the number of documents in the inbox
		$query  = "SELECT count(*) as num FROM ".$dmsdb->prefix('dms_objects')." ";
		$query .= "WHERE obj_owner='".$obj_id."'";
		$number_of_docs = $dmsdb->query($query,'num');

		$obj_type=INBOXEMPTY;
		if ($number_of_docs > 0) $obj_type = INBOXFULL;

		// Set the status of the inbox
		$query  = "UPDATE ".$dmsdb->prefix('dms_objects')." SET obj_type='".$obj_type."' ";
		$query .= "WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);
		}
}

function dms_set_obj_status($obj_id,$status)
	{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_objects")." ";
	$query .= "SET obj_status='".$status."'";
	if($status >= DELETED) $query .= ", time_stamp_delete='".time()."' ";
	$query .= " WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);
	}

function strleft($str, $num_chars)
{
	return substr ($str, 0, $num_chars);
}

function strright($str, $num_chars)
{
	$str_length = strlen($str);
	return substr ($str, ($str_length - $num_chars),$str_length);
}

function dms_strprep($str)
{
	$str = str_replace("'","`",$str);    // Replace ' with `
	$str = str_replace("\""," ",$str);   // Replace " with <space>
	$str = str_replace("("," ",$str);    // Replace ( with <space>
	$str = str_replace(")"," ",$str);    // Replace ) with <space>
	$str = str_replace("<"," ",$str);    // Replace < with <space>
	$str = str_replace(">"," ",$str);    // Replace > with <space>
    $str = str_replace(";","",$str);     // Replace ; with <nothing>
	return $str;
}

function dms_str_clean($str)
{
	$str = str_replace("'","",$str);     // Replace ' with <nothing>
	$str = str_replace("\""," ",$str);   // Replace " with <space>
	$str = str_replace("("," ",$str);    // Replace ( with <space>
	$str = str_replace(")"," ",$str);    // Replace ) with <space>
	$str = str_replace("<"," ",$str);    // Replace < with <space>
	$str = str_replace(">"," ",$str);    // Replace > with <space>
	$str = str_replace(";"," ",$str);    // Replace ; with <space>
	$str = str_replace("*"," ",$str);    // Replace * with <space>
	return $str;
}

function dms_str_get_bytes($number)
{
	$number = trim($number);

	$multiplier = strtoupper(substr($number,(strlen($number)-1),1));

	switch($multiplier)
		{
		case 'G':	$number *= 1024;
		case 'M':	$number *= 1024;
		case 'K':	$number *= 1024;
		}

	return $number;
}

function dms_str_restore($str)
{
	$str = str_replace("`","'",$str);    // Replace ` with '
	$str = str_replace("|",";",$str);
}

function dms_update_config_time_stamp()
{
	global $dmsdb;

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." ";
	$query .= "SET data='".time()."' ";
	$query .= "WHERE name='time_stamp'";

	$dmsdb->query($query);
}

function dms_update_misc_text($obj_id,$lifecycle_stage_override = 0)
{
	global $dms_config,$dmsdb;

	$misc_text = "";

	$query  = "SELECT template_obj_id, lifecycle_id, lifecycle_stage FROM ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$result = $dmsdb->query($query,"ROW");

	$template_obj_id = $result->template_obj_id;
	$lifecycle_id = $result->lifecycle_id;
	$lifecycle_stage = $result->lifecycle_stage;

	if($lifecycle_stage_override > 0) $lifecycle_stage = $lifecycle_stage_override;

	// If configured and applicable, add the template name.
	if( ($dms_config['misc_text_disp_template'] == 1) && ($template_obj_id > 0) )
		{
		$query = "SELECT obj_name FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$template_obj_id."'";
		$misc_text = $dmsdb->query($query,'obj_name');
		}

	// If configured and applicable, add the lifecycle stage name.
	if( ($dms_config['misc_text_disp_lc_stage'] == 1) && ($lifecycle_id > 0) )
		{
		if(strlen($misc_text) > 0) $misc_text = $misc_text.", ";

		$query  = "SELECT lifecycle_stage_name FROM ".$dmsdb->prefix("dms_lifecycle_stages")." WHERE ";
		$query .= "lifecycle_id = '".$lifecycle_id."' AND lifecycle_stage = '".$lifecycle_stage."'";
		$misc_text = $misc_text.$dmsdb->query($query,'lifecycle_stage_name');
		}

	$query  = "UPDATE ".$dmsdb->prefix("dms_objects")." SET misc_text='".$misc_text."' WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);
}

// Get a variable from the dms session cache.  If it does not exist, create it and set it to 0.
function dms_var_cache_get($variable)
{
	if(!isset($dms_var_cache[$variable]))
		{
		$dms_var_cache[$variable] = 0;
		}

	return $dms_var_cache[$variable];
}

//  If the dms_var_cache has not been created, initialize it.
//  Called from dms_var_cache_load()
function dms_var_cache_init()
{
    global $dms_cms;
    dms_var_cache_set("cms_url",$dms_cms->dms_url());

    dms_var_cache_save();
}

function dms_var_cache_load()
{
	global $dms_var_cache;

	// Check to see if the variable cache exists...if not, create it.
	if(!isset($_SESSION['dms_var_cache']))
		{
		$_SESSION['dms_var_cache'] = array();
        dms_var_cache_init();
		}

	foreach ($_SESSION['dms_var_cache'] as $key=>$value)
		{
		$dms_var_cache[$key] = $value;
		//print "\$dms_var_cache[\"$key\"]==$value<br>";
		}
}

function dms_var_cache_save()
	{
	global $dms_var_cache;

	// Check to see if the variable cache exists...if not, create it.
	if(!isset($_SESSION['dms_var_cache']))
		{
		$_SESSION['dms_var_cache'] = array();
		}

	foreach ($dms_var_cache as $key=>$value)
		{
		$_SESSION['dms_var_cache'][$key] = $value;
		}
	}

function dms_var_cache_set($variable, $value=0)
	{
	$dms_var_cache[$variable] = $value;
	}

function dms_view_counter_increment($obj_id)
	{
	global $dmsdb;

	$query  = "SELECT num_views FROM ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$num_views = $dmsdb->query($query,"num_views");

	$num_views++;

	$query  = "UPDATE ".$dmsdb->prefix("dms_objects")." ";
	$query .= "SET num_views='".$num_views."' WHERE obj_id='".$obj_id."'";
	$dmsdb->query($query);
	}

function dms_view_counter_num_views($obj_id)
	{
	global $dmsdb;

	$query  = "SELECT num_views FROM ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$num_views = $dmsdb->query($query,"num_views");
	return $num_views;
	}


?>
