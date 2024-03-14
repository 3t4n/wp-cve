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
 * Declares the methods that a specific archives director should provide.
 * 
 * @since 1.7.1
 */
interface VAPArchiveType
{
	/**
	 * Extracts the file from a package.
	 * 
	 * @param 	string  $source       The path of the package.
	 * @param 	string 	$destination  The folder in which the files should be extracted.
	 * 
	 * @return 	bool    True on success, false otherwise.
	 */
	public function extract($source, $destination);

	/**
	 * Compresses the specified folder into an archive.
	 * 
	 * @param 	string  $source       The path of the folder to compress.
	 * @param 	string 	$destination  The path of the resulting archive.
	 * 
	 * @return 	bool    True on success, false otherwise.
	 */
	public function compress($source, $destination);

	/**
	 * Downloads the specified archive.
	 * 
	 * @param 	string  $source  The path of the folder to compress.
	 * 
	 * @return 	void
	 * 
	 * @since 	1.7.1
	 */
	public function download($source);
}
