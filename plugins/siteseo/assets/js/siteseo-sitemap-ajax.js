jQuery(document).ready(function($) {
	$('#siteseo-flush-permalinks,#siteseo-flush-permalinks2').on('click', function() {
		$.ajax({
			method : 'GET',
			url : siteseoAjaxResetPermalinks.siteseo_ajax_permalinks,
			data: {
				action: 'siteseo_flush_permalinks',
				_ajax_nonce: siteseoAjaxResetPermalinks.siteseo_nonce,
			},
			success : function( data ) {
				window.location.reload(true);
			},
		});
	});
	$('#siteseo-flush-permalinks,#siteseo-flush-permalinks2').on('click', function() {
		$(this).attr("disabled", "disabled");
		$( '.spinner' ).css( "visibility", "visible" );
		$( '.spinner' ).css( "float", "none" );
	});
});