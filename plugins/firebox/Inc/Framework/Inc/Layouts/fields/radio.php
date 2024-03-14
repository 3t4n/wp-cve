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
$item_id = $this->data->get('id', '');
?>
<div class="fpf-choice-field<?php echo esc_attr($this->data->get('input_class')); ?>">
    <div class="grid-x grid-margin-x grid-margin-y">
        <?php
        $i = 0;
        foreach ($choices as $key => $_value)
        {
            $id = $this->data->get('name') . '_' . (empty($item_id) ? $key : $item_id);
            ?>
            <div class="cell medium-<?php echo esc_attr($this->data->get('cols', 12)); ?> choice-item">
                <input type="radio"<?php echo wp_kses_data($this->data->get('required_attribute', '')); ?> class="fpf-control-input-item" id="fpf-control-input-item_<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($this->data->get('name')); ?>" value="<?php echo esc_attr($key); ?>"<?php echo ($this->data->get('value') == $key) ? ' checked="checked"' : ''; ?> />
                <label for="fpf-control-input-item_<?php echo esc_attr($id); ?>"><span class="action"><span class="inner"></span></span><?php echo esc_html(fpframework()->_($_value)); ?></label>
            </div>
            <?php
            $i++;
        }
        ?>
    </div>
</div>