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

$picker_icon = strpos($this->data->get('input_class'), 'fpf-timepicker-item') !== false ? 'clock' : 'calendar-alt';

$value = $this->data->get('value', '');

$reset_classes = empty($value) ? ' is-hidden' : '';
?>
<div class="fpf-datepicker-parent">
	<input type="text"<?php echo wp_kses_data($this->data->get('required_attribute', '') . $this->data->get('extra_atts')); ?> id="fpf-control-input-item_<?php esc_attr_e($this->data->get('name')); ?>" class="fpf-field-item fpf-control-input-item fpf-datepicker-item<?php esc_attr_e($this->data->get('input_class', '')); ?>" placeholder="<?php esc_attr_e($this->data->get('placeholder', '')); ?>" value="<?php esc_attr_e($value); ?>" name="<?php esc_attr_e($this->data->get('name')); ?>" />
	<?php if ($this->data->get('show_open_button', true)): ?>
	<a href="#" class="fpf-button fpf-datepicker-button fpf-datepicker-open-calendar dashicons dashicons-<?php esc_attr_e($picker_icon); ?>" title="<?php esc_attr_e(fpframework()->_('FPF_OPEN')); ?>"></a>
	<?php endif; ?>
	<?php if ($this->data->get('show_clear_button', true)): ?>
	<a href="#" class="fpf-button fpf-datepicker-button fpf-datepicker-clear-calendar dashicons dashicons-no-alt<?php esc_attr_e($reset_classes); ?>" title="<?php esc_attr_e(fpframework()->_('FPF_CLEAR')); ?>"></a>
	<?php endif; ?>
</div>