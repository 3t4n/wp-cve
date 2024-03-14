jQuery(document).on('tinymce-editor-setup', function( event, editor ) {
	editor.settings.toolbar1 += ',readMoreButton';
	editor.addButton( 'readMoreButton', {
		text: 'Read More',
		icon: false,
		onclick: function () {
			jQuery('#yrm-dialog').dialog({
				width: 450,
				modal: true,
				title: "Insert the shortcode",
				dialogClass: "yrm-readmore-builder"
			});
		}
	});
});