(function (global, $) {
	"use strict";
	$(function () {
		
		// Wordpress Color Picker Control Init
		$('.jf-scs-color-picker').wpColorPicker();


		// ACE CSS Editor Init
		var editor,
		syncCSS = function() {
			$( '#jf_scs_custom_css_field_textarea' ).val( editor.getSession().getValue() );
		},
		loadAce = function() {
			editor = ace.edit( 'jf_scs_custom_css_field' );
			global.safecss_editor = editor;
			editor.getSession().setUseWrapMode( true );
			editor.setShowPrintMargin( false );
			editor.getSession().setValue( $( '#jf_scs_custom_css_field_textarea' ).val() );
			editor.getSession().setMode("ace/mode/css");
			jQuery.fn.spin&&$( '#jf_scs_custom_css_field_container' ).spin( false );
			$( 'form' ).submit( syncCSS );
		};
		
		if ( $.browser.msie&&parseInt( $.browser.version, 10 ) <= 7 ) {
			$( '#jf_scs_custom_css_field_container' ).hide();
			$( '#jf_scs_custom_css_field_textarea' ).show();
			return false;
		} else {
			$( global ).load( loadAce );
		}
		global.aceSyncCSS = syncCSS;

	});
}(this,jQuery));