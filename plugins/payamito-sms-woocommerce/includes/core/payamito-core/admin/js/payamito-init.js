jQuery(document).ready(function ($) {

    $("[data-tab-id='logs-view']").attr('href', payanitoObject.logsUrl);
    $("[data-tab-id='logs-view']").attr('target', '_blank');

    $("[data-tab-id='%d9%85%d8%b4%d8%a7%d9%87%d8%af%d9%87-%da%af%d8%b2%d8%a7%d8%b1%d8%b4%d8%a7%d8%aa']").attr('href', payanitoObject.logsUrl);
    $("[data-tab-id='%d9%85%d8%b4%d8%a7%d9%87%d8%af%d9%87-%da%af%d8%b2%d8%a7%d8%b1%d8%b4%d8%a7%d8%aa']").attr('target', '_blank');
    (function (parameters) {
        $(".payamito-summary").LoadingOverlay("show", {
            image: "",
            fontawesome: "fa fa-cog fa-spin",
        })
        $.ajax({
            'url': parameters.ajaxUrl,
            data: {
                'action': 'init_ajax',
                'method': 'statistics',
            },
        }).done(function (response) {

            $("#payamito_crediet").html(response.crediet);
            $("#payamito_all").html(response.statistics[-1]);
            $("#payamito_today").html(response.statistics[0])
            $("#payamito_7days").html(response.statistics[7])
            $("#payamito_30days").html(response.statistics[30])
            $(".payamito-summary").LoadingOverlay("hide");
        });
    })(payanitoObject);

    $("#payamito_test_connected").on('click', function () {
        $("#payamito_test_connected").prop('disabled', true);
        $("#payamito_test_connected").css('background-color', '#a6a6a6');

        $.ajax({
            'url': payanitoObject.ajaxUrl,
            data: {
                'action': 'init_ajax',
                'method': 'connect',
            },
        }).done(function (response) {

            $("#payamito_test_connected").prop('disabled', false);
            $("#payamito_test_connected").css('color', '#ffffff');
            $("#payamito_test_connected").css('border', '0px');
            $("#payamito_test_connected").html(response.m);
            if (response.status === '1') {
                $("#payamito_test_connected").css('background-color', '#61bf04');
            } else {
                $("#payamito_test_connected").css('background-color', '#e30909');
            }
            Swal.fire({
                title: response.title,
                text: response.connect,
                icon: response.type,
                confirmButtonText: response.btn
            });
        });
    });
})