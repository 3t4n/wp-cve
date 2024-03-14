<?php
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //

// config_main.php
// Configuration Page

global $dmsdb, $dms_config, $dms_global;
$dms_admin_flag = $dms_global['dms_admin_flag'];

if(!$dms_admin_flag)
    {
    dms_redirect($dms_config['dms_url']);
    exit(0);
    }

$os_types = array(0=>"Unknown",1=>"Linux",2=>"Unix",3=>"Windows");
$text_field_size_big = 30;


//$hdn_update_form = dms_get_var("hdn_update_form");
if (dms_get_var("hdn_update_form") == "TRUE")
	{
	//  Checkin/Checkout/Versioning


    if(defined("DMS_PRO"))
        {
        //  Automated Folder Creation
        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_auto_folder_creation")."' WHERE name='auto_folder_creation'";
        $dmsdb->query($query);
        }




    if(defined("DMS_PRO"))
        {
        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_checkinout_enable")."' WHERE name='checkinout_enable'";
        $dmsdb->query($query);
        }

    if(defined("DMS_PRO"))
        {
        //  Comments
        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_comments_enable")."' WHERE name='comments_enable'";
        $dmsdb->query($query);
        }

    if(defined("DMS_PRO"))
        {
        //  Comments on Main Screen
        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_comments_main_screen")."' WHERE name='comments_main_screen'";
        $dmsdb->query($query);
        }


/*
	//  Deletion System
	$query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_purge_enable")."' WHERE name='purge_enable'";
	$dmsdb->query($query);

	$query = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("slct_purge_level")."' WHERE name='purge_level'";
	$dmsdb->query($query);

	$query = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("slct_purge_delay")."' WHERE name='purge_delay'";
	$dmsdb->query($query);

	//  Document Expiration System
	$query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_doc_expiration_enable")."' WHERE name='doc_expiration_enable'";
	$dmsdb->query($query);
*/
	//  Document Properties

	for ($index = 0; $index < 10; $index++)
	{
	switch ($index)
		{
		case 0:  $data = dms_get_var('txt_property_0_name');  break;
		case 1:  $data = dms_get_var('txt_property_1_name');  break;
		case 2:  $data = dms_get_var('txt_property_2_name');  break;
		case 3:  $data = dms_get_var('txt_property_3_name');  break;
		case 4:  $data = dms_get_var('txt_property_4_name');  break;
		case 5:  $data = dms_get_var('txt_property_5_name');  break;
		case 6:  $data = dms_get_var('txt_property_6_name');  break;
		case 7:  $data = dms_get_var('txt_property_7_name');  break;
		case 8:  $data = dms_get_var('txt_property_8_name');  break;
		case 9:  $data = dms_get_var('txt_property_9_name');  break;
		}

	$query =  "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".$data."' ";
	$query .= "WHERE name='property_".$index."_name'";
	$dmsdb->query($query);
	}

	//  Document Repository

	$doc_path = dms_get_var('txt_doc_path');
	$doc_path = trim($doc_path);
	$doc_path = rtrim($doc_path,"/");
	$doc_path = rtrim($doc_path,"\\");
	$doc_path = str_replace("\\","\\\\",$doc_path);

	$query = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".$doc_path."' WHERE name='doc_path'";
	$dmsdb->query($query);
/*
	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_max_file_sys_counter")."' ";
	$query .= "WHERE name='max_file_sys_counter'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_doc_name_sync")."' ";
	$query .= "WHERE name='doc_name_sync'";
	$dmsdb->query($query);
*/

/*
	//  Document Templates
	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var('txt_template_root_obj_id')."' ";
	$query .= "WHERE name='template_root_obj_id'";
	$dmsdb->query($query);
*/

/*
	//  E-mail Configuration
	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_document_email_enable")."' ";
	$query .= "WHERE name='document_email_enable'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_document_email_subject")."' ";
	$query .= "WHERE name='document_email_subject'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_document_email_from")."' ";
	$query .= "WHERE name='document_email_from'";
	$dmsdb->query($query);

*/
    if(defined("DMS_PRO"))
        {
        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_notify_enable")."' WHERE name='notify_enable'";
        $dmsdb->query($query);

        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_admin_only_manage_notify")."' WHERE name='admin_only_manage_notify'";
        $dmsdb->query($query);

        $query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_notify_email_subject")."' ";
        $query .= "WHERE name='notify_email_subject'";
        $dmsdb->query($query);

        $query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_notify_email_from")."' ";
        $query .= "WHERE name='notify_email_from'";
        $dmsdb->query($query);
        }
/*
	$query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_sub_email_enable")."' WHERE name='sub_email_enable'";
	$dmsdb->query($query);

	$query = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_sub_email_subject")."' WHERE name='sub_email_subject'";
	$dmsdb->query($query);

	$query = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_sub_email_from")."' WHERE name='sub_email_from'";
	$dmsdb->query($query);
*/

	//  Interface Settings

    if(defined("DMS_PRO"))
        {
        $query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var('txt_dms_title')."' ";
        $query .= "WHERE name='dms_title'";
        $dmsdb->query($query);
        }

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var('txt_doc_display_limit')."' ";
	$query .= "WHERE name='doc_display_limit'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var('txt_frame_width')."' ";
	$query .= "WHERE name='frame_width'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var('txt_frame_height')."' ";
	$query .= "WHERE name='frame_height'";
	$dmsdb->query($query);


/*
	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk('chk_disp_main_int_options')."' ";
	$query .= "WHERE name='disp_main_int_options'";
	$dmsdb->query($query);
*/

/*

	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk('chk_misc_text_disp_template')."' ";
	$query .= "WHERE name='misc_text_disp_template'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk('chk_misc_text_disp_lc_stage')."' ";
	$query .= "WHERE name='misc_text_disp_lc_stage'";
	$dmsdb->query($query);
*/

	//  Lifecycles
    if(defined("DMS_PRO"))
        {
        $query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_lifecycle_enable")."' ";
        $query .= "WHERE name='lifecycle_enable'";
        $dmsdb->query($query);
        }
/*
	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_lifecycle_del_previous")."' ";
	$query .= "WHERE name='lifecycle_del_previous'";
	$dmsdb->query($query);

	$query =  "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_lifecycle_name_preserve")."' ";
	$query .= "WHERE name='lifecycle_name_preserve'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_lifecycle_alpha_move")."' ";
	$query .= "WHERE name='lifecycle_alpha_move'";
	$dmsdb->query($query);
*/

	//  Permissions Configuration
/*
	$query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_prop_perms_enable")."' WHERE name='prop_perms_enable'";
	$dmsdb->query($query);
*/
	$query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_inherit_perms")."' WHERE name='inherit_perms'";
	$dmsdb->query($query);


    if(defined("DMS_PRO"))
        {
        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_admin_only_perms")."' WHERE name='admin_only_perms'";
        $dmsdb->query($query);
        }


    if(defined("DMS_PRO"))
        {
        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_everyone_perms")."' WHERE name='everyone_perms'";
        $dmsdb->query($query);
        }

	//  Routing  --  Enable both routing and auto inbox creation with one checkbox.

    if(defined("DMS_PRO"))
        {
        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_routing_enable")."' WHERE name='routing_enable'";
        $dmsdb->query($query);

        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_routing_enable")."' WHERE name='routing_auto_inbox'";
        $dmsdb->query($query);
        }

/*
	$query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_routing_auto_inbox")."' WHERE name='routing_auto_inbox'";
	$dmsdb->query($query);
*/


/*
	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_routing_email_enable")."' ";
	$query .= "WHERE name='routing_email_enable'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_routing_email_subject")."' ";
	$query .= "WHERE name='routing_email_subject'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_routing_email_from")."' ";
	$query .= "WHERE name='routing_email_from'";
	$dmsdb->query($query);
*/
	//  Search Configuration
	$query  = "UPDATE ".$dmsdb->prefix('dms_config')." ";
	$query .= "SET data = '".dms_get_var('txt_search_limit')."' ";
	$query .= "WHERE name='search_limit'";
	$dmsdb->query($query);
/*
	$query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_enable_fts")."' WHERE name='full_text_search'";
	$dmsdb->query($query);
*/
	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_search_results_per_page")."' ";
	$query .= "WHERE name='search_results_per_page'";
	$dmsdb->query($query);

    if(defined("DMS_PRO"))
        {
        $query  = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_search_summary_flag")."' ";
        $query .= "WHERE name='search_summary_flag'";
        $dmsdb->query($query);




        //  Thumbnails

        $query = "UPDATE ".$dmsdb->prefix('dms_config')." SET data = '".dms_get_var_chk("chk_global_thumbnail_enable")."' ";
        $query .= "WHERE name='global_thumbnail_enable'";
        $dmsdb->query($query);

        $query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_global_thumbnail_width")."' ";
        $query .= "WHERE name='global_thumbnail_width'";
        $dmsdb->query($query);
        }

/*
	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_search_summary_c_before")."' ";
	$query .= "WHERE name='search_summary_c_before'";
	$dmsdb->query($query);

	$query  = "UPDATE ".$dmsdb->prefix("dms_config")." SET data = '".dms_get_var("txt_search_summary_c_after")."' ";
	$query .= "WHERE name='search_summary_c_after'";
	$dmsdb->query($query);
*/
	dms_update_config_time_stamp();
	}

