jQuery(document).ready(function($) {
	var type = $("select[name='wacf_type']").val();
	
	wcaf_condition_text( type );

	$("select[name='wacf_type']").on('change', function () {
		wcaf_condition_text( $(this).val() );
	})
});

function wcaf_condition_text( value ) {
	if( value == 'percentage' ) {
		jQuery("label[for='wacf_fee_charges']").text("Custom Fee percentage");
	} else {
		jQuery("label[for='wacf_fee_charges']").text("Custom Fee charges");
	}
}
