<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var string              $class
 */
?>

<?php 
if ($field->has_description()) {
    ?>

<span
	<?php 
    if (isset($class)) {
        ?>
		class="<?php 
        echo \esc_attr($class);
        ?>"
	<?php 
    }
    ?>
><?php 
    echo \wp_kses_post($field->get_description());
    ?></span>

<?php 
}
