<?php

declare (strict_types=1);
namespace {
    /**
     * @var Field_Interface $field
     */
    use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Checkbox_Interface;
    use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
    use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Number_Interface;
    use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Select_Interface;
    use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Text_Area_Interface;
    use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Text_Interface;
    ?>

<div class="options_group">
	<?php 
    $label = $field->get_label() ?: $field->get_id();
    switch ($field) {
        case $field instanceof Field_Text_Interface:
            \woocommerce_wp_text_input(['id' => $field->get_id(), 'name' => $field->get_label(), 'value' => $field->get_value(), 'class' => 'ilabs-metabox-field-text', 'label' => $field->get_label()]);
            break;
        case $field instanceof Field_Text_Area_Interface:
            \woocommerce_wp_textarea_input(['id' => $field->get_id(), 'name' => $field->get_label(), 'value' => $field->get_value(), 'class' => 'ilabs-metabox-field-textarea', 'label' => $field->get_label()]);
            break;
        case $field instanceof Field_Checkbox_Interface:
            \woocommerce_wp_checkbox(['id' => $field->get_id(), 'name' => $field->get_label(), 'value' => $field->get_value(), 'class' => 'ilabs-metabox-field-checkbox', 'label' => $field->get_label()]);
            break;
        case $field instanceof Field_Select_Interface:
            \woocommerce_wp_select(['id' => $field->get_id(), 'name' => $field->get_label(), 'value' => $field->get_value(), 'options' => $field->get_options(), 'class' => 'ilabs-metabox-field-select']);
            break;
        case $field instanceof Field_Number_Interface:
            \woocommerce_wp_text_input(['name' => $field->get_label(), 'value' => $field->get_value(), 'type' => 'number', 'class' => 'ilabs-metabox-field-number']);
            break;
    }
    ?>
</div>
<?php 
}
