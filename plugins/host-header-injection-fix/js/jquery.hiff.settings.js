jQuery(document).ready(function(){
	
	var hhif_el = 'table.form-table input[name="hhif_options[fix_type]"]';
	var hhif_go = jQuery(hhif_el +':checked').val();
	
	var hhif_c1 = jQuery('table.form-table tr').eq(-2);
	var hhif_c2 = jQuery('table.form-table tr').eq(-3);
	
	if (hhif_go !== 'custom') {
		hhif_c1.hide();
		hhif_c2.hide();
	}
	
	jQuery(hhif_el).bind('change',function(){
		
		if (jQuery(this).val() === 'custom') {
			hhif_c1.toggle(0);
			hhif_c2.toggle(0);
		} else {
			hhif_c1.hide();
			hhif_c2.hide();
		}
		
	});
});