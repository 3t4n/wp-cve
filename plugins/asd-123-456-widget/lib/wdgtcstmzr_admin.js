(function($){
"use strict";

	$(document).ready(function(){
		function updateColorPickers(){
			$('#widgets-right .wdgtcstmzr-color').each(function(){
				$(this).wpColorPicker({
					defaultColor: false,
					change: function(event, ui){},
					clear: function() {},
					hide: true,
				});
			});
		}
		updateColorPickers();
		$(document).ajaxSuccess(function(e, xhr, settings) {
			if(settings.data.search('action=save-widget') != -1 ) {
				$('.color-field .wp-picker-container').remove();
				updateColorPickers();
			}
		});
	});
})(jQuery);