<?php

namespace DropshippingXmlFreeVendor;

use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent;
/**
 * @var VariationComponent $field
 * @var \WPDesk\View\Renderer\Renderer $renderer
 * @var string $name_prefix
 * @var array $value
 *
 * @var string $template_name Real field template.
 */
$name_prefix = $name_prefix . '[variation_embedded]';
$get_variation_item = function (string $field_name) use($field) {
    foreach ($field->get_items() as $item) {
        if ($field_name === $item->get_id() || $field_name === $item->get_name()) {
            return $item;
        }
    }
    throw new \RuntimeException('Form field: ' . $field_name . ' not found.');
};
$get_variation_value = function (string $field_name) use($value) {
    if (\is_array($value) && isset($value[$field_name])) {
        return $value[$field_name];
    }
    return null;
};
?>

<div class="flex-container">
	<div class="flex-row stretch">
		<div class="flex-col variation-fields">
				<?php 
$item = $get_variation_item('variation_xpath');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div id="variation-hidden-fields" class="hidden">
	<div class="flex-row stretch">
		<div class="flex-col variation-fields">
			<p><a id="open-variation-window" href="#open"><?php 
echo \esc_html(\__('open window with variations', 'dropshipping-xml-for-woocommerce'));
?></a></p>	
		</div>
	</div>
	<div class="flex-row stretch">
		<div class="flex-col variation-fields">
				<?php 
$item = $get_variation_item('variation_virtual');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-checkbox', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>	
	<div class="flex-row stretch">
		<div class="flex-col variation-fields">
				<?php 
$item = $get_variation_item('variation_create_as_simple');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-checkbox', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="flex-col variation-fields">
		<?php 
$item = $get_variation_item('variation_first_as_default');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-checkbox', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch">
		<div class="flex-col variation-fields">
		<?php 
$item = $get_variation_item('variation_parent_selector');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-checkbox', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch hidden" id="variation_parent_options_wrapper">
		<div class="flex-col variation-fields">
		<?php 
$item = $get_variation_item('variation_parent_options');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-select', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>	
	<div class="flex-row stretch">
		<div class="flex-col variation-fields">
				<?php 
$item = $get_variation_item('variation_images');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="flex-col variation-fields">
		<?php 
$item = $get_variation_item('variation_SKU');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch">
		<div class="flex-col variation-fields" data-variation-field="regular_price" style="width:50%">
		<?php 
$item = $get_variation_item('variation_price');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="flex-col variation-fields" style="width:50%;" data-variation-field="sale_price">
		<?php 
$item = $get_variation_item('variation_sale_price');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch">
		<div class="flex-col variation-fields" style="width:50%" data-variation-field="weight">
			<?php 
$item = $get_variation_item('variation_weight');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="flex-col" style="width:50%" data-variation-field="dimensions">
		<div class="variation-fields" style="width:33.33%; padding-right: 2px;">
		<?php 
$item = $get_variation_item('variation_product_length');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="variation-fields" style="width:33.33%; padding-right: 2px; padding-left: 2px;">
		<label>&nbsp;</label>
		<?php 
$item = $get_variation_item('variation_product_width');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="variation-fields" style="width:33.33%; padding-left: 2px;">	
		<label>&nbsp;</label>
		<?php 
$item = $get_variation_item('variation_product_height');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>	
		</div>
	</div>
	<div class="flex-row stretch can-wrap" data-variation-field="stock">
		<div class="flex-col variation-fields" style="width:100%">
		<?php 
$item = $get_variation_item('variation_manage_stock');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-checkbox', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="flex-col variation-fields" data-variation-stock="static" style="width:100%">
		<?php 
$item = $get_variation_item('variation_stock_status');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-select', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="flex-col variation-fields" data-variation-stock="dynamic" style="width:50%">
		<?php 
$item = $get_variation_item('variation_stock');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="flex-col variation-fields" data-variation-stock="dynamic" style="width:50%">
		<?php 
$item = $get_variation_item('variation_backorders');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-select', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch" data-variation-field="shipping">
		<div class="flex-col variation-fields" style="width:50%">
		<?php 
