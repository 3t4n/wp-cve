jQuery(document).ready(function($){
	jQuery( "#main_media" ).disableSelection();

	var media_uploader = null;

	jQuery("#add_more").on('click', function(){
		media_uploader = wp.media({
	        frame:    "post", 
	        state:    "insert", 
	        multiple: true 
	    });
	    media_uploader.on("insert", function(){

        var length = media_uploader.state().get("selection").length;
        var images = media_uploader.state().get("selection").models

        for(var iii = 0; iii < length; iii++){
            var image_url = images[iii].changed.url;
        	var append_data = 
        	`
			<div class="bend_single_logos" id="bend_single_logos`+iii+`">
				<div class="image_area">
					<img src="`+image_url+`">
					<input type="hidden" name="image_name[]" value="`+image_url+`">
				</div>						
				<span class="pels_logo_remove"><i class="fa fa-times" aria-hidden="true" onclick="del_div(`+iii+`);"></i></span>
            	<div class="logo-content-holder">
                    <div class="input_area">
                    	<input type="text" name="bend_single_logo_name[]" class="widefat" placeholder="Insert Logo Title" value="">
                    </div>
                    <div class="input_area">
                    	<input type="url" name="bend_single_logo_url[]" class="widefat" placeholder="Insert Logo URL" value="">
                    </div>
                    <div class="input_area">
                   		<textarea id="logdesc" name="bend_single_logo_desc[]" class="widefat" placeholder="Logo Description"></textarea>
                    </div>
                </div>		                										        							
			</div>
        	`;
        	jQuery("#main_media").prepend(append_data);
        }
    });
		media_uploader.open();			
	});

	$("#fusion_pagination").on('change', function(){
		var fusion_pagination = jQuery("#fusion_pagination").val();
		if(fusion_pagination == "load_more"){
			jQuery("#fsh_1, #fsh_2, #fsh_3, #fsh_4, #fsh_5, #fsh_6").show('medium');
		} else {
			jQuery("#fsh_1, #fsh_2, #fsh_3, #fsh_4, #fsh_5, #fsh_6").hide('medium');
		}
	});

	var fusion_pagination = jQuery("#fusion_pagination").val();
    if(fusion_pagination == "load_more"){
		jQuery("#fsh_1, #fsh_2, #fsh_3, #fsh_4, #fsh_5, #fsh_6").show('medium');
	}
});

function del_div(y){
	var $item = jQuery("#bend_single_logos"+y);
	$item.hide('medium', function(){ $item.remove(); });	
}

function del_Saveddiv(y){
	var $item = jQuery("#bend_single_logos"+y);
	$item.hide('medium', function(){ $item.remove(); });		
}