jQuery( function( $ ) {
    $( document ).ready( function() {
        $('.thumbnail').on('click', function () {
            var clicked = $(this);
            var newSelection = clicked.data('image_url');
            //var $img = $('.primary').css("background-image", "url(" + newSelection + ")");
            var $img = $('.primary').attr('src', "" + newSelection + "");
            clicked.parent().find('.thumbnail').removeClass('selected');
            clicked.addClass('selected');
            $('.primary').empty().append($img.hide().fadeIn('slow'));
        });
        
        /*-- Theme Color Global--*/  
        // set dominent color for nice theme
        
        $(".emagic").prepend("<a>");
        var epColorRgbValue = $('.emagic, #primary.content-area .entry-content, .entry-content .emagic').find('a').css('color');
    
        /*-- Theme Color Global--*/ 
        var epColorRgb = epColorRgbValue;
        var avoid = "rgb";
        if( epColorRgb ) {
            var eprgbRemover = epColorRgb.replace(avoid, '');
            var emColor = eprgbRemover.substring(eprgbRemover.indexOf('(') + 1, eprgbRemover.indexOf(')'));
            $(':root').css('--themeColor', emColor );
        }

        let ep_font_size = eventprime.global_settings.ep_frontend_font_size;
        if( !ep_font_size ) {
            ep_font_size = 14;
        }
        $(':root').css('--themefontsize', ep_font_size + 'px' );
        
        //Adding class on body in case EP content
        if ($('.emagic').length) {
            $('html').addClass('ep-embed-responsive');
        }
        
        //Adding class incase event list right col is small
        var epEventListwidth = $(".ep-box-list-right-col").width();
        
        if(epEventListwidth < 210){
            $(".ep-box-list-right-col .ep-event-list-view-action").addClass("ep-column-small");
        }

        // set dark mode
        if( eventprime.global_settings.enable_dark_mode == 1 ) {
            $( 'body' ).addClass( 'ep-dark-mode-enabled' );
        }
        
    });
    
    $(function() {
        //----- OPEN
        $('[ep-modal-open]').on('click', function(e)  {
            var targeted_popup_class = jQuery(this).attr('ep-modal-open');
            $('[ep-modal="' + targeted_popup_class + '"]').fadeIn(100);
            $('body').addClass('ep-modal-open-body');
            e.preventDefault();
        });
    
        //----- CLOSE
        $('[ep-modal-close]').on('click', function(e)  {
            var targeted_popup_class = jQuery(this).attr('ep-modal-close');
            $('[ep-modal="' + targeted_popup_class + '"]').fadeOut(200);
            $('body').removeClass('ep-modal-open-body');
            e.preventDefault();
        });
    });

    // add event to wishlist
    $( document ).on( 'click', '.ep_event_wishlist_action', function() {
        if( $( '.ep-event-loader' ).length > 0 ) {
            $( '.ep-event-loader' ).show();
        }
        let event_id = $( this ).data( 'event_id' );
        var remove_row_id = $( this ).attr( 'data-remove_row' );
        if( event_id ) {
            let data = { 
                action   : 'ep_event_wishlist_action', 
                security : eventprime.event_wishlist_nonce,
                event_id : event_id
            };
            $.ajax({
                type        : "POST",
                url         : eventprime.ajaxurl,
                data        : data,
                success     : function( response ) {
                    if( $( '.ep-event-loader' ).length > 0 ) {
                        $( '.ep-event-loader' ).hide();
                    }
                    if( response.success == true ) {
                        show_toast( 'success', response.data.message );
                        // add, remove color
                        if( response.data.action == 'add' ) {
                            $( '#ep_event_wishlist_action_' + event_id + ' .ep-handle-fav' ).text( 'favorite' );
                            $( '#ep_event_wishlist_action_' + event_id + ' .ep-handle-fav' ).addClass( 'ep-text-danger' );
                        } else{
                            $( '#ep_event_wishlist_action_' + event_id + ' .ep-handle-fav' ).text( 'favorite_border' );
                            $( '#ep_event_wishlist_action_' + event_id + ' .ep-handle-fav' ).removeClass( 'ep-text-danger' );
                        }
                        $( '#ep_event_wishlist_action_' + event_id ).attr( 'title', response.data.title );
                        
                        // remove block of user profile
                        if( remove_row_id ) {
                            $( '#' + remove_row_id ).remove();
                        }
                        // update count
                        if( $( '#ep_wishlist_event_count' ).length > 0 ) {
                            let eve_count = $( '#ep_wishlist_event_count' ).text();
                            --eve_count;
                            $( '#ep_wishlist_event_count' ).text( eve_count );
                        }
                    } else{
                        show_toast( 'error', response.data.error );
                    }
                }
            });
        }
    });

    // ical export
    $( document ).on( 'click', '#ep_event_ical_export', function() {
        let event_id = $( this ).attr( 'data-event_id' );
        if( event_id ) {
            if( window.location.search ) {
                window.location = window.location.href + '&event='+event_id+'&download=ical';
            } else{
                window.location = window.location.href + '?event='+event_id+'&download=ical';
            }
        }
    });

    // image scaling
    $( document ).on( 'mouseover', '.ep-upcoming-box-card-item', function() {
        $( this ).addClass( 'ep-shadow' );
        $( this ).find( '.ep-upcoming-box-card-thumb img' ).css( 'transform', 'scale(1.1,1.1)' );
    });

    $( document ).on( 'mouseout', '.ep-upcoming-box-card-item', function() {
        $( this ).removeClass( 'ep-shadow' );
        $( this ).find( '.ep-upcoming-box-card-thumb img' ).css("transform", "scale(1,1)");
    });

    // Tabmenu 
    $( document ).on( 'click', '.ep-tab-item a', function(){
        $( '.ep-tab-item a' ).removeClass( 'ep-tab-active' );
        $(this).addClass('ep-tab-active');
        var tagid = $(this).data('tag');
        $( '.ep-tab-content' ).removeClass( 'active' ).addClass( 'ep-item-hide' );
        $( '#'+tagid ).addClass( 'active' ).removeClass( 'ep-item-hide' );
    });  
    // Tabmenu End
    
    //Sub Tab Menu
    // Tabmenu 
    $( document ).on( 'click', '.ep-profile-events-tabs a', function(){
        $( '.ep-profile-events-tabs a' ).removeClass( 'ep-tab-active' );
        $(this).addClass('ep-tab-active');
        var tagid = $(this).data('tag');
        $( '.ep-profile-event-tabs-content' ).addClass( 'ep-item-hide' );
        $( '#'+tagid).removeClass( 'ep-item-hide' );
    });  
    // Tabmenu End
    
});
    
