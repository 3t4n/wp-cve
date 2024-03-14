lightgallerywp_document_ready(function() {	
	var galleries = document.querySelectorAll('<?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'invoke_target_ignore' ) ); ?>');
	[].forEach.call(
		galleries,
		function(el) {
			var lg = lightGallery(el, {
				selector: '<?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'invoke_target_selector_ignore' ) ); ?>',
				licenseKey: '<?php echo esc_js( LightGallery\SmartlogixControlsWrapper::get_value( $args, 'invoke_license_key_ignore' ) ); ?>',
				<?php
				if ( isset( $args ) && is_array( $args ) ) {
					foreach ( $args as $key => $value ) {
						echo wp_kses_data( lightgallerywp_get_setting_parameter( $args, $key ) );
					}
				}
				?>
			});
		}
	);
});
