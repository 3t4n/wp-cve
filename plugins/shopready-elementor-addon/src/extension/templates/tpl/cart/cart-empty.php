<?php

/**
 * Empty cart page
 *
 * @since 1.0
 */

	defined( 'ABSPATH' ) || exit;

	$config = shop_ready_templates_config()->all();
	$tpl_id = null;

if ( isset( $config['empty_cart'] ) && isset( $config['empty_cart']['active'] ) && $config['empty_cart']['active'] == true ) {

	if ( isset( $config['empty_cart']['id'] ) && $config['empty_cart']['id'] > 1 ) {
		$tpl_id = $config['empty_cart']['id'];
	}
}

if ( is_null( $tpl_id ) ) {

	do_action( 'woocommerce_cart_is_empty' );

	if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
<p class="return-to-shop">
    <a class="button wc-backward"
        href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
        <?php
					echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to shop', 'shopready-elementor-addon' ) ) );
		?>
    </a>
</p>
<?php endif; ?>
<?php
} else {

	echo  \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $tpl_id, true );

}