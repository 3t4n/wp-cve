(function() {
	tinymce.PluginManager.requireLangPack('cvm_shortcode');
	tinymce.create('tinymce.plugins.CVMVideoPlugin', {
		init : function(ed, url) {
			
			// Register the command
			ed.addCommand('mceCVMVideo', function() {
				// dialog window, set in assets/back-end/js/shortcode-modal.js
				if( CVMVideo_DIALOG_WIN ){
					CVMVideo_DIALOG_WIN.dialog('open');
				}	
			});

			// Register button
			ed.addButton('cvm_shortcode', {
				title : 'cvm_shortcode.title',
				cmd : 'mceCVMVideo',
				class: 'CVM_dialog',
				url:'',
				image : url + '/images/ico.png'
			});

			/*
			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('example', n.nodeName == 'IMG');
			});
			*/
		},

		createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {
				longname 	: 'Vimeo Videos for WordPress',
				author 		: 'Constantin Boiangiu',
				authorurl 	: 'http://www.constantinb.com',
				infourl 	: 'http://www.constantinb.com',
				version 	: "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('cvm_shortcode', tinymce.plugins.CVMVideoPlugin);
})();