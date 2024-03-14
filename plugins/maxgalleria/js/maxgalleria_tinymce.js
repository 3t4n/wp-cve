(function() {
    tinymce.PluginManager.add('maxgalleria_tinymce', function( editor, url ) {
        editor.addButton( 'maxgalleria_tinymce', {
            title : 'Insert MaxGalleria',
            image : mg_promo.pluginurl + '/images/mg_32x32.png',
            onclick: function() {
              tb_show("", "#TB_inline?width=640&inlineId=select-maxgallery-container&width=753&height=273", "");                
            }
        });
    });
})();
