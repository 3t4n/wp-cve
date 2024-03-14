/**
 * Forward port jQuery.live()
 * Wrapper for newer jQuery.on()
 * Uses optimized selector context
 * Only add if live() not already existing.
*/
if (typeof jQuery.fn.live == 'undefined' || !(jQuery.isFunction(jQuery.fn.live))) {
    jQuery.fn.extend({
        live: function (event, callback) {
            if (this.selector) {
                jQuery(document).on(event, this.selector, callback);
            }
        }
    });
}

var $j = jQuery.noConflict();

jQuery(document).ready(function ($) {
    if (!localStorage.getItem('jem-export-showed-pricing')) {
      localStorage.setItem('jem-export-showed-pricing', '1');
      $j('#jem-export-modal-pro').modal('show');

      pro_feature = 'first-open';

      $('#jem-export-modal-pro .button-buy').each(function(ind, el) {
        tmp = $(el).data('href-org');
        tmp = tmp.replace('pricing-table', pro_feature);
        $(el).attr('href', tmp);
      });
    }

    $('#wpwrap').on('click', '.open-jem-pro-dialog', function(e) {
      e.preventDefault();

      $j('#jem-export-modal-pro').modal('show');

      pro_feature = $(this).data('pro-feature');
      if (!pro_feature) {
        pro_feature = 'unknown';
      }

      $('#jem-export-modal-pro .button-buy').each(function(ind, el) {
        tmp = $(el).data('href-org');
        tmp = tmp.replace('pricing-table', pro_feature);
        $(el).attr('href', tmp);
      });

      return false;
    });

    //turn on datepickers!
    $j('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

    $j('.jem-select2').select2({ containerCssClass: 'jem-select2' });
    //$j('select.jem-form-value').select2({ containerCssClass: 'jem-select2', tags: true });
    $j('.jem-select2-multiline').select2({ containerCssClass: 'jem-select2-multiline' });

    //Turn on more complicated select2's
    enable_ajax_select2();

    //This handles our generic expand/collapse
    $j('.jem-accordion').click(function () {
        accordionToggle($j(this));
    });

    //This handles our generic toggle function
    $j('.jem-toggle-trigger').click(function () {
        jem_toggle($j(this));
    });

    //This handles our tab toggle function
    $j('.jem-tab-toggle-trigger').click(function () {
        jem_tab_toggle($j(this));
    });

    //Another toggle function
    $j('.jem-toggle-disable').click(function () {
        jem_toggle_disable($j(this));
    });


    //The EXPORT button is pressed!
    $j('.jem-export-button').click(function (e) {
        export_button_pressed(e);
    });

    //Main Home Preview Botto Button Pressed
    $j('#main_preview_bottom_button').click(function (e) {
        main_preview_button_pressed(e);
    });

    //The SAVE SETTINGS button is pressed
    $j('#save-settings').click(function (e) {
        save_settings_button_pressed(e);
    });
    $j('#main-page-bottom-save-settings').click(function (e) {
        save_settings_button_pressed(e);
    });

    //Handles delete order filter item
    $j(document).on('click', '.jem-remove-order-filter-item', function (e) {
        remove_order_filter_item(e, this);
        var lp = $j('div#order-filter-holder .row.jem-order-field-filter-item').length;
        if (lp == 0) {
            $j('div#order-filter-holder .row').addClass('hid');
        }
    });

    //handles delete export field item
    $j(document).on('click', '.jem-delete-export-field', function (e) {
        delete_export_field(e, this);
    });

    //Handles add FBA item
    $j(document).on('click', '#jem-add-fba-item', function (e) {
        add_fba_item(e, this);
        $j('div#order-filter-holder .hid').removeClass('hid');
    });


    //ORDER EXPORT HANDLERS
    enable_order_export_handlers();

    $j('.export-available-field').draggable({
        connectToSortable: '#export-fields-selected',
        revert: 'invalid',
        helper: 'clone',
        zIndex: 100
    });

    $j('#export-fields-selected').sortable({
        placeholder: "ui-sortable-placeholder",
        update: function (event, ui) {
            add_field_to_fields_selected(event, ui)
        }
    });

    $j('#export-list').disableSelection();

    //bind the handler and select the first radio button for the field groups
    $j("input:radio[name=groupRadio]").click(function (e) {
        field_list_group_clicked(e, this);
    });
    $j("input:radio[name=groupRadio]:first").click();


    //Scheduled jobs add job button pressed
    //The EXPORT button is pressed!
    $j('#jem-add-schedule-button').click(function (e) {
        return false;
    });

    /* Date Range Selection Section - Start */

    // Select radio
    if ($j("input[name='export-date-ranges-orders']:checked").val() == 'predefined-range') {
        $j("input:radio[name=export-date-ranges-orders]:last-child").click();
    } else {
        $j("input:radio[name=export-date-ranges-orders]:first").click();
    }

    // bind the handler
    $j("input:radio[name=export-date-ranges-orders]").click(function (e) {
        jemx_export_date_ranges_orders_group_clicked(e, this);
    });
    $j("input:radio[name=predefinedGroupRadio]").click(function (e) {
        jemx_predefined_date_range_group_clicked(e, this);
    });

    /* Date Range Selection Section - End */

});  //END DOCUMENT READY


//Main preview Button Pressed
function main_preview_button_pressed(e) {
    e.preventDefault();
    var params = {};
    params = extract_export_params();

    params.order_settings.preview = true;

    var data = [];
    data.push({
        //@simon v3.0 - ajax action depends on order type!
        //Now it is just ORDERS
        name: 'action', value: 'JEMEXP_get_data_chunk'
    });

    data.push({
        name: '_ajax_nonce', value: jemexport_settings.settings_nonce
    });

    data.push({
        name: 'step', value: 1
    });

    data.push({
        name: 'export-data', value: JSON.stringify(params)
    });
    // Remove Previous Preview Data in table
    $j("#previewTable").find('tr,td,th').remove();

    //Disable buttons When Preview is running
    $j("#schedule_save").attr("disabled", true);
    $j("#schedule_save_and_exit").attr("disabled", true);
    $j("#schedule_cancel").attr("disabled", true);
    $j("#save-settings").attr("disabled", true);

    //Show Modal When Preview is Running
    $j('#jem-export-modal-preview').modal('show');

    data = $j.param(data);
    $j.post(
        ajaxurl,
        data
    )
        .success(function (data) {

            // Hide Runnning Preview Modal
            $j('#jem-export-modal-preview').modal('hide');

            if (data.valid == 'invalid') {

                $j('#jem-export-modal').modal('hide');

                $j('#jem-modal-message').html("There are some syntax error or duplicate function error in your custom code. Please check and try again.");
                $j('#jem-export-modal-message').modal('show');

                //set the cursor back to default
                $j(document.body).css({ 'cursor': 'default' });

                return;
            }

            //Enable Button When Preview is completed
            $j('.row.jem-filter-header.jem-accordion.v_middle_centr.hid').removeClass('hid');
            //We only want to triger a click if the accordian is CLOSED
            if ($j('#preview-accordian').is(':hidden')) {
                $j('#pbx').trigger('click');
            }

            $j("#schedule_save").attr("disabled", false);
            $j("#schedule_save_and_exit").attr("disabled", false);
            $j("#schedule_cancel").attr("disabled", false);
            $j("#save-settings").attr("disabled", false);

            if (!data.result) {
                var bxhtml = err_box(data.message);
                $j('#msg_box').html(bxhtml);
                $j("html, body").animate({ scrollTop: 0 }, "slow");
                return;
            }
            //Did we get zero records
            if (data.total == 0) {
                //@Navdeep - need to handle no records returned - show a message to the user in a Modal Dialog
                // look in main.js at function jemxp_got_all_data and how it is done there

                //$j('#jem-export-modal-preview-record-null').modal({backdrop: 'static', keyboard: false});
                $j('#jem-modal-message').text('No Records Returned');
                $j('#jem-export-modal-message').modal('show');
                return;
            }

            //OK we need to display these records at the bottom of the page
            //@Navdeep - iterate thru the records returned and display them
            var tableHeaders = data.headers;
            var tableData = data.rows;
            var tableHeadersArray = tableHeaders.split(data.delimiter);
            var tableDataArray = tableData.split("\n");
            // console.table(tableData);
            $j("#previewTable").show();
            tableHeadersArray.forEach(function (item, index) {
                $j('#previewTable tbody:last-child').append('<th>' + item + '</th>');
            });

            var html;
            tableDataArray.forEach(function (item, index) {
                var itemTableDataArray = item.split(data.delimiter);


                //Simon - need to account for strings inside the results e.g. "The answer, stuff", more stuff, 99, etc
                //var itemTableDataArray = item.split(data.delimiter);

                //Create regex
                var regex = '(".*?"|[^",]+)(?=\s*,|\s*$)';
                //replace with the users delimiter (normally comma)
                var regex = regex.replace(',', data.delimiter);
                var r = new RegExp(regex, 'g'); //make it global with the g

                var itemTableDataArray = item.match(r);

                //if it's null skip it
                if (itemTableDataArray == null) {
                    return;
                }

                itemTableDataArray.forEach(function (itemDetail, itemIndex) {
                    if (itemIndex == 0) {
                        html += "<tr>";
                    }
                    itemDetail = urldecode(itemDetail);
                    html += "<td>" + itemDetail + "</td>";
                    if (itemTableDataArray.length == (itemIndex + 1)) {
                        html += "</tr>";
                    }
                });
            });
            $j('#previewTable').append("<tr>" + html + "</tr>");
        })
        .error(function (data) {
            //@Navdeep - need to show an error modal

            $j('#jem-export-modal').modal('hide');

            $j('#jem-modal-message').html("There are some syntax error or duplicate function error in your custom code. Please check and try again.");
            $j('#jem-export-modal-message').modal('show');

            //set the cursor back to default
            $j(document.body).css({ 'cursor': 'default' });

            return;

            // $j('#jem-export-modal-message').modal({backdrop: 'static', keyboard: false});
            // return;
        });
}
function urldecode(url) {
    return decodeURIComponent(url.replace(/\+/g, ' '));
}
function open_common_modal(title, contents, showCloseButton) {
    showCloseButton = showCloseButton || false;

    if (showCloseButton) {
        $j('#jem-common-modal .modal-header button').css('display', '');
    } else {
        $j('#jem-common-modal .modal-header button').css('display', 'none');
    }

    //modal = $j('#jem-common-modal');
    $j('#jem-common-modal .modal-header h4').text(title);
    $j('#jem-common-modal .modal-body').html(contents);
    $j('#jem-common-modal').modal('show', { backdrop: 'static', keyboard: false });

}

function open_success_modal(title, contents, colorClass, icon) {

    $j('#jem-success-modal-header').removeClass();
    $j('#jem-success-modal-header').addClass('modal-header ' + colorClass);

    $j('#jem-success-modal-icon').removeClass();
    $j('#jem-success-modal-icon').addClass('fa ' + icon);

    $j('#jem-success-modal .modal-body h4').text(title);
    $j('#jem-success-modal .modal-body p').html(contents);
    $j('#jem-success-modal').modal('show', { backdrop: 'static', keyboard: false });

}


function close_common_modal() {
    $j('#jem-common-modal').modal('hide');
}


//This adds a new row to the fields to export
//It is used by metadata and als for any fields that hve been saved when we first
//   load the page
function add_field_to_fields_to_export(group, id, datatype, label, format) {
    var item = "<li class='ui-draggable ui-draggable-handle export-selected-field' data-key='" + id + "'>";
    item += "<input type='hidden' class='jem-group' value='" + group + "'>";
    item += "<input type='hidden' class='jem-id' value='" + id + "'>";
    item += "<input type='hidden' class='jem-datatype' value='" + datatype + "'>";
    item += "<input type='hidden' class='jem-format' value='" + format + "'>";

    item += '<div class="selected-name">';
    item += '<span class="fa fa-menu-hamburger" aria-hidden="true"></span><i class="fa fa-bars" aria-hidden="true"></i>';
    item += id;
    item += '<i class="tooltip_icon fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="' + id + '"></i></div>';
    item += '<div class="selected-placeholder">';
    item += "<input type=text class='placeholder-input' value='" + label + "'>";
    item += "</div>";

    item += '<div class="selected-delete">';
    item += '<span class="fa fa-trash jem-delete-export-field" aria-hidden="true"></span>';
    item += '</div>';

    item += "</li>";

    $j('#export-fields-selected').append(item);
    $j('#export-fields-selected').sortable('refresh');
}

//When the radio buttons of available fields is clicked
function field_list_group_clicked(e, item) {

    //which one is select
    var val = $j(item).attr('value');

    //hide em all
    $j(".available-field-group").hide();
    //And show this one
    $j("#" + val).show();

    //now hide the meta data
    $j('.available-meta-data').hide();
    //And show the correct one
    $j('.' + val).show();
}


function add_field_to_fields_selected(event, ui) {
    //If we're just sorting and not adding a new item we don't need to do anything
    if (!ui.item.hasClass('export-available-field')) {
        return;
    }

    //OK let's change this item to how we want it to look in the selected fields section
    ui.item.find('.jem-hide').removeClass('jem-hide');

    ui.item.removeAttr('style');

    ui.item.removeClass('export-available-field');
    ui.item.removeClass('li-state-default');
    ui.item.addClass('export-selected-field');
    //Add our sections now
    //ui.item.wrapInner('<div class="selected-name"></div>');
    //ui.item.append('<div class="selected-placeholder">test</div>');

    //jQuery(ui.item).replaceWith('<li>It works</li>');
}

//SAVE SETINGS BUTTON PRESSED
function save_settings_button_pressed(e) {

    //Stop the default stuff
    e.preventDefault();

    open_common_modal('Saving Settings', "<div class='loader'></div>");

    var self = this;

    //prevent double click
    $j(this).prop({ disabled: true });
    var data = $j('#postform').serializeArray();

    var exportParams = extract_export_params();

    //add on a couple of bits of info
    data.push({
        name: 'action', value: 'JEMEXP_save_settings'
    });

    data.push({
        name: '_ajax_nonce', value: jemexport_settings.settings_nonce
    });

    data.push({
        name: 'settings', value: JSON.stringify(exportParams)
    });

    //make it a nice url friendly one
    data = $j.param(data);

    $j.post(
        ajaxurl,
        data
    )
        .success(function (data) {
            close_common_modal();

            if (!data.result) {
                var bxhtml = err_box(data.message);
            }
            else {
                var bxhtml = success_box(data.message);
            }
            $j('#msg_box').html(bxhtml);
            $j("html, body").animate({ scrollTop: 0 }, "slow");
        })
        .error(function (data) {
            close_common_modal();

        });


}
function err_box(msg) {
    return '<div class="alert alert-danger alert-dismissible fade show">  <button type="button" class="close" data-bs-dismiss="alert">&times;</button>' + msg + '</div>';
}

function success_box(msg) {
    return '<div class="alert alert-success alert-dismissible fade show">  <button type="button" class="close" data-bs-dismiss="alert">&times;</button>' + msg + '</div>';
}

//Handles the export button
function export_button_pressed(e) {
    //first lets gather all the data we need


    //ok let's do the export!
    //TODO add check for are any fields selected
    var self = this;

    $j(this).prop({ disabled: true });

    e.preventDefault();

    //set the cursor to wait
    $j(document.body).css({ 'cursor': 'wait' });

    //pop the modal
    //$j('#jem-export-modal').modal({backdrop: 'static', keyboard: false});
    //open_common_modal('Downloading', "<div class='loader'></div>");
    $j('#jem-export-modal').modal('show', { backdrop: 'static', keyboard: false });

    //if we have a leftover progress notice remove it!
    var progressBar = $j('#jemxp-progress-meter');
    var progressLabel = $j('#jemxp-progress-label');
    var progressWrapper = $j('#jemxp-progress-wrapper');

    $j(progressBar).width(0);
    $j(progressLabel).text('');
    $j(progressWrapper).text('');


    //Let's start building the param's for the export
    var exportParams = extract_export_params();

    //No fields selected - error and HALT
    if ($j.isEmptyObject(exportParams.order_settings.fields_to_export)) {
        //TODO need and error here




        $j('#jem-modal-message').text("You have not selected any fields to be exported");
        $j('#jem-export-modal-message').modal('show');
        setTimeout(function () {
            $j('#jem-export-modal').modal('hide');
            //close_common_modal();
        }, 500)


        //set the cursor back to default
        $j(document.body).css({ 'cursor': 'default' });
        return;
    }


    //OK let's go ahead and get the export started
    jemxp_get_data_chunk(1, self, exportParams);
}

//********************************************
// This gets the main data for an export
// It is used by the actual export and also for
// saving settings.
// Type specific (order, product etc) is also
// gathered
// Returns a JSON object with all the data
//*******************************************
function extract_export_params() {


    var ret = {};

    //FIELDS WE ARE EXPORTING
    var fields = [];
    $j('#export-fields-selected li').each(function (i, obj) {
        var item = {};
        item.id = $j(obj).find('.jem-id').attr('value');
        item.group = $j(obj).find('.jem-group').attr('value');
        item.format = $j(obj).find('.jem-format').attr('value');
        item.datatype = $j(obj).find('.jem-datatype').val();
        item.label = $j(obj).find('.placeholder-input').val();
        var t = $j(obj).find('.selected-name').contents().filter(function () {
            return this.nodeType === 3;
        });

        item.name = t.text();
        fields.push(item);
    });


    //EXPORT TYPE
    //What kind of export is it?
    ret.exportType = $j('#export_type').val();


    //We are just doing order exports now! At some point we should refactor this
    ret.order_settings = extract_order_params();
    ret.order_settings.fields_to_export = fields;

    //Report format & output
    ret.order_settings.report_format = {};
    ret.order_settings.report_format.sort_by = $j('#sort-by option:selected').val();
    ret.order_settings.report_format.order_by = $j('#sort-order option:selected').val();
    ret.order_settings.report_format.date_format = $j('#date-format option:selected').val();
    ret.order_settings.report_format.time_format = $j('#time-format option:selected').val();
    ret.order_settings.report_format.filename = $j('#filename').val().trim();
    ret.order_settings.report_format.encoding = $j('#encoding').val();
    ret.order_settings.report_format.delimiter = $j('#delimiter').val().trim();
    ret.order_settings.report_format.line_break = $j('#linebreak').val();
    ret.order_settings.report_format.product_grouping = $j('#product-grouping').val();


    return ret;
}


//**********************************************************
//This gets all the parameters specific to an ORDER export
//**********************************************************
function extract_order_params() {

    var params = {};
    //Now get the order filter data
    params.order_filters_fba = extract_order_filter_by_anything_data();

    //order status
    params.orderStatus = $j('#order-status').val();

    //From & to date
    //params.dateFrom = $j('#date-from').datepicker("getDate");
    //params.dateTo = $j('#date-to').datepicker("getDate");
    params.date_from = $j('#date-from').val();
    params.date_to = $j('#date-to').val();
    params.selected_range = $j("input[name='export-date-ranges-orders']:checked").val();
    params.predefined_date = $j("input[name='predefinedGroupRadio']:checked").val();
    params.custom_code_hooks = $j('#jemx-custom-code-hooks').val();
    params.hook_code_valid = $j('#jemx-custom-code-flag').val();
    params.product_grouping = $j('#product-grouping').val();

    //Product filters
    var products = [];
    $j('#products').find(':selected').each(function () {
        var item = {};

        item.id = $j(this).val();
        item.label = $j(this).text();
        products.push(item);
    });

    params.product_filter = products;

    //Category filters - we have an ID and a label
    var cat = [];
    $j('#product_categories').find(':selected').each(function () {
        var item = {};

        item.id = $j(this).val();
        item.label = $j(this).text();
        cat.push(item);
    });

    params.category_filter = cat;


    //Coupon Filters
    var coupons = [];
    $j('#selected-coupons').find(':selected').each(function () {
        var item = {};

        item.id = $j(this).val();
        item.label = $j(this).text();
        coupons.push(item);
    });

    params.coupon_filter = coupons;

    //Any coupons checkbox
    params.any_coupons = $j('#any-coupon').is(':checked');

    //Export new orders only fields
    params.export_new_orders = $j('#export-new-orders').is(':checked');
    params.starting_from_num = $j('#starting-order-number').val();
    return params;

}


//Handles when the delete an order filter item is pressed
function remove_order_filter_item(e, btn) {
    e.preventDefault();
    //get the row
    //var row = $j(btn).closest('.form-horizontal');
    var row = $j(btn).closest('.row');
    row.remove();

}

//Handles adding a new FBA item filter
function add_fba_item(e, btn) {
    e.preventDefault();


    //get the data from the row
    var row = $j(btn).closest('.row');

    var fieldLabel = $j(row).find('#order-filter-by-anything option:selected').text();
    var name = $j(row).find('#order-filter-by-anything option:selected').val();
    var conditionLabel = $j(row).find('#fba-condition option:selected').text();
    var conditionKey = $j(row).find('#fba-condition option:selected').val();
    var valueLabel = $j(row).find('#fba-value-text option:selected').text();
    var valueKey = $j(row).find('#fba-vlaue-text option:selected').val();

    //Now get the type & data type
    var type = $j("#order-filter-by-anything option:selected").attr('data-type');
    var dataType = $j("#order-filter-by-anything option:selected").attr('data-data-type');

    //we do NOT add if it's he first item
    if (name == 'initial') {
        return;
    }

    //OK so let's build our row

    //Get a copy of the template
    var t = $j('#fba-template-item').clone();

    //remove the ID
    $j(t).find('#fba-template-item').attr('id', '');

    //set the fields
    $j(t).find('.field-name b').text(fieldLabel);
    $j(t).find('.condition').text(conditionLabel);
    $j(t).find('.condition').attr('value', conditionKey);
    $j(t).find('.value').text(valueLabel);

    //And the hidden fields
    //TODO finish these
    $j(t).find('.jem-form-type').attr('value', type);
    $j(t).find('.jem-form-data-type').attr('value', dataType);
    $j(t).find('.jem-form-name').attr('value', name);

    //now addend the template
    $j('#order-filter-holder').append($j(t).html());


    //And  reset the dropdown to the first item
    var item = $j(row).find('#order-filter-by-anything')
    item.val('initial').trigger('change');

    //For condition & value delete all the options
    $j('#fba-condition').empty();
    var opt = new Option('Conditions...', 'initial', true, true);
    $j('#fba-condition').append(opt).trigger('change');
    $j('#fba-condition').attr('disabled', true);

    $j('#fba-value-text').empty();
    opt = new Option('Values...', 'initial', true, true);
    $j('#fba-value-text').append(opt).trigger('change');
    $j('#fba-value-tex').attr('disabled', true);

    //And disable the ADD button
    $j('#jem-add-fba-item').attr('disabled', true);
}


//Handles delete of an export field
function delete_export_field(e, icon) {
    e.preventDefault();

    //get the row
    var row = $j(icon).closest('li');
    row.remove();

}


//Enables the AJAX vesions of select2
function enable_ajax_select2() {
    jQuery("#products").select2({
        ajax: {
            url: ajaxurl,
            datatype: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                    action: "get_order_data",
                    type: "product",
                    _ajax_nonce: jemexport_settings.settings_nonce
                };
            },
            processResults: function (data, page) {
                // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1
    });

    jQuery("#product_categories").select2({
        ajax: {
            url: ajaxurl,
            datatype: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                    action: "get_order_data",
                    type: "product_categories",
                    _ajax_nonce: jemexport_settings.settings_nonce
                };
            },
            processResults: function (data, page) {
                // parse the results into the format expected by Select2.
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1
    });

    jQuery("#selected-coupons").select2({
        ajax: {
            url: ajaxurl,
            datatype: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                    action: "get_order_data",
                    type: "all_coupons",
                    _ajax_nonce: jemexport_settings.settings_nonce
                };
            },
            processResults: function (data, page) {
                // parse the results into the format expected by Select2.
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1
    });

    jQuery("#order-filter-by-anything").select2({
        containerCssClass: 'jem-select2'
    });

}

//Gets all the order filters and returns them
function extract_order_filter_by_anything_data() {

    //find the data
    var dat = $j('#order-filter-holder');

    var ret = [];

    //iterate over it
    dat.find('.jem-order-field-filter-item').each(function () {
        var name = $j(this).find('.jem-form-name').val();
        var type = $j(this).find('.jem-form-type').val();
        var datatype = $j(this).find('.jem-form-data-type').val();
        var selectLabel = $j(this).find('.condition').text();
        var select = $j(this).find('.condition').attr('value');


        var value = $j(this).find('.value').text();

        //label
        //var label = $j(this).find('label').text();
        var label = $j(this).find('.field-name b').text();

        //If we didn't get a value the skip
        if (typeof (value) == 'undefined') {
            return; //goto next iteration
        }
        //add it to the array
        ret.push({
            'name': name,
            'label': label,
            'type': type,
            'datatype': datatype,
            'select': select,
            'selectlabel': selectLabel,
            'value': value
        });
    });

    return ret;
}


//Sets up the hooks for everything related to order by anything filters
function enable_order_export_handlers() {
    //filter by anything
    $j('#order-filter-by-anything').change(function () {
        order_filter_by_anything_changed($j(this));
    });

    //if we get a change on the select
    //$j('.jem-form-select').live('change', function(){
    //	order_filter_selection_changed( $j(this) );
    //});

}

//Gets called when the filter by anything select changes it's value
//basically
// 1. add the right values to the condition
// 2. Enable al the fields
// 3. Kick off the AJAX request

function order_filter_by_anything_changed(item) {

    //if it's the first item we don't do anything!

    if (item.val() == "initial") {
        return;
    }

    //get the label
    var label = item.find(":selected").text();

    //Now get the type
    var type = item.find(":selected").attr('data-type');
    //default to text
    if (type == "") {
        type = "text";
    }

    //change the options on the select
    var condOpts = {};
    var el = $j("#fba-condition");
    $j(el).empty();

    //Append the right condition options
    if (type == "text") {
        condOpts = {
            "is equal to": "=",
            "is NOT equal to": "!=",
            "contains": "LIKE"

        };
    } else {
        condOpts = {
            "is equal to": "=",
            "is NOT equal to": "!=",
            "is less than": "less",
            "is greater than": "greater"
        };
    }

    $j.each(condOpts, function (key, value) {
        $j(el).append($j("<option></option>")
            .attr("value", value).text(key));
    });

    //enable the condition
    $j(el).removeAttr('disabled').removeClass('disabled');

    //Kick off the ajax call
    var data = {
        'action': 'get_order_data',
        'type': 'order',
        'field': item.val(),
        _ajax_nonce: jemexport_settings.settings_nonce
    };

    //handle to the value selection
    var sel = $j('#fba-value-text');

    //Disable it
    jQuery(sel).attr('disabled', true).addClass('disabled');
    jQuery('#jem-add-fba-item').attr('disabled', true);

    jQuery.get(ajaxurl, data, function (response) {

        //empty the values
        $j(sel).empty();

        //And add the empty one
        jQuery(sel).append(new Option("empty", "empty"));


        jQuery.each(response, function (index, val) {
            jQuery(sel).append(new Option(val, val));
        });

        jQuery(sel).removeAttr('disabled');
        jQuery('#jem-add-fba-item').removeAttr('disabled').removeClass('disabled');

        //And turn on select2
        jQuery(sel).select2({ containerCssClass: 'jem-select2-fba', tags: true });


    });


    return;

    //Now get the data type
    var datatype = item.find(":selected").attr('data-data-type');

    //All good so add it in!
    add_new_filter(type, datatype, label, item.val(), null, null);

    //and reset the dropdown to first item
    //item.find(":selected").prop('selected', false);
    item.val('initial').trigger('change');
}


//Handles when the selection type (=, <, contains etc) chnages
//If it's text then we need to change the input type!
function order_filter_selection_changed(item) {

    //we only need to change if we are a 'text' item

    //get the main DIV
    var filter = item.closest('.jem-order-field-filter-item');

    //Now the type
    var type = filter.find('.jem-form-type').attr('value');

    if (type != "text") {
        return;
    }

    //Text input for LIKE everything else is the select
    //Also add/remove the right class
    if (item.val() == "LIKE") {
        filter.find('.jem-select-query-input').find('.jem-form-value-holder').removeClass('jem-form-value');
        filter.find('.jem-select-query-input').hide();

        filter.find('.jem-text-query-input').find('.jem-form-value-holder').addClass('jem-form-value');
        filter.find('.jem-text-query-input').show();

    } else {
        filter.find('.jem-select-query-input').find('.jem-form-value-holder').addClass('jem-form-value');
        filter.find('.jem-select-query-input').show();

        filter.find('.jem-text-query-input').find('.jem-form-value-holder').removeClass('jem-form-value');
        filter.find('.jem-text-query-input').hide();

    }

}


//Adds a new filter row
function add_new_filter(type, datatype, desc, key, selected, value) {

    //get the template
    var template_name;
    switch (type) {
        case "text":
            template_name = $j("#filter-template-text");
            break;

        case "number":
            template_name = $j("#filter-template-number");
            break;

        case "date":
            template_name = $j("#filter-template-date");
            break;

    }


    //make a copy so we don't end up submitting the template with the form!
    var template = $j(template_name).clone();


    //set the atributues on the fields to post to back end
    $j(template).find('label.field-name').text(desc);

    $j(template).find('.jem-form-type').attr('value', type);

    $j(template).find('.jem-form-data-type').attr('value', datatype);

    $j(template).find('.jem-form-name').attr('value', key);


    //if we have a selected item
    var t = '.jem-form-select option[value="' + selected + '"]';
    $j(template).find(t).attr('selected', 'selected');

    //And the value
    $j(template).find('input.jem-form-value').attr('value', value);

    $j('#order-filter-holder').append($j(template).html());

    //lets get the last one we appended
    //var sel = $j('#order-filter-holder').find('.jem-form-value').last();
    var sel = $j('#fba-value-text');

    //we only load values for text types so if not we're done!
    if (type != 'text') {
        return;
    }
    //kick off the ajax to get the data for this filter by anything
    var data = {
        'action': 'get_order_data',
        'type': 'order',
        'field': key,
        _ajax_nonce: jemexport_settings.settings_nonce
    };
    jQuery.get(ajaxurl, data, function (response) {



        //add these to the dropdown
        //var s = $j(template).find('.jem-form-value');
        jQuery.each(response, function (index, val) {
            jQuery(sel).append(new Option(val, val));
        });

        jQuery(sel).removeAttr('disabled');

        //And turn on select2
        jQuery(sel).select2({ containerCssClass: 'jem-select2-oba', tags: true });
    });


}


function jemxp_get_data_chunk(step, self, exportParams) {
    if (step === undefined) {
        step = 1;
    }

    //serialize to array
    //var data = $j('#postform').serializeArray();
    var data = [];

    //add on a couple of bits of info
    data.push({
        //@simon v3.0 - ajax action depends on order type!
        //Now it is just ORDERS
        name: 'action', value: 'JEMEXP_get_data_chunk',
    });
    data.push({
        name: 'step', value: step
    });
    data.push({
        name: 'export-data', value: JSON.stringify(exportParams)
    });

    data.push({
        name: '_ajax_nonce', value: jemexport_settings.settings_nonce
    });

    //make it a nice url friendly one
    data = $j.param(data);

    //Simon 2.0.4 - changing get to post
    $j.post(
        ajaxurl,
        data
    )
        .success(function (data) {
            if (data.valid == 'invalid') {
                $j('#jem-export-modal').modal('hide');

                $j('#jem-modal-message').html("There are some syntax error or duplicate function error in your custom code. Please check and try again.");
                $j('#jem-export-modal-message').modal('show');

                //set the cursor back to default
                $j(document.body).css({ 'cursor': 'default' });

                return;
            }

            //Did we get zero records
            if (data.total == 0) {
                jemxp_got_all_data(data.message);

                $j(self).parent().append("<div class='jemxp-progress-notice'><div class='jemxp-progress-wrapper'><div class='jemxp-error-label'>NO RECORDS RETURNED<BR></div></div>");
                return;
            }


            //update the progress!
            var progressBar = $j('#jemxp-progress-bar');
            var progressMeter = $j('#jemxp-progress-meter');
            var progressLabel = $j('#jemxp-progress-label');

            $j(progressLabel).text(data.progress + '%');
            var w = data.progress * $j(progressBar).width() / 100

            $j(progressMeter).width(w);


            if (data.complete == false) {
                step = step + 1;
                jemxp_get_data_chunk(step, self, exportParams);
            } else {
                jemxp_got_all_data(data);
                window.location = data.url;
            }

        })
        .error(function (data) {

            $j('#jem-export-modal').modal('hide');

            $j('#jem-modal-message').html("There are some syntax error or duplicate function error in your custom code. Please check and try again.");
            $j('#jem-export-modal-message').modal('show');

            //set the cursor back to default
            $j(document.body).css({ 'cursor': 'default' });

            return;

            // jemxp_got_ajax_error(self, data);
            // return;

        });

}

function jemxp_got_all_data(data) {
    //$j('#jemxp-progress-header').text('Export Complete');

    $j('#jem-export-modal').modal('hide');
    //close_common_modal();

    //Did we get a message
    if (data.result != true) {
        $j('#jem-modal-message').text(data.message);
        $j('#jem-export-modal-message').modal('show');
    }

    //do we need to update the max order number on the screen?
    if ($j('#export-new-orders').is(':checked')) {
        $j('#starting-order-number').val(data.max_order_num);
    }

    //set the cursor back to default
    $j(document.body).css({ 'cursor': 'default' });

}

function jemxp_got_ajax_error(self, data) {
    jemxp_got_all_data();
    $j(self).parent().append("<div class='jemxp-progress-notice'><div class='jemxp-progress-wrapper'><div class='jemxp-error-label'>System Error - Please Try Again<BR>If the problem persists, please contact support<BR></div></div>");
}


//****************************************
// 3.0 new stuff
//****************************************
function accordionToggle(header) {
    //basically we just toggle the next row

    //first find our parent row
    var row = header.closest('.row');


    //find the content area
    var sibling = row.next('.jem-accordion-content');

    //and toggle it!
    sibling.toggle(200);

    //change the icon
    row.find(".jem-accordion-icon").toggle();

}

//*******************************************************************
// Generic toggle function
// Looks for an attribute on the clicked item called data-target
// It HIDES all items within the closest jem-toggle-container class
// with the class of jem-toggle
// And then SHOWS the item with the ID from data-target
//********************************************************************
function jem_toggle(item) {
    //First lets get the outer container
    var container = $j(item).closest('.jem-toggle-container');

    //Now hide all the target elements INSIDE the container
    $j(container).find('.jem-toggle-target').hide();

    //now get the data-target from the item
    var target = $j(item).data('target');

    $j(target).show();

}

//*******************************************************************
// Generic toggle function -for a radio button
// Looks for an attribute on the clicked item called data-target
// It DISABLES the item with value held in data-target
//********************************************************************
function jem_toggle_disable(item) {
    //Get if we are checked or unchecked
    var checked = $j(item).is(':checked');

    //now get the data-target from the item
    var target = $j(item).data('target');

    //If it's checked hide else show
    if (checked) {
        $j(target).prop('disabled', false);
    } else {
        $j(target).prop('disabled', true);
    }


}

//*******************************************************************
// Generic tab toggle function - you cna show hide tabs
// Looks for an attribute on the clicked item called data-target
// I will then toggle show/hide
// with the class of jem-toggle
// And then SHOWS the item with the ID from data-target
//********************************************************************
function jem_tab_toggle(item) {
    //First lets get the outer container
    var container = $j(item).closest('.jem-toggle-container');

    //Get if we are checked or unchecked
    var checked = $j(item).is(':checked');

    //now get the data-target from the item
    var target = $j(item).data('target');
    var tabb = $j(item).data('tabb');

    //Ok are we showing or hiding?
    if (checked) {
        //make sure the tabs are showing!
        //we use the name of the checkbox and hide the associated item with the ID
        var hide = $j(item).attr('name');
        $j('#' + hide).show();
        $j(target).show();

        //And make it active
        $j('#schedule-panel .nav-tabs .nav-link').removeClass('active');
        $j('#schedule-panel ' + target).addClass('active');

        $j('#schedule-panel .tab-pane').removeClass('active');
        $j('#schedule-panel .tab-pane').removeClass('show');
        $j('#schedule-panel .tab-pane[aria-labelledby=' + tabb + ']').addClass('active show');
    } else {
        //HIDING
        $j(target).hide();

        //if there are no tabs left then we need to hide the tab panel
        //count the number of unchecked checkboxes
        var p = $j(item).parent().parent();
        var count = $j(p).find(':checked').length;


        if (count == 0) {
            //hide the whole darn thing
            //we use the name of the checkbox and hid the associated item with thta ID
            var hide = $j(item).attr('name');
            $j('#' + hide).hide();
        } else {
            //Show the first tab - so we need the FIRST checked
            var checked = $j(p).find(':checked:first');

            //now get the tab ID
            target = $j(checked).data('tabb');
            target_lnk = $j(checked).data('target');
            $j('#schedule-panel .tab-pane[aria-labelledby=' + target + ']').addClass('active show');
            $j('#schedule-panel .nav-tabs .nav-link').removeClass('active');
            $j(target_lnk).addClass('active');
            /*  $j(target).tab('show'); */

        }

    }

}
/*ToolTip Trigger*/
jQuery(function () {
    jQuery('[data-toggle="tooltip"]').tooltip();
});
jQuery(document).ready(function () {
    jQuery("body").tooltip({ selector: '[data-toggle="tooltip"]' });
});


// When the radio buttons of available predefined range fields is clicked
function jemx_predefined_date_range_group_clicked(e, item) {

    // which one is selected
    var data_datefrom = $j(item).attr('data-datefrom');
    var data_dateto = $j(item).attr('data-dateto');

    $j('#date-from').val(data_datefrom);
    $j('#date-to').val(data_dateto);
}

// When the radio buttons of export order option fields is clicked
function jemx_export_date_ranges_orders_group_clicked(e, item) {

    // which one is selected
    var export_option = $j(item).val();

    if (export_option == 'select-range') {
        /*$j('#date-from').val('');
        $j('#date-to').val('');*/
    } else if (export_option == 'predefined-range') {
        var checked_value = $j("input[name='predefinedGroupRadio']:checked").attr('id');
        var data_datefrom = $j('#' + checked_value).attr('data-datefrom');
        var data_dateto = $j('#' + checked_value).attr('data-dateto');
        $j('#date-from').val(data_datefrom);
        $j('#date-to').val(data_dateto);
    }
}
