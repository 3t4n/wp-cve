/**
 * Tgchannel admin
 * 
 *
 * @version 2018-04-07
 */

jQuery(document).ready(function($) {
$( ".sizeofwidget input" ).on( "click", function() {
 var checked = $( this ).val();
if (checked == "small" || checked == "medium"){
$(".bodysize0").hide();	
}else{
$(".bodysize0").show();		
}
});

});


jQuery(document).ready(function($){
    $('.my-color-field').wpColorPicker();

    var custom_uploader;
	
    $('#TG_Channel_upload_image_button').click(function(e) {

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

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            console.log(custom_uploader.state().get('selection').toJSON());
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#bglink').val(attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();

    });
});



