/* global yith_paypal_ec_frontend, paypal*/
(function ($) {

    'use strict';

    var is_valid = true,
        label = yith_paypal_ec_frontend.label,
        fundingicons = 'yes' == yith_paypal_ec_frontend.fundingicons ? true : false,
        branded = '';


    if ('buynow-branded' == label) {
        label = 'buynow';
        branded = true;
    }


    $('form.variations_form').on('show_variation', function (ev, variation, purchasable) {
        is_valid = purchasable;
    });

    $('form.variations_form').on('hide_variation', function (ev, variation, purchasable) {
        is_valid = false;
    });

    function toggleButton(actions) {
        return is_valid ? actions.enable() : actions.disable();
    }

    function onChangeVariation(handler) {
        $('form.variations_form').on('show_variation', handler);
        $('form.variations_form').on('hide_variation', handler);
    }

    function yith_ppec_start(){

        $(document).find('.paypal-button').each(function () {
            var button = $(this);
            button.hide();
            if( button.html() === '' ){
                paypal.Button.render({
                    env: yith_paypal_ec_frontend.env, // Or 'sandbox',
                    commit: true, // Show a 'Pay Now' button
                    locale: yith_paypal_ec_frontend.locale,
                    style: {
                        label: label,
                        branding: branded,
                        fundingicons: fundingicons,
                        size: yith_paypal_ec_frontend.size,    // small | medium | large | responsive
                        shape: yith_paypal_ec_frontend.style,     // pill | rect
                        color: yith_paypal_ec_frontend.color,
                        tagline: false,
                    },
                    payment: function (data, actions) {
                        var token = false;
                        if ($(document).find('form.cart').length) {
                            var form = $('form.cart');
                            var dataForm = new FormData(),
                                has_add_to_cart = false;

                            $.each(form.find("input[type='file']"), function (i, tag) {
                                $.each($(tag)[0].files, function (i, file) {
                                    dataForm.append(tag.name, file);
                                });
                            });

                            data = form.serializeArray();

                            $.each(data, function (i, val) {
                                if (val.name == 'add-to-cart') {
                                    has_add_to_cart = true;
                                }
                                dataForm.append(val.name, val.value);
                            });
                            dataForm.append('context', 'frontend');
                            dataForm.append('action', 'yith_paypal_ec_add_to_cart');
                            if (!has_add_to_cart) {
                                dataForm.append('add-to-cart', form.find('button[name="add-to-cart"]').val());
                            }

                            $.ajax({
                                url: yith_paypal_ec_frontend.ajaxurl.toString().replace('%%endpoint%%', 'yith_paypal_ec_add_to_cart'),
                                data: dataForm,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                type: 'POST',
                                async: false,
                                success: function (res) {
                                    if ('success' == res.result) {
                                        token = paypal.request.post(yith_paypal_ec_frontend.set_express_checkout_url).then(function (response) {
                                            if ('success' == response.result) {
                                                return response.token;
                                            } else {
                                                alert(response.error);
                                            }
                                        });
                                    }
                                }
                            });

                            return token;

                        } else {
                            var $url= yith_paypal_ec_frontend.set_express_checkout_url;
                            if ($(document.body).hasClass('woocommerce-order-pay')) {
                                var urlParams = new URLSearchParams(window.location.search);
                                $url = $url + '&woocommerce-order-pay=' + urlParams.get('key');
                            }
                            return paypal.request.post($url).then(function (response) {
                                if ('success' == response.result) {
                                    return response.token;
                                } else {
                                    alert(response.error);
                                }
                            });
                        }

                    },

                    onAuthorize: function (data, actions) {
                        if ($(document.body).hasClass('woocommerce-order-pay')) {
                            var urlParams = new URLSearchParams(window.location.search);
                            data.returnUrl = data.returnUrl + '&yith_paypal_ec_back=1&order_key='+ urlParams.get('key');
                        }

                        return actions.redirect();
                    },

                    validate: function (actions) {
                        toggleButton(actions);
                        onChangeVariation(function () {
                            toggleButton(actions);
                        });
                    },

                    onCancel: function (data, actions) {
                        /*
						 * Buyer cancelled the payment
						 */
                        $.post(yith_paypal_ec_frontend.ajaxurl.toString().replace('%%endpoint%%', 'yith_paypal_ec_cancelled_payment'));
                    },

                    onError: function (err) {
                        /*
						 * An error occurred during the transaction
						 */
                        alert( err );

                    }
                }, '.paypal-button').then(function () {
                    button.show();
                });
            }else{
                button.show();
            }

        });
    }

    yith_ppec_start();

    $( document.body ).on( 'updated_cart_totals', function(){  yith_ppec_start(); });
    $( document.body ).on( 'updated_checkout', function(){  yith_ppec_start(); });

    var confirm_checkout = yith_paypal_ec_frontend.confirm_checkout,
        needs_shipping = yith_paypal_ec_frontend.needs_shipping;

    if ('yes' == confirm_checkout && 'yes' == needs_shipping) {
        $('#ship-to-different-address-checkbox').attr('checked', 'checked');
    }


    function paymentChange () {

        var confirm_checkout = yith_paypal_ec_frontend.confirm_checkout,
            checkout_button = $(document).find('.woocommerce-checkout #paypal-button'),
            payment_checked = $(document).find('#payment_method_yith-paypal-ec').is(':checked');

        if( typeof checkout_button === 'undefined' || checkout_button.length == 0){
            return;
        }

        if ('yes' == confirm_checkout || ! payment_checked) {
            $(document).find('#place_order').attr('disabled', false);
        }else if( typeof checkout_button!=='undefined' && payment_checked ){
            $(document).find('#place_order').attr('disabled', true);
        }
    }

    $('body').on('change', 'input[name="payment_method"]', paymentChange );
    paymentChange();

}(jQuery));


