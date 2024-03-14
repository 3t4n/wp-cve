<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Floating Cart Layout One

	$add_to_cart_icon = shop_ready_render_icons( $settings['add_to_cart_icon'], 'wready-icons' );
	$popup_close_icon = shop_ready_render_icons( $settings['popup_close_icon'], 'wready-icons' );

?>

<div class="btn btn-raised woo-ready-cart-circle">
	<?php echo wp_kses_post( $add_to_cart_icon ); ?>
</div>

<div class="woo-ready-cart-box">
	<div class="cart-box-header">
		<span class="cart-box-title"><?php echo esc_html( $settings['floating_cart_title'] ); ?></span>
		<span class="woo-ready-cart-box-toggle"><?php echo wp_kses_post( $popup_close_icon ); ?></span>
	</div>

	<?php if ( 'content' == $settings['floating_cart_content_type'] ) : ?>
	<div class="widget_shopping_cart_content"></div>
	<?php elseif ( 'template' == $settings['floating_cart_content_type'] ) : ?>
		<?php
		if ( ! empty( $settings['floating_cart_content_template'] ) ) {
				echo wp_kses_post( '<div class="shopping_cart_template_content">' );
					echo wp_kses_post( \Elementor\Plugin::$instance->frontend->get_builder_content( $settings['floating_cart_content_template'], true ) );
				echo wp_kses_post( '</div>' );
		}
		?>
	<?php endif; ?>
</div>
