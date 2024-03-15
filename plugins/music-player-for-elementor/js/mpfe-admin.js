jQuery(document).ready(function($) {
	"use strict";

	$('.cta_text.import_available').click(function(e){
		e.preventDefault();

		var elt = $(this);
		var please_wait = elt.parent().find('.mpfe_importing');

		$('.mpfe_import_notice').hide();
		elt.hide();
		please_wait.show();

		var json_file = $(this).data('file');

		var data = {
			action: 'mpfe_import_template',
			filename: json_file
		};

		$.post(
			sdata.ajaxurl, 
			data, 
			function(response) {
				var obj;

				obj = $.parseJSON(response);
				elt.show();
				please_wait.hide();
				$("html, body").animate({ scrollTop: 0 }, "slow");
				if(obj.success === true) { 
					$('.mpfe_import_success').show();
				} else {
					$('.mpfe_import_error_message').remove();
					$('.mpfe_import_failed').append('<div class="mpfe_import_error_message"><strong>' + obj.message + '</strong></div>');
					$('.mpfe_import_failed').show();
				}
			});
	})
});