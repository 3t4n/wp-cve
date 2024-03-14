jQuery(function( $ ) {
    'use strict';

    var payever_wc_capture = {
        init: function() {
            var me = this;

            var isAllowedAmount = !($('#capture_amount').attr('disabled') == 'disabled' ?? false);
            var isAllowedQty = false;
            $('.payever-partial-item-qty.payever-capture-action .qty-input').each(function() {
                isAllowedQty = !($(this).attr('disabled') == 'disabled' ?? false);
            });
            $('div.wc-payever-capture').appendTo('#woocommerce-order-items .inside');
            $('#woocommerce-order-items').on('click', 'button.wc-payever-capture-button', this.partialCaptureShow);
            $('#woocommerce-order-items').on('click', 'button.payever-capture-action', this.doCapture);
            $('#woocommerce-order-items').on('change keyup', '.payever-partial-item-qty.payever-capture-action .qty-input', function(e) {
                /**
                 * Disable / Enable capture amount field if qty typed
                 */
                if ($(this).val() > 0) {
                    $('#capture_amount').attr('disabled', true);
                } else {
                    var valueFlag = false;
                    $('.payever-partial-item-qty.payever-capture-action .qty-input').each(function() {
                        if ($(this).val() > 0) {
                            valueFlag = true;
                        }
                    });
                    if (!valueFlag && isAllowedAmount) {
                        $('#capture_amount').removeAttr('disabled');
                    }
                }
                /** End */

                var cost = 0,
                    formattedAmount = '';

                $.each($('.payever-partial-item-qty.payever-capture-action .qty-input'), function (key, element) {
                    cost += $(element).attr('data-item-cost') * $(element).val()
                });

                if (cost) {
                    $('button.payever-capture-action').removeAttr('disabled');
                } else {
                    $('button.payever-capture-action').attr('disabled', 'disabled');
                }
                formattedAmount = me.formatting(cost);
                $('button .capture-amount .amount').text(formattedAmount);
                $('#capture_amount').val(formattedAmount);
            });
            $('#woocommerce-order-items').on('click', 'button.cancel-action', this.hideQty);
            $('div.wc-payever-capture').on('change keyup', '#capture_amount', function(e) {

                /**
                 * Disable / Enable qty inputs if amount typed
                 */
                if ($(this).val()) {
                    $('.payever-capture-action input.qty-input').val(0);
                    $('.payever-capture-action input.qty-input').attr('disabled', true);
                } else if(isAllowedQty) {
                    $('.payever-capture-action input.qty-input').removeAttr('disabled');
                }
                /** End */

                var total;
                total = accounting.unformat($(this).val(), woocommerce_admin.mon_decimal_point);
                if (total) {
                    $('button.payever-capture-action').removeAttr('disabled');
                } else {
                    $('button.payever-capture-action').attr('disabled', 'disabled');
                }
                return $('button .capture-amount .amount').text(me.formatting(total));
            });

            $('#wc_shipping_provider').on('change click',function (event) {
                if ($(this).val() === '') {
                    $('#provider_custom_row').show();
                    $('#tracking_url_row').show();
                } else {
                    $('#provider_custom_row').hide();
                    $('#tracking_url_row').hide();
                    $('#wc_shipping_provider_custom').val($(this).val());
                }
            });

            $('#wc_shipping_provider, #wc_tracking_number').on('change keypress', function () {
                var provider = $('#wc_shipping_provider').val();

                if (provider !== '') {
                    var url = $('#wc_shipping_provider option:selected').data('url');
                    var tracking_url = url.replace('%1$s', $('#wc_tracking_number').val());

                    $('#wc_tracking_url').val(tracking_url);
                }
            });
        },
        formatting: function(cost) {
            return accounting.formatMoney(cost, {
                symbol: woocommerce_admin_meta_boxes.currency_format_symbol,
                decimal: woocommerce_admin_meta_boxes.currency_format_decimal_sep,
                thousand: woocommerce_admin_meta_boxes.currency_format_thousand_sep,
                precision: woocommerce_admin_meta_boxes.currency_format_num_decimals,
                format: woocommerce_admin_meta_boxes.currency_format
            });
        },

        hideQty: function() {
            $('.payever-partial-item-qty.payever-capture-action .qty-input').hide();
        },

        /**
         * Partial capture functionality
         */
        partialCaptureShow: function() {
            $('div.wc-payever-capture').slideDown();
            $('.payever-partial-item-qty.payever-capture-action .qty-input').show();
            $('div.wc-order-data-row-toggle').not('div.wc-payever-capture').slideUp();
            $('div.wc-order-totals-items').slideUp();
        },

        doCapture: function() {
            var data = {
                    action: 'payever_capture_item',
                    order_id: woocommerce_admin_meta_boxes.post_id,
                    items: [],
                    amount: accounting.unformat($('#capture_amount').val(), woocommerce_admin.mon_decimal_point),
                    comment: $('#capture_comment').val(),
                    tracking_number: $('#wc_tracking_number').val(),
                    tracking_url: $('#wc_tracking_url').val(),
                    shipping_provider: $('#wc_shipping_provider_custom').val(),
                    shipping_date: $('#wc_shipping_date').val()
                };

            $.each($('.payever-partial-item-qty.payever-capture-action .qty-input'), function (key, value) {
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
            })
        },
    }

    payever_wc_capture.init();
});
