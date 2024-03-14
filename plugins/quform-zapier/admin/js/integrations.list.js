/**
 * Quform Form Builder WordPress plugin
 *
 * Zapier integrations list module
 *
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

/* globals jQuery, quformZapierIntegrationsListL10n, quformCoreL10n */

var quform = (function (quform, $, listL10n, coreL10n) {
    "use strict";

    var module,
        core = quform.core,
        c = core.cache,
        $document = $(document),
        adding = false,
        saving = false;

    module = {

        init: function () {
            if (window.location.hash === '#add') {
                module.showAddNewPopup();
            }

            c.get('.qfb-tabs-nav-zapier-add-integration').click(function (e) {
                e.preventDefault();
                module.showAddNewPopup();
            });

            c.get('#qfb-zapier-add-new-integration-popup').find('.qfb-popup-close-button').click(module.hideAddNewPopup);

            c.get('#qfb-zapier-add-new-integration-submit').click(module.addNew);

            c.get('#qfb-zapier-add-new-integration-name').keyup(function (event) {
                if (event.keyCode === 13) {
                    c.get('#qfb-zapier-add-new-integration-submit').click();
                }
            });

            c.get('#the-list').find('.column-shortcode input').on('click', function () {
                $(this).focus().select();
            });

            c.get('#the-list').find('.delete a').click(function () {
                return confirm(listL10n.singleConfirmDelete);
            });

            $('#doaction').click(function () {
                if ($('input[name="ids[]"]:checked').length > 0) {
                    if ($('#bulk-action-selector-top').val() === 'delete') {
                        return confirm(listL10n.pluralConfirmDelete);
                    }
                } else {
                    return false;
                }
            });

            $('#doaction2').click(function () {
                if ($('input[name="ids[]"]:checked').length > 0) {
                    if ($('#bulk-action-selector-bottom').val() === 'delete') {
                        return confirm(listL10n.pluralConfirmDelete);
                    }
                } else {
                    return false;
                }
            });

            // List table options
            c.get('#qfb-zapier-show-integration-table-settings').click(function () {
                module.showTableSettings();
            });

            c.get('#qfb-zapier-integrations-table-settings').find('.qfb-popup-save-button').click(function() {
                module.saveTableSettings();
            });

            c.get('#qfb-zapier-integrations-table-settings').find('.qfb-popup-close-button').click(function() {
                module.hideTableSettings();
            });

            c.get('#qfb-zapier-integrations-table-settings').find('.qfb-popup-overlay').click(function () {
                module.hideTableSettings();
            });

            c.get('#qfb_zapier_integrations_per_page').on('keyup', function (e) {
                if (e.keyCode && e.keyCode === 13) {
                    module.saveTableSettings();
                }
            });
        },

        showAddNewPopup: function () {
            $document.on('keydown.quform-close-popup', function (event) {
                if (event.keyCode === 27) {
                    module.hideAddNewPopup();
                }
            });

            c.get('body').css('overflow', 'hidden');
            c.get('#qfb-zapier-add-new-integration-popup').show();
            c.get('#qfb-zapier-add-new-integration-name').focus();
        },

        hideAddNewPopup: function () {
            $document.off('keydown.quform-close-popup');
            c.get('#qfb-zapier-add-new-integration-popup').hide();
            c.get('body').css('overflow', '');
        },

        addNew: function () {
            if (adding) {
                return;
            }
            adding = true;

            c.get('#qfb-zapier-add-new-integration-loading').css({ opacity: 1 });

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_zapier_add_integration',
                    _ajax_nonce: listL10n.addNewNonce,
                    name: c.get('#qfb-zapier-add-new-integration-name').val()
                },
                dataType: 'json'
            })
            .done(function (response) {
                response = core.sanitizeResponse(response);

                if (response.type === 'success') {
                    window.location = response.url;
                    return;
                }

                c.get('#qfb-zapier-add-new-integration-popup').find('.qfb-validation-error').remove();
                adding = false;

                if (response.type === 'error' || response.type === 'invalid') {
                    module.onAddFail(response);
                }
            })
            .fail(function () {
                c.get('#qfb-zapier-add-new-integration-popup').find('.qfb-validation-error').remove();
                adding = false;
                module.onAddFail({ message: coreL10n.ajaxError });
            })
            .always(function () {
                c.get('#qfb-zapier-add-new-integration-loading').css({ opacity: 0 });
            });
        },

        /**
         * @param {Object} response The response from the server
         */
        onAddFail: function (response) {
            var $firstError;

            if (core.isNonEmptyString(response.message)) {
                core.showFixedMessage(listL10n.errorAdding + '<br>' + response.message, 'error');
            }

            if (response.errors) {
                $.each(response.errors, function (id, message) {
                    var $setting = c.get('#' + id).closest(core.settingInputWrap);

                    if ( ! $firstError) {
                        $firstError = $setting;
                    }

                    core.addValidationError($setting, message);
                });
            }

            if ($firstError) {
                core.scrollTo($firstError, c.get('#qfb-zapier-add-new-integration-popup-inner'));
            }
        },

        /**
         * Show the table settings popup
         */
        showTableSettings: function () {
            c.get('body').css('overflow', 'hidden');
            c.get('#qfb-zapier-integrations-table-settings').show();
        },

        /**
         * Hide the table settings popup
         */
        hideTableSettings: function () {
            c.get('body').css('overflow', '');
            c.get('#qfb-zapier-integrations-table-settings').hide();
            c.get('#qfb-zapier-integrations-table-settings').find('.qfb-submission-error').remove();
            c.get('#qfb-zapier-integrations-table-settings').find('.qfb-validation-error').remove();
        },

        /**
         * Save the table settings
         */
        saveTableSettings: function () {
            if (saving) {
                return;
            }

            saving = true;

            c.get('#qfb-zapier-integrations-table-settings').find('.qfb-submission-error').remove();
            c.get('#qfb-zapier-integrations-table-settings').find('.qfb-validation-error').remove();

            $.ajax({
                url: coreL10n.ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'quform_zapier_save_integrations_table_settings',
                    _ajax_nonce: listL10n.saveTableSettingsNonce,
                    per_page: c.get('#qfb_zapier_integrations_per_page').val()
                }
            }).done(function (response) {
                response = core.sanitizeResponse(response);
                saving = false;

                if (response.type === 'success') {
                    window.location.reload();
                } else if (response.type === 'error' || response.type === 'invalid') {
                    var errors = [];

                    if (response.message) {
                        errors.push(core.addSubmissionError(c.get('#qfb-zapier-integrations-table-settings').find('.qfb-settings'), response.message));
                    }

                    if (response.errors) {
                        $.each(response.errors, function (id, message) {
                            var $setting = c.get('#' + id).closest(core.settingInputWrap);
                            core.addValidationError($setting, message);
                            errors.push($setting);
                        });
                    }

                    if (errors.length) {
                        core.scrollTo(errors[0], c.get('#qfb-zapier-integrations-table-settings-inner'));
                    }
                }
            }).fail(function () {
                core.scrollTo(
                    core.addSubmissionError(c.get('#qfb-zapier-integrations-table-settings').find('.qfb-settings'), coreL10n.ajaxError),
                    c.get('#qfb-zapier-integrations-table-settings-inner')
                );
                saving = false;
            });
        }

    };

    $(module.init);

    quform.zapier = quform.zapier || {};
    quform.zapier.list = module;

    return quform;

}(quform, jQuery, quformZapierIntegrationsListL10n, quformCoreL10n));
