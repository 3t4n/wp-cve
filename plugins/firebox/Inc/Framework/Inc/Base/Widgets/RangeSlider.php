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

/**
 *  The Range Slider widget
 */
class RangeSlider extends Widget
{
	/**
	 * Widget default options
	 *
	 * @var array
	 */
	protected $widget_options = [
		// The default value of the widget. 
		'value' => 0,

		// The minimum value of the slider
		'min' => 0,

		// The maximum value of the slider
		'max' => 100,

		// The step of the slider
		'step' => 1,

		// The main slider color
		'color' => '#1976d2',

		// The input border color of the slider inputs
		'input_border_color' => '#bdbdbd',

		// The input background color of the slider inputs
		'input_bg_color' => 'transparent'
	];

	/**
	 * Class constructor
	 *
	 * @param array $options
	 */
	public function __construct($options = [])
	{
		parent::__construct($options);

		// Base color is 20% of given color
		$this->options['base_color'] = $this->options['color'] . '33';

		// Calculate value
		$this->options['value'] = (int) $this->options['value'] < $this->options['min'] ? $this->options['min'] : ((int) $this->options['value'] > $this->options['max'] ? $this->options['max'] : (int) $this->options['value']);

		// Calculate bar percentage
		$this->options['bar_percentage'] = $this->options['max'] ? ~~(100 * ($this->options['value'] - $this->options['min']) / ($this->options['max'] - $this->options['min'])) : $this->options['value'];
	}
}