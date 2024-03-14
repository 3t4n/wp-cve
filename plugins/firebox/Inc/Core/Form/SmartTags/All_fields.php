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

/**
 * Syntax:						{fpf all_fields}
 * Hide labels:					{fpf all_fields --hideLabels=true}
 * Exclude empty values:		{fpf all_fields --excludeEmpty=true}
 * Exclude certain fields:		{fpf all_fields --excludeFields=text1,dropdown2}
 * Exclude certain field types: {fpf all_fields --excludeTypes=text,hidden}
 */
class All_fields extends \FPFramework\Base\SmartTags\SmartTag
{
	/**
	 * Get All Fields value
	 * 
	 * @return  string
	 */
	public function getAll_fields()
	{
		if (!$fields = $this->filteredFields())
		{
			return;
		}

		$all_fields = '';

		$hideLabels = $this->parsedOptions->get('hidelabels');
		
		foreach ($fields as $field)
		{
			if ($hideLabels)
			{
				$all_fields .= '<div>' . $field['class']->getValueHTML() . '</div>';
				continue;
			}

			$all_fields .= '<div><strong>' . $field['class']->getLabel() . '</strong>: ' . $field['class']->getValueHTML() . '</div>';
		}

		return $all_fields;
	}

	/**
	 * Filter submitted data with given filter options
	 *
	 * @return mixed	Null when no submission is found, array otherwise
	 */
	private function filteredFields()
	{
		$submission = isset($this->data['submission']) ? $this->data['submission'] : '';

		if (!$submission)
		{
			return '';
		}

		$excludeEmpty  = $this->parsedOptions->get('excludeempty', false);
		$excludeTypes  = array_filter(explode(',', $this->parsedOptions->get('excludetypes', '')));
		$excludeFields = array_filter(explode(',', $this->parsedOptions->get('excludefields', '')));

		return array_filter($submission['prepared_fields'], function($field) use ($excludeTypes, $excludeFields, $excludeEmpty)
		{
			if ($excludeEmpty && trim($field['value']) == '')
			{
				return;
			}

			if ($excludeTypes && in_array($field['class']->getOptionValue('type'), $excludeTypes))
			{
				return;
			}

			if ($excludeFields && in_array($field['class']->getOptionValue('name'), $excludeFields))
			{
				return;
			}

			return true;
		});
	}
}