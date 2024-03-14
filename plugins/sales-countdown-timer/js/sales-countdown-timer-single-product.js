jQuery(document).ready(function ($) {
    'use strict';
    let countdown_container=$('.woo-sctr-single-product-container');
    if (countdown_container.length) {
        let countdown_offset_top = parseInt(countdown_container.offset()['top']) + parseInt(countdown_container.find('.woo-sctr-shortcode-wrap-wrap').css('height'));
        window.onscroll = function () {
            let currentScrollPos = window.pageYOffset;
            if (currentScrollPos > countdown_offset_top) {
                countdown_container.find('.woo-sctr-shortcode-wrap-wrap').addClass('woo-sctr-sticky-top');
            } else {
                countdown_container.find('.woo-sctr-shortcode-wrap-wrap').removeClass('woo-sctr-sticky-top');
            }
        }
    }
});