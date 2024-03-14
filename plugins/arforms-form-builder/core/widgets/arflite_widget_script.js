jQuery(document).on('click', '#arf_enable_multicolumn_checkbox', function(){
	if(jQuery(this).is(":checked")) {
		jQuery(".arf_enable_multicolumn_hidden").val("1");
	} else {
		jQuery(".arf_enable_multicolumn_hidden").val("0");
	}
});