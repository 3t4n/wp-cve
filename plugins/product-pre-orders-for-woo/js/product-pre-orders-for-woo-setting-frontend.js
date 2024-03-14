/*
* Label variable
*/
jQuery(document).ready(function ($) {
    "use strict";
    jQuery(function ($) {
        let button = $('.single_add_to_cart_button'),
            add_to_cart_text = button.html();
        $('form.variations_form')
            .on('show_variation', function (event, variation) {
                if (variation.hasOwnProperty('pre_order_label')) {
                    button.html(variation.pre_order_label);
                } else {
                    button.html(add_to_cart_text);
                }
            })
            .on('hide_variation', function (event) {
                event.preventDefault();
                button.html(add_to_cart_text);
            });

    });
});

