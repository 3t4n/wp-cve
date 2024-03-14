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
 *  Color picker
 */
class ColorPicker extends Widget
{
	/**
	 * Widget default options
	 *
	 * @var array
	 */
	protected $widget_options = [
		// The default value of the widget. 
		'value' => '#dedede',

		// The input border color
		'input_border_color' => '#dedede',

		// The input border color on focus
		'input_border_color_focus' => '#dedede',

		// The input background color 
		'input_bg_color' => '#fff',

		// Input text color
		'input_text_color' => '#333'
	];
}