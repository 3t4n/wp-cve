jQuery(document).ready(function($) {

    // Color picker
	$('.colorpicker').each( function() {
		var colorPicker = new jscolor($(this)[0], {
			'hash': true
		});
	});

});