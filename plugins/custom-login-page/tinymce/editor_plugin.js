// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins

(function() {
	
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('CustomLoginPage');
	
	tinymce.create('tinymce.plugins.CustomLoginPage', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('mceCustomLoginPage', function(){
				
				var insertstring = '[a5-login-form]';
				
                ed.selection.setContent(insertstring);
				
            });

			// Register example button
			ed.addButton('CustomLoginPage', {
				title : 'Add Login Form',
				cmd : 'mceCustomLoginPage',
				image : url + '/CustomLoginPage.png'
			});

		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
					longname  : 'CustomLoginPage',
					author 	  : 'Stefan Crämer',
					authorurl : 'http://www.stefan-craemer.com',
					infourl   : 'http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/custom-login-page',
					version   : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('CustomLoginPage', tinymce.plugins.CustomLoginPage);
})();