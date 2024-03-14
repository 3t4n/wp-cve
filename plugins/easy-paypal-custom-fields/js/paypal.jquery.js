
jQuery( function($) {

 /**
 	* Keep the global namespace clean and tidy
 	*/
	var eppcf = {
		$rpsb: $('#rps-settings-box'),
		$checkbox: $('#textfield_checkbox'),
		$textfield: $('#custom_textfield')
	}

	// Hide the div containing the default settings & add change default settings trigger toggle
	eppcf.$rpsb.hide().before('<p><a class="button secondary" id="rps-view-toggle" href="#">Change default settings</a></p>');
	
	// When the link is clicked...
	$('#rps-view-toggle').toggle( function() {

		// Change the text of the trigger from 'change... to hide...'
		$(this).text( 'Hide default settings' );

		// Slide down and fade in the settings div
		eppcf.$rpsb.animate({ 'opacity' : 'toggle', 'height' : 'toggle' }, 300);

		// Stop the default behaviour of the clicked link
		return false;

	}, function() {
	
		// Change the text of the trigger back
		$(this).text( 'Change default settings' );
	
		// Fade out and slide up the settings div
		eppcf.$rpsb.animate({ 'opacity' : 'toggle', 'height' : 'toggle' }, 300);
	
		return false;
	
	});
 
 	// If the extra textbox checkbox is checked
 	if( eppcf.$checkbox.is(':checked') ) {
 	
 		// Make sure the textfield title field is visible
		eppcf.$textfield.show();
	
	} else {
	
		// Otherwise hide it
		eppcf.$textfield.hide();
		
	}
	
	// If the extra textbox checkbox is checked or unchecked
	eppcf.$checkbox.change( function(){

		// Fade in or out the textfield title field
		eppcf.$textfield.fadeToggle();

	});	
	
});