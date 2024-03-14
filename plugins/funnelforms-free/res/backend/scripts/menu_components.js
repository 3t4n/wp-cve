jQuery( document ).ready(function($) {

    if(af2_menu_components_object.dark_mode == 1) {
         jQuery('#wpbody-content').addClass('af2_background_color_dark');
         jQuery('#wpbody').addClass('af2_background_color_dark');
    }

    // Darkmode
    jQuery('#af2_toggle_dark_mode').on('click', function() {
        let checked = false;
        if (jQuery(this).is(':checked')) checked = true;

        jQuery('.af2_wrapper').toggleClass('af2_darkmode');

        if(checked) jQuery('#wpbody-content').addClass('af2_background_color_dark');
        else jQuery('#wpbody-content').removeClass('af2_background_color_dark');
        if(checked) jQuery('#wpbody').addClass('af2_background_color_dark');
        else jQuery('#wpbody').removeClass('af2_background_color_dark');

        jQuery.ajax({
                url: af2_menu_components_object.ajax_url,
                type: "POST",
                data: {
                    action: 'fnsf_af2_trigger_dark_mode',
                    nonce: af2_menu_components_object.nonce,
                    state: checked
                },
                success: () => { },
                error: () => { }
        });
    });

    // Search Filter
    jQuery('#af2_search_filter').on('input', function() {
        const filterText = jQuery(this).val().toLowerCase();
        const searchFilterColumn = jQuery(this).data('searchfiltercolumn');

        if(!searchFilterColumn.includes(';')) {
            const allFilterableTexts = jQuery('.af2_post_table_content[data-searchfilter="'+searchFilterColumn+'"]');

            jQuery(allFilterableTexts).each((i, el) => {
                const editElement = jQuery(el).parent();
                const actualText = jQuery(el).data('searchvalue') != null ? jQuery(el).data('searchvalue').toLowerCase() : null;

                if(actualText == null) return;
    
                if(actualText.includes(filterText)) jQuery(editElement).removeClass('af2_hide');
                else jQuery(editElement).addClass('af2_hide');
            });
        }
        else {
            const rows = searchFilterColumn.split(';');
            const allFilterableRows = jQuery('.af2_post_table_body .af2_post_table_row');

            jQuery(allFilterableRows).each((i, el) => {
                const editElement = jQuery(el);
                
                let allFilterableTexts = null;
                rows.forEach(ele => {
                    if(allFilterableTexts == null) allFilterableTexts = jQuery('#'+jQuery(el).attr('id')+'.af2_post_table_row .af2_post_table_content[data-searchfilter="'+ele+'"]');
                    else $.merge(allFilterableTexts, jQuery('#'+jQuery(el).attr('id')+'.af2_post_table_row .af2_post_table_content[data-searchfilter="'+ele+'"]'));
                });

                let show = false;
                jQuery(allFilterableTexts).each((i, ele) => {
                    const actualText = jQuery(ele).data('searchvalue') != null ? jQuery(ele).data('searchvalue').toString().toLowerCase() : null;

                    if(actualText == null) return;
                    if(actualText.includes(filterText)) show = true;
                });

                if(show) jQuery(editElement).removeClass('af2_hide');
                else jQuery(editElement).addClass('af2_hide');                
            });
        }
        
    });

    jQuery('.af2_choose_all_table_objects').on('click', function() {
        const val = jQuery(this).is(':checked') ? true : false;
        jQuery('.af2_choose_table_object').prop('checked', val);

        if(jQuery('.af2_choose_table_object:checked').length > 0) {
            jQuery('#af2_copy_posts').removeClass('af2_hide');
            jQuery('#af2_delete_posts').removeClass('af2_hide');
        }
        else {
            jQuery('#af2_copy_posts').addClass('af2_hide');
            jQuery('#af2_delete_posts').addClass('af2_hide');
        }
    });

    jQuery('.af2_choose_table_object').on('click', function() {
        if(jQuery('.af2_choose_table_object:checked').length > 0) {
            jQuery('#af2_copy_posts').removeClass('af2_hide');
            jQuery('#af2_delete_posts').removeClass('af2_hide');
        }
        else {
            jQuery('#af2_copy_posts').addClass('af2_hide');
            jQuery('#af2_delete_posts').addClass('af2_hide');
        }

        if(jQuery('.af2_choose_table_object').not('.af2_choose_table_object:checked').length > 0) jQuery('.af2_choose_all_table_objects').prop('checked', false);
        if(jQuery('.af2_choose_table_object:checked').length == jQuery('.af2_choose_table_object').length) jQuery('.af2_choose_all_table_objects').prop('checked', true);
    });
    
    jQuery('#af2_copy_posts').on('click', function() {
        let ids = [];
        jQuery('.af2_choose_table_object:checked').each((i, el) => {
            ids.push(jQuery(el).attr('id'));
        });
        jQuery.ajax({
            url: af2_menu_components_object.ajax_url,
            type: 'POST',
            data: {
                action: 'af2_fnsf_copy_posts',
                nonce: af2_menu_components_object.nonce,
                'post_ids': ids
            },
            success: () => { window.location.reload(); },
            error: () => {}
        });
    });

    jQuery('#af2_delete_posts').on('click', function() {
        let ids = [];
        jQuery('.af2_choose_table_object:checked').each((i, el) => {
            ids.push(jQuery(el).attr('id'));
        });
        jQuery.ajax({
            url: af2_menu_components_object.ajax_url,
            type: 'POST',
            data: {
                action: 'af2_fnsf_delete_posts',
                nonce: af2_menu_components_object.nonce,
                'post_ids': ids,
            },
            success: () => {  window.location.reload();
                 },
            error: () => {}
        });
    });

    jQuery('.af2_menu_functions_select').on('change', function() {
        const val = jQuery(this).val();

        window.location.href = jQuery(this).data('link')+'&'+jQuery(this).data('getattribute')+'='+val;
    });

    jQuery(document).on('click', '.af2_copy_to_clipboard_es', function(e){
        let textId = this.dataset.id;
        let input = jQuery(this).parent().find('#'+textId);
        input.focus();
    });
    jQuery(document).on('focus', '.af2_copy_to_clipboard_element', function(e){
        af2_copy_to_clipboard(this);        
    });


    jQuery(document).on('click', '.show_hide_helpcenter', function(e) {
        jQuery('#af2_helpcenter_frame').toggleClass('af2_hide');

        if(jQuery('#af2_helpcenter_frame').hasClass('af2_hide')) {
            jQuery('.show_hide_helpcenter i').removeClass('fa-times');
            jQuery('.show_hide_helpcenter i').addClass('fa-question');
        } else {
            jQuery('.show_hide_helpcenter i').removeClass('fa-question');
            jQuery('.show_hide_helpcenter i').addClass('fa-times');
        }
    });


    function af2_copy_to_clipboard(element){
        element.select();
        element.setSelectionRange(0, 9999999);
        window.document.execCommand('copy');
        /* navigator.clipboard.writeText(element.value); */
        //jQuery('[data-id="'+ element.id +'"]').append('<span class="copy-tip">Copied!</span>');
        //setTimeout(() => { jQuery('[data-id="'+ element.id +'"]').find('.copy-tip').remove(); }, 3000);
    }
});
