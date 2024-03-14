'use strict';

(function ($) {

    // Maybe row key has updated, get that value
    function dlsmb_sus_get_last_css_id(){
        var allIDs = [];
        var last_id = '';
        $('.dlsmb-field-key-repeater-dlssus_tasks tbody TR').each(function () {
            var itemID = $(this).attr('id').split('-');
            allIDs.push(itemID[itemID.length - 1]);
        });
        var IDs = allIDs.filter(function(el) {
            return el.length && el==+el;
        });
        last_id = Math.max.apply(Math, IDs);
        return last_id;
    }

    // Get most recent row id
    var row_key = dlsmb_sus_get_last_css_id();

    function fdsusShowHideTaskDates() {
        if ($('#dlsmb-field-checkbox--0-dlssus_use_task_dates').prop('checked')) {
            $('.dlsmb-field-key-datepicker-dlssus_date').hide();
            $('.dlsmb-field-key-repeater-dlssus_tasks .dlsmb-repeater-cell-dlssus_tasks-date').show();
            $('#sheet_date').val('');
        } else {
            $('.tasks .dls-sus-task-date INPUT').val('');
            $('.dlsmb-field-key-datepicker-dlssus_date').show();
            $('.dlsmb-field-key-repeater-dlssus_tasks .dlsmb-repeater-cell-dlssus_tasks-date').hide();
        }
    }

    $(document).ready(function () {

        // Task dates
        fdsusShowHideTaskDates();
        $('#dlsmb-field-checkbox--0-dlssus_use_task_dates').change(function () {
            fdsusShowHideTaskDates();
        });
        $('.dlsmb-field-type-repeater').on('dlsmb:add-after', '.dlsmb-js-add', function (event, element) {
            fdsusShowHideTaskDates();
        });

        // Sortable tasks
        $('.tasks').sortable({
            distance: 5,
            opacity: 0.6,
            cursor: 'move'
        });

        // Expand/Collapse all postboxes
        $('.dls-sus-expand-all-postbox').click(function () {
            if ($('.dls-sus-settings .postbox').hasClass('closed')) {
                $('.dls-sus-settings .postbox').removeClass('closed');
                dls_sus_toggle_postboxes();
            } else {
                $('.dls-sus-settings .postbox').addClass('closed');
                dls_sus_toggle_postboxes();
            }
            return false;
        });

        $('.dls-sus-settings .hndle').click(function () {
            dls_sus_toggle_postboxes();
        });

        function dls_sus_toggle_postboxes() {
            if ($('.dls-sus-settings .postbox').hasClass('closed')) {
                $('.dls-sus-expand-all-postbox').text('+ Expand All');
            } else {
                $('.dls-sus-expand-all-postbox').text('- Collapse All');
            }
        }

        // Admin Metabox
        $(".dls_sus.metabox-holder .chosen-select").chosen({width: "100%"});

        // Migrate status
        if ($('.dlssus-migrate-status').length != 0) {
            dlssusCheckMigrateStatus(); // This will run on page load
            var dlssusMigrateCheck = setInterval(function () {
                dlssusCheckMigrateStatus(); // this will run after every X seconds
            }, 5 * 1000);
        }

        function dlssusCheckMigrateStatus() {
            let data = {
                action: 'dlssus_migrate_status'
            };
            $.post(ajaxurl, data, function (response) {
                $('.dlssus-migrate-status').html(response.output);
                if (response.status == 'complete') {
                    clearInterval(dlssusMigrateCheck);
                }
            });
        }

        // Copy Task
        $('body').on('click', '.dlsmb-js-copy', function (e) {
            e.preventDefault();
            row_key = dlsmb_sus_get_last_css_id();
            let new_row_template = $(this).parent('TD').parent('TR').parent('TBODY').children('.dlsmb-blank-repeater')[0].outerHTML;
            // Update row_keys
            row_key++;
            let new_element = $(this).closest('TR').after(new_row_template).next('TR');
            new_element.removeClass('dlsmb-blank-repeater');
            new_element.attr('id', new_element.attr('class').split(' ')[0] + '-' + row_key); // Row ID

            new_element.find('.dlsmb-field .dlsmb-field-element').each(function () {
                dlssus_update_element_id_and_label($(this));
            });

            new_element.find('.dlsmb-field .dlsmb-input-image').each(function () {
                dlssus_update_element_id_and_label($(this));
            });

            $(this).closest('TR').find('.dlsmb-js-copy').trigger('dlsmb:copy-after');

            copy_task_row($(this).closest("TR"), new_element, true);
            fdsusShowHideTaskDates();

            return false;
        });

        function dlssus_update_element_id_and_label(element) {
            var new_element_name = element.attr('name');
            var new_element_name_prefix = new_element_name.substr(0, new_element_name.indexOf('[') + 1);
            var new_element_name_suffix = new_element_name.substr(new_element_name.indexOf(']'));
            element.attr('name', new_element_name_prefix + row_key + new_element_name_suffix);
            // Element ID & label
            var new_element_id = element.attr('id');
            if (new_element_id) {
                if (!(element.is('input') && (element.attr('type') === 'checkbox' || element.attr('type') === 'radio'))) {
                    // Non-checkbox and radio elements
                    var new_element_id_split = new_element_id.split('-');
                    var new_element_id_hyphenated_key = '-' + new_element_id_split[new_element_id_split.length - 2] + '-';
                    var new_element_id_prefix = new_element_id.substr(0, new_element_id.indexOf(new_element_id_hyphenated_key));
                    var new_element_id_suffix = new_element_id.substr(new_element_id.indexOf(new_element_id_hyphenated_key) + new_element_id_hyphenated_key.length);
                    element.attr('id', new_element_id_prefix + '-' + row_key + '-' + new_element_id_suffix);
                    element.closest('.dlsmb-field').children('.dlsmb-main-label').attr('for', new_element_id_prefix + '-' + row_key + '-' + new_element_id_suffix);
                } else {
                    // Checkboxes and Radio sub-elements
                    let oldSubElementId = element.attr('id');
                    let newSubElementId = element.attr('id').toLowerCase().replace('dlssus_tasks-x', 'dlssus_tasks-' + row_key);
                    element.attr('id', newSubElementId);
                    element.parent().find('label[for=' + oldSubElementId + ']').each(function () {
                        $(this).attr('for', newSubElementId);
                    });
                }
            }
        }

        function copy_task_row(original_tr, new_tr, copy_data) {
            copy_data = typeof copy_data !== 'undefined' ? copy_data : false;

            let original_element_name;
            let original_row_id;
            original_tr.find('.dlsmb-field :input').each(function () {
                original_element_name = $(this).attr('name');
                let idIndexStart = original_element_name.indexOf('[') + 1;
                let idIndexEnd = original_element_name.indexOf(']') - 1;
                let idLength = idIndexEnd - idIndexStart + 1;
                original_row_id = original_element_name.substr(idIndexStart, idLength);
            });

            new_tr.attr('id', new_tr.attr('class') + '-' + row_key);

            new_tr.find('.dlsmb-field :input').each(function () {
                // Element name
                let new_element = this;
                var new_element_name = $(this).attr('name');
                var new_element_name_prefix = new_element_name.substr(0, new_element_name.indexOf('[') + 1);
                var new_element_name_suffix = new_element_name.substr(new_element_name.indexOf(']'));
                var current_row = new_element_name.substr(new_element_name.indexOf('[') + 1, 1);

                var new_name = new_element_name_prefix + row_key + new_element_name_suffix;
                var original_name = new_element_name_prefix + original_row_id + new_element_name_suffix;
                $(this).attr('name', new_name);

                if (copy_data) {
                    if ($(this).attr('type') == 'text') {
                        $(this).val($(original_tr).find("input[name*='" + original_name + "']").val());
                    } else if ($(this).attr('type') == 'radio') {
                        $(this).val() == $(original_tr).find("input[name*='" + original_name + "']:checked").val() ? this.checked = true : '';
                    } else if ($(this).attr('type') == 'checkbox') {
                        $(original_tr).find("input[name*='" + original_name + "']").each(function () {
                            if (this.checked) {
                                $(new_element).parent().find("input[value='" + $(this).val() + "']").attr('checked', true);
                            }
                        });
                    } else if ($(this).is('select')) {
                        $(original_tr).find("select[name*='" + original_name + "']").find("option").each(function () {
                            if ($(this).attr('selected')) {
                                $(new_element).find("option[value='" + $(this).val() + "']").attr('selected', true);
                            }
                        });
                    } else if ($(this).is('textarea')) {
                        $(this).val($(original_tr).find("textarea[name*='" + original_name + "']").val());
                    }
                }
            });
        }

        $('body').on('click', '.dlsmb-js-add-header', function() {

            // Update row_keys
            row_key = dlsmb_sus_get_last_css_id();
            row_key++;

            //todo:  This needs to be put into a template just like the task row.
            // If you change this, you also need to change the similar code block in admin.php, line ~ 1017.
            var header_row_template = '<tr class="dlsmb-repeater-dlssus_tasks-row dls-sus-task-header-row" id="dlsmb-repeater-dlssus_tasks-row-' + row_key + '"><td class="dlsmb-sort"></td><td colspan="99">' +
                '<input name="dlssus_tasks[' + row_key + '][title]" value="" type="text">' +
                '<input name="dlssus_tasks[' + row_key + '][task_row_type]" value="header" type="hidden">' +
                '<input name="dlssus_tasks[' + row_key + '][id]" value="0" type="hidden">' +
                '<a href="#" class="dlsmb-icon dlsmb-js-remove" title="Delete Row"><i class="dashicons dashicons-trash"></i></a>' +
                '</td></tr>';

            $(this).parent().parent().after(header_row_template);

            fdsusShowHideTaskDates();
            return false;
        });

    });

    // Fix focus issue on mobile devices
    $('.postbox-container table tbody input, .postbox-container table tbody textarea').not(':hidden').on('click', function () {
        const elem = $(this);
        elem.focus();
        const str = elem.val();
        elem.val('');
        elem.val(str);
    });

    $('#select-all-clear').click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

})(jQuery);

