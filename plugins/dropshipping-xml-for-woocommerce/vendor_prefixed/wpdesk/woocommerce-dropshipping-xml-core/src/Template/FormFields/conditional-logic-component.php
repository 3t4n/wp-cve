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
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent;
$allowed_atributes = ['id' => [], 'type' => [], 'class' => [], 'data-value' => [], 'placeholder' => [], 'name' => [], 'value' => [], 'selected' => [], 'disabled' => [], 'required' => []];
$items_nr = \is_array($value) && \is_array(\reset($value)) ? \count(\reset($value)) : 1;
?>

<div id="dropshipping-logical-conditions" class="flex-container odd">

<?php 
for ($i = 0; $i < $items_nr; $i++) {
    ?>
<div class="wrap-condition single-condition flex-row">
	<?php 
    $items = $field->get_items();
    $single_value_columns = [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_IMPORT, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_XPATH, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\ConditionalLogicComponent::FIELD_VALUE_TYPE];
    if (\is_array($items)) {
        echo '<div class="flex-col width-100"><div class="flex-container"><div class="flex-row stretch flex-fields">';
        $str = $options = '';
        // phpcs:ignore
        foreach ($items as $item) {
            $val = isset($value[$item->get_name()][$i]) ? \strval($value[$item->get_name()][$i]) : '';
            $item->set_attribute('id', $name_prefix . '_' . $field->get_id() . '_' . $item->get_id() . '_' . $i);
            $template_name = $item->get_template_name() === 'input-text' ? 'input' : $item->get_template_name();
            if (\in_array($item->get_name(), $single_value_columns)) {
                // phpcs:ignore
                if ($item->has_label()) {
                    $str .= '<div class="flex-col"><b class="nowrap">' . \esc_html($item->get_label()) . '</b></div>';
                }
                $rendered = $renderer->render($template_name, ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix . '[' . $field->get_name() . ']', 'value' => $val, 'multiple' => \true]);
                $str .= '<div class="flex-col">' . $rendered . '</div>';
            } else {
                $attributes = $item->get_attributes();
                $data_value = isset($attributes['data-value']) ? 'data-value="' . $attributes['data-value'] . '"' : '';
                $rendered = $renderer->render($template_name, ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix . '[' . $field->get_name() . ']', 'value' => $val, 'multiple' => \true]);
                $options .= '<div class="field-wrapper hidden" ' . $data_value . '>' . $rendered . '</div>';
            }
        }
        if (!empty($options)) {
            $str .= '<div class="flex-col">' . $options . '</div>';
        }
        echo \wp_kses($str, ['div' => $allowed_atributes, 'input' => $allowed_atributes, 'select' => $allowed_atributes, 'option' => $allowed_atributes, 'b' => $allowed_atributes, 'span' => $allowed_atributes]);
        ?>
		
	</div></div></div>
		<?php 
        $str_class = $i === 0 ? 'hidden' : '';
        ?>
		<div class="flex-col" style="width:40px;"><a href="#" class="remove-condition <?php 
        echo \esc_attr($str_class);
        ?>"><span class="dashicons dashicons-trash"></span></a></div>
		<div class="flex-col" style="width:40px;"><a href="#" class="add-condition"><span class="dashicons dashicons-plus-alt"></span></a></div>
		<?php 
    }
    ?>
</div>
	<?php 
}
?>
</div>
<?php 
