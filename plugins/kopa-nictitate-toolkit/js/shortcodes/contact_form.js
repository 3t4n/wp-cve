(function() {  
    tinymce.create('tinymce.plugins.contact_form', {  
        init : function(ed, url) {              
            ed.addButton('contact_form', {  
                title : 'Add Contact Form',  
                image : url+'/icons/contact_form.png',  
                onclick : function() {        
                    ed.selection.setContent('[contact_form caption="Get in Touch!" address="5512 Lorem Ipsum Vestibulum 666/13" phone="+1 800 789 50 12" email="mail@compname.com"][/contact_form]');                                                               
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('contact_form', tinymce.plugins.contact_form);  
})();  