<?php
/*
Description: Settings Admin Page functions

Copyright 2022 Malcolm Shergold

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

include 'stageshowlib_admin.php';
include 'stageshowlib_utils.php';

if (!class_exists('StageShowLibSettingsAdminClass'))
{
	if (!defined('STAGESHOWLIB_DEFAULT_TEMPLATES_PATH'))
		define('STAGESHOWLIB_DEFAULT_TEMPLATES_PATH', dirname(__FILE__) . '/templates/');

	class StageShowLibSettingsAdminClass extends StageShowLibAdminClass // Define class
	{
		function __construct($env) //constructor
		{
			$this->pageTitle = 'Settings';

			$env['adminObj'] = $this;

			$this->adminListObj = $this->CreateAdminListObj($env, true);

			// Call base constructor
			parent::__construct($env);
		}

		function ProcessActionButtons()
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj = $this->myDBaseObj;

			$SettingsUpdateMsg = '';

			if (StageShowLibUtilsClass::IsElementSet('post', 'savechanges') || $this->isAJAXCall)
			{
				$this->CheckAdminReferer();

				if ($SettingsUpdateMsg === '')
				{
					$this->SaveSettings($myDBaseObj);
					//$myDBaseObj->saveOptions();

					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>'.__('Settings have been saved', 'stageshow').'</p></div>');
 				}
				else
				{
					$this->Reload();

					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.$SettingsUpdateMsg.'</p></div>');
					StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Settings have NOT been saved.', 'stageshow').'</p></div>');
				}
			}

		}

		function Output_MainPage($updateFailed)
		{
			$myPluginObj = $this->myPluginObj;
			$myDBaseObj = $this->myDBaseObj;

			// Settings HTML Output - Start

			$formClass = 'stageshow'.'-admin-form';
			StageShowLibEscapingClass::Safe_EchoHTML('<div class="'.$formClass.'">'."\n");
?>
	<form method="post">
<?php

			$this->WPNonceField();

			$this->adminListObj->detailsRowsDef = apply_filters('stageshow'.'_filter_settingslist', $this->adminListObj->detailsRowsDef, $this->myDBaseObj);

			/*
			Usage:

			add_filter('{DomainName}_filter_settingslist', 'XXXXXXXXFilterSettingsList', 10, 2);
			function XXXXXXXXFilterSettingsList($detailsRowsDef, $myDBaseObj)
			{
				$settingsCount = $myDBaseObj->getDbgOption('Dev_SettingCount');
				if (is_numeric($settingsCount))
				{
					$newDefs = array();
					$i = 0;
					foreach ($detailsRowsDef as $index => $def)
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
					$detailsRowsDef = $newDefs;
				}

				return $detailsRowsDef;
			}
			*/


			// Get setting as stdClass object
			$results = $myDBaseObj->GetAllSettingsList();

			if (count($results) == 0)
			{
				StageShowLibEscapingClass::Safe_EchoHTML("<div class='noconfig'>".__('No Settings Configured', 'stageshow')."</div>\n");
			}
			else
			{
				$this->adminListObj->OutputList($results, $updateFailed);

				if (!isset($this->adminListObj->editMode) || ($this->adminListObj->editMode))
				{
					if ((count($results) > 0) && !$this->usesAjax)
					{
						$this->OutputPostButton("savechanges", __("Save Changes", 'stageshow'), "button-primary");
					}
				}
			}

?>
	</form>
	</div>
