jQuery(document).ready(function(){
	vp_set_preview_font();
	jQuery("#voucherthumbs img").bind("click", vp_set_preview);
	jQuery(".checkbox").bind("click", vp_set_template_deleted);
	jQuery("#font").bind("change", vp_set_preview_font);
	jQuery("#name").bind("keyup", vp_limit_text);
	jQuery("#text").bind("keyup", vp_limit_text);
	jQuery("#terms").bind("keyup", vp_limit_text);
	jQuery("#previewbutton").bind("click", vp_preview_voucher);
	jQuery("#savebutton").bind("click", vp_save_voucher);
	jQuery("a.templatepreview").bind("click", vp_new_window);
	vp_hide_form_options();
	jQuery("#randomcodes").bind("click", vp_check_random);
	jQuery("#sequentialcodes").bind("click", vp_check_sequential);
	jQuery("#customcodes").bind("click", vp_check_custom);
	jQuery("#singlecode").bind("click", vp_check_single);
	jQuery("#showshortcodes").bind("click", vp_toggle_shortcodes);
	jQuery("#delete").bind("change", vp_toggle_deletion);
});
function vp_toggle_deletion(e) {
	if(this.checked) {
		jQuery("#previewbutton").hide();
		jQuery("#savebutton").val("Delete voucher");
	} else {
		jQuery("#previewbutton").show();
		jQuery("#savebutton").val("Save");
	}
}
function vp_toggle_shortcodes(e) {
	jQuery("#shortcodes").toggle();
	e.preventDefault();
	return false;
}
function vp_hide_form_options() {
	jQuery(".hider").hide();
}
function vp_check_random(e) {
	vp_hide_form_options();
	if (this.checked) {
		vp_show_random();
	}
}
function vp_show_random() {
	jQuery("#codelengthline").show();
	jQuery("#codeprefixline").show();
	jQuery("#codesuffixline").show();
}
function vp_check_sequential(e) {
	vp_hide_form_options();
	if (this.checked) {
		vp_show_sequential();
	}
}
function vp_show_sequential() {
	jQuery("#codeprefixline").show();
	jQuery("#codesuffixline").show();
}
function vp_check_custom(e) {
	vp_hide_form_options();
	if (this.checked) {
		vp_show_custom();
	}
}
function vp_show_custom() {
	jQuery("#customcodelistline").show();
	jQuery("#codeprefixline").hide();
	jQuery("#codesuffixline").hide();
}
function vp_check_single(e) {
	vp_hide_form_options();
	if (this.checked) {
		vp_show_single();
	}
}
function vp_show_single() {
	jQuery("#singlecodetextline").show();
	jQuery("#codeprefixline").hide();
	jQuery("#codesuffixline").hide();
}
function vp_new_window(e) {
	jQuery(this).attr("target", "_blank");
}
function vp_preview_voucher(e) {
	var form = jQuery("#voucherform");
	form.attr("action", form.attr("action") + "&preview=voucher");
	form.attr("target", "_blank");
	form.submit();
}
function vp_save_voucher(e) {
	var form = jQuery("#voucherform");
	form.attr("action", form.attr("action").replace("&preview=voucher", ""));
	form.attr("target", "");
	form.submit();
}
function vp_set_preview(e) {
	var id = this.id.replace("template_", "");
	var preview = "url(" + vp_siteurl + "/wp-content/plugins/voucherpress/templates/" + id + "_preview.jpg)";
	jQuery("#voucherpreview").css("background-image", preview);
	jQuery("#template").val(id);
}
function vp_set_template_deleted(e) {
	var td = jQuery(this).parent().get(0);
	var tr = jQuery(td).parent().get(0);
	jQuery(tr).toggleClass("deleted");
}
function vp_set_preview_font(e) {
	var font = jQuery("#font :selected").val();
	jQuery("#voucherpreview h2 textarea").attr("class", font);
	jQuery("#voucherpreview p textarea").attr("class", font);
	jQuery("#voucherpreview p").attr("class", font);
}
function vp_limit_text(e) {
	var limit = 30;
	var el = jQuery(this);
	if (el.attr("id") == "text") limit = 200;
	if (el.attr("id") == "terms") limit = 300;
	var length = el.val().length;
	if (parseFloat(length) >= parseFloat(limit)) {
		// if this is a character key, stop it being entered
		var key = vp_keycode(e) || e.code;
		if (key != 8 && key != 46 && key != 37 && key != 39) {
			el.val(el.val().substr(0, limit));
			e.preventDefault(); e.stopPropagation(); return false;
		}
	}
}
// return the keycode for this event
function vp_keycode(e) {
	if (window.event) {
		return window.event.keyCode;
	} else if (e) {
		return e.which;
	} else {
		return false;
	}
}