( function( spobj ) {

    var securepay_notice = function( form, messages ) {
        spobj( '.woocommerce-error, .woocommerce-message' )
            .remove();

        if ( '' !== messages ) {
            form.prepend( '<div class="woocommerce-error" role="alert"><strong>Error!</strong><br>Please check the following:<ul>' + messages + '</ul></div>' );
        }

        form.find( '.input-text, select, input:checkbox' )
            .blur();

        spobj( 'html, body' )
            .animate( {
                    scrollTop: ( spobj( form )
                        .offset()
                        .top - 100 )
                },
                1000
            );
    };

    var securepay_form_handler = function( form ) {
        if ( form.is( ".processing" ) ) {
            return !1;
        }
        return securepay_payment( form );
    };

    var form = spobj( "form.checkout" );
    form.length ? ( form.bind(
            "checkout_place_order_securepay",
            function() {
                return !1;
            }
        ) ) : spobj( "form#order_review" )
        .submit(
            function() {
                var payment_method = spobj( "#order_review input[name=payment_method]:checked" )
                    .val();
                return "securepay" === payment_method ? securepay_form_handler( spobj( this ) ) : void 0;
            }
        );

    var securepay_payment = function( form ) {

        var payment_method = form.find( 'input[name="payment_method"]:checked' )
            .val();

        if ( "securepay" !== payment_method ) {
            return;
        }

        var data = spobj( form )
            .serialize();

        var ajaxUrl = wc_checkout_params.checkout_url;

        spobj.ajax( {
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: data
            } )
            .always(
                function( response ) {
                    /* wp < 5.6 */
                    if ( response.responseJSON ) {
                        response = response.responseJSON;
                    }

                    if ( 'object' === typeof( response ) ) {

                        if ( response.result && response.result == 'failure' ) {
                            if ( response.messages ) {
                                securepay_notice( form, response.messages );
                            }
                            return !1;
                        }
                        if ( response.form ) {
                            spobj( '#frm_securepay_payment' )
                                .remove();
                            spobj( 'body' )
                                .append( response.form );
                            window.success = true;
                            spobj( "#frm_securepay_payment" )
                                .submit();
                        }
                    } else {
                        console.log( 'SecurePay: Backend error. Please contact the web administrator about this issue. Thanks.' );
                    }
                }
            );
        return !1;
    };

    spobj( 'form.checkout' )
        .on(
            'submit',
            function( e ) {
                var payment_method = spobj( 'input[name=payment_method]:checked' )
                    .val();
                if ( "securepay" === payment_method ) {
                    e.preventDefault();
                    return securepay_form_handler( spobj( this ) );
                }
            }
        );

    spobj( 'form#order_review' )
        .on(
            'submit',
            function() {
                return securepay_form_handler( spobj( this ) );
            }
        );

} )( jQuery );