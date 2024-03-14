function _owlc_delete(guid) {
	if(confirm(owlc_images.owlc_images_delete)) {
		document.frm_owlc_display.action="admin.php?page=owlc-images&ac=del&guid="+guid;
		document.frm_owlc_display.submit();
	}
}

function _owlc_view(guid) {
	if(guid != "") {
		document.frm_owlc_display.action="admin.php?page=owlc-images&ac=view&guid="+guid;
		document.frm_owlc_display.submit();
	} else {
		document.frm_owlc_display.action="admin.php?page=owlc-images";
		document.frm_owlc_display.submit();
	}
}

function _owlc_insert() {
	if(document.owlc_form.owl_galleryguid.value=="") {
		alert(owlc_images.owlc_images_add_gallery);
		document.owlc_form.owl_galleryguid.focus();
		return false;
	} else if( document.owlc_form.owl_image.value=="" ) {
		alert(owlc_images.owlc_images_add_image);
		document.owlc_form.owl_image.focus();
		return false;
	} else if( document.owlc_form.owl_order.value=="" || isNaN(document.owlc_form.owl_order.value) ) {
		alert(owlc_images.owlc_images_add_order);
		document.owlc_form.owl_order.focus();
		return false;
	}
}

function _owlc_redirect() {
		window.location = "admin.php?page=owlc-images";
}

function _owlc_help() {
		window.open("http://www.gopiplus.com/work/2017/11/18/owl-carousel-responsive-wordpress-plugin/");
}