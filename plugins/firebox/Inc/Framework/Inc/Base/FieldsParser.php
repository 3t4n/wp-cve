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

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Helpers\HTML;
use FPFramework\Helpers\StringHelper;

class FieldsParser
{
	/**
	 * The Bind Data
	 * 
	 * @var  array
	 */
	protected $bind_data;

	/**
	 * The fields path of plugin.
	 * 
	 * @var  string
	 */
	protected $fields_path;

	/**
	 * The fields name prefix.
	 * This is used to prefix our field names.
	 * 
	 * @var  string
	 */
	protected $fields_name_prefix;

	public function __construct($data = [])
	{
		$this->bind_data = isset($data['bind_data']) ? (array) $data['bind_data'] : [];
		$this->fields_path = isset($data['fields_path']) ? (array) $data['fields_path'] : null;
		$this->fields_name_prefix = isset($data['fields_name_prefix']) ? $data['fields_name_prefix'] : \FPFramework\Admin\Includes\MetaboxManager::$fields_prefix;
	}
	
	/**
	 * Renders the  sections, in left, plain or right orientation
	 * 
	 * @param   array  $section
	 * 
	 * @return  void
	 */
	public function renderContentFields($section)
	{
		if (!isset($section['content']) && !is_array($section['content']) && !count($section['content']))
		{
			return;
		}
	
		// loop sections
		foreach ($section['content'] as $_section)
		{
			// render section fields
			$this->renderSectionFields($_section);
		}
	}
	
	/**
	 * Renders all fields within a section
	 * 
	 * @param   array  $section
	 * 
	 * @return  void
	 */
	public function renderSectionFields($section)
	{
		// Hide section if set
		if (isset($section['hide']))
		{
			return;
		}
		
		if (!isset($section['fields']))
		{
			return;
		}

		// wrap section title and fields - start
		$this->wrapFieldsSectionStart($section);
		
		// Render title of this set of fields
		$this->renderSectionTitle($section);

		// start - wrap the fields in a parent div
		$this->checkAndStartWrapSectionFieldsInDiv($section);

		// render all fields
		$this->renderFields($section['fields']);

		// end - wrap the fields in a parent div
		$this->checkAndEndWrapSectionFieldsInDiv($section);
		
		// wrap section title and fields - end
		$this->wrapFieldsSectionEnd($section);
	}

	/**
	 * Renders given fields
	 * 
	 * @param   string  $fields
	 * 
	 * @return  void
	 */
	public function renderFields($fields)
	{
		if (!is_array($fields) || !count($fields))
		{
			return;
		}
		
		foreach ($fields as $key => $field)
		{
			$this->loadField($field);
		}
	}

	/**
	 * Loads the field
	 * 
	 * @param   array  $field
	 * 
	 * @return  mixed
	 */
	public function loadField($field)
	{
		if (!isset($field['type']))
		{
			return;
		}

		if (!$field_path = $this->getFieldPath($field))
		{
			return;
		}

		// check if we need this field to have a name without prepending the prefix
		$name_clean = isset($field['name_clean']) ? (bool) $field['name_clean'] : false;

		// set field name prefix
		if (isset($field['name']) && !$name_clean)
		{
			$field['name_key'] = $field['name'];
		}
		
		// set name prefix for the field
		if ($this->fields_name_prefix)
		{
			$field['name_prefix'] = $this->fields_name_prefix;
		}

		// set field path
		$field['field_path'] = $field_path;

		// full field class name
		$field_class = $field_path . $field['type'];

		// bind data
		if ($this->bind_data && isset($field['name_key']))
		{
			// get binded value
			$binded_value = $this->getBindDataFieldValue($field['name_key']);

			// set field value
			if($binded_value || $binded_value == '0')
			{
				$field['value'] = $binded_value;
			}
		}

		// initialize field
		$field_class = new $field_class($field);
		
		$field_class->render();
	}

	/**
	 * Returns the field path. Whether this field exists in the framework or in a plugin.
	 * 
	 * @param   array  $field
	 * 
	 * @return  mixed
	 */
	private function getFieldPath($field)
	{
		$field_type = $field['type'];

		if ($this->fields_path && is_array($this->fields_path))
		{
			foreach ($this->fields_path as $fields_path)
			{
				// ensure field exists in field path
				if (!class_exists($fields_path . $field_type))
				{
					continue;
				}

				return $fields_path;
			}
		}

		// check framework
		$framework_fields_path = '\\FPFramework\\Base\\Fields\\';
		if (class_exists($framework_fields_path . $field_type))
		{
			return $framework_fields_path;
		}
		
		return false;
	}

