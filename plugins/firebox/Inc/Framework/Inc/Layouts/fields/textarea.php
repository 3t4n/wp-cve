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
$mode = $this->data->get('mode', null);
$modeAtt = $mode ? ' data-mode="' . esc_attr($mode) . '"' : '';

$rows = (!empty($this->data->get('rows', ''))) ? ' rows="' . esc_attr($this->data->get('rows', '')) . '"' : '';
?>
<textarea
	name="<?php esc_attr_e($this->data->get('name')); ?>"
	<?php echo wp_kses_data($modeAtt . $this->data->get('required_attribute', '') . $rows . $this->data->get('extra_atts', '')); ?>
	id="fpf-control-input-item_<?php esc_attr_e($this->data->get('name')); ?>"
	class="fpf-field-item fpf-control-input-item textarea<?php esc_attr_e($this->data->get('input_class')); ?>"
	placeholder="<?php esc_attr_e($this->data->get('placeholder', '')); ?>"><?php echo esc_textarea($this->data->get('value', '')); ?></textarea>