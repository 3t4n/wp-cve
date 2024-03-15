(function() {  
    tinymce.create('tinymce.plugins.posts_popular', {  
        init : function(ed, url) {  
            ed.addButton('posts_popular', {  
                title : 'Add A Block Popular Posts By View Count',  
                image : url+'/icons/posts_popular.png',  
                onclick : function() {  
                    ed.selection.setContent('[posts count="10" orderby="popular" cats="" tags="" relation="OR" ][/posts]');
                }  
            });  
        },  
        createControl : function(n, cm) {  
            return null;  
        }
    });  
    tinymce.PluginManager.add('posts_popular', tinymce.plugins.posts_popular);  
})();
