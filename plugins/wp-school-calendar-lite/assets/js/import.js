/* global WPSC_Admin */

jQuery(document).ready(function($) {
    "use strict";
    
    $('.wpsc-button-import').on('click', function(){
        var t = $(this);
        
        var current_label = $(this).val();
        var processing    = $(this).attr('data-processing');
        var current_step  = $(this).parents('form').find('input[name="wpsc_import_current_step"]').val();
        
        var data = { 
            action       : 'wpsc_import',
            nonce        : WPSC_Admin.nonce,
            current_step : current_step
        };

        t.val(processing);
        t.attr('disabled', 'disabled');
        
        $.post(WPSC_Admin.ajaxurl, data, function(res){
            if (res.success) {
                $('.wpsc_import_process_section').hide();
                $('#wpsc_' + res.data.output.next_step + '_section').show();
                t.removeAttr('disabled');
                t.val(current_label);
            } else {
                console.log(res);
                t.removeAttr('disabled');
                t.val(current_label);
            }
        }).fail(function(xhr, textStatus, e) {
            console.log(xhr.responseText);
            t.removeAttr('disabled');
            t.val(current_label);
        });
        
        return false;
    });
});