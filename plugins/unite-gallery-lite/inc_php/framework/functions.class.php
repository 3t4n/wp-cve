<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');


	class UniteFunctionsUG{
		
		const VALIDATE_NUMERIC = "numeric";
		const VALIDATE_NUMERIC_OR_EMPTY = "numeric_orempty";
		const VALIDATE_NOT_EMPTY = "notempty";
		const VALIDATE_ALPHANUMERIC = "alphanumeric";
		const FORCE_NUMERIC = "force_numeric";
		
		const SANITIZE_ID = "sanitize_id";		//positive number or empty
		const SANITIZE_TEXT_FIELD = "sanitize_text_field";		
		const SANITIZE_KEY = "sanitize_key";
		const SANITIZE_NOTHING = "sanitize_nothing";
		
		
		
		public static function throwError($message,$code=null){
			if(!empty($code))
				throw new Exception($message);
			else
				throw new Exception($message);
		}

		
		/**
		 * get variable from post or from get. get wins
		 */
		public static function getPostGetVariable($name, $initVar = "", $sanitizeType = null){
			
			$var = $initVar;
			
			if(isset($_POST[$name])) 
				$var = $_POST[$name];
				else 
					if(isset($_GET[$name])) $var = $_GET[$name];
			
			$var = UniteProviderFunctionsUG::sanitizeVar($var, $sanitizeType);
			
			return($var);
		}
		
		
		/**
		 * get post variable
		 */
		public static function getPostVariable($name, $initVar = "", $sanitizeType = null){
			
			$var = $initVar;
			
			if(isset($_POST[$name])) 
				$var = $_POST[$name];
						
			$var = UniteProviderFunctionsUG::sanitizeVar($var, $sanitizeType);
						
			return($var);
		}
		
		
		/**
		 * get get variable
		 */
		public static function getGetVar($name, $initVar = "", $sanitizeType = null){
			
			$var = $initVar;
			
			if(isset($_GET[$name])) 
				$var = $_GET[$name];
			
			$var = UniteProviderFunctionsUG::sanitizeVar($var, $sanitizeType);
			
			
			return($var);
		}
		
				
		public static function z________________ARRAYS_______________(){}
		
		/**
		 * merge arrays with unique ids
		 */
		public static function mergeArraysUnique($arr1, $arr2, $arr3 = array()){
			
			if(empty($arr2) && empty($arr3))
				return($arr1);
			
			$arrIDs = array_merge($arr1, $arr2, $arr3);
			$arrIDs = array_unique($arrIDs);
			
			return($arrIDs);
		}
		
		
		/**
		 * get value from array. if not - return alternative
		 */
		public static function getVal($arr, $key, $altVal="", $validateType = null){
			
			$var = "";
			
			if(isset($arr[$key])){
				
				$var = $arr[$key];
				
				if(!empty($validateType))
					$var = self::validateVar($var, $name, $validateType);
				
				return($var);
			}
			
			return($altVal);
		}
		
		
		/**
		 * get first not empty key from array
		 */
		public static function getFirstNotEmptyKey($arr){
		
			foreach($arr as $key=>$item){
				if(!empty($key) && is_numeric($key))
					return($key);
			}
		
			return("");
		}
		
		
		/**
		 * filter array, leaving only needed fields - also array
		 *
		 */
		public static function filterArrFields($arr, $fields, $isFieldsAssoc = false){
			$arrNew = array();
			
			if($isFieldsAssoc == false){
				foreach($fields as $field){
					if(array_key_exists($field, $arr))
						$arrNew[$field] = $arr[$field];
				}
			}else{
				foreach($fields as $field=>$value){
					if(array_key_exists($field, $arr))
						$arrNew[$field] = $arr[$field];
				}
			}
			
			return($arrNew);
		}
		
		/**
		 * Convert std class to array, with all sons
		 */
		public static function convertStdClassToArray($d){
		
			if (is_object($d)) {
				$d = get_object_vars($d);
			}
			if (is_array($d)){
		
				return array_map(array("UniteFunctionsUG","convertStdClassToArray"), $d);
			} else {
				return $d;
			}
		}
		
		/**
		 *
		 * get random array item
		 */
		public static function getRandomArrayItem($arr){
			$numItems = count($arr);
			$rand = rand(0, $numItems-1);
			$item = $arr[$rand];
			return($item);
		}
		
		/**
		 * get different values in $arr from the default $arrDefault
		 * $arrMustKeys - keys that must be in the output
		 *
		 */
		public static function getDiffArrItems($arr, $arrDefault, $arrMustKeys = array()){
		
			if(gettype($arrDefault) != "array")
				return($arr);
		
			if(!empty($arrMustKeys))
				$arrMustKeys = UniteFunctionsUG::arrayToAssoc($arrMustKeys);
		
			$arrValues = array();
			foreach($arr as $key => $value){
		
				//treat must value
				if(array_key_exists($key, $arrMustKeys) == true){
					$arrValues[$key] = self::getVal($arrDefault, $key);
					if(array_key_exists($key, $arr) == true)
						$arrValues[$key] = $arr[$key];
					continue;
				}
		
				if(array_key_exists($key, $arrDefault) == false){
					$arrValues[$key] = $value;
					continue;
				}
		
				$defaultValue = $arrDefault[$key];
				if($defaultValue != $value){
					$arrValues[$key] = $value;
					continue;
				}
		
			}
		
			return($arrValues);
		}
		
		/**
		 *
		 * Convert array to assoc array by some field
		 */
		public static function arrayToAssoc($arr,$field=null){
			$arrAssoc = array();
		
			foreach($arr as $item){
				if(empty($field))
					$arrAssoc[$item] = $item;
				else
					$arrAssoc[$item[$field]] = $item;
			}
		
			return($arrAssoc);
		}
		
		
		/**
		 *
		 * convert assoc array to array
		 */
		public static function assocToArray($assoc){
			$arr = array();
			foreach($assoc as $item)
				$arr[] = $item;
		
			return($arr);
		}
		
		/**
		 *
		 * do "trim" operation on all array items.
		 */
		public static function trimArrayItems($arr){
			if(gettype($arr) != "array")
				UniteFunctionsUG::throwError("trimArrayItems error: The type must be array");
		
			foreach ($arr as $key=>$item)
				$arr[$key] = trim($item);
		
			return($arr);
		}
		
		/**
		 *
		 * encode array into json for client side
		 */
		public static function jsonEncodeForClientSide($arr){
			$json = "";
			if(!empty($arr)){
				$json = json_encode($arr);
				$json = addslashes($json);
			}
		
			$json = "'".$json."'";
		
			return($json);
		}
		
		
		/**
		 * add first value to array
		 */
		public static function addArrFirstValue($arr, $text, $value = ""){
			
			$arr = array($value => $text) + $arr;
			
			return($arr);
		}
		
		
		public static function z______________STRINGS_____________(){}
		
		/**
		 * return if the array is id's array
		 */
		public static function isValidIDsArray($arr){
			
			if(is_array($arr) == false)
				return(false);
				
			if(empty($arr))
				return(true);
			
			foreach($arr as $key=>$value){
				
				if(is_numeric($key) == false || is_numeric($value) == false)
					return(false);
			}

			return(true);
		}
		
		
		/**
		 * encode json for html data like data-key="json"
		 */
		public static function jsonEncodeForHtmlData($arr, $dataKey=""){
			
			$strJson = "";
			if(!empty($arr)){
				$strJson = json_encode($arr);
				$strJson = htmlspecialchars($strJson);
			}
			if(!empty($dataKey))
				$strJson = " data-{$dataKey}=\"{$strJson}\"";
			
			return($strJson);
		}
		
		
		/**
		 * get random string
		 */
		public static function getRandomString($length = 10){
		
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
			$randomString = '';
		
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
		
			return $randomString;
		}
		
			/**
		 * limit string chars to max size
		 */
		public static function limitStringSize($str, $numChars, $addDots = true){
			
			$encoding = "UTF-8";
			
			if(function_exists("mb_strlen") == false)
				return($str);
				
			if(mb_strlen($str, $encoding) <= $numChars)
				return($str);
			
			if($addDots)
				$str = mb_substr($str, 0, $numChars-3, $encoding)."...";				
			else
				$str = mb_substr($str, 0, $numChars, $encoding);
			
			
			return($str);
		}
		
		
		/**
		 * print string as chars - compare strings
		 */
		public static function printStringChars($str, $str2 = ""){
			$len = strlen($str);
			for($i=0;$i<$len;$i++){
				$chr = $str[$i];
				$num = ord($chr);
				$output = "$chr $num";
				if(!empty($str2)){
					if(!isset($str2[$i]))
						$output .= " -- not exists";
					else{
						$chr = $str2[$i];
						$num = ord($chr);
						$output .= " -- $chr $num";
					}
				}
				dmp($output);
			}
		}
		/**
		 * add prefix to each line in string
		 */
		public static function addPrefixToEachLine($str, $prefix){
			
			if(empty($str))
				return($str);
			
			$arr = explode("\n", $str);
			foreach($arr as $key=>$line){
				$trimmed = trim($line);
				if(empty($trimmed))
					continue;
				$arr[$key] = $prefix.$line;
			}
			
			$str = implode("\n", $arr);
			return($str);
		}
		
		public static function z______________ENCODE_DECODE_____________(){}
		
		/**
		 * check if text is encoded
		 */
		public static function isTextEncoded($content){

			if(is_string($content) == false)
				return(false);
			
			if(empty($content))
				return(false);
			
		    // Check if there is no invalid character in string
		    if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $content)) 
		    	return false;
			
		    $decoded = @base64_decode($content, true);
		    
		    // Decode the string in strict mode and send the response
		    if(empty($decoded)) 
		    	return false;
			
		    // Encode and compare it to original one
		    if(base64_encode($decoded) != $content)
		    	return false;
			
			return true;			
		}		
		
		
		/**
		 * maybe decode content
		 */
		public static function maybeDecodeTextContent($value){
			
			if(empty($value))
				return($value);
			
			if(is_string($value) == false)
				return($value);
			
			$isEncoded = self::isTextEncoded($value);
			
			if($isEncoded == false)
				return($value);
			
			$decoded = self::decodeTextContent($value);
			
			return($decoded);
		}
		
		
		/**
		 * decode string content
		 */
		public static function decodeTextContent($content){
			
			$content = rawurldecode(base64_decode($content));
			
			return($content);
		}
		
		
		/**
		 * encode content
		 */
		public static function encodeContent($content){
			
			if(is_array($content))
				$content = json_encode($content);
			
			$content = rawurlencode($content);
			
			$content = base64_encode($content);
						
			return($content);
		}
		
		
		/**
		 * decode content given from js
		 */
		public static function decodeContent($content, $convertToArray = true){
		
			if(empty($content))
				return($content);
			
			$content = rawurldecode(base64_decode($content));
			
			if($convertToArray == true)
				$arr = self::jsonDecode($content);
			else 
				$arr = @json_decode($content);
			
			return $arr;
		}
		
		
		/**
		 * decode content given from js
		 */
		public static function jsonDecode($content, $outputArray = false){
			
			if($outputArray == true && empty($content))
				return(array());
			
			$arr = @json_decode($content);
			$arr = self::convertStdClassToArray($arr);
			
			if($outputArray == true && empty($content))
				return(array());
			
			return $arr;
		}
		
		
		public static function z______________VALIDATIONS_____________(){}
		
		/**
		 * 
		 * validate that some file exists, if not - throw error
		 */
		public static function validateFilepath($filepath,$errorPrefix=null){
			if(file_exists($filepath) == true)
				return(false);
			if($errorPrefix == null)
				$errorPrefix = "File";
			$message = $errorPrefix." $filepath not exists!";
			self::throwError($message);
		}
		
		/**
		 *
		 * validate that some directory exists, if not - throw error
		 */
		public static function validateDir($pathDir, $errorPrefix=null){
			if(is_dir($pathDir) == true)
				return(false);
			
			if($errorPrefix == null)
				$errorPrefix = "Directory";
			$message = $errorPrefix." $pathDir not exists!";
			self::throwError($message);
		}
		
		
		/**
		 * 
		 * validate if some directory is writable, if not - throw a exception
		 */
		private static function validateWritable($name,$path,$strList,$validateExists = true){
		
			if($validateExists == true){
				//if the file/directory doesn't exists - throw an error.
				if(file_exists($path) == false)
					throw new Exception("$name doesn't exists");
			}
			else{
				//if the file not exists - don't check. it will be created.
				if(file_exists($path) == false) return(false);
			}
		
			if(is_writable($path) == false){
				chmod($path,0755);		//try to change the permissions
				if(is_writable($path) == false){
					$strType = "Folder";
					if(is_file($path)) $strType = "File";
					$message = "$strType $name is doesn't have a write permissions. Those folders/files must have a write permissions in order that this application will work properly: $strList";
					throw new Exception($message);
				}
			}
		}
		
		
		/**
		 * validate some variable
		 */
		public static function validateVar($val, $fieldName, $validateType){

			switch($validateType){
				case self::VALIDATE_NOT_EMPTY:
					self::validateNotEmpty($val, $fieldName);
				break;
				case self::VALIDATE_NUMERIC:
					self::validateNumeric($val, $fieldName);
				break;
				case self::VALIDATE_NUMERIC_OR_EMPTY:
					if(!empty($val))
						self::validateNumeric($val, $fieldName);
				break;
				case self::FORCE_NUMERIC:
					$val = (float)$val;
				break;
				case self::VALIDATE_ALPHANUMERIC:
					self::validateAlphaNumeric($val, $fieldName);
				break;
			}
			
			return($val);
		}
		
		
		/**
		 * 
		 * validate that some value is numeric
		 */
		public static function validateNumeric($val,$fieldName=""){
			self::validateNotEmpty($val,$fieldName);
			
			if(empty($fieldName))
				$fieldName = "Field";
			
			if(!is_numeric($val))
				self::throwError("$fieldName should be numeric ");
		}
		
		/**
		 * 
		 * validate that some variable not empty
		 */
		public static function validateNotEmpty($val, $fieldName=""){
			
			if(empty($fieldName))
				$fieldName = "Field";
				
			if(empty($val) && is_numeric($val) == false)
				self::throwError("Field <b>$fieldName</b> should not be empty");
		}
		
		
		/**
		 * validate that the variable is alphanumeric
		 */
		public static function validateAlphaNumeric($val, $fieldName=""){
			
			if(empty($val))
				return(true);
			
			if(empty($fieldName))
				$fieldName = "Field";
			
			if(ctype_alnum($val) == false)
				self::throwError("Field <b>$fieldName</b> has wrong characters");
		}
		
		/**
		 * check the php version. throw exception if the version beneath 5
		 */
		private static function validatePHPVersion(){
			$strVersion = phpversion();
			$version = (float)$strVersion;
			if($version < 5) 
				self::throwError("You must have php5 and higher in order to run the application. Your php version is: $version");
		}
		
		
		//--------------------------------------------------------------
		// valiadte if gd exists. if not - throw exception
		public static function validateGD(){
			if(function_exists('gd_info') == false)
				throw new Exception("You need GD library to be available in order to run this application. Please turn it on in php.ini");
		}
		
		
		
		/**
		 *
		 * convert php array to js array text
		 * like item:"value"
		 */
		public static function phpArrayToJsArrayText($arr){
			$str = "";
			$length = count($arr);
		
			$counter = 0;
			foreach($arr as $key=>$value){
				$str .= "{$key}:\"{$value}\"";
				$counter ++;
				if($counter != $length)
					$str .= ",\n";
			}
		
			return($str);
		}
		
		
		/**
		 * convert array with styles in each item to items string
		 */
		public static function arrStyleToStrStyle($arrStyle, $styleName = "", $addCss = ""){
			
			if(empty($arrStyle) && empty($addCss))
				return("");
			
			$br = "\n";
			$tab = "	";
			
			$output = $br;
			
			if(!empty($styleName))
				$output .= $styleName."{".$br;
			
			foreach($arrStyle as $key=>$value){
				$output .= $tab.$key.":".$value.";".$br;
			}
			
			//add additional css
			if(!empty($addCss)){
				$arrAddCss = explode($br, $addCss);
				$output .= $br;
				foreach($arrAddCss as $str){
					$output .= $tab.$str.$br;
				}
			}
			
			if(!empty($styleName))
				$output .= "}".$br;
			
			return($output);
		}
			
		
		public static function z______________FILE_SYSTEM_____________(){}
		
		/**
		 *
		 * if directory not exists - create it
		 * @param $dir
		 */
		public static function checkCreateDir($dir){
			if(!is_dir($dir))
				mkdir($dir);
		}
		
		
		//------------------------------------------------------------
		//get path info of certain path with all needed fields
		public static function getPathInfo($filepath){
			$info = pathinfo($filepath);
		
			//fix the filename problem
			if(!isset($info["filename"])){
				$filename = $info["basename"];
				if(isset($info["extension"]))
					$filename = substr($info["basename"],0,(-strlen($info["extension"])-1));
				$info["filename"] = $filename;
			}
		
			return($info);
		}
		
		
		
		//------------------------------------------------------------
		//save some file to the filesystem with some text
		public static function writeFile($str,$filepath){
			$fp = fopen($filepath,"w+");
			fwrite($fp,$str);
			fclose($fp);
		}
		
		/**
		 *
		 * get list of all files in the directory
		 */
		public static function getFileList($path){
			$dir = scandir($path);
			$arrFiles = array();
			foreach($dir as $file){
				if($file == "." || $file == "..") continue;
				$filepath = $path . "/" . $file;
				if(is_file($filepath)) $arrFiles[] = $file;
			}
			return($arrFiles);
		}
		
		/**
		 *
		 * get list of all directories in the directory
		 */
		public static function getDirList($path){
			$arrDirs = scandir($path);
		
			$arrFiles = array();
			foreach($arrDirs as $dir){
				if($dir == "." || $dir == "..")
					continue;
				$dirpath = $path . "/" . $dir;
		
				if(is_dir($dirpath))
					$arrFiles[] = $dir;
			}
		
			return($arrFiles);
		}
						
		
		/**
		 *
		 * clear debug file
		 */
		public static function clearDebug($filepath = "debug.txt"){
		
			if(file_exists($filepath))
				unlink($filepath);
		}
		
		/**
		 *
		 * save to filesystem the error
		 */
		public static function writeDebugError(Exception $e,$filepath = "debug.txt"){
			$message = $e->getMessage();
			$trace = $e->getTraceAsString();
		
			$output = $message."\n";
			$output .= $trace."\n";
		
			$fp = fopen($filepath,"a+");
			fwrite($fp,$output);
			fclose($fp);
		}
		
		
		//------------------------------------------------------------
		//save some file to the filesystem with some text
		public static function addToFile($str,$filepath){
			$fp = fopen($filepath,"a+");
			fwrite($fp,"---------------------\n");
			fwrite($fp,$str."\n");
			fclose($fp);
		}
		
		
		/**
		 *
		 * recursive delete directory or file
		 */
		public static function deleteDir($path,$deleteOriginal = true, $arrNotDeleted = array(),$originalPath = ""){
		
			if(empty($originalPath))
				$originalPath = $path;
		
			//in case of paths array
			if(getType($path) == "array"){
				$arrPaths = $path;
				foreach($path as $singlePath)
					$arrNotDeleted = self::deleteDir($singlePath,$deleteOriginal,$arrNotDeleted,$originalPath);
				return($arrNotDeleted);
			}
		
			if(!file_exists($path))
				return($arrNotDeleted);
		
			if(is_file($path)){		// delete file
				$deleted = unlink($path);
				if(!$deleted)
					$arrNotDeleted[] = $path;
			}
			else{	//delete directory
				$arrPaths = scandir($path);
				foreach($arrPaths as $file){
					if($file == "." || $file == "..")
						continue;
					$filepath = realpath($path."/".$file);
					$arrNotDeleted = self::deleteDir($filepath,$deleteOriginal,$arrNotDeleted,$originalPath);
				}
		
				if($deleteOriginal == true || $originalPath != $path){
					$deleted = @rmdir($path);
					if(!$deleted)
						$arrNotDeleted[] = $path;
				}
		
			}
		
			return($arrNotDeleted);
		}
		
		/**
		 * copy folder to another location.
		 *
		 */
		public static function copyDir($source,$dest,$rel_path = "",$blackList = null){
		
			$full_source = $source;
			if(!empty($rel_path))
				$full_source = $source."/".$rel_path;
		
			$full_dest = $dest;
			if(!empty($full_dest))
				$full_dest = $dest."/".$rel_path;
		
			if(!is_dir($full_source))
				self::throwError("The source directroy: '$full_source' not exists.");
		
			if(!is_dir($full_dest))
				mkdir($full_dest);
		
			$files = scandir($full_source);
			foreach($files as $file){
				if($file == "." || $file == "..")
					continue;
		
				$path_source = $full_source."/".$file;
				$path_dest = $full_dest."/".$file;
		
				//validate black list
				$rel_path_file = $file;
				if(!empty($rel_path))
					$rel_path_file = $rel_path."/".$file;
		
				//if the file or folder is in black list - pass it
				if(array_search($rel_path_file, $blackList) !== false)
					continue;
		
				//if file - copy file
				if(is_file($path_source)){
					copy($path_source,$path_dest);
				}
				else{		//if directory - recursive copy directory
					if(empty($rel_path))
						$rel_path_new = $file;
					else
						$rel_path_new = $rel_path."/".$file;
		
					self::copyDir($source,$dest,$rel_path_new,$blackList);
				}
			}
		}
		
		
		public static function z______________OTHERS_____________(){}

		
		/**
		 * check if the string is json, then convert to css, if not return original
		 */
		public static function jsonToCss($strCss, $wrappers = ""){
			
			//check if json
			$arrayDecoded = @json_decode($strCss);
			if(empty($arrayDecoded)){
				
				return($strCss);
			}
			
			$strCss = "";
			
			$arrayDecoded = (array)$arrayDecoded;
			foreach($arrayDecoded as $key=>$item){
				$strCss .= $key . ":" . $item."; ";
			}
			
			return($strCss);
		}
		
		
		/**
		 * convert timestamp to time string
		 * @param unknown_type $stamp
		 */
		public static function timestamp2Time($stamp){
			$strTime = date("H:i",$stamp);
			return($strTime);
		}
		
		//---------------------------------------------------------------------------------------------------
		// convert timestamp to date and time string
		public static function timestamp2DateTime($stamp){
			$strDateTime = date("d M Y, H:i",$stamp);
			return($strDateTime);
		}
		
		//---------------------------------------------------------------------------------------------------
		// convert timestamp to date string
		public static function timestamp2Date($stamp){
			$strDate = date("d M Y",$stamp);	//27 Jun 2009
			return($strDate);
		}
		
		
		
		/**
		 * 
		 * get link html
		 */
		public static function getHtmlLink($link,$text,$id="",$class="", $isNewWindow = false){
			
			if(!empty($class))
				$class = " class='$class'";
			
			if(!empty($id))
				$id = " id='$id'";
			
			$htmlAdd = "";
			if($isNewWindow == true)
				$htmlAdd = ' target="_blank"';
				
			$html = "<a href=\"$link\"".$id.$class.$htmlAdd.">$text</a>";
			return($html);
		}
		
		/**
		 * 
		 * get select from array
		 */
		public static function getHTMLSelect($arr,$default="",$htmlParams="",$assoc = false){
			
			$html = "<select $htmlParams>";
			foreach($arr as $key=>$item){				
				$selected = "";
				
				if($assoc == false){
					if($item == $default) $selected = " selected ";
				}
				else{ 
					if(trim($key) == trim($default))
						$selected = " selected ";
				}
					
				
				if($assoc == true)
					$html .= "<option $selected value='$key'>$item</option>";
				else
					$html .= "<option $selected value='$item'>$item</option>";
			}
			$html.= "</select>";
			return($html);
		}
		
				
		/**
		 * 
		 * strip slashes from textarea content after ajax request to server
		 */
		public static function normalizeTextareaContent($content){
			if(empty($content))
				return($content);
			$content = stripslashes($content);
			$content = trim($content);
			return($content);
		}
		
		/**
		 * normalize link - switch first & for ?, if no ? found
		 */
		public static function normalizeLink($link){
		
			//if there is no "?" - fix first appearance of & to ?
			$pos = strpos($link, "?");
			if($pos === false){
				$link = preg_replace('/\&/', '?', $link, 1);
			}
			
			
			//if found more then one ?, convert the rest to &
			$pos = strpos($link, "?");
			if($pos !== false){
				$pos2 = strpos($link, "?", $pos+1);
				if($pos2 !== false){
					$stringEnd = substr($link, $pos+1);
					$stringEnd = str_replace("?","&",$stringEnd);
					$link = substr_replace($link,$stringEnd,$pos+1);
				}
			}
			
			
			return($link);
		}
		
		
		/**
		 * Download Image
		 */
		public static function downloadImage($filepath, $filename, $mimeType=""){
			$contents = file_get_contents($filepath);
			$filesize = strlen($contents);
		
			if($mimeType == ""){
				$info = UniteFunctionsUG::getPathInfo($filepath);
				$ext = $info["extension"];
				$mimeType = "image/$ext";
			}
		
			header("Content-Type: $mimeType");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Length: $filesize");
			echo $contents;
			exit();
		}
		
		
		/**
		 * Download file from content
		 */
		public static function downloadFileFromContent($content, $filename, $mimeType="text/plain"){
			
			$filesize = strlen($content);
			
			header("Content-Type: $mimeType");
			header("Content-Disposition: attachment; filename=\"{$filename}\"");
			header("Content-Length: {$filesize}");
			echo $content;
			exit();
		}
		
		
		/**
		 *
		 * convert string to boolean
		 */
		public static function strToBool($str){
			if(is_bool($str))
				return($str);
		
			if(empty($str))
				return(false);
		
			if(is_numeric($str))
				return($str != 0);
		
			$str = strtolower($str);
			if($str == "true")
				return(true);
		
			return(false);
		}
		
		
		//------------------------------------------------------------
		// get black value from rgb value
		public static function yiq($r,$g,$b){
			return (($r*0.299)+($g*0.587)+($b*0.114));
		}
		
		//------------------------------------------------------------
		// convert colors to rgb
		public static function html2rgb($color){
			if ($color[0] == '#')
				$color = substr($color, 1);
			if (strlen($color) == 6)
				list($r, $g, $b) = array($color[0].$color[1],
						$color[2].$color[3],
						$color[4].$color[5]);
			elseif (strlen($color) == 3)
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
			else
				return false;
			$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
			return array($r, $g, $b);
		}
		
		/**
		 * 
		 *turn some object to string
		 */
		public static function toString($obj){
			return(trim((string)$obj));
		}

		
		/**
		 * 
		 * remove utf8 bom sign
		 * @return string
		 */
		public static function remove_utf8_bom($content){
			$content = str_replace(chr(239),"",$content);
			$content = str_replace(chr(187),"",$content);
			$content = str_replace(chr(191),"",$content);
			$content = trim($content);
			return($content);
		}
		
		/**
		 * put javascript redirection script
		 */
		public static function putRedirectJS($url){
			
			$html = "<script type='text/javascript'>
			location.href='{$url}';
			</script>";
			
			echo $html;
		}
		
		/**
		 * throw error and show function trace
		 */
		public static function showTrace($exit = false){
			
			try{
				throw new Exception("Show me the trace");
			}catch(Exception $e){
		
				$trace = $e->getTraceAsString();
				dmp($trace);
		
				if($exit == true)
					exit();
			}
		}
		
		
	}
	
?>