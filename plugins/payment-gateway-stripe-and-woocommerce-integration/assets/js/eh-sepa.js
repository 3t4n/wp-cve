jQuery( function( $ ) {
    'use strict';
    

try {
    var stripe = Stripe( eh_sepa_val.key, {apiVersion: eh_sepa_val.version} );
} catch( error ) {
    console.log( error );
    return;
}

var stripe_elements_option = Object.keys( eh_sepa_val.sepa_elements_option ).length ? eh_sepa_val.sepa_elements_option : {},
    elements                = stripe.elements(),
    iban;
    /**
     * Object to handle Stripe payment forms.
     */
    var eh_stripe_form = {
        

        unmountElements: function() {
            iban.unmount( '#eh-stripe-iban-element' );

        },
        mountElements: function() {
            if ( ! $( '#eh-stripe-iban-element' ).length ) {
                return;
            }
            
            iban.mount( '#eh-stripe-iban-element' );
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

            //iban = elements.create( 'iban', { style: elementStyles, classes: elementClasses } );
            iban = elements.create( 'iban', stripe_elements_option, { style: elementStyles, classes: elementClasses } );

           

            /**
             * Only in checkout page we need to delay the mounting of the
             * card as some AJAX process needs to happen before we do.
             */
            if ( 'yes' === eh_sepa_val.is_checkout ) {
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
                $('.sepa-source-errors').html('<ul class="woocommerce_error woocommerce-error eh-sepa-error"><li>'+ event.error.message +'</li></ul>');
              } else {
                $('.sepa-source-errors').html('');
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
            
            eh_stripe_form.createElements(); // create elements into inline form.
            

            $( this.form )
                
                .on( 'click', '#place_order', this.onSubmit )

                // WooCommerce lets us return a false on checkout_place_order_{gateway} to keep the form from submitting
                .on( 'submit checkout_place_order_eh_sepa_stripe' );

        },

        isSepaChosen: function() {
            return $( '#payment_method_eh_sepa_stripe' ).is( ':checked' );
        },
        getSelectedPaymentElement: function() {
            return $( '.payment_methods input[name="payment_method"]:checked' );
        },

        isStripeModalNeeded: function( e ) {     
            
            if ( ! eh_stripe_form.isSepaChosen() ) {
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
            var first_name = $( '#billing_first_name' ).length ? $( '#billing_first_name' ).val() : eh_sepa_val.billing_first_name,
                last_name  = $( '#billing_last_name' ).length ? $( '#billing_last_name' ).val() : eh_sepa_val.billing_last_name,
                owner      = { name: '', address: {}, email: '', phone: '' };

            owner.name = first_name;

            if ( first_name && last_name ) {
                owner.name = first_name + ' ' + last_name;
            } else {
                owner.name = $( '#eh-sepa-pay-data' ).data( 'full-name' );
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
                if ( $( '#eh-sepa-pay-data' ).data( 'email' ).length ) {
                    owner.email = $( '#eh-sepa-pay-data' ).data( 'email' );
                } else {
                    delete owner.email;
                }
            }

            if ( typeof owner.name === 'undefined' || 0 >= owner.name.length ) {
                delete owner.name;
            }

            owner.address.line1       = $( '#billing_address_1' ).val() || eh_sepa_val.billing_address_1;
            owner.address.line2       = $( '#billing_address_2' ).val() || eh_sepa_val.billing_address_2;
            owner.address.state       = $( '#billing_state' ).val()     || eh_sepa_val.billing_state;
            owner.address.city        = $( '#billing_city' ).val()      || eh_sepa_val.billing_city;
            owner.address.postal_code = $( '#billing_postcode' ).val()  || eh_sepa_val.billing_postcode;
            owner.address.country     = $( '#billing_country' ).val()   || eh_sepa_val.billing_country;

            return {
                owner: owner,
            };
        },
 
        /**
         * Handles responses, based on source object.
         *
         * @param {Object} response The `stripe.createSource` response.
         */
        sourceResponse: function( response ) { 
            if ( response.error ) {  console.log('source error');
                $('.sepa-source-errors').html('<ul class="woocommerce_error woocommerce-error eh-sepa-error"><li>'+ response.error.message +'</li></ul>');                return;
                return false;
            }
            else{
                console.log('here');
                var $form = eh_stripe_form.form;
                 console.log($form);
               $('#eh_sepa_source').val(response.source.id);  
               $('#eh_sepa_source_status').val(response.source.status);  

                // $form.append( '<input type="text" class="eh_sepa_source" name="eh_sepa_source" value="' + response.source.id + '"/>' );
                // $form.append( '<input type="text" class="eh_sepa_source_status" name="eh_sepa_source_status" value="' + response.source.status + '"/>' );
               
                $form.submit();
               // return true;               
            }


        },

        onSubmit: function( e ) {
            if ( eh_stripe_form.isStripeModalNeeded() ) {
                e.preventDefault();
            
                var $form = eh_stripe_form.form,
                    address_data = eh_stripe_form.getOwnerDetails(); console.log(address_data.owner);
                    var name_obj = { name : address_data.owner.name,email : address_data.owner.email };    console.log(name_obj);                
                        stripe.createPaymentMethod ({
                        type: 'sepa_debit',
                        sepa_debit:iban,
                       billing_details: name_obj,
                        }).then(function(result) {   

                            if (result.error) {
                                $('.eh-sepa-errors').html(
                                '<ul class="woocommerce_error woocommerce-error eh-stripe-error"><li>'+ result.error.message +'</li></ul>');
                            } 
                            else {


                                $form.find( 'input.eh_sepa_token' ).remove();

                                $form.append( '<input type="hidden"  name="eh_sepa_token" value="' + result.paymentMethod.id + '"/>' );

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
