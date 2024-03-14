(function($) {
	$(function() {
		directorypress_select2_init();
	});
	window.directorypress_select2_init = function () {
		jQuery('.directorypress-select2').select2();
	}
}(jQuery));