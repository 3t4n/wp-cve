if (typeof viwcuf_atc ==='undefined') {
    var viwcuf_atc = [];
}
if (typeof viwcuf_remove_cart_item ==='undefined') {
    var viwcuf_remove_cart_item = [];
}
jQuery(function ($) {
    $(document).ready(function () {
        'use strict';
        if (!viwcuf_frontend_ob_params) {
            return false;
        }
        let order_bump = viwcuf_order_bump;
        order_bump.wc_ajax_url = viwcuf_frontend_ob_params.wc_ajax_url;
        order_bump.i18n_unavailable_text = viwcuf_frontend_ob_params.i18n_unavailable_text || 'Sorry, this product is unavailable. Please choose a different combination.';
        order_bump.i18n_make_a_selection_text = viwcuf_frontend_ob_params.i18n_make_a_selection_text || 'Please select some product options before adding {product_name} to your cart.';
        order_bump.$wrap = $('.viwcuf-checkout-ob-container');
        order_bump.init();
    });
    var viwcuf_order_bump = {
        wc_ajax_url: '',
        i18n_unavailable_text: '',
        i18n_make_a_selection_text: '',
        $wrap: '',
        init: function () {
            let self = this;
            if (self.$wrap.find('.vi-wcuf-ob-product-wrap').length) {
                self.design();
            }
            $(document.body).on('updated_checkout', function (e, data) {
                self.$wrap = $('.viwcuf-checkout-ob-container');
                if (self.$wrap.find('.vi-wcuf-ob-product-wrap').length) {
                    self.$wrap.off('click');
                    self.design();
                }
            });
        },
        init_events: function () {
            let self = viwcuf_order_bump;
            self.$wrap.find('.vi-wcuf-ob-checkbox-loading').removeClass('vi-wcuf-ob-checkbox-loading');
            self.$wrap.on('click', '.vi-wcuf-ob-product-desc-read', function () {
                let current = $(this).closest('.vi-wcuf-ob-product-desc');
                if ($(this).hasClass('vi-wcuf-ob-product-desc-read-more')) {
                    current.find('.vi-wcuf-ob-product-desc-short').addClass('vi-wcuf-disable');
                    current.find('.vi-wcuf-ob-product-desc-full').removeClass('vi-wcuf-disable');
                } else {
                    current.find('.vi-wcuf-ob-product-desc-short').removeClass('vi-wcuf-disable');
                    current.find('.vi-wcuf-ob-product-desc-full').addClass('vi-wcuf-disable');
                }
                viwcuf_order_bump.design_form_atc($(this));
            });
            self.$wrap.on('click', '.vi-wcuf-ob-title', function () {
                $(this).parent().find('.vi-wcuf-ob-checkbox').trigger('click');
            });
            self.$wrap.on('click', '.vi-wcuf-ob-checkbox:not(.vi-wcuf-ob-checkbox-loading)', function () {
                let button = $(this);
                let product = button.closest('.vi-wcuf-ob-product-wrap');
                let form = product.find('.vi-wcuf-ob-cart-form');
                if (!form.length || (!form.find('[name="quantity"]').length && !form.find('.qty').length)) {
                    self.show_message(self.i18n_unavailable_text);
                    return false;
                }
                if (button.hasClass('vi-wcuf-ob-checkbox-checked')) {
                    button.addClass('vi-wcuf-ob-checkbox-loading');
                    self.remove_form_cart(button, product);
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
                    self.show_message(self.i18n_make_a_selection_text.replace('{product_name}', form.data('product_name')));
                    return false;
                }
                if (button.hasClass('vi-wcuf-ob-checkbox-swatches-disable')) {
                    self.show_message(self.i18n_unavailable_text);
                    return false;
                }
                button.addClass('vi-wcuf-ob-checkbox-loading');
                self.add_to_cart(button, form, product);
            });
        },
        add_to_cart: function (button, form, product) {
            button = $(button);
            form = $(form);
            product = $(product);
            button.addClass('vi-wcuf-ob-checkbox-checked');
            form.find('.vi-wcuf-add-to-cart, .vi-wcuf-product_id').val(product.data('product_id') || 0);
            let data = form.find('select, input').serialize();
            if (data.search('viwcuf_nonce') === -1) {
                data += '&viwcuf_nonce=' + viwcuf_frontend_ob_params.nonce;
            }
            if (typeof viwcuf_atc === 'undefined') {
                viwcuf_atc = [];
            }
            viwcuf_atc.push({
                type: 'post',
                url: viwcuf_order_bump.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcuf_add_to_cart'),
                data: data,
                beforeSend: function () {
                    product.closest('.viwcuf-checkout-ob-shortcode').find('.vi-wcuf-loading-wrap').removeClass('vi-wcuf-disable');
                },
                success: function (response) {
                    if (!response || response.error) {
                        if (response.message) {
                            viwcuf_order_bump.show_message(response.message);
                        }
                        viwcuf_atc = [];
                        return false;
                    } else {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, button]);
                        $(document.body).trigger('vi_wcuf_added_to_cart', [response.fragments, response.cart_hash, button]);
                        viwcuf_atc.shift();
                        if (viwcuf_atc.length > 0) {
                            $.ajax(viwcuf_atc[0]);
                        } else {
                            $(document.body).trigger('update_checkout', {update_shipping_method: false});
                        }
                    }
                },
                complete: function (response) {
                    form.find('.vi-wcuf-add-to-cart, .vi-wcuf-product_id').val('');
                    product.closest('.viwcuf-checkout-ob-shortcode').find('.vi-wcuf-loading-wrap').addClass('vi-wcuf-disable');
                },
            });
            if (viwcuf_atc.length === 1) {
                $.ajax(viwcuf_atc[0]);
            }
        },
        remove_form_cart: function (button, product) {
            button = $(button);
            product = $(product);
            button.removeClass('vi-wcuf-ob-checkbox-checked');
            viwcuf_remove_cart_item.push({
                type: 'post',
                url: viwcuf_order_bump.wc_ajax_url.toString().replace('%%endpoint%%', 'viwcuf_remove_form_cart'),
                data: {
                    viwcuf_nonce: viwcuf_frontend_ob_params.nonce,
                    product_id: product.data('product_id'),
                    product_type: 'viwcuf_ob_product',
                    cart_item_key: product.data('cart_item_key')
                },
                beforeSend: function () {
                    product.closest('.viwcuf-checkout-ob-shortcode').find('.vi-wcuf-loading-wrap').removeClass('vi-wcuf-disable');
                },
                success: function (response) {
                    if (!response || response.error) {
                        if (response.message) {
                            viwcuf_order_bump.show_message(response.message);
                        }
                        viwcuf_remove_cart_item = [];
                        return false;
                    } else {
                        $(document.body).trigger('removed_from_cart', [response.fragments, response.cart_hash, button]);
                        $(document.body).trigger('vi_wcuf_removed_from_cart', [response.fragments, response.cart_hash, button]);
                        viwcuf_remove_cart_item.shift();
                        if (viwcuf_remove_cart_item.length > 0) {
                            $.ajax(viwcuf_remove_cart_item[0]);
                        } else {
                            $(document.body).trigger('update_checkout', {update_shipping_method: false});
                        }
                    }
                },
                complete: function () {
                    product.closest('.viwcuf-checkout-ob-shortcode').find('.vi-wcuf-loading-wrap').addClass('vi-wcuf-disable');
                },
            });
            if (viwcuf_remove_cart_item.length === 1) {
                $.ajax(viwcuf_remove_cart_item[0]);
            }
        },
        design: function () {
            viwcuf_order_bump.$wrap.find('.vi-wcuf-ob-product-wrap-checked').each(function (k, v) {
                $(v).find('.vi-wcuf-ob-checkbox').addClass('vi-wcuf-ob-checkbox-checked');
            });
            if (viwcuf_order_bump.$wrap.outerWidth() < 400) {
                viwcuf_order_bump.$wrap.find('select').addClass('vi-wcuf-ob-full-width');
            }
            viwcuf_order_bump.$wrap.find('.vi-wcuf-ob-cart-form').each(function () {
                $(this).removeClass('vi-wcuf-ob-full-width');
                viwcuf_order_bump.design_form_atc($(this));
            });
            viwcuf_order_bump.$wrap.find('.vi-wcuf-cart-form-swatches:not(.vi-wcuf-cart-form-swatches-init)').each(function () {
                $(this).addClass('vi-wcuf-cart-form-swatches-init vi_wpvs_variation_form').viwcuf_get_variations({wc_ajax_url: viwcuf_order_bump.wc_ajax_url});
                // Babystreet theme of theAlThemist
                if ($(this).find('.babystreet-wcs-swatches').length) {
                    $(this).babystreet_wcs_variation_swatches_form();
                }
            });
            // WooCommerce Product Variations Swatches plugin of VillaTheme
            setTimeout(function () {
                $(document.body).trigger('vi_wpvs_variation_form');
            }, 100);
            viwcuf_order_bump.init_events();
        },
        design_form_atc: function (item) {
            let wrap = $(item).closest('.vi-wcuf-product');
            if (!wrap.find('.vi-wcuf-ob-product-image').length) {
                return false;
            }
            if (wrap.find('.vi-wcuf-ob-product-image').height() < wrap.find('.vi-wcuf-ob-product-desc').height()) {
                wrap.find('.vi-wcuf-ob-cart-form').addClass('vi-wcuf-ob-full-width');
            } else {
                wrap.find('.vi-wcuf-ob-cart-form').removeClass('vi-wcuf-ob-full-width')
            }
        },
        show_message: function (message) {
            if (!$('.vi-wcuf-warning-wrap').length) {
                $('body').append('<div class="vi-wcuf-warning-wrap vi-wcuf-warning-wrap-open">' + message + '</div>');
            } else {
                $('.vi-wcuf-warning-wrap').removeClass('vi-wcuf-warning-wrap-close').addClass('vi-wcuf-warning-wrap-open').html(message);
            }
            setTimeout(function () {
                $('.vi-wcuf-warning-wrap').addClass('vi-wcuf-warning-wrap-close').removeClass('vi-wcuf-warning-wrap-open');
            }, 3000);
        },
    };
    window.viwcuf_order_bump = viwcuf_order_bump;
});