'use strict';

(function ($) {
	const prefix = 'woocommerce_';
	const plugin = 'faire_product';

	const manageProductPreorderDatesFields = () => {
		const allowPreorder = $(`#${prefix}${plugin}_allow_preorder`);

		const setProductPreorderDatesFieldsVisibility = () => {
			const allowPreorderDates = $(`#${prefix}${plugin}_preorder_dates`);
			allowPreorderDates.toggle(allowPreorder.val() === 'allow');
		};

		allowPreorder.on('change', function () {
			setProductPreorderDatesFieldsVisibility();
		});

		setProductPreorderDatesFieldsVisibility();
	};

	$(document).ready(() => {
		const integrationAllowPreorder = $(
			`#${prefix}${plugin}_allow_preorder`
		);
		// Current page is product admin.
		if (integrationAllowPreorder.length) {
			manageProductPreorderDatesFields();
		}
	});
})(window.jQuery);
