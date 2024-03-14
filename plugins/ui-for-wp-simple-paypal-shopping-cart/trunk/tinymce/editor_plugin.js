// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('wwwpuiwpsppsc');
	
	tinymce.create('tinymce.plugins.wwwpuiwpsppsc', {
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

			ed.addCommand('mcewwwpuiwpsppsc', function() {
				ed.windowManager.open({
					file : ajaxurl + '?action=orbsius_ui_for_paypal_shopping_cart_ajax_render_popup_content', // wp admin ajax variable
					width : 600 + ed.getLang('wwwpuiwpsppsc.delta_width', 0),
					height : 450 + ed.getLang('wwwpuiwpsppsc.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('wwwpuiwpsppsc', {
				title : 'UI for WP Simple Paypal Shopping cart',
				cmd : 'mcewwwpuiwpsppsc',
				image : url + '/icon.png'
			});
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
					longname  : 'UI for WP Simple Paypal Shopping cart',
					author 	  : 'Svetoslav Marinov',
					authorurl : 'http://orbisius.com',
					infourl   : 'http://club.orbisius.com/products/wordpress-plugins/ui-for-wp-simple-paypal-shopping-cart/',
					version   : "1.0.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('wwwpuiwpsppsc', tinymce.plugins.wwwpuiwpsppsc);
})();
