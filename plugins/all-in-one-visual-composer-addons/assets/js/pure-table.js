jQuery(document).ready(function($) {
	$('.pricing-table-container .wdo-cols').each(function(index, el) {
	var columns_value = $(this).closest('.col-val').data('cols');
        $(this).addClass(columns_value);
    });
});