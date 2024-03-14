jQuery( function( $ ) {
    'use strict';
    

try {
    var stripe = Stripe( eh_oxxo_val.key, {apiVersion: eh_oxxo_val.version} );
} catch( error ) {
    console.log( error );
    return;
}

    /**
     * Object to handle Stripe payment forms.
     */
    var eh_stripe_form = {

        /**
         * Initialize e handlers and UI state.
         */
        init: function(  ) {

            if ( $( 'form.woocommerce-checkout' ).length ) {
                this.form = $( 'form.woocommerce-checkout' );
            }

            if ( $( 'form#order_review' ).length ) {
                this.form = $( 'form#order_review' );
            }

            // add payment method page
            if ( $( 'form#add_payment_method' ).length ) {
                this.form = $( 'form#add_payment_method' );
            }
            window.addEventListener( 'hashchange', eh_stripe_form.onHashChange );

            $( this.form )
                
                .on( 'click', '#place_order', this.onSubmit )

                // WooCommerce lets us return a false on checkout_place_order_{gateway} to keep the form from submitting
                .on( 'submit checkout_place_order_stripe' );

        },
        onHashChange: function() { 
            
            var partials = window.location.hash.match( /^#?confirm-oxxo-pi-([^:]+):(.+)$/ );

            if ( ( ! partials || 3 > partials.length  )) { 
                return;
            }

            // Cleanup the URL
            window.location.hash = '';
            if( ( partials ) ) { 
                var intentClientSecret = partials[1];
                var redirectURL        = decodeURIComponent( partials[2] );
                eh_stripe_form.openIntentModal( intentClientSecret, redirectURL );

            }
        },
        openIntentModal: function( intentClientSecret, redirectURL, alwaysRedirect ) {
            
                    stripe.confirmOxxoPayment(
                        intentClientSecret,
                        {
                          payment_method: {  
                            billing_details: {
                              name: $('#billing_first_name').val() + ' ' + $('#billing_last_name').val(),
                              email: $('#billing_email').val(),
                              address: {
                                line1: $('#billing_address_1').val(),
                                city: $('#billing_city').val(),
                                state: $('#billing_state').val(),
                                postal_code: $('#billing_postcode').val(),
                                country: $('#billing_country').val(),
                              },
                            }
                          }
                        }
                    ).then( function( response ) {
                    if ( response.error ) {
                        throw response.error;
                    }
                    //console.log(redirectURL);
                    window.location = redirectURL;
                } )
                .catch( function( error ) { 
                    $('.eh-oxxo-errors').html(
                            '<ul class="woocommerce_error woocommerce-error eh-oxxo-error"><li>'+ error.message +'</li></ul>');
                    if ( alwaysRedirect ) {
                        return window.location = redirectURL;
                    }
                    
                    if ( $( '.eh-besc-error' ).length ) {
                        $( 'html, body' ).animate({
                            scrollTop: ( $( '.eh-besc-error' ).offset().top - 200 )
                        }, 200 );
                    }
                    eh_stripe_form.unblock();
                    $.unblockUI();

                    eh_stripe_form.form && eh_stripe_form.form.removeClass( 'processing' );

                    // Report back to the server.
                    $.get( redirectURL + '&is_ajax' );
                } );
        }, 
        unblock: function() {
            eh_stripe_form.form.unblock();
        }, 
        isStripeChosen: function() {
            return $( '#payment_method_eh_oxxo_stripe' ).is( ':checked' );
        },
        getSelectedPaymentElement: function() {
            return $( '.payment_methods input[name="payment_method"]:checked' );
        },

        isStripeModalNeeded: function( e ) {    
            
            if ( ! eh_stripe_form.isStripeChosen() ) {
                return false;
            }

            if ( $( 'input#terms' ).length === 1 && $( 'input#terms:checked' ).length === 0 ) {
                return false;
            }

            if ( $( '#createaccount' ).is( ':checked' ) && $( '#account_password' ).length && $( '#account_password' ).val() === '' ) {
                return false;
            }

            //check to see if we need to validate shipping address
            if ( $( '#ship-to-different-address-checkbox' ).is( ':checked' ) ) {
                var $required_inputs = $( '.woocommerce-billing-fields .validate-required, .woocommerce-shipping-fields .validate-required' );
            } else {
                var $required_inputs = $( '.woocommerce-billing-fields .validate-required' );
            }

            if ( $required_inputs.length ) {
                var required_error = false;

                $required_inputs.each( function() {
                    if ( $( this ).find( 'input.input-text, select' ).not( $( '#account_password, #account_username' ) ).val() === '' ) {
                        required_error = true;
                    }
                });

                if ( required_error ) {
                    return false;
                }
            }

            return true;
        },
        getOwnerDetails: function() {
            var first_name = $( '#billing_first_name' ).length ? $( '#billing_first_name' ).val() : eh_oxxo_val.billing_first_name,
                last_name  = $( '#billing_last_name' ).length ? $( '#billing_last_name' ).val() : eh_oxxo_val.billing_last_name,
                owner      = { name: '', address: {}, email: '', phone: '' };

            owner.name = first_name;

            if ( first_name && last_name ) {
                owner.name = first_name + ' ' + last_name;
            } else {
                owner.name = '';
            }

            owner.email = $( '#billing_email' ).val()  || eh_oxxo_val.billing_email;
            owner.phone = $( '#billing_phone' ).val() || eh_oxxo_val.billing_phone;

            /* Stripe does not like empty string values so
             * we need to remove the parameter if we're not
             * passing any value.
             */
            if ( typeof owner.phone === 'undefined' || 0 >= owner.phone.length ) {
                delete owner.phone;
            }

            if ( typeof owner.email === 'undefined' || 0 >= owner.email.length ) {
                delete owner.email;
            }

            if ( typeof owner.name === 'undefined' || 0 >= owner.name.length ) {
                delete owner.name;
            }

            owner.address.line1       = $( '#billing_address_1' ).val() || eh_oxxo_val.billing_address_1;
            owner.address.line2       = $( '#billing_address_2' ).val() || eh_oxxo_val.billing_address_2;
            owner.address.state       = $( '#billing_state' ).val()     || eh_oxxo_val.billing_state;
            owner.address.city        = $( '#billing_city' ).val()      || eh_oxxo_val.billing_city;
            owner.address.postal_code = $( '#billing_postcode' ).val()  || eh_oxxo_val.billing_postcode;
            owner.address.country     = $( '#billing_country' ).val()   || eh_oxxo_val.billing_country;
            return owner;

        },

        onSubmit: function( e ) {
            if ( eh_stripe_form.isStripeModalNeeded() ) {
                e.preventDefault();
            
                var $form = eh_stripe_form.form,
                    address_data = eh_stripe_form.getOwnerDetails(); 
                        stripe.createPaymentMethod ({
                            type: 'oxxo',
                           billing_details: address_data,
                        }).then(function(result) {   

                            if (result.error) {
                                $('.eh-oxxo-errors').html(
                                '<ul class="woocommerce_error woocommerce-error eh-stripe-error"><li>'+ result.error.message +'</li></ul>');
                            } 
                            else {


                                $form.find( 'input.eh_oxxo_token' ).remove();

                                $form.append( '<input type="hidden"  name="eh_oxxo_token" value="' + result.paymentMethod.id + '"/>' );

                                $form.submit();
                            }
                    });
                    return false;


                    
                }

                return true;
        }


    };

    eh_stripe_form.init( );
} );
