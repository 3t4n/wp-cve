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

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}
$input_parent_class = $this->data->get('input_parent_class');
echo $this->data->get('field_top'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
/**
 * Add parent div with given class only to the control input.
 * This is useful when we only want to render the control input and want it to have a parent div for different purposes.
 */
if ($this->data->get('render_group') == false && $input_parent_class && is_array($input_parent_class) && count($input_parent_class))
{
	?><div class="<?php echo esc_attr(implode(' ', $input_parent_class)); ?>"><?php
}
echo $this->data->get('field_body'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
if ($this->data->get('render_group') == false && $input_parent_class && is_array($input_parent_class) && count($input_parent_class))
{
	?></div><?php
}