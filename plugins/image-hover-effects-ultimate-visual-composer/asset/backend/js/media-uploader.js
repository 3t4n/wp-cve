
jQuery.noConflict();
(function ($) {

    var custom_uploader;
    $(document.body).on("click", ".flip-box-image-upload", function (e) {

        var link = $(this).attr('oxi-upload');
        $('#oxi-addons-preview-data').prepend('<input type="hidden" id="flipbox-body-image-upload-hidden" value="' + link + '" />');

        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        custom_uploader.on('select', function () {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            var url = attachment.url;
            var alt = attachment.alt;
           
            if ((jQuery("#oxi-addons-list-data-modal").data('bs.modal') || {})._isShown) {
                jQuery("#oxi-addons-list-data-modal").css({
                    "overflow-x": "hidden",
                    "overflow-y": "auto"

                });
            }
            var lnkdata = $("#flipbox-body-image-upload-hidden").val();
            var altdata = lnkdata+'-alt';
            $(lnkdata).val(url).change();
            $(altdata).val(alt).change();

        });
        custom_uploader.open();

    });

})(jQuery)