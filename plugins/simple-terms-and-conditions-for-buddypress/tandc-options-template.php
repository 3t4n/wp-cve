<div class="wrap">
	<h2><?php _e('BuddyPress Simple Terms And Conditions option page', 'bp-simple-terms-and-conditions');?></h2>
	<form method="post" action="options.php"> 
		<?php settings_fields( 'tandc' ); ?>
		<?php do_settings_sections( 'tandc' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="tandc_headline"><?php _e('Headline (displayed on register page):', 'bp-simple-terms-and-conditions');?></label></th>
				<td><input type="text" id="tandc_headline" name="tandc_headline" value="<?php echo esc_attr( get_option('tandc_headline') ); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="tandc_description"><?php _e('Description (displayed on register page):', 'bp-simple-terms-and-conditions');?></label></th>
				<td><textarea cols="50" rows="6" name="tandc_description" ><?php echo esc_attr( get_option('tandc_description') ); ?></textarea></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="tandc_checkboxtext"><?php _e('Checkbox-Label (displayed on register page):', 'bp-simple-terms-and-conditions');?></label></th>
				<td><textarea cols="50" rows="3" name="tandc_checkboxtext"><?php echo esc_attr( get_option('tandc_checkboxtext') ); ?></textarea></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="tandc_error"><?php _e('Error message (displayed if the user didn\'t agree):', 'bp-simple-terms-and-conditions');?></label></th>
				<td><textarea cols="50" rows="3" name="tandc_error"><?php echo esc_attr( get_option('tandc_error') ); ?></textarea></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="tandc_style"><?php _e('CSS-Style for the displayed container:', 'bp-simple-terms-and-conditions');?></label></th>
				<td><textarea cols="50" rows="3" name="tandc_style"><?php echo esc_attr( get_option('tandc_style') ); ?></textarea></td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
</div>