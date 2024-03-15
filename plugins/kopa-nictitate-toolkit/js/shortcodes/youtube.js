(function() {  
    tinymce.create('tinymce.plugins.youtube', {  
        init : function(ed, url) {  
            ed.addButton('youtube', {  
                title : 'Add a youtube',  
                image : url+'/icons/youtube.png',  
                onclick : function() {  
                    ed.selection.setContent('[youtube]http://www.youtube.com/watch?v=ABCDEFGH[/youtube]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('youtube', tinymce.plugins.youtube);  
})();

