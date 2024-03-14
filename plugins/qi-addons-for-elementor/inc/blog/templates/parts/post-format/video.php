<?php
$video_meta = get_post_meta( get_the_ID(), 'qodef_post_format_video_url', true );

if ( ! empty( $video_meta ) ) {
	// Video player settings
	$settings = apply_filters(
		'qi_addons_for_elementor_filter_video_post_format_settings',
		array(
			'width'  => 1100, // Aspect ration is 16:9
			'height' => round( 1100 * 9 / 16 ),
			'loop'   => true,
		)
	);

	$oembed = wp_oembed_get( $video_meta );
	if ( ! empty( $oembed ) ) {
		echo wp_oembed_get( $video_meta, $settings );
	} else { ?>
		<div class="qodef-e-media-video">
			<?php
			// Init video player
			echo wp_video_shortcode( array_merge( array( 'src' => esc_url( $video_meta ) ), $settings ) );
			?>
		</div>
	<?php }
} else {
	// Include featured image
	qi_addons_for_elementor_template_part( 'blog', 'templates/parts/post-info/image' );
}
?>
