(function ($) { return $(function () {
    'use strict';
    var _a, _b;
    var $window = $(window);
    var $body = $(document.body);
    var $backdrop = $(document.createElement('div')).addClass('stancer-backdrop');
    // We create the frame, and set some of their attribute before wrapping it in jQuery.
    var $frame = $(document.createElement('iframe'))
        /*
        * We set allow = payment; we want to authorize paymentAPI in our Iframe
        * We set sandbox = allow-scripts ; we need it because we use javascript in the payment page.
        * We set sandbox = allow-forms;  we need it because we send a form in our Iframe.
        * We set sandbox = top-navigation; we need it to be able to interact with context outside our iframe, more precisely to get the event.data and use it.
        * We MUST not set allow-scripts and allow-same-origin at the same time, as it make sandbox useless!
        * https://developer.mozilla.org/en-US/docs/Web/HTML/Element/iframe#sandbox
        */
        .addClass('stancer-iframe')
        .attr('allow', 'payment')
        .attr('sandbox', 'allow-scripts allow-forms allow-top-navigation');
    var $stancer_payment_method = $('#payment_method_stancer');
    var $cardSelect = $('#stancer-card');
    var params = Object.fromEntries(window.location.search.slice(1).split('&').map(function (value) { return value.split('='); }));
    var STANCER_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 702.227 106.667"><path d="M31.75 26.656c-9.216 0-17.232 6.135-19.406 14.844L.219 95.344c-1.31 5.818 3.262 11.312 9.406 11.312h69c6.093 0 11.375-4.119 12.688-9.906L95.093 80H40.876c-3.81 0-6.617-3.512-5.719-7.156l11.375-46.188H31.75Z" fill="var(--stancer-back, #7899d6)"/><path d="M68.625 0C59.52 0 51.618 6.135 49.469 14.844L49 16.75h89.438l1.281-5.781C140.981 5.339 136.638 0 130.781 0H68.625Zm67.625 26.563-30.5.03L95.094 80h18.844c6.02 0 11.233-4.12 12.531-9.906l9.781-43.531Z" fill="var(--stancer-accent, #c8e8f9)"/><path d="m95.096 80 10.65-53.43-59.204.098-11.39 46.177C34.253 76.49 37.057 80 40.867 80H94.92" fill="var(--stancer-heart, #f35c6f)"/><g fill="var(--stancer-text, #101a2c)"><path d="M224.32 106.667c21.512 0 37.496-13.037 37.496-30.964 0-40.148-57.812-24.888-57.812-46.518 0-7.703 7.021-12.74 17.627-12.74 11.354 0 18.673 6.814 18.972 16.147h17.926c0-17.48-12.249-32.592-36.3-32.592-20.465 0-35.405 12.296-35.405 29.333 0 38.075 57.664 23.112 57.664 46.37 0 8.74-8.367 14.667-20.168 14.667-12.548 0-21.511-7.555-21.511-18.667H185.48c0 20.297 16.432 34.964 38.84 34.964M315.349 48.445V32.74h-19.121V13.333h-16.731v19.408h-13.893v15.704h13.893v29.48c0 18.372 10.756 28.149 26.89 28.149 2.688 0 6.422-.297 8.962-.889v-15.26c-1.642.445-3.585.593-5.377.593-8.068 0-13.744-3.703-13.744-12.888V48.445h19.121M381.625 32.741v12.74c-5.228-9.036-14.192-14.222-25.844-14.222-19.42 0-34.956 16.741-34.956 37.63 0 20.888 15.536 37.778 34.956 37.778 11.652 0 20.616-5.185 25.844-14.223v12.741h16.731V32.741h-16.731Zm-22.258 58.223c-12.399 0-22.11-9.78-22.11-22.074 0-12.149 9.711-21.927 22.11-21.927 12.698 0 22.258 9.48 22.258 21.927 0 12.592-9.56 22.074-22.258 22.074M451.045 31.259c-9.709 0-17.477 4.741-22.108 12.89V32.74h-16.582v72.444h16.582v-39.26c0-11.407 7.171-18.813 18.076-18.813 10.606 0 17.627 7.406 17.627 18.813v39.26h16.731V63.852c0-20.148-11.652-32.593-30.326-32.593M529.983 106.667c17.777 0 33.162-11.557 35.105-28h-17.029c-1.794 7.258-9.412 12.148-18.076 12.148-12.1 0-21.362-9.482-21.362-21.779 0-12.444 9.262-21.925 21.362-21.925 8.664 0 16.133 4.889 17.925 11.556h17.031c-2.242-15.704-17.328-27.408-34.956-27.408-21.362 0-37.795 16.296-37.795 37.777 0 21.482 16.433 37.631 37.795 37.631M647.085 67.408c0-21.186-15.984-36.15-35.702-36.15-21.064 0-37.646 16.297-37.646 37.334 0 21.63 16.582 38.075 37.944 38.075 17.927 0 31.819-10.075 34.507-25.334h-16.729c-1.495 6.223-8.516 10.37-17.479 10.37-12.4 0-20.019-7.26-21.661-18.813h56.318c.299-1.482.448-3.557.448-5.482Zm-56.169-7.111c2.54-9.334 9.711-15.408 19.569-15.408 10.01 0 17.479 6.37 18.526 15.408h-38.095M695.351 31.259c-8.666 0-15.387 4.149-19.72 11.111v-9.629h-16.58v72.444h16.58V66.518c0-13.036 8.665-20.296 19.272-20.296 2.389 0 4.929.445 7.32 1.037v-15.11c-2.242-.445-4.632-.89-6.872-.89"/></g></svg>';
    var messageCallback = function () { return false; };
    if ($cardSelect.selectWoo) {
        $cardSelect.selectWoo({
            minimumResultsForSearch: Infinity,
            width: '100%',
        });
    }
    var close = function () {
        $body.removeClass('stancer-block-scroll');
        $backdrop.detach().addClass('stancer-backdrop--hidden');
        $frame.detach();
    };
    var processResponse = function ($this, result) {
        try {
            if ('success' === result.result && result.redirect && result.redirect !== '') {
                $body.addClass('stancer-block-scroll');
                $backdrop.appendTo($body).removeClass('stancer-backdrop--hidden');
                $frame.appendTo($body).attr('src', result.redirect);
            }
            else if ('failure' === result.result) {
                throw new Error('Result failure');
            }
            else {
                throw new Error('Invalid response');
            }
        }
        catch (err) {
            // Reload page
            if (result.reload) {
                window.location.reload();
                return false;
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
        finally {
            $this.unblock();
        }
    };
    $backdrop
        .append($(document.createElement('div')).addClass('stancer-logo').append(STANCER_SVG))
        .on('click', close);
    // We don't get any messages from window, so we can't resize the iframe. (and maybe cannot load the payment form)
    $window
        .on('message', function (event) {
        var _a, _b;
        var data = event.originalEvent.data;
        // We use some of the api data structure to test if the message is a stancer payment message.
        if (typeof data.status === "undefined" || typeof data.width === "undefined" || typeof data.height === "undefined") {
            return;
        }
        if (data.url) {
            if (messageCallback(data)) {
                return;
            }
            if (['error', 'finished', 'secure-auth-error'].includes(data.status)) {
                window.location = data.url;
            }
            if (data.status === 'finished') {
                return;
            }
        }
        var maxHeight = (_a = $window.height()) !== null && _a !== void 0 ? _a : 100;
        var maxWidth = (_b = $window.width()) !== null && _b !== void 0 ? _b : 100;
        var height = 400;
        var radius = 10;
        var width = 400;
        if (data.status === 'secure-auth-start') {
            height = maxHeight;
            width = maxWidth;
        }
        else if (!['error', 'init', 'secure-auth-end', 'secure-auth-error'].includes(data.status)) {
            height = data.height;
            width = data.width;
        }
        if (height >= maxHeight) {
            height = maxHeight;
            radius = 0;
        }
        if (width >= maxWidth) {
            width = maxWidth;
            radius = 0;
        }
        document.body.style.setProperty('--stancer-iframe-height', "".concat(height, "px"));
        document.body.style.setProperty('--stancer-iframe-width', "".concat(width, "px"));
        document.body.style.setProperty('--stancer-iframe-border-radius', "".concat(radius, "px"));
    })
        .on('keydown', function (event) {
        if (event.code === 'Escape') {
            close();
        }
    });
    if ($('.js-stancer-change-payment-method').length) {
        $.ajax({
            url: (_a = stancer.changePaymentMethod) === null || _a === void 0 ? void 0 : _a.url,
            type: 'POST',
            data: {
                action: 'information',
                nonce: (_b = stancer.changePaymentMethod) === null || _b === void 0 ? void 0 : _b.nonce,
                subscription: params.change_payment_method,
            },
            dataType: 'json',
            success: function (result) {
                if (result.card) {
                    $('#payment .payment_method_stancer label[for=payment_method_stancer]').text(result.card);
                }
            },
            error: function (_jqXHR, _textStatus, errorThrown) { return $body.trigger('checkout_error', [errorThrown]); },
        });
    }
    $body
        .on('click', '.js-stancer-place-order', function (event) {
        if (!$stancer_payment_method.is(':checked')) {
            return true;
        }
        event.preventDefault();
        var $this = $(this);
        var $form = $this.parents('form');
        $this.block({ message: null });
        $.ajax({
            url: stancer.initiate,
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function (result) { return processResponse($this, result); },
            error: function (_jqXHR, _textStatus, errorThrown) {
                $body.trigger('checkout_error', [errorThrown]);
            }
        });
        return false;
    })
        .on('click', '.js-stancer-change-payment-method', function (event) {
        var _a, _b;
        var $this = $(this);
        event.preventDefault();
        messageCallback = function (data) {
            var _a, _b;
            if (data.status !== 'finished' && data.status !== 'error') {
                return false;
            }
            close();
            $.ajax({
                url: (_a = stancer.changePaymentMethod) === null || _a === void 0 ? void 0 : _a.url,
                type: 'POST',
                data: {
                    action: 'validate',
                    nonce: (_b = stancer.changePaymentMethod) === null || _b === void 0 ? void 0 : _b.nonce,
                    subscription: params.change_payment_method,
                },
                dataType: 'json',
                success: function (result) {
                    if (result.result === 'success') {
                        if (result.card) {
                            $('#order_review .shop_table tfoot tr:nth-child(2) td.product-total').text(result.card);
                        }
                        $('#payment').empty();
                    }
                    if (result.messages) {
                        var $message = $(document.createElement('div')).text(result.messages);
                        $('.woocommerce-notices-wrapper')
                            .siblings('.wc-block-components-notice-banner, .woocommerce-error, .woocommerce-info')
                            .remove()
                            .end()
                            .after($message);
                        if (result.result === 'success') {
                            $message.addClass('woocommerce-info');
                        }
                        else {
                            $message.addClass('woocommerce-error');
                        }
                    }
                },
            });
            return true;
        };
        $this.block({ message: null });
        $.ajax({
            url: (_a = stancer.changePaymentMethod) === null || _a === void 0 ? void 0 : _a.url,
            type: 'POST',
            data: {
                action: 'initiate',
                nonce: (_b = stancer.changePaymentMethod) === null || _b === void 0 ? void 0 : _b.nonce,
                subscription: params.change_payment_method,
            },
            dataType: 'json',
            success: function (result) { return processResponse($this, result); },
            error: function (_jqXHR, _textStatus, errorThrown) { return $body.trigger('checkout_error', [errorThrown]); },
        });
        return false;
    });
}); })(jQuery);
