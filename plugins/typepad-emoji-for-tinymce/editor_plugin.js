(function() {
	tinymce.create('tinymce.plugins.tpademoji', {
		
		init : function(ed, url) {
		// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('tpademoji', function() {
				ed.windowManager.open({
					file : url + '/window.php',
					width : 630,
					height : 350,
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('tpademoji', {
				title : 'TypePad emoji',
				cmd : 'tpademoji',
				image : url + '/icons/05/info01.gif'
			});
			
			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('tpademoji', n.nodeName == 'IMG');
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
					longname  : 'tpademoji',
					author 	  : 'Tsubasa',
					authorurl : 'http://www.picmo.net',
					infourl   : 'http://www.picmo.net',
					version   : "1.4"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('tpademoji', tinymce.plugins.tpademoji);
})();


