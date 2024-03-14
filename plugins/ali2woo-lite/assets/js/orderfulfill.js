jQuery(function($) {    
    $('body').append('<div id="fulfillment_model_container" class="a2wl-content"></div>')
    $( "#fulfillment_model_container" ).load(ajaxurl, { 'action': 'a2wl_load_fulfillment_model' });

    $('body').on("click", ".modal-fulfillment .modal-close, .modal-fulfillment .modal-btn-close", function () {
        $(".modal-overlay.modal-fulfillment").removeClass('opened');
        return false;
    });

    $(document).on("click", ".a2wl_aliexpress_order_fulfillment", function () {        
        const ids = (typeof $(this).attr('id') == "undefined" && $(this).attr('href').substr(0, 1) == "#")? [$(this).attr('href').substr(1)]: [$(this).attr('id').split('-')[1]];
        prepare_order_fulfillment_dialog(ids);
        return false;
    });

    function refresh_fulfillment_dialog() {
        const ids = $(".modal-overlay.modal-fulfillment").data('ids');

        prepare_order_fulfillment_dialog(ids);
    }

    function prepare_order_fulfillment_dialog(ids) {
        $(".modal-overlay.modal-fulfillment").data('ids', ids);

        $(".modal-overlay.modal-fulfillment .modal-content .loader").remove();
        $(".modal-overlay.modal-fulfillment .modal-content").append('<div class="loader a2wl-load-container"><div class="a2wl-load-speeding-wheel"></div></div>');
        $(".modal-overlay.modal-fulfillment").addClass('opened');

        $("#fulfillment-auto").removeAttr('disabled', 'disabled');
        $("#fulfillment-auto").removeClass('loading')
        $("#fulfillment-auto").show()
  
        $("#fulfillment-chrome").removeAttr('disabled', 'disabled');
        $("#fulfillment-chrome").removeClass('loading')
        $("#fulfillment-chrome").show()

        $("#pay-for-orders").hide()

        $(".modal-overlay.modal-fulfillment .modal-content .modal-body").load(ajaxurl, { 'action': 'a2wl_load_fulfillment_orders', ids }, function() {
            $(".modal-overlay.modal-fulfillment .modal-content .loader").remove();
            if($('.modal-fulfillment .single-order-wrap[data-order_id]').length == 0){
           
                $("#fulfillment-auto").attr('disabled', 'disabled');
            
                $("#fulfillment-chrome").attr('disabled', 'disabled');
            }
            $( '.modal-fulfillment .js_field-country' ).selectWoo()
            $( '.modal-fulfillment .js_field-country' ).trigger( 'change', [ true ] );
        });
    }

    function update_order_items(order_id) { 
        const order = $('.single-order-wrap[data-order_id="' + order_id + '"]')
        const shiping_to_country = $(order).attr('data-shiping_to_country')
        const data = { 'action': 'a2wl_update_fulfillment_shipping', order_id, shiping_to_country, items : [] }
        $(order).find('[data-order_item_id]').each(function () { 
            const order_item = $(this)
            const order_item_id = $(order_item).attr('data-order_item_id')        
            const shipping = $(order_item).find('.current-shipping-company').val()
            data.items.push({order_item_id, shipping})
        })    

        $.post(ajaxurl, data, function(response) {
            var json = $.parseJSON(response);  
            if (json.state == 'error') {
                console.log(json.message)
            } else {                
                $('.modal-fulfillment .order-total .total').html(json.result.total_order_price)
                $.each(json.result.items, function (_, item) {
                    const order_item = $('[data-order_item_id="' + item.order_item_id + '"]')
                    $(order_item).find('.delivery_time').html(item.shiping_time)
                    $(order_item).find('.shipping_cost').html(item.shiping_price)
                    $(order_item).find('.total_cost').html(item.total_item_price)
                });                
            }
        }).fail(function(xhr, status, error) {
            console.log(error);                        
        });
    }

    $(document.body).on("click", ".modal-fulfillment .order-ship-to .edit", function () {
        $(this).parents('.single-order-wrap').find('.order-edit-address-form').toggleClass('open');
        return false;             
    })

    $(document.body).on("change", ".modal-fulfillment #_shipping_country", function () {
        const country = $(this).val();
        const $rut_field = $('.modal-fulfillment ._shipping_rut');
        const $cpf_field = $('.modal-fulfillment ._shipping_cpf');

        $rut_field.addClass('hidden');
        $cpf_field.addClass('hidden');

        if (country === 'BR') {
            $cpf_field.removeClass('hidden');
        } else if (country === 'CL') {
            $rut_field.removeClass('hidden');
        }
    });

    $(document.body).on("click", ".modal-fulfillment #save-order-address", function () {
        const $form = $(this).parents('.single-order-wrap').find('.order-edit-address-form')
        $form.removeClass('open');
        const order_id = $(this).parents('.single-order-wrap').attr('data-order_id');

        const data = {
            'action': 'a2wl_save_order_shipping_info',
            'order_id': order_id,
            '_shipping_first_name': $form.find('#_shipping_first_name').val(),
            '_shipping_last_name': $form.find('#_shipping_last_name').val(),
            '_shipping_company': $form.find('#_shipping_company').val(),
            '_shipping_address_1': $form.find('#_shipping_address_1').val(),
            '_shipping_address_2': $form.find('#_shipping_address_2').val(),
            '_shipping_city': $form.find('#_shipping_city').val(),
            '_shipping_postcode': $form.find('#_shipping_postcode').val(),
            '_shipping_country': $form.find('#_shipping_country').val(),
            '_shipping_state': $form.find('#_shipping_state').val(),
            '_shipping_phone': $form.find('#_shipping_phone').val(),
        }


        const $rut = $form.find('#rut');
        const $cpf = $form.find('#cpf');

        if (!$rut.closest('._shipping_rut').hasClass('hidden')) {
            data['rut'] = $rut.val();
        }

        if (!$cpf.closest('._shipping_cpf').hasClass('hidden')) {
            data['cpf'] = $cpf.val();
        }

        $.post(ajaxurl, data, function(response) {
            var json = $.parseJSON(response);  
            if (json.state == 'error') {
                console.log(json.message)
            } else {
                refresh_fulfillment_dialog();
            }
        }).fail(function(xhr, status, error) {
            console.log(error);                        
        });
        return false;             
    });

    const a2wl_order_meta_data = {
        states : null
    }

    if (!( typeof woocommerce_admin_meta_boxes_order === 'undefined' || typeof woocommerce_admin_meta_boxes_order.countries === 'undefined' ) ) {
        a2wl_order_meta_data.states = JSON.parse( woocommerce_admin_meta_boxes_order.countries.replace( /&quot;/g, '"' ) );
    }


    const change_country = function(e, stickValue) {
        // Check for stickValue before using it
        if ( typeof stickValue === 'undefined' ){
            stickValue = false;
        }

        // Prevent if we don't have the metabox data
        if ( a2wl_order_meta_data.states === null ){
            return;
        }

        var $this = $( this ),
            country = $this.val(),
            $state = $this.parents( '.order-edit-address-form' ).find( ':input.js_field-state' ),
            $parent = $state.parent(),
            stateValue = $state.val(),
            input_name = $state.attr( 'name' ),
            input_id = $state.attr( 'id' ),
            value = $this.data( 'woocommerce.stickState-' + country ) ? $this.data( 'woocommerce.stickState-' + country ) : stateValue,
            placeholder = $state.attr( 'placeholder' ),
            $newstate;

        if ( stickValue ){
            $this.data( 'woocommerce.stickState-' + country, value );
        }

        // Remove the previous DOM element
        $parent.show().find( '.select2-container' ).remove();

        if ( ! $.isEmptyObject( a2wl_order_meta_data.states[ country ] ) ) {
            var state = a2wl_order_meta_data.states[ country ],
                $defaultOption = $( '<option value=""></option>' )
                    .text( woocommerce_admin_meta_boxes_order.i18n_select_state_text );

                $newstate = $( '<select></select>' )
                    .prop( 'id', input_id )
                    .prop( 'name', input_name )
                    .prop( 'placeholder', placeholder )
                    .addClass( 'js_field-state select short' )
                    .append( $defaultOption );

                $.each( state, function( index ) {
                    var $option = $( '<option></option>' )
                        .prop( 'value', index )
                        .text( state[ index ] );
                    if ( index === stateValue ) {
                        $option.prop( 'selected' );
                    }
                    $newstate.append( $option );
                } );

            $newstate.val( value );

            $state.replaceWith( $newstate );

            $newstate.show().selectWoo().hide().trigger( 'change' );
        } else {
            $newstate = $( '<input type="text" />' )
                .prop( 'id', input_id )
                .prop( 'name', input_name )
                .prop( 'placeholder', placeholder )
                .addClass( 'js_field-state' )
                .val( stateValue );
            $state.replaceWith( $newstate );
        }
    }

    const change_state = function() {
    }

    $(document.body).on("change", ".modal-fulfillment .js_field-country", change_country)
    $(document.body).on("change", ".modal-fulfillment select.js_field-state", change_state)

    $(document).on("change", ".modal-fulfillment .current-shipping-company", function () { 
        update_order_items($(this).parents('.single-order-wrap[data-order_id]').attr('data-order_id'))        
    })

    $(document).on("click", ".modal-fulfillment .remove-item", function () {
        const order = $(this).parents('.single-order-wrap[data-order_id]')        
        $(this).parents('[data-order_item_id]').remove() 
        if ($(order).find('[data-order_item_id]').length > 0) {
            update_order_items($(order).attr('data-order_id'))
        } else {
            $(order).remove()
            if ($('.modal-fulfillment .single-order-wrap[data-order_id]').length === 0) { 
                $(".modal-overlay.modal-fulfillment").removeClass('opened');
            }
        }
        $(this).parents('[data-order_item_id]').remove()                
    })

    $(document).on("click", "#fulfillment-auto", function () {
        $("#fulfillment-auto").attr('disabled', 'disabled');
        $("#fulfillment-auto").addClass('loading')
        $("#fulfillment-chrome").attr('disabled', 'disabled');
        const orders_to_plase = []
        $('.single-order-wrap[data-order_id]').each(function () {
            const items = []
            $(this).find('[data-order_item_id]').each(function () {
                items.push($(this).attr('data-order_item_id'))            
            })
            orders_to_plase.push({ 'action': 'a2wl_fulfillment_place_order', order_id: $(this).attr('data-order_id'), items })
        })

        const on_place_order = function (order_id, response_state, response_message, state, json = undefined) { 
            const order = $('.single-order-wrap[data-order_id="' + order_id + '"]')
            $(order).find('.order-message').text('');
            $(order).find('.item-message').text('');
            if (response_state == 'error') {
                $(order).find('.order-ship-to .edit').show();
                $(order).find('.order-message').html('State: <span class="error">' + response_message + '</span>')                
                if (json.error_code === "product_error") {
                    $.each(json.errors, function (_, error) {
                        $(order).find('[data-order_item_id="'+error.order_item_id+'"]').find('.item-message').html('State: <span class="error">' + error.message + '</span>')                                           
                    }); 
                }
            } else {
                $(order).find('.order-ship-to .edit').hide();
                $(order).find('.order-message').html('State: <span class="ok">We\'ve placed the order successfully.</span>');
            }

            if (state.total == state.ok + state.error) { 
                $("#fulfillment-auto").removeAttr('disabled', 'disabled');
                $("#fulfillment-auto").removeClass('loading')
                $("#fulfillment-auto").hide()
                $("#fulfillment-chrome").removeAttr('disabled', 'disabled');
                $("#fulfillment-chrome").removeClass('loading')
                $("#fulfillment-chrome").hide()
                
                $("#pay-for-orders").hide()
                if(state.ok > 0){
                    $("#pay-for-orders").show()
                }
                
                if(state.error > 0) {
                    $("#fulfillment-auto").show()
                    $("#fulfillment-chrome").show()
                }
            }
        }

        const before_place_order = async function ( order_id ) {
            const order = $( '.single-order-wrap[data-order_id="' + order_id + '"]' );
            let urls = [];

            if ( $( order ).data( 'urls' ) )
            {
                urls = $( order ).data( 'urls' ).split(';');  
            }

            try
            {
                await Promise.all(urls.map(url => fetch_url(url)));
            }
            catch (error)
            {
            }
        }

        const fetch_url = function (url) {
            return new Promise((res) => {
                let img = document.createElement("img");
                img.style.display = "none";
                img.addEventListener("load", (function() {
                    document.body.removeChild(img);
                    res();
                }));
                img.addEventListener("error", (function(e) {
                    document.body.removeChild(img);
                    res();
                }));
                img.src = url;
                document.body.appendChild(img);
            });
        }

        let state = { total: orders_to_plase.length, ok: 0, error: 0 };
        a2wl_js_fulfillment_place_order(orders_to_plase, state, on_place_order, before_place_order);
    })

    $(document).on("click", "#fulfillment-chrome", function () {
        const ids = []
        $('.single-order-wrap[data-order_id]').each(function () { ids.push($(this).attr('data-order_id')) })
        const order_item_ids = []
        $('[data-order_item_id]').each(function () { order_item_ids.push($(this).attr('data-order_item_id')) })
        $(".modal-overlay.modal-fulfillment").removeClass('opened');
        a2wl_start_order_process(ids, order_item_ids);
    })


    async function a2wl_js_fulfillment_place_order ( orders_to_plase, state, on_load_calback, before_load_callback )
    {
        if (orders_to_plase.length > 0) {
            var data = orders_to_plase.shift();  
            
            if ( before_load_callback )
            {
                await before_load_callback( data.order_id );    
            }
  
            $.post(ajaxurl, data).done(function (response) {
                var json = jQuery.parseJSON(response);
                if (json.state !== 'ok') {
                    console.log(json);
                }
                if (json.state === 'error') {
                    state.error++;
                } else {
                    state.ok++;
                }

                if (on_load_calback) {
                    on_load_calback(data.order_id, json.state, json.message, state, json);
                }

                a2wl_js_fulfillment_place_order(orders_to_plase, state, on_load_calback, before_load_callback);
            }).fail(function (xhr, status, error) {
                console.log(error);
                state.error++

                if (on_load_calback) {
                    on_load_calback(data.order_id, 'error', 'request error', state);
                }

                a2wl_js_fulfillment_place_order(orders_to_plase, state, on_load_calback, before_load_callback);
            });
        }
    }

    

    $.a2wl_ali_fulfill_order = function(id) {
        var data = { 'action': 'a2wl_get_aliexpress_order_data', 'id': id };

        $.post(ajaxurl, data, function(response) {

            var json = jQuery.parseJSON(response);


            if (json.state === 'error') {

                console.log(json);
                jQuery('.wrap > h1').after('<div class="error notice is-dismissible"><p>' + json.error_message + '</p><button id="a2wl-fulfill-dismiss-admin-message" class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');

                jQuery("#a2wl-fulfill-dismiss-admin-message").click(function(event) {
                    event.preventDefault();
                    jQuery('.' + 'error').fadeTo(100, 0, function() {
                        jQuery('.' + 'error').slideUp(100, function() {
                            jQuery('.' + 'error').remove();
                        });
                    });
                });

            } else {
                //console.log(json);
                if (json.action == 'upd_ord_status') {

                }
                a2w_get_order_fulfillment(json.data.content, function(data) {
                    console.log(data);
                });

            }

        });
    }

    function app_rsp_timer_run(t) {
        return setTimeout(function() {

            a2wl_reset_blocks();

            a2wl_show_tip(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.get_no_responces_from_chrome_ext_d, 'https://ali2woo.com/codex/chrome-no-responce-issue/'), false, false, false, true);
            $('.hover_a2wl_fulfillment .close').off('click').click(function() {
                a2wl_hide_block();
                a2w_close_chrome_tab();
            });


        }, 30000);
    }


    function a2wl_js_place_order(ids, state, on_load_calback) {

        if (ids.length > 0) {

            var tmp_ids = ids.slice(0),
                id = ids.shift();

            var data = { 'action': 'a2wl_get_aliexpress_order_data', 'id': id };

            $.post(ajaxurl, data, function(response) {

                var json = $.parseJSON(response);
                if (json.state !== 'ok') {
                    console.log(json);
                }

                if (json.state === 'error') {
                    state.error_cnt += 1;
                    if (on_load_calback) {

                        var data = { 'stage': -5 }; // unknown error

                        if (typeof json.error_code !== "undefined") {
                            data['stage'] = json.error_code
                        }

                        on_load_calback(json.state, state, data, tmp_ids);
                    }
                } else {
                    a2w_get_order_fulfillment(json.data.content, function(data) {
                        on_load_calback('ok', state, data, tmp_ids);
                    });
                }


            }).fail(function(xhr, status, error) {
                console.log(error);
                state.error_cnt += 1;

                if (on_load_calback) {
                    var data = { 'stage': -6 }; // server error
                    on_load_calback('error', state, data, tmp_ids);
                }
            });
        } else {
            var data = { 'stage': 6 };
            on_load_calback('ok', state, data, tmp_ids);
        }
    }

    var a2wl_start_order_process = function (ids, order_item_ids) {
        const total_ids = ids.length
        if (total_ids > 0) {
            a2wl_reset_blocks();
            a2wl_show_block(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.placing_orders_d_of_d, 0, total_ids));

            if (typeof a2w_get_order_fulfillment == "undefined") {

                a2wl_reset_blocks();
                a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.install_chrome_ext, false, false, false, true);
                $('.hover_a2wl_fulfillment .close').off('click').click(function() {
                    a2wl_hide_block();
                    a2w_close_chrome_tab();
                });

                return;
            }

            var skip_order = function(ids, state) {
                ids.shift();
                state.error_cnt += 1;
                state.success_cnt += 1;
                a2wl_reset_blocks();
                a2wl_show_block(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.placing_orders_d_of_d, state.success_cnt, state.num_to_update));
                rsp_timer = app_rsp_timer_run();
                a2wl_js_place_order(ids, state, on_load);
            }

            var on_load = function(response_state, state, data, ids) {

                clearTimeout(rsp_timer);

                if (response_state == "error") {

                    if (data.stage === -6) {
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.server_error, true);
                        $('.hover_a2wl_fulfillment .continue').off('click').click(function() {
                            a2wl_reset_blocks();
                            rsp_timer = app_rsp_timer_run();
                            a2wl_js_place_order(ids, state, on_load);
                        });
                    }

                    if (data.stage === -5) {
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.unknown_error, false, true);
                        $('.hover_a2wl_fulfillment .skip').off('click').click(function() {
                            skip_order(ids, state);
                        });
                    }

                    if (data.stage === -4) {
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.no_ali_products, 'https://ali2woo.com/codex/no-aliexpress-prodoct-error/'), false, true);
                        $('.hover_a2wl_fulfillment .skip').off('click').click(function() {
                            skip_order(ids, state);
                        });
                    }

                    if (data.stage === -3) {
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.no_product_url, 'https://ali2woo.com/codex/no-product-url-error/'), false, true);
                        $('.hover_a2wl_fulfillment .skip').off('click').click(function() {
                            skip_order(ids, state);
                        });
                    }

                    if (data.stage === -2) {
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.no_variable_data, 'https://ali2woo.com/codex/no-variable-data-error/'), false, true);
                        $('.hover_a2wl_fulfillment .skip').off('click').click(function() {
                            skip_order(ids, state);
                        });
                    }

                    if (data.stage === -1) {
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.bad_product_id, false, true);
                        $('.hover_a2wl_fulfillment .skip').off('click').click(function() {
                            skip_order(ids, state);
                        });
                    }

                } else if (typeof data !== "undefined") {

                    if (data.stage === 51) {
                        rsp_timer = app_rsp_timer_run();
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.cart_is_cleared);
                    }

                    if (data.stage === 52) {
                        rsp_timer = app_rsp_timer_run();
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.all_products_are_added);
                    }

                    if (data.stage === 53) {
                        rsp_timer = app_rsp_timer_run();
                        a2wl_reset_blocks();
                        var msg = typeof data.param !== "undefined" ? a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.product_is_added_to_cart, data.param) : a2wl_ali_orderfulfill_js.lang.product_is_added_to_cart;
                        a2wl_show_tip(msg);
                    }

                    if (data.stage === 54) {
                        rsp_timer = app_rsp_timer_run();
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.your_customer_address_entered);
                    }

                    if (data.stage === 55) {

                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.detected_old_aliexpress_interface, false, false, false, true);
                        $('.hover_a2wl_fulfillment .close').off('click').click(function() {
                            a2wl_hide_block();
                            a2w_close_chrome_tab();
                        });
                    }

                    if (data.stage === 56) {
                        rsp_timer = app_rsp_timer_run();
                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.fill_order_note);
                    }

                    if (data.stage === 57) {

                        a2wl_reset_blocks();
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.done_pay_manually, false, false, false, false, true);
                        $('.hover_a2wl_fulfillment .solve').off('click').click(function() {
                            rsp_timer = app_rsp_timer_run();
                            a2wl_switch_to_chrome_tab();
                        });
                    }

                    if (data.stage === 0) {
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.login_into_aliexpress_account, false, false, false, false, true);

                        $('.hover_a2wl_fulfillment .solve').off('click').click(function() {
                            rsp_timer = app_rsp_timer_run();
                            a2wl_switch_to_chrome_tab();
                        });

                    }

                    if (data.stage === 1) {
                        a2wl_show_tip(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.please_connect_chrome_extension_check_d, 'https://ali2woo.com/codex/ali2woo-google-chrome-extension/'), true);

                        $('.hover_a2wl_fulfillment .continue').off('click').click(function() {
                            rsp_timer = app_rsp_timer_run();
                            a2wl_start_order_process(ids, order_item_ids);
                        });
                    }

                    if (data.stage === 2) {
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.please_activate_right_store_apikey_in_chrome, true);

                        $('.hover_a2wl_fulfillment .continue').off('click').click(function() {
                            rsp_timer = app_rsp_timer_run();
                            a2wl_start_order_process(ids, order_item_ids);
                        });
                    }

                    if (data.stage === 11) {
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.we_found_old_order, true);

                        $('.hover_a2wl_fulfillment .continue').off('click').click(function() {
                            a2wl_reset_blocks();
                            a2wl_show_block(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.placing_orders_d_of_d, state.success_cnt, state.num_to_update));
                            rsp_timer = app_rsp_timer_run();
                            a2wl_js_place_order(ids, state, on_load);
                        });
                    }

                    if (data.stage === 21) {

                        a2wl_show_tip(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.cant_add_product_to_cart_d, 'https://ali2woo.com/codex/chrome-add-to-cart-issue/'), true, false, false, false, true);

                        $('.hover_a2wl_fulfillment .solve').off('click').click(function() {
                            a2wl_switch_to_chrome_tab();
                        });

                        $('.hover_a2wl_fulfillment .continue').off('click').click(function() {
                            rsp_timer = app_rsp_timer_run();
                            a2w_get_order_fulfillment({});
                            a2wl_switch_to_chrome_tab();
                        });
                    }

                    if (data.stage === 3) {
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.please_type_customer_address, false, true, false, false, true);

                        $('.hover_a2wl_fulfillment .solve').off('click').click(function() {
                            rsp_timer = app_rsp_timer_run();
                            a2wl_switch_to_chrome_tab();
                        });

                        //+
                        $('.hover_a2wl_fulfillment .skip').off('click').click(function() {
                            skip_order(ids, state);
                        });
                    }

                    if (data.stage === 33) {
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.please_input_captcha, false, false, false, false, true);

                        $('.hover_a2wl_fulfillment .solve').off('click').click(function() {
                            rsp_timer = app_rsp_timer_run();
                            a2wl_switch_to_chrome_tab();
                        });
                    }

                    if (data.stage === 5) {

                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.order_is_placed);

                        setTimeout(function() {
                            ids.shift();
                            state.success_cnt += 1;
                            a2wl_reset_blocks();
                            a2wl_show_block(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.placing_orders_d_of_d, state.success_cnt, state.num_to_update));
                            rsp_timer = app_rsp_timer_run();
                            a2wl_js_place_order(ids, state, on_load);

                        }, 1500);
                    }

                    if (data.stage === 41) {
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.internal_aliexpress_error, true, true);

                        $('.hover_a2wl_fulfillment .continue').off('click').click(function() {
                            rsp_timer = app_rsp_timer_run();
                            a2wl_start_order_process(ids, order_item_ids);
                        });

                        $('.hover_a2wl_fulfillment .skip').off('click').click(function() {
                            skip_order(ids, state);
                        })
                    }

                    if (data.stage === 42) {
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.payment_is_failed, true);

                        $('.hover_a2wl_fulfillment .continue').off('click').click(function() {
                            ids.shift();
                            state.error_cnt += 1;
                            state.success_cnt += 1;
                            a2wl_reset_blocks();
                            a2wl_show_block(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.placing_orders_d_of_d, state.success_cnt, state.num_to_update));
                            rsp_timer = app_rsp_timer_run();
                            a2wl_js_place_order(ids, state, on_load);
                        })
                    }

                    if (data.stage === 43) {
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.cant_get_order_id, true);

                        $('.hover_a2wl_fulfillment .continue').off('click').click(function() {
                            ids.shift();
                            state.error_cnt += 1;
                            state.success_cnt += 1;
                            a2wl_reset_blocks();
                            a2wl_show_block(a2wl_sprintf(a2wl_ali_orderfulfill_js.lang.placing_orders_d_of_d, state.success_cnt, state.num_to_update));
                            rsp_timer = app_rsp_timer_run();
                            a2wl_js_place_order(ids, state, on_load);
                        })
                    }

                    if (data.stage === 44) {
                        a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.choose_payment_method, false, false, false, false, true);

                        $('.hover_a2wl_fulfillment .solve').off('click').click(function() {
                            rsp_timer = app_rsp_timer_run();
                            a2wl_switch_to_chrome_tab();
                        })
                    }

                    if (data.stage === 6) {
                        a2wl_reset_blocks();
                        if (state.error_cnt < state.num_to_update) {
                            a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.all_orders_are_placed, false, false, true);
                            $('.hover_a2wl_fulfillment .payall').off('click').click(function() {
                                a2wl_go_to_payall();
                                a2wl_hide_block();
                            });
                        } else {
                            a2wl_show_tip(a2wl_ali_orderfulfill_js.lang.cant_process_your_orders, false, false, false, true);
                            $('.hover_a2wl_fulfillment .close').off('click').click(function() {
                                a2wl_hide_block();
                                a2w_close_chrome_tab();
                            });
                        }
                    }
                }


            };

            var state = { num_to_update: total_ids, success_cnt: 0, error_cnt: 0 };

            var rsp_timer = app_rsp_timer_run();
            a2wl_js_place_order(ids, state, on_load);

        }
    }

    $("#doaction, #doaction2").click(function(event) {
        const check_action= ($(this).attr('id') === 'doaction') ?
            $('#bulk-action-selector-top').val() :
            $('#bulk-action-selector-bottom').val();

        if ('a2wl_order_place_bulk' === check_action) {
            event.preventDefault();
            const ids = [];
            $('input:checkbox[name="post[]"]:checked').each(function() {
                ids.push($(this).val());
            });
            if (ids.length < 1) {
                //for HPOS screen
                $('input:checkbox[name="id[]"]:checked').each(function() {
                    ids.push($(this).val());
                });
            }
            prepare_order_fulfillment_dialog(ids)
        }
    });

});

