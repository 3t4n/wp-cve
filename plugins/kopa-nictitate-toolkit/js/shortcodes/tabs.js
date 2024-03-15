(function() {  
    tinymce.create('tinymce.plugins.tabs', {  
        init : function(ed, url) {              
            ed.addButton('tabs', {  
                title : 'Add Tabs',  
                image : url+'/icons/tabs.png',  
                onclick : function() {        
                    var string = '[tabs tab1="Tab 1" tab2="Tab 2" tab3="Tab 3"]';
                    string += '[tab id=1]Tab content 1[/tab]';
                    string += '[tab id=2]Tab content 2[/tab]';
                    string += '[tab id=3]Tab content 3[/tab]';
                    string += '[/tabs]';
                    ed.selection.setContent(string);                                                                 
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('tabs', tinymce.plugins.tabs);  
})();  