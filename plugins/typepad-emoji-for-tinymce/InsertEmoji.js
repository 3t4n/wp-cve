function init() {
	if (tinymce.isIE) {
		tinymce.get('content').focus();
		tinymce.activeEditor.windowManager.bookmark = tinyMCE.activeEditor.selection.getBookmark();
	}
	tinyMCEPopup.resizeToInnerSize();
}

function emojiinsert(line, icon) {

	var icontag;
	icontag = '<img src="'+tpademojidomain+'icons/'+line+'/'+icon+'" width="16" height="16" style="margin-left:3px; margin-right:3px; vertical-align:middle;">';

	if (parent.tinymce.isIE) {
		parent.tinymce.activeEditor.focus();
		parent.tinymce.activeEditor.selection.moveToBookmark(parent.tinymce.EditorManager.activeEditor.windowManager.bookmark);
	}

//	window.tinymce.execInstanceCommand('content', 'mceInsertContent', false, icontag);
	if ('function' === typeof window.tinymce.EditorCommands.execCommand) {
		// TinyMCE 3.x 対応コマンド
		window.tinymce.EditorCommands.execCommand('content', 'mceInsertContent', false, icontag);
	} else {
		// TinyMCE 4.x 対応コマンド
		tinymce.execCommand('mceInsertContent', false, icontag);
	}

	tinyMCEPopup.editor.execCommand('mceRepaint');
	tinyMCEPopup.close();
	return;
}
