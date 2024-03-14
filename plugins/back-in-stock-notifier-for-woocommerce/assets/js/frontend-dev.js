"use strict";
var ajax_url = cwginstock.ajax_url;
var security_error = cwginstock.security_error;
var userid = cwginstock.user_id;
var emptyname = cwginstock.empty_name;
var emptyemail = cwginstock.empty_email;
var emptyquantity = cwginstock.empty_quantity;
var invalidemail = cwginstock.invalid_email;
var recaptcha_enabled = cwginstock.enable_recaptcha;
var recaptcha_site_key = cwginstock.recaptcha_site_key;
var recaptcha_verify_enabled = cwginstock.enable_recaptcha_verify;
var recaptcha_secret_present = cwginstock.recaptcha_secret_present;
var is_iagree = cwginstock.is_iagree_enable;
var iagree_error = cwginstock.iagree_error;
var is_v3_recaptcha = cwginstock.is_v3_recaptcha;
var is_popup = cwginstock.is_popup;
var googlerecaptcha_widget_id = null;
var gtoken = '';
var iti;
var phone_field = cwginstock.phone_field;
var subscriber_phone = '';
var phone_meta_data = '';
var phone_error = cwginstock.phone_field_error;
var is_phone_field_optional = cwginstock.is_phone_field_optional;
var is_quantity_field_optional = cwginstock.is_quantity_field_optional;
var hide_country_placeholder = cwginstock.hide_country_placeholder;
var default_country_code = cwginstock.default_country_code;

function cwginstock_recaptcha_callback(response) {
    document.getElementsByClassName("cwgstock_button")[0].disabled = false;
    if (recaptcha_verify_enabled == '1' && recaptcha_secret_present == 'yes') {
        document.getElementsByClassName("cwg-security")[0].value = response;
    }
}

