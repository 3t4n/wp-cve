/**
 * Quform Form Builder WordPress plugin
 *
 * Mailchimp settings module
 *
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

/* globals jQuery, quformMailchimpSettingsL10n, quformCoreL10n */

var quform = (function (quform, $, settingsL10n, coreL10n) {
    "use strict";

    var module,

        // Shortcuts
        core = quform.core,
        c = core.cache,

        $window = $(window),
        saving = false,
        savingTimeout = null,
        verifying = false,
        uninstalling = false;

    module = {

        /**
         * Initialise this module
         */
        init: function () {
            // Do not allow the form to be submitted
            c.get('#qfb-mc-settings-form').submit(function() {
                return false;
            });

            c.get('#qfb-mc-save-settings').add(c.get('#qfb-fixed-save-button')).click(function () {
                module.saveSettings();
            });

            c.get('#qfb-mc-settings-verify').click(module.verifyApiKey);

            // Toggle all capabilities by clicking on the role name
            $('.qfb-permissions-role-name').click(function () {
                $(this).closest('.qfb-table-row').find('.qfb-permissions-capability').each(function () {
                    this.checked = ! this.checked;
                })
            });

            // Uninstall
            c.get('#qfb_mc_uninstall_confirm').change(function () {
                c.get('#qfb-mc-do-uninstall').closest(core.settingWrap)[this.checked ? 'qfbSlideShow' : 'qfbSlideHide']();
            });

            c.get('#qfb-mc-do-uninstall').click(module.uninstallPlugin);
        },

        /**
         * Save the settings
         */
        saveSettings: function () {
            // Return if we are already saving
            if (saving) {
                return;
            }
            saving = true;

            c.get('#qfb-settings-save-loading').show().animate({ maxWidth: '43px' });

            if (typeof savingTimeout === 'number') {
                clearTimeout(savingTimeout);
                savingTimeout = null;
            }

            c.get('#qfb-fixed-save-button').removeClass('qfb-saving qfb-saved qfb-save-error').addClass('qfb-saving');

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_mc_save_settings',
                    _ajax_nonce: settingsL10n.saveSettingsNonce,
                    options: JSON.stringify(module.getSettings())
                },
                dataType: 'json'
            }).done(function (response) {
                response = core.sanitiseResponse(response);

                if (response.type === 'success') {
                    module.onSaveSuccess();
                } else {
                    module.onSaveError(response.message);
                }
            }).fail(function () {
                module.onSaveError(coreL10n.ajaxError);
            }).always(function () {
                saving = false;
                c.get('#qfb-settings-save-loading').css({ maxWidth: 0 }).hide();
            });
        },

        /**
         * Get the settings values
         *
         * @return {Object}
         */
        getSettings: function () {
            var settings = {
                enabled: c.get('#qfb_mc_enabled').is(':checked')
            };

            settings.permissions = {};
            $('.qfb-permissions-capability').each(function () {
                var $capability = $(this),
                    role = $capability.data('role'),
                    capability = $capability.data('capability');

                if (typeof settings.permissions[role] === 'undefined') {
                    settings.permissions[role] = {};
                }

                settings.permissions[role][capability] = $capability.is(':checked');
            });

            // TODO core.doAction('saveMailchimpSettings', settings);

            return settings;
        },

        /**
         * Save success callback
         */
        onSaveSuccess: function () {
            core.showFixedMessage(settingsL10n.settingsSaved, 'success');

            c.get('#qfb-fixed-save-button').removeClass('qfb-saving').addClass('qfb-saved');

            savingTimeout = setTimeout(function () {
                c.get('#qfb-fixed-save-button').removeClass('qfb-saved');
            }, 2000);
        },

        /**
         * Save error callback
         *
         * @param {string} message
         */
        onSaveError: function (message) {
            core.showFixedMessage(settingsL10n.errorSavingSettings + '<br>' + message, 'error');

            c.get('#qfb-fixed-save-button').removeClass('qfb-saving').addClass('qfb-save-error');

            savingTimeout = setTimeout(function () {
                c.get('#qfb-fixed-save-button').removeClass('qfb-save-error');
            }, 2000);
        },

        /**
         * Verify the Mailchimp API key
         */
        verifyApiKey: function () {
            // Return if we are already verifying
            if (verifying) {
                alert(settingsL10n.waitVerifying);
                return;
            }

            var apiKey = c.get('#qfb_mc_api_key').val();
            if ( ! apiKey.length) {
                return;
            }

            verifying = true;
            c.get('#qfb-mc-settings-verify-loading').show().animate({ maxWidth: '43px' });

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_mc_verify_api_key',
                    _ajax_nonce: settingsL10n.verifyApiKeyNonce,
                    api_key: apiKey
                },
                dataType: 'json'
            }).done(function (response) {
                response = core.sanitiseResponse(response);

                if (response.type === 'success') {
                    module.onVerificationSuccess(response.message);
                } else if (response.type === 'error' || response.type === 'invalid') {
                    module.showVerificationMessage(response.message, 'error');

                    if (response.type === 'invalid') {
                        c.get('#qfb_mc_api_key_verified').prop('checked', false);

                        var $status = c.get('.qfb-license-status'),
                            $messageBox = $status.find('.qfb-message-box');

                        if ($messageBox.hasClass('qfb-message-box-success')) {
                            $status.fadeOut(function () {
                                $messageBox.removeClass('qfb-message-box-success').addClass('qfb-message-box-error').find('.qfb-message-box-inner').text(settingsL10n.unverified);
                                $status.fadeIn();
                            });
                        }
                    }
                }
            }).fail(function () {
                module.onVerificationError(coreL10n.ajaxError);
            }).always(function () {
                c.get('#qfb-mc-settings-verify-loading').css({ maxWidth: 0 }).hide();
                verifying = false;
            });
        },

        /**
         * Show a message with the result of the API key verification
         *
         * @param  {string}  text  The message in HTML
         * @param  {string}  type  The message type, 'success' or 'error'
         */
        showVerificationMessage: function (text, type) {
            c.get('#qfb-settings-verify-message').hide();
            c.get('#qfb-settings-verify-message').find('.qfb-message-box').removeClass('qfb-message-box-success qfb-message-box-error').addClass('qfb-message-box-' + type);
            c.get('#qfb-settings-verify-message').find('.qfb-message-box-inner').html(text);
            c.get('#qfb-settings-verify-message').qfbSlideShow();
        },

        /**
         * Show the successful API key verification message
         *
         * @param {string} message
         */
        onVerificationSuccess: function (message) {
            module.showVerificationMessage(message, 'success');

            c.get('#qfb_mc_api_key_verified').prop('checked', true);

            var $status = c.get('.qfb-license-status'),
                $messageBox = $status.find('.qfb-message-box');

            if ($messageBox.hasClass('qfb-message-box-error')) {
                $status.fadeOut(function () {
                    $messageBox.removeClass('qfb-message-box-error').addClass('qfb-message-box-success').find('.qfb-message-box-inner').text(settingsL10n.verified);
                    $status.fadeIn();
                });
            }
        },

        /**
         * Show the unsuccessful API key verification message
         *
         * @param {string} message
         */
        onVerificationError: function (message) {
            message = settingsL10n.errorVerifying + (message ? ' (' + message + ')' : '');

            module.showVerificationMessage(message, 'error');
        },

        /**
         * Uninstall the plugin
         */
        uninstallPlugin: function () {
            if (uninstalling) {
                return;
            }

            uninstalling = true;

            if ( ! c.get('#qfb_mc_uninstall_confirm').is(':checked') || ! confirm(settingsL10n.uninstallAreYouSure)) {
                uninstalling = false;
                return;
            }

            c.get('#qfb-mc-uninstall-loading').css({ opacity: 1 });

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_mc_uninstall_plugin',
                    _ajax_nonce: settingsL10n.uninstallPluginNonce
                },
                dataType: 'json'
            }).done(function (response) {
                response = core.sanitiseResponse(response);

                if (response.type === 'success') {
                    window.location = settingsL10n.pluginsUrl;
                } else if (response.type === 'error' || response.type === 'invalid') {
                    module.onUninstallPluginFail(response.message, 'error');
                }
            }).fail(function () {
                module.onUninstallPluginFail(coreL10n.ajaxError);
            }).always(function () {
                c.get('#qfb-mc-uninstall-loading').css({ opacity: 0 });
                uninstalling = false;
            });
        },

        /**
         * Show the error message when uninstalling fails
         *
         * @param {string} message
         */
        onUninstallPluginFail: function (message) {
            core.showFixedMessage(settingsL10n.errorUninstalling + '<br>' + message, 'error');
        }

    };

    $(module.init);

    $window.on('load', module.onWindowLoad);

    quform.settings = module;

    return quform;

}(quform || {}, jQuery, quformMailchimpSettingsL10n, quformCoreL10n));
