jQuery( function( $ ) {
    'use strict';
    

try {
    var stripe = Stripe( eh_affirm_val.key, {apiVersion: eh_affirm_val.version} );
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

            else if ( $( 'form#order_review' ).length ) {
                this.form = $( 'form#order_review' );
            }

            // add payment method page
            else if ( $( 'form#add_payment_method' ).length ) {
                this.form = $( 'form#add_payment_method' );
            }
            else{
                return;
            }

            $( this.form )
                
                .on( 'click', '#place_order', this.onSubmit )

                /* WooCommerce lets us return a false on checkout_place_order_{gateway} to keep the form from submitting */
                .on( 'submit checkout_place_order_stripe' );

        },

        isStripeChosen: function() {
            return $( '#payment_method_eh_affirm_stripe' ).is( ':checked' );
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

            /*check to see if we need to validate shipping address*/
            if ( $( '#ship-to-different-address-checkbox' ).is( ':checked' ) ) {
                var $required_inputs = $( '.woocommerce-billing-fields .validate-required, .woocommerce-shipping-fields .validate-required' );
            } else {
                var $required_inputs = $( '.woocommerce-billing-fields .validate-required' );
            }

            if ( $required_inputs.length ) {
                var required_error = false;

                $required_inputs.each( function() {
                    if ( $( this ).find( 'input.input-text, select' ).not('#account_password, #account_username').val() === '' ) {
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
            var first_name = $( '#billing_first_name' ).length ? $( '#billing_first_name' ).val() : eh_affirm_val.billing_first_name,
                last_name  = $( '#billing_last_name' ).length ? $( '#billing_last_name' ).val() : eh_affirm_val.billing_last_name,
                owner      = { name: '', address: {}, email: '', phone: '' };

            owner.name = first_name;

            if ( first_name && last_name ) {
                owner.name = first_name + ' ' + last_name;
            } else {
                owner.name = '';
            }

            owner.email = $( '#billing_email' ).val()  || eh_affirm_val.billing_email;
            owner.phone = $( '#billing_phone' ).val() || eh_affirm_val.billing_phone;

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

            owner.address.line1       = $( '#billing_address_1' ).val() || eh_affirm_val.billing_address_1;
            owner.address.line2       = $( '#billing_address_2' ).val() || eh_affirm_val.billing_address_2;
            owner.address.state       = $( '#billing_state' ).val()     || eh_affirm_val.billing_state;
            owner.address.city        = $( '#billing_city' ).val()      || eh_affirm_val.billing_city;
            owner.address.postal_code = $( '#billing_postcode' ).val()  || eh_affirm_val.billing_postcode;
            owner.address.country     = $( '#billing_country' ).val()   || eh_affirm_val.billing_country;
            return owner;

        },

        onSubmit: function( e ) {
            if ( eh_stripe_form.isStripeModalNeeded() ) {
                e.preventDefault();
            
                var $form = eh_stripe_form.form,
                    address_data = eh_stripe_form.getOwnerDetails(); 
                    var affirm = { country : address_data.address.country };                    
                        stripe.createPaymentMethod ({
                            type: 'affirm',
                           billing_details: address_data,
                        }).then(function(result) {   

                            if (result.error) {
                                $('.eh-affirm-errors').html(
                                '<ul class="woocommerce_error woocommerce-error eh-stripe-error"><li>'+ result.error.message +'</li></ul>');
                            } 
                            else {


                                $form.find( 'input.eh_affirm_token' ).remove();

                                $form.append( '<input type="hidden"  name="eh_affirm_token" value="' + result.paymentMethod.id + '"/>' );

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
