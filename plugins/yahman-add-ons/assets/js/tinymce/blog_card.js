(function() {

  // Register buttons
  tinymce.create('tinymce.plugins.YAHMAN_Addons_Blog_card_Button', {
  	init: function( editor, url ) {
      // Add button that inserts shortcode into the current position of the editor
      editor.addButton( 'yahman_addons_blog_card_button', {
      	title: 'Blog Card',
      	icon: 'dashicon dashicons-tickets-alt',
      	onclick: function() {
      		document.getElementById("yahman_bc_btn").checked = true;
      		document.getElementById("ya_blog_card_url").focus();
      	}
      });
  },
  createControl: function( n, cm ) {
  	return null;
  }
});
  // Add buttons
  tinymce.PluginManager.add( 'yahman_addons_blog_card_script', tinymce.plugins.YAHMAN_Addons_Blog_card_Button );
  tinymce.PluginManager.requireLangPack('yahman_addons_blog_card_script', 'ja');
})();
