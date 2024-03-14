(function() {
	tinymce.PluginManager.add('vap-shortcodes', function(editor, url) {
		// add Button to Visual Editor Toolbar
		editor.addButton('vap-shortcodes', {
			title: 'VikAppointments Shortcodes List',
			cmd: 'vap-shortcodes',
			icon: 'wp_code'
		});

		editor.addCommand('vap-shortcodes', function() {
			openVikAppointmentsShortcodes(editor);
		});

	});
})();

var shortcodes_editor = null;

function openVikAppointmentsShortcodes(editor) {

	shortcodes_editor = editor;

	var html = '';

	for (var group in VIKAPPOINTMENTS_SHORTCODES) {

		html += '<div class="shortcodes-block">';
		html += '<div class="shortcodes-group"><a href="javascript: void(0);" onclick="toggleVikAppointmentsShortcode(this);">' + group + '</a></div>';
		html += '<div class="shortcodes-container">';

		for (var i = 0; i < VIKAPPOINTMENTS_SHORTCODES[group].length; i++) {
			var row = VIKAPPOINTMENTS_SHORTCODES[group][i];

			html += '<div class="shortcode-record" onclick="selectVikAppointmentsShortcode(this);" data-code=\'' + row.shortcode + '\'">';
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
		'<div id="vap-shortcodes-backdrop" class="vap-tinymce-backdrop"></div>\n'+
		'<div id="vap-shortcodes-wrap" class="vap-tinymce-modal wp-core-ui has-text-field" role="dialog" aria-labelledby="link-modal-title">\n'+
			'<form id="vap-shortcodes" tabindex="-1">\n'+
				'<h1>VikAppointments Shortcodes List</h1>\n'+
				'<button type="button" onclick="dismissVikAppointmentsShortcodes();" class="vap-tinymce-dismiss"><span class="screen-reader-text">Close</span></button>\n'+
				'<div class="vap-tinymce-body">' + html + '</div>\n'+
				'<div class="vap-tinymce-submitbox">\n'+
					'<div id="vap-tinymce-cancel">\n'+
						'<button type="button" class="button" onclick="dismissVikAppointmentsShortcodes();">Cancel</button>\n'+
					'</div>\n'+
					'<div id="vap-tinymce-update">\n'+
						'<button type="button" class="button button-primary" disabled onclick="putVikAppointmentsShortcode();">Add</button>\n'+
					'</div>\n'+
				'</div>\n'+
			'</form>\n'+
		'</div>\n'
	);

	jQuery('#vap-shortcodes-backdrop').on('click', function() {
		dismissVikAppointmentsShortcodes();
	});
}

function dismissVikAppointmentsShortcodes() {
	jQuery('#vap-shortcodes-backdrop, #vap-shortcodes-wrap').remove();
}

function toggleVikAppointmentsShortcode(link) {
	var next = jQuery(link).parent().next();
	var show = next.is(':visible') ? false : true;

	jQuery('.shortcodes-container').slideUp();

	if (show) {
		next.slideDown();
	}
}

function selectVikAppointmentsShortcode(record) {
	jQuery('.shortcode-record').removeClass('selected');
	jQuery(record).addClass('selected');

	jQuery('#vap-tinymce-update button').prop('disabled', false);
}

function putVikAppointmentsShortcode() {
	var shortcode = jQuery('.shortcode-record.selected').data('code');

	shortcodes_editor.execCommand('mceReplaceContent', false, shortcode);

	dismissVikAppointmentsShortcodes();
}
