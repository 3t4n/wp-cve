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
$position = $this->data->get('position', 'start');
if ($position == 'start')
{
	$showon = (!empty($this->data->get('showon', ''))) ? ' data-showon="' . esc_attr($this->data->get('showon', '')) . '"' : '';
	$style = $this->data->get('style', '');
	$class = $this->data->get('class', []);
	?><div class="<?php echo esc_attr(implode(' ', $class)); ?>"<?php echo wp_kses_data($showon); ?><?php echo $style ? ' style="' . esc_attr($style) . '"' : ''; ?>>
	<?php
	return;
}
?>
</div>