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

// If cookie exists, hide notification
if ($this->data->get('cookies') && $cookie = fpframework()->helper->factory->getCookie($this->data->get('cookie_id')))
{
	return;
}

if ($this->data->get('load_css_vars') && !empty($this->data->get('custom_css')))
{
	wp_register_style('fpframework-widget-custom-' . $this->data->get('id'), false, ['fpframework-widget']);
	wp_enqueue_style('fpframework-widget-custom-' . $this->data->get('id'));
	wp_add_inline_style('fpframework-widget-custom-' . $this->data->get('id'), $this->data->get('custom_css'));
}
$allowed_tags = array_merge(wp_kses_allowed_html('post'), [
	'img' => [
		'style' => true,
		'class' => true,
		'id' => true
	],
	'mark' => [
		'style' => true,
		'class' => true
	]
]);
?>
<div class="fpf-widget fpf-notification<?php echo esc_attr($this->data->get('css_class')); ?>"
<?php
if ($this->data->get('cookies'))
{
	?>
	data-cookie-id="<?php echo esc_attr($this->data->get('cookie_id')); ?>"
	data-close-cookie-days="<?php echo esc_attr($this->data->get('close_cookie_days')); ?>"
	<?php
}
?>
>
	<span class="icons">
		<?php
		switch ($this->data->get('type')) {
			case 'information':
			default:
				?><svg width="20" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="information"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg><?php
				break;
			case 'success':
				?><svg width="20" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="success"><path fill="currentColor" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg><?php
				break;
			case 'error':
				?><svg width="20" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="error"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg><?php
				break;
			case 'warning':
				?><svg width="20" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="warning"><path fill="currentColor" d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg><?php
				break;
		}
		?>
	</span>
	<div class="text">
		<?php if (!empty($this->data->get('title')) && $this->data->get('enable_title')): ?>
		<div class="title"><?php echo wp_kses($this->data->get('title', ''), $allowed_tags); ?></div>
		<?php endif; ?>
		<?php if (!empty($this->data->get('message'))): ?>
		<div class="message"><?php echo wp_kses($this->data->get('message', ''), $allowed_tags); ?></div>
		<?php endif; ?>
	</div>
	<?php if ($this->data->get('show_close_button')): ?>
		<svg class="fpf-notification-close" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path></svg>
	<?php endif; ?>
</div>