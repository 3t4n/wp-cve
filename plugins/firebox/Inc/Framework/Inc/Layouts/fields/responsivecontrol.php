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
$html = $this->data->get('html');
if (empty($html))
{
	return;
}
?>
<div class="fpf-responsive-control-item">
	<?php echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<input type="hidden" class="fpf-responsive-control-item-device" value="<?php echo esc_attr($this->data->get('value.type', 'desktop')); ?>" name="<?php echo esc_attr($this->data->get('name')); ?>[type]" />
</div>