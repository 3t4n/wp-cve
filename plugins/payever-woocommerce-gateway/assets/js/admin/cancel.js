jQuery(function( $ ) {
    'use strict';

    var payever_wc_cancel = {
        init: function() {
            $('div.wc-payever-cancel').appendTo('#woocommerce-order-items .inside');
            $('#woocommerce-order-items').on('click', 'button.wc-payever-cancel-button', this.partialShow);
            $('#woocommerce-order-items').on('click', 'button.payever-cancel-action', this.doCancel);
            $('#woocommerce-order-items').on('change keyup', '.payever-cancel-action .qty-input', function() {
                // Calculate cancel amount
                var cost = 0,
                    formattedAmount = '';

                $.each($('.payever-partial-item-qty.payever-cancel-action .qty-input'), function (key, element) {
                    cost += $(element).attr('data-item-cost') * $(element).val()
                });

                if (cost) {
                    $('button.payever-cancel-action').removeAttr('disabled');
                } else {
                    $('button.payever-cancel-action').attr('disabled', 'disabled');
                }

                formattedAmount = accounting.formatMoney(cost, {
                    symbol: woocommerce_admin_meta_boxes.currency_format_symbol,
                    decimal: woocommerce_admin_meta_boxes.currency_format_decimal_sep,
                    thousand: woocommerce_admin_meta_boxes.currency_format_thousand_sep,
                    precision: woocommerce_admin_meta_boxes.currency_format_num_decimals,
                    format: woocommerce_admin_meta_boxes.currency_format
                });

                $('button .cancel-amount .amount').text(formattedAmount);
                $('#cancel_amount').val(formattedAmount);
            });

            $('#woocommerce-order-items').on('click', 'button.cancel-action', this.hideQty);
        },

        hideQty: function() {
            $('.payever-partial-item-qty.payever-cancel-action .qty-input').hide();
        },

        /**
         * Cancel functionality
         */
        partialShow: function() {
            $('div.wc-payever-cancel').slideDown();
            $('.payever-partial-item-qty.payever-cancel-action .qty-input').show();
            $('div.wc-order-data-row-toggle').not('div.wc-payever-cancel').slideUp();
            $('div.wc-order-totals-items').slideUp();
        },

        doCancel: function() {
            var data = {
                action: 'payever_cancel_item',
                order_id: woocommerce_admin_meta_boxes.post_id,
                items: []
            };

            $.each($('.payever-partial-item-qty.payever-cancel-action .qty-input'), function (key, value) {
                var element = $(value),
                    itemId = element.attr('data-item-id'),
                    qty = element.val();

                if (itemId && qty > 0) {
                    data.items.push({
                        item_id: itemId,
                        qty: qty
                    })
                }
            });

            $('#woocommerce-order-items').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.46
                }
            });

            $.ajax({
                url: woocommerce_admin_meta_boxes.ajax_url,
                data: data,
                type: 'POST',
                dataType: 'json'
            }).done(function( response ) {
                if ( true === response.success ) {
                    window.location.reload();
                } else {
                    window.alert( response.data.error );
                }
            }).always(function () {
                $('#woocommerce-order-items').unblock();
            });
        },
    }

    payever_wc_cancel.init();
});
