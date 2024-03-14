<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_FileUtils
{
	
	public static function isDirWritable($dir2check, $logResults=false, $level='WARNING'){
		$lastChar = substr($dir2check, -1);
		if($lastChar !== '/' && $lastChar !== '\\'){
			$dir2check .= "/";
		}
	
		if(!file_exists($dir2check)){
			if($logResults){
				WADA_Log::log('Dir ' . $dir2check . ' NOT existing!', $level);
			}
			return false;  // Directory not existing
		}
		if(!is_writable($dir2check)){
			if($logResults){
				WADA_Log::log('Dir ' . $dir2check . ' NOT writable!', $level);
			}
			return false;  // Directory not writable
		}
	
		if($logResults){
			WADA_Log::log('Dir ' . $dir2check . ' writable');
		}
		return true; // Directory writable
	}
	
	public static function isFileWritable($targetDir, $completeFilepath, $logResults=false, $level='WARNING'){
		$ok = self::isDirWritable($targetDir,$logResults,$level);
	
		if($ok){ // dir writable
			if(file_exists($completeFilepath) && !is_writable($completeFilepath)){
				if($logResults){
					WADA_Log::log('File ' . $completeFilepath . ' existing, but not writable', $level);
				}
				return false;  // File exists but is not writable
			}
			return true; // File writable
		}else{
			return false; // Dir not writable, therefore file not writable
		}
	}
	
	public static function fileWritable($fileName, $folderPath, $takeFileNameAsPath=false){
        if($takeFileNameAsPath){
            $folderPath = $fileName; // cannot check it
            $filePath = $fileName;
        }else{
            $folderPath = rtrim($folderPath, '/') . '/'; // make sure there is one trailing slash
            $filePath = $folderPath . $fileName;
        }
		if(!file_exists($folderPath)){
			return false;  // Log folder path wrong configured
		}
		if(!is_writable($folderPath)){
			return false;  // Directory not writable
		}
		if(file_exists($filePath) && !is_writable($filePath)){
			return false;  // Log file exists but is not writable
		}
		return true; // Directory writable, file may exist or not
	}
	
	public static function getFileSizeStringForSizeInBytes($sizeBytes){		
		if ($sizeBytes < 1024) {
			return $sizeBytes . ' ' . __( 'B', 'wp-admin-audit' );
		} elseif ($sizeBytes < 1048576) {
			return round($sizeBytes / 1024, 2) . ' ' . __( 'KB', 'wp-admin-audit' );
		} elseif ($sizeBytes < 1073741824) {
			return round($sizeBytes / 1048576, 2) . ' ' . __( 'MB', 'wp-admin-audit' );
		} elseif ($sizeBytes < 1099511627776) {
			return round($sizeBytes / 1073741824, 2) . ' ' . __( 'GB', 'wp-admin-audit' );
		} else {
			return round($sizeBytes / 1099511627776, 2) . ' ' . __( 'TB', 'wp-admin-audit' );
		}
	}
	
	public static function getFileSizeOfFile($filePath){
		return self::getFileSizeStringForSizeInBytes(filesize($filePath));
	}
	
}
