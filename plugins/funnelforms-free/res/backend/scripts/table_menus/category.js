jQuery( document ).ready(function($) {
    jQuery(document).on('click', '.add_category', function() {
        let label = jQuery(this).parent().find('.af2_add_category_input').val();

        jQuery.ajax({
            url: af2_menu_components_object.ajax_url,
            type: "POST",
            data: {
                action: 'af2_fnsf_add_category',
                nonce: af2_menu_components_object.nonce,
                label: label
            },
            success: () => { 
                window.location.href = af2_category_object.reload_url;
            },
            error: () => { }
        });
    });

    jQuery(document).on('click', '.af2_category_delete', function() {
        let id = jQuery(this).parent().attr('id');
        
        jQuery.ajax({
            url: af2_menu_components_object.ajax_url,
            type: "POST",
            data: {
                action: 'af2_fnsf_delete_category',
                nonce: af2_menu_components_object.nonce,
                id: id
            },
            success: () => { 
                window.location.href = af2_category_object.reload_url;
            },
            error: () => { }
        });
    });

    jQuery(document).on('change', '.af2_category_selection', function() {
        let id = jQuery(this).val();
        let elementid = jQuery(this).attr('data-elementid');

        jQuery.ajax({
            url: af2_menu_components_object.ajax_url,
            type: "POST",
            data: {
                action: 'af2_fnsf_set_element_category',
                nonce: af2_menu_components_object.nonce,
                id: id, elementid: elementid
            },
            success: () => { 
                window.location.href = af2_category_object.reload_url_nomodal;
            },
            error: () => { }
        });
    });
});
