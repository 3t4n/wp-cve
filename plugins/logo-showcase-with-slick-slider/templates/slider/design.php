<?php
/**
 * Slider Template
 *
 * /slider/design.php
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="lswssp-slide <?php echo esc_attr( $atts['wrp_cls'] ); ?>">
	<div class="lswssp-slide-inner">
		<div class="lswssp-logo-img-wrap">
			<?php if ( ! empty( $atts['logo_link'] ) ) { ?>
				<a href="<?php echo esc_url( $atts['logo_link'] ); ?>" class="lswssp-logo-img-link" target="<?php echo esc_attr( $atts['link_target'] ); ?>"><img src="<?php echo esc_url( $atts['logo_img_url'] ); ?>" class="lswssp-logo-img" alt="<?php echo esc_attr( $atts['logo_alt_text'] ); ?>" /></a>
			<?php } else { ?>
				<img class="lswssp-logo-img" src="<?php echo esc_url( $atts['logo_img_url'] ); ?>" alt="<?php echo esc_attr( $atts['logo_alt_text'] ); ?>" />
			<?php } ?>
		</div>

		<?php if( $atts['show_title'] && ! empty( $atts['logo_title'] ) ) { ?>
			<div class="lswssp-logo-title">
				<?php echo wp_kses_post( $atts['logo_title'] ); ?>
			</div>
		<?php }

		if( $atts['show_desc'] && ! empty( $atts['logo_desc'] ) ) { ?>
			<div class="lswssp-logo-desc">
				<?php echo wp_kses_post( wpautop( wptexturize( $atts['logo_desc'] ) ) ); ?>
			</div>
		<?php } ?>
	</div>
</div>