	/**
	 * Gets the value from bind data based on field name
	 * 
	 * @param   string  $name
	 * 
	 * @return  mixed
	 */
	protected function getBindDataFieldValue($name)
	{
		if (!$name)
		{
			return null;
		}

		$value = '';
		$found = true;

		// make sure bind data are set
		if (!isset($this->bind_data))
		{
			return null;
		}

		// split name as format is key.key2.key3
		$splitted_name = explode('.', $name);

		// make sure we splitted the name and have data to use
		if (!count($splitted_name))
		{
			// check if it exists
			if (!isset($this->bind_data[$name]))
			{
				return null;
			}
			
			return $this->bind_data[$name];
		}
		
		// get first name item
		$key = $splitted_name[0];

		// if we have 1 name key only
		if (count($splitted_name) == 1)
		{
			// make sure it exists
			if (!isset($this->bind_data[$key]))
			{
				return null;
			}
			
			return $this->bind_data[$key];
		}

		// get the bind data to use and find the value
		$value = $this->bind_data;

		// loop given name keys to find value
		foreach ($splitted_name as $n)
		{
			$value = is_object($value) ? (array) $value : $value;
			
			if (!isset($value[$n]))
			{
				$found = null;
				break;
			}
			
			$value = $value[$n];
		}

		if (!$found)
		{
			return null;
		}
		
		return $value;
	}

	/**
	 * Wraps the section title and fields.
	 * 
	 * @param   array  $section
	 * 
	 * @return  void
	 */
	private function wrapFieldsSectionStart($section)
	{
		if (!isset($section['fields']) && !is_array($section['fields']) && count($section['fields']) < 2 && !isset($section['wrapper']))
		{
			return;
		}
		
		$showon = isset($section['wrapper']['showon']) ? $section['wrapper']['showon'] : '';
		
		// outer classes
		$default_outer_classes = ['fpf-fields-wrapper'];
		$outer_classes = (isset($section['wrapper']) && isset($section['wrapper']['outer_class'])) ? $section['wrapper']['outer_class'] : $default_outer_classes;

		// inner classes
		$default_classes = ['grid-x', 'grid-margin-x', 'grid-margin-y'];
		$inner_classes = isset($section['wrapper']) && isset($section['wrapper']['class']) ? $section['wrapper']['class'] : $default_classes;
		
		// render div openings
		if ($outer_classes)
		{
			echo HTML::renderStartDiv([
				'showon' => $showon,
				'class' => $outer_classes
			]);
		}

		if ($inner_classes)
		{
			echo HTML::renderStartDiv([
				'class' => $inner_classes
			]);
		}
	}

	/**
	 * Ends the wrap of section title and fields.
	 * 
	 * @param   array  $section
	 * 
	 * @return  void
	 */
	private function wrapFieldsSectionEnd($section)
	{
		if (!isset($section['fields']) && !is_array($section['fields']) && count($section['fields']) < 2 && !isset($section['wrapper']))
		{
			return;
		}
		
		// outer classes
		$default_outer_classes = ['fpf-fields-wrapper'];
		$outer_classes = (isset($section['wrapper']) && isset($section['wrapper']['outer_class'])) ? $section['wrapper']['outer_class'] : $default_outer_classes;

		// inner classes
		$default_classes = ['grid-x', 'grid-margin-x', 'grid-margin-y'];
		$inner_classes = isset($section['wrapper']) && isset($section['wrapper']['class']) ? $section['wrapper']['class'] : $default_classes;
		
		// render div openings
		if ($outer_classes)
		{
			echo HTML::renderEndDiv();
		}

		if ($inner_classes)
		{
			echo HTML::renderEndDiv();
		}
	}

	/**
	 * Renders the title of the fields section
	 * 
	 * @param   array  $section
	 * 
	 * @return  string
	 */
	protected function renderSectionTitle($section)
	{
		if (!isset($section['title']))
		{
			return;
		}

		// set default heading type
		if (!isset($section['title']['heading_type']))
		{
			$section['title']['heading_type'] = 'h4';
		}
		
		$section['title']['input_class'][] = 'cell large-3 fpf-section-heading'; 

		echo HTML::renderHeading($section['title']);
	}

	/**
	 * Checks if we have a section title and wraps the fields with a div
	 * 
	 * @param   array  $section
	 * 
	 * @return  string
	 */
	protected function checkAndStartWrapSectionFieldsInDiv($section)
	{
		if (!isset($section['title']))
		{
			return;
		}

		echo HTML::renderStartDiv([
			'class' => ['cell', 'large-auto', 'fpf-section-content']
		]);
		
		echo HTML::renderStartDiv([
			'class' => ['grid-x', 'grid-margin-x', 'grid-margin-y']
		]);
	}

	/**
	 * Checks if we have a section title and close the div that wrapped the fields
	 * 
	 * @param   array  $section
	 * 
	 * @return  string
	 */
	protected function checkAndEndWrapSectionFieldsInDiv($section)
	{
		if (!isset($section['title']))
		{
			return;
		}

		echo HTML::renderEndDiv();
		echo HTML::renderEndDiv();
	}

	/**
	 * Set form bind data
	 * 
	 * @param   array  $bind_data
	 * 
	 * @return  void
	 */
	public function setBindData($bind_data)
	{
		$this->bind_data = $bind_data;
	}
}