/**
 * Format price with currency position.
 * @param {int|float} price 
 * @param {string} currency 
 * @returns Formatted Price.
 */
function ep_format_price_with_position( price, currency = null ) {
    price = parseFloat( price ).toFixed( 2 );
    if( !currency ) {
        currency = eventprime.currency_symbol;
    }
    position = eventprime.global_settings.currency_position;
    if( position == 'before' ) {
        price = currency + price;
    } else if( position == 'before_space' ) {
        price = currency + ' ' + price;
    } else if( position == 'after' ) {
        price = price + currency;
    } else if( position == 'after_space' ) {
        price = price + ' ' + currency;
    }
    return price;
}

/**
 * Return the translation of errors
 * 
 * @param {string} key
 */
function get_translation_string( key ) {
    let transObj = eventprime.trans_obj;
    if ( transObj.hasOwnProperty( key ) ) {
        return eventprime.trans_obj[key];
    }
}

/**
 * Validate the website url
 * 
 * @param {string} url Website URL
 * 
 * @return {bool} URL is valid or invalid
 */
function is_valid_url( url ) {
    var urlPattern = new RegExp('^(https?:\\/\\/)?' + // validate protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // validate domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // validate OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // validate port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // validate query string
            '(\\#[-a-z\\d_]*)?$', 'i'); // validate fragment locator
    return !!urlPattern.test( url );
}

/**
 * Validate the phone number
 * 
 * @param {string} phone_number Phone Number
 * 
 * @return {bool} Phone Number is valid or invalid
 */
function is_valid_phone( phone_number ) {
    var phonePattern = new RegExp('^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$');
    return !!phonePattern.test( phone_number );
}
/**
 * Validate the email
 * 
 * @param {string} email Email
 * 
 * @return {bool} Email is valid or invalid
 */
function is_valid_email( email ) {
    var emailPattern = new RegExp('[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+');
    return !!emailPattern.test( email );
}
