(function() {  
    tinymce.create('tinymce.plugins.one_fourth', {  
        init : function(ed, url) {  
            ed.addButton('one_fourth', {  
                title : 'Add a one_fourth column',  
                image : url+'/icons/one_fourth.png',  
                onclick : function() {  
                    ed.selection.setContent('[one_fourth last="no"]'+ed.selection.getContent()+'[/one_fourth]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('one_fourth', tinymce.plugins.one_fourth);  
})();
