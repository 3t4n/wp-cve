(function ($) {
    "use strict";

    QuickPay.prototype.init = function () {
        // Add event handlers
        this.actionBox.on('click', '[data-action]', $.proxy(this.callAction, this));
    };

    QuickPay.prototype.callAction = function (e) {
        e.preventDefault();
        var target = $(e.target);
        var action = target.attr('data-action');

        if (typeof this[action] !== 'undefined') {
            var message = target.attr('data-confirm') || 'Are you sure you want to continue?';
            if (confirm(message)) {
                this[action]();
            }
        }
    };

    QuickPay.prototype.capture = function () {
        var request = this.request({
            quickpay_action: 'capture'
        });
    };

    QuickPay.prototype.captureAmount = function () {
        var request = this.request({
            quickpay_action: 'capture',
            quickpay_amount: $('#qp-balance__amount-field').val()
        });
    };

    QuickPay.prototype.cancel = function () {
        var request = this.request({
            quickpay_action: 'cancel'
        });
    };

    QuickPay.prototype.refund = function () {
        var request = this.request({
            quickpay_action: 'refund'
        });
    };

    QuickPay.prototype.split_capture = function () {
        var request = this.request({
            quickpay_action: 'splitcapture',
            amount: parseFloat($('#quickpay_split_amount').val()),
            finalize: 0
        });
    };

    QuickPay.prototype.split_finalize = function () {
        var request = this.request({
            quickpay_action: 'splitcapture',
            amount: parseFloat($('#quickpay_split_amount').val()),
            finalize: 1
        });
    };

    QuickPay.prototype.request = function (dataObject) {
        var that = this;
        return $.ajax({
            type: 'POST',
            url: quickpayBackend.ajax_url + 'admin/manage-payment',
            dataType: 'json',
            data: $.extend({}, {post: this.postID}, dataObject),
            beforeSend: $.proxy(this.showLoader, this, true),
            success: function () {
                $.get(window.location.href, function (data) {
                    var newData = $(data).find('#' + that.actionBox.attr('id') + ' .inside').html();
                    that.actionBox.find('.inside').html(newData);
                    that.showLoader(false);
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText);
                that.showLoader(false);
            }
        });
    };

    QuickPay.prototype.showLoader = function (e, show) {
        if (show) {
            this.actionBox.append(this.loaderBox);
        } else {
            this.actionBox.find(this.loaderBox).remove();
        }
    };

    QuickPayCheckAPIStatus.prototype.init = function () {
        if (this.apiSettingsField.length) {
            $(window).on('load', $.proxy(this.pingAPI, this));
            this.apiSettingsField.on('blur', $.proxy(this.pingAPI, this));
            this.insertIndicator();
        }
    };

    QuickPayCheckAPIStatus.prototype.insertIndicator = function () {
        this.indicator.insertAfter(this.apiSettingsField.hide().fadeIn());
    };

    QuickPayCheckAPIStatus.prototype.pingAPI = function () {
        $.post(quickpayBackend.ajax_url + 'admin/settings/ping', {api_key: this.apiSettingsField.val()}, $.proxy(function (response) {
            if (response.success === true) {
                this.indicator.addClass('ok').removeClass('error');
            } else {
                this.indicator.addClass('error').removeClass('ok');
            }
        }, this), "json");
    };

    // DOM ready
    $(function () {
        new QuickPay().init();
        new QuickPayCheckAPIStatus().init();
        new QuickPayPrivateKey().init();

        function wcqpInsertAjaxResponseMessage(response) {
            if (response.hasOwnProperty('success')) {
                if (response.success === true) {
                    var message = $('<div id="message" class="updated"><p>' + response.data.message + '</p></div>');
                } else {
                    var message = $('<div id="message" class="error"><p>' + response.data + '</p></div>');
                }
                message.hide();
                message.insertBefore($('#wcqp_wiki'));
                message.fadeIn('fast', function () {
                    setTimeout(function () {
                        message.fadeOut('fast', function () {
                            message.remove();
                        });
                    }, 5000);
                });
            }
        }

        var emptyLogsButton = $('#wcqp_logs_clear');
        emptyLogsButton.on('click', function (e) {
            e.preventDefault();
            emptyLogsButton.prop('disabled', true);
            $.getJSON(quickpayBackend.ajax_url + 'admin/settings/empty-logs', function (response) {
                wcqpInsertAjaxResponseMessage(response);
                emptyLogsButton.prop('disabled', false);
            });
        });

        var flushCacheButton = $('#wcqp_flush_cache');
        flushCacheButton.on('click', function (e) {
            e.preventDefault();
            flushCacheButton.prop('disabled', true);
            $.getJSON(quickpayBackend.ajax_url + 'admin/settings/clear-cache', function (response) {
                wcqpInsertAjaxResponseMessage(response);
                flushCacheButton.prop('disabled', false);
            });
        });
    });

    function QuickPay() {
        this.actionBox = $('#quickpay-payment-actions');
        this.postID = typeof woocommerce_admin_meta_boxes !== 'undefined' ? woocommerce_admin_meta_boxes.post_id : null;
        this.loaderBox = $('<div class="loader"></div>');
    }

    function QuickPayCheckAPIStatus() {
        this.apiSettingsField = $('#woocommerce_quickpay_quickpay_apikey');
        this.indicator = $('<span class="wcqp_api_indicator"></span>');
    }

    function QuickPayPrivateKey() {
        this.field = $('#woocommerce_quickpay_quickpay_privatekey');
        this.apiKeyField = $('#woocommerce_quickpay_quickpay_apikey');
        this.refresh = $('<span class="wcqp_api_indicator refresh"></span>');
    }

    QuickPayPrivateKey.prototype.init = function () {
        var self = this;
        this.field.parent().append(this.refresh.hide());

        this.refresh.on('click', function () {
            if (!self.refresh.hasClass('ok')) {
                self.refresh.addClass('is-loading');
                $.post(quickpayBackend.ajax_url + 'admin/settings/private-key', {api_key: self.apiKeyField.val()}, function (response) {
                    if (response.success === true) {
                        self.field.val(response.data.private_key);
                        self.refresh.removeClass('refresh').addClass('ok');
                    } else {
                        self.flashError(response.data);
                    }

                    self.refresh.removeClass('is-loading');
                }, 'json');
            }
        });

        this.validatePrivateKey();
    }

    QuickPayPrivateKey.prototype.validatePrivateKey = function () {
        if (document.getElementById('woocommerce_quickpay_quickpay_privatekey')) {
            var self = this;
            $.post(quickpayBackend.ajax_url + 'admin/settings/clear-cache', {api_key: self.apiKeyField.val()}, function (response) {
                if (response.success === true && self.field.val() === response.data.private_key) {
                    self.refresh.removeClass('refresh').addClass('ok');
                }

                self.refresh.fadeIn();
            }, 'json');
        }
    };

    QuickPayPrivateKey.prototype.flashError = function (message) {
        var message = $('<div style="color: red; font-style: italic;"><p style="font-size: 12px;">' + message + '</p></div>');
        message.hide().insertAfter(this.refresh).fadeIn('fast', function () {
            setTimeout(function () {
                message.fadeOut('fast', function () {
                    message.remove();
                })
            }, 10000)
        });
    }
})(jQuery);
