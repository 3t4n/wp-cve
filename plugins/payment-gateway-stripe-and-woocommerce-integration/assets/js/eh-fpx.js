jQuery( function( $ ) {
    'use strict';
    

try {
    var stripe = Stripe( eh_fpx_val.key, {apiVersion: eh_fpx_val.version} );
} catch( error ) {
    console.log( error );
    return;
}

var stripe_elements_option = Object.keys( eh_fpx_val.fpx_elements_option ).length ? eh_fpx_val.fpx_elements_option : {},
    elements                = stripe.elements(),
    iban;
    /**
     * Object to handle Stripe payment forms.
     */
    var eh_stripe_form = {
        

        unmountElements: function() {
            iban.unmount( '#eh-stripe-fpx-element' );

        },
        mountElements: function() {
            if ( ! $( '#eh-stripe-fpx-element' ).length ) {
                return;
            }
            
            iban.mount( '#eh-stripe-fpx-element' );
        },


        createElements: function() { 
            var elementStyles = {
                base: {
                    iconColor: '#666EE8',
                    color: '#31325F',
                    fontSize: '15px',
                    '::placeholder': {
                        color: '#CFD7E0',
                    }
                }
            };

            var elementClasses = {
                focus: 'focused',
                empty: 'empty',
                invalid: 'invalid',
            };

            iban = elements.create( 'fpxBank', { accountHolderType: 'individual', style: elementStyles, classes: elementClasses } );

           

            /**
             * Only in checkout page we need to delay the mounting of the
             * card as some AJAX process needs to happen before we do.
             */
            if ( 'yes' === eh_fpx_val.is_checkout ) {
                $( document.body ).on( 'updated_checkout', function() {
                    // Don't mount elements a second time.
                    if ( iban ) {
                        eh_stripe_form.unmountElements();
                    }

                    eh_stripe_form.mountElements();
                } );

            } else if ( $( 'form#add_payment_method' ).length || $( 'form#order_review' ).length ) {
                    eh_stripe_form.mountElements();
            }

            iban.on('change', function(event) {
              if (event.error) { 
                    $('.eh-fpx-errors').html('<ul class="woocommerce_error woocommerce-error eh-fpx-error"><li>'+ event.error.message +'</li></ul>');
                } else {
                $('.eh-fpx-errors').html('');
              }
            });
        },

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

            this.stripe_submit = false;

            window.addEventListener( 'hashchange', eh_stripe_form.onHashChange );
            var hash = window.location.hash.substr(1); // pay for order
            console.log(hash);
            var result = hash.split('&').reduce(function (res, item) {
                var parts = item.split('='); 
                res[parts[0]] = parts[1];
                return res;
            }, {});

            if(typeof result.response != 'undefined'){ 
                eh_stripe_form.onHashChange();
            }
                        
            eh_stripe_form.createElements(); // create elements into inline form.            

            $( this.form )
                
                .on( 'click', '#place_order', this.onSubmit )

                // WooCommerce lets us return a false on checkout_place_order_{gateway} to keep the form from submitting
                .on( 'submit checkout_place_order_eh_fpx_stripe' );

        },
        onHashChange: function() { 
            
            var partials = window.location.hash.match( /^#?confirm-fpx-pi-([^:]+):(.+)$/ );

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
            
                    stripe.confirmFpxPayment(
                        intentClientSecret,
                        {
                          payment_method: {
                            fpx: iban
                          },
                          return_url:redirectURL
                        }
                    ).then( function( response ) {
                    if ( response.error ) {
                        throw response.error;
                    }

                    if ( 'requires_capture' !== response.paymentIntent.status && 'succeeded' !== response.paymentIntent.status  && 'processing' !== response.paymentIntent.status ) {
                        return;
                    }

                    window.location = redirectURL;
                } )
                .catch( function( error ) { 
                    $('.eh-fpx-errors').html(
                            '<ul class="woocommerce_error woocommerce-error eh-fpx-error"><li>'+ error.message +'</li></ul>');
                    if ( alwaysRedirect ) {
                        return window.location = redirectURL;
                    }
                    
                    if ( $( '.eh-besc-error' ).length ) {
                        $( 'html, body' ).animate({
                            scrollTop: ( $( '.eh-besc-error' ).offset().top - 200 )
                        }, 200 );
                    }
                    eh_stripe_form.unblock();
                    $.unblockUI(); // If arriving via Payment Request Button.


                    eh_stripe_form.form && eh_stripe_form.form.removeClass( 'processing' );

                    // Report back to the server.
                    $.get( redirectURL + '&is_ajax' );
                } );
        },        
        isFpxChosen: function() {
            return $( '#payment_method_eh_fpx_stripe' ).is( ':checked' );
        },
        getSelectedPaymentElement: function() {
            return $( '.payment_methods input[name="payment_method"]:checked' );
        },

        isStripeModalNeeded: function( e ) {     
            
            if ( ! eh_stripe_form.isFpxChosen() ) {
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

        block: function() {
            eh_stripe_form.form.block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        },

        unblock: function() {
            eh_stripe_form.form.unblock();
        },

        onClose: function() {
            eh_stripe_form.unblock();
        },

        getOwnerDetails: function() {
            var first_name = $( '#billing_first_name' ).length ? $( '#billing_first_name' ).val() : eh_fpx_val.billing_first_name,
                last_name  = $( '#billing_last_name' ).length ? $( '#billing_last_name' ).val() : eh_fpx_val.billing_last_name,
                owner      = { name: '', address: {}, email: '', phone: '' };

            owner.name = first_name;

            if ( first_name && last_name ) {
                owner.name = first_name + ' ' + last_name;
            } else {
                owner.name = $( '#eh-fpx-pay-data' ).data( 'full-name' );
            }

            owner.email = $( '#billing_email' ).val();
            owner.phone = $( '#billing_phone' ).val();

            /* Stripe does not like empty string values so
             * we need to remove the parameter if we're not
             * passing any value.
             */
            if ( typeof owner.phone === 'undefined' || 0 >= owner.phone.length ) {
                delete owner.phone;
            }

            if ( typeof owner.email === 'undefined' || 0 >= owner.email.length ) {
                if ( $( '#eh-fpx-pay-data' ).data( 'email' ).length ) {
                    owner.email = $( '#eh-fpx-pay-data' ).data( 'email' );
                } else {
                    delete owner.email;
                }
            }

            if ( typeof owner.name === 'undefined' || 0 >= owner.name.length ) {
                delete owner.name;
            }

            owner.address.line1       = $( '#billing_address_1' ).val() || eh_fpx_val.billing_address_1;
            owner.address.line2       = $( '#billing_address_2' ).val() || eh_fpx_val.billing_address_2;
            owner.address.state       = $( '#billing_state' ).val()     || eh_fpx_val.billing_state;
            owner.address.city        = $( '#billing_city' ).val()      || eh_fpx_val.billing_city;
            owner.address.postal_code = $( '#billing_postcode' ).val()  || eh_fpx_val.billing_postcode;
            owner.address.country     = $( '#billing_country' ).val()   || eh_fpx_val.billing_country;

            return owner;
        },

        onSubmit: function( e ) {
            if ( eh_stripe_form.isStripeModalNeeded() ) {
                e.preventDefault();
                var $form = eh_stripe_form.form,
                address_data = eh_stripe_form.getOwnerDetails(); 
                    stripe.createPaymentMethod ({
                        type: 'fpx',
                        fpx:iban,
                       billing_details: address_data,
                    }).then(function(result) {   

                        if (result.error) { 
                            $('.eh-fpx-errors').html('<ul class="woocommerce_error woocommerce-error eh-fpx-error"><li>'+ result.error.message +'</li></ul>');
                        } 
                        else {
                            $form.submit();
                        }
                });


                    
            }
            else{
                return true;
            }
                
        }



    };

    eh_stripe_form.init( );
} );
