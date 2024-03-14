jQuery(document).ready(function($) {
	var upload_button;
	
	/** upload & assign featured image **/
	$(".eti_upload_image").click(function(event) {
		upload_button 	= $(this);
		txt_box_id		= $(this).closest('.eti-image-wrap').find('.eti_image_url').attr('id');
		var frame;
		if (eti.wp_version >= "3.5") {
			event.preventDefault();
			if (frame) {
				frame.open();
				return;
			}
			frame = wp.media();
			frame.on( "select", function() {
				var attachment = frame.state().get("selection").first();
				frame.close();
				if (upload_button.parent().prev().children().hasClass("tax_list")) {
					upload_button.parent().prev().children().val(attachment.attributes.url);
					upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
					
				}
				else {
					if(upload_button.closest('.eti-image-wrap').find('.eti_image').length ){
						upload_button.closest('.eti-image-wrap').find('.eti_image').attr('src',attachment.attributes.sizes.thumbnail.url);
					}
					$("#"+txt_box_id).val(attachment.attributes.url);
				}
			});
			frame.open();
		}
		else {
			tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
			return false;
		}
	});
	
	/** remove featured & cover Image **/
	$(".eti_remove_image_button").click(function() {
		txt_box_id		= $(this).closest('.eti-image-wrap').find('.eti_image_url').attr('id');
		img_field		= $(this).closest('.eti-image-wrap').find('.eti_image');
		// on removal add placeholder image
		img_field.attr("src", eti.featured_placeholder);
		$("#"+txt_box_id).val("");
		$(this).parent().siblings(".title").children("img").attr("src","");
		$(".inline-edit-col :input[name=\'taxonomy_featured_image\']").val("");
		return false;
	});
	
	/** quick edit featured & cover Image **/
	$(".editinline").click(function() {	
	    var tax_id = $(this).parents("tr").attr("id").substr(4);
	    var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");
	    var cover_thumb = $("#tag-"+tax_id+" .thumb img").attr("src");
		
		if($(".inline-edit-col :input[name=\'taxonomy_featured_image\']").length) {
			if (thumb != eti.featured_placeholder) {
				$(".inline-edit-col :input[name=\'taxonomy_featured_image\']").val(thumb);
			} else  {
				$(".inline-edit-col :input[name=\'taxonomy_featured_image\']").val("");
			}
			$(".inline-edit-col .title img").attr("src",thumb)
		}
		
		if($(".inline-edit-col :input[name=\'taxonomy_cover_image\']").length) {
			if (cover_thumb != eti.cover_placeholder) {
				$(".inline-edit-col :input[name=\'taxonomy_cover_image\']").val(cover_thumb);
			} else  {
				$(".inline-edit-col :input[name=\'taxonomy_cover_image\']").val("");
			}
		}
		
		;
	});
});
