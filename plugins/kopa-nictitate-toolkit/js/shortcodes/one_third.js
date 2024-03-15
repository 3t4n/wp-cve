(function() {  
    tinymce.create('tinymce.plugins.one_third', {  
        init : function(ed, url) {  
            ed.addButton('one_third', {  
                title : 'Add a one_third column',  
                image : url+'/icons/one_third.png',  
                onclick : function() {  
                    ed.selection.setContent('[one_third last="no"]'+ed.selection.getContent()+'[/one_third]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('one_third', tinymce.plugins.one_third);  
})();
