<?php
/*
Description: General Utilities Code

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

if (!class_exists('StageShowLibUtilsClass'))
{
	if (!defined('STAGESHOWLIB_PHPFOLDER_PERMS'))
		define('STAGESHOWLIB_PHPFOLDER_PERMS', 0755);

	if (!defined('STAGESHOWLIB_PHPFILE_PERMS'))
		define('STAGESHOWLIB_PHPFILE_PERMS', 0644);

	if (!defined('STAGESHOWLIB_LOGFOLDER_PERMS'))
		define('STAGESHOWLIB_LOGFOLDER_PERMS', 0700);

	if (!defined('STAGESHOWLIB_CALLBACK_BASENAME'))
		define('STAGESHOWLIB_CALLBACK_BASENAME', basename(dirname(dirname(__FILE__))).'_callback');

	class StageShowLibUtilsClass // Define class
	{
		static function GetSiteID()
		{
			$siteURL = get_option('siteurl');
			$slashPosn = StageShowLibMigratePHPClass::Safe_strrpos($siteURL, '/');
			$siteURL = StageShowLibMigratePHPClass::Safe_substr($siteURL, $slashPosn+1);

			return $siteURL;
		}

		static function GetHTTPTextElem($reqArray, $elementId = 'undef', $defaultValue = '')
		{
			$str = self::GetArrayElement($reqArray, $elementId, $defaultValue);
			$str = sanitize_text_field($str);
			$str = StageShowLibMigratePHPClass::Safe_stripslashes($str);
			return $str;
		}

		static function GetHTTPTextareaElem($reqArray, $elementId = 'undef', $defaultValue = '')
		{
			$str = self::GetArrayElement($reqArray, $elementId, $defaultValue);
			$str = sanitize_textarea_field($str);
			$str = StageShowLibMigratePHPClass::Safe_stripslashes($str);
			return $str;
		}

		static function stageshowlib_filterHTMLStyles($styles)
		{
			// Adds permitted styles to wp_kses()
		    $styles[] = 'left';
		    $styles[] = 'right';
		    $styles[] = 'top';
		    $styles[] = 'bottom';
		    $styles[] = 'display';

		    return $styles;
		}

		static function GetAllowedProtocols()
		{
			global $stageshowlib_allowedProtocols;

			if (!is_array($stageshowlib_allowedProtocols))
			{
				$stageshowlib_allowedProtocols = wp_allowed_protocols();
				$stageshowlib_allowedProtocols[] = 'data';
			}
			
			return $stageshowlib_allowedProtocols;
			
		}

		static function GetAllowedHTMLTags($extraTags = array())
		{
			global $stageshowlib_allowedHTML;

			if (!is_array($stageshowlib_allowedHTML))
			{
				add_filter( 'safe_style_css', array('StageShowLibUtilsClass', 'stageshowlib_filterHTMLStyles'));

				$kses_allowed = array_merge(wp_kses_allowed_html('post'), $extraTags);

				// form fields - input
				$my_allowed['input'] = array(

					'class' => true,
					'id'    => true,
					'name'  => true,
					'title'  => true,
					'value' => true,
					'size' => true,
					'src' => true,
					'maxlength' => true,
					'type'  => true,
					'autocomplete'  => true,
					'checked'  => true,
					'readonly'  => true,
				);

				// select options
				$my_allowed['option'] = array(
					'selected' => true,
					'value'  => true,
				);

				// select options
				$my_allowed['form'] = array(
					'id' => true,
					'name' => true,
					'method' => true,
					'action' => true,
				);

				// link
				$my_allowed['link'] = array(
					'id' => true,
					'href'   => true,
					'rel'   => true,
					'media'   => true,
				);

				// script
				$my_allowed['script'] = array(
					'id' => true,
					'src'   => true,
					'language'   => true,
					'type'   => true,
				);

				// select
				$my_allowed['select'] = array(
					'class'  => true,
					'id'     => true,
					'name'   => true,
					'value'  => true,
					'type'   => true,
				);

				// select
				$my_allowed['span'] = array(
					'class'  => true,
					'name'   => true,
				);

				// style
				$my_allowed['style'] = array(
					'types' => true,
				);

				$my_allowed['head'] = array(
				);

				$my_allowed['html'] = array(
				);

				$my_allowed['meta'] = array(
					'http-equiv'  => true,
					'content'  => true,
					'charset'  => true,
				);

				$my_allowed['body'] = array(
					'text'  => true,
					'bgcolor'  => true,
				);

				$tagsWithEvents = array('a', 'div', 'input', 'select', 'textarea', 'th', 'td');
				foreach ($tagsWithEvents as $tagsWithEvent)
				{
					$my_allowed[$tagsWithEvent]['onchange'] = true;
					$my_allowed[$tagsWithEvent]['onclick'] = true;
					$my_allowed[$tagsWithEvent]['onkeypress'] = true;
					$my_allowed[$tagsWithEvent]['onkeydown'] = true;
					$my_allowed[$tagsWithEvent]['onkeyup'] = true;
					$my_allowed[$tagsWithEvent]['onfocus'] = true;
				}

				foreach ($my_allowed as $tag => $elems)
				{
					if (!isset($kses_allowed[$tag]))
						$kses_allowed[$tag] = array();

					foreach ($elems as $elemID => $elem)
					{
						$kses_allowed[$tag][$elemID] = true;
					}
				}

				$stageshowlib_allowedHTML = $kses_allowed;
			}

			return $stageshowlib_allowedHTML;
		}

		static function GetHTTPTextHttpElem($reqArray, $elementId = 'undef', $defaultValue = '', $extraTags = array())
		{
			$str = self::GetArrayElement($reqArray, $elementId, $defaultValue);
			$str = StageShowLibMigratePHPClass::Safe_stripslashes($str);
			$htmlTags = self::GetAllowedHTMLTags($extraTags);
			$str = wp_kses($str, $htmlTags);
			return $str;
		}

		static private function GetHTTPJSONEncode($reqArray, $elementId)
		{
			// sanitize_text_field breaks serialised data - don't use it ...
			$str = self::GetArrayElement($reqArray, $elementId, '');

			// Strip the slashes and then use json_decode to sanitize the valueget the values
			$str = StageShowLibMigratePHPClass::Safe_stripslashes($str);
			$postVarsArray = json_decode($str);

			if ($postVarsArray === null)
			{
				return false;
			}

			return $postVarsArray;
		}

		static function GetHTTPJSONEncodedArray($reqArray, $elementId)
		{
			$rslt = array();

			if (is_string($reqArray)) $reqArray = &self::GetArrayFromId($reqArray);

			$jsonArray = self::GetHTTPJSONEncode($reqArray, $elementId);

			if (!is_array($jsonArray))
			{
				return false;
			}

			// Verify that all the elements are objects
			foreach($jsonArray as $postKey => $postVal)
			{
				if (!is_object($postVal))
				{
				}

				$nextElem = array();
				foreach ($postVal as $arrayKey => $arrayElem)
				{
					$nextElem[$arrayKey] = $arrayElem;
				}
				$rslt[] = $nextElem;
			}

			return $rslt;
		}

		static function GetHTTPJSONEncodedElem($reqArray, $elementId)
		{
			if (is_string($reqArray)) $reqArray = &self::GetArrayFromId($reqArray);

			$postVarsArray = self::GetHTTPJSONEncode($reqArray, $elementId);

			if (!is_object($postVarsArray))
			{
				return false;
			}

			// Verify that all the elements are valid types
			foreach($postVarsArray as $postKey => $postVal)
			{
				switch (gettype($postVal))
				{
					case 'string':
					case 'boolean':
					case 'integer':
					case 'double':
						break;

					default:
						return false;
				}
			}

			// Add the entries to the POSTS array
			foreach($postVarsArray as $postKey => $postVal)
			{
				// Pass decode values to POST and REQUEST arrays
				// Note: This function doesn't know the context of the values
				// So they can only be sanitized when they are used ... '
				StageShowLibUtilsClass::SetElement('post', $postKey, $postVal);
				StageShowLibUtilsClass::SetElement('request', $postKey, $postVal);
			}

			// Now remove the encoded array element
			unset($reqArray[$elementId]);

			return true;
		}

		static function GetHTTPEMail($reqArray, $elementId = 'undef', $defaultValue = '')
		{
			$str = self::GetArrayElement($reqArray, $elementId, $defaultValue);
			$str = sanitize_email($str);
			return $str;
		}

		static function GetHTTPDateTime($reqArray, $elementId = 'undef', $defaultValue = '')
		{
			// This function gets an date & time array element
			$dateAndTime = self::GetArrayElement($reqArray, $elementId, $defaultValue);
			if (!self::IsValidDate($dateAndTime))
			{
				return $defaultValue;
			}

			return $dateAndTime;
		}

		static function GetHTTPIntegerArray($reqArray, $elementId)
		{
			$arr = self::GetArrayElement($reqArray, $elementId);
			if (!is_array($arr)) return array();

			$rslt = array();

			foreach ($arr as $arrval)
			{
				if (!is_numeric($arrval)) return array();
				$rslt[] = intval($arrval);
			}

			return $rslt;
		}

		static function GetHTTPInteger($reqArray, $elementId = 'undef', $defaultValue = null)
		{
			// This function will return the sanitised integer array element
			$strval = StageShowLibMigratePHPClass::Safe_trim(self::GetArrayElement($reqArray, $elementId, $defaultValue));
			$rtnval = (int)$strval;
			if ($rtnval != $strval)
			{
				return $defaultValue;
			}
			return $rtnval;
		}

		static function GetHTTPFloat($reqArray, $elementId = 'undef', $defaultValue = null)
		{
			// This function will return the sanitised float array element
			$strval = StageShowLibMigratePHPClass::Safe_trim(self::GetArrayElement($reqArray, $elementId, $defaultValue));
			$rtnval = floatval($strval);
			if ($rtnval != $strval)
			{
				return $defaultValue;
			}
			return $rtnval;
		}

		static function GetHTTPNumber($reqArray, $elementId = 'undef', $defaultValue = '')
		{
			// This function will return the sanitised numeric array element
			$strval = self::GetArrayElement($reqArray, $elementId, $defaultValue);
			$strval = preg_replace('/\s+/', '', $strval);
			if (!is_numeric($strval))
			{
				return $defaultValue;
			}

			return $strval;
		}

		static function GetHTTPBool($reqArray, $elementId = 'undef')
		{
			if (!self::IsElementSet($reqArray, $elementId))
				return false;
			$strval = self::GetArrayElement($reqArray, $elementId, false);
			if (!is_numeric($strval))
				return false;
			$boolVal = ($strval != 0);
			return $boolVal;
		}
		
		static function GetHTTPFilenameElem($reqArray, $elementId, $defaultValue = '')
		{
			$str = self::GetArrayElement($reqArray, $elementId, $defaultValue);
			$str = sanitize_file_name($str);
			$str = StageShowLibMigratePHPClass::Safe_stripslashes($str);
			return $str;
		}

		static function GetHTTPAlphaNumericElem($reqArray, $elementId, $defaultValue = '')
		{
			// Match string with only alphanumeric characters and dot, comma or underscore
			$str = self::GetArrayElement($reqArray, $elementId, $defaultValue);
			$str = StageShowLibMigratePHPClass::Safe_stripslashes($str);
			if (preg_match("/^[a-zA-Z0-9_\-\.\,\s]*$/", $str) == 0)
				$str = $defaultValue;
			return $str;
		}

		static function IsValidDate($myDateString)
		{
		    return (bool)StageShowLibMigratePHPClass::Safe_strtotime($myDateString);
		}

		static function GetDateTimestamp($myDateString)
		{
			$spPosn = StageShowLibMigratePHPClass::Safe_strpos($myDateString, " ");
			$ts = StageShowLibMigratePHPClass::Safe_strtotime(StageShowLibMigratePHPClass::Safe_substr($myDateString, 0, $spPosn));
			return $ts;
		}

		static function GetPageHost()
		{
			$siteHost = ( is_ssl() ? 'https://' : 'http://' ).self::GetHTTPTextElem('server', 'HTTP_HOST');
			return $siteHost;
		}

		static function GetPageURL()
		{
			$currentURL  = self::GetPageHost();
			$currentURL .= self::GetHTTPTextElem('server', 'REQUEST_URI');
			return $currentURL;
		}

		static function GetPageBaseURL()
		{
			$pageURL  = self::GetPageURL();
			$posnParams = StageShowLibMigratePHPClass::Safe_strpos($pageURL,'?');
			if ($posnParams !== false)
				$pageURL = StageShowLibMigratePHPClass::Safe_substr($pageURL, 0, $posnParams);
			return $pageURL;
		}

		static function GetStrippedPageURL($removePage = true)
		{
			$pageURL  = self::GetPageURL();
			$pageURL = remove_query_arg('action', $pageURL);
			$pageURL = remove_query_arg('id', $pageURL);
			$pageURL = remove_query_arg('_wpnonce', $pageURL);
			if ($removePage)
			{
				$pageURL = remove_query_arg('paged', $pageURL);
			}
			return $pageURL;
		}

		static function GetCallbackURL($callbackFilename)
		{
			return self::GetPageURL().'&'.STAGESHOWLIB_CALLBACK_BASENAME."={$callbackFilename}";
		}

		private static function &GetArrayFromId($globId, $dieOnError = true)
		{
			static $nullGuard = null;

			if (is_string($globId))
			{
				switch($globId)
				{
					case 'get': return $_GET;
					case 'post': return $_POST;
					case 'request': return $_REQUEST;
					case 'cookie': return $_COOKIE;
					case 'server': return $_SERVER;
				}
			}

			if ($dieOnError)
				die("Invalid reqArray($globId) in GetArrayFromId ");

			return $nullGuard;
		}

		static function IsElementSet($globId, $elementId)
		{
			$reqArray = &self::GetArrayFromId($globId);

			return isset($reqArray[$elementId]);
		}

		static function SetElement($globId, $elementId, $elementValue)
		{
			$reqArray = &self::GetArrayFromId($globId);

			if (is_array($elementId))
			{
				$noOfElems = count($elementId);
				foreach ($elementId as $nextId)
				{
					$noOfElems--;
					if ($noOfElems == 0)
					{
						$elementId = $nextId;
						break;
					}
					if (!isset($reqArray[$nextId]))
					{
						$reqArray[$nextId] = array();
					}
					$reqArray = &$reqArray[$nextId];
				}
			}

			$reqArray[$elementId] = $elementValue;
		}

		static function UnsetElement($globId, $elementId)
		{
			$reqArray = &self::GetArrayFromId($globId);

			unset($reqArray[$elementId]);
		}

		static function GetArrayKeys($globId)
		{
			$reqArray = &self::GetArrayFromId($globId);
			return array_keys($reqArray);
		}

		static function GetArrayElement($reqArray, $elementId = 'undef', $defaultValue = '')
		{
			if ($elementId === 'undef')	die("elementId not defined on line ".__LINE__);

			if (is_string($reqArray)) $reqArray = &self::GetArrayFromId($reqArray);

			// Get an element from the array ... if it exists
			if (!is_array($reqArray))
				return $defaultValue;
			if (!array_key_exists($elementId, $reqArray))
				return $defaultValue;
			return $reqArray[$elementId];
		}

		static function startsWith($haystack, $needle)
		{
		     $length = StageShowLibMigratePHPClass::Safe_strlen($needle);
		     return (StageShowLibMigratePHPClass::Safe_substr($haystack, 0, $length) === $needle);
		}

		static function recurse_copy($src, $dst, $perm=STAGESHOWLIB_PHPFOLDER_PERMS)
		{
			$rtnStatus = true;
			$filePerm = $perm & 0666;	// Files should not have execute permission

			$dir = opendir($src);
			if (file_exists($dst))
			{
				chmod($dst, $perm);
			}
			else
			{
				@mkdir($dst, $perm, true);
			}
			if (file_exists($dst))
			{
				while(false !== ( $file = readdir($dir)) )
				{
					if ( $file == '.' ) continue;
					if ( $file == '..' ) continue;
					if ( $file == 'Thumbs.db' ) continue;
					if ( is_dir($src . '/' . $file) )
					{
						if (!self::recurse_copy($src . '/' . $file, $dst . '/' . $file))
						{
							$rtnStatus = false;
							break;
						}
					}
					else
					{
						$srcFile = $src . '/' . $file;
						$dstFile = $dst . '/' . $file;

						if (file_exists($dstFile))
						{
							// Make sure that destination is not ReadOnly
							chmod($dstFile, $filePerm);
						}
						copy($srcFile, $dstFile);
					}
				}
			}
			else
			{
				$rtnStatus = false;
			}
			closedir($dir);

			return $rtnStatus;
		}

		static function MakeUniqueCopy($filePath, $srcFile)
		{
			$path_parts = pathinfo($filePath.$srcFile);

			if (!file_exists($filePath.$srcFile))
			{
				$filePath .= $path_parts['extension'].'/';
			}

			if (!file_exists($filePath.$srcFile))
			{
				return $srcFile;
			}

			for ($fileIndex = 1; $fileIndex<1000; $fileIndex++)
			{
				$dstFile = $path_parts['filename'].'-'.$fileIndex.'.'.$path_parts['extension'];
				if (!file_exists($filePath.$dstFile))
				{
					copy($filePath.$srcFile, $filePath.$dstFile);
					return $dstFile;
				}
			}

			return $srcFile;
		}

		static function deleteDir($dir)
		{
			if (StageShowLibMigratePHPClass::Safe_substr($dir, StageShowLibMigratePHPClass::Safe_strlen($dir)-1, 1) != '/')
				$dir .= '/';
			if ($handle = opendir($dir))
			{
				while ($obj = readdir($handle))
				{
					if ($obj != '.' && $obj != '..')
					{
						if (is_dir($dir.$obj))
						{
							if (!self::deleteDir($dir.$obj))
								return false;
						}
						elseif (is_file($dir.$obj))
						{
							if (!unlink($dir.$obj))
								return false;
						}
					}
				}
				closedir($handle);
				if (!@rmdir($dir))
					return false;
				return true;
			}
			return false;
		}

		static function ShowCallStackAndLine($filePath, $lineno)
		{
			$fileName = basename($filePath);
			$msg = "(Line $lineno in $fileName)";
			self::ShowCallStack(true, $msg);
		}

		static function ShowCallStack($echoOut = true, $msg = '')
		{
			$html_rtnVal = '';
			return $html_rtnVal;
		}

		static function LogCallStack($context = '')
		{
		}

		static function UndefinedFuncCallError($classObj, $funcName)
		{
			$classId = get_class($classObj);
			StageShowLibUtilsClass::ShowCallStack();
			StageShowLibEscapingClass::Safe_EchoHTML("<strong><br>function $funcName() must be defined in $classId class<br></strong>\n");
			die;
		}

		static function print_r($obj, $name='', $return = false, $eol = "<br>")
		{
			$html_rtnVal = '';

			return $html_rtnVal;
		}

		static function print_r_nocontent($obj, $name='')
		{
		}


		static function DeleteFile($filePath)
		{
			if (!file_exists($filePath))
				return;

			try
			{
				//throw exception if can't move the file
				chmod($filePath, 0666);
				unlink($filePath);
			}
			catch (Exception $e)
			{
			}
		}

		static function GetUserMessage($msg, $isError = true, $msgClass = '')
		{
			ob_start();
			self::UserMessage($msg, $isError, $msgClass);
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}

		static function UserMessage($msg, $isError = true, $msgClass = '')
		{
			if ($msgClass == '')
			{
				$msgClass = $isError ? 'error' : 'updated';
			}
			StageShowLibEscapingClass::Safe_EchoHTML('<div id="message" class="'.$msgClass.'"><p>'.$msg.'</p></div>');
		}

		static function DebugOut($msg)
		{
		}

	}

}



