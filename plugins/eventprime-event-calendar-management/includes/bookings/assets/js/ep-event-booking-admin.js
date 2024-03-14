jQuery( function( $ ) {
    //add notes
    $( document ).on( 'click', '#ep-add-notes', function() {
        let booking_id = $('#post_ID').val();
        let nonce = $('#_wpnonce').val();
        let note = $('#ep-booking-note').val();
        $('.spinner').addClass('is-active');
        $('#ep-add-notes').attr('disabled','disabled');
        if( booking_id ) {
            let data = { 
                action    : 'ep_booking_add_notes', 
                security  : nonce,
                booking_id: booking_id,
                note      : note       
            };
            $.ajax({
                type        : "POST",
                url         : ajaxurl,
                data        : data,
                success     : function( response ) {
                    if(response.success === true && response.data.note !==''){
                        $('#ep-notes-lists').prepend('<li>'+response.data.note+'</li>');
                        $('#ep-booking-note').val('');
                    }
                    $('.spinner').removeClass('is-active');
                    $('#ep-add-notes').removeAttr('disabled');
                }
            });
        }
    });
    
    $( document ).on( 'click', '#update_booking_status', function() {
        let booking_id = $('#post_ID').val();
        let nonce = $('#_wpnonce').val();
        let status = $('#ep-booking-status').val();
        $('.ep-booking-status-spinner').addClass('is-active');
        $('#update_booking_status').attr('disabled','disabled');
        if( booking_id ) {
            let data = { 
                action    : 'ep_booking_update_status', 
                security  : nonce,
                booking_id: booking_id,
                status      : status       
            };
            $.ajax({
                type        : "POST",
                url         : ajaxurl,
                data        : data,
                success     : function( response ) {
                    $('.ep-booking-status-spinner').removeClass('is-active');
                    $('#update_booking_status').removeAttr('disabled');
                    window. location. reload();
                }
            });
        }
    });
    $( document ).on( 'click', '#update_payment_status', function() {
        let booking_id = $('#post_ID').val();
        let nonce = $('#_wpnonce').val();
        let status = $('#ep-payment-status').val();
        $('.ep-payment-status-spinner').addClass('is-active');
        $('#update_payment_status').attr('disabled','disabled');
        if( booking_id ) {
            let data = { 
                action    : 'ep_booking_update_payment_status', 
                security  : nonce,
                booking_id: booking_id,
                status      : status       
            };
            $.ajax({
                type        : "POST",
                url         : ajaxurl,
                data        : data,
                success     : function( response ) {
                    $('.ep-payment-status-spinner').removeClass('is-active');
                    $('#update_payment_status').removeAttr('disabled');
                    window. location. reload();
                }
            });
        }
    });
    $( document ).on( 'click', '#ep-booking-status-edit', function() {
        $('#ep-booking-status-child').toggle();
    });
    $( document ).on( 'click', '#ep-payment-status-edit', function() {
        $('#ep-payment-status-child').toggle();
    });
    
    $(document).on('click','body.post-type-em_booking #doaction, body.post-type-em_booking #doaction2', function(e){
        var selectedPost = [];
        var bulk_val = $('#bulk-action-selector-top').val();
        if(bulk_val == 'ep_export_booking'){
            e.preventDefault();
        
            $(".check-column input:checked").each(function(){
                selectedPost.push($(this).val());
            });
            if(selectedPost.length >= 1){
                $('form#posts-filter').submit();
            }
        }
    });
    
    $(document).on('click','#ep_export_booking_all_btn', function(e){
        var event_id = $('#ep_booking_event_id').val();
        var pay_method = $('#ep_booking_payment').val();
        var start_date = $('#ep_booking_start_date').val();
        var end_date = $('#ep_booking_end_date').val();
        var status = $('.post_status_page').val();
        var nonce = $('#_wpnonce').val();
        let data = { 
            action    : 'ep_booking_export_all', 
            security  : nonce,
            event_id  : event_id,
            pay_method : pay_method,
            start_date : start_date,
            end_date : end_date,
            status: status
        };
        jQuery.ajax({
            type        : "POST",
            url         : ajaxurl,
            data        : data,
            success     : function( response ) {
                var blob      = new Blob([response]);
                var link      = document.createElement('a');
                link.href     = window.URL.createObjectURL(blob);
                link.download = "bookings.csv";
                link.click();
                $('.ep-spinner').removeClass('ep-is-active');
            }
        });
    });

    // show transaction log
    $( document ).on( 'click', '#ep_show_booking_transaction_log', function() {
        $( '.ep-booking-transaction-log' ).toggle();
    });

    // load edit booking attendee form html
    $( document ).on( 'click', '.ep-admin-edit-booking-attendee', function() {
        let event_id = $( this ).data( 'event_id' );
        let booking_id = $( this ).data( 'booking_id' );
        let ticket_id = $( this ).data( 'ticket_id' );
        let ticket_key = $( this ).data( 'ticket_key' );
        let attendee_val = $( this ).data( 'attendee_val' );
        let nonce = $('#ep_booking_attendee_data_nonce').val();
        // get attendee form html
        $data = {
            'action'      : 'ep_load_edit_booking_attendee_data',
            'security'    : nonce,
            'event_id'    : event_id,
            'ticket_id'   : ticket_id,
            'booking_id'  : booking_id,
            'ticket_key'  : ticket_key,
            'attendee_val': attendee_val
        };

        jQuery.ajax({
            type    : "POST",
            url     : ajaxurl,
            data    : data,
            success : function( response ) {
                console.log(response);
            }
        });
    });
});

// refund payment
function ep_booking_refund_status(booking_id, payment_gateway, status='refunded'){
    jQuery('<div>Are you sure! wants to refund<div>').dialog({ 
        modal:true, 
        width:600,
        dialogClass: "em-schedule-no-close",
        closeOnEscape: false,
        buttons: {
            Ok: function() {
                formError = 0;
                jQuery( this ).dialog( "close" );
                jQuery('#ep_refunded_btn').attr('disabled');
                let booking_id = jQuery('#post_ID').val();
                let nonce = jQuery('#_wpnonce').val();
                if( booking_id ) {
                    let data = { 
                        action    : 'ep_booking_update_status', 
                        security  : nonce,
                        booking_id: booking_id,
                        status      : status       
                    };
                    jQuery.ajax({
                        type        : "POST",
                        url         : ajaxurl,
                        data        : data,
                        success     : function( response ) {
                            jQuery('.ep-booking-status-spinner').removeClass('is-active');
                            jQuery('#update_booking_status').removeAttr('disabled');
                            window. location. reload();
                        }
                    });
                }
            },
            Cancel: function(){
                formError = 1;
                jQuery( this ).dialog( "close" );
            }
        }
    });
}