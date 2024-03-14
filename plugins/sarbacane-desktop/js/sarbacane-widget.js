function sarbacaneSubmitWidget( list_type ) {
	// For browsers which not support required
	var isSubmitable = true;
	jQuery( ".sarbacane_desktop_configuration_input_red" ).removeClass( "sarbacane_desktop_configuration_input_red" );
	jQuery( "#sarbacane_desktop_widget_form_" + list_type + " .required" ).each( function () {
		if ( ! jQuery( this ).val() ) {
			isSubmitable = false;
			jQuery( this ).addClass( "sarbacane_desktop_configuration_input_red" );
		}
	} );
	var emailValue = jQuery( "#sarbacane_desktop_widget_form_" + list_type + " #email_" + list_type ).val();
	var regex = new RegExp(
		"[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?",
		"ig"
	);
	if ( !regex.test( emailValue ) ) {
		isSubmitable = false;
		jQuery( "#sarbacane_desktop_widget_form_" + list_type + " #email_" + list_type )
			.addClass( "sarbacane_desktop_configuration_input_red" );
	}
	if ( isSubmitable ) {
		jQuery( "#sarbacane_desktop_widget_form_" + list_type + " .sarbacane_form_value" ).val( "sarbacane_desktop_widget" );
		return true;
	}
	return false;
}
