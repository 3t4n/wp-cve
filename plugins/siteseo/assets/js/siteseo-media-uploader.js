jQuery(document).ready(function ($) {
    
	//Clear the previous image if a user paste / edit the URL
    $(document).on('paste change', "#siteseo_social_fb_img_meta", function () {
        $("#siteseo_social_fb_img_attachment_id").val('');
        $("#siteseo_social_fb_img_width").val('');
        $("#siteseo_social_fb_img_height").val('');
    });
    
	$(document).on('paste change', "#siteseo_social_twitter_img_meta", function () {
        $("#siteseo_social_twitter_img_attachment_id").val('');
        $("#siteseo_social_twitter_img_width").val('');
        $("#siteseo_social_twitter_img_height").val('');
    });

    var mediaUploader;
    $(document).on( 'click', ".button.siteseo_social_facebook_img_cpt", function (e) {
        e.preventDefault();

        var url_field = $(this).parent().find("input[type=text]");
        // Extend the wp.media object
        mediaUploader = wp.media.frames.file_frame = wp.media({
            multiple: false,
        });

        // When a file is selected, grab the URL and set it as the text field's value
        mediaUploader.on("select", function () {
            attachment = mediaUploader
                .state()
                .get("selection")
                .first()
                .toJSON();
            $(url_field).val(attachment.url);
        });
        // Open the uploader dialog
        mediaUploader.open();
    });

    const array = [
        "#siteseo_social_knowledge_img",
        "#knowledge_img",
        "#siteseo_social_fb_img",
        ".siteseo_social_fb_img",
        "#siteseo_social_twitter_img",
        ".siteseo_social_twitter_img"
    ];

    array.forEach(function (item){
        var mediaUploader;
        $(document).on('click', item + "_upload", function (e) {
            e.preventDefault();
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            // Extend the wp.media object
            mediaUploader = wp.media.frames.file_frame = wp.media({
                multiple: false,
            });

            // When a file is selected, grab the URL and set it as the text field's value
            mediaUploader.on("select", function () {
                attachment = mediaUploader
                    .state()
                    .get("selection")
                    .first()
                    .toJSON();
                $(item + "_meta").val(attachment.url);
                if (
                    (item == "#siteseo_social_fb_img" || item == ".siteseo_social_fb_img") &&
                    typeof siteseo_social_img != "undefined"
                ) {
                    siteseo_social_img("fb");
                }
                if (
                    (item == "#siteseo_social_twitter_img" || item == ".siteseo_social_twitter_img") &&
                    typeof siteseo_social_img != "undefined"
                ) {
                    siteseo_social_img("twitter");
                }

                if ($(item + "_attachment_id").length != 0) {
                    $(item + "_attachment_id").val(attachment.id);
                    $(item + "_width").val(attachment.width);
                    $(item + "_height").val(attachment.height);
                }
            });

            // Open the uploader dialog
            mediaUploader.open();
        });
    });

    $(document).on('click', ".siteseo-btn-upload-media", function (e) {
        e.preventDefault();

        var mediaUploader;

        // If the uploader object has already been created, reopen the dialog
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        // Extend the wp.media object
        mediaUploader = wp.media.frames.file_frame = wp.media({
            multiple: false,
        });

        var _self = $(this);

        mediaUploader.on("select", function () {
            attachment = mediaUploader
                .state()
                .get("selection")
                .first()
                .toJSON();

            $(_self.data("input-value")).val(attachment.url);
        });

        // Open the uploader dialog
        mediaUploader.open();
    });
});
