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
 * VikAppointments custom field checkbox handler.
 *
 * @since 1.7
 */
class VAPCustomFieldCheckbox extends VAPCustomField
{
	/**
	 * Returns the name of the field type.
	 *
	 * @return 	string
	 */
	public function getType()
	{
		return JText::translate('VAPCUSTOMFTYPEOPTION5');
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
		if ($value == 1
			|| $value == 'JYES'
			|| $value == JText::translate('JYES'))
		{
			return JText::translate('JYES');
		}

		return JText::translate('JNO');
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
		// fetch "checked" status
		$data['checked'] = !empty($data['value'])
			&& (
				$data['value'] == 1
				|| $data['value'] == 'JYES'
				|| $data['value'] == JText::translate('JYES')
			);

		return parent::getDisplayData($data);
	}
}