class ToggleTip {
    constructor(domNode) {
        this.rootEl = domNode;
        this.buttonEl = this.rootEl.querySelector('button[aria-expanded], a[aria-expanded]');

        const controlsId = this.buttonEl.getAttribute('aria-controls');
        this.contentEl = document.getElementById(controlsId);

        this.open = this.buttonEl.getAttribute('aria-expanded') === 'true';

        this.buttonEl.addEventListener('click', this.onButtonClick.bind(this));

        const self = this;

        document.addEventListener('keydown', (event) => {
            const isNotCombinedKey = !(event.ctrlKey || event.altKey || event.shiftKey);
            if (event.key === 'Escape' && isNotCombinedKey) {
                self.close();
            }
        });
    }

    onButtonClick(event) {
        this.toggle(!this.open);
        event.preventDefault();
    }

    toggle(open) {
        // don't do anything if the open state doesn't change
        if (open === this.open) {
            return;
        }

        // update the internal state
        this.open = open;

        // handle DOM updates
        this.buttonEl.setAttribute('aria-expanded', `${open}`);
        if (open) {
            this.contentEl.removeAttribute('hidden');
        } else {
            this.contentEl.setAttribute('hidden', '');
        }
    }

    // Add public open and close methods for convenience
    open() {
        this.toggle(true);
    }

    close() {
        this.toggle(false);
    }
}

// init ToggleTips
const toggleTips = document.querySelectorAll('.fdsus-toggletip');
toggleTips.forEach((toggleTipEl) => {
    new ToggleTip(toggleTipEl);
});
