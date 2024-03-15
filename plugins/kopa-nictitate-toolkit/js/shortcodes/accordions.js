(function() {  
    tinymce.create('tinymce.plugins.accordions', {  
        init : function(ed, url) {              
            ed.addButton('accordions', {  
                title : 'Add Accordions',  
                image : url+'/icons/accordions.png',  
                onclick : function() {        
                    var string = '[accordions]';
                    string += '[accordion title="Accordion 1"]Accordion content 1[/accordion]';
                    string += '[accordion title="Accordion 2"]Accordion content 2[/accordion]';
                    string += '[accordion title="Accordion 3"]Accordion content 3[/accordion]';
                    string += '[/accordions]';
                    ed.selection.setContent(string);                                                                 
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('accordions', tinymce.plugins.accordions);  
})();  