(function($){

	var background_style_global = '';

	/**
	 * Use this file to register a module helper that
	 * adds additional logic to the settings form. The
	 * method 'FLBuilder._registerModuleHelper' accepts
	 * two parameters, the module slug (same as the folder name)
	 * and an object containing the helper methods and properties.
	 */
	FLBuilder._registerModuleHelper(
		'tnit-social-icon',
		{

			/**
			 * The 'init' method is called by the builder when
			 * the settings form is opened.
			 *
			 * @method init
			 */
			init: function()
		{
				var form         = $( '.fl-builder-settings' ),
				background_style = form.find( 'select[name=social_icon_bg_style]' );

				this._toggleBackgroundStyle();

				background_style.on( 'change', $.proxy( this._toggleBackgroundStyle, this ) );

			},

			_toggleBackgroundStyle: function()
		{
				var form         = $( '.fl-builder-settings' ),
				background_style = form.find( 'select[name=social_icon_bg_style]' ).val();

				background_style_global = background_style;
			},
		}
	);

	/**
	 * Validate a nested form created with 'type' => 'form'.
	 */
	FLBuilder.registerModuleHelper(
		'tnit_social_icon_form',
		{

			init: function()
		{
				var form   = $( '.fl-form-field-settings .fl-builder-settings' ),
				image_type = form.find( 'select[name=image_type]' );

				this._toggleImageType();

				image_type.on( 'change', $.proxy( this._toggleImageType, this ) );
			},

			_toggleImageType: function()
		{
				var form   = $( '.fl-form-field-settings .fl-builder-settings' ),
				image_type = form.find( 'select[name=image_type]' ).val(),
				photo      = form.find( 'input[name=photo]' );

				form.find( '#fl-field-icon_color' ).hide();
				form.find( '#fl-field-icon_hvr_color' ).hide();
				form.find( '#fl-field-bg_color' ).hide();
				form.find( '#fl-field-bg_hvr_color' ).hide();
				form.find( '#fl-field-border_color' ).hide();
				form.find( '#fl-field-border_hvr_color' ).hide();
				form.find( '#fl-builder-settings-section-colors' ).show();
				form.find( '.fl-builder-settings-tabs a:nth-child(2)' ).css( "display", "inherit" );

				// Remove photo required.
				photo.rules( 'remove' );

				if ( image_type === 'icon' ) {
					form.find( '#fl-field-icon_color' ).show();
					form.find( '#fl-field-icon_hvr_color' ).show();
				}

				if ( image_type === 'photo' ) {
					// Add photo required.
					photo.rules( 'add', { required: true } );
				}

				if ( (background_style_global === 'circle' || background_style_global === 'square' || background_style_global === 'custom') && image_type === 'icon' ) {
					form.find( '#fl-field-bg_color' ).show();
					form.find( '#fl-field-bg_hvr_color' ).show();
				}

				if ( background_style_global === 'custom' ) {
					form.find( '#fl-field-border_color' ).show();
					form.find( '#fl-field-border_hvr_color' ).show();
				}

				if ( (background_style_global === 'simple' || background_style_global === 'circle' || background_style_global === 'square') && image_type === 'photo' ) {
					form.find( '#fl-builder-settings-section-colors' ).hide();
					form.find( '.fl-builder-settings-tabs a:nth-child(2)' ).css( "display", "none" );
				}
			}

		}
	);

})( jQuery );
