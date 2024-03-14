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
$choices = $this->data->get('choices', []);
if (empty($choices))
{
	echo fpframework()->_('FPF_NO_ITEMS_FOUND');
	return;
}

$prepend_select_option = $this->data->get('prepend_select_option', '');
$prepended = false;

$value = $this->data->get('value', null);
?>
<select name="<?php esc_attr_e($this->data->get('name')); ?>"<?php echo wp_kses_data($this->data->get('required_attribute', '')); ?> id="fpf-control-input-item_<?php esc_attr_e($this->data->get('name')); ?>" class="fpf-select-field fpf-control-input-item<?php esc_attr_e($this->data->get('input_class')); ?>">
<?php
foreach ($choices as $key => $val)
{
	// prepend select option
	if (!empty($prepend_select_option) && !$prepended)
	{
		$prepended = true;
		?><option value="none"><?php esc_html_e(fpframework()->_($prepend_select_option)); ?></option><?php
		$prepend_select_option = false;
	}
	$selected = (strtolower($key ?? '') === strtolower($value ?? '')) ? ' selected="selected"' : '';
	if (is_object($val))
	{
		?><optgroup label="<?php esc_html_e(fpframework()->_($key)); ?>"><?php
		foreach($val as $key2 => $val2)
		{
			$selected2 = $key2 == $value ? ' selected="selected"' : '';
			?><option value="<?php esc_attr_e($key2); ?>"<?php echo wp_kses($selected2, ['selected' => []]); ?>><?php esc_html_e(fpframework()->_($val2)); ?></option><?php
		}
		?></optgroup><?php
	}
	else
	{
		?><option value="<?php esc_attr_e($key); ?>"<?php echo wp_kses($selected, ['selected' => []]); ?>><?php esc_html_e(fpframework()->_($val)); ?></option><?php
	}
}
?>
</select>