'use strict';

const wlbObj = () => {

    jQuery(document).ready(function () {
        var lookbooks = [];
        jQuery('.wlb-lookbook-instagram-item').each(function () {
            var lookbook = jQuery(this).find('.wlb-zoom').attr('data-id');
            lookbooks.push(lookbook);
        });
        /*Zoom, Next, Previous, Ajax quick view*/
        jQuery('.wlb-zoom,.wlb-controls-next,.wlb-controls-previous,.wlb-ajax').bind('click', function () {
            jQuery('.wlb-loading').show();
            jQuery('.wlb-product-frame').hide();
            jQuery('.woo-lookbook-quickview').fadeIn(100);
            jQuery('.wlb-loading').fadeIn(100);
            jQuery('.wlb-product-frame').hide();
            jQuery('html').css({'overflow': 'hidden'});
            var l_id = jQuery(this).attr('data-id');
            var p_id = jQuery(this).attr('data-pid');
            // console.log(p_id);
            // console.log(l_id);
            /*Get lookbook on instagram*/
            if (l_id) {
                /*Remove product ID on next and previous button*/
                jQuery('.wlb-controls-next,.wlb-controls-previous').removeAttr('data-pid');

                /*Add Lookbook ID for next and previous button*/
                var index = lookbooks.indexOf(l_id);
                // console.log(index);
                var next = 0;
                var prev = lookbooks.length - 1;
                if (index < (lookbooks.length - 1)) {
                    next = index + 1;
                }
                if (index > 0) {
                    prev = index - 1;
                }
                jQuery('.wlb-controls-next').attr('data-id', lookbooks[next]);
                jQuery('.wlb-controls-previous').attr('data-id', lookbooks[prev]);

                /*Ajax get Instagram image*/
                var str_data = '&lookbook_id=' + l_id + '&nonce=' + _woocommerce_lookbook_params.nonce;
                jQuery.ajax({
                    type: 'POST',
                    data: 'action=wlb_get_lookbook' + str_data,
                    url: _woocommerce_lookbook_params.ajax_url,
                    success: function (data) {
                        jQuery('.wlb-loading').hide();
                        jQuery('.wlb-product-data').html(data);
                        jQuery('.wlb-product-frame').fadeIn(200);
                        variations();
                        submit_form();
                    },
                    error: function (html) {
                    }
                })
            }

            /*Product quick view*/
            if (p_id) {
                /*Remove product ID on next and previous button*/
                jQuery('.wlb-controls-next,.wlb-controls-previous').removeAttr('data-id');
                var products = [];
                jQuery(this).closest('.wlb-lookbook-item-wrapper').find('.wlb-ajax').each(function () {
                    var product = jQuery(this).attr('data-pid');
                    products.push(product);
                });
                if (products.length < 1) {
                    products = jQuery('.wlb-controls').attr('data-products');
                    products = products.split(',');
                } else {
                    jQuery('.wlb-controls').attr('data-products', products);
                }

                /*Add Lookbook ID for next and previous button*/
                var index = products.indexOf(p_id);
                // console.log(index);
                var next = 0;
                var prev = products.length - 1;
                if (index < (products.length - 1)) {
                    next = index + 1;
                }
                if (index > 0) {
                    prev = index - 1;
                }


                jQuery('.wlb-controls-next').attr('data-pid', products[next]);
                jQuery('.wlb-controls-previous').attr('data-pid', products[prev]);
                /*Get Product information*/
                var str_data = '&product_id=' + p_id + '&nonce=' + _woocommerce_lookbook_params.nonce;
                jQuery.ajax({
                    type: 'POST',
                    data: 'action=wlb_get_product' + str_data,
                    url: _woocommerce_lookbook_params.ajax_url,
                    success: function (data) {
                        jQuery('.wlb-loading').hide();
                        jQuery('.wlb-product-data').html(data);
                        jQuery('.wlb-product-frame').fadeIn(200);
                        variations();
                        submit_form();
                    },
                    error: function (html) {
                    }
                })
            }
        });

        /*Hide quick view*/
        jQuery('.wlb-overlay,.wlb-close').bind('click', function () {
            jQuery('.woo-lookbook-quickview').fadeOut(200);
            jQuery('html').attr('style', '');

        });
        /*Short code Slide*/
        jQuery('.wlb-lookbook-slide').slidesjs({
            width: _woocommerce_lookbook_params.width,
            height: _woocommerce_lookbook_params.height,
            navigation: {
                active: _woocommerce_lookbook_params.navigation,
                effect: _woocommerce_lookbook_params.effect
            },
            pagination: {
                active: _woocommerce_lookbook_params.pagination,
                effect: _woocommerce_lookbook_params.effect
            },
            play: {
                auto: _woocommerce_lookbook_params.auto,
                interval: _woocommerce_lookbook_params.time,
                pauseOnHover: true,
                effect: _woocommerce_lookbook_params.effect
            }
        });

        /*Instagram Carousel*/
        if (jQuery('.wlb-lookbook-carousel').length > 0) {
            /*Check responsive*/
            var item_per_row = jQuery('.wlb-lookbook-carousel').attr('data-col');
            var windowsize = jQuery(window).width();
            if (item_per_row == undefined) {
                item_per_row = 4;
            }
            if (windowsize < 768 && windowsize >= 600) {
                item_per_row = 2;
            }
            if (windowsize < 600) {
                item_per_row = 1;
            }


            var rtl = jQuery('.wlb-lookbook-carousel').attr('data-rtl');
            if (parseInt(rtl)) {
                rtl = true;
            } else {
                rtl = false;
            }

            jQuery('.wlb-lookbook-carousel').vi_flexslider({
                selector: '.woo-lookbook-inner >.wlb-lookbook-instagram-item',
                animation: "slide",
                animationLoop: false,
                itemWidth: 200,
                itemMargin: 10,
                controlNav: false,
                maxItems: item_per_row,
                reverse: false,
                slideshow: false,
                rtl: rtl
            });
        }

        /*Reinit Product variation*/
        function variations() {
            var form_variation = jQuery('.variations_form');
            form_variation.each(function () {
                jQuery(this).wc_variation_form();
            })
        }

        function submit_form() {
            jQuery('.wlb-product-frame').on('submit', '.cart', function (e) {
                e.preventDefault();
                var data = jQuery(this).serializeArray();
                var button = jQuery(this).find('button');
                data.push({name: button.attr('name'), value: button.val()});
                // console.log(data);
                jQuery.ajax({
                    url: jQuery(this).attr('action'),
                    type: jQuery(this).attr('method'),
                    data: data,
                    success: function (html) {
                        jQuery('.wlb-added').fadeIn(500);
                        jQuery('.wlb-added').fadeOut(3000);
                    }
                });
                e.preventDefault();
            });
        }
    })
}

wlbObj();

jQuery(window).on('elementor/frontend/init', function () {
    if (window.elementor) {
        console.log('sss');
        elementorFrontend.hooks.addAction('frontend/element_ready/woo-lookbook.default', function () {
            wlbObj();
        });
    }
});