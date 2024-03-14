<?php

if( isset( $_POST['wpsnow_update_settings'] ) && $_POST['wpsnow_update_settings'] === 'yes' ) {
	if( ! wp_verify_nonce( $_POST['ironikus_wpsnow_settings_nonce'], 'ironikus_wpsnow_settings' ) ) {
		return;
	}

	// START General Settings
	foreach( $this->settings as $settings_name => $setting ){

		$value = '';

		if( $setting['type'] == 'checkbox' ){
			if( ! isset( $_POST[ $settings_name ] ) ){
				$value = 'no';
			} else {
				$value = 'yes';
			}
		} elseif( $setting['type'] == 'text' ){
			if( isset( $_POST[ $settings_name ] ) ){
				$value = stripslashes( $_POST[ $settings_name ] );
			}
		} elseif( $setting['type'] == 'number' ){
			if( isset( $_POST[ $settings_name ] ) ){
				$value = ( intval( $_POST[ $settings_name ] ) == 0 ) ? '' : intval( $_POST[ $settings_name ] );
			}
		} elseif( $setting['type'] == 'select' ){
			if( isset( $_POST[ $settings_name ] ) ){
				$value = sanitize_title( $_POST[ $settings_name ] );
			}
		}

		if( $settings_name === 'wps_flakes_styles' ){
		    if( $value !== '' && ! preg_match( "/^[a-zA-Z0-9\,\#\:\;\ \-\(\)\%\"\_\.\/\!\?]*$/", $value ) ){
			    $value = '';
			    echo '<div class="notice notice-warning"><p>' . __( 'Error while saving the following field. Please make sure you use the correct syntax. Only valid CSS is allowed: ', 'wp-snow' ) . $setting['label'] . '</p></div>';
            }
        }

		if( $settings_name === 'wps_flakes_color' ){
		    if( $value !== '' && ! preg_match( "/^[a-zA-Z0-9\,\#\ ]*$/", $value ) ){
			    var_dump($value);
			    $value = '';
			    echo '<div class="notice notice-warning"><p>' . __( 'Error while saving the following field. Please make sure you use the correct syntax. Only hex colors and "," are allowed: ', 'wp-snow' ) . $setting['label'] . '</p></div>';
            }
        }

		if( $settings_name === 'wps_show_on_specific_pages_only' ){
		    if( $value !== '' && ! preg_match( "/^[0-9\,\ ]*$/", $value ) ){
			    $value = '';
			    echo '<div class="notice notice-warning"><p>' . __( 'Error while saving the following field. Please make sure you use the correct syntax. Only numbers and "," are allowedx: ', 'wp-snow' ) . $setting['label'] . '</p></div>';
            }
        }

		if( $settings_name === 'wps_flakes_font' ){
		    if( $value !== '' && ! preg_match( "/^[a-zA-Z0-9\,\-\ ]*$/", $value ) ){
			    $value = '';
			    echo '<div class="notice notice-warning"><p>' . __( 'Error while saving the following field. Please make sure you use the correct syntax. Only numbers, letters and "," "-" are allowed: ', 'wp-snow' ) . $setting['label'] . '</p></div>';
            }
        }

		if( $settings_name === 'wps_flakes_entity' ){
		    $value = htmlspecialchars($value);
        }

		if( $settings_name === 'wps_flakes_falling_speed' ){
		    if( empty( $value ) || ! is_numeric( $value ) ){
			    $value = '';
            }
        }

		update_option( $settings_name, $value );
		$this->reload_settings();
	}
	// END General Settings

	echo '<div class="notice notice-success"><p>' . __( 'Settings saved.', 'wp-snow' ) . '</p></div>';
}

?>
<h2><?php echo WPSNOW_NAME; ?></h2>
<p>
	<?php _e( 'On this page, you will find all available settings for your snow flakes.', 'wp-snow' ); ?>
</p>

<form method="post" action="">

	<table class="form-table">
		<tbody>

		<?php foreach( $this->settings as $setting_name => $setting ) :

			$is_checked = ( $setting['type'] == 'checkbox' && $setting['value'] == 'yes' ) ? 'checked' : '';
			$value = ( $setting['type'] != 'checkbox' ) ? htmlspecialchars( $setting['value'], ENT_QUOTES, 'utf-8' ) : '1';
			$placeholder = ( ! empty( $setting['placeholder'] ) ) ? $setting['placeholder'] : '';

			$disabled = '';
			if( isset( $setting['disabled'] ) && ! empty( $setting['disabled'] ) ){
				$disabled = 'disabled';
			}

			?>
			<tr valign="top">
				<th scope="row">
					<label for="<?php echo $setting_name; ?>">
						<strong><?php echo $setting['label']; ?></strong>
					</label>
				</th>
				<td>
					<?php if( $setting['type'] !== 'select' ) : ?>
						<input id="<?php echo $setting['id']; ?>" placeholder="<?php echo $placeholder; ?>" name="<?php echo $setting_name; ?>" type="<?php echo $setting['type']; ?>" class="regular-text" value="<?php echo $value; ?>" <?php echo $is_checked; ?> />
					<?php else : ?>
						<select id="<?php echo $setting['id']; ?>" name="<?php echo $setting_name; ?>" <?php echo $disabled; ?>>

							<?php foreach( $setting['choices'] as $key => $name ) :
								if( is_numeric( $name ) || empty( $name ) ){
									$name = $key;
								}

								$selected = '';
								if( $value == $key ){
									$selected = 'selected="selected"';
								}

								?>
								<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $name; ?></option>
							<?php endforeach; ?>

						</select>
						<?php if( ! empty( $value ) && $value !== 'empty' ) : ?>
							<p class="fa-preview"><?php echo __( 'Preview:', 'wp-snow' ); ?> <?php echo "<i style='font-size:18px;' class='fas fa-" . sanitize_title( $value ) . "'></i>"; ?></p>
						<?php endif; ?>
					<?php endif; ?>
					<p class="description">
						<?php echo $setting['description']; ?>
					</p>
				</td>
			</tr>
		<?php endforeach; ?>

		</tbody>
	</table>


	<input type="hidden" name="wpsnow_update_settings" value="yes">
	<?php wp_nonce_field( 'ironikus_wpsnow_settings', 'ironikus_wpsnow_settings_nonce' ); ?>
	<?php submit_button( __( 'Save', 'wp-snow' ) ); ?>

</form>