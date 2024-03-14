<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// group_editor.php


$group_select_width = " style='width: 60mm;' ";


global $dms_config, $dms_admin_flag, $dms_users, $dms_groups, $dms_global;

if ( $dms_admin_flag!=1 )
	{
	dms_redirect($dms_config['dms_url']);
	exit(0);
	}

$selected_group_id = dms_get_var("hdn_group_id");
if($selected_group_id == FALSE) $selected_group_id = 0;

$function = dms_get_var("hdn_function");
if($function == FALSE) $function = "NONE";

switch($function)
    {
    //  Add a new group
    case "ADD":

//        if($selected_group_id == 999999) // Group 999999 is the magic number for a new group.
//            {
            //  Create the group and get the group id.
            $selected_group_id = $dms_groups->grp_create(dms_get_var("txt_new_group_name"),"");
/*
            //  Populate the group
            $index = 0;
            $slct_group_list = dms_get_var("slct_group_list");

            while(isset($slct_group_list[$index]))
                {
                $dms_groups->usr_add($selected_group_id,$slct_group_list[$index]);

                $index++;
                }
//            }
*/
        break;


    case "RENAME":

        if($selected_group_id != 0)
            {
            $dms_groups->grp_rename($selected_group_id,dms_get_var("txt_rename_group_name"));
            }

        break;

    //  Select a different group
    case "SELECT":
       break;

    //  Update the selected group
    case "UPDATE":

        $dms_groups->usr_delete_all($selected_group_id);

        $index = 0;

        $slct_group_list = dms_get_var("slct_group_list");

        while(isset($slct_group_list[$index]))
            {
            $dms_groups->usr_add($selected_group_id,$slct_group_list[$index]);
            $index++;
            }

        break;

    //  Default
     default:
    }


print "<SCRIPT LANGUAGE='Javascript'>\r";

print "  function select_group()\r";
print "    {\r";
print "    if( (document.frm_group_editor.slct_group.value > 0) && (document.frm_group_editor.slct_group.value != 999999) )\r";
print "      {\r";
print "      document.frm_group_editor.hdn_group_id.value=document.frm_group_editor.slct_group.value;\r";
print "      document.frm_group_editor.hdn_function.value='SELECT';\r";
print "      document.frm_group_editor.submit();\r";
print "      }\r";
print "    }\r";

print "  function add_group()\r";
print "    {\r";
//print "    if( (document.frm_group_editor.slct_group.value == 999999) && (document.frm_group_editor.txt_new_group_name.value.length > 0) )\r";
print "    if( document.frm_group_editor.txt_new_group_name.value.length > 0 )\r";
print "      {\r";
print "      document.frm_group_editor.hdn_function.value='ADD';\r";
print "      document.frm_group_editor.hdn_group_id.value=document.frm_group_editor.slct_group.value;\r";
print "      document.frm_group_editor.submit();\r";
print "      }\r";
print "    }\r";


print "  function rename_group()\r";
print "    {\r";
//print "    if( (document.frm_group_editor.slct_group.value == 999999) && (document.frm_group_editor.txt_new_group_name.value.length > 0) )\r";
print "    if( document.frm_group_editor.txt_rename_group_name.value.length > 0 )\r";
print "      {\r";
print "      document.frm_group_editor.hdn_function.value='RENAME';\r";
print "      document.frm_group_editor.hdn_group_id.value=document.frm_group_editor.slct_group.value;\r";
print "      document.frm_group_editor.submit();\r";
print "      }\r";
print "    }\r";



print "  function update_group()\r";
print "    {\r";
print "    var index;\r";
print "    for ( index = 0; index < document.frm_group_editor.elements['slct_group_list[]'].length; index++)\r";
print "      {\r";
print "      document.frm_group_editor.elements['slct_group_list[]'].options[index].selected = 'TRUE';\r";
print "      }\r";
print "    for ( index = 0; index < document.frm_group_editor.elements['slct_group_list[]'].length; index++)\r";
print "      {\r";
print "      document.frm_group_editor.elements['slct_group_list[]'].options[index].selected = 'TRUE';\r";
print "      }\r";

print "    document.frm_group_editor.hdn_function.value = 'UPDATE';\r";
print "    document.frm_group_editor.submit();\r";
print "    }\r";

print "  function add_user()\r";
print "    {\r";
print "    var index, item, new_flag;\r";
print "    new_flag = \"TRUE\";\r";
print "    item = document.frm_group_editor.slct_user_list.options[document.frm_group_editor.slct_user_list.selectedIndex].text;\r";
print "    value = document.frm_group_editor.slct_user_list.options[document.frm_group_editor.slct_user_list.selectedIndex].value;\r";
print "    for ( index = 0; index < document.frm_group_editor.elements['slct_group_list[]'].length; index++)\r";
print "      {\r";
print "      if (item == document.frm_group_editor.elements['slct_group_list[]'].options[index].text) new_flag = \"FALSE\";\r";
print "      }\r";
print "    if (new_flag == \"TRUE\")\r";
print "     document.frm_group_editor.elements['slct_group_list[]'].options[document.frm_group_editor.elements['slct_group_list[]'].length]\r";
print "      = new Option (item,value);\r";
print "    }\r";

print "  function remove_user()\r";
print "    {\r";
print "    if (document.frm_group_editor.elements['slct_group_list[]'].selectedIndex >= 0)\r";
print "     document.frm_group_editor.elements['slct_group_list[]'].options[document.frm_group_editor.elements['slct_group_list[]'].selectedIndex] = null;\r";
print "    }\r";

print "</SCRIPT>\r";



