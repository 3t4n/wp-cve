<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Form\SmartTags;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Field extends \FPFramework\Base\SmartTags\SmartTag
{
	/**
	 * Run only when we have a valid submissions object.
	 *
	 * @return boolean
	 */
	public function canRun()
	{
		return isset($this->data['submission']) ? parent::canRun() : false;
	}
	
    /**
	 * Fetch field value
	 * 
	 * @param   string  $key
	 * 
	 * @return  string
	 */
	public function fetchValue($key)
	{
		$submission = $this->data['submission'];

		// Separate key parts into an array as it's very likely to have a key in the format: field.label
		$keyParts = explode('.', $key);
		$fieldName = strtolower($keyParts[0]);
		$special_param = isset($keyParts[1]) ? $keyParts[1] : null;
		// Make keys lowercase to ensure our lowercase field name is matched
		$fields = array_change_key_case($submission['prepared_fields']);

		// Check that the field name does exist in the submission data
		if (!array_key_exists($fieldName, $fields))
		{
			return;
		}
		
		// Make sure $fieldName is strtolower-ed as prepared_fields is an assoc array with lower case keys.
		$field = $fields[$fieldName];

		// We need to return the value of the field
		switch ($special_param)
		{
			case 'raw':
				// The raw value as saved in the database.
				return $field['class']->getValueRaw();
				break;

			case 'html':
				// The value as transformed to be shown in HTML.
				return $field['class']->getValueHTML();
				break;
			
			default:
				// The value in plain text. Arrays will be shown comma separated.
				return $field['class']->getValue();
		}
	}
}