(function( $ ) {
	/**
	 * Our tinyMCE button that adds our shortcode functionality.
	 */
	tinymce.PluginManager.add( 'open_rdw_kenteken_button', function( editor, url ) {

		// Add Button to Visual Editor Toolbar
		editor.addButton('open_rdw_kenteken_button', {
			tooltip: 'Insert Open RDW shortcode',
			cmd: 'open_rdw_kenteken_button',
			image: url + '../../../images/admin/open-rdw_grey.png',
			class: 'blabla',
		});

		var test = 0;

		// Add Command when Button Clicked
		editor.addCommand('open_rdw_kenteken_button', function() {
			tb_show('Open RDW shortcode', '#TB_inline?inlineId=rdw-thickbox');
			// Some dirty "hacks" :x
			$('#TB_ajaxContent').removeAttr('style');
			$('#TB_window').css('overflowY', 'scroll');

			$('#rdw-thickbox-submit').unbind().click(function(e) {
				e.preventDefault();

				var content = '';
				var rdw_fields = [];

				$('.rdw-sort-fields input:checked').map(function() {
					var value = $(this).attr('name');
					if (typeof value !== 'undefined') {
						rdw_fields.push(value);
					}
				});
				
				if (rdw_fields.length > 0) {
					content = '[open_rdw_check "' + rdw_fields.join('" "') + '"]';
				} else {
					content = '[open_rdw_check]';
				}

				tinymce.execCommand('mceInsertContent', false, content);
				tb_remove();

			});
		});
	});
})( jQuery );