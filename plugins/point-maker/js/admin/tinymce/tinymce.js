(function() {
  // Register buttons
  tinymce.create('tinymce.plugins.Point_Maker_Button', {
    init: function( editor, url ) {
      // Add button that inserts shortcode into the current position of the editor
      editor.addButton( 'point_maker_button', {
        title: 'Point Maker',
        icon: ' pm-hand-pointer-o',
        onclick: function() {
          document.getElementById('point_maker_modal_open').onclick();
        }
      });
    },
    createControl: function( n, cm ) {
      return null;
    }
  });
  // Add buttons
  tinymce.PluginManager.add( 'point_maker_script', tinymce.plugins.Point_Maker_Button );
  tinymce.PluginManager.requireLangPack('point_maker_script', 'ja');
})();