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
$device = $this->data->get('device');
?>
<div class="fpf-responsive-control-device-item<?php echo ($this->data->get('selected_device') == $device) ? ' is-active' : ''; ?>" data-device="<?php echo esc_attr($device); ?>">
	<?php echo $this->data->get('html'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>