function es_af_submit() {
	if(document.es_af_form.es_af_title.value == "") {
		alert(esaf_settings_notices.esaf_form_title);
		document.es_af_form.es_af_title.focus();
		return false;
	}
}

function es_af_delete(id) {
	if(confirm(esaf_settings_notices.esaf_settings_delete_record)) {
		document.frm_es_af_display.action="admin.php?page=es-af-advancedform&ac=del&did="+id;
		document.frm_es_af_display.submit();
	}
}