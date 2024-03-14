/**
 * Add CLINK shortcode inserting button to TinyMCE
 */
(function($) {
	tinymce.create('tinymce.plugins.clink', {
		init : function(ed, url) {
			this.editor = ed;
			var pluginUrl = url.replace(/\/static\/js\/tinymce$/, '');
			var wpBaseUrl = url.replace(/\/wp-content\/plugins\/[^\/]+\/static\/js\/tinymce$/, '');
			// register commands
			ed.addCommand('mceClinkCmd', function() {
				ed.windowManager.open({
					title: 'Insert Cloaked Link',
					file : wpBaseUrl + '/wp-admin/admin.php?page=pmlc-admin-tinymce',
					width : 335,
					height : 247,
					inline : 1
				});
			});

			// register buttons
			ed.addButton('clink', {
				title : 'Insert Cloaked Link',
				image : pluginUrl + '/static/img/wizard-icon-20x20.png',
				cmd : 'mceClinkCmd'
			});
			
		},

		getInfo : function() {
			return {
				longname : 'Cloaked Link',
				author : 'Pressmatix',
				infourl : '',
				version : tinymce.majorVersion + '.' + tinymce.minorVersion
			};
		}
	});

	// register plugin
	tinymce.PluginManager.add('clink', tinymce.plugins.clink);
	
})(jQuery);