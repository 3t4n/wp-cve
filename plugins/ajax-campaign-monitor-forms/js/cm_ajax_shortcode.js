(function() {
	tinymce.create('tinymce.plugins.cm_ajax_shortcode', {
		init : function(ed, url) {
			ed.addCommand('cm_ajax_shortcode_popup', function() {
					ed.windowManager.open({
						file: url + '/../../../../index.php?cm_ajax_shortcode=1&cm_ajax_response=ajax&cm_ajax_shortcode_action=renderpopup',
						width : 400 + ed.getLang('example.delta_width', 0),
						height : 450 + ed.getLang('example.delta_height', 0),
				        inline : 1
						}, {
							plugin_url : url
						}
					);
				}
			);
			ed.addButton('cm_ajax_shortcode', {
				title : 'Add a Campaign Monitor sign-up form',
				image : url+'/image.png',
				cmd :  'cm_ajax_shortcode_popup'
				}
			);
		},
		createControl : function(n, cm) {
			return null;
		},
	});
	tinymce.PluginManager.add('cm_ajax_shortcode', tinymce.plugins.cm_ajax_shortcode);
})();