dms_get_config();

//	print "<form method='post' action='config_main.php'>\r";
	print "<form method='post' action='".$dms_config['dms_url'].$dms_global["first_separator"]."dms_page=config'>\r";

	print "<div ".$dms_config['class_content']." style='text-align: left' >\r";

	print "<b>"._DMS_L_CONFIG_TITLE."</b><BR><BR>\r";



    if(!defined("DMS_PRO"))
        {

	print "<BR><BR>\r";

    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

//        print "<BR>\r";
        print _DMS_L_CONFIG_UPGRADE_NOTICE_URL ." <a href=\"http://www.blitzenware.com\">Blitzenware</a><BR>";
        print "<BR>\r";
        }

    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";








	//  Automated Folder Creation
	dms_display_spaces(5);
	printf( _DMS_L_AUTOMATED_FOLDER_CREATION .":<BR><BR>\r");

	$checked = $dms_config['auto_folder_creation'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);

    print  _DMS_L_CONFIG_ENABLE .":  ";

    if(defined("DMS_PRO"))
        {
        print "<input type='checkbox' name='chk_auto_folder_creation' ".$checked.">\r";
        }
    else
        {
        print "&nbsp;&nbsp;". _DMS_L_CONFIG_UPGRADE_NOTICE;
        }

	$checked = $dms_config['auto_folder_creation'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";
/*
    if(defined("DMS_PRO"))
        {
        print "<BR>";
        dms_display_spaces(10);
        print "Display on Main Screen:  ";
        print "<input type='checkbox' name='chk_comments_main_screen' ".$checked."><BR>\r";
        }
*/
	print "<BR><BR>\r";

    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";















	//  Comments
	dms_display_spaces(5);
	printf( _DMS_L_CONFIG_COMMENTS .":<BR><BR>\r");

	$checked = $dms_config['comments_enable'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);

    print  _DMS_L_CONFIG_ENABLE .":  ";

    if(defined("DMS_PRO"))
        {
        print "<input type='checkbox' name='chk_comments_enable' ".$checked.">\r";
        }
    else
        {
        print "&nbsp;&nbsp;". _DMS_L_CONFIG_UPGRADE_NOTICE;
        }

	$checked = $dms_config['comments_main_screen'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

    if(defined("DMS_PRO"))
        {
        print "<BR>";
        dms_display_spaces(10);
        print "Display on Main Screen:  ";
        print "<input type='checkbox' name='chk_comments_main_screen' ".$checked."><BR>\r";
        }

	print "<BR><BR>\r";

    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

/*
	// Deletion System

	$purge_delay_value = array(0=>"No Delay",1 => 1,2 => 2, 3=>3,4=>4,5=>5,10=>10,20=>20,30=>30,60=>60,90=>90);
	$purge_level_value = array(FLAGGING=>"Retain Files and Audit",FILES=>"Delete Files and Audit",TOTAL=>"Delete Files and Data");

KEEP!!!!
	dms_display_spaces(5);
	print "Deletion System:<BR><BR>\r";

	$checked = $dms_config['purge_enable'];

	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Enable Permanent Document Deletion:  ";
	print "<input type='checkbox' name='chk_purge_enable' ".$checked.">\r";

	print "<BR>\r";
*/


/*

KEEP!!!!
	print "<BR>\r";

	dms_display_spaces(10);
	print "Type of Document Deletion:  ";

	print "<select name='slct_purge_level'>\r";

	foreach ($purge_level_value as $value=>$key)
		{
		$selected = "";
		if($value==$dms_config['purge_level']) $selected = " selected ";
		print "<option value='".$value."' ".$selected.">".$key."</option>\r";
		}

	print "</select>\r";
	print "<BR><BR><BR>\r";
*/


/*

KEEP!!!!

	//  Document Expiration System
	dms_display_spaces(5);
	printf("Document Expiration System:<BR><BR>\r");

	$checked = $dms_config['doc_expiration_enable'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Enable:  ";
	print "<input type='checkbox' name='chk_doc_expiration_enable' ".$checked.">\r";
	print "<BR><BR><BR>\r";
*/
	//  Document Properties

	dms_display_spaces(5);
	printf( _DMS_L_CONFIG_DOCUMENT_PROPERTIES .":<BR><BR>\r");

	for ($index = 0; $index < 10; $index++)
		{
		$query = 'SELECT data FROM '.$dmsdb->prefix("dms_config")." WHERE name='property_".$index."_name'";
		$result = $dmsdb->query($query,'data');

		dms_display_spaces(10);
		print _DMS_L_CONFIG_PROPERTY ." ".$index.":  ";
		print "<input type=text name='txt_property_".$index."_name' value='".$result."' size='".$text_field_size_big."' maxlength='250'><BR>\r";
		}

    print "<BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

	//  Document Repository

	dms_display_spaces(5);
	printf( _DMS_L_CONFIG_DOCUMENT_REPOSITORY .":<BR><BR>");

	dms_display_spaces(10);
	print _DMS_L_CONFIG_DOCUMENT_STORAGE_PATH .":  ";
//	if($dms_config['init_config_lock'] == "UNLOCKED")
		printf("<input type=text name='txt_doc_path' value='%s' size='%d' maxlength='250'>",$dms_config['doc_path'], $text_field_size_big);
/*
	else
		{
		print $dms_config['doc_path'];
		print "<input type='hidden' name='txt_doc_path' value='".$dms_config['doc_path']."'>";
		}
*/
	print "<BR>\r";

/*
KEEP!!!

	$checked = $dms_config['doc_name_sync'];

	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Synchronize File Names With Document Names:  ";
	print "<input type='checkbox' name='chk_doc_name_sync' ".$checked.">\r";

	print "<BR><BR><BR>\r";
*/

/*
KEEP!!!!!
	//  E-mail Configuration

    */

    print "<BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

    dms_display_spaces(5);
    printf( _DMS_L_CONFIG_EMAIL_CONFIGURATION .":<BR><BR>");

    if(defined("DMS_PRO"))
        {
/*
	dms_display_spaces(10);
	print "Document:<BR>";

	$checked = $dms_config['document_email_enable'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(15);
	print "Enable E-Mail:  ";
	print "<input type='checkbox' name='chk_document_email_enable' ".$checked."><BR>\r";

	dms_display_spaces(15);
	print "Sender E-mail Address:  ";
	printf("<input type=text name='txt_document_email_from' value='%s' size='60' maxlength='60'><BR>",$dms_config['document_email_from']);

	dms_display_spaces(15);
	print "Subject Line:  ";
	printf("<input type=text name='txt_document_email_subject' value='%s' size='60' maxlength='250'><BR>",$dms_config['document_email_subject']);
*/
        dms_display_spaces(10);
        print _DMS_L_CONFIG_FOLDER_SUBSCRIPTIONS .":<BR>";

        $checked = $dms_config['notify_enable'];
        if ($checked == '0') $checked = "";
        else $checked = " checked";

        dms_display_spaces(15);
        print _DMS_L_CONFIG_ENABLE .":  ";
        print "<input type='checkbox' name='chk_notify_enable' ".$checked."><BR>\r";


        $checked = $dms_config['admin_only_manage_notify'];
        if ($checked == '0') $checked = "";
        else $checked = " checked";

        dms_display_spaces(15);
        print _DMS_L_CONFIG_ADMIN_MANAGE_NOTIFY .":  ";
        print "<input type='checkbox' name='chk_admin_only_manage_notify' ".$checked."><BR>\r";


        dms_display_spaces(15);
        print _DMS_L_CONFIG_FOLDER_SUBSCRIPTIONS_SENDER_EMAIL . ":  ";
        printf("<input type=text name='txt_notify_email_from' value='%s' size='%d' maxlength='60'><BR>",$dms_config['notify_email_from'], $text_field_size_big);

        dms_display_spaces(15);
        print _DMS_L_CONFIG_FOLDER_SUBSCRIPTIONS_SENDER_SUBJECT .":  ";
        printf("<input type=text name='txt_notify_email_subject' value='%s' size='%d' maxlength='250'><BR>",$dms_config['notify_email_subject'], $text_field_size_big);

//       	printf("<BR><BR><BR>\r");
        }
    else
        {
        dms_display_spaces(10);
        print  _DMS_L_CONFIG_UPGRADE_NOTICE . "<BR>";
        }


/*

	dms_display_spaces(10);
	print "Document Subscriptions:<BR>";

	$checked = $dms_config['sub_email_enable'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(15);
	print "Enable:  ";
	print "<input type='checkbox' name='chk_sub_email_enable' ".$checked."><BR>\r";

	dms_display_spaces(15);
	print "Sender E-mail Address:  ";
	printf("<input type=text name='txt_sub_email_from' value='%s' size='60' maxlength='60'><BR>",$dms_config['sub_email_from']);

	dms_display_spaces(15);
	print "Subject Line:  ";
	printf("<input type=text name='txt_sub_email_subject' value='%s' size='60' maxlength='250'><BR>",$dms_config['sub_email_subject']);
	printf("<BR><BR><BR>\r");
*/

    print "<BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

	//  Interface Settings

	dms_display_spaces(5);
	printf( _DMS_L_CONFIG_INTERFACE_SETTINGS .":<BR><BR>\r");

	dms_display_spaces(10);
	print _DMS_L_CONFIG_INTERFACE_SETTINGS_PAGE_TITLE .":  ";

    if(defined("DMS_PRO"))
        {
        printf("<input type=text name='txt_dms_title' value='%s' size='%d'><BR>",$dms_config['dms_title'], $text_field_size_big);
        }
    else
        {
        printf("%s<BR>",$dms_config['dms_title']);
        }

	dms_display_spaces(10);
	printf(  _DMS_L_CONFIG_INTERFACE_SETTINGS_DOCS_PER_PAGE .":  ");
	printf("<input type=text name='txt_doc_display_limit' value='%s' size='4'><BR>",$dms_config['doc_display_limit']);

	dms_display_spaces(10);
	printf(_DMS_L_CONFIG_INTERFACE_SETTINGS_FRAME_WIDTH .":  ");
	printf("<input type=text name='txt_frame_width' value='%s' size='4'><BR>",$dms_config['frame_width']);

	dms_display_spaces(10);
	printf(_DMS_L_CONFIG_INTERFACE_SETTINGS_FRAME_HEIGHT .":  ");
	printf("<input type=text name='txt_frame_height' value='%s' size='4'><BR>",$dms_config['frame_height']);


//	print "<BR>\r";

/*
	$checked = $dms_config['misc_text_disp_template'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Display Document Template Name:  ";
	print "<input type='checkbox' name='chk_misc_text_disp_template' ".$checked.">\r";
	print "<BR>\r";
*/

/*
	$checked = $dms_config['misc_text_disp_lc_stage'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Display Lifecycle Stage:  ";
	print "<input type='checkbox' name='chk_misc_text_disp_lc_stage' ".$checked.">\r";
	print "<BR>\r";
*/
//	printf("<BR>");




//////////////////////////////////////////////////////


/*

	$checked = $dms_config['inherit_perms'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	printf( _DMS_L_CONFIG_PERMISSIONS_SYSTEM_INHERIT . ":  ");
	printf("<input type='checkbox' name='chk_inherit_perms' %s><BR>\r",$checked);

	$checked = $dms_config['everyone_perms'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	printf( _DMS_L_CONFIG_PERMISSIONS_SYSTEM_EVERYONE .":  ");

    if(defined("DMS_PRO"))
        {
        printf("<input type='checkbox' name='chk_everyone_perms' %s>\r",$checked);
        }
    else
        {
        print "&nbsp;&nbsp;" . _DMS_L_CONFIG_UPGRADE_NOTICE;
        }

    print "<BR><BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

*/




/////////////////////////////////////////////////////

//        printf("<input type='checkbox' name='chk_everyone_perms' %s>\r",$checked);
/*
        }
    else
        {
        print "&nbsp;&nbsp;" . _DMS_L_CONFIG_UPGRADE_NOTICE;
        }
*/

    print "<BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

    //  Lifecycles
    dms_display_spaces(5);
    printf("Lifecycles:<BR><BR>\r");


    if(defined("DMS_PRO"))
        {
        $checked = $dms_config['lifecycle_enable'];
        if ($checked == '0') $checked = "";
        else $checked = " checked";

        dms_display_spaces(10);
        print "Enable:  ";
        print "<input type='checkbox' name='chk_lifecycle_enable' ".$checked.">\r";

//	print "<BR>\r";
/*
    KEEP

	$checked = $dms_config['lifecycle_del_previous'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Keep Final Revision Only:  ";
	print "<input type='checkbox' name='chk_lifecycle_del_previous' ".$checked.">\r";
	print "<BR>\r";

	$checked = $dms_config['lifecycle_name_preserve'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Keep Lifecycle Stage Name:  ";
	print "<input type='checkbox' name='chk_lifecycle_name_preserve' ".$checked.">\r";
	print "<BR>\r";

	$checked = $dms_config['lifecycle_alpha_move'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Move documents into aphabetical sub-folders:  ";
	print "<input type='checkbox' name='chk_lifecycle_alpha_move' ".$checked.">\r";

*/


        }
    else
        {
        dms_display_spaces(10);
        print "&nbsp;&nbsp;" . _DMS_L_CONFIG_UPGRADE_NOTICE;
        }





    print "<BR><BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

	//  Permissions System
	dms_display_spaces(5);
	printf( _DMS_L_CONFIG_PERMISSIONS_SYSTEM .":<BR><BR>\r");
/*
	$checked = $dms_config['prop_perms_enable'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	printf("Enable Permissions Propagation Button:  ");
	printf("<input type='checkbox' name='chk_prop_perms_enable' %s>\r",$checked);

	printf("<BR>\r");
*/
	$checked = $dms_config['inherit_perms'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	printf( _DMS_L_CONFIG_PERMISSIONS_SYSTEM_INHERIT . ":  ");
	printf("<input type='checkbox' name='chk_inherit_perms' %s><BR>\r",$checked);



	$checked = $dms_config['admin_only_perms'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	printf( _DMS_L_CONFIG_ADMIN_CHANGE_PERMS .":  ");
    //printf("Only administrators can change permissions" . ":  ");

    if(defined("DMS_PRO"))
        {
        printf("<input type='checkbox' name='chk_admin_only_perms' %s><BR>\r",$checked);
        }
    else
        {
        print "&nbsp;&nbsp;" . _DMS_L_CONFIG_UPGRADE_NOTICE . "<BR>";
        }



	$checked = $dms_config['everyone_perms'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	printf( _DMS_L_CONFIG_PERMISSIONS_SYSTEM_EVERYONE .":  ");

    if(defined("DMS_PRO"))
        {
        printf("<input type='checkbox' name='chk_everyone_perms' %s><BR>\r",$checked);
        }
    else
        {
        print "&nbsp;&nbsp;" . _DMS_L_CONFIG_UPGRADE_NOTICE . "<BR>";
        }

    print "<BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

	//  Routing
	dms_display_spaces(5);
	print _DMS_L_CONFIG_ROUTING .":<BR><BR>";

	$checked = $dms_config['routing_enable'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print _DMS_L_CONFIG_ENABLE .":  ";

    if(defined("DMS_PRO"))
        {
        print "<input type='checkbox' name='chk_routing_enable' ".$checked.">\r";
        }
    else
        {
        print "&nbsp;&nbsp;" . _DMS_L_CONFIG_UPGRADE_NOTICE;
        }

/*
	$checked = $dms_config['routing_auto_inbox'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Enable Automatic Inbox Creation:  ";
	print "<input type='checkbox' name='chk_routing_auto_inbox' ".$checked."><BR>\r";
*/

/*
	$checked = $dms_config['routing_email_enable'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print "Enable E-Mail:  ";
	print "<input type='checkbox' name='chk_routing_email_enable' ".$checked."><BR>\r";

	dms_display_spaces(10);
	print "Sender E-mail Address:  ";
	printf("<input type=text name='txt_routing_email_from' value='%s' size='60' maxlength='60'><BR>",$dms_config['routing_email_from']);

	dms_display_spaces(10);
	print "Subject Line:  ";
	printf("<input type=text name='txt_routing_email_subject' value='%s' size='60' maxlength='250'><BR>",$dms_config['routing_email_subject']);
*/

    print "<BR><BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

	//  Search Configuration

	dms_display_spaces(5);
	print _DMS_L_CONFIG_SEARCH_CONFIGURATION .":<BR><BR>\r";

	dms_display_spaces(10);
	print _DMS_L_CONFIG_SEARCH_LIMIT .":  ";
	printf("<input type=text name='txt_search_limit' value='%s' size='5' maxlength='5'><BR>\r",$dms_config['search_limit']);

	dms_display_spaces(10);
	print _DMS_L_CONFIG_DOCUMENTS_PER_PAGE .":  ";
	printf("<input type=text name='txt_search_results_per_page' value='%s' size='5' maxlength='5'><BR><BR>\r",$dms_config['search_results_per_page']);
/*
	dms_display_spaces(10);
	print "Full Text Search:<BR>\r";

	$checked = $dms_config['full_text_search'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(15);
	print "Enable:  ";
	print "<input type='checkbox' name='chk_enable_fts' ".$checked."><BR>\r";

	$checked = $dms_config['full_text_search_cdo'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(15);
	print "Search Current Document Versions Only:  ";
	print "<input type='checkbox' name='chk_full_text_search_cdo' ".$checked."><BR>\r";

	dms_display_spaces(15);
	print "SWISH-E path:  ";
	print "<input type=text name='txt_swishe_path' value='".$dms_config['swish-e_path']."' size='60' maxlength='250'>\r";
	print "<BR>\r";

	if(strlen($dms_config['doc_path']) > 2)
		{
		printf("<BR>\r");
		dms_display_spaces(15);
		print "<input type='button' value='Write Configuration Files' onclick='location=\"config_write_swishe_config.php\";'>\r";
		}

	print "<BR><BR>";
*/
	dms_display_spaces(10);
	print _DMS_L_CONFIG_SEARCH_SUMMARIES.":<BR>\r";

	$checked = $dms_config['search_summary_flag'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(15);
	print _DMS_L_CONFIG_ENABLE .":  ";

    if(defined("DMS_PRO"))
        {
        print "<input type='checkbox' name='chk_search_summary_flag' ".$checked.">\r";
        }
    else
        {
        print "&nbsp;&nbsp;" . _DMS_L_CONFIG_UPGRADE_NOTICE;
        }

/*
	dms_display_spaces(15);
	print "Characters Before Search Term:  ";
	printf("<input type=text name='txt_search_summary_c_before' value='%s' size='5' maxlength='5'><BR>\r",$dms_config['search_summary_c_before']);

	dms_display_spaces(15);
	print "Characters After Search Term:  ";
	printf("<input type=text name='txt_search_summary_c_after' value='%s' size='5' maxlength='5'><BR>\r",$dms_config['search_summary_c_after']);
*/



    print "<BR><BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

	//  Thumbnails
	dms_display_spaces(5);
	printf( _DMS_L_CONFIG_THUMBNAILS .":<BR><BR>\r");

    dms_display_spaces(10);
	print _DMS_L_CONFIG_THUMBNAILS_DISPLAY_GLOBALLY .":  ";

    if(defined("DMS_PRO"))
        {
      	$checked = $dms_config['global_thumbnail_enable'];
        if ($checked == '0') $checked = "";
        else $checked = " checked";

        print "<input type='checkbox' name='chk_global_thumbnail_enable' ".$checked."><BR>\r";

        dms_display_spaces(10);
        print _DMS_L_CONFIG_THUMBNAILS_WIDTH .":  ";
        printf("<input type=text name='txt_global_thumbnail_width' value='%s' size='5' maxlength='5'><BR>\r",$dms_config['global_thumbnail_width']);
        }
    else
        {
        print "&nbsp;&nbsp;". _DMS_L_CONFIG_UPGRADE_NOTICE ."<BR>";
        }

    print "<BR>";
    print "<table><tr><td background='".DMS_ICONS."/custom/line.png' nowrap></td></tr></table>\r";

	//  Checkin/Checkout/Versioning
	dms_display_spaces(5);
	printf( _DMS_L_CONFIG_VERSION_CONTROL .":<BR><BR>\r");

	$checked = $dms_config['checkinout_enable'];
	if ($checked == '0') $checked = "";
	else $checked = " checked";

	dms_display_spaces(10);
	print _DMS_L_CONFIG_ENABLE .":  ";

    if(defined("DMS_PRO"))
        {
        print "<input type='checkbox' name='chk_checkinout_enable' ".$checked.">\r";
        }
    else
        {
        print "&nbsp;&nbsp;". _DMS_L_CONFIG_UPGRADE_NOTICE ."";
        }



    print "<BR><BR>";






	//  Update and Exit Buttons
	print "<BR><BR><BR>\r";
	print "<input type='hidden' name='hdn_update_form' value='TRUE'>\r";
	print "<input type='submit' value='"._DMS_L_CONFIG_UPDATE."'>&nbsp;&nbsp;\r";
	print "<input type='button' value='"._DMS_L_EXIT."' onclick='location=\"".$dms_config['dms_url']."\";'>\r";
	print "</form>";

	print "</div>";
?>
