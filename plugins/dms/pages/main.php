<?php
/*
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //
*/

// main.php

global $dms_config, $dms_users, $dms_global;
global $dms_disp_flag, $dms_admin_flag;



//print "DMS USER ID:   ".$dms_user_id."<BR>";


// Main interface functions

function display_db_version_diff()
	{
	global $dms_config, $dms_admin_flag;

	print "<tr><td>\r";
	print "WARNING:  The Document Management System module has been updated but is still configured for a previous version.<BR>\r";
	print "&nbsp;&nbsp;&nbsp;Please run the Update Manager to bring the system up-to-date.\r";
	print "<BR><BR>\r";
	print "Current Version:  ".DMS_VERSION."<BR>\r";
	print "Previous Version:  ".$dms_config['version']."\r";;

	if($dms_admin_flag == 1)
		{
		print "<BR><BR>\r";
		print "<input name='btn_update_manager' type='button' value='Update Manager' onclick='location=\"./admin/update_manager.php\";'>\r";
		}

	print "</td></tr>\r";
	}

define('SEPARATOR_LIMIT',3);
$separator_counter = 0;
function display_separator()
	{
	global $separator_counter;

	//$bg_image="images/line.png";

    $bg_image = DMS_ICONS . "/custom/line.png";

	//$separator_counter ++;
	if ($separator_counter > SEPARATOR_LIMIT)
		{
		print "  <tr>\r";
		print "    <td height='1' background='".$bg_image."' nowrap></td>\r";
		print "    <td background='".$bg_image."' nowrap></td>\r";
		print "    <td background='".$bg_image."' nowrap></td>\r";
		print "    <td background='".$bg_image."' nowrap></td>\r";
		print "    <td background='".$bg_image."' nowrap></td>\r";
		print "    <td background='".$bg_image."' nowrap></td>\r";
		print "  <td background='".$bg_image."' nowrap></td>\r";
		print "  </tr>\r";

		$separator_counter = 1;
		}
	}
/*
function list_disk_dir($obj_id, $interface_type = "MULTIPLE")
	{
	global $active_folder,$dms_config,$level,$separator_counter,$dmsdb;

	// If this folder is not active, exit out of the function.
	if($obj_id != $active_folder) return(0);

	$bg_color="";
	$bg_image="images/line.png";

	// Set up display offsets
	$level_offset="";
	$index=0;
	while($index < $level)
		{
		$level_offset .= "&nbsp;&nbsp;&nbsp;";
		$index++;
		}

	if  ( $active_folder != 0  && ($obj_id == $active_folder) )
		$class = $dms_config['class_subheader']; //"class='cSubHeader'";
	else $class = "";

	if($interface_type = "SINGLE") $class = "";

	if($dms_config['default_interface'] == 2) $class="";

	// Get the directory to display
	$query  = "SELECT data from ".$dmsdb->prefix("dms_object_misc")." ";
	$query .= "WHERE obj_id=".$obj_id." AND data_type='".PATH."'";
	$dir = $dmsdb->query($query,'data');

	$file_list = array();

	$counter = 0;
	$handle = opendir($dir);
	while( ($file = readdir($handle) ) != false)
		{
		if($file =='.' || $file =='..') continue;

		if("file" == filetype($dir."/".$file))
			{
			$file_list[$counter] = $file."\n";
			$counter++;
			}
		}

	closedir($handle);

	sort($file_list);

	$counter = 0;
	while($file_list[$counter])
		{
		$separator_counter++;
		display_separator();

		print "<tr>\r";

		print "    <td ".$class." align='left' colspan='3'>".$level_offset."<a title='File'><img src='images/file.png'></a>&nbsp;&nbsp;&nbsp;\r";
		print "<a title='Click to import.' href='file_dir_import.php?obj_id=".$obj_id."&obj_num=".$counter."'>".$file_list[$counter]."</a>\r";
		print "    </td>\r";

		print "  <td></td>\r";
		print "  <td></td>\r";
		print "  <td></td>\r";
		print "  <td></td>\r";

		print "</tr>\r";

		$counter++;
		}
	}
*/



// Automatically create inboxes, if enabled.
if( ($dms_config['routing_auto_inbox']) == 1 && ($dms_user_id > 0) )
	{
	//  See if the user has an inbox, if there isn't an inbox, create one.
	if(dms_inbox_id($dms_user_id) == 0) dms_folder_create($dms_users->get_username($dms_user_id),0,INBOXEMPTY);
	}


// IF DMS display is permitted, load User Interface based upon dms_config.default_interface setting.
if($dms_disp_flag == "TRUE")
	{
	// Message Box

	//require_once( DMS_DIR . "/includes/general/i_message_box.php" );

	//dms_message_box();
	//dms_dhtml_mb_functions(0);

	//include_once 'inc_message_box.php';

    require_once( DMS_DIR . "/includes/general/i_main_ui_2.php" );

	//dms_show_mb();

    //  If user is the administrator, ensure that the /dms/config/config.php and /repository are writable.

    if($dms_admin_flag == 1)
        {
        //  Check dms/config/config.php
        $file = DMS_DIR."config/config.php";
        if(!is_writeable($file))
            {
            print "<table width='100%'><tr>";
                print "<td style='width: 25%'><font color='red'>Warning:</font></td>";
                print "<td>The DMS plugin is unable to write to the config.php file at ".DMS_DIR."config/config.php</td>";
            print "</tr></table>";
            }

        if(!is_writeable($dms_config['doc_path']))
            {
            print "<table width='100%'><tr>";
                print "<td style='width: 25%'><font color='red'>Warning:</font></td>";
                print "<td>The DMS plugin is unable to write to the document repository at ".$dms_config['doc_path']."</td>";
            print "</tr></table>";
            }
        }
	}
?>
