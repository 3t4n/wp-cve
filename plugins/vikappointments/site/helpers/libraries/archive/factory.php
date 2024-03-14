<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Archives helper class.
 * 
 * @since 1.7.1
 */
abstract class VAPArchiveFactory
{
	/**
	 * Extracts the file from a package.
	 * 
	 * @param 	string  $source       The path of the package.
	 * @param 	string 	$destination  The folder in which the files should be extracted.
	 * 
	 * @return 	bool    True on success, false otherwise.
	 * 
	 * @throws 	Exception
	 */
	public static function extract($source, $destination)
	{
		return static::getInstance($source)->extract($source, $destination);
	}

	/**
	 * Compresses the specified folder into an archive.
	 * 
	 * @param 	string  $source       The path of the folder to compress.
	 * @param 	string 	$destination  The path of the resulting archive.
	 * 
	 * @return 	bool    True on success, false otherwise.
	 * 
	 * @throws 	Exception
	 */
	public static function compress($source, $destination)
	{
		return static::getInstance($destination)->compress($source, $destination);
	}

	/**
	 * Downloads the specified archive.
	 * 
	 * @param 	string  $source  The path of the folder to compress.
	 * 
	 * @return 	void
	 * 
	 * @since 	1.7.1
	 * 
	 * @throws 	Exception
	 */
	public static function download($source)
	{
		static::getInstance($source)->download($source);
	}

	/**
	 * Returns a new instance of the specified archive handler.
	 * 
	 * @param 	string 	$type  Either the archive type or a path, from which
	 *                         the file type will be extracted.
	 * 
	 * @return 	VAPArchiveType
	 * 
	 * @throws 	Exception
	 */
	protected static function getInstance($type)
	{
		// check if we have a path
		if (preg_match("/[\/\\\\]/", $type))
		{
			// extract file type from path
			$type = pathinfo($type, PATHINFO_EXTENSION);
		}

		switch (strtolower($type))
		{
			case 'zip':

				// create a new ZIP archive instance
				VAPLoader::import('libraries.archive.type.zip');
				return new VAPArchiveTypeZip;

			default:
				// invalid archive type
				throw new Exception(sprintf('Archive [%s] not supported', $type), 500);
		}
	}
}
