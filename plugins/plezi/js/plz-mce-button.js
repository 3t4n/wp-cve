(function() {
  tinymce.PluginManager.add('plezi_form', function(editor) {
    var options = [];

    jQuery.each(editor.getLang('plz_tinymce_plugin.plugin_options'), function(key, value) {
      var item = {};

      item.text = value;
      item.onclick = function() { editor.insertContent('[plezi form=' + key + ']'); };

      options.push(item);
    });

    editor.addButton('plezi_form', {
      text: editor.getLang('plz_tinymce_plugin.plugin_title'),
      icon: null,
      type: 'menubutton',
      menu: options
   });
  });
})();
