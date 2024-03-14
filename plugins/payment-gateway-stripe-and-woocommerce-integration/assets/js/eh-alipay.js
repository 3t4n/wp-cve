jQuery( function( $ ) {
    'use strict';
    

try {
    var stripe = Stripe( eh_alipay_val.key, {apiVersion: eh_alipay_val.version} );
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

            $( this.form )
                
                .on( 'click', '#place_order', this.onSubmit )

                // WooCommerce lets us return a false on checkout_place_order_{gateway} to keep the form from submitting
                .on( 'submit checkout_place_order_stripe' );

        },

        isStripeChosen: function() {
            return $( '#payment_method_eh_alipay_stripe' ).is( ':checked' );
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

        onSubmit: function( e ) {
            if ( eh_stripe_form.isStripeModalNeeded() ) {
                e.preventDefault();
            
                var $form = eh_stripe_form.form,
                    $data = $( '#eh-stripe-pay-data' );
                    
                        stripe.createPaymentMethod ({
                            type: 'alipay',
                        }).then(function(result) {   console.log(result); 

                            if (result.error) {
                                $('.stripe-source-errors').html(
                                '<ul class="woocommerce_error woocommerce-error eh-stripe-error"><li>'+ result.error.message +'</li></ul>');
                            } 
                            else {


                                $form.find( 'input.eh_alipay_token' ).remove();

                                $form.append( '<input type="hidden" class="eh_alipay_token" name="eh_alipay_token" value="' + result.paymentMethod.id + '"/>' );

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
