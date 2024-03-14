jQuery(document).ready(function ($) {


    $(".paymito-chosen-select").chosen({
        placeholder_text_multiple: " ",
        no_results_text: "",
        disable_search: true,
        allow_single_deselect: true,
        rtl: payamito_export.rtl,
        width: "100%"
    });

    const PAYAMITO_EXTEND = $("#payamito_btn_extend");

    PAYAMITO_EXTEND.on('click', function () {

        $("#endregionpayamito_btn_extend").prop('disabled', true);
        $("#payamito_btn_extend").css('cursor', 'wait');
        $.ajax({
            url: payamito_export.ajxurl,
            type: 'POST',
            data: {
                'action': "payamito_export",
                'nonce': $("#_wpnonce").val(),
                "status": $("#payamito-status").val(),
                "reciever": $("#payamito-reciever").val(),
                "method": $("#payamito-method").val(),
                "operative": $("#payamito-operative").val(),
                "limit": $("#payamito-limit").val(),
                "columns": $("#payamito-columns").val(),
            }
        }).done(function (r, s) {

            if (s == 'success' && r != '0' && r != "" && typeof r === 'object') {

                if (r.download == true) {
                    notification(1, r.messege);
                    payamito_downloed_exsport(r.file_name);

                } else {
                    notification(-1, r.messege);
                }
            }
        }).fail(function (r, s) {

        }).always(function (r, s) {
            $(".payamito_um_send_otp").removeAttr('disabled');
            $("#payamito_btn_extend").css('cursor', 'grab');
        });

    });

    function payamito_downloed_exsport(file_name) {

        let element = document.createElement('a');
        let href = payamito_export.url + '/' + file_name;
        element.setAttribute('href', href);

        element.setAttribute('download', file_name);

        document.body.appendChild(element);

        element.click();
    }

    function notification(ty = -1, m) {
        switch (ty) {
            case ty = -1:
                iziToast.error({
                    timeout: 10000,

                    message: m,
                    displayMode: 2
                });
                break;
            case ty = 0:
                iziToast.warning({
                    timeout: 10000,

                    message: m,
                    displayMode: 2
                });
                break;
            case ty = 1:
                iziToast.success({
                    timeout: 10000,

                    message: m,
                    displayMode: 2
                });
        }
    }
});