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
	'ul' => [
		'style' => true
	],
	'li' => true,
	'br' => true,
	'strong' => true
];
$type = $this->data->get('type');
$type = strtolower($type) === 'datepicker' ? 'type-' . $type : $type;
$description_class = $this->data->get('description_class', ['top']);
$description_class = $description_class && is_array($description_class) && !empty($description_class) ? ' ' . implode(' ', $description_class) : '';
$description = $this->data->get('description', '');
$units = $this->data->get('units', []);
$classes = $this->data->get('class');
$control_inner_class = $this->data->get('control_inner_class', []);
$showon = (!empty($this->data->get('showon'))) ? ' data-showon="' . esc_attr($this->data->get('showon')) . '"' : '';
// if we have only units to show and its not a Responsivecontrol field, make the fields appear in columns so we can have units appear next to the field's control
if ($this->data->get('type') != 'ResponsiveControl' && !empty($this->data->get('units')))
{
	$control_inner_class[] =  'flex-container';
}
?>
<div class="cell<?php echo $classes ? esc_attr(' ' . implode(' ', $classes)) : ''; ?>"<?php echo wp_kses_data($showon); ?>>
	<div class="fpf-field-control-group">

		<?php fpframework()->renderer->field->render('label', $this->data); ?>

		<div class="fpf-field-control <?php echo esc_attr(strtolower($type)); ?>">

			<?php if (!empty($description)): ?>
			<div class="fpf-field-control-description<?php echo esc_attr($description_class); ?>"><?php echo wp_kses($description, $allowed_tags); ?></div>
			<?php endif; ?>

			<?php if ($this->data->get('field_top') || $this->data->get('field_body')) { ?>
				<div class="fpf-field-control-inner<?php echo $control_inner_class && is_array($control_inner_class) && count($control_inner_class) ? ' ' . esc_attr(implode(' ', $control_inner_class)) : ''; ?>">
					<?php fpframework()->renderer->field->render('field_control', $this->data); ?>
				</div>
			<?php } ?>

		</div>
	</div>
</div>