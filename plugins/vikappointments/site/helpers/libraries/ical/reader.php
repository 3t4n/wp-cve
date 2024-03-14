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
 * Defines the command needed to extract an iCalendar buffer from
 * a specific type of source.
 * 
 * @since 1.7.3
 */
interface VAPIcalReader
{
	/**
	 * Extracts the iCalendar buffer from a source.
	 * 
	 * @return  string  The iCalendar string.
	 */
	public function load();
}
