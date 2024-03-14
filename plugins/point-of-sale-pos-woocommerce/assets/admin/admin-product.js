/* eslint-disable */

jQuery(document).on('ready', function () {
	if ('pos' !== window.zposAdminProduct.visibility) {
		return;
	}

	var $option = jQuery('#_visibility_pos');
	var label = $option.data('label');

	$option.prop('checked', true);

	if (jQuery('input[name=_featured]').is(':checked')) {
		label = label + ', ' + window.woocommerce_admin_meta_boxes.featured_label;
	}

	jQuery('#catalog-visibility-display').text(label);
});
