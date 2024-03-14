jQuery(document).ready(


    function($){

        jQuery('#scrollup_upload_image_preview').attr('src', jQuery('#scrollup_upload_image').val());

        if(jQuery('#scrollup_type').val()=="image") {
            jQuery('#scrollup_custom_image_section').parent().parent().show();
            jQuery('#scrollup_custom_text_section').parent().parent().hide();
            jQuery('.scrollup_custom_icon_section').parent().parent().hide();
        }
        else if(jQuery('#scrollup_type').val()=="icon") {
            jQuery('#scrollup_custom_image_section').parent().parent().hide();
            jQuery('#scrollup_custom_text_section').parent().parent().hide();
            jQuery('.scrollup_custom_icon_section').parent().parent().show();
        }
        else{
            jQuery('#scrollup_custom_image_section').parent().parent().hide();
            jQuery('#scrollup_custom_text_section').parent().parent().show();
            jQuery('.scrollup_custom_icon_section').parent().parent().hide();
        }

        $('#scrollup_custom_icon_button').click(function() {
            $('#scrollup_custom_icon_dialog').dialog({
                title: 'Select Icon',
                modal: true,
                width: 800,
                height: 400
            });
            return false;
        });

        $('.scrollup-custom-icon-list-icon').click(function() {
            jQuery('#scrollup_custom_icon').attr('value', $(this).attr('name'));
            jQuery('#scrollup_custom_icon_preview').attr('class', 'fa ' + jQuery( "#scrollup_custom_icon" ).val() +' ' + jQuery( "#scrollup_custom_icon_size option:selected" ).val());
            $('#scrollup_custom_icon_dialog').dialog('close')
            return false;
        });

        jQuery('#scrollup_type').change(
            function(){
                var i= jQuery('#scrollup_type').val();
                if(i=="image") {
                    jQuery('#scrollup_custom_image_section').parent().parent().fadeIn(100);
                    jQuery('#scrollup_custom_text_section').parent().parent().fadeOut(100);
                    jQuery('.scrollup_custom_icon_section').parent().parent().fadeOut(100);
                }
                else if(i=="icon") {
                    jQuery('#scrollup_custom_image_section').parent().parent().fadeOut(100);
                    jQuery('#scrollup_custom_text_section').parent().parent().fadeOut(100);
                    jQuery('.scrollup_custom_icon_section').parent().parent().fadeIn(100);
                }
                else
                {
                    jQuery('#scrollup_custom_image_section').parent().parent().fadeOut(100);
                    jQuery('#scrollup_custom_text_section').parent().parent().fadeIn(100);
                    jQuery('.scrollup_custom_icon_section').parent().parent().fadeOut(100);
                }
            }
        );

        jQuery('#scrollup_custom_icon_size').change(
            function(){
                jQuery('#scrollup_custom_icon_preview').attr('class', 'fa ' + jQuery( "#scrollup_custom_icon" ).val() +' ' + jQuery( "#scrollup_custom_icon_size option:selected" ).val());
            }
        );
        

        jQuery('.scrollup_upload_image_button').click(
            function(e) {
                e.preventDefault();
                var image = wp.media(
                    { 
                        title: 'Upload Image',
                        // mutiple: true if you want to upload multiple files at once
                        multiple: false
                    }
                ).open()
                .on(
                    'select', function(e){
                        // This will return the selected image from the Media Uploader, the result is an object
                        var uploaded_image = image.state().get('selection').first();
                        // We convert uploaded_image to a JSON object to make accessing it easier
                        var image_url = uploaded_image.toJSON().url;
                        // Let's assign the url value to the input field
                        jQuery('#scrollup_upload_image').val(image_url);
                        jQuery('#scrollup_upload_image_preview').attr('src', image_url);
                    }
                );
            }
        );
    }
);