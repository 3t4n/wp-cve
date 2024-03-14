( function() {
	tinymce.create( 'tinymce.plugins.cognito_mce_plugin', {
		getInfo: function() {
			return {
				longname: 'Cognito Forms',
				author: 'cognitoapps',
				authorurl: 'https://cognitoforms.com',
				infourl: 'https://cognitoforms.com',
				version: "2.0",
			};
		},
		init: function( editor, url ) {
			editor.addCommand( 'cognito_embed_window', function() {
				editor.windowManager.open( {
					title: 'Cognito Forms',
					url: ajaxurl + '?action=cognito_tinymce_dialog',
					width: 500,
					height: 500,
				} );
			} );

			editor.addButton( 'cognito', {
				title: 'Cognito Forms',
				cmd: 'cognito_embed_window',
				image: url + '/cogicon.png',
			} );
		},
	} );

	tinymce.PluginManager.add( 'cognito_mce_plugin', tinymce.plugins.cognito_mce_plugin );
} )();

