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
?>
<input type="file"<?php echo wp_kses_data($this->data->get('required_attribute', '') . $this->data->get('extra_atts')); ?> id="fpf-control-input-item_<?php esc_attr_e($this->data->get('name')); ?>" class="fpf-field-item fpf-control-input-item file<?php esc_attr_e($this->data->get('input_class')); ?>" name="<?php esc_attr_e($this->data->get('name')); ?>" />