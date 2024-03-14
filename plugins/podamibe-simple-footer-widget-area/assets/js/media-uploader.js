jQuery(function ($) {

    // Set all variables to be used in scope
    var frame,
        addImgLink = '.upload-custom-img',
        delImgLink = '.delete-custom-img';

    // ADD IMAGE LINK
    $('body').on('click', addImgLink, function (event) {
        event.preventDefault();
        var addImgButton = $(this);
        var delImgButton = addImgButton.parent().find(".delete-custom-img");
        var imgContainer = addImgButton.parent().parent().find('.custom-img-container');
        var imgIdInput = addImgButton.parent().parent().find('.custom-img-id');
        console.log(addImgButton);
        console.log(delImgButton);

        // Create a new media frame
        frame = wp.media({
            title: 'Select or Upload Media',
            button: {
                text: 'Use this image'
            },
            multiple: false // Set to true to allow multiple files to be selected
        });


        // When an image is selected in the media frame...
        frame.on('select', function () {

            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();

            // Send the attachment URL to our custom image input field.
            imgContainer.append('<img src="' + attachment.url + '" alt="" style="max-width:100%;"/>');

            // Send the attachment id to our hidden input
            imgIdInput.val(attachment.url);

            // Hide the add image link
            addImgButton.addClass('hidden');

            // Unhide the remove image link
            delImgButton.removeClass('hidden');
        });

        // Finally, open the modal on click
        frame.open();
    });


    // DELETE IMAGE LINK
    $('body').on('click', delImgLink, function (event) {
        event.preventDefault();
        var delImgButton = $(this);
        var addImgButton = $(this).parent().find(".upload-custom-img");
        var imgContainer = addImgButton.parent().parent().find('.custom-img-container');
        var imgIdInput = addImgButton.parent().parent().find('.custom-img-id');

        // Clear out the preview image
        imgContainer.html('');

        // Un-hide the add image link
        addImgButton.removeClass('hidden');

        // Hide the delete image link
        delImgButton.addClass('hidden');

        // Delete the image id from the hidden input
        imgIdInput.val('');

    });
    $('body').on('mousedown', '.icp-auto', function (event) {

        $(this).iconpicker( {
            title: 'Select Icons',
            icons: ['facebook', 'facebook-official', 'facebook-square', 'twitter', 'twitter-square', 'pinterest', 'pinterest-p', 'pinterest-square'],
            
        });
    });

});