<?php
namespace LightGallery;

/**
 * The lightgallery Shortcode output.
 */
add_shortcode(
	'lightgallery',
	function( $atts ) {
		global $post;
		$output = '';
		if ( is_singular() && is_a( $post, 'WP_Post' ) ) {
			$atts = shortcode_atts(
				[
					'id' => 0,
				],
				$atts,
				'lightgallery'
			);
			if ( 0 !== $atts['id'] ) {
				$settings  = get_post_meta( $atts['id'], 'wp_lightgalleries_data', true );
				$is_inline = ( ( isset( $settings['layout_ignore'] ) && ( 'inline' === $settings['layout_ignore'] ) ) ? true : false );
				$style     = '';
				if ( $is_inline ) {
					$inline_width  = ( ( '' !== $settings['inline_width_ignore'] ) ? $settings['inline_width_ignore'] : '100%' );
					$inline_height = ( ( '' !== $settings['inline_height_ignore'] ) ? $settings['inline_height_ignore'] : '60%' );
					$style         = 'height: 0; overflow:hidden; width: ' . $inline_width . ';';
					if ( false !== strpos( $inline_height, '%' ) ) {
						$style .= ' padding-bottom: ' . ( ( intval( $inline_height ) / intval( $inline_width ) ) * 100 ) . '%;';
					} else {
						$style .= ' padding-bottom: ' . intval( $inline_height ) . '%;';
					}
				}
				$output .= '<div id="lightgallery-grid-' . $atts['id'] . '" class="lightgallery-grid' . ( ( $is_inline ) ? ' lg-wp-inline' : '' ) . '" style="' . esc_attr( $style ) . '">';
				if ( isset( $settings['slide_image_ignore'] ) && ( count( $settings['slide_image_ignore'] ) > 0 ) ) {
					$index              = 0;
					$slide_widths       = ( ( isset( $settings['slide_width_ignore'] ) && is_array( $settings['slide_width_ignore'] ) ) ? $settings['slide_width_ignore'] : [] );
					$slide_heights      = ( ( isset( $settings['slide_height_ignore'] ) && is_array( $settings['slide_height_ignore'] ) ) ? $settings['slide_height_ignore'] : [] );
					$slide_titles       = ( ( isset( $settings['slide_title_ignore'] ) && is_array( $settings['slide_title_ignore'] ) ) ? $settings['slide_title_ignore'] : [] );
					$slide_descriptions = ( ( isset( $settings['slide_description_ignore'] ) && is_array( $settings['slide_description_ignore'] ) ) ? $settings['slide_description_ignore'] : [] );
					$slide_videos       = ( ( isset( $settings['slide_video_ignore'] ) && is_array( $settings['slide_video_ignore'] ) ) ? $settings['slide_video_ignore'] : [] );
					$global_width       = SmartlogixControlsWrapper::get_value( $settings, 'slide_global_width_ignore', '220' );
					$global_height      = SmartlogixControlsWrapper::get_value( $settings, 'slide_global_height_ignore', '220' );
					if ( isset( $settings['slide_image_ignore'] ) && is_array( $settings['slide_image_ignore'] ) ) {
						foreach ( $settings['slide_image_ignore'] as $slide_image ) {
							if ( '' !== $slide_image ) {
								$data_src  = '';
								$full_size = [
									'width'  => '1280',
									'height' => '720',
								];
								if ( isset( $slide_videos[ $index ] ) && ( '' !== $slide_videos[ $index ] ) ) {
									$data_src = $slide_videos[ $index ];
								} else {
									$full_size_image = wp_get_attachment_image_src( $slide_image, 'full' );
									if ( is_array( $full_size_image ) && ( '' !== $full_size_image[0] ) ) {
										$data_src            = $full_size_image[0];
										$full_size['width']  = $full_size_image[1];
										$full_size['height'] = $full_size_image[2];
									}
								}

								if ( '' !== $data_src ) {
									$slide_width = $global_width;
									if ( isset( $slide_widths[ $index ] ) && ( '' !== $slide_widths[ $index ] ) ) {
										$slide_width = $slide_widths[ $index ];
									}
									$slide_height = $global_height;
									if ( isset( $slide_heights[ $index ] ) && ( '' !== $slide_heights[ $index ] ) ) {
										$slide_height = $slide_heights[ $index ];
									}
									$thumbnail_image = wp_get_attachment_image_src( $slide_image, 'medium_large' );
									$caption = '';
									if ( isset( $slide_titles[ $index ] ) && ( '' !== $slide_titles[ $index ] ) ) {
										$caption .= '<h4>' . esc_html( $slide_titles[ $index ] ) . '</h4>';
									}
									if ( isset( $slide_descriptions[ $index ] ) && ( '' !== $slide_descriptions[ $index ] ) ) {
										$caption .= '<p>' . esc_html( $slide_descriptions[ $index ] ) . '</p>';
									}
									$output     .= '<a class="lightgallery-grid-item" data-lg-size="' . esc_attr( $full_size['width'] ) . '-' . esc_attr( $full_size['height'] ) . '" data-sub-html="' . esc_attr( $caption ) . '" data-src="' . esc_attr( $data_src ) . '" style="width: ' . esc_attr( $slide_width ) . 'px; height: ' . esc_attr( $slide_height ) . 'px;">';
										$output .= '<img src="' . esc_attr( $thumbnail_image[0] ) . '" style="width: ' . esc_attr( $slide_width ) . 'px; height: ' . esc_attr( $slide_height ) . 'px;" />';
									$output     .= '</a>';
								}
							}
							$index++;
						}
					}
				}
				$output                                   .= '</div>';
				$output                                   .= '<script type="text/javascript">';
				$settings['gallery_id']                    = $atts['id'];
				$settings['plugins_multioption']           = lightgallerywp_get_active_plugins( $settings );
				$settings['invoke_target_ignore']          = '#lightgallery-grid-' . $settings['gallery_id'];
				$settings['invoke_target_selector_ignore'] = '.lightgallery-grid-item';
				$settings['invoke_license_key_ignore']     = apply_filters( 'lightgallerywp_license_key', '' );
				$output                                   .= lightgallerywp_get_custom_gallery_lightgallery_scripts( $settings, $is_inline );
				$output                                   .= '</script>';
				if ( ! $is_inline ) {
					$output .= '<script type="text/javascript">';
					$output .= lightgallerywp_get_custom_gallery_justified_gallery_scripts( $settings );
					$output .= '</script>';
				}
			}
		}
		return $output;
	}
);

/**
 * Function to generate the javascript calls for Custom Light Gallery.
 *
 * @param array   $settings    Array of settings.
 * @param boolean $is_inline Boolean to specify whether the Gallery Layout is Inline or Not.
 */
function lightgallerywp_get_custom_gallery_lightgallery_scripts( $settings, $is_inline ) {
	if ( $is_inline ) {
		$settings['closable_boolean'] = 'false';
	}
	return lightgallerywp_load_file( 'custom-gallery/script' . ( ( $is_inline ) ? '-inline' : '' ) . '-js.php', $settings );
}

/**
 * Function to generate the javascript calls for Justified Gallery Layout.
 *
 * @param array $settings Array of settings.
 */
function lightgallerywp_get_custom_gallery_justified_gallery_scripts( $settings ) {
	return lightgallerywp_load_file( 'custom-gallery/justified-gallery-js.php', $settings );
}

