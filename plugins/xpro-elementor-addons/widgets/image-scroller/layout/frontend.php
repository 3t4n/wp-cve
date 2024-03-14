<?php

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

$html_tag = ( $settings['link']['url'] ) ? 'a' : 'div';
$attr     = $settings['link']['is_external'] ? ' target="_blank"' : '';
$attr    .= $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
$attr    .= $settings['link']['url'] ? ' href="' . $settings['link']['url'] . '"' : '';

if ( $settings['link'] && $settings['link']['custom_attributes'] ) {
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

<<?php echo esc_attr( $html_tag ); ?> <?php xpro_elementor_kses( $attr ); ?> class="xpro-scroll-image-wrapper">
<div class="xpro-scroll-image-inner xpro-image-<?php echo esc_attr( $settings['trigger_type'] ); ?>">
	<!-- Image -->
	<div class="xpro-scroll-image-<?php echo esc_attr( $settings['direction_type'] ); ?> xpro-image-scroll-img">
		<?php
		if ( $settings['image'] ) {
			echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' ) );
		}
		?>
	</div>

	<?php if ( 'yes' === $settings['show_indicator'] ) : ?>
		<!-- Icon Indicator -->
		<span class="xpro-scroll-image-indicator-icon">
			<?php
			if ( $settings['icon_indicator'] ) {
				Icons_Manager::render_icon( $settings['icon_indicator'], array( 'aria-hidden' => 'true' ) );
			}
			?>
		</span>
	<?php endif; ?>

	<?php if ( 'yes' === $settings['show_badge'] ) : ?>
		<!-- Badge -->
		<span class="xpro-scroll-image-badge xpro-badge xpro-badge-<?php echo esc_attr( $settings['badge_position'] ); ?> ">
				<?php echo esc_attr( $settings['badge_text'] ); ?>
			</span>
	<?php endif; ?>

</div>
</<?php echo esc_attr( $html_tag ); ?>>
