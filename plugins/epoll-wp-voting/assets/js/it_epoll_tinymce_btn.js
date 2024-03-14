(function() {
   tinymce.create('tinymce.plugins.it_epoll', {
      init : function(ed, url) {
         ed.addButton('it_epoll', {
            title : 'Insert Poll',
            image : url+'/epoll.png',
            onclick : function() {
               var poll_id = prompt("Enter Poll ID", "");

                  if (poll_id != null && poll_id != ''){
                     ed.execCommand('mceInsertContent', false, '[IT_EPOLL id="'+poll_id+'"][/IT_EPOLL]');
                  }else{
                     ed.execCommand('mceInsertContent', false, '[IT_EPOLL id="1"][/IT_EPOLL]');
               }
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname : "IT_EPOLL WP VOTING",
            author : 'InfoTheme',
            authorurl : 'http://www.infotheme.in',
            infourl : 'http://infotheme.in/products/plugins/epoll-wp-voting-system/',
            version : "2.0"
         };
      }
   });
   tinymce.PluginManager.add('it_epoll', tinymce.plugins.it_epoll);
})();