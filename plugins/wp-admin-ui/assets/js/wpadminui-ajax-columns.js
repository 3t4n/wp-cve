jQuery(document).ready(function(){
	jQuery('#wpui-refresh').on('click', function() {
		var wpui_all_cpt = wpuiAjaxColumns.wpui_post_types.length;
		var count_success = 0;
		jQuery.ajax({
			method : 'GET',
			url : wpuiAjaxColumnsMedia.wpui_media_url,
			success : function( data ) {   
				count_success++;
			},
		});
		for(var i = 0; i < wpui_all_cpt; i++) {//Pages and other CPT
			var wpui_post_types = wpuiAjaxColumns.wpui_post_types[i];
			jQuery.ajax({
				method : 'GET',
				url : wpuiAjaxColumns.wpui_post_url+'?post_type='+wpui_post_types,
				_ajax_nonce: wpuiAjaxColumns.wpui_nonce,
				success : function( data ) {   
					count_success++;
					if(count_success == wpui_all_cpt) {
						window.location.reload(true);
					}
				},
			});
		};
	});
});

jQuery(document).ready(function(){
	jQuery('#wpui-refresh').on('click', function() {
		jQuery(this).attr("disabled", "disabled");
		jQuery( '.spinner' ).css( "visibility", "visible" );
		jQuery( '.spinner' ).css( "float", "left" );
	});
});