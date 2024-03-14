jQuery(document).ready(function($) {
    $(document).on('click', '.storeone-extension-notice-dissmiss', function(event) {
        event.preventDefault();
        var type = $(this).closest('.storeone-extension-notice-dissmiss').data('notice');
        $.ajax({
                url: storeone_extension.ajax_url,
                type: 'POST',
                data: {
                    action: 'storeone_extension_dismissed_notice_handler',
                    // type: type,
                }
            })
            .done(function(d) {
                console.log("success");
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

    });
});