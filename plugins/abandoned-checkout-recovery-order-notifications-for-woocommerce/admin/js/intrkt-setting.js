jQuery(document).ready(function ($) {
    $("select.intrkt_order_status").select2();
    $("#intrkt-set-live").click(function () {
        var interkt_status_table = $("#intrkt_order_status_table tbody");
        var tableValues = [];
        var countryCodeSelection = $(
            'input[name="country_code_status"]:checked'
        ).val();
        countryCode = $("#intrkt_country_code").val();
        if (
            (countryCode == null || countryCode == "") &&
            countryCodeSelection ==
                intrktSettingVars.intrktWithoutCountryCode &&
            "false" == intrktSettingVars.intrktintegrationStatus
        ) {
            $("#intrkt_country_code").addClass("intrkt_text_input_error");
            // $(".intrkt_text_input_error_msg").show();
            return;
        }
        $("#intrkt_country_code").removeClass("intrkt_text_input_error");
        // $(".intrkt_text_input_error_msg").hide();
        console.log(countryCode);
        interkt_status_table.find("tr").each(function (index, element) {
            var intrkt_status;
            var order_status;
            var payment_method;
            $(element)
                .find("td")
                .each(function (index, element) {
                    if (0 == index) {
                        intrkt_status = $(element).attr(
                            "data-intrktOrderStatus"
                        );
                    } else if (1 == index) {
                        order_status = $(element).find("select").val();
                        if (undefined === order_status) {
                            order_status = "any";
                        }
                    } else if (2 == index) {
                        payment_method = $(element).find("select").val();
                        if (undefined === payment_method) {
                            payment_method = "any";
                        }
                    }
                });
            var currentRow = {
                intrkt_status: intrkt_status,
                order_status: order_status,
                payment_method: payment_method,
            };
            console.log(payment_method);
            tableValues.push(currentRow);
        });
        intrkt_setting_ajax_call(
            "intrkt_save_setting",
            tableValues,
            countryCodeSelection,
            countryCode
        );
    });
    $("#intrkt_disconnect").click(function (e) {
        e.preventDefault();
        var is_confirm = confirm(intrktSettingVars.disconnectConfirm);
        if (true === is_confirm) {
            intrkt_disconnect_ajax_call();
        }
    });
    function intrkt_setting_ajax_call(
        action,
        tableValues,
        countryCodeSelection,
        countryCode
    ) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: intrktSettingVars.ajaxurl,
            data: {
                action: action,
                _ajax_nonce: intrktSettingVars._nonce,
                tableValues,
                countryCodeSelection,
                countryCode,
            },
            success: function (response) {
                location.reload();
            },
        });
    }
    function intrkt_disconnect_ajax_call() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: intrktSettingVars.ajaxurl,
            data: {
                action: "intrkt_disconnect_oauth",
                _ajax_nonce: intrktSettingVars._nonce,
            },
            success: function (response) {
                alert(intrktSettingVars.completeDisconnect);
                location.reload();
            },
        });
    }

    // Load js for accordion only for mobile devices
    if (window.innerWidth <= 768) {
        $(".intrkt_main_wrapper .intrkt_accordion_body")
            .not(":first")
            .removeClass("show");
    }
    $("h3#interakt_order_status_heading").tooltip({
        placement: "right",
    });
    $(".intrkt_section-two-wrap.intrkt-disable-section")
        .parent()
        .hover(
            function () {
                $(".intrkt_section-two-heading").css("pointer-events", "unset");
                $(".intrkt_section-two-heading *").css(
                    "pointer-events",
                    "unset"
                );
            },
            function () {
                $(".intrkt_section-two-heading *").css(
                    "pointer-events",
                    "none"
                );
                $(".intrkt_section-two-heading").css("pointer-events", "none");
            }
        );
    $(".intrkt_section-two-wrap.intrkt-disable-section").click(function (e) {
        e.preventDefault();
    });
});
