(function ($) { return $(function () {
    'use strict';
    var $body = $(document.body);
    var $stancer_payment_method = $('#payment_method_stancer');
    var $cardSelect = $('#stancer-card');
    if ($cardSelect.selectWoo) {
        $cardSelect.selectWoo({
            minimumResultsForSearch: Infinity,
            width: '100%',
        });
    }
    $body.on('click', '.js-stancer-place-order', function (event) {
        if (!$stancer_payment_method.is(':checked')) {
            return true;
        }
        event.preventDefault();
        var $this = $(this);
        var $form = $this.parents('form');
        var $body = $(document.body);
        var width = 550;
        var height = 855;
        var left = (screen.width - width) / 2;
        var top = Math.max((screen.height - height) / 2, 0);
        var popup = window.open('about:blank', '_blank', "popup, width=".concat(width, ", height=").concat(height, ", top=").concat(top, ", left=").concat(left));
        if (!popup) {
            return;
        }
        $.ajax({
            url: stancer.initiate,
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function (result) {
                try {
                    if ('success' === result.result && result.redirect && result.redirect !== '') {
                        popup.location.href = result.redirect;
                    }
                    else if ('failure' === result.result) {
                        throw new Error('Result failure');
                    }
                    else {
                        throw new Error('Invalid response');
                    }
                }
                catch (err) {
                    popup.close();
                    // Reload page
                    if (result.reload) {
                        window.location.reload();
                        return;
                    }
                    // Trigger update in case we need a fresh nonce
                    if (result.refresh) {
                        $body.trigger('update_checkout');
                    }
                    // Add new errors
                    if (result.messages) {
                        $('.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message').remove();
                        $('form.checkout')
                            .prepend("<div class=\"woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout\">".concat(result.messages, "</div>"))
                            .removeClass('processing')
                            .unblock()
                            .find('.input-text, select, input:checkbox')
                            .trigger('validate')
                            .trigger('blur');
                        var $scrollElement = $('.woocommerce-NoticeGroup-updateOrderReview, .woocommerce-NoticeGroup-checkout');
                        if ($scrollElement.length) {
                            $.scroll_to_notices($scrollElement);
                        }
                        else {
                            $.scroll_to_notices($('form.checkout'));
                        }
                        $body.trigger('checkout_error', [result.messages]);
                    }
                }
            },
            error: function (_jqXHR, _textStatus, errorThrown) {
                $body.trigger('checkout_error', [errorThrown]);
            }
        });
    });
}); })(jQuery);
