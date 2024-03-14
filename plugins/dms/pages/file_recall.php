<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// file_recall.php

global $dmsdb, $dms_user_id, $dms_global;


// Determine which web page to return to.

$return_page = dms_get_var("return_page");
$obj_id = dms_get_var("obj_id");

if($return_page == FALSE || $obj_id == FALSE)
    {
    dms_redirect($dms_config['dms_url']);
    exit(0);
    }

$return_url = $dms_config['dms_url'].$dms_global["first_separator"]."dms_page=".$return_page."&obj_id=".dms_get_var("obj_id");




if (dms_get_var("hdn_file_recall") == "confirm")
	{
	$slct_recall_doc_ids = dms_get_var("slct_recall_doc_ids");

	$index = 0;
	foreach($slct_recall_doc_ids as $obj_id)
		{
		// Get the obj_id of the inbox
		$query = "SELECT obj_owner FROM ".$dmsdb->prefix('dms_objects')." WHERE obj_id='".$obj_id."'";
		$obj_owner = $dmsdb->query($query,"obj_owner");

		// Delete the link
		$query = "DELETE from ".$dmsdb->prefix('dms_objects')." WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);

		$query = "DELETE from ".$dmsdb->prefix('dms_object_perms')." WHERE ptr_obj_id='".$obj_id."'";
		$dmsdb->query($query);

		$query = "DELETE from ".$dmsdb->prefix('dms_routing_data')." WHERE obj_id='".$obj_id."'";
		$dmsdb->query($query);

		dms_set_inbox_status($obj_owner);
		}

	//dms_message("The document has been recalled.");

	dms_redirect($return_url);
	exit(0);
	}
else
	{
	$obj_id = dms_get_var("hdn_obj_id");
	if($obj_id == FALSE) $obj_id = dms_get_var("obj_id");

	// Permissions required to access this page:
	//  EDIT, OWNER
	$perms_level = dms_perms_level($obj_id);

	if ( ($perms_level != 3) && ($perms_level != 4) )
		{
		print("<SCRIPT LANGUAGE='Javascript'>\r");
		print("  location='".$dms_config['dms_url']."';");
		print("</SCRIPT>");
		end();
		}

	// Get file information
	$query  = "SELECT obj_name from ".$dmsdb->prefix("dms_objects")." ";
	$query .= "WHERE obj_id='".$obj_id."'";
	$doc_name = $dmsdb->query($query,'obj_name');

	print "  <table width='100%'>\r";
	print "  <form method='post' name='frm_recall_r_docs' action='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=file_recall'>\r";
	dms_display_header(2);

	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <tr><td colspan='2' align='left'><b>Recall Routed Document:</b></td></tr>\r";
	print "  <tr><td colspan='2'><BR></td></tr>\r";
	print "  <tr>\r";
	print "    <td colspan='2' align='left'>&nbsp;&nbsp;&nbsp;Document Name:&nbsp;&nbsp;&nbsp;";
	print "        ".$doc_name."</td>\r";
	print "  </tr>\r";
	print "  <tr><td colspan='2'><BR></td></tr>\r";

	// Get the object ID's and inbox(es) that the document has been routed to...
	$query  = "SELECT o.obj_id,o.obj_owner FROM ".$dmsdb->prefix("dms_objects")." AS o ";
	$query .= "INNER JOIN ".$dmsdb->prefix("dms_routing_data")." AS rd ON rd.obj_id = o.obj_id ";
	$query .= "WHERE ptr_obj_id = '".$obj_id."' AND obj_type = '".DOCLINK."' AND source_user_id = '".$dms_user_id."'";

	$routed_docs = $dmsdb->query($query);

	$index = 0;
	while($indiv_doc = $dmsdb->getarray($routed_docs))
		{
		$query = "SELECT obj_name FROM ".$dmsdb->prefix("dms_objects")." WHERE obj_id='".$indiv_doc['obj_owner']."'";

		$inbox_names[$index] = $dmsdb->query($query,"obj_name");

		$routed_doc_obj_id[$index] = $indiv_doc['obj_id'];

		$index++;
		//print "        <tr><td>&nbsp;&nbsp;&nbsp;".$inbox_name."</td></tr>\r";
		}

	if(isset($inbox_names)) asort($inbox_names);

	print "  <tr><td colspan='2'>\r";

	print "    &nbsp;&nbsp;&nbsp;Inbox(es):<BR>\r";
	print "    &nbsp;&nbsp;&nbsp;<select size='10' name='slct_recall_doc_ids[]' multiple>\r";

	foreach ($inbox_names as $index => $indiv_inbox_name)
		{
		print "        <option value='".$routed_doc_obj_id[$index]."'>".$indiv_inbox_name."</option>\r";
		}

	print "    </select>\r";
	print "  </td></tr>\r";

	print "  <tr><td colspan='2'><BR></td></tr>\r";

	print "  <td colspan='2' align='left'><input type='submit' name='btn_submit' value='"._DMS_L_RECALL."'>&nbsp;&nbsp;";
	print "                               <input type=button name='btn_cancel' value='" . _DMS_L_CANCEL . "' onclick='location=\"".$return_url."\";'></td>\r";
	print "</table>\r";
	print "<input type='hidden' name='hdn_file_recall' value='confirm'>\r";
	print "<input type='hidden' name='obj_id' value='".$obj_id."'>\r";
    print "<input type='hidden' name='return_page' value='".$return_page."'>\r";
	print "</form>\r";
	}
?>



