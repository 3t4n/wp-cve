<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Utils;


final class MchFileUtils
{
	public static function rename($fileName, $newFileName)
	{
		@rename($fileName, $newFileName);
	}

	public static function copy($sourceFileName, $destinationFileName)
	{
		if(@copy($sourceFileName, $destinationFileName) === FALSE)
			return false;

		return true;
	}

	public static function getFileExtension($fileName, $withDot = false)
	{
		$extension = \pathinfo($fileName, \PATHINFO_EXTENSION);

		if(empty($extension))
			return null;

		return $withDot === true ? "." . $extension : $extension; // return html
	}

	public static function getFileName($filePath)
	{
		$fileName = pathinfo($filePath, PATHINFO_FILENAME);
		return empty($fileName) ? null : $fileName;

		//return  urldecode(basename(str_replace(array('%2F', '%5C'), '/', urlencode($fileName))));

	}

	public static function getFileBaseName($filePath)
	{
		$fileBaseName = \pathinfo($filePath, PATHINFO_BASENAME);
		return empty($fileBaseName) ? null : $fileBaseName;
	}

	public static function getFileDirectoryName($filePath)
	{
		$dirName = \pathinfo($filePath, PATHINFO_DIRNAME);
		return empty($dirName) ? null : $dirName;
	}


	public static function fileExists($filePath, $clearStatCache = false)
	{
		!$clearStatCache ?: @clearstatcache(true, $filePath);

		return @file_exists($filePath);
	}


	public static function getPathParts($fileName)
	{
		return pathinfo($fileName);
	}

	public static function getSizeInBytes($fileName)
	{
		$fileSize = filesize($fileName);

		// Fix for overflowing signed 32 bit integers,
		// works for sizes up to 2^32-1 bytes (4 GiB - 1):
		($fileSize >= 0) ?: $fileSize += 2.0 * (\PHP_INT_MAX + 1);

		return $fileSize; // return int in bytes

	}

	public static function getSizeInKiloBytes($fileName)
	{
		return round(self::getSizeInBytes($fileName) / 1024, 2);
	}

	public static function getSizeInMegaBytes($fileName)
	{
		return round(self::getSizeInBytes($fileName) / 1048576, 2);
	}

	public static function getSizeInGigaBytes($fileName)
	{
		return round(self::getSizeInBytes($fileName) / 1073741824, 2);
	}
	public static function getSizeInTeraBytes($fileName)
	{
		return round(self::getSizeInBytes($fileName) / 1099511627776, 2);
	}



	public static function getContent($fileName)
	{
		$fileContent = \file_get_contents($fileName);
		if($fileContent === FALSE)
			return null;

		return $fileContent;
	}

	public static function writeContent($fileName, $contentString)
	{
		return \file_put_contents($fileName, $contentString, \LOCK_EX);
	}

	public static function writeContentToFile($content, $filePath, $exclusiveLock = true)
	{
		$filePointer = @fopen($filePath, 'wb');
		if(false === $filePointer)
			return 0;

		if( false === flock( $filePointer, ( $exclusiveLock ? LOCK_EX : LOCK_EX|LOCK_NB ) ) ){
			fclose($filePointer);
			return 0;
		}

		$bytesWritten = fwrite($filePointer, $content);
		flock($filePointer, LOCK_UN);
		fclose($filePointer);

		return (false === $bytesWritten) ? 0 : $bytesWritten;

	}

	public static function writeLine($fileName, $contentString)
	{
		$contentString .= (stripos(PHP_OS, "win") === true) ? "\r\n" : "\n";

		return \file_put_contents($fileName, $contentString, \LOCK_EX);
	}

	public static function appendContent($fileName, $contentString)
	{
		return \file_put_contents($fileName, $contentString, \LOCK_EX | \FILE_APPEND);
	}

	public static function appendLine($fileName, $contentString)
	{
		$contentString .= (stripos(PHP_OS, "win") === true) ? "\r\n" : "\n";
		return \file_put_contents($fileName, $contentString, \LOCK_EX | \FILE_APPEND);
	}

	public static function getLastModificationTime($fileName)
	{
		return \filemtime($fileName);
	}

	public static function readAllLines($fileName, $skipEmptyLines = false)
	{
		if ($skipEmptyLines)
			return \file($fileName, \FILE_SKIP_EMPTY_LINES);

		return \file($fileName);
	}

	public static function delete($fileName)
	{
		(!@\is_file($fileName)) ?: @\unlink($fileName);
	}

}