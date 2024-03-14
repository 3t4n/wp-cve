jQuery(document).ready(function(){
	jQuery('#wpui-refresh').on('click', function() {
		jQuery.ajax({
			method : 'POST',
			url : wpuiAjaxDashboard.wpui_post_url,
			_ajax_nonce: wpuiAjaxDashboard.wpui_nonce,
			success : function( data ) {   
				window.location.reload(true);
			},
		});
	});
});

jQuery(document).ready(function(){
	jQuery('#wpui-refresh').on('click', function() {
		jQuery(this).attr("disabled", "disabled");
		jQuery( '.spinner' ).css( "visibility", "visible" );
		jQuery( '.spinner' ).css( "float", "left" );
	});
});