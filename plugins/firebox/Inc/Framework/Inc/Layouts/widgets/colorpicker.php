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

if (!$this->data->get('readonly') && !$this->data->get('disabled'))
{
	wp_register_script(
		'fpframework-colorpicker-widget',
		FPF_MEDIA_URL . 'public/js/widgets/colorpicker.js',
		[],
		FPF_VERSION,
		true
	);
	wp_enqueue_script('fpframework-colorpicker-widget');
}

if ($this->data->get('load_stylesheet'))
{
	wp_register_style(
		'fpframework-colorpicker-widget',
		FPF_MEDIA_URL . 'public/css/widgets/colorpicker.css',
		[],
		FPF_VERSION,
		false
	);
	wp_enqueue_style('fpframework-colorpicker-widget');
}

if ($this->data->get('load_css_vars'))
{
	wp_add_inline_style('fpframework-colorpicker-widget', '
		.fpf-colorpicker-wrapper.' . esc_attr($this->data->get('id')) . ' {
			--input-background-color: ' . esc_attr($this->data->get('input_bg_color')) . ';
			--input-border-color: ' . esc_attr($this->data->get('input_border_color')) . ';
			--input-border-color-focus: ' . esc_attr($this->data->get('input_border_color_focus')) . ';
			--input-text-color: ' . esc_attr($this->data->get('input_text_color')) . ';
		}
	');
}
?>
<div class="fpf-widget fpf-colorpicker-wrapper<?php echo esc_attr($this->data->get('css_class')); ?>">
	<input type="color"
		value="<?php echo esc_attr($this->data->get('value')); ?>"
		<?php if ($this->data->get('readonly') || $this->data->get('disabled')): ?>
		disabled
		<?php endif; ?>
	/>
	<input type="text"
		id="<?php echo esc_attr($this->data->get('id')); ?>"
		name="<?php echo esc_attr($this->data->get('name')); ?>"
		class="<?php echo esc_attr($this->data->get('input_class')); ?>"
		value="<?php echo esc_attr($this->data->get('value')); ?>"
		placeholder="<?php echo esc_attr($this->data->get('placeholder')); ?>"
		<?php if ($this->data->get('required')) { ?>
			required
		<?php } ?>
		<?php if ($this->data->get('readonly')): ?>
		readonly
		<?php endif; ?>
	/>
</div>