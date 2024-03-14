(function ($)
{
	'use strict';

	var WP_Adminify_Error_Logs = {

		// AJAX request: Clear Log Data
		Error_Refresh: function() {

			$( '#adminify_error_log_refresh' ).on(
				'click',
				function (e) {
					e.preventDefault();

					var tempLabel = $( '#adminify_error_log_refresh' ).html();

					$.ajax(
						{
							type: 'POST',
							dataType: 'text',
							async:false,
							url: WPAdminify_ErrorL.ajax_url,
							data: {
								action: 'jltwp_adminify_error_log_content_refresh',
								security: WPAdminify_ErrorL.security_nonce,
								command: 'refresh_error_log',
							},
							beforeSend: function () {
								$( '#adminify_error_log_refresh' ).text( WPAdminify_ErrorL.label_update );
							},
							cache: false,
							success: function (data){
								var refresh_content = $.parseJSON( data );
								// Refresh the textarea content
								$( "textarea#adminify_error_log_area" ).val( refresh_content.file_content );
								// // Change the button text
								$( '#adminify_error_log_refresh' ).html( WPAdminify_ErrorL.label_done );

								// // Set button text to start text
								setTimeout( function () { $( '#adminify_error_log_refresh' ).html( tempLabel ); }, 1500 );
							},
							error: function (data){
								$( '#adminify_error_log_refresh' ).html( 'ERROR' );
							}
						}
					);

				}
			);
		},

		// AJAX request: Clear Log Data
		Error_Clear_Logs: function(){

			$( '#adminify_error_log_clear' ).on(
				'click',
				function (e) {
					e.preventDefault();

					var tempLabel = $( '#adminify_error_log_clear' ).html();

					$.ajax(
						{
							type: 'POST',
							dataType: 'text',
							async:false,
							url: WPAdminify_ErrorL.ajax_url,
							data: {
								action: 'jltwp_adminify_error_log_content_clear',
								security: WPAdminify_ErrorL.security_nonce,
								command: 'clear_error_log',
							},
							beforeSend: function () {
								// Clearing button text
								$( '#adminify_error_log_clear' ).html( WPAdminify_ErrorL.label_clear );
							},
							cache: false,
							success: function (data) {
								// Refresh the textarea content
								$( "textarea#adminify_error_log_area" ).val( data.file_content );

								// Change the button text
								$( '#adminify_error_log_clear' ).html( WPAdminify_ErrorL.label_done );

								// Set button text to start text
								setTimeout( function () { $( '#adminify_error_log_clear' ).html( tempLabel ); }, 1500 );
							},
							error: function (data) {
								$( '#adminify_error_log_clear' ).html( 'ERROR' );
							}
						}
					);

				}
			);
		}
	}

	// Documents Loaded
	$( document ).ready(
		function() {
			WP_Adminify_Error_Logs.Error_Refresh();
			WP_Adminify_Error_Logs.Error_Clear_Logs();
		}
	);

})( jQuery );
