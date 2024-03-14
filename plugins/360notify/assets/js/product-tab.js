jQuery(document).ready(function () {
    if (jQuery().select2)
        jQuery('.WooNotify_tab_status').select2();
    jQuery('#add_another_whatsApp_tab').on('click', function (e) {
        if (jQuery().select2)
            jQuery('.WooNotify_tab_status').select2("destroy");
        var clone_container = jQuery('#duplicate_this_row_whatsApp');
        var new_count = parseInt(jQuery('#whatsApp_tab_counter').val()) + 1;
        var remove_tab_button = jQuery('#duplicate_this_row_whatsApp .whatsApp_tab_counter');
        var move_tab_content_buttons = jQuery('#duplicate_this_row_whatsApp .button-holder-whatsApp');
        clone_container.children('p').each(function () {
            jQuery(this).clone().insertBefore('#duplicate_this_row_whatsApp').removeClass('hidden_duplicator_row_title_field_whatsApp').removeClass('hidden_duplicator_row_content_field_whatsApp').addClass('new_duplicate_row_whatsApp');
        }).promise().done(function () {
            var duplicate = jQuery('.new_duplicate_row_whatsApp');
            duplicate.find('input').each(function () {
                if (jQuery(this).is('input[name="hidden_duplicator_row_mobile"]')) {
                    jQuery(this).attr('name', 'WooNotify_tab_mobile_' + new_count).attr('id', 'WooNotify_tab_mobile_' + new_count).parents('p').addClass('WooNotify_tab_mobile_' + new_count + '_field').removeClass('hidden_duplicator_row_title_field_whatsApp').find('label').removeAttr('for').attr('for', 'WooNotify_tab_mobile_' + new_count + '_field');
                }
            });
            duplicate.find('select').each(function () {
                if (jQuery(this).is('select[name="hidden_duplicator_row_statuses[]"]')) {
                    jQuery(this).attr('name', 'WooNotify_tab_status_' + new_count + '[]').attr('id', 'WooNotify_tab_status_' + new_count).parents('p').addClass('WooNotify_tab_status_' + new_count + '_field').removeClass('hidden_duplicator_row_content_field_whatsApp').find('label').removeAttr('for').attr('for', 'WooNotify_tab_status_' + new_count + '_field');
                }
            });
            jQuery('#whatsApp_tab_counter').val(new_count);
            duplicate.first().before('<div class="WooNotify-tab-divider"></div>');
        });
        move_tab_content_buttons.clone().insertAfter(jQuery('.WooNotify-tab-divider').last()).addClass('last-button-holder-whatsApp');
        remove_tab_button.clone().prependTo('.last-button-holder-whatsApp').removeAttr('style');
        jQuery('.button-holder-whatsApp').first().prev('.WooNotify-tab-divider').hide();
        jQuery('.last-button-holder-whatsApp').removeAttr('alt').attr('alt', new_count);
        setTimeout(function () {
            jQuery('.last-button-holder-whatsApp').removeClass('last-button-holder-whatsApp');
            jQuery('.new_duplicate_row_whatsApp').removeClass('new_duplicate_row_whatsApp');
        }, 100);
        if (jQuery().select2)
            jQuery('.WooNotify_tab_status').select2();
        e.preventDefault();
    });
    // end duplicate tab

    /*
        Remove a new tab
    */
    jQuery('body').on('click', '.whatsApp_tab_counter', function (e) {
        var clicked_parent = jQuery(this).parents('.button-holder-whatsApp');
        var tab_title_to_remove = clicked_parent.next();
        var tab_content_to_remove = tab_title_to_remove.next();
        var divider_to_remove_next = tab_content_to_remove.next('.WooNotify-tab-divider');
        var divider_to_remove_prev = clicked_parent.prev('.WooNotify-tab-divider');
        tab_title_to_remove.remove();
        tab_content_to_remove.remove();
        divider_to_remove_prev.remove();
        divider_to_remove_next.remove();
        clicked_parent.remove();
        e.preventDefault();
    });
    // end remove

    jQuery('.WooNotify-tab-help-toggle').on('click', function () {
        jQuery('.WooNotify-tab-help').fadeToggle();
    }).show();
});