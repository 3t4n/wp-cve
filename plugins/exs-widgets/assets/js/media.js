'use strict';
jQuery(document).ready(function ($) {
	var $body=$('body');
	function media_upload(button_selector) {
		var _custom_media = true,
			_orig_send_attachment = wp.media.editor.send.attachment;
		$body.on('click', button_selector, function () {
			var button_id = $(this).attr('id');
			wp.media.editor.send.attachment = function (props, attachment) {
				if (_custom_media) {
					$('.' + button_id + '_img').attr('src', attachment.url);
					$('.' + button_id + '_url').val(attachment.url).trigger('change');
				} else {
					return _orig_send_attachment.apply($('#' + button_id), [props, attachment]);
				}
			}
			wp.media.editor.open($('#' + button_id));
			return false;
		});
	}
	media_upload('.exs_meta_widget_upload_media');
	//remove button process
	$body.on('click','.exs_meta_widget_remove_media',function () {
		$(this).closest('.exs-meta-widget-media').find('img').attr('src','').end().find('[type="text"]').val('').trigger('change');
	});
});