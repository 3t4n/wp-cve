(function($){
	"use strict";

	FLBuilder.registerModuleHelper(
		'tnit-adv-infobox',
		{

			init: function()
		{
				var form         = $( '.fl-builder-settings' ),
				image_type       = form.find( 'select[name=image_type]' ),
				imgicon_position = form.find( 'select[name=imgicon_position]' );

				this._toggleImagePosition();
				this._changePlaceholder();

				image_type.on( 'change', $.proxy( this._toggleImagePosition, this ) );
				imgicon_position.on( 'change', $.proxy( this._toggleImagePosition, this ) );
				imgicon_position.on( 'change', $.proxy( this._changePlaceholder, this ) );
			},

			_toggleImagePosition: function() {
				var form         = $( '.fl-builder-settings' ),
				image_type       = form.find( 'select[name=image_type]' ).val(),
				imgicon_position = form.find( 'select[name=imgicon_position]' ).val();

				if ( image_type === 'none' ) {
					form.find( '#fl-field-imgicon_ver_alignment' ).hide();
					form.find( '#fl-field-mobile_structure' ).hide();
				} else if ( image_type !== 'none' ) {
					if ( imgicon_position !== 'top' ) {
						form.find( '#fl-field-imgicon_ver_alignment' ).show();
						form.find( '#fl-field-mobile_structure' ).show();
					}

				}
			},

			_changePlaceholder: function() {
				var form         = $( '.fl-builder-settings' ),
				imgicon_position = form.find( 'select[name=imgicon_position]' ).val();

				form.find( 'input[name=imgicon_margin_right]' ).attr( 'placeholder','0' );
				form.find( 'input[name=imgicon_margin_left]' ).attr( 'placeholder','0' );
				form.find( 'input[name=imgicon_margin_bottom]' ).attr( 'placeholder','0' );

				if ( imgicon_position === 'top' ) {
					form.find( 'input[name=imgicon_margin_bottom]' ).attr( 'placeholder','20' );
				} else if ( imgicon_position === 'left' ) {
					form.find( 'input[name=imgicon_margin_right]' ).attr( 'placeholder','20' );
				} else if ( imgicon_position === 'right' ) {
					form.find( 'input[name=imgicon_margin_left]' ).attr( 'placeholder','20' );
				}
			},

		}
	);

})( jQuery );
