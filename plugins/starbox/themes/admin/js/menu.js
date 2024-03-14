jQuery(document).ready(function () {
    jQuery('#abh_settings').find('.abh_show_extra_description').on('click', function () {
        jQuery('#abh_settings').find('.abh_extra_description').show();
        jQuery('#abh_settings').find('.abh_show_extra_description').hide();
    });
    jQuery('form').attr('enctype', 'multipart/form-data');
    if (jQuery('#description').length > 0) {
        jQuery('#description').parents('.form-table:last').before(jQuery('#abh_settings'));
        jQuery('.abh_description_author').append('<table></table>');
        jQuery('.abh_description_author').find('table').append(jQuery('#description').parents('tr:last'));
    }
    jQuery('#abh_subscribe_subscribe').on('click', function (event) {
        if (event) {
            event.preventDefault();
        }

        if (abh_validateEmail(jQuery('#abh_subscribe_email').val())) {
            jQuery.post(
                'https://api.squirrly.co/sq/users/subscribe?callback=?', {
                    email: jQuery('#abh_subscribe_email').val(),
                    url: jQuery('#abh_subscribe_url').val()
                }).done(function (data) {
                jQuery.post(
                    abh_Query.ajaxurl,
                    {
                        action: 'abh_settings_subscribe',
                        abh_nonce: abh_Query.abh_nonce
                    }).done(function (data) {

                });

                jQuery('#abh_option_subscribe').hide();
                jQuery('#abh_option_social').show();
                if (data.result == "success") {
                    jQuery('#abh_option_social').prepend('<div id="abh_subscribe_confirmation">Thank you!</div>');
                }
            });
        } else {
            alert('The email is not valid! Please enter a valid email address. Thank you');
        }
    });
    jQuery('#abh_theme_select,#abh_titlefontsize_select, #abh_descfontsize_select').on('change', function () {
        jQuery('#abh_box_preview').addClass('abh_loading');
        jQuery('#abh_box_preview').html('');
        jQuery.post(
            abh_Query.ajaxurl,
            {
                action: 'abh_get_box',
                user_id: jQuery('#user_id').val(),
                abh_theme: jQuery('#abh_theme_select').find(":selected").val(),
                abh_titlefontsize: jQuery('#abh_titlefontsize_select').find(":selected").val(),
                abh_descfontsize: jQuery('#abh_descfontsize_select').find(":selected").val(),
                abh_nonce: abh_Query.abh_nonce
            }).done(function (data) {
            jQuery('#abh_box_preview').removeClass('abh_loading');
            if (typeof data.box !== "undefined") {
                jQuery('#abh_box_preview').html(data.box);
            }
        });
    });
    jQuery('.abh_powered_by').on('click', function () {
        jQuery.post(
            abh_Query.ajaxurl,
            {
                action: 'abh_powered_by',
                abh_powered_by: jQuery('#abh_settings').find('input[name=abh_powered_by]:checked').val(),
                abh_nonce: abh_Query.abh_nonce
            }).done(function (data) {

        });
    });
});

function abh_validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (!emailReg.test($email)) {
        return false;
    } else {
        return true;
    }
}
