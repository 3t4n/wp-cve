<?php

/*------------------------------------------------------------------------------------*/
/*  Option Control Panel
/*  require_once enoty-settings.php
/*------------------------------------------------------------------------------------*/

// VARIABLES

function enoty_get_settings_opt() {
	
	$enshort = "easynotify";
	
	// Set the Options Array
	return array (
	 
	array( "name" => ENOTIFY_NAME." Options",
		"type" => "title"),
	
	array( "name" => "Default Notify",
		"type" => "section"),
	array( "type" => "open"),
	
	array( "name" => "Select Default Notify",
		"desc" => "Select your default Notify. Default : Disabled",
		"id" => $enshort."_defaultnotify",
		"type" => "defaultnotify",
		"std" => "disabled"),	
		
	array( "name" => "Show on Home/Frontpage",
		"desc" => "If ON, your default notify will appear on your homepage or frontpage.",
		"id" => $enshort."_swhome",
		"type" => "checkbox",
		"std" => "1"),	
		
	array( "name" => "Show on Page",
		"desc" => "If ON, your default notify will appear on your Page.",
		"id" => $enshort."_swpage",
		"type" => "checkbox",
		"std" => "1"),	
		
	array( "name" => "Show on Post",
		"desc" => "If ON, your default notify will appear on your Post.",
		"id" => $enshort."_swpost",
		"type" => "checkbox",
		"std" => "1"),
		
	array( "name" => "Show on Categories/Archive",
		"desc" => "If ON, your default notify will appear on your Categories/Archive page.",
		"id" => $enshort."_swcatarch",
		"type" => "checkbox",
		"std" => "1"),				
		
	array( "name" => "Disable for logged users",
		"desc" => "Enable or temporarily disable the DEFAULT Notify for logged users.",
		"id" => $enshort."_disen_loggedusr",
		"type" => "checkbox",
		"std" => "0"),	
		
	array( "type" => "close"),
	
	array( "name" => "Mailing List Manager &nbsp;&nbsp;<span style='font-style:italic;padding:2px 8px 2px 8px;background-color: #E74C3C; border-radius:9px;margin-left:7px;color:#fff;font-size:11px;'>PRO Version Only</span>",
		"type" => "section",
		"groupfield" => "mailmanager-fields"),
	array( "type" => "open"),
	
	// Generate Mailing List Manager	
	array( "name" => "Mailing List Manager",
		"desc" => "This option allows you to select what Mailing List Manager that you use.",
		"id" => $enshort."_mailman",
		"group" => "mailmanager-selector",
		"type" => "mailmanager",
		"std" => "selectone",	
		"options" => array (
					"selectone-"=> "Select One",
					"aweber"=> "Aweber",
					"mailchimp"=> "MailChimp",
					"getresponse"=> "Getresponse",
					"campaignmonitor"=> "Campaign Monitor",
					"icontact"=> "iContact",
					"madmimi"=> "Mad Mimi",
					"constantcontact"=> "Constant Contact",
					"emailnotify"=> "Email Notification",
					),
			),
	
	
	// Aweber
	array( "name" => "Aweber Connect",
		"desc" => "Step 1. <a href=\"https://auth.aweber.com/1.0/oauth/authorize_app/dadd4327\" target=\"_blank\">Click here to generate your authorization code</a><br />Step 2. Paste your authorization code to field on left :<br />Step 3. Click Connect Button ",
		"id" => $enshort."_aweber_auth_code",
		"group" => "aweber-field",
		"type" => "textareaauth",
		"std" => ""),	
		
	array( "name" => "Default Mailing List",
		"desc" => "Please click the Grab Lists button to receive your current Mailing List, after that you can choose one.",
		"id" => $enshort."_list_aweber",
		"type" => "selectmmlist",
		"options" => array (
					'none'=> 'None'
					),
		"group" => "aweber-field",
		"std" => "none"),
		
	// MailChimp	
	array( "name" => "MailChimp API Key",
		"desc" => "Please define MailChimp API Key, Learn here to <a href=\"http://kb.mailchimp.com/article/where-can-i-find-my-api-key\" target=\"_blank\">find API Key</a>",
		"id" => $enshort."_key_mailchimp",
		"group" => "mailchimp-field",
		"type" => "text",
		"std" => ""),	
		
	array( "name" => "Default Mailing List",
		"desc" => "Please click the Grab Lists button to receive your current Mailing List, after that you can choose one.",
		"id" => $enshort."_list_mailchimp",
		"type" => "selectmmlist",
		"options" => array (
					'none'=> 'None'
					),
		"group" => "mailchimp-field",
		"std" => "none"),	
		
	array( "name" => "Double Opt-in?",
		"desc" => "If ON, your new subscriber not automatically registered in the list until the link contained in confirmation email from MailChimp is clicked.",
		"id" => $enshort."_mailchimp_double_optin",
		"group" => "mailchimp-field",
		"type" => "checkbox",
		"std" => "0"),
		
		
	// Getresponse	
	array( "name" => "Getresponse API Key",
		"desc" => "Please define Getresponse API Key, Learn here to <a href=\"http://support.getresponse.com/faq/where-i-find-api-key\" target=\"_blank\">find API Key</a>",
		"id" => $enshort."_key_getresponse",
		"group" => "getresponse-field",
		"type" => "text",
		"std" => ""),
			
	array( "name" => "Default Mailing List",
		"desc" => "Please click the Grab Lists button to receive your current Mailing List, after that you can choose one.",
		"id" => $enshort."_list_getresponse",
		"type" => "selectmmlist",
		"options" => array (
					'none'=> 'None'
					),
		"group" => "getresponse-field",
		"std" => "none"),	
	
	
	// CampaignMonitor
	array( "name" => "Campaign Monitor Client ID",
		"desc" => "Please define Campaign Monitor Client ID, Learn here to <a href=\"http://www.campaignmonitor.com/api/getting-started/#clientid\" target=\"_blank\">find Client ID</a>",
		"id" => $enshort."_cid_campaignmonitor",
		"group" => "campaignmonitor-field",
		"type" => "text",
		"std" => ""),
		
	array( "name" => "Campaign Monitor API Key",
		"desc" => "Please define Getresponse API Key, Learn here to <a href=\"http://help.campaignmonitor.com/topic.aspx?t=206\" target=\"_blank\">find API Key</a>",
		"id" => $enshort."_key_campaignmonitor",
		"group" => "campaignmonitor-field",
		"type" => "text",
		"std" => ""),
		
	array( "name" => "Default Mailing List",
		"desc" => "Please click the Grab Lists button to receive your current Mailing List, after that you can choose one.",
		"id" => $enshort."_list_campaignmonitor",
		"type" => "selectmmlist",
		"options" => array (
					'none'=> 'None'
					),
		"group" => "campaignmonitor-field",
		"std" => "none"),
		
		
	// iContact
	array( "name" => "iContact Username ( email )",
		"desc" => "Please define iContact Username or your iContact email",
		"id" => $enshort."_username_icontact",
		"group" => "icontact-field",
		"type" => "text",
		"std" => ""),
		
	array( "name" => "iContact App ID",
		"desc" => "Please define your iContact App ID or you can <a href=\"https://app.icontact.com/icp/core/registerapp\" target=\"_blank\">create new iContact App</a> here",
		"id" => $enshort."_key_icontact",
		"group" => "icontact-field",
		"type" => "text",
		"std" => ""),
		
	array( "name" => "iContact App Password",
		"desc" => "Please define your iContact App password.",
		"id" => $enshort."_pass_icontact",
		"group" => "icontact-field",
		"type" => "textpass",
		"std" => ""),
		
	array( "name" => "Default Mailing List",
		"desc" => "Please click the Grab Lists button to receive your current Mailing List, after that you can choose one.",
		"id" => $enshort."_list_icontact",
		"type" => "selectmmlist",
		"options" => array (
					'none'=> 'None'
					),
		"group" => "icontact-field",
		"std" => "none"),
	
		
	// Mad Mimi
	array( "name" => "Mad Mimi Username ( email )",
		"desc" => "Please define Mad Mimi Username or your Mad Mimi email",
		"id" => $enshort."_username_madmimi",
		"group" => "madmimi-field",
		"type" => "text",
		"std" => ""),
		
	array( "name" => "Mad Mimi API Key",
		"desc" => "Please define Mad Mimi API Key, you can go here to <a href=\"https://madmimi.com/user/edit?account_info_tabs=account_info_personal\" target=\"_blank\">find your API Key</a>",
		"id" => $enshort."_key_madmimi",
		"group" => "madmimi-field",
		"type" => "text",
		"std" => ""),
		
	array( "name" => "Default Mailing List",
		"desc" => "Please click the Grab Lists button to receive your current Mailing List, after that you can choose one.",
		"id" => $enshort."_list_madmimi",
		"type" => "selectmmlist",
		"options" => array (
					'none'=> 'None'
					),
		"group" => "madmimi-field",
		"std" => "none"),	
		
	
	// Constant Contact
	array( "name" => "Constant Contact Username",
		"desc" => "Please define Constant Contact Username.",
		"id" => $enshort."_username_constantcontact",
		"group" => "constantcontact-field",
		"type" => "text",
		"std" => ""),
			
	array( "name" => "Constant Contact Password",
		"desc" => "Please define your Constant Contact App password.",
		"id" => $enshort."_pass_constantcontact",
		"group" => "constantcontact-field",
		"type" => "textpass",
		"std" => ""),
		
	array( "name" => "Default Mailing List",
		"desc" => "Please click the Grab Lists button to receive your current Mailing List, after that you can choose one.",
		"id" => $enshort."_list_constantcontact",
		"type" => "selectmmlist",
		"options" => array (
					'none'=> 'None'
					),
		"group" => "constantcontact-field",
		"std" => "none"),
		
	// Email Notify
	array( "name" => "Email Receipt",
		"desc" => "Please define your Email Receipt.",
		"id" => $enshort."_emailreceipt",
		"group" => "emailnotify-field",
		"type" => "text",
		"std" => ""),
	
	array( "type" => "close"),
	array( "name" => "Global Styling Options &nbsp;&nbsp;<span style='font-style:italic;padding:2px 8px 2px 8px;background-color: #E74C3C; border-radius:9px;margin-left:7px;color:#fff;font-size:11px;'>PRO Version Only</span>",
		"type" => "section"),
	array( "type" => "open"),
		
	array( "name" => "Custom CSS",
		"desc" => "Want to add any custom CSS code? Put in here, and the rest is taken care of. This overrides any other stylesheets.",
		"id" => $enshort."_custom_css",
		"type" => "textarea",
		"std" => ""),
		
	array( "type" => "close"),
	array( "name" => "Miscellaneous",
		"type" => "section"),
	array( "type" => "open"),
	
	array( "name" => "Enable Plugin",
		"desc" => "Enable or temporarily disable this plugin.",
		"id" => $enshort."_disen_plug",
		"type" => "checkbox",
		"std" => "1"),
		
	array( "name" => "Auto Update Plugin",
		"desc" => "Enable or temporarily disable auto update plugin.",
		"id" => $enshort."_disen_autoupdt",
		"type" => "checkbox",
		"std" => "1"),
		
	array( "name" => "Upgrade Notification",
		"desc" => "Enable/Disable upgrade notifications.",
		"id" => $enshort."_disen_admnotify",
		"type" => "checkbox",
		"std" => "1"),	
	
	array( "name" => "Keep data when uninstall",
		"desc" => "Enable this option to keep all plugin data and settings before you uninstall for update this plugin.",
		"id" => $enshort."_disen_databk",
		"type" => "checkbox",
		"std" => "1"),
	
	array( "name" => "Wordpress Info",
		"desc" => "You can provide this wordpress information to our support staff when you face any issue with this plugin.",
		"id" => $enshort."_plugin_wpinfo",
		"type" => "textareainfo",
		"std" => ""),
		
	array( "type" => "close")
		
	);

}


