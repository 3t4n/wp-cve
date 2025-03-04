document.addEventListener('DOMContentLoaded', function () {
    bopobb_no_atc_ajax();
});

function bopobb_no_atc_ajax() {
    let is_bopobb = false;
    if (jQuery('.bopobb-single-wrap').length) is_bopobb = true;
    if (document.body.classList.contains('ajax-single-add-to-cart') && document.body.classList.contains('theme-zoa') && is_bopobb) {
        jQuery(document.body).removeClass('ajax-single-add-to-cart');
    }
    if (document.body.classList.contains('theme-xstore')) {
        jQuery('.product.type-product.product-type-bopobb .single_add_to_cart_button').removeClass('ajax_add_to_cart');
    }
}

jQuery(document).ready(function ($) {
    "use strict";

    $('.bopobb-single-wrap').each(function () {
        bopobbInit($(this));
    });

    $(document).on('click touch', '.bopobb-item-change', function () {
        let btn_index = $(this).attr('data-item');
        if ($(this).closest('.bopobb-single-wrap').find('.bopobb-items-bottom-wrap .bopobb-item-product.bopobb-item-' + btn_index).attr('data-change') == 1) {
            bopobbDisableScroll();
            $(this).closest('.bopobb-single-wrap').addClass('bopobb-swap-active');
            $('.bopobb-area .bopobb-inner .bopobb-overlay').addClass('bopobb-open');
            $('.bopobb-area .bopobb-inner .bopobb-popup').addClass('bopobb-open');
            $('.bopobb-area .bopobb-inner').addClass('bopobb-open');
            $('.bopobb-area .bopobb-inner .bopobb-popup-header .bopobb-btn-back').hide(100);
            $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').css({
                'visibility': 'visible',
                'opacity': 1
            });
            let popup_id_product = $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').attr('data-product');
            let popup_index = $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').attr('data-item');
            let item_id_product = $(this).attr('data-product');
            let item_index = $(this).attr('data-item');
            if (popup_index === '' || popup_index !== item_index || popup_id_product != item_id_product) {
                $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').html('').addClass('bopobb-loading');
                $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').html('');
                let data = {
                    action: 'bopobb_product_list',
                    item: $(this).attr('data-item'),
                    product: $(this).attr('data-product'),
                    nonce: bopobbVars.nonce,
                };
                $.post(bopobbVars.ajaxurl, data, function (response) {
                    $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list')
                        .html(response).attr('data-product', item_id_product)
                        .attr('data-product', item_id_product)
                        .attr('data-item', item_index)
                        .removeClass('bopobb-loading');
                });
            }
        } else {
            if ($(this).closest('.bopobb-single-wrap').find('.bopobb-items-bottom-wrap .bopobb-item-product.bopobb-item-' + btn_index).attr('data-change') == '') {
                alert(bopobbVars.alert_no_item);
            }
        }
    });

    $(document).on('click touch', '.bopobb-product-pages:not(.bopobb-product-page-active)', function () {
        // let btn_index = $(this).closest('.bopobb-product-list').attr('data-item');
        bopobbDisableScroll();
        // $('.bopobb-area .bopobb-inner .bopobb-overlay').addClass('bopobb-open');
        // $('.bopobb-area .bopobb-inner .bopobb-popup').addClass('bopobb-open');
        // $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-gallery-wrap').bopobbSlideLeft(1, 0, 960);
        // $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').css({
        //     'visibility': 'visible',
        //     'opacity': 1
        // });
        // $('.bopobb-area .bopobb-inner .bopobb-popup-header .bopobb-btn-back').hide(100);
        // let popup_id_product = $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').attr('data-product');
        // let popup_index = $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').attr('data-item');
        let item_id_product = $(this).closest('.bopobb-product-list').attr('data-product');
        let item_index = $(this).closest('.bopobb-product-list').attr('data-item');
        // if (popup_index === '' || popup_index !== item_index || popup_id_product != item_id_product) {
        $('.bopobb-area .bopobb-inner .bopobb-popup').attr('data-caller', 'single');
        $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').html('').addClass('bopobb-loading');
        // $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').html('');
        let data = {
            action: 'bopobb_product_list',
            item: item_index,
            product: item_id_product,
            page: $(this).attr('data-page'),
            nonce: bopobbVars.nonce,
        };
        $.post(bopobbVars.ajaxurl, data, function (response) {
            $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').html(response);
            // .attr('data-product', item_id_product)
            // .attr('data-item', item_index)
            setTimeout(function () {
                $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').removeClass('bopobb-loading');
            }, 100)
        });
        // }
    });

    $(document).on('click touch', '.bopobb-product-img-wrap', function () {
        switch ($(this).closest('.bopobb-product').attr('data-type')) {
            case 'simple':
                bopobbApplyProduct($(this).closest('.bopobb-product-list').attr('data-item'), $(this));
                break;
            case 'variation':
                bopobbApplyProduct($(this).closest('.bopobb-variation-list').attr('data-item'), $(this));
                break;
            case 'variable':
                let variable_title = $(this).closest('.bopobb-product').find('.bopobb-product-title-wrap .bopobb-product-title').html();
                let left_slide = $('.bopobb-area .bopobb-inner .bopobb-popup').width();
                $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').bopobbSlideLeft(400, 0, left_slide);
                $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').bopobbSlideLeft(400, 1, left_slide);
                $('.bopobb-area .bopobb-inner .bopobb-popup-header .bopobb-btn-back').show(400);
                $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-popup-header .bopobb-popup-title').html(variable_title);
                $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-popup-header .bopobb-popup-title').attr('title', variable_title);
                if ($(this).closest('.bopobb-product').attr('data-product') != $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').attr('data-product')) {
                    $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').addClass('bopobb-loading');
                    let variable_id = $(this).closest('.bopobb-product').attr('data-product');
                    let data = {
                        action: 'bopobb_product_variations',
                        product: variable_id,
                        nonce: bopobbVars.nonce,
                    };
                    $.post(bopobbVars.ajaxurl, data, function (response) {
                        $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').html(response).attr('data-product', variable_id).removeClass('bopobb-loading');
                        let bottom_filter_height = $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list .bopobb-product-filter').outerHeight();
                        $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').css('padding-bottom', bottom_filter_height);
                    });
                }
                $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').attr('data-item', $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').attr('data-item'));
                break;
            default:
                break;
        }
    });

    $(document).on('click change', '.bopobb-attr-select', function () {
        let variation_array = [];
        $('.bopobb-filter-variation .bopobb-attr-select').each(function () {
            let attr_name = $(this).attr('name');
            variation_array[attr_name] = $(this).val();
        })
        $('.bopobb-variation-list .bopobb-product').each(function () {
            let product_display = true;
            for (let key in variation_array) {
                if (variation_array.hasOwnProperty(key) && variation_array[key] != '') {
                    if (variation_array[key] != $(this).data(key)) {
                        product_display = false;
                    }
                }
            }
            if (product_display) {
                $(this).show(400);
            } else {
                $(this).hide(400);
            }
        })
    });

    $(document).on('click touch', '.bopobb-overlay', function () {
        bopobbClosePopup();
    });

    $(document).on('click touch', '.bopobb-btn-close', function () {
        bopobbClosePopup();
    });

    $(document).on('click touch', '.bopobb-btn-back', function () {
        $(this).hide(100);
        $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-popup-header .bopobb-popup-title').html(bopobbVars.bopobb_popup_title);
        let left_slide = $('.bopobb-area .bopobb-inner .bopobb-popup').width();
        $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').bopobbSlideRight(400, 1);
        $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').bopobbSlideRight(400, 0);
    });

    function bopobbInit($wrap, stage = 'init') {
        let wrap_id = $wrap.attr('data-id');
        let $container = bopobb_wrap_container($wrap, wrap_id);

        bopobb_check_ready($container, stage);
        bopobb_calc_price($container);
        bopobb_save_ids($container);

        $wrap.removeClass('bopobb-initial-process');
        $(document).trigger('bopobb_init', [$wrap, $container]);
    }

    function bopobb_wrap_container($wrap, id) {
        if ($wrap.closest('.bopobb-shortcode-form').length) {
            return $wrap.closest('.bopobb-shortcode-form');
        }

        if ($wrap.closest('.bopobb-container').length) {
            return $wrap.closest('.bopobb-container');

        }
        if ($wrap.closest('#product-' + id).length) {
            let summary_wrap = $wrap.closest('#product-' + id + ' .summary.entry-summary');
            let information_wrap = $wrap.closest('#product-' + id + ' .product-information');
            if (summary_wrap.length) return summary_wrap;
            if (information_wrap.length) return information_wrap;

        }
        if ($wrap.closest('.product.post-' + id).length) {
            return $wrap.closest('.product.post-' + id);

        }
        if ($wrap.closest('div.product-type-bopobb').length) {
            return $wrap.closest('div.product-type-bopobb');

        }
        if ($wrap.closest('.elementor-product-bopobb').length) {
            return $wrap.closest('.elementor-product-bopobb');

        }
        if ($wrap.closest('body.theme-Divi').length && $wrap.closest('.et_pb_column').length) {

            return $wrap.closest('.et_pb_column');
        }

        return $wrap;
    }

    function bopobb_container(id, stage) {
        let aditional_class = stage == 'change' ? '.bopobb-initial-process' : '';

        if ($('.bopobb-single-wrap-' + id + aditional_class).closest('.bopobb-shortcode-form').length) {
            return $('.bopobb-single-wrap-' + id + aditional_class).closest('.bopobb-shortcode-form').attr('class');
        }

        if ($('.bopobb-single-wrap-' + id + aditional_class).closest('.bopobb-container').length) {
            return '.bopobb-container';

        }

        if ($('.bopobb-single-wrap-' + id + aditional_class).closest('#product-' + id).length) {
            return '#product-' + id + ' .summary.entry-summary';

        }
        if ($('.bopobb-single-wrap-' + id + aditional_class).closest('.product.post-' + id).length) {
            return '.product.post-' + id;

        }
        if ($('.bopobb-single-wrap-' + id + aditional_class).closest('div.product-type-bopobb').length) {
            return 'div.product-type-bopobb';

        }
        if ($('.bopobb-single-wrap-' + id + aditional_class).closest('.elementor-product-bopobb').length) {
            return '.elementor-product-bopobb';

        }

        return 'body.single-product';
    }

    // Disable add to cart button
    function bopobb_check_ready($container, stage = 'init') {
        let is_empty = false;
        let is_max = false;
        let $price = $container.find('.price');
        let $products = $container.find('.bopobb-items-bottom-wrap .bopobb-item-product');
        let $alert = $container.find('.bopobb-alert');
        let $btn = $container.find('.single_add_to_cart_button');
        let count_item = $container.find('.bopobb-single-wrap .bopobb-items-bottom-wrap .bopobb-item-product').length;

        // remove ajax add to cart
        $btn.removeClass('ajax_add_to_cart');

        if ($container.find('.bopobb-items-bottom-wrap.bopobb-template-2').length) {
            $container.find('.bopobb-single-wrap .bopobb-detail-table .bopobb-item-product').each(function () {
                if ($(this).attr('data-id') == '') {
                    // $(this).addClass('bopobb-item-product-null');
                    // $(this).find('th .bopobb-item-img-wrap').hide();
                    $(this).find('th .bopobb-item-detail').addClass('bopobb-item-detail-null');
                    $(this).find('th .bopobb-item-detail .bopobb-item-title').html(bopobbVars.bopobb_popup_title).addClass('bopobb-item-title-placeholder');
                    // $(this).find('th .bopobb-item-change .dashicons').removeClass('dashicons-update-alt').addClass('dashicons-insert');
                } else {
                    // $(this).removeClass('bopobb-item-product-null');
                    // $(this).find('th .bopobb-item-img-wrap').show();
                    $(this).find('th .bopobb-item-detail').removeClass('bopobb-item-detail-null');
                    $(this).find('th .bopobb-item-detail .bopobb-item-title').removeClass('bopobb-item-title-placeholder');
                    // $(this).find('th .bopobb-item-change .dashicons').addClass('dashicons-update-alt').removeClass('dashicons-insert');
                }
            })
        }

        if (stage === 'init') {
            let item_width = parseInt($container.find('.bopobb-single-wrap .bopobb-item-top:first-child').width());
            if ($container.find('.bopobb-items-bottom-wrap.bopobb-template-1').length) {
                for (let i = 0; i < count_item; i++) {
                    if ($container.find('.bopobb-single-wrap .bopobb-item-top.bopobb-item-' + i).attr('data-default') == 0) {
                        $container.find('.bopobb-single-wrap .bopobb-item-top.bopobb-item-' + i + ' .bopobb-item-img-wrap.bopobb-item-change')
                            .addClass('bopobb-icon-plus2').css('min-height', item_width);
                        // $container.find('.bopobb-single-wrap .bopobb-item-top.bopobb-item-' + i + ' .bopobb-item-img-wrap.bopobb-item-change').addClass('dashicons').addClass('dashicons-insert');
                        // $container.find('.bopobb-single-wrap .bopobb-item-top.bopobb-item-' + i + ' .bopobb-item-change-wrap').css({
                        //     'opacity': 0,
                        //     'visibility': 'hidden'
                        // });
                        // $container.find('.bopobb-single-wrap .bopobb-items-bottom-wrap .bopobb-item-' + i).hide();
                    }
                }
            } else {
                for (let i = 0; i < count_item; i++) {
                    if (!$container.find('.bopobb-single-wrap .bopobb-items-bottom-wrap .bopobb-item-product.bopobb-item-' + i).attr('data-id')) {
                        $container.find('.bopobb-single-wrap .bopobb-items-bottom-wrap .bopobb-item-product.bopobb-item-' + i + ' .bopobb-item-img')
                            .css('min-height', '80px');
                    }
                }
            }
        }

        $products.each(function () {
            if ($(this).attr('data-id') == '') is_empty = true;
            if ((parseInt($(this).attr('data-max')) >= 0) && (parseInt($(this).attr('data-max')) < parseInt($(this).attr('data-qty')))) is_max = true;

            if ($(this).attr('data-change') != 1) {
                if ($container.find('.bopobb-items-bottom-wrap').hasClass('bopobb-template-1')) {
                    $container.find('.bopobb-item-change-wrap .bopobb-item-change.bopobb-item-' + $(this).attr('data-item')).css({
                        'opacity': 0,
                        'visibility': 'hidden'
                    });
                }
                if ($container.find('.bopobb-items-bottom-wrap').hasClass('bopobb-template-2')) {
                    $(this).find('.bopobb-item-change-wrap .bopobb-item-change').hide()
                }
            }
        });

        if (is_empty || is_max) {
            $price.hide();
            $btn.addClass('bopobb-disabled');
            if (is_empty) {
                $alert.html(bopobbVars.alert_empty).slideDown();
                return;
            }
            if (is_max) {
                $alert.html(bopobbVars.alert_stock).slideDown();
                return;
            }
        } else {
            $price.show();
            $alert.html('').slideUp();
            $btn.removeClass('bopobb-disabled');
        }
    }

    function bopobb_save_ids($wrap) {
        let ids = Array(),
            $ids = $wrap.find('.bopobb-ids'),
            $atc_btn = $wrap.find('.single_add_to_cart_button'),
            $products = $wrap.find('.bopobb-detail-table');

        $products.find('.bopobb-item-product').each(function () {
            let $this = $(this);

            if ((
                parseInt($this.attr('data-id')) > 0
            ) && (
                parseFloat($this.attr('data-qty')) > 0
            )) {
                if ($this.find('.bopobb-item-detail .bopobb-item-variations').val()) {
                    ids.push($this.attr('data-id') + '/' + $this.attr('data-qty') + '/' + $this.find('.bopobb-item-detail .bopobb-item-variations').val());

                } else {
                    ids.push($this.attr('data-id') + '/' + $this.attr('data-qty'));
                }
            }
        });

        $ids.val(ids.join(','));
        $atc_btn.data('bopobb-ids', ids.join(','));

        $(document).trigger('bopobb_save_ids', [ids]);
    }

    function bopobbClosePopup() {
        bopobbEnableScroll();
        $('.bopobb-single-wrap').removeClass('bopobb-swap-active');
        $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-popup-title').html(bopobbVars.bopobb_popup_title);
        $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-product-list').bopobbSlideRight(0, 0);
        $('.bopobb-area .bopobb-inner .bopobb-popup .bopobb-variation-list').bopobbSlideRight(0, 0);
        $('.bopobb-area .bopobb-inner .bopobb-overlay').removeClass('bopobb-open');
        $('.bopobb-area .bopobb-inner .bopobb-popup').removeClass('bopobb-open');
        $('.bopobb-area .bopobb-inner .bopobb-popup-header .bopobb-btn-back').hide();
    }

    function bopobbApplyProduct(index = 0, item = '') {
        if (item) {
            // $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index).show();
            // $('.bopobb-single-wrap.bopobb-swap-active .bopobb-item-top.bopobb-item-' + index + ' .bopobb-item-change-wrap').css({
            //     'opacity': 1,
            //     'visibility': 'visible'
            // });

            let prod_img = item.html();
            let prod_regular_title = item.closest('.bopobb-product').find('.bopobb-product-title-wrap .bopobb-product-title').html();
            let prod_title = item.closest('.bopobb-product').find('.bopobb-product-title-wrap .bopobb-product-title').html();
            if (bopobbVars.bopobb_view_quantity) prod_title += ' x' + $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index).attr('data-qty');
            let prod_price_html = item.closest('.bopobb-product').find('.bopobb-product-price').html();
            let prod_price = item.closest('.bopobb-product').attr('data-price');
            let prod_id = item.closest('.bopobb-product').attr('data-product');
            let prod_stock_html = item.closest('.bopobb-product').find('.bopobb-product-stock').html();
            let prod_stock = item.closest('.bopobb-product').find('.bopobb-product-stock').attr('data-stock');
            let prod_ratting = item.closest('.bopobb-product').find('.bopobb-product-ratting').html();
            let prod_description = item.closest('.bopobb-product').find('.bopobb-product-description').html();
            let prod_variations = item.closest('.bopobb-product').find('.bopobb-product-variations').val() ? item.closest('.bopobb-product').find('.bopobb-product-variations').val() : '';
            if (bopobbVars.bopobb_link_individual == 1) {
                let prod_link = item.closest('.bopobb-product').find('.bopobb-product-title-wrap .bopobb-variation-title').length ?
                    item.closest('.bopobb-product').find('.bopobb-product-title-wrap .bopobb-variation-title').attr('href') :
                    item.closest('.bopobb-product').find('.bopobb-product-title-wrap .bopobb-product-title').attr('href');
                $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' .bopobb-item-title').attr('href', prod_link);
            }
            if ($('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap.bopobb-template-1').length) {
                $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-top-wrap .bopobb-item-' + index + ' .bopobb-item-img-wrap')
                    .attr('title', '').removeClass('bopobb-icon-plus2').css('min-height', 'unset');
                $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-top-wrap .bopobb-item-' + index + ' .bopobb-item-img')
                    .html(prod_img).attr('title', prod_regular_title.trim());
            } else {
                $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' .bopobb-item-img-wrap').attr('title', '');
                $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' .bopobb-item-img')
                    .html(prod_img).attr('title', prod_regular_title.trim()).css('min-height', 'unset');
            }
            $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' th .bopobb-item-detail .bopobb-item-title').html(prod_title).attr('title', prod_description);
            $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index)
                .attr('data-id', prod_id).attr('data-price', prod_price).attr('data-max', prod_stock);
            $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' td').html(prod_price_html);
            $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' th .bopobb-item-detail .stock').remove();
            $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' th .bopobb-item-detail').append(prod_stock_html);
            $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' th .bopobb-item-detail .star-rating').remove();
            $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' th .bopobb-item-detail').append(prod_ratting);
            $('.bopobb-single-wrap.bopobb-swap-active .bopobb-items-bottom-wrap .bopobb-item-' + index + ' th .bopobb-item-detail .bopobb-item-variations').val(prod_variations);
            $('.bopobb-single-wrap.bopobb-swap-active').addClass('bopobb-initial-process');

            bopobbClosePopup();
            bopobbInit($('.bopobb-single-wrap.bopobb-initial-process'), 'change');
        }
    }

    function bopobbDisableScroll() {
        if ($(document).height() > $(window).height()) {
            let scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop();
            $('html').addClass('bopobb-html-scroll').css('top', -scrollTop);
        }
    }

    function bopobbEnableScroll() {
        let scrollTop = parseInt($('html').css('top'));
        $('html').removeClass('bopobb-html-scroll');
        $('html,body').scrollTop(-scrollTop);
    }

    function bopobb_calc_price($container) {
        let total = 0;
        let total_sale = 0;
        let $wrap = $container.find('.bopobb-single-wrap');
        let wrap_id = $wrap.attr('data-id');
        let $products = $container.find('.bopobb-detail-table');
        let price_suffix = $products.attr('data-price-suffix');
        let $total = $container.find('.bopobb-total');
        let $woobt = $('.woobt-wrap-' + wrap_id);
        let total_woobt = parseFloat($woobt.length ? $woobt.attr('data-total') : 0);
        let discount = 0;
        let discount_amount = 0;
        let fixed_price = $products.attr('data-fixed-price');
        let fixed_sale = $products.attr('data-fixed-sale');
        let flex_no_discount = true;
        let saved = '';
        let fix = Math.pow(10, Number(bopobbVars.price_decimals) + 1);
        let tax_include = $container.find('.bopobb-detail-table').attr('data-tax-include');
        let tax_view = $container.find('.bopobb-detail-table').attr('data-tax-view');
        let tax_exempt = $container.find('.bopobb-detail-table').attr('data-tax-exempt');
        let tax_rate = parseFloat($container.find('.bopobb-detail-table').attr('data-tax-rate'));
        $products.find('.bopobb-item-product').each(function () {
            let $this = $(this);
            if (parseFloat($this.attr('data-price')) > 0) {
                let this_price = parseFloat($this.attr('data-price')) *
                    parseFloat($this.attr('data-qty'));
                let l_price = this_price;

                if (tax_view == 1 && tax_exempt == 0) {
                    total += this_price + (this_price * (tax_rate / 100));
                } else {
                    total += this_price;
                }
                discount = $this.attr('data-discount-type');
                discount_amount = parseFloat($this.attr('data-discount-number'));
                if (discount_amount != 0) flex_no_discount = false;

                if (discount === '0') {
                    this_price *= (100 - discount_amount) / 100;
                    this_price = Math.round(this_price * fix) / fix;
                } else if (discount === '1') {
                    this_price = discount_amount >= this_price ? 0 : this_price - discount_amount;
                    this_price = Math.round(this_price * fix) / fix;
                }
                if (tax_view == 1 && tax_exempt == 0) {
                    l_price += l_price * (tax_rate / 100);
                    this_price += this_price * (tax_rate / 100);
                }
                // local price
                let l_total_html = '';
                if (discount_amount != 0) {
                    l_total_html = bopobb_price_html(l_price, this_price);
                } else {
                    l_total_html = bopobb_price_html(l_price, l_price);
                }
                $(this).find('td').html(l_total_html);

                total_sale += this_price;
            }
        });
        // fix js number https://www.w3schools.com/js/js_numbers.asp
        total = bopobb_round(total, bopobbVars.price_decimals);

        if (fixed_price != 0) {
            total_sale = parseFloat($products.attr('data-fixed-sale') !== '' ? $products.attr('data-fixed-sale') : 0);
            saved = bopobb_format_price(total_sale - fixed_sale);
        } else {
            if (!flex_no_discount) {
                let total_sale_amount = total - total_sale;
                saved = bopobb_format_price(total_sale_amount);
            } else {
                total_sale = total;
            }
        }

        let total_html = bopobb_price_html(total, total_sale);

        if (saved != '') {
            total_html += ' <small class="woocommerce-price-suffix">' +
                bopobbVars.saved_text.replace('[d]', saved) + '</small>';
        }

        let price_selector = '.price';

        if ((fixed_price == 0)) {
            // change the main price
            $container.find(price_selector).html(total_html + price_suffix);
        }

        $(document).trigger('bopobb_calc_price',
            [total_sale, total, total_html, price_suffix]);
    }

    function bopobb_change_price($product, price, regular_price, price_html) {
        let $products = $product.closest('.bopobb-products');
        let price_suffix = $product.attr('data-price-suffix');

        // hide ori price
        $product.find('.bopobb-price-ori').hide();

        // calculate new price
        if (bopobbVars.bundled_price === 'subtotal') {
            let ori_price = parseFloat(price) *
                parseFloat($product.attr('data-qty'));

            if (bopobbVars.bundled_price_from === 'regular_price' &&
                regular_price !== undefined) {
                ori_price = parseFloat(regular_price) *
                    parseFloat($product.attr('data-qty'));
            }

            let new_price = ori_price;

            if (parseFloat($products.attr('data-discount')) > 0) {
                new_price = ori_price *
                    (100 - parseFloat($products.attr('data-discount'))) / 100;
            }

            $product.find('.bopobb-price-new').html(bopobb_price_html(ori_price, new_price) + price_suffix).show();
        } else {
            if (parseFloat($products.attr('data-discount')) > 0) {
                let ori_price = parseFloat(price);

                if (bopobbVars.bundled_price_from === 'regular_price' &&
                    regular_price !== undefined) {
                    ori_price = parseFloat(regular_price);
                }

                let new_price = ori_price *
                    (100 - parseFloat($products.attr('data-discount'))) / 100;
                $product.find('.bopobb-price-new').html(bopobb_price_html(ori_price, new_price) + price_suffix).show();
            } else {
                if (bopobbVars.bundled_price_from === 'regular_price' &&
                    regular_price !== undefined) {
                    $product.find('.bopobb-price-new').html(bopobb_price_html(regular_price) + price_suffix).show();
                } else if (price_html !== '') {
                    $product.find('.bopobb-price-new').html(price_html).show();
                }
            }
        }
    }

    function bopobb_price_html(regular_price, sale_price) {
        let price_html = '';

        if (sale_price < regular_price) {
            price_html = '<del>' + bopobb_format_price(regular_price) + '</del> <ins>' +
                bopobb_format_price(sale_price) + '</ins>';
        } else {
            price_html = bopobb_format_price(regular_price);
        }

        return price_html;
    }

    function bopobb_format_price(price) {
        let price_html = '<span class="woocommerce-Price-amount amount">';
        let price_formatted = bopobb_format_money(price, bopobbVars.price_decimals, '',
            bopobbVars.price_thousand_separator, bopobbVars.price_decimal_separator);

        switch (bopobbVars.price_format) {
            case '%1$s%2$s':
                //left
                price_html += '<span class="woocommerce-Price-currencySymbol">' +
                    bopobbVars.currency_symbol + '</span>' + price_formatted;
                break;
            case '%1$s %2$s':
                //left with space
                price_html += '<span class="woocommerce-Price-currencySymbol">' +
                    bopobbVars.currency_symbol + '</span> ' + price_formatted;
                break;
            case '%2$s%1$s':
                //right
                price_html += price_formatted +
                    '<span class="woocommerce-Price-currencySymbol">' +
                    bopobbVars.currency_symbol + '</span>';
                break;
            case '%2$s %1$s':
                //right with space
                price_html += price_formatted +
                    ' <span class="woocommerce-Price-currencySymbol">' +
                    bopobbVars.currency_symbol + '</span>';
                break;
            default:
                //default
                price_html += '<span class="woocommerce-Price-currencySymbol">' +
                    bopobbVars.currency_symbol + '</span> ' + price_formatted;
        }

        price_html += '</span>';

        return price_html;
    }

    function bopobb_format_money(number, places, symbol, thousand, decimal) {
        number = number || 0;
        places = !isNaN(places = Math.abs(places)) ? places : 2;
        symbol = symbol !== undefined ? symbol : '$';
        thousand = thousand || ',';
        decimal = decimal || '.';

        let negative = number < 0 ? '-' : '',
            i = parseInt(
                number = bopobb_round(Math.abs(+number || 0), places).toFixed(places),
                10) + '',
            j = 0;

        if (i.length > 3) {
            j = i.length % 3;
        }

        return symbol + negative + (
            j ? i.substr(0, j) + thousand : ''
        ) + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousand) + (
            places ?
                decimal +
                bopobb_round(Math.abs(number - i), places).toFixed(places).slice(2) :
                ''
        );
    }

    function bopobb_round(value, decimals) {
        return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
    }

    $.fn.bopobbfadeSlideRight = function (speed, fn) {
        return $(this).animate({
            'opacity': 1,
            'width': '750px'
        }, speed || 400, function () {
            $.isFunction(fn) && fn.call(this);
        });
    };

    $.fn.bopobbfadeSlideLeft = function (speed, fn) {
        return $(this).animate({
            'opacity': 0,
            'width': '0px'
        }, speed || 400, function () {
            $.isFunction(fn) && fn.call(this);
        });
    };

    $.fn.bopobbSlideLeft = function (speed, opacity, left, fn) {
        if (opacity == 0) $(this).css('visibility', 'hidden'); else $(this).css('visibility', 'visible');
        return $(this).animate({
            'opacity': opacity,
            'left': '-' + left + 'px'
        }, speed || 400, function () {
            $.isFunction(fn) && fn.call(this);
        });
    };

    $.fn.bopobbSlideRight = function (speed, opacity, fn) {
        if (opacity == 0) $(this).css('visibility', 'hidden'); else $(this).css('visibility', 'visible');
        return $(this).animate({
            'opacity': opacity,
            'left': '0'
        }, speed || 400, function () {
            $.isFunction(fn) && fn.call(this);
        });
    };
});