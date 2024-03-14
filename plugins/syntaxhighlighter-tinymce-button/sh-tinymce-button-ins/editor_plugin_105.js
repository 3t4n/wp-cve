// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('shtb_adv_insert');
	
	tinymce.create('tinymce.plugins.shtb_adv_insert', {

		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('shtb_adv_insert_cmd', function() {
				ed.windowManager.open({
					file : url + '/window.php',
					width : 378 + ed.getLang('shtb_adv_insert.delta_width', 0),
					height : 200 + ed.getLang('shtb_adv_insert.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('shtb_adv_insert', {
				title : 'SyntaxHighlighter TinyMCE Button Select & Insert',
				cmd : 'shtb_adv_insert_cmd',
				image : url + '/shtb_img.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('shtb_adv_insert', n.nodeName == 'IMG');
			});
		},

                createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {
					longname  : 'SyntaxHighlighter TinyMCE Button Select & Insert',
					author 	  : 'redcocker',
					authorurl : 'http://www.near-mint.com/blog',
					infourl   : 'http://www.near-mint.com/blog',
					version   : "0.7.5"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('shtb_adv_insert', tinymce.plugins.shtb_adv_insert);
})();
