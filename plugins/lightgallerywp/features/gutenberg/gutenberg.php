<?php
require_once dirname( __FILE__ ) . '/settings.php';
require_once dirname( __FILE__ ) . '/layout-settings.php';

add_action(
	'wp_enqueue_scripts',
	function() {
		$settings                              = get_option( 'lightgallerywp_default_gallery_settings' );
		$settings['plugins_multioption']       = lightgallerywp_get_active_plugins( $settings );
		$settings['invoke_license_key_ignore'] = apply_filters( 'lightgallerywp_license_key', '' );
		if ( isset( $settings['enable_gutenberg_gallery_ignore'] ) && ( 'true' === $settings['enable_gutenberg_gallery_ignore'] ) ) {
			// For pre WordPress 5.9 Gutenberg users.
			$settings['invoke_target_ignore']          = '.blocks-gallery-grid';
			$settings['invoke_target_selector_ignore'] = 'figure a';
			wp_add_inline_script( 'lightgalleryjs', lightgallerywp_load_file( 'gutenberg/script-js.php', $settings ) );
			// For post WordPress 5.9 Gutenberg users.
			$settings['invoke_target_ignore']          = '.wp-block-gallery';
			$settings['invoke_target_selector_ignore'] = 'figure a';
			wp_add_inline_script( 'lightgalleryjs', lightgallerywp_load_file( 'gutenberg/script-js.php', $settings ) );
			// If chosen layout is Justified Gallery.
			if ( isset( $settings['layout_ignore'] ) && ( 'justified' === $settings['layout_ignore'] ) ) {
				$settings['invoke_target_ignore'] = '.wp-block-gallery';
				$settings['justified_gallery_selector_ignore'] = '.wp-block-image';
				$settings['justified_gallery_image_selector_ignore'] = 'img';
				wp_add_inline_script( 'lightgalleryjs', lightgallerywp_load_file( 'gutenberg/justified-gallery-js.php', $settings ) );
			}
		}
		if ( isset( $settings['enable_indivigual_images_ignore'] ) && ( 'true' === $settings['enable_indivigual_images_ignore'] ) ) {
			$settings['invoke_target_ignore']          = '.wp-block-image .wp-block-image';
			$settings['invoke_target_selector_ignore'] = 'figure a';
			wp_add_inline_script( 'lightgalleryjs', lightgallerywp_load_file( 'gutenberg/script-js.php', $settings ) );
		}
	},
	1000
);

