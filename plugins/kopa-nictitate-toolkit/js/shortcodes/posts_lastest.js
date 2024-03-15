(function() {  
    tinymce.create('tinymce.plugins.posts_lastest', {  
        init : function(ed, url) {  
            ed.addButton('posts_lastest', {  
                title : 'Add A Block Lastest Posts',  
                image : url+'/icons/posts_lastest.png',  
                onclick : function() {  
                    ed.selection.setContent('[posts count="10" orderby="lastest" cats="" tags="" relation="OR" ][/posts]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('posts_lastest', tinymce.plugins.posts_lastest);  
})();
