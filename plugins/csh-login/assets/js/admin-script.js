jQuery(document).ready(function($) {
	if ( !$( 'input[type="radio"][value = "Custom URL"]' ).is(":checked") ) {
		$('#custom_redirect').parent().parent().hide();
	}
	$('input[type="radio"]').change(function() {
	  if ($(this).val() == 'Custom URL') {
	    $('#custom_redirect').parent().parent().show(300);
	  } else {
	    $('#custom_redirect').parent().parent().hide(300);
	  }
	});
});