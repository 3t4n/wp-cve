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
 * Base interface to display a custom input.
 *
 * @since 	1.6
 */
interface UIInput
{
	/**
	 * Call this method to build and return the HTML of the input.
	 *
	 * @return 	string 	The input HTML.
	 */
	public function display();
}
