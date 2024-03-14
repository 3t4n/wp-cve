'use strict';

if ( ! window.ACADPLoadScript ) {
	// Load script files.
	var ACADPLoadScript = ( url, type = null ) => {
		return new Promise(( resolve, reject ) => { 
			const filename = url.substring( url.lastIndexOf( '/' ) + 1, url.lastIndexOf( '.' ) );
			const id = 'acadp-script-' + filename;

			if ( document.querySelector( '#' + id ) !== null ) {
				resolve();
				return false;
			}

			const script = document.createElement( 'script' );

			script.id    = id;
			script.src   = url;
			script.defer = true;

			if ( type !== null ) {
				script.type = type;	
			}		

			script.onload  = () => resolve();
			script.onerror = () => reject();

			document.body.appendChild( script );
		});
	}
}

if ( ! window.ACADPMediaUploader ) {
	// Upload files.
	var ACADPMediaUploader = ( callback ) => { 
		let fileFrame, json;

		// If an instance of fileFrame already exists, then we can open it rather than creating a new instance.
		if ( undefined !== fileFrame ) { 
			fileFrame.open();
			return false; 
		}

		// Here, use the wp.media library to define the settings of the media uploader.
		fileFrame = wp.media.frames.file_frame = wp.media({
			frame: 'post',
			state: 'insert',
			multiple: false
		});

		// Setup an event handler for what to do when an image has been selected.
		fileFrame.on( 'insert', function() { 
			// Read the JSON data returned from the media uploader.
			json = fileFrame.state().get( 'selection' ).first().toJSON();
		
			// First, make sure that we have the URL of an image to display.
			if ( json.url.trim().length === 0 ) {
				return false;
			}
		
			callback( json ); 
		});

		// Now display the actual fileFrame.
		fileFrame.open(); 
	}
}

(function( $ ) {				

	// Init color picker for widgets.
	const initWidgetColorPicker = ( widget ) => {
		widget.find( '.acadp-form-control-color-picker' ).wpColorPicker( {
			change: _.throttle( function() { // For Customizer
				$( this ).trigger( 'change' );
			}, 3000 )
		});
	}
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {			
		
		// Load the required script files.
		document.querySelectorAll( '.acadp-require-js' ).forEach(( el ) => {
			const script = el.dataset.script;			
			ACADPLoadScript( acadp_admin.plugin_url + 'admin/assets/js/' + script + '.js' );			
		}); 
		
		ACADPLoadScript( acadp_admin.plugin_url + 'public/assets/js/select.js', 'module' );
		
		// Init color picker
		if ( $.fn.wpColorPicker ) {
			document.querySelectorAll( '.acadp-form-control-color-picker' ).forEach(( el ) => {
				$( el ).wpColorPicker();
			});
	
			$( document ).on( 'widget-added widget-updated', ( event, widget ) => {
				initWidgetColorPicker( widget );
			});
		}
		
	});

})( jQuery );
