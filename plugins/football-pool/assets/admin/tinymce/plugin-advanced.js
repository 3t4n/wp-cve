/**
 * @preserve Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

tinymce.PluginManager.add( 'footballpool', function( editor, url ) {
	// Add a button that opens a window
	editor.addButton( 'footballpool', {
		// text: FootballPoolTinyMCE.text,
		icon: false,
		image: url + '/footballpool-tinymce-16.png',
		tooltip: FootballPoolTinyMCE.tooltip,
		onclick: function() {
			// Open window
			editor.windowManager.open( {
				title: FootballPoolTinyMCE.text,
				url: url + '/tinymce-dialog.php',
				width: get_dimension( jQuery( window ).width(), 600 ),
				height: get_dimension( jQuery( window ).height(), 350 ),
				buttons: [ {
					text: FootballPoolTinyMCE.button_text,
					classes: 'primary',
					onclick: function( e ) {
						// A callback to the get_shortcode() function is set as a property of activeEditor in
						// the dialog window so we can use it here.
						let content = tinymce.activeEditor.get_shortcode();
						// todo: replace execCommand with clipboard API because it is deprecated
						//       https://developer.mozilla.org/en-US/docs/Web/API/Clipboard_API
						//       Or move to blocks and remove the tinymce extension.
						tinymce.activeEditor.execCommand( 'mceInsertContent', false, content );
						
						// Close the dialog
						top.tinymce.activeEditor.windowManager.close();
					}
				}, {
					text: FootballPoolAjax.cancel_button,
					onclick: 'close'
				} ]
			} );
		}
	} );
	
	function get_dimension( dim, max ) {
		let d = dim * .8;
		return ( d > max ) ? max : d;
	}
	
	return {
		// Return the getMetadata object for the help plugin. Not really needed, but hey, it was in the example plugin :P
		getMetadata: function () {
			return	{
				title: "Football Pool shortcodes",
				url: "https://wordpress.org/plugins/football-pool/"
			};
		}
	};
} );
