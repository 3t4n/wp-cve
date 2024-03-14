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
 * VikAppointments custom field separator handler.
 *
 * @since 1.7
 */
class VAPCustomFieldSeparator extends VAPCustomField
{
	/**
	 * Returns the name of the field type.
	 *
	 * @return 	string
	 */
	public function getType()
	{
		return JText::translate('VAPCUSTOMFTYPEOPTION6');
	}

	/**
	 * Extracts the value of the custom field and applies any
	 * sanitizing according to the settings of the field.
	 *
	 * @param 	array  &$args  The array data to fill-in in case of
	 *                         specific rules (name, e-mail, etc...).
	 *
	 * @return 	mixed  A scalar value of the custom field.
	 */
	protected function extract(&$args)
	{
		return '';
	}

	/**
	 * Returns the HTML of the field.
	 *
	 * @param 	array   $data   An array of display data.
	 * @param 	string  $input  The HTML of the input to wrap.
	 *
	 * @return  string  The HTML of the input.
	 */
	protected function getControl($data, $input = null)
	{
		// do not wrap field within a control
		return is_null($input) ? $this->getInput($data) : $input;
	}

	/**
	 * Returns an array of display data.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	array
	 */
	protected function getDisplayData(array $data)
	{
		// init display data
		$data = parent::getDisplayData($data);

		if ($sfx = $this->get('choose'))
		{
			// inject class suffix
			$data['class'] = trim($data['class'] . ' ' . $sfx);
		}

		return $data;
	}
}
