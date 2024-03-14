(function( $ ) {

	'use strict';

	var api = wp.customize;

	$(function(){
		
		/* Customizer preview  */
		$( '.lce-preview-button' ).on( 'click', function() {
			wp.customize.previewer.refresh();
		} );

		/* Customizer code editor */
		ace.require( 'ace/ext/language_tools' );
		
		$( '.lce-code-editor' ).each( function() {
			
			var editDiv 		= $( this ),
				editMode		= editDiv.data( 'mode' ),
				editTextarea 	= editDiv.siblings( 'textarea' ),
				editor 			= ace.edit( editDiv[0] ),
				themeDiv        = $( '#customize-control-live_code_theme select' ),
				theme           = themeDiv.val();
			
			editTextarea.hide();
			editor.$blockScrolling = Infinity;
			editor.renderer.setShowGutter( false );
			editor.getSession().setValue( editTextarea.val() );
			editor.setTheme( 'ace/theme/' +theme );
			editor.getSession().setMode( 'ace/mode/' + editMode );

			themeDiv.on( 'change' , function() {
				var theme = $( this ).val();
				editor.setTheme( 'ace/theme/' +theme );
			});
			
			editor.setOptions({
		        enableBasicAutocompletion: true,
		        enableLiveAutocompletion: true,
		        enableSnippets: false
		    });
			
			editor.getSession().on( 'change' , function( e ) {
				editTextarea.val( editor.getSession().getValue() ).trigger( 'change' );
			});
		} );

	});

})( jQuery );