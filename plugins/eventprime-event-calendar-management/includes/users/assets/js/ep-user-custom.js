jQuery( function( $ ) {

    $( document ).ready( function() {
        // add select2 on the registration form timezone field
        $( '#ep_register_timezone' ).select2({
            
        }); 
    });
    // show registration form
    $( document ).on( 'click', '#em_login_register', function() {
        $( '#ep_attendee_register_form_wrapper' ).show();
        $( '#ep_attendee_login_form_wrapper' ).hide();
    });

    // show login form
    $( document ).on( 'click', '#em_register_login', function() {
        $( '#ep_attendee_login_form_wrapper' ).show();
        $( '#ep_attendee_register_form_wrapper' ).hide();
    });
    
    $( document ).on( 'click', '.ep-login-form-submit', function(e){
        e.preventDefault();
        var formData = new FormData(document.getElementById('ep_attendee_login_form'));
        formData.append('action', 'ep_submit_login_form');
        $('.ep-spinner').addClass('ep-is-active');
        $('.ep-login-response').html();
        $.ajax({
            type : "POST",
            url : ep_frontend.ajaxurl,
            data: formData,
            contentType: false,
            processData: false,       
            success: function(response) {
                $('.ep-spinner').removeClass('ep-is-active');
                if( response.data.success ) {
                    $( '.ep-login-response' ).html( '<div class="ep-login-success">' + response.data.msg + '</div>' );
                    // redirect
                    if( response.data.redirect ) {
                        setTimeout( function() {
                            if( response.data.redirect == 'reload' ) {
                                location.reload();
                            } else{
                                window.location.replace( response.data.redirect );
                            }
                        }, 1000);
                    } else{
                        $( document ).trigger( 'afterEPLogin', { response: response } );
                    }
                }else{
                    $('.ep-login-response').html('<div class="ep-error-message">'+response.data.msg+'</div>');
                }
                
            }
        });
    });
    
    $( document ).on( 'click', '.ep-register-form-submit', function(e){
        e.preventDefault();
        var formData = new FormData(document.getElementById('ep_attendee_register_form'));
        formData.append('action', 'ep_submit_register_form');
        $('.ep-spinner').addClass('ep-is-active');
        $('.ep-register-response').html();
        $.ajax({
            type : "POST",
            url : ep_frontend.ajaxurl,
            data: formData,
            contentType: false,
            processData: false,       
            success: function(response) {
                $('.ep-spinner').removeClass('ep-is-active');
                if( response.data.success ) {
                    $('.ep-register-response').html('<div class="ep-success-message">'+response.data.msg+"</div>");
                    if( response.data.redirect !== '' ) {
                        setTimeout(function() {
                            if( response.data.redirect == 'reload' ) {
                                setTimeout( function() {
                                    location.reload();
                                }, 1000 );
                            } else{
                                window.location.replace( response.data.redirect );
                            }
                        }, 1000 );
                    }
                }else{
                    $('.ep-register-response').html('<div class="ep-error-message">'+response.data.msg+"</div>");
                }
            }
        });
    });

    // edit timezone
    $( document ).on( 'click', '#ep-user-profile-timezone-edit', function() {
        $( this ).hide();
        $( '.ep-user-profile-timezone-list' ).show();
    });

    // save the user timezone
    $( document ).on( 'click', '#ep_user_profile_timezone_save', function() {
        let time_zone = $( '#ep_user_profile_timezone_list' ).val();
        if( time_zone ) {
            $( '.ep-loader' ).show();
            let data = { 
                action    : 'ep_update_user_timezone',
                security  : ep_frontend._nonce,
                time_zone : time_zone
            };
            $.ajax({
                type    : "POST",
                url     : eventprime.ajaxurl,
                data    : data,
                success : function( response ) {
                    if( response == -1 ) {
                        show_toast( 'error', ep_frontend.nonce_error );
                        return false;
                    }
                    if( response.success == false ) {
                        show_toast( 'error', response.data.error );
                        return false;
                    } else{
                        show_toast( 'success', response.data.message );
                        $( '.ep-loader' ).hide();
                        $( '#ep_user_profile_timezone_data' ).text( time_zone );
                        $( '#ep-user-profile-timezone-edit' ).show();
                        $( '.ep-user-profile-timezone-list' ).hide();
                    }
                }
            });
        }
    });

    // delete fes event
    $( document ).on( 'click', '#ep_user_profile_delete_user_fes_event', function() {
        let fes_event_id = $( this ).data('fes_event_id' );
        if( fes_event_id ) {
            if( confirm( ep_frontend.delete_event_confirm ) == true ) {
                $( '.ep-loader' ).show();
                let data = { 
                    action    : 'ep_delete_user_fes_event',
                    security  : ep_frontend._nonce,
                    fes_event_id : fes_event_id
                };
                $.ajax({
                    type    : "POST",
                    url     : eventprime.ajaxurl,
                    data    : data,
                    success : function( response ) {
                        if( response == -1 ) {
                            show_toast( 'error', ep_frontend.nonce_error );
                            return false;
                        }
                        if( response.success == false ) {
                            show_toast( 'error', response.data.error );
                            return false;
                        } else{
                            show_toast( 'success', response.data.message );
                            $( '.ep-loader' ).hide();
                            $( '#ep_user_profile_my_events_' + fes_event_id ).remove();
                        }
                    }
                });
            }
        }
    });

});

function ep_event_download_attendees( event_id ){
    if( event_id ){
        jQuery.ajax({
            type: "POST",
            url: ep_frontend.ajaxurl,
            data: {action: 'ep_export_submittion_attendees', security  : ep_frontend._nonce, event_id: event_id},
            success: function (response) {
                var link = document.createElement('a');
                link.download = "attendees.csv";
                link.href = 'data:application/csv;charset=utf-8,' + encodeURIComponent(response);
                link.click();
            }
        });
    }
}