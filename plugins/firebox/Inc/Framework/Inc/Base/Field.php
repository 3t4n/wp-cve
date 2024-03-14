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

use FPFramework\Libs\Registry;
use FPFramework\Helpers\StringHelper;
use FPFramework\Helpers\ArrayHelper;
use FPFramework\Helpers\FieldsHelper;

abstract class Field
{
	/**
	 * All field options.
	 * 
	 * @var  array
	 */
	protected $options = [];

	/**
	 * Options specific for this field
	 * 
	 * @var  array
	 */
	protected $field_options = [];

	/**
	 * The filter of the field
	 *
	 * @var  Filter
	 */	
	protected $filter;

	public function __construct($options = [])
	{
		$this->options = $options;

		if (method_exists($this, 'setFieldOptions'))
		{
			$this->setFieldOptions($this->options);
		}

		// merge default and given field options
		$this->combineOptions();

		// prepare field early 
		$this->prePrepareData();

		// set value
		$this->options['value'] = $this->getValue();
		
		// prepare field after value has been set
		$this->postPrepareData();
	}

	/**
	 * Prepares the field data after field default settings and any field specifc settings have been combined 
	 * 
	 * @return  void
	 */
	protected function prePrepareData()
	{
		$options = new Registry($this->options);

		$this->options['value_raw'] = $this->options['value'];
		
		$name = $options->get('name', '');

		// prepare name
		if (!empty($name) && !$options->get('name_clean', false))
		{
			$name = $this->prepareName($options);
		}

		// add [value] to the name of the field if it has units
		if (count($options->get('units', [])) && !in_array($options->get('type'), ['Units', 'ResponsiveControl', 'Dimensions']))
		{
			$name .= '[value]';
		}
		$this->options['name'] = $name;

		// set input class
		if (is_array($options->get('input_class')) && count($options->get('input_class')))
		{
			$this->options['input_class'] = ' ' . implode(' ', $options->get('input_class', []));
		}

		// set required attribute
		if ($options->get('required'))
		{
			$this->options['required_attribute'] = ' required="required"';
		}
	}

	/**
	 * Prepares the field data after value has been set
	 * 
	 * @return  void
	 */
	protected function postPrepareData()
	{
		// add the "disable" class to the input if the selected unit was "auto" or if no unit value is set and the default unit is set to "auto"
		if (isset($this->options['unit']) && $this->options['unit'] == 'auto' ||
			(!isset($this->options['unit']) && isset($this->options['units_default']) && $this->options['units_default'] == 'auto'))
		{
			$this->options['extra_atts']['readonly'] = 'readonly';
		}

		// generate extra attributes
		$this->options['extra_atts'] = \FPFramework\Helpers\FieldsHelper::getHTMLAttributes($this->options['extra_atts']);
	}

	/**
	 * General filter value
	 * 
	 * @param   string   $filter
	 * 
	 * @return  void
	 */
	public function filterValue($filter = \FPFramework\Base\Filter::defaultFilter)
	{
		$filter = empty($filter) ? $this->options['filter'] : $filter;
		
		// set filter
		$this->filter = Filter::getInstance();

		$this->options['value'] = $this->filter->clean($this->options['value'], $filter);
	}

	/**
	 * Renders a field
	 * 
	 * @return  void
	 */
	public function render()
	{
		/**
		 * Runs before the field renders
		 */
		if (method_exists($this, 'onBeforeRender'))
		{
			$this->onBeforeRender();
		}

		$options = $this->getOptions();

		// check whether we manually set to skip loading of the field
		if ($this->options['skip_render'])
		{
			return;
		}

		// render top section only if needed by field
		if ($options['render_top'])
		{
			// get field top
			$options['field_top'] = $this->getFieldTop();
		}

		// get field body
		$options['field_body'] = $this->getFieldBody();

		// check if we need to render the whole group or only the field
		$render_file = !$options['render_group'] ? 'field_control' : 'tmpl';

		// render field
		fpframework()->renderer->field->render($render_file, $options);

		/**
		 * Runs after the field renders
		 */
		if (method_exists($this, 'onAfterRender'))
		{
			$this->onAfterRender();
		}
	}

