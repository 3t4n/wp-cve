<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * @package    Dotdigital_WordPress
 *
 * @var Dotdigital_WordPress_Setting_Form $form
 */
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form;
?>
<form method="<?php 
echo esc_attr($form->get_method());
?>" action="<?php 
echo esc_attr($form->get_action());
?>">

	<?php 
settings_fields($form->get_page());
do_settings_sections($form->get_page());
?>

	<?php 
submit_button();
?>

</form>
<?php 
