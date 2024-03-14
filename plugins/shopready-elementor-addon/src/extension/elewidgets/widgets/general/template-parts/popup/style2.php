<?php
if (!defined('ABSPATH')) {
    exit;
}
// popup 

$label = $settings['user_label'];
$nav_icon = $settings['nav_icon'];
$before_text = $settings['icon_before_text'];
$_popup_close = $settings['shop_ready_popup_close'];
$close_icon = $settings['shop_ready_popup_close_icon'];
$modal_animation = $settings['shop_ready_popup_modal_animation'];

?>

<!--====== mini Poup  START ======-->
<div class="woo-ready-block-header woo-ready-dropdown">
    <a class="woo-ready-user-interface woo-ready-popup" href="javascript:void(0);" data-gnash="gnash-dropdown">

        <?php if ($before_text == 'yes'): ?>
        <?php echo wp_kses_post(shop_ready_render_icons($nav_icon, 'wready-popup-mini')); ?>
        <?php endif; ?>
        <?php if ($label != ''): ?>
        <div class="display:inline-block woo-ready-mini-popup-label">
            <?php echo esc_html($label); ?>
        </div>
        <?php endif; ?>

        <?php if ($before_text != 'yes'): ?>
        <?php echo wp_kses_post(shop_ready_render_icons($nav_icon, 'wready-popup-mini')); ?>
        <?php endif; ?>

    </a>

</div>
<div class="shop-ready-pro-minipopup-popup-modal margin:20 nifty-modal <?php echo esc_attr($modal_animation); ?>"
    id="shop-ready-pro-minipopup-popup-modal">
    <div class="shop-ready-pro-newslatter-popup-modal-content wready-md-content">

        <div class='wready-md-body'>
            <?php if ($settings['modal_template_id'] > 0 && is_numeric($settings['modal_template_id'])): ?>
            <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($settings['modal_template_id'], true) ; ?>
            <?php endif; ?>
        </div>
        <?php if ($_popup_close == 'yes'): ?>
        <div class="wready-md-close">

            <?php echo wp_kses_post(shop_ready_render_icons($close_icon, 'wready-icons')); ?>

        </div>
        <?php endif; ?>

    </div>
</div>
<div class="wready-md-overlay"></div>
<!--======  PopUp END ======-->