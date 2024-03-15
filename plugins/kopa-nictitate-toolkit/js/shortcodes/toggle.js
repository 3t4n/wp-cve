(function() {  
    tinymce.create('tinymce.plugins.toggle', {  
        init : function(ed, url) {              
            ed.addButton('toggle', {  
                title : 'Add Toggle',  
                image : url+'/icons/toggle.png',  
                onclick : function() {    
                    var string = '[toggles]';
                    string += '[toggle title=""]Toggle content 1[/toggle]';
                    string += '[toggle title=""]Toggle content 2[/toggle]';
                    string += '[toggle title=""]Toggle content 3[/toggle]';
                    string += '[/toggles]';
                    ed.selection.setContent(string);                                                                          
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('toggle', tinymce.plugins.toggle);  
})();  