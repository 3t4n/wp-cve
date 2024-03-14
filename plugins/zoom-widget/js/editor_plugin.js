(function() {
    tinymce.create('tinymce.plugins.Spider_Zoom_mce', {
 
        init : function(ed, url){
			
			ed.addCommand('mceSpider_Zoom_mce', function() {
				ed.execCommand('mceInsertContent', 0,'[Web-Dorado_Zoom]');
			});
            ed.addButton('Spider_Zoom_mce', {
            title : 'Insert Zoom',
			cmd : 'mceSpider_Zoom_mce',
            });
        }
    });
 
    tinymce.PluginManager.add('Spider_Zoom_mce', tinymce.plugins.Spider_Zoom_mce);
 
})();