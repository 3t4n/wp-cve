<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// popup

$label       = $settings['user_label'];
$nav_icon    = $settings['nav_icon'];
$before_text = $settings['icon_before_text'];
$close_icon  = $settings['close_icon'];

?>

<!--====== mini Poup  START ======-->
<div class="woo-ready-block-header woo-ready-dropdown">
    <div class="woo-ready-user-interface woo-ready-popup">

        <?php if ( $before_text == 'yes' ) : ?>
        <?php echo wp_kses_post( shop_ready_render_icons( $nav_icon, 'wready-popup-mini' ) ); ?>
        <?php endif; ?>
        <?php if ( $label != '' ) : ?>
        <div class="display:inline-block woo-ready-mini-popup-label shop-ready-mini-popup-label-modifire">
            <?php echo esc_html( $label ); ?>
        </div>
        <?php endif; ?>

        <?php if ( $before_text != 'yes' ) : ?>
        <?php echo wp_kses_post( shop_ready_render_icons( $nav_icon, 'wready-popup-mini' ) ); ?>
        <?php endif; ?>

    </div>

    <div class="header-account woo-ready-sub-content <?php echo esc_attr( $settings['_kt_entrance_animation'] ); ?>">
        <?php if ( $settings['modal_template_id'] > 0 && is_numeric( $settings['modal_template_id'] ) ) : ?>
        <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['modal_template_id'], true ) ; ?>
        <?php endif; ?>
        <div class="shop-ready-cart-count-close-btn">
            <?php echo wp_kses_post( shop_ready_render_icons( $close_icon, 'wready-icons' ) ); ?>
        </div>
    </div>
</div>
<!--======  PopUp END ======-->