/* global faireAdminProductsList */

'use strict';

(function ($) {
	$(document).ready(() => {
		const {
			ajaxUrl,
			productManualSyncFailed,
			nonceProductManualSync
		} = faireAdminProductsList;

		$('.wc_faire_manual_product_sync').on('click', function(e) {
			e.preventDefault();

			const product_id = $(this).data('product');
			const row_last_sync = $(this).closest('td').find('.wc_faire_product_last_sync_result');
			const row_faire_id = $(this).closest('td').find('.wc_faire_product_faire_id');
			const btn_el = $(this);
			let row_last_sync_display = row_last_sync.html();
			let row_faire_id_display = row_faire_id.html();

			btn_el.attr('disabled', true);
			row_last_sync.html('...');

			$.post(
				ajaxUrl,
				{
					action: 'faire_single_product_manual_sync',
					nonce: nonceProductManualSync,
					product_id : product_id
				},
				data => {
					if (typeof data.data.result_string !== 'undefined') {
						row_last_sync_display = data.data.result_string;
					}
					if (typeof data.data.faire_product_id !== 'undefined') {
						row_faire_id_display = data.data.faire_product_id;
					}
					// eslint-disable-next-line no-console
				}
			)
			.fail(() => alert(productManualSyncFailed))
			.always(() => {
				btn_el.attr('disabled', false);
				row_last_sync.html(row_last_sync_display);
				row_faire_id.html(row_faire_id_display);
			});
		});
	});
})(window.jQuery);
