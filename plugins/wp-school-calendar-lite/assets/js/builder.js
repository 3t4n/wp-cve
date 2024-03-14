/* global WPSC_Admin, Infinity */

jQuery(document).ready(function($) {
    "use strict";
    
    $('#adminmenuback, #adminmenuwrap').remove();
    
    $('.wpsc-select').select2({
        minimumResultsForSearch: Infinity,
        width: "100%"
    });
    
    $('#wpsc-builder-navigation-name').on('click', function(){
        $('#wpsc-builder-general-option-items').show();
        $('#wpsc-builder-field-name').focus();
    });
    
    $('#wpsc-view-shortcode-button').on('click', function(){
        $.magnificPopup.open({
            items: {
                src  : '#wpsc-shortcode-panel',
                type : 'inline'
            },
            preloader : false,
            modal     : false
        });
            
        return false;
    });
    
    $('#wpsc-save-calendar-button').on('click', function(){
        var t = $(this);
        var form_data = $('#wpsc-builder-form').serializeArray();
        
        var data = { 
            action : 'wpsc_save_calendar',
            nonce  : WPSC_Admin.nonce
        };
        
        var str = '{';
        
        $.each(form_data, function(key, field){
            var field_value = field.value;
            str += '"' + field.name + '":"' + field_value.replace(/\"/g, '\\"') +'"';
            if (key < form_data.length-1) str += ',';
        });
        
        str += '}';
        
        data = $.extend(data, JSON.parse(str));
        
        t.attr('disabled', 'disabled');
        
        $.post(WPSC_Admin.ajaxurl, data, function(res){
            if (res.success) {
                if (res.data.replace) {
                    $('#wpsc-builder-form').attr('data-id', res.data.replace.calendar_id);
                    $('#wpsc-builder-field-calendar-id').val(res.data.replace.calendar_id);
                    $('#wpsc-builder-shortcode-field').val('[wp_school_calendar id="' + res.data.replace.calendar_id + '"]');
                    $('#wpsc-view-shortcode-button').removeAttr('disabled');
                    let stateObj = { id: "100" };
                    window.history.replaceState(stateObj, res.data.replace.title, res.data.replace.url);
                }
            } else {
                console.log(res);
            }
            
            t.removeAttr('disabled');
        }).fail(function(xhr, textStatus, e) {
            console.log(xhr.responseText);
            t.removeAttr('disabled');
        });
        
        return false;
    });
    
    var reload_preview = function() {
        if ( $('#wpsc-reload').val() === 'N') {
            $('#wpsc-reload').val('Y');
            
            var form_data = $('#wpsc-builder-form').serializeArray();

            var data = { 
                action : 'wpsc_reload_preview',
                nonce  : WPSC_Admin.nonce
            };

            var str = '{';

            $.each(form_data, function(key, field){
                var field_value = field.value;
                str += '"' + field.name + '":"' + field_value.replace(/\"/g, '\\"') +'"';
                if (key < form_data.length-1) str += ',';
            });

            str += '}';

            data = $.extend(data, JSON.parse(str));
            
            $.post(WPSC_Admin.ajaxurl, data, function(res){
                if (res.success) {
                    if (res.data.replace) {
                        $('#wpsc-builder-field-calendar-id').val(res.data.replace.calendar_id);
                        $('#wpsc-builder-shortcode-field').val('[wp_school_calendar id="' + res.data.replace.calendar_id + '"]');
                        $('#wpsc-view-shortcode-button').removeAttr('disabled');
                        let stateObj = { id: "100" };
                        window.history.replaceState(stateObj, res.data.replace.title, res.data.replace.url);
                    }

                    $('#wpsc-block-calendar').html(res.data.content);
                } else {
                    console.log(res);
                }
                
                $('#wpsc-reload').val('N');
            }).fail(function(xhr, textStatus, e) {
                console.log(xhr.responseText);
                $('#wpsc-reload').val('N');
            });

            return false;
        }
    };
    
    $('.wpsc-select, .wpsc-checkbox').on('change', function(){
        if ($(this).hasClass('wpsc-select-multiple')) {
            var target = $(this).attr('data-field-target');
            var str = '';

            $(this).find('option:selected').each(function(){
                str += $(this).val() + ',';
            });

            if (str.length > 0) str = str.substr(0, str.length-1);

            $('#' + target).val(str);
        }
        
        reload_preview();
    });
    
    $('.wpsc-text').on('keyup', function(){
        clearTimeout($.data(this, 'timer'));
        
        var wait = setTimeout(function(){
            reload_preview();
        }, 1500);
        
        $(this).data('timer', wait);
    });
    
    $('#wpsc-builder-field-name').on('keyup', function(){
        $('#wpsc-builder-navigation-name').text($(this).val());
    });
    
    $('#wpsc-builder-shortcode-field').on('click', function(){
        $(this).select();
        document.execCommand('copy');
    });
    
    var reload_custom_default_month_range = function() {
        var start_year = $('#wpsc-builder-field-start-year').val();
        var num_months = $('#wpsc-builder-field-num-months').val();
        
        $('#wpsc-builder-field-custom-default-range').html('');
        
        var options = null;
        
        $.each(WPSC_Admin.custom_default_month_range, function(key, obj_month_range){
            if (obj_month_range.num_month === num_months && obj_month_range.start_year === start_year) {
                options = obj_month_range.month_range;
                return false;
            }
        });
        
        $.each(options, function(key, obj_month_range){
            $('#wpsc-builder-field-custom-default-range').append('<option value="' + obj_month_range.key +  '">' + obj_month_range.value + '</option>');
        });
        
        $('#wpsc-builder-field-custom-default-range').trigger('change');
    };
    
    $('#wpsc-builder-field-start-year').on('change', function(){
        $('#wpsc-builder-field-custom-default-year').html('');
        
        if ($(this).val() === '01') {
            var options = WPSC_Admin.custom_default_year_single;
        } else {
            var options = WPSC_Admin.custom_default_year_dual;
        }
        
        $.each(options, function(key, obj_year){
            $('#wpsc-builder-field-custom-default-year').append('<option value="' + obj_year.key +  '">' + obj_year.value + '</option>');
        });
        
        reload_custom_default_month_range();
        
        $('#wpsc-builder-field-custom-default-year').trigger('change');
    });
    
    $('#wpsc-builder-field-num-months').on('change', function(){
        reload_custom_default_month_range();
    });
    
    $('#wpsc-builder-field-default-month-range').on('change', function(){
        if ($(this).val() === 'custom') {
            $('#wpsc-custom-default-year-option').show();
            $('#wpsc-custom-default-month-range-option').show();
        } else {
            $('#wpsc-custom-default-year-option').hide();
            $('#wpsc-custom-default-month-range-option').hide();
        }
    });
    
    $('.wpsc-builder-field-toggle').on('change', function(){
        var target = $(this).attr('data-target');
        
        if ($(this).is(':checked')) {
            $('.' + target).show();
        } else {
            $('.' + target).hide();
        }
    });
    
    $('.wpsc-builder-option-heading button').on('click', function(){
        var t = $(this);
        var target = $(this).attr('data-target');
        
        $('#' + target).toggle(300, function(){
            if (t.hasClass('wpsc-builder-option-items-open')) {
                t.removeClass('wpsc-builder-option-items-open');
            } else {
                t.addClass('wpsc-builder-option-items-open');
            }
        });
    });
});