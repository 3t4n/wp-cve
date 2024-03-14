(function () {
    jQuery("document").ready(function() {

        //Default textarea content
        var content = '<img src="{{media_src_large}}">\n' +
            '\n' +
            '{{attachment_title}}'
        jQuery("textarea[name=apaiu_custom_textarea]").val(content);
        jQuery("input[name=apaiu_custom_title]").val("{{attachment_title}}");

        jQuery("#savePrefsButton").on("click", function(e) {

            var finalData = {
                "custom_post_title": jQuery("input[name=apaiu_custom_title]").val(),
                "custom_post_content": jQuery("textarea[name=apaiu_custom_textarea]").val(),
                "custom_post_categories": jQuery("select[name=apaiu_categories]").val(),
                "custom_post_tags": jQuery("select[name=apaiu_tags]").val(),
                "custom_post_status": jQuery("select[name=apaiu_status]").val(),
                "apaiu_set_featured": jQuery("select[name=apaiu_set_featured]").val(),
                "custom_post_format": jQuery("select[name=custom_post_format]").val(),
            }

            var data = {
                'action': 'apaiu_save_preference',
                'configs': JSON.stringify(finalData)
            };

            // We can also pass the url value separately from ajaxurl for front end AJAX implementations
            jQuery.post(ajaxurl, data, function (response) {
                if(response.data.success === true){
                    Swal.fire(
                        'Congratulations!!',
                        'Your preferences have been saved',
                        'success'
                    )
                }
            })
        })

        //Get existing preferences
        jQuery.post(ajaxurl, {"action": "apaiu_get_preference"}, function (response) {
            var prefs = response.data.prefs;

            if(prefs !== false){
                jQuery("input[name=apaiu_custom_title]").val(prefs.custom_post_title);
                jQuery("textarea[name=apaiu_custom_textarea]").val(prefs.custom_post_content);
                jQuery("select[name=apaiu_categories]").val(prefs.custom_post_categories);
                jQuery("select[name=apaiu_tags]").val(prefs.custom_post_tags);
                jQuery("select[name=apaiu_status]").val(prefs.custom_post_status);
                jQuery("select[name=custom_post_format]").val(prefs.custom_post_format);
                jQuery("select[name=apaiu_set_featured]").val(prefs.apaiu_set_featured);
            }
        })
    })
})();