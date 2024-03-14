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
$btn_classes = !empty($this->data->get('btn_class')) ? ' ' . implode(' ', $this->data->get('btn_class')) : '';
$actions_classes = !empty($this->data->get('actions_class')) ? ' ' . implode(' ', $this->data->get('actions_class')) : '';
$remove_action_class = !empty($this->data->get('remove_action_class')) ? ' ' . implode(' ', $this->data->get('remove_action_class')) : '';
$items = $this->data->get('value');
$class = $this->data->get('class', []);
$class = $class && is_array($class) && count($class) ? ' ' . esc_attr(implode(' ', $class)) : '';
$repeater_item_class = count($this->data->get('repeater_item_class')) ? ' ' . esc_attr(implode(' ', $this->data->get('repeater_item_class'))) : '';
$actions = '
    <ul class="actions">'
        . $this->data->get('actions_prepend', '') . '
        <li class="fpf-repeater-field-add-item-btn add" title="' . esc_attr(fpframework()->_('FPF_ADD_ITEM')) . '"><i class="dashicons dashicons-plus-alt"></i></li>
        <li class="fpf-repeater-field-remove-item-btn remove' . esc_attr($remove_action_class) . '" title="' . esc_attr(fpframework()->_('FPF_REMOVE_ITEM')) . '"><i class="dashicons dashicons-no-alt"></i></li>
        <li class="fpf-repeater-field-move-item-btn" title="' . esc_attr(fpframework()->_('FPF_MOVE_ITEM')) . '"><i class="dashicons dashicons-move"></i></li>
        ' . $this->data->get('actions_append', '') . '
    </ul>';
$default = $this->data->get('default', []);
$value_raw = $this->data->get('value_raw');
$default_values = $this->data->get('default_values');
$default_html = $this->data->get('default_html');
// If the repeater has default values and no actual value is given, then show the fields with default values
if ($value_raw === null && $default_values)
{
    $items = $default_html;
}
?>
<div class="fpf-control-group-field fpf-repeater-field<?php esc_attr_e($class); ?>"
     data-name="<?php esc_attr_e($this->data->get('name_key')); ?>">
    <div class="items">
        <?php
        if (count($items))
        {
            $i = 1;
            foreach ($items as $key => $html)
            {
                ?><div class="fpf-repeater-item<?php esc_attr_e($repeater_item_class); ?>" data-item-id="<?php esc_attr_e($i); ?>">
                    <?php echo $html . $actions; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div><?php
                $i++;
            }
        }
        ?>
    </div>
    <div class="template fpf-repeater-item<?php esc_attr_e($repeater_item_class); ?>" data-item-id="[ITEM_ID]">
        <?php echo $this->data->get('template', '') . $actions; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
    <div class="repeater-actions<?php esc_attr_e($actions_classes); ?>">
        <a href="#" class="fpf-button fpf-repeater-field-add-item-btn<?php esc_attr_e($btn_classes); ?>"><i class="icon dashicons dashicons-plus-alt"></i><?php echo esc_html(fpframework()->_($this->data->get('btn_label'))); ?></a>
    </div>
</div>