/**
 * plugin.js
 *
 * Released under LGPL License.
 * Copyright (c) 1999-2015 Ephox Corp. All rights reserved
 *
 * License: http://www.tinymce.com/license
 * Contributing: http://www.tinymce.com/contributing
 */

/*global tinymce:true */

tinymce.PluginManager.add('ippplugin', function(editor, url) {
	editor.addCommand('InsertIPushPullPage', function() {

		editor.windowManager.open({
			title: 'ipushpull Shortcode Generator',
			file : url + '/dialog.php?url='+url,
			width : 800,
			height : 600,
			inline : 1
		},{
			editor: editor
		})

	});

	editor.addButton('ippplugin', {
		image: url + '/icon.png',
		tooltip: 'Insert ipushpull page',
		cmd: 'InsertIPushPullPage'
	});

	// editor.addMenuItem('myplugin', {
	// 	icon: 'hr',
	// 	text: 'Horizontal line',
	// 	cmd: 'InsertHorizontalRule',
	// 	context: 'insert'
	// });
});
