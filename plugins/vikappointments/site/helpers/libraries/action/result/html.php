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
 * Wraps the results returned by the subscribers.
 * Accepts only displayable strings.
 * 
 * @since 1.7.3
 */
class VAPActionResultHtml extends VAPActionResult
{
	/**
	 * Filters the resulting elements while constructing the object.
	 * Children classes can override this method to sanitize the 
	 * received results.
	 * 
	 * @param 	mixed 	$elem  The element to map.
	 * 
	 * @return 	mixed   The mapped element.
	 */
	protected function map($elem)
	{
		// accepts only strings
		return is_string($elem) ? $elem : null;
	}

	/**
	 * Implodes all the results into a single string.
	 * 
	 * @param 	string  $glue  The strings separator.
	 * 
	 * @return 	string  The resulting HTML.
	 */
	public function display($glue = "\n")
	{
		return implode($glue, $this->toArray());
	}
}
