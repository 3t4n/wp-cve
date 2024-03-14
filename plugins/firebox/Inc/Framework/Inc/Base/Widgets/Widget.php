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

namespace FPFramework\Base\Widgets;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Widget
{
	/**
	 * Widget's default options
	 *
	 * @var array
	 */
	protected $options = [
		// Set whether to load the CSS variables
		'load_css_vars' => true,

		// Set whether to load the default stylesheet
		'load_stylesheet' => true,

		// If true, the widget will be rended in read-only mode.
		'readonly' => false,

		// If true, the widget will be rended in disabled mode.
		'disabled' => false,

		// Indicates the widget's input field must be filled out before submitting the form.
		'required' => false,

		// The CSS class to be used on the widget's wrapper
		'css_class' => '',

		// The CSS class to be used on the input
		'input_class' => '',

		// The default widget value
		'value' => '',

		// A short hint that describes the expected value
		'placeholder' => '',

		// Custom CSS added by widgets
		'custom_css' => ''
	];

	/**
	 * If no name is provided, this counter is appended to the widget's name to prevent name conflicts 
	 *
	 * @var int
	 */
	protected static $counter = 0;

	/**
	 * Class constructor
	 *
	 * @param array $options
	 */
	public function __construct($options = [])
	{
		// Merge Widget class default options with given Widget default options
		$this->options = array_merge($this->options, $this->widget_options, $options);

		// Set ID if none given
		if (!isset($this->options['id']))
		{
			$this->options['id'] = $this->getName() . self::$counter;
		}

		// Help developers target the whole widget by applying the widget's ID to the CSS class list.
		// Do not use the id="xx" attribute in the HTML to prevent conflicts with the input's ID.
		$this->options['css_class'] .= ' ' . $this->options['id'];
		
		// Set name if none given
		if (!isset($this->options['name']))
		{
			$this->options['name'] = $this->options['id'];
		}
		
		// Set disabled class if widget is disabled
		if ($this->options['disabled'])
		{
			$this->options['css_class'] .= ' disabled';
		}

		if ($this->options['readonly'])
		{
			$this->options['css_class'] .= ' readonly';
		}

		self::$counter++;

		// Let each widget to register & load their assets individually
		if (method_exists($this, 'public_assets'))
		{
			$this->public_assets();
		}
	}

	/**
	 * Renders the widget
	 * 
	 * @return  string
	 */
	public function render()
	{
		return fpframework()->renderer->render('widgets/' . $this->getName(), $this->options, true);
	}

	/**
	 * Get the name of the widget
	 *
	 * @return void
	 */
	public function getName()
	{
		return strtolower((new \ReflectionClass($this))->getShortName());
	}
}