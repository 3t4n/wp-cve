//hide and show product field
jQuery(document).on('click', ".wcatcbll_wrap", function (e) {
	e.preventDefault();
	var rm_fld = jQuery(this).attr('id');
	var adtm = rm_fld.split("_");
	var stmid = adtm[2];
	//console.log(stmid);
	jQuery('#' + rm_fld).find('.tgl-indctr').toggleClass('rotate180');
	jQuery("#wcatcbll_fld_" + stmid).slideToggle();

});
//Add more custom product button
jQuery("#catcbll_add_btn").click(function (e) {
	e.preventDefault();
	var counter = jQuery("#catcbll_hide_value").val();
	var counter = parseInt(counter) + 1;
	var chkbx_cnt = counter - 1;
	jQuery("#wcatcbll_repeat").append('<div id="main_fld_' + counter + '" class="main_prd_fld"><div id="wcatcbll_wrap_' + counter + '" class="wcatcbll_wrap"><div class="wcatcbll" id="wcatcbll_prdt_' + counter + '"><div class="wcatcbll_mr_100"><span class="tgl-indctr" aria-hidden="true"></span><button id="btn_remove_' + counter + '" class="btn_remove top_prd_btn" data-field="' + counter + '">Remove</button></div></div></div><div class="wcatcbll_content" id="wcatcbll_fld_' + counter + '"><div class="wcatcbll_p-20"><label for="wcatcbll_atc_text" style="width:150px; display:inline-block;">' + wcatcbll_vars.product_btn_labal + '</label><input type="text" name="wcatcbll_wcatc_atc_text[]" id="title_field_' + counter + '" class="title_field" value="" style="width:300px;" placeholder="' + wcatcbll_vars.product_btn_lbl_plchldr + '"/> <div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i><span class="wcatcblltooltiptext">' + wcatcbll_vars.product_btn_lbl_desc + '</span></div><br><br><label for="title_field" style="width:150px; display:inline-block;">' + wcatcbll_vars.product_btn_url + '</label><input type="url" name="wcatcbll_wcatc_atc_action[]" id="title_field_' + counter + '" class="title_field" value="" style="width:300px;" placeholder="https://hirewebxperts.com"/> <div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i><span class="wcatcblltooltiptext">' + wcatcbll_vars.product_btn_url_desc + '</span></div></div></div>');
	jQuery("#catcbll_hide_value").val(counter);
	var length = jQuery("#wcatc_meta_box #catcbll_hide_value").val();
	if (length >= 1) {
		jQuery("#wcatc_meta_box .wcatcbll").css('display', 'block');
		jQuery("#wcatc_meta_box .catcbll_clone").css('background', '#f6f4f4');
	}

});

//remove field
jQuery(document).on('click', ".btn_remove", function (e) {
	e.preventDefault();
	jQuery(this).parent().parent().parent().parent().remove();
	var length = jQuery("#wcatc_meta_box .main_prd_fld").length;
	if (length < 2) {
		jQuery("#wcatc_meta_box .wcatcbll").css('display', 'none');
		jQuery("#wcatc_meta_box .catcbll_clone").css('background', '#fff');
	}
});

// document ready check field value
jQuery(document).ready(function () {
	var length = jQuery("#wcatc_meta_box .main_prd_fld").length;
	if (length < 2) {
		jQuery("#wcatc_meta_box .wcatcbll").css('display', 'none');
		jQuery("#wcatc_meta_box .catcbll_clone").css('background', '#fff');
	}
});