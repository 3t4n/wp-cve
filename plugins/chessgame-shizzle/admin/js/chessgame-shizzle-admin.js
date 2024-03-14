/*
 * JavaScript for Chessgame Shizzle Admin.
 */


/*
 * Edit Post. Toggle metabox with help info.
 *
 * @since 1.0.0
 */
jQuery(document).ready(function($) {
	jQuery( "span.cs_chessgame_admin_help_header" ).on( 'click', function() {
		jQuery(this).css( 'display', 'none' );

		var cs_chessgame_meta = jQuery(this).parent();
		jQuery( cs_chessgame_meta ).find('div.cs_chessgame_admin_help_inside').slideDown(500);

		return false;
	});
});


/*
 * Import Page, enable/disable submit button.
 *
 * @since 1.0.0
 */
jQuery(document).ready(function($) {

	/* Checking checkbox will enable the submit button for PGN-file */
	jQuery("input#start_import_cs_file").on('change', function() {
		if ( jQuery(this).val() ) {
			jQuery("#start_import_cs").addClass( 'button-primary' );
			jQuery("#start_import_cs").prop('disabled', false);
		} else {
			jQuery("#start_import_cs").removeClass( 'button-primary' );
			jQuery("#start_import_cs").prop('disabled', true);
		}
	});

});


/*
 * Settings Page, select the right tab.
 *
 * @since 1.0.0
 */
jQuery(document).ready(function($) {

	/* Select the right tab on the options page */
	jQuery( '.cs-nav-tab-wrapper a' ).on('click', function() {
		jQuery( 'form.cs_tab_options' ).removeClass( 'active' );
		jQuery( '.cs-nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );

		var rel = jQuery( this ).attr('rel');
		jQuery( '.' + rel ).addClass( 'active' );
		jQuery( this ).addClass( 'nav-tab-active' );

		return false;
	});

});


/*
 * Post metabox.
 * Select a result from a dropdown.
 *
 * @since 1.1.2
 */
jQuery(document).ready(function(){
	jQuery( "select.cs_result_ajax" ).on('change', function ( el ) {
		var result = jQuery( "option:selected", this ).val();

		if ( result !== '' ) {
			jQuery( 'input#cs_chessgame_result' ).val( result );
		}
	});
});




/*
 * Export Page, download games in pgn files in parts.
 *
 * @since 1.1.8
 */
jQuery(document).ready(function($) {

	/* Checking checkbox will enable the submit button */
	jQuery("input#start_export_enable").prop("checked", false); // init
	jQuery("input.chessgame_shizzle_export_part").val( 1 ); // init

	jQuery("input#start_export_enable").on('change', function() {
		var checked = jQuery( "input#start_export_enable" ).prop('checked');
		if ( checked == true ) {
			jQuery(".chessgame_shizzle_start_export").addClass( 'button-primary' );
			jQuery(".chessgame_shizzle_start_export").prop('disabled', false);
		} else {
			jQuery(".chessgame_shizzle_start_export").removeClass( 'button-primary' );
			jQuery(".chessgame_shizzle_start_export").prop('disabled', true);
		}
	});


	/* Click Event, submit the form through AJAX and receive a CSV-file.
	 * Will request multi part files, every 5 seconds to be easy on the webserver.
	 */
	jQuery( 'input.chessgame_shizzle_start_export' ).on( 'click', function(event) {

		if ( jQuery(".chessgame_shizzle_start_export").prop('disabled') ) {
			// Not sure if this block is needed... Just in case.
			return;
		}

		// Reset back to initial state.
		jQuery( "input.chessgame_shizzle_start_export" ).removeClass( 'button-primary' );
		jQuery( "input.chessgame_shizzle_start_export" ).prop( 'disabled', true );
		jQuery( "input#start_export_enable" ).prop( 'checked', false );
		// Show that we are busy.
		jQuery( ".chessgame_shizzle_export_gif" ).css( 'visibility', 'visible' );

		var parts = parseFloat( jQuery("input.chessgame_shizzle_export_parts").val() );

		for ( var part = 1; part < (parts + 1); part++ ) {
			var timeout = (part - 1) * 10000;
			chessgame_shizzle_export_part( part, timeout );
		}

		setTimeout(
			function() {
				jQuery( ".chessgame_shizzle_export_gif" ).css( 'visibility', 'hidden' );
			}, ( (part - 1)  * 10000 )
		);

		event.preventDefault();
	});

	/* Do the Submit Event. */
	function chessgame_shizzle_export_part( part, timeout ) {
		setTimeout(
			function() {
				jQuery('.chessgame_shizzle_export_part').val( part );
				var form = jQuery('form#chessgame_shizzle_export');
				form.trigger('submit');
			}, ( timeout )
		);
	}

});
