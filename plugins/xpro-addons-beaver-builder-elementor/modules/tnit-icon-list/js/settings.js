( function( $ ) {

	var icon_bg_style_global = '';

	FLBuilder.registerModuleHelper(
		'tnit-icon-list',
		{

			init: function()
		{
				var form      = $( '.fl-builder-settings' ),
				icon_bg_style = form.find( 'select[name=icon_bg_style]' );

				this._toggleBackgroundStyle();

				icon_bg_style.on( 'change', $.proxy( this._toggleBackgroundStyle, this ) );

			},

			_toggleBackgroundStyle: function()
		{
				var form      = $( '.fl-builder-settings' ),
				icon_bg_style = form.find( 'select[name=icon_bg_style]' ).val();

				icon_bg_style_global = icon_bg_style;
			},
		}
	);

	FLBuilder.registerModuleHelper(
		'icon_list_form',
		{

			init: function()
		{
				var form = $( '.fl-form-field-settings .fl-builder-settings' );

				form.find( '#fl-field-icon_bg_color' ).hide();
				form.find( '#fl-field-icon_bg_hvr_color' ).hide();
				form.find( '#fl-field-icon_border_color' ).hide();
				form.find( '#fl-field-icon_border_hvr_color' ).hide();

				if ( icon_bg_style_global === 'circle' || icon_bg_style_global === 'square' || icon_bg_style_global === 'custom' ) {
					form.find( '#fl-field-icon_bg_color' ).show();
					form.find( '#fl-field-icon_bg_hvr_color' ).show();
				}

				if ( icon_bg_style_global === 'custom' ) {
					form.find( '#fl-field-icon_border_color' ).show();
					form.find( '#fl-field-icon_border_hvr_color' ).show();
				}

			},

		}
	);

})( jQuery );
