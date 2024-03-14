jQuery(document).ready(function () {

    if (jQuery('.tlms-products').length === jQuery('.tlms-products:checked').length) {
        jQuery('#tlms-integrate-all').html(translations.unselect_all_message);
    }

    // toggle check/uncheck all courses for integration
    jQuery('#tlms-integrate-all').on('click', function () {
        if (jQuery(this).html() === translations.unselect_all_message) {
            jQuery('.tlms-products').prop('checked', false);
            jQuery(this).html(translations.select_all_message);
        } else {
            jQuery('.tlms-products').prop('checked', true);
            jQuery(this).html(translations.unselect_all_message);
        }
    });


    jQuery('.tlms-reset-course').click(function () {

        jQuery(this).parent().parent().parent().append('<div class="progress-message">'+translations.progress_message+'</div>');
        var data = {
            'action': 'tlms_resynch',
            'course_id': jQuery(this).data('course-id')
        }
        jQuery.post(ajaxurl, data)
               .done(function (response) {
                var parsed_response = JSON.parse(response);
                if (parsed_response.api_limitation === 'none') {
                    jQuery('.progress-message').html(translations.success_message);
                } else {
                    jQuery('.progress-message').html(parsed_response.api_limitation);
                }
                setTimeout(function () {
                    jQuery('.progress-message').remove(); }, 3000);
               })
               .fail(function (jqXHR, textStatus, error) {
                jQuery('.progress-message').html(jqXHR.responseText);
               });
    });

    jQuery(function () {
        jQuery("#tlms-integrations-table").dataTable({
            "order": [[ 0, "asc" ]] ,
            "columns": [
                                                             { "orderable": true },
                                                             { "orderable": false }
                                                         ]
                                                     });
    });

});
