(function() {
   tinymce.create('tinymce.plugins.buckets', {
      init : function(ed, url) {
        ed.addCommand('bucketShortcodes', function() {
           ed.windowManager.open({
                   file : url + '/shortcodes.php',
                   width : 320 + ed.getLang('shortcodes.delta_width', 0),
                   height : 600 + ed.getLang('shortcodes.delta_height', 0),
                   inline : 1
           }, {
                   plugin_url : url 
           });
         });

         ed.addButton('buckets', {
            title : 'Buckets',
            image : url+'/bucket.png',
            cmd   : 'bucketShortcodes'
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname : "Buckets",
            author : 'Matthew Restorff',
            authorurl : 'http://matthewrestorff.com',
            infourl : '',
            version : "1.0"
         };
      }
   });
   tinymce.PluginManager.add('buckets', tinymce.plugins.buckets);
})();