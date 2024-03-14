jQuery( document ).on( 'tinymce-editor-setup', function( event, editor ) {
    // have to start with , at beginning!
    editor.settings.toolbar1 += ',strikethrough,blockquote,hr,alignleft,aligncenter,alignright,link,undo,redo,formatselect,fontsizeselect';
});