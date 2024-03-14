/*!
 *  Responsive Portfolio Image Gallery 1.2
 *  by @realwebcare - https://www.realwebcare.com/
 */
jQuery(document).ready(function(jQuery) {
	"use strict";
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;

	jQuery('#rcpig_image_upload').click(function() {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = jQuery(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment) {
			if ( _custom_media ) {
				console.log(attachment);

				if(jQuery('#image_upload_val').val() === '') {
					jQuery('#image_upload_val').val(attachment.id);
				} else {
					var oldVal = jQuery('#image_upload_val').val();
					jQuery('#image_upload_val').val(oldVal+','+attachment.id);
				}

				var src_str = attachment.url;
				jQuery('#image_upload_val').before('<img width=63 height=63 src='+src_str+' data-id='+attachment.id+' class=attachment-63x63 />');
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			}
		};
		wp.media.editor.open(button);
		return false;
	});

	jQuery('.add_media').on('click', function() {
		_custom_media = false;
	});

	jQuery('body').on('click', '#rcpig_portfolio_meta img', function() {
		var valArr = jQuery('#image_upload_val').val().split(',');
		console.log(valArr);
		confirm ("Are you sure you want to delete?");
		var index = valArr.indexOf(jQuery(this).attr('data-id'));
		if (index > -1) {
			valArr.splice(index, 1);
			jQuery(this).remove();
		}
		console.log(valArr);
		jQuery('#image_upload_val').val(valArr.toString());
	});
});