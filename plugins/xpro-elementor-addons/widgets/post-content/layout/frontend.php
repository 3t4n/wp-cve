<?php

use Elementor\Plugin;

$limit = ( 'excerpt' === $settings['content_type'] && $settings['limit']['size'] ) ? $settings['limit']['size'] : '';
$post  = get_demo_post_data();

if ( ! isset( $post ) ) {
	return;
}

static $level = 0;

if ( post_password_required( $post->ID ) ) {
	// PHPCS - `get_the_password_form`. is safe.
	echo get_the_password_form( $post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

?>

<div class="xpro-elementor-content">
		<?php

		$dummy = __( 'This is a dummy text to demonstration purpose. It will be replaced with the post content or excerpt.', 'xpro-elementor-addons' );

		if ( 'excerpt' === $settings['content_type'] ) {
			if ( ! empty( $post->post_excerpt ) ) {
				echo wp_trim_words( wp_strip_all_tags( $post->post_excerpt ), $limit ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo wp_trim_words( wp_strip_all_tags( $post->post_content ), $limit ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		} else {
			if ( ! empty( $post->post_content ) ) {
				echo apply_filters( 'the_content', get_post_field( 'post_content', $post->ID ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo wp_kses_post( $dummy );
			}
		}

		wp_link_pages(
			array(
				'before'      => '<div class="page-links xpro-page-links"><span class="xpro-page-links-title">' . esc_html__( 'Pages:', 'xpro-elementor-addons' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'xpro-elementor-addons' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			)
		);
		?>
</div>

<?php
