jQuery( function( $ ){

	// When the select them list changes
	$( 'select#theme' ).change( function(){
		
		var theme = $(this).val(), // The vhosed theme
			imageArray = [ 'light', 'dark', 'blue', 'red' ], // Array of image options
			newButton, // New button to replace existing on screen
			buttonText = $( 'input#button-text' ).val(); // The text for the custom button
		
		// If the theme is one of the image options, set image flag to true
		var image = ( $.inArray( theme, imageArray ) != -1 ) ? true : false;
		
		// If it's an image
		if( image === true ) {
			
			// Create the input
			newButton = '<input id="eppcf-button" type="submit" class="rps-custom-theme-button rps-paypal-button-' + theme + '" value="' + buttonText + '">';
		
		} else {
			
			var buttonType = $( 'select#button-type' ).val(), // Type of button ('Buy Now', or 'Donations')
				uri = $( 'input#plugin-url' ).val(), // The plugins URI
				src = uri + '/images/'; // The source of the image
			
			// If it's a 'Buy Now' button
			if( buttonType === 'Buy Now' ) {
				
				// Set the src according to whether the button is large or small
				src += ( theme === 'pp_large' ) ? 'btn_buynow_LG.gif' : 'btn_buynow_SM.gif';
			
			// Etc.
			} else {
			
				src += ( theme === 'pp_large' ) ? 'btn_donate_LG.gif' : 'btn_donate_SM.gif';
										
			}
			
			// Our new button looks like this
			newButton = '<input id="eppcf-button" class="eppcf-image-button" type="image" src="' + src + '" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">';
		
		}
		
		// Append the new button to the DOM
		$( 'h4.paypal-form' ).find( 'input' ).remove().end().append( newButton );
	
	// Trigger the event
	}).trigger( 'change' );
	
	
	
	// When the button type select list changes
	$( 'select#button-type' ).change( function(){
	
		var input = $( '#eppcf-button' ),
			buttonType = $(this).val(),
			theme = $( 'select#theme' ).val(),
			uri = $( 'input#plugin-url' ).val(), // The plugins URI
			src = uri + '/images/'; // The source of the image
		
		// If the button is an image
		if( input.hasClass( 'eppcf-image-button' ) ) {
			
			// If it's a 'Buy Now' button
			if( buttonType === 'Buy Now' ) {
				
				// Set the src according to whether the button is large or small
				src += ( theme === 'pp_large' ) ? 'btn_buynow_LG.gif' : 'btn_buynow_SM.gif';
			
			// Etc.
			} else {
			
				src += ( theme === 'pp_large' ) ? 'btn_donate_LG.gif' : 'btn_donate_SM.gif';
										
			}
			
			input.attr( 'src', src ); 
		
		}
	
	});
	
	
	
	// When the button text input changes
	$( 'input#button-text' ).keyup( function(){
				 	
	 	var customText = $( this ).val(), // Get the value
			input = $( '#eppcf-button' ); // Our input
		
		// If the button is a custom theme
		if( input.hasClass( 'rps-custom-theme-button' ) ) {
			
			// Set the text to this value
			input.val( customText );
		
		}
		
	});
	


});