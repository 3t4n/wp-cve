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
 * VikAppointments custom field control interface.
 *
 * @since 1.7
 */
interface VAPCustomFieldControl
{
	/**
	 * Returns the HTML of the field.
	 *
	 * @param 	array   $data   An array of display data.
	 * @param 	string  $input  The HTML of the input to wrap.
	 *
	 * @return  string  The HTML of the input.
	 */
	public function render($data, $input = null);
}