	/**
	 * Returns the field data (plugin and type).
	 * 
	 * @return  string
	 */
	private function getFieldData()
	{
		$options = $this->getOptions();

		$framework = 'fpframework';

		if (!isset($options['field_path']) || empty($options['field_path']))
		{
			return $framework;
		}

		// check if we have a layout override and use it instead of the given field path
		$field_path = isset($this->layout_override) ? $this->layout_override : $options['field_path'];

		$plugin = '';

		// retrieve the name of the plugin from the given field source
		$path_data = explode('\\', $field_path);
		$path_data = array_filter($path_data);
		$path_data = array_values($path_data);

		// get plugin (fpframework or plugin name);
		$plugin = ltrim($path_data[0], '\\');
		$plugin = strtolower($plugin);

		// ensure plugin(framework or plugin) function exists
		if (!function_exists($plugin))
		{
			return $framework;
		}
		
		// if a layout override exists, show the field from the override, otherwise show the given field
		$type = isset($this->layout_override) ? $path_data[count($path_data) - 1] : $options['type'];
		
		return [
			'plugin' => $plugin,
			'type' => $type
		];
	}

	/**
	 * Gets the field value
	 * 
	 * @return  mixed
	 */
	public function getValue()
	{
		global $pagenow;

		$options = $this->getOptions();

		// if we were given a value directly, then use it
		if (!empty($options['value']) || $options['value'] == '0')
		{
			/**
			 * If a field has a unit (and the field is not an instance of Unit), it's value becomes:
			 * 'value' => [
			 * 		'value' => 123,
			 * 		'unit' => px
			 * ]
			 * 
			 * so we need to check whether the field has a unit and return its inner value, otherwise return the whole value
			 */
			return $this->hasUnits() && !$this instanceof \FPFramework\Base\Fields\Units && isset($options['value']->value) ? $options['value']->value : $options['value'];
		}
		
		// In a new Custom Post Type(CPT) Item
		if ($pagenow == 'post-new.php')
		{
			return $options['default'];
		}

		// Editing a CPT Item, we need to fetch the data from the post meta
		if ($pagenow == 'post.php')
		{
			return $this->getValueFromPostMeta();
		}

		return $this->getValueFromOptions();
	}

	/**
	 * Get value from post meta
	 * 
	 * @param   array   $meta
	 * 
	 * @return  string
	 */
	private function getValueFromPostMeta($meta = [])
	{
		$options = $this->getOptions();

		$default = isset($options['default']) ? $options['default'] : null;

		$name = $options['name'];

		if (empty($name))
		{
			return $default;
		}

		if (!$meta)
		{
			global $post;
			$meta = get_post_meta($post->ID, 'fpframework_meta_settings', true);
		}
		
		$val = FieldsHelper::findFieldValueInArray($name, $default, $meta);

		return $val;
	}

	/**
	 * Searching for value in the get_option() function
	 * 
	 * @return  string
	 */
	private function getValueFromOptions()
	{
		$options = $this->getOptions();

		// get the setting name
		$name_initial = $options['name'];
		$name = explode('[', $name_initial);

		// setting data
		$data = get_option($name[0]);

		$default = isset($options['default']) ? $options['default'] : null;
		
		// no results, return default
		if (!is_array($data))
		{
			return $default;
		}

		return FieldsHelper::findFieldValueInArray($name_initial, $default, $data);
	}

	/**
	 * Replaces dots with [].
	 * 
	 * @param   string  $name
	 * 
	 * @return  mixed
	 */
	private function prepareName($options)
	{
		$name = $options->get('name', null);
		if (!$name)
		{
			return;
		}

		$prefix = $suffix = '';

		$name_prefix = $options->get('name_prefix', null);

		// If we were given a name prefix and the name does not start with it, add prefix
		if ($name_prefix && !StringHelper::startsWith($name, $name_prefix))
		{
			$prefix = $name_prefix . '[';
		}
		// if no name prefix was given and if the first character of the name
		// does not start with open bracket then add prefix
		else if (!$name_prefix && $name[0] != '[')
		{
			$prefix = '[';
		}

		$name = str_replace('.', '][', $name);
		$suffix = $name[strlen($name) - 1] == ']' ? '' : ']';

		// Final Name
		return $prefix . $name . $suffix;
	}

	/**
	 * Sets field's value
	 * 
	 * @param   string  $value
	 * 
	 * @return  void
	 */
	public function setValue($value)
	{
		$this->options['value'] = $value;
	}

	/**
	 * Set options value
	 * 
	 * @param   string  $key
	 * @param   string  $value
	 * 
	 * @return  void
	 */
	public function setOptionsValue($key, $value)
	{
		$this->options[$key] = $value;
	}

