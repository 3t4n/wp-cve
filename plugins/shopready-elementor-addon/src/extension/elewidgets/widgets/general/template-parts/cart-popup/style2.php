<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// links to go cart page
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

$count_icon  = WReady_Helper::get_global_setting( 'woo_ready_widget_cart_count_icon' );
$cart_label  = WReady_Helper::get_global_setting( 'woo_ready_widget_cart_label' );
$singular    = WReady_Helper::get_global_setting( 'woo_ready_widget_cart_count_singular' );
$plural      = WReady_Helper::get_global_setting( 'woo_ready_widget_cart_count_plural' );
$before_text = WReady_Helper::get_global_setting( 'woo_ready_widget_cart_number_before_text' );

?>

<!--====== Poup Cart START ======-->
<div class="woo-ready-block-header woo-ready-dropdown wooready_shop_cart_layout_1">
    <a class="woo-ready-user-interface woo-ready-cart-fr" href="<?php echo esc_url( wc_get_cart_url() ); ?>">

        <?php echo wp_kses_post( shop_ready_render_icons( $count_icon, 'wready-cart-count' ) ); ?>
        <?php if ( $before_text == 'yes' ) : ?>
        <?php if ( isset( WC()->cart ) ) { ?>
        <span class="wready-cart-count-before">
            <?php echo wp_kses_post( count( WC()->cart->get_cart() ) > 1 ? wp_kses_post( sprintf( '%s %s', count( WC()->cart->get_cart() ), $plural ) ) : wp_kses_post( sprintf( '%s %s', count( WC()->cart->get_cart() ), $singular ) ) ); ?>
        </span>
        <?php } ?>
        <?php endif; ?>
        <?php if ( $cart_label != '' ) : ?>
        <div class="display:inline-block wready-cart-count-text">
            <?php echo esc_html( $cart_label ); ?>
        </div>
        <?php endif; ?>
        <?php if ( $before_text != 'yes' ) : ?>
        <?php if ( isset( WC()->cart ) ) { ?>
        <span class="wready-cart-count-after">
            <?php echo wp_kses_post( count( WC()->cart->get_cart() ) > 1 ? wp_kses_post( sprintf( '%s %s', count( WC()->cart->get_cart() ), $plural ) ) : wp_kses_post( sprintf( '%s %s', count( WC()->cart->get_cart() ), $singular ) ) ); ?>
        </span>
        <?php } ?>
        <?php endif; ?>

    </a>
</div>
<!--======  PopUp cart END ======-->