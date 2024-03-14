jQuery( function( $ ) {

    // ical export
    $( document ).on( 'click', '#ep_event_ical_export', function() {
        let event_id = $( this ).attr( 'data-event_id' );
        if( event_id ) {
            window.location = window.location.href + '&event='+event_id+'&download=ical';
        }
    });
    
    // cancel booking
    $( document ).on( 'click', '#ep_event_booking_cancel_booking', function() {
        $( '#ep_event_booking_cancellation_loader' ).show();
        let booking_id = $( '#ep_event_booking_cancellation_action' ).data( 'booking_id' );
        if( booking_id ) {
            booking_id = JSON.parse( booking_id );
            let data = { 
                action    : 'ep_event_booking_cancel', 
                security  : ep_event_booking_detail.booking_cancel_nonce,
                booking_id: booking_id
            };
            $.ajax({
                type        : "POST",
                url         : eventprime.ajaxurl,
                data        : data,
                success     : function( response ) {
                    $( '#ep_event_booking_cancellation_loader' ).hide();
                    // hide popup
                    $( '[ep-modal="ep_booking_cancellation_modal"]' ).fadeOut(200);
                    $( 'body' ).removeClass( 'ep-modal-open-body' );
                    if( response.success == true ) {
                        show_toast( 'success', response.data.message );
                        setTimeout( function() {
                            location.reload();
                        }, 2000);
                    } else{
                        show_toast( 'error', response.data.error );
                    }
                }
            });
        }
    });
});