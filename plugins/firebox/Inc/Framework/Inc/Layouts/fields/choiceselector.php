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
if (!$choices = $this->data->get('choices', []))
{
    return;
}
$allowed_tags = \FPFramework\Helpers\WPHelper::getAllowedHTMLTags();
$mode = $this->data->get('mode', 'text');
$item_id = $this->data->get('id', '');
?>
<div class="grid-x grid-margin-x grid-margin-y fpf-choice-selector-field<?php esc_attr_e($this->data->get('input_class')); ?>">
    <?php
    $i = 0;
    foreach ($choices as $key => $_value)
    {
        $_value = !is_string($_value) ? (array) $_value : $_value;
        
        $id = $this->data->get('name') . '_' . (empty($item_id) ? $key : $item_id);
        
        $image = isset($_value['image']) ? $_value['image'] : false;
        $icon = isset($_value['icon']) ? $_value['icon'] : false;
        $label = isset($_value['label']) ? $_value['label'] : $_value;
        $pro = isset($_value['pro']) ? (bool) $_value['pro'] : false;
        ?>
        <div class="cell<?php esc_attr_e($this->data->get('choice_item_class')); ?> choice <?php echo $mode . ($pro ? ' pro fpf-modal-opener' : ''); ?>"<?php echo $pro ? ' data-fpf-modal="#fpfUpgradeToPro" data-fpf-modal-item="' . esc_attr(fpframework()->_($label)) . '" data-fpf-plugin="' . esc_attr($this->data->get('plugin')) . '"' : ''; ?>>
            <div class="inner">
                <?php echo $mode == 'image' && !empty($image) ? '<img src="' . esc_url($image) . '" alt="' . esc_attr($image) . '" />' : ''; ?>
                <?php echo $pro ? '<span class="pro fpf-badge small text-uppercase">' . esc_html(fpframework()->_('FPF_PRO')) . '</span>' : ''; ?>

                <?php echo $mode == 'image' ? ' <div class="bottom">' : ''; ?>
                
                <input type="radio"<?php echo $this->data->get('required_attribute'); ?> id="fpf-control-input-item_<?php esc_attr_e($id); ?>" name="<?php esc_attr_e($this->data->get('name')); ?>" value="<?php esc_attr_e($key); ?>"<?php echo ($this->data->get('value') == $key) ? ' checked="checked"' : ''; ?> />
                <label for="fpf-control-input-item_<?php esc_attr_e($id); ?>">
                    <?php echo $mode == 'icon' && !empty($icon) ? '<i class="' . esc_attr($icon) . '"></i>' : ''; ?>
                    <?php echo $mode == 'svg' && !empty($icon) ? wp_kses($icon, $allowed_tags) : ''; ?>
                    <span class="text"><?php esc_html_e(fpframework()->_($label)); ?></span>
                </label>

                <?php echo $mode == 'image' ? ' </div>' : ''; ?>
            </div>
        </div>
        <?php
        $i++;
    }
    ?>
</div>