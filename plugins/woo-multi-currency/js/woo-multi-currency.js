jQuery(document).ready(function ($) {
    'use strict';
    $(document.body).on('updated_checkout', function (event, response) {
        if (response.hasOwnProperty('wmc_update_checkout') && response.wmc_update_checkout) {
            $(document.body).trigger('update_checkout');
        }
    });
    $(document.body).on('payment_method_selected', function (event, response) {
        let selectedPaymentMethod = $('.woocommerce-checkout input[name="payment_method"]:checked').val();
        if (selectedPaymentMethod === 'ppcp-gateway') {
            $(document.body).trigger('update_checkout');
        }
    });
    window.woo_multi_currency = {
        init: function () {
            this.design();
            this.checkPosition();
            this.cacheCompatible();
            this.ajaxComplete();
        },

        design: function () {
            var windowsize = jQuery(window).width();
            if (windowsize <= 768) {
                jQuery('.woo-multi-currency.wmc-sidebar').on('click', function () {
                    jQuery(this).toggleClass('wmc-hover');
                    if (jQuery(this).hasClass('wmc-hover')) {
                        jQuery('html').css({'overflow': 'hidden'});
                    } else {
                        jQuery('html').css({'overflow': 'visible'});
                    }
                })
            } else {
                /*replace hover with mouseenter mouseleave in some cases to work correctly*/
                jQuery('.woo-multi-currency.wmc-sidebar').on('mouseenter', function () {
                    let $this = jQuery(this);
                    $this.addClass('wmc-hover');
                });
                jQuery('.woo-multi-currency.wmc-sidebar').on('mouseleave', function () {
                    let $this = jQuery(this);
                    $this.removeClass('wmc-hover');
                })
            }
        },

        checkPosition: function () {
            jQuery('.woo-multi-currency .wmc-currency-wrapper').on('mouseenter', function () {
                if (this.getBoundingClientRect().top / $(window).height() > 0.5) {
                    $('.woo-multi-currency .wmc-sub-currency').addClass('wmc-show-up');
                } else {
                    $('.woo-multi-currency .wmc-sub-currency').removeClass('wmc-show-up');
                }
            });
        },

        cacheCompatible() {
            if (wooMultiCurrencyParams.enableCacheCompatible === '1') {

                if (typeof wc_checkout_params !== 'undefined') {
                    if (parseInt(wc_checkout_params.is_checkout) === 1) return;
                }

                let pids = [];
                let simpleCache = $('.wmc-cache-pid');
                if (simpleCache.length) {
                    simpleCache.each(function (i, element) {
                        let wmc_product_id = $(element).data('wmc_product_id');
                        if (wmc_product_id) {
                            pids.push(wmc_product_id);
                        }
                    });
                }

                let variationCache = $('.variations_form');
                if (variationCache.length) {
                    variationCache.each(function (index, variation) {
                        let data = $(variation).data('product_variations');
                        if (data.length) {
                            data.forEach((element) => {
                                pids.push(element.variation_id);
                            });
                        }
                    });
                }

                let $shortcodes = $('.woo-multi-currency.wmc-shortcode').not('.wmc-list-currency-rates'),
                    shortcodes = [];
                $shortcodes.map(function () {
                    let $shortcode = $(this);
                    shortcodes.push({
                        layout: $shortcode.data('layout') ? $shortcode.data('layout') : 'default',
                        flag_size: $shortcode.data('flag_size'),
                    });
                });
                if (pids.length) pids = [...new Set(pids)]; //remove duplicate element

                let exchangePrice = [];
                $('.wmc-cache-value').each(function (i, element) {
                    exchangePrice.push({
                        price: $(element).data('price'),
                        original_price: $(element).data('original_price'),
                        currency: $(element).data('currency'),
                        product_id: $(element).data('product_id'),
                        keep_format: $(element).data('keep_format')
                    });
                });
                exchangePrice = [...new Set(exchangePrice.map(JSON.stringify))].map(JSON.parse);

                $.ajax({
                    url: wooMultiCurrencyParams.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'wmc_get_products_price',
                        pids: pids,
                        shortcodes: shortcodes,
                        wmc_current_url: $('.wmc-current-url').val(),
                        exchange: exchangePrice
                    },
                    success(res) {
                        if (res.success) {
                            let prices = res.data.prices || '',
                                currentCurrency = res.data.current_currency || '',
                                exSc = res.data.exchange || '';

                            if (shortcodes.length > 0 && shortcodes.length === res.data.shortcodes.length) {
                                for (let i = 0; i < shortcodes.length; i++) {
                                    $shortcodes.eq(i).replaceWith(res.data.shortcodes[i]);
                                }
                            }

                            if (wooMultiCurrencyParams.switchByJS !== '1') {
                                $('.wmc-currency a').unbind();
                            }

                            if (currentCurrency) {
                                /*Sidebar*/
                                $('.wmc-sidebar .wmc-currency').removeClass('wmc-active');
                                $(`.wmc-sidebar .wmc-currency[data-currency=${currentCurrency}]`).addClass('wmc-active');
                                /*Product price switcher*/
                                $('.wmc-price-switcher .wmc-current-currency i').removeClass().addClass('vi-flag-64 flag-' + res.data.current_country);
                                $(`.wmc-price-switcher .wmc-sub-currency-current`).removeClass('wmc-sub-currency-current');
                                $(`.wmc-price-switcher .wmc-currency[data-currency=${currentCurrency}]`).addClass('wmc-sub-currency-current');

                                $(`select.wmc-nav option[data-currency=${currentCurrency}]`).prop('selected', true);
                                $('body').removeClass(`woocommerce-multi-currency-${wooMultiCurrencyParams.current_currency}`).addClass(`woocommerce-multi-currency-${currentCurrency}`);
                            }
                            // woo_multi_currency.disableCurrentCurrencyLink();
                            if (typeof woo_multi_currency_switcher !== 'undefined') {
                                woo_multi_currency_switcher.init();
                            }


                            if (prices) {
                                for (let id in prices) {
                                    $(`.wmc-cache-pid[data-wmc_product_id=${id}]`).replaceWith(prices[id]);
                                }

                                $('.variations_form').each((i, form) => {
                                    let data = $(form).data('product_variations');
                                    if (data) {
                                        data.map((element) => {
                                            let pid = element.variation_id;
                                            element.price_html = prices[pid];
                                            return element
                                        });
                                        $(form).data('product_variations', data);
                                    }
                                });
                            }

                            if (exSc) {
                                for (let i in exSc) {
                                    $(`.wmc-cache-value[data-price="${exSc[i]['price']}"][data-product_id="${exSc[i]['product_id']}"][data-keep_format="${exSc[i]['keep_format']}"][data-original_price="${exSc[i]['original_price']}"][data-currency="${exSc[i]['currency']}"]`).replaceWith(exSc[i]['shortcode']);
                                }
                            }
                            jQuery(document.body).trigger("wmc_cache_compatible_finish", [res.data]);
                        }
                    }
                });
            }
        },

        ajaxComplete() {
            $(document).on('append.infiniteScroll', () => {
                this.cacheCompatible();
            });
        }
    };

    woo_multi_currency.init();
});
