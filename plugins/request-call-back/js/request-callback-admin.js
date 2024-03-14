jQuery(document).ready(function($) {

	function setStyleMode() {
		var selectedStyle = $('input[name=wpcallback_plugin_option\\[styling\\]]:checked').val();

		if(selectedStyle == 'custom') {
			$('#button_colour_select').prop('disabled', 'disabled');
		}
		else {
			$('#button_colour_select').prop('disabled', false);
		}
	}

	function setLightboxMode() {
		var selectedMode = $('input[name=wpcallback_plugin_option\\[lightbox\\]]:checked').val();

		if(selectedMode == 'disabled') {
			$('#link_to_page').show();
		}
		else {
			$('#link_to_page').hide();
		}
	}

	function setAvailableTimeMode() {
		var selectedTime = $('input[name=wpcallback_plugin_option\\[field_time\\]]:checked').val();

		if(selectedTime != 'disabled') {
			$('#available_times').show();
		}
		else {
			$('#available_times').hide();
		}
	}

	setStyleMode();
	setLightboxMode();
	setAvailableTimeMode();

	$('.callback_options input[name=wpcallback_plugin_option\\[styling\\]]').click(function() {
		setStyleMode();
	});

	$('.callback_options input[name=wpcallback_plugin_option\\[lightbox\\]]').click(function() {
		setLightboxMode();
	});

	$('.callback_options input[name=wpcallback_plugin_option\\[field_time\\]]').click(function() {
		setAvailableTimeMode();
	});

});