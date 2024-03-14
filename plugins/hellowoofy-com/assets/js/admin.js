jQuery(document).ready(function($) {
    $('.mws_enable_popup').select2();
    width: 'resolve'
    theme: 'classic'
    $('.mws_select_page_tr').hide();
    $('.mws_select_position_tr').hide();
    $('.mws_enable').click(function(){
        if($(this).is(":checked")){
            $('.mws_select_page_tr').show();
            $('.mws_select_position_tr').show();
        }
        else if($(this).is(":not(:checked)")){
            $('.mws_select_page_tr').hide();
            $('.mws_select_position_tr').hide();
        }
    });

    
    if ($(".mws_enable").is(':checked')){
        $('.mws_select_page_tr').show();
        $('.mws_select_position_tr').show();
    }else{
    	$('.mws_select_page_tr').hide();
            $('.mws_select_position_tr').hide();
    }

    let mwsWebSelectBtn = 0;
	let mwsWebMedia = 0;
	// This method is used to upload image file of base field for update.
	jQuery(document).on('click','#mws_web_story_icon_upload', function(e){
		e.preventDefault();
		mwsWebSelectBtn = jQuery(this);
		// Extend the wp.media object
		mwsWebMedia = wp.media.frames.file_frame = wp.media({
			title: 'Select media',
			button: {
			text: 'Select media'
		}, multiple: false });
		// When a file is selected, grab the URL and set it as the text field's value
		mwsWebMedia.on('select', function() {
			var attachment = mwsWebMedia.state().get('selection').first().toJSON();
			jQuery('#mws_web_story_icon').val(attachment.id);
			jQuery('#mws_web_story_img').attr('src', attachment.url);
		});
		// Open the upload dialog
		mwsWebMedia.open();
	});
});