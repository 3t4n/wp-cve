/**
 * Gestpay for WooCommerce
 *
 * Copyright: © 2013-2016 Mauro Mascia (info@mauromascia.com)
 * Copyright: © 2017-2021 Axerve S.p.A. - Gruppo Banca Sella (https://www.axerve.com - ecommerce@sella.it)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

(function($){

    function gestpayMyBankSelectWoo() {
        if ( !$().selectWoo ) {
            return;
        }

        // Works on checkout and order_review forms
        var form = 'form.woocommerce-checkout';
        if ( $( 'form#order_review' ).length > 0 ) {
            form = 'form#order_review';
        }

        if ( $( form + ' input[name="payment_method"]:checked' ).attr( 'id' ) != 'payment_method_wc_gateway_gestpay_mybank' ) {
            // Don't run on other payment methods
            return;
        }

        $( 'select#gestpay-mybank-banklist' ).selectWoo({
            matcher: function (params, data) {
                // Thanks to Yuray https://stackoverflow.com/a/31626588/1992799

                // If there are no search terms, return all of the data
                if ($.trim(params.term) === '') {
                    return data;
                }

                var keywords = (params.term).split(" ");

                // check if data.text contains all of keywords, if some is missing, return null
                for (var i = 0; i < keywords.length; i++) {
                    if (((data.text).toUpperCase()).indexOf((keywords[i]).toUpperCase()) == -1) {
                        // Return `null` if the term should not be displayed
                        return null;
                    }
                }

                // If here, data.text contains all keywords, so return it.
                return data;
            }
        });

        // Be sure there is enaugh space for the asterisk of the required field
        var $span = $( 'select#gestpay-mybank-banklist+.select2-container' );
        if ( $span.length == 1 ) {
            $span.width( $span.width() - 20 );
        }
    }

    // Runs when selecting or when reloading the already selected method.
    $( document.body ).on( 'payment_method_selected updated_checkout', function() {
        gestpayMyBankSelectWoo();
    });

})(jQuery);