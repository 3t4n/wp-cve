jQuery(document).ready( function($) {


    $('a[data-rel^=lightcase]').lightcase();


    jQuery('.bc-doc__image-picker-button').click(function(e) {

        let hidden_input = $(this).closest('.bc-image-picker').find('.bc_image_picker_hidden_input').first();
        let image_preview = $(this).closest('.bc-image-picker').find('.bc_image_preview').first();
        e.preventDefault();
        var image_frame;
        if(image_frame){
            image_frame.open();
        }
        // Define image_frame as wp.media object
        frame = wp.media({
            title: 'Select Cart Image',
            multiple : false,
            library : {
                type : 'image',
            }
        });

        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            console.log(attachment.url);
            image_preview.attr('src', attachment.url);
            hidden_input.val(attachment.url);

        });

        frame.open();
    });

});