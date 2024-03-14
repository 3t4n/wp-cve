/**
 * The Js public-facing functionality of the plugin.
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/public
 */

( function ( $ ) {
    'use strict';

    /**
     * Get selected point after payment method change
     */
    $( document ).ready(
        function () {

            $( 'body' ).on(
                'click',
                'ul.payment_methods li',
                function () {
                    var codOnly = false;
                    var payment = jQuery( this ).find( 'input' );

                    if (payment.val() === 'cod') {
                        codOnly = true;
                    }

                    var data = {
                        action: 'getPointToPayment',
                        cod: codOnly,
                        security: jQuery( '#furgonetka_setPoint' ).val(),
                    };
                    jQuery.post(
                        settings.ajaxurl,
                        data,
                        function ( response ) {

                            if ( response.success == true ) {
                                if ( jQuery( '#select-point-container' ).length ) {
                                    jQuery( '#select-point-container' ).html( response.data.button );
                                    jQuery( '#furgonetkaPoint' ).val( response.data.code );
                                }
                            }
                        }
                    );
                }
            );
            /*woocommerce place order hook*/
            $( "form.woocommerce-checkout" )
            .on(
                'checkout_place_order',
                function() {
                    return furgonetkaFunction( this );
                }
            );
        }
    );
})( jQuery );

var currentService = null;
/**
 * Click in select point link
 */
function openFurgonetkaMap( service, city, street )
{
    var codOnly = false;

    if ( jQuery( 'input[name="payment_method"]:checked' ).val() === 'cod' ) {
        codOnly = true;
    }

    if ( typeof ( window.Furgonetka.Map ) === 'function' ) {
        var pointTypesFilter = [];

        if ( codOnly ) {
            pointTypesFilter.push( 'cod_only' );
        }

        currentService = service;

        jQuery( '#furgonetkaService' ).val( currentService );
        new window.Furgonetka.Map(
            {
                courierServices: [service],
                city: city,
                street: street,
                pointTypesFilter: pointTypesFilter,
                callback: callbackFurgonetka,
            }
        ).view();
        return false;
    } else {
        alert( "Wystąpił problem z załadowaniem mapy. Spróbuj ponownie." );
    }
}

/**
 * Save selected point in session
 */
function callbackFurgonetka( properties )
{
    jQuery( '#selected-point' ).text( properties.name );
    var codOnly = false;

    if ( jQuery( 'input[name="payment_method"]:checked' ).val() === 'cod' ) {
        codOnly = true;
    }

    var data = {
        action: 'savePoint',
        currentService: currentService,
        name: properties.name,
        code: properties.code,
        cod: codOnly,
        security: jQuery( '#furgonetka_setPoint' ).val(),
    };

    jQuery( '#furgonetkaPoint' ).val( properties.code );
    jQuery( '#furgonetkaPointName' ).val( properties.name );
    jQuery.post( settings.ajaxurl, data );
}

/**
 * Check if point is selected before placing order
 */
function furgonetkaFunction( form )
{
    if ( ! jQuery( '#select-point-container' ).length) {
        return true;
    }

    if ( jQuery( '#furgonetkaPoint' ).val() != '' ) {
        return true;
    }
    {
        if ( ! jQuery( '#select-point' ).length ) {
            return true;
        } else {
            jQuery( '#select-point' ).click();
            return false;
        }
    }
}
