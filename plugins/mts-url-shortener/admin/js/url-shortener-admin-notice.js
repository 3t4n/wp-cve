jQuery(document).on('click', '.mts-urlshortener-dismiss', function(e){
	e.preventDefault();
	jQuery(this).parent().remove();
	jQuery.ajax({
		type: "POST",
		url: ajaxurl,
		data: {
			action: 'mts_dismiss_urlshortener_notice',
			dismiss: jQuery(this).data('ignore')
		}
	});
	return false;
});