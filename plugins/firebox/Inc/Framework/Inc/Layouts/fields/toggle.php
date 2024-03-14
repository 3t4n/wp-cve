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
$default = $this->data->get('default', '');
$input_class = $this->data->get('input_class', ' default');
$choices = (array) $this->data->get('choices', []);

// 0 = no, 1 = yes
$isYesNo = strpos($input_class, 'yesno') !== false;
// 0 = disabled, 1 = yes, 2 = no
$isDisabledYesNo = strpos($input_class, 'disabled-yes-no') !== false;
// whether the 0 value will be red
$isDisabledRed = strpos($input_class, 'disabled-red') !== false;
?>
<fieldset id="fpf-control-input-item_<?php echo esc_attr($this->data->get('name_key')); ?>_fieldset" class="fpf-field-item fpf-control-group-field toggle<?php echo esc_attr($input_class); ?>">
	<?php
	foreach ($choices as $key => $value)
	{
		$btn_class = '';
		if ($isYesNo || $isDisabledYesNo)
		{
			$btn_class = $key == '1' ? 'btn-success' : ($key == '2' ? 'btn-danger' : '');
		}

		if ($isDisabledRed)
		{
			$btn_class = $key == '0' ? 'btn-danger' : $btn_class;
		}
		?>
		<div class="item">
			<input type="radio"<?php echo wp_kses_data($this->data->get('extra_atts', '')); ?> id="fpf-control-input-item_<?php echo esc_attr($this->data->get('name_key')); ?><?php echo esc_attr($key); ?>" name="<?php echo esc_attr($this->data->get('name')); ?>" class="fpf-control-input-item fpf-toggle-control-item" value="<?php echo esc_attr($key); ?>"<?php echo ($this->data->get('checked') == $key) ? ' checked="checked"' : ''; ?> />
			<label for="fpf-control-input-item_<?php echo esc_attr($this->data->get('name_key')); ?><?php echo esc_attr($key); ?>" class="fpf-button<?php echo ((string) $this->data->get('checked') === (string) $key) ? ' active ' . esc_attr($btn_class) : ''; ?>"><?php echo esc_html(fpframework()->_($value)); ?></label>
		</div>
	<?php
	}
	?>
</fieldset>