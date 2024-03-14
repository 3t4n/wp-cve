
var public_woo_amc_show;
var public_woo_amc_get_cart;


(function( $ ) {

    var PerfectScrollbar_line = '';
    var nonce = wooAmcVars.nonce;

    $(document).ready(function(){
        if (wooAmcVars.cart_type!='center') {
            PerfectScrollbar_line = new PerfectScrollbar('.woo_amc_items_scroll');
        }

        $('body').on('click','.woo_amc_close, .woo_amc_bg',function () {
            woo_amc_hide();
        });
        $('body').on('click','.woo_amc_open_active',function () {
            woo_amc_show();
        });
        
        $('body').on( 'added_to_cart', function(e, fragments, cart_hash, this_button){
            woo_amc_show();
            woo_amc_get_cart();
        });

        $('body').on('click','.woo_amc_item_delete',function () {
            var key = $(this).data('key');
            var item = $(this).parents('.woo_amc_item_wrap');
            woo_amc_remove_item(key,item);
        });

        $('body').on('click','.woo_amc_item_quanity_update',function () {
            woo_amc_quanity_update_buttons($(this));
        });

        $('body').on('blur','.woo_amc_item_quanity',function () {
            woo_amc_quanity_update($(this));
        });

        $(document).on('click', '.single_add_to_cart_button', function (e) {
            e.preventDefault();
            var $button = $(this),
                $form = $button.closest('form.cart'),
                product_id = $form.find('input[name=add-to-cart]').val() || $button.val();

            if (!product_id)
                return;

            if ($button.is('.disabled'))
                return;

            var data = {
                action: 'woo_amc_add_to_cart',
                'add-to-cart': product_id,
            };

            $form.serializeArray().forEach(function (element) {
                data[element.name] = element.value;
            });

            $(document.body).trigger('adding_to_cart', [$button, data]);

            jQuery.ajax({
                type: 'post',
                url: wooAmcVars.ajaxurl,
                data: data,
                beforeSend: function (response) {
                    $button.removeClass('added').addClass('loading');
                },
                complete: function (response) {
                    $button.addClass('added').removeClass('loading');
                },
                success: function (response) {

                    if (response.error & response.product_url) {
                        window.location = response.product_url;
                        return;
                    } else {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                        $('.woocommerce-notices-wrapper').empty().append(response.notices);

                    }
                },
            });

            return false;

        });
    });

    function woo_amc_hide() {
        if (wooAmcVars.cart_type=='center') {
            $('.woo_amc_container_wrap_center').animate({opacity:'hide'},300);
        } else {
            $('.woo_amc_container_wrap').removeClass('woo_amc_show');
            $('.woo_amc_bg').removeClass('woo_amc_show');
        }
    }

    function woo_amc_show() {
        if (wooAmcVars.cart_type=='center') {
            $('.woo_amc_container_wrap_center').animate({opacity:'show'},300);
        } else {
            $('.woo_amc_container_wrap').addClass('woo_amc_show');
            $('.woo_amc_bg').addClass('woo_amc_show');
        }

    }

    function woo_amc_get_cart() {
        $( '.woo_amc_items_wrap' ).addClass( 'woo_amc_items_wrap_loading' );
        var data = {
            action: 'woo_amc_get_cart',
            type: wooAmcVars.cart_type,
        };
        $.post( wooAmcVars.ajaxurl, data, function( response ) {
            var cart_response = JSON.parse( response );
            $( '.woo_amc_items' ).html( cart_response['html'] );
            woo_amc_perfect_scrollbar();
            $('.woo_amc_footer_products .woo_amc_value, .woo_amc_open_count').html(cart_response['count']);
            $('.woo_amc_footer_total .woo_amc_value').html(cart_response['total']);
            if (!$('.woo_amc_open').hasClass('woo_amc_open_active')){
                $('.woo_amc_open').addClass('woo_amc_open_active');
            }
            $( '.woo_amc_items_wrap' ).removeClass( 'woo_amc_items_wrap_loading' );
            nonce = cart_response['nonce'];
        } );
    }

    var woo_amc_quanity_update_send = true;
    function woo_amc_quanity_update_buttons( el ) {
        if(woo_amc_quanity_update_send){
            $( '.woo_amc_items_wrap' ).addClass( 'woo_amc_items_wrap_loading' );
            woo_amc_quanity_update_send = false;
            var wrap = $(el).parents('.woo_amc_item_wrap');
            var input = $(wrap).find('.woo_amc_item_quanity');
            var key = $(input).data('key');
            var number = parseInt($(input).val());
            var type = $(el).data('type');
            if(type=='minus'){
                number--;
            } else {
                number++;
            }
            if (number<1){
                number = 1;
            }
            $(input).val(number);
            var data = {
                action: 'woo_amc_quanity_update',
                key: key,
                number: number,
                security: nonce
            };
            $(wrap).addClass('loading');
            $.post( wooAmcVars.ajaxurl, data, function( response ) {
                var cart_response = JSON.parse( response );
                $('.woo_amc_footer_products .woo_amc_value, .woo_amc_open_count').html(cart_response['count']);
                $('.woo_amc_footer_total .woo_amc_value').html(cart_response['total']);
                $(wrap).find('.woo_amc_item_total_price').html(cart_response['item_price']);
                $(wrap).removeClass('loading');
                woo_amc_quanity_update_send = true;
                $( '.woo_amc_items_wrap' ).removeClass( 'woo_amc_items_wrap_loading' );
            } );
        }
    }

    function woo_amc_quanity_update( input ) {
        $( '.woo_amc_items_wrap' ).addClass( 'woo_amc_items_wrap_loading' );
        var wrap = $(input).parents('.woo_amc_item_wrap');
        var key = $(input).data('key');
        var number = parseInt($(input).val());
        if (!number || number<1){
            number = 1;
        }
        $(input).val(number);
        var data = {
            action: 'woo_amc_quanity_update',
            key: key,
            number: number,
            security: nonce
        };
        $(wrap).addClass('loading');
        $.post( wooAmcVars.ajaxurl, data, function( response ) {
            var cart_response = JSON.parse( response );
            $('.woo_amc_footer_products .woo_amc_value, .woo_amc_open_count').html(cart_response['count']);
            $('.woo_amc_footer_total .woo_amc_value').html(cart_response['total']);
            $(wrap).find('.woo_amc_item_total_price').html(cart_response['item_price']);
            $(wrap).removeClass('loading');
            woo_amc_quanity_update_send = true;
            $( '.woo_amc_items_wrap' ).removeClass( 'woo_amc_items_wrap_loading' );
        } );
    }

    function woo_amc_remove_item( key,item ) {
        var data = {
            action: 'woo_amc_delete_item',
            key: key,
            security: nonce
        };
        if (wooAmcVars.cart_type=='left'){
            $(item).animate({right: '100%'}, 300, function () {
                $(item).remove();
                woo_amc_perfect_scrollbar();
            });
        } else {
            $(item).animate({left: '100%'}, 300, function () {
                $(item).remove();
                woo_amc_perfect_scrollbar();
            });
        }

        $.post( wooAmcVars.ajaxurl, data, function( response ) {
            var cart_response = JSON.parse( response );
            $('.woo_amc_footer_products .woo_amc_value, .woo_amc_open_count').html(cart_response['count']);
            $('.woo_amc_footer_total .woo_amc_value').html(cart_response['total']);
            if(!parseInt(cart_response['count'])){
                $('.woo_amc_open').removeClass('woo_amc_open_active');
                woo_amc_hide();
            }
        } );
    }

    function woo_amc_perfect_scrollbar() {
        if (wooAmcVars.cart_type!='center') {
            PerfectScrollbar_line.update();
        }
    }

    public_woo_amc_show = woo_amc_show;
    public_woo_amc_get_cart = woo_amc_get_cart;

})( jQuery );
