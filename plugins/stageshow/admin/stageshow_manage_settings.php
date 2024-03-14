<?php
/* 
Description: Code for Managing StageShow Settings
 
Copyright 2020 Malcolm Shergold

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

include STAGESHOW_INCLUDE_PATH.'stageshowlib_gateway_settings.php';

if (!class_exists('StageShowSettingsAdminListClass')) 
{
	define('STAGESHOW_ORGANISATIONID_TEXTLEN',60);
	define('STAGESHOW_EMPTYBOXOFFICEMSG_TEXTLEN',60);
		
	define('STAGESHOW_ORGANISATIONID_EDITLEN',60);
	define('STAGESHOW_EMPTYBOXOFFICEMSG_EDITLEN',60);
	
	class StageShowSettingsAdminListClass extends GatewaySettingsAdminListClass // Define class
	{		
		function __construct($env, $editMode = false) //constructor
		{
			$this->tableUsesSerializedPost = apply_filters('stageshow_filter_usesserializedpost', false, 'Settings');

			/*
			Usage: 
			
			add_filter('stageshow_filter_usesserializedpost', 'StageShowFilterUseSerialisedPost', 10, 2);
			function StageShowFilterUseSerialisedPost($state, $page)
			{
				if ($page != 'Settings') return $state;
				
				return true;
			}
			*/
						
			if (!current_user_can(STAGESHOWLIB_CAPABILITY_SETUPUSER))
			{
				$editMode = false;
			}
			
			// Call base constructor
			parent::__construct($env, $editMode);
			
			if (!$editMode)
			{
				$this->hiddenRowClass = '';
				$this->hiddenRowsButtonId = '';
				$this->moreText = '';
			}
			
			$this->defaultTabId = 'general-settings-tab';
		}
		
		function GetTableID($result)
		{
			return "stageshow-settings";
		}
		
		function GetMainRowsDefinition()
		{
	  		// FUNCTIONALITY: Settings = Auto Update Settings
			$this->isTabbedOutput = true;
			
			$rowDefs = array(			
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'General',      StageShowLibTableClass::TABLEPARAM_ID => 'general-settings-tab', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Advanced',     StageShowLibTableClass::TABLEPARAM_ID => 'advanced-settings-tab', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Reservations', StageShowLibTableClass::TABLEPARAM_ID => 'reservations-settings-tab', ),
			);
		
			$rowDefs = $this->MergeSettings($rowDefs, parent::GetMainRowsDefinition());

			return $rowDefs;
		}		
		
		function GetDetailsRowsDefinition()
		{
			$myDBaseObj = $this->myDBaseObj;
			
			$rowDefs = array(
				);

			$maxSampleValLen = 20;
			
			$pluginID = STAGESHOW_FOLDER;
			$templatePath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/emails/';
			$csstemplatePath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/css/';
			$jstemplatePath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/js/';
			$phptemplatePath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/';
			
			$loggedInUser = wp_get_current_user();										
			$user_metaInfo = get_user_meta($loggedInUser->ID);
			
			// TODO - Remove "System" fields from drop down list
			$systemFields = array(
				'admin_color', 
				'aim', 
				'closedpostboxes_page', 
				'comment_shortcuts', 
				'description', 
				'dismissed_wp_pointers', 
				'first_name', 
				'jabber', 
				'last_name', 
				'metaboxhidden_page', 
				'nickname', 
				'rich_editing', 
				'show_admin_bar_front', 
				'show_welcome_panel', 
				'use_ssl', 
				'wp_capabilities', 
				'wp_dashboard_quick_press_last_post_id', 
				'wp_user_level', 
				'yim', 
			);

			// $userFieldNames is a stdClass object - convert to array 
			$userFieldNames = array('');
			foreach ($user_metaInfo as $key => $value)
			{
				if (in_array($key, $systemFields))
					continue;
				
				if (StageShowLibMigratePHPClass::Safe_substr($key, 0, StageShowLibMigratePHPClass::Safe_strlen("wp_")) == "wp_")
					continue;
					
				if (StageShowLibMigratePHPClass::Safe_substr($key, StageShowLibMigratePHPClass::Safe_strlen("_dashboard")*-1) == "_dashboard")
					continue;
					
				$sampleValue = $value[0];
				if (StageShowLibMigratePHPClass::Safe_strlen($sampleValue) > $maxSampleValLen)
				{
					$sampleValue = StageShowLibMigratePHPClass::Safe_substr($sampleValue, 0, $maxSampleValLen)."...";
				}
				
				$userFieldNames[] = $key.'|'.$key.' ('.$sampleValue.')';
			}
			
			$phptemplateNames = array(
				'stageshow-wp-config.php|stageshow-wp-config.php',
			);
			
			$barcodeOptions = array();
			$barcodeClass = $this->myDBaseObj->GetBarcodeClass();
			foreach ($barcodeClass::GetBarcodeTypesArray() as $barcodeType => $barcodeId)
			{
				$barcodeOptions[] = "$barcodeType|$barcodeId";
			}

			$pluginID = STAGESHOW_FOLDER;
			$mimeEncodingOptions = array(
				STAGESHOWLIB_MIMEENCODING_PHPMAILER.'|'.__('Uses PHPMailer Methods', 'stageshow'), 
				STAGESHOWLIB_MIMEENCODING_PLUGIN.'|'.sprintf(__('Uses %s Plugin Code', 'stageshow'), $myDBaseObj->get_pluginName()), 
			);
			
			$reservationOptions = array(
				STAGESHOW_RESERVATIONSMODE_DISABLED.'|'.__('Disabled', 'stageshow'), 
				STAGESHOW_RESERVATIONSMODE_LOGIN.'|'.__('User Profile', 'stageshow'), 
				STAGESHOW_RESERVATIONSMODE_FORM.'|'.__('User Form', 'stageshow'),
				STAGESHOW_RESERVATIONSMODE_LOGINFORM.'|'.__('User Form (Logged in)', 'stageshow'), 
			);
			
			$pluginID = STAGESHOW_FOLDER;
			$templatePath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/emails/';
			
			$checkoutNoteOptions = array(
				'header|'.__('In Header', 'stageshow'),
				'titles|'.__('Above Titles', 'stageshow'),
				'above|'.__('Above Buttons', 'stageshow'),
				'below|'.__('Below Buttons', 'stageshow'),
				'bottom|'.__('At Bottom', 'stageshow'),
			);

			$textEntryText = __('Text Entry', 'stageshow');
			$dropdownEntryText = __('Drop Down Select', 'stageshow');
			
			$singleButtonsText = __('with Add Button per Performance', 'stageshow');
			$multipleButtonsText = __('with Add Button per Price', 'stageshow');
			
			$ticketQtySelectMode = array(
				STAGESHOWLIB_QTYSELECT_TEXT.STAGESHOWLIB_QTYSELECT_SINGLE."|$textEntryText $singleButtonsText",
				STAGESHOWLIB_QTYSELECT_DROPDOWN.STAGESHOWLIB_QTYSELECT_SINGLE."|$dropdownEntryText $singleButtonsText",
				STAGESHOWLIB_QTYSELECT_TEXT.STAGESHOWLIB_QTYSELECT_MULTIPLE."|$textEntryText $multipleButtonsText",
				STAGESHOWLIB_QTYSELECT_DROPDOWN.STAGESHOWLIB_QTYSELECT_MULTIPLE."|$dropdownEntryText $multipleButtonsText",
			);
			
			// Set the length of the 'Max Ticket Qty' to the number of sigits in STAGESHOW_MAXTICKETCOUNT (or 2)
			$ticketQtyLen = max(StageShowLibMigratePHPClass::Safe_strlen((string)STAGESHOW_MAXTICKETCOUNT), 2);
			
			$rowDefs = self::MergeSettings($rowDefs, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Organisation ID',                 StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'OrganisationID',		 StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_TEXT,     StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_ORGANISATIONID_TEXTLEN, StageShowLibTableClass::TABLEPARAM_SIZE => STAGESHOW_ORGANISATIONID_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Bcc EMails to Sales EMail',       StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'BccEMailsToAdmin',	     StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Send EMail confirmation to Administrator' ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Currency Symbol',		           StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'UseCurrencySymbol',     StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Include in Box Office Output' ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Box Office Below Trolley',        StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'ProductsAfterTrolley',  StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Move Box Office below Active Trolley', StageShowLibTableClass::TABLEPARAM_DEFAULT => false ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Items per Page',                  StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'PageLength',			 StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_INTEGER,  StageShowLibTableClass::TABLEPARAM_LEN => 3, StageShowLibTableClass::TABLEPARAM_DEFAULT => STAGESHOWLIB_EVENTS_PER_PAGE),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Max Ticket Qty',                  StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'MaxTicketQty',          StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_INTEGER,  StageShowLibTableClass::TABLEPARAM_LEN => $ticketQtyLen, StageShowLibTableClass::TABLEPARAM_DEFAULT => STAGESHOW_MAXTICKETCOUNT),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Performance Expires Limit',		StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'PerfExpireLimit',          StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_INTEGER,StageShowLibTableClass::TABLEPARAM_LEN => 7, StageShowLibTableClass::TABLEPARAM_SIZE => 7, StageShowLibTableClass::TABLEPARAM_NEXTINLINE => true, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => '',				                    StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'PerfExpireUnits',          StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => array('1|'.__('Seconds', 'stageshow'), '60|'.__('Minutes', 'stageshow'), '3600|'.__('Hours', 'stageshow')), ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Empty Box Office Message',	        StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'EmptyBoxOfficeMsg',        StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_EMPTYBOXOFFICEMSG_TEXTLEN, StageShowLibTableClass::TABLEPARAM_SIZE => STAGESHOW_EMPTYBOXOFFICEMSG_EDITLEN, StageShowLibTableClass::TABLEPARAM_DEFAULT => STAGESHOW_EMPTYBOXOFFICEMSG_DEFAULT),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Terminal Location',				StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'TerminalLocation',         StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_COOKIE, StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_LOCATION_TEXTLEN, ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale Summary Report EMail',        StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'SaleSummaryEMail',         StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOWLIB_MAIL_TEXTLEN, StageShowLibTableClass::TABLEPARAM_SIZE => STAGESHOWLIB_MAIL_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Summary EMail Template',	    	StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'EMailSummaryTemplatePath', StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_DIR => $templatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'php', StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', ),
				
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Client Record EMail Template',	   StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'DBEMailTemplatePath',   StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_DIR => $templatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'php', StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Note Position',          StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'CheckoutNotePosn',      StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_ITEMS => $checkoutNoteOptions, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Note',                   StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'CheckoutNote',          StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_TEXTBOX,  StageShowLibTableClass::TABLEPARAM_ROWS  => 4, StageShowLibTableClass::TABLEPARAM_COLS => 60, StageShowLibTableClass::TABLEPARAM_ALLOWHTML => true, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Donations',                        StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'AllowDonation',            StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Accept Purchaser Donations',  StageShowLibTableClass::TABLEPARAM_DEFAULT => false, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Note To Seller',                  StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'UseNoteToSeller',       StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Accept Purchaser Text Input',  StageShowLibTableClass::TABLEPARAM_DEFAULT => false ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Seats Available',                 StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'ShowSeatsAvailable',    StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Show Seats Available on Box Office',  StageShowLibTableClass::TABLEPARAM_DEFAULT => false ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Available Seats Button',          StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'AvailableSeatsButton',StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX,    StageShowLibTableClass::TABLEPARAM_TEXT => 'Link on Box-Office page to Available Seats' ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Add Ticket Quantities',           StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'QtySelectMode',         StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_ITEMS => $ticketQtySelectMode, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Minimum Empty Seats',             StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'MinSeatSpace',        StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_INTEGER,     StageShowLibTableClass::TABLEPARAM_LEN => 3, ),
				
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Booking Fee',                      StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'TransactionFee',           StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_FLOAT,  StageShowLibTableClass::TABLEPARAM_LEN => 9, StageShowLibTableClass::TABLEPARAM_DEFAULT => '0', StageShowLibTableClass::TABLEPARAM_NEXTINLINE => true, StageShowLibTableClass::TABLEPARAM_TEXT => '+', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => '',				                    StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'TransactionFeePerCent',    StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_FLOAT,  StageShowLibTableClass::TABLEPARAM_LEN => 3, StageShowLibTableClass::TABLEPARAM_DEFAULT => '0', StageShowLibTableClass::TABLEPARAM_TEXT => '%', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Delivery',                  StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'PostTicketsEnabled',       StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Enable Post Tickets Option',  StageShowLibTableClass::TABLEPARAM_DEFAULT => false, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Postage Fee',                      StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'PostageFee',               StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_FLOAT,  StageShowLibTableClass::TABLEPARAM_LEN => 9, StageShowLibTableClass::TABLEPARAM_DEFAULT => '0',  ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Custom Admin Stylesheet',	    	StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'CustomAdminStylesheetPath',StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_DIR => $csstemplatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'css', StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true,  ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Custom Stylesheet',	    	    StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'CustomStylesheetPath',     StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_DIR => $csstemplatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'css', StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true,  ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Custom Javascript',	    	    StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'CustomJavascriptPath',     StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_DIR => $jstemplatePath,  StageShowLibTableClass::TABLEPARAM_EXTN => 'js',  StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true,  ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Custom PHP',	     	            StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'CustomPHPPath',            StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_DIR => $phptemplatePath, StageShowLibTableClass::TABLEPARAM_ITEMS => $phptemplateNames, StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', StageShowLibTableClass::TABLEPARAM_NEXTINLINE => true,  ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => '',		                    		StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'CustomPHPSamples',         StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_VIEW,   StageShowLibTableClass::TABLEPARAM_TEXT => 'Sample',        StageShowLibTableClass::TABLEPARAM_DECODE => 'CustomPHPButton', ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Barcode Type',                     StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'BarcodeType',              StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $barcodeOptions,  ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'EMail MIME Encoding',              StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'MIMEEncoding',             StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $mimeEncodingOptions,  ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Website Link',                     StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'SkipDrivenLink',           StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Remove StageShow Plugin Website Link',  StageShowLibTableClass::TABLEPARAM_DEFAULT => false,  ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Printing',                        StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',  StageShowLibTableClass::TABLEPARAM_ID => 'EnablePrinting',        StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Enable Ticket Printing (Undocumented)', StageShowLibTableClass::TABLEPARAM_DEFAULT => false,  ),
				)
			);
			
			if ($myDBaseObj->IsPrintingActive())
			{
				$ticketTemplatePath = STAGESHOW_UPLOADS_PATH.'/tickets/';
				$printerTemplatePath = STAGESHOW_UPLOADS_PATH.'/printers/';
				$rowDefs = self::MergeSettings($rowDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Default Ticket Template',  StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab', StageShowLibTableClass::TABLEPARAM_ID => 'TicketTemplatePath', StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_DIR => $ticketTemplatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'xml', StageShowLibTableClass::TABLEPARAM_HIDEEXTNS => true, StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true, ),
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Printer Type',      StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab', StageShowLibTableClass::TABLEPARAM_ID => 'PrinterDefPath',     StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_DIR => $printerTemplatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'xml', StageShowLibTableClass::TABLEPARAM_HIDEEXTNS => true, StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true, ),
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Ticket Printer Server IP', StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab', StageShowLibTableClass::TABLEPARAM_ID => 'PrinterIPAddress',   StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOW_IP_TEXTLEN, ),
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Print Server ID',          StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab', StageShowLibTableClass::TABLEPARAM_ID => 'AuthTxnId',          StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_SALETXNID_TEXTLEN, StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_SALETXNID_EDITLEN, ),
					)
				);
			}
			
			$rowDefs = self::MergeSettings($rowDefs, array(


				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Reservations Mode',                StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'ReservationsMode',         StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $reservationOptions, ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Reservation EMail Template',       StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'ReserveEMailTemplatePath', StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_DIR => $templatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'php', StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Modes',                   StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'CheckoutModesEnabled',     StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_CHECKBOX, StageShowLibTableClass::TABLEPARAM_TEXT => 'Include In Prices Page',  StageShowLibTableClass::TABLEPARAM_DEFAULT => false, ),
				
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Address 1',                        StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'UserAddress1',             StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $userFieldNames, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Address 2',                        StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'UserAddress2',             StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $userFieldNames, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Address 3',                        StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'UserAddress3',             StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $userFieldNames, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'City',                             StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'UserCity',                 StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $userFieldNames, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'County',                           StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'UserCounty',               StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $userFieldNames, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Postcode',                         StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'UserPostcode',             StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $userFieldNames, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Country',                          StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'UserCountry',              StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $userFieldNames, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Phone',                            StageShowLibTableClass::TABLEPARAM_TAB => 'reservations-settings-tab',        StageShowLibTableClass::TABLEPARAM_ID => 'UserPhone',                StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $userFieldNames, ),

				)
			);
						
			$rowDefs = $this->MergeSettings($rowDefs, parent::GetDetailsRowsDefinition());
			
			return $rowDefs;
		}
