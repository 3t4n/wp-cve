/**
 * Main JS
 */
;( function ( $ ) {
    'use strict';

    // On document ready
    $( document ).ready( function () {

        if($('.single-product  .cart').length && $('.single-product  .cart').attr('method') == 'post'){
            product_type = 'simple';
        }

        if($('.single-product .variations_form.cart').length){
            product_type = 'variable';
        }

        if($('.single-product  .cart.grouped_form').length){
            product_type = 'grouped';
        }

        if(product_type == 'variable'){
             add_to_cart_selector = '.woocommerce-variation-add-to-cart-enabled .single_add_to_cart_button';
        } else if(product_type == 'simple'){
             add_to_cart_selector = '.single_add_to_cart_button';
        }

        if( !add_to_cart_selector ){
            return;
        }

        // Ajax add to cart
        $(document).on( 'click', add_to_cart_selector, function (e) {
            e.preventDefault();

            var $this = $(this),
                $form           = $this.closest('form.cart'),
                product_qty     = $form.find('input[name=quantity]').val() || 1,
                product_id      = $form.find('input[name=product_id]').val() || $this.val(),
                variation_id    = $form.find('input[name=variation_id]').val() || 0;

            /* For Variation product */    
            var item = {},
                variations = $form.find( 'select[name^=attribute]' );
                if ( !variations.length) {
                    variations = $form.find( '[name^=attribute]:checked' );
                }
                if ( !variations.length) {
                    variations = $form.find( 'input[name^=attribute]' );
                }

                variations.each( function() {
                    var $thisitem = $( this ),
                        attributeName = $thisitem.attr( 'name' ),
                        attributevalue = $thisitem.val(),
                        index,
                        attributeTaxName;
                        $thisitem.removeClass( 'error' );
                    if ( attributevalue.length === 0 ) {
                        index = attributeName.lastIndexOf( '_' );
                        attributeTaxName = attributeName.substring( index + 1 );
                        $thisitem.addClass( 'required error' );
                    } else {
                        item[attributeName] = attributevalue;
                    }
                });

            var data = {
                action: 'wpbforwpbakery_ajax_add_to_cart',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
                variation: item,
            };

            $( document.body ).trigger('adding_to_cart', [$this, data]);

            $.ajax({
                type: 'post',
                url: woocommerce_params.ajax_url,
                data: data,

                beforeSend: function (response) {
                    $this.removeClass('added').addClass('loading');
                },

                complete: function (response) {
                    $this.addClass('added').removeClass('loading');
                },

                success: function (response) {
                    if ( response.error & response.product_url ) {
                        window.location = response.product_url;
                        return;
                    } else {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $this]);

                        $('.wc-forward').hide();

                        // Genrate Notice Popup
                        $.ajax( {
                            type: 'POST',
                            url: woocommerce_params.ajax_url,
                            data: {
                                action: 'wpbforwpbakery_ajax_add_to_cart_notice',
                            },
                            success: function( response ) {
                                if ( ! response ) {
                                    return;
                                }

                                tb_remove();

                                $( '#wpbforwpbakery_notice_popup' ).html( response );

                                tb_show( '', '#TB_inline?&amp;width=600&amp;inlineId=wpbforwpbakery_notice_popup' );
                            },
                            error: function(errorThrown) {
                                console.log(errorThrown);
                            },
                        } );
                    }
                },

            });

            return false;
        });
        
    });
})(jQuery);