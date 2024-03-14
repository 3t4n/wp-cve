var memsourceSourceLanguage;
var memsourceTargetLanguages = [];
var memsourceServices = [];
var memsourceServiceSelector;
var memsourceLanguageStatus = {};
var memsourceWorkUuid;
var memsourceWorkProcessing = false;

function arrayContains(array1, array2) {
    var intersection = jQuery.grep(array1, function(item) {
        return jQuery.inArray(item, array2) > -1;
    });
    return intersection.length === array2.length;
}

function arraysIntersect(array1, array2) {
    var intersection = jQuery.grep(array1, function(item) {
        return jQuery.inArray(item, array2) > -1;
    });
    return intersection.length > 0;
}

function hasActiveCheckboxes(name) {
    return jQuery('input[name="' + name + '"]').not(":disabled").length > 0;
}

function getSelectedCheckboxValues(name) {
    return jQuery('input[name="' + name + '"]:checked').not(":disabled").map(function() {
        return jQuery(this).val();
    }).get();
}

function selectAllCheckboxes(name, callback) {
    var checked = jQuery('#memsource-all-languages').is(':checked');
    jQuery('input[name="' + name + '"]').not(":disabled").prop('checked', checked);
    if (callback) {
        callback.call();
    }
}

function fixSelectAllStatus(name) {
    var unchecked = jQuery('input[name="' + name + '"]').not(':checked');
    jQuery('#memsource-all-languages').prop('checked', unchecked.length === 0);
}

function setMemsourceServiceSelector(selectorElementId) {
    memsourceServiceSelector = jQuery('#' + selectorElementId);
}

function setMemsourceSourceLanguage(language) {
    memsourceSourceLanguage = language;
}

function setMemsourceTargetLanguages(checkBoxName) {
    memsourceTargetLanguages = jQuery('input[name="' + checkBoxName + '"]').map(function() {
        return jQuery(this).val();
    }).get();
}

function loadMemsourceServices(statusElementId, zeroOptionName, errorText) {
    memsourceServices = [];
    var serviceElement = memsourceServiceSelector;
    var statusElement = jQuery('#' + statusElementId);
    statusElement.html('<span id="service-spinner" class="spinner is-active"></span>');
    var data = {action: 'get_automation_widget'};
    jQuery.get(ajaxurl, data, function(response) {
        if (response) {
            serviceElement.empty();
            serviceElement.append(jQuery('<option></option>').val(0).html(zeroOptionName));
            jQuery.each(response.services, function(index, service) {
                // add only compatible services
                if (jQuery.inArray(memsourceSourceLanguage, service.sourceLangs) >= 0 &&
                    arraysIntersect(memsourceTargetLanguages, service.targetLangs)) {
                    memsourceServices.push(service);
                    serviceElement.append(jQuery('<option></option>').val(service.id).html(service.name));
                }
            });
            statusElement.html('');
            if (memsourceServices.length === 1) {
                // select this service automatically
                var service = memsourceServices[0];
                serviceElement.find('option[value=' + service.id + ']').prop('selected', true);
                handleServiceChange(service);
            }
        } else {
            statusElement.html('<div class="dashicons dashicons-warning red-icon" title="' + errorText + '"></div>');
        }
    }).fail(function(error) {
        statusElement.html('<div class="dashicons dashicons-warning red-icon" title="' + errorText + '"></div>');
    });
}

function shouldLoadQuote(language) {
    return !memsourceLanguageStatus[language] ||
        (memsourceLanguageStatus[language] != 'InProgress' && memsourceLanguageStatus[language] != 'Completed');
}

function handleServiceChange(service) {
    jQuery.each(memsourceTargetLanguages, function(index, language) {
        if (shouldLoadQuote(language)) {
            var disabled = jQuery.grep(service.targetLangs, function(item) {
                    return item == language;
                }).length === 0;
            var checkbox = jQuery('#memsource-language-' + language);
            checkbox.prop('disabled', disabled);
            if (disabled) {
                checkbox.prop('checked', false);
            }
        }
    });
    var selectAllCheckbox = jQuery('#all-languages-row');
    var disabled = hasActiveCheckboxes('memsource-language');
    selectAllCheckbox.prop('disabled', disabled);
    if (disabled) {
        selectAllCheckbox.prop('checked', false);
    }
    // check dueDateSchemeId and enable/disable due date field
    jQuery('#due-date').prop('disabled', service.dueDateSchemeId > 0);
    jQuery('#due-time').prop('disabled', service.dueDateSchemeId > 0);
}

function enableForm() {
    jQuery('#translate-button').prop('disabled', false);
}

function disableForm() {
    jQuery('#translate-button').prop('disabled', true);
}

function getMemsourceServiceId() {
    var id = memsourceServiceSelector.val();
    return id ? parseInt(id) : 0;
}

function getSelectedService() {
    var serviceId = getMemsourceServiceId();
    var result = jQuery.grep(memsourceServices, function(item) {
        return item.id === serviceId;
    });
    return result.length === 0 ? undefined : result[0];
}

function initClipboard(successText) {
    var clipboard = new Clipboard('.btn');
    clipboard.on('success', function(e) {
        var triggerId = jQuery(e.trigger).prop('id');
        jQuery('#' + triggerId + '-result').html(successText);
    });
    clipboard.on('error', function(e) {
        console.log(e);
    });
}

function toggleSection(type, showText, hideText) {
    var toggle = jQuery('#memsource-admin-toggle-' + type);
    var link = jQuery('#memsource-admin-link-' + type);
    var arrow = jQuery('#memsource-admin-arrow-' + type);
    var section = jQuery('#memsource-admin-section-' + type);
    if (section.is(':visible')) {
        if (showText) {
            link.text(showText);
            arrow.removeClass('dashicons-arrow-up');
            arrow.addClass('dashicons-arrow-down');
        }
        section.hide('slow');
    } else {
        if (hideText) {
            link.text(hideText);
            arrow.removeClass('dashicons-arrow-down');
            arrow.addClass('dashicons-arrow-up');
        }
        section.show('slow');
    }
}
