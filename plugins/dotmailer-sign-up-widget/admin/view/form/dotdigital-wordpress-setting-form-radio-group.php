<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * @package    Dotdigital_WordPress
 *
 * @var Dotdigital_WordPress_Setting_Form_Radio_Group $form
 */
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form_Radio_Group;
?>

<h2><?php 
echo esc_html($form->get_title());
?></h2>
<form method="<?php 
echo esc_attr($form->get_method());
?>" action="<?php 
echo esc_attr($form->get_action());
?>">
	<?php 
if (settings_fields($form->get_page())) {
    esc_html(settings_fields($form->get_page()));
} else {
    echo '';
}
?>
	<?php 
if ($form->get_value()) {
    $selected_option = $form->get_value();
} else {
    $selected_option = array('noRedirection' => 1);
}
?>
	<?php 
foreach ($form->get_inputs() as $row_key => $input) {
    ?>
		<table class="form-table">
			<tr id="<?php 
    echo esc_attr($row_key);
    ?>" class="radio-selection-group">
				<th scope="row">
					<input
						class="form-group-radio"
						type="radio"
						id="<?php 
    echo esc_attr(\str_replace(' ', '', $form->get_name() . '_' . $row_key));
    ?>"
						name="<?php 
    echo esc_attr($form->get_name());
    ?>"
						value="<?php 
    echo esc_attr(\array_key_first($selected_option) == $row_key ? '1' : '0');
    ?>"
						<?php 
    echo esc_attr(\array_key_first($selected_option) == $row_key ? 'checked' : '');
    ?>
					>
					<label class="form-group-radio-label" for="<?php 
    echo esc_attr(\str_replace(' ', '', $form->get_name() . '_' . $row_key));
    ?>"><?php 
    echo esc_html($input->get_label());
    ?></label>
				</th>
				<td data-group="<?php 
    echo esc_attr($row_key);
    ?>" class="list-column">
					<table>
						<tr class="radio-inputs">
							<td><?php 
    esc_html($input->render());
    ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?php 
}
?>

	<?php 
submit_button();
?>

</form>
<?php 
