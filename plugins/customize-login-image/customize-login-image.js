// Trigger upload dialog
jQuery(document).ready(function() {
	var header_clicked = false;
	jQuery('#upload_image_button').click(function() {
		formfield = jQuery('#upload_image').attr('name');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		header_clicked = true;
	return false;
	});

	// Store original function
	window.original_send_to_editor = window.send_to_editor;
	window.original_tb_remove = window.tb_remove;

	//  Override removing function (resets our boolean)
	window.tb_remove = function() {
		header_clicked = false;
		window.original_tb_remove();
	}

	/**
	* Override send_to_editor function from original script
	* Writes URL into the textbox.
	*
	* Note: If header is not clicked, we use the original function.
	*/
	window.send_to_editor = function(html) {
		if (header_clicked) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#upload_image').val(imgurl);
			tb_remove();
		} else {
			window.original_send_to_editor(html);
		}
	}
});

// Color picker
jQuery(document).ready(function() {
	jQuery('.color-picker').wpColorPicker();
});