print "<table width='100%' border='0'>\r";
dms_display_header();

print "  <tr><td colspan='2'><BR></td></tr>\r";
print "  <tr><td colspan='2' align='left' ".$dms_config['class_subheader']."><b>"._DMS_L_GROUP_EDITOR."</b></td></tr>\r";
print "  <tr><td colspan='2'><BR></td></tr>\r";

// Get a list of all the groups
$group_list = array();
$group_list = $dms_groups->grp_list_all();
asort($group_list);
reset($group_list);

//  If there are any groups and the $selected_group_id is 0, set the $selected_group_id to the $g_id of the first group.
if($selected_group_id == 0)
    {
    foreach($group_list as $g_id => $g_name)
        {
        $selected_group_id = $g_id;
        break;
        }
    }

//print "  <form name='frm_group_editor' action='".$dms_config['dms_url']."&dms_page=group_editor' method='post'>\r";
print "  <form name='frm_group_editor' action='".$dms_config['dms_url'].$dms_global['first_separator']."dms_page=group_editor' method='post'>\r";


print "                      <input type='hidden' name='hdn_function' value='NONE'>";
print "                      <input type='hidden' name='hdn_group_id' value='".$selected_group_id."'>";


print "  <tr>\r";
print "    <td align='left' valign='top' width='50%'>\r";
print "      "._DMS_L_GROUP."&nbsp;&nbsp;&nbsp;";
print "      <select name='slct_group' onchange=select_group();>\r";

//print "        <option value='999999'>[New]</option>\r";

foreach($group_list as $g_id => $g_name)
	{
	print "        <option value='".$g_id."'";
	if($selected_group_id == 0) $selected_group_id = $g_id;
	if($g_id == $selected_group_id) print " selected";
	print ">".$g_name."</option>\r";
	}

print "      </select>\r";
print "    </td>\r";

print "    <td align='right' width='50%' valign='top'>\r";
print "      "._DMS_L_GROUP_NEW_GROUP_NAME."  ";
print "      <input type='text' name='txt_new_group_name' size='20' maxlength='48' tabindex='100'>\r";
print "      <input type='button' name='btn_add' value='"._DMS_L_ADD."' onclick=add_group();>";


print "<BR><BR>";

print "      "._DMS_L_GROUP_CHANGE_NAME." ";

print "      <input type='text' name='txt_rename_group_name' size='20' maxlength='48' tabindex='100'>\r";
print "      <input type='button' name='btn_rename' value='"._DMS_L_GROUP_RENAME."' onclick=rename_group();>";


print "    </td>\r";

print "  </tr>\r";

print "  <tr><td colspan='2'><table>\r";

// Get a list of users in Registered Users
$user_list = array();
$user_list = $dms_groups->usr_list_all();

// Sort $user_list alphabetically
asort($user_list);
reset($user_list);

print "  <tr><td colspan='4'><BR></td></tr>\r";

print "  <tr>\r";
// Display list of users based upon the group selected in the drop-down box below.
print "    <td style='vertical-align: top;' align='left'>\r";
print "      "._DMS_L_GROUP_ALL_USERS."<BR><BR>&nbsp;&nbsp;&nbsp;";
print "      <select name='slct_user_list' size='10' ".$group_select_width.">\r";

foreach ($user_list as $u_id => $u_name)
	{
	print "        <option value='".$u_id."'>".$u_name."</option>\r";
	}

print "      </select>\r";
print "    </td>\r";

print "    <td align='center' width='25%' style='vertical-align: middle;'>\r";
print "      <input type='button' name='btn_add_user' value='" . _DMS_L_ADD . "&nbsp;&gt;&gt;' onclick='add_user();'> <BR><BR><BR>\r";
print "      <input type='button' name='btn_remove_user' value='&lt;&lt;&nbsp;" . _DMS_L_REMOVE . "' onclick='remove_user();'>\r";
print "    </td>\r";

$users_in_group = array();

// Display list of users in the group
if($selected_group_id > 0)
	{
	$users_in_group = $dms_groups->usr_list($selected_group_id);

	// Sort $user_list alphabetically
	asort($users_in_group);
	reset($users_in_group);
	}

print "    <td style='vertical-align: top;' align='left'>\r";
print "      "._DMS_L_GROUP_USERS_IN_GROUP."<BR><BR><BR>\r";
print "      <select name='slct_group_list[]' size='10' multiple ".$group_select_width.">\r";


if(count($users_in_group) > 0)
    {
    foreach ($users_in_group as $u_id => $u_name)
        {
        print "        <option value='".$u_id."'>".$u_name."</option>\r";
        }
    }

print "      </select>\r";
print "    </td>\r";
print "    <td width='100%'></td>\r";
print "  </tr>\r";

print "  </table></td></tr>\r";

print "  <tr><td colspan='2'><BR></td></tr>\r";
print "  <tr><td colspan='2' align='left'>";

print "                      <input type='button' name='btn_update' value='"._DMS_L_UPDATE."' onclick='update_group();'>";
print "                      &nbsp;&nbsp;";
//print "                      <input type=button name='btn_cancel' value='Exit' onclick='location=\"" . $dms_config['dms_url'] . "&dms_page=admin\";'></td></tr>\r";
print "                      <input type='button' name='btn_cancel' value='"._DMS_L_EXIT."' onclick='location=\"" . $dms_config['dms_url'] . "\";'></td></tr>\r";
// print "                      <input type='hidden' name='hdn_function' value='NONE'>";
// print "                      <input type='hidden' name='hdn_group_id' value='".$selected_group_id."'>";

print "</form>\r";


print "  </table>\r";
?>
