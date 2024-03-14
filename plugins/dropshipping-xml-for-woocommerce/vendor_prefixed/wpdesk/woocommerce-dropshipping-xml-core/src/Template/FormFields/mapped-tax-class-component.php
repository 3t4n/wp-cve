<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var \WPDesk\View\Renderer\Renderer $renderer
 * @var string $name_prefix
 * @var string $value
 *
 * @var string $template_name Real field template.
 */
?>

<?php 
$items_nr = \is_array($value) && \is_array(\reset($value)) ? \count(\reset($value)) : 1;
for ($i = 0; $i < $items_nr; $i++) {
    ?>
<span class="wrap wrap-tax-class single-item">
	<?php 
    $items = $field->get_items();
    if (\is_array($items)) {
        foreach ($items as $item) {
            $val = isset($value[$item->get_name()][$i]) ? \strval($value[$item->get_name()][$i]) : '';
            $item->set_attribute('id', $name_prefix . '_' . $field->get_id() . '_' . $item->get_id() . '_' . $i);
            if ($item->get_type() === 'select') {
                $renderer->output_render('select', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix . '[' . $field->get_name() . ']', 'value' => $val, 'multiple' => $field->is_multiple()]);
            } else {
                $renderer->output_render('input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix . '[' . $field->get_name() . ']', 'value' => $val, 'multiple' => $field->is_multiple()]);
            }
        }
    }
    ?>
	<a href="#" class="remove-button <?php 
    echo \esc_attr($i === 0 ? 'hidden' : '');
    ?>"><span class="dashicons dashicons-trash"></span></a>
</span>
	<?php 
}
