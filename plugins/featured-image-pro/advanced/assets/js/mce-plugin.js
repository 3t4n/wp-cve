tinymce.PluginManager.add('featured_image_pro', function( editor, url ) {
    console.log('foo');
    init: 
    editor.on('Change', function(e) {
        var content = editor.getContent();
        console.log(content);
        contentm = content.replace('featured_image_pro', 'foo');
        editor.setContent(contentm);
    });
});