	/**
	 * Checks whether the field contains units.
	 * 
	 * @return  bool
	 */
	protected function hasUnits()
	{
		return is_array($this->options['units']) && count($this->options['units']);
	}

	/**
	 * Renders field top
	 * Contains the Responsive Controls and the Units
	 * 
	 * @return  void
	 */
	public function getFieldTop()
	{
		$options = $this->getOptions();
		
		// units
		if (count($options['units']))
		{
			$default_units_value = isset($options['units_default']) ? $options['units_default'] : null;
			$unit_value = isset($options['unit']) && !empty($options['unit']) ? $options['unit'] : $default_units_value;
			$units_field_data = [
				'name' => rtrim($options['name_key'], ']') . '][unit]',
				'units' => (array) $options['units'],
				'value' => $unit_value,
				'units_relative_position' => $options['units_relative_position'],
				'render_top' => false,
				'render_group' => false
			];
			$units = new \FPFramework\Base\Fields\Units($units_field_data);

			ob_start();
			$units->render();
			$render = ob_get_contents();
			ob_end_clean();
			$options['field_top_units'] = $render;
		}
		
		return fpframework()->renderer->field->render('field_top', $options, true);
	}

	/**
	 * Renders field body
	 * 
	 * @return  void
	 */
	public function getFieldBody()
	{
		// determine who will render the field (plugin or framework)
		$field_data = $this->getFieldData();

		// render a specific field
		$plugin = isset($field_data['plugin']) ? $field_data['plugin'] : $field_data;
		$type = isset($field_data['type']) ? $field_data['type'] : $this->options['type'];
		
		return $plugin()->renderer->fields->render(strtolower($type), $this->getOptions(), true);
	}

	/**
	 * Combines all options
	 * 
	 * @return  array
	 */
	protected function combineOptions()
	{
		$this->options = $this->getDefaultOptions($this->options);

		// merge both the base field options as well as the specific field options
		$this->options = ArrayHelper::arrayMerge($this->options, $this->field_options);
	}

	/**
	 * Returns all options
	 * 
	 * @return  array
	 */
	protected function getOptions()
	{
		return $this->options;
	}

	/**
	 * Return specific option value
	 * 
	 * @param   string  $option
	 * 
	 * @return  array
	 */
	public function getOption($option = null)
	{
		if (!$option && !is_string($option) && !isset($this->option[$option]))
		{
			return null;
		}

		return $this->options[$option];
	}

	/**
	 * Gets the default options for a Field.
	 * 
	 * @param   array  $options
	 * 
	 * @return  void
	 */
	private function getDefaultOptions($options)
	{
		$options = new Registry($options);

		$initial_name = $options->get('name', '');
		
		$name_prefix = $options->get('name_prefix', \FPFramework\Admin\Includes\MetaboxManager::$fields_prefix);
		
		return [
			'name' => $initial_name,
			'name_key' => $options->get('name_key', $initial_name),
			'id' => $options->get('id', ''),
			'type' => $options->get('type', ''),
			'name_clean' => $options->get('name_clean', false),
			'name_prefix' => $name_prefix,
			'field_path' => $options->get('field_path', ''),
			'label' => fpframework()->_($options->get('label', '')),
			'description' => fpframework()->_($options->get('description', '')),
			'description_class' => $options->get('description_class', ['top']),
			'value_raw' => $options->get('value_raw', null),
			'value' => $options->get('value', null),
			'placeholder' => fpframework()->_($options->get('placeholder', '')),
			'default' => $options->get('default', null),
			'required' => $options->get('required', false),
			'required_attribute' => null,
			'class' => $options->get('class', null),
			'input_class' => $options->get('input_class', ''),
			'control_inner_class' => $options->get('control_inner_class', []),
			'input_parent_class' => $options->get('input_parent_class', []),
			'showon' => \FPFramework\Helpers\FormHelper::parseShowOnConditions($options->get('showon', null), $name_prefix),
			'filter' => $options->get('filter', 'sanitize_text_field'),
			'extra_atts' => $options->get('extra_atts', []),
			'render_group' => $options->get('render_group', true),
			'tooltip' => fpframework()->_($options->get('tooltip', '')),
			'unit' => $options->get('unit', null),
			'units' => $options->get('units', []),
			'units_relative_position' => $options->get('units_relative_position', true),
			'units_default' => $options->get('units_default', null),
			'render_top' => $options->get('render_top', true),
			'skip_render' => $options->get('skip_render', false)
		];
	}
}