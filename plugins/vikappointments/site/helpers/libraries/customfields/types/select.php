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
 * VikAppointments custom field select handler.
 *
 * @since 1.7
 */
class VAPCustomFieldSelect extends VAPCustomField
{
	/**
	 * Returns the name of the field type.
	 *
	 * @return 	string
	 */
	public function getType()
	{
		return JText::translate('VAPCUSTOMFTYPEOPTION4');
	}

	/**
	 * Children classes can override this method to alter the
	 * value to display.
	 *
	 * @param 	string 	$value  The value stored within the database.
	 *
	 * @param 	string  A readable text.
	 */
	public function getReadableValue($value)
	{
		if (!strlen($value))
		{
			// empty value
			return '';
		}

		if (preg_match("/^\[/", $value))
		{
			// list received, JSON decode it
			$value = (array) json_decode($value);
		}
		else
		{
			// treat value as array
			$value = (array) $value;
		}

		// extract options and translations
		$choose = (array) json_decode($this->get('_choose', ''), true);
		$lang   = (array) json_decode($this->get('choose', ''), true);

		// map values to access the related texts
		$value = array_map(function($elem) use ($choose, $lang)
		{
			// look for a translation
			if (!empty($lang[$elem]))
			{
				return $lang[$elem];
			}

			// look for an original option
			if (isset($choose[$elem]))
			{
				return $choose[$elem];
			}

			// option not found
			return '';
		}, $value);

		// strip empty values and join them
		return implode(', ', array_filter($value));
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
		$data = parent::getDisplayData($data);

		// create list of options if not specified
		if (!isset($data['options']))
		{
			$data['options'] = array();

			if (!$this->get('multiple'))
			{
				/**
				 * Add an empty option that acts as a placeholder in
				 * case of dropdowns with single selection.
				 *
				 * @since 1.7
				 */
				$data['options'][] = JHtml::fetch('select.option', '', '');
			}
		}

		// extract options and translations
		$choose = (array) json_decode($this->get('_choose', ''), true);
		$lang   = (array) json_decode($this->get('choose', ''), true);

		foreach ($choose as $value => $text)
		{
			if (!empty($lang[$value]))
			{
				// overwrite text with given translation
				$text = $lang[$value];
			}

			$data['options'][] = JHtml::fetch('select.option', $value, $text);
		}

		return $data;
	}
}
