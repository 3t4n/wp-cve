<?php
/* 
Description: Core Library Admin Page functions
 
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

if (!class_exists('StageShowLibPluginClass')) 
{
	class StageShowLibPluginClass // Define class
  	{
		var $myDBaseObj;
		var $ajaxParams = array();
		var $adminPageActive = false;
		var $shortcodeCount = 0;
						
		function __construct($pluginFile, $myDBaseObj)	 //constructor	
		{
			$this->myDBaseObj = $myDBaseObj;
			
			// add_action('wp_ajax_'.STAGESHOWLIB_PLUGINNAME, array(&$this, 'stageshowlib_AjaxAction'));
		}
		
		function stageshowlib_GetAjaxPageParams()
		{
			$ajaxParams = array('ajaxpage');
			return $ajaxParams;
		}
		
		function stageshowlib_AjaxCheckParams($ajaxParams)
		{
			$response['msg'] = '';
			foreach ($ajaxParams as $param)
			{
				if (!StageShowLibUtilsClass::IsElementSet('post', $param))
				{
					$response['msg'] = "$param ".__('not specified', 'stageshow');
					$response['status'] = 'error';
					return $response;
				}
				else
				{
					$postVal = StageShowLibUtilsClass::GetHTTPTextElem('post', $param); 
					$response['msg'] .= "$param = ".$postVal."\n";						
				}
			}
			
			$response['status'] = 'ok';
			return $response;
		}
		
		function stageshowlib_AjaxAction()
		{
			$response = array();
			$response['status'] = 'error';
			$response['msg'] = '';
			
			if (!check_ajax_referer(STAGESHOWLIB_AJAXNONCEKEY, 'security', false))
			{
				$response['msg'] = __('NOnce Error', 'stageshow');
				return $response;
			} 

			$pageParams = $this->stageshowlib_GetAjaxPageParams();
			$response = $this->stageshowlib_AjaxCheckParams($pageParams);
			if ($response['status'] == 'ok')
			{
				$myDBaseObj = $this->myDBaseObj;

				$id = StageShowLibUtilsClass::GetHTTPTextElem('post', 'ajaxid');
				$value = StageShowLibUtilsClass::GetHTTPTextElem('post', 'ajaxval');
				$_POST[$id] = $value;

				$this->env['ajax'] = true;		// Set env variable to disable HTML output
				$_GET['page'] = StageShowLibUtilsClass::GetHTTPTextElem('post', 'ajaxpage'); 
				
				unset($_POST['ajaxid']);
				unset($_POST['ajaxval']);
				unset($_POST['ajaxpage']);

				ob_start();			
				$this->printAdminPage();				
				$ajaxOutput = ob_get_contents();
				ob_end_clean();
				
				file_put_contents(ABSPATH.'logs/wplettings-ajax.log', $ajaxOutput);
				//$response['msg'] .= "ajaxOutput = ".StageShowLibMigratePHPClass::Safe_strlen($ajaxOutput)." bytes\n";						

				//$myDBaseObj->UpdateState($switchID, $switchState);		
				$response['status'] = 'page';
				$response['msg'] .= "AJAX Call Processed";
				
				return $response;
			}
			
			$response = $this->stageshowlib_AjaxCheckParams($this->ajaxParams);
			return $response;
		}
		
		function GetButtonTextAndTypeDef($buttonText, $buttonID, $buttonName = '', $buttonType = '', $buttonClasses = 'button-primary')
		{
			$buttonDef  = $this->GetButtonTypeDef($buttonID, $buttonName, $buttonType, $buttonClasses);
			if (StageShowLibMigratePHPClass::Safe_strpos($buttonDef, " src="))
				$buttonDef .= ' alt="'.$buttonText.'"';
			else
				$buttonDef .= ' value="'.$buttonText.'"';

			return $buttonDef;
		}
			
		function AdminButtonHasClickHandler($buttonID)
		{
			return false;
		}
			
		function GetButtonTypeDef($buttonID, $buttonName = '', $buttonType = '', $buttonClasses = 'button-primary')
		{
			$buttonTypeDef = '';
	
			if ($buttonType == '')
			{
				$buttonType = 'submit';
			}
			
			if (!$this->adminPageActive)
			{
				$buttonImage = $this->myDBaseObj->ButtonURL($buttonID);
				if ($buttonImage == '')
				{
					// Try for a payment gateway defined button ...
					$buttonImage = $this->myDBaseObj->gatewayObj->GetButtonImage($buttonID);
				}
				if ($buttonImage != '')
				{
					$buttonType = 'image';
					$buttonTypeDef .= 'src="'.$buttonImage.'" ';
				}				
			}
			
			$buttonTypeDef .= 'type="'.$buttonType.'"';
				
			if ($buttonName == '')
			{
				$buttonName = $this->myDBaseObj->GetButtonID($buttonID);
			}

			if ($buttonType == 'image')
			{
				$buttonClasses .= ' '.'stageshow'.'-button-image';				
			}

			if (isset($this->cssTrolleyBaseID))
			{
				$buttonClasses .= ' '.$this->cssTrolleyBaseID.'-ui';
				$buttonClasses .= ' '.$this->cssTrolleyBaseID.'-button';
			}

			$buttonTypeDef .= ' id="'.$buttonName.'" name="'.$buttonName.'"';					
			$buttonTypeDef .= ' class="'.$buttonClasses.'"';					

			$addClickHandler = true;
			if ($this->adminPageActive)
			{
				$addClickHandler = $this->AdminButtonHasClickHandler($buttonID);
			}

			if ($addClickHandler)
			{
				$onClickHandler = STAGESHOWLIB_DOMAIN.'_OnClick'.ucwords($buttonID);
				$buttonTypeDef .= ' onClick="return '.$onClickHandler.'(this, '.$this->shortcodeCount.')"';				
			}
			
			return $buttonTypeDef;
		}
				
	}
}



