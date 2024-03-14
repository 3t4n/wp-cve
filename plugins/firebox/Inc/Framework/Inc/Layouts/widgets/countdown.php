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

if ($this->data->get('countdown_type') === 'static' && (empty($this->data->get('value')) || $this->data->get('value') === '0000-00-00 00:00:00'))
{
	return;
}

if ($this->data->get('load_css_vars') && !empty($this->data->get('custom_css')))
{
	wp_register_style('fpframework-widget-custom-' . $this->data->get('id'), false, ['fpframework-widget']);
	wp_enqueue_style('fpframework-widget-custom-' . $this->data->get('id'));
	wp_add_inline_style('fpframework-widget-custom-' . $this->data->get('id'), $this->data->get('custom_css'));
}

$allowed_tags = [
	'span' => [
		'class' => true,
		'style' => true,
		'id' => true,
	],
	'div' => [
		'class' => true,
		'style' => true,
		'id' => true,
		'data-*' => true
	],
	'p' => [
		'class' => true,
		'style' => true,
		'id' => true,
	],
	'br' => true
];
?>
<div class="fpf-widget fpf-countdown<?php echo esc_attr($this->data->get('css_class')); ?>"
	id="<?php echo esc_attr($this->data->get('id')); ?>"
	<?php echo wp_kses($this->data->get('atts', ''), $allowed_tags); ?>><?php echo wp_kses($this->data->get('preview_html', ''), $allowed_tags); ?></div>