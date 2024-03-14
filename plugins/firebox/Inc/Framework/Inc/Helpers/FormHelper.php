<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class FormHelper
{
	/**
	 * Extra validation on the fields that we are preparing to save
	 * 
	 * @param   array  $fields
	 * 
	 * @return  array
	 */
	public static function validateFields($fields)
	{
		// remove repeater ITEM_ID template items
		foreach ($fields as $key => $value)
		{
			if ($key === 'ITEM_ID')
			{
				unset($fields[$key]);
			}
			else if (is_array($value))
			{
				$fields[$key] = self::validateFields($value);
			}
		}

		return $fields;
	}

	/**
	 * Filters every field based on the form settings
	 * 
	 * @param   array  $fields
	 * @param   array  $form_settings
	 * 
	 * @return  array
	 */
	public static function filterFields(&$fields, $form_settings)
	{
		// Validates the fields
		$fields = self::validateFields($fields);

		// Filter the fields only if we have entered new values
		if ($fields)
		{
			// filter all fields
			self::loopFieldsRecursive($fields, $form_settings);
		}
	}

	/**
	 * Loop all submitted fields and filter their values
	 * 
	 * @param   array   $fields
	 * @param   array   $form_settings
	 * @param   string  $fieldName
	 * 
	 * @return  void
	 */
	public static function loopFieldsRecursive(&$fields, $form_settings, $fieldName = '')
	{
		foreach ($fields as $key => &$value)
		{
			if (is_array($value) || is_object($value))
			{
				// loop all fields till we find a field name to sanitize
				self::loopFieldsRecursive($value, $form_settings, $fieldName . '[' . $key . ']');
			}
			else
			{
				// sanitize field
				self::sanitizeFieldRecursive($form_settings, $value, $fieldName . '[' . $key . ']');
			}
		}
	}

	/**
	 * Sanitizes the field value based on its filter or uses a general sanitize filter
	 * 
	 * @param   array   $form_settings
	 * @param   string  $fieldValue
	 * @param   string  $fieldName
	 * 
	 * @return  void
	 */
	public static function sanitizeFieldRecursive($form_settings, &$fieldValue, $fieldName = '')
	{
		// set filter
		$filter = \FPFramework\Base\Filter::getInstance();

		foreach ($form_settings as $key => $value)
		{
			// Skip if value is not an array
			if (!is_array($value))
			{
				continue;
			}

			// Filter each
			if (isset($value['name']) && $value['name'] == $fieldName)
			{
				$filterType = isset($value['filter']) ? $value['filter'] : \FPFramework\Base\Filter::defaultFilter;

				$fieldValue = $filter->clean($fieldValue, $filterType);
				return;
			}
			else
			{
				// else recurse till we find a valid field
				self::sanitizeFieldRecursive($value, $fieldValue, $fieldName);
			}
		}
	}
	
	/**
	 * Parse the show on conditions
	 *
	 * @param   string  $showOn       Show on conditions.
	 * @param   string  $formControl  Form name.
	 * @param   string  $group        The dot-separated form group path.
	 *
	 * @return  array   Array with show on conditions.
	 */
	public static function parseShowOnConditions($showOn, $formControl = null, $group = null)
	{
		// Process the showon data.
		if (!$showOn)
		{
			return [];
		}

		$formPath = $formControl ?: '';

		if ($group)
		{
			$groups = explode('.', $group);

			// An empty formControl leads to invalid shown property
			// Use the 1st part of the group instead to avoid.
			if (empty($formPath) && isset($groups[0]))
			{
				$formPath = $groups[0];
				array_shift($groups);
			}

			foreach ($groups as $group)
			{
				$formPath .= '[' . $group . ']';
			}
		}

		$showOnData  = [];
		$showOnParts = preg_split('#(\[AND\]|\[OR\])#', $showOn, -1, PREG_SPLIT_DELIM_CAPTURE);
		$op          = '';

		foreach ($showOnParts as $showOnPart)
		{
			if (($showOnPart === '[AND]') || $showOnPart === '[OR]')
			{
				$op = trim($showOnPart, '[]');
				continue;
			}

			$compareEqual     = strpos($showOnPart, '!:') === false;
			$showOnPartBlocks = explode(($compareEqual ? ':' : '!:'), $showOnPart, 2);

			if (strpos($showOnPartBlocks[0], '.') !== false)
			{
				if ($formControl)
				{
					$field = $formControl . ('[' . str_replace('.', '][', $showOnPartBlocks[0]) . ']');
				}
				else
				{
					$groupParts = explode('.', $showOnPartBlocks[0]);
					$field      = array_shift($groupParts) . '[' . join('][', $groupParts) . ']';
				}
			}
			else
			{
				$field = $formPath ? $formPath . $showOnPartBlocks[0] : $showOnPartBlocks[0];
			}

			$showOnData[] = [
				'field'  => $field,
				'values' => explode(',', $showOnPartBlocks[1]),
				'sign'   => $compareEqual === true ? '=' : '!=',
				'op'     => $op,
			];

			if ($op !== '')
			{
				$op = '';
			}
		}

		return wp_json_encode($showOnData);
	}
}