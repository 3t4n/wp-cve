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
$media = $this->data->get('media', '');
$value = $this->data->get('value', '');
?>
<div class="fpf-media-uploader-wrapper<?php echo esc_attr($this->data->get('input_class')); ?>" data-media="<?php echo esc_attr($media); ?>">
	<input type="hidden"<?php echo wp_kses_data($this->data->get('required_attribute', '') . $this->data->get('extra_atts', '')); ?> id="fpf-control-input-item_<?php echo esc_attr($this->data->get('name_key')); ?>" class="fpf-field-item fpf-control-input-item mediauploader" value="<?php echo esc_url($value); ?>" name="<?php echo esc_attr($this->data->get('name')); ?>" />
	<div class="actions">
		<a href="#" class="fpf-media-uploader-add fpf-button">
			<span class="dashicons dashicons-upload icon"></span>
			<span class="text"><?php echo fpframework()->_('FPF_UPLOAD'); ?></span>
		</a>
		<a href="#" class="fpf-media-uploader-remove dashicons dashicons-no-alt fpf-button small<?php echo (!empty($value)) ? ' is-visible' : ''; ?>"></a>
	</div>
	<div class="fpf-media-uploader-preview<?php echo (!empty($value)) ? ' is-visible' : ''; ?>">
		<?php if ($media == 'image') { ?>
			<img src="<?php echo esc_url($value); ?>" alt="<?php echo fpframework()->_('FPF_MEDIA_UPLOAD_TMP_IMG_ALT'); ?>" />
		<?php } else if ($media == 'url') { ?>
			<div class="preview-url"></div>
		<?php } ?>
	</div>
</div>