(function() {  
    tinymce.create('tinymce.plugins.google_map', {  
        init : function(ed, url) {  
            ed.addButton('google_map', {  
                title : 'Add a google_map',  
                image : url+'/icons/google_map.png',  
                onclick : function() {  
                    ed.selection.setContent('[google_map]<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Holywood&amp;ie=UTF8&amp;hq=&amp;hnear=Holywood,+North+Down,+United+Kingdom&amp;ll=54.63949,-5.83778&amp;spn=0.013312,0.042272&amp;t=m&amp;z=14&amp;output=embed"></iframe>[/google_map]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('google_map', tinymce.plugins.google_map);  
})();

