jQuery(document).ready(function($){
	$('#cursor_color').wpColorPicker({
		change: function( event, ui ) {
			$("#custom-background-image").css('background-color', ui.color.toString());
		},
		clear: function() {
			$("#custom-background-image").css('background-color', '');
		}
	});

	$('#cursor_border_color').wpColorPicker({
		change: function( event, ui ) {
			$("#custom-background-image").css('background-color', ui.color.toString());
		},
		clear: function() {
			$("#custom-background-image").css('background-color', '');
		}
	});
	
	$('#rail_bg_color').wpColorPicker({
		change: function( event, ui ) {
			$("#custom-background-image").css('background-color', ui.color.toString());
		},
		clear: function() {
			$("#custom-background-image").css('background-color', '');
		}
	});
});