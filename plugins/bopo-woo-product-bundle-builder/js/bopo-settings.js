jQuery(document).ready(function ($) {
    'use strict';
    /*Color picker*/
    $('.vi-ui.dropdown').dropdown();

    $('.color-picker').iris({
        change: function (event, ui) {
            $(this).parent().find('.color-picker').css({backgroundColor: ui.color.toString()});
            let ele = $(this).data('ele');
            if (ele == 'highlight') {
                $('#message-purchased').find('a').css({'color': ui.color.toString()});
            } else if (ele == 'textcolor') {
                $('#message-purchased').css({'color': ui.color.toString()});
            } else {
                $('#message-purchased').css({backgroundColor: ui.color.toString()});
            }
        },
        hide: true,
        border: true
    }).click(function () {
        $('.iris-picker').hide();
        $(this).closest('td').find('.iris-picker').show();
    });

    $('body').click(function () {
        $('.iris-picker').hide();
    });
    $('.color-picker').click(function (event) {
        event.stopPropagation();
    });

    $('.bopobb-create-product').on('click', function () {
        $.ajax({
            type: 'POST',
            url: bopobb_ajax_url,
            data: {
                action: 'bopobb_first_bopobb_link',
                nonce: $('#bopobb_nonce_field').val(),
            },
            success: function (response) {
                $('.bopobb-create-product').parent().fadeOut(300);
            },
            error: function (err) {
                $('.bopobb-create-product').parent().fadeOut(300);
            }
        });
    })

});