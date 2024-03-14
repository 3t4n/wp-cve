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
$units = $this->data->get('units', []);
if (!is_array($units))
{
	return;
}

$units = array_values(array_unique($units));
$units = array_filter($units);
if (empty($units))
{
	return;
}

$device = $this->data->get('device', '');
$device = !empty($device) ? $device . '_' : '';

$units_default = $this->data->get('units_default', $units[0]);
$value = $this->data->get('value', $units_default);

$name = $this->data->get('name', '');

$classes = is_array($units) && count($units) == 1 ? ' no-selector' : '';
?>
<div
	class="fpf-units-control<?php echo esc_attr($classes); ?>"
	<?php echo (is_array($units) && count($units) > 1) ? 'title="' . esc_html(fpframework()->_('FPF_UNITS_TITLE')) . '"' : ''; ?>>
	<span class="fpf-units-control-selected-unit-preview"><?php echo esc_html($value); ?></span>
	<?php if (is_array($units) && count($units) > 1) { ?>
	<a href="#" class="fpf-units-control-selector-toggle dashicons dashicons-arrow-down-alt2"></a>
	<div class="fpf-units-control-selector">
		<?php
		foreach ($units as $unit)
		{
			if (is_array($unit))
			{
				continue;
			}
			
			$isActive = ($value == $unit || (empty($value) && $unit == $units[0]));
			?>
			<div class="fpf-unit-control-item">
				<input id="fpf-unit-control-input-item_<?php echo esc_attr($device . $this->data->get('name_key') . '_' . $unit); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($unit); ?>"<?php echo $isActive ? ' checked="checked"' : ''; ?> type="radio" />
				<label for="fpf-unit-control-input-item_<?php echo esc_attr($device . $this->data->get('name_key') . '_' . $unit); ?>"><?php echo esc_html($unit); ?></label>
			</div>
			<?php
		}
		?>
	</div>
	<?php } ?>
</div>