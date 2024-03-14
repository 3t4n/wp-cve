(function ($) {

    var timer;
    var isProcessing = false;
    var spoki_cart_abandonment = {
        init: function () {

            if (SpokiACVars._show_gdpr_message && !$("#spoki_cf_gdpr_message_block").length) {
                $("#billing_email").after("<span id='spoki_cf_gdpr_message_block'> <span style='font-size: xx-small'> " + SpokiACVars._gdpr_message + " <a style='cursor: pointer' id='spoki_ca_gdpr_no_thanks'> " + SpokiACVars._gdpr_nothanks_msg + " </a></span></span>");
            }
            $(document).on(
                'keyup keypress change',
                '#billing_email, #billing_phone, input.input-text, textarea.input-text, select',
                this._getCheckoutData
            );

            $("#spoki_ca_gdpr_no_thanks").click(function () {
                spoki_cart_abandonment._set_cookie();
            });

            $(document.body).on('updated_checkout', function () {
                spoki_cart_abandonment._getCheckoutData();
            });

            $(document).on('ready', function (e) {
                setTimeout(function () {
                    spoki_cart_abandonment._getCheckoutData();
                }, 800);
            });
        },

        _set_cookie: function () {


            var data = {
                'spoki_ca_skip_track_data': true,
                'action': 'cartflows_skip_cart_tracking_gdpr',
                'security': SpokiACVars._gdpr_nonce,
            };

            jQuery.post(
                SpokiACVars.ajaxurl, data,
                function (response) {

                    if (response.success) {
                        $("#spoki_cf_gdpr_message_block").empty().append("<span style='font-size: xx-small'>" + SpokiACVars._gdpr_after_no_thanks_msg + "</span>").delay(5000).fadeOut();
                    }

                }
            );

        },

        _validate_email: function (value) {
            var valid = true;
            if (value.indexOf('@') == -1) {
                valid = false;
            } else {
                var parts = value.split('@');
                var domain = parts[1];
                if (domain.indexOf('.') == -1) {
                    valid = false;
                } else {
                    var domainParts = domain.split('.');
                    var ext = domainParts[1];
                    if (ext.length > 14 || ext.length < 2) {
                        valid = false;
                    }
                }
            }
            return valid;
        },

        _validate_phone: function (value) {
            var phone = value ? (value + "").trim() : '';
            var prefix_length = phone.startsWith('+') ? 3 : (SpokiACVars._default_prefix || '').length
            return phone.length - prefix_length >= 3;
        },

        _get_valid_phone: function (value) {
            if (typeof value === 'undefined' || value === null) { //If phone number field does not exist on the Checkout form
                value = '';
            }
            if (!value.startsWith('+') && SpokiACVars._default_prefix) {
                value = SpokiACVars._default_prefix + value;
            }
            return value;
        },

        _getCheckoutData: function () {

            // if (isProcessing == true)
            // return;

            var spoki_phone = spoki_cart_abandonment._get_valid_phone(jQuery("#billing_phone").val() || "");
            var spoki_email = jQuery("#billing_email").val();

            if (typeof spoki_email === 'undefined') {
                return;
            }

            clearTimeout(timer);

            var atposition = spoki_email.indexOf("@");
            var dotposition = spoki_email.lastIndexOf(".");

            if (!(atposition < 1 || dotposition < atposition + 2 || dotposition + 2 >= spoki_email.length) && spoki_cart_abandonment._validate_phone(spoki_phone)) {
                //If Phone valid
                var spoki_email = jQuery("#billing_email").val() || "";
                var spoki_name = jQuery("#billing_first_name").val() || "";
                var spoki_surname = jQuery("#billing_last_name").val() || "";
                var spoki_country = jQuery("#billing_country").val() || "";
                var spoki_city = jQuery("#billing_city").val() || "";

                //Other fields used for "Remember user input" function
                var spoki_billing_company = jQuery("#billing_company").val() || "";
                var spoki_billing_address_1 = jQuery("#billing_address_1").val() || "";
                var spoki_billing_address_2 = jQuery("#billing_address_2").val() || "";
                var spoki_billing_state = jQuery("#billing_state").val() || "";
                var spoki_billing_postcode = jQuery("#billing_postcode").val() || "";
                var spoki_shipping_first_name = jQuery("#shipping_first_name").val() || "";
                var spoki_shipping_last_name = jQuery("#shipping_last_name").val() || "";
                var spoki_shipping_company = jQuery("#shipping_company").val() || "";
                var spoki_shipping_country = jQuery("#shipping_country").val() || "";
                var spoki_shipping_address_1 = jQuery("#shipping_address_1").val() || "";
                var spoki_shipping_address_2 = jQuery("#shipping_address_2").val() || "";
                var spoki_shipping_city = jQuery("#shipping_city").val() || "";
                var spoki_shipping_state = jQuery("#shipping_state").val() || "";
                var spoki_shipping_postcode = jQuery("#shipping_postcode").val() || "";
                var spoki_order_comments = jQuery("#order_comments").val() || "";

                var data = {
                    action: "spoki_cartflows_save_cart_abandonment_data",
                    spoki_email: spoki_email,
                    spoki_name: spoki_name,
                    spoki_surname: spoki_surname,
                    spoki_phone: spoki_phone,
                    spoki_country: spoki_country,
                    spoki_city: spoki_city,
                    spoki_billing_company: spoki_billing_company,
                    spoki_billing_address_1: spoki_billing_address_1,
                    spoki_billing_address_2: spoki_billing_address_2,
                    spoki_billing_state: spoki_billing_state,
                    spoki_billing_postcode: spoki_billing_postcode,
                    spoki_shipping_first_name: spoki_shipping_first_name,
                    spoki_shipping_last_name: spoki_shipping_last_name,
                    spoki_shipping_company: spoki_shipping_company,
                    spoki_shipping_country: spoki_shipping_country,
                    spoki_shipping_address_1: spoki_shipping_address_1,
                    spoki_shipping_address_2: spoki_shipping_address_2,
                    spoki_shipping_city: spoki_shipping_city,
                    spoki_shipping_state: spoki_shipping_state,
                    spoki_shipping_postcode: spoki_shipping_postcode,
                    spoki_order_comments: spoki_order_comments,
                    security: SpokiACVars._nonce,
                    spoki_post_id: SpokiACVars._post_id,
                }

                isProcessing = true;
                timer = setTimeout(
                    function () {
                        if (spoki_cart_abandonment._validate_email(data.spoki_email)) {
                            jQuery.post(
                                SpokiACVars.ajaxurl, data, //Ajaxurl coming from localized script and contains the link to wp-admin/admin-ajax.php file that handles AJAX requests on Wordpress
                                function (response) {
                                    isProcessing = false;
                                }
                            );
                        }
                    }, 500
                );
            } else {
                //console.log("Not a valid email");
            }
        }
    }

    spoki_cart_abandonment.init();

})(jQuery);