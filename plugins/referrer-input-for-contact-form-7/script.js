jQuery('.cf7-rfr').each(function(){
	if( '' == jQuery(this).val() ){
		jQuery(this).val(document.referrer);
	}
});