/*		
		function OutputList($results, $updateFailed = false)
		{
			$myDBaseObj = $this->myDBaseObj;

			$settingsCount = $myDBaseObj->getDbgOption('Dev_SettingCount');
			if (is_numeric($settingsCount))
			{				
				$newDefs = array();
				$i = 0;
				foreach ($this->detailsRowsDef as $index => $def)
				{
					$tabParts = explode('-', $def['Tab']);
					if ((count($tabParts) == 4) && ($tabParts[3] !== 'paypal'))
					{
						continue;
					}
					$newDefs[] = $def;
					$i++;
					if ($i >= $settingsCount) break;
				}		
				$this->detailsRowsDef = $newDefs;				
			}

			parent::OutputList($results, $updateFailed);
		}
*/		

		function CustomPHPButton($entryInDB, $result)
		{
			$customPHPSampleURL = StageShowLibUtilsClass::GetCallbackURL(STAGESHOW_SAMPLES_TARGET);
			$customPHPSampleURL .= '&file=stageshow_wp-config_sample.php,stageshow-filters-sample.php';
			
			$buttonText = __('View Samples', 'stageshow');
			
			return '<a target="_blank" class="button-secondary" href="'.$customPHPSampleURL.'">'.$buttonText.'</a>';
		}
		
		// Commented out Class Def (StageShowSettingsAdminListClass)
	}
}

