<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var \WPDesk\View\Renderer\Renderer $renderer
 * @var string $name_prefix
 * @var array $value
 *
 * @var string $template_name Real field template.
 */
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent;
function dropshipping_price_groups_generate($group_id, $pricing, $conditions)
{
    ?>
		<div class="price-mod-group flex-container group-switch group-closed odd">
			<div class="price-mod-group-name flex-row group-switcher">
				<div class="flex-col justify-left">
					<span class="group-title"><?php 
    echo \sprintf(\__('Group <span class="group-counter">%s</span>', 'dropshipping-xml-for-woocommerce'), $group_id);
    ?></span>
					<span class="group-arrow dashicons dashicons-arrow-down"></span>
				</div>
				<div class="flex-col justify-right">
					<a href="#" class="remove-group"><?php 
    \_e('Delete group', 'dropshipping-xml-for-woocommerce');
    ?></a>
				</div>					
			</div>	
			<div class="price-mod-group-pricing flex-row">
				<div class="flex-col">
					<?php 
    echo $pricing;
    ?>
				</div>				
			</div>
			<div class="price-mod-group-conditions flex-row">
				<div class="flex-col">
					<?php 
    echo $conditions;
    ?>
				</div>				
			</div>
		</div>
<?php 
}
?>

<?php 
function dropshipping_price_modificator_generate($value, $group_id, $name_prefix, $field, $renderer)
{
    ?>

	<div id="price-mod-setting" class="flex-container">
	<div class="flex-row">
		
		<?php 
    $price_items = $field->get_price_item_modificator_fields();
    foreach ($price_items as $item) {
        ?>
				<div class="flex-col">
				<?php 
        $val = isset($value[$item->get_name()]) ? \strval($value[$item->get_name()]) : '';
        echo $renderer->render($item->get_template_name(), ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix . '[' . $field->get_name() . ']' . '[' . $group_id . ']', 'value' => $val, 'multiple' => \false]);
        ?>
				</div>
				<?php 
    }
    ?>
			<div class="flex-col">
				<b><?php 
    \_e('when', 'dropshipping-xml-for-woocommerce');
    ?></b>
			</div>
		</div>
	</div>

<?php 
}
?>	

<?php 
function dropshipping_price_logic_generate($value, $group_id, $name_prefix, $field, $renderer)
{
    ?>
	<?php 
    $allowed_atributes = ['id' => [], 'type' => [], 'class' => [], 'data-value' => [], 'placeholder' => [], 'name' => [], 'value' => [], 'selected' => [], 'disabled' => [], 'required' => []];
    ?>

<div class="price-mod-conditions flex-container odd">
	<?php 
    $items_nr = \is_array($value) && isset($value['xpath']) && \is_array($value['xpath']) ? \count($value['xpath']) : 1;
    for ($i = 0; $i < $items_nr; $i++) {
        ?>
<div class="wrap-condition single-condition flex-row">
		<?php 
        $items = $field->get_items();
        $single_value_columns = [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_XPATH, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\PriceModificatorComponent::FIELD_VALUE_TYPE];
        if (\is_array($items)) {
            echo '<div class="flex-col width-100"><div class="flex-container"><div class="flex-row stretch flex-fields">';
            $str = $options = '';
            // phpcs:ignore
            foreach ($items as $item) {
                $val = isset($value[$item->get_name()][$i]) ? \strval($value[$item->get_name()][$i]) : '';
                //$item->set_attribute( 'id', $name_prefix . '_' . $field->get_id() . '_' . $item->get_id() . '_' . $group_id . '_' . $i );
                $template_name = $item->get_template_name() === 'input-text' ? 'input' : $item->get_template_name();
                if (\in_array($item->get_name(), $single_value_columns)) {
                    // phpcs:ignore
                    if ($item->has_label()) {
                        $str .= '<div class="flex-col"><b class="nowrap">' . \esc_html($item->get_label()) . '</b></div>';
                    }
                    $rendered = $renderer->render($template_name, ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix . '[' . $field->get_name() . ']' . '[' . $group_id . ']', 'value' => $val, 'multiple' => \true]);
                    $str .= '<div class="flex-col">' . $rendered . '</div>';
                } else {
                    $attributes = $item->get_attributes();
                    $data_value = isset($attributes['data-value']) ? 'data-value="' . $attributes['data-value'] . '"' : '';
                    $rendered = $renderer->render($template_name, ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix . '[' . $field->get_name() . ']' . '[' . $group_id . ']', 'value' => $val, 'multiple' => \true]);
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
}
?>


<div id="price-mod-groups-container">
	<div id="price-mod-groups">
	<?php 
$value = \is_array($value) ? $value : array();
foreach ($value as $key => $val) {
    \ob_start();
    \DropshippingXmlFreeVendor\dropshipping_price_modificator_generate($val, $key, $name_prefix, $field, $renderer);
    $pricing = \ob_get_contents();
    \ob_end_clean();
    \ob_start();
    \DropshippingXmlFreeVendor\dropshipping_price_logic_generate($val, $key, $name_prefix, $field, $renderer);
    $conditions = \ob_get_contents();
    \ob_end_clean();
    \DropshippingXmlFreeVendor\dropshipping_price_groups_generate($key, $pricing, $conditions);
}
?>
	</div>
	<div class="price-mod-groups-add-button">
		<div class="flex-container">
				<div class="flex-row">
					<div class="flex-col justify-left">
						<a href="#" id="add-price-mod-group" class="add-group"><?php 
\_e('Add new group', 'dropshipping-xml-for-woocommerce');
?></a>
					</div>
				</div>
		</div>	
	</div>
</div>

<script id="groups-template" type="text/x-custom-template">
	<div>
<?php 
$i = 0;
$val = array();
\ob_start();
\DropshippingXmlFreeVendor\dropshipping_price_modificator_generate($val, $i, $name_prefix, $field, $renderer);
$pricing = \ob_get_contents();
\ob_end_clean();
\ob_start();
\DropshippingXmlFreeVendor\dropshipping_price_logic_generate($val, $i, $name_prefix, $field, $renderer);
$conditions = \ob_get_contents();
\ob_end_clean();
\DropshippingXmlFreeVendor\dropshipping_price_groups_generate($i, $pricing, $conditions);
?>
	</div>
</script>

<script id="condition-template" type="text/x-custom-template">
<?php 
$i = 0;
$val = array();
\ob_start();
\DropshippingXmlFreeVendor\dropshipping_price_logic_generate($val, $i, $name_prefix, $field, $renderer);
$conditions = \ob_get_contents();
\ob_end_clean();
echo $conditions;
?>
</script>

<?php 
