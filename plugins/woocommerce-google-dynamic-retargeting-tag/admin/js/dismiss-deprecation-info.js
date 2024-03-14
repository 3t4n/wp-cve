jQuery(function (){

    jQuery(document).on('click', '#wgdr-got-it', function (e) {
        e.preventDefault();

        let data = {
            'action': 'dismiss_wgdr_deprecation_info'
        };

        jQuery.post(ajaxurl, data, function (response) {});

        jQuery('.wgdr-deprecation-notice').remove();
    });
});