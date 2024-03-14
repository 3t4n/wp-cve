jQuery(document).ready(function() {
	jQuery(document)
		.on('click', '.lasso-lite-notice-dismiss', lasso_lite_dismiss);
});

function lasso_lite_dismiss() {
	jQuery('.lasso-lite-notice').addClass('lasso-lite-d-none');
	jQuery.ajax({
		url: '/wp-admin/admin-ajax.php',
		type: 'post',
		data: {
			action: 'lasso_lite_dismiss_notice',
			nonce: lassoLiteOptionsData.optionsNonce,
		}
	})
		.done(function () {});
}
