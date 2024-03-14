jQuery(document).ready(function($) {

	 // Triggered when the Upload Thumbnail button is clicked
	 $('#upload_default_thumb').on('click', function(e) {
        e.preventDefault();

        // Create a media uploader instance
        var mediaUploader = wp.media({
            title: wpafi_vars.upload_button_text,
            multiple: false,
        });

        // When a file is selected, do something
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();

            // Update the preview image
            $('#uploaded_thumb_preview').html('<img src="' + attachment.url + '" style="max-width:100%;">');

            // Update the hidden input field with the attachment ID
            $('#default_thumb_id').val(attachment.id);
        });

        // Open the media uploader
        mediaUploader.open();
    });

    // Triggered when the Delete Thumbnail button is clicked
    $('#delete_thumb').on('click', function() {
        // Clear the preview image
        $('#uploaded_thumb_preview').empty();

        // Clear the hidden input field value
        $('#default_thumb_id').val('');
    });

	// Add select2 to dropdowns.
	$('.wpafi-select').select2({
		placeholder: 'Select options',
        allowClear: true,
	});
});