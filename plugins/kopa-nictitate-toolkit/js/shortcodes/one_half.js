(function() {  
    tinymce.create('tinymce.plugins.one_half', {  
        init : function(ed, url) {  
            ed.addButton('one_half', {  
                title : 'Add a one_half column',  
                image : url+'/icons/one_half.png',  
                onclick : function() {  
                    ed.selection.setContent('[one_half last="no"]'+ed.selection.getContent()+'[/one_half]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('one_half', tinymce.plugins.one_half);  
})();
