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
 * Interface used to support an import type for the working days.
 * 
 * @since 1.7.1
 */
interface VAPWorktimeImportType
{
	/**
	 * Processes the given buffer and imports the working days.
	 * 
	 * @param 	string  $buffer  The contents to parse.
	 * 
	 * @return 	array   An array containing all the fetched working times.
	 */
	public function process($buffer);

	/**
	 * Returns a string describing how the file content should be built.
	 * 
	 * @return 	string  An example of usage.
	 */
	public function getSample();
}
