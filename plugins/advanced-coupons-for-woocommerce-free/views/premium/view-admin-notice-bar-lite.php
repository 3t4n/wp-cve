<?php if ( ! defined( 'ABSPATH' ) ) {
exit;} // Exit if accessed directly ?>
<style>
#screen-meta, #contextual-help-link-wrap, #screen-options-link-wrap { top: 5px !important; }
@media screen and (max-width: 1023px) {
    #acfwf-notice-bar {
        display: none !important;
    }
}

.acfwf-notice-bar a {
    color: #00a32a;
}

.acfwf-notice-bar a:hover {
    color: #079460;
}

.acfwf-notice-bar {
    background-color: #e3e3e3;
    border-top: 3px solid #00a32a;
    text-align: center;
}

.acfwf-notice-bar.notice-warning {
    border-left: 1px solid #d5d5d5;
}

#acfwf-notice-bar {
    background-color: #e5e5e5;
    border-top: 3px solid #46b450;
    color: #777777;
    text-align: center;
    position: relative;
    padding: 7px;
    margin-bottom: -4px;
    opacity: 1;
    transition: all .3s ease-in-out;
    max-height: 100px;
    overflow: hidden;
}

#acfwf-notice-bar.top-lite {
    top: 60px; /* #woocommerce-embedded-root .woocommerce-layout__header .woocommerce-layout__header-heading (60px) = top: 92px */
}

#acfwf-notice-bar.out {
    opacity: .5;
    max-height: 0;
}

#acfwf-notice-bar a {
    color: #00a32a;
}

#acfwf-notice-bar a:hover {
    color: #079460;
}

#acfwf-notice-bar .acfwf-dismiss-button {
    position: absolute;
    top: 0;
    right: 0;
    border: none;
    padding: 5px;
    margin-top: 1px;
    background: 0 0;
    color: #72777c;
    cursor: pointer;
}

#acfwf-notice-bar .acfwf-dismiss-button:before {
    background: 0 0;
    color: #72777c;
    content: "\f335";
    display: block;
    font: normal 20px/20px dashicons;
    height: 20px;
    text-align: center;
    width: 20px;
    -webkit-font-smoothing: antialiased;
}
</style>

<div id="acfwf-notice-bar" class="acfwf-dismiss-container top-lite">
    <span class="acfwf-notice-bar-message">
        <?php
        echo wp_kses_post(
            sprintf(
                /* translators: 1: opening link tag, 2: closing link tag */
                __( "You're using the free version of the Advanced Coupons plugin. To unlock more features consider %1\$supgrading to Premium%2\$s", 'advanced-coupons-for-woocommerce-free' ),
                '<a href="https://advancedcouponsplugin.com/pricing/?utm_source=acfwf&utm_medium=upsell&utm_campaign=litebar" target="_blank">',
                '</a>'
            ),
        );
        ?>
    </span>
</div>
