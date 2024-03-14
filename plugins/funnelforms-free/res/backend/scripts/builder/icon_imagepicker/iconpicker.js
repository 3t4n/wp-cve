jQuery( document ).ready(function() {
    jQuery(document).on('input', '#af2_iconpicker_search', function() {
        const value = jQuery(this).val();
        const iconpicker = jQuery(this).closest('.af2_fontawesome_iconpicker');

        jQuery(iconpicker).find('.af2_iconpicker_icon').removeClass('af2_hide');
        jQuery(jQuery(iconpicker).find('.af2_iconpicker_icon').toArray().filter(element => !jQuery(element).data('iconid').toLowerCase().includes(value))).addClass('af2_hide');
    });

    jQuery(document).on('click', '.af2_iconpicker_icon', function(e) {
        const iconpicker = jQuery(this).closest('.af2_fontawesome_iconpicker');
        jQuery(iconpicker).find('.af2_iconpicker_icon').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery(iconpicker).find('#af2_iconpicker_save').attr('data-iconvalue', jQuery(this).data('iconid'));
    });

    jQuery(document).on('click', '#af2_iconpicker_save', function() { 
        const id = jQuery(this).data('objectid');
        const iconpicker = jQuery(this).closest('.af2_fontawesome_iconpicker');
        const val = jQuery(iconpicker).find('#af2_iconpicker_save').data('iconvalue');

        if(val == null || val.trim() == '') { af2_close_modal(); return; }

        let event = jQuery.Event('icon_picked');
        event.value = val;
        event.object_id = id;
        jQuery('#'+id).trigger(event);
        af2_close_modal();
    });
});