<?php

use Elementor\Icons_Manager;

$hover_animation = ( '2d-transition' === $settings['social_icon_hover_animation'] ) ? 'xpro-button-2d-animation ' . $settings['social_icon_hover_2d_css_animation'] : ( ( 'background-transition' === $settings['social_icon_hover_animation'] ) ? 'xpro-button-bg-animation ' . $settings['social_icon_hover_background_css_animation'] : ( ( 'hover-effect' === $settings['social_icon_hover_animation'] ) ? 'xpro-unique-' . $settings['social_icon_hover_effect_animation'] : 'xpro-elementor-button-animation-none' ) );
?>

<!-- Social Icon -->
<ul class="xpro-social-icon-wrapper">
	<?php foreach ( $settings['item'] as $i => $item ) : ?>
		<li class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
			<?php
			$target   = $item['link']['is_external'] ? ' target="_blank"' : '';
			$nofollow = $item['link']['nofollow'] ? ' rel="nofollow"' : '';
			$icon     = $item['icon']['value'];
			$library  = 'svg' === $item['icon']['library'] ? 'svg' : 'icon';
			if ( $item['icon']['value'] && 'svg' !== $library ) {
				$social_name = str_replace( array( 'fa fa-', 'fab fa-', 'far fa-', 'fas fa-' ), '', $icon );
			} else {
				$social_name = 'svg';
			}
			echo ( $item['link']['url'] ) ? '<a class="xpro-social-icon ' . esc_attr( $hover_animation ) . ' elementor-social-icon-' . esc_attr( $icon ? $social_name : 'label' ) . ' " href="' . esc_url( $item['link']['url'] ) . '" ' . esc_attr( $target ) . esc_attr( $nofollow ) . '>' : '<span class="xpro-social-icon ' . $hover_animation . ' elementor-social-icon-' . esc_attr( $icon ? $social_name : 'label' ) . ' ">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
			<?php
			if ( $item['icon'] ) {
				Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) );
			}
			?>
			<?php
			if ( $item['title'] ) {
				?>
				<span class="xpro-social-icon-title"><?php echo esc_html( $item['title'] ); ?></span>
			<?php } ?>
			<?php echo ( $item['link']['url'] ) ? '</a>' : '</span>'; ?>
		</li>
	<?php endforeach; ?>
</ul>
