(function() {
	tinymce.PluginManager.add('vri-shortcodes', function(editor, url) {
		// add Button to Visual Editor Toolbar
		editor.addButton('vri-shortcodes', {
			title: 'VikRentItems Shortcodes List',
			cmd: 'vri-shortcodes',
			icon: 'wp_code'
		});

		editor.addCommand('vri-shortcodes', function() {
			openVikRentItemsShortcodes(editor);
		});

	});
})();

var shortcodes_editor = null;

function openVikRentItemsShortcodes(editor) {

	shortcodes_editor = editor;

	var html = '';

	for (var group in VIKRENTITEMS_SHORTCODES) {

		html += '<div class="shortcodes-block">';
		html += '<div class="shortcodes-group"><a href="javascript: void(0);" onclick="toggleVikRentItemsShortcode(this);">' + group + '</a></div>';
		html += '<div class="shortcodes-container">';

		for (var i = 0; i < VIKRENTITEMS_SHORTCODES[group].length; i++) {
			var row = VIKRENTITEMS_SHORTCODES[group][i];

			html += '<div class="shortcode-record" onclick="selectVikRentItemsShortcode(this);" data-code=\'' + row.shortcode + '\'">';
			html += '<div class="maindetails">' + row.name + '</div>';
			html += '<div class="subdetails">';
			html += '<small class="postid">Post ID: ' + row.post_id + '</small>';
			html += '<small class="createdon">Created On: ' + row.createdon + '</small>';
			html += '</div>';
			html += '</div>';
		}

		html += '</div></div>';
	}

	jQuery('body').append(
		'<div id="vri-shortcodes-backdrop" class="vri-tinymce-backdrop"></div>\n'+
		'<div id="vri-shortcodes-wrap" class="vri-tinymce-modal wp-core-ui has-text-field" role="dialog" aria-labelledby="link-modal-title">\n'+
			'<form id="vri-shortcodes" tabindex="-1">\n'+
				'<h1>VikRentItems Shortcodes List</h1>\n'+
				'<button type="button" onclick="dismissVikRentItemsShortcodes();" class="vri-tinymce-dismiss"><span class="screen-reader-text">Close</span></button>\n'+
				'<div class="vri-tinymce-body">' + html + '</div>\n'+
				'<div class="vri-tinymce-submitbox">\n'+
					'<div id="vri-tinymce-cancel">\n'+
						'<button type="button" class="button" onclick="dismissVikRentItemsShortcodes();">Cancel</button>\n'+
					'</div>\n'+
					'<div id="vri-tinymce-update">\n'+
						'<button type="button" class="button button-primary" disabled onclick="putVikRentItemsShortcode();">Add</button>\n'+
					'</div>\n'+
				'</div>\n'+
			'</form>\n'+
		'</div>\n'
	);

	jQuery('#vri-shortcodes-backdrop').on('click', function() {
		dismissVikRentItemsShortcodes();
	});
}

function dismissVikRentItemsShortcodes() {
	jQuery('#vri-shortcodes-backdrop, #vri-shortcodes-wrap').remove();
}

function toggleVikRentItemsShortcode(link) {
	var next = jQuery(link).parent().next();
	var show = next.is(':visible') ? false : true;

	jQuery('.shortcodes-container').slideUp();

	if (show) {
		next.slideDown();
	}
}

function selectVikRentItemsShortcode(record) {
	jQuery('.shortcode-record').removeClass('selected');
	jQuery(record).addClass('selected');

	jQuery('#vri-tinymce-update button').prop('disabled', false);
}

function putVikRentItemsShortcode() {
	var shortcode = jQuery('.shortcode-record.selected').data('code');

	shortcodes_editor.execCommand('mceReplaceContent', false, shortcode);

	dismissVikRentItemsShortcodes();
}
