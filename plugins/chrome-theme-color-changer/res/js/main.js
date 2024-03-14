'use strict';

(function ($){
	function updateColor(){
		$('.color-preview').css('background', '#'+$('.color-picker').val());
	}

	updateColor();

	$('.color-picker').change(updateColor);

	$('.color-preview').click(function(event) {
		$('.color-picker').focus();
	});
})(jQuery);
