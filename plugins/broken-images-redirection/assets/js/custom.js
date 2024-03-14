jQuery(function($){

    // on upload button click
    $('body').on( 'click', '.broken_image_upload_link', function(e){

        e.preventDefault();

        var button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                library : {
                    // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                    type : 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false
            }).on('select', function() { // it also has "open" and "close" events
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                button.html('<img src="' + attachment.url + '"  style="max-width: 80px;">').next().val(attachment.id).next().show();
                $(".broken_image_hidden").val(attachment.id);
                $(".broken_image_remove_link").show();
            }).open();

    });

    // on remove button click
    $('body').on('click', '.broken_image_remove_link', function(e){

        e.preventDefault();

        var button = $(this);
        button.next().val(''); // emptying the hidden field
        button.hide().prev().html('<span>Upload image</span>');
        $(".broken_image_remove_link").hide();
    });

});
jQuery(document).ready(function() {
    jQuery('#brocken_image').DataTable({
        "columns": [
            null,
            null,
            { "width": "12%" }
        ]
    });
});