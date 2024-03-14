<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                  Written By:  Brian E. Reifsnyder                         //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //


// folder_new.php

global $dms_global;

if (dms_get_var("txt_folder_name") != FALSE)
	{
	dms_folder_create(dms_get_var("txt_folder_name"),dms_get_var("hdn_active_folder"));

	dms_redirect($dms_config['dms_url']);
	}
else
	{
	$dms_tab_index = 0;


	// Get active folder
	$active_folder = dms_active_folder();

    if($dms_users->admin() == FALSE)
			{
			$active_folder_perms = dms_perms_level($active_folder);
			if( ($active_folder_perms != EDIT) && ($active_folder_perms != OWNER) )
				{
				print("<SCRIPT LANGUAGE='Javascript'>\r");
				print("location='" . $dms_config['dms_url'] . "';");
				print("</SCRIPT>");
				}
			}

//	print "<form name='frm_folder_new' method='post' action='folder_new.php'>\r";
	print "<form name='frm_folder_new' method='post' action='" . $dms_config['dms_url'] . $dms_global["first_separator"]. "dms_page=folder_new'>\r";
	print "<table width='100%'>\r";

	dms_display_header(2,"","",FALSE);

	print "  <tr><td colspan='2' align='left'><BR></td></tr>\r";
	print "  <tr><td colspan='2' align='left'><b>" . _DMS_L_CREATE_FOLDER . "</b></td></tr>\r";
	print "  <tr><td colspan='2' align='left'><BR></td></tr>\r";
	print "  <tr>\r";
	print "    <td align='left'>" . _DMS_L_FOLDER_NAME  . "</td>\r";

	print '    <td align="left"><input type="text" name="txt_folder_name" size="40" maxlength="250" tabindex="'.$dms_tab_index++.'"></td>'."\r";
	print "  </tr>\r";

	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <td colspan='2' align='left'><input type=submit name='btn_submit' value='" . _DMS_L_SUBMIT . "' tabindex='".$dms_tab_index++."'>";
	print "                               <input type=button name='btn_cancel' value='" . _DMS_L_CANCEL . "' onclick='location=\"" .$dms_config['dms_url'] . "\";' tabindex='".$dms_tab_index++."'></td>\r";
	print "</table>\r";
	print "<input type='hidden' name='hdn_active_folder' value='".$active_folder."'>\r";
	print "</form>\r";

	print("<SCRIPT LANGUAGE='Javascript'>\r");
	print("  document.frm_folder_new.txt_folder_name.focus();");
	print("</SCRIPT>");
	}

?>
