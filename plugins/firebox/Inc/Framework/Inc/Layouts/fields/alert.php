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
$allowed_tags = [
	'a' => [ 'href' => true, 'target' => true ],
	'i' => [ 'class' => true ],
	'strong' => true,
	'b' => true
];
$icon = $this->data->get('icon', '');
$right_action = $this->data->get('right_action', '');
$showon = (!empty($this->data->get('showon'))) ? ' data-showon="' . esc_attr($this->data->get('showon')) . '"' : '';
?>
<div class="fpf-alert callout<?php echo esc_attr($this->data->get('input_class')); ?>"<?php echo wp_kses_data($showon); ?>>
	<?php if (!empty($icon)): ?>
	<i class="dashicons <?php echo esc_attr($icon); ?>"></i>
	<?php endif; ?>
	<p><?php echo wp_kses(fpframework()->_($this->data->get('text')), $allowed_tags); ?></p>
	<?php if (!empty($right_action)): ?>
	<div class="actions"><?php echo wp_kses($right_action, $allowed_tags); ?></div>
	<?php endif; ?>
</div>