jQuery(document).ready(function($) {
    var custom_uploader;
    $('#upload_image_button').click(function(e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: true
        });
        var i = 1;
        var j = 0;
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            var selection = custom_uploader.state().get('selection');
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                $('#upload_image').after("<input type='hidden' id='firstimage"+i+"' name='code_gallery_attachment[]' value="+attachment.id+" size='25'><div class='editthumb' id='imagediv"+i+"'><img src="+attachment.url+"><span class='removebtn'><a  onClick='removeImage("+i+")' id='removebutton"+i+"' class='glyphicon glyphicon-remove buttonremove' ></a></span></div>");
                i++;
                ++j;

            });

        });
        //Open the uploader dialog
        custom_uploader.open();
    });
    $('#upload_image_button_tax').click(function(e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: true
        });
        var i = 0;
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            var selection = custom_uploader.state().get('selection');
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                $('#upload_image_tax').val(attachment.url);
                $('#taxeditimg').attr('src',attachment.url);
                if(('#upload_image_tax').val() == '') {
                    $('taxeditimg').hide();
                }


            });
        });
        //Open the uploader dialog
        custom_uploader.open();
    });
});