/*------------------------------------------------------------------------------------*/
/*  RESTORE DEFAULT SETTINGS
/*------------------------------------------------------------------------------------*/

function easynotify_restore_to_default($cmd) {
	
	if ( $cmd == 'reset' ) {
		
		delete_option( 'easynotify_opt' );
		
				$enshort = "easynotify";
		
				$arr = array(
				$enshort.'_deff_init' => '1',																				
				$enshort.'_disen_databk' => '1',			
				$enshort.'_disen_plug' => '1',
				$enshort.'_disen_upchk' => '1',	
				$enshort.'_disen_autoupdt' => '1',
				$enshort.'_disen_loggedusr' => '0',
				$enshort.'_disen_admnotify' => '1',
				$enshort.'_defaultnotify' => 'disabled',				
				$enshort.'_swhome' => '1',				
				$enshort.'_swpage' => '1',
				$enshort.'_swpost' => '1',
				$enshort.'_swcatarch' => '1',												
													
				);
				update_option('easynotify_opt', $arr);
				return;
	}
}



/*------------------------------------------------------------------------------------*/
/*  1ST CONFIGURATION
/*------------------------------------------------------------------------------------*/

function easynotify_1st_config() {

				$thshort = "easynotify";
				
				$arr = array(
				$thshort.'_deff_init' => '1',																				
				$thshort.'_disen_databk' => '1',			
				$thshort.'_disen_plug' => '1',
				$thshort.'_disen_upchk' => '1',	
				$thshort.'_disen_autoupdt' => '1',
				$thshort.'_disen_loggedusr' => '0',
				$thshort.'_disen_admnotify' => '1',
				$thshort.'_defaultnotify' => 'disabled',				
				$thshort.'_swhome' => '1',				
				$thshort.'_swpage' => '1',
				$thshort.'_swpost' => '1',
				$thshort.'_swcatarch' => '1',	
				
				);
				update_option( 'easynotify_opt', $arr, '', 'yes' );
				return;
}


?>