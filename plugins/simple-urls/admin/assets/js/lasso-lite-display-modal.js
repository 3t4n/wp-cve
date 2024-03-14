let tiny_mce_editor;
let lasso_editor_check = 0;
let html = '';
let tinymce_lasso_button_label = 'Add A Lasso Display';

if (typeof tinymce !== 'undefined') {
	tinymce.PluginManager.add('lasso_lite_tc_button', function(editor, url) {

		if(lasso_editor_check === 0) {
			tiny_mce_editor = editor;
			lasso_editor_check++;
		}

		let url_arr = url.split('/');
		url_arr.pop();
		let asset_url = url_arr.join('/');

		editor.addButton('lasso_lite_tc_button', {
			title: tinymce_lasso_button_label,
			image: asset_url + '/images/lasso-icon-tinymce.svg',
			icon: false,
			onclick: function() {
				let popup = jQuery('#lasso-display-add');
				if ( 0 === popup.length ) {
				    jQuery('#wpcontent').append(html);
				}
				popup.modal('show');
			}
		});
	});
}

jQuery(function() {
	function loadPopupContent() {
		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'get',
			data: {
				action: 'lasso_lite_get_display_html',
				nonce: lassoLiteOptionsData.optionsNonce,
			}
		})
			.done(function(res) {
				if (typeof res.data != 'undefined') {
					let data = res.data;
					html = data.html;

					jQuery('div[aria-label="' + tinymce_lasso_button_label + '"]').click(function() {
						let popup = jQuery('#lasso-display-add');
						if ( 0 === popup.length ) {
							jQuery('#wpcontent').append(html);
							popup.modal('toggle');
						}
					});
				}
			});
	}

	loadPopupContent();

});

function add_short_code_single(obj) {
	let link_slug = jQuery(obj).data('link-slug');
	let post_id = jQuery(obj).data('post-id');
	let shortcode = '[lasso ref="' + link_slug + '" id="' + post_id + '"]';
	tiny_mce_editor.insertContent(shortcode);
	jQuery('#lasso-display-add').modal('hide');
}

jQuery(document).on('show.bs.modal', '#lasso-display-add', function () {
	jQuery('body').addClass('lasso-display-add-modal-open');
});
jQuery(document).on('hide.bs.modal', '#lasso-display-add', function () {
	jQuery('body').removeClass('lasso-display-add-modal-open');
});
