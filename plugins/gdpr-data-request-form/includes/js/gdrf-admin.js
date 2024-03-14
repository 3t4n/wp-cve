( function( $ ) {
	'use strict';

	$( window ).on( 'load', function() {

		var section_title     = gdrf_settings.section_title;
		var input_label       = gdrf_settings.input_label;
		var input_value       = gdrf_settings.input_value;
		var save_button_label = gdrf_settings.save_button_label;
	
		var html  = '';

		html += '<hr /><form method="POST" action="" class="gdrf-settings">';
		html += '<h2>' + section_title + '</h2>';
		html += '<table class="form-table tools-privacy-policy-page" role="presentation"><tbody><tr>';
		html += '<th scope="row"><label for="gdrf_email_setting">' + input_label + '</label></th>';
		html += '<td><p><input name="gdrf_email_setting" type="email" value="' + input_value + '" class="regular-text" /></p>';
		html += '<p><input class="button-primary" type="submit" value="' + save_button_label + '" /></p></td>';
		html += '</tr></tbody>';
		html += '</form>';
	
		$( '.privacy-settings-body' ).append( html );

	});

})( jQuery );
