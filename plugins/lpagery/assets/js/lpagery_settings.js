jQuery(function ($) {
    $('#lpagery_save_settings').on('click', function (e) {
        $.lpageryToggleRotating($(this), true)
        var settings_payload = {
            "_ajax_nonce": lpagery_ajax_object_settings.nonce,

            'action': 'lpagery_save_settings',
            'settings': {
                'spintax': $('#lpagery_spintax-enabled').is(':checked'),
                'image_processing': $('#lpagery_image-processing-enabled').is(':checked'),
                'custom_post_types': $('#lpagery_custom_post_types').val(),
                'google_sheet_sync_interval': $('#lpagery_google_sync_interval').val(),
                'next_google_sheet_sync': $('#lpagery_next_sheet_run').val(),
                'author_id': $('#lpagery_author_settings').val()
            }
        }
        $.ajax({
            "method": "post",
            "url": lpagery_ajax_object_settings.ajax_url,
            "data": settings_payload,
            "success": function () {
                setTimeout(function () {
                    $.lpageryToggleRotating($('#lpagery_save_settings'), false)
                    $.lpageryToggleEnabled($('#lpagery_save_settings'), false);
                }, 300);

            }
        })
    })

    $('.lpagery-settings-input').bind('input keyup', function () {
        $.lpageryToggleEnabled($('#lpagery_save_settings'), true)
    });


    let settings_payload = {
        'action': 'lpagery_get_settings',
        "_ajax_nonce": lpagery_ajax_object_settings.nonce
    }
    $('#lpagery_settings_container_skeleton').show();
    jQuery.ajax({
        "method": "get",
        "url": lpagery_ajax_object_settings.ajax_url,
        "data": settings_payload,
        "success": function (response) {
            $('#lpagery_settings_container_skeleton').hide();
            var parsed = JSON.parse(response);


            $('#lpagery_author_settings').val(parsed.author_id).change()
            $('#lpagery_spintax-enabled').prop('checked', JSON.parse(parsed.spintax))
            $('#lpagery_image-processing-enabled').prop('checked', JSON.parse(parsed.image_processing))
            $('#lpagery_custom_post_types').val(parsed.custom_post_types).change()
            $('#lpagery_google_sync_interval').val(parsed.google_sheet_sync_interval).change()
            $('#lpagery_next_sheet_run').val(parsed.next_google_sheet_sync)
            let now = new Date(),
                minDate = now.toISOString().substring(0, 16);
            $('#lpagery_next_sheet_run').prop('min', minDate);

        }
    })
});