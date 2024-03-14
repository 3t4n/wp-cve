;(function ($) {
    $(function () {
        var templates = {
            notice: '<div id="fortnox-message" class="updated notice notice-{{ type }} is-dismissible"><p>{{{ message }}}</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>'
        };

        var intervals={};

        function __(txt){
            if(typeof window.fortnox_l10n != "undefined")
            {
                if(typeof window.fortnox_l10n[txt] != "undefined")
                {
                    return window.fortnox_l10n[txt];
                }
            }
            return txt;
        }

        function update_progress_bar(bar, percentage) {
            if (percentage < 0) percentage = 0;
            if (percentage > 100) percentage = 100;
            bar.find('.fill').css('width', percentage + '%');
            percentage = Math.ceil(percentage);
            bar.find('.text').html(percentage + ' %');
        }

        /**
         * Orders sync
         */
        let modals = {
            fortnox_sync_orders_date_range: async (loader) => {
                let main_button = $('input#fortnox_order_date_range_btn');
                let description = $('#fortnox_order_sync_range_progress_description');
                $( '.date-picker-field, .date-picker' ).datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    showButtonPanel: true
                });

                function orderSyncRestoreButton(){
                    setTimeout(()=>{
                        $('#order_sync_date_to').removeAttr('disabled');
                        $('#order_sync_date_from').removeAttr('disabled');
                        main_button.removeClass('button-secondary');
                        main_button.addClass('button-primary');
                        main_button.val(__('Start'));
                        //main_button.off();
                        main_button.click(orderSyncStartMethod);
                        running=false;
                    },1000);
                }

                function orderSyncBindClose() {
                    $('button.fortnox_modal_close').off();
                    console.log($('button.fortnox_modal_close').length);
                    $(document).on("click", "button.fortnox_modal_close", (e) => {
                        console.log('close');
                        $('.fortnox_sync_orders_date_range_shade').hide();
                        $('.fortnox_sync_orders_date_range_modal').hide();
                        loader.css({visibility: "hidden"});
                        $('.fortnox_order_sync_range_setup').show();
                    });
                }

                orderSyncBindClose();

                $('.fortnox_sync_orders_date_range_shade').show();
                $('.fortnox_sync_orders_date_range_modal').show();
                $('button.fortnox_modal_close').off();
                $('span.order_sync_start').off();

                async function synchronizeOrder (orderId){
                    return $.ajax({
                        url: window.ajaxurl,
                        data: {
                            action: "fortnox_action",
                            fortnox_action: "sync_order",
                            order_id: orderId
                        },
                        type: "POST",
                        dataType: "json",
                        success: function (response) {
                        }
                    });
                }

                async function processOrders(orderIds) {
                    $('button.fortnox_modal_close').off();
                    $('#order_sync_date_to').attr('disabled','');
                    $('#order_sync_date_from').attr('disabled','');
                    $('.fortnox-order-sync-range-progress').show();
                    $('.fortnox_order_sync_range_setup').hide();
                    let progress = 0;

                    for (let index = 0; index < orderIds.length; index++) {
                        await synchronizeOrder(orderIds[index]).then(function () {
                            progress = ((index+1)/orderIds.length) * 100
                            update_progress_bar($('.fortnox_order_sync_range_progress_bar'), progress);
                        });
                    }
                }

                let orderSyncStartMethod = async (e) => { //start function
                    console.log('orderSyncStartMethod')
                    error = false;
                    //main_button.off();
                    description.html('');
                    if ("" == $('#order_sync_date_to').val() || "" == $('#order_sync_date_from').val()) {
                        let message = __("Please set ranges") +' (' + status + '): ' + error;
                        description.html('<br>' + message);
                        orderSyncRestoreButton();
                        return;
                    }
                    $('.fortnox_sync_orders_date_range_modal .wc-backbone-modal-main').addClass('hide-footer');

                    await $.ajax({
                        url: window.ajaxurl,
                        data: {
                            action: "fortnox_bulk_action",
                            bulk: 'fortnox_sync_orders_date_range',
                            from_date: $('#order_sync_date_from').val(),
                            to_date: $('#order_sync_date_to').val(),
                            status: $('#fortnox_sync_on_status').val()
                        },
                        success: (response) => {
                            if (response.error) {
                                error = true;
                                description.append('<br><b>'+__('Error')+':</b><br>' + response.message);
                                $('.fortnox-order-sync-range-progress').show();
                                $('.fortnox_sync_orders_date_range_modal .wc-backbone-modal-main').removeClass('hide-footer');
                            } else {
                                processOrders(response.order_ids)
                            }
                        },
                        error: (request, status, error) => {
                            error = true;
                            let message = __('Error occurred querying backend with status') +' (' + status + '): ' + error;
                            description.html('<br>' + message);
                            $('.fortnox_sync_orders_date_range_modal .wc-backbone-modal-main').removeClass('hide-footer');
                        },
                        dataType: "json"
                    });

                };

                orderSyncRestoreButton();
            },
            fortnox_sync_products: async (loader) => {
                let main_button = $('input#fortnox_sync_products_btn');
                let description = $('#fortnox_product_sync_progress_description');

                function productSyncRestoreButton(){
                    setTimeout(()=>{
                        main_button.removeClass('button-secondary');
                        main_button.addClass('button-primary');
                        main_button.val(__('Start'));
                        main_button.off();
                        main_button.click(productSyncStartMethod);
                        running=false;
                    },1000);
                }

                function productSyncBindClose() {
                    $('button.fortnox_modal_close').off();
                    console.log($('button.fortnox_modal_close').length);
                    $(document).on("click", "button.fortnox_modal_close", (e) => {
                        console.log('close');
                        $('.fortnox_sync_products_shade').hide();
                        $('.fortnox_sync_products_modal').hide();
                        loader.css({visibility: "hidden"});
                        $('.fortnox_sync_products_setup').show();
                    });
                }

                productSyncBindClose();

                $('.fortnox_sync_products_shade').show();
                $('.fortnox_sync_products_modal').show();
                $('button.fortnox_modal_close').off();
                $('span.order_sync_start').off();

                async function synchronizeProduct (productId){
                    description.html(__('Processing Product ID: ') + productId);
                    return $.ajax({
                        url: window.ajaxurl,
                        data: {
                            action: "fortnox_action",
                            fortnox_action: "sync_product",
                            product_id: productId
                        },
                        type: "POST",
                        dataType: "json",
                        success: function (response) {
                        }
                    });
                }

                async function processProducts(productIds) {
                    $('button.fortnox_modal_close').off();
                    $('.fortnox-product-sync-progress').show();
                    $('.fortnox_sync_products_setup').hide();
                    let progress = 0

                    for (let index = 0; index < productIds.length; index++) {
                        await synchronizeProduct(productIds[index]).then(function () {
                            progress = ((index+1)/productIds.length) * 100
                            update_progress_bar($('.fortnox_sync_products_progress_bar'), progress);
                        });
                    }
                    description.html(__('Process done'));
                }

                let productSyncStartMethod = async (e) => { //start function
                    error = false;
                    main_button.off();
                    description.html(__('Process started'));

                    $('.fortnox_sync_products_modal .wc-backbone-modal-main').addClass('hide-footer');

                    await $.ajax({
                        url: window.ajaxurl,
                        data: {
                            action: "fortnox_bulk_action",
                            bulk: 'fortnox_sync_products',
                        },
                        success: (response) => {
                            if (response.error) {
                                error = true;
                                description.append('<br><b>'+__('Error')+':</b><br>' + response.message);
                                $('.fortnox_sync_products_modal .wc-backbone-modal-main').removeClass('hide-footer');
                            } else {
                                processProducts(response.product_ids)
                            }
                        },
                        error: (request, status, error) => {
                            error = true;
                            let message = __('Error occurred querying backend with status')+' (' + status + '): ' + error;
                            description.html('<br>' + message);
                            $('.fortnox_sync_products_modal .wc-backbone-modal-main').removeClass('hide-footer');
                            return alert(message);
                        },
                        dataType: "json"
                    });

                };

                productSyncRestoreButton();
            }
        };

        /**
         * Warehoues delivery status
         */
        let searchParams = new URLSearchParams(window.location.search)
        if( 'order' == searchParams.get('tab')){
            if( 1 != order_settings.fortnox_has_warehouse_module ){
                table = $(".form-table");
                table.find('tr').eq(2).hide();
            }

            $("input[type='checkbox'][name='fortnox_has_warehouse_module']").change(function() {
                table = $(".form-table");
                if(this.checked) {
                    table.find('tr').eq(2).show();
                }
                else {
                    table.find('tr').eq(2).hide();
                }
            });
        }

        function poller(type,message_alert=null){
            if(message_alert)message_alert.html('<div class="notice notice-success">Requesting...</div>');

            $.ajax({
                url: window.ajaxurl,
                data: {
                    action: "check_" + type,
                },
                success: function (response) {
                    var html = response.error ? '<div class="notice notice-error">' + response.message + '</div>' : '<div class="notice notice-success">' + response.message + '</div>'
                    if(message_alert)message_alert.html(html);
                    if("undefined"!=typeof response.finished) {
                        clearInterval(intervals[type]);
                    }
                },
                dataType: "json"
            });
        }

        /**
         * Check connection
         */
        if ($('.button.fortnox-check-connection').length) {

            $('.button.fortnox-check-connection').on('click', function (event) {
                event.preventDefault();

                var loader = $(this).siblings('.spinner');
                var message_alert = $(this).siblings('.alert');

                loader.css({visibility: "visible"});

                $.ajax({
                    url: window.ajaxurl,
                    data: {
                        action: "check_" + $(this).siblings('[type=text]').attr('name'),
                        key: $(this).siblings('[type=text]').val()
                    },
                    success: function (response) {
                        console.log(typeof(response.error))
                        loader.css({visibility: "hidden"});
                        var html = response.error ? '<div class="notice notice-error">' + response.message + '</div>' : '<div class="notice notice-success">' + response.message + '</div>'
                        message_alert.html(html);
                        if(!response.error && "undefined" != typeof response.extra){
                            switch(response.extra){
                                case 'pull_for_result_auth_by_organisation_number':
                                    break;
                                default:
                                    html = '<div class="notice notice-error">Unexpected payload.</div>';
                                    message_alert.html(html);
                            }
                        }
                    },
                    dataType: "json"
                });
            });
        }

        /**
         * class-wf-ajax bulk actions
         */
        $('.fortnox-bulk-action').on('click', function (event) {

            event.preventDefault();

            var loader = $(this).siblings('.spinner');

            if (!$(this).data('fortnox-bulk-action'))
                return console.warn("No bulk action specified.");

            loader.css({visibility: "visible"});

            if ($(this).data('modal')) {
                modals[$(this).data('fortnox-bulk-action')](loader);
                return;
            }

            $.ajax({
                url: window.ajaxurl,
                data: {
                    action: "fortnox_bulk_action",
                    bulk: $(this).data('fortnox-bulk-action')
                },
                success: function (response) {
                    loader.css({visibility: "hidden"});
                    if ("undefined" !== typeof response.message)
                        return alert(response.message);
                },
                dataType: "json"
            });
        });

        /**
         * Sync order
         */
        $('.syncOrderToFortnox').on('click', function (event) {
            event.preventDefault();

            var orderId = $(this).data('order-id');
            var nonce = $(this).data('nonce');
            var loader = $(this).siblings('.fortnox-spinner');
            var status = $(this).siblings('.wetail-fortnox-status');

            loader.css({visibility: "visible"});
            status.hide();

            $('#fortnox-message').remove();

            $.ajax({
                url: window.ajaxurl,
                data: {
                    action: "fortnox_action",
                    fortnox_action: "sync_order",
                    order_id: orderId
                },
                type: "POST",
                dataType: "json",
                success: function (response) {
                    if (!response.error)
                        status.removeClass('wetail-icon-cross').addClass('wetail-icon-check');

                    loader.css({visibility: "hidden"});
                    status.show();

                    $('#wpbody .wrap h1').after(Mustache.render(templates.notice, {
                        type: response.error ? "error" : "success",
                        message: response.message
                    }));

                    var fortnoxMessageElement = $('#fortnox-message');
                    fortnoxMessageElement.show();

                    $('.sendInvoiceToCustomer[data-order-id=' + orderId + ']').removeClass("wetail-hidden");

                    $('html, body').animate({scrollTop: fortnoxMessageElement.offset().top - 100});
                }
            });

            return;
        });

        /**
         * Sync product
         */
        $('.syncProductToFortnox').on('click', function (event) {
            event.preventDefault();

            var productId = $(this).data('product-id');
            var nonce = $(this).data('nonce');
            var loader = $(this).siblings('.fortnox-spinner');
            var status = $(this).siblings('.wetail-fortnox-status');

            loader.css({visibility: "visible"});
            status.hide();

            $('#fortnox-message').remove();

            $.ajax({
                url: window.ajaxurl,
                data: {
                    action: "fortnox_action",
                    fortnox_action: "sync_product",
                    product_id: productId
                },
                type: "POST",
                dataType: "json",
                success: function (response) {
                    if (!response.error)
                        status.removeClass('wetail-icon-cross').addClass('wetail-icon-check');

                    loader.css({visibility: "hidden"});
                    status.show();

                    $('#wpbody .wrap h1').after(Mustache.render(templates.notice, {
                        type: response.error ? "error" : "success",
                        message: response.message
                    }));

                    var fortnoxMessageElement = $('#fortnox-message');
                    fortnoxMessageElement.show();
                    $('html, body').animate({scrollTop: fortnoxMessageElement.offset().top - 100});
                }
            });
            return;
        });


        $(document.body).on('click', '.notice-dismiss', function() {
            $(this).parents('.is-dismissible').first().hide();
        })


        $('.sendInvoiceToCustomer').on('click', function (event) {

            console.log(window.ajaxurl);

            event.preventDefault();

            var orderId = $(this).data('order-id');
            var nonce = $(this).data('nonce');
            var loader = $(this).siblings('.fortnox-spinner');
            var status = $(this).siblings('.wetail-fortnox-status');

            loader.css({visibility: "visible"});
            status.hide();

            $('#fortnox-message').remove();

            $.ajax({
                url: window.ajaxurl,
                data: {
                    action: "fortnox_action",
                    fortnox_action: "send_invoice",
                    order_id: orderId
                },
                type: "POST",
                dataType: "json",
                success: function (response) {
                    if (!response.error)
                        status.removeClass('wetail-icon-cross').addClass('wetail-icon-check');

                    loader.css({visibility: "hidden"});
                    status.show();

                    $('#wpbody .wrap h1').after(Mustache.render(templates.notice, {
                        type: response.error ? "error" : "success",
                        message: response.message
                    }));

                    $('.sendInvoiceToCustomer[data-order-id=' + orderId + ']').addClass("wetail-hidden");

                    $('html, body').animate({scrollTop: $('#fortnox-message').offset().top - 100});
                }
            });
        });

        $( document.body )
            .on( 'init_tooltips', function() {
                var tiptip_args = {
                    'attribute': 'data-tip',
                    'fadeIn': 50,
                    'fadeOut': 50,
                    'delay': 200
                };

                $( '.tips, .help_tip, .woocommerce-help-tip' ).tipTip( tiptip_args );

                // Add tiptip to parent element for widefat tables
                $( '.parent-tips' ).each( function() {
                    $( this ).closest( 'a, th' ).attr( 'data-tip', $( this ).data( 'tip' ) ).tipTip( tiptip_args ).css( 'cursor', 'help' );
                });
            })
            .trigger( 'init_tooltips' );
    });


})(jQuery);
