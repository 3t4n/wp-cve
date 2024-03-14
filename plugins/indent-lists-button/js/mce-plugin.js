(function($) {
    /* Bail out if the admin_head hook hasn't been called to add the iliGlob variable */
	if(typeof iliGlob === 'undefined')
		return;
	/* add the indent list style to the editor */
	tinymce.editors.forEach(function(ed){
	    ed.contentStyles.push(iliGlob.css);
	});
	/*
	 * Variable to save the instances of the button for use in
	 * the callbacks.
	 */
	var iliButton = null;
	/* Firstly, create a tinymce plugin that adds a new button editor to the editor */
	tinymce.create('tinymce.plugins.ili_mce_plugin', {
		init : function(ed, url) {
			/**
			 * Tell tinyMCE that this plugin should add a button to the editor, setting the
			 * title, registering the 'indent_lists' function, setting the icon
			 * class, and making sure the button is saved in global iliButton
			 * for reference later on
			 */
			ed.addButton('the_ili_button', {
				title : (typeof(iliGlob.title) !== 'undefined') ? iliGlob.title : 'Indent List',
				cmd : 'indent_lists',
				icon : 'ili_button',
				onPostRender : function() {
					iliButton = this;
				},
			});
			/**
			 * Tell tinyMCE what to do when the newly added button is pressed:
			 * check whether the caret is in a node and if so, toggle that
			 * node's 'ili-indent'-class if a list-node and then repaint the
			 * editor to see the effect (includes call to NodeChange
			 * eventhandler)
			 */
			ed.addCommand('indent_lists', function() {
				var nodeAtCaret = tinymce.activeEditor.selection.getNode();
				if (nodeAtCaret) {
					/* Indent a list if present in this main node */
					$(nodeAtCaret).closest('ul, ol').toggleClass('ili-indent');
					ed.execCommand("mceRepaint");
				}
			});
			/**
			 * Tell tinyMCE what we want to do when the caret is moved
			 * inside/within the editor in order to either 1. disable the button if
			 * there is no list-element or 2. activate the button if the list is
			 * indented and set the icon arrow's direction
			 */
			ed.on('NodeChange', function() {
				if (iliButton) {
					icon = $('i.mce-i-ili_button');
					list = $(tinymce.activeEditor.selection.getNode()).closest('ul, ol');
					inList = (list.length === 1);
					inIndentedList = (inList && list.hasClass('ili-indent'));
					/* Disable the button whenever the caret is outside a list. */
					iliButton.disabled(!inList);
					/* Activate button if the caret is in an indented list. */
					iliButton.active(inIndentedList);
					/* Set the right arrow direction on the button. */
					icon.toggleClass('ili-indented', inIndentedList);
				}
			});
		},
		/**
		 * Returns information about the plugin as a name/value array.
		 * 
		 * @return {Object} Name/value array containing information about the
		 *         plugin.
		 */
		getInfo : function() {
			return {
				longname : ((typeof(iliGlob.name) !== 'undefined') ? iliGlob.name : 'Indent Lists Button') + ' mce-plugin',
				author : 'Klaas van der Linden',
				authorurl : 'http://culturalservices.nl',
				infourl : (typeof(iliGlob.pluginURI) !== 'undefined') ? iliGlob.pluginURI : '',
				version : (typeof(iliGlob.version) !== 'undefined') ? iliGlob.version : ''
			};
		}
	});
	// Secondly, add the newly created plugin to the tinymce.PluginManager
	tinymce.PluginManager.add('ili_mce_plugin', tinymce.plugins.ili_mce_plugin);
})(jQuery);