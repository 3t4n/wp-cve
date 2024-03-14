"use strict";

/**
 * All of the code for your admin-facing JavaScript source
 * should reside in this file.
 *
 * Note: It has been assumed you will write jQuery code here, so the
 * $ function reference has been prepared for usage within the scope
 * of this function.
 *
 * This enables you to define handlers, for when the DOM is ready:
 *
 * $(function() {
 *
 * });
 *
 * When the window is loaded:
 *
 * $( window ).load(function() {
 *
 * });
 *
 * ...and/or other possibilities.
 *
 * Ideally, it is not considered best practise to attach more than a
 * single DOM-ready or window-load handler for a particular page.
 * Although scripts in the WordPress core, Plugins and Themes may be
 * practising this, we should strive to set a better example in our own work.
 */
(function ($) {
    $(document).ready(function () {
        let body = $('body');

        /**
         * Change settings panel tab
         */
        body.on('click', '.nav-tab', function (e) {
            e.preventDefault();
            let $this = $(this),
                tab = $this.data('tab');
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            $('.vuukle-tab-content').removeClass('vuukle-tab-content-active');
            $($this.attr('href')).addClass('vuukle-tab-content-active');
            $('input[name="tab"]').val(tab);
        });

        /**
         * Reset settings
         */
        body.on('click', '#reset-settings', function (e) {
            e.preventDefault();
            if (!confirm("Are you sure you want to reset to default settings?")) {
                return false;
            }
            $('#action').val('vuukleResetSettings');
            $('#vuukle-settings-form').submit();
        });

        /**
         * Export comments per page specified in the amount input
         */
        body.on('click', '#export_button', function (e) {
            let $this = $(this),
                offset = $this.data('offset'),
                loader = $($('.loader-animation')[0]);
            /**
             * Hide button
             * Show loader
             */
            $this.hide();
            loader.show();

            /**
             * Proceed ajax call
             *
             * @param offset
             * @param button
             */
            function loadXMLDoc(offset, button) {
                let amountComments = $($('.amount_comments')[0]).val(),
                    params = {
                        action: 'exportComments',
                        offset: offset,
                        amount_comments: amountComments,
                        _wpnonce: fcfwv_admin_vars.nonce,
                    },
                    queryString = Object.keys(params)
                        .map(function (key) {
                            return key + "=" + params[key];
                        })
                        .join("&");
                // Exact call
                $.get(fcfwv_admin_vars.ajax_url + '?' + queryString, function (data) {
                    if (data) {
                        let result = data.result;
                        if (result > 0) {
                            button.data('offset', result);
                            loadXMLDoc(result, button);
                        } else if (result === 0) {
                            button.data('offset', result);
                            loader.hide();
                            button.show();
                            if (data.link) {
                                window.location.assign(data.link);
                            }
                        } else if (data.result < 0) {
                            alert(result.message);
                        }
                    }
                }, 'json').fail(function () {
                    alert('Technical error');
                });
            }

            loadXMLDoc(offset, $this);
        });

        /**
         * Registers/Fetch API key
         */
        body.on('click', '#quick_register', function (e) {
            // Show loading
            let loader = $($('.api-key-loading')[0]);
            loader.show();
            // Proceed ajax call
            $.post(fcfwv_admin_vars.ajax_url, {
                action: 'quickRegister',
                _wpnonce: fcfwv_admin_vars.nonce
            }, function (data) {
                if (data) {
                    loader.hide();
                    $('input[name="AppId"]').val(data);
                }
            }).fail(function () {
                alert('Something went wrong. Please try again later');
                loader.hide();
            });
        });

        /**
         * Close support popup
         */
        body.on('click', '.vuukle_popup_close', function (e) {
            $('.vuukle_overlay').hide();
        });

        /**
         * Enable/Disable functionality for below option
         * 'Enable horizontal for mobile and vertical for desktop'
         */
        body.on('change', 'input[name="enable_h_v"]', function (e) {
            let $this = $(this),
                value = $this.val(),
                shareTypeHorizontal = $('input[name="share_type"]');

            if (value === 'yes') {
                shareTypeHorizontal.attr('disabled', true);
                $('input[name="share_type_vertical"]').attr('disabled', true);
            } else {
                shareTypeHorizontal.attr('disabled', false);
                $('input[name="share_type_vertical"]').attr('disabled', false);
            }
            shareTypeHorizontal.trigger('change');
        });

        /**
         * Enable/Disable functionality for below option
         * 'Share Bar Type'
         * Condition is connected with horizontal option mainly
         */
        body.on('change', 'input[name="share_type"]', function (e) {
            let $this = $('input[name="share_type"]'),
                checked = $this.is(':checked'),
                enableHvYes = $('input[name="enable_h_v"]:checked').val() === 'yes',
                afterContentPost = $('input[name="share_position"]'),
                beforeContentPost = $('input[name="share_position2"]');
            if (!checked && !enableHvYes) {
                // Horizontal is checked but vertical not checked
                afterContentPost.attr('disabled', true);
                beforeContentPost.attr('disabled', true);
            } else {
                afterContentPost.attr('disabled', false);
                beforeContentPost.attr('disabled', false);
            }
            afterContentPost.trigger('change');
        });

        /**
         * Enable/Disable functionality for below option
         * 'After Content Post  Before Content Post'
         * Condition is connected with showing/hiding div related options
         */
        body.on('change', 'input[name="share_position"],input[name="share_position2"]', function (e) {
            let main = $('input[name="share_position"]'),
                checked = main.is(':checked'),
                other = $('input[name="share_position2"]'),
                otherIsChecked = other.is(':checked'),
                shareTypeHorizontal = $('input[name="share_type"]'),
                enableHvYes = $('input[name="enable_h_v"]:checked').val() === 'yes',
                embedPowerBar = $('input[name="embed_powerbar"]'),
                divClassPowerBar = $('input[name="div_class_powerbar"]'),
                divIdPowerBar = $('input[name="div_id_powerbar"]');

            if (checked || otherIsChecked || (!shareTypeHorizontal.is(':checked') && !enableHvYes)) {
                embedPowerBar.attr('disabled', true);
                divClassPowerBar.attr('disabled', true);
                divIdPowerBar.attr('disabled', true);
            } else {
                embedPowerBar.attr('disabled', false);
                divClassPowerBar.attr('disabled', false);
                divIdPowerBar.attr('disabled', false);
            }
        });
    });
}(jQuery));