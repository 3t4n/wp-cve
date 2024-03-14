(function($) {
    jQuery(document).ready(function( ) {
		var upload_url_path = $("#upload_url_path");
		if ( upload_url_path.val() == '/wp-content/uploads' ||  upload_url_path.val() == '/wp-content/uploads' ){
			upload_url_path.val("");
		}
    });
})(jQuery);
