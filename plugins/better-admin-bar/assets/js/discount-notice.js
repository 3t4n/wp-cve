(function ($) {
	var ajax = {};

	function init() {
		$(document).on(
			"click",
			".swift-control-discontinue-notice.is-dismissible .notice-dismiss",
			ajax.saveDismissal
		);
	}

	ajax.saveDismissal = function (e) {
		$.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				action: 'sc_discount_notice_dismissal',
				nonce: swiftControlDismissal.nonces.dismissalNonce,
				dismiss: 1
			}
		}).always(function (r) {
			if (r.success) console.log(r.data);
		});
	};

	init();
})(jQuery);