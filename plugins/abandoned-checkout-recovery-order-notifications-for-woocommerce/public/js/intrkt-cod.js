jQuery(document).ready(function ($) {
    $("#intrkt_action_confirm").click(function () {
        intrkt_cod_ajax_call("intrkt_cod_action", "confirm");
    });
    $("#intrkt_action_cancel").click(function () {
        intrkt_cod_ajax_call("intrkt_cod_action", "cancel");
    });
    function intrkt_cod_ajax_call(action, cod_action) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: intrktCodVars.ajaxurl,
            data: {
                action: action,
                _ajax_nonce: intrktCodVars._nonce,
                cod_action,
                order_id: intrktCodVars.orderId,
            },
            success: function (response) {
                location.reload();
            },
        });
    }
});
