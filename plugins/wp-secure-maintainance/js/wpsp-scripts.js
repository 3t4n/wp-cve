jQuery(document).ready(function() {
    jQuery("#imageUpload").change(function() {
        wpsp_read_fike_url(this);
    });

    jQuery("input.wpsp-color-picker").change(function() {
        var $color = jQuery(this).val();
        jQuery("input:checked + .slider").css("background-color", $color);
    });

    if(jQuery('input[name="wp-secure-settings_options[wpsp-enable-maintenance-mode]"]').is(':checked')) {
        var disabled = false;
        wpsp_wp_media_upload();
    } else {
        var disabled = true;
    }

    wpsp_disable_fields(disabled);

    jQuery('input[name="wp-secure-settings_options[wpsp-enable-maintenance-mode]"]').change(function() {
        if(jQuery('input[name="wp-secure-settings_options[wpsp-enable-maintenance-mode]"]').is(':checked')) {
            wpsp_wp_media_upload();
            var disabled = false;
            wpsp_disable_fields(disabled);
        } else {
            var disabled = true;
            wpsp_disable_fields(disabled);
            jQuery(this).css("background-color", "#cccccc!important");
        }
    });
});

function wpsp_disable_fields(disabled) {
    jQuery("input.field-input").attr("readonly", disabled);
    jQuery("input.wpsp-color-picker").attr("disabled", disabled);
    jQuery("textarea#wpsp_custom_css").attr("readonly", disabled);
}


function wpsp_wp_media_upload() {
    var file_frame;
    jQuery('label.onetarek-upload-button').on('click', function( event ){
        event.preventDefault();
    
        var that = jQuery(this);
    
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
          title: 'WP Secure Maintenance Logo',
          button: {
            text: 'Upload',
          },
          multiple: false  // Set to true to allow multiple files to be selected
        });
        file_frame.open();
    
        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
    
          // We set multiple to false so only get one image from the uploader
          attachment = file_frame.state().get('selection').first().toJSON();
            jQuery("#imagePreview").css("background-image", "url(" + attachment.url + ")");
            jQuery("input[name='wp-secure-settings_options[wpsp-logo]']").val( attachment.id );
          
        });
    
        // Finally, open the modal
        file_frame.open();
    });
}

function wpsp_read_fike_url(input) {
    console.log(input);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            jQuery('#imagePreview').css('background-image', 'url('+e.target.result +')');
            jQuery('#imagePreview').hide();
            jQuery('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}