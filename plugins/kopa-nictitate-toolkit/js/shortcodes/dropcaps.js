(function() {  
    tinymce.create('tinymce.plugins.dropcaps', {  
        init : function(ed, url) {  
            ed.addButton('dropcaps', {  
                title : 'Add a dropcaps',  
                image : url+'/icons/dropcaps.png',  
                onclick : function() {  
                    ed.selection.setContent('[dropcaps round="no"]'+ed.selection.getContent()+'[/dropcaps]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('dropcaps', tinymce.plugins.dropcaps);  
})();

