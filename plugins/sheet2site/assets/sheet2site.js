jQuery(document).ready(function($) {
    var form = $('#sheet2site-terms-ajax');
    $(form).submit(function(event) {
        event.preventDefault();
        var data = {};
        var formData = new FormData(this);
        for (var entry of formData) {
            data[entry[0]] = entry[1];
        }
        $.post({
            url: ajaxurl,
            data: data,
            success: function () {
                window.location = sheet2siteAdmin.pluginPage;
            }
        });
    }); 
});
