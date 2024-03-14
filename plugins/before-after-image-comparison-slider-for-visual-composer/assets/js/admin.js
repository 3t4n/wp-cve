jQuery(document).ready(function(){
	jQuery('.wb-vc-baic-already-reviewed, .wb-vcbaics-review-notice .notice-dismiss').on('click', function(e){
		e.preventDefault();
		var _this = this;
		jQuery.ajax({
			type: 'post',
			url: wb_vc_baic_ajax_object.ajax_url,
			data: {
				action: 'wb_vc_baic_review_transient',
			},
			success: function( result ){
				jQuery(_this).parents('.notice').slideUp();
				console.log(result);
			}
		});
	});
	jQuery('.wpvcbaic-up-pro-link').parent().attr('target','_blank');
});