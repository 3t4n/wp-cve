"use strict";

jQuery(document).ready(function ($) {
    $(document).on('click', '#wobel-activation-activate', function () {
        $('#wobel-activation-type').val('activate');

        if ($('#wobel-activation-email').val() != '') {
            if ($('#wobel-activation-industry').val() != '') {
                setTimeout(function () {
                    $('#wobel-activation-form').first().submit();
                }, 200)
            } else {
                swal({
                    title: "Industry is required !",
                    type: "warning"
                });
            }
        } else {
            swal({
                title: "Email is required !",
                type: "warning"
            });
        }
    });

    $(document).on('click', '#wobel-activation-skip', function () {
        $('#wobel-activation-type').val('skip');

        setTimeout(function () {
            $('#wobel-activation-form').first().submit();
        }, 200)
    });
})