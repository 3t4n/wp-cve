(function() {  
    tinymce.create('tinymce.plugins.alert', {  
        init : function(ed, url) {  
            ed.addButton('alert', {  
                title : 'Add a alert box',  
                image : url+'/icons/alert.png',  
                onclick : function() {  
                    ed.selection.setContent('[alert type="e.g. block, error, success, info" title=""]'+ed.selection.getContent()+'[/alert]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('alert', tinymce.plugins.alert);  
})();

