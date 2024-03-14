jQuery(document).ready(function(){
	jQuery('#wpui-refresh').on('click', function() {
		var wpui_all_cpt = wpuiAjaxMetaboxes.wpui_post_types.length;
		var count_success = 0;
		for(var i = 0; i < wpui_all_cpt; i++) {	
			var wpui_post_types = wpuiAjaxMetaboxes.wpui_post_types[i];
			jQuery.ajax({
				method : 'GET',
				url : wpuiAjaxMetaboxes.wpui_post_url+'?post_type='+wpui_post_types,
				_ajax_nonce: wpuiAjaxMetaboxes.wpui_nonce,
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