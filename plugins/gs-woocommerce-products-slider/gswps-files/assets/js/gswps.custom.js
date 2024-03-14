
  
jQuery(document).ready(function($) {
  
	// custom js for product column
	
	if ($('.owl-item').width() < 300) {
		$('.gs_wps_area').addClass('mid_prod_w');
	}
	if ($('.owl-item').width() < 250) {
		$('.gs_wps_area').addClass('sml_prod_w').removeClass('mid_prod_w');
	}
	
	$(".gs_wps_price a.button").unwrap();
});



