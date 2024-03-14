/**
 * Ordernumber Admin JS
 */
jQuery( function ( $ ) {

	$('input#customize_ordernumber').change(function() {
		if ($(this).is(':checked')) {
			$('#ordernumber_format').closest('tr').show();
			$('#ordernumber_global').closest('tr').show();
			$('#ordernumber-countertable-ordernumber').closest('tr').show();
		} else {
			$('#ordernumber_format').closest('tr').hide();
			$('#ordernumber_global').closest('tr').hide();
			$('#ordernumber-countertable-ordernumber').closest('tr').hide();
		}
	}).change();

	$('input#customize_invoice').change(function() {
		if ($(this).is(':checked')) {
			$('#invoice_format').closest('tr').show();
			$('#invoice_global').closest('tr').show();
			$('#ordernumber-countertable-invoice').closest('tr').show();
		} else {
			$('#invoice_format').closest('tr').hide();
			$('#invoice_global').closest('tr').hide();
			$('#ordernumber-countertable-invoice').closest('tr').hide();
		}
	}).change();


});
