jQuery(document).ready(function ($) {
    "use strict";
    
    // Tabs
    function fami_wccp_show_active_tab_content() {
        $('.fami-wccp-tabs').each(function () {
            var $thisTabs = $(this);
            var tab_id = $thisTabs.find('.nav-tab.nav-tab-active').attr('data-tab_id');
            $thisTabs.find('.tab-content').removeClass('tab-content-active');
            $thisTabs.find('.tab-content#' + tab_id).addClass('tab-content-active');
        });
    }
    
    fami_wccp_show_active_tab_content();
    
    $(document).on('click', '.fami-wccp-tabs .nav-tab', function (e) {
        var $this = $(this);
        var $thisTabs = $this.closest('.fami-wccp-tabs');
        if ($this.is('.nav-tab-active')) {
            return false;
        }
        $thisTabs.find('.nav-tab').removeClass('nav-tab-active');
        $this.addClass('nav-tab-active');
        fami_wccp_show_active_tab_content();
        e.preventDefault();
    });
    
    // Compare fields checkbox
    function fami_wccp_update_all_compare_fields() {
        var fields_val = '';
        var all_fields_order = '';
        $('.fami-wccp-fields-attrs-list .compare-field-cb').each(function () {
            if ($(this).is(':checked')) {
                if (fields_val == '') {
                    fields_val = $(this).val();
                }
                else {
                    fields_val += ',' + $(this).val();
                }
            }
            if (all_fields_order == '') {
                all_fields_order = $(this).val();
            }
            else {
                all_fields_order += ',' + $(this).val();
            }
        });
        $('input[name="compare_fields_attrs"]').val(fields_val);
        $('input[name="all_compare_fields_attrs_order"]').val(all_fields_order);
    }
    
    // Save all settings
    $(document).on('click', '.fami-wccp-save-all-settings', function (e) {
        var $this = $(this);
        
        if ($this.is('.processing')) {
            return false;
        }
        
        var $allSettingsForm = $('.fami-all-settings-form');
        var all_settings = {};
        
        fami_wccp_update_all_compare_fields();
        
        $allSettingsForm.find('.fami-wccp-field').each(function () {
            var this_name = $(this).attr('name');
            if (typeof this_name != 'undefined' && typeof this_name != false) {
                if ($(this).is(':checkbox')) {
                    if ($(this).is(':checked')) {
                        all_settings[this_name] = 'yes';
                    }
                    else {
                        all_settings[this_name] = 'no';
                    }
                }
                else {
                    all_settings[this_name] = $(this).val();
                }
            }
        });
        
        $this.addClass('processing disabled');
        $allSettingsForm.find('.fami-wccp-message').remove();
        
        var data = {
            action: 'fami_wccp_save_all_settings_via_ajax',
            all_settings: all_settings,
            nonce: fami_wccp['security']
        };
        
        $.post(ajaxurl, data, function (response) {
            
            fami_wccp_display_multi_messages($allSettingsForm, response, 'bottom');
            $this.removeClass('processing disabled');
        });
        
        e.preventDefault();
        return false;
    });
    
    // Import settings
    $(document).on('submit', 'form[name="fami_wccp_import_settings_form"]', function (e) {
        var $thisForm = $(this);
        
        if ($thisForm.is('.processing')) {
            return false;
        }
        
        var c = confirm(fami_wccp['text']['confirm_import_settings']);
        if (!c) {
            return false;
        }
        
        var form_data = new FormData(this);
        
        $thisForm.addClass('processing');
        $thisForm.find('button[type="submit"]').prop('disabled', true);
        
        $.ajax({
            url: fami_wccp['import_settings_url'],
            type: 'POST',
            data: form_data,
            processData: false,
            contentType: false,
            success: function (response) {
                $thisForm.removeClass('processing');
                $thisForm.find('button[type="submit"]').prop('disabled', false);
                fami_wccp_display_multi_messages($thisForm, response, 'bottom');
                location.reload();
            }
        });
        
        e.preventDefault();
        return false;
    });
    
    // Sortable
    if ($('.fami-wccp-sortable').length) {
        $('.fami-wccp-sortable').each(function () {
            $(this).sortable();
            $(this).disableSelection();
        });
    }
    
    /**
     *
     * @param $form
     * @param response
     * @param position  top or bottom.
     */
    function fami_wccp_display_multi_messages($form, response, position) {
        $form.find('.fami-wccp-message').remove();
        
        var msg_class = '';
        
        if (response['err'] === 'yes') {
            msg_class += 'alert-danger notice notice-error';
        }
        else {
            msg_class += 'alert-success updated notice notice-success';
        }
        
        if ($.type(response['message']) === 'string') {
            if (response['message'] !== '') {
                if (position === 'top') {
                    $form.prepend('<div class="fami-wccp-message alert ' + msg_class + '"><p>' + response['message'] + '</p></div>');
                }
                else {
                    $form.append('<div class="fami-wccp-message alert ' + msg_class + '"><p>' + response['message'] + '</p></div>');
                }
            }
        }
        else {
            $.each(response['message'], function (index, item) {
                if (position === 'top') {
                    $form.prepend('<div class="fami-wccp-message alert ' + msg_class + '"><p>' + item + '</p></div>');
                }
                else {
                    $form.append('<div class="fami-wccp-message alert ' + msg_class + '"><p>' + item + '</p></div>');
                }
            });
        }
    }
    
});