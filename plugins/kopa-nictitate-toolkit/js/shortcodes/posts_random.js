(function() {  
    tinymce.create('tinymce.plugins.posts_random', {  
        init : function(ed, url) {  
            ed.addButton('posts_random', {  
                title : 'Add A Block Random Posts',  
                image : url+'/icons/posts_random.png',  
                onclick : function() {  
                    ed.selection.setContent('[posts count="10" orderby="random" cats="" tags="" relation="OR" ][/posts]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('posts_random', tinymce.plugins.posts_random);  
})();
