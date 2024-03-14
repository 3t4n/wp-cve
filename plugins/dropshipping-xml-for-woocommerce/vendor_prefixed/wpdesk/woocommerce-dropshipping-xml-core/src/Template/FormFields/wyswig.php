<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var string $name_prefix
 * @var string $value
 */
\wp_print_styles('media-views');
?>

<?php 
$editor_settings = ['textarea_name' => \esc_attr($name_prefix) . '[' . \esc_attr($field->get_name()) . ']'];
$editor_settings = \array_merge($editor_settings, $field->get_attributes());
\wp_editor(\wp_kses_post($value), $field->get_name(), $editor_settings);
