jQuery(document).ready(function($){
    // 'use strict';

    var wacUpdateTimeout = null;

    wacChange = function(qtyInput) {


        // ask user if they really want to remove this product
        if ( ( wooajaxcart.confirm_zero_qty == 'yes' ) && !wacZeroQuantityCheck(qtyInput) ) {
            return false;
        }

        // when qty is set to zero, then fires default woocommerce remove link
        if ( qtyInput.val() == 0 ) {
            var removeLink = qtyInput.closest('.cart_item').find('.product-remove a');
            removeLink.trigger('click');
            return false;
        }

        // clear previous timeout, if exists
        if ( wacUpdateTimeout !== null ) {
            clearTimeout(wacUpdateTimeout);
        }

        wacUpdateTimeout = setTimeout(function(){
            wacRefreshCart(qtyInput);
        }, wooajaxcart.ajax_timeout);

        return true;
    };

    wacRefreshCart = function(qtyInput) {

        // some themes creates multiple cart forms so it will try to find the correct update button
        if ( qtyInput.closest('.woocommerce-cart-form').length ) {
            var mainSelector = qtyInput.closest('.woocommerce-cart-form');
        }
        else {
            var mainSelector = $(document);
        }
        
        // deal with update cart button
        var updateButton = mainSelector.find("button[name='update_cart']:not(.dgfw-add-gift-button),input[name='update_cart']:not(.dgfw-add-gift-button)");

        updateButton.removeAttr('disabled')
                    .trigger('click')
                    .val( wooajaxcart.updating_text )
                    .prop('disabled', true);
        
        // change the Update cart button
        $("a.checkout-button.wc-forward").addClass('disabled')
                                         .html( wooajaxcart.updating_text );
    };

    // overrided by wac-js-calls.php
    var wacZeroQuantityCheck = function(qtyInput) {
        // when changes quantity to zero
        if ( parseInt(qtyInput.val()) == 0 && !confirm(wooajaxcart.warn_remove_text) ) {
            var newQty = wacResetNewQty(qtyInput);
            qtyInput.val( newQty);

            return false;
        }

        // when empty the quantity input
        if ( qtyInput.val().length === 0 && !confirm(wooajaxcart.warn_remove_text) ) {
            var newQty = wacResetNewQty(qtyInput);
            qtyInput.val( newQty ).trigger('change');

            return false;
        }

        return true;
    };

    var wacResetNewQty = function(qtyInput) {
        var min = qtyInput.attr('min');

        if ( min != 'undefined' ) {
            var newQty = ( ( min > 0 ) ? min : 1 );
        }
        else {
            var newQty = 1;
        };

        return newQty;
    };

    var wacListenQtyChange = function() {
        $(document).on('change', '.qty', function(e){
            // prevent troubles with WooCommerce Product Bundles plugin
            if ( $('.bundle_add_to_cart_button').length ) {
                return true;
            }

            // prevent to set invalid quantity on select
            if ( $(this).is('select') && ( $(this).attr('max') > 0 ) &&
                 ( parseInt($(this).val()) > parseInt($(this).attr('max')) ) ) {
                $(this).val( $(this).attr('max') );

                e.preventDefault();
                return false;
            }

            return wacChange( $(this) );
        });
    };

    var wacFindInputQty = function(btn) {
        var inputQty = btn.parent().parent().parent().find('.qty');

        if ( !inputQty.length ) {
            inputQty = btn.closest('.cart').find('.qty');
        }

        if ( !inputQty.length ) {
            inputQty = btn.closest('.product-quantity').find('.qty');
        }

        return inputQty;
    };

    wacListenQtyButtons = function() {
        var fnIncrement = function(e){
            if ( $(this).hasClass('disabled') ) {
                e.preventDefault();
                return false;
            }

            var inputQty = wacFindInputQty( $(this) );

            if ( inputQty.attr('max') != 'undefined' && parseFloat(inputQty.val()) >= parseFloat(inputQty.attr('max')) ) {
                return false;
            }

            inputQty.val( function(i, oldval) { return ++oldval; });
            inputQty.trigger('change');

            return false;
        };

        var fnDecrement = function(e){
            if ( $(this).hasClass('disabled') ) {
                e.preventDefault();
                return false;
            }

            var inputQty = wacFindInputQty( $(this) );
            var oldVal = inputQty.val();

            if ( inputQty.attr('min') != 'undefined' && parseInt(inputQty.attr('min')) >= oldVal ) {
                return false;
            }

            inputQty.val( --oldVal );
            inputQty.trigger('change');

            return false;
        };

        if ( $('.wac-btn-inc').length ) {
            // fix common style problem in single product page
            if ( !$('#wac-style-prod-qty').length ) {
                $("<style type='text/css' id='wac-style-prod-qty'>.product .quantity { display: inline; float: none !important; margin-right: 0 !important; }</style>").appendTo('head');
            }
 
            $('.wac-btn-inc').off('click.wac2910').on('click.wac2910', fnIncrement);
            $('.wac-btn-sub').off('click.wac2911').on('click.wac2911', fnDecrement);

            if ( $('.grid').length ) {
                setTimeout(wacListenQtyButtons, 500);
            }
        }

        $(document).off('click.wac3000').on('click.wac3000', '.wac-btn-inc', fnIncrement);
        $(document).off('click.wac3001').on('click.wac3001', '.wac-btn-sub', fnDecrement);
    };

    // onload calls
    wacListenQtyChange();
    wacListenQtyButtons();

    $(document.body).on('wc_fragments_refreshed', function(){
        wacListenQtyButtons();
    });

    $( document.body ).on( 'added_to_cart', function( event, fragments, cart_hash ) {
        // prevent woocommerce to reload the page after Add to cart in certain conditions (outside Cart page)
        // the window.location.reload() was defined in woo cart.js script
		if ( $( '.woocommerce-cart-form' ).length == 0 ) {
            $('body').append('<div class="woocommerce-cart-form" style="display: none;"></div>');
		}
    });
});
