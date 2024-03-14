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
<input
	type="submit"
	class="fpf-button <?php echo esc_attr($this->data->get('input_class')); ?>"
	value="<?php echo esc_attr(fpframework()->_($this->data->get('value'))); ?>"
	name="<?php echo esc_attr($this->data->get('name')); ?>" />