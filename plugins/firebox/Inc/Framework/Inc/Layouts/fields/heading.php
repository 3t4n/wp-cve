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

if (empty($this->data->get('title')))
{
	return;
}

$render_group = (bool) $this->data->get('render_group');
$description = $this->data->get('description', '');
$type = $this->data->get('heading_type');

$allowed_tags = [
	'a' => [ 'href' => true, 'target' => true ],
	'i' => [ 'class' => true ],
	'br' => true,
	'strong' => true
];
?>
<<?php echo esc_attr($type); ?> class="fpf-field-item heading<?php echo esc_attr($this->data->get('input_class')); ?>">
	<span class="heading_item"><?php echo esc_html(fpframework()->_($this->data->get('title'))); ?></span>
	<?php if ($description && !$render_group) { ?>
		<span class="fpf-heading-field-item-description"><?php echo wp_kses($description, $allowed_tags); ?></span>
	<?php } ?>
</<?php echo esc_attr($type); ?>>