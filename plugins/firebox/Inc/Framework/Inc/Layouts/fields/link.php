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
$class = !empty($this->data->get('class')) ? ' ' . implode(' ', $this->data->get('class')) : '';
$icon = $this->data->get('icon', '');
$target = $this->data->get('target', '');
$targetAtt = !empty($target) ? ' target="' . esc_attr($target) . '"' : '';
?>
<div class="fpf-link-wrapper<?php echo esc_attr($class); ?>">
	<a href="<?php echo esc_attr($this->data->get('href', '')); ?>" class="<?php echo esc_attr($this->data->get('input_class')); ?>"<?php echo wp_kses_data($targetAtt . $this->data->get('extra_atts')); ?>>
		<?php if (!empty($icon)): ?>
		<i class="icon dashicons <?php echo esc_attr($icon); ?>"></i>
		<?php endif; ?>
		<?php echo esc_html(fpframework()->_($this->data->get('title'))); ?>
	</a>
</div>