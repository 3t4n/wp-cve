jQuery(document).ready(function () {
	var $ = jQuery; // I don't want to waste space.
	
	// Script for bulk actions in listers.
	if (onetrt_postaction.is_lister == "1" && onetrt_postaction.bulk_action == "1") {
		jQuery('<option>').val('onetrt').text(onetrt_postaction.labels.bulk_action).appendTo("select[name='action']");
		jQuery('<option>').val('onetrt').text(onetrt_postaction.labels.bulk_action).appendTo("select[name='action2']");

		$("#doaction,#doaction2").click(function (e) {
			var ruri = "", ids = [], checked = null;
			if ( (this.id == "doaction" && $("select[name='action']").val() == "onetrt") || (this.id == "doaction2" && $("select[name='action2']").val() == "onetrt") ) {
				checked = $("input[name='"+( onetrt_postaction.is_imageatt == "1" ? "media" : "post")+"[]']:checked");
				if (checked.length < 1) {
					alert(onetrt_postaction.labels.bulk_noselected);
				} else if (confirm(onetrt_postaction.labels.bulk_confirm)) {
					ruri = onetrt_postaction.refresh_uri;
					checked.each(function () { ids.push(this.value); });
					window.location.href=ruri + ids.join(";");
				}
				e.preventDefault();
			}

		});
	}
	
});