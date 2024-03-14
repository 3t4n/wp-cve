<?php
/* 
Description: Code for Managing Payment Gateway Settings
 
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

include 'stageshowlib_adminlist.php';
include 'stageshowlib_settingsadmin.php';

if (!class_exists('GatewaySettingsAdminListClass')) 
{
	define('STAGESHOWLIB_MAIL_TEXTLEN',127);
	define('STAGESHOWLIB_MAIL_EDITLEN',60);

	define('STAGESHOWLIB_ADMINID_TEXTLEN',110);	
	define('STAGESHOWLIB_ADMINID_EDITLEN', 60);
	
	class GatewaySettingsAdminListClass extends StageShowLibAdminListClass // Define class
	{	
		var	$hasAdminEMailName = true;
		
		function __construct($env, $editMode = true) //constructor
		{			
			$myDBaseObj = $env['DBaseObj'];
			
			$this->gatewayName = $myDBaseObj->gatewayObj->GetName();
			$this->hasAdminEMailName = $myDBaseObj->GatewayHasEMailName();
			
			// Call base constructor
			parent::__construct($env, $editMode);
			
			$this->defaultTabId = 'gateway-settings-tab';
			$this->HeadersPosn = StageShowLibTableClass::HEADERPOSN_TOP;
		}
		
		function GetTableID($result)
		{
			return "gateway-settings";
		}
		
		function GetRecordID($result)
		{
			return '';
		}
		
		function GetMainRowsDefinition()
		{
			$this->isTabbedOutput = true;

			$rowDefs = array();
			for ($acctNo=1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$gName = ($acctNo > 1) ? '_'.$acctNo : '';
				$tabName = ($acctNo > 1) ? ' '.$acctNo : '';

				$rowDefs = array_merge($rowDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Payment Gateway'.$tabName,  StageShowLibTableClass::TABLEPARAM_ID => 'gateway'.$gName.'-settings-tab',  StageShowLibTableClass::TABLEPARAM_BEFORE => 'general-settings-tab', ),
				));
			}
						
			return $rowDefs;
		}		
		
		function GetDetailsRowsDefinition()
		{
			$pluginID = basename(dirname(dirname(__FILE__)));	// Library files should be in 'include' folder			
			$uploadImagesPath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/images';
			$templatePath = WP_CONTENT_DIR . '/uploads/'.$pluginID.'/emails/';

			include 'stageshowlib_gatewaybase.php';
			$gatewayList = StageShowLibGatewayBaseClass::GetGatewaysList($this->myDBaseObj);
			$serverSelect = array();
			foreach ($gatewayList as $gatewayDef)
			{
				$serverSelect[] = $gatewayDef->Id.'|'.$gatewayDef->Name;
			}
						
			$gatewayDefs = array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Payment Gateway', StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab', StageShowLibTableClass::TABLEPARAM_ID => 'GatewaySelected', StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ITEMS => $serverSelect, ),
			);

			foreach ($gatewayList as $gatewayDef)
			{
				$gatewayDefs = array_merge($gatewayDefs, $gatewayDef->Obj->Gateway_SettingsRowsDefinition());
			}
			
			for ($acctNo=1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
			{
				$gName = ($acctNo > 1) ? '_'.$acctNo : '';

				$gatewayDefs = array_merge($gatewayDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sale EMail Template',             StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$gName.'-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'EMailTemplatePath'.$gName,     StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_SELECT,   StageShowLibTableClass::TABLEPARAM_DIR => $templatePath, StageShowLibTableClass::TABLEPARAM_EXTN => 'php', StageShowLibTableClass::TABLEPARAM_BUTTON => 'Edit', StageShowLibTableClass::TABLEPARAM_AFTER => 'PayPalAPIEMail'.$gName, ),
				));

				if ($this->hasAdminEMailName)
				{
					$gatewayDefs = array_merge($gatewayDefs, array(
						array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sales EMail Name',                StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$gName.'-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'AdminID'.$gName,               StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_TEXT,     StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOWLIB_ADMINID_TEXTLEN,   StageShowLibTableClass::TABLEPARAM_SIZE => STAGESHOWLIB_ADMINID_EDITLEN,  StageShowLibTableClass::TABLEPARAM_AFTER => 'EMailTemplatePath'.$gName, ),
					));					
				}
				
				$gatewayDefs = array_merge($gatewayDefs, array(
					array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Sales EMail',                     StageShowLibTableClass::TABLEPARAM_TAB => 'gateway'.$gName.'-settings-tab',   StageShowLibTableClass::TABLEPARAM_ID => 'AdminEMail'.$gName,            StageShowLibTableClass::TABLEPARAM_TYPE => self::TABLEENTRY_TEXT,     StageShowLibTableClass::TABLEPARAM_LEN => STAGESHOWLIB_MAIL_TEXTLEN,      StageShowLibTableClass::TABLEPARAM_SIZE => STAGESHOWLIB_MAIL_EDITLEN, StageShowLibTableClass::TABLEPARAM_AFTER => 'EMailTemplatePath'.$gName, ),
				));					
			}
						
			$rowDefs = array(

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'EMail Logo Image File',                 StageShowLibTableClass::TABLEPARAM_TAB => 'general-settings-tab', StageShowLibTableClass::TABLEPARAM_ID => 'PayPalLogoImageFile',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_SELECT, StageShowLibTableClass::TABLEPARAM_ADDEMPTY => true, StageShowLibTableClass::TABLEPARAM_DIR => $uploadImagesPath, StageShowLibTableClass::TABLEPARAM_EXTN => 'gif,jpeg,jpg,png', ),

				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Timeout',                      StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',StageShowLibTableClass::TABLEPARAM_ID => 'CheckoutTimeout',       StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_INTEGER,StageShowLibTableClass::TABLEPARAM_LIMITS => "'U', ".PAYMENT_API_CHECKOUT_TIMEOUT_MINIMUM,      StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_CHECKOUT_TIMEOUT_TEXTLEN,    StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_CHECKOUT_TIMEOUT_EDITLEN, ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Log Files Folder Path',                 StageShowLibTableClass::TABLEPARAM_TAB => 'advanced-settings-tab',StageShowLibTableClass::TABLEPARAM_ID => 'LogsFolderPath',        StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_FILEPATH_TEXTLEN, StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_FILEPATH_EDITLEN, ),				
			);
			
			$rowDefs = array_merge($rowDefs, $gatewayDefs);
			
			$rowDefs = array_merge($rowDefs, parent::GetDetailsRowsDefinition());
			
			$rowDefs = array_merge($rowDefs, array(
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Complete URL',                 StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab', StageShowLibTableClass::TABLEPARAM_ID => 'CheckoutCompleteURL',   StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_URL_TEXTLEN,         StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_URL_EDITLEN,  ),
				array(StageShowLibTableClass::TABLEPARAM_LABEL => 'Checkout Cancelled URL',                StageShowLibTableClass::TABLEPARAM_TAB => 'gateway-settings-tab', StageShowLibTableClass::TABLEPARAM_ID => 'CheckoutCancelledURL',  StageShowLibTableClass::TABLEPARAM_TYPE => StageShowLibTableClass::TABLEENTRY_TEXT,   StageShowLibTableClass::TABLEPARAM_LEN => PAYMENT_API_URL_TEXTLEN,         StageShowLibTableClass::TABLEPARAM_SIZE => PAYMENT_API_URL_EDITLEN,  ),
				)
			);
			
			return $rowDefs;
		}		
				
		function JS_Bottom($defaultTab)
		{
			$jsCode  = parent::JS_Bottom($defaultTab);		
			$jsCode .= "
StageShowLib_addWindowsLoadHandler(stageshowlib_OnSettingsLoad); 
			";
			
			return $jsCode;
		}
		
		function OutputJavascript($selectedTabIndex = 0)
		{
			$myDBaseObj = $this->myDBaseObj;
			
			// FUNCTIONALITY: Settings - Default settings tab "incremented"" once Prices configured
			// Change default tab if Gateway settings have been set
			$selectedTab = 0;
			if ($myDBaseObj->SettingsConfigured() && (count($this->columnDefs)>0))
			{
				$selectedTab = $this->GetSettingsRowIndex($this->columnDefs, $this->defaultTabId);
			}
			
			parent::OutputJavascript($selectedTab);
		}
		
		function OutputList($results, $updateFailed = false)
		{
			ob_start();
			parent::OutputList($results, $updateFailed);
			$htmlout = ob_get_contents();
			ob_end_clean();
			
			$gatewaySelectIDDef = 'id="GatewaySelected"';
			$gatewaySelectOnClick = ' onchange="stageshowlib_ClickGateway(this)" ';
			
			$htmlout = StageShowLibMigratePHPClass::Safe_str_replace($gatewaySelectIDDef, $gatewaySelectOnClick.$gatewaySelectIDDef, $htmlout);
			StageShowLibEscapingClass::Safe_EchoHTML($htmlout);
		}
		
	}
}

if (!class_exists('GatewaySettingsAdminClass')) 
{
	class GatewaySettingsAdminClass extends StageShowLibSettingsAdminClass // Define class
	{
		function __construct($env) //constructor	
		{
			$this->myDBaseObj = $env['DBaseObj'];
			
			// Call base constructor
			parent::__construct($env);			
		}
		
		function ProcessActionButtons()
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj = $this->myDBaseObj;			
					
			$SettingsUpdateMsg = '';
			$this->hiddenTags = '';
				
			// Gateway SETTINGS
			if (StageShowLibUtilsClass::IsElementSet('post', 'savechanges'))
			{
				$this->CheckAdminReferer();				
				if (StageShowLibUtilsClass::IsElementSet('post', 'errormsglive') || $myDBaseObj->gatewayObj->IsLoginChanged($myDBaseObj->adminOptions))
				{
					$SettingsUpdateMsg = $myDBaseObj->gatewayObj->VerifyLogin();	
					if ($SettingsUpdateMsg != '')	
					{
						// FUNCTIONALITY: Settings - Reject Settings if cannot login successfully
						$this->hiddenTags .= '<input type="hidden" name="errormsglive" value="'.$SettingsUpdateMsg.'"/>'."\n";
					}
				}
				        
				for ($acctNo=1; $acctNo <= STAGESHOWLIB_NO_OF_PAYMENT_GATEWAYS; $acctNo++)
				{
					$optionGatewaySuffix = $this->myDBaseObj->gatewayObj->GetOptionGatewaySuffix($acctNo);

					if ($this->IsOptionChanged($myDBaseObj->adminOptions, 'AdminEMail'.$optionGatewaySuffix))
					{
						if (!$this->ValidateEmail(StageShowLibUtilsClass::GetHTTPTextElem('post', 'AdminEMail'.$optionGatewaySuffix)))
						{
							$SettingsUpdateMsg = __('Invalid Sales EMail', 'stageshow');
						}
					}
        		}
        		
				if ($this->IsOptionChanged($myDBaseObj->adminOptions, 'LogsFolderPath'))
				{
					// Confrm that logs folder path is valid or create folder
					$LogsFolder = StageShowLibUtilsClass::GetHTTPTextElem('post', 'LogsFolderPath');
					if (!StageShowLibMigratePHPClass::Safe_strpos($LogsFolder, ':'))
						$LogsFolder = ABSPATH . $LogsFolder;

					$LogsFolderValid = is_dir($LogsFolder);
					if (!$LogsFolderValid)
					{
						mkdir($LogsFolder, STAGESHOWLIB_LOGFOLDER_PERMS, true);
						$LogsFolderValid = is_dir($LogsFolder);
					}
					
					if ($LogsFolderValid)
					{
						// New Logs Folder Settings are valid			
					}
					else
					{
						$SettingsUpdateMsg = __('Cannot Create Logs Folder', 'stageshow');
					}
				}
        
				
				if ($SettingsUpdateMsg === '')
				{
					$this->SaveSettings($myDBaseObj);					
					$myDBaseObj->saveOptions();
					
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>'.__('Settings have been saved', 'stageshow').'</p></div>');
				}
				else
				{
					$this->Reload();		
					
					$gatewayName = $this->myDBaseObj->gatewayObj->GetName();
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.$SettingsUpdateMsg.'</p></div>');
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.$gatewayName.' '.__('Settings have NOT been saved.', 'stageshow').'</p></div>');
				}
			}
			
		}
		
		function Reload($reloadMode = true)
		{
			$this->reloadMode = $reloadMode;
		}
		
		function SaveSettings($myDBaseObj)
		{			
			$gatewayList = StageShowLibGatewayBaseClass::GetGatewaysList($myDBaseObj);
			foreach ($gatewayList as $gatewayDef)
			{
				$gatewayDef->Obj->SaveSettings($myDBaseObj);
			}
			
			parent::SaveSettings($myDBaseObj);
		}
		
	}
}


