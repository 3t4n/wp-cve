(function($){
	"use strict";

	var hover_card_style_global = '';

	FLBuilder.registerModuleHelper(
		'tnit-hover-card',
		{

			init: function()
		{
				var form         = $( '.fl-builder-settings' ),
				hover_card_style = form.find( 'select[name=hover_card_style]' );

				hover_card_style_global = hover_card_style.val();

				// Init validation events.
				this._get_hover_card_style();

				hover_card_style.on( 'change', $.proxy( this._get_hover_card_style, this ) );
			},

			_get_hover_card_style: function() {

				var form         = $( '.fl-builder-settings' ),
				hover_card_style = form.find( 'select[name=hover_card_style]' ).val();

				hover_card_style_global = hover_card_style;

				if ( hover_card_style === 'style-12' ) {
					// Add arrows offset placeholder
					form.find( '#fl-field-hover_card_box_padding input[name=hover_card_box_padding_top]' ).attr( 'placeholder','35' );
					form.find( '#fl-field-hover_card_box_padding input[name=hover_card_box_padding_right]' ).attr( 'placeholder','28' );
					form.find( '#fl-field-hover_card_box_padding input[name=hover_card_box_padding_bottom]' ).attr( 'placeholder','35' );
					form.find( '#fl-field-hover_card_box_padding input[name=hover_card_box_padding_left]' ).attr( 'placeholder','28' );
				} else {
					form.find( '#fl-field-hover_card_box_padding input[name=hover_card_box_padding_top]' ).attr( 'placeholder','20' );
					form.find( '#fl-field-hover_card_box_padding input[name=hover_card_box_padding_right]' ).attr( 'placeholder','35' );
					form.find( '#fl-field-hover_card_box_padding input[name=hover_card_box_padding_bottom]' ).attr( 'placeholder','20' );
					form.find( '#fl-field-hover_card_box_padding input[name=hover_card_box_padding_left]' ).attr( 'placeholder','35' );
				}
			},

		}
	);

	FLBuilder.registerModuleHelper(
		'hover_card_form',
		{

			init: function()
		{
				var form = $( '.fl-builder-settings' );

				// Init validation events.
				this._hover_seprator_tab_hide();
			},

			_hover_seprator_tab_hide: function() {

				var form = $( '.fl-builder-settings' );

				form.find( 'a[href="#fl-builder-settings-tab-seprator_tab"]' ).hide();

				if ( hover_card_style_global === 'style-9' || hover_card_style_global === 'style-11' || hover_card_style_global === 'style-12' ) {

					form.find( 'a[href="#fl-builder-settings-tab-seprator_tab"]' ).show();
				}

				if ( hover_card_style_global === 'style-9') {

					form.find( '#fl-field-separator_height' ).hide();
					form.find( '#fl-field-separator_width' ).hide();
					form.find( '#fl-field-separator_margin' ).hide();
				}

				if ( hover_card_style_global === 'style-10') {

					form.find( '#fl-builder-settings-section-image_overlay' ).hide();
				}

				if ( hover_card_style_global === 'style-11') {

					form.find( '#fl-field-separator_height' ).hide();
				}

				if ( hover_card_style_global === 'style-12') {

					form.find( '#fl-field-separator_width' ).hide();
				}

				if ( hover_card_style_global === 'style-11') {
					form.find( '#fl-field-separator_margin input[name=separator_margin_top]' ).attr( 'placeholder','5' );
					form.find( '#fl-field-separator_margin input[name=separator_margin_right]' ).attr( 'placeholder','auto' );
					form.find( '#fl-field-separator_margin input[name=separator_margin_bottom]' ).attr( 'placeholder','5' );
					form.find( '#fl-field-separator_margin input[name=separator_margin_left]' ).attr( 'placeholder','auto' );
				} else if ( hover_card_style_global === 'style-12') {
					form.find( '#fl-field-separator_margin input[name=separator_margin_top]' ).attr( 'placeholder','0' );
					form.find( '#fl-field-separator_margin input[name=separator_margin_right]' ).attr( 'placeholder','0' );
					form.find( '#fl-field-separator_margin input[name=separator_margin_bottom]' ).attr( 'placeholder','0' );
					form.find( '#fl-field-separator_margin input[name=separator_margin_left]' ).attr( 'placeholder','0' );
				} else {
					form.find( '#fl-field-separator_margin input[name=separator_margin_top]' ).attr( 'placeholder','5' );
					form.find( '#fl-field-separator_margin input[name=separator_margin_right]' ).attr( 'placeholder','0' );
					form.find( '#fl-field-separator_margin input[name=separator_margin_bottom]' ).attr( 'placeholder','5' );
					form.find( '#fl-field-separator_margin input[name=separator_margin_left]' ).attr( 'placeholder','0' );
				}

			},

		}
	);

})( jQuery );
