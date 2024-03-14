(function ($) {
    window.increaseItmQty = (currentEle, aero_key = '', callback = '') => {
        let $this = $(currentEle);
        let qtyEle = $this.siblings(".wfacp_product_quantity_number_field");
        let value = parseFloat(qtyEle.val(), 10);
        value = isNaN(value) ? 0 : value;
        let max = qtyEle.attr('max');
        let step = qtyEle.attr('step');
        step = isNaN(step) ? 1 : parseFloat(step);
        if (undefined != max && '' != max) {
            max = parseInt(max, 10);
            if (value >= max) {
                wfacp_frontend.hooks.doAction('wfacp_max_quantity_reached', value, qtyEle);
                return;
            }
        }
        if ('' !== aero_key) {
            //trigger aerocheckout page
            let el = $('.wfacp_increase_item[data-item-key=' + aero_key + ']');
            if (el.length > 0) {
                increaseItmQty(el[0], '', function (rsp) {
                    if (rsp.hasOwnProperty('error')) {
                        let parent = $this.parents('.product-name-area');
                        wfacp_show_error(parent, rsp);
                        return;
                    }
                });
                return;
            }
        }

        value = value + step;
        qtyEle.val(value);
        let product_row = $this.parents('fieldset.wfacp_product_row');

        let product_row_class = $this.parents('.wfacp_product_row');
        product_row_class.parents('#product_switching_field').addClass("wfacp_animation_start");


        if (product_row.find('.wfacp_product_switch').length > 0 && product_row.find('.wfacp_product_switch:checked').length == 0) {
            product_row.find('.wfacp_product_switch').trigger('click');
            return;
        }
        qtyEle.trigger("change", [callback]);
    };
    window.decreaseItmQty = (currentEle, aero_key = '') => {

        let $this = $(currentEle);
        let qtyEle = $this.siblings(".wfacp_product_quantity_number_field");
        var value = parseFloat(qtyEle.val(), 10);
        value = isNaN(value) ? 0 : value;
        if (value < 1) {
            value = 1;
        }
        let min = qtyEle.attr('min');
        let step = qtyEle.attr('step');
        step = isNaN(step) ? 1 : parseFloat(step);

        let product_row_class = $this.parents('.wfacp_product_row');
        if (undefined != min && '' != min) {
            min = parseInt(min, 10);
            if (value <= min) {

                wfacp_frontend.hooks.doAction('wfacp_min_quantity_reached', value, qtyEle);
                return;
            }
        }
        if ('' !== aero_key && value > 1) {

            let el = $('.wfacp_decrease_item[data-item-key=' + aero_key + ']');
            if (el.length > 0) {
                el.click();
                return;
            }

        }

        value = value - step;
        qtyEle.val(value);


        qtyEle.trigger("change");

    };
    window.wfacp_product_switch = function (data, cb) {
        let switch_panel = $('.wfacp-product-switch-panel');
        switch_panel.aero_block();
        data.you_save = switch_panel.data('you-save-text');
        data.is_hide = switch_panel.data('is-hide');
        set_aero_data({
            'action': 'switch_product_addon',
            'type': 'post',
            'data': data
        }, cb);
    };
    let cart_goes_zero = (parseFloat(wfacp_frontend.cart_total) === 0);
    let cart_is_virtual = ('1' == wfacp_frontend.cart_is_virtual) ? true : false;
    let remove_coupon_div = (coupon) => {
        let dat_coupon = $('[data-coupon="' + coupon + '"]');


        if (dat_coupon.length > 0) {

            var delay_time = wfacp_frontend.hooks.applyFilters('wfacp_coupon_html_remove_delay_time', 5000);

            if (typeof delay_time == "undefined" || delay_time == '') {
                delay_time = 5000;
            }


            setTimeout(function () {
                dat_coupon.parents('tr').remove();
                dat_coupon.parents('.woocommerce-message1.wfacp_coupon_success').remove();

                dat_coupon.parents('.wfacp_single_coupon_msg').remove();
                if ($('.wfacp_coupon_applied').length > 0) {
                    $('.wfacp_coupon_applied').remove();
                }


            }, delay_time);

        }
    };
    let checkout_form = $('form.checkout');
    let wc_checkout_coupons_main = {
        coupon: '',
        init: function () {
            $(document.body).off('click', '.woocommerce-remove-coupon').on('click', '.woocommerce-remove-coupon', this.remove_coupon);
            $(document.body).on('click', '.wfacp_main_showcoupon', wc_checkout_coupons_main.show_coupon_form);
            $('form.checkout_coupon').off('submit').on('submit', this.submit);
            wfacp_frontend.hooks.addAction('wfacp_ajax_apply_coupon_main', this.response);
        },

        response: function (rsp) {
            let wfacp_coupon_msg = $('.wfacp_coupon_msg');
            wfacp_coupon_msg.html('').show();
            let $form = $('form.checkout_coupon');

            if ($form.hasClass('wfacp_layout_shopcheckout')) {
                $form.removeClass('processing').children(".wfacp_coupon_row").aero_unblock();
            } else {
                $form.removeClass('processing').aero_unblock();
            }
            $('.woocommerce-error, .woocommerce-message').remove();

            $form.find('button[name="apply_coupon"]').removeClass('wfacp_btn_clicked');

            $(document.body).trigger('wfacp_coupon_apply', [rsp]);
            if (rsp.hasOwnProperty('message')) {
                let message = rsp.message;
                if (message.hasOwnProperty('error')) {
                    //for error message;
                    let error = message.error;
                    if (error.length > 0) {
                        $form.addClass('wfacp_invalid_coupon');

                        for (let i = 0; i < error.length > 0; i++) {
                            let message = error[i];
                            let error_message = error[i];
                            if (typeof message == "object" && message.hasOwnProperty('notice')) {
                                error_message = message.notice;
                            }
                            wfacp_coupon_msg.prepend('<div class="woocommerce-error wfacp_error" role="alert">' + error_message + "</div>");
                        }
                    }
                }

                if (message.hasOwnProperty('success')) {
                    //for error message;
                    let success = message.success;
                    if (success.length > 0) {
                        $form.removeClass('wfacp_invalid_coupon');
                        for (let j = 0; j < success.length > 0; j++) {
                            let message = success[j];
                            let success_message = success[j];
                            if (typeof message == "object" && message.hasOwnProperty('notice')) {
                                success_message = message.notice;
                            }
                            wfacp_coupon_msg.prepend('<div class="woocommerce-message wfacp_success" role="alert">' + success_message + "</div>");
                        }
                    }

                    let coupon = wc_checkout_coupons_main.coupon;
                    if (!wfacp_frontend.applied_coupons.hasOwnProperty(coupon)) {
                        wfacp_frontend.applied_coupons[coupon] = coupon;
                    }
                }
            }
            // wc_checkout_coupons_main.hideMessage();
        },
        hideMessage() {

            var delay_time = wfacp_frontend.hooks.applyFilters('wfacp_coupon_html_remove_delay_time', 5000);

            if (typeof delay_time == "undefined" || delay_time == '') {
                delay_time = 5000;
            }

            if ($('.wfacp_coupon_msg  .woocommerce-message').length > 0) {
                setTimeout(function () {
                    $('.wfacp_coupon_msg  .woocommerce-message').html('');
                }, delay_time);
            }
            if ($('.wfacp_coupon_msg  .woocommerce-error').length > 0) {

                setTimeout(function () {
                    $('.wfacp_coupon_msg  .woocommerce-error').html('');
                }, delay_time);
            }
            if ($('.wfacp_coupon_field_msg').length > 0) {
                setTimeout(function () {
                    $('.wfacp_coupon_field_msg  .wfacp_single_coupon_msg').html('');
                }, delay_time);
            }


        },


        submit: function (e) {
            e.preventDefault();
            if (typeof wc_checkout_params == "undefined") {
                console.log('Coupon functionality not working in preview mode');
                return;
            }
            let $form = $(this);
            let coupon_code = $form.find('input[name="coupon_code"]').val();

            if ('' === coupon_code) {
                $form.find('input[name="coupon_code"]').addClass("wfacp_coupon_failed_error");
                return false;
            }


            $form.find('input[name="coupon_code"]').removeClass("wfacp_coupon_failed_error");

            if ($form.is('.processing')) {
                return false;
            }
            $form.addClass('processing');
            $form.find('button[name="apply_coupon"]').addClass('wfacp_btn_clicked');


            let wfacp_coupon_msg = $('.wfacp_coupon_msg');
            wfacp_coupon_msg.html('').show();
            wc_checkout_coupons_main.coupon = coupon_code;
            set_aero_data({
                'action': 'apply_coupon_main',
                'type': 'post',
                'coupon_code': $form.find('input[name="coupon_code"]').val(),
                'wfacp_id': $('._wfacp_post_id').val()
            });
        },
        show_coupon_form: function (e) {
            e.preventDefault();


            let field = $(this).parents('.wfacp_woocommerce_form_coupon').find('form.woocommerce-form-coupon');

            if (field.hasClass('wfacp_display_block')) {

                field.removeClass('wfacp_display_block');
                let classes = ['wfacp_desktop_view', 'wfacp_tablet_view', 'wfacp_mobile_view'];
                for (let i in classes) {
                    if (field.parent('.wfacp_mini_cart_classes').hasClass(classes[i])) {
                        field.parent('.wfacp_mini_cart_classes').removeClass(classes[i]);
                    }
                }
            }

            field.slideToggle(400, function () {
                field.find('.wfacp-form-control').focus();
            });
            return false;
        },
        remove_coupon: function (e) {
            e.preventDefault();
            if (typeof wc_checkout_params == "undefined") {
                console.log('Coupon functionality not working in preview mode');
                return;
            }
            let container = $(this).parents('.woocommerce-checkout-review-order');
            let coupon = $(this).data('coupon');
            container.addClass('processing');
            show_blocked_mini_cart();
            let data = {
                security: wc_checkout_params.remove_coupon_nonce,
                coupon: coupon
            };
            let wfacp_coupon_msg = $('.wfacp_coupon_msg');

            $.ajax({
                type: 'POST',
                url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon'),
                data: data,
                success: function (code) {

                    $('.woocommerce-error, .woocommerce-message').remove();
                    container.removeClass('processing');
                    show_unblocked_mini_cart();
                    if (code) {


                        remove_coupon_div(coupon);
                        if (wfacp_coupon_msg.length > 0) {
                            wfacp_coupon_msg.html(code);

                        } else {
                            $('.wfacp_layout_9_coupon_error_msg').html(code);
                        }
                        $(document.body).trigger('update_checkout', {update_shipping_method: false});
                        let coupon_field = $('form.checkout_coupon').find('input[name="coupon_code"]');
                        // Remove coupon code from coupon field
                        coupon_field.val('');
                        coupon_field.trigger('input');
                        coupon_field.parent('.wfacp-input-form').removeClass('wfacp-anim-wrap');
                    }
                    if ($('form.woocommerce-form-coupon:visible').length > 0) {
                        $.scroll_to_notices($(".wfacp_main_form"));
                    }
                    if (wfacp_frontend.applied_coupons.hasOwnProperty(coupon)) {
                        delete wfacp_frontend.applied_coupons[coupon];
                    }

                    $(document.body).trigger('wfacp_coupon_form_removed', [code]);
                    wc_checkout_coupons_main.hideMessage();
                },
                error: function () {
                    if (wc_checkout_params.debug_mode) {
                        /* jshint devel: true */
                    }
                },
                dataType: 'html'
            });
        }
    };
    let wc_checkout_coupon_field = {
        coupon_code: '',
        remove_field_el: '',
        init: function () {

            $(document.body).on('click', '.wfacp_showcoupon', wc_checkout_coupon_field.show_coupon_form);
            $(document.body).on('click', '.wfacp-coupon-field-btn', wc_checkout_coupon_field.apply_coupon);
            $(document.body).on('click', '.wfacp_remove_coupon', wc_checkout_coupon_field.remove_coupon);
            $(document.body).on('click', '.wfacp_coupon_apply', wc_checkout_coupon_field.hideMessage);
            wfacp_frontend.hooks.addAction('wfacp_ajax_apply_coupon_field', this.apply_coupon_response);
            wfacp_frontend.hooks.addAction('wfacp_ajax_remove_coupon_field', this.remove_coupon_field);
        },
        remove_class: function () {
            checkout_form.removeClass('processing');


        },
        show_coupon_form: function (e) {
            e.preventDefault();
            let $this = $(this);

            let field = $this.parents('.wfacp_woocommerce_form_coupon');
            field.find('.wfacp_coupon_field_box').slideToggle(400, function () {

                field.find('.wfacp_coupon_field_box').addClass("wfacp_coupon_collapsed");
                field.find('.wfacp_coupon_field_box').find(':input:eq(0)').focus();
            });
            return false;
        },
        apply_coupon: function () {
            if (typeof wc_checkout_params == "undefined") {
                console.log('Coupon functionality not working in preview mode');
                return;
            }
            wc_checkout_coupon_field.remove_class();

            let $this = $(this);
            let field = $this.parents('.wfacp_woocommerce_form_coupon');
            if (field.is('.processing')) {
                return false;
            }

            let coupon = field.find('.wfacp_coupon_code').val();
            if ('' === coupon) {
                field.removeClass('wfacp_coupon_success');
                field.addClass('wfacp_coupon_failed');
                return;
            }
            field.removeClass('wfacp_coupon_success');
            field.removeClass('wfacp_coupon_failed');
            field.addClass('processing').find(".wfacp_coupon_field_box").aero_block();

            wc_checkout_coupon_field.coupon_code = coupon;
            $this.addClass('wfacp_btn_clicked');

            set_aero_data({
                'action': 'apply_coupon_field',
                'type': 'post',
                'coupon_code': coupon,
                'wfacp_id': $('._wfacp_post_id').val()
            });
        },

        remove_coupon: function (e) {
            e.preventDefault();
            if (typeof wc_checkout_params == "undefined") {
                console.log('Coupon functionality not working in preview mode');
                return;
            }
            wc_checkout_coupon_field.remove_class();
            let $this = $(this);
            let field = $this.parents('.wfacp_woocommerce_form_coupon');
            wc_checkout_coupon_field.remove_field_el = field;
            field.addClass('processing').find(".wfacp_coupon_field_box").aero_block();
            let coupon = $(this).data('coupon');
            field.removeClass('wfacp_coupon_success');
            field.removeClass('wfacp_coupon_failed');


            wc_checkout_coupon_field.coupon_code = coupon;
            if (wfacp_frontend.applied_coupons.hasOwnProperty(coupon)) {
                delete wfacp_frontend.applied_coupons[coupon];
            }


            set_aero_data({
                'action': 'remove_coupon_field',
                'type': 'post',
                'coupon_code': coupon,
                'wfacp_id': $('._wfacp_post_id').val()
            });
        },
        apply_coupon_response: function (rsp) {
            $('.woocommerce-error, .woocommerce-message').remove();
            let $this = $('.wfacp-coupon-field-btn');
            let field = $this.parents('.wfacp_woocommerce_form_coupon');
            let wfacp_coupon_error_msg = field.find('.wfacp_coupon_error_msg');
            field.removeClass('processing').find(".wfacp_coupon_field_box").aero_unblock();
            //field.find('.wfacp-anim-wrap').removeClass('wfacp-anim-wrap');



            $(document.body).trigger('wfacp_coupon_apply', [rsp]);

            if (rsp.hasOwnProperty('message')) {
                let message = rsp.message;
                $this.removeClass('wfacp_btn_clicked');


                if (message.hasOwnProperty('error')) {
                    let error = message.error;
                    if (error.length > 0) {
                        for (let i = 0; i < error.length > 0; i++) {
                            let message = error[i];
                            let error_message = error[i];
                            if (typeof message == "object" && message.hasOwnProperty('notice')) {
                                error_message = message.notice;
                            }
                            wfacp_coupon_error_msg.html('<div class="woocommerce_single_error_message" role="alert">' + error_message + "</div>");
                        }
                    }
                    field.addClass('wfacp_coupon_failed');
                    var delay_time = wfacp_frontend.hooks.applyFilters('wfacp_coupon_html_remove_delay_time', 5000);

                    if (typeof delay_time == "undefined" || delay_time == '') {
                        delay_time = 5000;
                    }


                    setTimeout((el) => {
                      el.html('');
                    }, delay_time, wfacp_coupon_error_msg);

                } else {


                    let coupon = wc_checkout_coupon_field.coupon_code;
                    if (!wfacp_frontend.applied_coupons.hasOwnProperty(coupon)) {
                        wfacp_frontend.applied_coupons[coupon] = coupon;
                    }

                    field.addClass('wfacp_coupon_success');
                }
            }
            // wc_checkout_coupon_field.hideMessage();
        },
        remove_coupon_field: function (rsp) {

            let field = wc_checkout_coupon_field.remove_field_el;
            $('.woocommerce-error, .woocommerce-message').remove();
            field.removeClass('processing').find(".wfacp_coupon_field_box").aero_unblock();
            if (rsp.hasOwnProperty('message') && '' !== rsp.message) {
                let message = rsp.message;
                remove_coupon_div(wc_checkout_coupon_field.coupon_code);
                let html = $("<div>" + message + "</div>");
                field.find('.wfacp_coupon_remove_msg').html(html.text());
                $(document.body).trigger('update_checkout', {update_shipping_method: false});
                $('form.checkout_coupon').find('input[name="coupon_code"]').val('');
                $('form.checkout_coupon').find('input[name="coupon_code"]').trigger('input');

                var delay_time = wfacp_frontend.hooks.applyFilters('wfacp_coupon_html_remove_delay_time', 5000);

                if (typeof delay_time == "undefined" || delay_time == '') {
                    delay_time = 5000;
                }


                setTimeout(() => {
                    field.find('.wfacp_coupon_remove_msg').html('');
                }, delay_time, field);


                $(document.body).trigger('wfacp_coupon_form_removed', [message]);
            }
            wc_checkout_coupon_field.hideMessage();


        },
        hideMessage() {

            if ($('.wfacp_coupon_field_msg').length > 0) {
                var delay_time = wfacp_frontend.hooks.applyFilters('wfacp_coupon_html_remove_delay_time', 5000);

                if (typeof delay_time == "undefined" || delay_time == '') {
                    delay_time = 5000;
                }


                setTimeout(function () {
                    $('.wfacp_coupon_field_msg  .wfacp_single_coupon_msg').html('');
                }, delay_time);
            }

        }

    };
    let cart_virtual_fnc = (rsp) => {
        setTimeout(() => {
            let shipping_checkout = $('#shipping_same_as_billing_field');
            if (shipping_checkout.length > 0 && rsp.hasOwnProperty('cart_is_virtual') && cart_is_virtual !== rsp.cart_is_virtual) {
                if (true == rsp.cart_is_virtual) {
                    shipping_checkout.hide();
                    $('#shipping_same_as_billing').prop('checked', false).trigger('change');
                } else {
                    shipping_checkout.show();
                }
                let action_data = {'previous': cart_is_virtual, 'current': rsp.cart_is_virtual};
                wfacp_frontend.hooks.doAction('wfacp_cart_behaviour_changed', action_data);
                cart_is_virtual = rsp.cart_is_virtual;
            }
        }, rsp, 500);
    };
    wfacp_frontend.hooks.addAction('wfacp_ajax_switch_product_addon', handle_switch_product_addon);
    wfacp_frontend.hooks.addAction('wfacp_ajax_response', global_ajax_response);// Run When Our Action is running
    wfacp_frontend.hooks.addAction('wfob_ajax_response', global_ajax_response);// Run When Our Action is running
    wfacp_frontend.hooks.addAction('wfacp_update_order_review_response', global_ajax_response); // Run when normal Update order review runs
    wfacp_frontend.hooks.addAction('wfacp_ajax_error_response', global_error_ajax_response);

    function global_error_ajax_response() {
        let form_coupon = $('form.checkout_coupon');
        if (form_coupon.length > 0) {
            form_coupon.removeClass('processing');
        }
        let coupon_field = $('.wfacp_woocommerce_form_coupon');
        if (coupon_field.length > 0) {
            coupon_field.removeClass('processing');
        }
        checkout_form.removeClass('processing');
    }

    function global_ajax_response(rsp, fragments) {
        if (typeof rsp !== "object") {
            rsp = {'status': false};
        }
        if (rsp.hasOwnProperty('force_redirect')) {
            // Force redirect after removing last item
            window.location.href = rsp.force_redirect;
            return;
        }

        if (rsp.hasOwnProperty('cart_contains_subscription') && true == rsp.cart_contains_subscription) {
            let create_account = $('#createaccount');
            if (create_account.length > 0) {
                if (!create_account.is(":checked")) {
                    create_account.trigger('click');
                }
            }
        }

        cart_virtual_fnc(rsp);
        if (fragments.hasOwnProperty('wfacp_ajax_data')) {
            $(document.body).trigger('wfacp_update_fragments', rsp);
        }
        if (rsp.hasOwnProperty('cart_total') && parseInt(rsp.cart_total) === 0) {
            cart_goes_zero = true;
            $(document.body).trigger('wfacp_cart_goes_empty', {'cart_total': parseInt(rsp.cart_total)});
        } else {
            cart_goes_zero = false;
        }
        if (cart_goes_zero) {
            cart_goes_zero = false;
            update_checkout();
            return;
        } else {
            if (fragments && fragments.hasOwnProperty('.cart_total') && parseFloat(fragments['.cart_total']) === 0) {
                $(document.body).trigger('wfacp_cart_goes_empty', {'cart_total': parseFloat(rsp.cart_total)});
                update_checkout();
            }
        }

        if (fragments.hasOwnProperty('wfacp_ajax_data')) {
            $(document.body).trigger('wfacp_after_fragment_received', {'response': rsp});
            if (rsp.hasOwnProperty('analytics_data')) {
                $(document.body).trigger('wfacp_checkout_data', {'checkout': rsp.analytics_data});
            }
        }
    }


    function handle_switch_product_addon(rsp, fragments) {
        if (rsp.status === true) {
            if (undefined != rsp.new_cart_key && '' != rsp.new_cart_key) {
                setTimeout(() => {
                    $(document.body).trigger('wfacp_product_added', {
                        'item': rsp.cart_item,
                        'item_key': rsp.item_key,
                        'cart_key': rsp.new_cart_key,
                    });
                }, 300);
            }
        }
    }

    function product_switchers() {
        // product switching event start from here
        $(document.body).on('change', ".wfacp_product_switch", function (e, v) {
            if (!$(this).is(":checked")) {
                return false;
            }
            let el = $(this);
            let data = {};
            let new_item_key = el.data('item-key');
            let selected = $('.wfacp-selected-product');

            selected.removeClass('wfacp-selected-product');
            let parent = el.parents('.wfacp_product_row');
            parent.addClass('wfacp-selected-product');
            parent.parents('#product_switching_field').addClass('wfacp_animation_start');

            data.wfacp_id = $('._wfacp_post_id').val();
            data.new_item = new_item_key;
            data.remove_item_key = selected.attr('cart_key');
            data.quantity = 1;
            data.variation_id = 0;
            data = set_variation_data(data, v);
            if (parent.find('input.wfacp_product_switcher_quantity').length > 0) {
                let temp_qty = parent.find('input.wfacp_product_switcher_quantity').val();
                if (temp_qty > 0) {
                    data.quantity = temp_qty;
                }
            }
            if (typeof v == "object") {
                if (v.hasOwnProperty('old_qty')) {
                    data.old_qty = v.old_qty;
                }
                if (v.hasOwnProperty('variation_id') && v.hasOwnProperty('quantity')) {
                    data.quantity = v.quantity;
                } else {
                    data.quantity = v.qty;
                }
            }
            wfacp_product_switch(data, function (rsp) {
                if (rsp.status === false) {
                    wfacp_show_error(parent, rsp);
                    parent.removeClass('wfacp-selected-product');
                    selected.addClass('wfacp-selected-product');
                    selected.find('.wfacp_product_switch').prop('checked', true);
                }
            });
        });

        $(document.body).on('change', ".wfacp_product_choosen", function (e, v) {
            let switch_panel = $('.wfacp-product-switch-panel');
            let element = $(this);
            let is_checked = $(this).is(":checked");
            let type = $(this).attr("type");
            let parent = $(this).parents('.wfacp_product_row');

            parent.parents('#product_switching_field').addClass('wfacp_animation_start');
            if ('radio' === type) {
                $('.wfacp-product-switch-panel .wfacp_product_row').removeClass('wfacp-selected-product');
            }

            let action = 'addon_product';
            let data = {};
            if (!is_checked) {
                action = 'remove_addon_product';
            }
            data.type = type;
            data.wfacp_id = $('._wfacp_post_id').val();

            if (typeof v != "undefined") {
                if (v.action === 'add') {
                    action = 'addon_product';
                    data.quantity = v.qty;
                }
                if (v.action === 'remove') {
                    action = 'remove_addon_product';
                    if (v.hasOwnProperty('old_qty')) {
                        data.old_qty = v.old_qty;
                    }
                }
            }
            switch_panel.aero_block();
            let item_key = $(this).data('item-key');
            let cart_key = $(this).attr('cart_key');
            data.item_key = item_key;
            if (typeof cart_key != "undefined" && cart_key !== '') {
                data.item_key = cart_key;
            }
            data = set_variation_data(data, v);
            let ajax_data = {
                'action': action,
                'data': data
            };
            let cb = function (rsp) {
                if (rsp.hasOwnProperty('error')) {
                    if (action === 'addon_product') {
                        element.prop('checked', false);
                    } else {
                        element.prop('checked', true);
                    }
                    parent = $(`fieldset[cart_key="${rsp.item_key}"]`);
                    wfacp_show_error(parent, rsp);
                    show_unblocked_mini_cart();
                    return;
                }
                if (rsp.status == true) {
                    if (action === 'addon_product') {
                        $(document.body).trigger('wfacp_product_added', {
                            'item': rsp.cart_item,
                            'item_key': rsp.item_key,
                            'cart_key': rsp.new_cart_key,
                        });
                    }
                }
            };
            set_aero_data(ajax_data, cb);
        });
        $(document.body).on('change', '.wfacp_product_switcher_quantity', function (e, v) {
            let parent = $(this).parents('.wfacp_product_row');
            if ($(this).hasClass('wfacp_product_global_quantity_bump')) {
                parent.parents('#product_switching_field').removeClass('wfacp_animation_start');
                return;
            }

            if ($(this).is(":disabled")) {
                parent.parents('#product_switching_field').removeClass('wfacp_animation_start');
                return false;
            }


            let chooses_product = parent.find('.wfacp_product_choosen');
            if (chooses_product.length === 0) {
                chooses_product = parent.find('.wfacp_product_switch');
            }
            let item_key = chooses_product.data('item-key');
            let cart_key = chooses_product.attr('cart_key');
            let el = $(this);

            let wfacp_id = $('._wfacp_post_id').val();
            let qty = $(this).val();


            let max = $(this).attr('max');
            let old_qty = $(this).attr('data-value');
            let old_val = $(this).data('value');
            let step = $(this).attr('step');
            let delete_enabled = "0";
            let old_qty_is = parseInt(qty) + parseInt(step);

            if ($(this).parents('.wfacp-product-switch-panel').hasClass('wfacp_enable_delete_item')) {

                delete_enabled = "1";
            }


            if (undefined != max && '' != max) {
                max = parseInt(max, 10);
                if (qty > max) {
                    $(this).val(old_qty);
                    wfacp_frontend.hooks.doAction('wfacp_max_quantity_reached', qty, $(this));
                    parent.parents('#product_switching_field').removeClass('wfacp_animation_start');
                    return;
                }
            }

            let min = $(this).attr('min');

            if (undefined != min && '' != min) {
                min = parseInt(min, 10);
                if (old_qty_is <= min && step !== old_qty_is) {
                    if (qty == '0') {
                        old_qty = 0;

                        $(this).val(old_qty);
                    } else {
                        $(this).val(old_qty);

                        wfacp_frontend.hooks.doAction('wfacp_min_quantity_reached', qty, $(this));
                        parent.parents('#product_switching_field').removeClass('wfacp_animation_start');
                        return;
                    }
                }
            }


            if (qty == undefined || qty == '' || qty == 0) {

                if ('1' != delete_enabled) {


                    el.val(old_val);
                    return;
                }
            }


            parent.parents('#product_switching_field').addClass('wfacp_animation_start');


            if ('' !== cart_key) {
                if (0 === parseInt(qty) || '' === qty) {

                    let old_val = $(this).data('value');
                    let enable_delete = (wfacp_frontend.switcher_settings.hasOwnProperty('enable_delete_item') && wfacp_frontend.switcher_settings.enable_delete_item === 'true');
                    if (enable_delete) {
                        chooses_product.trigger('change', {'action': 'remove', 'qty': 0, 'old_qty': old_val});
                    } else {
                        let old_val = $(this).data('value');
                        $(this).val(old_val);
                    }

                    return;
                }
            } else {
                parent.parents('#product_switching_field').removeClass('wfacp_animation_start');
                if (0 === parseInt(qty) || '' === qty) {
                    return;
                }


                chooses_product.trigger('change', {'action': 'add', 'qty': qty});
                return;
            }
            let switch_panel = $('.wfacp-product-switch-panel');
            switch_panel.aero_block();

            let ajax_data = {
                'action': 'update_product_qty',
                'type': 'post',
                'data': {
                    item_key: item_key,
                    cart_key: cart_key,
                    qty: qty,
                    wfacp_id: wfacp_id,
                    is_hide: switch_panel.data('is-hide')
                }
            };
            let cb = (rsp) => {

                if (typeof v == "function") {
                    v(rsp);
                }
                if (rsp.hasOwnProperty('error')) {
                    if (rsp.hasOwnProperty('item_key')) {
                        parent = $('.wfacp_product_row[data-item-key="' + rsp.item_key + '"]');
                    } else if (rsp.hasOwnProperty('cart_key')) {
                        parent = $('.wfacp_product_row[cart_key="' + rsp.cart_key + '"]');
                    }
                    wfacp_show_error(parent, rsp);
                    show_unblocked_mini_cart();
                    switch_panel.aero_unblock();
                    return;
                }
            };
            set_aero_data(ajax_data, cb);
        });
        $(document.body).on('change', '.wfacp_product_global_quantity_bump', function () {
            let qty = $(this).val();
            let parent = $(this).parents('.cart_item');
            let cart_key = parent.attr('cart_key');
            let action = 'update_cart_item_quantity';
            let old_val = $(this).data('value');
            parent.parents('#product_switching_field').addClass('wfacp_animation_start');

            let max = $(this).attr('max');
            let old_qty = $(this).attr('data-value');
            if (undefined != max && '' != max) {
                max = parseInt(max, 10);
                if (qty > max) {
                    $(this).val(old_qty);
                    wfacp_frontend.hooks.doAction('wfacp_max_quantity_reached', qty, $(this));
                    parent.parents('#product_switching_field').removeClass('wfacp_animation_start');
                    return;
                }
            }

            let min = $(this).attr('min');

            if (undefined != min && '' != min) {
                min = parseInt(min, 10);
                if (qty < min) {
                    $(this).val(old_qty);
                    wfacp_frontend.hooks.doAction('wfacp_min_quantity_reached', qty, $(this));
                    parent.parents('#product_switching_field').removeClass('wfacp_animation_start');
                    return;
                }
            }


            if (qty == undefined || qty == '' || qty == 0) {
                let enable_delete = (wfacp_frontend.switcher_settings.hasOwnProperty('enable_delete_item') && wfacp_frontend.switcher_settings.enable_delete_item === 'true');
                if (!enable_delete) {
                    $(this).val(old_val);
                    parent.parents('#product_switching_field').removeClass('wfacp_animation_start');

                    return;
                }
            }


            let switch_panel = $('.wfacp-product-switch-panel');
            switch_panel.aero_block();
            let ajax_data = {
                'action': action,
                'type': 'post',
                'data': {
                    'cart_key': cart_key,
                    'quantity': qty,
                    'old_qty': old_val,
                    'by': 'product_switcher',
                    'wfacp_id': $("._wfacp_post_id").val()
                }
            };
            let cb = (rsp) => {
                switch_panel.aero_unblock();
                if (rsp.hasOwnProperty('error') && rsp.hasOwnProperty('cart_key')) {
                    parent = $('.wfacp_product_row[cart_key="' + rsp.cart_key + '"]');
                    wfacp_show_error(parent, rsp);
                    return;
                }
                update_fragments(rsp);
            };
            set_aero_data(ajax_data, cb);
        });
        // product switching event end here
        $(document.body).on('change', '.wfacp_mini_cart_update_qty', function () {


            let el = $(this);
            let qty = el.val();
            let delete_enabled = el.parents('.wfacp_min_cart_widget').data('delete-enabled');
            let parent = el.parent('.cart_item');
            let cart_key = el.attr('cart_key');
            let action = 'update_cart_item_quantity';
            let old_val = el.data('value');
            let step = el.attr('step');

            let old_qty_is = parseInt(qty) + parseInt(step);

            let max = el.attr('max');
            let old_qty = el.attr('data-value');

            if (undefined != max && '' != max) {
                max = parseInt(max, 10);
                if (qty > max) {
                    wfacp_frontend.hooks.doAction('wfacp_max_quantity_reached', qty, el);
                    $(this).val(old_qty);
                    return;
                }
            }

            let min = $(this).attr('min');


            if (undefined != min && '' != min) {
                min = parseInt(min, 10);

                if (old_qty_is <= min && step !== old_qty_is) {

                    if (qty == '0') {
                        old_qty = 0;
                        $(this).val(old_qty);
                    } else {
                        $(this).val(old_qty);
                        wfacp_frontend.hooks.doAction('wfacp_min_quantity_reached', qty, el);
                        return;
                    }


                }
            }


            if (qty == undefined || qty == '' || qty == 0) {
                if ('1' != delete_enabled) {
                    el.val(old_val);
                    return;
                }
            }
            let switch_panel = $('.wfacp-product-switch-panel');
            switch_panel.aero_block();

            let cb = (rsp) => {
                if (rsp.hasOwnProperty('error') && rsp.hasOwnProperty('cart_key')) {
                    $(this).val(old_val);
                    parent = $('.wfacp_min_cart_widget .cart_item[cart_key="' + rsp.cart_key + '"] .product-name-area');
                    wfacp_show_error(parent, rsp);
                }
            };
            set_aero_data({
                'action': action,
                'type': 'post',
                'data': {
                    'cart_key': cart_key,
                    'quantity': qty,
                    'old_qty': old_val,
                    'by': 'mini_cart',
                    'wfacp_id': $("._wfacp_post_id").val()
                }
            }, cb);
        });
        $(document.body).on('click', '.wfacp_mini_cart_remove_item_from_cart', function (e) {
            e.preventDefault();
            let el = $(this);
            let parent = $(this).parents('.cart_item');
            let cart_key = $(this).data('cart_key');
            let item_key = $(this).data('item_key');
            let action = 'remove_cart_item';
            let switch_panel = $('.wfacp-product-switch-panel');
            switch_panel.aero_block();
            let cb = (rsp, fragments) => {
                if (rsp.hasOwnProperty('error') && rsp.hasOwnProperty('cart_key')) {
                    let elementor = el.parents('.wfacp_elementor_mini_cart_widget');
                    parent = $('.wfacp_min_cart_widget .cart_item[cart_key="' + rsp.cart_key + '"] .product-name');
                    if (elementor.length > 0) {
                        parent = $('.wfacp_min_cart_widget .cart_item[cart_key="' + rsp.cart_key + '"] .product-name-area');
                    }
                    wfacp_show_error(parent, rsp);
                    return;
                }
                $(document.body).trigger('removed_from_cart', [fragments, '', el]);

            };
            set_aero_data({
                'action': action,
                'type': 'post',
                'data': {
                    'cart_key': cart_key,
                    'item_key': item_key,
                    'by': 'mini_cart',
                    'wfacp_id': $("._wfacp_post_id").val()
                }
            }, cb);
        });
        $(document.body).on('click', '.wfacp_force_last_delete', function (e) {
            e.preventDefault();
            let parentEl = $(this).parents('.cart_item');
            if (parentEl.length == 0) {
                return;
            }

            let cart_key = parentEl.attr('cart_key');
            $('#product_switching_field').aero_block();


            if ('' != cart_key || undefined != cart_key) {
                wfacp_send_ajax({
                    'action': 'delete_last_item',
                    'type': 'post',
                    'data': {
                        'cart_key': cart_key
                    }
                }, (rsp) => {
                    if (rsp.status == true) {
                        window.location.href = rsp.redirect;
                    } else {
                        $('#product_switching_field').aero_unblock();
                    }

                });
            }

        });
        $(document.body).on('click', '.wfacp_remove_item_from_cart', function (e) {
            e.preventDefault();
            let el = $(this);
            let parent = $(this).parents('.cart_item');
            let cart_key = $(this).data('cart_key');
            let item_key = $(this).data('item_key');
            let action = 'remove_cart_item';
            let switch_panel = $('.wfacp-product-switch-panel');
            parent.parents('#product_switching_field').addClass('wfacp_animation_start');

            switch_panel.aero_block();
            let cb = (rsp, fragments) => {
                if (rsp.hasOwnProperty('error') && rsp.hasOwnProperty('cart_key')) {
                    let s_parent = el.parents('.wfacp_min_cart_widget');
                    parent = $('.wfacp_product_row[cart_key="' + rsp.cart_key + '"]');
                    //mini cart
                    if (s_parent.length > 0) {
                        parent = el.parents('td');
                    }

                    if (parent.length > 0) {
                        show_unblocked_mini_cart();
                        wfacp_show_error(parent, rsp);
                    }
                    return;
                }
                $(document.body).trigger('removed_from_cart', [fragments, '', el]);
            };
            set_aero_data({
                'action': action,
                'data': {
                    'cart_key': cart_key,
                    'item_key': item_key,
                    'by': 'product_switcher',
                    'wfacp_id': $("._wfacp_post_id").val()
                }
            }, cb);
        });
        $(document.body).on('click', '.wfacp_remove_item_from_order_summary', function (e) {
            e.preventDefault();
            let el = $(this);
            let cart_key = $(this).data('cart_key');
            let action = 'remove_cart_item';
            let switch_panel = $('.wfacp-product-switch-panel');
            let order_summary = $('.wfacp_order_summary_container');
            switch_panel.aero_block();
            order_summary.aero_block();
            let cb = (rsp, fragments) => {
                show_unblocked_mini_cart();
                switch_panel.aero_unblock();
                order_summary.aero_unblock();
                $(document.body).trigger('removed_from_cart', [fragments, '', el]);
            };

            set_aero_data({
                'action': action,
                'data': {
                    'cart_key': cart_key,
                    'by': 'mini_cart',
                    'wfacp_id': $("._wfacp_post_id").val()
                }
            }, cb);
        });

        $(document.body).on('click', '.wfacp_restore_cart_item', function (e) {
            e.preventDefault();
            let cart_key = $(this).data('cart_key');
            let action = 'undo_cart_item';
            let switch_panel = $('.wfacp-product-switch-panel');

            switch_panel.addClass('wfacp_animation_start');
            switch_panel.aero_block();
            let by = 'product_switcher';
            if ($(this).parents('.wfacp_min_cart_widget').length > 0) {
                by = 'mini_cart';
            }
            let order_summary = $('.wfacp_order_summary_container');
            order_summary.aero_block();
            set_aero_data({
                'action': action,
                'type': 'post',
                'data': {
                    'cart_key': cart_key,
                    'wfacp_id': $("._wfacp_post_id").val(),
                    'by': by
                }
            }, function () {
                switch_panel.aero_unblock();
                order_summary.aero_unblock();
            });
        });

    }

    $(document.body).on('wfacp_setup', function () {
        product_switchers();
        wc_checkout_coupons_main.init();
        wc_checkout_coupon_field.init();
    });
})(jQuery);
