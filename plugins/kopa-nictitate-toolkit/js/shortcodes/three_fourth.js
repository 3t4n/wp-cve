(function() {  
    tinymce.create('tinymce.plugins.three_fourth', {  
        init : function(ed, url) {  
            ed.addButton('three_fourth', {  
                title : 'Add a three_fourth column',  
                image : url+'/icons/three_fourth.png',  
                onclick : function() {  
                    ed.selection.setContent('[three_fourth last="no"]'+ed.selection.getContent()+'[/three_fourth]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('three_fourth', tinymce.plugins.three_fourth);  
})();
