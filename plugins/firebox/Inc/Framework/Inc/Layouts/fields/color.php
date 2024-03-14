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
$value = $this->data->get('value');
?>
<div class="fpf-colorpicker-toggle-control">
	<div class="fpf-colorpicker-opener">
		<span class="color-preview" style="background:<?php esc_attr_e($value); ?>;"></span>
		<input
			type="text"
			id="fpf-control-input-item_<?php esc_attr_e($this->data->get('name')); ?>"
			<?php echo wp_kses_data($this->data->get('required_attribute', '')); ?>
			placeholder="<?php esc_attr_e($this->data->get('placeholder', '')); ?>"
			value="<?php esc_attr_e($value); ?>"
			class="fpf-control-input-item color-preview-value<?php esc_attr_e($this->data->get('input_class')); ?>"
		/>
	</div>
</div>
<input
	type="text"<?php echo wp_kses_data($this->data->get('extra_atts', '')); ?>
	data-default-color="<?php esc_attr_e($this->data->get('default')); ?>"
	data-alpha="true"
	class="fpf-field-item fpf-control-input-item fpf-colorpicker-item"
	value="<?php esc_attr_e($value); ?>"
	name="<?php esc_attr_e($this->data->get('name')); ?>"
/>