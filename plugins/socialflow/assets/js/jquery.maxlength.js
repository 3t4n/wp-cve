/**
 * jQuery Maxlength plugin
 * @version		$Id: jquery.maxlength.js 18 2009-05-16 15:37:08Z emil@anon-design.se $
 * @package		jQuery maxlength 1.0.5
 * @copyright	Copyright (C) 2009 Emil Stjerneman / http://www.anon-design.se
 * @license		GNU/GPL, see LICENSE.txt
 */
/**
 * jQuery Maxlength plugin
 * @version		$Id: jquery.maxlength.js 18 2009-05-16 15:37:08Z emil@anon-design.se $
 * @package		jQuery maxlength 1.0.5
 * @copyright	Copyright (C) 2009 Emil Stjerneman / http://www.anon-design.se
 * @license		GNU/GPL, see LICENSE.txt
 */

(function($) 
{

	$.fn.sfMaxlength = function(options)
	{
		var settings = jQuery.extend(
		{
			events:				[], // Array of events to be triggerd
			maxCharacters:		10, // Characters limit
			status:				true, // True to show status indicator bewlow the element
			statusClass:		"status", // The class on the status div
			statusText:			"character left", // The status text
			notificationClass:	"notification",	// Will be added to the emement when maxlength is reached
			showAlert: 			false, // True to show a regular alert message
			alertText:			"You have typed too many characters.", // Text in the alert message
			slider:				false, // Use counter slider
			twitterText:        false,
			secondField:        [],
		}, options );
		
		// Add the default event
		$.merge(settings.events, ['keyup']);

		return this.each(function() 
		{
			var $items = $(this).data( 'field', 'main' );

			var maxCharacters = settings.maxCharacters;

			// Validate
			if( !validateElement( $items ) ) 
				return false;

			var $secondField = settings.secondField;

			if ( $secondField && $secondField.length && validateElement( $secondField ) ) {
				$secondField.data( 'field', 'secondary' ).addClass( 'sf-secondary-field' );
				$items = $items.add( $secondField );
			}

			var charactersLength = getLength();

			var maxLength = maxLengthClosure();
			
  		    // Update the status text
			function updateStatus()
			{
				var charactersLeft = maxCharacters - charactersLength;
				
				if ( charactersLeft < 0 ) 
					charactersLeft = 0;

				$items.last().next("div").html( charactersLeft + " " + settings.statusText );
			}

			function checkChars( $item ) {
				// Too many chars?
				if ( charactersLength >= maxCharacters ) {
					maxLength.save( $item );

					// Add the notifycation class when we have too many chars
					$items.addClass( settings.notificationClass );
					// Cut down the string
					$item.val( $item.val().substr( 0, maxLength.currentt( $item ) ) );
					// Show the alert dialog box, if its set to true
					showAlert();
				} else {
					maxLength.resett();

					// Remove the notification class
					if ( $item.hasClass( settings.notificationClass ) ) 
						$items.removeClass( settings.notificationClass );
				}

				if ( settings.status )
					updateStatus();
			}
						
			// Shows an alert msg
			function showAlert() 
			{
				if ( settings.showAlert )
					alert( settings.alertText );
			}

			// Check if the element is valid.
			function validateElement( $item ) 
			{
				var ret = false;
				
				if ( $item.is('textarea' ) ) {
					ret = true;
				} else if ( $item.filter( "input[type=text]" ) ) {
					ret = true;
				} else if ( $item.filter( "input[type=password]" ) ) {
					ret = true;
				}

				return ret;
			}

			function isTwitter()
			{
				return ( settings.twitterText && typeof twttr !== 'undefined' );
			}

			function getLength() {
				var message = '';
				var spaceLength = 1;
				var testUrl = 'https://socialflow.com/';

				maxCharacters = settings.maxCharacters;

				$items.each( function() {
					var $item = $(this);
					var val   = $item.val();
					var data  = $item.data( 'field' );

					message += val;

					if ( !isTwitter() ) 
						return;

					if ( 'secondary' == data && val.length )
						// remove hidden symbols
						maxCharacters -= spaceLength;

					if ( 'main' == data ) {
						// remove hidden symbols
						maxCharacters -= spaceLength + twttr.txt.getTweetLength( testUrl );

						if ( isImagePost() ) 
							// remove hidden symbols
							maxCharacters -= spaceLength + twttr.txt.getTweetLength( testUrl );
					}
				});				

				if ( !isTwitter() )
					return message.length;

				return twttr.txt.getTweetLength( message );
			}

			function isImagePost() {
				return ( $('#socialflow-compose').hasClass('sf-compose-attachment') || $('#sf_media_compose').prop( 'checked' ) );
			}

			function maxLengthClosure() {
				var data = {};
				var saved = false;

				function a() {
					return data;
				}

				a.save = function( $current ) {
					if ( true === saved ) 
						return;

					var error = '';
					var summ = 0;

					saved = true;

					$items.each( function() {
						var $item  = $(this);
						var length = $item.val().length;
						var field  = $item.data( 'field' );

						// error set if long keep some key
						if ( charactersLength > maxCharacters && field == $current.data( 'field' ) ) {
							error = field;
						};

						data[ field ] = length;
					});

					if ( !error )
						return;

					$.each( data, function( i, val ) {
						if ( i == error ) 
							return;

						summ += val;
					});

					data[ error ] = maxCharacters - summ;
				}

				a.resett = function() {
					saved = false;
				}

				a.currentt = function( $item ) {
					return data[ $item.data( 'field' ) ];
				}

				return a;
			}

			/**
			 * Disable secondary field if it is empty and 1 or less sybols left
			 */
			function secondFieldActions( $item ) {
				if ( 'secondary' == $item.data( 'field' ) )
					return;

				var $sec = $items.filter('.sf-secondary-field');

				if ( $sec.val().length > 0 )
					return;

				if ( charactersLength + 1 >= maxCharacters ) {
					$sec.prop( 'disabled', true ).addClass( 'sf-disabled' );
				} else {
					$sec.prop( 'disabled', false ).removeClass( 'sf-disabled' );;
				}
			}
			
			// Loop through the events and bind them to the element
			$.each(settings.events, function ( i, n ) {
				$items.bind( n, function (e) {
					charactersLength = getLength();
					checkChars( $(this) );
					secondFieldActions( $(this) );
				});
			});

			// Insert the status div
			if ( settings.status ) 
			{
				$items.last().after( $( "<div/>" ).addClass( settings.statusClass ).html( '-' ) );
				updateStatus();

				$items.each( function (e) {
					checkChars( $(this) );
					secondFieldActions( $(this) );
				});
			}

			// Remove the status div
			if ( !settings.status ) 
			{
				var removeThisDiv = $items.last().next( "div." + settings.statusClass );
				
				if( removeThisDiv ) 
					removeThisDiv.remove();
			}
		});
	};
})(jQuery);