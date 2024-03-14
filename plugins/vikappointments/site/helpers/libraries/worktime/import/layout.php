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
 * Interface used to change the layout of the processed working days.
 * 
 * @since 1.7.1
 */
interface VAPWorktimeImportLayout
{
	/**
	 * Refactor the times array into a specific layout.
	 * 
	 * @param 	array   $times  An array of working times.
	 * 
	 * @return 	mixed   The resulting layout.
	 */
	public function build($times);
}
