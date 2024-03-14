(function($){
	"use strict";

	var progress_color_type_global = '';

	FLBuilder.registerModuleHelper(
		'tnit-progress-bar',
		{

			init: function()
		{
				var form            = $( '.fl-builder-settings' ),
				progress_color_type	= form.find( 'select[name=progress_color_type]' );
				this._changeProgressColorType();
				progress_color_type.on( 'change', this._changeProgressColorType );
			},

			_changeProgressColorType: function() {
				var form            = $( '.fl-builder-settings' ),
				progress_color_type	= form.find( 'select[name=progress_color_type]' ).val();

				progress_color_type_global = progress_color_type;
			},

		}
	);

	FLBuilder.registerModuleHelper(
		'progressbar_form',
		{

			init: function()
		{
				var form = $( '.fl-form-field-settings' );

				form.find( '#fl-field-progress_color' ).hide();
				form.find( '#fl-field-progress_gradient' ).hide();

				if ('color' === progress_color_type_global) {
					form.find( '#fl-field-progress_color' ).show();
				} else if ('gradient' === progress_color_type_global) {
					form.find( '#fl-field-progress_gradient' ).show();
				}
			},

		}
	);

})( jQuery );
