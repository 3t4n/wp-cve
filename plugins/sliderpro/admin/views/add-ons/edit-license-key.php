<div class="modal-window-container edit-license-key">
	<div class="modal-window">
		<span class="close-x"></span>
		<div>
			<input id="license-key" class="license-key-input" type="text" name="license_key" placeholder="<?php _e( 'Please enter the license key here.', 'sliderpro' ) ?>" value="<?php echo esc_attr( $license_key ); ?>" />
			<a class="button-secondary license-key-update-button" href="#" data-nonce="<?php echo wp_create_nonce( 'verify-add-on-license-key-' . $add_on_slug ); ?>"><span></span><p><?php _e( 'Update', 'sliderpro' ); ?></p></a>
		</div>
		<div class="verify-log"><span class="verify-log-icon dashicons"></span><p class="verify-log-message"></p></div>
		
		<?php
			if ( ! empty( $license_key_info ) ) {
				echo '<div class="license-key-info ' . esc_attr( $license_key_info_class ) . '">' . $license_key_info . '</div>';
			}
		?>
	</div>
</div>