var a2wl_show_block = function(message) {
    jQuery('.hover_a2wl_fulfillment .pr').html(message)
    jQuery('.hover_a2wl_fulfillment').show();
}

var a2wl_hide_block = function() {
    jQuery('.hover_a2wl_fulfillment').hide();
}

var a2wl_show_tip = function(message, _continue, skip, payall, close, solve, refresh) {

    a2wl_reset_blocks();

    jQuery('.hover_a2wl_fulfillment .tip').html(message)
    jQuery('.hover_a2wl_fulfillment .tip').show();

    if (_continue) {
        jQuery('.hover_a2wl_fulfillment .continue').show();
    }
    if (skip) {
        jQuery('.hover_a2wl_fulfillment .skip').show();
    }
    if (payall) {
        jQuery('.hover_a2wl_fulfillment .payall').show();
    }

    if (typeof close !== "undefined" && close) {
        jQuery('.hover_a2wl_fulfillment .close').show();
    }

    if (typeof solve !== "undefined" && solve) {
        jQuery('.hover_a2wl_fulfillment .solve').show();
    }

    if (typeof refresh !== "undefined" && refresh) {
        jQuery('.hover_a2wl_fulfillment .refresh').show();
    }
}

var a2wl_reset_blocks = function() {
    jQuery('.hover_a2wl_fulfillment .tip').html('');
    jQuery('.hover_a2wl_fulfillment .tip').hide();
    jQuery('.hover_a2wl_fulfillment .continue').hide();
    jQuery('.hover_a2wl_fulfillment .skip').hide();
    jQuery('.hover_a2wl_fulfillment .payall').hide();
    jQuery('.hover_a2wl_fulfillment .close').hide();
    jQuery('.hover_a2wl_fulfillment .solve').hide();
    jQuery('.hover_a2wl_fulfillment .refresh').hide();
}
