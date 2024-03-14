<?php

use Elementor\Icons_Manager;

$html_tag = $settings['title_tag'];
$url      = ( 'custom' === $settings['custom_link'] && $settings['title_link']['url'] ) ? $settings['title_link']['url'] : get_home_url();
$attr     = ( 'custom' === $settings['custom_link'] && $settings['title_link']['is_external'] ) ? ' target="_blank"' : '';
$attr    .= ( 'custom' === $settings['custom_link'] && $settings['title_link']['nofollow'] ) ? ' rel="nofollow"' : '';

if ( 'custom' === $settings['custom_link'] && $settings['title_link']['custom_attributes'] ) {
	$attributes = explode( ',', $settings['title_link']['custom_attributes'] );

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

$class  = 'xpro-site-title';
$class .= ( $settings['icon']['value'] ) ? ' xpro-site-title-icon-' . $settings['icon_align'] : '';
?>

<a href="<?php echo esc_url( $url ); ?>"<?php xpro_elementor_kses( $attr ); ?>>
	<<?php echo esc_attr( $html_tag ); ?> class="<?php echo esc_attr( $class ); ?>">
	<?php if ( $settings['icon']['value'] ) : ?>
		<span class="xpro-site-title-icon">
			<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
		</span>
	<?php endif; ?>
	<span class="xpro-site-title-text">
		<?php echo esc_html( get_bloginfo() ); ?>
	</span>

</<?php echo esc_attr( $html_tag ); ?>>
</a>
