function _owlc_delete(guid) {
	if(confirm(owlc_gallery.owlc_gallery_delete_record)) {
		document.frm_owlc_display.action="admin.php?page=owlc-gallery&ac=del&guid="+guid;
		document.frm_owlc_display.submit();
	}
}

function _owlc_insert() {
	if(document.owlc_form.owl_title.value=="") {
		alert(owlc_gallery.owlc_gallery_add_title);
		document.owlc_form.owl_title.focus();
		return false;
	} else if( document.owlc_form.owl_items_1000.value=="" || isNaN(document.owlc_form.owl_items_1000.value) ) {
		alert(owlc_gallery.owlc_gallery_img_count);
		document.owlc_form.owl_items_1000.focus();
		return false;
	} else if( document.owlc_form.owl_items_800.value=="" || isNaN(document.owlc_form.owl_items_800.value) ) {
		alert(owlc_gallery.owlc_gallery_img_count);
		document.owlc_form.owl_items_800.focus();
		return false;
	} else if( document.owlc_form.owl_items_600.value=="" || isNaN(document.owlc_form.owl_items_600.value) ) {
		alert(owlc_gallery.owlc_gallery_img_count);
		document.owlc_form.owl_items_600.focus();
		return false;
	} else if( document.owlc_form.owl_items_0.value=="" || isNaN(document.owlc_form.owl_items_0.value) ) {
		alert(owlc_gallery.owlc_gallery_img_count);
		document.owlc_form.owl_items_0.focus();
		return false;
	} else if( document.owlc_form.owl_autoplaytimeout.value=="" || isNaN(document.owlc_form.owl_autoplaytimeout.value) ) {
		alert(owlc_gallery.owlc_gallery_autoplaytimeout);
		document.owlc_form.owl_autoplaytimeout.focus();
		return false;
	}
}

function _owlc_redirect() {
		window.location = "admin.php?page=owlc-gallery";
}

function _owlc_help() {
		window.open("http://www.gopiplus.com/work/2017/11/18/owl-carousel-responsive-wordpress-plugin/");
}