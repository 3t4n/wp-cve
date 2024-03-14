<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wpsbc-settings-field-wrapper wpsbc-settings-field-inline">

	<label class="wpsbc-settings-field-label">
		<strong><?php echo __( 'Languages', 'wp-simple-booking-calendar' ); ?></strong>
		<br /><br />
		<?php echo __( 'What languages do you wish to use?', 'wp-simple-booking-calendar' ); ?>
	</label>

	<div class="wpsbc-settings-field-inner">
		
		<?php

			$languages = wpsbc_get_languages();

			foreach( $languages as $code => $name ) {

				echo '<div>';
					echo '<label>';
						echo '<input type="checkbox" name="wpsbc_settings[active_languages][]" value="' . esc_attr( $code ) . '" ' . ( ! empty( $settings['active_languages'] ) && in_array( $code, $settings['active_languages'] ) ? 'checked' : '' ) . ' />';
						echo '<img src="' . WPSBC_PLUGIN_DIR_URL . 'assets/img/flags/' . esc_attr( $code ) . '.png" />';
						echo esc_html( $name );
					echo '</label>';
				echo '</div>';

			}

		?>

	</div>
	
</div>

<!-- Submit button -->
<input type="submit" class="button-primary" value="<?php echo __( 'Save Settings', 'wp-simple-booking-calendar' ); ?>" />