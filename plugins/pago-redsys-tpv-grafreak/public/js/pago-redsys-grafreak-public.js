/**
 * All of the code for your public-facing JavaScript source
 * should reside in this file.
 *
 * @link       http://www.grafreak.net
 * @since      1.0.0
 *
 * @package    Pago_Redsys_Grafreak
 * @subpackage Pago_Redsys_Grafreak/public/js
 */

(function( $ ) {
    'use strict';

    $( document ).ready(
        function() {
            $( 'input#amountTPV' ).keydown(
                function(e) {
                    if (e.keyCode === 190) {
                        e.preventDefault();
                    }
                }
            );
            $( 'input#amountTPV,input#orderNumber' ).focus(
                function(){
                    $( '.tpv-plugin-codigo-pedido span' ).removeClass( 'show' );
                    $( '.tpv-plugin-cantidad-pagar span' ).removeClass( 'show' );
                    $( this ).removeClass( 'warning' );
                }
            );
            $( '#form_tpv_submit' ).click(
                function(e) {
                    e.preventDefault();
                    var error = false;
                    if ($( 'input#amountTPV' ).val() == '' || $( 'input#amountTPV' ).val() == 0) {
                        $( '.tpv-plugin-cantidad-pagar span' ).addClass( 'show' );
                        $( 'input#amountTPV' ).addClass( 'warning' );
                        error = true;
                    }
                    if ($( 'input#orderNumber' ).val().length < 4 || $( 'input#orderNumber' ).val().length > 8) {
                        $( '.tpv-plugin-codigo-pedido span' ).addClass( 'show' );
                        $( 'input#orderNumber' ).addClass( 'warning' );
                        error = true;
                    }
                    if ($( 'input#orderDesc' ).val().length < 0 || $( 'input#orderDesc' ).val().length > 125) {
                        $( '.tpv-plugin-desc-pedido span' ).addClass( 'show' );
                        $( 'input#orderDesc' ).addClass( 'warning' );
                        error = true;
                    }
                    if ( ! error) {
                        $( '.table-form-tpv' ).addClass( 'loading' );
                        var data = {
                            action: 'pago_tpv_ajax',
                            c: $( 'input#amountTPV' ).val().replace(/,/g, '.'),
                            np: $( 'input#orderNumber' ).val(),
                            desc: $( 'input#orderDesc' ).val(),
                            noncesecure: $( 'input#nonce_secure' ).val()
                        };
                        if ( $( '.plugin-form-tpv input#url_ok' ).length > 0 ) {
                            data.url_ok = $( '.plugin-form-tpv input#url_ok' ).val();
                        }
                        if ( $( '.plugin-form-tpv input#url_ok' ).length > 0 ) {
                            data.url_ko = $( '.plugin-form-tpv input#url_ko' ).val();
                        }
                        $.post(
                            the_ajax_script.ajaxurl,
                            data,
                            function(response) {
                                $( 'div.form_tpv' ).html( response );
                                $( '.table-form-tpv' ).removeClass( 'loading' );
                                $( '#form_tpv' ).submit();
                                return true;
                            }
                        );
                    }
                    return false;

                }
            );
        }
    );

})( jQuery );
