<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}
?>

<h3><?php echo esc_attr( $label['costumer'] ); ?></h3>
<table class="form-table">
<tr>
	<th><label for="bod"><?php echo esc_attr( $label['bod'] ); ?></label></th>
	<td>
		<input type="text" name="billing_bod" id="billing_bod" class="regular-text" value="<?php echo esc_attr( $bod ); ?>" />
		<br />
		<span class="description">DD-MM-YYYY</span>
	</td>
</tr>
<tr>
	<th><label for="gender"><?php echo esc_attr( $label['gender'] ); ?></label></th>
	<td>
		<select id="billing_gender" name="billing_gender" title="Gender" style="width: 25em;">
			<option value="Male" 
			<?php
			if ( 'Male' === $gender ) {
				echo 'selected="selected"'; }
?>
><?php echo esc_attr( $label['gender_male'] ); ?></option>
			<option value="Female" 
			<?php
			if ( 'Female' === $gender ) {
				echo 'selected="selected"'; }
?>
><?php echo esc_attr( $label['gender_female'] ); ?></option>
		</select>
	</td>
</tr>
</table>
