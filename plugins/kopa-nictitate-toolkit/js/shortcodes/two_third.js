(function() {  
    tinymce.create('tinymce.plugins.two_third', {  
        init : function(ed, url) {  
            ed.addButton('two_third', {  
                title : 'Add a two_third column',  
                image : url+'/icons/two_third.png',  
                onclick : function() {  
                    ed.selection.setContent('[two_third last="no"]'+ed.selection.getContent()+'[/two_third]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('two_third', tinymce.plugins.two_third);  
})();
