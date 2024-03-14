(function($) {
	$(document).ready(function() {
		 
		$('#upload_image_button').click(function() {
			window.send_to_editor = function(html) {
				imgurl = $('img', html).attr('src');
				$('#upload_image').val(imgurl);
				tb_remove();
			};
			 
			tb_show('', 'media-upload.php?post_id=1&amp;type=image&amp;TB_iframe=true');
			return false;
		});
	 
	});
})(jQuery);
 