(function() {  
    tinymce.create('tinymce.plugins.button', {  
        init : function(ed, url) {  
            ed.addButton('button', {  
                title : 'Add a button',  
                image : url+'/icons/button.png',  
                onclick : function() {  
                    ed.selection.setContent('[button size="e.g. small, medium, big" link="" target=""]'+ed.selection.getContent()+'[/button]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('button', tinymce.plugins.button);  
})();

