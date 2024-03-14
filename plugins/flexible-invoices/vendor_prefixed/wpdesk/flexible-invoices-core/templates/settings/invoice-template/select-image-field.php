<?php

namespace WPDeskFIVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var string              $name_prefix
 * @var mixed               $value
 */
$disabled_label = $field->get_attribute('is_disabled') === 'yes' ? 'disabled ' : '';
$disabled_class = $field->get_attribute('is_disabled') === 'yes' ? 'disabled-checked-image' : '';
?>
<div class="select-images <?php 
echo $disabled_class;
?>">
	<?php 
foreach ($field->get_possible_values() as $possible_value => $image) {
    ?>
		<label class="<?php 
    echo $disabled_label;
    if ($possible_value === $value || !$value && $possible_value === $field->get_default_value()) {
        ?>checked-image<?php 
    }
    ?>">
			<?php 
    if (isset($image['large_src'])) {
        ?>
				<a class="zoom" href="#"><span class="dashicons dashicons-search"></span></a>
				<div class="large-view" style="display: none;">
					<span class="close"><span class="dashicons dashicons-no-alt"></span></span>
					<img src="<?php 
        echo $image['large_src'];
        ?>" alt="">
				</div>
			<?php 
    }
    ?>
			<input name="<?php 
    echo \esc_attr($name_prefix);
    ?>[<?php 
    echo \esc_attr($field->get_name());
    ?>]<?php 
    echo $field->is_multiple() ? '[]' : '';
    ?>"
				   type="radio"
				   id="<?php 
    echo \esc_attr($field->get_id());
    ?>"
				   <?php 
    if ($possible_value === $value || !$value && $possible_value === $field->get_default_value()) {
        ?>checked="checked"<?php 
    }
    ?>
				   value="<?php 
    echo \esc_attr($possible_value);
    ?>"/>
			<img src="<?php 
    echo $image['thumb_src'];
    ?>" alt="">
			<span><?php 
    echo $image['name'];
    ?></span>
		</label>
	<?php 
}
?>
</div>
<?php 
