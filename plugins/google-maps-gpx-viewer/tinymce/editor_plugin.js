/*
editor_plugin.js, V 1.04, altm, 26.08.2013 
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
Google Map V3 init Multimap support
released under GNU General Public License
*/
(function() {
	var field;
	var fenster;
	var fName = jQuery('#editable-post-name-full').html();
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('GmapGpx');
	tinymce.create('tinymce.plugins.GmapGpx', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			ed.addCommand('mceGmapGpx', function() {
				ed.windowManager.open({
					file : ajaxurl + '?action=gmap_tinymce',
					width : 450 + ed.getLang('GmapGpx.delta_width', 0),
					height : 220 + ed.getLang('GmapGpx.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});
			ed.addCommand('mceGmapGpxUpload', function(field_name, fen) {
				fenster = fen;
				field = field_name;
				var cmd = ajaxurl + '?action=gmap_tinymce_upload&input=' + field_name;
				var title =  'ATLsoft Uploader';
				var inline = 1;
				var width = 350, height = 140;
				maxi = false;
				if (field_name == 'drawFile'){
					var post_id = tinymce.DOM.get('post_ID').value;	
					cmd = ajaxurl + '?action=gmap_tinymce_editor&input=' + field_name + '&post=' + post_id;
					title = 'ATLsoft Editor';
					width = 686; height = 500;
					inline = 0;
					maxi = true;
					field = 'mapFile'
				}
				ed.windowManager.open({
					file            : cmd,   
					title           : title,
					width           : width,  
					height          : height,
					resizable       : "yes", 
					maximizable		: maxi,
					inline          : inline,    // This parameter only has an effect if you use the inlinepopups plugin!
					close_previous  : "no"
				}, {
					/* input : field_name */
				});
			});
			
 			ed.addCommand('mceGMapInsertContent', function(uri) {
				fenster.document.getElementById(field).value = uri;
			}); 
			
 			ed.addCommand('mceGMapPermalink', function() {
				return fName;
			}); 
			
			// Register example button
			ed.addButton('GmapGpx', {
				title : 'GmapGpx.desc',
				cmd : 'mceGmapGpx',
				image : url + '/GmapGpx.gif'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('GmapGpx', n.nodeName == 'IMG');
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
					longname  : 'GmapGpx plugin',
					author 	  : 'ATLsoft',
					authorurl : 'http://ATLsoft.de',
					infourl   : 'http://ATLsoft.de',
					version   : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('GmapGpx', tinymce.plugins.GmapGpx);
})();

