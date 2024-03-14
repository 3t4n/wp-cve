<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Advertisement Menu Layout
 * @since 1.0
 */

    $cat_target   = $settings['wready_adv_category_link']['is_external'] ? ' target="_blank"' : '';
    $cat_nofollow = $settings['wready_adv_category_link']['nofollow'] ? ' rel="nofollow"' : '';
    $pro_target   = $settings['adv_product_link']['is_external'] ? ' target="_blank"' : '';
    $pro_nofollow = $settings['adv_product_link']['nofollow'] ? ' rel="nofollow"' : '';
    $image_html   = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'adv_image' );

?>


<div class="woo-ready-product-ads-area">
    <div class="woo-ready-ads woo-ready-overlay-anim">
        <div class="woo-ready-ads-content">
            <a class='ads-cat'
                href="<?php echo esc_url($settings['wready_adv_category_link']['url'] . '"' . $cat_target . $cat_nofollow); ?>">
                <?php echo wp_kses_post( $settings[ 'wready_adv_category' ] ); ?>
            </a>
            <h4 class="text-uppercase">
                <?php echo wp_kses_post( $settings[ 'wready_adv_heading' ]); ?>
            </h4>
            <a class="ads-btn" href="<?php echo esc_url($settings[ 'adv_product_link' ][ 'url' ] . '"' . $pro_target . $pro_nofollow); ?>><?php echo wp_kses_post($settings['adv_button_text']); ?></a>
        </div>
        <?php echo wp_kses_post( $image_html ); ?>
    </div>
</div>