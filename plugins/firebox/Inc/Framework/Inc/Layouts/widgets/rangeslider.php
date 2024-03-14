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
		'fpframework-slider-widget',
		FPF_MEDIA_URL . 'public/js/widgets/slider.js',
		[],
		FPF_VERSION,
		true
	);
	wp_enqueue_script('fpframework-slider-widget');
}

if ($this->data->get('load_stylesheet'))
{
	wp_register_style(
		'fpframework-slider-widget',
		FPF_MEDIA_URL . 'public/css/widgets/slider.css',
		[],
		FPF_VERSION,
		false
	);
	wp_enqueue_style('fpframework-slider-widget');
}

if ($this->data->get('load_css_vars'))
{
	wp_add_inline_style('fpframework-slider-widget', '
		.fpf-slider-wrapper.' . esc_attr($this->data->get('id')) . ' {
			--base-color: ' . esc_attr($this->data->get('base_color')) . ';
			--progress-color: ' . esc_attr($this->data->get('color')) . ';
			--input-bg-color: ' . esc_attr($this->data->get('input_bg_color')) . ';
			--input-border-color: ' . esc_attr($this->data->get('input_border_color')) . ';
			--thumb-shadow-color: ' . esc_attr($this->data->get('color')) . '26' . ';
		}
	');
}
?>
<div class="fpf-widget fpf-slider-wrapper<?php echo esc_attr($this->data->get('css_class')); ?>">
	<input
		type="range"
		class="fpf-slider-range"
		min="<?php echo esc_attr($this->data->get('min')); ?>"
		max="<?php echo esc_attr($this->data->get('max')); ?>"
		step="<?php echo esc_attr($this->data->get('step')); ?>"
		value="<?php echo esc_attr($this->data->get('value')); ?>"
		<?php if ($load_css_vars): ?>
			data-base-color="<?php echo esc_attr($this->data->get('base_color')); ?>"
			data-progress-color="<?php echo esc_attr($this->data->get('color')); ?>"
			style="background: linear-gradient(to right, <?php echo esc_attr($this->data->get('color')); ?> 0%, <?php echo esc_attr($this->data->get('color')) . ' ' . esc_attr($this->data->get('bar_percentage')); ?>%, <?php echo esc_attr($this->data->get('base_color')) . ' ' . esc_attr($this->data->get('bar_percentage')); ?>%, <?php echo esc_attr($this->data->get('base_color')); ?> 100%)"
		<?php endif; ?>
		<?php if ($this->data->get('readonly') || $this->data->get('disabled')): ?>
		disabled
		<?php endif; ?>
	/>
	<input
		type="number"
		value="<?php echo esc_attr($this->data->get('value')); ?>"
		id="<?php echo esc_attr($this->data->get('id')); ?>"
		name="<?php echo esc_attr($this->data->get('name')); ?>"
		min="<?php echo esc_attr($this->data->get('min')); ?>"
		max="<?php echo esc_attr($this->data->get('max')); ?>"
		step="<?php echo esc_attr($this->data->get('step')); ?>"
		class="fpf-slider-value <?php echo esc_attr($this->data->get('input_class')); ?>"
		<?php if ($this->data->get('readonly') || $this->data->get('disabled')): ?>
		readonly
		<?php endif; ?>
	/>
</div>