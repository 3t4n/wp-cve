/**
 * Quform Form Builder WordPress plugin
 *
 * Mailchimp integrations edit module
 *
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

/* globals jQuery, quformMailchimpIntegrationsEditL10n, quformCoreL10n */

var quform = (function (quform, $, editL10n, coreL10n) {
    "use strict";

    var module,
        core = quform.core,
        c = core.cache,
        $window = $(window),
        $document = $(document),
        saving = false,
        syncingLists = false,
        syncingFormFields = false,
        syncingMergeFields = false,
        syncingGroups = false,
        syncingLogic = false,
        savingTimeout;

    module = {

        // The integration configuration
        integration: {},

        // The JSON string of the currently saved integration configuration
        savedIntegrationJson: '',

        // The most recently fetched merge field tags
        currentMergeFieldTags: [],

        // The most recently fetched logic source elements
        currentLogicSources: [],

        /**
         * Initialise this module
         *
         * @param {object} integration
         */
        init: function (integration) {
            module.integration = integration;

            // Do not allow the form to be submitted
            c.get('#qfb-mc-integrations-edit-form').submit(function() {
                return false;
            });

            c.get('#qfb-mc-save-integration').add(c.get('#qfb-fixed-save-button')).click(module.save);

            // If something has changed, show an alert if leaving the page
            module.savedIntegrationJson = JSON.stringify(module.integration);
            window.onbeforeunload = function () {
                module.update();
                if (module.savedIntegrationJson !== JSON.stringify(module.integration)) {
                    return editL10n.unsavedChanges;
                }
            };

            module.syncLists(module.getValue(module.integration, 'listId'));

            c.get('#qfb-mc-integration-list-sync').click(function () {
                module.syncLists(c.get('#qfb_mc_integration_list').val());
            });

            module.syncFormFields(module.getValue(module.integration, 'emailElement'));

            c.get('#qfb_mc_integration_form').change(function () {
                module.syncFormFields();
            });

            c.get('#qfb-mc-integration-email-sync').click(function () {
                module.syncFormFields(c.get('#qfb_mc_integration_email_element').val());
            });

            // Merge fields
            module.syncMergeFields(
                module.getValue(module.integration, 'formId'),
                module.getValue(module.integration, 'listId'),
                module.getValue(module.integration, 'mergeFields')
            );

            c.get('#qfb-mc-merge-add-merge-field').click(function () {
                c.get('#qfb-mc-integration-merge-fields-empty').hide();
                c.get('#qfb-mc-merge-fields').append(module.getMergeFieldHtml(module.getNewMergeField())).show();
            });

            c.get('#qfb-mc-merge-fields').on('click', '.qfb-small-add-button', function () {
                $(this).closest('.qfb-mc-merge-field').after(module.getMergeFieldHtml(module.getNewMergeField()));
            });

            c.get('#qfb-mc-merge-fields').on('click', '.qfb-small-remove-button', function () {
                $(this).closest('.qfb-mc-merge-field').remove();

                if (c.get('#qfb-mc-merge-fields').find('> .qfb-mc-merge-field').length === 0) {
                    c.get('#qfb-mc-integration-merge-fields-empty').fadeIn();
                }
            });

            c.get('#qfb_mc_integration_form').add(c.get('#qfb_mc_integration_list')).change(module.resyncMergeFields);
            c.get('#qfb-mc-integration-merge-fields-sync').click(module.resyncMergeFields);

            // Insert variable
            c.get('#qfb-mc-integrations-edit-form').on('click', '.qfb-mc-insert-variable', function (e) {
                e.preventDefault();
                e.stopPropagation();

                module.openInsertVariableMenu($(this));
            });

            c.get('#qfb-mc-integrations-edit-form').on('click', '.qfb-mc-merge-field-value', function (e) {
                e.stopPropagation(); // Fix menu closing instantly
            });

            c.get('#qfb-mc-integrations-edit-form').on('focus', '.qfb-mc-merge-field-value', function () {
                if ( ! core.isNonEmptyString($(this).val())) {
                    module.openInsertVariableMenu($(this).siblings('.qfb-mc-insert-variable'));
                }
            });

            // Groups
            module.syncGroups(
                module.getValue(module.integration, 'listId'),
                module.getValue(module.integration, 'groups')
            );

            if ($.fn.select2) {
                c.get('#qfb_mc_integration_groups').select2({
                    theme: 'qfb',
                    language: {
                        noResults: function () {
                            return coreL10n.noResultsFound;
                        }
                    }
                });
            }

            c.get('#qfb-mc-integration-groups-sync').click(function () {
                module.syncGroups(
                    c.get('#qfb_mc_integration_list').val(),
                    c.get('#qfb_mc_integration_groups').val() || []
                );
            });

            c.get('#qfb_mc_integration_list').change(function () {
                module.syncGroups(c.get('#qfb_mc_integration_list').val(), []);
            });

            // Conditional logic
            c.get('#qfb_mc_integration_logic_enabled').change(function () {
                c.get('#qfb-mc-logic').closest(core.settingWrap)[c.get('#qfb_mc_integration_logic_enabled').is(':checked') ? 'qfbSlideShow' : 'qfbSlideHide']();
            });

            module.syncLogic(
                module.getValue(module.integration, 'formId'),
                module.getValue(module.integration, 'logicAction'),
                module.getValue(module.integration, 'logicMatch'),
                module.getValue(module.integration, 'logicRules')
            );

            c.get('#qfb_mc_integration_form').change(function () {
                // Reset logic when switching forms
                module.resetLogic();
                module.resyncLogic();
            });

            c.get('#qfb-mc-integration-logic-sync').click(module.resyncLogic);

            c.get('#qfb-add-logic-rule').click(function () {
                c.get('#qfb-mc-logic').find('.qfb-no-logic-rules').remove();
                c.get('#qfb-mc-logic').find('.qfb-logic-rules').append(module.buildLogicRule(module.getNewLogicRule()));
            });
        },

        /**
         * Update the integration config with the current form settings
         */
        update: function () {
            module.integration.name = c.get('#qfb_mc_integration_name').val();
            module.integration.active = c.get('#qfb_mc_integration_active').is(':checked');
            module.integration.formId = c.get('#qfb_mc_integration_form').val();
            module.integration.listId = c.get('#qfb_mc_integration_list').val();
            module.integration.listName = module.integration.listId === '' ? '' : c.get('#qfb_mc_integration_list').find('option:selected').text();
            module.integration.emailElement = c.get('#qfb_mc_integration_email_element').val();
            module.integration.doubleOptIn = c.get('#qfb_mc_integration_double_opt_in').is(':checked');

            module.integration.mergeFields = [];
            c.get('#qfb-mc-merge-fields').find('> .qfb-mc-merge-field').each(function () {
                var $mergeField = $(this);

                module.integration.mergeFields.push({
                    tag: $mergeField.find('.qfb-mc-merge-field-tag').val(),
                    value: $mergeField.find('.qfb-mc-merge-field-value').val()
                });
            });

            module.integration.groups = c.get('#qfb_mc_integration_groups').val() || [];
            module.integration.tags = c.get('#qfb_mc_integration_tags').val();
            module.integration.logicEnabled = c.get('#qfb_mc_integration_logic_enabled').is(':checked');
            module.integration.logicAction = c.get('#qfb-mc-logic').find('.qfb-logic-action').val() !== '0';
            module.integration.logicMatch = c.get('#qfb-mc-logic').find('.qfb-logic-match').val() || 'all';
            module.integration.logicRules = module.getLogicRules(c.get('#qfb-mc-logic'));
        },

        /**
         * Validate the integration config
         *
         * @return {boolean}
         */
        validate: function () {
            c.get('#qfb-mc-integrations-edit-form').find('.qfb-validation-error').remove();
            c.get('#qfb-mc-integrations-edit-form').find('.qfb-field-error').removeClass('qfb-field-error');

            var errors = [];

            if ( ! core.isNonEmptyString(module.integration.name)) {
                errors.push({
                    scrollTarget: c.get('#qfb_mc_integration_name').closest(core.settingWrap),
                    show: function () {
                        c.get('#qfb_mc_integration_name').addClass('qfb-field-error');
                        core.addValidationError(c.get('#qfb_mc_integration_name').closest(core.settingInputWrap), coreL10n.thisFieldIsRequired);
                    }
                });
            }

            if ( ! core.isNonEmptyString(module.integration.formId)) {
                errors.push({
                    scrollTarget: c.get('#qfb_mc_integration_form').closest(core.settingWrap),
                    show: function () {
                        c.get('#qfb_mc_integration_form').addClass('qfb-field-error');
                        core.addValidationError(c.get('#qfb_mc_integration_form').closest(core.settingInputWrap), coreL10n.thisFieldIsRequired);
                    }
                });
            }

            if ( ! core.isNonEmptyString(module.integration.listId)) {
                errors.push({
                    scrollTarget: c.get('#qfb_mc_integration_list').closest(core.settingWrap),
                    show: function () {
                        c.get('#qfb_mc_integration_list').addClass('qfb-field-error');
                        core.addValidationError(c.get('#qfb_mc_integration_list').closest(core.settingInputWrap), coreL10n.thisFieldIsRequired);
                    }
                });
            }

            if ( ! core.isNonEmptyString(module.integration.emailElement)) {
                errors.push({
                    scrollTarget: c.get('#qfb_mc_integration_email_element').closest(core.settingWrap),
                    show: function () {
                        c.get('#qfb_mc_integration_email_element').addClass('qfb-field-error');
                        core.addValidationError(c.get('#qfb_mc_integration_email_element').closest(core.settingInputWrap), coreL10n.thisFieldIsRequired);
                    }
                });
            }

            c.get('#qfb-mc-merge-fields').find('> .qfb-mc-merge-field').each(function () {
                module.validateMergeField($(this), errors);
            });

            if (errors.length) {
                core.showFixedMessage(editL10n.correctHighlightedFields, 'error');

                for (var i = 0; i < errors.length; i++) {
                    errors[i].show();
                }

                core.scrollTo(errors[0].scrollTarget);

                return false;
            }

            return true;
        },

        /**
         * Validate a single merge field
         *
         * @param  {jQuery}  $mergeField
         * @param  {array}   errors
         */
        validateMergeField: function ($mergeField, errors) {
            var $tag = $mergeField.find('.qfb-mc-merge-field-tag'),
                $value = $mergeField.find('.qfb-mc-merge-field-value');

            if ( ! core.isNonEmptyString($tag.val())) {
                errors.push({
                    scrollTarget: $mergeField,
                    show: function () {
                        $tag.addClass('qfb-field-error');
                        core.addValidationError($tag.closest('.qfb-mc-merge-field-column'), coreL10n.thisFieldIsRequired);
                    }
                });
            }

            if ( ! core.isNonEmptyString($value.val())) {
                errors.push({
                    scrollTarget: $mergeField,
                    show: function () {
                        $value.addClass('qfb-field-error');
                        core.addValidationError($value.closest('.qfb-mc-merge-field-column'), coreL10n.thisFieldIsRequired);
                    }
                });
            }
        },

        /**
         * Save the integration
         */
        save: function () {
            if (saving) {
                return;
            }

            saving = true;

            module.update();

            if ( ! module.validate()) {
                saving = false;
                return;
            }

            if (typeof savingTimeout === 'number') {
                clearTimeout(savingTimeout);
                savingTimeout = null;
            }

            c.get('#qfb-fixed-save-button').removeClass('qfb-saving qfb-saved qfb-save-error').addClass('qfb-saving');

            var integration = JSON.stringify(module.integration);

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_mc_save_integration',
                    _ajax_nonce: editL10n.saveIntegrationNonce,
                    integration: integration
                },
                dataType: 'json'
            }).done(function (response) {
                response = core.sanitizeResponse(response);

                switch (response.type) {
                    case 'success':
                        module.savedIntegrationJson = integration;

                        core.showFixedMessage(editL10n.integrationSaved, 'success');

                        c.get('#qfb-fixed-save-button').removeClass('qfb-saving').addClass('qfb-saved');
                        savingTimeout = setTimeout(function () {
                            c.get('#qfb-fixed-save-button').removeClass('qfb-saved');
                        }, 2000);
                        break;
                    case 'error':
                    case 'invalid':
                        core.showFixedMessage(editL10n.errorSavingIntegration + '<br>' + response.message, 'error');
                        c.get('#qfb-fixed-save-button').removeClass('qfb-saving').addClass('qfb-save-error');
                        break;
                }
            }).fail(function () {
                core.showFixedMessage(editL10n.errorSavingIntegration + '<br>' + coreL10n.ajaxError, 'error');
                c.get('#qfb-fixed-save-button').removeClass('qfb-saving').addClass('qfb-save-error');
            }).always(function () {
                saving = false;
            });
        },

        /**
         * Sync the available lists with the latest data from the MailChimp API
         *
         * @param {string} [selectedList]
         */
        syncLists: function (selectedList) {
            if (syncingLists) {
                return;
            }

            syncingLists = true;

            c.get('#qfb_mc_integration_list').hide();
            c.get('#qfb-mc-integration-list-error').hide().html('');
            c.get('#qfb-mc-integration-list-spinner').show();

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_mc_get_lists'
                },
                dataType: 'json'
            }).done(function (response) {
                response = core.sanitizeResponse(response);

                if (response.type === 'success') {
                    var lists = [new Option(editL10n.pleaseSelect, '')];

                    for (var i = 0, length = response.lists.length; i < length; i++) {
                        lists.push(new Option(response.lists[i].name, response.lists[i].id));
                    }

                    c.get('#qfb_mc_integration_list').html(lists).show();
                    core.setSelectVal(c.get('#qfb_mc_integration_list'), selectedList);
                } else {
                    module.onSyncListsFail(response.message);
                }
            }).fail(function () {
                module.onSyncListsFail(coreL10n.ajaxError);
            }).always(function () {
                syncingLists = false;
                c.get('#qfb-mc-integration-list-spinner').hide();
            });
        },

        /**
         * When list syncing fails, display the error message
         *
         * @param {string} [message]
         */
        onSyncListsFail: function (message) {
            c.get('#qfb-mc-integration-list-error').html(message).show();
        },

        /**
         * Sync the available Email elements with the latest data from the database
         *
         * @param {string} [selectedEmailElement]
         */
        syncFormFields: function (selectedEmailElement) {
            if (syncingFormFields) {
                return;
            }

            syncingFormFields = true;

            c.get('#qfb_mc_integration_email_element').hide();
            c.get('#qfb-mc-integration-email-error').hide().html('');
            c.get('#qfb-mc-integration-email-spinner').show();

            var formId = c.get('#qfb_mc_integration_form').val();

            if ( ! $.isNumeric(formId)) {
                syncingFormFields = false;
                module.onSyncFormFieldsFail(editL10n.pleaseSelectAFormFirst);
                return;
            }

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_mc_get_form_email_elements',
                    form_id: formId
                },
                dataType: 'json'
            }).done(function (response) {
                response = core.sanitizeResponse(response);

                if (response.type === 'success') {
                    var elements = [new Option(editL10n.pleaseSelect, '')];

                    for (var i = 0, length = response.elements.length; i < length; i++) {
                        elements.push(new Option(response.elements[i].label, response.elements[i].name));
                    }

                    c.get('#qfb_mc_integration_email_element').html(elements).show();

                    if (typeof selectedEmailElement === 'string') {
                        core.setSelectVal(c.get('#qfb_mc_integration_email_element'), selectedEmailElement);
                    }
                } else {
                    module.onSyncFormFieldsFail(response.message);
                }
            }).fail(function () {
                module.onSyncFormFieldsFail(coreL10n.ajaxError);
            }).always(function () {
                syncingFormFields = false;
                c.get('#qfb-mc-integration-email-spinner').hide();
            });
        },

        /**
         * When element syncing fails, display the error message
         *
         * @param {string} [message]
         */
        onSyncFormFieldsFail: function (message) {
            c.get('#qfb-mc-integration-email-spinner').hide();
            c.get('#qfb-mc-integration-email-error').html(message).show();
        },

        /**
         * Sync the available merge fields with the latest data from the MailChimp API
         *
         * @param  {string}  formId
         * @param  {string}  listId
         * @param  {array}   mergeFields
         */
        syncMergeFields: function (formId, listId, mergeFields) {
            if (syncingMergeFields) {
                return;
            }

            syncingMergeFields = true;

            c.get('#qfb-mc-merge-fields').hide().html('');
            c.get('#qfb-mc-integration-merge-fields-error').hide().html('');
            c.get('#qfb-mc-integration-merge-fields-empty').hide();
            c.get('#qfb-mc-integration-merge-fields-spinner').show();

            if ( ! $.isNumeric(formId)) {
                syncingMergeFields = false;
                module.onSyncMergeFieldsFail(editL10n.pleaseSelectAFormFirst);
                return;
            }

            if ( ! core.isNonEmptyString(listId)) {
                syncingMergeFields = false;
                module.onSyncMergeFieldsFail(editL10n.pleaseSelectAListFirst);
                return;
            }

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_mc_get_merge_fields',
                    form_id: formId,
                    list_id: listId
                },
                dataType: 'json'
            }).done(function (response) {
                response = core.sanitizeResponse(response);

                if (response.type === 'success') {
                    module.currentMergeFieldTags = response.tags;
                    module.rebuildInsertVariableMenuElements(response.elements);
                    module.rebuildMergeFields(mergeFields);
                } else {
                    module.onSyncMergeFieldsFail(response.message);
                }
            }).fail(function () {
                module.onSyncMergeFieldsFail(coreL10n.ajaxError);
            }).always(function () {
                syncingMergeFields = false;
                c.get('#qfb-mc-integration-merge-fields-spinner').hide();
            });
        },

        /**
         * When merge field syncing fails, display the error message
         *
         * @param {string} [message]
         */
        onSyncMergeFieldsFail: function (message) {
            module.currentMergeFieldTags = [];
            module.integration.mergeFields = [];

            c.get('#qfb-mc-integration-merge-fields-spinner').hide();
            c.get('#qfb-mc-integration-merge-fields-error').html(message).show();
        },

        /**
         * Refresh the available tags and values in the merge fields
         */
        resyncMergeFields: function () {
            module.update();

            module.syncMergeFields(
                c.get('#qfb_mc_integration_form').val(),
                c.get('#qfb_mc_integration_list').val(),
                module.getValue(module.integration, 'mergeFields')
            );
        },

        /**
         * Rebuild the HTML for the merge fields
         *
         * @param {array} mergeFields
         */
        rebuildMergeFields: function(mergeFields) {
            var html = [],
                i = 0,
                length = mergeFields.length;

            if (length > 0) {
                for ( ; i < length; i++) {
                    html.push(module.getMergeFieldHtml(mergeFields[i]));
                }

                c.get('#qfb-mc-merge-fields').html(html).show();
            } else {
                c.get('#qfb-mc-merge-fields').hide();
                c.get('#qfb-mc-integration-merge-fields-empty').show();
            }
        },

        /**
         * Get a new empty merge field
         *
         * @return {{ tag: string, value: string }}
         */
        getNewMergeField: function () {
            return { tag: '', value: '' };
        },

        /**
         * Get the HTML for the given merge field as a jQuery object
         *
         * @param   {{ tag: string, value: string }}  mergeField
         * @return  {jQuery}
         */
        getMergeFieldHtml: function (mergeField) {
            var $mergeField = $(editL10n.mergeFieldHtml),
                $tag = $('<select class="qfb-mc-merge-field-tag">'),
                tags = [new Option(editL10n.selectAMailchimpTag, '')],
                valueVariableId = module.getVariableId(),
                i = 0;

            for ( ; i < module.currentMergeFieldTags.length; i++) {
                tags.push(new Option(module.currentMergeFieldTags[i].label, module.currentMergeFieldTags[i].tag));
            }

            $mergeField.find('.qfb-mc-merge-field-column-tag').html($tag.html(tags));
            core.setSelectVal($tag, mergeField.tag);

            $mergeField.find('.qfb-mc-merge-field-value').val(mergeField.value).attr('id', valueVariableId).siblings('.qfb-mc-insert-variable').data('target-id', valueVariableId);

            return $mergeField;
        },

        /**
         * Get a unique ID
         *
         * @return {string}
         */
        getVariableId: function getVariableId() {
            getVariableId.count = getVariableId.count || 0;

            return 'qmc' + ++getVariableId.count;
        },

        /**
         * Open the insert variable menu
         *
         * @param {jQuery} $trigger
         */
        openInsertVariableMenu: function ($trigger) {
            module.closeInsertVariableMenu();

            var $menu = c.get('#qfb-mc-insert-variable'),
                menuOuterHeight = $menu.outerHeight(),
                topPosition = ($trigger.offset().top - $window.scrollTop()) + ($trigger.outerHeight() / 2) - (menuOuterHeight / 2),
                leftPosition = $trigger.offset().left + $trigger.outerWidth(),
                bottomPosition = 'auto';

            if (topPosition < 42) {
                // Stop the menu going out of the top of the viewport
                topPosition = 42;
            } else if (topPosition + menuOuterHeight > $window.height() - 10) {
                // Stop the menu going out of the bottom of the viewport
                topPosition = 'auto';
                bottomPosition = 10;
            }

            $trigger.addClass('qfb-mc-active');
            $menu.css({ top: topPosition, left: leftPosition, bottom: bottomPosition }).fadeIn(200);

            c.get('#qfb-mc-integrations-edit-form').one('click.insert-variable', '.qfb-mc-variable', function (e) {
                e.preventDefault();

                var targetId = $trigger.data('target-id'),
                    tag = core.toString($(this).data('tag')) || '';

                if (tag.length) {
                    var $target = $('#' + targetId);

                    if ($target.length) {
                        module.insertAtCursor($target[0], tag);
                    }
                }
            });

            $document.one('click.close-insert-variable', module.closeInsertVariableMenu);
        },

        /**
         * Close the insert variable menu and unbind the close handler
         */
        closeInsertVariableMenu: function () {
            $('.qfb-mc-insert-variable').removeClass('qfb-mc-active');
            c.get('#qfb-mc-insert-variable').add(c.get('#qfb-mc-insert-variable-pre-process')).scrollTop(0).hide();
            c.get('#qfb-mc-integrations-edit-form').off('click.insert-variable');
            $document.off('click.close-insert-variable');
        },

        /**
         * Insert the given content to the given field at the cursor position
         *
         * @param  {HTMLTextAreaElement|HTMLInputElement}  field
         * @param  {string}                                content
         */
        insertAtCursor: function(field, content) {
            var sel, startPos, endPos, scrollTop, text, event;

            if (document.selection) { //IE
                field.focus();
                sel = document.selection.createRange();
                sel.text = content;
                field.focus();
            } else if (field.selectionStart || field.selectionStart === 0) { // FF, WebKit, Opera
                text = field.value;
                startPos = field.selectionStart;
                endPos = field.selectionEnd;
                scrollTop = field.scrollTop;

                field.value = text.substring(0, startPos) + content + text.substring(endPos, text.length);

                field.selectionStart = startPos + content.length;
                field.selectionEnd = startPos + content.length;
                field.scrollTop = scrollTop;
                field.focus();
            } else {
                field.value += content;
                field.focus();
            }

            if (document.createEvent) {
                event = document.createEvent('HTMLEvents');
                event.initEvent('change', false, true);
                field.dispatchEvent(event);
            } else if (field.fireEvent) {
                field.fireEvent('onchange');
            }

            return true;
        },

        /**
         * Rebuild the form elements in the insert variable menu
         *
         * @param {array} elements
         */
        rebuildInsertVariableMenuElements: function (elements) {
            var variables = [];

            for (var i = 0; i < elements.length; i++) {
                var element = elements[i],
                    shortLabel = module.shorten(element.label);

                variables.push($('<div class="qfb-mc-variable">').data({
                    tag: '{element|id:' + element.id + '|' + shortLabel + '}',
                    id: element.id
                }).html([
                    $('<span class="qfb-mc-variable-label">').text(shortLabel),
                    $('<span class="qfb-mc-variable-identifier">').text('(' + element.identifier + ')')
                ]));
            }

            c.get('#qfb-mc-insert-variable-element').html(variables);
        },

        /**
         * Shorten the given string to maxLength characters
         *
         * @param   {string}  text
         * @param   {number}  [maxLength]
         * @param   {string}  [join]
         * @return  {string}
         */
        shorten: function (text, maxLength, join) {
            maxLength = maxLength || 30;
            join = join || '...';

            var halfLength = Math.floor(maxLength / 2);

            if (text.length > maxLength) {
                var firstHalf = text.slice(0, halfLength - 1);
                var secondHalf = text.slice(-halfLength);
                text = firstHalf + join + secondHalf;
            }

            return text;
        },

        /**
         * Get the element admin label, or if that is empty get the element label
         *
         * @param   {Object}  element
         * @return  {string}
         */
        getAdminLabel: function (element) {
            if (typeof element.adminLabel === 'string' && element.adminLabel.length > 0) {
                return element.adminLabel;
            }

            if (typeof element.label === 'string' && element.label.length > 0) {
                return element.label;
            }

            return '';
        },

        /**
         * Get a shortened version of the element admin label
         *
         * @param   {Object}  element
         * @return  {string}
         */
        getShortenedAdminLabel: function (element) {
            var adminLabel = module.shorten(module.getAdminLabel(element));

            return editL10n.adminLabelElementId.replace('%1$s', adminLabel).replace('%2$s', element.identifier);
        },

        /**
         * Sync the groups field
         *
         * @param  {string}  listId
         * @param  {array}   selectedGroups
         */
        syncGroups: function (listId, selectedGroups) {
            if (syncingGroups) {
                return;
            }

            syncingGroups = true;

            c.get('#qfb-mc-integration-groups-outer').hide();
            c.get('#qfb-mc-integration-groups-error').hide().html('');
            c.get('#qfb-mc-integration-groups-spinner').show();

            if ( ! core.isNonEmptyString(listId)) {
                syncingGroups = false;
                module.onSyncGroupsFail(editL10n.pleaseSelectAListFirst);
                return;
            }

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_mc_get_groups',
                    list_id: listId
                },
                dataType: 'json'
            }).done(function (response) {
                response = core.sanitizeResponse(response);

                if (response.type === 'success') {
                    var categories = [];

                    for (var i = 0, length = response.categories.length; i < length; i++) {
                        var category = document.createElement('optgroup');
                        category.label = response.categories[i].title;

                        for (var j = 0; j < response.categories[i].interests.length; j++) {
                            category.appendChild(new Option(response.categories[i].interests[j].name, response.categories[i].interests[j].id));
                        }

                        categories.push(category);
                    }

                    c.get('#qfb_mc_integration_groups').html(categories).val(selectedGroups).trigger('change');
                    c.get('#qfb-mc-integration-groups-outer').show();
                } else {
                    module.onSyncGroupsFail(response.message);
                }
            }).fail(function () {
                module.onSyncGroupsFail(coreL10n.ajaxError);
            }).always(function () {
                syncingGroups = false;
                c.get('#qfb-mc-integration-groups-spinner').hide();
            });
        },

        /**
         * When group syncing fails, display the error message
         *
         * @param {string} message
         */
        onSyncGroupsFail: function (message) {
            c.get('#qfb-mc-integration-groups-spinner').hide();
            c.get('#qfb-mc-integration-groups-error').html(message).show();
        },

        /**
         * Build the logic HTML based on the given logic settings
         *
         * @param  {string}   formId  The form ID
         * @param  {boolean}  action  The logic action
         * @param  {string}   match   The logic match
         * @param  {array}    rules   The logic rules
         */
        syncLogic: function (formId, action, match, rules) {
            if (syncingLogic) {
                return;
            }

            syncingLogic = true;

            c.get('#qfb-mc-logic').hide();
            c.get('#qfb-mc-logic-error').hide().html('');
            c.get('#qfb-mc-integration-logic-spinner').show();

            if ( ! $.isNumeric(formId)) {
                syncingLogic = false;
                module.onSyncLogicFail(editL10n.pleaseSelectAFormFirst);
                return;
            }

            $.ajax({
                type: 'POST',
                url: coreL10n.ajaxUrl,
                data: {
                    action: 'quform_mc_get_logic_sources',
                    form_id: formId
                },
                dataType: 'json'
            }).done(function (response) {
                response = core.sanitizeResponse(response);

                if (response.type === 'success') {
                    module.currentLogicSources = response.logicSources;
                    module.buildLogic([
                            { text: editL10n.runThisIntegration, value: '1' },
                            { text: editL10n.doNotRunThisIntegration, value: '0' }
                        ],
                        [
                            { text: editL10n.ifAllOfTheseRulesMatch, value: 'all' },
                            { text: editL10n.ifAnyOfTheseRulesMatch, value: 'any' }
                        ],
                        rules,
                        c.get('#qfb-mc-logic'),
                        action ? '1' : '0',
                        match
                    );
                } else {
                    module.onSyncLogicFail(response.message);
                }
            }).fail(function () {
                module.onSyncLogicFail(coreL10n.ajaxError);
            }).always(function () {
                syncingLogic = false;
                c.get('#qfb-mc-integration-logic-spinner').hide();
            });
        },

        onSyncLogicFail: function (message) {
            module.resetLogic();

            c.get('#qfb-mc-integration-logic-spinner').hide();
            c.get('#qfb-mc-logic-error').html(message).show();
        },

        /**
         * Build the HTML for the given logic
         *
         * @param  {array}   actions         The array of logic action options
         * @param  {array}   matches         The array of logic match options
         * @param  {array}   rules           The logic rules
         * @param  {jQuery}  $logic          The jQuery object for the wrapper of the logic
         * @param  {string}  selectedAction  The selected action
         * @param  {string}  selectedMatch   The selected match
         */
        buildLogic: function (actions, matches, rules, $logic, selectedAction, selectedMatch) {
            // If there are no logic elements, display a message and return
            if (module.currentLogicSources.length === 0) {
                $logic.html(module.buildMessageBox(editL10n.noLogicElements, 'warning'));
                return;
            }

            $logic.empty();

            var $logicTop = $('<div class="qfb-logic-top qfb-settings-row">'),
                $logicTopLeft = $('<div class="qfb-settings-column">'),
                $logicTopRight = $('<div class="qfb-settings-column">');

            if (actions.length) {
                $logicTop.addClass('qfb-settings-row-2');
                var $actions = $('<select class="qfb-logic-action">');

                for (var i = 0; i < actions.length; i++) {
                    $actions.append($('<option>', actions[i]));
                }

                core.setSelectVal($actions, selectedAction);
                $logicTop.append($logicTopLeft.append($actions));
            }

            var $matches = $('<select class="qfb-logic-match">');

            for (var j = 0; j < matches.length; j++) {
                $matches.append($('<option>', matches[j]));
            }

            core.setSelectVal($matches, selectedMatch);
            $logicTop.append($logicTopRight.append($matches));

            $logic.append($logicTop);

            var $rules = $('<div class="qfb-logic-rules qfb-cf">');

            if (rules.length) {
                for (var k = 0; k < rules.length; k++) {
                    $rules.append(module.buildLogicRule(rules[k]));
                }
            } else {
                $rules.append(module.getNoLogicRulesMessage());
            }

            $logic.append($rules).show();
        },

        /**
         * Build the logic rule HTML for the given rule
         *
         * @param   {Object}  rule  The rule configuration
         * @return  {Object}        The jQuery object for the rule wrapper
         */
        buildLogicRule: function (rule) {
            // If there are no logic sources, the rule is not an object we cannot build the rule
            if (module.currentLogicSources.length === 0 || typeof rule !== 'object') {
                return null;
            }

            var $rule = $(editL10n.logicRuleHtml).data('rule', rule);

            var $element = $('<select class="qfb-logic-rule-element">');

            for (var i = 0; i < module.currentLogicSources.length; i++) {
                $element.append($('<option>', { text: module.getShortenedAdminLabel(module.currentLogicSources[i]), value: module.currentLogicSources[i].id }));
            }

            core.setSelectVal($element, rule.elementId);
            $rule.find('.qfb-logic-rule-column-element').html($element);

            // Build the operator select menu
            var $operator = $('<select class="qfb-logic-rule-operator">');

            $operator.append($('<option>', { text: editL10n.is, value: 'eq' }));
            $operator.append($('<option>', { text: editL10n.isNot, value: 'neq' }));
            $operator.append($('<option>', { text: editL10n.isEmpty, value: 'empty' }));
            $operator.append($('<option>', { text: editL10n.isNotEmpty, value: 'not_empty' }));
            $operator.append($('<option>', { text: editL10n.greaterThan, value: 'gt' }));
            $operator.append($('<option>', { text: editL10n.lessThan, value: 'lt' }));
            $operator.append($('<option>', { text: editL10n.contains, value: 'contains' }));
            $operator.append($('<option>', { text: editL10n.startsWith, value: 'starts_with' }));
            $operator.append($('<option>', { text: editL10n.endsWith, value: 'ends_with' }));

            core.setSelectVal($operator, rule.operator);
            $rule.find('.qfb-logic-rule-column-operator').html($operator);

            $rule.find('.qfb-logic-rule-column-value').html(module.buildLogicRuleValues($element.val(), rule));

            // When changing the element or operator select menu, save the element ID and rebuild the operators and value fields
            $rule.on('change', '.qfb-logic-rule-element, .qfb-logic-rule-operator', function () {
                var rule = {
                    elementId: $rule.find('.qfb-logic-rule-element').val(),
                    operator: $rule.find('.qfb-logic-rule-operator').val(),
                    value: '',
                    optionId: null
                };

                $rule.find('.qfb-logic-rule-column-value').html(module.buildLogicRuleValues($element.val(), rule));
            });

            $rule.find('.qfb-small-add-button').click(function () {
                $(this).closest('.qfb-logic-rule').after(module.buildLogicRule(module.getNewLogicRule()));
            });

            $rule.find('.qfb-small-remove-button').click(function () {
                var $rules = $(this).closest('.qfb-logic-rules');

                $(this).closest('.qfb-logic-rule').remove();

                if ($rules.find('.qfb-logic-rule').length === 0) {
                    module.getNoLogicRulesMessage().hide().appendTo($rules).fadeIn();
                }
            });

            return $rule;
        },

        /**
         * Build the logic rule value HTML for the given rule
         *
         * @param   {string}  elementId  The element ID
         * @param   {object}  rule       The logic rule data object
         * @return  {object}             The jQuery object of the HTML for the logic value
         */
        buildLogicRuleValues: function (elementId, rule) {
            var $value,
                selectedElement = module.getLogicSourceElementById(parseInt(elementId, 10));

            if (rule.operator === 'empty' || rule.operator === 'not_empty') {
                $value = $('<input class="qfb-logic-rule-value" type="hidden">');
            } else if ((selectedElement.type === 'select' || selectedElement.type === 'radio' || selectedElement.type === 'checkbox' || selectedElement.type === 'multiselect') && (rule.operator === 'eq' || rule.operator === 'neq')) {
                var i = 0;

                $value = $('<select class="qfb-logic-rule-value">');

                for ( ; i < selectedElement.options.length; i++) {

                    if ((selectedElement.type === 'select' || selectedElement.type === 'multiselect') && typeof selectedElement.options[i].options !== 'undefined') {
                        var $optgroup = $('<optgroup>', { label: module.shorten(selectedElement.options[i].label) }),
                            j = 0;

                        for ( ; j < selectedElement.options[i].options.length; j++) {
                            $optgroup.append($('<option>', { text: module.getShortenedOptionLabel(selectedElement.options[i].options[j]), value: selectedElement.options[i].options[j].id }));
                        }

                        $value.append($optgroup);
                    } else {
                        $value.append($('<option>', { text: module.getShortenedOptionLabel(selectedElement.options[i]), value: selectedElement.options[i].id }));
                    }
                }

                core.setSelectVal($value, rule.optionId);
            } else {
                $value = $('<input class="qfb-logic-rule-value" type="text">').attr('placeholder', editL10n.enterValue).val(rule.value);
            }

            return $value;
        },

        /**
         * Get a logic source element by ID
         *
         * @param   {number}       id
         * @return  {object|null}
         */
        getLogicSourceElementById: function (id) {
            for (var i = 0; i < module.currentLogicSources.length; i++) {
                if (module.currentLogicSources[i].id === id) {
                    return module.currentLogicSources[i];
                }
            }

            return null;
        },

        /**
         * Get the option from the given element matching the given ID
         *
         * @param   {object}         element
         * @param   {number}         optionId
         * @return  {(object|null)}
         */
        getLogicSourceElementOptionById: function (element, optionId) {
            var i = 0;

            for ( ; i < element.options.length ; i++) {
                if (typeof element.options[i].options === 'undefined') {
                    if (element.options[i].id === optionId) {
                        return element.options[i];
                    }
                } else {
                    for (var j = 0; j < element.options[i].options.length; j++) {
                        if (element.options[i].options[j].id === optionId) {
                            return element.options[i].options[j];
                        }
                    }
                }
            }

            return null;
        },

        /**
         * Get the shortened label for an option
         *
         * If the label is empty it will get the value
         *
         * @param   {Object}  option
         * @return  {string}
         */
        getShortenedOptionLabel: function (option) {
            if (option.label.length > 0) {
                return module.shorten(option.label);
            }

            return module.shorten(option.value);
        },

        /**
         * Get the logic rules
         *
         * @param {jQuery} $logic The wrapper for the div containing the logic
         */
        getLogicRules: function ($logic) {
            var rules = [];

            $logic.find('.qfb-logic-rule').each(function () {
                rules.push(module.getLogicRuleObjectFromValues($(this)));
            });

            return rules;
        },

        /**
         * Build and return a logic rule data object from the values in the given wrapper
         *
         * @param   {jQuery}  $rule  The jQuery wrapper of the rule
         * @return  {Object}         The logic rule data object
         */
        getLogicRuleObjectFromValues: function ($rule) {
            var $value = $rule.find('.qfb-logic-rule-value'),
                elementId = $rule.find('.qfb-logic-rule-element').val(),
                value = $value.val(),
                optionId = null;

            // Get the actual value of the option if it's for a multi element they selected the option ID
            if ($value.is('select')) {
                var element = module.getLogicSourceElementById(parseInt(elementId, 10));

                if (element) {
                    var option = module.getLogicSourceElementOptionById(element, parseInt(value, 10));

                    if (option) {
                        optionId = value;
                        value = option.value;
                    }
                }
            }

            return {
                elementId: elementId,
                operator: $rule.find('.qfb-logic-rule-operator').val(),
                optionId: optionId,
                value: value
            };
        },

        /**
         * Get a new empty logic rule
         *
         * @return {Object}
         */
        getNewLogicRule: function () {
            return { elementId: '', operator: 'eq', value: '', optionId: null };
        },

        /**
         * Get the message that is displayed when there are no logic rules
         *
         * @return {jQuery}
         */
        getNoLogicRulesMessage: function () {
            return module.buildMessageBox(editL10n.noLogicRules, 'info', 'qfb-no-logic-rules');
        },

        /**
         * Reset the logic settings to the default values
         */
        resetLogic: function () {
            c.get('#qfb-mc-logic').find('.qfb-logic-action').val('1');
            c.get('#qfb-mc-logic').find('.qfb-logic-match').val('all');
            c.get('#qfb-mc-logic').find('.qfb-logic-rules').empty();
        },

        /**
         * Refresh the logic rules based on the current integration settings
         */
        resyncLogic: function () {
            module.update();

            module.syncLogic(
                module.getValue(module.integration, 'formId'),
                module.getValue(module.integration, 'logicAction'),
                module.getValue(module.integration, 'logicMatch'),
                module.getValue(module.integration, 'logicRules')
            );
        },

        /**
         * Get the jQuery wrapped HTML for an info message
         *
         * @param   {string}  content       The content of the message
         * @param   {string}  [type=info]   The type of message: error info success warning
         * @param   {string}  [extraClass]  An extra class to add to the message
         * @return  {Object}
         */
        buildMessageBox: function (content, type, extraClass) {
            type = type || 'info';

            var $message = $('<div class="qfb-message-box qfb-message-box-' + type + '">').append($('<div class="qfb-message-box-inner">').text(content));

            if (extraClass) {
                $message.addClass(extraClass);
            }

            return $message;
        },

        /**
         * Get the value of the integration config with the given key
         *
         * If the value is not set it will return the default
         *
         * @param   {Object}  integration
         * @param   {string}  key
         * @return  {*}
         */
        getValue: function (integration, key) {
            return core.getProperty(integration, key, core.getProperty(editL10n.defaultIntegrationConfig, key));
        }

    };

    quform.mailchimp = quform.mailchimp || {};
    quform.mailchimp.edit = module;

    return quform;

}(quform, jQuery, quformMailchimpIntegrationsEditL10n, quformCoreL10n));
