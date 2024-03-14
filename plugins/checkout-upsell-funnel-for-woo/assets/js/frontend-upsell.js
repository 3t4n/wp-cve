var viwcuf_countdown, viwcuf_count_time, viwcuf_atc = [],viwcuf_remove_cart_item=[];
jQuery( function( $ ) {
    $(window).on('load', function () {
        'use strict';
        if (!viwcuf_frontend_us_params) {
            return false;
        }
        let upsell_funnel = viwcuf_upsell_funnel;
        upsell_funnel.wc_ajax_url = viwcuf_frontend_us_params.wc_ajax_url;
        upsell_funnel.checkout_url = viwcuf_frontend_us_params.checkout_url;
        upsell_funnel.is_redirect_page = viwcuf_frontend_us_params.is_redirect_page;
        upsell_funnel.is_popup = viwcuf_frontend_us_params.is_popup;
        upsell_funnel.position = viwcuf_frontend_us_params.position;
        upsell_funnel.limit_rows = viwcuf_frontend_us_params.limit_rows;
        upsell_funnel.pd_hide_after_atc = viwcuf_frontend_us_params.pd_hide_after_atc;
        upsell_funnel.i18n_unavailable_text = viwcuf_frontend_us_params.i18n_unavailable_text || 'Sorry, this product is unavailable. Please choose a different combination.';
        upsell_funnel.i18n_make_a_selection_text = viwcuf_frontend_us_params.i18n_make_a_selection_text || 'Please select some product options before adding {product_name} to your cart.';
        upsell_funnel.i18n_quantity_error = viwcuf_frontend_us_params.i18n_quantity_error || 'The maximum number of {product_name} quantity is {pd_limit_quantity}';
        upsell_funnel.$wrap = $('.viwcuf-checkout-funnel-container');
        upsell_funnel.$checkout_form = $('form.checkout');
        upsell_funnel.init();
    });
    var viwcuf_upsell_funnel = {
        wc_ajax_url: '',
        checkout_url: '',
        is_redirect_page: '',
        limit_rows: '',
        pd_hide_after_atc: '',
        i18n_unavailable_text: '',
        i18n_make_a_selection_text: '',
        i18n_quantity_error: '',
        is_popup: '',
        position: '',
        $supports_html5_storage: ('sessionStorage' in window && window.sessionStorage !== null),
        $wrap: $('.viwcuf-checkout-funnel-container'),
        $checkout_form: $('form.checkout'),
        init: function () {
            let self = this;
            if (self.$wrap.find('.vi-wcuf-us-shortcode-wrap').length) {
                self.design();
            }
            if (!self.is_popup) {
                $(document).on('click', '#place_order', function (e) {
                    self.$wrap.remove();
                });
            }
            if (!self.is_redirect_page && self.is_popup) {
                if (!$('.viwcuf_us_position').length) {
                    self.$checkout_form.append('<input type="hidden" name="viwcuf_us_position" class="viwcuf_us_position" value="' + self.position + '">');
                }
                if (self.$checkout_form.find('#payment_method_twocheckout_inline').length) {
                    self.$checkout_form.on('click', '#place_order', function () {
                        if (self.position === 'footer') {
                            self.$checkout_form.find('.viwcuf_us_position').remove();
                            self.remove_session();
                        } else {
                            self.create_session();
                        }
                    });
                }
                self.$checkout_form.on('submit', function () {
                    if (!self.$wrap.hasClass('viwcuf-checkout-funnel-container-popup-init')) {
                        self.create_session();
                    }
                });
            }
            $(document.body).on('updated_checkout', function (e, data) {
                self.$wrap = $('.viwcuf-checkout-funnel-container');
                self.$checkout_form = $('form.checkout');
                if (self.$wrap.find('.vi-wcuf-us-shortcode-wrap').length) {
                    self.design();
                }
                self.$wrap.find('.vi-wcuf-us-product-bt-atc-loading').removeClass('vi-wcuf-us-product-bt-atc-loading');
                self.$wrap.find('.vi-wcuf-product-loading').removeClass('vi-wcuf-product-loading');
            });
            $(document.body).on('checkout_error', function (e, error_message) {
                $('.vi-wcaio-sidebar-cart-overlay, .vi-wcaio-sidebar-cart-close-wrap').removeClass('vi-wcaio-not-hidden');
                $('.vi-wcuf-loading-wrap').addClass('vi-wcuf-disable');
                if (self.is_redirect_page || self.$wrap.hasClass('viwcuf-checkout-funnel-container-popup-init')) {
                    $('html, body').stop();
                    $.ajax({
                        type: 'post',
                        url: self.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcuf_us_set_session'),
                        data: {
                            time_end: 1,
                            viwcuf_nonce: viwcuf_frontend_us_params.nonce,
                            error_message: error_message ? error_message : $('.woocommerce-error, .woocommerce-message, .woocommerce-NoticeGroup-checkout').html()
                        },
                        complete: function () {
                            if (self.is_redirect_page) {
                                // window.location = self.checkout_url;
                            }
                        }
                    });
                }
                let viwcuf_data = $('[data-viwcufenable="yes"]').data('viwcufdata');
                if (!viwcuf_data && error_message) {
                    let $reg = /data-viwcufdata=\"(.*?)\"/g;
                    viwcuf_data = $reg.exec(error_message);
                    if (viwcuf_data !== null) {
                        viwcuf_data = viwcuf_data[1].replace(/&quot;/g, '"');
                        viwcuf_data = JSON.parse(viwcuf_data);
                    }
                }
                if (!viwcuf_data) {
                    self.remove_session();
                    return false;
                }
                $('html, body').stop();
                if (!viwcuf_data.product_ids) {
                    self.remove_session();
                    self.$wrap.trigger('vi_wcuf_continue_checkout');
                    return false;
                }
                if (viwcuf_data.is_popup) {
                    if (viwcuf_data.popup_html) {
                        $('.viwcuf-checkout-us-container').html(viwcuf_data.popup_html);
                        self.$wrap = $('.viwcuf-checkout-funnel-container');
                        self.$checkout_form = $('form.checkout');
                        self.$checkout_form.find('#woocommerce-process-checkout-nonce').replaceWith(viwcuf_data.wc_process_checkout_nonce);
                        self.$wrap.addClass('viwcuf-checkout-funnel-container-popup-init').removeClass('vi-wcuf-disable');
                        // self.$wrap.find('.viwcuf-checkout-funnel-overlay').before(viwcuf_data.popup_html);
                        self.design();
                    } else {
                        self.remove_session();
                        self.$wrap.trigger('vi_wcuf_continue_checkout');
                    }
                    return false;
                }
                let redirect_url = viwcuf_data.redirect_url;
                if (!redirect_url) {
                    self.remove_session();
                    self.$wrap.trigger('vi_wcuf_continue_checkout');
                } else {
                    if (!self.$supports_html5_storage || !sessionStorage.getItem('viwcuf_upsell_funnel_session')) {
                        self.$wrap.trigger('vi_wcuf_continue_checkout');
                        return false;
                    }
                    redirect_url = redirect_url.replace(/&amp;/gm, '&').replace(/&#38;/gm, '&');
                    self.$checkout_form.addClass('processing').block({
                        message: null,
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.6
                        }
                    });
                    window.location = redirect_url;
                }
                return false;
            });
            //event of template 2
            $(document).on('change', '.vi-wcuf-us-swatches-popup-wrap .viwcuf_us_product_qty', function (e) {
                e.preventDefault();
                e.stopPropagation();
                let new_val, val = parseFloat($(this).val() || 0),
                    min = parseFloat($(this).attr('min') || 0),
                    max = parseFloat($(this).parent().find('.viwcuf_us_qty_available').val() || 0);
                new_val = val;
                if (min > val) {
                    new_val = min;
                }
                if (max > -1 && max < val) {
                    new_val = max;
                }
                $(this).val(new_val);
            });
            $(document).on('click', '.vi-wcuf-us-swatches-popup-overlay', function () {
                $('.vi-wcuf-us-swatches-popup-wrap-wrap .vi-wcuf-swatches-control-footer-bt-cancel').trigger('click');
            });
            $(document).on('click', '.vi-wcuf-swatches-control-footer-bt-cancel', function () {
                if (self.position === 'footer') {
                    $('.viwcuf-checkout-funnel-container-popup-init').animate({left: '0'}, 500);
                    $('.vi-wcuf-us-swatches-popup-wrap-wrap').animate({left: '100%'}, 500, function () {
                        $('.vi-wcuf-us-swatches-popup-wrap-wrap').remove();
                    });
                } else {
                    $('.vi-wcuf-us-swatches-popup-wrap-wrap').remove();
                }
                let continue_countdown = $(this).hasClass('vi-wcuf-swatches-control-footer-bt-cancel-pause_countdown');
                $('html').removeClass('viwcuf-html-non-scroll');
                $('.vi-wcuf-us-product-editing').removeClass('vi-wcuf-us-product-editing');
                $('.vi-wcuf-swatches-selected-wrap-clicking').removeClass('vi-wcuf-swatches-selected-wrap-clicking');
                if (!continue_countdown) {
                    self.run_countdown(viwcuf_count_time);
                }
            });
            $(document).on('click', '.vi-wcuf-swatches-control-footer-bt-ok:not(.vi-wcuf-us-product-bt-atc-loading)', function (e) {
                e.stopPropagation();
                e.preventDefault();
                let button = $(this),
                    form = $(this).closest('.vi-wcuf-us-cart-form');
                if (button.hasClass('vi-wcuf-button-atc-disable-qty')) {
                    self.show_message(self.i18n_quantity_error.replace('{product_name}', form.find('.viwcuf_us_qty_available').data('product_name')).replace('{pd_limit_quantity}', form.find('.viwcuf_us_qty_available').data('limit_quantity')));
                    form.find('.viwcuf_us_product_qty').val(0);
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                let check_attribute = true;
                form.find('.viwcuf-attribute-options').each(function (k, item) {
                    if (!$(item).val()) {
                        check_attribute = false;
                        return false;
                    }
                });
                if (!check_attribute) {
                    self.show_message(self.i18n_make_a_selection_text.replace('{product_name}', form.find('.viwcuf_us_qty_available').data('product_name')));
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                if (button.hasClass('vi-wcuf-button-swatches-disable') || button.hasClass('vi-wcuf-button-atc-disable')) {
                    self.show_message(self.i18n_unavailable_text);
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                let qty = parseFloat(form.find('.viwcuf_us_product_qty').val()),
                    limit_qty = parseFloat(form.find('.viwcuf_us_qty_available').val());
                if (!limit_qty || (qty > limit_qty && limit_qty > -1)) {
                    self.show_message(self.i18n_quantity_error.replace('{product_name}', form.find('.viwcuf_us_qty_available').data('product_name')).replace('{pd_limit_quantity}', form.find('.viwcuf_us_qty_available').data('limit_quantity')));
                    form.find('.viwcuf_us_product_qty').val(1);
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                $('.vi-wcuf-swatches-selected-wrap.vi-wcuf-swatches-selected-wrap-clicking span').html(viwcuf_upsell_funnel.get_attribute_selected(form));
                button.addClass('vi-wcuf-us-product-bt-atc-loading');
                self.add_to_cart(button, limit_qty, qty, form, 'btn_popup');
            });
            $(document).on('click', '.vi_wcuf_us_change_qty', function (e) {
                e.preventDefault();
                e.stopPropagation();
                let qty_input = $(this).closest('.vi-wcuf-us-quantity-wrap').find('.viwcuf_us_product_qty');
                let val = parseFloat(qty_input.val()),
                    step = parseFloat(qty_input.attr('step'));
                if ($(this).hasClass('vi_wcuf_us_plus')) {
                    val += step;
                } else {
                    val -= step;
                }
                qty_input.val(val).trigger('change');
            });
        },
        init_events:function (){
            let self = this;
            self.$wrap.on('vi_wcuf_continue_checkout', self.continue_checkout);
            self.$wrap.on('click', '.vi-wcuf-us-shortcode-bt-continue-wrap', function (e) {
                e.stopPropagation();
                e.preventDefault();
                clearInterval(viwcuf_countdown);
                self.$wrap.trigger('vi_wcuf_continue_checkout');
            });
            self.$wrap.on('click', '.vi-wcuf-us-shortcode-bt-pause-wrap', function () {
                clearInterval(viwcuf_countdown);
                self.$wrap.find('.vi-wcuf-us-shortcode-countdown-wrap').addClass('viwcuf-countdown-pausing').addClass('viwcuf-hidden');
                $.ajax({
                    type: 'post',
                    url: self.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcuf_us_set_session'),
                    data: {
                        time_pause: 1,
                        viwcuf_nonce: viwcuf_frontend_us_params.nonce,
                    }
                });
            });
            //event of template 2
            self.$wrap.on('click', '.vi-wcuf-us-checkbox.vi-wcuf-us-product-bt-atc:not(.vi-wcuf-us-product-bt-atc-loading)', function (e) {
                clearInterval(viwcuf_countdown);
                let button = $(this);
                let product = button.closest('.vi-wcuf-us-product');
                if (button.hasClass('vi-wcuf-us-checkbox-checked')) {
                    button.addClass('vi-wcuf-us-product-bt-atc-loading');
                    self.remove_form_cart(button, product);
                    return false;
                }
                if (product.find('.vi-wcuf-cart-form-swatches').length) {
                    product.find('.vi-wcuf-swatches-selected-wrap').trigger('click');
                    return false;
                }
                let form = product.find('.vi-wcuf-us-cart-form');
                if (button.hasClass('vi-wcuf-button-atc-disable-qty')) {
                    self.show_message(self.i18n_quantity_error.replace('{product_name}', form.find('.viwcuf_us_qty_available').data('product_name')).replace('{pd_limit_quantity}', form.find('.viwcuf_us_qty_available').data('limit_quantity')));
                    form.find('.viwcuf_us_product_qty').val(0);
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                if (button.hasClass('vi-wcuf-button-swatches-disable') || button.hasClass('vi-wcuf-button-atc-disable')) {
                    self.show_message(self.i18n_unavailable_text);
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                let qty = parseFloat(form.find('.viwcuf_us_product_qty').val()),
                    limit_qty = parseFloat(form.find('.viwcuf_us_qty_available').val());
                if (!limit_qty || (limit_qty > -1 && qty > limit_qty)) {
                    self.show_message(self.i18n_quantity_error.replace('{product_name}', form.find('.viwcuf_us_qty_available').data('product_name')).replace('{pd_limit_quantity}', form.find('.viwcuf_us_qty_available').data('limit_quantity')));
                    form.find('.viwcuf_us_product_qty').val(1);
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                button.addClass('vi-wcuf-us-product-bt-atc-loading');
                self.add_to_cart(button, limit_qty, qty, form, 'checkbox');
            });
            self.$wrap.on('click', '.vi-wcuf-swatches-selected-wrap:not(.vi-wcuf-swatches-selected-wrap-clicking):not(.vi-wcuf-swatches-selected-wrap-not-click)', function (e) {
                e.stopPropagation();
                e.preventDefault();
                clearInterval(viwcuf_countdown);
                $(this).addClass('vi-wcuf-swatches-selected-wrap-clicking');
                let product = $(this).closest('.vi-wcuf-us-product');
                product.addClass('vi-wcuf-us-product-editing');
                $('html').addClass('viwcuf-html-non-scroll');
                $('body').append('<div class="vi-wcuf-us-swatches-popup-wrap-wrap"><div class="vi-wcuf-us-swatches-popup-wrap"></div><div class="vi-wcuf-us-swatches-popup-overlay"></div></div>');
                let popup_swatches = $('.vi-wcuf-us-swatches-popup-wrap');
                if (self.position === 'footer') {
                    popup_swatches.parent().css({left: '100%'});
                }
                if (self.$wrap.hasClass('viwcuf-checkout-funnel-container-rtl')) {
                    popup_swatches.addClass('vi-wcuf-us-swatches-popup-wrap-rtl')
                }
                product.find('.vi-wcuf-us-cart-form').clone().appendTo(popup_swatches);
                popup_swatches.find('.vi-wcuf-us-cart-form').append('<span class="vi-wcuf-swatches-control-footer-bt-cancel">x</span>');
                popup_swatches.find('.vi-wcuf-swatches-selected-wrap').remove();
                popup_swatches.find('.vi-wcuf-swatches-control-wrap-wrap').removeClass('vi-wcuf-disable');
                if (popup_swatches.find('.viwcuf_us_product_qty[type="hidden"]').length) {
                    popup_swatches.find('.vi-wcuf-swatches-control-content-quantity').addClass('vi-wcuf-disable');
                }
                popup_swatches.find('.vi-wcuf-cart-form-swatches:not(.vi-wcuf-cart-form-swatches-init)').each(function () {
                    $(this).find('select').each(function (k, v) {
                        $(v).val($(v).closest('.vi-wcuf-swatches-value').data('selected') || $(v).closest('.vi-wcuf-swatches-value').find('.vs-option-wrap-selected').data('attribute_value') || '');
                    });
                    $(this).addClass('vi-wcuf-cart-form-swatches-init vi_wpvs_variation_form').viwcuf_get_variations({wc_ajax_url: viwcuf_upsell_funnel.wc_ajax_url});
                    // Babystreet theme of theAlThemist
                    if ($(this).find('.babystreet-wcs-swatches').length) {
                        $(this).babystreet_wcs_variation_swatches_form();
                    }
                });
                // WooCommerce Product Variations Swatches plugin of VillaTheme
                $(document.body).trigger('vi_wpvs_variation_form');
                if (self.position === 'footer') {
                    $('.viwcuf-checkout-funnel-container-popup-init').animate({left: '-100%'}, 500);
                    popup_swatches.parent().animate({left: '0'}, 500);
                }
            });
            //event of template 1
            self.$wrap.on('change', '.viwcuf_us_product_qty', function () {
                let new_val, val = parseFloat($(this).val() || 0),
                    min = parseFloat($(this).attr('min') || 0),
                    max = parseFloat($(this).parent().find('.viwcuf_us_qty_available').val() || 0);
                new_val = val;
                if (min > val) {
                    new_val = min;
                }
                if (max > -1 && max < val) {
                    new_val = max;
                }
                $(this).val(new_val);
            });
            self.$wrap.on('click', '.vi-wcuf-us-product-bt-remove:not(.vi-wcuf-us-product-bt-atc-loading)', function (e) {
                e.stopPropagation();
                e.preventDefault();
                clearInterval(viwcuf_countdown);
                self.remove_form_cart($(this), $(this).closest('.vi-wcuf-us-product-wrap-wrap'));
            });
            self.$wrap.on('click', '.vi-wcuf-us-product-bt-atc:not(.vi-wcuf-us-checkbox):not(.vi-wcuf-us-product-bt-atc-loading)', function (e) {
                e.stopPropagation();
                e.preventDefault();
                clearInterval(viwcuf_countdown);
                let button = $(this),
                    form = $(this).closest('.vi-wcuf-us-cart-form');
                if (button.hasClass('vi-wcuf-button-atc-disable-qty')) {
                    self.show_message(self.i18n_quantity_error.replace('{product_name}', form.find('.viwcuf_us_qty_available').data('product_name')).replace('{pd_limit_quantity}', form.find('.viwcuf_us_qty_available').data('limit_quantity')));
                    form.find('.viwcuf_us_product_qty').val(0);
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                let check_attribute = true;
                form.find('.viwcuf-attribute-options').each(function (k, item) {
                    if (!$(item).val()) {
                        check_attribute = false;
                        return false;
                    }
                });
                if (!check_attribute) {
                    self.show_message(self.i18n_make_a_selection_text.replace('{product_name}', form.find('.viwcuf_us_qty_available').data('product_name')));
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                if (button.hasClass('vi-wcuf-button-swatches-disable') || button.hasClass('vi-wcuf-button-atc-disable')) {
                    self.show_message(self.i18n_unavailable_text);
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                let qty = parseFloat(form.find('.viwcuf_us_product_qty').val()),
                    limit_qty = parseFloat(form.find('.viwcuf_us_qty_available').val());
                if (!limit_qty || (limit_qty > -1 && qty > limit_qty)) {
                    self.show_message(self.i18n_quantity_error.replace('{product_name}', form.find('.viwcuf_us_qty_available').data('product_name')).replace('{pd_limit_quantity}', form.find('.viwcuf_us_qty_available').data('limit_quantity')));
                    form.find('.viwcuf_us_product_qty').val(1);
                    self.run_countdown(viwcuf_count_time);
                    return false;
                }
                button.addClass('vi-wcuf-us-product-bt-atc-loading');
                self.add_to_cart(button, limit_qty, qty, form);
            });
            self.$wrap.on('click', '.vi-wcuf-us-shortcode-bt-alltc:not(.vi-wcuf-us-shortcode-bt-alltc-loading)', function (e) {
                e.stopPropagation();
                e.preventDefault();
                let error, data = [], products = self.$wrap.find('.vi-wcuf-us-product-wrap-wrap');
                products.each(function (k, v) {
                    let qty = parseFloat($(v).find('.viwcuf_us_quantity').val() || 0),
                        limit_qty = parseFloat($(v).find('.viwcuf_us_qty_available').val() || 0);
                    if (limit_qty > -1 && limit_qty < 1) {
                        $(v).find('.vi-wcuf-us-product-bt-atc').addClass('vi-wcuf-button-atc-disable-qty');
                        $(v).find('select.viwcuf-attribute-options').addClass('viwcuf-attribute-options-check');
                    } else if (limit_qty > -1 && qty > limit_qty) {
                        error = self.i18n_quantity_error.replace('{product_name}', $(v).find('.viwcuf_us_qty_available').data('product_name')).replace('{pd_limit_quantity}', $(v).find('.viwcuf_us_qty_available').data('limit_quantity'));
                    }
                });
                if (error) {
                    self.show_message(error);
                    return false;
                }
                products.find('.viwcuf-attribute-options:not(.viwcuf-attribute-options-check)').each(function (k, v) {
                    if (!$(v).val()) {
                        error = self.i18n_make_a_selection_text.replace('{product_name}', $(v).closest('.vi-wcuf-us-product').find('.woocommerce-loop-product__title').text());
                        return false;
                    }
                });
                if (error) {
                    self.show_message(error);
                    return false;
                }
                let button = $(this),
                    buttons = self.$wrap.find('.vi-wcuf-us-product-bt-atc:not(.vi-wcuf-button-atc-disable-qty):not(.vi-wcuf-button-swatches-disable):not(.vi-wcuf-button-atc-disable)');
                if (buttons.length) {
                    clearInterval(viwcuf_countdown);
                    button.addClass('vi-wcuf-us-shortcode-bt-alltc-loading');
                    self.$wrap.find('.vi-wcuf-loading-wrap').removeClass('vi-wcuf-disable');
                    buttons.each(function (k, v) {
                        let form = $(v).closest('.vi-wcuf-us-cart-form');
                        form.find('.vi-wcuf-add-to-cart, .vi-wcuf-product_id').val(form.data('product_id') || 0);
                        data[k] = form.find('select, input').serializeArray();
                    });
                    $.ajax({
                        type: 'POST',
                        url: self.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcuf_us_add_all_to_cart'),
                        data: {
                            viwcuf_us_alltc: data,
                            viwcuf_nonce: viwcuf_frontend_us_params.nonce,
                        },
                        success: function (response) {
                            if (response.status === 'error') {
                                if (response.message) {
                                    self.show_message(response.message);
                                    return false;
                                }
                            }
                            $(document.body).trigger('vi_wcuf_added_all_to_cart', [response.fragments, response.cart_hash, button]);
                            $(document.body).trigger('update_checkout', {update_shipping_method: false});
                            if (self.is_popup) {
                                self.$wrap.find('.vi-wcuf-us-shortcode-bt-continue-wrap').trigger('click');
                            } else {
                                if (!viwcuf_upsell_funnel.pd_hide_after_atc) {
                                    button.each(function (k, v) {
                                        let product = $(v).closest('.vi-wcuf-us-product-wrap-wrap');
                                        let qty = parseFloat(product.find('.viwcuf_us_product_qty').val()) || 0,
                                            limit_qty = parseFloat(product.find('.viwcuf_us_qty_available').val()) || 0;
                                        if (limit_qty > -1) {
                                            let available_qty = limit_qty > qty ? limit_qty - qty : 0;
                                            product.find('.viwcuf_us_qty_available').val(available_qty);
                                            if (available_qty > 0) {
                                                product.find('.viwcuf_us_product_qty').attr('max', available_qty);
                                            } else {
                                                product.find('.viwcuf_us_product_qty').attr('type', 'hidden');
                                                $(v).addClass('vi-wcuf-button-atc-disable-qty');
                                            }
                                        }
                                    });
                                    button.removeClass('vi-wcuf-us-shortcode-bt-alltc-loading');
                                    self.$wrap.find('.vi-wcuf-loading-wrap').addClass('vi-wcuf-disable');
                                    self.$wrap.find('.vi-wcuf-add-to-cart, .vi-wcuf-product_id').val('');
                                    self.run_countdown(viwcuf_count_time);
                                }
                            }
                        }
                    });
                }
                return false;
            });
            self.$wrap.on('click', '.vi-wcuf-us-product-not-redirect .vi-wcuf-us-product-top, .vi-wcuf-us-product-not-redirect .vi-wcuf-us-product-desc', function (e) {
                e.stopPropagation();
                e.preventDefault();
                let product = $(this).closest('.vi-wcuf-us-product');
                product.find('.vi-wcuf-us-product-bt-atc').trigger('click');
            });
        },
        remove_form_cart: function (button, product) {
            button = $(button);
            product = $(product);
            let cart_item = product.find('.vi-wcuf-us-cart-item-info');
            button.removeClass('vi-wcuf-us-checkbox-checked');
            let data = {
                viwcuf_nonce: viwcuf_frontend_us_params.nonce,
                product_id: product.data('product_id') || cart_item.data('added_id'),
                product_type: 'viwcuf_us_product',
                cart_item_key: cart_item.data('cart_item_key')
            };
            viwcuf_remove_cart_item.push({
                type: 'post',
                url: viwcuf_upsell_funnel.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcuf_remove_form_cart'),
                data: data,
                beforeSend: function () {
                    product.addClass('vi-wcuf-product-loading');
                },
                success: function (response) {
                    if (!response || response.error) {
                        if (response.message) {
                            viwcuf_upsell_funnel.show_message(response.message);
                        }
                        viwcuf_remove_cart_item = [];
                        viwcuf_upsell_funnel.run_countdown(viwcuf_count_time);
                        return false;
                    } else {
                        if (product.hasClass('vi-wcuf-us-product-added')) {
                            let max = product.find('.viwcuf_us_product_qty').attr('max') ? parseFloat(product.find('.viwcuf_us_product_qty').attr('max')) : '',
                                count_added = parseFloat(product.find('.vi-wcuf-us-cart-item-info').data('count_added') || 0),
                                qty_available = parseFloat(product.find('.viwcuf_us_qty_available').val() || 0);
                            if (qty_available > -1) {
                                product.find('.viwcuf_us_qty_available').val(qty_available + count_added);
                            }
                            if (max && max > -1) {
                                product.find('.viwcuf_us_product_qty').attr('max', max + count_added);
                            }
                            if ((qty_available + count_added) > 1) {
                                product.find('.viwcuf_us_product_qty').attr('type', 'number');
                            }
                            product.find('.vi-wcuf-us-cart-item-info').data({'count_added': 0, 'cart_item_key': ''});
                            product.find('.vi-wcuf-us-product-bt-atc').removeClass('added');
                            product.removeClass('vi-wcuf-us-product-added').find('.vi-wcuf-button-atc-disable-qty').removeClass('vi-wcuf-button-atc-disable-qty');
                        } else {
                            if (product.find('.vi-wcuf-us-cart-item-info').length && product.find('.viwcuf_us_qty_available').val() != -1) {
                                let max = product.find('.viwcuf_us_product_qty').attr('max') ? parseFloat(product.find('.viwcuf_us_product_qty').attr('max')) : '',
                                    count_added = parseFloat(product.find('.vi-wcuf-us-cart-item-info').data('count_added')),
                                    qty_available = parseFloat(product.find('.viwcuf_us_qty_available').val() || 0);
                                if (qty_available > -1) {
                                    product.find('.viwcuf_us_qty_available').val(qty_available + count_added);
                                }
                                if (max && max > -1) {
                                    product.find('.viwcuf_us_product_qty').attr('max', max + count_added);
                                }
                                product.find('.vi-wcuf-us-cart-item-info').remove();
                            }
                            product.find('.vi-wcuf-swatches-selected-wrap span').html(viwcuf_upsell_funnel.get_attribute_selected(product));
                            product.find('.vi-wcuf-swatches-selected-wrap').removeClass('vi-wcuf-swatches-selected-wrap-not-click');
                            product.find('.vi-wcuf-us-quantity-wrap').removeClass('vi-wcuf-us-quantity-wrap-not-change');
                        }
                        product.find('.vi-wcuf-us-quantity-wrap').removeClass('vi-wcuf-us-quantity-wrap-not-change');
                        $(document.body).trigger('removed_from_cart', [response.fragments, response.cart_hash, button]);
                        $(document.body).trigger('vi_wcuf_removed_from_cart', [response.fragments, response.cart_hash, button]);
                        viwcuf_remove_cart_item.shift();
                        if (viwcuf_remove_cart_item.length > 0) {
                            $.ajax(viwcuf_remove_cart_item[0]);
                        } else if (viwcuf_atc.length > 0) {
                            return false;
                        } else {
                            viwcuf_upsell_funnel.run_countdown(viwcuf_count_time);
                            $(document.body).trigger('update_checkout', {update_shipping_method: false});
                        }
                    }
                },
                complete: function () {
                    product.removeClass('vi-wcuf-product-loading');
                },
            });
            if (viwcuf_remove_cart_item.length === 1) {
                $.ajax(viwcuf_remove_cart_item[0]);
            }
        },
        add_to_cart: function (button, limit_qty, qty, form, button_type = '') {
            button = $(button);
            form = $(form);
            form.find('.vi-wcuf-add-to-cart, .vi-wcuf-product_id').val(form.data('product_id') || 0);
            let product;
            switch (button_type) {
                case "btn_popup":
                    product = $('.vi-wcuf-swatches-selected-wrap.vi-wcuf-swatches-selected-wrap-clicking').closest('.vi-wcuf-us-product-wrap-wrap');
                    form.find('.vi-wcuf-swatches-control-footer-bt-cancel').addClass('vi-wcuf-swatches-control-footer-bt-cancel-pause_countdown').trigger('click');
                    break;
                default:
                    product = form.closest('.vi-wcuf-us-product-wrap-wrap');
            }
            let data = form.find('select, input').serialize();
            if (data.search('viwcuf_nonce') === -1) {
                data += '&viwcuf_nonce=' + viwcuf_frontend_us_params.nonce;
            }
            viwcuf_atc.push({
                type: 'post',
                url: viwcuf_upsell_funnel.wc_ajax_url.replace('%%endpoint%%', 'viwcuf_add_to_cart'),
                data: data,
                beforeSend: function (response) {
                    product.find('.vi-wcuf-us-checkbox').addClass('vi-wcuf-us-checkbox-checked vi-wcuf-us-product-bt-atc-loading');
                    form.addClass('vi-wcuf-product-loading').closest('.vi-wcuf-us-product').addClass('vi-wcuf-product-loading');
                },
                success: function (response) {
                    if (!response || response.error) {
                        product.find('.vi-wcuf-us-checkbox').removeClass('vi-wcuf-us-checkbox-checked');
                        if (response.message) {
                            viwcuf_upsell_funnel.show_message(response.message);
                        }
                        viwcuf_atc = [];
                        viwcuf_upsell_funnel.run_countdown(viwcuf_count_time);
                        return false;
                    } else {
                        if (response.message) {
                            viwcuf_upsell_funnel.show_message(response.message);
                        }
                        if (!viwcuf_upsell_funnel.pd_hide_after_atc) {
                            switch (button_type) {
                                case "checkbox":
                                    form.find('.vi-wcuf-us-quantity-wrap').addClass('vi-wcuf-us-quantity-wrap-not-change');
                                    form.find('.vi-wcuf-swatches-selected-wrap').addClass('vi-wcuf-swatches-selected-wrap-not-click');
                                    break;
                                case "btn_popup":
                                    product.find('.vi-wcuf-swatches-selected-wrap').addClass('vi-wcuf-swatches-selected-wrap-not-click');
                                    form.find('.vi-wcuf-swatches-control-footer-bt-cancel').addClass('vi-wcuf-swatches-control-footer-bt-cancel-pause_countdown').trigger('click');
                                    break;
                                default:
                                    form.closest('.vi-wcuf-us-product-wrap-wrap').addClass('vi-wcuf-us-product-added');
                                    form.find('.viwcuf_us_product_qty').attr('data-qty_added', qty);
                                    if (form.find('.vi-wcuf-us-cart-item-info').length) {
                                        form.find('.vi-wcuf-us-cart-item-info').data('count_added', parseFloat(form.find('.vi-wcuf-us-cart-item-info').data('count_added') || 0) + qty);
                                    } else {
                                        form.append('<span class="vi-wcuf-us-cart-item-info vi-wcuf-disable" data-added_id="' + form.data('product_id') + '" data-count_added="' + qty + '"></span>');
                                    }
                                    if (limit_qty > -1) {
                                        let qty_available = limit_qty > qty ? limit_qty - qty : 0;
                                        form.find('.viwcuf_us_qty_available').val(qty_available);
                                        if (qty_available) {
                                            form.find('.viwcuf_us_product_qty').attr('max', qty_available);
                                        } else {
                                            form.find('.viwcuf_us_product_qty').attr('type', 'hidden');
                                            button.addClass('vi-wcuf-button-atc-disable-qty');
                                        }
                                    }
                            }
                        }
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, button]);
                        $(document.body).trigger('vi_wcuf_added_to_cart', [response.fragments, response.cart_hash, button]);
                        viwcuf_atc.shift();
                        if (viwcuf_atc.length > 0) {
                            $.ajax(viwcuf_atc[0]);
                        } else if (typeof viwcuf_remove_cart_item !== 'undefined' && viwcuf_remove_cart_item.length > 0) {
                            if ($('.vi-wcuf-us-product-wrap-wrap').length) {
                                viwcuf_upsell_funnel.design(true);
                                viwcuf_upsell_funnel.run_countdown(viwcuf_count_time);
                            } else if (viwcuf_upsell_funnel.is_popup) {
                                viwcuf_upsell_funnel.$wrap.find('.vi-wcuf-us-shortcode-bt-continue-wrap').trigger('click');
                            } else {
                                viwcuf_upsell_funnel.$wrap.remove();
                            }
                        } else {
                            $(document.body).trigger('update_checkout', {update_shipping_method: false});
                            if ($('.vi-wcuf-us-product-wrap-wrap').length) {
                                viwcuf_upsell_funnel.design(true);
                                viwcuf_upsell_funnel.run_countdown(viwcuf_count_time);
                            } else if (viwcuf_upsell_funnel.is_popup) {
                                viwcuf_upsell_funnel.$wrap.find('.vi-wcuf-us-shortcode-bt-continue-wrap').trigger('click');
                            } else {
                                viwcuf_upsell_funnel.$wrap.remove();
                            }
                        }
                    }

                },
                error: function (err) {
                    viwcuf_upsell_funnel.show_message(err.statusText);
                    console.log(err);
                    product.find('.vi-wcuf-us-checkbox').removeClass('vi-wcuf-us-checkbox-checked');
                    form.find('.vi-wcuf-swatches-control-footer-bt-cancel').trigger('click');
                },
                complete: function (response) {
                    form.find('.vi-wcuf-add-to-cart, .vi-wcuf-product_id').val('');
                    form.removeClass('vi-wcuf-product-loading').closest('.vi-wcuf-us-product').removeClass('vi-wcuf-product-loading');
                },
            });
            if (viwcuf_atc.length === 1) {
                $.ajax(viwcuf_atc[0]);
            }
        },
        get_attribute_selected: function (wrap) {
            wrap = $(wrap);
            let attr_selected = [];
            wrap.find('select').each(function (k, v) {
                if ($(v).val()) {
                    attr_selected.push($(v).find('option:selected').html());
                }
            });
            return attr_selected.length ? attr_selected.join(',') : wrap.find('option[value=""]').first().html();
        },
        design: function (only_design = false) {
            let wrap = viwcuf_upsell_funnel.$wrap;
            let pd_width = wrap.find('.vi-wcuf-us-shortcode-content-wrap').outerWidth(),
                item_per_row = wrap.find('.vi-wcuf-us-shortcode-products-wrap').data('item_per_row') || 4,
                item_per_row_mobile = wrap.find('.vi-wcuf-us-shortcode-products-wrap').data('item_per_row_mobile') || 2;
            if (pd_width < 600 && pd_width >= 480) {
                item_per_row = 3;
            }
            if (pd_width < 480) {
                item_per_row = item_per_row_mobile;
            }
            if (wrap.hasClass('viwcuf-checkout-funnel-container-scroll')) {
                let limit_rows = viwcuf_upsell_funnel.limit_rows, limit_height,
                    item_width = 10 / item_per_row;
                wrap.find('.vi-wcuf-us-shortcode-products-wrap').css({'grid-template-columns': 'repeat(' + item_per_row + ',' + item_width + 'fr)'});
                if (limit_rows && limit_rows > 0) {
                    limit_height = wrap.find('.vi-wcuf-us-product-wrap-wrap').outerHeight();
                    limit_height = 10 + limit_height * limit_rows;
                    wrap.find('.vi-wcuf-us-shortcode-content-2').css({'max-height': limit_height + 'px'});
                }
            } else {
                let rtl = wrap.find('.vi-wcuf-us-shortcode-products-wrap').attr('data-rtl');
                if (parseInt(rtl)) {
                    rtl = true;
                } else {
                    rtl = false;
                }
                let itemWidth = (pd_width - 12 * item_per_row) / item_per_row;
                wrap.find('.vi-wcuf-us-shortcode-content-2').removeData("flexslider");
                wrap.find('.vi-wcuf-us-shortcode-content-2').viwcuf_flexslider({
                    namespace: "vi-wcuf_slider-",
                    selector: '.vi-wcuf-us-shortcode-products-wrap .vi-wcuf-us-product-wrap-wrap',
                    animation: "slide",
                    animationLoop: false,
                    itemWidth: itemWidth,
                    itemMargin: 12,
                    controlNav: false,
                    maxItems: item_per_row,
                    reverse: false,
                    slideshow: false,
                    rtl: rtl,
                }).addClass('vi-wcuf-us-shortcode-content-2-slider');
            }
            if (only_design) {
                return false;
            }
            wrap.find('.vi-wcuf-us-shortcode-element-wrap:not(.vi-wcuf-us-shortcode-element2-wrap) .vi-wcuf-us-shortcode-element').each(function (k, v) {
                if (!$(v).children().length) {
                    $(v).parent().remove();
                }
            });
            viwcuf_upsell_funnel.run_countdown();
            wrap.find('.vi-wcuf-us-cart-item-info').each(function () {
                let form = $(this).closest('.vi-wcuf-us-cart-form');
                form.addClass('vi-wcuf-us-form-checked');
                form.find('.vi-wcuf-us-checkbox').addClass('vi-wcuf-us-checkbox-checked');
                form.find('.vi-wcuf-us-quantity-wrap').addClass('vi-wcuf-us-quantity-wrap-not-change');
                form.find('.vi-wcuf-swatches-selected-wrap').addClass('vi-wcuf-swatches-selected-wrap-not-click');
                if ($(this).data('variation')){
                    form.find('.vi-wcuf-swatches-selected-wrap span').html(Object.values($(this).data('variation')).join(', '));
                }
            });
            wrap.find('.vi-wcuf-us-cart-form:not(.vi-wcuf-us-form-checked):not(.vi-wcuf-cart-form-swatches)').each(function () {
                let qty = $(this).find('.viwcuf_us_product_qty');
                let min = parseFloat(qty.attr('min') || 0),
                    max = parseFloat(qty.attr('max') || 0);
                if ((max > -1 && max < min) || max === 0) {
                    qty.attr('type', 'hidden');
                    $(this).find('.vi-wcuf-us-product-bt-atc').addClass('vi-wcuf-button-disable');
                }
            });
            wrap.find('.vi-wcuf-us-product-2 .vi-wcuf-us-cart-form:not(.vi-wcuf-cart-form-swatches) .viwcuf_us_product_qty').each(function () {
                if ($(this).attr('type') === 'hidden') {
                    $(this).closest('.vi-wcuf-us-product-2').addClass('vi-wcuf-us-product-2-qty-disable');
                }
            });
            wrap.find('.vi-wcuf-cart-form-swatches:not(.vi-wcuf-us-form-checked):not(.vi-wcuf-cart-form-swatches-init)').each(function () {
                $(this).find('select').each(function (k, v) {
                    $(v).val($(v).closest('.vi-wcuf-swatches-value').data('selected') || $(v).closest('.vi-wcuf-swatches-value').find('.vs-option-wrap-selected').data('attribute_value') || '');
                });
                if ($(this).find('.vi-wcuf-swatches-selected-wrap').length) {
                    let selected = viwcuf_upsell_funnel.get_attribute_selected($(this));
                    $(this).find('.vi-wcuf-swatches-selected-wrap span').html(selected);
                } else {
                    $(this).addClass('vi-wcuf-cart-form-swatches-init vi_wpvs_variation_form').viwcuf_get_variations({wc_ajax_url: viwcuf_upsell_funnel.wc_ajax_url});
                    // Babystreet theme of theAlThemist
                    if ($(this).find('.babystreet-wcs-swatches').length) {
                        $(this).babystreet_wcs_variation_swatches_form();
                    }
                }
            });
            // WooCommerce Product Variations Swatches plugin of VillaTheme
            $(document.body).trigger('vi_wpvs_variation_form');
            this.init_events();
        },
        run_countdown: function (time) {
            clearInterval(viwcuf_countdown);
            let wrap = viwcuf_upsell_funnel.$wrap.find('.vi-wcuf-us-shortcode-countdown-wrap:not(.viwcuf-countdown-pausing)');
            if (wrap.length === 0) {
                return false;
            }
            wrap.find('.vi-wcuf-us-shortcode-progress_bar-value').css({'transform': 'rotate(' + $(this).data('deg') + 'deg)'});
            time = time || wrap.find('.vi-wcuf-us-shortcode-countdown-container-wrap').data('count') || 0;
            time = parseInt(time);
            viwcuf_countdown = setInterval(function () {
                wrap.find('.vi-wcuf-us-shortcode-countdown-container-wrap').attr('data-count', time);
                wrap.find('.vi-wcuf-us-shortcode-countdown-container-wrap span').html(time);
                let deg = 6 * Math.floor(time % 60);
                if (deg < 180) {
                    wrap.find('.vi-wcuf-us-shortcode-progress_bar-wrap').removeClass('vi-wcuf-us-shortcode-progress_bar-wrap-over50');
                    wrap.find('.vi-wcuf-us-shortcode-progress_bar-first50').addClass('vi-wcuf-hidden');
                } else {
                    wrap.find('.vi-wcuf-us-shortcode-progress_bar-wrap').addClass('vi-wcuf-us-shortcode-progress_bar-wrap-over50');
                    wrap.find('.vi-wcuf-us-shortcode-progress_bar-first50').removeClass('vi-wcuf-hidden');
                }
                wrap.find('.vi-wcuf-us-shortcode-progress_bar-value').css({'transform': 'rotate(' + deg + 'deg)'});
                time--;
                viwcuf_count_time = time;
                if (time < 0) {
                    clearInterval(viwcuf_countdown);
                    if (viwcuf_upsell_funnel.is_popup) {
                        viwcuf_upsell_funnel.$wrap.find('.vi-wcuf-us-shortcode-bt-continue-wrap').trigger('click');
                    } else {
                        viwcuf_upsell_funnel.$wrap.remove();
                    }
                }
            }, 1000);
        },
        show_message: function (message) {
            if (!$('.vi-wcuf-warning-wrap').length) {
                $('body').append('<div class="vi-wcuf-warning-wrap vi-wcuf-warning-wrap-open">' + message + '</div>');
            } else {
                $('.vi-wcuf-warning-wrap').removeClass('vi-wcuf-warning-wrap-close').addClass('vi-wcuf-warning-wrap-open').html(message);
            }
            setTimeout(function () {
                $('.vi-wcuf-warning-wrap').addClass('vi-wcuf-warning-wrap-close').removeClass('vi-wcuf-warning-wrap-open');
            }, 5000);
        },
        checkout: function () {
            viwcuf_upsell_funnel.remove_session();
            if (viwcuf_upsell_funnel.$wrap.hasClass('viwcuf-checkout-funnel-container-footer')) {
                viwcuf_upsell_funnel.$wrap.addClass('vi-wcuf-disable');
            } else {
                viwcuf_upsell_funnel.$wrap.find('.vi-wcuf-loading-wrap').removeClass('vi-wcuf-disable');
            }
            viwcuf_upsell_funnel.$checkout_form.find('.viwcuf_us_position').remove();
            viwcuf_upsell_funnel.$checkout_form.find('#place_order').trigger('click');
        },
        continue_checkout: function () {
            if (viwcuf_upsell_funnel.is_redirect_page || viwcuf_upsell_funnel.$wrap.hasClass('viwcuf-checkout-funnel-container-popup-init')) {
                let funnel_session = JSON.parse(sessionStorage.getItem('viwcuf_upsell_funnel_session'));
                if (!funnel_session) {
                    viwcuf_upsell_funnel.checkout();
                    return false;
                }
                let funnel_session_data = funnel_session.data;
                let funnel_session_html = $(funnel_session.html);
                $.each(funnel_session_data, function (k, v) {
                    let item_data = funnel_session_html.find('[name="' + v.name + '"]');
                    if (!item_data.length) {
                        viwcuf_upsell_funnel.$checkout_form.append('<input type="hidden" />').addClass(v.name).attr('name', v.name).val(v.value);
                    } else if (item_data.length === 1) {
                        if (item_data.val() != v.value) {
                            item_data.val(v.value);
                        }
                    } else {
                        item_data.each(function () {
                            if ($(this).val() == v.value && !$(this).prop('checked')) {
                                $(this).prop('checked', true);
                                return false;
                            }
                        });
                    }
                });
                funnel_session_html.find('#woocommerce-process-checkout-nonce').val(viwcuf_upsell_funnel.$checkout_form.find('#woocommerce-process-checkout-nonce').val());
                viwcuf_upsell_funnel.$checkout_form.html(funnel_session_html);
                viwcuf_upsell_funnel.$checkout_form.find('.viwcuf_funnel_session_prop_checked').each(function () {
                    $(this).prop('checked', true);
                });
                if (typeof window.wc_stripe !== "undefined") {
                    window.wc_stripe.payment_token_received = true;
                    window.wc_stripe.credit_card.payment_token_received = true;
                }
            }
            viwcuf_upsell_funnel.checkout();
        },
        create_session: function () {
            if (viwcuf_upsell_funnel.$supports_html5_storage) {
                let form = viwcuf_upsell_funnel.$checkout_form.clone();
                form.find('select').each(function (k, v) {
                    $(v).val(viwcuf_upsell_funnel.$checkout_form.find('select').eq(k).val());
                });
                form.find('.viwcuf-checkout-ob-container, .viwcuf_us_position').remove();
                form.find('.woocommerce-error, .woocommerce-message, .woocommerce-NoticeGroup').remove();
                form.find('.blockUI').remove();
                form.find(':checked').addClass('viwcuf_funnel_session_prop_checked');
                let data = {
                    data: form.serializeArray(),
                    html: form.html()
                };
                sessionStorage.setItem("viwcuf_upsell_funnel_session", JSON.stringify(data));
            }
        },
        remove_session: function () {
            if (viwcuf_upsell_funnel.$supports_html5_storage) {
                sessionStorage.removeItem("viwcuf_upsell_funnel_session");
            }
        }
    };
});