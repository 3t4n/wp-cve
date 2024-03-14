(function (api, $) {
    "use strict";

    var unique_id = function () {
        return Math.random().toString(36).substring(2) + (new Date()).getTime().toString(36);
    };

    var custom_fields = function () {
        var data_store;
        return (data_store = $('.mo-fields-save-field').val()) !== "" ? JSON.parse(data_store) : [];
    };

    var display_webhook_request_ui_on_load = function (type) {

        type = type || 'body';

        $('.mo-webhook-integration-' + type).each(function () {
            var cache,
                parent = $(this).parent('.WebHookConnect_request_' + type + '_fields'),
                _this = this,
                template = wp.template('mo-webhook-' + type + '-template'),
                data_store = request_data_store(parent, type);

            if (!_.isEmpty(data_store)) {

                _.each(data_store, function (element) {

                    cache = $(template().replace(/{mo-integration-index}/g, unique_id())).appendTo(_this);

                    if ('body' == type) {
                        $(document).trigger('mo_webhook_custom_field_update');
                    }

                    cache.find('.dropdown_key.mo-webhook-field select').val(typeof element.dropdown_key !== "undefined" ? element.dropdown_key : '');
                    cache.find('.key.mo-webhook-field input, .key.mo-webhook-field select').val(typeof element.key !== "undefined" ? element.key : '');
                    cache.find('.value.mo-webhook-field input, .value.mo-webhook-field select').val(typeof element.value !== "undefined" ? element.value : '');

                    if ('header' == type) {
                        $('.dropdown_key.mo-webhook-field select').trigger('toggle_custom_header_display');
                    }
                });
            }
        });
    };

    var request_data_store = function ($parent, type) {

        type = type || 'body';

        var old_data = $('.WebHookConnect_request_' + type + '_fields', $parent).val();
        if (typeof old_data === 'undefined' || old_data === '') {
            old_data = [];
        } else {
            old_data = JSON.parse(old_data);
        }

        return old_data;
    };

    var update_options_select = function (newOptions, $el) {

        if (_.isEmpty(newOptions)) newOptions = {}; // in case it's an empty array

        var old_select_val = $el.val();

        $el.empty(); // remove old options
        $el.append($("<option value=''>–––––––––</option>"));
        $.each(newOptions, function (key, value) {
            $el.append($("<option></option>")
                .attr("value", key).text(value));
        });

        $el.val(old_select_val)
    };

    var update_cf_select_dropdowns = function () {

        var parent = $(this).parents('.mo-integration-widget-content'),
            select_options = {
                'mo_name': moWebhookGlobals.name_label,
                'mo_fname': moWebhookGlobals.firstname_label,
                'mo_lname': moWebhookGlobals.lastname_label,
                'mo_email': moWebhookGlobals.email_label
            };

        _.each(custom_fields(), function (element) {
            select_options[element.cid] = element.placeholder == "" ? element.cid : element.placeholder;
        });

        select_options = $.extend({}, select_options, JSON.parse(moWebhookGlobals.system_fields));

        $('.mo-integration-block .mo-webhook-cf-dropdown select').each(function (elem) {
            update_options_select(select_options, $(this));
        });
    };

    var toggle_custom_header_display = function () {

        var val = this.value;

        $(this).parents('.mo-integration-widget-wrap').find('.mo-webhook-header-name-text-field').toggle('mo_custom_header' == val);
        $(this).parents('.mo-integration-widget-wrap').find('.mo-webhook-header-name-select-field').toggle('mo_custom_header' != val);
    };

    var add_request_header = function (e) {

        e.preventDefault();

        var old_data,
            parent = $(this).parents('.WebHookConnect_request_header_fields'),
            template = wp.template('mo-webhook-header-template'),
            append_wrap = $('.mo-webhook-integration-header', parent),
            index = append_wrap.children().length;

        append_wrap.append(template().replace(/{mo-integration-index}/g, unique_id()));

        $(document).trigger('mo_webhook_custom_field_update');

        old_data = request_data_store(parent, 'header');

        if (typeof old_data[index] == 'undefined') old_data[index] = {};

        old_data[index]['key'] = '';
        old_data[index]['value'] = '';

        $('.WebHookConnect_request_header_fields', parent).val(JSON.stringify(old_data)).trigger('change');
    };

    var add_request_body = function (e) {

        e.preventDefault();

        var old_data,
            parent = $(this).parents('.WebHookConnect_request_body_fields'),
            template = wp.template('mo-webhook-body-template'),
            append_wrap = $('.mo-webhook-integration-body', parent),
            index = append_wrap.children().length;

        append_wrap.append(template().replace(/{mo-integration-index}/g, unique_id()));

        $(document).trigger('mo_webhook_custom_field_update');

        old_data = request_data_store(parent);

        if (typeof old_data[index] == 'undefined') old_data[index] = {};

        old_data[index]['key'] = '';
        old_data[index]['value'] = '';

        $('.WebHookConnect_request_body_fields', parent).val(JSON.stringify(old_data)).trigger('change');
    };

    var save_webhook_data = function (_this, type) {

        type = type || 'body';

        var parent = $(_this).parents('.WebHookConnect_request_' + type + '_fields'),
            old_data = request_data_store(parent, type),
            index = $(_this).parents('.mo-webhook-' + type + '-wrap').index(),
            field_key = $(_this).attr('name');

        old_data[index][field_key] = $(_this).val();

        $('.WebHookConnect_request_' + type + '_fields', parent).val(JSON.stringify(old_data)).trigger('change');
    };

    var remove_repeater_item = function (e) {
        e.preventDefault();

        var parent, old_data,
            index = $(this).parent().index(),
            webhook_type = $(this).parent().data('webhook-data-type');

        parent = $(this).parents('.WebHookConnect_request_' + webhook_type + '_fields');
        old_data = request_data_store(parent, webhook_type);

        if (typeof old_data[index] !== 'undefined') {
            old_data.splice(index, 1);
            $('.WebHookConnect_request_' + webhook_type + '_fields', parent).val(JSON.stringify(old_data)).trigger('change');
        }

        $(this).parent('.mo-integration-widget-wrap').remove();
    };

    $(window).on('load', function () {
        $(document).on('mo_webhook_custom_field_update mo_optin_custom_field_saved', update_cf_select_dropdowns);
        $(document).on('change toggle_custom_header_display', ".mo-webhook-header-name-select-field select", toggle_custom_header_display);
        $(document).on('click', ".mo-integration-webhook__add_header_new button", add_request_header);
        $(document).on('click', ".mo-integration-webhook__add_body_new button", add_request_body);
        $(document).on('click', ".mo-integration-webhook__remove", remove_repeater_item);

        $(document).on('keyup change', ".mo-webhook-integration-body select, .mo-webhook-integration-body input", _.debounce(function () {
            save_webhook_data(this, 'body');
        }, 500));

        $(document).on('keyup change', ".mo-webhook-integration-header select, .mo-webhook-integration-header input", _.debounce(function () {
            save_webhook_data(this, 'header');
        }, 500));

        display_webhook_request_ui_on_load('body');
        display_webhook_request_ui_on_load('header');
    });

})(wp.customize, jQuery)