<?php
		}

		function SaveSettings($dbObj)
		{
			$settingOpts = $this->adminListObj->GetDetailsRowsDefinition();

			// Save admin settings to database
			foreach ($settingOpts as $settingOption)
			{
				if (isset($settingOption[StageShowLibTableClass::TABLEPARAM_READONLY]))
				{
					continue;
				}

				$controlId = $settingOption[StageShowLibTableClass::TABLEPARAM_ID];
				if ($this->isAJAXCall && !StageShowLibUtilsClass::IsElementSet('post', $controlId))
				{
					continue;
				}

				switch ($settingOption[StageShowLibTableClass::TABLEPARAM_TYPE])
				{
					case StageShowLibTableClass::TABLEENTRY_READONLY:
					case StageShowLibTableClass::TABLEENTRY_VIEW:
						break;

					case StageShowLibTableClass::TABLEENTRY_CHECKBOX:
						$controlId = $settingOption[StageShowLibTableClass::TABLEPARAM_ID];
						$dbObj->adminOptions[$controlId] = StageShowLibUtilsClass::IsElementSet('post', $controlId) ? true : false;
						break;

					case StageShowLibAdminListClass::TABLEENTRY_DATETIME:
						// Text Settings are "Trimmed"
						$controlId = $settingOption[StageShowLibTableClass::TABLEPARAM_ID];
						$dbObj->adminOptions[$controlId] = StageShowLibUtilsClass::GetHTTPDateTime('post', $controlId);
						break;

					case StageShowLibTableClass::TABLEENTRY_TEXT:
						// Text Settings are "Trimmed"
						$controlId = $settingOption[StageShowLibTableClass::TABLEPARAM_ID];
						$dbObj->adminOptions[$controlId] = StageShowLibMigratePHPClass::Safe_trim(StageShowLibUtilsClass::GetHTTPTextElem('post', $controlId));
						break;

					case StageShowLibTableClass::TABLEENTRY_TEXTBOX:
						// Text Settings are "Trimmed"
						$controlId = $settingOption[StageShowLibTableClass::TABLEPARAM_ID];
						if (isset($settingOption[StageShowLibTableClass::TABLEPARAM_ALLOWHTML]))
							$dbObj->adminOptions[$controlId] = StageShowLibUtilsClass::GetHTTPTextHttpElem('post', $controlId);
						else
							$dbObj->adminOptions[$controlId] = StageShowLibUtilsClass::GetHTTPTextareaElem('post', $controlId);
						break;

					default:
						$controlId = $settingOption[StageShowLibTableClass::TABLEPARAM_ID];
						$dbObj->adminOptions[$controlId] = StageShowLibUtilsClass::GetHTTPTextElem('post', $controlId);
						break;
				}
			}

			if (defined('STAGESHOWLIB_SETTINGS_SAVED'))
				$dbObj->adminOptions[STAGESHOWLIB_SETTINGS_SAVED] = true;

			$dbObj->saveOptions();

			if ($this->isAJAXCall) $this->donePage = true;
		}

		function EditTemplate($templateID, $folder='emails', $isEMail = true)
		{
			if (!current_user_can( 'manage_options' )) return false;

			$pluginRoot = StageShowLibMigratePHPClass::Safe_str_replace('plugins', 'uploads', dirname(dirname(__FILE__)));
			$pluginId = basename($pluginRoot);

/*
			$len = StageShowLibMigratePHPClass::Safe_strlen($templateID);
			foreach (StageShowLibUtilsClass::GetArrayKeys('post') as $postKey)
			{
				$postVal = StageShowLibUtilsClass::GetHTTPTextElem('post', $postKey);
				if (StageShowLibMigratePHPClass::Safe_substr($postKey, 0, $len) !== $templateID) continue;
				$postKeyParts = explode('-', $postKey);
				if (count($postKeyParts) < 2) continue;
				if (($postKeyParts[1] === 'Button') || ($postKeyParts[1] === 'Save'))
				{
					$templateID = $postKeyParts[0];
					break;
				}
			}
*/
			if (StageShowLibUtilsClass::IsElementSet('post', $templateID.'-Button'))
			{
				$templateFile = StageShowLibUtilsClass::GetHTTPFilenameElem('post', $templateID);

				$templatePath = $pluginRoot;
				if ($folder != '') $templatePath .= '/'.$folder;
				$templatePath .= '/'.$templateFile;

				$editorID = $templateID.'-Editor';

				$isPHP = false;
				
				if ($templateFile == '')
				{
					$fileExtn = StageShowLibUtilsClass::IsElementSet('post', $templateID.'-Extn');
					$templateFile = "{$pluginId}-custom.{$fileExtn}";
					$templatePath .= $templateFile;

					// TODO: - Create default file ....
					return false;
				}
				else
				{
					$contents = file_get_contents($templatePath);
					if ((substr($templatePath, -4, 4) === '.php') && !$isEMail)
					{
						// Remove first and last lines 
						$isPHP = true;
						$contents = substr($contents, strpos($contents, "\n") + 1);
						$contents = substr($contents, 0, strrpos(trim($contents), "\n"));
					}
				}
				if ($isEMail)
				{
					$pregRslt = preg_match('/(.*[\n])(.*[\n])([\s\S]*?)(\*\/[\s]*?\?\>)/', $contents, $matches);
					if ($pregRslt != 1)
					{
						StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Error parsing file.', 'stageshow').' - '.$templateFile. '</p></div>');
						$this->donePage = true;
						return true;
					}
					$subject = $matches[2];
					$contents = $matches[3];
					$htmlMode = (StageShowLibMigratePHPClass::Safe_strpos($contents, '</html>') > 0);
					$styles = '';
					if ($htmlMode)
					{
						// Extract any styles from the source - Editor removes them
						if (preg_match_all('/\<style[\s\S]*?\>([\s\S]*?)\<\/style\>[\s\S]*?/', $contents, $matches) >= 1)
						{
							foreach ($matches[1] as $style)
							{
								$styles .= "\n<style>$style</style>\n";
							}
						}

						$pregRslt = preg_match('/\<body[\s\S]*?\>([\s\S]*?)\<\/body/', $contents, $matches);
						if ($pregRslt != 1)
						{
							StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Error parsing HTML in file', 'stageshow').' - '.$templateFile. '</p></div>');
							return true;
						}
						else
						{
							$contents = $matches[1];
						}
						$contents = StageShowLibMigratePHPClass::Safe_str_replace("\n", "", $contents);		// Remove all line ends
						$mystyle = '
<style>
#'.$editorID.'_ifr
{
	border: solid black 1px;
}
</style>';
						$settings = array(
							'wpautop' => false,
						    'editor_css' => $mystyle
						);
					}
					else
					{
						StageShowLibEscapingClass::Safe_EchoHTML('
<style>
#'.$editorID.'-tmce,
#qt_'.$editorID.'_toolbar,
#wp-'.$editorID.'-media-buttons
{
	display: none;
}
</style>');
						$settings = array();
					}
				}
				else
				{
					// Just need a text editor
					$htmlMode = false;
					$settings = array(
						'wpautop' => true,
					    'media_buttons' => false,
					    'editor_css' => '',
					    'tinymce' => false,
					    'quicktags' => false
						);
				}

				$saveButtonId = $templateID.'-Save';
				$buttonValue = __('Save', 'stageshow');
				$buttonCancel = __('Cancel', 'stageshow');

				$this->pageTitle .= " ($templateFile)";

				StageShowLibEscapingClass::Safe_EchoHTML('<form method="post" id="'.$pluginId.'-fileedit">'."\n");
				if ($isEMail)
				{
					StageShowLibEscapingClass::Safe_EchoHTML("<div id=".$pluginId."-fileedit-div-subject>\n");
					StageShowLibEscapingClass::Safe_EchoHTML(__("Subject", 'stageshow')."&nbsp;<input name=\"$pluginId-fileedit-subject\" id=\"$pluginId-fileedit-subject\" type=\"text\" value=\"$subject\" maxlength=80 size=80 /></div>\n");
				}

				wp_editor($contents, $editorID, $settings);
				if ($htmlMode)
				{
					StageShowLibEscapingClass::Safe_EchoHTML("<input name=\"$pluginId-fileedit-html\" id=\"$pluginId-fileedit-html\" type=\"hidden\" value=1 />\n");
				}
				StageShowLibEscapingClass::Safe_EchoHTML("<input name=\"$pluginId-fileedit-isEMail\" id=\"$pluginId-fileedit-isEMail\" type=\"hidden\" value=\"$isEMail\" />\n");
				StageShowLibEscapingClass::Safe_EchoHTML("<input name=\"$pluginId-fileedit-name\" id=\"$pluginId-fileedit-name\" type=\"hidden\" value=\"$templateFile\" />\n");
				StageShowLibEscapingClass::Safe_EchoHTML("<input name=\"$pluginId-fileedit-folder\" id=\"$pluginId-fileedit-folder\" type=\"hidden\" value=\"$folder\" />\n");
				if (isset($styles)) StageShowLibEscapingClass::Safe_EchoHTML("<input name=\"$pluginId-fileedit-styles\" id=\"$pluginId-fileedit-styles\" type=\"hidden\" value=\"".$styles."\" />\n");
				StageShowLibEscapingClass::Safe_EchoHTML("<input class=\"button-primary\" name=\"$saveButtonId\" id=\"$saveButtonId\" type=\"submit\" value=\"$buttonValue\" />\n");
				StageShowLibEscapingClass::Safe_EchoHTML("<input class=\"button-secondary\" type=\"submit\" value=\"$buttonCancel\" />\n");
				StageShowLibEscapingClass::Safe_EchoHTML("</form>\n");

				$this->donePage = true;
				return true;
			}

			if (StageShowLibUtilsClass::IsElementSet('post', $templateID.'-Save'))
			{
				$isPHP = false;
				$templateHeader = '';
				$templateFooter = '';
				
				$templateFile = StageShowLibUtilsClass::GetHTTPFilenameElem('post', $pluginId.'-fileedit-name');
				$templateDir = StageShowLibUtilsClass::GetHTTPFilenameElem('post', $pluginId.'-fileedit-folder');
				$fileParts = pathinfo($templateFile);
				$templateName = $fileParts['filename'];
				$templateExtn = $fileParts['extension'];
				$templateContents = stripslashes(StageShowLibUtilsClass::GetArrayElement('post', $templateID.'-Editor'));
	
				switch($templateExtn)
				{
					case 'css':
					case 'js':
						$folderName = $templateExtn;
						$templateFolder = STAGESHOWLIB_UPLOADS_PATH.'/'.$folderName.'/';
						$subject = '';
						break;

					default:
						if ($templateDir == '')
						{
							$folderName = '';
							$templateFolder = STAGESHOWLIB_UPLOADS_PATH.'/';
							$isPHP = true;
							$subject = '';
							break;
						}
						$folderName = 'emails';
						$templateFolder = STAGESHOWLIB_UPLOADS_PATH.'/'.$folderName.'/';
						$isPHP = true;
						$templateHeader = " /* Hide template from public access ... Next line is email subject - Following lines are template body\n";
						$subject = StageShowLibUtilsClass::GetHTTPTextElem('post', $pluginId.'-fileedit-subject');
						if ($subject == '') $subject = __('No subject', 'stageshow');
						$templateHeader .= "$subject\n";
						$templateFooter = "*/ ";
						if (StageShowLibMigratePHPClass::Safe_strpos($subject, '*/') || StageShowLibMigratePHPClass::Safe_strpos($templateContents, '*/'))
						{
							StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Template not saved - Invalid Content', 'stageshow').'</p></div>');
							return false;
						}
						break;
				}
				$defaultTemplateFolder = STAGESHOWLIB_DEFAULT_TEMPLATES_PATH.$folderName;
				if (file_exists($defaultTemplateFolder.'/'.$templateFile))
				{
					// The template is a default template - Save with new name
					$fileNumber = 1;
					while (true)
					{
						$destFileName = $templateName."-$fileNumber.".$templateExtn;
						if ($destFileName == $templateFolder.$templateFile)
							break;
						if (!file_exists($templateFolder.$destFileName))
						{
							$templateFile = $destFileName;
							$this->myDBaseObj->adminOptions[$templateID] = $destFileName;
							$this->myDBaseObj->saveOptions();
							break;
						}
						$fileNumber++;
						if ($fileNumber > 1000)
						{
							StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.__('Template Not Saved - Could not rename file.', 'stageshow').'-'.$templateFile.'</p></div>');
							return false;
						}
					}
				}

				$htmlMode = StageShowLibUtilsClass::IsElementSet('post', $pluginId.'-fileedit-html');

				$contents  = '';

				if ($isPHP)
				{
					$contents .= '<?php'.$templateHeader."\n";
				}

				if ($htmlMode)
				{
					$contents .= '
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">';

					if (StageShowLibUtilsClass::IsElementSet('post', "$pluginId-fileedit-styles"))
					{
						$contents .= StageShowLibUtilsClass::GetHTTPTextElem('post', "$pluginId-fileedit-styles");
					}

					$contents .= '
</head>
<body text="#000000" bgcolor="#FFFFFF">';
					$contents .= StageShowLibMigratePHPClass::Safe_str_replace("<br />", "<br />\n", $templateContents);
					$contents .= '
</body>
</html>
';
				}
				else
				{
					$contents .= $templateContents;
				}

				if ($isPHP)
				{
					$contents .= "{$templateFooter}?>";
				}

				file_put_contents($templateFolder.$templateFile, $contents);
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="updated"><p>'.__('Template Updated.', 'stageshow').' - '.$templateFile. '</p></div>');
			}

			return false;
		}

	}
}


