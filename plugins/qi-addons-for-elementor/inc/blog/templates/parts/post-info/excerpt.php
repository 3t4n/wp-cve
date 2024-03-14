<?php

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
} else {
	$excerpt = get_the_excerpt();

	if ( ! isset( $excerpt_length ) || ( isset( $excerpt_length ) && '' === $excerpt_length ) ) {
		$excerpt_length = qi_addons_for_elementor_get_blog_list_excerpt_length();
	}

	if ( ! empty( $excerpt ) && 'no' !== $show_excerpt ) {
		$new_excerpt = ( $excerpt_length > 0 ) ? substr( $excerpt, 0, intval( $excerpt_length ) ) : $excerpt;
		?>
		<p itemprop="description" class="qodef-e-excerpt">
			<?php echo esc_html( strip_tags( strip_shortcodes( $new_excerpt ) ) ); ?>
		</p>
	<?php }
} ?>
