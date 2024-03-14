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
$showLink = $this->data->get('showLink', true);

$default = $this->data->get('default', '');
$value = $this->data->get('value');

$linked = isset($value->linked) && $value->linked == 'true' ? true : (!isset($value->linked) ? $this->data->get('isLinked', false) : false);
?>
<ul class="fpf-dimensions-control<?php echo $this->data->get('class') ? esc_attr(' ' . implode(' ', $this->data->get('class'))) : ''; echo ($showLink) ? ' with-link' : ''; echo ($linked) ? ' is-linked' : ''; ?>">
	<?php
	foreach ($this->data->get('labels', []) as $key => $dimension_label)
	{
		$dimension_value = isset($value->$key) ? $value->$key : $default;
		?>
		<li class="fpf-dimensions-control-item">
			<input type="number"<?php echo wp_kses_data($this->data->get('number_atts', '') . $this->data->get('required_attribute') . $this->data->get('extra_atts')); ?> id="fpf-control-input-item_<?php echo esc_attr($this->data->get('name_key') . '_' . $key); ?>" class="fpf-field-item fpf-control-input-item dimensions<?php echo esc_attr($this->data->get('input_class')); ?>" placeholder="<?php echo esc_attr($this->data->get('placeholder', '')); ?>" name="<?php echo esc_attr($this->data->get('name') . '[' . $key . ']'); ?>" value="<?php echo esc_attr($dimension_value); ?>" />
			<label for="fpf-control-input-item_<?php echo esc_attr($this->data->get('name_key') . '_' . $key); ?>"><?php echo esc_html($dimension_label); ?></label>
		</li>
		<?php
	}
	?>
	<?php if ($showLink) : ?>
		<li class="fpf-dimensions-control-item">
			<button class="fpf-dimensions-link-button<?php echo ($linked) ? ' is-active' : ''; ?>" title="<?php echo fpframework()->_('FPF_DIMENSIONS_FIELD_LINK_VALUES_TITLE') ?>">
				<span class="dashicons dashicons-admin-links icon link"></span>
				<input type="hidden" value="<?php echo ($linked) ? 'true' : 'false'; ?>" name="<?php echo esc_attr($this->data->get('name')) . '[linked]'; ?>" />
			</button>
		</li>
	<?php endif; ?>
</ul>