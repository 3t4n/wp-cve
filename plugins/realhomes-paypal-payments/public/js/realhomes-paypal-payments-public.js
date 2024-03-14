/**
 * PayPal's buttons and payment process handler on the dashboard properties page.
 *
 * 2.0.0
 */
( function ( $ ) {
    $( document ).ready( function () {

        // Render PayPal buttons for each property container
        document.querySelectorAll( '.paypal-button-container' ).forEach( function ( container ) {

            paypal.Buttons( {
                style       : {
                    layout : 'vertical',
                    // color:  'blue',
                    shape   : 'rect',
                    label   : 'pay',
                    tagline : false
                },
                createOrder : function ( data, actions ) {
                    return jQuery.ajax( {
                        url     : ajaxurl, // Replace with the actual path to your PHP file
                        type    : 'POST',
                        data    : {
                            action      : 'realhomes_create_paypal_order', // Action name for the server-side function
                            property_id : parseInt( container.getAttribute( 'data-property-id' ) )
                        },
                        success : function ( orderId ) {
                            // Handle the response from the server (e.g., get the order ID)
                            return orderId;
                        },
                        error   : function ( error ) {
                            // Handle any errors from the server
                            alert( error );
                        }
                    } );

                },
                onApprove   : function ( data, actions ) {

                    jQuery.ajax( {
                        url     : ajaxurl, // Replace with the actual path to your PHP file
                        type    : 'POST',
                        data    : {
                            action   : 'realhomes_complete_order', // Action name for the server-side function
                            order_id : data.orderID
                        },
                        success : function ( response ) {
                            let responseData = JSON.parse( response );
                            if ( responseData.redirect_url ) {
                                window.location.href = responseData.redirect_url;
                            }
                        },
                        error   : function ( error ) {
                            // Handle any errors from the server
                            alert( error );
                        }
                    } );

                },
                onError     : function ( error ) {
                    // Handle errors, e.g., display an error message to the user
                    alert( error );
                }
            } ).render( container );
        } );
    } );
} )( jQuery );