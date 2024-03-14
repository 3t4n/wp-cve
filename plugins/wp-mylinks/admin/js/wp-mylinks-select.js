(function ($) {
    'use strict';
    function attachChangeEventToLinkURL() {
        $('.mylinks-input-select2').off('change').on('change', function () {
            const selectedID = $(this).val();
            const $inputSelectedUrl = $(this).closest('.cmb-row').next().find('.mylinks-selected-url');
            const selectedOptionText = $(this).find('option:selected').text();
            const selectedURL = selectedOptionText.split(' - ')[1];
            $inputSelectedUrl.val(selectedURL);
        });
    }
    function initSelect2(selector, options) {
        $(selector).select2(options);
    }

    function initSortable(selector) {
        $(selector).select2_sortable();
    }

    function destroySelect2(selector) {
        $(selector).select2('destroy');
    }

    function clearSelectedOptions(selector) {
        $('option:selected', selector).removeAttr('selected');
    }

    const defaultOptions = {
        allowClear: true
    };

    $.fn.extend({
        select2_sortable: function () {
            var select = $(this);
            $(select).select2();
            var ul = $(select).next('.select2-container').first('ul.select2-selection__rendered');
            ul.sortable({
                containment: 'parent',
                items: 'li:not(.select2-search--inline)',
                tolerance: 'pointer',
                stop: function () {
                    $($(ul).find('.select2-selection__choice').get().reverse()).each(function () {
                        var id = $(this).data('data').id;
                        var option = select.find('option[value="' + id + '"]')[0];
                        $(select).prepend(option);
                    });
                }
            });
        }
    });

    initSelect2('.mylinks-input-select2, .pw_select', defaultOptions);
    initSortable('.pw_multiselect');

    $('.cmb-repeatable-group').on('cmb2_add_group_row_start', function (event, instance) {
        const $oldRow = $(instance).closest('.cmb-repeatable-group').find('.cmb-repeatable-grouping').last();
        destroySelect2($oldRow.find('.mylinks-input-select2, .pw_select, .pw_multiselect'));
    });

    $('.cmb-repeatable-group').on('cmb2_add_row', function (event, newRow) {
        clearSelectedOptions($(newRow).find('.mylinks-input-select2, .pw_select, .pw_multiselect'));
        initSelect2($(newRow).find('.mylinks-input-select2, .pw_select'), defaultOptions);
        initSortable($(newRow).find('.pw_multiselect'));
        attachChangeEventToLinkURL();
    });

    $('.cmb-repeatable-group').on('cmb2_shift_rows_start', function (event, instance) {
        const $groupWrap = $(instance).closest('.cmb-repeatable-group');
        destroySelect2($groupWrap.find('.mylinks-input-select2, .pw_select, .pw_multiselect'));
    });

    $('.cmb-repeatable-group').on('cmb2_shift_rows_complete', function (event, instance) {
        const $groupWrap = $(instance).closest('.cmb-repeatable-group');
        initSelect2($groupWrap.find('.mylinks-input-select2, .pw_select'), defaultOptions);
        initSortable($groupWrap.find('.pw_multiselect'));
    });

    $('.cmb-add-row-button').on('click', function (event) {
        const $oldRow = $(document.getElementById($(event.target).data('selector'))).find('.cmb-row').last();
        destroySelect2($oldRow.find('.mylinks-input-select2, .pw_select, .pw_multiselect'));
    });

    $('.cmb-repeat-table').on('cmb2_add_row', function (event, newRow) {
        clearSelectedOptions($(newRow).prev().find('.mylinks-input-select2, .pw_select, .pw_multiselect'));
        initSelect2($(newRow).prev().find('.mylinks-input-select2, .pw_select'), defaultOptions);
        initSortable($(newRow).prev().find('.pw_multiselect'));
    });

    $('.mylinks-input-select2').on('change', function () {
        const selectedID = $(this).val();
        const $inputSelectedUrl = $(this).closest('.cmb-row').next().find('.mylinks-selected-url');
        const selectedOptionText = $(this).find('option:selected').text();
        const selectedURL = selectedOptionText.split(' - ')[1];
        $inputSelectedUrl.val(selectedURL);
    });

    attachChangeEventToLinkURL();

})(jQuery);

