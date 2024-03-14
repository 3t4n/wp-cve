jQuery(document).ready(function($) {
	$(document).on("click", ".smartlib-upload-image-button", function() {

		var $link_handle =  $(this);
    var $parentUploadArea = $link_handle.parents('.smartlib-widget-upload-area');
		var inputText = $parentUploadArea.find('.smartlib-upload-input');
		var imageContainer = $parentUploadArea.find('.smartlib-widget-image-outer');
    console.log($link_handle.parents());
		jQuery.data(document.body, 'prevElement', $(this).prev());

		window.send_to_editor = function(html) {
			var imgurl = jQuery('img',html).attr('src');

			if(inputText != undefined && inputText != '')
			{
				inputText.val(imgurl);
				imageContainer.find('img').attr('src', imgurl);

			}

			tb_remove();
		};

		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		return false;
	});
});
