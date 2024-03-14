/**
 * Widget constant.
 */
const toChatBeWidget = {
    popupStatus: function() {
        return jQuery( '.tochatbe-widget__body' ).data( 'tochatbe-popup-status' );
    },
    trigger: function() {
        var popup = jQuery( '.tochatbe-widget__body' );

        if ( '0' === this.popupStatus() ) { // not open
            popup.data( 'tochatbe-popup-status', '1' );
            popup.slideUp();
        } else { // opened
            popup.data( 'tochatbe-popup-status', '0' );
            popup.slideDown();
        }
    },
    autoPopup: function( timeInSec = 5 ) {
        setTimeout( function() {
            toChatBeWidget.trigger();
        }, Number( timeInSec * 1000 ) );
    },
    is_mobile: {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return ( toChatBeWidget.is_mobile.Android() || toChatBeWidget.is_mobile.BlackBerry() || toChatBeWidget.is_mobile.iOS() || toChatBeWidget.is_mobile.Opera() || toChatBeWidget.is_mobile.Windows());
        },
    },
    send: function( number = '', message = '' ) {
        if ( this.is_mobile.any() ) {
            window.open( 'https://api.whatsapp.com/send?phone=' + number + '&text=' + message + '' );
        } else {
            window.open( 'https://api.whatsapp.com/send?phone=' + number + '&text=' + message + '' );
        }

        return true;
    },
}

;( function( $ ) {
    "use strict";

    $( document ).ready( function() {

        function tochatbe_click_log( message = '', cotactedTo = '' ) {
            $.ajax( {
                url: tochatbe.ajax_url,
                method: 'POST',
                data: {
                    'action':       'tochatbe_click_log',
                    'message':      message,
                    'contacted_to': cotactedTo,
                }
            } );
        }

        /**
         * Trigger the popup.
         */
        $( '[data-tochatbe-trigger]' ).on( 'click', function() {
            toChatBeWidget.trigger();
        } );

        /**
         * Auto popup.
         */
        if ( 'yes' === tochatbe.auto_popup_status ) {
            toChatBeWidget.autoPopup( tochatbe.auto_popup_delay );
        }

        /**
         * Send message.
         */
        $( '[data-tochatbe-person]' ).on( 'click', function() {
            var number  = jQuery( this ).data( 'tochatbe-number' );
            var message = jQuery( this ).data( 'tochatbe-message' );

            // GDPR check
            if ( 'yes' === tochatbe.grpr_status && false === $( '#tochatbe-gdpr-checkbox' ).is( ':checked' ) ) {
                $( '.tochatbe-gdpr' ).addClass( 'error' );
                return;
            }

            tochatbe_click_log( message, number );
            toChatBeWidget.send( number, message );

        } );

        /**
         * Remove GDPR check error border.
         */
        $( '#tochatbe-gdpr-checkbox' ).on( 'change', function() {
            if ( true === $( '#tochatbe-gdpr-checkbox' ).is( ':checked' ) ) {
                $( '.tochatbe-gdpr' ).removeClass( 'error' );
            }
        } );

        /**
         * Type and chat.
         */
        $( '.tochatbe-input-icon' ).on( 'click', function() {
            var number  = tochatbe.type_and_chat_number;
            var message = $( '#tochatbe-type-and-chat-input' ).val();

            tochatbe_click_log( message, number );
            toChatBeWidget.send( number, message );
        } );

        // Remove blinking cursor on input focus
        $( '#tochatbe-type-and-chat-input' ).on( 'focus', function() {
            $( '.tochatbe-blinking-cursor' ).hide();
        } );

        /**
         * Google event analytics.
         */
        $( '[data-tochatbe-person], .tochatbe-input-icon, .tochatbe_jwi' ).on( 'click', function() {
            if ( 'yes' != tochatbe.ga_status ) {
                return;
            }

            var gaCategory = tochatbe.ga_category;
            var gaAction   = tochatbe.ga_action;
            var gaLabel    = tochatbe.ga_label;

            try {
                gtag( 
                    'event', 
                    gaAction, {
                        'event_category': gaCategory,
                        'event_label': gaLabel,
                    } 
                );
            } catch ( error ) {
                if ( error ) {
                    try {
                        ga( 
                            'send', 
                            'event', 
                            gaCategory, 
                            gaAction, 
                            gaLabel
                        );
                    } catch ( error ) {
                        if ( error ) {
                            try {
                                _gaq.push([ 
                                    '_trackEvent', 
                                    gaCategory, 
                                    gaAction, 
                                    gaLabel 
                                ] );
                            } catch ( error ) {
                                if ( error ) {
                                    try {
                                        dataLayer.push({
                                            'event': 'customEvent',
                                            'eventCategory': gaCategory,
                                            'eventAction': gaAction,
                                            'eventLabel': gaLabel
                                        } );
                                    } catch ( error ) {
                                        window.console && console.log( 'TOCHAT.BE: No Google analytics or tag script found.' );
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
        } );

        /**
         * Facebook event analytics.
         */
         $( '[data-tochatbe-person], .tochatbe-input-icon, .tochatbe_jwi' ).on( 'click', function() {
            if ( 'yes' != tochatbe.fb_status ) {
                return;
            }

            var fbPixeled    = false;
            var fbEventName  = tochatbe.fb_event_name;
            var fbEventLabel = tochatbe.fb_event_label;

            try {
                if ( ! fbPixeled ) {
                    fbq( 'trackCustom', 'TOCHATE.BE', {
                        event: fbEventName,
                        account: fbEventLabel
                    } );

                    fbPixeled = true;
                }
            } catch ( error ) {
                window.console && console.log( 'TOCHATE.BE facebook pixel not installed. ' + error.message );
            }
            
        } );

    } ); // Document ready end.

} )( jQuery );