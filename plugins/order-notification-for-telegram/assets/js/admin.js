NKTelegramNotification = {
    init: function() {
        jQuery('#nktgnfw_send_test_message').click(this.send_test_message);
    },
    block: function () {
        jQuery( '#mainform' ).block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
    },
    unblock: function() {
        jQuery( '#mainform' ).unblock();
    },
    send_test_message:function () {
        NKTelegramNotification.block();
        jQuery.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: {
                action: 'nktgnfw_send_test_message',
            },
            type: 'post',
            success: function( response ) {
                var data = JSON.parse(response);
                alert(data.message);
                NKTelegramNotification.unblock();
            },
            error: function( jqXHR, textStatus, errorThrown ){
                alert(textStatus+', '+errorThrown);
                NKTelegramNotification.unblock();
            }
        });
    }
};
NKTelegramNotification.init();