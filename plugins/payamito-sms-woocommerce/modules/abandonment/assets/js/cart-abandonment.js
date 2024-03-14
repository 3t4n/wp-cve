(function ($) {

    var timer;
    var pwc_abandonment_cart_abandonment = {

        init: function () {

            $(document).on(
                'keyup keypress change',
                '#billing_email, #billing_phone, input.input-text, textarea.input-text, select', function () {
                    pwc_abandonment_cart_abandonment._getCheckoutData();
                });

            $(document.body).on('updated_checkout', function () {

                pwc_abandonment_cart_abandonment._getCheckoutData();
            });

            $(document).on('ready', function (e) {
                setTimeout(function () {
                    pwc_abandonment_cart_abandonment._getCheckoutData();
                }, 800);
            });
        },


        _getCheckoutData: function () {


            var phone = jQuery("#billing_phone").val();
            var email = jQuery("#billing_email").val();

            if (typeof email === 'undefined') {
                return;
            }

            var atposition = email.indexOf("@");
            var dotposition = email.lastIndexOf(".");


            if (typeof phone === 'undefined' || phone === null) { //If phone number field does not exist on the Checkout form
                phone = '';
            }

            clearTimeout(timer);

            if (!(atposition < 1 || dotposition < atposition + 2 || dotposition + 2 >= email.length) || phone.length >= 1) { //Checking if the email field is valid or phone number is longer than 1 digit

                var data = {
                    action: "abandonment_data",
                    billing_email: this._select_field_value("billing_email"),
                    billing_first_name: this._select_field_value("billing_first_name"),
                    billing_last_name: this._select_field_value("billing_last_name"),
                    billing_phone: this._select_field_value("billing_phone"),
                    billing_country: this._select_field_value("billing_country"),
                    billing_city: this._select_field_value("billing_city"),
                    billing_company: this._select_field_value("billing_company"),
                    billing_address_1: this._select_field_value("billing_address_1"),
                    billing_address_2: this._select_field_value("billing_address_2"),
                    billing_state: this._select_field_value("billing_state"),
                    billing_postcode: this._select_field_value("billing_postcode"),
                    shipping_first_name: this._select_field_value("shipping_first_name"),
                    shipping_last_name: this._select_field_value("shipping_last_name"),
                    shipping_company: this._select_field_value("shipping_company"),
                    shipping_country: this._select_field_value("shipping_country"),
                    shipping_address_1: this._select_field_value("shipping_address_1"),
                    shipping_address_2: this._select_field_value("shipping_address_2"),
                    shipping_city: this._select_field_value("shipping_city"),
                    shipping_state: this._select_field_value("shipping_state"),
                    shipping_postcode: this._select_field_value("shipping_postcode"),
                    order_comments: this._select_field_value("order_comments"),
                    security: PayamitoWcVars._nonce,
                    post_id: PayamitoWcVars._post_id,

                }

                timer = setTimeout(
                    function () {
                        if (true) {
                            jQuery.post(
                                PayamitoWcVars.ajaxurl, data, //Ajaxurl coming from localized script and contains the link to wp-admin/admin-ajax.php file that handles AJAX requests on Wordpress
                                function (response) {
                                    // success response
                                }
                            );
                        }
                    }, 500
                );
            }
        },
        _select_field_value(id) {
            return jQuery("#" + id).val()
        }
    }

    pwc_abandonment_cart_abandonment.init();

})(jQuery);