var instock_notifier = {
    init: function () {
        if (is_popup == 'no') {
            instock_notifier.generate_v3_response();
        }
        jQuery(document).on('click', '.cwgstock_button', this.submit_form);
        jQuery(".single_variation_wrap").on("show_variation", this.perform_upon_show_variation);
        if (phone_field == '1') {
            instock_notifier.initialize_phone();
        }
    },

    initialize_phone: function () {
        var input = document.querySelector(".cwgstock_phone");
        if (input) {
            iti = window.intlTelInput(input, {
                allowDropdown: true,
                formatOnDisplay: true,
                autoHideDialCode: false,
                separateDialCode: true,
                utilsScript: cwginstock.phone_utils_js,
                initialCountry: default_country_code,
                customPlaceholder: function (selectedCountryPlaceholder, selectedCountryData) {
                    default_country_code = default_country_code.toLowerCase();
                    if (default_country_code == selectedCountryData.iso2 && cwginstock.hide_country_placeholder == '2') {
                        if (cwginstock.custom_country_placeholder != '') {
                            return cwginstock.custom_country_placeholder;
                        }
                        return selectedCountryPlaceholder;
                    } else {
                        return '';
                    }
                }
            });

        }
    },
    perform_upon_show_variation: function (event, variation) {
        var vid = variation.variation_id;
        jQuery('.cwginstock-subscribe-form').hide(); //remove existing form
        jQuery('.cwginstock-subscribe-form-' + vid).show(); //add subscribe form to show
        if (recaptcha_enabled == '1') {
            instock_notifier.onloadcallback();
        }
        if (phone_field == '1') {
            instock_notifier.initialize_phone();
        }
    },
    generate_v3_response: function () {
        if (recaptcha_enabled == '1' && is_v3_recaptcha == 'yes') {
            grecaptcha.ready(function () {
                grecaptcha.execute(recaptcha_site_key, { action: 'subscribe_form' }).then(function (token) {
                    var hasClass = document.getElementsByClassName("cwg-security");
                    if (hasClass.length > 0) {
                        document.getElementsByClassName("cwg-security")[0].value = token;
                        document.getElementsByClassName("cwgstock_button")[0].disabled = false;
                        gtoken = token;
                    }
                });
            });
        }
    },
    is_email: function (email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    },
    submit_form: function (e) {
        e.preventDefault();
        var submit_button_obj = jQuery(this);
        var subscriber_name = jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_name').val();
        var email_id = jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_email').val();
        var quantity = jQuery(this).closest('.cwginstock-subscribe-form').find('.add_quantity_field').val();
        if (quantity === '' || quantity <= 0) {
            if (is_quantity_field_optional == '2') {
                jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').fadeIn();
                jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').html("<div class='cwginstockerror' style='color:red;'>" + emptyquantity + "</div>");
                return false;
            }
        }
        //Customised for Phone Field
        if (phone_field == '1') {
            var subscriber_phone = iti.getNumber();//jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_phone').val();
            phone_meta_data = iti.getSelectedCountryData();
            if (!iti.isValidNumber()) {

                var errorCode = iti.getValidationError();
                console.log(errorCode);
                var errorMsg = phone_error[errorCode];
                if (errorCode == -99) {
                    errorMsg = phone_error[0];
                }
                if ((errorCode != -99 && is_phone_field_optional == '1') || is_phone_field_optional == '2') {
                    jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').fadeIn();
                    jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').html("<div class='cwginstockerror' style='color:red;'>" + errorMsg + "</div>");
                    return false;
                }
            }
        }
        var product_id = jQuery(this).closest('.cwginstock-subscribe-form').find('.cwg-product-id').val();
        var var_id = jQuery(this).closest('.cwginstock-subscribe-form').find('.cwg-variation-id').val();
        if (subscriber_name == '') {
            jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').fadeIn();
            jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').html("<div class='cwginstockerror' style='color:red;'>" + emptyname + "</div>");
            return false;

        } else if (email_id == '') {
            jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').fadeIn();
            jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').html("<div class='cwginstockerror' style='color:red;'>" + emptyemail + "</div>");
            return false;
        } else {
            //check is valid email
            if (!instock_notifier.is_email(email_id)) {
                jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').fadeIn();
                jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').html("<div class='cwginstockerror' style='color:red;'>" + invalidemail + "</div>");
                return false;
            }

            if (is_iagree == '1') {
                if (!jQuery(this).closest('.cwginstock-subscribe-form').find('.cwg_iagree_checkbox_input').is(':checked')) {
                    jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').fadeIn();
                    jQuery(this).closest('.cwginstock-subscribe-form').find('.cwgstock_output').html("<div class='cwginstockerror' style='color:red;'>" + iagree_error + "</div>");
                    return false;
                }
            }
            var security = jQuery(this).closest('.cwginstock-subscribe-form').find('.cwg-security').val();
            var data = {
                action: 'cwginstock_product_subscribe',
                product_id: product_id,
                variation_id: var_id,
                subscriber_name: subscriber_name,
                subscriber_phone: subscriber_phone,
                subscriber_phone_meta: phone_meta_data,
                user_email: email_id,
                user_id: userid,
                security: security,
                dataobj: cwginstock,
                custom_quantity: quantity
            };

            //jQuery.blockUI({message: null});
            if (jQuery.fn.block) {
                submit_button_obj.closest('.cwginstock-subscribe-form').block({ message: null });
            } else {
                var overlay = jQuery('<div id="cwg-bis-overlay"> </div>');
                overlay.appendTo(submit_button_obj.closest('.cwginstock-subscribe-form'));
            }

            //perform ajax functionality
            instock_notifier.perform_ajax(data, submit_button_obj);
        }
    },

    recaptcha_callback: function (response) {
        var hasClass = document.getElementsByClassName("cwg-security");
        if (hasClass.length > 0) {
            document.getElementsByClassName("cwgstock_button")[0].disabled = false;
            if (recaptcha_verify_enabled == '1' && recaptcha_secret_present == 'yes') {
                document.getElementsByClassName("cwg-security")[0].value = response;
            }
        }
    },
    onloadcallback: function () {
        if (recaptcha_enabled == '1') {
            if (is_v3_recaptcha == 'no') {
                if (jQuery('#cwg-google-recaptcha').length) {
                    if (googlerecaptcha_widget_id === null) {
                        googlerecaptcha_widget_id = grecaptcha.render('cwg-google-recaptcha', {
                            'sitekey': recaptcha_site_key,
                            'callback': this.recaptcha_callback,
                        });
                    } else {
                        grecaptcha.reset(googlerecaptcha_widget_id);
                        this.recaptcha_callback();
                        googlerecaptcha_widget_id = null;
                        instock_notifier.onloadcallback();
                    }
                }
            } else {
                instock_notifier.generate_v3_response();
            }
        }
    },
    resetcallback: function () {
        if (recaptcha_enabled == '1') {
            if (is_v3_recaptcha == 'no') {
                grecaptcha.reset();
                document.getElementsByClassName("cwgstock_button")[0].disabled = true;
            } else {
                instock_notifier.generate_v3_response();
            }
        }
    },
    perform_ajax: function (data, submit_button_obj) {

        jQuery.ajax({
            type: "post",
            url: ajax_url,
            data: data,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', cwginstock.security);
            },
            success: function (msg) {
                msg = msg.msg;
                submit_button_obj.closest('.cwginstock-subscribe-form').find('.cwgstock_output').fadeIn(2000);
                submit_button_obj.closest('.cwginstock-subscribe-form').find('.cwgstock_output').html(msg);
                //jQuery.unblockUI();
                if (jQuery.fn.block) {
                    submit_button_obj.closest('.cwginstock-subscribe-form').unblock();
                } else {
                    submit_button_obj.closest('.cwginstock-subscribe-form').find('#cwg-bis-overlay').fadeOut(400, function () {
                        submit_button_obj.closest('.cwginstock-subscribe-form').find('#cwg-bis-overlay').remove();
                    });
                }
                instock_notifier.resetcallback();
                jQuery(document).trigger('cwginstock_success_ajax', data);
            },
            error: function (request, status, error) {
                if (typeof request.responseJSON !== 'undefined') {
                    if (request.responseJSON.hasOwnProperty('code')) {
                        if (typeof request.responseJSON.code !== 'undefined') {
                            if ((request.responseJSON.code == 'rest_cookie_invalid_nonce') || (request.responseJSON.code == 'cwg_nonce_verify_failed')) {
                                request.responseText = -1;
                            }
                        }
                    }
                }
                if (request.responseText === '-1' || request.responseText === -1) {
                    submit_button_obj.closest('.cwginstock-subscribe-form').find('.cwgstock_output').fadeIn(2000);
                    submit_button_obj.closest('.cwginstock-subscribe-form').find('.cwgstock_output').html("<div class='cwginstockerror' style='color:red;'>" + security_error + "</div>");
                }
                //jQuery.unblockUI();
                if (jQuery.fn.block) {
                    submit_button_obj.closest('.cwginstock-subscribe-form').unblock();
                } else {
                    submit_button_obj.closest('.cwginstock-subscribe-form').find('#cwg-bis-overlay').fadeOut(400, function () {
                        submit_button_obj.closest('.cwginstock-subscribe-form').find('#cwg-bis-overlay').remove();
                    });
                }
                instock_notifier.resetcallback();
                jQuery(document).trigger('cwginstock_error_ajax', data);
            }
        });
    },
};

instock_notifier.init();