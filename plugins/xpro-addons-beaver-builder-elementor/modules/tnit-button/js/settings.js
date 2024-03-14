(function($){
	"use strict";

	FLBuilder.registerModuleHelper(
		'tnit-button',
		{

			init: function()
		{
				var form      = $( '.fl-builder-settings' ),
				icon_position = form.find( 'select[name=icon_position]' ),
				icon_style    = form.find( 'select[name=icon_style]' );

				this._toggleIconBgStyle();
				icon_style.on( 'change', this._toggleIconBgStyle );
				icon_position.on( 'change', this._toggleIconBgStyle );
			},

			_toggleIconBgStyle: function() {
				var form      = $( '.fl-builder-settings' ),
				icon_position = form.find( 'select[name=icon_position]' ).val(),
				icon_style    = form.find( 'select[name=icon_style]' ).val();

				form.find( '#fl-field-icon_border_color' ).hide();

				if ( (icon_position === 'outer_left' || icon_position === 'outer_right') && icon_style !== 'custom' ) {
					form.find( '#fl-field-icon_border_color' ).show();
				}

				if ( (icon_position === 'before' || icon_position === 'after') && icon_style !== 'custom' ) {
					form.find( '#fl-field-icon_border_hover_color' ).hide();
				} else {
					form.find( '#fl-field-icon_border_hover_color' ).show();
				}
			},

		}
	);

})( jQuery );
