jQuery( function($) {

	var loading_step = 0;
	var total_steps  = 4;

	/**
	 * Handles the incrementation of the loading step and 
	 *
	 */
	function increment_loading_step() {

		loading_step++;

		// Increment the data-step
		$('#wpsbc-upgrader-loading-bar-wrapper').attr( 'data-step', loading_step );

		$('#wpsbc-upgrader-message-step-' + loading_step ).fadeOut( 200, function() {
			$('#wpsbc-upgrader-message-doing-step-' + loading_step ).fadeIn( 200 );
		} );

		
		$('#wpsbc-upgrader-message-doing-step-' + ( loading_step - 1 ) ).fadeOut( 200, function() {
			$('#wpsbc-upgrader-message-step-' + ( loading_step - 1 ) ).fadeIn( 200 );
		} );

	}


	/**
	 * Makes consecutive AJAX calls to migrate the old plugin data into the new format
	 *
	 */
	function migrate_data( token ) {

		if( typeof token == 'undefined' )
			return false;

		increment_loading_step();

		/**
		 * Finish migration if loading steps have been done
		 *
		 */
		if( loading_step - 1 == total_steps ) {

			$('#wpsbc-upgrader-button-start-upgrade').remove();
			$('#wpsbc-upgrader-button-continue').css( 'display', 'block' );

			return false;

		}

		/**
		 * Process migration step
		 *
		 */
		var action = '';

		if( loading_step == 1 )
			action = 'wpsbc_action_ajax_migrate_calendars';

		if( loading_step == 2 )
			action = 'wpsbc_action_ajax_migrate_bookings';

		if( loading_step == 3 )
			action = 'wpsbc_action_ajax_migrate_general_settings';

		if( loading_step == 4 )
			action = 'wpsbc_action_ajax_migrate_finishing_up';

		if( action == '' )
			return false;
		
		var data = {
			action : action,
			token  : token
		}

		$.post( ajaxurl, data, function( response ) {

			response = JSON.parse( response );

			if( response.success ) {

				setTimeout( function() {

					migrate_data( token );

				}, 1000 );

			}

		});
		
	}


	/**
	 * Starts the upgrade procedure
	 *
	 */
	$(document).on( 'click', '#wpsbc-upgrader-button-start-upgrade', function(e) {

		e.preventDefault();

		$this = $(this);

		if( $this.hasClass('disabled') )
			return false;

		// Disable the button
		$this.addClass('disabled');

		// Change the text of the button
		$this.find('span:first-of-type').hide();
		$this.find('span:nth-of-type(2)').show();

		// Add and remove the spinner next to the button
		$this.siblings( '.spinner' ).css( 'visibility', 'visible' );

		setTimeout( function() {
			$this.siblings( '.spinner' ).css( 'visibility', 'hidden' );
		}, 1200 );
		

		// Remove the copy text and show the loading bar
		$('#wpsbc-upgrader-content-inner').delay( 1200 ).fadeOut( 300, function() {

			$('#wpsbc-upgrader-loading-bar-wrapper').fadeIn( 300 );

		});

		// Remove the skip upgrader message
		$('#wpsbc-upgrader-skip-wrapper').fadeOut( 300 );

		// Get the token
		var token = $('#wpsbc_token').val();
		
		// Run the data migrator
		setTimeout( function() {

			migrate_data( token );
			
		}, 1200 );

	});

});