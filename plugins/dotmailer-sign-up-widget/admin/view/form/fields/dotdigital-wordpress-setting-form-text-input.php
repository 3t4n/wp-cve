<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * @package    Dotdigital_WordPress
 *
 * @var Dotdigital_WordPress_Setting_Form_Input_Interface $form_field
 */
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Input_Interface;
?>
<input
	class="<?php 
echo esc_html(apply_filters("{$form_field->get_page()}/{$form_field->get_name()}/css_classes", 'dotdigital-input'));
?>"
	id='<?php 
echo esc_attr($form_field->get_name());
?>'
	name='<?php 
echo esc_html($form_field->get_name());
?>'
	type='<?php 
echo esc_attr($form_field->get_type());
?>'
	value='<?php 
echo esc_attr($form_field->get_value());
?>'
	<?php 
echo esc_html(apply_filters("{$form_field->get_page()}/{$form_field->get_name()}/attributes", ''));
?>
	<?php 
echo $form_field->is_disabled() ? 'disabled' : '';
?>
/>
<?php 
