<?php
add_action(
	'wp_enqueue_scripts',
	function() {
		$galleries = get_posts(
			[
				'numberposts' => -1,
				'post_type'   => 'lightgalleries',
				'fields'      => 'ids',
			]
		);
		if ( isset( $galleries ) && is_array( $galleries ) ) {
			foreach ( $galleries as $gallery_id ) {
				$settings = get_post_meta( $gallery_id, 'wp_lightgalleries_data', true );
				if ( isset( $settings['advanced_container_ignore'] ) && ( '' !== $settings['advanced_container_ignore'] ) ) {
					$settings['plugins_multioption']       = lightgallerywp_get_active_plugins( $settings );
					$settings['invoke_license_key_ignore'] = apply_filters( 'lightgallerywp_license_key', '' );
					$settings['invoke_target_ignore']      = $settings['advanced_container_ignore'];
					if ( isset( $settings['advanced_selector_ignore'] ) && ( '' !== $settings['advanced_selector_ignore'] ) ) {
						$settings['invoke_target_selector_ignore'] = $settings['advanced_selector_ignore'];
					}
					wp_add_inline_script( 'lightgalleryjs', lightgallerywp_load_file( 'custom-gallery/script-js.php', $settings ) );
				}
			}
		}
	},
	1000
);

