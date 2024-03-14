/* custom_order_button_text */
( function( $ ) {
    $( "form.checkout" )
        .on(
            "change",
            "input[name^=payment_method]",
            function() {
                var t = {
                    updateTimer: !1,
                    dirtyInput: !1,
                    reset_update_checkout_timer: function() {
                        clearTimeout( t.updateTimer )
                    },
                    trigger_update_checkout: function() {
                        t.reset_update_checkout_timer(), t.dirtyInput = !1,
                            $( document.body )
                            .trigger( "update_checkout" )
                    }
                };
                t.trigger_update_checkout();
            }
        );
} )( jQuery );