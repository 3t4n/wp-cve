'use strict';
var wpmseo_target_id;
jQuery(document).on('click', '#wpms-onelementor-tab', function (e) {
    var wpmseo_uploader;
    jQuery('.wpmseo_image_upload_button').on('click', function (e) {
        wpmseo_target_id = jQuery(this).attr('id').replace(/_button$/, '');
        e.preventDefault();
        if (wpmseo_uploader) {
            wpmseo_uploader.open();
            return;
        }
        wpmseo_uploader = wp.media.frames.file_frame = wp.media({
            title: wpmseoMediaL10n.choose_image,
            button: {text: wpmseoMediaL10n.choose_image},
            multiple: false
        });

        wpmseo_uploader.on('select', function () {
            var attachment = wpmseo_uploader.state().get('selection').first().toJSON();
            jQuery('#' + wpmseo_target_id).val(attachment.url);
            wpmseo_uploader.close();
        });

        wpmseo_uploader.open();
    });
});