$item = $get_variation_item('variation_product_shipping_class');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-select', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	<div class="flex-col variation-fields" style="width:50%">
		<?php 
$item = $get_variation_item('variation_tax_status');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-select', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
	</div>
	</div>	

	<div class="flex-row stretch">
		<div class="flex-col variation-fields">
		<?php 
$item = $get_variation_item('variation_tax_class');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-select', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch">
		<div class="flex-col variation-fields">
		<?php 
$item = $get_variation_item('variation_tax_class_xpath_switcher');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-checkbox', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch hidden" data-variation-tax-class="xpath">
		<div class="flex-col variation-fields">
		<?php 
$item = $get_variation_item('variation_tax_class_mapper_field');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-input', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch hidden" data-variation-tax-class="xpath">
		<div class="flex-col variation-fields" id="variation-tax-class-wrapper">
		<?php 
$item = $get_variation_item('variation_tax_class_map');
$val = $get_variation_value($item->get_name());
?>
		<label><?php 
echo \esc_html($item->get_label());
?></label>
		<?php 
$renderer->output_render('mapped-tax-class-component', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch hidden" data-variation-tax-class="xpath">
		<div class="flex-col variation-fields" style="align-items: end; width:100%">
			<a href="#" id="add_variation_tax_class"><?php 
echo \esc_html(\__('Add +', 'dropshipping-xml-for-woocommerce'));
?></a>
		</div>
	</div>
	<div class="flex-row stretch">
		<div class="flex-col variation-fields">
		<?php 
$item = $get_variation_item('variation_description');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-textarea', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>	
	<div class="flex-row stretch">
		<div class="flex-col variation-fields" id="variation-attributes-wrapper">
		<?php 
$item = $get_variation_item('variation_attribute');
$val = $get_variation_value($item->get_name());
?>
		<label><?php 
echo \esc_html($item->get_label());
?></label>
		<?php 
$renderer->output_render('attributes-component', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
	</div>
	<div class="flex-row stretch">
		<div class="flex-col variation-fields" style="width:50%">
		<?php 
$item = $get_variation_item('attribute_as_taxonomy');
$val = $get_variation_value($item->get_name());
$renderer->output_render('variation-form-field-checkbox', ['field' => $item, 'renderer' => $renderer, 'name_prefix' => $name_prefix, 'value' => $val]);
?>
		</div>
		<div class="flex-col variation-fields" style="align-items: end; width:50%">
			<a href="#" id="add_variation_attribute"><?php 
echo \esc_html(\__('Add +', 'dropshipping-xml-for-woocommerce'));
?></a>
		</div>
	</div>		
	</div>
</div>

<div id="variation-popup">
	<div class="flex-container">
		<div class="flex-row stretch">
			<div class="flex-col close-button" >
				<a href="#" class="variation-popup-close">X</a>
			</div>
		</div>
		<div class="flex-row stretch">
			<div class="flex-col" >
				<fieldset style="width: 100%; text-align: center;">
					<a href="#" class="dashicons dashicons-arrow-left-alt2 to-left" id="dropshipping-variations-item-position-arrow-left"></a>
					<input type="number" class="input-text regular-input padding-xs" name="item_nr" id="dropshipping-variations-item-nr" value="1"> <span id="dropshipping-variations-item-position-separator"><?php 
echo \esc_html(\__('of', 'dropshipping-xml-for-woocommerce'));
?></span> <span id="dropshipping-variations-item-position">0</span>
					<a href="#" class="dashicons dashicons-arrow-right-alt2 to-right" id="dropshipping-variations-item-position-arrow-right"></a>
				</fieldset>
			</div>
		</div>
		<div class="flex-row stretch">
			<div class="flex-col variation-popup-content" id="variation-popup-content">
				
			</div>
		</div>
	</div>
	<div class="variation-loader" id="variation-loader">
		<img src="<?php 
echo \esc_url(\includes_url() . 'js/tinymce/skins/lightgray/img/loader.gif');
?>" />
	</div>
</div>


<?php 
