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
$default_name = $this->data->get('name');
$data_name = !empty($default_name) ? ' ' . ($this->data->get('empty_value') ? '' : 'data-') . 'name="' . esc_attr($default_name) . '"' : '';
$name = !empty($default_name) ? ' name="' . esc_attr($default_name) . '"' : '';
?>
<div class="fpf-control-group-field fptoggle">
    <input type="hidden" data-name="<?php esc_attr_e($default_name); ?>"<?php echo wp_kses_data($data_name); ?> value="0" />
    <input type="checkbox"<?php echo $this->data->get('checked') ? ' checked="checked"' : ''; ?> value="1" class="fpf-control-input-item toggle-default<?php esc_attr_e($this->data->get('input_class')); ?>"<?php echo wp_kses_data($this->data->get('extra_atts', '') . $name); ?> id="toggle-default-<?php esc_attr_e($this->data->get('name')); ?>" />
    <label for="toggle-default-<?php esc_attr_e($this->data->get('name')); ?>" class="fpf-fptoggle-btn toggle-default-btn"><span class="inner"></span></label>
</div>