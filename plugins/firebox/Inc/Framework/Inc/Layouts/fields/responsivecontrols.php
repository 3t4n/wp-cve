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
$devices = $this->data->get('devices', []);
if (!is_array($devices))
{
	return;
}

$devices = array_filter(array_values(array_unique($devices)));
if (empty($devices))
{
	return;
}

$value = $this->data->get('value', $devices[0]);
?>
<div class="fpf-responsive-controls-item">
	<?php
	foreach ($devices as $device)
	{
		if (is_array($device))
		{
			continue;
		}
		$isActive = $value == $device;
		$icon = $device == 'mobile' ? 'smartphone' : $device;
		?>
		<div
			class="fpf-responsive-controls-inner-item"
			data-device="<?php echo esc_attr($device); ?>"
			title="<?php echo esc_html(sprintf(fpframework()->_('FPF_X_DEVICE_SETTINGS'), ucfirst($device))); ?>">
			<input type="radio" id="fpf-responsive-controls-inner-input-item_<?php echo esc_attr($this->data->get('name_key') . '_' . $device); ?>" name="<?php echo esc_attr($this->data->get('name', '')); ?>" value="<?php echo esc_attr($device); ?>"<?php echo $isActive ? ' checked="checked"' : ''; ?> />
			<label for="fpf-responsive-controls-inner-input-item_<?php echo esc_attr($this->data->get('name_key') . '_' . $device); ?>"><span class="dashicons dashicons-<?php echo esc_attr($icon); ?>"></span></label>
		</div>
		<?php
	}
	?>
</div>