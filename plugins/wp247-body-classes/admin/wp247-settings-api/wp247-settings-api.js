/*
 * wp247 Settings API Javascript to handle Tab Navigation
*/
jQuery( document ).ready( function($)
{

	// Initiate Color Picker
	$( '.wp247sapi-color-picker-field' ).wpColorPicker();

	// Switches option sections
	$( '.wp247sapi-form' ).hide();
	var active_tab_var = wp247sapi_plugin_slug + '_active_tab';
	var active_tab = '';
	if ( typeof( localStorage ) != 'undefined' )
	{
		active_tab = localStorage.getItem( active_tab_var );
	}
	if ( active_tab != '' && $( active_tab ).length > 0 )
	{
		$( active_tab ).fadeIn( 100 );
	}
	else
	{
		$( '.wp247sapi-form:first' ).fadeIn( 100 );
	}
	$( '.wp247sapi-form .collapsed' ).each(function()
	{
		$(this).find( 'input:checked' ).parent().parent().parent().nextAll().each(
		function()
		{
			if ( $(this).hasClass( 'last' ) )
			{
				$(this).removeClass( 'hidden' );
				return false;
			}
			$( this ).filter( '.hidden' ).removeClass( 'hidden' );
		});
	});

	if ( active_tab != '' && $( active_tab + '_tab' ).length > 0 )
	{
		$( active_tab + '_tab' ).addClass( 'nav-tab-active' );
	}
	else
	{
		$( '.nav-tab-wrapper a:first' ).addClass( 'nav-tab-active' );
	}
	$( '.nav-tab-wrapper a' ).click( function( evt )
	{
		$( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
		$( this ).addClass( 'nav-tab-active' ).blur();
		var active_form = $( this ).attr( 'href' );
		if ( typeof( localStorage)  != 'undefined' )
		{
			localStorage.setItem( active_tab_var, $( this ).attr( 'href' ) );
		}
		$( '.wp247sapi-form' ).hide();
		$( active_form ).fadeIn( 100 );
		evt.preventDefault();
	} );

	var file_frame = null;
	$( '.wp247sapi-browse' ).on( 'click', function ( event ) {
		event.preventDefault();

		var self = $( this );

		// If the media frame already exists, reopen it.
		if ( file_frame )
		{
			file_frame.open();
			return false;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media( {
			title: self.data( 'uploader_title' ),
			button: {
				text: self.data( 'uploader_button_text' ),
			},
			multiple: false
		} );

		file_frame.on( 'select', function () {
			attachment = file_frame.state().get( 'selection' ).first().toJSON();

			self.prev( '.wp247sapi-url' ).val( attachment.url );
		} );

		// Finally, open the modal
		file_frame.open();
	} );

	// Sticky Infobar
	$( window ).resize( function() {
		$('.wp247sapi-infobar').css('height','');
		var hc = $('.wp247sapi-content').height();
		var hi = $('.wp247sapi-infobar').height();
		if ( hc - hi - 20 > hi )
		{
			$('.wp247sapi-infobar').css('height', ( hc - hi - 20 ) + 'px');
		}
	});
	$( window ).resize();

	$('.nav-tab').click( function()
	{
		$( window ).resize();
	});

} );