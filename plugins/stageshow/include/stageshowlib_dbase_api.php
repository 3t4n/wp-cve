<?php
/*
Description: Core Library Database Access functions

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

require_once STAGESHOWLIB_INCLUDE_PATH.'stageshowlib_utils.php';
require_once STAGESHOWLIB_INCLUDE_PATH.'stageshowlib_dbase_base.php';
require_once STAGESHOWLIB_INCLUDE_PATH.'stageshowlib_logfile.php';

if (!class_exists('StageShowLibDBaseClass'))
{
	if (!defined('STAGESHOWLIB_EVENTS_PER_PAGE'))
		define('STAGESHOWLIB_EVENTS_PER_PAGE', 20);

	if (!defined('STAGESHOWLIB_SESSION_TIMEOUT'))
		define('STAGESHOWLIB_SESSION_TIMEOUT', 60*60*24);

	define('STAGESHOWLIB_PLUGINNAME', basename(dirname(dirname(__FILE__))));
	define('STAGESHOWLIB_FILENAME_COMMSLOG', STAGESHOWLIB_PLUGINNAME.'.log');

	if (!defined('STAGESHOWLIB_SITEID_OPTIONID'))
		define('STAGESHOWLIB_SITEID_OPTIONID', 'OrganisationID');

	if (!defined('STAGESHOWLIB_LOGOIMAGE_OPTIONID'))
		define('STAGESHOWLIB_LOGOIMAGE_OPTIONID', 'PayPalLogoImageFile');

	define('STAGESHOWLIB_SESSIONERR_FALSE', 1);
	define('STAGESHOWLIB_SESSIONERR_INACTIVE', 2);
	define('STAGESHOWLIB_SESSIONERR_NOTABLE', 3);
	define('STAGESHOWLIB_SESSIONERR_NOMATCH', 4);

	class StageShowLibDBaseClass extends StageShowLibGenericDBaseClass // Define class
	{
		const MYSQL_DATE_FORMAT = 'Y-m-d';
		const MYSQL_TIME_FORMAT = 'H:i:s';
		const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';
		const MYSQL_DATETIME_NOSECS_FORMAT = 'Y-m-d H:i';

		const ForReading = 1;
		const ForWriting = 2;
		const ForAppending = 8;

		const SessionDebugPrefix = 'stageshowlib_debug_';

		const VERSION_NEWINSTALL = 1;
		const VERSION_UNCHANGED = 2;
		const VERSION_CHANGED = 3;

		var $optionsTable = '';
		var $optionsID;

		var $adminOptions;
		var $dbgOptions;
		var $pluginInfo;
		var $opts;

		var	$buttonImageURLs = array();

		var	$emailObjClass = 'StageShowLibHTMLEMailAPIClass';
		var	$emailClassFilePath = STAGESHOWLIB_INCLUDE_PATH.'stageshowlib_htmlemail_api.php';

		var $sid;
		
		function __construct($opts = null) //constructor
		{			
			$this->sid = get_option('siteurl');

			// Get the session here ... before any output 
			$this->GetSessionCookie();

			$dbPrefix = $this->getTablePrefix();
			$this->DBTables = $this->getTableNames($dbPrefix);

			parent::__construct($opts);

			$this->opts = $opts;
			$this->getOptions();

		}

		function PurgeDB($alwaysRun = false)
		{
			if (current_user_can(STAGESHOWLIB_CAPABILITY_DEVUSER))
			{
				$className = get_class($this);
				StageShowLibEscapingClass::Safe_EchoHTML("PurgeDB not defined in $className class <br>\n");
			}
		}

		function PurgeOrphans($dbFields, $condition = '')
		{
			$masterCol = $dbFields[0];

			$dbFieldParts = explode('.', $masterCol);
			$masterTable = $dbFieldParts[0];
			$masterIndex = $dbFieldParts[1];

			$subCol = $dbFields[1];

			$dbFieldParts = explode('.', $subCol);
			$subTable = $dbFieldParts[0];
			$subIndex = $dbFieldParts[1];

			$sqlSelect  = 'SELECT '.$masterCol.' AS id ';
			$sql  = 'FROM '.$masterTable.' ';
			$sql .= 'LEFT JOIN '.$subTable.' ON '.$masterTable.'.'.$subIndex.'='.$subTable.'.'.$subIndex.' ';
			$sql .= 'WHERE '.$subTable.'.'.$subIndex.' IS NULL ';

			if ($condition != '')
			{
				$sql .= 'AND '.$condition.' ';
			}

			if ($this->isDbgOptionSet('Dev_ShowDBOutput'))
			{
				$this->get_results('SELECT * '.$sql);
			}

			$sql = $sqlSelect.$sql;
			$idsList = $this->get_results($sql);
			if (count($idsList) == 0) return;

			$ids = '';
			foreach ($idsList AS $idEntry)
			{
				if ($ids != '') $ids .= ',';
				$ids .= $idEntry->id;
			}

			$sql  = 'DELETE FROM '.$masterTable.' ';
			$sql .= 'WHERE '.$masterIndex.' IN ( ';
			$sql .= $ids;
			$sql .= ') ';

			$this->query($sql);

		}

	    function uninstall()
	    {
		}

		function AllUserCapsToServervar()
		{
			$this->SetSessionElem(STAGESHOWLIB_SESSION_VALIDATOR, true);	// Set Element and Write Cache to DB
		}

		function UserCapToServervar($capability)
		{
			$this->SetSessionElem('Capability_'.$capability, current_user_can($capability), false);
		}

		static function GetIPAddr()
		{
			//Get the forwarded IP if it exists
			if (StageShowLibUtilsClass::IsElementSet('server', 'X-Forwarded-For'))
			{
				$the_ip  = StageShowLibUtilsClass::GetHTTPTextElem('server', 'X-Forwarded-For');
			}
			elseif (StageShowLibUtilsClass::IsElementSet('server', 'HTTP_X_FORWARDED_FOR'))
			{
				$the_ip  = StageShowLibUtilsClass::GetHTTPTextElem('server', 'HTTP_X_FORWARDED_FOR');
			}
			else
			{
				$the_ip  = StageShowLibUtilsClass::GetHTTPTextElem('server', 'REMOTE_ADDR');
			}

			return $the_ip;
		}

		function IfButtonHasURL($buttonID)
		{
			$ourButtonURL = $this->ButtonURL($buttonID);
			if ($ourButtonURL == '')
				return false;

			return true;
		}

		function ButtonHasURL($buttonID, &$buttonURL)
		{
			$ourButtonURL = $this->ButtonURL($buttonID);
			if ($ourButtonURL == '')
				return false;

			$buttonURL = $ourButtonURL;
			return true;
		}

		function ButtonURL($buttonID)
		{
			if (!isset($this->buttonImageURLs[$buttonID])) return '';
			return $this->buttonImageURLs[$buttonID];
		}

		function IsButtonClicked($buttonID)
		{
			$normButtonID = $this->GetButtonID($buttonID);
			$rtnVal = (StageShowLibUtilsClass::IsElementSet('request', $normButtonID) || StageShowLibUtilsClass::IsElementSet('request', $normButtonID.'_x'));
			return $rtnVal;
		}

		function GetButtonID($buttonID)
		{
			return $buttonID;
		}

		function getDebugFlagsArray()
		{
			$debugFlagsArray = array();

			$len = StageShowLibMigratePHPClass::Safe_strlen(self::SessionDebugPrefix);
			$sessionKeys = array_keys($this->GetSession());
			foreach ($sessionKeys as $key)
			{
				if (StageShowLibMigratePHPClass::Safe_substr($key, 0, $len) != self::SessionDebugPrefix)
					continue;
				$debugFlagsArray[] = $key;
			}
			return $debugFlagsArray;
		}

		function getTablePrefix()
		{
			global $wpdb;
			return $wpdb->prefix;
		}

		function getTableNames($dbPrefix)
		{
			$DBTables = new stdClass();

			$DBTables->Settings = $dbPrefix.'mjslibOptions';
			$DBTables->Sessions = $dbPrefix.'sessions';

			return $DBTables;
		}

		function AddGenericFields($EMailTemplate)
		{
			return $EMailTemplate;
		}

		function AddGenericDBFields(&$event)
		{
			$event->organisation = $this->adminOptions['OrganisationID'];
			if ($this->isOptionSet('HomePageURL'))
			{
				$event->url = $this->getOption('HomePageURL');
			}
			else
			{
				$event->url = get_option('home');
			}
		}

		function GetWPNonceField($referer = '', $name = '_wpnonce')
		{
			return $this->WPNonceField($referer, $name, false);
		}

		function WPNonceField($referer = '', $name = '_wpnonce', $echoOut = true)
		{
			$html_nonce = '';

			if ($referer == '')
			{
				$caller = $this->opts['Caller'];
				$referer = plugin_basename($caller);
			}

			if ( function_exists('wp_nonce_field') )
			{
				if ($this->getDbgOption('Dev_ShowWPOnce'))
					$html_nonce .= "<!-- wp_nonce_field($referer) ".$this->GetNOnceElements($referer)." -->\n";
				$html_nonce .= wp_nonce_field($referer, $name, false, false);
				$html_nonce .=  "\n";
			}

			if ($echoOut) StageShowLibEscapingClass::Safe_EchoHTML($html_nonce);
			return $html_nonce;
		}

		function AddParamAdminReferer($caller, $theLink)
		{
			if (!function_exists('add_query_arg'))
				return $theLink;

			if ($caller == '')
				return $theLink;

			$baseName = plugin_basename($caller);
			$nonceVal = wp_create_nonce( $baseName );

			if ($this->getDbgOption('Dev_ShowWPOnce'))
			{
				$user = wp_get_current_user();
				$uid  = (int) $user->ID;
				$token = wp_get_session_token();
				$i     = wp_nonce_tick();
				StageShowLibEscapingClass::Safe_EchoHTML("\n<!-- AddParamAdminReferer  NOnce:$nonceVal  ".$this->GetNOnceElements($baseName)." -->\n");
			}

			$theLink = add_query_arg( '_wpnonce', $nonceVal, $theLink );

			return $theLink;
		}

		function CheckAdminReferer($referer = '')
		{
			if ($referer == '')
			{
				$caller = $this->opts['Caller'];
				$referer = plugin_basename($caller);
			}

			check_admin_referer($referer);
		}

		function GetNOnceElements($action)
		{
			$user = wp_get_current_user();
			$uid  = (int) $user->ID;
			$token = wp_get_session_token();
			$i     = wp_nonce_tick();
			return "elems:{$i}|{$action}|{$uid}|{$token}";
		}

		function ActionButtonHTML($buttonText, $caller, $domainId, $buttonClass, $elementId = 0, $buttonAction = '', $extraParams = '', $target = '')
		{
			//if ($buttonAction == '') $buttonAction = strtolower(str_replace(" ", "", $buttonText));
			$buttonText = __($buttonText, $domainId);
			$page = StageShowLibUtilsClass::GetHTTPTextElem('get', 'page');

			$buttonId = $domainId.'-'.$buttonAction.'-'.$elementId;

			$editLink = 'admin.php?page='.$page;
			if ($buttonAction !== '') $editLink .= '&action='.$buttonAction;
			if ($elementId !== 0) $editLink .= '&id='.$elementId;
			$editLink = $this->AddParamAdminReferer($caller, $editLink);
			if ($extraParams != '') $editLink .= '&'.$extraParams;
			if ($target != '') $target = 'target='.$target;

			$editControl = "<a id=$buttonId name=$buttonId $target".' class="button-secondary" href="'.$editLink.'">'.$buttonText.'</a>'."\n";
			if ($buttonClass != '')
			{
				$editControl = '<div class='.$buttonClass.'>'.$editControl.'</div>'."\n";
			}
			return $editControl;
		}

		function URLsToAnchor($page)
		{
			$posnHTTP = 0;
			
			// Look for a word followed by a URL
			$URLRegex = "#(\w*\s)(https:[\w.\/\-\?\=]*)#";
			
			$matches = array();
			$noOfMatches = preg_match_all($URLRegex, $page, $matches);
			if ($noOfMatches !== false)
			{
				for ($i=0; $i<$noOfMatches; $i++)
				{ 
					$entry = $matches[0][$i];
					$url = $matches[2][$i];
					$txt = StageShowLibMigratePHPClass::Safe_trim($matches[1][$i]);
					
					$page = StageShowLibMigratePHPClass::Safe_str_replace($entry, '<a target="_blank" href="'.$url.'">'.$txt.'</a>', $page);
				}
			}
			
			return $page;
		}
		
		function DeleteCapability($capID)
		{
			global $wp_roles;
			
			if (!isset($wp_roles))
			{
				$wp_roles = new WP_Roles();
				$wp_roles->use_db = true;
			}

			// Get all roles
			$roleIDs = $wp_roles->get_names();
			foreach ($roleIDs as $roleID => $publicID)
				$wp_roles->remove_cap($roleID, $capID);
		}

		function checkVersion()
		{
			$result = $this->compareVersion();

			return ($result != self::VERSION_UNCHANGED);
		}

		function compareVersion()
		{
			// Check if updates required

			// Get current version from Wordpress API
			$currentVersion = $this->get_name().'-'.$this->get_version();

			// Get last known version from adminOptions
			$lastVersion = $this->adminOptions['LastVersion'];
			if ($lastVersion == '')
			{
				$rslt = self::VERSION_NEWINSTALL;
			}
			else if ($currentVersion === $lastVersion)
			{
				// Compare versions
				$rslt = self::VERSION_UNCHANGED;				
			}
			else
			{
				$rslt = self::VERSION_CHANGED;				
			}
			
			if ($rslt != self::VERSION_UNCHANGED)
			{
				// Save current version to options
				$this->adminOptions['LastVersion'] = $currentVersion;
				$this->saveOptions();
			}

			return $rslt;
		}

		function get_pluginInfo($att = '')
		{
			if (!isset($this->pluginInfo))
			{
				if (!function_exists('get_plugins'))
					require_once(ABSPATH . 'wp-admin/includes/plugin.php');
				$allPluginsInfo = get_plugins();
				if (isset($this->opts['PluginFolder']))
				{
					$basename = $this->opts['PluginFolder'];
				}
				else
				{
					$basename = plugin_basename(__FILE__);
					for ($i = 0; ($i < 10) && StageShowLibMigratePHPClass::Safe_strpos($basename, '/'); $i++)
						$basename = dirname($basename);
				}

				foreach ($allPluginsInfo as $pluginPath => $pluginInfo)
				{
					if ($basename == dirname($pluginPath))
					{
						$this->pluginInfo = $pluginInfo;
						break;
					}
				}
			}

			if ($att == '')
				return $this->pluginInfo;

			return isset($this->pluginInfo[$att]) ? $this->pluginInfo[$att] : '';
		}

		function get_domain()
		{
			// This function returns a default profile (for translations)
			// Descendant classes can override this if required)
			return basename(dirname(dirname(__FILE__)));
		}

		function get_pluginName()
		{
			return $this->get_name();
		}

		function get_name()
		{
			return $this->get_pluginInfo('Name');
		}

		function get_version()
		{
			return $this->get_pluginInfo('Version');
		}

		function get_author()
		{
			return $this->get_pluginInfo('Author');
		}

		function get_distURI()
		{
			return $this->get_pluginInfo('AuthorURI');
		}

		function get_pluginURI()
		{
			return $this->get_pluginInfo('PluginURI');
		}

		function ShowDebugModes()
		{
			$html_dbgmsg = '';
			$debugFlagsArray = $this->getDebugFlagsArray();
			asort($debugFlagsArray);
			if (count($debugFlagsArray) > 0)
			{
				$html_dbgmsg .= '<strong>'.__('Session Debug Modes', 'stageshow').':</strong> ';
				$comma = '';
				foreach ($debugFlagsArray as $debugMode)
				{
					$debugMode = $comma.StageShowLibMigratePHPClass::Safe_str_replace(self::SessionDebugPrefix, '', $debugMode);
					$html_dbgmsg .= $debugMode;
					$comma = ', ';
				}
				$html_dbgmsg .= "<br>\n";
				$hasDebug = true;
			}
			else
			{
				$hasDebug = false;
			}

			if (defined('STAGESHOWLIB_BLOCK_HTTPS'))
			{
				$html_dbgmsg .= '<strong>'.__('SSL over HTTP', 'stageshow').":</strong> Blocked<br>\n";
			}

			StageShowLibEscapingClass::Safe_EchoHTML($html_dbgmsg);

			return $hasDebug;
		}

		function InTestMode()
		{
			if (!$this->IsSessionElemSet('stageshowlib_debug_test')) return false;

			if (!function_exists('wp_get_current_user')) return false;

			return current_user_can(STAGESHOWLIB_CAPABILITY_DEVUSER);
		}

		function createDBTable($table_name, $tableIndex, $dropTable = false)
		{
			if ($dropTable)
				$this->DropTable($table_name);

			$sql  = "CREATE TABLE ".$table_name.' (';
			$sql .= $tableIndex.' INT UNSIGNED NOT NULL AUTO_INCREMENT, ';
			$sql .= $this->getTableDef($table_name);
			$sql .= 'UNIQUE KEY '.$tableIndex.' ('.$tableIndex.')
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;';

			//excecute the query
			$this->dbDelta($sql);
		}

		function getTableDef($tableName)
		{
			$sql = "";

			switch($tableName)
			{
				case $this->DBTables->Settings:
					$sql .= '
						option_name VARCHAR(50),
						option_value LONGTEXT,
					';
					break;

				case $this->DBTables->Sessions:
					$sql .= '
						sessionCookieID TEXT,
						sessionExpires DATETIME DEFAULT NULL,
						sessionVal LONGTEXT,
					';
					break;
			}

			return $sql;
		}

		function dbDelta($sql)
		{
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			// Remove any blank lines - dbDelta is fussy and doesn't like them ...'
			$sql = preg_replace('/^[ \t]*[\r\n]+/m', '', $sql);
			$this->ShowSQL($sql);
			dbDelta($sql);
		}

		function tableExists($table_name)
		{
			global $wpdb;

			$sql = "SHOW TABLES LIKE '$table_name'";
			$rslt = $this->get_results($sql);

			return ( count($rslt) > 0 );
		}

		static function GetSafeString($paramId, $defaultVal = '')
		{
			$rtnVal = StageShowLibHTTPIO::GetRequestedString($paramId, $defaultVal);
			$rtnVal = self::_real_escape($rtnVal);
			return $rtnVal;
		}

		static function _real_escape($string)
		{
			global $wpdb;
			return $wpdb->_real_escape($string);
		}

		function GetInsertId()
		{
			global $wpdb;

			return $wpdb->insert_id;
		}

		function getColumnSpec($table_name, $colName)
		{
			$sql = "SHOW COLUMNS FROM $table_name WHERE field = '$colName'";

			$typesArray = $this->get_results($sql);

			return isset($typesArray[0]) ? $typesArray[0] : '';
		}

		function DeleteColumnIfExists($table_name, $colName)
		{
			if (!$this->IfColumnExists($table_name, $colName))
				return;

			$this->deleteColumn($table_name, $colName);
		}

		function renameColumn($table_name, $oldColName, $newColName)
		{
			if (!$this->IfColumnExists($table_name, $oldColName))
				return "Does Not Exist";

 			$sql = "ALTER TABLE $table_name RENAME COLUMN $oldColName TO $newColName";

			$this->query($sql);
			return "OK";
		}

		function deleteColumn($table_name, $colName)
		{
 			$sql = "ALTER TABLE $table_name DROP $colName";

			$this->query($sql);
			return "OK";
		}

		function IfColumnExists($table_name, $colName)
		{
			if (!$this->tableExists($table_name)) return false;

			$colSpec = $this->getColumnSpec($table_name, $colName);
			return (isset($colSpec->Field));
		}

		function MergeColumns($table_name, $indexColName, $fromColName, $toColName)
		{
			// Crude way to merge two DB columns
			$sql = "SELECT $indexColName, $fromColName, $toColName FROM $table_name";
			$results = $this->get_results($sql);
			
			foreach ($results as $result)
			{
				if (is_null($result->$fromColName)) continue;
					
				if (StageShowLibMigratePHPClass::Safe_strlen($result->$fromColName) == 0) continue;
					
				$newVal = $result->$fromColName.$result->$toColName;
				$indexID = $result->$indexColName;
				
				$sql  = "UPDATE $table_name ";
				$sql .= "SET $toColName=\"$newVal\" ";
				$sql .= "WHERE $indexColName=$indexID ";
				$this->query($sql);				
			}
			
			// Remove the redundant column
			$this->deleteColumn($table_name, $fromColName);
		}
		
		function CopyTable($src_table_name, $dest_table_name, $dropTable = false)
		{
			global $wpdb;

			$sql  = "CREATE TABLE ".$dest_table_name.' ';
			$sql .= "SELECT * FROM ".$src_table_name.';';

			$this->ShowSQL($sql);
			$wpdb->query($sql);
		}

		function DropTable($table_name)
		{
			global $wpdb;

			$sql = "DROP TABLE IF EXISTS $table_name";
			$this->ShowSQL($sql);
			$wpdb->query($sql);
		}

		function TruncateTable($table_name)
		{
			global $wpdb;

			$sql = "TRUNCATE TABLE $table_name";
			$this->ShowSQL($sql);
			$wpdb->query($sql);
		}

		static function FirstRecord($results)
		{
			if (is_null($results)) return false;
			
			if (count($results) == 0) return false;
				
			return $results[0];
		}
		
		function StartTransaction()
		{
			$sql = "START TRANSACTION";
			$this->query($sql);
		}

		function RollbackTransaction()
		{
			$sql = "ROLLBACK";
			$this->query($sql);
		}

		function GetSearchSQL($searchtext, $searchFields)
		{
			$this->searchText = $searchtext;
			if ($searchtext == '') return '';

			$sqlWhere = '(';
			$sqlOr = '';
			foreach ($searchFields as $searchField)
			{
				$sqlWhere .= $sqlOr;
				$sqlWhere .= $searchField.' LIKE "%'.$searchtext.'%"';
				$sqlOr = ' OR ';
			}
			$sqlWhere .= ')';

			$this->searchSQL = $sqlWhere;
			return $sqlWhere;
		}

		function AddSearchParam(&$currentURL)
		{
			if (isset($this->searchText) && ($this->searchText != ''))
			{
				$currentURL .= '&lastsalessearch='.$this->searchText;
			}
		}

		function getOptionsFromDB()
		{
			if (!isset($this->opts['CfgOptionsID']))
			{
				StageShowLibEscapingClass::Safe_EchoHTML('CfgOptionsID must be defined<br>');
				exit;
			}

			if (!isset($this->opts['DbgOptionsID']))
			{
				StageShowLibEscapingClass::Safe_EchoHTML('DbgOptionsID must be defined<br>');
				exit;
			}

			// Get current values from MySQL
			$currOptions = $this->ReadSettings($this->opts['CfgOptionsID']);
			if (StageShowLibUtilsClass::IsElementSet('get', 'nodbg'))
			{
				$this->dbgOptions = array();
				$this->WriteSettings($this->opts['DbgOptionsID'], $this->dbgOptions);
			}
			else
			{
				$this->dbgOptions = $this->ReadSettings($this->opts['DbgOptionsID']);
			}

			return $currOptions;
		}

		function getOptions($childOptions = array())
		{
			// Initialise settings array with default values
			$ourOptions = array(
				'ActivationCount' => 0,
				'LastVersion' => '',

				'OrganisationID' => get_bloginfo('name'),

				'BccEMailsToAdmin' => true,
				'UseCurrencySymbol' => false,

				'LogsFolderPath' => 'logs',
				'PageLength' => STAGESHOWLIB_EVENTS_PER_PAGE,

				'Unused_EndOfList' => ''
			);

			$ourOptions = array_merge($ourOptions, $childOptions);

			// Get current values from MySQL
			$currOptions = $this->getOptionsFromDB();

			// Now update defaults with values from DB
			if (!empty($currOptions))
			{
				$saveToDB = false;
				foreach ($currOptions as $key => $option)
					$ourOptions[$key] = $option;
			}
			else
			{
				// New options ... save to DB
				$saveToDB = true;
			}

			$this->pluginInfo['Name'] = $this->get_name();
			$this->pluginInfo['Version'] = $this->get_version();
			$this->pluginInfo['Author'] = $this->get_author();
			$this->pluginInfo['PluginURI'] = $this->get_pluginURI();
			$ourOptions['pluginInfo'] = $this->pluginInfo;

			$this->adminOptions = $ourOptions;

			if ($saveToDB)
				$this->saveOptions();// Saving Options - in getOptions functions


			return $ourOptions;
		}

		function GetAllSettingsList()
		{
			$ourOptions = $this->getOptions();

			$current = new stdClass;

			foreach ($ourOptions as $key => $value)
			{
				$current->$key = $value;
			}

			$settingsList[0] = $current;
			return $settingsList;
		}

		function getDbgOption($optionID)
		{
			$rtnVal = '';
			return $rtnVal;
		}

		function setOption($optionID, $optionValue, $optionClass = self::ADMIN_SETTING)
		{
			switch ($optionClass)
			{
				case self::ADMIN_SETTING:
					$this->adminOptions[$optionID] = $optionValue;
					break;

				case self::DEBUG_SETTING:
					$this->dbgOptions[$optionID] = $optionValue;
					break;

				default:
					return '';
			}

			return $optionValue;
		}

		function isDbgOptionSet($optionID)
		{
			$rtnVal = false;

			return $rtnVal;
		}

		function isOptionSet($optionID, $optionClass = self::ADMIN_SETTING)
		{
			$value = $this->getOption($optionID, $optionClass);
			if ($value == '')
				return false;

			return true;
		}

		// Saves the admin options to the options data table
		function saveOptions()
		{
			$this->WriteSettings($this->opts['CfgOptionsID'], $this->adminOptions);
			$this->WriteSettings($this->opts['DbgOptionsID'], $this->dbgOptions);
		}

		function NormaliseSettings($settings)
		{
			return $settings;
		}

		function ReadSettings($optionName)
		{
			static $firstTime = true;

			if ($firstTime)
			{
				if (!$this->tableExists($this->DBTables->Settings))
				{
					$dbPrefix = $this->getTablePrefix();
					$defaultSettingsTable = $dbPrefix.'mjslibOptions';
					if ( ($this->DBTables->Settings != $defaultSettingsTable)
					  && ($this->tableExists($defaultSettingsTable)) )
					{
						$this->CopyTable($defaultSettingsTable, $this->DBTables->Settings);
						$this->DropTable($defaultSettingsTable);
					}
					else
						$this->createDBTable($this->DBTables->Settings, 'optionID');
				}
				$firstTime = false;
			}

			$sql = "SELECT * FROM ".$this->DBTables->Settings." WHERE option_name='$optionName'";
			$rslt = $this->get_results($sql);
			if (count($rslt) == 0)
			{
				$settings = get_option($optionName, null);
				if ($settings === null)
				{
					$settings = get_option($optionName.'_', null);
				}
				if ($settings === null)
				{
					$settings = array();
				}
				$serializedValue = addslashes(serialize($settings));
				$sql  = 'INSERT INTO '.$this->DBTables->Settings.'(option_name, option_value)';
				$sql .= ' VALUES("'.$optionName.'", "'.$serializedValue.'")';
				$this->query($sql);

				delete_option($optionName);
				delete_option($optionName.'_');
			}
			else
			{
				$settings = $rslt[0]->option_value;
				$settings = unserialize($settings);
			}
			return $settings;
		}

		function WriteSettings($optionName, $settings)
		{
			$settings = addslashes(serialize($settings));
			$sql  = "UPDATE ".$this->DBTables->Settings." SET ";
			$sql .= 'option_value = "'.$settings.'" ';
			$sql .= "WHERE option_name='$optionName'";
			$this->query($sql);
		}

		function DeleteSettings($optionName)
		{
			delete_option($optionName);		// Settings were in wp_options

			$sql  = "DELETE FROM ".$this->DBTables->Settings." ";
			$sql .= "WHERE option_name='$optionName'";
			$this->query($sql);
		}

		function dev_ShowTrolley()
		{
			$rtnVal = false;

			if ($this->isDbgOptionSet('Dev_ShowTrolley') || $this->IsSessionElemSet('stageshowlib_debug_trolley'))
			{
				if ($this->getDbgOption('Dev_ShowCallStack') || $this->IsSessionElemSet('stageshowlib_debug_stack'))
				{
					StageShowLibUtilsClass::ShowCallStack();
				}
				$rtnVal = true;
			}

			return $rtnVal;
		}

		function GetRowsPerPage()
		{
			if (isset($this->adminOptions['PageLength']))
				$rowsPerPage = $this->adminOptions['PageLength'];
			else
				$rowsPerPage = STAGESHOWLIB_EVENTS_PER_PAGE;

			return $rowsPerPage;
		}

		function clearAll()
		{
			$this->DropTable($this->DBTables->Settings);
		}

		function createDB($dropTable = false)
		{
		}

		function ArrayValsToDefine($optionsList, $indent = '    ')
			{
			$defines = " array(\n";
				foreach ($optionsList as $optionID => $optionValue)
				{
				if (is_array($optionValue))
				{
					$optionValue = $this->ArrayValsToDefine($optionValue, $indent.'    ');
				}
				else
				{
					$optionValue = "'$optionValue'";
				}
				$defines .= "$indent'$optionID' => $optionValue,\n";
			}

			$defines .= "$indent)";

			return $defines;
		}

		function OptionsToDefines($globalVarId, $optionsList)
		{
			$optionID = '$'.$globalVarId;

			$defines = '$'.$globalVarId." = ";

			$defines .= $this->ArrayValsToDefine($optionsList).";\n\n";

			return $defines;
		}

		static function StripURLRoot($url)
		{
			$url = StageShowLibMigratePHPClass::Safe_substr($url, StageShowLibMigratePHPClass::Safe_strpos($url, '://')+3);
			return $url;
		}

		static function GetTimeFormat()
		{
			if (defined('STAGESHOWLIB_TIME_BOXOFFICE_FORMAT'))
				$timeFormat = STAGESHOWLIB_TIME_BOXOFFICE_FORMAT;
			else
				// Use Wordpress Time Format
				$timeFormat = get_option( 'time_format' );

			return $timeFormat;
		}

		static function GetDateFormat()
		{
			if (defined('STAGESHOWLIB_DATE_BOXOFFICE_FORMAT'))
				$dateFormat = STAGESHOWLIB_DATE_BOXOFFICE_FORMAT;
			else
				// Use Wordpress Date Format
				$dateFormat = get_option( 'date_format' );

			return $dateFormat;
		}

		static function GetDateTimeFormat()
		{
			if (defined('STAGESHOWLIB_DATETIME_BOXOFFICE_FORMAT'))
				$dateFormat = STAGESHOWLIB_DATETIME_BOXOFFICE_FORMAT;
			else
				// Use Wordpress Date and Time Format
				$dateFormat = get_option( 'date_format' ).' '.get_option( 'time_format' );

			return $dateFormat;
		}

		static function GetLocalDateTime($timestamp = 0)
		{
			if ($timestamp == 0) $timestamp = current_time('timestamp');
				
			$localTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, $timestamp);
			return $localTime;
		}

		function get_JSandCSSver()
		{
			static $ver = false;

			if ($ver == false)
			{
				if ($this->isDbgOptionSet('Dev_DisableJSCache'))
					$ver = time();
				else
					$ver = $this->get_version();
			}

			return $ver;
		}

		function enqueue_style( $handle, $src = false, $deps = array(), $ver = false, $media = 'all' )
		{
			$ver = $this->get_JSandCSSver();
			wp_enqueue_style($handle, $src, $deps, $ver, $media);
		}

		function enqueue_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false)
		{
			$ver = $this->get_JSandCSSver();
			wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
		}

		function DoTemplateLoop($section, $loopType, $dbRecords)
		{
			$emailContent = '';

			$subSections = explode('[nextloop]', $section);
			$subCount = count($subSections);
			
			switch ($loopType)
			{
				case '[startloop]':
					$noOfRecords = count($dbRecords);
					$subIndex = 0;
					for ($index = 0;; $index++, $subIndex++)
					{
						if ($subIndex >= $subCount) 
							$subIndex = 0;

						if ($index < $noOfRecords)
						{
							$dbRecord = $dbRecords[$index];
						}
						else
						{
							if ($subIndex == 0) break;
								
							// Overflow!
							if (!isset($emptyRecord))
							{
								$emptyRecord = new stdClass();
								foreach ($dbRecord as $recId => $recVal)
								{
									$emptyRecord->$recId = "";
								}
							}
							$dbRecord = $emptyRecord;						
						}

						$emailContent .= $this->AddEventToTemplate($subSections[$subIndex], $dbRecord);
					}
					break;

				default:
					$emailContent = "<br><strong>Unknown Loop Definition in Template ($loopType)</strong><br><br>";
					break;
			}

			return $emailContent;
		}

		function AddFieldsToTemplate($dbRecord, $mailTemplate, &$EMailSubject, &$emailContent)
		{
			if (!isset($dbRecord[0]))
			{
				// Add a dummy entry
				$dbRecord[0] = new stdClass();
			}

			$emailContent = '';
			// Find the line with the open php entry then find the end of the line
			$posnPHP = StageShowLibMigratePHPClass::Safe_stripos($mailTemplate, '<?php');
			if ($posnPHP !== false) $posnPHP = StageShowLibMigratePHPClass::Safe_strpos($mailTemplate, "\n", $posnPHP);
			if ($posnPHP !== false) $posnEOL = StageShowLibMigratePHPClass::Safe_strpos($mailTemplate, "\n", $posnPHP+1);
			if (($posnPHP !== false) && ($posnEOL !== false))
			{
				$EMailSubject = $this->AddEventToTemplate(StageShowLibMigratePHPClass::Safe_substr($mailTemplate, $posnPHP+1, $posnEOL-$posnPHP-1), $dbRecord[0]);
				$mailTemplate = StageShowLibMigratePHPClass::Safe_substr($mailTemplate, $posnEOL);
			}

			// Find the line with the close php entry then find the start of the line
			$posnPHP = StageShowLibMigratePHPClass::Safe_stripos($mailTemplate, '?>');
			if ($posnPHP !== false) $posnPHP = StageShowLibMigratePHPClass::Safe_strrpos(StageShowLibMigratePHPClass::Safe_substr($mailTemplate, 0, $posnPHP), "\n");
			if ($posnPHP !== false) $mailTemplate = StageShowLibMigratePHPClass::Safe_substr($mailTemplate, 0, $posnPHP);

			$loopCount = 0;
			for (; $loopCount < 10; $loopCount++)
			{
				if (preg_match('/(\[[a-zA-Z0-9]*loop\])/', $mailTemplate, $matches) != 1)
					break;

				$loopStart = StageShowLibMigratePHPClass::Safe_stripos($mailTemplate, $matches[0]);
				$loopEnd = StageShowLibMigratePHPClass::Safe_stripos($mailTemplate, '[endloop]');
				if (($loopStart === false) || ($loopEnd === false))
					break;

				$beforeLoop = StageShowLibMigratePHPClass::Safe_substr($mailTemplate, 0, $loopStart);

				$loopStart += StageShowLibMigratePHPClass::Safe_strlen($matches[0]);
				$loopLen = $loopEnd - $loopStart;

				$loopSection = StageShowLibMigratePHPClass::Safe_substr($mailTemplate, $loopStart, $loopLen);

				$loopEnd += StageShowLibMigratePHPClass::Safe_strlen('[endloop]');
				$afterLoop = StageShowLibMigratePHPClass::Safe_substr($mailTemplate, $loopEnd);

				$loopOutput = $this->DoTemplateLoop($loopSection, $matches[0], $dbRecord);

				$mailTemplate  = $beforeLoop;
				$mailTemplate .= $loopOutput;
				$mailTemplate .= $afterLoop;

			}

			// Process the rest of the mail template
			$emailContent = $this->AddEventToTemplate($mailTemplate, $dbRecord[0]);

			return 'OK';
		}

		function RetrieveEventElement($tag, $field, &$event)
		{
			return $event->$field;
		}

		function DoTemplateConditionals(&$EMailTemplate, $event)
		{
			$if_marker = '[if';
			$if_text = $if_marker.' ';
			$ifnot_text = $if_marker.'not ';
			$endif_text = '[endif]';
			$else_text = '[else]';
			
			$if_len = StageShowLibMigratePHPClass::Safe_strlen($if_text);
			$ifnot_len = StageShowLibMigratePHPClass::Safe_strlen($ifnot_text);
			$endif_len = StageShowLibMigratePHPClass::Safe_strlen($endif_text);
			$else_len = StageShowLibMigratePHPClass::Safe_strlen($else_text);
			
			$offset = 0;
			$changes = 0;
			
			// Loop while there are if markers
			while(true)
			{
				// Search backwards for if marker
				$nextPosn = StageShowLibMigratePHPClass::Safe_strrpos($EMailTemplate, $if_marker, $offset);
				if ($nextPosn === false) break;
					
				// Determine type of if statement 
				if (StageShowLibMigratePHPClass::Safe_substr($EMailTemplate, $nextPosn, $if_len) === $if_text)
				{
					$ifTagStartPosn = $nextPosn + $if_len;
					$dbCond = true;
				}
				else if (StageShowLibMigratePHPClass::Safe_substr($EMailTemplate, $nextPosn, $ifnot_len) === $ifnot_text)
				{
					$ifTagStartPosn = $nextPosn + $ifnot_len;
					$dbCond = false;
				}
				else
				{
					$offset = $nextPosn - StageShowLibMigratePHPClass::Safe_strlen($EMailTemplate) - 1;
					continue;
				}
				
				$TemplateLen = StageShowLibMigratePHPClass::Safe_strlen($EMailTemplate);
				
				$ifTagEndPosn = StageShowLibMigratePHPClass::Safe_strpos($EMailTemplate, ']', $ifTagStartPosn);
				$dbField = StageShowLibMigratePHPClass::Safe_substr($EMailTemplate, $ifTagStartPosn, $ifTagEndPosn-$ifTagStartPosn);

				// Search for end marker
				$sectionEnd = StageShowLibMigratePHPClass::Safe_strpos($EMailTemplate, $endif_text, $nextPosn);
				if ($sectionEnd === false) 
					return __('Missing Conditional end marker', 'stageshow');
				
				// Search forwards for else marker (assumes later else markers have been processed)
				$elseStart = StageShowLibMigratePHPClass::Safe_strpos($EMailTemplate, $else_text, $nextPosn);
				if ($elseStart === false)
				{
					$ifTrueStart = $ifTagEndPosn+1;
					$ifTrueEnd = $sectionEnd;
					$ifFalseStart = $sectionEnd;
					$ifFalseEnd = $sectionEnd;
				}
				else
				{
					$ifTrueStart = $ifTagEndPosn+1;
					$ifTrueEnd = $elseStart;
					$ifFalseStart = $elseStart + $else_len;
					$ifFalseEnd = $sectionEnd;
				}
				$endPosn = $sectionEnd + $endif_len;

				$dbSet = false;
				if (isset($event->$dbField))
				{
					if (is_numeric($event->$dbField))
					{
						$dbSet = $event->$dbField > 0;
					}
					else
					{
						$dbSet = (StageShowLibMigratePHPClass::Safe_strlen($event->$dbField) != 0);
					}
				}

				$startText = StageShowLibMigratePHPClass::Safe_substr($EMailTemplate, 0, $nextPosn);
				$endText = StageShowLibMigratePHPClass::Safe_substr($EMailTemplate, $endPosn, $TemplateLen);
				
				if ($dbSet == $dbCond)
				{
					// Replace the section including the conditional commands with the matched section
					$midText = StageShowLibMigratePHPClass::Safe_substr($EMailTemplate, $ifTrueStart, $ifTrueEnd-$ifTrueStart);
				}
				else
				{
					// Replace the section with the else section (if it exists)
					$midText = StageShowLibMigratePHPClass::Safe_substr($EMailTemplate, $ifFalseStart, $ifFalseEnd-$ifFalseStart);
				}

				$EMailTemplate = $startText.$midText.$endText;
				$changes++;
						
				$offset = 0 - StageShowLibMigratePHPClass::Safe_strlen($midText) - StageShowLibMigratePHPClass::Safe_strlen($endText);
			}
			
		}
		
		function AddEventToTemplate($EMailTemplate, $event)
		{
			foreach ($event as $key => $value)
			{
				$tag = '['.$key.']';
				$value = $this->RetrieveEventElement($tag, $key, $event);
				$EMailTemplate = StageShowLibMigratePHPClass::Safe_str_replace($tag, $value, $EMailTemplate);
			}

			$EMailTemplate = $this->DoEmbeddedImage($EMailTemplate, 'logoimg', STAGESHOWLIB_LOGOIMAGE_OPTIONID);

			return $EMailTemplate;
		}

		function DoEmbeddedImage($eMailFields, $fieldMarker, $optionID)
		{
			// Can be overloaded in derived class
			return $eMailFields;
		}

		function GetAdminEMail()
		{
			return '';
		}

		function GetServerEmail()
		{
			return '';
		}

		function ReadTemplateFile($Filepath)
		{
			$hfile = fopen($Filepath,"r");
			if ($hfile != 0)
			{
				$fileLen = filesize($Filepath);
				if ($fileLen > 0)
					$fileContents = fread($hfile, $fileLen);
				else
					$fileContents = '';
				fclose($hfile);
			}
			else
			{
				StageShowLibEscapingClass::Safe_EchoHTML("Error reading $Filepath<br>\n");
				$fileContents = '';
			}

			return $fileContents;
		}

		function ReadEMailTemplateFile($Filepath)
		{
			// Added check for template file lines beginning with a dot '.'
			// Add a space before to prevent any problem with SMTP "dot stuffing"
			// See https://tools.ietf.org/html/rfc5321#section-4.5.2
			$template = $this->ReadTemplateFile($Filepath);
			$this->mailTemplate_origLen = StageShowLibMigratePHPClass::Safe_strlen($template);
			$template = preg_replace("/^(\..*)/m", "  $1", $template);
			$this->mailTemplate_newLen = StageShowLibMigratePHPClass::Safe_strlen($template);

			if ( ($this->mailTemplate_origLen != $this->mailTemplate_newLen)
			  && (StageShowLibUtilsClass::IsElementSet('post', 'EMailSale_DebugEnabled')) )
			{
				StageShowLibEscapingClass::Safe_EchoHTML("************************************************************************<br>\n");
				StageShowLibEscapingClass::Safe_EchoHTML(__("WARNING: EMail Template contains one or mores lines with leading dots ('.')", 'stageshow')."<br>\n");
				StageShowLibEscapingClass::Safe_EchoHTML("************************************************************************<br><br>\n");
			}

			return $template;
		}

		function AddRecordToTemplate($dbRecord, $templatePath, &$EMailSubject, &$emailContent)
		{
			$EMailSubject = "EMail Subject NOT Defined";
			$mailTemplate = $this->ReadEMailTemplateFile($templatePath);
			if (StageShowLibMigratePHPClass::Safe_strlen($mailTemplate) == 0)
				return "EMail Template Not Found ($templatePath)";

			$posnEndPHP = StageShowLibMigratePHPClass::Safe_stripos($mailTemplate, '?>');
			$templateFooter = StageShowLibMigratePHPClass::Safe_substr($mailTemplate, $posnEndPHP);
			$footerLines = explode("\n", $templateFooter);
			foreach ($footerLines as $footerLine)
			{
				$fields = explode(':', $footerLine);
				if (count($fields) > 1)
				{
					switch ($fields[0])
					{
						case 'Attach':
							$filename = StageShowLibMigratePHPClass::Safe_trim($fields[1]);

							$filepath = dirname(WP_CONTENT_DIR).'/'.$filename;
							if (isset($this->emailObj))
							{
								$this->emailObj->AddAttachment($filepath);
							}
							else
							{
								$this->emailAttachments[] = $filepath;
							}
							break;

						default:
							break;
					}

				}
			}

			$status = $this->AddFieldsToTemplate($dbRecord, $mailTemplate, $EMailSubject, $emailContent);
			$emailContent = apply_filters('stageshow'.'_filter_emailbody', $emailContent, $dbRecord[0]);
			return $status;
		}

		function BuildEMailFromTemplate($eventRecord, $templatePath)
		{
			$EMailSubject = '';
			$emailContent = '';

			include $this->emailClassFilePath;
			$this->emailObj = new $this->emailObjClass($this);
			$this->emailObj->adminEMail = $this->GetAdminEMail();

			$rtnval['status'] = $this->AddRecordToTemplate($eventRecord, $templatePath, $EMailSubject, $emailContent);
			if ($rtnval['status'] == 'OK')
			{
				$rtnval['subject'] = $EMailSubject;
				$rtnval['email'] = $emailContent;
			}

			return $rtnval;
		}

		function SendEMailWithDefaults($eventRecord, $EMailSubject, $EMailContent, $EMailTo = '', $headers = '')
		{
			// Get email address and organisation name from settings
			$EMailFrom = $this->GetServerEmail();

			$rtnStatus = $this->emailObj->sendMail($EMailTo, $EMailFrom, $EMailSubject, $EMailContent, $headers);

			return $rtnStatus;
		}

		function SendEMailFromTemplate($eventRecord, $templatePath, $EMailTo = '')
		{
			$emailRslt = $this->BuildEMailFromTemplate($eventRecord, $templatePath, $EMailTo);
			$rtnstatus = $emailRslt['status'];

			if ($rtnstatus == 'OK')
			{
				$rtnstatus = $this->SendEMailWithDefaults($eventRecord, $emailRslt['subject'], $emailRslt['email'], $EMailTo);
			}

			return $rtnstatus;
		}

		function SendEMailByTemplateID($eventRecord, $templateID, $folder, $EMailTo = '')
		{
			// EMail Template defaults to templates folder
			$templateRoot = STAGESHOWLIB_UPLOADS_PATH.'/'.$folder.'/';
			$templatePath = $templateRoot.$this->adminOptions[$templateID];

			return $this->SendEMailFromTemplate($eventRecord, $templatePath, $EMailTo);
		}

		function OutputDebugStart()
		{
			if (!isset($this->debugToLog))
				$this->debugToLog = $this->isDbgOptionSet('Dev_DebugToLog');
			if ($this->debugToLog) ob_start();
		}

		function OutputDebugEnd()
		{
			if ($this->debugToLog)
			{
				$debugOutput = ob_get_contents();
				ob_end_clean();
				if ($debugOutput != '')
				{
					$this->AddToStampedCommsLog($debugOutput);
					if (StageShowLibMigratePHPClass::Safe_strpos($debugOutput, 'id="message"') !== false)
						StageShowLibEscapingClass::Safe_EchoHTML($debugOutput);
				}
			}
		}

		function ShowSQL($sql, $values = null)
		{
			if (!$this->isDbgOptionSet('Dev_ShowSQL'))
			{
				return;
			}

			$this->OutputDebugStart();
			//if (!$this->isDbgOptionSet('Dev_ShowCaller'))
			{
				ob_start();
				debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
				$callStack = ob_get_contents();
				ob_end_clean();

				$callStack = preg_split('/#[0-9]+[ ]+/', $callStack, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
				$caller = explode("(", $callStack[2]);
				$caller = StageShowLibMigratePHPClass::Safe_str_replace("->", "::", $caller[0]);
				StageShowLibEscapingClass::Safe_EchoHTML("SQL Caller: $caller() \n");
			}

			parent::ShowSQL($sql, $values);
			$this->OutputDebugEnd();
		}

		function show_results($results)
		{
			if ( !$this->isDbgOptionSet('Dev_ShowSQL')
			  && !$this->isDbgOptionSet('Dev_ShowDBOutput') )
			{
				return;
			}

			$this->OutputDebugStart();
			parent::show_results($results);
			$this->OutputDebugEnd();
		}

		function show_cache($results, $id='')
		{
			if ( !$this->isDbgOptionSet('Dev_DebugToLog')
			  || !$this->isDbgOptionSet('Dev_ShowDBOutput'))
			  	return;

			if (function_exists('wp_get_current_user'))
			{
				if (!$this->isSysAdmin())
					return;
			}

			$this->OutputDebugStart();
			if ($this->isDbgOptionSet('Dev_ShowCallStack'))
				StageShowLibUtilsClass::ShowCallStack();
			StageShowLibEscapingClass::Safe_EchoHTML("<br>Cache: $id: ".print_r($results, true)."<br>\n");
			$this->OutputDebugEnd();
		}

		function query($sql)
		{
			if ( !$this->isDbgOptionSet('Dev_DebugToLog')
			  || !$this->isDbgOptionSet('Dev_ShowDBOutput'))
			  	return parent::query($sql);

			$this->OutputDebugStart();
			$result = parent::query($sql);
			$this->OutputDebugEnd();

			return $result;
		}


		function AddToStampedCommsLog($logMessage)
		{
			return $this->WriteCommsLog($logMessage, true);
		}

		function WriteCommsLog($logMessage, $addTimestamp = false, $mode = StageShowLibLogFileClass::ForAppending)
		{
			$logMessage .= "\n";

			if (!isset($this->logFileObj))
			{
				// Create log file using mode passed in call
				$LogsFolder = ABSPATH.$this->getOption('LogsFolderPath');
				$this->logFileObj = new StageShowLibLogFileClass($LogsFolder);
				$this->logFileObj->LogToFile(STAGESHOWLIB_FILENAME_COMMSLOG, '', $mode);
				$mode = StageShowLibLogFileClass::ForAppending;
			}

			if ($addTimestamp)
				$logMessage = current_time('D, d M y H:i:s').' '.$logMessage;

			$this->logFileObj->LogToFile(STAGESHOWLIB_FILENAME_COMMSLOG, $logMessage, $mode);
		}

		function ClearCommsLog()
		{
			return $this->WriteCommsLog('Log Cleared', true, StageShowLibLogFileClass::ForWriting);
		}

		function GetCommsLog()
		{
			$LogsFolder = ABSPATH.$this->getOption('LogsFolderPath');
			$logsPath = $LogsFolder.'/'.STAGESHOWLIB_FILENAME_COMMSLOG;
			return file_get_contents($logsPath);
		}

		function GetCommsLogSize()
		{
			$LogsFolder = ABSPATH.$this->getOption('LogsFolderPath');
			$logsPath = $LogsFolder.'/'.STAGESHOWLIB_FILENAME_COMMSLOG;
			if (!file_exists($logsPath)) return "0";
			$logSize = filesize($logsPath);
			if ($logSize === false) return "0";

			return round($logSize/1024, 2).'k';
		}

		function SetSessionID($sessionID)
		{
			$this->sessionCookieID = $sessionID;
		}

		function GetSessionCookie()
		{
			if (!isset($this->sessionCookieID))
			{
				// Get the PHPSESSIONID from server ... then close session
				session_start();
				$this->sessionCookieID = session_id();
				session_write_close();
				
				if ($this->sessionCookieID === false)
				{
					$this->lastSessionErr = STAGESHOWLIB_SESSIONERR_FALSE;
					$this->sessionCookieID = null;
				}
				else if ($this->sessionCookieID == '')
				{
					$this->lastSessionErr = STAGESHOWLIB_SESSIONERR_INACTIVE;
					$this->sessionCookieID = null;
				}
			}
				
		}

		function GetSessionID()
		{
			$addSession = false;

			if (!isset($this->sessionTableChecked))
			{
				// Check if Session Table Exists
				if (is_null($this->sessionCookieID))
				{
					// No sessionID ... just initialise sessionVal and return
					$this->sessionVal = array();
				}
				else if (!$this->tableExists($this->DBTables->Sessions))
				{
					$this->createDBTable($this->DBTables->Sessions, 'sessionID');
					$addSession = true;
					$this->lastSessionErr = STAGESHOWLIB_SESSIONERR_NOTABLE;
				}
				else
				{
					$this->PurgeSessions();
					$this->sessionVal = $this->ReadSession();
					if ($this->sessionVal == null)
					{
						$addSession = true;
						$this->lastSessionErr = STAGESHOWLIB_SESSIONERR_NOMATCH;
					}
				}
	
				if ($addSession)
				{
					// Session Table Entry Missing - Write an empty Session Entry
					$ts = current_time( 'timestamp' );
					$sessionExpires = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, $ts + STAGESHOWLIB_SESSION_TIMEOUT);
					
					$this->sessionVal = array();
					//$this->sessionVal[STAGESHOWLIB_SESSION_VALIDATOR] = true;
					$sessionValSer = StageShowLibMigratePHPClass::Safe_str_replace('"', '\"', serialize($this->sessionVal));
					
					$sql  = 'INSERT INTO '.$this->DBTables->Sessions;
					$sql .= ' (sessionCookieID, sessionExpires, sessionVal) ';
					$sql .= 'VALUES("'.$this->sessionCookieID.'", "'.$sessionExpires.'", "'.$sessionValSer.'")';
					$this->query($sql);
				}

				$this->sessionTableChecked = true;
			}
			return $this->sessionCookieID;
		}

		function GetSession()
		{				
			if (!isset($this->sessionVal))
			{
				$this->GetSessionID();
			}

			if (!isset($this->sessionVal))
			{
				$this->sessionVal = $this->ReadSession();
			}

			return $this->sessionVal;
		}

		function PurgeSessions()
		{
			// Remove any expired sessions
			$currDateTime = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, current_time( 'timestamp' ));

			$sql  = 'DELETE FROM '.$this->DBTables->Sessions.' ';
			$sql .= 'WHERE sessionExpires < "'.$currDateTime.'"';

			$this->query($sql);
		}

		function ReadSession()
		{
			// Get the Session Array and return it ....
			$sql  = 'SELECT * FROM '.$this->DBTables->Sessions.' ';
			$sql .= ' WHERE sessionCookieID="'.$this->sessionCookieID.'"';
			$sql .= ' ORDER BY sessionID DESC LIMIT 1';
			$sessionEntries = $this->get_results($sql);
			if (count($sessionEntries) == 0)
				return null;

			$rtnVal = unserialize($sessionEntries[0]->sessionVal);

			return $rtnVal;
		}

		function IsSessionElemSet($elemId)
		{
			$this->GetSession();

			return isset($this->sessionVal[$elemId]);
		}

		function GetSessionElem($elemId)
		{
			$this->GetSession();

			if (!$this->sessionVal[$elemId])
				return null;

			return $this->sessionVal[$elemId];
		}

		function SetSessionElem($elemId, $elemVal, $writeToDB = true)
		{
			$this->GetSession();

			$this->sessionVal[$elemId] = $elemVal;

			if ($writeToDB)
				$this->WriteSession();
		}

		function UnsetSessionElem($elemId)
		{
			$this->GetSession();

			unset($this->sessionVal[$elemId]);

			$this->WriteSession();
		}

		function WriteSession()
		{
			$this->GetSession();

			$sessionValSer = StageShowLibMigratePHPClass::Safe_str_replace('"', '\"', serialize($this->sessionVal));

			// Update the Session Expiry time
			$sessionExpires = date(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT, current_time( 'timestamp' ) + STAGESHOWLIB_SESSION_TIMEOUT);

			$sql  = 'UPDATE '.$this->DBTables->Sessions.' SET ';
			$sql .= 'sessionExpires = "'.$sessionExpires.'", sessionVal = "'.$sessionValSer.'" ';
			$sql .= 'WHERE sessionCookieID="'.$this->sessionCookieID.'" ';
			$sql .= 'ORDER BY sessionID DESC LIMIT 1';
			$this->query($sql);
		}

		function Output_PluginHelp($exHelpHTML = '')
		{
			$timezone = get_option('timezone_string');
			if ($timezone == '')
			{
				$settingsPageURL = $this->sid.'/wp-admin/options-general.php';
				$statusMsg = __('Timezone not set - Set it', 'stageshow')." <a href=$settingsPageURL>".__('Here', 'stageshow').'</a>';
				StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="error"><p>'.$statusMsg.'</p></div>');
			}
			
			$version = (defined('STAGESHOWLIB_SCREENSHOTSMODE')) ? '*.*' : $this->get_version();
			$phpinfoURL = STAGESHOWLIB_URL.'lib/phpinfo.php';
			
			StageShowLibEscapingClass::Safe_EchoHTML('<strong>'.__('Plugin', 'stageshow').':</strong> '.$this->get_pluginName()."<br>\n");			
			StageShowLibEscapingClass::Safe_EchoHTML('<strong>'.__('Version', 'stageshow').':</strong> '.$version."<br>\n");			

			if ($exHelpHTML != '') StageShowLibEscapingClass::Safe_EchoHTML($exHelpHTML);
				
			StageShowLibEscapingClass::Safe_EchoHTML('<strong>WP:</strong> '.$this->GetWPVersion()."<br>\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<strong>MySQL:</strong> '.$this->GetMySQLVersion()."<br>\n");
			StageShowLibEscapingClass::Safe_EchoHTML('<strong>PHP: </strong><a href="'.$phpinfoURL.'" target="_blank">'.PHP_VERSION."</a><br>\n");			
			StageShowLibEscapingClass::Safe_EchoHTML('<strong>'.__('Timezone', 'stageshow').':</strong> '.$timezone."<br>\n");			

			if (!$this->isDbgOptionSet('Dev_DisableTestMenus'))
				$this->ShowDebugModes();
		}
		
		function VerifyUpdates()
		{
// Dev code Start --------------------------
			$target = $this->GetVerfPath();
			$this->verfRslt = StageShowLibHTTPIO::HTTPGet($target);
// Dev code End --------------------------
		}
		
		function ConvertDateTimeForMYSQL($dateTime)
		{
			$dateObj = DateTime::createFromFormat(STAGESHOWLIB_DATETIMEFORMAT, $dateTime);
			
			return $dateObj->format(StageShowLibDBaseClass::MYSQL_DATETIME_FORMAT);
		}

		function WriteToLog($logId, $msg, $append = true)
		{
			$flags = ($append) ? FILE_APPEND : '';
			$logsPath = ABSPATH."/{$logId}.txt";
			
			$timestamp = current_time('timestamp');			
			$localTime = date('Y-m-d H:i:s', $timestamp);
	
			file_put_contents($logsPath, "{$localTime} - {$msg}\n", $flags);
		}

	}
}