if (!class_exists('StageShowSettingsAdminClass')) 
{
	class StageShowSettingsAdminClass extends GatewaySettingsAdminClass // Define class
	{		
		function __construct($env) //constructor
		{
			// Call base constructor
			parent::__construct($env);
		}
			
		function ProcessActionButtons()
		{
			$donePage = false;
			$donePage |= $this->EditTemplate('TicketTemplatePath', 'tickets', false);
			$donePage |= $this->EditTemplate('EMailSummaryTemplatePath');
			$donePage |= $this->EditTemplate('ReserveEMailTemplatePath');
			$donePage |= $this->EditTemplate('CustomAdminStylesheetPath', 'css', false);
			$donePage |= $this->EditTemplate('CustomStylesheetPath', 'css', false);
			$donePage |= $this->EditTemplate('CustomJavascriptPath', 'js', false);
			$donePage |= $this->EditTemplate('CustomPHPPath', '', false);
			$donePage |= $this->EditTemplate('EMailTemplatePath');
			$donePage |= $this->EditTemplate('DBEMailTemplatePath');
			
			if (!$donePage)
			{
				parent::ProcessActionButtons();		
			}
			
			if (StageShowLibUtilsClass::IsElementSet('post', 'savechanges'))
			{
				$myDBaseObj = $this->myDBaseObj;
				$optionsUpdated = false;	
				
				// Notes: If Custom CSS or JS files shipped with SS are selected
				// then they will be copied to a new files when SS is updated or activated
				// by calls to CheckCustomIncludeFiles				
				
				if ($optionsUpdated)
				{
					$myDBaseObj->saveOptions();
				}
			}
		}
		
		function SaveSettings($dbObj)
		{
			$newPrinterType = StageShowLibUtilsClass::GetHTTPFilenameElem('post', 'PrinterDefPath');
			if ($newPrinterType != '')
			{
				// Check that the XML parses
				$this->myDBaseObj->ParsePrinterDef($newPrinterType);
			}
			
			parent::SaveSettings($dbObj);			
		}
		
	}
}






