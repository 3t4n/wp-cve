(function() {  
    tinymce.create('tinymce.plugins.vimeo', {  
        init : function(ed, url) {  
            ed.addButton('vimeo', {  
                title : 'Add a vimeo',  
                image : url+'/icons/vimeo.png',  
                onclick : function() {  
                    ed.selection.setContent('[vimeo]http://vimeo.com/123456789[/vimeo]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('vimeo', tinymce.plugins.vimeo);  
})();

