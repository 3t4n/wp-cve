jQuery( document ).ready( function($) {
	
	// Initilise html code editor
	if( $('.ecsnippets-html-editor').length ) {
		var editorCssSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
		editorCssSettings.codemirror = _.extend(
			{},
			editorCssSettings.codemirror,
			{
				indentUnit: 2,
				tabSize: 2,
				mode: 'htmlmixed',
			}
		);

		$('.ecsnippets-html-editor').each( function() {
			var editor = wp.codeEditor.initialize( $(this), editorCssSettings );
		} );
	}
	
} );