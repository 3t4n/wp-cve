<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * @package    Dotdigital_WordPress
 *
 * @var Dotdigital_WordPress_Setting_Form_Checkbox_Input $form_field
 */
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Checkbox_Input;
?>
<input
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
	value='<?php 
echo esc_attr($form_field->get_value() ?? 'on');
?>'
	<?php 
echo esc_attr($form_field->get_is_checked() ? 'checked' : '');
?>
	<?php 
echo esc_attr(apply_filters("{$form_field->get_page()}/{$form_field->get_name()}/attributes", ''));
?>
	<?php 
echo esc_attr($form_field->is_disabled() ? 'disabled' : '');
?>
/>
<?php 
