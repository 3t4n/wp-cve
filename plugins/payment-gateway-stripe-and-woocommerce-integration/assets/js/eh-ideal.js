jQuery( function( $ ) {
    'use strict';
    

try {
    var stripe = Stripe( eh_ideal_val.key, {apiVersion: eh_ideal_val.version} );
} catch( error ) {
    console.log( error );
    return;
}

var stripe_elements_option = Object.keys( eh_ideal_val.ideal_elements_option ).length ? eh_ideal_val.ideal_elements_option : {},
    elements                = stripe.elements(),
    iban;
    /**
     * Object to handle Stripe payment forms.
     */
    var eh_stripe_form = {
        

        unmountElements: function() {
            iban.unmount( '#eh-stripe-ideal-element' );

        },
        mountElements: function() {
            if ( ! $( '#eh-stripe-ideal-element' ).length ) {
                return;
            }
            
            iban.mount( '#eh-stripe-ideal-element' );
        },


        createElements: function() { 
            var elementStyles = {
                base: {
                    iconColor: '#666EE8',
                    color: '#31325F',
                    fontSize: '15px',
                }
            };

            var elementClasses = {
                focus: 'focused',
                empty: 'empty',
                invalid: 'invalid',
            };

            iban = elements.create( 'idealBank', stripe_elements_option, { style: elementStyles, classes: elementClasses } );

           

            /**
             * Only in checkout page we need to delay the mounting of the
             * card as some AJAX process needs to happen before we do.
             */
            if ( 'yes' === eh_ideal_val.is_checkout ) {
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
                $('.ideal-source-errors').html('<ul class="woocommerce_error woocommerce-error eh-ideal-error"><li>'+ event.error.message +'</li></ul>');
              } else {
                $('.ideal-source-errors').html('');
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
            
            eh_stripe_form.createElements(); // create elements into inline form.
            

            $( this.form )
                
                .on( 'click', '#place_order', this.onSubmit )

                // WooCommerce lets us return a false on checkout_place_order_{gateway} to keep the form from submitting
                .on( 'submit checkout_place_order_eh_ideal_stripe' );

        },
        onHashChange: function() { 
            
            var partials = window.location.hash.match( /^#?confirm-ideal-pi-([^:]+):(.+)$/ );

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
            
                    stripe.confirmIdealPayment(
                        intentClientSecret,
                        {
                          payment_method: {
                            ideal: iban,
                            billing_details: {
                              name:$( '#eh-stripe-ideal-accountholder-name' ).length ? $( '#eh-stripe-ideal-accountholder-name' ).val() : $('#billing_first_name').val(),
                            }
                          },
                          return_url: redirectURL,
                        }
                    ).then( function( response ) {
                    if ( response.error ) {
                        throw response.error;
                    }

                    if ( 'requires_capture' !== response.paymentIntent.status && 'succeeded' !== response.paymentIntent.status  && 'processing' !== response.paymentIntent.status ) {
                        //return;
                    }
                    
                    //window.location = redirectURL;
                } )
                .catch( function( error ) { 
                    $('.eh-ideal-errors').html('<ul class="woocommerce_error woocommerce-error eh-ideal-error"><li>'+ error.message +'</li></ul>');
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
        isIdealChosen: function() {
            return $( '#payment_method_eh_ideal_stripe' ).is( ':checked' );
        },
        getSelectedPaymentElement: function() {
            return $( '.payment_methods input[name="payment_method"]:checked' );
        },

        isStripeModalNeeded: function( e ) {     
            
            if ( ! eh_stripe_form.isIdealChosen() ) {
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

        createSource: function() {
            var extra_details = eh_stripe_form.getOwnerDetails();

            // Handle SEPA Direct Debit payments.
            if ( eh_stripe_form.isIdealChosen() ) { 
                extra_details.currency = $( '#eh-ideal-pay-data' ).data( 'currency' );
                //extra_details.mandate  = { notification_method: eh_ideal_val.ideal_mandate_notification };
                extra_details.type     = 'ideal_debit';

                return stripe.createSource( iban, extra_details ).then( eh_stripe_form.sourceResponse );
            }

        },

        getOwnerDetails: function() {
            var first_name = $( '#billing_first_name' ).length ? $( '#billing_first_name' ).val() : eh_ideal_val.billing_first_name,
                last_name  = $( '#billing_last_name' ).length ? $( '#billing_last_name' ).val() : eh_ideal_val.billing_last_name,
                owner      = { name: '', address: {}, email: '', phone: '' };

            owner.name = first_name;

            if ( first_name && last_name ) {
                owner.name = first_name + ' ' + last_name;
            } else {
                owner.name = $( '#eh-ideal-pay-data' ).data( 'full-name' );
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
                if ( $( '#eh-ideal-pay-data' ).data( 'email' ).length ) {
                    owner.email = $( '#eh-ideal-pay-data' ).data( 'email' );
                } else {
                    delete owner.email;
                }
            }

            if ( typeof owner.name === 'undefined' || 0 >= owner.name.length ) {
                delete owner.name;
            }

            owner.address.line1       = $( '#billing_address_1' ).val() || eh_ideal_val.billing_address_1;
            owner.address.line2       = $( '#billing_address_2' ).val() || eh_ideal_val.billing_address_2;
            owner.address.state       = $( '#billing_state' ).val()     || eh_ideal_val.billing_state;
            owner.address.city        = $( '#billing_city' ).val()      || eh_ideal_val.billing_city;
            owner.address.postal_code = $( '#billing_postcode' ).val()  || eh_ideal_val.billing_postcode;
            owner.address.country     = $( '#billing_country' ).val()   || eh_ideal_val.billing_country;

            return owner;
        },
 
        /**
         * Handles responses, based on source object.
         *
         * @param {Object} response The `stripe.createSource` response.
         */
        sourceResponse: function( response ) { 
            if ( response.error ) {  
                $('.ideal-source-errors').html('<ul class="woocommerce_error woocommerce-error eh-ideal-error"><li>'+ response.error.message +'</li></ul>');                return;
                return false;
            }
            else{
                
                var $form = eh_stripe_form.form;
                 
               $('#eh_ideal_source').val(response.source.id);  
               $('#eh_ideal_source_status').val(response.source.status);  

                // $form.append( '<input type="text" class="eh_ideal_source" name="eh_ideal_source" value="' + response.source.id + '"/>' );
                // $form.append( '<input type="text" class="eh_ideal_source_status" name="eh_ideal_source_status" value="' + response.source.status + '"/>' );
               
                $form.submit();
               // return true;               
            }


        },

        onSubmit: function( e ) {
            if ( eh_stripe_form.isStripeModalNeeded() ) {
                e.preventDefault();
                var $form = eh_stripe_form.form,
                address_data = eh_stripe_form.getOwnerDetails(); 
                    stripe.createPaymentMethod ({
                        type: 'ideal',
                        ideal:iban,
                       billing_details: address_data,
                    }).then(function(result) {   

                        if (result.error) { 
                            $('.eh-ideal-errors').html(
                            '<ul class="woocommerce_error woocommerce-error eh-stripe-error"><li>'+ result.error.message +'</li></ul>');
                        } 
                        else {


                            $form.find( 'input.eh_sofort_token' ).remove();

                            $form.append( '<input type="hidden"  name="eh_ideal_token" value="' + result.paymentMethod.id + '"/>' );

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
