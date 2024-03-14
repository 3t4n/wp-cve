jQuery(document).ready(function($){
	"use strict";
	
	// Loading Spinner
	var cad_loader = '<div class="loading_bg"><div class="loader"><p><img src="https://app.content.ad/Images/ajax-loader.gif" alt="" /></p><p>Loading<br />please wait</p></div></div>';
	function spinner(action, position) {
		if(position === 'iframe') {
			$('#TB_iframeContent').remove();
			$('#TB_window').append(cad_loader);
			$('.loading_bg').addClass('iframe');
		} else {
			$('body').append(cad_loader);
		}
		if(action === 'show') {
			$('.loading_bg').addClass('show');
			$('.loader').focus();
		} else if(action === 'hide') {
			$('.loading_bg').removeClass('show').removeClass('iframe');
		}
	}

	// Reload the page when thickbox is closed
	var original_tb_remove = tb_remove;
	tb_remove = function () {
		if($('#TB_iframeContent').attr('src').indexOf('Publisher/Widgets') > -1){
			location.reload(true);
			spinner('show', 'iframe');
			return false;
		} else {
			original_tb_remove();
		}
	};
	
	$('#verify_api_key').on('click', function() {
		spinner('show');
	});
});