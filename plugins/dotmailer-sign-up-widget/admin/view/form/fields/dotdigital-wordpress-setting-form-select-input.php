<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * @package    Dotdigital_WordPress
 *
 * @var Dotdigital_WordPress_Setting_Form_Select_Input $form_field
 */
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Select_Input;
?>
<select
	class="<?php 
echo esc_attr(apply_filters("{$form_field->get_page()}/{$form_field->get_name()}/css_classes", 'dotdigital-input'));
?>"
	id='<?php 
echo esc_attr($form_field->get_name());
?>'
	name='<?php 
echo esc_attr($form_field->get_name());
?>'
	type='<?php 
echo esc_attr($form_field->get_type());
?>'
	<?php 
echo esc_html(apply_filters("{$form_field->get_page()}/{$form_field->get_name()}/attributes", ''));
?>
	<optgroup label="<?php 
echo esc_html($form_field->get_page());
?>">
		<?php 
foreach ($form_field->get_options() as $value => $label) {
    ?>
			<option value="<?php 
    echo esc_attr($value);
    ?>"<?php 
    selected($value, $form_field->get_value());
    ?>>
				<?php 
    echo esc_html($label);
    ?>
			</option>
		<?php 
}
?>
	</optgroup>
</select>
<?php 
