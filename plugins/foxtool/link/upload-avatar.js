jQuery( document ).ready( function($) {
    var _foxtool_media = true;
	var _foxtool_send_attachment = wp.media.editor.send.attachment;
	$('.foxtool-image').click(function() {
		var button = $(this),
			textbox_id = $(this).attr('data-id'),
			image_id = $(this).attr('data-src'),
			_shr_media = true;
		wp.media.editor.send.attachment = function(props, attachment) {
			if (_shr_media && attachment.type === 'image') {
				if (image_id.indexOf(",") !== -1) {
					image_id = image_id.split(",");
					var $image_ids = '';
					$.each(image_id, function(key, value) {
						if ($image_ids)
							$image_ids = $image_ids + ',#' + value;
						else
							$image_ids = '#' + value;
					});
					var current_element = $($image_ids);
				} else {
					var current_element = $('#' + image_id);
				}
				$('#' + textbox_id).val(attachment.id);
				current_element.attr('src', attachment.url).show();
			} else {
				alert('Please select a valid image file');
				return false;
			}
		};
		wp.media.editor.open(button);
		return false;
	});
	/* xoa avatar */				
	$('#reset-hinh-anh').click(function() {
		$('#foxtool_image_id').attr('value', ''); 
		$('#foxtool-img').attr('src','#'); 
		$("#foxtool-img").css('display', 'none');
		$("#reset-hinh-anh").css('display', 'none');
	});
	$('#foxtool-image').on('click', function(e) {
		$('#foxtool-img').attr('src','');
		$("#foxtool-img").css('display', 'inline-block');
		$("#reset-hinh-anh").css('display', 'block');
	});												
});