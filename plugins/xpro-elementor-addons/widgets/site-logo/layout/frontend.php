<?php

use Elementor\Utils;

$image = ( 'custom' === $settings['logo_type'] ) ? $settings['custom_logo']['url'] : get_theme_mod( 'custom_logo' );
$url   = ( 'custom' === $settings['link_type'] && $settings['link']['url'] ) ? $settings['link']['url'] : get_home_url();
$attr  = ( 'custom' === $settings['link_type'] && $settings['link']['is_external'] ) ? ' target="_blank"' : '';
$attr .= ( 'custom' === $settings['link_type'] && $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';

if ( 'custom' === $settings['link_type'] && $settings['link']['custom_attributes'] ) {
	$attributes = explode( ',', $settings['link']['custom_attributes'] );

	foreach ( $attributes as $attribute ) {
		if ( ! empty( $attribute ) ) {
			$custom_attr = explode( '|', $attribute, 2 );
			if ( ! isset( $custom_attr[1] ) ) {
				$custom_attr[1] = '';
			}
			$attr .= ' ' . $custom_attr[0] . '="' . $custom_attr[1] . '"';
		}
	}
}

?>
<a href="<?php echo esc_url( $url ); ?>"<?php xpro_elementor_kses( $attr ); ?>>
	<div class="xpro-site-logo">
		<?php
		$image_markup = ( ! empty( $image ) ) ? wp_get_attachment_image( attachment_url_to_postid( $image ), $settings['thumbnail_size'] ) : '';
		echo ! empty( $image_markup ) ? $image_markup : '<img src="' . esc_url( $settings['custom_logo']['url'] ) . '">';
		?>
	</div>
</a>
