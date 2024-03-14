jQuery.noConflict();
  jQuery(document).ready(function($){
  	$( "#catsubcat" ).menu();
	$(".ui-widget-content").css("background-color", "#f5f5f5");
	$(".ui-widget-content").css("background-image", "none");
	$(".ui-state-hover").css("font-weight","normal");
	$(".ui-menu").removeClass("children");
	$("#catsubcat").mouseleave(function(){
	    $(".ui-front").hide();
	});
	$(window).scroll(function(){
	    $(".ui-front").hide();
	});

  	var custom_uploader;
	
 	$(".iclcat_remove").click( function(e) { iclcat_remove_image(e) });

	$("#iclcat_remove").click( function(e) { iclcat_remove_image(e) });
 	
 	$(".iclcat_icon_button").click(function(e) { iclcat_image_uploader(e) });
 	
 	function iclcat_image_uploader(e)
 	{	
        	e.preventDefault();
	 	size = 'small';

        	//If the uploader object has already been created, reopen the dialog
        	if (custom_uploader) {
            	custom_uploader.open();
            	return;
        	}
 
 		title = 'Choose Image';
 		var term_name = $('input[name="name"]').attr('value');
 		if (term_name != 'undefined' && term_name != '')
 			title = title + ' for ' + term_name;
 			
 		title = title + ' (' + size + ')';	 
 			
 		
 		//console.log(term_name);
        	//Extend the wp.media object
        	custom_uploader = wp.media.frames.file_frame = wp.media({
            	title: title,
            	button: {
                	text: 'Choose Image'
            	},
           		multiple: false
        	});
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();


            var attach_id = attachment.id; 
            var data = {
			action:  'iclcat_new_icon',
			img_url: attachment.url,
			attach_id: attach_id, 
			size: size
			};
            
            $.post(ajax_object.ajax_url, data, function(response) {

	            $('#iclcat_icon_' + size).val(attach_id);
	            $('#iclcat_preview_' + size).html('<img src=' + response.newimg[0] + '>');
            
            }); 
            
            
        });
 
        //Open the uploader dialog
        custom_uploader.open();
    }
 
	function iclcat_remove_image(e)
	{
		size = 'small';
		$('#iclcat_icon_' + size).val(-1);
		$('#iclcat_preview_' + size).html('');   
	}

 });