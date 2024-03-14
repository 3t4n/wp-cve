<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var string              $class
 * @var array               $attributes
 */
$label = isset($label) ? $label : ($field->has_label() ? $field->get_label() : '');
?>

<label 
	for="<?php 
echo \esc_attr($field->get_id());
?>"
		<?php 
if (isset($class)) {
    ?>
			class="<?php 
    echo \esc_attr($class);
    ?>"
		<?php 
}
?>

	<?php 
if (isset($attributes) && \is_array($attributes)) {
    ?>
		<?php 
    foreach ($attributes as $key => $atr_val) {
        echo \esc_attr($key) . '="' . \esc_attr($atr_val) . '"';
        ?>
			<?php 
    }
    ?>
	<?php 
}
?>

> <?php 
echo \esc_html($label);
?></label>
<?php 
