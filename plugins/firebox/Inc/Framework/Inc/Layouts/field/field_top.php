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
$type = $this->data->get('type');
$units_relative_position = $this->data->get('units_relative_position', true);
$units = $this->data->get('units', []);
if ($type != 'ResponsiveControl' && !count($units))
{
	return;
}
$class = '';
$class .= ($type != 'ResponsiveControl' && !$units_relative_position) ? ' margin-bottom-0' : '';
// if we have only units, add them in same line after the field
$class .= empty($this->data->get('field_top_responsive_controls')) && !empty($this->data->get('field_top_units')) ? ' fpf-units-end' : '';
// whether units is set to appear in non-relative position (above the control, with absolute position, used when we have responsive control)
$class .= !$units_relative_position ? ' not-relative-pos' : '';
?>
<div class="fpf-field-control-top<?php echo esc_attr($class); ?>">
	<?php
		// render responsive controls
		echo $this->data->get('field_top_responsive_controls'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		// render units
		echo $this->data->get('field_top_units'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</div>